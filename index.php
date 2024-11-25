<?php
session_start();

function validateUsername(string $username) {
    return !empty($username);
}

function validatePassword(string $password) {
    return strlen($password) >= 8;
}

function validateEmail(string $email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validateAge(string $age) {
    return !empty($age) && is_numeric($age) && strlen((string)$age) <= 100;
}

function validateSelect($select) {
    return !empty($select);
}

function validateSex($sex) {
    return !empty($sex);
}

function validateCheckbox($checkbox) {
    return isset($checkbox);
}

$errors=[];

$username = $_POST['username'] ??'';
$password = $_POST['password'] ??'';
$email = $_POST['email'] ??'';
$age = $_POST['age'] ??'';
$select = $_POST['select'] ??'';
$sex = $_POST['radio'] ??'';
$checkbox = $_POST['checkbox'] ??'';

//валидация

if (!validateUsername($username)) {
    $errors[] = 'Ваш логин не может быть пустым.';
}
if (!validatePassword($password)) {
    $errors[] = 'Пароль должен содержать минимум 8 символов.';
}
if (!validateEmail($email)) {
    $errors[] = 'Емаил должен быть заполнен.';
}
if (!validateAge($age)) {
    $errors[] = 'Возраст должен быть заполнен и до 100 лет. Если вам 101, извините(';
}
if (!validateSelect($select)) {
    $errors[] = 'Обязательно выберите один вариант: Да или Нет.';
}
if (!validateSex($sex)) {
    $errors[] = 'Обязательно выберите пол: Мужской или Женский';
}
if (!validateCheckbox($checkbox)) {
    $errors[] = 'Вы робот? Нажмите "Я не робот", пожалуйста.';
}

//объявление ошибок, если они есть, добавляем в сессию
if (!empty($errors)) {
    $_SESSION['error'] = $errors;
    header('Location: index.php');
    exit();
}

//далее - логируем данные, если все норм и ошибок нет
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$data = [
    'date' => date('Y-m-d H:i:s'),
    'username'=> $username,
    'password'=> $hashedPassword,
    'email'=> $email,
    'age'=> $age,
    'select'=> $select,
    'radio'=> $sex,
    'checkbox'=> isset($checkbox) ? 1 : 0 //если нажат, то true - 1, иначе - false = 0
];

//запишем в json
file_put_contents('formdatafile.json', json_encode($data) . PHP_EOL, FILE_APPEND);

echo "Спасибо! Ваши данные успешно обработаны и сохранены!";


    
 
require("form.html");

