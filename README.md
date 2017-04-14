# Json-RPC Server and Client

json-rpc 2.0 server and client

## Installation
```
composer require wwtg99/jsonrpc
```

## Usage
### Server Side
1. Bind Method
```
$ph = new ProcessHandler();
//bind function
$ph->bind('m1', function($request) {
    return [1, 2, 3];
});
//bind method
$ph->bind('m2', 'Test\BindingTest@test1');
```

2. Use Laravel or other framework to build web service
Register in laravel 
```
$this->app->singleton('ProcessHandler', function ($app) {
    return new ProcessHandler();
});
//bind methods
```
In the Controller
```
$req = RequestFactory::parse(request()->getContent());
$handler = resolve('ProcessHandler');
$res = $handler->execute($req)->getResponseArray();
return response()->json($res, 200, [], JSON_UNESCAPED_UNICODE);
```

3. Send request in client
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

