<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\ConfigRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client as GuzzleClient;
use App\Models\Formula;
use App\Models\Language;
use App\Models\Publisher;
use App\Models\Country;

class ConfigController extends Controller
{
    /**
     * @var ConfigRepositoryInterface
     */
    private $configRepository;

    /**
     * ConfigController constructor.
     *
     * @param ConfigRepositoryInterface $configRepository
     */
    public function __construct(ConfigRepositoryInterface $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    public function getList() {
        $data = $this->configRepository->findAll();

        $dataReturn = [];

        foreach ($data as $item) {
            if (!isset($dataReturn[$item->type])) {
                $dataReturn[$item->type] = [];
            }

            $dataReturn[$item->type][] = $item;
        }

        return response()->json([
            'data' => $dataReturn,
            'total' => count($data)
        ]);
    }

    public function edit(Request $request) {

        $response = ['success' => false];
        $input = $request->only(['id', 'value']);

        Validator::make($input, [
            'id' => 'required',
            'value' => 'required',
        ])->validate();

        $config = $this->configRepository->findById($input['id']);
        if (!$config) {
            return response()->json($response);
        }

        $this->configRepository->update($config, $input);
        $response['success'] = true;
        return response()->json($response);
    }

    public function getSubscriptionInfo() {
        $client = new GuzzleClient();
        $subscription_url = 'https://apiv2.ahrefs.com/?token=1221d525cb70af4ee61e2561ced8985935920451&limit=1000&output=json&from=subscription_info';
        $response = $client->get($subscription_url);
        $result = json_decode($response->getBody()->getContents(), true);

        return $result;
    }

    public function getFormula() {
        $formula = Formula::all();
        return response()->json(['data' => $formula],200);
    }

    public function updateFormula(Request $request) {
        $request->validate([
            'additional' => 'required',
            'percentage' => 'required',
        ]);

        $input = $request->except('id');
        $formula = Formula::findOrFail($request->id);
        $formula->update($input);
        return response()->json(['success' => true],200);
    }

    public function storeLanguage(Request $request) {
        $input = $request->all();
        $request->validate([
            'name' => 'required',
            'code' => 'required',
        ]);

        $language = Language::create($input);
        return response()->json(['success' => true],200);
    }

    public function getLanguage() {
        $language = Language::orderBy('name', 'asc');
        return [
            'data' => $language->get(),
            'total' => $language->count(),
        ];
    }

    public function updateLanguage(Request $request){
        $request->validate([
            'name' => 'required',
            'code' => 'required',
        ]);

        $language = Language::findOrFail($request->id);
        $language->update([
            'name' => $request->name,
            'code' => $request->code,
        ]);
        return response()->json(['success' => true],200);
    }


    public function getCountryWebsite() {
        $list_sites = Publisher::whereNull('country_id')->pluck('url','id')->take(10);

        $test = [];
        if( count($list_sites) > 0 ) {
            foreach( $list_sites as $key => $sites) {

                $url = "https://check-host.net/ip-info?host=".$sites;

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                $headers = array(
                "Accept: application/json",
                );
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        
                // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

                $resp = curl_exec($curl);
                curl_close($curl);

                $dom = new \DOMDocument();
                @$dom->loadHtml($resp);
                $country_name = isset($dom->getElementsByTagName('strong')->item(2)->nodeValue) ? $dom->getElementsByTagName('strong')->item(2)->nodeValue : '';
                $country_id = $this->getCountryId($country_name);

                if (!is_null($country_id) && $country_name != '') {
                    $publisher = Publisher::findOrFail($key);
                    if($publisher) {
                        $publisher->update(['country_id' => $country_id]);
                    }
                }

                array_push($test,[
                    'sites' => $sites,
                    'country' => $country_name,
                    'id' => $country_id,
                ]);
                // dd($dom->getElementsByTagName('strong')->item(2)->nodeValue);
                // dd($dom);

            }
        }

        return response()->json(['data' => $test]);
        // dd($test);

    }

    private function getCountryId($country) {
        $result = null;
        $country_list = Country::where('name', 'like', '%'.$country.'%')->first();

        if($country_list) {
            $result = $country_list->id;
        } 

        if ($country == 'United States of America')  $result = 247;
        if ($country == 'United Kingdom of Great Britain and Northern Ireland')  $result = 246;

        return $result;
    }

}
