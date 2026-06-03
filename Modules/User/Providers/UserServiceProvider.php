<?php

namespace Modules\User\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class UserServiceProvider extends ServiceProvider
{
    protected $moduleName = 'User';
    protected $moduleNameLower = 'user';

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->registerMigrations();
        $this->registerRoutes();
    }

    protected function registerMigrations(): void
    {
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    protected function registerRoutes(): void
    {
        Route::prefix('api/v1')
            ->middleware('api')
            ->namespace('Modules\User\Http\Controllers')
            ->group(module_path($this->moduleName, 'Routes/api.php'));
    }
}
