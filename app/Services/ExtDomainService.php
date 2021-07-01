<?php

namespace App\Services;

use App\ExtDomainStatus;
use App\Models\ExtDomain;
use Carbon\Carbon;

class ExtDomainService
{
    public function updateStatusDate($request)
    {
        $domain = ExtDomain::find($request->id);
        $domain->status_updated_at = Carbon::now();
        $domain->save();

        // for status change log table
//        $status = new ExtDomainStatus(['status' => $request->status]);
//
//        $domain = ExtDomain::find($request->id);
//
//        $domain->status()->save($status);
    }
}
