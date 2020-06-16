<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\PublisherRepositoryInterface;

class PublisherController extends Controller
{
    private $publisherRepository;

    public function __construct(PublisherRepositoryInterface $publisherRepository)
    {
        $this->publisherRepository = $publisherRepository;
    }

    public function getList()
    {
        $data = $this->publisherRepository->getList();
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
}
