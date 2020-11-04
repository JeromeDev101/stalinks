<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\ConfigRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client as GuzzleClient;
use App\Models\Formula;
use App\Models\Language;

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
}
