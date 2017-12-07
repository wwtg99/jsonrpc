JSON-RPC Server and Client
==========================

JSON-RPC 2.0 server and client, implementation of [JSON-RPC 2.0](http://www.jsonrpc.org/) for Laravel.

# JSON-RPC specification
- [英文版](http://www.jsonrpc.org/specification)
- [中文版](http://wiki.geekdream.com/Specification/json-rpc_2.0.html)

# Installation
```
composer require wwtg99/jsonrpc
```

# Usage
## Server Side
1. Add JsonRpcServiceProvider in Laravel app providers. There is no need to add manually for Laravel 5.5+.
```
Wwtg99\JsonRpc\Provider\JsonRpcServiceProvider::class
```

2. Bind Methods
```
$ph = resolve('ProcessHandler');
//bind function
$ph->bind('m1', function($request) {
    return [1, 2, 3];
});
//bind method
namespace Test;
class BindingTest {
    public function test1($request) 
    {
    }
}
$ph->bind('m2', 'Test\BindingTest@test1');
```

3. Add `use JsonRpcRequestTrait;` in Controller, add codes in your controller
```
$res = $this->parseJsonRpc($request);
return response()->json($res, 200, [], JSON_UNESCAPED_UNICODE);
```

4. Add route
```
Route::match(['GET', 'POST'], '/json_rpc', 'YourController@jsonrpc');
```
Or simply add route without controller
```
Route::match(['GET', 'POST'], '/json_rpc', function (Request $request) {
    return Wwtg99\JsonRpc\Provider\JsonRpcRouter::parse($request);
});
```

### Client Side
1. Send request in client
```
$cli = new JsonRpcClient();
$req1 = new JsonRpcRequest('m1', 1, [1, 2, 3]);
$req1 = new JsonRpcRequest('m1', 2, [1, 2, 3]);
//send one request
$res = $cli->send($req1);
//send batch requests
$res = $cli->appendRequest($req1)->appendRequest($req2)->send();
//send notify
$cli->notify('m2', ['a'=>'b'])
```

