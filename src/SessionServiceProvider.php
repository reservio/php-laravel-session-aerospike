<?php

namespace Reservio\Session\Aerospike;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class SessionServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/aerospike.php', 'session.connections.aerospike');

        Session::extend('aerospike', function ($app) {
            $session = $app['config']['session'];
            $aerospike = $session['connections']['aerospike'];
            $client = new \Aerospike($aerospike['servers']);

            return new SessionHandler(
                $client,
                (string)$aerospike['namespace'],
                (string)$aerospike['set'],
                (int)$session['lifetime']
            );
        });
    }

}
