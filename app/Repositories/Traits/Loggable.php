<?php

namespace App\Repositories\Traits;

use App\Models\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Trait Loggable
 * @package App\Repositories\Traits
 *
 * Automatically Log Add, Update, Delete events of Model
 * To customize events, declare protected static $recordEvents = ['created'] in Model (add events in the array)
 */
trait Loggable
{
    protected static function bootLoggable()
    {
        foreach (static::getRecordActivityEvents() as $eventName) {
            static::$eventName(function (Model $model) use ($eventName) {
                try {
                    $page = null;
                    $payload = collect([
                        'payload' => $model->getDirty(),
                        'id' => $model->getOriginal('id')
                    ]);

                    if (Request()->server() && isset(Request()->server()['HTTP_REFERER'])) {
                        $path = Request()->server()['HTTP_REFERER'];

                        $page = $path ? (new self)->cleanUrlPath($path) : null;
                    }

                    Log::create([
                        'table' => 'App\\Models\\' . class_basename($model),
                        'action' => static::getActionName($eventName),
                        'user_id' => auth()->check() ? auth()->user()->id : 49,
                        'payload' => json_encode($payload),
                        'page' => $page
                    ]);
                } catch (\Exception $e) {
                    \Log::error($e);
                }
            });
        }
    }

    /**
     * Set the default events to be recorded if the $recordEvents
     * property does not exist on the model.
     *
     * @return array
     */
    protected static function getRecordActivityEvents()
    {
        if (isset(static::$recordEvents)) {
            return static::$recordEvents;
        }

        return [
            'created',
            'updated',
            'deleted',
        ];
    }

    /**
     * Return Suitable action name for Supplied Event
     *
     * @param $event
     * @return string
     */
    protected static function getActionName($event)
    {
        switch (strtolower($event)) {
            case 'created':
                return 'create';
                break;
            case 'updated':
                return 'update';
                break;
            case 'deleted':
                return 'delete';
                break;
            default:
                return 'unknown';
        }
    }

    /**
     * Removes parameters and root path from url
     *
     * @param $path
     * @return false|string|string[]
     */
    private function cleanUrlPath($path) {
        $root = config('app.url');

        $position = strpos($path, "?");

        if ($position !== false) {
            $result = substr($path, 0, $position);
        } else {
            $result = $path;
        }

        return str_replace($root, '', $result);
    }
}
