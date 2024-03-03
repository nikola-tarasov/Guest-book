<?php
error_reporting(-1);
session_start();

require_once 'db.php';
require_once 'funcs.php';


if (isset($_POST['register'])) {
    registration();
    header("Location: index.php");
    die;
}

if (isset($_POST['auth'])) {
    login();
    header("Location: index.php");
    die;
}

if (isset($_POST['add'])) {
    save_message();
    header("Location: index.php");
    die;
}


// выход из админки
if (!empty($_GET['do']) == 'logout') { //если пришед гет запрос
    if ($_SESSION['user']) { //проверяем авторизован ли пользователь
        unset($_SESSION['user']); //уничтожаем сессию
        header("Location: index.php");
        die;

    }
}




// var_dump($massages);

// var_dump(isset($_SESSION['user']['name']));
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Guest book</title>
</head>
<body>
    <div class="container">
        <?php
        if (!empty($_SESSION['success'])) {           
            echo'
            <div class="row justify-content-center">
                <div class="col-4 mt-3">
                    <div class="alert alert-primary" role="alert">';
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
            echo'
                    </div>
                </div>
            </div>
            ';       
        }
        ?>
        <?php      
        if (!empty($_SESSION['errors'])){ 
            echo'
            <div class="row justify-content-center">
                <div class="col-4">
                    <div class="alert alert-danger" role="alert">'; 
                        echo $_SESSION['errors'];
                        unset($_SESSION['errors']);
            echo'
                    </div>
                </div>
            </div>
            ';
        }
        ?>
<!-- конец вывода ошибок -->
<?php


if (empty($_SESSION['user']['name'])) {
    echo'
    <!-- Регистрация -->
    <div class="row justify-content-center">
        <div class="col-4">
            <h3>Регистрация</h3>
        <form method="POST" action="index.php">
    <div class="mb-3">
      <label for="exampleInputEmail1" class="form-label">Имя</label>
      <input type="text" name="login" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
    </div>
    <div class="mb-3">
      <label for="exampleInputPassword1" class="form-label">Пароль</label>
      <input type="password" name="pass" class="form-control" id="exampleInputPassword1">
    </div>
    <button type="submit" name="register" class="btn btn-primary">Зарегистрироваться</button>
    </form>
        </div>
    </div>
    <!-- Конец регистрации -->
    <!-- Авторизация -->
    <div class="row justify-content-center">
        <div class="col-4 mt-3">
            <h3>Авторизация</h3>
        <form method="POST" action="index.php">
    <div class="mb-3">
      <label for="exampleInputEmail1" class="form-label">Имя</label>
      <input type="text" name="login" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
    </div>
    <div class="mb-3">
      <label for="exampleInputPassword1" class="form-label">Пароль</label>
      <input type="password" name="pass" class="form-control" id="exampleInputPassword1">
    </div>
    <button type="submit" name="auth" class="btn btn-primary">Войти</button>
    </form>
        </div>
        </div>
        <!--Конец регистрации  -->';
}else{
    echo'
<div class="row justify-content-center">
    <div class="col-4 mt-3">
        <h4>Вы авторизовались как:'.$_SESSION['user']['name'].'</h3>
        <a href="?do=logout">Log_out</a>
    <form method="POST" action="index.php">
    <div class="mb-3">
        <label for="exampleFormControlTextarea1" class="form-label">Напишите сообщение</label>
        <textarea class="form-control" name="message" id="exampleFormControlTextarea1" rows="3"></textarea>
    </div>
    <button type="submit" name="add"  class="btn btn-primary">Отправить</button>
    </form>
    </div>
</div>


';
}
  
?>
<?php

$messages = get_message();

    foreach ($messages as $message) {
        echo'
        <!-- Вывод сообщений -->
            <div class="row justify-content-center">
                <div class="col-4 mt-4">
                    <h4>Сообщение от: '.$message['name'].'</h4>
                    <div class="border border-success p-2 mb-2">
                        '.$message['message'].'
                    <p>'.$message['created_at'].'</p>
                    </div>
                </div>
            </div>
            <!-- Конец вывода сообщений -->';
    }

?>
</div>

</body>
</html>