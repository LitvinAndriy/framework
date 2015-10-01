<?php
ini_set ("session.use_trans_sid", true);
session_start(); 
include ('lib/connect.php');
include ('lib/fun_global.php');
$login = $_POST['login'];
$password = $_POST['password'];
$password = md5(md5($password));

	$ress = mysql_query("select id, login, password from users where login = '".$login."' and password = '".$password."'");
		if($row = mysql_fetch_assoc($ress))
			{
				setcookie ("login", $row['login'], time() + 50000);
				setcookie ("password", $row['password'], time() + 50000);
				setcookie ("id", $row['id'], time() + 50000, '/');
				redirect('home.php');
			}else{
				echo'Ћогин и пароль не верен';
				}
					
					
?>