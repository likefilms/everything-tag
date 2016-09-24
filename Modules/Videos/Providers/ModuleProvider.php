<?php

namespace TypiCMS\Modules\Videos\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use TypiCMS\Modules\Core\Facades\TypiCMS;
use TypiCMS\Modules\Core\Observers\FileObserver;
use TypiCMS\Modules\Core\Observers\SlugObserver;
use TypiCMS\Modules\Core\Services\Cache\LaravelCache;
use TypiCMS\Modules\Videos\Models\Video;
use TypiCMS\Modules\Videos\Models\VideoTranslation;
use TypiCMS\Modules\Videos\Repositories\CacheDecorator;
use TypiCMS\Modules\Videos\Repositories\EloquentVideo;

class ModuleProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'typicms.videos'
        );

        $modules = $this->app['config']['typicms']['modules'];
        $this->app['config']->set('typicms.modules', array_merge(['videos' => ['linkable_to_page']], $modules));

        $this->loadViewsFrom(__DIR__.'/../resources/views/', 'videos');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'videos');

        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/videos'),
        ], 'views');
        $this->publishes([
            __DIR__.'/../database' => base_path('database'),
        ], 'migrations');

        AliasLoader::getInstance()->alias(
            'Videos',
            'TypiCMS\Modules\Videos\Facades\Facade'
        );

        // Observers
        VideoTranslation::observe(new SlugObserver());
        Video::observe(new FileObserver());
    }

    public function register()
    {
        $app = $this->app;

        /*
         * Register route service provider
         */
        $app->register('TypiCMS\Modules\Videos\Providers\RouteServiceProvider');

        /*
         * Sidebar view composer
         */
        $app->view->composer('core::admin._sidebar', 'TypiCMS\Modules\Videos\Composers\SidebarViewComposer');

        /*
         * Add the page in the view.
         */
        $app->view->composer('videos::public.*', function ($view) {
            $view->page = TypiCMS::getPageLinkedToModule('videos');
        });

        $app->bind('TypiCMS\Modules\Videos\Repositories\VideoInterface', function (Application $app) {
            $repository = new EloquentVideo(new Video());
            if (!config('typicms.cache')) {
                return $repository;
            }
            $laravelCache = new LaravelCache($app['cache'], 'videos', 10);

            return new CacheDecorator($repository, $laravelCache);
        });
    }
}
