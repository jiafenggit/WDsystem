<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class WebServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //


    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //

        $this->app->bind('App\Http\Controller\Webs\Web',function($app,$type){

            switch ($type['id']){
                case 1:
                    return new \App\Http\Controllers\Webs\ZhihuWeb();
                    break;
                case 2:
                    return new \App\Http\Controllers\Webs\AiGuPiao();
                    break;
                default:

                    break;

            }


        });

    }
}
