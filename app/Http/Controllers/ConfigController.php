<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\ConfigRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client as GuzzleClient;
use App\Models\Formula;
use App\Models\Language;
use App\Models\ExtDomain;
use App\Models\Country;
use App\Models\Publisher;
use App\Jobs\PriceBasisJob;

class ConfigController extends Controller
{
    /**
     * @var ConfigRepositoryInterface
     */
    private $configRepository;

    /**
     * ConfigController constructor.
     *
     * @param ConfigRepositoryInterface $configRepository
     */
    public function __construct(ConfigRepositoryInterface $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    public function getList() {
        $data = $this->configRepository->findAll();

        $dataReturn = [];

        foreach ($data as $item) {
            if (!isset($dataReturn[$item->type])) {
                $dataReturn[$item->type] = [];
            }

            $dataReturn[$item->type][] = $item;
        }

        return response()->json([
            'data' => $dataReturn,
            'total' => count($data)
        ]);
    }

    public function edit(Request $request) {

        $response = ['success' => false];
        $input = $request->only(['id', 'value']);

        Validator::make($input, [
            'id' => 'required',
            'value' => 'required',
        ])->validate();

        $config = $this->configRepository->findById($input['id']);
        if (!$config) {
            return response()->json($response);
        }

        $this->configRepository->update($config, $input);
        $response['success'] = true;
        return response()->json($response);
    }

    public function getSubscriptionInfo() {
        $client = new GuzzleClient();
        $subscription_url = 'https://apiv2.ahrefs.com/?token=1221d525cb70af4ee61e2561ced8985935920451&limit=1000&output=json&from=subscription_info';
        $response = $client->get($subscription_url);
        $result = json_decode($response->getBody()->getContents(), true);

        return $result;
    }

    public function getFormula() {
        $formula = Formula::all();
        return response()->json(['data' => $formula],200);
    }

    public function updateFormula(Request $request) {
        $request->validate([
            'additional' => 'required',
            'percentage' => 'required',
        ]);

        $input = $request->except('id');
        $formula = Formula::findOrFail($request->id);
        $formula->update($input);
        return response()->json(['success' => true],200);
    }

    public function storeLanguage(Request $request) {
        $input = $request->all();
        $request->validate([
            'name' => 'required',
            'code' => 'required',
        ]);

        $language = Language::create($input);
        return response()->json(['success' => true],200);
    }

    public function getLanguage() {
        $language = Language::orderBy('name', 'asc');
        return [
            'data' => $language->get(),
            'total' => $language->count(),
        ];
    }

    public function updateLanguage(Request $request){
        $request->validate([
            'name' => 'required',
            'code' => 'required',
        ]);

        $language = Language::findOrFail($request->id);
        $language->update([
            'name' => $request->name,
            'code' => $request->code,
        ]);
        return response()->json(['success' => true],200);
    }

    public function urlProspectEmailExtraction() {
        $emails = ExtDomain::select('email', 'id')
                ->whereNotNull('email')
                ->where(function($query){
                    return $query->where('email', 'like', '%.png%')
                                ->orWhere('email', 'like', '%.jpg%')
                                ->orWhere('email', 'like', '%.svg%')
                                ->orWhere('email', 'like', '%.jpeg%')
                                ->orWhere('email', 'like', '%.webp%')
                                ->orWhere('email', 'like', '%.jp2%')
                                ->orWhere('email', 'like', '%.gif%');
                })
                ->get();

        $new_emails = [];
        foreach( $emails as $email ) {
            if(strpos($email->email, '|') !== false){
                $explode_email = explode('|', $email->email);
                $new = [];
                foreach($explode_email as $exp_email) {
                    $lowercase_email = strtolower($exp_email);
                    if(
                        strpos($lowercase_email, '.png') !== false || 
                        strpos($lowercase_email, '.jpg') !== false || 
                        strpos($lowercase_email, '.jpeg') !== false || 
                        strpos($lowercase_email, '.svg') !== false || 
                        strpos($lowercase_email, '.gif') !== false || 
                        strpos($lowercase_email, '.jp2') !== false || 
                        strpos($lowercase_email, '.webp')
                    ) {
                    } else{
                        $new[] = $exp_email;
                    }
                }

                if(count($new) > 0) {
                    array_push($new_emails,[
                        'id' => $email->id,
                        'email' => implode('|', $new)
                    ]);
                } else {
                    array_push($new_emails,[
                        'id' => $email->id,
                        'email' => null
                    ]);
                }
            } else {
                $lowercase_email = strtolower($email);
                if(
                    strpos($lowercase_email, '.png') !== false || 
                    strpos($lowercase_email, '.jpg') !== false || 
                    strpos($lowercase_email, '.jpeg') !== false || 
                    strpos($lowercase_email, '.svg') !== false || 
                    strpos($lowercase_email, '.gif') !== false || 
                    strpos($lowercase_email, '.jp2') !== false || 
                    strpos($lowercase_email, '.webp')
                ) {
                    array_push($new_emails,[
                        'id' => $email->id,
                        'email' => null
                    ]);
                } else{
                    array_push($new_emails,[
                        'id' => $email->id,
                        'email' => $email->email
                    ]);
                }
                
            }
        }

        // updating of emails
        foreach($new_emails as $new_email) {
            $ext_domain = ExtDomain::find($new_email['id']);
            $ext_domain->update([
                'email' => $new_email['email']
            ]);
        }

        return response()->json($emails, 200);
    }

    public function updatePriceBasis()
    {
        PriceBasisJob::dispatch();
    }

}
