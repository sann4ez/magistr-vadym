<?php

if (! function_exists('array_search_assoc')) {
    /**
     * Знайти асоціативний елемент в асоц. масиві.
     *
     * @param array $needle
     * @param array $haystack
     * @param $returnItem
     * @return false|int|mixed|string
     */
    function array_search_assoc(array $needle, array $haystack, $returnItem = false)
    {
        $keys = array_keys($needle);
        foreach ($haystack as $n => $item) {
            $break = false;
            foreach ($keys as $key) {
                if ($item[$key] !== $needle[$key]) {
                    $break = true;
                    break;
                }
            }
            if (!$break) {
                return $returnItem ? $item : $n;
            }
        }

        return false;
    }
}

if (! function_exists('array_unset_value')) {
    /**
     * Видалити елемент масиву по значенню.
     *
     * @param array $array
     * @param $val
     * @return array
     */
    function array_unset_value(array $array, $val): array
    {
        $key = array_search($val, $array);

        if ($key !== false) {
            unset($array[$key]);
        }

        return $array;
    }
}

if (! function_exists('url_add_params')) {
    /**
     * Доповнити URL GET-параметрами.
     *
     * @param string $url
     * @param array $paramsToAdd
     * @return string
     */
    function url_add_params(string $url, array $paramsToAdd = [])
    {
        if ($paramsToAdd === []) {
            return $url;
        }

        // Розділяємо URL на шлях та GET-параметри
        $urlParts = parse_url($url);

        // Визначаємо протокол (якщо відсутній)
        $protocol = isset($urlParts['scheme']) ? $urlParts['scheme'] . '://' : 'https://';

        // Визначаємо шлях
        $path = isset($urlParts['path']) ? $urlParts['path'] : '';

        // Визначаємо GET-параметри
        $query = isset($urlParts['query']) ? $urlParts['query'] : '';

        // Розбиваємо наявні GET-параметри на масив
        parse_str($query, $existingParams);

        // Об'єднуємо наявні та додаткові GET-параметри
        $combinedParams = array_merge($existingParams, $paramsToAdd);

        // Перетворюємо масив параметрів в рядок
        $newQuery = http_build_query($combinedParams);

        // Збираємо оновлений URL
        $newUrl = $protocol . $urlParts['host'] . $path . '?' . $newQuery;

        return $newUrl;
    }
}


if (! function_exists('pluralize_ukrainian')) {
    /**
     * @param string $url
     * @param array $paramsToAdd
     * @return string
     */
    function pluralize_ukrainian(int $number, array $forms = [])
    {
        $cases = [2, 0, 1, 1, 1, 2];

        return $number . ' ' . $forms[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
    }
}

if (! function_exists('escape_markdown')) {
    /**
     * @param $text
     * @return array|string|string[]
     */
    function escape_markdown(string $text): string
    {
        // Символи, які потрібно екранувати
        $specialCharacters = ['\\', '_', '*', '`', '[', ']', '(', ')', '~', '#', '+', '-', '.', '!'];

        // Екрануємо кожен спеціальний символ
        foreach ($specialCharacters as $char) {
            $text = str_replace($char, '\\' . $char, $text);
        }

        return $text;
    }
}

if (! function_exists('is_valid_uuid')) {
    /**
     * @param $text
     * @return bool
     */
    function is_valid_uuid(string $text): bool
    {
        return is_scalar($text) && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $text);
    }
}


if (! function_exists('get_lfm_image_cache')) {
    /**
     * @param $text
     * @return bool
     */
    function get_lfm_image_cache(string $url): string
    {
        $pos = strpos($url, 'photos');

        if (preg_match('/\.(jpeg|jpg|png|gif)$/', $url) && $pos !== false) {
            $path = substr($url, $pos);

            $fullPath = storage_path("app/public/{$path}");

            if (file_exists($fullPath)) {
                $sharesIndex = strpos($fullPath, 'shares/');
                $startIndex = $sharesIndex + strlen('shares/');
                $relativePath = substr($fullPath, $startIndex);

                return route('imagecache', $relativePath);
            }
        }

        return $url;
    }
}

if (! function_exists('telegram_clean_html')) {
    /**
     * @param string $html
     * @return string
     */
    function telegram_clean_html(string $html): string
    {
        $html = str_replace(['<br>', '<br/>', '<br />'], "\n", $html);
        $html = str_replace(['<p>', '</p>'], "\n", $html);

        $config = \HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'b,strong,i,em,a[href],code,pre,blockquote');
        $purifier = new \HTMLPurifier($config);

        $cleanHtml = $purifier->purify($html);

        return $cleanHtml;
    }
}

if (! function_exists('filter_explode')) {
    /**
     * @param $val
     * @return array
     */
    function filter_explode($val = null): array
    {
        if (empty($val)) {
            return [];
        }

        if (is_string($val)) {
            return explode(',', $val);
        }

        if (is_array($val)) {
            return $val;
        }

        return [];
    }
}

