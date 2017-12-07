<?php
/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2017/4/14
 * Time: 13:31
 */

namespace Wwtg99\JsonRpc\Client;


use GuzzleHttp\Client;
use Wwtg99\JsonRpc\Http\JsonRpcRequest;

class JsonRpcClient
{

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $uri = '';

    /**
     * @var string
     */
    protected $returnType = 'json';  //json, string

    /**
     * @var string
     */
    protected $httpMethod = 'post';

    /**
     * @var array
     */
    protected $requests = [];

    /**
     * JsonRpc constructor.
     * @param $uri
     * @param array $config
     */
    public function __construct($uri = '', $config = [])
    {
        if (isset($config['http_method'])) {
            $this->httpMethod = $config['http_method'];
        }
        if (isset($config['return_type'])) {
            $this->returnType = $config['return_type'];
        }
        if (!isset($config['verify'])) {
            $config['verify'] = false;
        }
        if (!isset($config['http_errors'])) {
            $config['http_errors'] = false;
        }
        $this->client = new Client($config);
        $this->uri = $uri;
    }

    /**
     * @param JsonRpcRequest $request
     * @return $this
     */
    public function appendRequest(JsonRpcRequest $request)
    {
        array_push($this->requests, $request);
        return $this;
    }

    /**
     * @param JsonRpcRequest|array $request
     * @return array|null
     */
    public function send($request = null)
    {
        if ($request instanceof JsonRpcRequest) {
            array_push($this->requests, $request);
        } elseif (is_array($request)) {
            $this->requests = array_merge($this->requests, $request);
        }
        return $this->sendRequests();
    }

    /**
     * @param $method
     * @param int $id
     * @param array $params
     * @return array|null
     */
    public function sendOne($method, $id = 1, $params = [])
    {
        $this->requests = new JsonRpcRequest($method, $id, $params);
        return $this->sendRequests();
    }

    /**
     * @param $method
     * @param array $params
     * @return array
     */
    public function notify($method, $params = [])
    {
        $this->requests = new JsonRpcRequest($method, null, $params);
        return $this->sendRequests();
    }

    /**
     * @return array
     */
    public function getContentBody()
    {
        return $this->buildBody();
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     * @return JsonRpcClient
     */
    public function setUri(string $uri)
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * @return array
     */
    public function getRequests()
    {
        return $this->requests;
    }

    /**
     * @param $requests
     * @return JsonRpcClient
     */
    public function setRequests($requests)
    {
        $this->requests = $requests;
        return $this;
    }

    /**
     * @return array|string|null
     */
    protected function sendRequests()
    {
        $body = $this->buildBody();
        if ($this->uri && $body) {
            if (strtolower($this->httpMethod) == 'get') {
                $res = $this->client->get($this->uri, ['query'=>$body]);
            } else {
                $res = $this->client->post($this->uri, ['body' => json_encode($body, JSON_UNESCAPED_UNICODE)]);
            }
            if ($this->returnType == 'json') {
                return \GuzzleHttp\json_decode((string)$res->getBody(), true);
            } else {
                return (string)$res->getBody();
            }
        }
        return null;
    }

    /**
     * @return array
     */
    protected function buildBody()
    {
        $body = [];
        if ($this->requests instanceof JsonRpcRequest) {
            $body = $this->requests->toArray();
        } elseif (is_array($this->requests)) {
            if (count($this->requests) == 1 && ($this->requests[0] instanceof JsonRpcRequest)) {
                $body = $this->requests[0]->toArray();
            } else {
                $body = [];
                foreach ($this->requests as $request) {
                    if ($request instanceof JsonRpcRequest) {
                        array_push($body, $request->toArray());
                    }
                }
            }
        }
        return $body;
    }

}