<?php
/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2017/4/14
 * Time: 12:03
 */

namespace Wwtg99\JsonRpc\Http;


use Wwtg99\JsonRpc\Exception\JsonRpcError;

class JsonRpcResponse
{
    /**
     * @var string
     */
    protected $jsonrpc = '2.0';

    /**
     * @var array
     */
    protected $result = null;

    /**
     * @var JsonRpcError
     */
    protected $error = null;

    /**
     * @var int
     */
    protected $id = null;

    /**
     * JsonRpcResponse constructor.
     * @param $id
     * @param null|array $result
     * @param null|JsonRpcError $error
     */
    public function __construct($id, $result = null, $error = null)
    {
        $this->result = $result;
        $this->error = $error;
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $res = ['id'=>$this->getId(), 'jsonrpc'=>$this->getJsonrpc()];
        if ($this->getError()) {
            $res['error'] = $this->getError()->toArray();
        } elseif ($this->getResult()) {
            $res['result'] = $this->getResult();
        } else {
            $res['error'] = JsonRpcError::getError(-32603);
        }
        return $res;
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
     * @return JsonRpcResponse
     */
    public function setJsonrpc(string $jsonrpc)
    {
        $this->jsonrpc = $jsonrpc;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param array|null $result
     * @return JsonRpcResponse
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * @return JsonRpcError|null
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param JsonRpcError $error
     * @return JsonRpcResponse
     */
    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return JsonRpcResponse
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
}