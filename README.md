# hejunjie/error-log

不同框架通常自带日志系统，但要么强绑定到框架，更换框架就要重构日志方案，要么像 Monolog 这类强大的日志系统功能过于庞大。而为了在不同框架中保持通用性，同时避免过度复杂，我基于责任链模式实现了一个轻量级的日志模块

## 安装方式

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

## 🔧 更多工具包（可独立使用，也可统一安装）

本项目最初是从 [hejunjie/tools](https://github.com/zxc7563598/php-tools) 拆分而来，如果你想一次性安装所有功能组件，也可以使用统一包：

```bash
composer require hejunjie/tools
```

当然你也可以按需选择安装以下功能模块：

[hejunjie/cache](https://github.com/zxc7563598/php-cache) - 多层缓存系统，基于装饰器模式。

[hejunjie/china-division](https://github.com/zxc7563598/php-china-division) - 中国省市区划分数据包。

[hejunjie/mobile-locator](https://github.com/zxc7563598/php-mobile-locator) - 国内手机号归属地 & 运营商识别。

[hejunjie/utils](https://github.com/zxc7563598/php-utils) - 常用工具方法集合。

[hejunjie/address-parser](https://github.com/zxc7563598/php-address-parser) - 收货地址智能解析工具，支持从非结构化文本中提取用户/地址信息。

[hejunjie/url-signer](https://github.com/zxc7563598/php-url-signer) - URL 签名工具，支持对 URL 进行签名和验证。

👀 所有包都遵循「轻量实用、解放双手」的原则，能单独用，也能组合用，自由度高，欢迎 star 🌟 或提 issue。

---

该库后续将持续更新，添加更多实用功能。欢迎大家提供建议和反馈，我会根据大家的意见实现新的功能，共同提升开发效率。








