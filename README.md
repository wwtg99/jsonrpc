# Json-RPC Server and Client

json-rpc 2.0 server and client

## Installation
```
composer require wwtg99/jsonrpc
```

## Usage
### Server Side
1. Add JsonRpcServiceProvider in Laravel app providers
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

