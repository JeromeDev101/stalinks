<?php

namespace App\Http\Controllers;

use App\BestPriceGenerator;
use App\Events\BestPriceGenerationStart;
use App\Jobs\GenerateBestPrice;
use App\Jobs\GenerateCountryByLanguageJob;
use App\Jobs\SimpleMultipleUpdate;
use App\Models\DomainZone;
use App\Notifications\CsvUploaded;
use App\Services\HttpClient;
use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;
use Illuminate\Http\Request;
use App\Repositories\Contracts\PublisherRepositoryInterface;
use App\Repositories\Contracts\ConfigRepositoryInterface;
use App\Models\Publisher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use League\OAuth2\Server\RequestEvent;
use App\Models\User;
use App\Models\Registration;
use App\Models\Backlink;
use App\Models\Formula;
use Illuminate\Support\Facades\DB;
use Mailgun\Api\Domain;
use Psr\Http\Message\ResponseInterface;

class PublisherController extends Controller
{
    private $publisherRepository;

    /**
     * @var ConfigRepositoryInterface
     */
    private $configRepository;

    private $httpClient;

    public function __construct(PublisherRepositoryInterface $publisherRepository,
                                ConfigRepositoryInterface $configRepository, HttpClient $httpClient)
    {
        $this->publisherRepository = $publisherRepository;
        $this->configRepository = $configRepository;
        $this->httpClient = $httpClient;
    }

    public function getList(Request $request)
    {
        $filter = $request->all();
        $data = $this->publisherRepository->getList($filter);
        return response()->json($data);
    }

    public function importExcel(Request $request){
        if (Auth::user()->isOurs == 1){
            $request->validate([
                'account_valid' => 'required|in:false',
                'file' => 'bail|required|mimes:csv,txt',
//                'language' => 'required',
            ]);
        } else{
            $request->validate([
                'file' => 'bail|required|mimes:csv,txt',
            ]);
        }

        $file = $request->all();
        $data = $this->publisherRepository->importExcel($file);


        if($data['success'] === false){
            unset($data['success']);
            return response()->json($data, 422);
        } else {
            // send email to user upload successful
            if (Auth::user()->email) {
                auth()->user()->notify(new CsvUploaded(auth()->user()));
            }
        }

        return response()->json(['success' => true, 'data' => $data['exist'] ], 200);

    }

    public function store(Request $request){
        $valid = 'valid';
        $request->validate([
            'account_valid' => 'required|in:' . false,
            'seller' => 'required',
            'inc_article' => 'required',
            'url' => 'required',
            'language_id' => 'required',
            'price' => 'required',
            'casino_sites' => 'required',
            'topic' => 'required',
            'kw_anchor' => Rule::requiredIf(function () use ($request) {
                return Auth::user()->isOurs == 1;
            }),
        ]);

        $url_copy = remove_http($request->url);

        $isDuplicate = $this->checkDuplicate($url_copy, $request->seller);

        if($isDuplicate) {
            return response()->json([
                "message" => 'The given data was invalid.',
                "errors" => [
                    "url" => 'Duplicate url and seller.',
                ],
            ],422);
        }

        $publisher = Publisher::where('url', 'like', '%'.$url_copy.'%')->where('valid', 'valid')->count();
        if ($publisher > 0) {
            $valid = 'unchecked';
        }

        $input = $request->except('seller','topic','url');
        $input['user_id'] = $request->seller;
        $input['valid'] = $valid;
        $input['topic'] = is_array($request->topic) ? implode(",", $request->topic):$request->topic;

        $url = str_replace( '/','',preg_replace('/^www\./i', '', $url_copy));

        // check if url is valid
        $isValidURL = $this->publisherRepository->isValidURL($url);

        if (!$isValidURL) {
            return response()->json([
                "message" => 'The given data was invalid.',
                "errors" => [
                    "url" => 'Invalid url format.',
                ],
            ],422);
        }

        $input['url'] = $url;

        if (!isset($input['kw_anchor']) || $input['kw_anchor'] == null) {
            unset($input['kw_anchor']);
        }

        $input['is_https'] = $this->httpClient->getProtocol($url_copy) == 'https' ? 'yes' : 'no';

        Publisher::create($input);
        return response()->json(['success' => true], 200);
    }

