<?php


namespace App\Repositories;


use App\Jobs\SendEmailExtJob;
use App\Models\MailContent;
use App\Repositories\Contracts\MailRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class MailRepository extends BaseRepository implements MailRepositoryInterface
{
    protected $model;

    /**
     * MailRepository construct
     *
     * @param  mixed $model
     *
     * @return void
     */
    public function __construct(MailContent $model)
    {
        parent::__construct($model);
    }

    public function findMail($id)
    {
        return $this->model->where('id', $id)->where('user_id', Auth::id())->first();
    }

    public function getTemplateList($page, $perPage, $filters, $isFullPage, $is_general_template)
    {
        $queryBuilder = $this->buildSimpleFilterQuery($filters);
        $queryBuilder->when($is_general_template, function($query) use ($is_general_template) {
            return $query->where('is_general_template', $is_general_template);
        });
        // if (Auth::user()->type == config('constant.USER_TYPE_ADMIN')) {
            $queryBuilder->select('id', 'title', 'content', 'mail_name', 'country_id', 'is_general_template')->with('language')->orderBy('id', 'desc');
        // } else {
        //     $queryBuilder->where('user_id', Auth::id())->select('id', 'title', 'content', 'mail_name', 'country_id')->with('country')->orderBy('id', 'desc');
        // }

        if ($isFullPage) {
            $data = $queryBuilder->get();
            return [
                'data' => $data,
                'total' => count($data)
            ];
        }

       return $queryBuilder->paginate($perPage, ['*'], 'page', $page);
    }

    public function sendEmails(Collection $extDomains, $mail_name, $title, $content)
    {
        $user = Auth::user();
        foreach($extDomains as $ext) {
            if (!isset($ext->email) || $ext->email === '') {
                continue;
            }

            $emailsList = explode('|', $ext->email);
            if (count($emailsList) === 0) continue;

            foreach($emailsList as $email) {
                dispatch(new SendEmailExtJob($user, $mail_name, $email, $title, $content, $ext));
            }
        }

        return true;
    }


}