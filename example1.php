<?php
mb_internal_encoding('UTF-8');
require_once __DIR__ . '/library/Fast/Loader.php';

use Fast\Useful;
use Fast\Form\AbstractForm;

class ExampleForm extends AbstractForm
{
    public $email;
    
    public $password;
    
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            [['email'], 'email'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
            'password' => 'Пароль',
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
  <h3 class="m-3">Пример формы 1. Форма входа на сайт</h3>

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
    <label><?= ($form->attributeLabel('email') ?: 'email'); ?></label>
    <input name="<?= $form->attributeName('email'); ?>" type="text" class="form-control" 
        value="<?= $form->attributeEscapeValue('email'); ?>"
        placeholder="<?= ($form->attributeLabel('email') ?: 'email'); ?>"
    >
  </div>
  <div class="form-group">
    <label><?= ($form->attributeLabel('password') ?: 'password'); ?></label>
    <input name="<?= $form->attributeName('password'); ?>" type="password" class="form-control" 
        value="<?= $form->attributeEscapeValue('password'); ?>"
        placeholder="<?= ($form->attributeLabel('password') ?: 'password'); ?>"
    >
  </div>
  
  <button type="submit" class="btn btn-primary">Отправить</button>
</form>
    
    </div>
  </div>

</div>
</body>
</html>
