<?php

namespace App\Jobs;

use App\Models\Config;
use App\Models\ExtDomain;
use App\Models\MailLog;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class SendEmailExtJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    //attributes
    private $email = '';
    private $extDomain = '';
    private $user = '';
    private $title = '';
    private $content = '';
    private $mail_name = '';

    public function __construct($user, $mail_name, $email, $title, $content, ExtDomain $extDomain)
    {
        $this->user = $user;
        $this->mail_name = $mail_name;
        $this->title = $title;
        $this->content = $content;
        $this->email = $email;
        $this->extDomain = $extDomain;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mailLog = new MailLog();
        $mailLog->user_id = $this->user->id;
        $mailLog->from = $this->user->work_mail;
        $mailLog->to = $this->email;
        $mailLog->ext_domain = $this->extDomain->domain;
        $mailLog->status = 2;
        $mailLog->title = $this->title;
        $mailLog->content = $this->content;
        $mailLog->save();

        try {
            $transport = (new Swift_SmtpTransport($this->user->host_work_mail, $this->user->port_work_mail, $this->user->security_work_mail))
                ->setUsername($this->user->work_mail)
                ->setPassword($this->user->work_mail_pass)
                ->setStreamOptions(array(
                    'ssl' => array(
                        'allow_self_signed' => true,
                        'verify_peer' => false)));

            $mailer = new Swift_Mailer($transport);
            $emailName = $this->mail_name;

            $message = (new Swift_Message($this->title))
                ->setFrom([$this->user->work_mail => $emailName])
                ->setTo([$this->email])
                ->setBody($this->content);

            $result = $mailer->send($message);
            Log::info('[result = '.$result. '] '. $this->user->name.  '(id='.$this->user->id.') using '. $this->user->work_mail. ' sent ext msg to '. $this->email);

            $mailLog->status = $result;
            $mailLog->save();

            if ($result == 1) {
                $this->extDomain->status = config('constant.EXT_STATUS_CONTACTED');
                $this->extDomain->save();
            }
        } catch (\Exception $e) {
            $mailLog->status = 0;
            $mailLog->save();
            Log::error('send email failed '. $e->getMessage());
        }
    }
}
