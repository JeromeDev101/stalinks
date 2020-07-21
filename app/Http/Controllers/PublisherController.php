<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\PublisherRepositoryInterface;
use App\Repositories\Contracts\ConfigRepositoryInterface;
use App\Models\Publisher;
use Illuminate\Support\Facades\Auth;

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
        $request->validate([
            'file' => 'bail|required|mimes:csv,txt',
            'language' => 'required'
        ]);

        $file = $request->all();
        $data = $this->publisherRepository->importExcel($file);

        
        if($data['success'] === false){
            unset($data['success']);
            return response()->json($data, 422);
        }

        return response()->json(['success' => true], 200);

    }

    public function update(Request $request){
        $input = $request->except('name', 'company_name', 'url', 'username');

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
}
