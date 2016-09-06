<?php

namespace Bravist\Exchange;

use Pikirasa\RSA as Rsa;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

class Api
{
    protected $rsa;
    protected $client;
    protected $host;
    protected $port;
    protected $url;

    /**
     * Status code
     * @var integer
     */
    public $statusCode = 200;

    /**
     * Access token expired
     * @var integer
     */
    public $accessTokenExpired = 401;

    /**
     * Settings
     * @var array
     */
    public $settings = [];

    /**
     * Construct
     * @param Rsa        $rsa  Pikirasa\RSA
     * @param string     $host host
     * @param inteter    $port port
     */
    public function __construct(Rsa $rsa, Client $client, array $config)
    {
        $this->rsa = $rsa;
        $this->client = $client;
        $this->config = $config;
    }

    /**
     * URL接口地址
     * @return string
     */
    public function setUrl($uri)
    {
        if ($this->config['port']) {
            $this->url = sprintf('http://%s:%s/%s', $this->config['api_host'], $this->config['port'], $uri);
        } else {
            $this->url = sprintf('http://%s/%s', $this->config['api_host'], $uri);
        }
    }

    /**
     * Get url
     * @return string
     */
    private function getUrl()
    {
        return $this->url;
    }

    /**
     * Get method
     * @param  string $uri        uri
     * @param  array  $parameters parameters
     * @param  array  $header     headers
     * @return mixed
     */
    public function get($uri, array $parameters,  array $header = [])
    {
        $this->setUrl($uri);
        $encode = json_encode($parameters);
        $requestData = [
            'debug' => $this->config['debug'],
            'query' => [
                'Data'  => $encode,
                'Sign'  => $this->rsa->encrypt($encode),
            ],
        ];
        return $this->request('GET', $requestData, $header);
    }

    /**
     * Post request
     * @param  string $uri        http uri
     * @param  array  $parameters [description]
     * @return mixed
     */
    public function post($uri, array $parameters, array $header = [])
    {
        $this->setUrl($uri);
        $encode = json_encode($parameters);
        /**
         * Sending application/x-www-form-urlencoded POST requests
         * requires that you specify the POST fields as an array in the form_params request options
         */
        $postParameters = [
            "form_params"   => [
                'Data'  => $encode,
                'Sign'  => $this->rsa->base64Encrypt($encode),
            ],
        ];
        $requestData = [
            'debug' => $this->config['debug'],
        ];
        $requestData = array_merge($requestData, $postParameters, $header);
        return $this->request('POST', $requestData);
    }

    /**
     * Http request
     * @param  string $method      method
     * @param  array  $requestData request data
     * @return mixed
     */
    public function request($method, $requestData)
    {
        try {
            $response = $this->client->request($method, $this->getUrl(), $requestData);
            return json_decode($response->getBody()->getContents());
        } catch (RequestException $e) {
            // $res = [
            //     'request'   => Psr7\str($e->getRequest()),
            //     'response'  => Psr7\str($e->getResponse()),
            // ];
            // return $res;
            throw $e;
        }
    }

    public function __get($key)
    {
        return $this->settings[$key];
    }

    public function __set($key, $value)
    {
        $this->settings[$key] = $value;
    }


}