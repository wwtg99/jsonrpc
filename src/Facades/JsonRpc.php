<?php
/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2017/12/7
 * Time: 10:14
 */

namespace Wwtg99\Facades;


use Illuminate\Support\Facades\Facade;

class JsonRpc extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'jsonrpc_handler';
    }

}