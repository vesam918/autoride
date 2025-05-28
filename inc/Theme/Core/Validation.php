<?php
namespace AutoRide\Theme\Core;

class Validation {
    public function isNotEmpty($value): bool {
        return !$this->isEmpty($value);
    }

    public function isEmpty($value): bool {
        if (is_array($value)) {
            return empty($value);
        }
        return ($value === null || $value === '' || $value === false);
    }

    public function isColor($value): bool {
        if ($this->isEmpty($value)) {
            return false;
        }

        // Remove any spaces and # if present
        $value = str_replace(' ', '', $value);
        $value = ltrim($value, '#');

        return ctype_xdigit($value) && (strlen($value) == 6 || strlen($value) == 3);
    }

    public function isNumber($value, int $min = 0, int $max = PHP_INT_MAX): bool {
        if (!is_numeric($value)) {
            return false;
        }

        $number = (int) $value;
        return $number >= $min && $number <= $max;
    }

    public function isEmail(string $email): bool {
        return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function isUrl(string $url): bool {
        return (bool) filter_var($url, FILTER_VALIDATE_URL);
    }

    public function isDate(string $date, string $format = 'Y-m-d'): bool {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    public function inRange($value, $min, $max): bool {
        if (!is_numeric($value)) {
            return false;
        }
        return $value >= $min && $value <= $max;
    }

    public function isAlpha(string $value): bool {
        return (bool) preg_match('/^[a-zA-Z]+$/', $value);
    }

    public function isAlphaNumeric(string $value): bool {
        return (bool) preg_match('/^[a-zA-Z0-9]+$/', $value);
    }

    public function isSlug(string $value): bool {
        return (bool) preg_match('/^[a-z0-9-]+$/', $value);
    }

    public function matchesRegex(string $value, string $pattern): bool {
        return (bool) preg_match($pattern, $value);
    }

    public function isJson(string $string): bool {
        json_decode($string);
        return (json_last_error() === JSON_ERROR_NONE);
    }

    public function isSerialized($data): bool {
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

    public function sanitizeHexColor(string $color): string {
        if ($this->isEmpty($color)) {
            return '';
        }

        // Remove any spaces and # if present
        $color = str_replace(' ', '', $color);
        $color = ltrim($color, '#');

        // Check if it's a valid hex color
        if ($this->isColor($color)) {
            return '#' . $color;
        }

        return '';
    }

    public function sanitizeNumber($number, int $min = 0, int $max = PHP_INT_MAX): int {
        $number = (int) $number;
        return max($min, min($max, $number));
    }
}
