<?php
namespace Fast\Validator;

use Fast\Useful;

/**
 * Проверка на строку.
 */
class StringValidator extends AbstractValidator
{

    /**
     * Точная длина строки.
     * 
     * @var null|integer 
     */
    public $length;
    
    /**
     * Минимальная длина строки.
     * 
     * @var null|integer 
     */
    public $min;
    
    /**
     * Максимальная длина строки.
     * 
     * @var null|integer 
     */
    public $max;
    
    /**
     * Сообщение для точной длины строки.
     * 
     * @var null|string 
     */
    public $equLength;
    
    /**
     * Сообщение, если строка меньше указанной длины.
     * 
     * @var null|string
     */
    public $tooSmall;
    
    /**
     * Сообщение, если строка больше указанной длины.
     * 
     * @var null|string
     */
    public $tooBig;
    
    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        if (isset($config['attribute'])) $this->attribute = $config['attribute'];
        if (isset($config['length'])) $this->length = $config['length'];
        if (isset($config['min'])) $this->min = $config['min'];
        if (isset($config['max'])) $this->max = $config['max'];
        if (isset($config['message'])) $this->message = $config['message'];
        else {
            if (is_null($this->attribute)) $this->message = 'Значение не является строкой.';
            else $this->message = 'Значение "{attribute}" не является строкой.';
        } 
        
        if (isset($config['equLength'])) $this->equLength = $config['equLength'];
        else {
            if (is_null($this->attribute)) $this->equLength = 'Длина строки должна быть {length} символов.';
            else $this->equLength = 'Длина строки "{attribute}" должна быть {length} символов.';
        }
        
        if (isset($config['tooSmall'])) $this->tooSmall = $config['tooSmall'];
        else {
            if (is_null($this->attribute)) $this->tooSmall = 'Длина строки должна быть не меньше {min} символов.';
            else $this->tooSmall = 'Длина строки "{attribute}" должна быть не меньше {min} символов.';
        }
        
        if (isset($config['tooBig'])) $this->tooBig = $config['tooBig'];
        else {
            if (is_null($this->attribute)) $this->tooBig = 'Длина строки должна быть не больше {max} символов.';
            else $this->tooBig = 'Длина строки "{attribute}" должна быть не больше {max} символов.';
        }
    }
    
    /**
     * @inheritdoc
     */
    public function validate($value)
    {
        if ($this->skipIfEmpty && $this->isEmpty($value)) return null;
        $next = true;
        $msgs = [];
        if (is_string($value)) {
            
        } else {
            $msgs[] = Useful::str($this->message, ['attribute' => $this->attribute, 'length' => $this->length, 
                'min' => $this->min, 'max' => $this->max]);
            $next = false;
        }
        if (!is_null($this->length) && $next) {
            if (mb_strlen($value) != $this->length) {
                $msgs[] = Useful::str($this->equLength, ['attribute' => $this->attribute, 'length' => $this->length,
                    'min' => $this->min, 'max' => $this->max]);
                $next = false;
            }
        }
        if (!is_null($this->min) && $next) {
            if (mb_strlen($value) < $this->min) {
                $msgs[] = Useful::str($this->tooSmall, ['attribute' => $this->attribute, 'length' => $this->length, 
                    'min' => $this->min, 'max' => $this->max]);
                $next = false;
            }
        }
        if (!is_null($this->max) && $next) {
            if (mb_strlen($value) > $this->max) {
                $msgs[] = Useful::str($this->tooBig, ['attribute' => $this->attribute, 'length' => $this->length, 
                    'min' => $this->min, 'max' => $this->max]);
                $next = false;
            }
        }

        if ($msgs) return $msgs;
        return null;
    }

}
