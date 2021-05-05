<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 'on');

//Устанавливаем доступы к базе данных:
$host = 'localhost'; //имя хоста, на локальном компьютере это localhost
$user = 'root'; //имя пользователя, по умолчанию это root
$password = 'root'; //пароль, по умолчанию пустой
$db_name = 'emagazin'; //имя базы данных
//Соединяемся с базой данных используя наши доступы (возвращет объект):
 $link = mysqli_connect($host, $user, $password, $db_name) or die(mysqli_error($link));
 //Устанавливаем кодировку (не обязательно, но поможет избежать проблем):
 mysqli_query($link, "SET NAMES 'utf8'");