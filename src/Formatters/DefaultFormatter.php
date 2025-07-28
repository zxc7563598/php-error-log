<?php

namespace Hejunjie\ErrorLog\Formatters;

use Hejunjie\ErrorLog\Interfaces\LogFormatterInterface;

/**
 * 默认日志格式化器（文本格式）
 * 
 * @package Hejunjie\ErrorLog\Formatters
 */
class DefaultFormatter implements LogFormatterInterface
{
    /**
     * 格式化日志
     * 
     * @param string $level 日志级别
     * @param string $message 日志内容
     * @param array $context 上下文
     * 
     * @return string 
     */
    public function format(string $level, string $message, array $context = []): string
    {
        return sprintf("[%s] %s: %s %s\n", date('Y-m-d H:i:s'), $level, $message, json_encode($context, JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES + JSON_PRESERVE_ZERO_FRACTION));
    }
}
