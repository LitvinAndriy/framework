<?php
include('lib/connect.php');
if(!file_exists('images/user')){
mkdir('images/user');
}
print_r($_POST);
print_r($_FILES);

if(empty($_POST['name'])){
	echo'<div style="color:red; text-align:center; font-size:20px;">Поле имя поустое</div><br/>';
	$error=1;
}

if(empty($_POST['last_name'])){
	echo'<div style="color:red; text-align:center; font-size:20px;">Поле фамилия поустое</div><br/>';
	$error=1;
}

if(empty($_POST['login_'])){
	echo'<div style="color:red; text-align:center; font-size:20px;">Поле логин поустое</div><br/>';
	$error=1;
}

if(empty($_POST['password'])){
	echo'<div style="color:red; text-align:center; font-size:20px;">Поле пароль поустое</div><br/>';
	$error=1;
}

if(empty($_POST['mail'])){
	echo'<div style="color:red; text-align:center; font-size:20px;">Поле почта поустое</div><br/>';
	$error=1;
}

if(empty($_POST['country_'])){
	echo'<div style="color:red; text-align:center; font-size:20px;">Поле страна поустое</div><br/>';
	$error=1;
}

if(empty($_POST['city_'])){
	echo'<div style="color:red; text-align:center; font-size:20px;">Поле город поустое</div><br/>';
	$error=1;
}

if(empty($_FILES['photo_user_']['name'])){
	echo'<div style="color:red; text-align:center; font-size:20px;">Поле фото поустое</div><br/>';
	$error=1;
}



if($error!==1){
	
	$l = date(c);
	
	$name = $_FILES['photo_user_']['name'];
	$random = rand(1,100);
	$name = md5(md5($name).$random);
	$name = $name.'.jpg';
$answer = mysql_query("INSERT INTO users (name, last_name, login, password, mail, reg_date, country, city, photo_user) VALUES ('".$_POST['name']."','".$_POST['last_name']."','".$_POST['login_']."','".$_POST['password']."','".$_POST['mail']."','".$l."','".$_POST['country_']."','".$_POST['city_']."','".'images/user/'.$name."')");

//print_r($answer);
	}
	
	$directory = 'images/user/'.$name;
	 $tmp_file = $_FILES['photo_user_']['tmp_name'];
	 	 
	 if($answer==1){
	move_uploaded_file($_FILES['photo_user_']['tmp_name'], $directory);
	} 
?>