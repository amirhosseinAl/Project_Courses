<?php

namespace Modules\Course\Providers;

use Illuminate\Support\ServiceProvider;

class CourseServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->loadRoutesFrom(module_path('Course', 'Routes/api.php'));
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'course');

    }
}
