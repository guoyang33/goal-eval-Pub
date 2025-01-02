<?php
namespace Cyouliao\Goaleval;

class Log {

    const VERBOSE_KEY = 'verbose';
    const VERBOSE_ON = 'on';
    const VERBOSE_OFF = 'off';

    public static function switchVerboseMode(): void {
        if (key_exists(self::VERBOSE_KEY, $_COOKIE)) {
            if ($_COOKIE[self::VERBOSE_KEY] == self::VERBOSE_ON) {
                setcookie(self::VERBOSE_KEY, self::VERBOSE_OFF);

            } else {
                setcookie(self::VERBOSE_KEY, self::VERBOSE_ON);

            }
        } else {
            setcookie(self::VERBOSE_KEY, self::VERBOSE_ON);

        }
    }

    public static function d(string $message=null, bool $isHtml=true, bool $isOnServer=true) {
        if (self::isVerboseMode()) {
            echo '<p>';
            echo $message;
            echo '</p>';
        }
    }

    public static function greeting(bool $isHtml=true, bool $isOnServer=true): void {
        $file = __FILE__;
        self::d("$file: Log is on." , $isHtml, $isOnServer);
    }

    public static function enableFileLog(): void {
        // 設定錯誤日誌檔案的位置
        ini_set('log_errors', '1'); // 啟用錯誤日誌
        ini_set('error_log', __DIR__ . '/exec_error.log'); // 指定錯誤日誌檔案
    }

    public static function isVerboseMode(): bool {
        if (key_exists(self::VERBOSE_KEY, $_COOKIE)) {
            return $_COOKIE[self::VERBOSE_KEY] == self::VERBOSE_ON;
        }

        return false;
    }
}