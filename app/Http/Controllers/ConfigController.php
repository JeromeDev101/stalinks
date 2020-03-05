<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\ConfigRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
}
