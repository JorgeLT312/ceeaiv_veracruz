<?php

namespace App\Providers;

use App\Models\PrimerContacto;
use App\Models\RegistrosEstatales;
use App\Models\Renavi;
use App\Policies\PrimerContactoPolicy;
use App\Policies\RegistrosEstatalesPolicy;
use App\Policies\RenaviPolicy;
// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        PrimerContacto::class => PrimerContactoPolicy::class,
        RegistrosEstatales::class => RegistrosEstatalesPolicy::class,
        Renavi::class => RenaviPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
