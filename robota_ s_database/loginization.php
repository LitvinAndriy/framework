<?php
ini_set ("session.use_trans_sid", true);
session_start(); 
header('Content-type: text/html; charset=utf-8');
include ('lib/connect.php');
include ('lib/fun_global.php');
$login = $_POST['login'];
$password = $_POST['password'];
//$password = md5(md5($password));

	$ress = mysql_query("select id, login, password from users where login = '".$login."' and password = '".$password."'");
		if($row = mysql_fetch_assoc($ress))
			{	setcookie ("login", $row['login'], time() + 50000);
				setcookie ("password", $row['password'], time() + 50000);
				//setcookie ("id", $row['id'], time() + 50000, '/');
				redirect('index.php');
			}else{
			
				echo'<div style="color:red; text-align:center; font-size:20px;">Логин и пароль не верен</div>';
				echo'<h1 class="login_h1">Подписаться на открытие сайта!</h1><form method="post" action="loginization.php"><input id="username" class="login" type="text" name="login" placeholder="Login" /><br /><input id="password" class="pass" type="password" name="password" placeholder="Password" /><br /><input id="submit" class="submit" type="submit" name="sub" value="Залогиниться" /><br /></form>';
				}
					
					
?>