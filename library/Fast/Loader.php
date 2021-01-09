<?php
namespace Fast;

require_once __DIR__ . '/Exception.php';

/**
 * Загрузчик классов. Только наших.
 */
class Loader
{
    
    /**
     * Загрузить класс.
     * 
     * @param $className string Название класса
     * @return void
     */
    public static function load($className)
    {
        // Проверим, наш класс или нет, через пространство имён.
        $find = mb_stripos($className, '\\');
        if ($find) {
            $prefix = mb_substr($className, 0, $find);
            if ($prefix == 'Fast') {
                $fileName = $className;
                $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $fileName) . '.php';
                require_once __DIR__ . '/../' . $fileName;    
            }
        }
    }
        
}

spl_autoload_register(['Fast\Loader', 'load']);