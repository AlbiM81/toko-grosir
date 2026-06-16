<?php

// app/Providers/AppServiceProvider.php

namespace App\Providers;

use App\Services\MidtransService;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider

{

    public function register(): void

    {

        // Daftarkan MidtransService sebagai singleton

        $this->app->singleton(MidtransService::class, function ($app) {

            return new MidtransService();

        });

    }

    public function boot(): void

    {

        //

    }

}
