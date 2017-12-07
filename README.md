JSON-RPC Server and Client
==========================

JSON-RPC 2.0 server and client, implementation of [JSON-RPC 2.0](http://www.jsonrpc.org/) for Laravel.

# Features
- JSON-RPC 2.0 only
- Support batch requests and notifications
- Simple to use for Laravel
- Require PHP >= 5.6 and GuzzleHttp >= 6.0
- License: MIT

# JSON-RPC specification
- [English](http://www.jsonrpc.org/specification)
- [中文版](http://wiki.geekdream.com/Specification/json-rpc_2.0.html)

# Installation
```
composer require wwtg99/jsonrpc
```

For Lumen or earlier Laravel than v5.5, you need to register the service provider and alias manually,
```php
Wwtg99\JsonRpc\Provider\JsonRpcServiceProvider::class
```
```php
'JsonRpc' => Wwtg99\JsonRpc\Facades\JsonRpc::class
```

# Usage
## Server Side

### Bind Methods

Bind callback:
```php
JsonRpc::bind('method1', function($request) {
    $method = $request->getMethod();
    $params = $request->getParams();
    $p = $request->parseParam('name');  //get param name
    $id = $request->getId();
    
    //some process...
    
    //return result array, request id will be added automatically
    return [1, 2, 3];
    //Or use JsonRpcResponse
    return new JsonRpcResponse($id, [1, 2, 3]);
    //return error
    return new JsonRpcResponse($id, null, ['code'=>1, 'message'=>'error']);
});

// Or use handler instance
$ph = resolve('ProcessHandler');
$ph->bind('method1', function($request) {
    return [1, 2, 3];
});
```

Bind class method
```php
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
```php
//you should disable VerifyCsrfToken middleware if use post method
Route::match(['GET', 'POST'], '/json_rpc', function() {
    $res = JsonRpc::parse(request());
    //other process
    return response()->json($res);
});
```
Or simply use
```php
Route::match(['GET', 'POST'], '/json_rpc', function (\Illuminate\Http\Request $request) {
    return Wwtg99\JsonRpc\Provider\JsonRpcRouter::parse($request);
});
```

## Client Side
### Send request in client

The first parameter is json rpc server url, and second parameter is config options.

#### Options
- http_method: http method to send request, get or post, default post
- return_type: return type for response, json or string, default json

Other options will be sent to [Guzzle client](http://docs.guzzlephp.org/en/stable/request-options.html).

```php
//get client
$cli = new JsonRpcClient($url);  //default method is post, return type json
//use get method
//$cli = new JsonRpcClient($url, ['http_method'=>'get']);
//use raw string return instead of json
//$cli = new JsonRpcClient($url, ['return_type'=>'string']);

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
