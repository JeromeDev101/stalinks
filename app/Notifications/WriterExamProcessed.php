<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WriterExamProcessed extends Notification implements ShouldQueue
{
    use Queueable;

    protected $writerExam, $mode;

    /**
     * Create a new notification instance.
     *
     * @param $writerExam
     * @param $mode
     */
    public function __construct($writerExam, $mode)
    {
        $this->writerExam = $writerExam;
        $this->mode = $mode;
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
        // notify writer that an exam is created
        if ($this->mode === 'setup') {
            return (new MailMessage)
                ->subject("Writer's Examination Created")
                ->markdown('writer.writer_exam_created', ['exam'=>$this->writerExam]);
        } else if ($this->mode === 'approved' || $this->mode === 'disapproved') {
            return (new MailMessage)
                ->subject("Writer's Examination Result")
                ->markdown('writer.writer_exam_result', ['exam'=>$this->writerExam, 'mode' => $this->mode]);
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
            $message = "Our team has created a writer's examination for you.
                        Navigate to the 'Writer's Validation' page now to see the instructions.";
        } else if ($this->mode === 'checking') {
            $message = "Writer " . $this->writerExam->writer->name . " has submitted his exam (Title: "
                        . $this->writerExam->title . ", Anchor Text: " . $this->writerExam->anchor_text . "). Please evaluate it on the
                        'Writer's Validation' page now.";
        } else if ($this->mode === 'approved') {
            $message = "Congratulations! You have passed the writer's examination.
                        You can navigate to the 'Article' page and start accepting and writing articles for our clients.";
        } else if ($this->mode === 'disapproved') {
            $message = "We regret to inform you that you did not pass the writer's examination provided by our team.
                        We will now deactivate your writer's account. We wish you good luck on your future endeavors. Thank you!";
        }

        return [
            'message' => $message
        ];
    }
}
