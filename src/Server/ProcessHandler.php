<?php
/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2017/4/14
 * Time: 12:12
 */

namespace Wwtg99\JsonRpc\Server;


use Illuminate\Http\Request;
use Wwtg99\JsonRpc\Exception\JsonRpcError;
use Wwtg99\JsonRpc\Http\JsonRpcRequest;
use Wwtg99\JsonRpc\Http\JsonRpcResponse;
use Wwtg99\JsonRpc\Provider\JsonRpcRouter;

class ProcessHandler
{

    /**
     * @var array
     */
    protected $bindings = [];

    /**
     * @var array|JsonRpcResponse
     */
    protected $responses;

    /**
     * @param Request $request
     * @return array
     */
    public function parse(Request $request)
    {
        $fa = new RequestFactory();
        $cont = $request->getContent();
        if ($cont) {
            $req = $fa->parse($cont);
        } else {
            $req = $fa->parse($request->all());
        }
        return $this->execute($req)->getResponseArray();
    }

    /**
     * @param array|JsonRpcRequest $request
     * @return $this
     */
    public function execute($request)
    {
        if ($request instanceof JsonRpcRequest) {
            $this->responses = $this->handleRequest($request);
        } elseif (is_array($request)) {
            $this->responses = [];
            foreach ($request as $item) {
                if ($item instanceof JsonRpcRequest) {
                    $re = $this->handleRequest($item);
                    array_push($this->responses, $re);
                } elseif ($item instanceof JsonRpcError) {
                    array_push($this->responses, new JsonRpcResponse(null, null, $item));
                } else {
                    $err = JsonRpcError::getError(-32600);
                    array_push($this->responses, new JsonRpcResponse(null, null, $err));
                }
            }
        } else {
            $this->responses = new JsonRpcResponse(null, null, JsonRpcError::getError(-32600));
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getResponseArray()
    {
        if ($this->responses) {
            if ($this->responses instanceof JsonRpcResponse) {
                return $this->responses->toArray();
            } elseif (is_array($this->responses)) {
                $res = [];
                foreach ($this->responses as $response) {
                    if ($response instanceof JsonRpcResponse) {
                        array_push($res, $response->toArray());
                    }
                }
                return $res;
            }
        }
        return [];
    }

    /**
     * @return array|JsonRpcResponse
     */
    public function getResponse()
    {
        return $this->responses;
    }

    /**
     * Bind method to callback.
     *
     * @param $method
     * @param $callback
     * @return $this
     */
    public function bind($method, $callback)
    {
        $this->bindings[$method] = $callback;
        return $this;
    }

    /**
     * @param JsonRpcRequest $request
     * @return JsonRpcResponse
     */
    protected function handleRequest(JsonRpcRequest $request)
    {
        if (array_key_exists($request->getMethod(), $this->bindings)) {
            $callback = $this->bindings[$request->getMethod()];
            if (is_callable($callback)) {
                $re = $callback($request);
                if ($re instanceof JsonRpcResponse) {
                    return $re;
                } else {
                    return new JsonRpcResponse($request->getId(), $re);
                }
            } elseif (is_array($callback)) {
                $ins = isset($callback['instance']) ? $callback['instance'] : '';
                $method = isset($callback['method']) ? $callback['method'] : '';
                if ($ins && $method) {
                    $ref = new \ReflectionClass($ins);
                    $md = $ref->getMethod($method);
                    $re = $md->invoke($ins, $request);
                    if ($re instanceof JsonRpcResponse) {
                        return $re;
                    } else {
                        return new JsonRpcResponse($request->getId(), $re);
                    }
                }
            } else {
                if (strpos($callback, '@') !== false) {
                    $c = explode('@', $callback);
                    $ref = new \ReflectionClass($c[0]);
                    if ($ref->hasMethod($c[1])) {
                        $ins = $ref->newInstance();
                        $this->bindings[$request->getMethod()] = ['instance'=>$ins, 'method'=>$c[1]];
                        $method = $ref->getMethod($c[1]);
                        $re = $method->invoke($ins, $request);
                        if ($re instanceof JsonRpcResponse) {
                            return $re;
                        } else {
                            return new JsonRpcResponse($request->getId(), $re);
                        }
                    } else {
                        $this->bindings[$request->getMethod()] = [];
                    }
                }
            }
        }
        return new JsonRpcResponse($request->getId(), null, JsonRpcError::getError(-32601));
    }
}