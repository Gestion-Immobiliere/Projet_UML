<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Liste des policies associées aux modèles (laissez vide si vous n'en utilisez pas).
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Enregistrement des policies et autres bootstraps liés à l’authentification.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
