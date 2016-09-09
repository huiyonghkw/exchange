
Laravel 私有化扩展包：摩交所接口

> 微博：`会勇同学`

## 特性

* 使用GuzzleHttp发送HTTP请求
* 利用PHP openssl扩展非对称加密算法完成数据加密传输
* 使用Composer 实现组件的下载与安装


## 安装

* 通过composer，这是推荐的方式，可以使用composer.json 声明依赖，或者运行下面的命令。SDK 包已经放到这里 [`bravist/exchange`](https://github.com/bravist/exchange)

```bash
$ composer require bravist/exchange -vvv
```


## 配置

### Laravel 应用

1. 注册 `ServiceProvider`:

  ```php
  Bravist\Exchange\ExchangeProvider::class,
  ```

2. 创建配置文件：

  ```shell
  php artisan vendor:publish
  ```

3. 请修改应用根目录下的 `config/exchange.php` 中对应的项即可；


4. 在文件中注入依赖，完成接口请求

```php

use Bravist\Exchange\Authorize;

class Aname
{
    protected $exchange;

    public function __construct(Authorize $exchange)
    {
        $this->exchange = $exchange;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->exchange->phone = 186****1103;
        $res = $this->exchange->http(config('exchange.get_member'), ['Phone' => 186****1103]);
        var_dump($res);
    }
}
```

5. 响应结果如下:

```json

{#484
  +"Data": "{"UserID":"2c90855154e083f30154e08a59730003","RealName":"赵兵","Phone":"18628951103","AccountBalance":0.0,"DebitCard":"6217234402000064089","DepositBank":"工商银行","CardholderName":"赵兵","PayPassword":null}"
  +"Code": 200
  +"Message": null
  +"Description": null
  +"Success": true
  +"Sign": "JGfyvwNGG4FyI1MUPjv4q2pj/ZzA4TuLOOrMhrKmSbCTFp65wKl2Fp8jIAfdR0S11NFFM+XJuHAHj5PxlB80BpLBVr4gETsWlwFpxZSqXESVE0jyQ5wgip8JnA/f0fUAjV247p2tPtzAAHbTrwM9ldjsoLtLbnfBBHuXfQkWbj8Di+lxMK5tNzBcyugvqSfT43qDip3FQBsAQInmIlQ42vZiJP1C89gFDsyyx5jkUl0yNP2uyvQUQbhcluHOTHw4R0PLJKrvhi4VwuW8+RKgT3+5UVTeBAc1L7cFe3FOHk00bxzNZ71C1LWIdpW4nlKNvPE6pJzEJSltNmhfcvk1Pw=="
}
```




## License

MIT
