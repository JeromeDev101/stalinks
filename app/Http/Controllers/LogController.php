<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\LogRepositoryInterface;
use App\Repositories\Contracts\MailLogRepositoryInterface;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * @var LogRepositoryInterface
     */
    private $logRepository;

    /**
     * @var MailLogRepositoryInterface
     */
    private $mailLogRepository;

    /**
     * LogController constructor.
     *
     * @param LogRepositoryInterface $logRepository
     * @param MailLogRepositoryInterface $mailLogRepository
     */
    public function __construct(LogRepositoryInterface $logRepository, MailLogRepositoryInterface $mailLogRepository)
    {
        $this->logRepository = $logRepository;
        $this->mailLogRepository = $mailLogRepository;
    }

    public function getList(Request $request) {
        $input = $request->all();
        $userEmail = '';
        $perPage = config('common.paginate.default');
        $filters = [
            'whereIn' => [],
            'where' => []
        ];

        if (isset($input['action']) && $input['action'] != '') {
            $filters['where'][] = ['action', $input['action']];
        }

        if (isset($input['email']) && $input['email'] != '') {
            $userEmail = $input['email'];
        }

        if (isset($input['table']) && $input['table'] != '') {
            $filters['where'][] = ['table', $input['table']];
        }

        if (isset($input['per_page'])) {
            $perPage = $input['per_page'];
        }

        $data = $this->logRepository->paginate($perPage, $userEmail, $filters);
        $data['paginate'] = $this->addPaginationRaw($data['paginate']);
        return response()->json($data);
    }

    public function getMailList(Request $request) {
        $input = $request->all();
        $userEmail = '';
        $perPage = config('common.paginate.default');
        $filters = [
            'whereIn' => [],
            'where' => []
        ];

        if (isset($input['action']) && $input['action'] != '') {
            $filters['where'][] = ['action', $input['action']];
        }

        if (isset($input['email']) && $input['email'] != '') {
            $userEmail = $input['email'];
        }

        if (isset($input['status']) && $input['status'] != '') {
            $filters['where'][] = ['status', $input['status']];
        }

        if (isset($input['from']) && $input['from'] != '') {
            $filters['where'][] = ['from', 'like', '%'.$input['from'].'%'];
        }

        if (isset($input['to']) && $input['to'] != '') {
            $filters['where'][] = ['to', 'like', '%'.$input['to'].'%'];
        }

        if (isset($input['ext']) && $input['ext'] != '') {
            $filters['where'][] = ['ext_domain', 'like', '%'.$input['ext'].'%'];
        }

        if (isset($input['per_page'])) {
            $perPage = $input['per_page'];
        }

        $data = $this->mailLogRepository->paginate($perPage, $userEmail, $filters);
        $data['paginate'] = $this->addPaginationRaw($data['paginate']);

        return response()->json($data);
    }

    public function getTables() {
        $path = app_path('Models');

        $models = $this->logRepository->getModels($path);


        foreach ($models as $model) {
            $data[$model] = with(new $model())->getTable();
        }

//        $data = [
//            'App\Models\Article' => 'Article',
//            'App\Models\Billing' => 'Billing',
//            'App\Models\BillingWriter' => 'Billing Writer',
//            'App\Models\BuyerPurchased' => 'Buyer Purchased',
//            'App\Models\Config' => 'Config',
//            'App\Models\Config' => 'Continent',
//            'App\Models\ExtDomain' => 'External Domain',
//            'App\Models\IntDomain' => 'Internal Domain',
//            'App\Models\Backlink' => 'Back link',
//            'App\Models\HostingProvider' => 'Hosting Provider',
//            'App\Models\DomainProvider' => 'Domain Provider',
//            'App\Models\User' => 'User',
//            'App\Models\Country' => 'Country',
//            'App\Models\MailContent' => 'Mail Template',
//            'App\Models\UserCountry' => 'Country for IntDomain',
//            'App\Models\UserCountryExt' => 'Country for ExtDomain',
//            'App\Models\UserIntDomain' => 'IntDomain Permission',
//        ];

        return response()->json($data);
    }

    public function getActions() {
        $data = [
            config('constant.ACTION_CREATE') => 'Create',
            config('constant.ACTION_UPDATE') => 'Update',
            config('constant.ACTION_DELETE') => 'Delete',
            config('constant.ACTION_ALEXA') => 'Alexa'
        ];

        return response()->json($data);
    }
}
