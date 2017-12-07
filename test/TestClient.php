<?php

/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2017/4/14
 * Time: 14:42
 */
class TestClient extends PHPUnit_Framework_TestCase
{

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        require_once '../vendor/autoload.php';
    }

    public function testRequest()
    {
        $cli = new \Wwtg99\JsonRpc\Client\JsonRpcClient();
        $req1 = new \Wwtg99\JsonRpc\Http\JsonRpcRequest('m1', 1, [1, 2, 3]);
        $b1 = $cli->appendRequest($req1)->getContentBody();
        self::assertEquals(['jsonrpc'=>'2.0', 'method'=>'m1', 'params'=>[1, 2, 3], 'id'=>1], $b1);
        $req2 = new \Wwtg99\JsonRpc\Http\JsonRpcRequest('m2', 2, ['a'=>'b']);
        $b2 = $cli->appendRequest($req2)->getContentBody();
        self::assertEquals([
            ['jsonrpc'=>'2.0', 'method'=>'m1', 'params'=>[1, 2, 3], 'id'=>1],
            ['jsonrpc'=>'2.0', 'method'=>'m2', 'params'=>['a'=>'b'], 'id'=>2],
        ], $b2);
    }
}
