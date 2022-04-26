<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateMailTemplateRequest;
use App\Models\MailContent;
use App\Models\User;
use App\Repositories\Contracts\MailRepositoryInterface;
use App\Repositories\Contracts\ExtDomainRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class MailController extends Controller
{
    /**
     * @var MailRepositoryInterface
     */
    private $mailRepository;
    /**
     * @var ExtDomainRepositoryInterface
     */
    private $extDomainRepository;

    /**
     * MailController constructor.
     *
     * @param MailRepositoryInterface $mailRepository
     * @param ExtDomainRepositoryInterface $extDomainRepository
     */
    public function __construct(MailRepositoryInterface $mailRepository, ExtDomainRepositoryInterface $extDomainRepository)
    {
        $this->mailRepository = $mailRepository;
        $this->extDomainRepository = $extDomainRepository;
    }

    public function getList(Request $request) {

        $input = $request->all();
        $countryId = 0;
        $page = 0;
        $is_general_template = null;
        $isFullPage = false;
        $perPage = config('common.paginate.default');
        $filters = [
            'whereIn' => [],
            'where' => []
        ];

        if (isset($input['is_general_template'])) {
            $is_general_template = $input['is_general_template'];
        }

        if (isset($input['page'])) {
            $page = $input['page'];
        }

        if (isset($input['per_page'])) {
            $perPage = $input['per_page'];
        }

        if (isset($input['full_page'])) {
            $isFullPage = $input['full_page'];
        }

        if (isset($input['title']) && $input['title'] != '') {
            $filters['where'][] = ['title', 'like', '%'.$input['title'].'%'];
        }

        if (isset($input['mail_name']) && $input['mail_name'] != '') {
            $filters['where'][] = ['mail_name', 'like', '%'.$input['mail_name'].'%'];
        }

        if (isset($input['content']) && $input['content'] != '') {
            $filters['where'][] = ['content', 'like', '%'.$input['content'].'%'];
        }

        if (isset($input['country_id']) && $input['country_id'] != 0) {
            $filters['where'][] = ['country_id', '=', $input['country_id']];
        }

        if (isset($input['category']) && $input['category'] != '') {
            if ($input['category'] === 'none') {
                $filters['where'][] = ['category', '=', null];
            } else {
                $filters['where'][] = ['category', '=', $input['category']];
            }
        }

        if (isset($input['type']) && $input['type'] != '') {
            if ($input['type'] === 'none') {
                $filters['where'][] = ['type', '=', null];
            } else {
                $filters['where'][] = ['type', '=', $input['type']];
            }
        }

        $data = $this->mailRepository->getTemplateList($page, $perPage, $filters, $isFullPage, $is_general_template);

        return response()->json($this->addPaginationRaw($data));
    }

    public function store(Request $request) {
        $input = $request->only(['title', 'content', 'mail_name', 'country_id', 'category', 'type']);
        $input['is_general_template'] = $request->is_general_template ? 1:0;

        Validator::make($input, [
            'title' => 'required|unique:mail_contents|max:255',
            'content' => 'required',
            'mail_name' => 'required',
            'country_id' => 'required|not_in:0',
        ])->validate();

        $input['user_id'] = Auth::id();
        $newTemplate = $this->mailRepository->create($input);

        return response()->json(['success' => true, 'data' => $newTemplate]);
    }

    public function edit(UpdateMailTemplateRequest $request) {
        $response = ['success' => false];
        $input = $request->only('id', 'title', 'content', 'mail_name', 'country_id', 'type', 'category');

        $item = $this->mailRepository->findById($input['id']);
        if (!$item) {
            return response()->json($response);
        }

        $this->mailRepository->update($item, $input);
        $response['success'] = true;
        return response()->json($response);
    }

    public function delete(Request $request) {
        $response = ['success' => false];
        $content = MailContent::find($request->id);

        if ($content) {
            $content->delete();
            $response['success'] = true;
        }

        return response()->json($response);
    }

    public function sendEmails(Request $request) {
        $input = $request->all();

        Validator::make($input, [
            'ids' => 'required',
            'title' => 'required',
            'mail_name' => 'required',
            'content' => 'required',
        ])->validate();

        $listExt = explode(",", $input['ids']);
        $sizeExt = count($listExt);
        $user = User::find(Auth::id());

        if (!$user->isSetupWorkMail()) {
            return response()->json([
                'success' => false
            ]);
        }

        if ($sizeExt=== 0 || $sizeExt > 150) {
            return response()->json([
                'success' => false
            ]);
        }

        $extDomains = $this->extDomainRepository->findByIds($listExt);

        $result = $this->mailRepository->sendEmails($extDomains, $input['mail_name'], $input['title'], $input['content']);
        return response()->json(['success' => $result]);
    }

}
