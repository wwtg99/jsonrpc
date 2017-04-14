<?php
/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2017/4/14
 * Time: 16:54
 */

namespace Wwtg99\JsonRpc\Provider;


use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Routing\Route;
use Wwtg99\JsonRpc\Server\ProcessHandler;
use Wwtg99\JsonRpc\Server\RequestFactory;

class JsonRpcServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('ProcessHandler', function ($app) {
            return new ProcessHandler();
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}