<?php

namespace Bravist\Exchange;

use Bravist\Exchange\Api;

class Authorize extends Api
{

    protected $authCacheKey = '!@#$%^&*()';

    /**
     * 1. 获取身份验证码
     * @return mixed
     */
    private function getAuthenticationCode()
    {
        $request = [
            'MerchantCode'  => $this->config['merchant_code'],
            'Phone'         => $this->settings['phone'],
        ];
        return $this->post($this->config['get_binding'], $request);
    }

    /**
     * 2. 验证身份验证码
     * @return mixed
     */
    private function validateAuthenticationCode()
    {
        $auth = $this->getAuthenticationCode();
        $request = [
            'ValidateCode'      => $auth->Data,
            'LoginName'         => $this->settings['phone'],
        ];
        $response = $this->post($this->config['validate'], $request);

        if ($response->Code != $this->statusCode) {
            throw new \Exception($response->Message);
        }

        if ($response->Code == $this->accessTokenExpired) {
            return $this->validateAuthenticationCode();
        }

        return $response;
    }

    /**
     * Set auth cache key
     */
    private function getAuthCacheKey()
    {
        return md5($this->settings['phone'] . $this->authCacheKey);
    }

    /**
     * Send http request
     * @param string $uri        http url uri
     * @param array  $parameters request parameters
     * @param string $method     http request method
     * @return json object
     */
    public function http($uri, array $parameters, $method = 'post')
    {
        $key = $this->getAuthCacheKey();

        if ($this->cache->has($key)) {
            $auth = $this->cache->get($key);
        } else {
            $auth = $this->validateAuthenticationCode();
            $this->cache->add($key, $auth, $this->config['cache_life_time']);
        }
        $accessToken = json_decode($auth->Data)->access_token;
        $header = [
            'headers'  => [
                'Authorization'    => 'Bearer ' . $accessToken,
            ],
        ];
        return $this->$method($uri, $parameters, $header);

    }


}
