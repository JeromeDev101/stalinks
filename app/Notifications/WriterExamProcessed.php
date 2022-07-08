<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WriterExamProcessed extends Notification implements ShouldQueue
{
    use Queueable;

    protected $writerExam, $mode, $reason;

    /**
     * Create a new notification instance.
     *
     * @param $writerExam
     * @param $mode
     * @param $reason
     */
    public function __construct($writerExam, $mode, $reason = null)
    {
        $this->writerExam = $writerExam;
        $this->mode = $mode;
        $this->reason = $reason;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($this->mode === 'setup' || $this->mode === 'approved' || $this->mode === 'disapproved') {
            return ['mail', 'database'];
        } else if ($this->mode === 'checking') {
            return ['database'];
        }
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $attempt = $this->writerExam->attempt === 1 ? '- 1st Attempt' : '- 2nd Attempt';

        // notify writer that an exam is created
        if ($this->mode === 'setup') {
            return (new MailMessage)
                ->subject("Writer's Examination Created " . $attempt)
                ->markdown('writer.writer_exam_created', ['exam'=>$this->writerExam]);
        } else if ($this->mode === 'approved' || $this->mode === 'disapproved') {
            return (new MailMessage)
                ->subject("Writer's Examination Result " . $attempt)
                ->markdown('writer.writer_exam_result', [
                    'exam'=>$this->writerExam,
                    'mode' => $this->mode,
                    'fail_reason' => $this->reason
                ]);
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $message = '';

        // notify writer that an exam is created
        if ($this->mode === 'setup') {
            $attempt = $this->writerExam->attempt === 1 ? '(1st attempt)' : '(2nd attempt)';

            $message = "Our team has created a writer's examination" . $attempt . " for you.
                        Navigate to the 'Writer's Validation' page now to see the instructions.";
        } else if ($this->mode === 'checking') {
            $attempt = $this->writerExam->attempt === 1 ? '- 1st attempt' : '- 2nd attempt';

            $message = "Writer " . $this->writerExam->writer->name . " has submitted his exam " . $attempt . " (Title: "
                        . $this->writerExam->title . ", Anchor Text: " . $this->writerExam->anchor_text . ").
                        Please evaluate it on the 'Writer's Validation' page now.";
        } else if ($this->mode === 'approved') {
            $message = "Congratulations! You have passed the writer's examination. Please re-login to update your account.
                        You can navigate to the 'Article' page and start accepting and writing articles for our clients.";
        } else if ($this->mode === 'disapproved') {
            if ($this->writerExam->attempt === 1) {
                $message = "We regret to inform you that you did not pass your first attempt for the writer's examination.
                But no worries, you will get a chance to do a second attempt exam after 3 days! We wish you good luck!";
            } else {
                $message = "We regret to inform you that you did not pass the writer's examination (2nd attempt)
                provided by our team. We will now deactivate your writer's account. We wish you good luck on your
                future endeavors. Thank you!";
            }
        }

        return [
            'message' => $message
        ];
    }
}
