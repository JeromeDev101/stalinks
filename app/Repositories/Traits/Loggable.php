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
                    Log::create([
                        'table' => 'App\\Models\\' . class_basename($model),
                        'action' => static::getActionName($eventName),
                        'user_id' => auth()->check() ? auth()->user()->id : 49,
                        'payload' => json_encode($model->getDirty())
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
}
