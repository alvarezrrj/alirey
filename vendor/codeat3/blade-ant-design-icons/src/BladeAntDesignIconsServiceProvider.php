<?php

declare(strict_types=1);

namespace Codeat3\BladeAntDesignIcons;

use BladeUI\Icons\Factory;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

final class BladeAntDesignIconsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerConfig();

        $this->callAfterResolving(Factory::class, function (Factory $factory, Container $container) {
            $config = $container->make('config')->get('blade-ant-design-icons', []);

            $factory->add('ant-design-icons', array_merge(['path' => __DIR__.'/../resources/svg'], $config));
        });
    }

    private function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/blade-ant-design-icons.php', 'blade-ant-design-icons');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/svg' => public_path('vendor/blade-ant-design-icons'),
            ], 'blade-ant-design-icons');

            $this->publishes([
                __DIR__.'/../config/blade-ant-design-icons.php' => $this->app->configPath('blade-ant-design-icons.php'),
            ], 'blade-ant-design-icons-config');
        }
    }
}
