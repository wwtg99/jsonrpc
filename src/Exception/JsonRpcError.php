<?php
/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2017/4/13
 * Time: 18:09
 */

namespace Wwtg99\JsonRpc\Exception;


class JsonRpcError
{
    /**
     * @var int
     */
    protected $code = 0;

    /**
     * @var string
     */
    protected $message = '';

    /**
     * @var array
     */
    protected $data = [];

    /**
     * JsonRpcError constructor.
     * @param int $code
     * @param string $message
     */
    public function __construct($code, $message)
    {
        $this->code = $code;
        $this->message = $message;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     * @return JsonRpcError
     */
    public function setCode(int $code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return JsonRpcError
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return JsonRpcError
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @throws JsonRpcException
     */
    public function raiseException()
    {
        throw new JsonRpcException($this->getMessage(), $this->getCode());
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $d = ['code'=>$this->getCode(), 'message'=>$this->getMessage()];
        if ($this->getData()) {
            $d['data'] = $this->getData();
        }
        return $d;
    }

    /**
     * Define errors here.
     *
     * @param int $code
     * @param string $message
     * @return JsonRpcError
     */
    public static function getError($code, $message = '')
    {
        switch ($code) {
            case -32700: $msg = 'Parse error'; break;
            case -32600: $msg = 'Invalid Request'; break;
            case -32601: $msg = 'Method not found'; break;
            case -32602: $msg = 'Invalid params'; break;
            case -32603: $msg = 'Internal error'; break;
            default: $msg = '';
        }
        if ($message) {
            $msg = $message;
        }
        return new JsonRpcError($code, $msg);
    }
}