    private function checkDuplicate($url, $seller_id) {
        $publisher = Publisher::where('url', $url)->where('user_id', $seller_id);
        return $publisher->count() > 0;
    }

    private function checkDuplicateUpdate($url, $seller_id, $id) {
        $publisher = Publisher::where('url', $url)->where('user_id', $seller_id)->where('id', '!=', $id)
        ->whereNull('deleted_at');
        return $publisher->count() > 0;
    }

    public function updateMultiple(Request $request) {
        // dd($request->all());
        foreach( $request->ids as $id ) {
            $publisher = Publisher::findOrFail($id);
            $publisher->update([
                'language_id' => $request->language == '' ? $publisher->language_id : $request->language,
                'country_id' => $request->country == '' ? $publisher->country_id : $request->country,
                'continent_id' => $request->continent_id == '' ? $publisher->continent_id : $request->continent_id,
                'topic' => $request->topic == '' ? $publisher->topic : implode(",", $request->topic),
                'casino_sites' => $request->casino_sites == '' ? $publisher->casino_sites : $request->casino_sites,
                'kw_anchor' => $request->kw_anchor == '' ? $publisher->kw_anchor : $request->kw_anchor,
                'price' => $request->price == '' ? $publisher->price : $request->price,
                'inc_article' => $request->inc_article == '' ? $publisher->inc_article : $request->inc_article,
                'qc_validation' => $request->qc_validation == '' ? $publisher->qc_validation : $request->qc_validation,
            ]);
        }

        return response()->json(['success' => true], 200);
    }

    public function update(Request $request){

        $request->validate([
            'url' => 'required',
            'topic' => 'required',
            'language_id' => 'required',
            'price' => 'required',
            'casino_sites' => 'required',
            'kw_anchor' => 'required',
        ]);

        $request['url'] = remove_http($request->url);

        $isDuplicate = $this->checkDuplicateUpdate($request->url, $request->user_id, $request->id);

        if($isDuplicate) {
            return response()->json([
                "message" => 'The given data was invalid.',
                "errors" => [
                    "url" => 'Duplicate url and seller.',
                ],
            ],422);
        }

        $input = $request->except('name', 'company_name', 'username', 'topic', 'user_id', 'team_in_charge', 'team_in_charge_old');

//        if($input['continent_id']){
//            $input['country_id'] = null;
//        }

        $input['topic'] = is_array($request->topic) ? implode(",", $request->topic):$request->topic;
        $publisher = Publisher::findOrFail($input['id']);
        $publisher->update($input);

        // Updating Team in Charge
        // if( $request->user_id != "" && $request->team_in_charge_old != "" && $request->team_in_charge != $request->team_in_charge_old ) {
        //     $user = User::find($request->user_id);
        //     $registration = Registration::where('email', $user->email)->first();
        //     if(isset($registration->id)){
        //         $registration->update(['team_in_charge' => $request->team_in_charge]);
        //     }
        // }

        return response()->json(['success' => true], 200);
    }

    public function delete(Request $request){
        $input['deleted_at'] = date('Y-m-d');
        if( is_array($request->id) ){
            foreach( $request->id as $ids ){
                $publisher = Publisher::findOrFail($ids);
                $publisher->update($input);
            }

            return response()->json(['success' => true], 200);
        }

        $publisher = Publisher::findOrFail($request->id);
        $publisher->update($input);

        return response()->json(['success' => true], 200);
    }

    public function getListSellerIncharge($userId) {
        $data = User::select('users.*', 'registration.id as register_id', 'registration.team_in_charge')
            ->leftJoin('registration', 'users.email', '=', 'registration.email')
            ->where('registration.team_in_charge', $userId)
            ->orWhere('users.id', $userId)
            ->get();

        return compact('data');
    }

    public function getAhrefs(Request $request) {
        $input = $request->all();

        if (!isset($input['params']['domain_ids'])) {
            return response()->json(['success' => false, 'message' => 'id domains is empty']);
        }

        $listId = explode(",", $input['params']['domain_ids']);
        $configs = $this->configRepository->getConfigs('ahrefs');
        $data = $this->publisherRepository->getAhrefs($listId, $configs);
        return response()->json($data);
    }

    public function getSummary()
    {
        $user = Auth::user();

        $data = $this->publisherRepository->getPublisherSummary($user->id);

        return response()->json($data);
    }

