<?php

namespace App\Repositories;

use App\Models\ExtDomain;
use App\Repositories\Contracts\CrawlContactRepositoryInterface;
use Carbon\Carbon;
use Exception;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Promise\EachPromise;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Str;

class CrawlContactRepository implements CrawlContactRepositoryInterface {

    /*
     * crawl using Goutte library, It's can't request async concurrency
     */
    public function crawl(ExtDomain $extDomain)
    {
        $result = ['success' => false];
        $client = $this->getClient();

        try {
            $crawler = $client->request('GET', $extDomain->domain);
        } catch (ConnectException $ignore) {
            Log::error($extDomain->domain,['error' => $ignore]);
            return $result;
        } catch (Exception $e) {
            Log::error($extDomain->domain,['error' => $e]);
            return $result;
        }

        return $this->handleCrawler($crawler, $result, $extDomain);
    }

    protected function handleCrawler(Crawler $crawler, array $result, ExtDomain $extDomain) {
        $selectorDomList = config('crawler.selectorDomCrawl');
        $emailArray = [];
        $facebookArray = [];

        if ($crawler->count() == 0) {
            $result['ext_domain'] = $extDomain;
            $result['emails'] = $emailArray;
            $result['facebook'] = $facebookArray;
            $result['success'] = false;
            return $result;
        }

        foreach($selectorDomList as $selector) {
            $crawler->filter($selector)->each(function($node) use(&$emailArray, &$facebookArray) {
                $emailCrawled = [];
                $fbCrawled = [];
                preg_match_all(config('crawler.regex_email'), $node->html(), $emailCrawled);
                preg_match_all(config('crawler.regex_fb'), $node->html(), $fbCrawled);

                if (count($emailCrawled) > 0) {
                    $emailArray = array_merge($emailArray, $emailCrawled[0]);
                }

                if (count($fbCrawled) > 0) {
                    $facebookArray = array_merge($facebookArray, $fbCrawled[0]);
                }
            });
        }

        $this->handleFilterFacebookLink($crawler, $facebookArray);
        $this->handleFilterEmailAddress($crawler, $emailArray);

        $result['success'] = true;
        $result['emails'] = $emailArray;
        $result['facebook'] = $facebookArray;
        $result['ext_domain'] = $extDomain;

        Log::info('email found on : '.$extDomain->domain, ['emails' => $emailArray]);
        return $result;
    }

    public function handleFilterFacebookLink(Crawler $crawler, &$facebookArray) {
        $crawler->filter('iframe[src*="facebook.com/"]')->each(function($node) use (&$facebookArray) {
            $link = explode('href=', $node->attr('src'));
            if (count($link) > 0) $link = $link[1];
            else return;
            $link = explode('&', $link)[0];
            $facebookArray[] = urldecode($link);
        });

        if (count($facebookArray) == 0) {
            $crawler->filter('*[href*="facebook.com/"]')->each(function($node) use (&$facebookArray) {
                $facebookArray[] = $node->attr('href');
            });
        }

        // filter page link
        foreach($facebookArray as $key => &$link) {
            $link = strtolower(rtrim($link, '/'));
            $link = explode("/photos", $link)[0];
            $link = explode("/videos", $link)[0];
            if (strpos($link,"/dialog/")) unset($facebookArray[$key]);
            if (strpos($link,"/plugins/")) unset($facebookArray[$key]);
            if (Str::contains($link, 'facebook.com') === false  && Str::contains($link, 'fb.com') === false) unset($facebookArray[$key]);

            if ($this->startsWith($link, 'http://') === false && $this->startsWith($link, 'https://') === false) {
                $link = 'https://'.$link;
            }
        }

        $facebookArray = array_unique($facebookArray);
    }

    // Function to check string starting
    // with given substring
    private function startsWith ($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }

    public function handleFilterEmailAddress(Crawler $crawler, &$emailArray) {
        $emailArray = array_unique($emailArray);
    }

    public function getClient() {
        $client = new Client();
        $guzzleClient = new GuzzleClient(array(
            'timeout' => 60,
        ));

        $client->setClient($guzzleClient);
        return $client;
    }

