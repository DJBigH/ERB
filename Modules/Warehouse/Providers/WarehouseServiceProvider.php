<?php

namespace Modules\Warehouse\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class WarehouseServiceProvider extends ServiceProvider
{
    protected $moduleName = 'Warehouse';
    protected $moduleNameLower = 'warehouse';

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
            ->namespace('Modules\Warehouse\Http\Controllers')
            ->group(module_path($this->moduleName, 'Routes/api.php'));
    }
}
