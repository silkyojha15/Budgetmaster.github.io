<?php

namespace ContentEgg\application\helpers;

use ContentEgg\application\components\ContentManager;

/**
 * TemplateHelper class file
 * 
 * @author keywordrush.com <support@keywordrush.com>
 * @link http://www.keywordrush.com/
 * @copyright Copyright &copy; 2015 keywordrush.com
 * 
 */
class TemplateHelper {

    static public function currencyTyping($c)
    {
        $types = array("RUB" => "руб.", "UAH" => "грн.", "USD" => "$", "CAD" => "C$", "GBP" => "&pound;", "EUR" => "&euro;", "JPY" => "&yen;", "CNY" => "&yen;", "INR" => "&#8377;", "AUD" => "AU $", "RUR" => 'руб.');
        if (key_exists($c, $types))
            return $types[$c];
        else
            return $c;
    }

    public static function number_format_i18n($number, $decimals = 0)
    {
        $decimal_point = __('number_format_decimal_point', 'content-egg-tpl');
        $thousands_sep = __('number_format_thousands_sep', 'content-egg-tpl');

        if ($decimal_point == 'number_format_decimal_point')
            $decimal_point = '.';

        if ($thousands_sep == 'number_format_thousands_sep')
            $thousands_sep = ',';

        return number_format($number, absint($decimals), $decimal_point, $thousands_sep);
    }

    public static function price_format_i18n($number, $currency = null)
    {
        if ($currency  && in_array($currency, array('RUB', 'UAH')))
			$decimal = 0;
        else
			$decimal = 2;	
        return self::number_format_i18n($number, $decimal);
    }

    public static function truncate($string, $length = 80, $etc = '...', $charset = 'UTF-8', $break_words = false, $middle = false)
    {
        if ($length == 0)
            return '';

        if (mb_strlen($string, 'UTF-8') > $length)
        {
            $length -= min($length, mb_strlen($etc, 'UTF-8'));
            if (!$break_words && !$middle)
            {
                $string = preg_replace('/\s+?(\S+)?$/', '', mb_substr($string, 0, $length + 1, $charset));
            }
            if (!$middle)
            {
                return mb_substr($string, 0, $length, $charset) . $etc;
            } else
            {
                return mb_substr($string, 0, $length / 2, $charset) . $etc . mb_substr($string, -$length / 2, $charset);
            }
        } else
        {
            return $string;
        }
    }

    /**
     * Возвращает количтсво дней, секунд и минут с текущего момента
     * до окончания события
     * @param string $end_time_gmt GNU формат даты
     * @param bool $return_array вернуть в виде массива или форматированной строки?
     * @return mixed false - если $timeleft < 0, массив или строку
     */
    static public function getTimeLeft($end_time_gmt, $return_array = false)
    {

        $current_time = strtotime(gmdate("M d Y H:i:s"));
        $timeleft = strtotime($end_time_gmt) - $current_time;
        if ($timeleft < 0)
            return '';

        $days_left = floor($timeleft / 86400);
        $hours_left = floor(($timeleft - $days_left * 86400) / 3600);
        $min_left = floor(($timeleft - $days_left * 86400 - $hours_left * 3600) / 60);
        // Если нужно вернуть в виде массива
        if ($return_array)
        {
            return array(
                'days' => $days_left,
                'hours' => $hours_left,
                'min' => $min_left,
            );
        }

        if ($days_left)
            return $days_left . __('d', 'content-egg-tpl') . ' ';
        elseif ($hours_left)
            return $hours_left . __('h', 'content-egg-tpl') . ' ';
        elseif ($min_left)
            return $min_left . __('m', 'content-egg-tpl');
        else
            return '<1' . __('m', 'content-egg-tpl');
    }

    public static function filterData($data, $field_name, $field_values, $extra = false, $inverse = false)
    {
        $results = array();
        foreach ($data as $key => $d)
        {
            if ($extra)
            {
                if (!isset($d['extra']) || !isset($d['extra'][$field_name]))
                    continue;
                $value = $d['extra'][$field_name];
            } else
            {
                if (!isset($d[$field_name]))
                    continue;
                $value = $d[$field_name];
            }
            if (!is_array($field_values))
                $field_values = array($field_values);

            if (!$inverse && in_array($value, $field_values))
                $results[$key] = $d;
            elseif ($inverse && !in_array($value, $field_values))
                $results[$key] = $d;
        }
        return $results;
    }

    public static function formatDatetime($datetime, $type = 'mysql', $separator = ' ')
    {
        if ('mysql' == $type)
        {
            return mysql2date(get_option('date_format'), $datetime) . $separator . mysql2date(get_option('time_format'), $datetime);
        } else
        {
            return date_i18n(get_option('date_format'), $datetime) . $separator . date_i18n(get_option('time_format'), $datetime);
        }
    }

    public static function splitAttributeName($attribute)
    {
        return trim(preg_replace('/([A-Z])/', ' $1', $attribute));
    }

    public static function getAmazonLink(array $itemLinks, $description)
    {
        foreach ($itemLinks as $link)
        {
            if ($link['Description'] == $description)
                return $link['URL'];
        }
        return false;
    }

    public static function getLastUpdate($module_id)
    {
        global $post;
        return \get_post_meta($post->ID, ContentManager::META_PREFIX_LAST_ITEMS_UPDATE . $module_id, true);
    }

    public static function getLastUpdateFormatted($module_id, $timezone = true)
    {
        $format = get_option('date_format') . ' ' . get_option('time_format');
        if ($timezone)
            $format .= ' T';
        // local time
        return get_date_from_gmt(date('Y-m-d H:i:s', self::getLastUpdate($module_id)), $format);
    }

    public static function formatPriceCurrency($price, $currencyCode)
    {
        $return = '';
        $currency = self::currencyTyping($currencyCode);
        $price = self::price_format_i18n($price, $currencyCode);

        if (in_array($currencyCode, array('RUB', 'UAH')))
            return $price . ' ' . $currency;
        else
            return $currency . '' . $price;
    }

}
