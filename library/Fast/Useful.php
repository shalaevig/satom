<?php
namespace Fast;

/**
 * Класс с полезными функциями.
 */ 
class Useful
{
    
    /**
     * Подстановка значений в строку.
     * 
     * @param $str string Строка
     * @param $params array Параметры
     * @return string
     */ 
    public static function str($str, array $params = [])
    {
        $keys = array_keys($params);
        foreach ($keys as $k => $v) {
            $keys[$k] = '{' . $v . '}';
        }
        $vals = array_values($params);
        $str = str_replace($keys, $vals, $str);
        return $str; 
    }
    
    /**
     * Экранировать значения для формы.
     * 
     * @param $value string Строка
     * @return string
     */
    public static function escapeHtml($value) 
    {
        return htmlentities($value, ENT_QUOTES);
    }
    
}