<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

//use App\Http\Livewire\Holiday;
use App\Models\Holiday;
use App\Models\User;
use App\Policies\HolidayPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Holiday::class => HolidayPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        // Not necessary anymore since laravel 10
        // $this->registerPolicies();

        Gate::define('admin-only', function(User $user) {
            return $user->isAdmin();
        });
    }
}
