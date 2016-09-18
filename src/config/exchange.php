<?php

return [

    'debug'                     => env('GUZZLE_HTTP_DEBUG', false),

    //服务器地址
    'api_host'                  => env('MJS_API_HOST', '120.76.239.26'),

    //服务器端口
    'port'                      => env('MJS_API_PORT', 8090),

    //1. 身份验证码参数：商户号
    'merchant_code'             => 'WP10001',

    //缓存过期时间
    'cache_life_time'           => 7200,

    //非对称加密公钥
    'public_key_file'           => config_path() . '/fixtures/public.pem',

    //非对称加密私钥
    'private_key_file'          => config_path() . '/fixtures/private.pem',

    /**
     * 1. 获取身份验证码
     * 在进行转账、交易等一系列操作时需要对请求数据的身份进行验证，该接口在进行验证前获取会员的身份验证码。
     */
    'get_binding'               => 'api/user/getbinding',

    /**
     * 2. 身份验证
     * 在进行转账、交易等一系列操作时需要对请求数据的身份进行验证，该接口通过身份验证码对身份进行验证
     */
    'validate'                  => 'api/user/validate',

    /**
     * 3. 获取会员信息
     * 通过参数获取会员基本信息资料，该接口必须授权认证通过才会有效（在header中加入Authorization: Bearer *****， 其中*****为授权的token）
     */
    'get_member'                => 'api/member/getmember',

    /**
     * 4. 获取交易记录
     */
    'transfer_record'            => 'api/member/TransferRecord',

    /**
     * 5. 出金
     */
    'withdraw'                  => 'api/trade/withdraw',      

    /**
     * 6. 交易转账
     */
    'transfer'                  => 'api/trade/transfer',
];
