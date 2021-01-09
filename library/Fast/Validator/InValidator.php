<?php
namespace Fast\Validator;

use Fast\Useful;
use Fast\Validator\ValidatorException;

/**
 * Проверка присутствия значения в массиве.
 */
class InValidator extends AbstractValidator
{
    /**
     * Массив значений.
     * 
     * @var array 
     */
    public $items;
    
    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        if (isset($config['attribute'])) $this->attribute = $config['attribute'];
        if (!isset($config['items']) || !is_array($config['items']) || !$config['items']) {
            throw new ValidatorException('Не указан массив элементов.');
        } else {
            $this->items = $config['items'];
        }
        if (isset($config['message'])) $this->message = $config['message'];
        else {
            if (is_null($this->attribute)) $this->message = 'Значение отсутствует в списке доступных значений.';
            else $this->message = 'Значение "{attribute}" отсутствует в списк доступных значений.';
        }
    }

    /**
     * @inheritdoc
     */
    public function validate($value)
    {
        if ($this->skipIfEmpty && $this->isEmpty($value)) return null;
        $msgs = [];
        if (!in_array($value, $this->items)) {
            $msgs[] = Useful::str($this->message, ['attribute' => $this->attribute]);
        }
        if ($msgs) return $msgs;
        return null;
    }

}
