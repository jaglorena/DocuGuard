<?php
namespace App\Providers;

use App\Repositories\Eloquent\DocumentoRepository;
use App\Repositories\Eloquent\PermisoRepository;
use App\Repositories\Eloquent\UsuarioRepository;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(RepositoryInterface::class, DocumentoRepository::class);
        $this->app->bind(RepositoryInterface::class, PermisoRepository::class);
        $this->app->bind(RepositoryInterface::class, UsuarioRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
