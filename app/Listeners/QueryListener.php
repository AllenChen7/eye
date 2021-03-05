<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Log;

class QueryListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  QueryExecuted  $event
     * @return void
     */
    public function handle(QueryExecuted $event)
    {
        if (env('APP_DEBUG') == true) {
            if($event->sql){
                // 把sql  的日志独立分开
                $sql = str_replace("?", "'%s'", $event->sql);
                $log = vsprintf($sql, $event->bindings);

                Log::info($log);
            }
        }
    }
}
