<?php
namespace Fast\Validator;

use Fast\Useful;
use Fast\Validator\ValidatorException;

/**
 * Проверка, что значение эквивалетно другому.
 */
class EquValidator extends AbstractValidator
{
    /**
     * Второе значение.
     * 
     * @var mixed 
     */
    public $equValue;
    
    /**
     * Название второго значения.
     * 
     * @var string 
     */
    public $equLabel = 'Значение 2';
    
    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        if (isset($config['attribute'])) $this->attribute = $config['attribute'];
        if (!isset($config['equValue']) || $this->isEmpty($config['equValue'])) {
            throw new ValidatorException('Не указано значение для сравнения.');
        } else {
            $this->equValue = $config['equValue'];
        }
        if (isset($config['equLabel'])) $this->equLabel = $config['equLabel'];
        if (isset($config['message'])) $this->message = $config['message'];
        else {
            if (is_null($this->attribute)) $this->message = 'Значение не эквивалентно значению "{equLabel}".';
            else $this->message = 'Значение "{attribute}" не эквивалетно значению "{equLabel}".';
        }
    }
    
    /**
     * @inheritdoc
     */
    public function validate($value)
    {
        if ($this->skipIfEmpty && $this->isEmpty($value)) return null;
        $msgs = [];
        $equValue = $this->equValue;
        if (is_callable($equValue)) $equValue = $equValue(); 
        if ($value !== $equValue) {
            $msgs[] = Useful::str($this->message, ['attribute' => $this->attribute, 'equLabel' => $this->equLabel]);
        }

        if ($msgs) return $msgs;
        return null;
    }

}
