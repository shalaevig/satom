<?php
namespace Fast\Validator;

use Fast\Useful;

/**
 * Класс проверки e-mail адреса.
 */ 
class EmailValidator extends AbstractValidator
{
    
    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        if (isset($config['attribute'])) $this->attribute = $config['attribute'];
        if (isset($config['message'])) $this->message = $config['message'];
        else {
            if (is_null($this->attribute)) $this->message = 'Значение не является e-mail адресом.';
            else $this->message = 'Значение "{attribute}" не является e-mail адресом.';
        }
    }
    
    /**
     * @inheritdoc
     */
    public function validate($value)
    {
        if ($this->skipIfEmpty && $this->isEmpty($value)) return null;
        $msgs = [];
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $msgs[] = Useful::str($this->message, ['attribute' => $this->attribute]);
        }
        if ($msgs) return $msgs;
        return null;
    }

}
