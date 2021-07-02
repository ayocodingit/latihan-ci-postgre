<?php

namespace App\Providers;

use App\Http\Services\GCloudPubSubService;
use App\Http\Services\PelaporanService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        JsonResource::withoutWrapping();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind(GCloudPubSubService::class, function () {
            return new GCloudPubSubService();
        });

        App::bind(PelaporanService::class, function ($app, $item) {
            return new PelaporanService($item);
        });
    }
}
