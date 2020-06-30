<?php
/**
 * Makes a request to ATS for the top 10 sites in a country
 */
namespace App\Libs;
use App\Repositories\Contracts\ExtDomainRepositoryInterface;
use App\Repositories\ExtDomainRepository;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Aws\CognitoIdentity\CognitoIdentityClient;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Promise\EachPromise;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

class Alexa {
    protected static $ActionName            = 'Topsites';
    protected static $ResponseGroupName     = 'Country';
    protected static $ServiceHost           = 'ats.api.alexa.com';
    protected static $ServiceEndpoint       = 'ats.api.alexa.com';
    protected static $NumReturn             = 10;
    protected static $StartNum              = 2000;
    protected static $Region                = 'us-east-1';
    protected static $HashAlgorithm         = 'HmacSHA256';
    protected static $ServiceURI            = "/api";
    protected static $ServiceRegion         = "us-east-1";
    protected static $ServiceName           = "execute-api";
    protected static $CognitoUserPoolId     = "us-east-1_n8TiZp7tu";
    protected static $CognitoClientId       = "6clvd0v40jggbaa5qid2h6hkqf";
    protected static $CognitoIdentityPoolId = "us-east-1:bff024bb-06d0-4b04-9e5d-eb34ed07f884";
    protected static $CachedCredentials     = "./.alexa_credentials";
    protected $idProviderClient;

    private $start = 0;
    private $count = 10;
    private $countryCode = "VN";
    private $amzDate;
    private $dateStamp;
    private $apiKey;
    private $apiUser;
    private $apiPassword;

    public function __construct($countryCode, $start, $count) {
        $now = time();
        $this->amzDate = gmdate("Ymd\THis\Z", $now);
        $this->dateStamp = gmdate("Ymd", $now);
        $this->countryCode = $countryCode;
//        $this->apiKey = config('alexa.api_key');
//        $this->apiUser = config('alexa.api_user');
//        $this->apiPassword = config('alexa.api_password');
        $this->start = $start;
        $this->count = $count;
    }

    public function setConfigs($configs) {
        $this->apiKey = $configs['api_key'];
        $this->apiUser = $configs['api_user'];
        $this->apiPassword = $configs['api_password'];
    }

    /**
    Save credentials in cache
     */

    protected function SaveCredentialsToCache($awsCredentials) {
        $fp = fopen(self::$CachedCredentials, "w");
        fwrite($fp, serialize($awsCredentials));
        fclose($fp);
    }

    protected function GetCredentials() {
        $awsCredentials = null;
        if (file_exists(self::$CachedCredentials)){
            $objData = file_get_contents(self::$CachedCredentials);
            $awsCredentials = unserialize($objData);
            if (!empty($awsCredentials)) {
                $expiresOn = strtotime ($awsCredentials["Expiration"]);
                $nowGMT = time();
                if ($nowGMT > $expiresOn) {
                    $awsCredentials = null;
                }
            }
        }

        if ($awsCredentials == null) {
            $awsCredentials = self::GetCognitoCredentials();
            self::SaveCredentialsToCache($awsCredentials);
        }

        self::SetCredentials($awsCredentials);
    }

    /**
     * Set AWS credentials
     */
    protected function SetCredentials($awsCredentials) {
        $this->accessKeyId = $awsCredentials["AccessKeyId"];
        $this->secretAccessKey = $awsCredentials["SecretKey"];
        $this->sessionToken = $awsCredentials["SessionToken"];
        $this->expiration = $awsCredentials["Expiration"];
    }

    protected function hide_term() {
        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')
            system('stty -echo');
    }

    protected function restore_term() {
        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')
            system('stty echo');
    }

