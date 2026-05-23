<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Domain\Dispatch\Contracts\AssignOrderToDriverContract;
use Src\Domain\Dispatch\Contracts\DistanceCalculatorContract;
use Src\Domain\Dispatch\Contracts\FindBestAvailableDriverContract;
use Src\Domain\Dispatch\Services\AssignOrderToDriverService;
use Src\Domain\Dispatch\Services\DistanceCalculatorService;
use Src\Domain\Dispatch\Services\FindBestAvailableDriverService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FindBestAvailableDriverContract::class, FindBestAvailableDriverService::class);
        $this->app->bind(AssignOrderToDriverContract::class, AssignOrderToDriverService::class);
        $this->app->bind(DistanceCalculatorContract::class, DistanceCalculatorService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