    public function validData(Request $request){
        $result = [];
        foreach( $request->ids AS $id ){
            $publisher = Publisher::findOrfail($id);

            $check = Publisher::where('valid', 'valid')->where('url', 'like', '%'.$publisher->url.'%');

            if( $check->count() > 0 && $publisher->valid != 'valid' && $request->valid == 'valid'){

                array_push($result,[
                    'id' => $publisher->id,
                    'message' => 'existing',
                    'url' => $publisher->url
                ]);
            } else {
                array_push($result,[
                    'id' => $publisher->id,
                    'message' => 'validated',
                    'url' => $publisher->url
                ]);

                $publisher->update([
                    'valid' => $request->valid,
                ]);
            }

        }


        return response()->json(['success'=> true, 'data' => $result],200);
    }

    public function qcValidationUpdate(Request $request) {
//        $result = Publisher::whereIn('id', $request->ids)
//            ->update(['qc_validation' => $request->qc_validation]);

        $result = SimpleMultipleUpdate::dispatchNow(new Publisher(), $request->ids, ['qc_validation' => $request->qc_validation]);;

        return response()->json(['success'=> true, 'data' => $result],200);
    }

    public function getInfo(Request $request) {
        $publisher = Publisher::where('url', $request->url)->first();
        if( !$publisher ){
            return response()->json(['success' => false, 'data' => ''],200);
        }
        return response()->json(['success' => true, 'data' => $publisher],200);
    }

    public function qcValidation() {
        $publishers = Publisher::select('id')
                        ->where('valid', 'valid')
                        ->where('publisher.ur', '!=', '0')
                        ->where('publisher.dr', '!=', '0')
                        ->get();

        foreach($publishers as $publisher) {

            $pub = Publisher::find($publisher->id);
            $pub->update(['qc_validation' => 'yes']);

        }

        return response()->json(['success'=> true],200);
    }

    private function getStalinksPrices($price, $article) {

        $formula = Formula::all();

        $additional = floatVal($formula[0]->additional);
        $percent = floatVal($formula[0]->percentage);
        $selling_price = $price;

        if( $price != '' && $price != null ){ // check if price has a value

            if( strtolower($article) == 'yes' ){ //check if with article

                // if( commission == 'no' ){
                //     selling_price = price
                // }

                // if( commission == 'yes' ){
                    $percentage = $this->percentage($percent, $price);
                    $selling_price = floatVal($percentage) + floatVal($price);
                // }
            }

            if( strtolower($article) == 'no' ){ //check if without article

                // if( commission == 'no' ){
                //     selling_price = parseFloat(price) + additional
                // }

                // if( commission == 'yes' ){
                    $percentage = $this->percentage($percent, $price);
                    $selling_price = floatVal($percentage) + floatVal($price) + $additional;
                // }

            }

        }

        return $selling_price;
    }

    private function percentage($percent, $total) {
        return (($percent/ 100) * $total);
    }

    public function generateBestPrice()
    {
        $log = BestPriceGenerator::orderBy('created_at', 'DESC')->first();

        if ($log->status == 'end') {
            GenerateBestPrice::dispatch(auth()->user()->id)->onQueue('high');
        } else {
            return response()->json([
                'error' => 'Someone is already generating, please try again later..'
            ], 500);
        }

        return response()->json('success');
    }

    public function bestPricesGenerationLog()
    {
        $log = BestPriceGenerator::orderBy('created_at', 'DESC')->get();

        return response()->json($log);
    }

    public function getDomainZoneExtensions()
    {
//        $zones = Publisher::selectRaw("
//                trim(trailing ')'
//                    from trim( trailing '/'
//                        from REPLACE(SUBSTRING_INDEX(url, '.', -1), ' ', ''))) as domain2
//            ")
//            ->whereNull('deleted_at')
//            ->groupBy('domain2')
//            ->having('domain2', '!=', '')
//            ->orderBy('domain2', 'ASC');

        $zones = DomainZone::orderBy('name', 'ASC');

        return response()->json([
            'data' => $zones->get(),
            'count' => 0,
        ],200);
    }

    public function generateCountry(Request $request)
    {
        GenerateCountryByLanguageJob::dispatch($request->get('ids'));

        return response()->json('success');
    }
}