    /**
     * Crawl customize with promise for request async concurrency
     * @param Collection $extDomains
     * @return array
     */
    public function crawls(Collection $extDomains)
    {
        $before = Carbon::now();
        $successCount = 0;
        $totalCount = 0;
        $guzzleClient = new GuzzleClient();

        $promises = (function () use ($extDomains, $guzzleClient) {
            foreach ($extDomains as $extDomain) {
                  $extDomain->status = config('constant.EXT_STATUS_CRAWL_FAILED');
                  $extDomain->save();

                  yield $guzzleClient->requestAsync('GET', $extDomain->domain, [
                      'headers' => [
                          'decode_content' => false,
                          'accept-encoding' => 'gzip, deflate'
                      ]])
                      ->then(function(ResponseInterface $response) use ($extDomain) {

                          $html = $response->getBody()->getContents();
                          $crawler = new Crawler($html);
                          $crawlerResult = $this->handleCrawler($crawler, ['success' => false], $extDomain);

                          if ($crawler->count() == 0) {
                              $emailArray = [];
                              $facebookArray = [];
                              $this->regexContact($html, $crawler, $emailArray, $facebookArray);
                              $crawlerResult['emails'] = $emailArray;
                              $crawlerResult['facebook'] = $facebookArray;
                          }

                          return $crawlerResult;
                      })->then(function(array $crawlResult) {
                          return $this->saveToDatabaseSingle($crawlResult);
                      });

            }
        })();

        $maxProcess = config('crawler.max_process');
        $eachPromise = new EachPromise($promises, [
            'concurrency' => $maxProcess,
            'fulfilled' => function ($result) use (&$successCount, &$totalCount) {
                //TODO: save $result to database
                if ($result) $successCount++;
                $totalCount++;
            },
            'rejected' => function ($reason) {
                Log::error('reject ', ['error' => $reason]);
            }
        ]);

        $eachPromise->promise()->wait();
        if ($totalCount == 0) $totalCount = 1;
        $dataReturn = ['before' => $before, 'after' => Carbon::now(), 'contact found' => $successCount, 'total' => $totalCount, 'rate' => ($successCount / $totalCount)];
        return $dataReturn;
        //$this->saveToDatabase($dataResult);
    }

    private function regexContact($html, $crawler, &$emailArray, &$facebookArray) {
        $emailCrawled = [];
        $fbCrawled = [];
        preg_match_all(config('crawler.regex_email'), $html, $emailCrawled);
        preg_match_all(config('crawler.regex_fb'), $html, $fbCrawled);

        if (count($emailCrawled) > 0) {
            $emailArray = array_merge($emailArray, $emailCrawled[0]);
        }

        if (count($fbCrawled) > 0) {
            $facebookArray = array_merge($facebookArray, $fbCrawled[0]);
        }

        $this->handleFilterFacebookLink($crawler, $facebookArray);
        $this->handleFilterEmailAddress($crawler, $emailArray);
    }

    /**
     * saveToDatabase
     * @param array $data: array result of crawl format ['ext_domain' => ExtDomain, 'emails' => email list, 'facebook' => facebook list]
     * @return array
     */

    protected function saveToDatabase($data) {
        $success = 0;
        $total = 0;

        foreach ($data as $item) {
            $total++;
            if ($this->saveToDatabaseSingle($item)) $success++;
        }

        $dataReturn = ['contact found' => $success, 'total' => $total, 'rate' => ($success / $total)];
        Log::info('CrawlContactRepository done', $dataReturn);
        return $dataReturn;
    }

    /**
     * @param $item: result of crawl format ['ext_domain' => ExtDomain, 'emails' => email list, 'facebook' => facebook list]
     * @return true if item has contact
     */
    protected function saveToDatabaseSingle($item) {
        $hasContact = false;
        $extDomain = $item['ext_domain'];
        if (count($item['facebook']) + count($item['emails']) > 0) {
            $hasContact = true;
            $extDomain->email = join("|", $item['emails']);
            $extDomain->facebook = join("|", $item['facebook']);
            $extDomain->status = (count($item['emails']) > 0 && !empty($item['emails']))
                ? config('constant.EXT_STATUS_GOT_EMAIL')
                : config('constant.EXT_STATUS_GOT_CONTACTS');
        } else {
            $extDomain->status = config('constant.EXT_STATUS_CONTACTS_NULL');
        }

        $extDomain->save();
        return $hasContact;
    }

}
