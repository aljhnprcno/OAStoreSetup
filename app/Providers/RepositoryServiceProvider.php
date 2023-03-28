<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\IDPrinting\IdPrintingInterface;
use App\Repositories\IDPrinting\IdPrintingRepo;
use App\Repositories\IDRequests\IDRequestsRepoInterface;
use App\Repositories\IDRequests\IDRequestsRepo;
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IdPrintingInterface::class, IdPrintingRepo::class);
        $this->app->bind(IDRequestsRepoInterface::class, IDRequestsRepo::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
