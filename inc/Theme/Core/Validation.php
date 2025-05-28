<?php
namespace AutoRide\Theme\Core;

class Validation {
    public static function isNotEmpty($value): bool {
        return !self::isEmpty($value);
    }

    public static function isEmpty($value): bool {
        if (is_array($value)) {
            return empty($value);
        }
        return ($value === null || $value === '' || $value === false);
    }

    public static function isColor($value): bool {
        if (self::isEmpty($value)) {
            return false;
        }

        // Remove any spaces and # if present
        $value = str_replace(' ', '', $value);
        $value = ltrim($value, '#');

        return ctype_xdigit($value) && (strlen($value) == 6 || strlen($value) == 3);
    }

    public static function isNumber($value, int $min = 0, int $max = PHP_INT_MAX): bool {
        if (!is_numeric($value)) {
            return false;
        }

        $number = (int) $value;
        return $number >= $min && $number <= $max;
    }

    public static function isEmail(string $email): bool {
        return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function isUrl(string $url): bool {
        return (bool) filter_var($url, FILTER_VALIDATE_URL);
    }

    public static function isDate(string $date, string $format = 'Y-m-d'): bool {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    public static function inRange($value, $min, $max): bool {
        if (!is_numeric($value)) {
            return false;
        }
        return $value >= $min && $value <= $max;
    }

    public static function isAlpha(string $value): bool {
        return (bool) preg_match('/^[a-zA-Z]+$/', $value);
    }

    public static function isAlphaNumeric(string $value): bool {
        return (bool) preg_match('/^[a-zA-Z0-9]+$/', $value);
    }

    public static function isSlug(string $value): bool {
        return (bool) preg_match('/^[a-z0-9-]+$/', $value);
    }

    public static function matchesRegex(string $value, string $pattern): bool {
        return (bool) preg_match($pattern, $value);
    }

    public static function isJson(string $string): bool {
        json_decode($string);
        return (json_last_error() === JSON_ERROR_NONE);
    }

    public static function isSerialized($data): bool {
        if (!is_string($data)) {
            return false;
        }
        $data = trim($data);
        if ('N;' === $data) {
            return true;
        }
        if (!preg_match('/^([adObis]):/', $data, $badions)) {
            return false;
        }
        switch ($badions[1]) {
            case 'a':
            case 'O':
            case 's':
                if (preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data)) {
                    return true;
                }
                break;
            case 'b':
            case 'i':
            case 'd':
                if (preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data)) {
                    return true;
                }
                break;
        }
        return false;
    }
}
