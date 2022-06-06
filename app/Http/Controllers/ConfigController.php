<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\User;
use App\Repositories\Contracts\ConfigRepositoryInterface;
use App\UserWorkMails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

    public function getEmailAccessList() {
        $email_access = User::distinct('work_mail')
            ->where('isOurs', 0)
            ->where('type', '!=', 10)
            ->whereNotNull('work_mail')
            ->where('work_mail', '!=', '')
            ->where('status', '!=', 'inactive')
            ->with('access:user_id,work_mail')
            ->with('role')
            ->orderBy('work_mail', 'asc');

        return response()->json([
            'data' => $email_access->get(),
            'total' => $email_access->count(),
        ]);
    }

    public function addEmailAccess(Request $request)
    {
        $req_emails = $request->emails;
        $emails = [];
        $restore = [];

        $user = User::find($request->user_id);

        // get trashed items from database
        $trashed = $user->access()->onlyTrashed()->pluck('work_mail')->toArray();

        // filter items on email array, get restored items and remove in request array
        foreach ($trashed as $key=>$trash) {
            if (in_array($trash, $req_emails)) {
                $restore[] = $trash;
            }
        }

        // restore the trashed items that are in the email array
        $user->access()->onlyTrashed()->whereIn('work_mail', $restore)->restore();

        // create array of model instance
        foreach ($req_emails as $email) {
            $emails[] = $user->access()->firstOrNew(['work_mail' => $email]);
        }

        // save many through relationship
        $user->access()->saveMany($emails);

        // delete items that are not on the email array
        $user->access()->whereNotIn('work_mail', $request->emails)->delete();

        return response()->json(['success' => true],200);
    }

    public function updateLanguage(Request $request){
        $request->validate([
            'name' => 'required',
            'code' => 'required',
        ]);

        $language = Language::findOrFail($request->id);
        $language->update([
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

    public function updateCryptoAddress(Request $request)
    {
        $file = $request->file('file');

        if ($file) {
            Storage::disk('local')->put('usdt.jpg', file_get_contents($file));
        }

        if ($request->has('address')) {
            Config::updateOrCreate([
                'code' => 'usdt_address'
            ], [
                'name' => 'USDT Address',
                'code' => 'usdt_address',
                'value' => $request->get('address'),
                'type' => 'crypto'
            ]);
        }

        return 'OK';
    }


    public function updateCryptoAddressBtc(Request $request)
    {
        $file = $request->file('file');

        if ($file) {
            Storage::disk('local')->put('btc.jpg', file_get_contents($file));
        }

        if ($request->has('address')) {
            Config::updateOrCreate([
                'code' => 'btc_address'
            ], [
                'name' => 'BTC Address',
                'code' => 'btc_address',
                'value' => $request->get('address'),
                'type' => 'crypto'
            ]);
        }

        return 'OK';
    }

    public function updateCryptoAddressEth(Request $request)
    {
        $file = $request->file('file');

        if ($file) {
            Storage::disk('local')->put('eth.jpg', file_get_contents($file));
        }

        if ($request->has('address')) {
            Config::updateOrCreate([
                'code' => 'eth_address'
            ], [
                'name' => 'ETH Address',
                'code' => 'eth_address',
                'value' => $request->get('address'),
                'type' => 'crypto'
            ]);
        }

        return 'OK';
    }
}
