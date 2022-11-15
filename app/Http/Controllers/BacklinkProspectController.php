<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BacklinkProspect;
use App\Models\ExtDomain;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client as GuzzleClient;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Libs\Ahref;

class BacklinkProspectController extends Controller
{
    public function getList(Request $request){
        $filter = $request->all();

        $paginate = (isset($filter['paginate']) && !empty($filter['paginate']) ) ? $filter['paginate']:50;

        $backlink_prospects = BacklinkProspect::when(isset($filter['referring_domain']) && !empty($filter['referring_domain']), function($query) use ($filter) {
                                        return $query->where('referring_domain', 'like', '%'.$filter['referring_domain'].'%');
                                    })
                                    ->with('prospect');

        if (isset($filter['ur']) && !empty($filter['ur'])) {
            if ($filter['ur_direction'] === 'Above') {
                $backlink_prospects->where('ur' , '>=', intval($filter['ur']));
            } else {
                $backlink_prospects->where('ur', '<=', intval($filter['ur']));
            }
        }

        // Date upload filter
        $filter['date_upload'] = \GuzzleHttp\json_decode($filter['date_upload'], true);

        if (isset($filter['date_upload']) && $filter['date_upload']['startDate'] != null && $filter['date_upload']['endDate'] != null) {
            $backlink_prospects->whereDate('created_at', '>=', Carbon::create($filter['date_upload']['startDate'])->format('Y-m-d'));
            $backlink_prospects->whereDate('created_at', '<=', Carbon::create($filter['date_upload']['endDate'])->format('Y-m-d'));
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

        if (isset($filter['is_moved']) && !empty($filter['is_moved'])) {
            $value = $filter['is_moved'] === 'yes' ? 1 : 0;

            $backlink_prospects->where('is_moved', $value);
        }

        if (isset($filter['status2'])) {
            if ($filter['status2'] === 'null') {
                $backlink_prospects->has('prospect', '<', 1);
            } else {
                $backlink_prospects->whereHas('prospect', function($q) use ($filter) {
                    $q->where('status', $filter['status2']);
                });
            }
        }

        $backlink_prospects = $backlink_prospects->orderBy('created_at', 'desc');

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

    public function getAhrefs(Request $request) {

        $bp_list = BacklinkProspect::whereIn('id', $request->ids)->get();

        if ($bp_list->count() == 0) {
            return [];
        }

        $configs['token'] = '1221d525cb70af4ee61e2561ced8985935920451';
        $configs['base_url'] = 'https://apiv2.ahrefs.com?mode=subdomains&limit=1&target=';

        $ahrefsInstant = new Ahref($configs);
        $data = $ahrefsInstant->getAhrefsBpAsync($bp_list);

        return $data;
    }

    public function fetchBacklinkProspect(Request $request) {
        $curl = curl_init();

        $link = 'https://mad.apacaff.com/api/fetch-backlink-prospect-data';

        curl_setopt_array($curl, array(
            CURLOPT_URL => $link,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);


        curl_close($curl);

        $response = json_decode($response, true);

        $data = [];
        foreach($response['data'] as $res) {
            $data[] = $res['ref_domain'];

            $referring_domain = $res['ref_domain'];
            $ur = $res['ur'];
            $dr = $res['dr'];
            $backlinks = $res['backlinks'];
            $org_kw = $res['org_keywords'];
            $org_traffic = $res['org_traffic'];
            $ref_domain = $res['ref_domains'];
            // $category = $res['ref_domain'];
            // $status = $res['ref_domain'];
            $note = $res['notes'];
            $date_created = $res['created_at'];

            if( trim($referring_domain, " ") != '' ){

                if(isset($res['status_dropdown']['name']) && strtolower($res['status_dropdown']['name']) == 'new') {
                    $isRefDomainExist = $this->checkRefDomainExist($referring_domain);

                    if(!$isRefDomainExist) {
                        BacklinkProspect::create([
                            'referring_domain' => $referring_domain,
                            'ur' => $ur,
                            'dr' => $dr,
                            'backlinks' => $backlinks,
                            'ref_domain_ahref' => $ref_domain,
                            'org_kw' => $org_kw,
                            'org_traffic' => $org_traffic,
                            'category' => isset($res['category_dropdown']['name']) ? $res['category_dropdown']['name']:null,
                            'status' => $res['status_dropdown']['name'],
                            'note' => $note,
                            'date_created' => $date_created,
                        ]);
                    }
                }
            }
        }

        dd($data);
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
                $date_created = $line[10];

                if( trim($referring_domain, " ") != '' ){

                    if($status == 'New' && $status != '') {
                        $isRefDomainExist = $this->checkRefDomainExist($referring_domain);

                        if(!$isRefDomainExist) {
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
                                'date_created' => $date_created,
                            ]);
                        }
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

    private function checkRefDomainExist($domain) {
        $ref_domain = BacklinkProspect::where('referring_domain', $domain)->count();

        return $ref_domain > 0 ? true:false;
    }

    public function moveToUrlProspect(Request $request) {
        $isExist = $this->checkExistUrlProspect($request->referring_domain);
        $id = Auth::user()->id;
        $result = 'true';

        // update backlink prospect is moved
        $backlink_prospect = BacklinkProspect::find($request->id);
        $backlink_prospect->update(['is_moved' => 1]);

        if(!$isExist) {
            ExtDomain::create([
                'user_id' => $id,
                'domain' => $request->referring_domain,
                'backlink_prospect_id' => $request->id,
                'country_id' => 5,
                'ahrefs_rank' => 0,
                'no_backlinks' => 0,
                'url_rating' => 0,
                'domain_rating' => 0,
                'organic_keywords' => '',
                'organic_traffic' => '',
                'alexa_rank' => 0,
                'ref_domains' => 0,
                'status' => 0,
                'email' => null,
                'from' => 'Backlinks'
            ]);

            $result = 'true';
        } else {
            $ext_domain = ExtDomain::where('domain', $request->referring_domain)->first();
            $ext_domain->update([
                'backlink_prospect_id' => $request->id
                // 'from' => 'Backlinks'
            ]);

            // update the 3rd party status (apacaff) automatically based on the status of url prospect
            $prospect_statuses = [
                10 => 'To be contacted',
                20 => 'To be contacted',
                30 => 'To be contacted',
                110 => 'To be contacted',
                100 => 'Qualified',
                90 => 'Unqualified',
                50 => 'Contacted',
                120 => 'Contacted Via Form',
                70 => 'In-touched',
                60 => 'Refused',
                55 => 'No Answer',
            ];

            if (array_key_exists($ext_domain->status, $prospect_statuses)) {
                $backlink_prospect->update(['status' => $prospect_statuses[$ext_domain->status]]);
            }

            $result = 'false';
        }

        return $result;
    }

    private function checkExistUrlProspect($domain) {
        $ext_domain = ExtDomain::where('domain', $domain)->count();

        return $ext_domain > 0 ? true:false;
    }

    public function editMultiple(Request $request) {
        if(is_array($request->ids)) {
            foreach($request->ids as $id) {
                $backlink_prospect = BacklinkProspect::find($id);
                $backlink_prospect->update([
                    'category' => $request->category == "" ? $backlink_prospect->category:$request->category,
                    'status' => $request->status == "" ? $backlink_prospect->status:$request->status
                ]);
            }
        }
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

    public function getTotals () {
        $totals = DB::table('backlink_prospect')
            ->selectRaw("count(*) as Total")
            ->selectRaw("count(case when status = 'New' then 1 end) as New")
            ->selectRaw("count(case when status = 'Qualified' then 1 end) as Qualified")
            ->selectRaw("count(case when status = 'Unqualified' then 1 end) as Unqualified")
            ->selectRaw("count(case when status = 'Hosting Expired' then 1 end) as Hosting")
            ->selectRaw("count(case when status = 'Free Submission' then 1 end) as Free")
            ->selectRaw("count(case when status = 'To be contacted' then 1 end) as ToBeContacted")
            ->selectRaw("count(case when status = 'Contacted' then 1 end) as Contacted")
            ->selectRaw("count(case when status = 'Contacted Via Form' then 1 end) as ContactedViaForm")
            ->selectRaw("count(case when status = 'In-touched' then 1 end) as Intouched")
            ->selectRaw("count(case when status = 'Refused' then 1 end) as Refused")
            ->selectRaw("count(case when status = 'No Answer' then 1 end) as NoAnswer")
            ->first();

        return response()->json($totals);
    }

    public function getTotalsStatus2 () {
        $totals = DB::table('backlink_prospect')
            ->selectRaw("count(case when ext_domains.status = 0 and ext_domains.deleted_at is null then 1 end) as New")
            ->selectRaw("count(case when ext_domains.status = 20 and ext_domains.deleted_at is null then 1 end) as ContactNull")
            ->selectRaw("count(case when ext_domains.status = 50 and ext_domains.deleted_at is null then 1 end) as Contacted")
            ->selectRaw("count(case when ext_domains.status = 120 and ext_domains.deleted_at is null then 1 end) as ContactedViaForm")
            ->selectRaw("count(case when ext_domains.status = 70 and ext_domains.deleted_at is null then 1 end) as Intouched")
            ->selectRaw("count(case when ext_domains.status = 100 and ext_domains.deleted_at is null then 1 end) as Qualified")
            ->selectRaw("count(case when ext_domains.status = 60 and ext_domains.deleted_at is null then 1 end) as Refused")
            ->selectRaw("count(case when ext_domains.status = 55 and ext_domains.deleted_at is null then 1 end) as NoAnswer")
            ->selectRaw("count(case when ext_domains.status = 30 and ext_domains.deleted_at is null then 1 end) as GotContacts")
            ->selectRaw("count(case when ext_domains.status = 110 and ext_domains.deleted_at is null then 1 end) as GotEmail")
            ->selectRaw("count(case when ext_domains.status = 10 and ext_domains.deleted_at is null then 1 end) as CrawlFailed")
            ->leftJoin('ext_domains', 'backlink_prospect.id', '=', 'ext_domains.backlink_prospect_id')
            ->first();

        return response()->json($totals);
    }
}
