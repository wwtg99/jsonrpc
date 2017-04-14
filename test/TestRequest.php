<?php

/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2017/4/14
 * Time: 10:44
 */
class TestRequest extends PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        require_once '../vendor/autoload.php';
    }

    public function testRequestFactory()
    {
        $reqf = new \Wwtg99\JsonRpc\Server\RequestFactory();
        $request1 = ['jsonrpc'=>'2.0', 'method'=>'method1', 'params'=>[], 'id'=>1];
        $req1 = $reqf->parse($request1);
        self::assertInstanceOf('\Wwtg99\JsonRpc\Server\JsonRpcRequest', $req1);
        self::assertEquals('method1', $req1->getMethod());
        self::assertEquals(1, $req1->getId());
        $request2 = [];
        $req2 = $reqf->parse($request2);
        self::assertInstanceOf('\Wwtg99\JsonRpc\Exception\JsonRpcError', $req2);
        self::assertEquals(-32600, $req2->getCode());
        $request3 = ['jsonrpc'=>'2.0'];
        $req3 = $reqf->parse($request3);
        self::assertInstanceOf('\Wwtg99\JsonRpc\Exception\JsonRpcError', $req3);
        self::assertEquals(-32600, $req3->getCode());
        $request4 = [['jsonrpc'=>'2.0', 'method'=>'aa', 'params'=>['aa'=>'bb'], 'id'=>1], ['jsonrpc'=>'2.0', 'method'=>'aa', 'params'=>['aa'=>'bb'], 'id'=>2]];
        $req4 = $reqf->parse($request4);
        self::assertEquals(count($request4), count($req4));
        self::assertEquals(1, $req4[0]->getId());
        $request5 = [['jsonrpc'=>'2.0', 'method'=>'aa', 'params'=>['aa'=>'bb'], 'id'=>1], ['jsonrpc'=>'2.0', 'params'=>['aa'=>'bb'], 'id'=>2]];
        $req5 = $reqf->parse($request5);
        self::assertEquals(count($request5), count($req5));
        self::assertInstanceOf('\Wwtg99\JsonRpc\Exception\JsonRpcError', $req5[1]);
        self::assertEquals(-32600, $req5[1]->getCode());
        $request6 = '{"jsonrpc":"2.0]';
        $req6 = $reqf->parse($request6);
        self::assertInstanceOf('\Wwtg99\JsonRpc\Exception\JsonRpcError', $req6);
        self::assertEquals(-32700, $req6->getCode());
        $request7 = '{"jsonrpc":"2.0", "method": "aa", "params": []}';
        $req7 = $reqf->parse($request7);
        self::assertInstanceOf('\Wwtg99\JsonRpc\Server\JsonRpcRequest', $req7);
        self::assertEquals('aa', $req7->getMethod());
        $request8 = '[{"jsonrpc":"2.0", "method": "aa", "params": []}, {"jsonrpc":"2.0", "method": "bb"}]';
        $req8 = $reqf->parse($request8);
        self::assertEquals(2, count($req8));
        self::assertInstanceOf('\Wwtg99\JsonRpc\Server\JsonRpcRequest', $req8[1]);
        self::assertEquals('bb', $req8[1]->getMethod());
    }

    public function testRequestFactoryThrows()
    {
        $reqf = new \Wwtg99\JsonRpc\Server\RequestFactory(true);
        $request1 = ['jsonrpc'=>'2.0', 'method'=>'method1', 'params'=>[], 'id'=>1];
        $req1 = $reqf->parse($request1);
        self::assertInstanceOf('\Wwtg99\JsonRpc\Server\JsonRpcRequest', $req1);
        self::assertEquals('method1', $req1->getMethod());
        self::assertEquals(1, $req1->getId());
        $request2 = [];
        try {
            $req2 = $reqf->parse($request2);
        } catch (\Wwtg99\JsonRpc\Exception\JsonRpcException $e) {
            echo "\ncatch exception 2\n";
            echo $e->getMessage();
        }
        $request3 = ['jsonrpc'=>'2.0'];
        try {
            $req3 = $reqf->parse($request3);
        } catch (\Wwtg99\JsonRpc\Exception\JsonRpcException $e) {
            echo "\ncatch exception 3\n";
            echo $e->getMessage();
        }
        $request4 = [['jsonrpc'=>'2.0', 'method'=>'aa', 'params'=>['aa'=>'bb'], 'id'=>1], ['jsonrpc'=>'2.0', 'params'=>['aa'=>'bb'], 'id'=>2]];
        $req4 = $reqf->parse($request4);
        self::assertEquals(count($request4), count($req4));
        self::assertInstanceOf('\Wwtg99\JsonRpc\Exception\JsonRpcError', $req4[1]);
        self::assertEquals(-32600, $req4[1]->getCode());
        $request5 = '{"jsonrpc":"2.0]';
        try {
            $req5 = $reqf->parse($request5);
        } catch (\Wwtg99\JsonRpc\Exception\JsonRpcException $e) {
            echo "\ncatch exception 5\n";
            echo $e->getMessage();
        }
    }
}
