<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\PublisherRepositoryInterface;
use App\Models\Publisher;

class PublisherController extends Controller
{
    private $publisherRepository;

    public function __construct(PublisherRepositoryInterface $publisherRepository)
    {
        $this->publisherRepository = $publisherRepository;
    }

    public function getList(Request $request)
    {
        $filter = $request->all();
        $data = $this->publisherRepository->getList($filter);
        return response()->json($data);
    }

    public function importExcel(Request $request){
        $request->validate([
            'file' => 'bail|required|mimes:csv,txt'
        ]);

        $file = $request->file;
        $this->publisherRepository->importExcel($file);
        
        return response()->json(['success' => true], 200);
        
    }

    public function update(Request $request){
        $input = $request->except('name', 'company_name', 'url');
       
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
}
