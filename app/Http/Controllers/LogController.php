<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use App\Repositories\Contracts\LogRepositoryInterface;
use App\Repositories\Contracts\MailLogRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * @param LogRepositoryInterface     $logRepository
     * @param MailLogRepositoryInterface $mailLogRepository
     */
    public function __construct(LogRepositoryInterface $logRepository, MailLogRepositoryInterface $mailLogRepository)
    {
        $this->logRepository     = $logRepository;
        $this->mailLogRepository = $mailLogRepository;
    }

    public function getList(Request $request)
    {
        $input     = $request->all();
        $userEmail = '';
        $perPage   = config('common.paginate.default');
        $filters   = [
            'whereIn' => [],
            'where'   => []
        ];

        if (isset($input['action']) && $input['action'] != '') {
            $filters['where'][] = [
                'action',
                $input['action']
            ];
        }

        if (isset($input['email']) && $input['email'] != '') {
            $userEmail = $input['email'];
        }

        if (isset($input['table']) && $input['table'] != '') {
            $filters['where'][] = [
                'table',
                $input['table']
            ];
        }

        if (isset($input['per_page'])) {
            $perPage = $input['per_page'];
        }

        $data             = $this->logRepository->paginate($perPage, $userEmail, $filters);
        $data['paginate'] = $this->addPaginationRaw($data['paginate']);

        return response()->json($data);
    }

    public function getMailList(Request $request)
    {
        $input     = $request->all();
        $userEmail = '';
        $perPage   = config('common.paginate.default');
        $filters   = [
            'whereIn' => [],
            'where'   => []
        ];

        if (isset($input['action']) && $input['action'] != '') {
            $filters['where'][] = [
                'action',
                $input['action']
            ];
        }

        if (isset($input['email']) && $input['email'] != '') {
            $userEmail = $input['email'];
        }

        if (isset($input['status']) && $input['status'] != '') {
            $filters['where'][] = [
                'status',
                $input['status']
            ];
        }

        if (isset($input['from']) && $input['from'] != '') {
            $filters['where'][] = [
                'from',
                'like',
                '%' . $input['from'] . '%'
            ];
        }

        if (isset($input['to']) && $input['to'] != '') {
            $filters['where'][] = [
                'to',
                'like',
                '%' . $input['to'] . '%'
            ];
        }

        if (isset($input['ext']) && $input['ext'] != '') {
            $filters['where'][] = [
                'ext_domain',
                'like',
                '%' . $input['ext'] . '%'
            ];
        }

        if (isset($input['per_page'])) {
            $perPage = $input['per_page'];
        }

        $data             = $this->mailLogRepository->paginate($perPage, $userEmail, $filters);
        $data['paginate'] = $this->addPaginationRaw($data['paginate']);

        return response()->json($data);
    }

    public function getTables()
    {
        $path = app_path('Models');

        $models = $this->logRepository->getModels($path);

        foreach ($models as $model) {
            $data[] = [
                'id' => $model,
                'value' => with(new $model())->getTable()
            ];
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

    public function getActions()
    {
        $data = [
            [
                'id' => config('constant.ACTION_CREATE'),
                'value' => 'Create'
            ],
            [
                'id' => config('constant.ACTION_UPDATE'),
                'value' => 'Update'
            ],
            [
                'id' => config('constant.ACTION_DELETE'),
                'value' => 'Delete'
            ],
            [
                'id' => config('constant.ACTION_ALEXA'),
                'value' => 'Alexa'
            ]
        ];

        return response()->json($data);
    }

    /**
     * Get last 3 months for filter purposes
     */
    public function getMonths()
    {
        $log = Log::selectRaw('MONTHNAME(created_at) AS month_name, MONTH(created_at) AS month')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        return response()->json($log);
    }

    /**
     * Delete data within selected month
     */
    public function flushMonth($month)
    {
        $dateStart = Carbon::parse('01-' . $month . '-' . date("Y"));
        $dateEnd   = Carbon::parse('31-' . $month . '-' . date("Y"));

        Log::where('created_at', '>', $dateStart)
            ->where('created_at', '<', $dateEnd)
            ->delete();

        return 'OK';
    }
}
