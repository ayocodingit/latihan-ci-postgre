<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * isAuthorize
     *
     * @var bool
     */
    protected $isAuthorize = true;

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->checkAuthorize();

        if (!$this->isAuthorize) {
            return $this->isAuthorize;
        }
    }

    /**
     * checkAuthorize
     *
     * @return void
     */
    public function checkAuthorize()
    {
        Gate::define('isAdmin', function ($user) {
            $this->isAuthorize = $user->role_id == ROLE_ADMIN;
        });
        Gate::define('isAdminRegister', function ($user) {
            $this->isAuthorize = in_array($user->role_id, [ROLE_REGISTER, ROLE_ADMIN]);
        });
        Gate::define('isAdminSampel', function ($user) {
            $this->isAuthorize = in_array($user->role_id, [ROLE_SAMPEL, ROLE_ADMIN]);
        });
        Gate::define('isAdminEkstraksi', function ($user) {
            $this->isAuthorize = in_array($user->role_id, [ROLE_EKSTRAKSI, ROLE_ADMIN]);
        });
        Gate::define('isAdminPCR', function ($user) {
            $this->isAuthorize = in_array($user->role_id, [ROLE_PCR, ROLE_ADMIN]);
        });
        Gate::define('isAdminVerifikator', function ($user) {
            $this->isAuthorize = in_array($user->role_id, [ROLE_VERIFIKATOR, ROLE_ADMIN]);
        });
        Gate::define('isAdminValidator', function ($user) {
            $this->isAuthorize = in_array($user->role_id, [ROLE_VALIDATOR, ROLE_ADMIN]);
        });
        Gate::define('isAdminSwabAntigen', function ($user) {
            $this->isAuthorize = in_array($user->role_id, [ROLE_VALIDATOR, ROLE_REGISTER, ROLE_ADMIN]);
        });
    }
}
