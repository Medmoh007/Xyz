<?php
namespace App\Lib;

class Logger {
    public static function log(string $message): void {
        file_put_contents(
            __DIR__ . '/../../logs/app.log',
            date('[Y-m-d H:i:s] ') . $message . PHP_EOL,
            FILE_APPEND
        );
    }
}
