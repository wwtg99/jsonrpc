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

For Lumen or earlier Laravel than v5.5, you need to register the service provider and alias manually,
```
Wwtg99\JsonRpc\Provider\JsonRpcServiceProvider::class
```
```
'JsonRpc' => Wwtg99\JsonRpc\Facades\JsonRpc::class
```

# Usage
## Server Side

### Bind Methods

Bind callback:
```
JsonRpc::bind('method1', function($request) {
    return [1, 2, 3];
});

// Or use handler instance
$ph = resolve('ProcessHandler');
$ph->bind('method1', function($request) {
    return [1, 2, 3];
});
```

Bind class method
```
namespace Test;
class BindingTest {
    public function test1($request) 
    {
        return [1, 2, 3];
    }
}
JsonRpc::bind('method2', 'Test\BindingTest@test1');
// Or $ph->bind('method2', 'Test\BindingTest@test1');
```

### Handle requests

Add route
```
Route::match(['GET', 'POST'], '/json_rpc', function($request) {
    $res = JsonRpc::parse($request);
    //other process
    return response()->json($res);
});
```
Or simply use
```
Route::match(['GET', 'POST'], '/json_rpc', function (Request $request) {
    return Wwtg99\JsonRpc\Provider\JsonRpcRouter::parse($request);
});
```

## Client Side
### Send request in client
```
$cli = new JsonRpcClient();
//build requests
$req1 = new JsonRpcRequest('method1', 1, [1, 2, 3]);
$req1 = new JsonRpcRequest('method1', 2, [1, 2, 3]);
//send one request
$res = $cli->send($req1);
//send batch requests
$res = $cli->appendRequest($req1)->appendRequest($req2)->send();
//send notify
$cli->notify('method2', ['a'=>'b'])
```
