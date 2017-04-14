<?php
/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2017/4/13
 * Time: 18:21
 */

namespace Wwtg99\JsonRpc\Server;


use Wwtg99\JsonRpc\Exception\JsonRpcError;
use Wwtg99\JsonRpc\Http\JsonRpcRequest;

class RequestFactory
{

    /**
     * @var bool
     */
    protected $throws = false;

    /**
     * RequestFactory constructor.
     *
     * @param bool $throws
     */
    public function __construct($throws = false)
    {
        $this->throws = $throws;
    }

    /**
     * @param $request
     * @return JsonRpcRequest|array|JsonRpcError
     */
    public function parse($request)
    {
        if (!$request) {
            $err = JsonRpcError::getError(-32600);
            return $this->parseError($err);
        }
        if (is_array($request)) {
            $re = $this->parseRequest($request);
        } else {
            $request = json_decode($request, true);
            if (!$request) {
                $err = JsonRpcError::getError(-32700);
                return $this->parseError($err);
            } elseif ($request == []) {
                $err = JsonRpcError::getError(-32600, 'empty request');
                return $this->parseError($err);
            }
            $re = $this->parseRequest($request);
        }
        if ($re instanceof JsonRpcError) {
            return $this->parseError($re);
        }
        return $re;
    }

    /**
     * @param array $request
     * @return JsonRpcError|JsonRpcRequest|array
     */
    private function parseRequest(array $request)
    {
        if (isset($request['jsonrpc']) && isset($request['method'])) {
            //single
            $req = self::parseJsonObject($request);
            if ($req) {
                return $req;
            } else {
                return JsonRpcError::getError(-32600);
            }
        } else {
            //batch
            $batchObj = [];
            foreach ($request as $item) {
                if (!is_array($item)) {
                    return JsonRpcError::getError(-32600, 'invalid request format');
                }
                $req = self::parseJsonObject($item);
                if ($req) {
                    array_push($batchObj, $req);
                } else {
                    $err = JsonRpcError::getError(-32600);
                    array_push($batchObj, $err);
                }
            }
            return $batchObj;
        }
    }

    /**
     * @param JsonRpcError $err
     * @return JsonRpcError
     */
    private function parseError(JsonRpcError $err)
    {
        if ($this->throws) {
            $err->raiseException();
        }
        return $err;
    }

    /**
     * @param array $data
     * @return JsonRpcRequest|null
     */
    private function parseJsonObject($data)
    {
        if (isset($data['jsonrpc']) && $data['jsonrpc'] == '2.0' && isset($data['method'])) {
            $method = $data['method'];
            $params = isset($data['params']) ? $data['params'] : null;
            if ($params && is_string($params)) {
                $params = json_decode($params, true);
            }
            $id = isset($data['id']) ? $data['id'] : null;
            return new JsonRpcRequest($method, $id, $params);
        }
        return null;
    }
}