    /**
     * Get temporary $credentials
     */
    protected function GetCognitoCredentials() {

        $this->idProviderClient = new CognitoIdentityProviderClient([
            'version' => '2016-04-18',
            'region' => self::$Region,
            'credentials' => false,
        ]);

        try {
            $result = $this->idProviderClient->initiateAuth([
                'AuthFlow' => 'USER_PASSWORD_AUTH',
                'ClientId' => self::$CognitoClientId,
                'UserPoolId' => self::$CognitoUserPoolId,
                'AuthParameters' => [
                    'USERNAME' => $this->apiUser,
                    'PASSWORD' => $this->apiPassword,
                ],
            ]);
            $accessToken = $result->get('AuthenticationResult')['AccessToken'];
            $idToken = $result->get('AuthenticationResult')['IdToken'];
            //echo "Access Token: $accessToken\n";
            //echo "ID Token: $idToken\n";

            $idClient = new CognitoIdentityClient([
                'version' => '2014-06-30',
                'region' => self::$Region,
                'credentials' => false,
            ]);

            $provider = 'cognito-idp.us-east-1.amazonaws.com/'.self::$CognitoUserPoolId;

            $clientIDResponse = $idClient->getId([
                'IdentityPoolId' => self::$CognitoIdentityPoolId,
                'Logins' => [ $provider  => $idToken ]
            ]);

            $clientId = $clientIDResponse->get('IdentityId');

            $result = $idClient->getCredentialsForIdentity([
                'IdentityId' => $clientId,
                'Logins' => [ $provider  => $idToken ]
            ]);

            $awsCredentials = $result->get('Credentials');

            return $awsCredentials;

        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            echo "Error: $errorMessage";
            return $e->getMessage();
        }
    }

    /**
     * Get site info from ATS.
     * @param ExtDomainRepositoryInterface $extDomainRepository
     */
    public function getTopSites($extDomainRepository) {
        self::GetCredentials();

        $guzzleClient = new GuzzleClient();
        $count = $this->count;
        $start = $this->start;

        $startArr = [];

        $dataTotalTopSites = 0;

        $promises = (function () use ($count, $start, $guzzleClient, $extDomainRepository, &$startArr) {
            $countTemp = ($count <= 100) ? $count : 100;

            while ($count > 0) {
                $count -= $countTemp;
                $startArr[] = $start.'_'.$countTemp;
                $canonicalQuery = $this->buildQueryParamsDynamic($start, $countTemp);
                $canonicalHeaders =  $this->buildHeaders(true);
                $signedHeaders = $this->buildHeaders(false);
                $payloadHash = hash('sha256', "");
                $canonicalRequest = "GET" . "\n" . self::$ServiceURI . "\n" . $canonicalQuery . "\n" . $canonicalHeaders . "\n" . $signedHeaders . "\n" . $payloadHash;
                $algorithm = "AWS4-HMAC-SHA256";
                $credentialScope = $this->dateStamp . "/" . self::$ServiceRegion . "/" . self::$ServiceName . "/" . "aws4_request";
                $stringToSign = $algorithm . "\n" .  $this->amzDate . "\n" .  $credentialScope . "\n" .  hash('sha256', $canonicalRequest);
                $signingKey = $this->getSignatureKey();
                $signature = hash_hmac('sha256', $stringToSign, $signingKey);
                $authorizationHeader = $algorithm . ' ' . 'Credential=' . $this->accessKeyId . '/' . $credentialScope . ', ' .  'SignedHeaders=' . $signedHeaders . ', ' . 'Signature=' . $signature;
                $url = 'https://' . self::$ServiceHost . self::$ServiceURI . '?' . $canonicalQuery;

                $oldStart = $start;
                $oldCount = $countTemp;
                $start += $countTemp + 1;
                $countTemp = ($count <= 100) ? $count : 100;

                $headers = array(
                    'Accept: application/xml',
                    'Content-Type: application/xml',
                    'X-Amz-Date: ' . $this->amzDate,
                    'Authorization: ' . $authorizationHeader,
                    'x-api-key: ' . $this->apiKey,
                    'x-amz-security-token: ' . $this->sessionToken
                );

                $ret = self::makeRequest($url, $authorizationHeader);
                $topsitesArray = json_decode($ret, true);
                if (isset($topsitesArray)) {
                    if (isset($topsitesArray["Ats"]) && $topsitesArray["Ats"]["Results"]["ResponseStatus"]["StatusCode"] == 200) {
                        $dataExtDomain = $topsitesArray["Ats"]["Results"]["Result"]["Alexa"]["TopSites"]["Country"]["Sites"]["Site"];
                        $dataTotalTopSites = $topsitesArray["Ats"]["Results"]["Result"]["Alexa"]["TopSites"]["Country"]["TotalSites"];
                        $newData = $extDomainRepository->importAlexaSites($dataExtDomain, $dataTotalTopSites, $this->countryCode, $oldStart, $oldCount);
                        yield $newData;
                    }
                } else yield [
                    'extDomains' => [],
                    'total' => 0,
                    'new' => 0,
                    'existed' => 0,
                ];

                /*$guzzleClient->requestAsync('GET', $url, ['headers' => $headers])
                    ->then(function(ResponseInterface $response) use ($extDomainRepository, $oldStart, $oldCount) {
                        $topsites = json_decode($response->getBody()->getContents(), true);
                        dd($topsites);
                        if (isset($topsites)) {
                            $topsitesArray = json_decode($topsites, true);
                            if (isset($topsitesArray["Ats"]) && $topsitesArray["Ats"]["Results"]["ResponseStatus"]["StatusCode"] == 200) {
                                $dataExtDomain = $topsitesArray["Ats"]["Results"]["Result"]["Alexa"]["TopSites"]["Country"]["Sites"]["Site"];
                                $newData = $extDomainRepository->importAlexaSites($dataExtDomain, $this->countryCode, $oldStart, $oldCount);
                                return $newData;
                            }
                        }
                    });*/
            }
        })();

        $dataReturn = [
            'extDomains' => [],
            'new' => 0,
            'existed' => 0,
            'total' => 0,
        ];

        $maxProcess = config('crawler.max_process_ahrefs');
        $totalCount = 0;
        $eachPromise = new EachPromise($promises, [
            'concurrency' => $maxProcess,
            'fulfilled' => function ($newData) use(&$dataReturn, &$totalCount) {
                $dataReturn['extDomains'] = array_merge($dataReturn['extDomains'], $newData['extDomains']);
                $dataReturn['new'] += $newData['new'];
                $dataReturn['existed'] += $newData['existed'];
                $dataReturn['total'] = $newData['total'];
                $totalCount++;
            },
            'rejected' => function ($reason) {
                Log::error('reject ', ['error' => $reason]);
            }
        ]);

        $eachPromise->promise()->wait();
        return $dataReturn;
    }

