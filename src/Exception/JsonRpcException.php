<?php
/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2017/4/13
 * Time: 18:15
 */

namespace Wwtg99\JsonRpc\Exception;


class JsonRpcException extends \Exception
{

    /**
     * JsonRpcException constructor.
     * @param $message
     * @param $code
     */
    public function __construct($message = '', $code = 0)
    {
        parent::__construct($message, $code);
    }
}