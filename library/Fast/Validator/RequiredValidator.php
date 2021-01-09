<?php
namespace Fast\Validator;

use Fast\Useful;

/**
 * Проверка на пустоту.
 */
class RequiredValidator extends AbstractValidator
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
            if (is_null($this->attribute)) $this->message = 'Значение не заполнено.';
            else $this->message = 'Значение "{attribute}" не заполнено.';
        }
    }
    
    /**
     * @inheritdoc
     */
    public function validate($value)
    {
        $msgs = [];
        if ($this->isEmpty($value)) {
            $msgs[] = Useful::str($this->message, ['attribute' => $this->attribute]);
        }
        if ($msgs) return $msgs;
        return null;
    }

}