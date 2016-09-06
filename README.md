
Laravel 私有化扩展包：摩交所接口

> 微博：`会勇同学`

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


## License

MIT