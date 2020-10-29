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

        return response()->json(['success' => true], 200);

    }

    public function store(Request $request){
        $request->validate([
            'seller' => 'required',
            'inc_article' => 'required',
            'url' => 'required',
            'language_id' => 'required',
            'price' => 'required',
            'casino_sites' => 'required',
            'topic' => 'required',
        ]);
        
        $input = $request->except('seller');
        $input['user_id'] = $request->seller;
        $input['valid'] = 'unchecked';

        Publisher::create($input);
        return response()->json(['success' => true], 200);
    }

    public function update(Request $request){
        $input = $request->except('name', 'company_name', 'username');

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
                    return response()->json([
                        'errors' => [
                            'file' => ''
                        ],
                        'message' => '',
                        'data' => $publisher->url
                    ],422);
                }
            }

            $result[] = $publisher->update([
                'valid' => $request->valid,
            ]);
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
