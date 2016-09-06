<?php

namespace Bravist\Exchange;

use Bravist\Exchange\Api;

class Authorize extends Api
{
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
        return $this->post(config('exchange.get_binding'), $request);
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
        $response = $this->post(config('exchange.validate'), $request);

        if ($response->Code != $this->statusCode) {
            throw new \Exception($response->Message);
        }

        if ($response->Code == $this->accessTokenExpired) {
            return $this->validateAuthenticationCode();
        }

        return $response;
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
        $auth = $this->validateAuthenticationCode();
        $accessToken = json_decode($auth->Data)->access_token;
        $header = [
            'headers'  => [
                'Authorization'    => 'Bearer ' . $accessToken,
            ],
        ];
        return $this->$method($uri, $parameters, $header);

    }


}