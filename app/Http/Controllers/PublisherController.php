<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\PublisherRepositoryInterface;
use App\Repositories\Contracts\ConfigRepositoryInterface;
use App\Models\Publisher;
use Illuminate\Support\Facades\Auth;
use League\OAuth2\Server\RequestEvent;

class PublisherController extends Controller
{
    private $publisherRepository;

    /**
     * @var ConfigRepositoryInterface
     */
    private $configRepository;

    public function __construct(PublisherRepositoryInterface $publisherRepository,
                                ConfigRepositoryInterface $configRepository)
    {
        $this->publisherRepository = $publisherRepository;
        $this->configRepository = $configRepository;
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
                'file' => 'bail|required|mimes:csv,txt',
                'language' => 'required',
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
        }

        return response()->json(['success' => true, 'data' => $data['exist'] ], 200);

    }

    public function store(Request $request){
        $valid = 'valid';
        $request->validate([
            'seller' => 'required',
            'inc_article' => 'required',
            'url' => 'required',
            'language_id' => 'required',
            'price' => 'required',
            'casino_sites' => 'required',
            'topic' => 'required',
        ]);
        
        $publisher = Publisher::where('url', 'like', '%'.$request->url.'%')->where('valid', 'valid')->count();
        if ($publisher > 0) {
            $valid = 'unchecked';
        }
        
        $input = $request->except('seller','topic');
        $input['user_id'] = $request->seller;
        $input['valid'] = $valid;
        $input['topic'] = implode(",", $request->topic);

        Publisher::create($input);
        return response()->json(['success' => true], 200);
    }

    public function updateMultiple(Request $request) {
        // dd($request->all());
        foreach( $request->ids as $id ) {
            $publisher = Publisher::findOrFail($id);
            $publisher->update([
                'language_id' => $request->language == '' ? $publisher->language_id : $request->language,
                'country_id' => $request->country == '' ? $publisher->country_id : $request->country,
                'topic' => $request->topic == '' ? $publisher->topic : implode(",", $request->topic),
                'casino_sites' => $request->casino_sites == '' ? $publisher->casino_sites : $request->casino_sites,
                'kw_anchor' => $request->kw_anchor == '' ? $publisher->kw_anchor : $request->kw_anchor,
                'price' => $request->price == '' ? $publisher->price : $request->price,
                'inc_article' => $request->inc_article == '' ? $publisher->inc_article : $request->inc_article,
            ]);
        }

        return response()->json(['success' => true], 200);
    }

    public function update(Request $request){
        $input = $request->except('name', 'company_name', 'username', 'topic');
        $input['topic'] = implode(",", $request->topic);
        // dd($input);
        $publisher = Publisher::findOrFail($input['id']);
        $publisher->update($input);

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

    public function getAhrefs(Request $request) {
        $input = $request->all();

        if (!isset($input['domain_ids'])) {
            return response()->json(['success' => false, 'message' => 'id domains is empty']);
        }

        $listId = explode(",", $input['domain_ids']);
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

            if( $request->valid == 'valid' && $publisher->valid != 'valid'){
                $check = Publisher::where('valid', 'valid')->where('url', 'like', '%'.$publisher->url.'%');

                if( $check->count() > 0 ){

                    array_push($result,[
                        'id' => $publisher->id,
                        'message' => 'existing',
                        'url' => $publisher->url
                    ]);
                } 
                
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

    public function getInfo(Request $request) {
        $publisher = Publisher::where('url', $request->url)->first();
        if( !$publisher ){
            return response()->json(['success' => false, 'data' => ''],200);
        }
        return response()->json(['success' => true, 'data' => $publisher],200);
    }
}