if (!function_exists('url_build_with_date_range')) {
    /**
     * @param $from
     * @param $to
     * @return string
     */
    function url_build_with_date_range($from, $to):string {
        $currentParams = request()->query();
        $currentParams['created_at_from'] = $from->format('Y-m-d');
        $currentParams['created_at_to'] = $to->format('Y-m-d');

        return url()->current() . '?' . http_build_query($currentParams);
    }
}

if (!function_exists('url_clear')) {
    function url_clear(string $str) {
        $res = preg_replace('#<a[^>]*>(.*?)</a>#is', '$1', $str);
        $regex = "@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?).*$)@";

        return preg_replace($regex, ' ', $res);
    }
}

if (!function_exists('hex_to_rgba')) {
    function hex_to_rgba($hex, $alpha = 1): string
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        return "rgba($r, $g, $b, $alpha)";
    }
}


if (!function_exists('datetime_to_client')) {
    function datetime_to_client($datetime = null, $format = null, $default = null)
    {

        if (empty($datetime)) {
            return $default;
        }

        $clientTZ = config('app.timezone_client') ?: config('app.timezone');

        $date = $datetime instanceof \DateTime
            ? $datetime
            : \Carbon\Carbon::parse($datetime);

        $res = $date->timezone($clientTZ);

        if ($res && $format) {
            return $res->format($format);
        }

        return $res;

    }
}

if (! function_exists('get_path_without_host')) {
    /**
     * @param string $str
     * @param bool $withQuery
     * @return string
     */
    function get_path_without_host(mixed $str, bool $withQuery = false): string
    {
        $path = parse_url(strval($str), PHP_URL_PATH);

        if (!($path === '/')) {
            $path = trim($path, '/\\');
        }

        if ($withQuery && ($query = parse_url(strval($str), PHP_URL_QUERY))) {
            $path = $path . '?' . $query;
        }

        return $path;
    }
}

if (! function_exists('calculate_reading_time')) {
    /**
     * Підрахунок часу читання у хвилинах
     *
     * @param string|null $text
     * @param int $readingSpeedChars
     * @param string $stripType
     * @return int
     */
    function calculate_reading_time(string $text = null, int $readingSpeedChars = 1500, string $stripType = 'html'): int
    {
        if (is_null($text)) return 0;

        $charCount = 0;

        switch ($stripType) {
            case 'html':
                $text = strip_tags($text);
                $charCount = \Illuminate\Support\Str::length($text);
                break;

            case 'json':
                $decoded = json_decode($text, true);
                if (is_array($decoded) && isset($decoded['blocks']) && is_array($decoded['blocks'])) {
                    foreach ($decoded['blocks'] as $block) {
                        // Якщо простий текст
                        if (isset($block['data']['text'])) {
                            $charCount += \Illuminate\Support\Str::length(strip_tags($block['data']['text']));
                        }

                        // Якщо список (checklist)
                        if ($block['type'] === 'checklist' && isset($block['data']['items'])) {
                            foreach ($block['data']['items'] as $item) {
                                $charCount += \Illuminate\Support\Str::length(strip_tags($item['text'] ?? ''));
                            }
                        }
                    }
                }
                break;

            default:
                $charCount = \Illuminate\Support\Str::length($text);
                break;
        }

        $minutes = (int) ceil($charCount / $readingSpeedChars);

        return $minutes;
    }
}

if (! function_exists('strip_json')) {
    /**
     * Видалення json з тексту (editorJS)
     *
     * @param string|null $json
     * @return string
     */
    function strip_json(string $json = null): string
    {
        if (is_null($json)) return '';

        $decoded = json_decode($json, true);

        if (!is_array($decoded) || !isset($decoded['blocks']) || !is_array($decoded['blocks'])) {
            return '';
        }

        $texts = [];

        foreach ($decoded['blocks'] as $block) {
            // Якщо є текст у блокі
            if (isset($block['data']['text'])) {
                $texts[] = trim(strip_tags($block['data']['text']));
            }

            // Якщо список (checklist)
            if ($block['type'] === 'checklist' && isset($block['data']['items'])) {
                foreach ($block['data']['items'] as $item) {
                    $texts[] = trim(strip_tags($item['text'] ?? ''));
                }
            }
        }

        // Склеюємо через пробіл, можна змінити на "\n" для краще читабельності
        return trim(implode(' ', $texts));
    }
}

if (! function_exists('client_timezone')) {
    /**
     * Отрмуємо та Нормалізуємо часовий пояс клієнта
     *
     * @param string|null $timezone
     * @return string
     */
    function client_timezone(string $timezone = null): string
    {
        // Якщо передається часовиий пояс, то використовуємо його
        // В іншому випадку вибираємо часовий пояс користувача або дефолтний
        $timezone ??= request()->user()?->timezone
            ?: request()->input('stimezone')
                ?: request()->header('stimezone')
                    ?: config('app.timezone_client');

        // Нормалізуємо часовий пояс під нову версію
        $timezoneVersion = config('app.timezone_version', 'new');

        $timezoneAliases = config("app.timezone_aliases.$timezoneVersion");

        return $timezoneAliases[$timezone] ?? $timezone;
    }
}