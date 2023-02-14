<?php

namespace App\Jobs;

use App\Models\Article;
use App\Models\User;
use App\Notifications\ArticlesOnQueue;
use App\Notifications\ArticlesOnQueueInternal;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;

class SendArticlesQueueEmailToWriters implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $articles;

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
        $this->articles = $this->getArticlesOnQueue();
        $writers = $this->getValidWriters();

        // send notification to external valid writers
        if ($writers) {
            foreach ($writers as $writer) {
                $articlesByLanguage = $this->getArticlesByWriterLanguageId(json_decode($writer->language_id));

                if ($articlesByLanguage) {
                    // send notification to writers
                    $writer->notify(new ArticlesOnQueue($writer, $articlesByLanguage));
                }
            }
        }

        // send notification to internal writers
        $internal_articles = $this->getArticlesOnQueueInternal();
        $internal = $this->getValidInternalWriters();

        if ($internal) {
            if ($internal_articles) {
                Notification::send($internal, new ArticlesOnQueueInternal($internal_articles));
            }
        }
    }

    /**
     * get articles on queue by language id
     *
     * @return array
     */
    protected function getArticlesOnQueue () {

        $columns = [
            'article.id',
            'article.id_language',
            'article.id_backlink',
            'article.id_writer',
            'publisher.topic',
            'publisher.casino_sites',
            'users.username as writer',
            'backlinks.status as backlink_status'
        ];

        return Article::select($columns)
            ->leftJoin('backlinks', 'article.id_backlink', '=', 'backlinks.id')
            ->leftJoin('publisher', 'backlinks.publisher_id', '=', 'publisher.id')
            ->leftJoin('users', 'article.id_writer', '=', 'users.id')
            ->with('language:id,name')
            ->where('backlinks.status' ,'!=', 'Pending')
            ->where('article.is_confirmed', 1)
            ->whereNull('article.status_writer')
            ->whereNotIn('backlinks.status', ['Issue', 'Canceled'])
            ->orderBy('article.id', 'desc')
            ->get()
            ->groupBy('id_language')
            ->toArray();
    }

    /**
     * get valid writers - passed the exam
     *
     * @return array
     */
    protected function getValidWriters () {
        $columns = [
            'users.*',
            'registration.language_id',
            'registration.is_exam_passed'
        ];

        return User::select($columns)
            ->where('role_id', 4)
            ->leftJoin('registration', 'users.email', '=', 'registration.email')
            ->where('users.isOurs', 1)
            ->where('users.status', 'active')
            ->where('registration.account_validation', 'valid')
            ->where('registration.is_exam_passed', 1)
            ->get();
    }

    /**
     * get articles on queue
     *
     * @return array
     */
    protected function getArticlesOnQueueInternal () {

        $columns = [
            'article.id',
            'article.id_language',
            'article.id_backlink',
            'article.id_writer',
            'publisher.topic',
            'publisher.casino_sites',
            'users.username as writer',
            'backlinks.status as backlink_status'
        ];

        return Article::select($columns)
            ->leftJoin('backlinks', 'article.id_backlink', '=', 'backlinks.id')
            ->leftJoin('publisher', 'backlinks.publisher_id', '=', 'publisher.id')
            ->leftJoin('users', 'article.id_writer', '=', 'users.id')
            ->with('language:id,name')
            ->where('backlinks.status' ,'!=', 'Pending')
            ->whereNull('article.status_writer')
            ->whereNotIn('backlinks.status', ['Issue', 'Canceled'])
            ->orderBy('article.id', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * get valid internal writers
     *
     * @return array
     */
    protected function getValidInternalWriters () {
        return User::where('role_id', 13)->where('isOurs', 0)->where('status', 'active')->get();
    }

    /**
     * sort articles by writer language id
     *
     * @param $writer_language_ids
     * @return array
     */
    protected function getArticlesByWriterLanguageId ($writer_language_ids) {
        $articles = [];

        if ($writer_language_ids) {
            foreach ($writer_language_ids as $lang_id) {
                if (array_key_exists($lang_id, $this->articles)) {
                    $articles = array_merge($articles, $this->articles[$lang_id]);
                }
            }
        }

        return $articles;
    }
}
