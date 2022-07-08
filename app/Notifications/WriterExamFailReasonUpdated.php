<?php

namespace App\Notifications;

use App\Models\WriterExam;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WriterExamFailReasonUpdated extends Notification  implements ShouldQueue
{
    use Queueable;

    protected $exam, $reason;

    /**
     * Create a new notification instance.
     *
     * @param WriterExam $exam
     * @param $reason
     */
    public function __construct(WriterExam $exam, $reason)
    {
        $this->exam = $exam;
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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $attempt = $this->exam->attempt === 1 ? '- 1st Attempt' : '- 2nd Attempt';

        return (new MailMessage)
            ->subject("Writer's Examination Fail Reason Updated " . $attempt)
            ->markdown('writer.writer_exam_updated_reason', ['exam'=>$this->exam, 'reason' => $this->reason]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $attempt = $this->exam->attempt === 1 ? '(1st attempt)' : '(2nd attempt)';

        return [
            'message' => "We've updated the reason of your failed Writer Validation Exam" . $attempt . ". You can check it out on Writer's Validation page."
        ];
    }
}
