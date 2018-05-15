<?php

namespace forestyle\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Relations\Relation;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);

        \View::composer('layout.nav', function($view){
            $user = \Auth::user();
            $view->with('user', $user);
        });

        \View::composer('layout.sidebar', function($view){
            $topics = \forestyle\Topic::all();
            $view->with('topics', $topics);
        });
//        Relation::morphMap([
//            'fans' => \forestyle\Fan::class,
//            'zans' => \forestyle\Zan::class,
//        ]);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
