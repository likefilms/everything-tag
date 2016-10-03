<?php

namespace TypiCMS\Modules\Videos\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use TypiCMS\Modules\Core\Facades\TypiCMS;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'TypiCMS\Modules\Videos\Http\Controllers';

    /**
     * Define the routes for the application.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function (Router $router) {

            /*
             * Front office routes
             */
            if ($page = TypiCMS::getPageLinkedToModule('videos')) {
                $options = $page->private ? ['middleware' => 'auth'] : [];
                foreach (config('translatable.locales') as $lang) {
                    if ($page->translate($lang)->status && $uri = $page->uri($lang)) {
                        $router->get($uri, $options + ['as' => $lang.'.videos', 'uses' => 'PublicController@index']);
                        $router->get($uri.'/create', $options + ['as' => $lang.'.videos.create', 'uses' => 'PublicController@create']);
                        $router->get($uri.'/{slug}', ['as' => $lang.'.videos.slug', 'uses' => 'PublicController@show']);
                        $router->get($uri.'/{slug}/json_labels', ['as' => $lang.'.videos.json_labels', 'uses' => 'PublicController@json_labels']);
                        $router->get($uri.'/{slug}/oembed', ['as' => $lang.'.videos.oembed', 'uses' => 'PublicController@oembed']);
                        $router->get($uri.'/{slug}/delete', $options + ['as' => $lang.'.videos.delete', 'uses' => 'PublicController@delete']);
                        $router->get($uri.'/{slug}/edit', $options + ['as' => $lang.'.videos.edit', 'uses' => 'PublicController@edit']);
                    }
                }
            }

            /*
             * Admin routes
             */
            $router->get('admin/videos', 'AdminController@index')->name('admin::index-videos');
            $router->get('admin/videos/create', 'AdminController@create')->name('admin::create-video');
            $router->get('admin/videos/{video}/edit', 'AdminController@edit')->name('admin::edit-video');
            $router->post('admin/videos', 'AdminController@store')->name('admin::store-video');
            $router->put('admin/videos/{video}', 'AdminController@update')->name('admin::update-video');

            /*
             * API routes
             */
            $router->get('api/videos', 'ApiController@index')->name('api::index-videos');
            $router->post('api/videos/upload_video', 'ApiController@upload_video')->name('api::upload_video-video');
            $router->post('api/videos/uploadYoutube', 'ApiController@uploadYoutube')->name('api::upload_youtube-video');
            $router->get('api/analytics', 'ApiController@analytics')->name('api::analytics-video');
            $router->put('api/videos/{video}', 'ApiController@update')->name('api::update-video');
            $router->post('api/videos/update/{video}', 'ApiController@update')->name('api::update-video');
            $router->post('api/videos/create/{video}', 'ApiController@store')->name('api::store-video');
            $router->delete('api/videos/{video}', 'ApiController@destroy')->name('api::destroy-video');
            
        });
    }
}
