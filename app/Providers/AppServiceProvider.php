<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use App\Services\DropdownService;
use App\Repositories\BranchRepository;
use App\Repositories\ShiftRepository;
use App\Repositories\DivisionRepository;
use App\Repositories\DistrictRepository;
use App\Repositories\UpazilaRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
   public function register()
    {
        $this->app->singleton(DropdownService::class, function ($app) {
            return new DropdownService(   // here, i am getting Undefined type 'App\Services\DropdownService'.?
                $app->make(BranchRepository::class),
                $app->make(ShiftRepository::class),
                $app->make(DivisionRepository::class),
                $app->make(DistrictRepository::class),
                $app->make(UpazilaRepository::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
