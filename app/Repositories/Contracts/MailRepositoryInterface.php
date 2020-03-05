<?php


namespace App\Repositories\Contracts;



use Illuminate\Database\Eloquent\Collection;

interface MailRepositoryInterface extends RepositoryInterface
{
    public function getTemplateList($page, $perPage, $filters, $isFullPage);

    public function sendEmails(Collection $extDomains, $mail_name, $title, $content);

    public function findMail($id);
}