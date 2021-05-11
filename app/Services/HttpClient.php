<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;

class HttpClient
{
    protected $guzzle;

    public function __construct()
    {
        $this->guzzle = new Client();
    }

    public function getProtocol($uri)
    {
        $url = '';

        try {
            $this->guzzle->request('GET', trim_special_characters($uri), [
                'on_stats' => function (TransferStats $stats) use (&$url) {
                    $url = $stats->getHandlerStats()['url'];
                }
            ]);

            return explode(':', $url)[0];
        } catch (\Exception $exception) {
            return null;
        }
    }
}
