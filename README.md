# hejunjie/error-log

<div align="center">
  <a href="./README.md">English</a>｜<a href="./README.zh-CN.md">简体中文</a>
  <hr width="50%"/>
</div>

An error logging component using the Chain of Responsibility pattern. Supports multiple output channels like local files, remote APIs, and console logs—ideal for flexible and scalable logging strategies.

## Installation

Install via Composer:

```bash
composer require hejunjie/error-log
```

## Usage

```php
<?php

use Hejunjie\ErrorLog\Logger;
use Hejunjie\ErrorLog\Handlers;

$log = new Logger([
    new Handlers\ConsoleHandler(),                // Print to console
    new Handlers\FileHandler('path'),  // Save to file
    new Handlers\RemoteApiHandler('url')       // Send to a specific address
]);

$log->info('title','content',['Context']);     // INFO Level
$log->warning('title','content',['Context']);  // WARNING Level
$log->error('title','content',['Context']);    // ERROR Level

$log->log('level','title','content',['Context']);
```

## Purpose & Original Intent

The origin of this component is actually quite simple:
The code runs on different servers, some are as quiet as retired old men, while others explode into fireworks at the slightest provocation — but they're all running "the same code," and every time something breaks, they come looking for me.

The most absurd part is that everyone claims they’re running the "latest version," but whether it's a code issue, an environment issue, or a deployment issue, who knows?
So, I wrote this little tool: to flexibly output logs to files, consoles, and remote servers, with customizable formats. This way, I can find the problem before I'm questioned.

Later, I also wrote a small log receiving script. Combined with this component, it can directly display remote logs, allowing me to receive, display, filter, and manage log error information.

👉 [oh-shit-logger](https://github.com/zxc7563598/oh-shit-logger)

## 🔧 Additional Toolkits (Can be used independently or installed together)

This project was originally extracted from [hejunjie/tools](https://github.com/zxc7563598/php-tools).
To install all features in one go, feel free to use the all-in-one package:

```bash
composer require hejunjie/tools
```

Alternatively, feel free to install only the modules you need：

[hejunjie/utils](https://github.com/zxc7563598/php-utils) - A lightweight and practical PHP utility library that offers a collection of commonly used helper functions for files, strings, arrays, and HTTP requests—designed to streamline development and support everyday PHP projects.

[hejunjie/cache](https://github.com/zxc7563598/php-cache) - A layered caching system built with the decorator pattern. Supports combining memory, file, local, and remote caches to improve hit rates and simplify cache logic.

[hejunjie/china-division](https://github.com/zxc7563598/php-china-division) - Regularly updated dataset of China's administrative divisions with ID-card address parsing. Distributed via Composer and versioned for use in forms, validation, and address-related features

[hejunjie/error-log](https://github.com/zxc7563598/php-error-log) - An error logging component using the Chain of Responsibility pattern. Supports multiple output channels like local files, remote APIs, and console logs—ideal for flexible and scalable logging strategies.

[hejunjie/mobile-locator](https://github.com/zxc7563598/php-mobile-locator) - A mobile number lookup library based on Chinese carrier rules. Identifies carriers and regions, suitable for registration checks, user profiling, and data archiving.

[hejunjie/address-parser](https://github.com/zxc7563598/php-address-parser) - An intelligent address parser that extracts name, phone number, ID number, region, and detailed address from unstructured text—perfect for e-commerce, logistics, and CRM systems.

[hejunjie/url-signer](https://github.com/zxc7563598/php-url-signer) - A PHP library for generating URLs with encryption and signature protection—useful for secure resource access and tamper-proof links.

[hejunjie/google-authenticator](https://github.com/zxc7563598/php-google-authenticator) - A PHP library for generating and verifying Time-Based One-Time Passwords (TOTP). Compatible with Google Authenticator and similar apps, with features like secret generation, QR code creation, and OTP verification.

[hejunjie/simple-rule-engine](https://github.com/zxc7563598/php-simple-rule-engine) - A lightweight and flexible PHP rule engine supporting complex conditions and dynamic rule execution—ideal for business logic evaluation and data validation.

👀 All packages follow the principles of being lightweight and practical — designed to save you time and effort. They can be used individually or combined flexibly. Feel free to ⭐ star the project or open an issue anytime!

---

This library will continue to be updated with more practical features. Suggestions and feedback are always welcome — I’ll prioritize new functionality based on community input to help improve development efficiency together.
