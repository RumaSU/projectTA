<?php

namespace App\Jobs\Logs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class User implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     * @param array $payload {title, desc, type, action, actor, detail:{label, field, old, new, list:{label, field, old, new} }  }
     */
    public function __construct($id_user, $identifier = null, $title, $desc, $type, $action, $actor = 'user', $payload, $ip, $agent)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
    
    
    
}
