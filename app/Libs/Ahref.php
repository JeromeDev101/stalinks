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
    private $token;// = "929b05bb1165cddd3fe562f240cedc83";
    private $outputFormat = 'json';
    private $baseUrl;// = "http://apiv2.dexuat.com/?mode=ahrefs.com&target=";

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
        return $this->baseUrl.$domain."&output=".$this->outputFormat."&token=".$this->token."&from=".$from;
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
}
