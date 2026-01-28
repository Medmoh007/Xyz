<?php
namespace App\Lib;

class Mailer {
    public static function send(string $to, string $subject, string $msg): void {
        Logger::log("MAIL to:$to | $subject | $msg");
    }
}
