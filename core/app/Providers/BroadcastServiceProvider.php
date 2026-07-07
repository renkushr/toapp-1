<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Broadcast::routes(['middleware' => ['web', 'auth:web']]);
        Broadcast::routes(['prefix' => 'agent', 'middleware' => ['web', 'toapp.agent']]);

        require base_path('routes/channels.php');
    }
}
