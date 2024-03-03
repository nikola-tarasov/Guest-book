<?php

// функция вывода сообщений
function get_message(): array
{
    global $pdo;

    $res = $pdo->query("SELECT * FROM messages");

    return $res->fetchAll(PDO::FETCH_ASSOC);

}





// Функция для отправки сообщений
function save_message() : bool
{
    global $pdo; // подключем базу

    if (!empty($_POST['message'])) {
        $message = $_POST['message'];
        trim($message);
    }else{
        $message = null;
    }


    if (!isset($_SESSION['user']['name'])) {
        $_SESSION['errors'] = 'Необходимо авторизоваться!';
        return false;
    }


    if (empty($message)) {
        $_SESSION['errors'] = 'введите текст сообщения';
        return false;
    }

    $res = $pdo->prepare("INSERT INTO messages(name, message) VALUES (?,?)");

    $messageRes = $res->execute([$_SESSION['user']['name'], $message]);

    if ($messageRes) {
        $_SESSION['success'] = 'Ваше сообщение добавлено!';
        return true;

    }else {
        $_SESSION['errors'] = 'Ошибка!';
    }


}


// функция для авторизации
function login(): bool
{
    global $pdo; // подключем базу
    // записываем в переменную и обрезаем пробелы
    if (!empty($_POST['login'])) {
        $login = $_POST['login'];
        trim($login);
    }else{
        $login = null;
    }

    if (!empty($_POST['pass'])) {
        $pass = $_POST['pass'];
        trim($pass);
    } else {
        $pass = null;
    }

    if (empty($login) || empty($pass)) {
        $_SESSION['errors'] = 'Поля логин/пароль обязательны';
        return false;
    }
    
    
    $res = $pdo->prepare("SELECT * FROM users WHERE login = ?");
    $res->execute([$login]);

    $user = $res->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $_SESSION['errors'] = 'логин/пароль неверен!';
        return false;
    }

    if (!password_verify( $pass, $user['pass'])) {
        $_SESSION['errors'] = 'Поля логин/пароль введены неверно!';
        return false;
    }else{
        $_SESSION['success'] = 'Вы успешно авторизовались!';
        $_SESSION['user']['name'] = $user['login'];
        $_SESSION['user']['id'] = $user['id'];
        return true;

    }
}


// функция для регистрации пользователя с проверкой 
function registration(): bool 
{

    global $pdo; // подключем базу 

// записываем в переменную и обрезаем пробелы
    if (!empty($_POST['login'])) {
        $login = $_POST['login'];
        trim($login);
    }else{
        $login = null;
    }

    if (!empty($_POST['pass'])) {
        $pass = $_POST['pass'];
        trim($pass);
    } else {
        $pass = null;
    }
// конец проверки 

// если глобалная переменная post пустая одна и вторая то выдаем ошибку в сессию и возврашаем fals
    if (empty($login) || empty($pass)) {
        $_SESSION['errors'] = 'Поля логин/пароль обязательны';
        return false;
    }
    // } else{   // или возвращает tru при успешной проверке 
    //     $_SESSION['success'] = 'Вы успешно зарегестрированы!';
    //     return true;
    // }

    $res = $pdo->prepare("SELECT count(*) FROM users WHERE login=?"); // подготавливаем запрос

    $res->execute([$login]); // запускаем подготовленный запрос 
    if ($res->fetchColumn()) {   // проверяем сколько колонок пришло с совпадением с логином таким же
        $_SESSION['errors'] = 'Логин занят!'; 
        return true;
    }

    $pass = password_hash($pass, PASSWORD_DEFAULT); // кодируем пароль
    $res = $pdo->prepare("INSERT INTO users (login, pass) VALUES (?,?)"); // подготавливаем запрос
    
    if ($res->execute([$login,$pass])) {   // запускаем подготовленный запрос 
        $_SESSION['success'] = 'Успешная регистрация';
        return true;
    }else{
        $_SESSION['errors'] = 'Ошибка регистрации';
        return false;
    }
}




?>