<?php

namespace Bendt\Autocms;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

use Bendt\Autocms\Classes\ConfigManager;
use Bendt\Autocms\Classes\LanguageManager;
use Bendt\Autocms\Classes\PageManager;
use Bendt\Autocms\Classes\StoreManager;

use Bendt\Autocms\Facades\Config as Config;
use Bendt\Autocms\Facades\Language as LanguageFacades;
use Bendt\Autocms\Facades\Page as PageFacades;
use Bendt\Autocms\Facades\Store as StoreFacades;
use Bendt\Autocms\routes\Cms as CMSRoute;

use Bendt\Autocms\Middleware\Language;

class CMSServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        Schema::defaultStringLength(191);

        $this->publishes([

            __DIR__.'/config/bendt-cms.php' => config_path('bendt-cms.php'),
            __DIR__.'/Views/backend/cms/config.blade.php' => resource_path('views/backend/cms/config.blade.php'),
            __DIR__.'/Assets' => public_path('static'),
        ], 'views');

        //Load Migrations
        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');

        //Register Middleware
        $this->app['router']->pushMiddlewareToGroup('web',Language::class);

        //Load Views
        $this->loadViewsFrom(__DIR__ . '/Views', 'autocms');

        //Load routes
        //Require Routes if not disabled
        if(!config('bendt-cms.routes_disabled', false)) {
            require __DIR__ . '/routes/autocms.php';
        }
        require __DIR__ . '/helper.php';
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $alias_loader = AliasLoader::getInstance();

        // Bind Page Manager
        App::bind('pageManager', function()
        {
            return new PageManager();
        });
        $alias_loader->alias('CMSPage', PageFacades::class);

        //Bind Language Manager
        App::bind('languageManager', function()
        {
            return new LanguageManager();
        });
        $alias_loader->alias('Language', LanguageFacades::class);

        // Bind Config Class
        App::bind('configManager', function()
        {
            return new ConfigManager();
        });
        $alias_loader->alias('CMSConfig', Config::class);

        // Bind Config Class
        App::bind('storeManager', function()
        {
            return new StoreManager();
        });
        $alias_loader->alias('CMSStore', StoreFacades::class);
    }
}
