<?php

namespace App\Providers;

use App\Enums\RoleEnum;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * roles
     *
     * @var array
     */
    protected $roles = [];

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

        $this->setRoles();

        foreach ($this->roles as $define) {
            Gate::define($define['name'], function ($user) use ($define) {
                return in_array($user->role_id, $define['roles']);
            });
        }
    }

    /**
     * mappingAdminRole
     *
     * @param  mixed $roles
     * @return array
     */
    protected function mappingAdminRole($roles = []): array
    {
        return array_merge([RoleEnum::ADMIN()->getIndex()], $roles);
    }

    /**
     * setRoles
     *
     * @return void
     */
    protected function setRoles()
    {
        $this->setRoleAdmin();
        $this->setRoleRegister();
        $this->setRoleSampel();
        $this->setRoleEkstraksi();
        $this->setRolePCR();
        $this->setRoleVerifikator();
        $this->setRoleValidator();
        $this->setRoleSwabAntigen();
    }

    /**
     * setRoleAdmin
     *
     * @return void
     */
    protected function setRoleAdmin()
    {
        $this->roles[] = [
            'name' => 'isAdmin',
            'roles' => $this->mappingAdminRole()
        ];
    }

    /**
     * setRoleRegister
     *
     * @return void
     */
    protected function setRoleRegister()
    {
        $this->roles[] = [
            'name' => 'isAdminRegister',
            'roles' => $this->mappingAdminRole([RoleEnum::REGISTER()->getIndex()])
        ];
    }

    /**
     * setRoleSampel
     *
     * @return void
     */
    protected function setRoleSampel()
    {
        $this->roles[] = [
            'name' => 'isAdminSampel',
            'roles' => $this->mappingAdminRole([RoleEnum::SAMPEL()->getIndex()])
        ];
    }

    /**
     * setRoleEkstraksi
     *
     * @return void
     */
    protected function setRoleEkstraksi()
    {
        $this->roles[] = [
            'name' => 'isAdminEkstraksi',
            'roles' => $this->mappingAdminRole([RoleEnum::EKSTRAKSI()->getIndex()])
        ];
    }

    /**
     * setRolePCR
     *
     * @return void
     */
    protected function setRolePCR()
    {
        $this->roles[] = [
            'name' => 'isAdminPCR',
            'roles' => $this->mappingAdminRole([RoleEnum::PCR()->getIndex()])
        ];
    }

    /**
     * setRoleVerifikator
     *
     * @return void
     */
    protected function setRoleVerifikator()
    {
        $this->roles[] = [
            'name' => 'isAdminVerifikator',
            'roles' => $this->mappingAdminRole([RoleEnum::VERIFIKATOR()->getIndex()])
        ];
    }

    /**
     * setRoleValidator
     *
     * @return void
     */
    protected function setRoleValidator()
    {
        $this->roles[] = [
            'name' => 'isAdminValidator',
            'roles' => $this->mappingAdminRole([RoleEnum::VALIDATOR()->getIndex()])
        ];
    }

    /**
     * setRoleSwabAntigen
     *
     * @return void
     */
    protected function setRoleSwabAntigen()
    {
        $this->roles[] = [
            'name' => 'isAdminSwabAntigen',
            'roles' => $this->mappingAdminRole([
                RoleEnum::VALIDATOR()->getIndex(),
                RoleEnum::REGISTER()->getIndex(),
            ])
        ];
    }
}
