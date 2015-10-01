<?php
ini_set ("session.use_trans_sid", true);
session_start(); 
include ('lib/connect.php');
include ('lib/fun_global.php');
header('Content-type: text/html; charset=utf-8');
echo '<form method="post" enctype="multipart/form-data" action="registration.php">
<input id="username" class="login" type="text" name="name" placeholder="Имя" /><br />
<input id="password" class="pass" type="text" name="last_name" placeholder="Фамилия" /><br />
<input id="password" class="pass" type="text" name="login " placeholder="Логин" /><br />
<input id="password" class="pass" type="password" name="password" placeholder="Пароль" /><br />
<input id="password" class="pass" type="text" name="mail" placeholder="Почта" /><br />
<input id="password" class="pass" type="text" name="country " placeholder="Страна" /><br />
<input id="password" class="pass" type="text" name="city " placeholder="Город" /><br />
<input id="password" class="pass" type="file" name="photo_user " placeholder="Фотка" /><br />
<input id="submit" class="submit" type="submit" name="sub" value="Зарегистрироваться" /><br /></form>';

?>