<?php
namespace App\Support;
use Exception;

class Assertion
{
    public static function notEmpty($value, string $message): void
    {
        if (empty($value)) {
            throw new Exception($message);
        }
    }

    public static function minLength($value, int $min, string $message): void
    {
        if (strlen($value) < $min) {
            throw new Exception($message);
        }
    }

    public static function email($value, string $message): void
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            throw new Exception($message);
        }
    }
}
?>