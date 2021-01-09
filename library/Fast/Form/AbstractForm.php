<?php
namespace Fast\Form;

use Fast\Useful;

/**
 * Абстрактный класс формы.
 */ 
abstract class AbstractForm
{
    
    /**
     * Имя формы.
     * 
     * @var null|string 
     */
    protected $_formName;
    
    /**
     * Массив сообщений в случае проверки.
     * 
     * @var array 
     */
    protected $_messages = [];
    
    /**
     * Получить/установить имя формы.
     *
     * @param $name null|string 
     * @return string
     */
    public function formName($name = null)
    {
        if (!is_null($name)) {
            $this->_formName = $name;
        }
        if (is_null($this->_formName)) {
            $_name = get_class($this);
            $_name = explode('\\', $_name);
            return $_name[sizeof($_name) - 1];
        }
        return $this->_formName;
    }
    
    /**
     * Название атрибута.
     *
     * @param $attr string 
     * @return string
     */
    public function attributeName($attr)
    {
        $formName = $this->formName();
        return $formName . '[' . $attr . ']';
    }
    
    /**
     * Экранировать значение.
     *
     * @param $attr string 
     * @return string
     */
    public function attributeEscapeValue($attr)
    {
        $val = Useful::escapeHtml($this->{$attr});
        return $val;
    }
    
    /**
     * Загрузить данные на форму. Вернет false, если данных нет.
     *
     * @param $vals array 
     * @return bool
     */
    public function load(array $vals)
    {
        $formName = $this->formName();
        if (isset($vals[$formName])) {
            $_vals = $vals[$formName];
            // Получим значения только тех атрибутов, которые проверяем.
            $attrs = $this->getCheckAttributes();
            if (!$attrs) return false;
            foreach ($attrs as $k => $v) {
                if (isset($_vals[$v])) $this->{$v} = $_vals[$v]; 
            }
            return true;
        }
        return false;
    }
    
    /**
     * Получить метки атрибутов.
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [];
    }
    
    /**
     * Получить метку атрибута.
     *
     * @param $attr string
     * @return null|string
     */
    public function attributeLabel($attr)
    {
        $labels = $this->attributeLabels();
        if (isset($labels[$attr])) return $labels[$attr];
        return null; 
    }
    
    /**
     * Получить атрибуты для проверки.
     *
     * @return array
     */
    public function getCheckAttributes()
    {
        $attrs = [];
        $rules = $this->rules();
        foreach ($rules as $k => $v) {
            if (is_array($v)) {
                $attrs = array_merge($attrs, $v[0]);
            }
        }
        $attrs = array_unique($attrs);
        return $attrs;
    }
    
    /**
     * Проверить атрибуты формы.
     *
     * @return bool
     */
    public function validate()
    {
        $this->_messages = [];
        $rules = $this->rules();
        if (!$rules) return true;
        
        $validators = [];
        foreach ($rules as $k => $v) {
            if (!is_array($v)) continue;
            if (!is_array($v[0])) continue;
            $_validator = $v[1];
            $_params = [];
            if (isset($v[2])) $_params = $v[2];
            foreach ($v[0] as $k1 => $v1) {
                if (!isset($validators[$v1])) {
                    $validators[$v1] = [];
                }
                $validators[$v1][$_validator] = $_params;
            }
        }
        if (!$validators) return true;
        
        // Проходимся по валидаторам.
        $msgs = [];
        $labels = $this->attributeLabels();
        foreach ($validators as $k => $v) {
            foreach ($v as $_validator => $_params) {
                // Только встроенные валидаторы.
                $_name = 'Fast\Validator\\' . ucfirst($_validator) . 'Validator';
                if (!isset($_params['attribute'])) {
                    if (isset($labels[$k])) $_params['attribute'] = $labels[$k];
                    else $_params['attribute'] = $k; 
                }
                $obj = new $_name($_params);
                $_result = $obj->validate($this->{$k});
                if ($_result) {
                    if (!isset($msgs[$k])) $msgs[$k] = [];
                    $msgs[$k] = array_merge($msgs[$k], $_result);
                }
            }
        }
        $this->_messages = $msgs;
        
        if ($this->_messages) return false;
        return true;
    }
    
    /**
     * Получить все сообщения по атрибутам.
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->_messages;
    }
    
    /**
     * Получить все сообщения в одномерном массиве.
     *
     * @return array
     */
    public function getAllMessages()
    {
        $msgs = [];
        foreach ($this->_messages as $k => $v) {
            $msgs = array_merge($msgs, $v);
        }
        return $msgs;
    }
    
    /**
     * Метод проверки. Если ошибок нет вернет true, иначе false.
     * 
     * @param $value mixed
     * @return bool
     */
    abstract public function rules();

}