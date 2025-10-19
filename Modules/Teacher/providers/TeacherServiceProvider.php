<?php

namespace Modules\Teacher\Providers;

use Illuminate\Support\ServiceProvider;

class TeacherServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->loadRoutesFrom(module_path('Teacher', 'Routes/api.php'));
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'teacher');
    }
}
