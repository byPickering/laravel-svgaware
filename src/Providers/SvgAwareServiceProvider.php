<?php

namespace Pickering\SvgAware\Providers;

use Pickering\SvgAware\SvgAwareService;
use Pickering\SvgAware\View\Components\SvgAwareComponent;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class SvgAwareServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SvgAwareService::class, function($app)
        {
            return new SvgAwareService();
        });

        $this->mergeConfigFrom(
        __DIR__.'/../../config/svgaware.php', 'svgaware'
    );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/svgaware.php' => config_path('svgaware.php')
        ], "svgaware-config");

        Blade::directive(config("svgaware.directive"), function ($expression) 
        {
            return (new SvgAwareService())->directiveCall($expression);
        });

        Blade::component(config("svgaware.component"),SvgAwareComponent::class);
    }
}
