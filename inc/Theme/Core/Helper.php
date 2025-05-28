<?php
namespace AutoRide\Theme\Core;

class Helper {
    public static function createClassAttribute(array $classes): string {
        $classes = array_filter($classes);
        return !empty($classes) ? ' class="' . esc_attr(implode(' ', $classes)) . '"' : '';
    }

    public static function createStyleAttribute(array $styles): string {
        if (empty($styles)) {
            return '';
        }

        $style_string = '';
        foreach ($styles as $property => $value) {
            if (!empty($value)) {
                $style_string .= $property . ': ' . $value . '; ';
            }
        }

        return !empty($style_string) ? ' style="' . esc_attr(trim($style_string)) . '"' : '';
    }

    public static function sanitizeHexColor(string $color): string {
        if (empty($color)) {
            return '';
        }

        // Remove any spaces and # if present
        $color = str_replace(' ', '', $color);
        $color = ltrim($color, '#');

        // Check if it's a valid hex color
        if (ctype_xdigit($color) && (strlen($color) == 6 || strlen($color) == 3)) {
            return '#' . $color;
        }

        return '';
    }

    public static function sanitizeNumber($number, int $min = 0, int $max = 999999): int {
        $number = (int) $number;
        return max($min, min($max, $number));
    }

    public static function isColor($value): bool {
        if (empty($value)) {
            return false;
        }

        // Remove any spaces and # if present
        $value = str_replace(' ', '', $value);
        $value = ltrim($value, '#');

        return ctype_xdigit($value) && (strlen($value) == 6 || strlen($value) == 3);
    }

    public static function isNumber($value, int $min = 0, int $max = 999999): bool {
        if (!is_numeric($value)) {
            return false;
        }

        $number = (int) $value;
        return $number >= $min && $number <= $max;
    }

    public static function isEmpty($value): bool {
        return empty($value) && $value !== '0';
    }

    public static function isNotEmpty($value): bool {
        return !self::isEmpty($value);
    }

    public static function getImageUrl(int $attachment_id, string $size = 'full'): string {
        $image = wp_get_attachment_image_src($attachment_id, $size);
        return $image ? $image[0] : '';
    }

    public static function getImageAlt(int $attachment_id): string {
        return get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
    }

    public static function formatDate($date, string $format = ''): string {
        if (empty($format)) {
            $format = get_option('date_format');
        }
        return date_i18n($format, strtotime($date));
    }

    public static function truncateText(string $text, int $length = 55, string $more = '...'): string {
        if (str_word_count($text, 0) > $length) {
            $words = str_word_count($text, 2);
            $pos = array_keys($words);
            $text = substr($text, 0, $pos[$length]) . $more;
        }
        return $text;
    }

    public static function getArchiveTitle(): string {
        if (is_category()) {
            return single_cat_title('', false);
        } elseif (is_tag()) {
            return single_tag_title('', false);
        } elseif (is_author()) {
            return get_the_author();
        } elseif (is_post_type_archive()) {
            return post_type_archive_title('', false);
        } elseif (is_tax()) {
            return single_term_title('', false);
        }
        return '';
    }
}
