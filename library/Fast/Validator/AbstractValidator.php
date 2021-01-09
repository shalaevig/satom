<?php
namespace Fast\Validator;

/**
 * Абстрактный класс проверки.
 */ 
abstract class AbstractValidator
{
    /**
     * Атрибут проверки, если форма.
     * 
     * @var string 
     */
    public $attribute;
    
    /**
     * Текст сообщения.
     * 
     * @var string 
     */
    public $message;
    
    /**
     * Пропускать, если пустое значение.
     * 
     * @var bool 
     */
    public $skipIfEmpty = true;
    
    /**
     * Конструктор.
     * 
     * @param $config array
     */ 
    public function __construct($config = [])
    {
        
    }
    
    /**
     * Проверить, пустое значение или нет.
     * 
     * @param $value mixed
     * @return bool
     */
    public function isEmpty($value)
    {
        return (($value === null) || ($value === []) || ($value === ''));
    }

    /**
     * Метод проверки.
     * Возвращает null, если нет ошибок, иначе массив с сообщениями.
     * 
     * @param $value mixed
     * @return null|array
     */
    abstract public function validate($value);

}
