<?php

/*
 * This file is part of Laravel Service Provider.
 *
 * (c) DraperStudio <hello@draperstudio.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DraperStudio\ServiceProvider;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\File;

/**
 * Class ServiceProvider.
 */
abstract class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $packagePath;
    protected $packageName;

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->packagePath = $this->getPackagePath();
        $this->packageName = $this->getPackageName();

        $this->registerAssetPublisher();

        $this->registerConfigPublisher();

        $this->registerViewPublisher();

        $this->registerMigrationPublisher();

        $this->registerSeedPublisher();

        $this->registerTranslationPublisher();

        $this->registerViewLoader();

        $this->registerRouteLoader();

        $this->registerTranslationLoader();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'publisher.asset',
            'publisher.config',
            'publisher.views',
            'publisher.migrations',
            'publisher.seeds',
            'publisher.translations',
            'loader.views',
            'loader.routes',
            'loader.translations',
        ];
    }

    /**
     * Register configuration paths to be published by the publish command.
     */
    protected function publishConfig()
    {
        $this->publishes(
            $this->app['publisher.config']->getFileList($this->packagePath),
            'config'
        );
    }

    /**
     * Register migration paths to be published by the publish command.
     */
    protected function publishMigrations()
    {
        $this->publishes(
            $this->app['publisher.migrations']->getFileList($this->packagePath),
            'migrations'
        );
    }

    /**
     * Register views paths to be published by the publish command.
     */
    protected function publishViews()
    {
        $this->publishes(
            $this->app['publisher.views']->getFileList($this->packagePath),
            'views'
        );
    }

    /**
     * Register assets paths to be published by the publish command.
     */
    protected function publishAssets()
    {
        $this->publishes(
            $this->app['publisher.asset']->getFileList($this->packagePath),
            'assets'
        );
    }

    /**
     * Register seeds paths to be published by the publish command.
     */
    protected function publishSeeds()
    {
        $this->publishes(
            $this->app['publisher.seeds']->getFileList($this->packagePath),
            'seeds'
        );
    }

    /**
     * Register a view file namespace.
     */
    protected function loadViews()
    {
        $this->loadViewsFrom(
            $this->app['loader.views']->getFileList($this->packagePath),
            $this->packageName
        );
    }

    /**
     * Register a translation file namespace.
     */
    protected function loadTranslations()
    {
        $this->loadTranslationsFrom(
            $this->app['loader.translations']->getFileList($this->packagePath),
            $this->packageName
        );
    }

    /**
     * Register a route file namespace.
     */
    protected function loadRoutes()
    {
        if (!$this->app->routesAreCached()) {
            require $this->app['loader.routes']->getFileList($this->packagePath);
        }
    }

    /**
     * Merge the given configuration with the existing configuration.
     */
    protected function mergeConfig()
    {
        $this->mergeConfigFrom(
            $this->packagePath.'/resources/config/'.$this->getFileName($this->packageName),
            $this->packageName
        );
    }

    /**
     * Get the default package path.
     *
     * @return string
     */
    protected function getPackagePath()
    {
        return dirname((new \ReflectionClass($this))->getFileName()).'/..';
    }

    /**
     * Get the default package name.
     *
     * @return string
     */
    abstract protected function getPackageName();

    /**
     * Register the asset publisher service and command.
     */
    protected function registerAssetPublisher()
    {
        $packagePath = $this->packagePath;
        $packageName = $this->packageName;

        $this->app->singleton('publisher.asset', function (Application $app) use ($packagePath, $packageName) {
            $publicPath = $app->publicPath();

            $publisher = new Publisher\AssetPublisher($app->make('files'), $publicPath);

            $publisher->setPackagePath($packagePath);
            $publisher->setPackageName($packageName);

            return $publisher;
        });
    }

    /**
     * Register the configuration publisher class and command.
     */
    protected function registerConfigPublisher()
    {
        $packagePath = $this->packagePath;
        $packageName = $this->packageName;

        $this->app->singleton('publisher.config', function (Application $app) use ($packagePath, $packageName) {
            $path = $app->configPath();

            $publisher = new Publisher\ConfigPublisher($app->make('files'), $path);

            $publisher->setPackagePath($packagePath);
            $publisher->setPackageName($packageName);

            return $publisher;
        });
    }

    /**
     * Register the view publisher class and command.
     */
    protected function registerViewPublisher()
    {
        $packagePath = $this->packagePath;
        $packageName = $this->packageName;

        $this->app->singleton('publisher.views', function (Application $app) use ($packagePath, $packageName) {
            $viewPath = $app->basePath().'/resources/views/vendor';

            $publisher = new Publisher\ViewPublisher($app->make('files'), $viewPath);

            $publisher->setPackagePath($packagePath);
            $publisher->setPackageName($packageName);

            return $publisher;
        });
    }

    /**
     * Register the migration publisher class and command.
     */
    protected function registerMigrationPublisher()
    {
        $packagePath = $this->packagePath;
        $packageName = $this->packageName;

        $this->app->singleton('publisher.migrations', function (Application $app) use ($packagePath, $packageName) {
            $viewPath = $app->databasePath().'/migrations';

            $publisher = new Publisher\MigrationPublisher($app->make('files'), $viewPath);

            $publisher->setPackagePath($packagePath);
            $publisher->setPackageName($packageName);

            return $publisher;
        });
    }

    /**
     * Register the migration publisher class and command.
     */
    protected function registerSeedPublisher()
    {
        $packagePath = $this->packagePath;
        $packageName = $this->packageName;

        $this->app->singleton('publisher.seeds', function (Application $app) use ($packagePath, $packageName) {
            $viewPath = $app->databasePath().'/seeds';

            $publisher = new Publisher\SeedPublisher($app->make('files'), $viewPath);

            $publisher->setPackagePath($packagePath);
            $publisher->setPackageName($packageName);

            return $publisher;
        });
    }

    /**
     * Register the migration publisher class and command.
     */
    protected function registerTranslationPublisher()
    {
        $packagePath = $this->packagePath;
        $packageName = $this->packageName;

        $this->app->singleton('publisher.translations', function (Application $app) use ($packagePath, $packageName) {
            $viewPath = $app->basePath().'/resources/lang/vendor';

            $publisher = new Publisher\TranslationPublisher($app->make('files'), $viewPath);

            $publisher->setPackagePath($packagePath);
            $publisher->setPackageName($packageName);

            return $publisher;
        });
    }

    /**
     * Register the view loader class and command.
     */
    protected function registerViewLoader()
    {
        $packagePath = $this->packagePath;

        $this->app->singleton('loader.views', function (Application $app) use ($packagePath) {
            $publisher = new Loader\ViewLoader($app->make('files'));

            $publisher->setPackagePath($packagePath);

            return $publisher;
        });
    }

    /**
     * Register the view loader class and command.
     */
    protected function registerRouteLoader()
    {
        $packagePath = $this->packagePath;

        $this->app->singleton('loader.routes', function (Application $app) use ($packagePath) {
            $publisher = new Loader\RouteLoader($app->make('files'));

            $publisher->setPackagePath($packagePath);

            return $publisher;
        });
    }

    /**
     * Register the view loader class and command.
     */
    protected function registerTranslationLoader()
    {
        $packagePath = $this->packagePath;

        $this->app->singleton('loader.translations', function (Application $app) use ($packagePath) {
            $publisher = new Loader\TranslationLoader($app->make('files'));

            $publisher->setPackagePath($packagePath);

            return $publisher;
        });
    }

    /**
     * @param $file
     *
     * @return string
     */
    protected function getFileName($file)
    {
        $file = basename($file);

        if (!ends_with($file, '.php')) {
            $file = $file.'.php';
        }

        return $file;
    }
}
