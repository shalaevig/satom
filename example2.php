<?php
mb_internal_encoding('UTF-8');
require_once __DIR__ . '/library/Fast/Loader.php';

use Fast\Useful;
use Fast\Form\AbstractForm;

class ExampleForm extends AbstractForm
{
    public $login;
    
    public $email;
    
    public $password;
    
    public $password_repeat;
    
    public $firstname;
    
    public $lastname;
    
    public $middlename;
    
    public function rules()
    {
        return [
            [['login', 'email', 'password', 'password_repeat'], 'required'],
            [['login'], 'string', ['min' => 6, 'max' => 50]],
            [['email', 'firstname', 'lastname', 'middlename'], 'string', ['max' => 100]],
            [['email'], 'email'],
            [['password', 'password_repeat'], 'string', ['min' => 6, 'max' => 16]],
            [['password'], 'equ', ['equValue' => function(){
                    return $this->password_repeat;
                },
                'message' => 'Пароль и повтор пароля должны совпадать', 
            ]],
            [['login'], 'pattern', ['pattern' => '/^[a-zA-Z0-9_.\-]+$/iu']],
            [['firstname', 'lastname', 'middlename'], 'pattern', ['pattern' => '/^[ёЁа-яА-Яa-zA-Z\- ]+$/iu']],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'login' => 'Имя пользователя',
            'email' => 'E-mail',
            'password' => 'Пароль',
            'password_repeat' => 'Повтор пароля',
            'firstname' => 'Имя',
            'lastname' => 'Фамилия',
            'middlename' => 'Отчество',
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
  <h3 class="m-3">Пример формы 2. Форма регистрации</h3>

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
    <label><?= ($form->attributeLabel('login') ?: 'login'); ?></label>
    <input name="<?= $form->attributeName('login'); ?>" type="text" class="form-control" 
        value="<?= $form->attributeEscapeValue('login'); ?>"
        placeholder="<?= ($form->attributeLabel('login') ?: 'login'); ?>"
    >
    <small class="form-text text-muted">Имя пользователя должно быть от 6 до 50 символов. Только буквы и цифры, знаки: _ . - </small>
  </div>
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
    <small class="form-text text-muted">Пароль должен быть от 6 до 16 символов.</small>
  </div>
  
  <div class="form-group">
    <label><?= ($form->attributeLabel('password_repeat') ?: 'password_repeat'); ?></label>
    <input name="<?= $form->attributeName('password_repeat'); ?>" type="password" class="form-control" 
        value="<?= $form->attributeEscapeValue('password_repeat'); ?>"
        placeholder="<?= ($form->attributeLabel('password_repeat') ?: 'password_repeat'); ?>"
    >
    <small class="form-text text-muted">Повтор пароля для проверки правильности ввода.</small>
  </div>
  
  <div class="form-group">
    <label><?= ($form->attributeLabel('firstname') ?: 'firstname'); ?></label>
    <input name="<?= $form->attributeName('firstname'); ?>" type="text" class="form-control" 
        value="<?= $form->attributeEscapeValue('firstname'); ?>"
        placeholder="<?= ($form->attributeLabel('firstname') ?: 'firstname'); ?>"
    >
    <small class="form-text text-muted">Макс. 100 символов. Только буквы, пробел, и дефис.</small>
  </div>
  
  <div class="form-group">
    <label><?= ($form->attributeLabel('lastname') ?: 'lastname'); ?></label>
    <input name="<?= $form->attributeName('lastname'); ?>" type="text" class="form-control" 
        value="<?= $form->attributeEscapeValue('lastname'); ?>"
        placeholder="<?= ($form->attributeLabel('lastname') ?: 'lastname'); ?>"
    >
    <small class="form-text text-muted">Макс. 100 символов. Только буквы, пробел, и дефис.</small>
  </div>
  
  <div class="form-group">
    <label><?= ($form->attributeLabel('middlename') ?: 'middlename'); ?></label>
    <input name="<?= $form->attributeName('middlename'); ?>" type="text" class="form-control" 
        value="<?= $form->attributeEscapeValue('middlename'); ?>"
        placeholder="<?= ($form->attributeLabel('middlename') ?: 'middlename'); ?>"
    >
    <small class="form-text text-muted">Макс. 100 символов. Только буквы, пробел, и дефис.</small>
  </div>
  
  <button type="submit" class="btn btn-primary">Отправить</button>
</form>
    
    </div>
  </div>

</div>
</body>
</html>
