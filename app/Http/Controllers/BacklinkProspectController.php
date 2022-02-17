<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BacklinkProspect;

class BacklinkProspectController extends Controller
{
    public function getList(Request $request){
        $filter = $request->all();
        $paginate = (isset($filter['paginate']) && !empty($filter['paginate']) ) ? $filter['paginate']:50;

        $backlink_prospects = BacklinkProspect::when(isset($filter['referring_domain']) && !empty($filter['referring_domain']), function($query) use ($filter) {
                                        return $query->where('referring_domain', 'like', '%'.$filter['referring_domain'].'%');
                                    });
        
        if (isset($filter['ur']) && !empty($filter['ur'])) {
            if ($filter['ur_direction'] === 'Above') {
                $backlink_prospects->where('ur' , '>=', intval($filter['ur']));
            } else {
                $backlink_prospects->where('ur', '<=', intval($filter['ur']));
            }
        }

        if (isset($filter['dr']) && !empty($filter['dr'])) {
            if ($filter['dr_direction'] === 'Above') {
                $backlink_prospects->where('dr' , '>=', intval($filter['dr']));
            } else {
                $backlink_prospects->where('dr', '<=', intval($filter['dr']));
            }
        }

        if (isset($filter['org_kw']) && !empty($filter['org_kw'])) {
            if ($filter['org_kw_direction'] === 'Above') {
                $backlink_prospects->where('org_keywords' , '>=', intval($filter['org_kw']));
            } else {
                $backlink_prospects->where('org_keywords', '<=', intval($filter['org_kw']));
            }
        }

        if (isset($filter['org_traffic']) && !empty($filter['org_traffic'])) {
            if ($filter['org_traffic_direction'] === 'Above') {
                $backlink_prospects->where('org_traffic' , '>=', intval($filter['org_traffic']));
            } else {
                $backlink_prospects->where('org_traffic', '<=', intval($filter['org_traffic']));
            }
        }

        if (isset($filter['status']) && !empty($filter['status'])) {
            $backlink_prospects->where('status', $filter['status']);
        }

        if(isset($filter['paginate']) && !empty($filter['paginate']) && $filter['paginate'] == 'All' ){
            return response()->json([
                'data' => $backlink_prospects->get(),
                'total' => $backlink_prospects->count(),
            ],200);
        } else {
            return $backlink_prospects->paginate($paginate);
        }
        
        return response()->json(['data' => $backlink_prospects],200);
    }

    public function importCsv(Request $request) {
        $request->validate([
            'file' => 'bail|required|mimes:csv,txt',
        ]);

        $csv_file = $request->file;

        $result = true;
        $message = '';
        $file_message = '';

        $csv = fopen($csv_file, 'r');
        $ctr = 0;

        while ( ($line = fgetcsv($csv) ) !== FALSE) {
            // dd(count($line));
            // break;

            if(count($line) > 11 || count($line) < 11){
                $message = "Please check the header: Referring Domain, UR, DR, Backlinks, Org Kw, Org Traffic, Ref Domain, Category, Status and Note.";
                $file_message = "Invalid Header format. ".$message;
                $result = false;
                break;
            }

            if( $ctr > 0 ){
                $referring_domain = $line[0];
                $ur = $line[1];
                $dr = $line[2];
                $backlinks = $line[3];
                $org_kw = $line[4];
                $org_traffic = $line[5];
                $ref_domain = $line[6];
                $category = $line[7];
                $status = $line[8];
                $note = $line[9];

                if( trim($referring_domain, " ") != '' ){

                    // if($status == 'New' && $status != '') {
                    if($status != '') {
                        BacklinkProspect::create([
                            'referring_domain' => $referring_domain,
                            'ur' => $ur,
                            'dr' => $dr,
                            'backlinks' => $backlinks,
                            'ref_domain_ahref' => $ref_domain,
                            'org_kw' => $org_kw,
                            'org_traffic' => $org_traffic,
                            'category' => $category,
                            'status' => $status,
                            'note' => $note,
                        ]);
                    } 
                }
            }

            $ctr++;
        }

        fclose($csv);

        return [
            "success" => $result,
            "message" => $message,
            "errors" => [
                "file" => $file_message,
            ]
        ];

    }

    public function delete(Request $request) {
        if(is_array($request->ids)) {
            foreach($request->ids as $id) {
                $backlink_prospect = BacklinkProspect::find($id);
                $backlink_prospect->delete();
            }
        }
    }

    public function update(Request $request) {

        $backlink_prospect = BacklinkProspect::find($request->id);
        $backlink_prospect->update([
            'category' => $request->category,
            'status' => $request->status,
            'note' => $request->note
        ]);

        return response()->json(['success' => true], 200);
    }
}
