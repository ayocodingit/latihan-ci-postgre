<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    const DEFINE_GATE = [
        [
            'name' => 'isAdmin',
            'roles' => [ROLE_ADMIN]
        ],
        [
            'name' => 'isAdminRegister',
            'roles' => [ROLE_REGISTER, ROLE_ADMIN]
        ],
        [
            'name' => 'isAdminSampel',
            'roles' => [ROLE_SAMPEL, ROLE_ADMIN]
        ],
        [
            'name' => 'isAdminEkstraksi',
            'roles' => [ROLE_EKSTRAKSI, ROLE_ADMIN]
        ],
        [
            'name' => 'isAdminPCR',
            'roles' => [ROLE_PCR, ROLE_ADMIN]
        ],
        [
            'name' => 'isAdminVerifikator',
            'roles' => [ROLE_VERIFIKATOR, ROLE_ADMIN]
        ],
        [
            'name' => 'isAdminValidator',
            'roles' => [ROLE_VALIDATOR, ROLE_ADMIN]
        ],
        [
            'name' => 'isAdminSwabAntigen',
            'roles' => [ROLE_VALIDATOR, ROLE_REGISTER, ROLE_ADMIN]
        ]
    ];

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

        foreach (self::DEFINE_GATE as $define) {
            Gate::define($define['name'], function ($user) use ($define) {
                return in_array($user->role_id, $define['roles']);
            });
        }
    }
}
