<?php

namespace Modules\User\Providers;

use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->loadRoutesFrom(module_path('User', 'Routes/api.php'));
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'user');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'user');
    }
}