    protected function sign($key, $msg) {
        return hash_hmac('sha256', $msg, $key, true);
    }

    protected function getSignatureKey() {
        $kSecret = 'AWS4' . $this->secretAccessKey;
        $kDate = $this->sign($kSecret, $this->dateStamp);
        $kRegion = $this->sign($kDate, self::$ServiceRegion);
        $kService = $this->sign($kRegion, self::$ServiceName);
        $kSigning = $this->sign($kService, 'aws4_request');
        return $kSigning;
    }

    /**
     * Builds headers for the request to AWIS.
     * @return String headers for the request
     */
    protected function buildHeaders($list) {
        $params = array(
            'host'            => self::$ServiceEndpoint,
            'x-amz-date'      => $this->amzDate
        );
        ksort($params);
        $keyvalue = array();
        foreach($params as $k => $v) {
            if ($list)
                $keyvalue[] = $k . ':' . $v;
            else {
                $keyvalue[] = $k;
            }
        }
        return ($list) ? implode("\n",$keyvalue) . "\n" : implode(';',$keyvalue) ;
    }

    /**
     * Builds query parameters for the request to AWIS.
     * Parameter names will be in alphabetical order and
     * parameter values will be urlencoded per RFC 3986.
     * @return String query parameters for the request
     */
    protected function buildQueryParams() {
        $params = array(
            'Action'            => self::$ActionName,
            'ResponseGroup'     => self::$ResponseGroupName,
            'CountryCode'       => $this->countryCode,
            'Count'             => $this->count,
            'Start'             => $this->start,
            'Output'            => config('alexa.output')
        );
        ksort($params);
        $keyvalue = array();
        foreach($params as $k => $v) {
            $keyvalue[] = $k . '=' . rawurlencode($v);
        }
        return implode('&',$keyvalue);
    }

    protected function buildQueryParamsDynamic($start, $count) {
        $params = array(
            'Action'            => self::$ActionName,
            'ResponseGroup'     => self::$ResponseGroupName,
            'CountryCode'       => $this->countryCode,
            'Count'             => $count,
            'Start'             => $start,
            'Output'            => config('alexa.output')
        );
        ksort($params);
        $keyvalue = array();
        foreach($params as $k => $v) {
            $keyvalue[] = $k . '=' . rawurlencode($v);
        }
        return implode('&',$keyvalue);
    }

    /**
     * Makes request to TopSites
     * @param String $url   URL to make request to
     * @param String authorizationHeader  Authorization string
     * @return String       Result of request
     */
    protected function makeRequest($url, $authorizationHeader) {
        //echo "\nMaking request to:\n$url\n";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 4);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/xml',
            'Content-Type: application/xml',
            'X-Amz-Date: ' . $this->amzDate,
            'Authorization: ' . $authorizationHeader,
            'x-api-key: ' . $this->apiKey,
            'x-amz-security-token: ' . $this->sessionToken
        ));

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
