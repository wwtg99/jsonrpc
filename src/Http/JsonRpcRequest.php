<?php
/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2017/4/13
 * Time: 18:18
 */

namespace Wwtg99\JsonRpc\Http;


class JsonRpcRequest
{
    /**
     * @var string
     */
    protected $jsonrpc = '2.0';

    /**
     * @var string
     */
    protected $method = '';

    /**
     * @var null|array
     */
    protected $params = null;

    /**
     * @var null|int
     */
    protected $id = null;

    /**
     * JsonRpcRequest constructor.
     * @param $method
     * @param $id
     * @param $params
     */
    public function __construct($method, $id = null, $params = null)
    {
        $this->method = $method;
        $this->id = $id;
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return ['jsonrpc'=>$this->getJsonrpc(), 'method'=>$this->getMethod(), 'params'=>$this->getParams(), 'id'=>$this->getId()];
    }

    /**
     * @param $key
     * @param int|null $position
     * @return bool
     */
    public function hasParam($key, $position = null)
    {
        if ($this->params) {
            if (isset($this->params[$key])) {
                return true;
            } elseif (is_int($position) && isset($this->params[(int)$position])) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $key
     * @param int|null $position
     * @param $default
     * @return mixed
     */
    public function parseParam($key, $position = null, $default = null)
    {
        if ($this->params) {
            if (isset($this->params[$key])) {
                return $this->params[$key];
            } elseif (is_int($position) && isset($this->params[(int)$position])) {
                return $this->params[(int)$position];
            }
        }
        return $default;
    }

    /**
     * @return string
     */
    public function getJsonrpc()
    {
        return $this->jsonrpc;
    }

    /**
     * @param string $jsonrpc
     * @return JsonRpcRequest
     */
    public function setJsonrpc(string $jsonrpc)
    {
        $this->jsonrpc = $jsonrpc;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return JsonRpcRequest
     */
    public function setMethod(string $method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return null|array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param null $params
     * @return JsonRpcRequest
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @return null|int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param null|int $id
     * @return JsonRpcRequest
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
}