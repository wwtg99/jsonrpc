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
use Wwtg99\JsonRpc\Server\ProcessHandler;

class JsonRpcServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('jsonrpc_handler', function ($app) {
            return new ProcessHandler();
        });
        $this->app->alias('jsonrpc_handler', ProcessHandler::class);
    }
}