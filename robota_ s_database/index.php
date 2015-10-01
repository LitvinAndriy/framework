<?php 
ini_set ("session.use_trans_sid", true);
session_start(); 
header('Content-type: text/html; charset=utf-8');
include ('lib/connect.php');
include ('lib/fun_global.php');
print_r($_COOKIE['id']);
print_r($_COOKIE['login']);
print_r($_COOKIE['password']);

//Функция проверяет залогинился ли пользователь?
	proverka_loginizacii($_COOKIE['login'], $_COOKIE['password']);
	$kto = admin_auntintiphikacia($_COOKIE['login']);
	$url = andrej_get_url();
 //скрипт для админа
	if(empty($kto)){
	rabota_s_magazom();// формирует мне ссылки для соз, ред, удал магазина.
	doing_shop($url);
	}

 
 //скрипт для юзера
	if(!empty($kto)){
	 echo ' Зашол юзер ';
	 }
?>