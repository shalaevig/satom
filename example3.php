<?php
mb_internal_encoding('UTF-8');
require_once __DIR__ . '/library/Fast/Loader.php';

use Fast\Useful;
use Fast\Form\AbstractForm;

class ExampleForm extends AbstractForm
{
    public $name;
    
    public $width;
    
    public $height;
    
    public $length;
    
    public $type;
        
    public function rules()
    {
        return [
            [['name', 'width', 'height', 'length', 'type'], 'required'],
            [['name'], 'string', ['max' => 200]],
            [['width', 'height', 'length'], 'numeric', ['min' => 1, 'max' => 1000]],
            [['type'], 'in', ['items' => array_keys($this->typeItems()) ]],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'name' => 'Название товара',
            'width' => 'Ширина',
            'height' => 'Высота',
            'length' => 'Длина',
            'type' => 'Тип упаковки',
        ];
    }

    public function typeItems()
    {
        return [
            1 => 'Коробка',
            2 => 'Конверт',
            3 => 'Рамка',
            4 => 'Другое',
        ];
    }

}

$form = new ExampleForm();

$message = [];

if ($form->load($_POST)) {
    if ($form->validate()) {
        $message = [
            'type' => 'success',
            'value' => 'Данные формы прошли проверку.',
        ];
    } else {
        $msgs = $form->getAllMessages();
        $message = [
            'type' => 'danger',
            'value' => implode('<br>', $msgs),
        ];
    }
}

?>
<html>
<head>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body>

<div class="container-fluid">
  <h3 class="m-3">Пример формы 3. Данные товара</h3>

<?php 
if ($message) {
?>
    <p class="alert alert-<?= $message['type']; ?>"><?= $message['value']; ?></p>
<?php    
}
?>
  
  <div class="row pt-3">
    <div class="col-6">
  
<form method="post">
  <div class="form-group">
    <label><?= ($form->attributeLabel('name') ?: 'name'); ?></label>
    <input name="<?= $form->attributeName('name'); ?>" type="text" class="form-control" 
        value="<?= $form->attributeEscapeValue('name'); ?>"
        placeholder="<?= ($form->attributeLabel('name') ?: 'name'); ?>"
    >
  </div>
  <div class="form-group">
    <label><?= ($form->attributeLabel('width') ?: 'width'); ?></label>
    <input name="<?= $form->attributeName('width'); ?>" type="text" class="form-control" 
        value="<?= $form->attributeEscapeValue('width'); ?>"
        placeholder="<?= ($form->attributeLabel('width') ?: 'width'); ?>"
    >
    <small class="form-text text-muted">Значение должно быть от 1 до 1000.</small>
  </div>
  <div class="form-group">
    <label><?= ($form->attributeLabel('height') ?: 'height'); ?></label>
    <input name="<?= $form->attributeName('height'); ?>" type="text" class="form-control" 
        value="<?= $form->attributeEscapeValue('height'); ?>"
        placeholder="<?= ($form->attributeLabel('height') ?: 'height'); ?>"
    >
    <small class="form-text text-muted">Значение должно быть от 1 до 1000.</small>
  </div>
  <div class="form-group">
    <label><?= ($form->attributeLabel('length') ?: 'length'); ?></label>
    <input name="<?= $form->attributeName('length'); ?>" type="text" class="form-control" 
        value="<?= $form->attributeEscapeValue('length'); ?>"
        placeholder="<?= ($form->attributeLabel('length') ?: 'length'); ?>"
    >
    <small class="form-text text-muted">Значение должно быть от 1 до 1000.</small>
  </div>
  <div class="form-group">
    <label><?= ($form->attributeLabel('type') ?: 'type'); ?></label>
    <select class="form-control" name="<?= $form->attributeName('type'); ?>">
      <option value=""> </option>
      <?php
        foreach ($form->typeItems() as $k => $v) {
            $selected = ($k == $form->type) ? 'selected="selected"' : '';
      ?>
        <option value="<?= $k; ?>" <?= $selected; ?>><?= $v; ?></option>
      <?php
        }
      ?>
    </select>
  </div>
  
  <button type="submit" class="btn btn-primary">Отправить</button>
</form>
    
    </div>
  </div>

</div>
</body>
</html>
