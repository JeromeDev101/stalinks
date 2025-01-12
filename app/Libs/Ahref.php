<?php
namespace App\Libs;

use App\Models\ExtDomain;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Promise\EachPromise;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

class Ahref {
    private $token;
    private $outputFormat = 'json';
    private $limit = 1;
    private $baseUrl;

    public function __construct($config) {
        $this->token = $config['token'];
        $this->baseUrl = $config['base_url'];
    }

    public function requestApi($domain) {
        $client = $this->getClient();
        $response = $client->request('GET', $this->getApiUrl($domain));

        return json_decode($response, true);
    }

    public function getApiUrl($domain, $from) {
        return $this->baseUrl.$domain."&output=".$this->outputFormat."&limit=".$this->limit."&token=".$this->token."&from=".$from;
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
     * Get ahrefs with promise for request async concurrency
     * @param Collection $extDomains
     * @return array
     */
    public function getAhrefsAsync(Collection $extDomains) {

        $getFrom = [
            'ahrefs_rank',
            'domain_rating',
            'metrics',
            'positions_metrics',
            'refdomains'
        ];

        $guzzleClient = new GuzzleClient();
        $promises = (function () use ($extDomains, $guzzleClient, $getFrom) {
            foreach ($extDomains as $extDomain) {
                foreach($getFrom as $from) {
                    yield $guzzleClient->requestAsync('GET', $this->getApiUrl($extDomain->domain, $from))
                        ->then(function(ResponseInterface $response) use ($extDomain) {
                            $result = json_decode($response->getBody()->getContents(), true);

                            if (isset($result['metrics']) && isset($result['metrics']['backlinks'])) {
                                $extDomain->no_backlinks = $result['metrics']['backlinks'];
                            }

                            if(isset($result['metrics']) && isset($result['metrics']['positions']) ){
                                $extDomain->organic_keywords = $result['metrics']['positions'];
                                $extDomain->organic_traffic = $result['metrics']['traffic'];
                            }

                            if(isset($result['domain'])){
                                $extDomain->ahrefs_rank = $result['domain']['ahrefs_top'];
                                $extDomain->domain_rating = $result['domain']['domain_rating'];
                            }

                            if(isset($result['pages'])){
                                $extDomain->url_rating = $result['pages'][0]['ahrefs_rank'];
                            }

                            if(isset($result['stats'])){
                                $extDomain->ref_domains = $result['stats']['refdomains'];
                            }

                            $extDomain->save();

                            return $extDomain;
                        });
                    }
                }
        })();

        $dataExt = [];
        $maxProcess = config('crawler.max_process_ahrefs');
        $totalCount = 0;
        $eachPromise = new EachPromise($promises, [
            'concurrency' => $maxProcess,
            'fulfilled' => function ($extDomain) use(&$dataExt, &$totalCount) {
                $dataExt[] = $extDomain;
                $totalCount++;
            },
            'rejected' => function ($reason) {
                Log::error('reject ', ['error' => $reason]);
            }
        ]);

        $eachPromise->promise()->wait();
        $dataReturn = [];

        foreach($dataExt as $item) {
            $dataReturn[$item->id] = $item;
        }

        return $dataReturn;
    }

    /**
     * Get ahrefs with promise for request async concurrency
     * @param Collection $publisher
     * @return array
     */
    public function getAhrefsPublisherAsync(Collection $publishers) {

        $getFrom = [
            'ahrefs_rank',
            'domain_rating',
            'metrics',
            'positions_metrics',
            'refdomains'
        ];

        $guzzleClient = new GuzzleClient();
        $promises = (function () use ($publishers, $guzzleClient, $getFrom) {
            foreach ($publishers as $publisher) {
                foreach($getFrom as $from) {
                    $url = remove_http($publisher->url);
                    $url = trim_special_characters($url);

                    yield $guzzleClient->requestAsync('GET', $this->getApiUrl($url, $from))
                        ->then(function(ResponseInterface $response) use ($publisher) {
                            $result = json_decode($response->getBody()->getContents(), true);

                            if (isset($result['metrics']) && isset($result['metrics']['backlinks'])) {
                                $publisher->backlinks = $result['metrics']['backlinks'];
                            }

                            if(isset($result['metrics']) && isset($result['metrics']['positions']) ){
                                $publisher->org_keywords = $result['metrics']['positions'];
                                $publisher->org_traffic = $result['metrics']['traffic'];
                            }

                            if(isset($result['domain'])){
                                $publisher->dr = $result['domain']['domain_rating'];
                            }

                            if(isset($result['pages'])){
                                $publisher->ur = $result['pages'][0]['ahrefs_rank'];
                            }

                            if(isset($result['stats'])){
                                $publisher->ref_domain = $result['stats']['refdomains'];
                            }

                            $publisher->save();

                            return $publisher;
                        });
                    }
                }
        })();

        $dataExt = [];
        $maxProcess = config('crawler.max_process_ahrefs');
        $totalCount = 0;
        $eachPromise = new EachPromise($promises, [
            'concurrency' => $maxProcess,
            'fulfilled' => function ($publisher) use(&$dataExt, &$totalCount) {
                $dataExt[] = $publisher;
                $totalCount++;
            },
            'rejected' => function ($reason) {
                Log::error('reject ', ['error' => $reason]);
            }
        ]);

        $eachPromise->promise()->wait();
        $dataReturn = [];

        foreach($dataExt as $item) {
            $dataReturn[$item->id] = $item;
        }

        return $dataReturn;
    }


    public function getAhrefsGenerateListAsync(Collection $generateList) {

        $getFrom = [
            'ahrefs_rank',
            'domain_rating',
            'metrics',
            'positions_metrics',
            'refdomains'
        ];

        $guzzleClient = new GuzzleClient();
        $promises = (function () use ($generateList, $guzzleClient, $getFrom) {
            foreach ($generateList as $list) {
                foreach($getFrom as $from) {
                    $url = remove_http($list->url);
                    $url = trim_special_characters($url);

                    yield $guzzleClient->requestAsync('GET', $this->getApiUrl($url, $from))
                        ->then(function(ResponseInterface $response) use ($list) {
                            $result = json_decode($response->getBody()->getContents(), true);

                            if (isset($result['metrics']) && isset($result['metrics']['backlinks'])) {
                                $list->backlinks = $result['metrics']['backlinks'];
                            }

                            if(isset($result['metrics']) && isset($result['metrics']['positions']) ){
                                $list->org_kw = $result['metrics']['positions'];
                                $list->org_traffic = $result['metrics']['traffic'];
                            }

                            if(isset($result['domain'])){
                                $list->dr = $result['domain']['domain_rating'];
                            }

                            if(isset($result['pages'])){
                                $list->ur = $result['pages'][0]['ahrefs_rank'];
                            }

                            if(isset($result['stats'])){
                                $list->ref_domain = $result['stats']['refdomains'];
                            }

                            $list->save();

                            return $list;
                        });
                    }
                }
        })();

        $dataExt = [];
        $maxProcess = config('crawler.max_process_ahrefs');
        $totalCount = 0;
        $eachPromise = new EachPromise($promises, [
            'concurrency' => $maxProcess,
            'fulfilled' => function ($list) use(&$dataExt, &$totalCount) {
                $dataExt[] = $list;
                $totalCount++;
            },
            'rejected' => function ($reason) {
                Log::error('reject ', ['error' => $reason]);
            }
        ]);

        $eachPromise->promise()->wait();
        $dataReturn = [];

        foreach($dataExt as $item) {
            $dataReturn[$item->id] = $item;
        }

        return $dataReturn;
    }


    public function getAhrefsBpAsync(Collection $BpList) {

        $getFrom = [
            'ahrefs_rank',
            'domain_rating',
            'metrics',
            'positions_metrics',
            'refdomains'
        ];

        $guzzleClient = new GuzzleClient();
        $promises = (function () use ($BpList, $guzzleClient, $getFrom) {
            foreach ($BpList as $list) {
                foreach($getFrom as $from) {
                    $url = remove_http($list->referring_domain);
                    $url = trim_special_characters($url);

                    yield $guzzleClient->requestAsync('GET', $this->getApiUrl($url, $from))
                        ->then(function(ResponseInterface $response) use ($list) {
                            $result = json_decode($response->getBody()->getContents(), true);

                            if (isset($result['metrics']) && isset($result['metrics']['backlinks'])) {
                                $list->backlinks = $result['metrics']['backlinks'];
                            }

                            if(isset($result['metrics']) && isset($result['metrics']['positions']) ){
                                $list->org_kw = $result['metrics']['positions'];
                                $list->org_traffic = number_format((float)$result['metrics']['traffic'], 2, '.', '');
                            }

                            if(isset($result['domain'])){
                                $list->dr = $result['domain']['domain_rating'];
                            }

                            if(isset($result['pages'])){
                                $list->ur = $result['pages'][0]['ahrefs_rank'];
                            }

                            if(isset($result['stats'])){
                                $list->ref_domain_ahref = $result['stats']['refdomains'];
                            }

                            $list->save();

                            return $list;
                        });
                    }
                }
        })();

        $dataExt = [];
        $maxProcess = config('crawler.max_process_ahrefs');
        $totalCount = 0;
        $eachPromise = new EachPromise($promises, [
            'concurrency' => $maxProcess,
            'fulfilled' => function ($list) use(&$dataExt, &$totalCount) {
                $dataExt[] = $list;
                $totalCount++;
            },
            'rejected' => function ($reason) {
                Log::error('reject ', ['error' => $reason]);
            }
        ]);

        $eachPromise->promise()->wait();
        $dataReturn = [];

        foreach($dataExt as $item) {
            $dataReturn[$item->id] = $item;
        }

        return $dataReturn;
    }
}
