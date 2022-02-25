<?php

namespace App\Jobs;

use App\Models\Article;
use App\Models\User;
use App\Notifications\ArticleReminder;
use Carbon\Carbon;
use FontLib\TrueType\Collection;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;

class SendArticleReminderToWriters implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // send reminder for 6h
        $article_reminder_6h = $this->getArticlesInWritingWithNullContent(6);

        $this->sendArticleReminderEmailToWriters($article_reminder_6h, 6);

        // send reminder for 12h
        $article_reminder_12h = $this->getArticlesInWritingWithNullContent(12);

        $this->sendArticleReminderEmailToWriters($article_reminder_12h, 12);

        // send reminder for 24h (auto cancellation)
        $article_reminder_24h = $this->getArticlesInWritingWithNullContent(24);

        $this->sendArticleReminderEmailToWriters($article_reminder_24h, 24);
    }

    /**
     * get articles in writing with null content depending on hours
     * value of hrs is 6, 12, 24
     *
     * @param $hours
     * @return \Illuminate\Support\Collection
     */
    protected function getArticlesInWritingWithNullContent ($hours) {
        $end_hours = [
            6 => 12,
            12 => 24,
        ];

        $columns = [
            'article.id',
            'article.status_writer',
            'article.id_backlink',
            'article.id_writer',
            'article.date_start',
            'article.reminded_via',
            'article.reminded_at',
            'article.content',
            'publisher.topic',
            'users.username as writer',
            'backlinks.status as backlink_status'
        ];

        $articles = Article::select($columns)
            ->leftJoin('backlinks', 'article.id_backlink', '=', 'backlinks.id')
            ->leftJoin('publisher', 'backlinks.publisher_id', '=', 'publisher.id')
            ->leftJoin('users', 'article.id_writer', '=', 'users.id')
            ->where('backlinks.status' ,'!=', 'Pending')
            ->where('status_writer', 'In Writing')
            ->whereNull('content')
            ->whereNotNull('id_writer')
            ->whereNotNull('date_start');

        if ($hours === 24) {
            $articles = $articles->where('date_start', '<=',Carbon::parse('-' . $hours . ' hours'));
        } else {
            $articles = $articles->where('date_start', '<=', Carbon::parse('-' . $hours . ' hours'))
                ->where('date_start', '>=', Carbon::parse('-' . $end_hours[$hours] . ' hours'));
        }

        $articles = $articles->where(function ($query) use ($hours) {
            $query->where('reminded_via', '!=', $hours)
                ->orWhereNull('reminded_via');
        });

        return $articles->get();
    }

    /**
     * send email reminder to writers
     *
     * @param $articles
     * @param $hours
     */
    protected function sendArticleReminderEmailToWriters ($articles, $hours) {
        if ($articles->count()) {
            // notification for writers
            $writer_ids = $articles->pluck('id_writer')->toArray();
            $writers = $this->getWritersViaIds($writer_ids);
            $articles_grouped_by_id_writer = $articles->groupBy('id_writer')->toArray();

            if ($writers->count()) {
                Notification::send($writers, new ArticleReminder($articles_grouped_by_id_writer, $hours));
            }

            // update article reminded columns
            $article_ids = $articles->pluck('id')->toArray();

            $this->updateArticlesRemindedColumns($article_ids, $hours);
        }
    }

    protected function getWritersViaIds ($ids) {
        return User::whereIn('id', $ids)->get();
    }

    protected function updateArticlesRemindedColumns ($article_ids, $hours) {
        $articles = Article::whereIn('id', $article_ids);

        $articles->update([
            'reminded_via' => $hours,
            'reminded_at' => Carbon::now()
        ]);

        if ($hours === 24) {
            $articles->update([
                'id_writer' => null,
                'status_writer' => null,
                'date_start' => null
            ]);
        }
    }
}
