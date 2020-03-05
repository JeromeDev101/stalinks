<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Function to check string starting
    // with given substring
    function startsWith ($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }

    /**
     * add pagnation html render of an \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @param $data
     * @return mixed
     */
    protected function addPaginationRaw($data) {
        $paginateView = '';

        if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator ) {
            $paginateView = View::make('paginate', ['data' => $data])->render();
            $resultRegex = [];
            preg_match_all('/\?page=(.*?)"/', $paginateView, $resultRegex);
            $mapPage = [];
            foreach($resultRegex[1] as $pageNum) {
                if (!isset($mapPage[$pageNum])) {
                    $mapPage[$pageNum] = true;
                    $repText = '?page='.$pageNum.'"';
                    $paginateView = str_replace($repText, $repText.' v-on:click.prevent=goToPage('.$pageNum.')', $paginateView);
                }
            }

            $data = $data->toArray();
        }

        $data['pagination'] = $paginateView;
        return $data;
    }
}
