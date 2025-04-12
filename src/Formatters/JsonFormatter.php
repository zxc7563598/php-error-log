<?php

namespace Hejunjie\ErrorLog\Formatters;

use Hejunjie\ErrorLog\Interfaces\LogFormatterInterface;

/**
 * Json格式化器
 * 
 * @package Hejunjie\ErrorLog\Formatters
 */
class JsonFormatter implements LogFormatterInterface
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
        return json_encode([
            'time' => date('Y-m-d H:i:s'),
            'level' => $level,
            'message' => $message,
            'context' => $context
        ], JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES + JSON_PRESERVE_ZERO_FRACTION);
    }
}
