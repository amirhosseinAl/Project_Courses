<?php

namespace Modules\Student\Providers;

use Illuminate\Support\ServiceProvider;

class StudentServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->loadRoutesFrom(module_path('Student', 'Routes/api.php'));
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'student');
    }
}
