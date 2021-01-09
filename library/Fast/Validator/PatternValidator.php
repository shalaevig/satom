<?php
namespace Fast\Validator;

use Fast\Useful;
use Fast\Validator\ValidatorException;

/**
 * Проверка по шаблону.
 */
class PatternValidator extends AbstractValidator
{
    /**
     * Минимальное значение.
     * 
     * @var string 
     */
    public $pattern;
    
    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        if (isset($config['attribute'])) $this->attribute = $config['attribute'];
        if (!isset($config['pattern']) || $this->isEmpty($config['pattern'])) {
            throw new ValidatorException('Не указан шаблон.');
        } else {
            $this->pattern = $config['pattern'];
        }
        if (isset($config['message'])) $this->message = $config['message'];
        else {
            if (is_null($this->attribute)) $this->message = 'Значение не соответствует шаблону.';
            else $this->message = 'Значение "{attribute}" не соответствует шаблону.';
        }
    }

    /**
     * @inheritdoc
     */
    public function validate($value)
    {
        if ($this->skipIfEmpty && $this->isEmpty($value)) return null;
        $msgs = [];
        if (!preg_match($this->pattern, $value)) {
            $msgs[] = Useful::str($this->message, ['attribute' => $this->attribute]);
        }
        if ($msgs) return $msgs;
        return null;
    }

}
