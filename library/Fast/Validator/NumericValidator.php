<?php
namespace Fast\Validator;

use Fast\Useful;

/**
 * Проверка на числовое значение.
 */
class NumericValidator extends AbstractValidator
{
    /**
     * Минимальное значение.
     * 
     * @var null|integer 
     */
    public $min;
    
    /**
     * Максимальное значение.
     * 
     * @var null|integer 
     */
    public $max;
    
    /**
     * Сообщение, если число меньше минимального.
     * 
     * @var null|string 
     */
    public $tooSmall;
    
    /**
     * Сообщение, если число больше максимального.
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
        if (isset($config['min'])) $this->min = $config['min'];
        if (isset($config['max'])) $this->max = $config['max'];
        if (isset($config['message'])) $this->message = $config['message'];
        else {
            if (is_null($this->attribute)) $this->message = 'Значение не является числом.';
            else $this->message = 'Значение "{attribute}" не является числом.';
        } 
        
        if (isset($config['tooSmall'])) $this->tooSmall = $config['tooSmall'];
        else {
            if (is_null($this->attribute)) $this->tooSmall = 'Значение должно быть не меньше {min}.';
            else $this->tooSmall = 'Значение "{attribute}" должно быть не меньше {min}.';
        }
        
        if (isset($config['tooBig'])) $this->tooBig = $config['tooBig'];
        else {
            if (is_null($this->attribute)) $this->tooBig = 'Значение должно быть не больше {max}.';
            else $this->tooBig = 'Значение "{attribute}" должно быть не больше {max}.';
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
        if (is_numeric($value)) {
            
        } else {
            $msgs[] = Useful::str($this->message, ['attribute' => $this->attribute, 'min' => $this->min, 'max' => $this->max]);
            $next = false;
        }
        if (!is_null($this->min) && $next) {
            if ($value < $this->min) {
                $msgs[] = Useful::str($this->tooSmall, ['attribute' => $this->attribute, 'min' => $this->min, 'max' => $this->max]);
                $next = false;
            }
        }
        if (!is_null($this->max) && $next) {
            if ($value > $this->max) {
                $msgs[] = Useful::str($this->tooBig, ['attribute' => $this->attribute, 'min' => $this->min, 'max' => $this->max]);
                $next = false;
            }
        }

        if ($msgs) return $msgs;
        return null;
    }

}
