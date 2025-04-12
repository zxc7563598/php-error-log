<?php

namespace Hejunjie\ErrorLog\Interfaces;

/**
 * 日志格式化器接口
 * 
 * @package Hejunjie\ErrorLog
 */
interface LogFormatterInterface
{
    public function format(string $level, string $message, array $context = []): string;
}
