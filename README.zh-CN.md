# hejunjie/error-log

<div align="center">
  <a href="./README.md">English</a>｜<a href="./README.zh-CN.md">简体中文</a>
  <hr width="50%"/>
</div>

基于责任链模式的错误日志处理组件，支持多通道日志处理（如本地文件、远程 API、控制台输出），适用于复杂日志策略场景。

**本项目已经经由 Zread 解析完成，如果需要快速了解项目，可以点击次数进行查看：[了解本项目](https://zread.ai/zxc7563598/php-error-log)**

## 安装方式

使用 Composer 安装：

```bash
composer require hejunjie/error-log
```

## 使用方式

```php
<?php

use Hejunjie\ErrorLog\Logger;
use Hejunjie\ErrorLog\Handlers;

$log = new Logger([
    new Handlers\ConsoleHandler(),                // 打印到控制台
    new Handlers\FileHandler('日志存储文件夹路径'),  // 存储到文件
    new Handlers\RemoteApiHandler('请求url')       // 发送到某个地址
]);

$log->info('标题','内容',['上下文']);     // INFO 级
$log->warning('标题','内容',['上下文']);  // WARNING 级
$log->error('标题','内容',['上下文']);    // ERROR 级

$log->log('自定义级别','标题','内容',['上下文']);
```

## 用途 & 初衷

这个组件的起因其实很简单：
代码跑在不同的服务器上，有的安静得像退休老头，有的动不动炸成烟花——但它们都在跑“同一份代码”，每次炸了还都来找我。

最离谱的是，每个人都说是“最新版”，但到底是代码问题、环境问题、部署问题，谁知道？
于是我写了这个小东西：让日志可以灵活地 输出到文件、控制台、远程服务器，最好还能自定义格式，让我在被质问之前，先找到锅。

后来还写了个日志接收小脚本，配合这个组件可以把远程日志直接展示出来，用来接收、展示、筛选、管理日志错误信息：

👉 [oh-shit-logger](https://github.com/zxc7563598/oh-shit-logger)

> 对应完整的自定义错误处理（来自webman）

```php
<?php

namespace app\exception;

use Carbon\Carbon;
use Throwable;
use Webman\Exception\ExceptionHandler;
use Webman\Http\Request;
use Webman\Http\Response;
use support\exception\BusinessException;
use Hejunjie\ErrorLog\Logger;
use Hejunjie\ErrorLog\Handlers;

/**
 * Class Handler
 * @package support\exception
 */
class Handler extends ExceptionHandler
{
    public $dontReport = [
        BusinessException::class,
    ];

    public function report(Throwable $exception)
    {
        parent::report($exception);
        if ($this->shouldntReport($exception)) {
            return;
        }
        $request = request();
        $date = Carbon::now()->timezone(config('app')['default_timezone'])->format('Y-m-d');
        (new Logger([
            new Handlers\FileHandler(runtime_path("logs/{$date}/critical")),
            new Handlers\RemoteApiHandler(config('app')['log_report_url'])
        ]))->error(get_class($exception), $exception->getMessage(), [
            'project' => config('app')['app_name'],
            'ip' => $request->getRealIp(),
            'method' => $request->method(),
            'full_url' => $request->fullUrl(),
            'trace' => $this->getDebugData($exception)
        ]);
    }

    public function render(Request $request, Throwable $exception): Response
    {
        $isDebug = config('app')['debug'] == 1;
        $statusCode = $this->getHttpStatusCode($exception);
        $response = [
            'code' => $this->getErrorCode($exception),
            'message' => $isDebug ? $exception->getMessage() : 'Server Error',
            'data' => $isDebug ? $this->getDebugData($exception) : new \stdClass()
        ];
        if ($requestId = $request->header('X-Request-ID')) {
            $response['request_id'] = $requestId;
        }
        return new Response(
            $statusCode,
            ['Content-Type' => 'application/json'],
            json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }

    protected function getHttpStatusCode(Throwable $exception): int
    {
        $code = $exception->getCode();
        return ($code >= 100 && $code < 600) ? $code : 500;
    }

    protected function getErrorCode(Throwable $exception): int
    {
        return $exception->getCode() ?: 500;
    }

    protected function getDebugData(Throwable $exception): array
    {
        $trace = $exception->getTrace();
        $simplifiedTrace = array_map(function ($frame) {
            return [
                'file' => $frame['file'] ?? '[internal function]',
                'line' => $frame['line'] ?? 0,
                'function' => $frame['function'] ?? null,
                'class' => $frame['class'] ?? null,
                'type' => $frame['type'] ?? null
            ];
        }, $trace);
        return [
            'class' => get_class($exception),
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => config('app')['debug'] == 1 ? $simplifiedTrace : array_slice($simplifiedTrace, 0, 5),
            'previous' => $exception->getPrevious() ? [
                'class' => get_class($exception->getPrevious()),
                'message' => $exception->getPrevious()->getMessage()
            ] : null
        ];
    }
}

```

## 🔧 更多工具包（可独立使用，也可统一安装）

本项目最初是从 [hejunjie/tools](https://github.com/zxc7563598/php-tools) 拆分而来，如果你想一次性安装所有功能组件，也可以使用统一包：

```bash
composer require hejunjie/tools
```

当然你也可以按需选择安装以下功能模块：

[hejunjie/utils](https://github.com/zxc7563598/php-utils) - 一个零碎但实用的 PHP 工具函数集合库。包含文件、字符串、数组、网络请求等常用函数的工具类集合，提升开发效率，适用于日常 PHP 项目辅助功能。

[hejunjie/cache](https://github.com/zxc7563598/php-cache) - 基于装饰器模式实现的多层缓存系统，支持内存、文件、本地与远程缓存组合，提升缓存命中率，简化缓存管理逻辑。

[hejunjie/china-division](https://github.com/zxc7563598/php-china-division) - 定期更新，全国最新省市区划分数据，身份证号码解析地址，支持 Composer 安装与版本控制，适用于表单选项、数据校验、地址解析等场景。

[hejunjie/error-log](https://github.com/zxc7563598/php-error-log) - 基于责任链模式的错误日志处理组件，支持多通道日志处理（如本地文件、远程 API、控制台输出），适用于复杂日志策略场景。

[hejunjie/mobile-locator](https://github.com/zxc7563598/php-mobile-locator) - 基于国内号段规则的手机号码归属地查询库，支持运营商识别与地区定位，适用于注册验证、用户画像、数据归档等场景。

[hejunjie/address-parser](https://github.com/zxc7563598/php-address-parser) - 收货地址智能解析工具，支持从非结构化文本中提取姓名、手机号、身份证号、省市区、详细地址等字段，适用于电商、物流、CRM 等系统。

[hejunjie/url-signer](https://github.com/zxc7563598/php-url-signer) - 用于生成带签名和加密保护的URL链接的PHP工具包，适用于需要保护资源访问的场景

[hejunjie/google-authenticator](https://github.com/zxc7563598/php-google-authenticator) - 一个用于生成和验证时间基础一次性密码（TOTP）的 PHP 包，支持 Google Authenticator 及类似应用。功能包括密钥生成、二维码创建和 OTP 验证。

[hejunjie/simple-rule-engine](https://github.com/zxc7563598/php-simple-rule-engine) - 一个轻量、易用的 PHP 规则引擎，支持多条件组合、动态规则执行，适合业务规则判断、数据校验等场景。

👀 所有包都遵循「轻量实用、解放双手」的原则，能单独用，也能组合用，自由度高，欢迎 star 🌟 或提 issue。

---

该库后续将持续更新，添加更多实用功能。欢迎大家提供建议和反馈，我会根据大家的意见实现新的功能，共同提升开发效率。
