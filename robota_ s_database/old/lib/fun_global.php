<?php
include ('connect.php');

session_start();
function end_cookes(){
		setcookie ("login", "", time() - 50000, '/');
		setcookie ("password", "", time() - 50000, '/');
		setcookie ("id", "", time() - 50000, '/');
		}

function registrationCorrect() { // функция отвечает за то чтобы в регистрационной форме все поля были заполнены
	if ($_POST['name'] == "") return false;  	
	if ($_POST['last_name'] == "") return false;  	
	if ($_POST['country'] == "") return false;  	
	if ($_POST['city'] == "") return false;  	
	if ($_POST['login'] == "") return false;  	
	if ($_POST['password'] == "") return false; 
	if ($_POST['mail'] == "") return false; 
	return true; 
}

function loginisationCorrect(){
	if ($_POST['login'] == "") return false;  	
	if ($_POST['password'] == "") return false; 
	return true;
}



function registr_admin()
{
	$adm = mysql_query("SELECT * FROM users");
	$adm_rez = @mysql_fetch_assoc($adm);
	if ($adm_rez['adm'] == 0)
    {	
		return true;
	}
}


function admin_auntintiphikacia($cookie_login)
{
	$adm_aunt = mysql_query("SELECT * FROM users where login = '".$cookie_login."' and adm = 1");
	if (@mysql_num_rows($adm_aunt) != 0)  
    {	
		return true;
	}
}



	
function redirect($url)
		{
			echo '<script type="text/javascript">
						// Javascript URL redirection
							window.location.replace("'.$url.'");
							</script>';
		}
		


function del_coocie_session(){
				end_cookes();
				session_unset();
				session_destroy();		
				redirect('index.php');			
}



function url(){
	$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; // эта функция получает текущий url со страницы на которой я нахожусь работает она автоматически вследствии того что function_global.php заинклужен к тем страницам urlы с которых мне нужно получать при перемещении на них она получает url автоматически и постоянно при перемещении по ним
	return $url;
}


/*это форма выхода и задействования убоя куков и сесии*/
function menu_admin(){
	echo'<div class="menu_admin">
							<ul class="submenu">
						<li> <a href="http://progect/index.php*exit">ВЫХОД </a> </li>
					</ul> 
				</div>';

}


function show_avatarka($id){

	$result = mysql_query("select photo_user from users where id = '".$id."'");
	@$row = mysql_fetch_assoc($result);
	return'<img src="'.$row['photo_user'].'">';

}


/*Это функция ресайза она пизженая но тем не мение работает как часы (разобрать для себя)*/
function img_resize($src, $dest, $width, $height)
{
  $rgb=0xFFFFFF;
  $quality=100;
  if (!file_exists($src)) return false;
 
  $size = getimagesize($src);
 
  if ($size === false) return false;
 
  $format = strtolower(substr($size['mime'], strpos($size['mime'], '/')+1));
  $icfunc = "imagecreatefrom" . $format;
  if (!function_exists($icfunc)) return false;
 
  $x_ratio = $width / $size[0];
  $y_ratio = $height / $size[1];
 
  $ratio       = min($x_ratio, $y_ratio);
  $use_x_ratio = ($x_ratio == $ratio);
 
  $new_width   = $use_x_ratio  ? $width  : floor($size[0] * $ratio);
  $new_height  = !$use_x_ratio ? $height : floor($size[1] * $ratio);
  $new_left    = $use_x_ratio  ? 0 : floor(($width - $new_width) / 2);
  $new_top     = !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);
 
  $isrc = $icfunc($src);
  $idest = imagecreatetruecolor($width, $height);
 
  imagefill($idest, 0, 0, $rgb);
  imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0, 
    $new_width, $new_height, $size[0], $size[1]);
 
  imagejpeg($idest, $dest, $quality);
 
  imagedestroy($isrc);
  imagedestroy($idest);
 
  return true;
 
}


function tabl_users(){
	echo '<div class="line_admin_right_block"><span>Меню юзеров</span></div>';
	echo'<table class="table_content"><tr>
	<td class="tabl_users_head_id">id</td>
	<td class="tabl_users_head_id">Имя</td>
	<td class="tabl_users_head_id">Фамилия</td>
	<td class="tabl_users_head_id">Логин</td>
	<td class="tabl_users_head_id">E-mail</td>
	<td colspan="2" class="tabl_users_head_id">Редактировать</td>
	</tr>';
	
	$select = mysql_query("select * from users");
	while($select_obr = mysql_fetch_assoc($select)){

		echo '<tr">
		<td class="tabl_users_id">'.$select_obr['id'].'</td>
		<td class="tabl_users_id">'.$select_obr['name'].'</td>
		<td class="tabl_users_id">'.$select_obr['last_name'].'</td>
		<td class="tabl_users_id">'.$select_obr['login'].'</td>
		
		<td class="tabl_users_id">'.$select_obr['mail'].'</td>
		
		<td class="tabl_users_delete"><button  class="submit" onclick="delete_in_tabl_users_profile('.$select_obr['id'].')"><i class="kn_d  fa-times"></i>Удалить</button></a></td>
	
		<td class="tabl_users_redact"><button  class="submit" onclick="redact_in_tabl_users_profile_ajax('.$select_obr['id'].')"><i class="kn_r  fa-pencil"></i>Изменить</button></a></td></tr>';
	}	
	echo'</table>';
}

function view_and_redact_profile_user($id){
echo '<div class="line_admin_right_block"><span>Меню юзеров редактирование</span></div>';
	$select = mysql_query("SELECT * FROM users WHERE id = '".$id."' ");
	$select_obr = mysql_fetch_assoc($select);
	echo'<div class="view_profile_user">
		
		<div class="view_profile_user_id">id: <input class="id" id="view_profile_user_id_inp" value="'.$select_obr['id'].'" name="id"></div>
		<div class="view_profile_user_name">Имя: <input class="name" id="view_profile_user_name_inp" value="'.$select_obr['name'].'" name="name"></div>
		<div class="view_profile_user_last_name">Фамилия: <input class="last_name" id="view_profile_user_last_name_inp" value="'.$select_obr['last_name'].'" name="last_name"></div>
		<div class="view_profile_user_login">Логин: <input class="login" id="view_profile_user_login_inp" value="'.$select_obr['login'].'" name="login"></div>
		
		<div class="view_profile_user_mail">E-mail: <input class="mail" id="view_profile_user_mail_inp" value="'.$select_obr['mail'].'" name="mail"></div>
		<div class="view_profile_user_reg_date">Дата регистрации: <input id="view_profile_user_reg_date_inp" value="'.$select_obr['reg_date'].'" name=""></div>
		
		<div class="view_profile_user_admin">Админские права: <input class="adm" id="view_profile_user_admin_inp" value="'.$select_obr['adm'].'" name=""></br></br></div>
		<button class="submit" onclick="redact_and_save_profile_users()">Сохранить</button>
		
		</div>';
}

/*функция для создания товара в рубрике для этого делаем селект всего что в БД с pid = 0 и запихиваем в селект и цикл*/
function add_tovar(){
echo '<div class="line_admin_right_block"><span>Добавление товара</span></div>';
echo '<form id="add_tovar_form" action="#" method="post" enctype="multipart/form-data">';
	echo '<div id="add_name_tovar">Название товара: <input class="name" id="add_name_tovar_inp" name="name"></div>
		<div id="add_sex_category_tovar">Принадлежность:
		<select class="sex_category" id="add_sex_category_tovar_inp_sel" name="sex_category">
		<option value="man">man</option>
		<option value="woman">woman</option>
		</select></div>';

	echo'<div id="add_clothing_category_tovar"> Категория:<select class="clothing_category" name="clothing_category">';
		$select_razdel = mysql_query("SELECT * FROM razdel");
		while($select_razdel_obr = mysql_fetch_assoc($select_razdel)){
		echo '<option value="'.$select_razdel_obr['name_razdel'].'">"'.$select_razdel_obr['name_razdel'].'"</option>';
		}
	echo '</select></div>';
		
	echo	'<div id="add_clothing_category_tovar">Рубрика:		
		<select class="rubric" id="add_clothing_category_tovar_inp_sel" name="rubric">';
		$select = mysql_query("select * from tovar where parent_id = 0");
        while($select_obr = mysql_fetch_assoc($select)){
	echo	'<option value="'.$select_obr['id'].'">"'.$select_obr['name_content'].'"</option>';
		}
	echo '</select></div>'; 
   
	echo '<div id="add_clothing_category_tovar">Магазин:		
		<select class="magizin" id="add_clothing_category_tovar_inp_sel" name="magizin">';
		$select_magazin = mysql_query("select * from magazin");
        while($select_magazin_obr = mysql_fetch_assoc($select_magazin)){
	echo '<option value="'.$select_magazin_obr['id'].'">"'.$select_magazin_obr['name_magazin'].'"</option>';
		}
	echo '</select></div>';
	
	echo'<div id="adres_tovara">URL адрес товара:<input class="adres" id="" name="adres"></div>';

   echo'<div id="add_description_tovar">Загрузить фото: <input style=" -moz-opacity: 1; filter: alpha(opacity=1); opacity: 1; font-size: 15px; height: 31px; width:78px; margin-left:70px" class="submit"  id="filename" type="file" name="filename"/></div>';
   
	echo'<div id="add_description_tovar">Описание: <p><textarea class="description" id="add_description_tovar_inp" rows="5" cols="45" name="text"></textarea></p></div>';
	echo '</form>'; 	
	echo '<button class="submit"  onclick="add_tovar()">Сохранить</button>';
}


/*--------------------------------------------------------------------------------*/

function view_and_add_rubric(){
echo '<div class="line_admin_right_block"><span>Создание и редактирование рубрик</span></div>';
	echo'<div class="tabl_users_create"><button class="submit" onclick="add_new_rubrica_ajax()">Создать новую рубрику</button></a></div>';
	echo'<div class="tabl_users_create"><button class="submit" onclick="add_new_razdel_ajax()">Создать новый раздел</button></a></div>'; 

	echo'<table class="table_content">
	<tr>
	<td  style="width:743px;" class="tabl_users_head_id">Название рубрики</td>
	<td colspan="3" class="tabl_users_head_id">Редактировать</td>
	</tr>';
	
	$select = mysql_query("select * from tovar where parent_id = 0");
	while($select_obr = mysql_fetch_assoc($select)){
	echo '<tr>
		<td class="tabl_users_id">'.$select_obr['name_content'].'</td>
		
		<td class="tabl_users_delete"><button class="submit" onclick="del_rubrici_and_sodergimoe_rubrici_ajax('.$select_obr['id'].')"><i class="kn_d  fa-times"></i>Удалить</button></a></td>
	
		<td class="tabl_users_delete"><button class="submit" onclick="view_and_redact_veszi_ajax('.$select_obr['id'].')"><i class="kn_r  fa-pencil"></i>Редактировать содержимое</button></a></td>
		
		<td class="tabl_users_delete"><button class="submit" onclick="view_and_redact_rubrica_ajax('.$select_obr['id'].')"><i class="kn_r  fa-pencil"></i>Редактировать рубрику</button></a></td>
		
		</tr>';

	}

}

function add_new_rubrica_form(){
echo '<div class="line_admin_right_block"><span>Создание новой рубрики</span></div>';
	$select_razdel = mysql_query("SELECT * FROM razdel");
	
	echo '<div class="create_rubrica_inp_name">Название рубрики: <input class="name" name="name"></div>
	<div class="create_rubrica_inp_name_sex_category">Категория:<select class="sex_category" name="sex_category"><option value="man">man</option><option value="woman">woman</option></select></div>  
	<div class="create_rubrica_inp_clothing_category">
	Категория одежды:<select class="clothing_category" name="clothing_category">';
		while($select_razdel_obr = mysql_fetch_assoc($select_razdel)){
	echo '<option value="'.$select_razdel_obr['name_razdel'].'">"'.$select_razdel_obr['name_razdel'].'"</option>';
	}
	echo '</select></div>';
	echo'<div class="create_rubrica_inp_description">Описание: <p><textarea id="create_rubrica_inp_description_ta" class="description" rows="5" cols="45" name="text"></textarea></p></div>
	<button id="create_rubrica_inp_but" onclick="add_and_save_new_rubrica_ajax()">Создать</button>';

}

/*----------инсерт раздела--------------*/
function add_new_razdel(){

	$name_razdel = $_POST['name_razdel'];

	$insert = mysql_query("INSERT INTO razdel (name_razdel) VALUE ('".$name_razdel."')");

	if(isset($insert)){
				echo 'Запись произведена';
			}

}
/*----------инсерт раздела--------//------*/

function add_new_rubrica(){

	$name = $_POST['name'];
	$sex_category = $_POST['sex_category'];
	$clothing_category = $_POST['clothing_category'];
	$description = $_POST['description'];

		$insert = mysql_query("INSERT INTO tovar (parent_id, name_content, sex_category, clothing_category, description) VALUES (0, '".$name."','".$sex_category."','".$clothing_category."','".$description."')");

		if(isset($insert)){
			echo 'Запись произведена';
		}


}


function redact_rubrica($id){ 
	$id = $_POST['id'];
	
	$select = mysql_query("SELECT * FROM tovar WHERE id = '".$id."'");
	
	
	$select_obr = mysql_fetch_assoc($select);
echo '<div class="line_admin_right_block"><span>Редактирование корня рубрики</span></div>';
		echo '			
			<div id="redact_veszi_id">id: <input id="redact_veszi_id_inp" class="id" value="'.$select_obr['id'].'" name="id"></div>
			<div id="redact_veszi_name_content" >Название рубрики: <input id="redact_veszi_name_content_inp" class="name_content" value="'.$select_obr['name_content'].'" name="name_content"></div>
			<div id="redact_veszi_sex_category">Принадлежность:<select id="redact_veszi_sex_category_inp_sel" class="sex_category" name="sex_category"><option value="man">man</option><option value="woman">woman</option></select></div>
			<div class="create_rubrica_inp_clothing_category">
			Категория одежды:<select class="clothing_category" name="clothing_category">';
			$select_razdel = mysql_query("SELECT * FROM razdel");
				while($select_razdel_obr = mysql_fetch_assoc($select_razdel)){
			echo '<option value="'.$select_razdel_obr['name_razdel'].'">"'.$select_razdel_obr['name_razdel'].'"</option>';
			}
			echo '</select></div>
			
			<button  class="submit" onclick="save_update_rubrica_ajax()">Сохранить</button></div>';
		
}


function redact_rubrica_update(){

	$id = $_POST['id'];
	$name_content = $_POST['name_content'];
	$sex_category = $_POST['sex_category'];
	$clothing_category = $_POST['clothing_category'];

	$update = mysql_query("UPDATE tovar SET name_content='".$name_content."', sex_category='".$sex_category."', clothing_category='".$clothing_category."' WHERE id='".$id."'");
		if(!empty($id)){
		echo 'UPDATE успешно выполнен!';
	}

}



function del_rubrici_and_sodergimoe_rubrici($id){

	echo $id = $_POST['id'];
	$del = mysql_query("DELETE FROM tovar WHERE id = '".$id."'");
	$del_2 = mysql_query("DELETE FROM tovar WHERE parent_id = '".$id."'");
	
	if(isset($del)){
	
		echo 'запись удалена';
	
	}
	

}

function get_viszi_iz_rubric($id){
echo '<div class="line_admin_right_block"><span>Редактирование содержимого рубрики</span></div>';
	$id = $_POST['id'];
	echo '<table class="table_content">';
	echo'<tr id="tabl_rubric_head">
	<td  style="width: 695px;" class="tabl_users_head_id">Название вещи</td>
	<td  class="tabl_users_head_id" colspan="2">Редактировать</td>
	</tr>';
	
	$select = mysql_query("SELECT * FROM tovar WHERE parent_id = '".$id."'");
	while($select_obr = mysql_fetch_assoc($select)){
    echo '<tr>';
	echo '<td class="tabl_users_id">'.$select_obr['name_content'].'</td>';
	
	echo '<td class="tabl_users_delete"><button class="submit" onclick="delete_veszi_ajax('.$select_obr['id'].')"><i class="kn_d fa-times"></i>Удалить</button></a></td>';
	echo '<td class="tabl_users_delete"><button class="submit"  onclick="redact_veszi_ajax('.$select_obr['id'].')"><i class="kn_r fa-pencil"></i>Редактировать</button></a></td>';
	  echo '</tr>';
	}
	echo '</table>';
}



function add_new_vesz(){
	
	$name = $_POST['name'];
	$sex_category = $_POST['sex_category'];
	$clothing_category = $_POST['clothing_category'];
	$description = $_POST['description'];

		$insert = mysql_query("INSERT INTO tovar (parent_id, name_content, sex_category, clothing_category, description) VALUES ('".$id."', '".$name."','".$sex_category."','".$clothing_category."','".$description."')");

		if(isset($insert)){
			echo 'Запись произведена';
		}


}


function redact_viszi_iz_rubric($id){
echo '<div class="line_admin_right_block"><span>Редактирование содержимого конкретной рубрики</span></div>';
	$id = $_POST['id'];
	$select = mysql_query("SELECT * FROM tovar WHERE id = '".$id."'");
	while($select_obr = mysql_fetch_assoc($select)){

		echo '			
			<div id="redact_veszi_id">id: <input id="redact_veszi_id_inp" class="id" value="'.$select_obr['id'].'" name="id"></div>
			<div id="redact_veszi_name_content" >Название вещи: <input id="redact_veszi_name_content_inp" class="name_content" value="'.$select_obr['name_content'].'" name="name_content"></div>
			<div id="redact_veszi_sex_category">Принадлежность:<select id="redact_veszi_sex_category_inp_sel" class="sex_category" name="sex_category"><option value="man">man</option><option value="woman">woman</option></select></div>';
	
	$select_razdel = mysql_query("SELECT * FROM razdel");
	echo'<div id="add_clothing_category_tovar">Категория:		
			<select class="clothing_category" id="add_clothing_category_tovar_inp_sel" name="clothing_category">';
        while($select_razdel_obr = mysql_fetch_assoc($select_razdel)){
	echo	'<option value="'.$select_razdel_obr['name_razdel'].'">"'.$select_razdel_obr['name_razdel'].'"</option>';
		}
	echo '</select></div>';
	
	$select_rubrika = mysql_query("SELECT * FROM tovar WHERE parent_id = 0");
	echo'<div id="add_clothing_category_tovar">Рубрика:		
			<select class="rubrika" id="add_clothing_category_tovar_inp_sel" name="rubrika">';
        while($select_rubrika_obr = mysql_fetch_assoc($select_rubrika)){
	echo	'<option value="'.$select_rubrika_obr['id'].'">"'.$select_rubrika_obr['name_content'].'"</option>';
		}
	echo '</select></div>';
		
	$select_magazin = mysql_query("select * from magazin");	
	echo'<div id="add_clothing_category_tovar">Магазин:		
			<select class="magizin" id="add_clothing_category_tovar_inp_sel" name="magizin">';
        while($select_magazin_obr = mysql_fetch_assoc($select_magazin)){
	echo	'<option value="'.$select_magazin_obr['id'].'">"'.$select_magazin_obr['name_magazin'].'"</option>';
		}
	echo '</select></div>';	
	
	
	echo'	<div id="redact_veszi_clothing_category">URL адрес: <input id="redact_veszi_clothing_category_inp" class="url_tovara" value="'.$select_obr['url_tovara'].'" name="url_tovara"></div>
			<div id="redact_veszi_description">Описание: <p><textarea id="redact_veszi_description_inp" class="description" value="'.$select_obr['description'].'" rows="5" cols="45" name="text"></textarea></p></div>
			<button class="submit" onclick="save_update_veszi_ajax()">Сохранить</button></div>';
			
	}
}

function save_update_veszi_iz_rubric(){

	$id = $_POST['id'];
	$name_content = $_POST['name_content'];
	$sex_category = $_POST['sex_category'];
	$magizin = $_POST['magizin'];
	$clothing_category = $_POST['clothing_category'];
	$url_tovara = $_POST['url_tovara'];
	$rubrika = $_POST['rubrika'];
	$description = $_POST['description'];
	

	$update = mysql_query("UPDATE tovar SET name_content='".$name_content."', sex_category='".$sex_category."', name_magazin='".$magizin."', clothing_category='".$clothing_category."', url_tovara='".$url_tovara."', parent_id='".$rubrika."', description='".$description."' WHERE id='".$id."'");
		if(!empty($id)){
		echo 'UPDATE успешно выполнен!';
	}
}

function del_viszi_iz_rubric($id){

	echo $id = $_POST['id'];
	$del = mysql_query("DELETE FROM tovar WHERE id = '".$id."'");
	
	if(isset($del)){
	
		echo 'запись удалена';
	
	}

}


function view_and_redact_veszi(){//!!!!!!!!!!!!

	$select = mysql_query("select * from tovar where parent_id = '".$id."'");
	while($select_obr = mysql_fetch_assoc($select)){
		echo '<div class="tabl_users_id">'.$select_obr['parent_id'].'</div>
		<div class="tabl_users_name">'.$select_obr['name_content'].'</div>
		<div class="tabl_users_last_name">'.$select_obr['sex_category'].'</div>
		<div class="tabl_users_last_name">'.$select_obr['sex_category'].'</div>
		<div class="tabl_users_login">'.$select_obr['login'].'</div>
		
		<div class="tabl_users_reg_date"><button onclick="">Удалить</button></a></div>

		<div class="tabl_users_admin"><button onclick="">редактировать</button></a></br></div>';

	}

}

/*-------магазины--------------------------------------------------*/

function view_and_add_magazin(){
echo '<div class="line_admin_right_block"><span>Создание и редактирование магазина</span></div>';

	echo'<div class="tabl_users_create"><button class="submit" onclick="create_add_magazin()">Добавить новый магазин</button></a></div>';

	echo'<table class="table_content">
	<tr>
	<td style="width:370px;" class="tabl_users_head_id">Название магазина</td>
	<td style="width:370px;" class="tabl_users_head_id">Адрес магазина</td>
	<td colspan="2" class="tabl_users_head_id">Редактировать</td>
	</tr>';
	
	$select = mysql_query("select * from magazin");
	while($select_obr = mysql_fetch_assoc($select)){
	echo '<tr>
		<td class="tabl_users_id" class="">'.$select_obr['name_magazin'].'</td>
		<td class="tabl_users_id" class="">'.$select_obr['url_magazin'].'</td>
		
		<td class="tabl_users_delete"><button class="submit" onclick="del_magazin_ajax('.$select_obr['id'].')"><i class="kn_d fa-pencil"></i>Удалить</button></a></td>
	
		<td class="tabl_users_delete"><button class="submit" onclick="redact_magazin_ajax('.$select_obr['id'].')"><i class="kn_r  fa-pencil"></i>Редактировать</button></a></td></tr>';

	}

}

/* function add_new_magazin(){
	$name = $_POST['name'];
	$url_magazin = $_POST['url_magazin'];

		$insert = mysql_query("INSERT INTO magazin (name_magazin, url_magazin) VALUES ('".$name."', '".$url_magazin."')");

		if(isset($insert)){
			echo 'Запись произведена';
		}


} */

function del_magazin($id){

	echo $id = $_POST['id'];
	$del = mysql_query("DELETE FROM magazin WHERE id = '".$id."'");
	
	if(isset($del)){
	
		echo 'запись удалена';
	
	}
	
}


function redact_magazin($id){
echo '<div class="line_admin_right_block"><span>Редактирование корня магазина</span></div>';
	$id = $_POST['id'];
	$select = mysql_query("SELECT * FROM magazin WHERE id = '".$id."'");
	while($select_obr = mysql_fetch_assoc($select)){

		echo '<form id="red_new_magazin_ajax" enctype="multipart/form-data" method="post" action="#">			
			<div id="redact_veszi_id">id: <input id="redact_veszi_id_inp" class="id" value="'.$select_obr['id'].'" name="id"></div>
			<div id="redact_veszi_name_content" >Название магазина: <input id="redact_veszi_name_content_inp" class="name_magazin" value="'.$select_obr['name_magazin'].'" name="name_magazin"></div>
			<div id="redact_veszi_clothing_category">URL адрес магазина: <input id="redact_veszi_clothing_category_inp" class="url_magazin" value="'.$select_obr['url_magazin'].'" name="url_magazin"></div>
			
			<div id="redact_veszi_clothing_category">Действующее фото: <img id="" src='.$select_obr['url_photo'].'></div>
			
			<div class="photo_creat_magaz">Фото магазина: <input class="photo_creat_magaz_form" type="file"  name="filename" ></div></form>
			<button  class="submit" onclick="save_update_magazin_ajax()">Сохранить</button></div>';
			
	}
}

/* function save_update_magazin(){

	$id = $_POST['id'];
	$name_magazin = $_POST['name_magazin'];
	$url_magazin = $_POST['url_magazin'];
	

	$update = mysql_query("UPDATE magazin SET name_magazin='".$name_magazin."', url_magazin='".$url_magazin."' WHERE id='".$id."'");
		if(!empty($id)){
		echo 'UPDATE успешно выполнен!';
	}
}
 */
/*-------магазины---------------------//-----------------------------*/

/*-------статьи----------------------------------------------------*/

function view_and_add_and_redact_statei(){
echo '<div class="line_admin_right_block"><span>Создание и редактирование статей</span></div>';
		echo'<div class="tabl_users_create"><button class="submit" onclick="create_new_statei_ajax()">Написать новую статью</button></a></div>';

		
		echo'<table class="table_content">
	<tr>
	<td style="width:692px;" class="tabl_users_head_id">Название статьи</td>
	
	<td colspan="2" class="tabl_users_head_id">Редактировать</td>
	</tr>';
	
	$select = mysql_query("SELECT * FROM statei");
	while($select_obr = mysql_fetch_assoc($select)){
	echo '<tr>
		<td class="tabl_users_id" class="">'.$select_obr['name_statei'].'</td>
	
		
		<td class="tabl_users_delete"><button class="submit" onclick="del_statei_ajax('.$select_obr['id'].')"><i class="kn_d fa-pencil"></i>Удалить</button></a></td>
	
		<td class="tabl_users_delete"><button class="submit" onclick="redact_statei_ajax('.$select_obr['id'].')"><i class="kn_r  fa-pencil"></i>Редактировать</button></a></td></tr>';

	}
}

function create_new_statei(){
		echo '<div class="line_admin_right_block"><span>Новая статья</span></div>';
		echo '<form method="post" action="../ajax/create_and_save_new_statei.php"> <!-- в этой форме мы считываем параметр из текстового поля и отправляем его методом POST сюдаже где его и считываем ниже тем же методом POST-->
		<div class="view_profile_user_id">
		Заголовок: <input id="name_statei" type="text" name="name_statei" />
		</div>
		<div class="view_profile_user_id">
		Принадлежность:
		<select class="sex" id="add_sex_category_tovar_inp_sel" name="sex">
		<option value="man">man</option>
		<option value="woman">woman</option>
		</select></div>
		
		<div class="ck">
		<textarea name="ckeditor" id="ckeditor" cols="45" rows="5"></textarea>
		<script type="text/javascript">
		CKEDITOR.replace("ckeditor");
		</script>
		<input type="submit" class="submit"  name="OK" value="Создать статью">
		</div>
		</form>';
		
}


function save_new_statei(){

if (!empty($_POST['name_statei']) and !empty($_POST['ckeditor']) and !empty($_POST['sex']))
		{
			if(mysql_query("INSERT INTO statei (name_statei, body_statei, sex) VALUES ('".$_POST['name_statei']."','".$_POST['ckeditor']."', '".$_POST['sex']."')")){ // в этом запросе в БД мы производим запись в таблицу подрубрики в колонки id_rubriki и собственно название name_pod_rubriki в которые кладем значения нашей "массивной" переменной $expload_2[1] и POSTа пришедшего к нам из формы $_POST['name_content']
				echo 'INSERT успешно выполнен!';
				redirect('../index.php'); 
				}
		
		}

}


function del_statei($id){

	echo $id = $_POST['id'];
	$del = mysql_query("DELETE FROM statei WHERE id = '".$id."'");
	
	if(isset($del)){
	
		echo 'запись удалена';
	
	}
	
}

function redact_statei($id){
echo '<div class="line_admin_right_block"><span>Редактирование статьи</span></div>';
	$id = $_POST['id'];
	$select = mysql_query("SELECT * FROM statei WHERE id = '".$id."'");
	while($select_obr = mysql_fetch_assoc($select)){

		echo '<form method="post" action="../ajax/update_and_save_statei.php"> 
		<div class="view_profile_user_id">
		id: <input id="redact_veszi_id_inp" class="id" value="'.$select_obr['id'].'" name="id"> 
		</div>
		<div class="view_profile_user_id">
		Название статьи: <input id="name_statei" type="text" value="'.$select_obr['name_statei'].'" name="name_statei" />
		</div>
		<div class="view_profile_user_id">
		Принадлежность:
		<select class="sex" id="add_sex_category_tovar_inp_sel" name="sex">
		<option value="man">man</option>
		<option value="woman">woman</option>
		</select>
		</div>
		<div class="ck">
		<textarea name="ckeditor" id="ckeditor" cols="45" rows="5" value="'.$select_obr['body_statei'].'"></textarea>
		<script type="text/javascript">
		CKEDITOR.replace("ckeditor");
		</script>
		<input type="submit" class="submit" name="OK" value="Сохранить изменения в статье">
		</div>
		</form>';
	}
}

function update_statei(){

$id = $_POST['id'];
$name_statei = $_POST['name_statei'];
$ckeditor = $_POST['ckeditor'];
$sex = $_POST['sex'];

			$update = mysql_query("UPDATE statei SET name_statei = '".$name_statei."', body_statei = '".$ckeditor."', sex = '".$sex."' WHERE id='".$id."'");
				echo 'UPDATE успешно выполнен!';
				redirect('../index.php'); 
				
}




/*-------статьи---------------------//-----------------------------*/


function show_random_tovar_admin(){ 

	$select = mysql_query("SELECT * FROM tovar where parent_id != 0");
	while($select_obr = mysql_fetch_assoc($select)){
	$select_name_magazin = mysql_query("SELECT * FROM magazin where id = '".$select_obr['name_magazin']."'");
	$select_name_magazin_obr = mysql_fetch_assoc($select_name_magazin);
	echo '<div class="show_random_tovar">
	<div id="show_random_tovar_photo_tovara"><a href="index.php?'.$select_obr['id'].'"><img id="show_image_tovar_gl_str" src='.$select_obr['photo_tovara'].'></a></div>
	<div id="show_random_tovar_name"><a href="index.php?'.$select_obr['id'].'">'.$select_obr['name_content'].'</a></div>';
	//echo '<div id="show_random_tovar_name_magazin"><a href="'.$select_name_magazin_obr['url_magazin'].'">'.$select_name_magazin_obr['name_magazin'].'</a></div>';

		$select_user_dobavil = mysql_query("SELECT id_user FROM my_veszi WHERE id_tovar = '".$select_obr['id']."'");
		$select_user_dobavil_obr = mysql_fetch_assoc($select_user_dobavil);
		$select_name_user = mysql_query("SELECT * FROM users where id = '".$select_user_dobavil_obr['id_user']."'");
		$select_name_user_obr = mysql_fetch_assoc($select_name_user);
		if($select_name_user_obr['id'] != $_COOKIE['id']){

				if(!empty($select_name_user_obr['photo_user'])){
					echo '<div class="show_random_tovar_name_user"><a href="index.php?user%'.$select_name_user_obr['id'].'"><img id="" src='.$select_name_user_obr['photo_user'].'></a></div>';
					echo '<div class="kto_sohranil">Сохранено&nbsp;<a href="index.php?user%'.$select_name_user_obr['id'].'">'.$select_name_user_obr['login'].'</a></div>';
					echo '<div class="show_random_tovar_name_magazin">Магазин &nbsp;<span><a href="index.php?magazin='.$select_name_magazin_obr['id'].'">'.$select_name_magazin_obr['name_magazin'].'</a></span></div>';
				}
				else{
					//echo '<div class="show_random_tovar_name_user">111<a href="index.php?user%'.$select_name_user_obr['id'].'">'.$select_name_user_obr['login'].'</a></div>';
					echo '<div class="show_random_tovar_name_user"><img  src="images/no-photo.jpg"></div>';
					echo '<div class="kto_sohranil">Сохранено&nbsp;<span>Системой</span></div>';
					echo '<div class="show_random_tovar_name_magazin">Магазин <span><a href="index.php?magazin='.$select_name_magazin_obr['id'].'">'.$select_name_magazin_obr['name_magazin'].'</a></span></div>';
				
				}
			}
			
		else{
				if(!empty($select_name_user_obr['photo_user'])){
					echo '<div class="show_random_tovar_name_user"><a href="index.php?polzovatel%'.$select_name_user_obr['id'].'"><img id="" src='.$select_name_user_obr['photo_user'].'></a></div>';
					echo '<div class="kto_sohranil">Сохранено&nbsp;<a href="index.php?polzovatel%'.$select_name_user_obr['id'].'">'.$select_name_user_obr['login'].'</a></div>';
					echo '<div class="show_random_tovar_name_magazin">Магазин<span><a href="index.php?magazin='.$select_name_magazin_obr['id'].'">'.$select_name_magazin_obr['name_magazin'].'</a></span></div>';
				}
				else{
					echo '<div class="show_random_tovar_name_user"><a href="index.php?polzovatel%'.$select_name_user_obr['id'].'"><img  src="images/no-photo.jpg"></a></div>';
				}
			}
		echo '<div id="rubric_redact_but_random"><button onclick="redact_veszi_ajax('.$select_obr['id'].')"><i class="kn_r fa-pencil"></i></button></a></br></div>
	 <div id="rubric_delete_but_random"><button onclick="delete_veszi_ajax('.$select_obr['id'].')"><i class="kn_d fa-times"></i></button></a></br></div>
	 </div>';
	}
}

function show_random_tovar_user(){ 

	$select = mysql_query("SELECT * FROM tovar where parent_id != 0");
	while($select_obr = mysql_fetch_assoc($select)){
	
	$select_name_magazin = mysql_query("SELECT * FROM magazin where id = '".$select_obr['name_magazin']."'");
	$select_name_magazin_obr = mysql_fetch_assoc($select_name_magazin);
	echo '<div class="show_random_tovar">
	<div class="im_block"><a href="index.php?'.$select_obr['id'].'"><img id="show_image_tovar_gl_str" src='.$select_obr['photo_tovara'].'></a></div>
	<div id="show_random_tovar_name"><a href="index.php?'.$select_obr['id'].'">'.$select_obr['name_content'].'</a></div>';
	echo '<div id="show_random_tovar_name_magazin"><a href="index.php?magazin'.$select_name_magazin_obr['id'].'">'.$select_name_magazin_obr['name_magazin'].'</a></div>'; // !!!!!!!!!!!!это юрл магазина!!!!!!!!!
	if(isset($_COOKIE['login']) && isset($_COOKIE['password'])){
		 $select_user_dobavil = mysql_query("SELECT id_user FROM my_veszi WHERE id_tovar = '".$select_obr['id']."'");
			$select_user_dobavil_obr = mysql_fetch_assoc($select_user_dobavil);
			$select_name_user = mysql_query("SELECT * FROM users where id = '".$select_user_dobavil_obr['id_user']."'");
			$select_name_user_obr = mysql_fetch_assoc($select_name_user);
			if($select_name_user_obr['id'] != $_COOKIE['id']){
			
				if(!empty($select_name_user_obr['photo_user'])){
					echo '<div class="show_random_tovar_name_user"><img id="" src='.$select_name_user_obr['photo_user'].'></div>';
					echo '<div class="kto_sohranil">Сохранено&nbsp;<span><a href="index.php?user%'.$select_name_user_obr['id'].'">'.$select_name_user_obr['login'].'</a></span></div>';
					echo '<div class="show_random_tovar_name_magazin">Магазин <span><a href="index.php?magazin='.$select_name_magazin_obr['id'].'">'.$select_name_magazin_obr['name_magazin'].'</a></span></div>';
				}
				else{
						echo '<div class="show_random_tovar_name_user"><img  src="images/no-photo.jpg"></div>';
					echo '<div class="kto_sohranil">Сохранено&nbsp;<span>Системой</span></div>';
					echo '<div class="show_random_tovar_name_magazin">Магазин <span><a href="index.php?magazin='.$select_name_magazin_obr['id'].'">'.$select_name_magazin_obr['name_magazin'].'</a></span></div>';
				}
			}
			
		else{
				
				if(!empty($select_name_user_obr['photo_user'])){
					echo '<div class="show_random_tovar_name_user"><a href="index.php?polzovatel%'.$select_name_user_obr['id'].'"><img id="" src='.$select_name_user_obr['photo_user'].'></a></div>';
					echo '<div class="kto_sohranil">Сохранено&nbsp;<span><a href="index.php?polzovatel%'.$select_name_user_obr['id'].'">'.$select_name_user_obr['login'].'</a></span></div>';
					echo '<div class="show_random_tovar_name_magazin">Магазин <span><a href="index.php?magazin='.$select_name_magazin_obr['id'].'">'.$select_name_magazin_obr['name_magazin'].'</a></span></div>';
				
				
					//echo '<div class="show_random_tovar_name_user"><a href="index.php?polzovatel%'.$select_name_user_obr['id'].'">'.$select_name_user_obr['login'].'<img id="" src='.$select_name_user_obr['photo_user'].'></a></div>';
				}
				else{
					echo '<div class="show_random_tovar_name_user"><a href="index.php?polzovatel%'.$select_name_user_obr['id'].'">'.$select_name_user_obr['login'].'</a></div>';
				}
			}		
		}
		
	else{
			$select_user_dobavil = mysql_query("SELECT id_user FROM my_veszi WHERE id_tovar = '".$select_obr['id']."'");
			$select_user_dobavil_obr = mysql_fetch_assoc($select_user_dobavil);
			$select_name_user = mysql_query("SELECT * FROM users where id = '".$select_user_dobavil_obr['id_user']."'");
			$select_name_user_obr = mysql_fetch_assoc($select_name_user);

			if(!empty($select_name_user_obr['photo_user'])){
						//echo '<div class="show_random_tovar_name_user">'.$select_name_user_obr['login'].'<img id="" src='.$select_name_user_obr['photo_user'].'>111</div>';
						
						
						
						echo '<div class="show_random_tovar_name_user"><img id="" src='.$select_name_user_obr['photo_user'].'></div>';
					echo '<div class="kto_sohranil">Сохранено&nbsp;<span>'.$select_name_user_obr['login'].'</span></div>';
					echo '<div class="show_random_tovar_name_magazin">Магазин <span><a href="index.php?magazin='.$select_name_magazin_obr['id'].'">'.$select_name_magazin_obr['name_magazin'].'</a></span></div>';
					}
					else{
						//echo '<div class="show_random_tovar_name_user">'.$select_name_user_obr['login'].'11111111</div>';
						echo '<div class="show_random_tovar_name_user"><img  src="images/no-photo.jpg"></div>';
					echo '<div class="kto_sohranil">Сохранено&nbsp;<span>Системой</span></div>';
					echo '<div class="show_random_tovar_name_magazin">Магазин <span><a href="index.php?magazin='.$select_name_magazin_obr['id'].'">'.$select_name_magazin_obr['name_magazin'].'</a></span></div>';
					}
		
		}
	 echo'</div>';
	}
}

function straniza_magazina(){
	
		$url_page_magazin_id = explode("=", url());

	
	$select_name_magazin = mysql_query("SELECT * FROM magazin WHERE id = '".$url_page_magazin_id[1]."'");
	$select_name_magazin_obr = mysql_fetch_assoc($select_name_magazin);
	
	echo $select_name_magazin_obr['name_magazin'];
	
	if(isset($_COOKIE['login']) && isset($_COOKIE['password'])){
	$select_proverka_na_podpisku = mysql_query("SELECT * FROM my_magazini WHERE id_user = '".$_COOKIE['id']."' and id_magazin = '".$url_page_magazin_id[1]."'");
					$select_proverka_na_podpisku_obr = mysql_fetch_assoc($select_proverka_na_podpisku);
					if($select_proverka_na_podpisku_obr == 0){
					//	echo'<div id="podpis"><button onclick="podpisatsa_na_usera_ajax('.$_COOKIE['id'].','.$select_obr['id'].')"><i class="">Подписаться</i></button></a></br></div>';
						echo '<div class="podpisatsa_na_usera_knopka"><a name="modal" href="#dialog" onclick="podpisatsa_na_magazin_ajax('.$_COOKIE['id'].','.$url_page_magazin_id[1].')">Подписаться</a></br></div>';
						echo'<script>
						$(document).ready(function() {
							$(".podpisatsa_na_usera_knopka").click(function() {
							$(this).html("Вы подписались на магазин");
							 $(this).addClass("podpisatsa_na_usera_knopka_nazata");
							});
						});
						</script>';
					}
					else{
					//	echo'<div id=""><button onclick="otpisatsia_ot_usera_ajax('.$_COOKIE['id'].','.$select_obr['id'].')"><i class="">Отписаться</i></button></a></br></div>';
						echo '<div class="otpisatsia_ot_usera_knopka"><a name="modal" href="#dialog" onclick="otpisatsia_ot_magazina_ajax('.$_COOKIE['id'].','.$select_name_magazin_obr['id'].')">Отписаться</a></br></div>';
						echo'<script>
						$(document).ready(function() {
							$(".otpisatsia_ot_usera_knopka").click(function() {
							$(this).html("Отписка произведена");
							 $(this).addClass("otpisatsia_ot_usera_knopka_nazata");
							});
						});
						</script>';
					}
	}
	
	$select_tovar_iz_magazina = mysql_query("SELECT * FROM tovar WHERE name_magazin = '".$url_page_magazin_id[1]."'");
	while($select_tovar_iz_magazina_obr = mysql_fetch_assoc($select_tovar_iz_magazina)){
	

	echo '<div class="show_random_tovar">
	<div id=""><a href="index.php?'.$select_tovar_iz_magazina_obr['id'].'"><img id="show_image_tovar_gl_str" src='.$select_tovar_iz_magazina_obr['photo_tovara'].'></a></div>
	<div id="show_random_tovar_name"><a href="index.php?'.$select_tovar_iz_magazina_obr['id'].'">'.$select_tovar_iz_magazina_obr['name_content'].'</a></div></div>';
	
	}

}


function straniza_tovara(){
	
	$url_page = explode("?", url());
	echo '<div class="big_tovar">';
	if($url_page[1]!=0)
	{
		echo'<div class="tovar_vivod_stranizi_head">';
		$select = mysql_query("SELECT * FROM tovar WHERE id = '".$url_page[1]."'");
		$select_obr = mysql_fetch_assoc($select);
		
		$select_name_rub = mysql_query("SELECT * FROM tovar WHERE id = '".$select_obr['parent_id']."'");
		$select_obr_name_rub = mysql_fetch_assoc($select_name_rub);
		
	//	echo $select_obr_name_rub['name_content'].'</br>';
		
		echo '<div class="name_content_obertka_right">';
		//echo $select_obr['name_content'].'</br>';
	//	echo $select_obr['id'].'</br>';
		echo '<img id="show_image_tovar_gl_str" src="'.$select_obr['photo_tovara'].'">'.'</br>';
	//	echo $select_obr['sex_category'].'</br>';
	//	echo $select_obr['description'].'</br>';
	//	echo $select_obr['name_magazin'].'</br>';
	//	echo $select_obr['url_tovara'].'</br>';
		echo '</div>';
		
		echo '<div class="kupit"><a href="'.$select_obr['url_tovara'].'">Купить</a></br></div>';

		/*
		$select_name_rub = mysql_query("SELECT * FROM tovar WHERE id = '".$select_obr['parent_id']."'");
		$select_obr_name_rub = mysql_fetch_assoc($select_name_rub);
		*/
	
		$select_2 = mysql_query("SELECT * FROM magazin WHERE id = '".$select_obr['name_magazin']."'");
		$select_obr_2 = mysql_fetch_assoc($select_2);
	//	echo $select_obr_2['name_magazin'].'</br>';
		
		
		if(isset($_COOKIE['login']) && isset($_COOKIE['password'])){
		
		$select_proverka_na_uze_dobavlennost = mysql_query("SELECT * FROM my_veszi WHERE id_tovar = '".$select_obr['id']."' and id_user = '".$_COOKIE['id']."'");
		$select_proverka_na_uze_dobavlennost_obr = mysql_fetch_assoc($select_proverka_na_uze_dobavlennost);
		
			if($select_proverka_na_uze_dobavlennost_obr['id'] == 0){
			
		//	echo'<div id=""><button onclick="add_vesz_on_my_page_ajax('.$_COOKIE['id'].','.$select_obr['id'].','.$select_obr['name_magazin'].')"><i class="">Добавить</i></button></a></br></div>';
			echo '<div class="add_vesz_on_my_page_knopka"><a name="modal" href="#dialog" onclick="add_vesz_on_my_page_ajax('.$_COOKIE['id'].','.$select_obr['id'].','.$select_obr['name_magazin'].')">Добавить</a></br>';
			echo'<script>
				$(document).ready(function() {
					$(".add_vesz_on_my_page_knopka").click(function() {
					$(this).html("Добавлено");
					 $(this).addClass("add_vesz_on_my_page_knopka_nazat");
					});
				});
			</script>';
			}
			
			else{
				echo '<div class="add_vesz_on_my_page_kogda_vesz_dobavlena">Данная вешь уже добавлена на вашу страницу</div>';
			}
		}
		echo'</div>';
	}
	echo '</div>';
	/*----вывод подобных товаров---------------------------------------------------------------------------------------------------------------------------------------------------*/
	echo'<div id="show_podobnie_tovari_obszaia_obertka">';
		$select_3 = mysql_query("SELECT * FROM tovar WHERE sex_category = '".$select_obr['sex_category']."' and name_magazin = '".$select_obr['name_magazin']."' and parent_id = '".$select_obr['parent_id']."' and id != '".$url_page[1]."' LIMIT 8 ");
	while($select_obr_3 = mysql_fetch_assoc($select_3)){
	echo '<div id="show_podobnie_tovari_obertka">';
		echo '<div id="show_podobnie_tovari_photo_tovara">';	
		echo '<a href="index.php?'.$select_obr_3['id'].'"><img id="sr_show_podobnie_tovari" src="'.$select_obr_3['photo_tovara'].'"></a>'.'</br>';
		echo '</div>';
		echo '<div id="show_podobnie_tovari_name_content">';
		echo '<a href="index.php?'.$select_obr_3['id'].'">'.$select_obr_3['name_content'].'</a></br>';
		echo '</div>';
	/*--------вывод юзеров которые их добавили под подобными товарами----------------------------------------------------------------*/
		$select_user_dobavil = mysql_query("SELECT id_user FROM my_veszi WHERE id_tovar = '".$select_obr_3['id']."'");
			$select_user_dobavil_obr = mysql_fetch_assoc($select_user_dobavil);
			$select_name_user = mysql_query("SELECT * FROM users where id = '".$select_user_dobavil_obr['id_user']."'");
			$select_name_user_obr = mysql_fetch_assoc($select_name_user);
			
		if(isset($_COOKIE['login']) && isset($_COOKIE['password'])){
		
			if($select_name_user_obr['id'] != $_COOKIE['id']){
			
				if(!empty($select_name_user_obr['photo_user'])){
					echo '<div class="show_random_tovar_name_user"><a href="index.php?polzovatel%'.$select_name_user_obr['id'].'"><img id="" src='.$select_name_user_obr['photo_user'].'></a></div><a href="index.php?polzovatel%'.$select_name_user_obr['id'].'">'.$select_name_user_obr['login'].'</a>';
				}
				else{
					echo '<div class="show_random_tovar_name_user"><a href="index.php?user%'.$select_name_user_obr['id'].'">'.$select_name_user_obr['login'].'</a></div>';
				}
			}
			else{
			
				if(!empty($select_name_user_obr['photo_user'])){
					echo '<div class="show_random_tovar_name_user"><a href="index.php?polzovatel%'.$select_name_user_obr['id'].'"><img id="" src='.$select_name_user_obr['photo_user'].'></a></div><a href="index.php?polzovatel%'.$select_name_user_obr['id'].'">'.$select_name_user_obr['login'].'</a>';
				}
				else{
					echo '<div class="show_random_tovar_name_user"><a href="index.php?polzovatel%'.$select_name_user_obr['id'].'">'.$select_name_user_obr['login'].'</a></div>';
				}
			}
		}
		else{
			
			if(!empty($select_name_user_obr['photo_user'])){
					echo '<div class="show_random_tovar_name_user"><img id="" src='.$select_name_user_obr['photo_user'].'></div><a href="">'.$select_name_user_obr['login'].'</a>';
				}
				else{
					echo '<div class="show_random_tovar_name_user">'.$select_name_user_obr['login'].'</div>';
				}

		}
	/*--------вывод юзеров которые их добавили под подобными товарами--------//------------------------------------------------------*/
		echo '</div>';
	}
	echo'</div>';
	/*----вывод подобных товаров------------------------------//-------------------------------------------------------------------------------------------------------------------*/

	
	echo'<div class="useri_dobavivsie_vez">';
	
	//echo $select_obr['id'];
	
	$select_user_dobavisie_etu_vesz = mysql_query("SELECT * FROM my_veszi WHERE id_tovar = '".$select_obr['id']."'");
	while($select_user_dobavisie_etu_vesz_obr = mysql_fetch_assoc($select_user_dobavisie_etu_vesz)){
	
		//echo $select_user_dobavisie_etu_vesz_obr['id_user'];
		
		$select_user_all_inform = mysql_query("SELECT * FROM users WHERE id = '".$select_user_dobavisie_etu_vesz_obr['id_user']."'");
		$select_user_all_inform_obr = mysql_fetch_assoc($select_user_all_inform);
		echo '<div class="user_dobavivsiy_vesz">';
		echo '<div class="user_dobavivsiy_vesz_hed">';
	if(isset($_COOKIE['login']) && isset($_COOKIE['password'])){
	
		if($select_user_all_inform_obr['id'] != $_COOKIE['id']){
			echo'<div class="img_niz"><a href="index.php?user%'.$select_user_all_inform_obr['id'].'"><img id="" src='.$select_user_all_inform_obr['photo_user'].'></a></div>';	
			echo'<div class="img_kto">'.$select_user_all_inform_obr['login'].'</div>';	
			
			
		}
		else{
			echo'<div class="img_niz"><a href="index.php?polzovatel%'.$select_user_all_inform_obr['id'].'"><img id="" src='.$select_user_all_inform_obr['photo_user'].'></a></div>';	
			echo'<div class="img_kto"><a href="index.php?polzovatel%'.$select_user_all_inform_obr['id'].'">'.$select_user_all_inform_obr['login'].'</a></div>';	
			
		}
	}
	else{
		
		
		echo'<div class="img_niz"><img id="" src='.$select_user_all_inform_obr['photo_user'].'></div>';	
			echo'<div class="img_kto">'.$select_user_all_inform_obr['login'].'</div>';	
	}
		
		/*--------------------количество подписчиков у данных юзеров----------------------------------------------*/
			$select_sum_podpisziki_usera = mysql_query("SELECT count(id_user_podpisnoy) FROM podpiski where id_user_podpisnoy = '".$select_user_all_inform_obr['id']."'");
			$select_sum_podpisziki_usera_obr = mysql_fetch_assoc($select_sum_podpisziki_usera);
			echo '<br/><div class="img_podpischik">'.$select_sum_podpisziki_usera_obr['count(id_user_podpisnoy)'].' подписчиков'.'</div>';
			
		/*--------------------количество подписчиков у данных юзеров-----------------//---------------------------*/
	
	if(isset($_COOKIE['login']) && isset($_COOKIE['password'])){
	
		if($select_user_all_inform_obr['id'] != $_COOKIE['id']){
			echo'<div class="proverka_podpiski">';
					/*-------проверка на подписан или нет--------------*/
					$select_proverka_na_podpisku = mysql_query("SELECT * FROM podpiski WHERE id_user_podpiszik = '".$_COOKIE['id']."' and id_user_podpisnoy = '".$select_user_all_inform_obr['id']."'");
					$select_proverka_na_podpisku_obr = mysql_fetch_assoc($select_proverka_na_podpisku);
					if($select_proverka_na_podpisku_obr == 0){
					//	echo'<div id="podpis"><button onclick="podpisatsa_na_usera_ajax('.$_COOKIE['id'].','.$select_obr_3['id'].')"><i class="">Подписаться</i></button></a></br></div>';
						echo '<div class="otpisatsia_ot_usera_knopka"><a name="modal" href="#dialog" onclick="podpisatsa_na_usera_ajax('.$_COOKIE['id'].','.$select_user_all_inform_obr['id'].')">Подписаться</a></br></div>';
						echo'<script>
						$(document).ready(function() {
							$(".podpisatsa_na_usera_knopka").click(function() {
							$(this).html("Вы подписались на пользователя '.$select_obr['login'].'");
							 $(this).addClass("podpisatsa_na_usera_knopka_nazata");
							});
						});
						</script>';
					}
					else{
					
					//	echo'<div id=""><button onclick="otpisatsia_ot_usera_ajax('.$_COOKIE['id'].','.$select_obr_3['id'].')"><i class="">Отписаться</i></button></a></br></div>';
						echo '<div class="otpisatsia_ot_usera_knopka"><a name="modal" href="#dialog" onclick="otpisatsia_ot_usera_ajax('.$_COOKIE['id'].','.$select_user_all_inform_obr['id'].')">Отписаться</a></br></div>';
						echo'<script>
						$(document).ready(function() {
							$(".otpisatsia_ot_usera_knopka").click(function() {
							$(this).html("Отписка произведена");
							 $(this).addClass("otpisatsia_ot_usera_knopka_nazata");
							});
						});
						</script>';
					}
					/*------проверка на подписан или нет---------//---------*/	
			echo '</div>';
			}
		}
		echo '</div>';
		//echo $select_user_all_inform_obr['id'].'</br>';
		
		$select_veszi_uzerov = mysql_query("SELECT * FROM my_veszi WHERE id_user = '".$select_user_all_inform_obr['id']."' LIMIT 4");
		while($select_veszi_uzerov_obr = mysql_fetch_assoc($select_veszi_uzerov)){
		//echo $select_veszi_uzerov_obr['id_tovar'].'</br>';
		
			$select_veszi_uzerov_all_info = mysql_query("SELECT * FROM tovar WHERE id = '".$select_veszi_uzerov_obr['id_tovar']."'");
			$select_veszi_uzerov_all_info_obr = mysql_fetch_assoc($select_veszi_uzerov_all_info);
			echo '<div class="user_dobavivsiy_vesz_veszi">';
			echo '<a href="index.php?'.$select_veszi_uzerov_all_info_obr['id'].'"><img id="sr_show_podobnie_tovari" src="'.$select_veszi_uzerov_all_info_obr['photo_tovara'].'"></a>'.'</br>';
			echo '</div>';
		}
		echo '</div>';
	}
	echo'</div>';
}

/*--------------------добавление и удаление вещи на странице юзера---------------*/
function add_vesz_on_my_page($id_user, $id_tovar){

	$id_user = $_POST['id_user'];	
	$id_tovar = $_POST['id_tovar'];
	$id_magazin = $_POST['id_magazin'];
	
	$date_record = date("r", mktime());
	
	if(mysql_query("INSERT INTO my_veszi (id_user, id_tovar, date_record, id_magazin) VALUES ('".$id_user."','".$id_tovar."', '".$date_record."', '".$id_magazin."')")){
	
		echo 'Товар добавлен к вам на страницу';
	}
}

function del_vesz_on_my_page($id_user, $id_tovar){

	$id_user = $_POST['id_user'];	
	$id_tovar = $_POST['id_tovar'];
	
	if(mysql_query("DELETE FROM my_veszi WHERE id_user = '".$id_user."' and id_tovar = '".$id_tovar."'")){
	
		echo 'Товар удален со страницы';
	}
}
/*------------------добавление и удаление вещи на странице юзера-------//------------*/

function filtr(){
echo '<ul class="menu">';
	echo '<li class="sex">
			<a class="sex_a" href="index.php?man">Мужчинам</a>';
				echo'<div class="razdel_ul">';
				echo'<div class="razdel_ul_2">';
					$select_razdel = mysql_query("select clothing_category, id, name_content from tovar where  parent_id = 0 and sex_category = 'man' GROUP BY clothing_category ");
					while($select_razdel_obr = mysql_fetch_assoc($select_razdel)){
						
						echo '<div class="all_menu_div">';
							echo '<div class="razdel_div"><a href="index.php?man&'.$select_razdel_obr['clothing_category'].'">'.$select_razdel_obr['clothing_category'].'</a></div>';
							
							echo'<ul class="razdel_ul">';						
							 $cat = $select_razdel_obr['clothing_category'];
							  $select_razdel_2 = mysql_query("select name_content, id from tovar where parent_id = 0 and clothing_category = '".$cat."' and sex_category = 'man' ");
							   while($select_razdel_obr_2 = mysql_fetch_assoc($select_razdel_2)){
								echo '<div class="div_rubrica"><li class="rubrica"><a href="index.php?man&'.$select_razdel_obr['clothing_category'].'$'.$select_razdel_obr_2['id'].'">'.$select_razdel_obr_2['name_content'].'</a></li></div>';
							   }							
							
						echo'</ul>';
						
						echo'</div>';
							}
						echo'</div>';
						
						echo'</li>';
						
					echo'</ul>';
					
echo '<ul class="menu">';
	echo '<li class="sex">
			<a class="sex_a" href="index.php?woman">Женщинам</a>';
				echo'<div class="razdel_ul">';
				echo'<div class="razdel_ul_2">';
					$select_razdel = mysql_query("select clothing_category, id, name_content from tovar where  parent_id = 0 and sex_category = 'woman' GROUP BY clothing_category ");
					while($select_razdel_obr = mysql_fetch_assoc($select_razdel)){
						echo '<div class="all_menu_div">';
							echo '<div class="razdel_div"><a href="index.php?woman&'.$select_razdel_obr['clothing_category'].'">'.$select_razdel_obr['clothing_category'].'</a></div>';
							
							echo'<ul class="razdel_ul">';						
							 $cat = $select_razdel_obr['clothing_category'];
							  $select_razdel_2 = mysql_query("select name_content, id from tovar where parent_id = 0 and clothing_category = '".$cat."' and sex_category = 'woman' ");
							   while($select_razdel_obr_2 = mysql_fetch_assoc($select_razdel_2)){
								echo '<div class="div_rubrica"><li class="rubrica"><a href="index.php?woman&'.$select_razdel_obr['clothing_category'].'$'.$select_razdel_obr_2['id'].'">'.$select_razdel_obr_2['name_content'].'</a></li></div>';
							   }							
							
						echo'</ul>';
						echo'</div>';
							}
						echo'</div>';
						echo'</li>';
						
					echo'</ul>';
					echo '<ul class="menu_statii">';
	echo '<li class="sex">
			<a class="sex_a" href="index.php?stati^showe_stati">Статьи</a>';		
			echo'</li>';		
		echo'</ul>';
}

function top(){
/*	echo '<ul class="menu">';
	echo '<li class="sex">
			<a class="sex_a" href="index.php?stati^showe_stati">Статьи</a>';		
			echo'</li>';		
		echo'</ul>';*/
		echo '<ul class="menu_top_ludi">';
	echo '<li class="sex">
			<a class="sex_a" href="index.php?top_user">Топ люди</a>';		
			echo'</li>';		
		echo'</ul>';
		echo '<ul class="menu_top_magazin">';
	echo '<li class="sex_1">
			<a class="sex_a_1" href="index.php?top_magazin">Топ магазины</a>';		
			echo'</li>';		
		echo'</ul>';
	

}
/*это менюшка "моя страница" в голове сайта*/
function menu_user_page(){		
	echo '<ul class="user_menu_1">';
	echo '<li class="user_lenta">
			<a class="user_lenta_a" href="index.php?lenta:'.$_COOKIE['id'].'">Лента</a>';		
			echo'</li>';
			echo'</ul>';

	echo '<ul class="user_menu">';
	echo '<li class="user_sex">
			<a class="user_sex_a" href="index.php?polzovatel%'.$_COOKIE['id'].'">Моя страница</a>';		
			echo'</li>';
			echo'</ul>';
}






function probniy_filtr_sel(){
	
	
	$url_page = explode("?", url());
	
	$url_page_2 = explode("&", url());
	
	$url_page_2_sex = explode("?", $url_page_2[0]);
	
	$url_page_3 = explode("$", url());
	
	$url_page_4 = explode("^", url());
	
	$url_page_5 = explode("*", url());
	
	$url_page_6 = explode("!", url());
	
	
	$select_obszie_rub = mysql_query("select * from tovar where id = '".$url_page_2[1]."'");
	$select_obszie_rub_obr = mysql_fetch_assoc($select_obszie_rub);
	
	if($url_page[1]){

	$rez_man_woman = mysql_query("SELECT * FROM tovar WHERE sex_category = '".$url_page[1]."' and parent_id != 0");
	while($rez_man_woman_obr = mysql_fetch_assoc($rez_man_woman)){
	echo '<div class="show_random_tovar">
	<div id="show_random_tovar_photo_tovara"><a href="index.php?'.$rez_man_woman_obr['id'].'"><img id="show_image_tovar_gl_str" src="'.$rez_man_woman_obr['photo_tovara'].'"></a></div>
	<div id="show_random_tovar_name"><a href="index.php?'.$rez_man_woman_obr['id'].'">"'.$rez_man_woman_obr['name_content'].'"</a></div>';
	
	echo '<div id="show_random_tovar_name_magazin"><a href="index.php?magazin='.$select_name_magazin_obr['id'].'">'.$select_name_magazin_obr['name_magazin'].'</a></div>';
	
	if(isset($_COOKIE['login']) && isset($_COOKIE['password'])){
	 $select_user_dobavil = mysql_query("SELECT id_user FROM my_veszi WHERE id_tovar = '".$rez_man_woman_obr['id']."'");
		$select_user_dobavil_obr = mysql_fetch_assoc($select_user_dobavil);
		$select_name_user = mysql_query("SELECT * FROM users where id = '".$select_user_dobavil_obr['id_user']."'");
		$select_name_user_obr = mysql_fetch_assoc($select_name_user);
		
		if($select_name_user_obr['id'] != $_COOKIE['id']){

				if(!empty($select_name_user_obr['photo_user'])){
				
					echo '<div class="show_random_tovar_name_user"><img id="" src='.$select_name_user_obr['photo_user'].'></div>';
					echo '<div class="kto_sohranil">Сохранено&nbsp;<span><a href="index.php?user%'.$select_name_user_obr['id'].'">'.$select_name_user_obr['login'].'</a></span></div>';
					
					
					
				}
				else{
						echo '<div class="show_random_tovar_name_user"><img  src="images/no-photo.jpg"></div>';
					echo '<div class="kto_sohranil">Сохранено&nbsp;<span>Системой</span></div>';
					
				}
			}
			
		else{
				if(!empty($select_name_user_obr['photo_user'])){
										
					echo '<div class="show_random_tovar_name_user"><a href="index.php?polzovatel%'.$select_name_user_obr['id'].'"><img id="" src='.$select_name_user_obr['photo_user'].'></a></div>';
					echo '<div class="kto_sohranil">Сохранено&nbsp;<span><a href="index.php?user%'.$select_name_user_obr['id'].'">'.$select_name_user_obr['login'].'</a></span></div>';
				}
				else{
									
					echo '<div class="show_random_tovar_name_user"><img  src="images/no-photo.jpg"></div>';
					echo '<div class="kto_sohranil">Сохранено&nbsp;<span>Системой</span></div>';
				}
			}
			
			
		/*------------------вывод магазинов для зарегистрированного пользователя или админа----------------------------------------------------------------------*/
		$select_name_magazin = mysql_query("SELECT * FROM magazin where id = '".$rez_man_woman_obr['name_magazin']."'");
		$select_name_magazin_obr = mysql_fetch_assoc($select_name_magazin);
		/* echo '<div><a href="index.php?magazin='.$select_name_magazin_obr['id'].'">'.$select_name_magazin_obr['name_magazin'].'</a></div>';  */
		
		echo '<div class="show_random_tovar_name_magazin">Магазин <span><a href="index.php?magazin='.$select_name_magazin_obr['id'].'">'.$select_name_magazin_obr['name_magazin'].'</a></span></div>';
		/*------------------вывод магазинов для зарегистрированного пользователя или админа------------------//--------------------------------------------------*/
		
		/*----------------проверка на админа для отображения конопок редакта---------------------------------------------------------------------------------------*/
		$select_provarka_admina = mysql_query("SELECT adm FROM users WHERE id = '".$_COOKIE['id']."'");
		$select_provarka_admina_obr = mysql_fetch_assoc($select_provarka_admina);
		if($select_provarka_admina_obr['adm'] == 1){
		echo '<div id="rubric_redact_but_random"><button onclick="redact_veszi_ajax('.$rez_man_woman_obr['id'].')"><i class="kn_r fa-pencil"></i></button></a></br></div>
		 <div id="rubric_delete_but_random"><button onclick="delete_veszi_ajax('.$rez_man_woman_obr['id'].')"><i class="kn_d fa-times"></i></button></a></br></div>';
		/*----------------проверка на админа для отображения конопок редакта-------------------//------------------------------------------------------------------*/
		}
	}
	
	else{
	
		$select_user_dobavil = mysql_query("SELECT id_user FROM my_veszi WHERE id_tovar = '".$rez_man_woman_obr['id']."'");
		$select_user_dobavil_obr = mysql_fetch_assoc($select_user_dobavil);
		$select_name_user = mysql_query("SELECT * FROM users where id = '".$select_user_dobavil_obr['id_user']."'");
		$select_name_user_obr = mysql_fetch_assoc($select_name_user);

		if(!empty($select_name_user_obr['photo_user'])){
					echo '<div class="show_random_tovar_name_user"><img id="" src='.$select_name_user_obr['photo_user'].'></div>';
					echo '<div class="kto_sohranil">Сохранено&nbsp;<span>'.$select_name_user_obr['login'].'</span></div>';
				}
				else{
						echo '<div class="show_random_tovar_name_user"><img  src="images/no-photo.jpg"></div>';
					echo '<div class="kto_sohranil">Сохранено&nbsp;<span>Системой</span></div>';
				}
				
		/*------------------вывод магазинов для не!!!зарегистрированного пользователя----------------------------------------------------------------------*/
		$select_name_magazin = mysql_query("SELECT * FROM magazin where id = '".$rez_man_woman_obr['name_magazin']."'");
		$select_name_magazin_obr = mysql_fetch_assoc($select_name_magazin);
		
		
		echo '<div class="show_random_tovar_name_magazin">Магазин <span><a href="index.php?magazin='.$select_name_magazin_obr['id'].'">'.$select_name_magazin_obr['name_magazin'].'</a></span></div>';
		/*------------------вывод магазинов для не!!!зарегистрированного пользователя------------------//--------------------------------------------------*/
	}
	
	echo'</div>';
		}
	}	
	
	if($url_page_2[1]){
	
	$rez_man_woman_weszi = mysql_query("SELECT * FROM tovar WHERE clothing_category = '".$url_page_2[1]."' and sex_category = '".$url_page_2_sex[1]."' and parent_id != 0");
	while($rez_man_woman_weszi_obr = mysql_fetch_assoc($rez_man_woman_weszi)){
	echo '<div class="show_random_tovar">
	<div id="show_random_tovar_photo_tovara"><a href="index.php?'.$rez_man_woman_weszi_obr['id'].'"><img id="show_image_tovar_gl_str" src="'.$rez_man_woman_weszi_obr['photo_tovara'].'"></a></div>
	<div id="show_random_tovar_name"><a href="index.php?'.$rez_man_woman_weszi_obr['id'].'">"'.$rez_man_woman_weszi_obr['name_content'].'"</a></div>';
	if(isset($_COOKIE['login']) && isset($_COOKIE['password'])){
	 $select_user_dobavil = mysql_query("SELECT id_user FROM my_veszi WHERE id_tovar = '".$rez_man_woman_weszi_obr['id']."'");
		$select_user_dobavil_obr = mysql_fetch_assoc($select_user_dobavil);
		$select_name_user = mysql_query("SELECT * FROM users where id = '".$select_user_dobavil_obr['id_user']."'");
		$select_name_user_obr = mysql_fetch_assoc($select_name_user);

		if($select_name_user_obr['id'] != $_COOKIE['id']){

				
				if(!empty($select_name_user_obr['photo_user'])){
					echo '<div class="show_random_tovar_name_user"><a href="index.php?user%'.$select_name_user_obr['id'].'"><img id="" src='.$select_name_user_obr['photo_user'].'></a></div><div class="komy_zagl">Сохранено&nbsp;<span><a href="index.php?user%'.$select_name_user_obr['id'].'">'.$select_name_user_obr['login'].'</span></a></div>';
				}
				else{
					echo '<div class="show_random_tovar_name_user">Сохранено&nbsp;<span><a href="index.php?user%'.$select_name_user_obr['id'].'">'.$select_name_user_obr['login'].'<img src="images/no-photo.jpg"></span></a></div>';
				}
			}
			
		else{
				if(!empty($select_name_user_obr['photo_user'])){
						echo '<div class="show_random_tovar_name_user"><a href="index.php?user%'.$select_name_user_obr['id'].'"><img id="" src='.$select_name_user_obr['photo_user'].'></a></div><div class="komy_zagl">Сохранено&nbsp;<span><a href="index.php?user%'.$select_name_user_obr['id'].'">'.$select_name_user_obr['login'].'</a></span></div>';
						
				}
				else{
					echo '<div class="show_random_tovar_name_user">'.$select_name_user_obr['login'].'<img src="images/no-photo.jpg"></div>';
				}
			}
		/*------------------вывод магазинов для зарегистрированного пользователя или админа---------------------------------------------------*/
		$select_name_magazin = mysql_query("SELECT * FROM magazin where id = '".$rez_man_woman_weszi_obr['name_magazin']."'");
		$select_name_magazin_obr = mysql_fetch_assoc($select_name_magazin);
		echo '<div class="kto_nazv_mag"><a href="index.php?magazin='.$select_name_magazin_obr['id'].'">'.$select_name_magazin_obr['name_magazin'].'</a></div>'; 
		/*------------------вывод магазинов для зарегистрированного пользователя или админа------------------//------------------------------*/
		
		/*----------------проверка на админа для отображения конопок редакта---------------------------------------------------------------------------------------*/
		$select_provarka_admina = mysql_query("SELECT adm FROM users WHERE id = '".$_COOKIE['id']."'");
		$select_provarka_admina_obr = mysql_fetch_assoc($select_provarka_admina);
		if($select_provarka_admina_obr['adm'] == 1){
		echo '<div id="rubric_redact_but_random"><button onclick="redact_veszi_ajax('.$rez_man_woman_obr['id'].')"><i class="kn_r fa-pencil"></i></button></a></br></div>
		 <div id="rubric_delete_but_random"><button onclick="delete_veszi_ajax('.$rez_man_woman_obr['id'].')"><i class="kn_d fa-times"></i></button></a></br></div>';
		}
		/*----------------проверка на админа для отображения конопок редакта-----------------//--------------------------------------------------------------------*/
	}
	else{
		
		$select_user_dobavil = mysql_query("SELECT id_user FROM my_veszi WHERE id_tovar = '".$rez_man_woman_weszi_obr['id']."'");
		$select_user_dobavil_obr = mysql_fetch_assoc($select_user_dobavil);
		$select_name_user = mysql_query("SELECT * FROM users where id = '".$select_user_dobavil_obr['id_user']."'");
		$select_name_user_obr = mysql_fetch_assoc($select_name_user);

		if(!empty($select_name_user_obr['photo_user'])){
									
			echo '<div class="show_random_tovar_name_user"><img id="" src='.$select_name_user_obr['photo_user'].'></div><div class="komy_zagl">Сохранено&nbsp;<span>'.$select_name_user_obr['login'].'</span></div>';
				
			
		}
		else{
			
		echo '<div class="show_random_tovar_name_user"><img src="images/no-photo.jpg"/></div><div class="komy_zagl">Сохранено&nbsp;<span>'.$select_name_user_obr['login'].'</span></div>';
			
		}
		
		/*------------------вывод магазинов для не!!!зарегистрированного пользователя------------------------------------------------------------------------------------*/
		$select_name_magazin = mysql_query("SELECT * FROM magazin where id = '".$rez_man_woman_weszi_obr['name_magazin']."'");
		$select_name_magazin_obr = mysql_fetch_assoc($select_name_magazin);
		echo '<div class="kto_nazv_mag"><a href="index.php?magazin='.$select_name_magazin_obr['id'].'">'.$select_name_magazin_obr['name_magazin'].'</a></div>'; 
		/*------------------вывод магазинов для не!!!зарегистрированного пользователявывод магазинов------------------//-------------------------------------------------*/
	}
	
	echo'</div>';
		}
	}
	
	
	if($url_page_3[1]){
	
	$rez_man_woman_weszi_id = mysql_query("SELECT * FROM tovar WHERE parent_id = '".$url_page_3[1]."' and sex_category = '".$url_page_2_sex[1]."'");
	while($rez_man_woman_weszi_id_obr = mysql_fetch_assoc($rez_man_woman_weszi_id)){
	echo '<div class="show_random_tovar">
	<div id="show_random_tovar_photo_tovara"><a href="index.php?'.$rez_man_woman_weszi_id_obr['id'].'"><img id="show_image_tovar_gl_str" src="'.$rez_man_woman_weszi_id_obr['photo_tovara'].'"></a></div>
	<div id="show_random_tovar_name"><a href="index.php?'.$rez_man_woman_weszi_id_obr['id'].'">"'.$rez_man_woman_weszi_id_obr['name_content'].'"</a></div>';
	if(isset($_COOKIE['login']) && isset($_COOKIE['password'])){
	 $select_user_dobavil = mysql_query("SELECT id_user FROM my_veszi WHERE id_tovar = '".$rez_man_woman_weszi_id_obr['id']."'");
		$select_user_dobavil_obr = mysql_fetch_assoc($select_user_dobavil);
		$select_name_user = mysql_query("SELECT * FROM users where id = '".$select_user_dobavil_obr['id_user']."'");
		$select_name_user_obr = mysql_fetch_assoc($select_name_user);
		
		if($select_name_user_obr['id'] != $_COOKIE['id']){

				if(!empty($select_name_user_obr['photo_user'])){
					echo '<div class="show_random_tovar_name_user"><a href="index.php?user%'.$select_name_user_obr['id'].'"><img id="" src='.$select_name_user_obr['photo_user'].'></a></div><div class="komy_zagl">Сохранено&nbsp;<span><a href="index.php?user%'.$select_name_user_obr['id'].'">'.$select_name_user_obr['login'].'</a></span></div>';
				}
				else{
					echo '<div class="show_random_tovar_name_user">Сохранено&nbsp;<span><a href="index.php?user%'.$select_name_user_obr['id'].'">'.$select_name_user_obr['login'].'<img src="images/no-photo.jpg"></a></span></div>';
				}
			}
			
		else{
				if(!empty($select_name_user_obr['photo_user'])){
						echo '<div class="show_random_tovar_name_user"><a href="index.php?user%'.$select_name_user_obr['id'].'"><img id="" src='.$select_name_user_obr['photo_user'].'></a></div><div class="komy_zagl">Сохранено&nbsp;<span><a href="index.php?user%'.$select_name_user_obr['id'].'">'.$select_name_user_obr['login'].'</a></span></div>';
				
				}
				else{
					echo '<div class="show_random_tovar_name_user">Сохранено&nbsp;<span>'.$select_name_user_obr['login'].'<img src="images/no-photo.jpg"></span></div>';
				}
			}
		
		
		/*------------------вывод магазинов для зарегистрированного пользователя или админа-----------------------------------------------*/
		$select_name_magazin = mysql_query("SELECT * FROM magazin where id = '".$rez_man_woman_weszi_id_obr['name_magazin']."'");
		$select_name_magazin_obr = mysql_fetch_assoc($select_name_magazin);
		echo '<div class="kto_nazv_mag"><a href="index.php?magazin='.$select_name_magazin_obr['id'].'">'.$select_name_magazin_obr['name_magazin'].'</a></div>'; 
		/*------------------вывод магазинов для зарегистрированного пользователя или админа------------//---------------------------------*/
		
		/*----------------проверка на админа для отображения конопок редакта------------------------------------------------------------------------------------------*/
		$select_provarka_admina = mysql_query("SELECT adm FROM users WHERE id = '".$_COOKIE['id']."'");
		$select_provarka_admina_obr = mysql_fetch_assoc($select_provarka_admina);
		if($select_provarka_admina_obr['adm'] == 1){
		echo '<div id="rubric_redact_but_random"><button onclick="redact_veszi_ajax('.$rez_man_woman_obr['id'].')"><i class="kn_r fa-pencil"></i></button></a></br></div>
		 <div id="rubric_delete_but_random"><button onclick="delete_veszi_ajax('.$rez_man_woman_obr['id'].')"><i class="kn_d fa-times"></i></button></a></br></div>';
		}
		/*----------------проверка на админа для отображения конопок редакта----------//-----------------------------------------------------------------------------*/
	}
	
	else{
		$select_user_dobavil = mysql_query("SELECT id_user FROM my_veszi WHERE id_tovar = '".$rez_man_woman_weszi_id_obr['id']."'");
		$select_user_dobavil_obr = mysql_fetch_assoc($select_user_dobavil);
		$select_name_user = mysql_query("SELECT * FROM users where id = '".$select_user_dobavil_obr['id_user']."'");
		$select_name_user_obr = mysql_fetch_assoc($select_name_user);

				if(!empty($select_name_user_obr['photo_user'])){
					echo '<div class="show_random_tovar_name_user">Сохранено&nbsp;<span>'.$select_name_user_obr['login'].'<img id="" src='.$select_name_user_obr['photo_user'].'></span></div>';
				}
				else{
					echo '<div class="show_random_tovar_name_user">Сохранено&nbsp;<span>'.$select_name_user_obr['login'].'</span></div>';
				}
				
			/*------------------вывод магазинов для не!!!зарегистрированного пользователя-------------------------------------------------------------------*/
			$select_name_magazin = mysql_query("SELECT * FROM magazin where id = '".$rez_man_woman_weszi_id_obr['name_magazin']."'");
			$select_name_magazin_obr = mysql_fetch_assoc($select_name_magazin);
			echo '<div><a href="index.php?magazin='.$select_name_magazin_obr['id'].'">'.$select_name_magazin_obr['name_magazin'].'</a><div>'; 
			/*------------------вывод магазинов для не!!!зарегистрированного пользователя------------------//----------------------------------------------*/		
		}
		
	echo'</div>';
		}
	}
	
	/*вывод статей*/
	if($url_page_4[1]){
	
		echo '<div class="statei_obertka_filtra">
		
		<div class="statei_filtr_head_man_woman_1"><a class="statei_filtr_head_man_woman_text" href="index.php?stati^showe_stati*man">Мужчинам</a></div></br>
		
		<div class="statei_filtr_head_man_woman_2"><a class="statei_filtr_head_man_woman_text" href="index.php?stati^showe_stati*woman">Женщинам</a></div></br>
	
		</div>';
	
		if(!isset($url_page_5[1])){
			$select = mysql_query("SELECT * FROM statei");
			while($select_obr = mysql_fetch_assoc($select)){
			echo'<div class="statei_body">';
				echo '<a href="index.php?stati^showe_stati*'.$url_page_5[1].'!'.$select_obr['id'].'">'.$select_obr['name_statei'].'</a></br>';
				//echo $select_obr['body_statei'].'</br>';
				
			$select_like = mysql_query("SELECT count(id_user) FROM push_like where id_cont = '".$select_obr['id']."'");
			if($select_like_obr = mysql_fetch_assoc($select_like)){
			echo $select_like_obr['count(id_user)'].'</br>';}
				
			echo'</div>';
			}
		}
				
		if($url_page_5[1]){
		
			$select = mysql_query("SELECT * FROM statei WHERE sex = '".$url_page_5[1]."'");
			while($select_obr = mysql_fetch_assoc($select)){
			echo'<div class="statei_body">';
				echo '<a href="index.php?stati^showe_stati*'.$url_page_5[1].'!'.$select_obr['id'].'">'.$select_obr['name_statei'].'</a></br>';
				//echo $select_obr['body_statei'].'</br>';
				
			$select_like = mysql_query("SELECT count(id_user) FROM push_like where id_cont = '".$select_obr['id']."'");
			if($select_like_obr = mysql_fetch_assoc($select_like)){
			echo $select_like_obr['count(id_user)'].'</br>';}
				
			echo'</div>';
			}
		}

		if($url_page_6[1]){
		
			$select = mysql_query("SELECT * FROM statei WHERE id = '".$url_page_6[1]."'");
			$select_obr = mysql_fetch_assoc($select);
			echo'<div class="statei_body_showe">';
				echo'<div class="statei_body_showe_zaglavie">'.$select_obr['name_statei'].'</div></br>';
				echo'<div class="statei_body_showe_telo_statei">'.$select_obr['body_statei'].'</div></br>';
				
			$select_like = mysql_query("SELECT count(id_user) FROM push_like where id_cont = '".$select_obr['id']."'");
			if($select_like_obr = mysql_fetch_assoc($select_like)){
			echo $select_like_obr['count(id_user)'].'</br>';}
			
			$select_like_no_duble = mysql_query("SELECT * FROM push_like where id_user = '".$_COOKIE['id']."' and id_cont = '".$select_obr['id']."'");
			$select_like_no_duble_obr = mysql_fetch_assoc($select_like_no_duble);
			if($select_like_no_duble_obr['id_user'] == $_COOKIE['id']){
				
			}
			else{
			//	echo'<div class="add_like"><button onclick="add_like_ajax('.$select_obr['id'].','.$_COOKIE['id'].')"><i class="">Поставить лайк</i></button></a></br></div>';
				echo '<div class="add_like_knopka"><a name="modal" href="#dialog" onclick="add_like_ajax('.$select_obr['id'].','.$_COOKIE['id'].')">Поставить лайк</a></br></div>';
				echo'<script>
						$(document).ready(function() {
							$(".add_like_knopka").click(function() {
							$(this).html("Вы поставили лайк");
							$(this).addClass("add_like_knopka_nazata");
							});
						});
						</script>';
			}
	
		echo'</div>';
		}
	}
	
}

function add_like($id_cont, $id_user){
	
	$id_cont = $_POST['id_cont'];
	$id_user = $_POST['id_user'];	
	
	if(mysql_query("INSERT INTO push_like (id_cont, id_user) VALUES ('".$id_cont."','".$id_user."')")){
	
		echo 'Лайк добавлен';
	
	}
	
}

function create_new_album(){

	$name_album = $_POST['name_album'];
		
			if(isset($name_album)){
			mysql_query("INSERT INTO my_album (parent_id, id_user, name_content) VALUES (0, '".$_COOKIE['id']."', '".$name_album."')");
				//echo 'INSERT успешно выполнен!';
				redirect('../index.php?polzovatel%'.$_COOKIE['id'].'!albom'); 
				}

}



function user(){

	$url_page = explode("?", url());
	
	$url_page_user = explode("%", url());
	
	$url_page_user_content = explode("!", url());
	
//	$url_page_user_content_2 = explode("=", url());
			
//	$url_page_user_content_album_name = explode("albom", url());
	
	$url_page_user_proverka = explode("?", $url_page_user[0]); // в этой строчке мы 
	
	if($url_page[1] == 'lenta:'.$_COOKIE['id'].''){
	
		//echo 'проверочка ленты</br>';
		
		$select = mysql_query("SELECT * FROM podpiski where id_user_podpiszik = '".$_COOKIE['id']."'");		
		while($select_obr = mysql_fetch_assoc($select)){
	echo '<div class="lenta_obertka">';
		$select_user_name = mysql_query("SELECT * FROM users where id = '".$select_obr['id_user_podpisnoy']."'");
		$select_user_name_obr = mysql_fetch_assoc($select_user_name);
		echo '<div class="user_lena">';

			echo '<div class="user_photo_2"><a href="index.php?user%'.$select_user_name_obr['id'].'"><img id="" src='.$select_user_name_obr['photo_user'].'></a></div>';
			echo '<div class="user_name_lenta"><a href="index.php?user%'.$select_user_name_obr['id'].'">'.$select_user_name_obr['login'].'</br></a></div>';
			/*--------------------количество подписчиков у данных юзеров----------------------------------------------*/
			$select_sum_podpisziki_usera = mysql_query("SELECT count(id_user_podpisnoy) FROM podpiski where id_user_podpisnoy = '".$select_user_name_obr['id']."'");
			$select_sum_podpisziki_usera_obr = mysql_fetch_assoc($select_sum_podpisziki_usera);
			echo '<div class="lenta_podpiska">'.$select_sum_podpisziki_usera_obr['count(id_user_podpisnoy)'].' подписчиков</div>';
			/*--------------------количество подписчиков у данных юзеров-----------------//---------------------------*/

					/*------проверка на подписан или нет---------------*/
					$select_proverka_na_podpisku = mysql_query("SELECT * FROM podpiski WHERE id_user_podpiszik = '".$_COOKIE['id']."' and id_user_podpisnoy = '".$select_user_name_obr['id']."'");
					$select_proverka_na_podpisku_obr = mysql_fetch_assoc($select_proverka_na_podpisku);
					if($select_proverka_na_podpisku_obr == 0){
						echo '<div id=""><a name="modal" href="#dialog" onclick="podpisatsa_na_usera_ajax('.$_COOKIE['id'].','.$select_user_name_obr['id'].')">Подписаться</a></br></div>';
					}
					else{
						echo '<div class="otpisatsia_ot_usera_knopka"><a name="modal" href="#dialog" onclick="otpisatsia_ot_usera_ajax('.$_COOKIE['id'].','.$select_user_name_obr['id'].')">Отписаться</a></br></div>';
						echo'<script>
						$(document).ready(function() {
							$(".otpisatsia_ot_usera_knopka").click(function() {
							$(this).html("Отписка произведена");
							 $(this).addClass("otpisatsia_ot_usera_knopka_nazata");
							});
						});
						</script>';
					
					}
					/*------проверка на подписан или нет--------//----------*/	
		
		echo '</div>';
		echo '<div class="obertka_vezey_v_lente">';
			$select_my_veszi = mysql_query("SELECT * FROM my_veszi WHERE id_user = '".$select_obr['id_user_podpisnoy']."' and id_user != '".$_COOKIE['id']."' ORDER BY `id` DESC limit 3");
			while($select_my_veszi_obr = mysql_fetch_assoc($select_my_veszi)){
			//echo $select_my_veszi_obr['id_tovar'].'</br>';
				
						$select_veszi_tovar = mysql_query("SELECT * FROM tovar WHERE id = '".$select_my_veszi_obr['id_tovar']."'");
						$select_veszi_tovar_obr = mysql_fetch_assoc($select_veszi_tovar);
					
						echo '<div class="vesz_v_lente">';
						echo '<a href="index.php?'.$select_veszi_tovar_obr['id'].'"><img id="veszi_usera_kartinka" src="'.$select_veszi_tovar_obr['photo_tovara'].'"></a></br>';
						echo '</div>';	
			}
				echo '</div>';
			echo '</div>';
		}
	
	}
	
	if($url_page[1] == 'top_user'){
		echo '<div class="text_top_ludi">Топ люди</div>';
		$select_2 = mysql_query("SELECT id_user_podpisnoy, count(id_user_podpisnoy) from podpiski GROUP BY `id_user_podpisnoy` ORDER BY count(id_user_podpisnoy) DESC LIMIT 10");
			while($select_obr_2 = mysql_fetch_assoc($select_2)){
			echo '<div class="top_zelovek">';
			   echo'<div class="user_top_obertka">';
			 
				echo '<div class="podpischiki_top">';
				echo $select_obr_2['count(id_user_podpisnoy)'].' &nbsp;подписчиков</div>';
			
				$select_3 = mysql_query("SELECT * FROM users WHERE id = '".$select_obr_2['id_user_podpisnoy']."'");
				while($select_obr_3 = mysql_fetch_assoc($select_3)){
		
				if($select_obr_3['id'] != $_COOKIE['id']){
						
					
						echo '<div class="name_top_ludi"><a href="index.php?user%'.$select_obr_3['id'].'">'.$select_obr_3['login']."...".'</a></div>';
					
						echo '<div class="user_photo"><a href="index.php?user%'.$select_obr_3['id'].'"><img id="" src="'.$select_obr_3['photo_user'].'"></a></div>';
					
					/*-------проверка на подписан или нет--------------*/
					$select_proverka_na_podpisku = mysql_query("SELECT * FROM podpiski WHERE id_user_podpiszik = '".$_COOKIE['id']."' and id_user_podpisnoy = '".$select_obr_3['id']."'");
					$select_proverka_na_podpisku_obr = mysql_fetch_assoc($select_proverka_na_podpisku);
					if($select_proverka_na_podpisku_obr == 0){
					//	echo'<div id="podpis"><button onclick="podpisatsa_na_usera_ajax('.$_COOKIE['id'].','.$select_obr_3['id'].')"><i class="">Подписаться</i></button></a></br></div>';
						echo '<div class="podpisatsa_na_usera_knopka"><a name="modal" href="#dialog" onclick="podpisatsa_na_usera_ajax('.$_COOKIE['id'].','.$select_obr_3['id'].')">Подписаться</a></br></div>';
						echo'<script>
						$(document).ready(function() {
							$(".podpisatsa_na_usera_knopka").click(function() {
							$(this).html("Вы подписались на пользователя '.$select_obr['login'].'");
							 $(this).addClass("podpisatsa_na_usera_knopka_nazata");
							});
						});
						</script>';
					}
					else{
					
					//	echo'<div id=""><button onclick="otpisatsia_ot_usera_ajax('.$_COOKIE['id'].','.$select_obr_3['id'].')"><i class="">Отписаться</i></button></a></br></div>';
						echo '<div class="otpisatsia_ot_usera_knopka"><a name="modal" href="#dialog" onclick="otpisatsia_ot_usera_ajax('.$_COOKIE['id'].','.$select_obr_3['id'].')">Отписаться</a></br></div>';
						echo'<script>
						$(document).ready(function() {
							$(".otpisatsia_ot_usera_knopka").click(function() {
							$(this).html("Отписка произведена");
							 $(this).addClass("otpisatsia_ot_usera_knopka_nazata");
							});
						});
						</script>';
					}
					/*------проверка на подписан или нет---------//---------*/	
						
				}
				else{
					
						
						echo '<div class="name_top_ludi"><a href="index.php?polzovatel%'.$select_obr_3['id'].'">'.$select_obr_3['login'].'</a></div> ';
						
						echo '<div class="user_photo"><a href="index.php?polzovatel%'.$select_obr_3['id'].'"><img id="" src="'.$select_obr_3['photo_user'].'"></a></div>';
						
				}
				
				echo'</div>';
				
				echo'<div class="veszi_usera_obertka">';
				
				
				
					$select_id_tovar_usera = mysql_query("SELECT * from my_veszi WHERE id_user = '".$select_obr_3['id']."' LIMIT 4");
						while($select_id_tovar_usera_obr = mysql_fetch_assoc($select_id_tovar_usera)){
							//echo $select_id_tovar_usera_obr['id_tovar'].' ';
							$select_name_tovara = mysql_query("SELECT * FROM tovar WHERE id = '".$select_id_tovar_usera_obr['id_tovar']."' and parent_id !=0");
							while($select_name_tovara_obr = mysql_fetch_assoc($select_name_tovara)){
								echo'<div class="veszi_usera">';
								echo'<a href="index.php?'.$select_name_tovara_obr['id'].'"><img id="veszi_usera_kartinka" src="'.$select_name_tovara_obr['photo_tovara'].'"></a>';
								echo '</div>';
							}
						}			
			echo '</div>';
			}
			echo '</div>';
		}
			
	}
		
	if($url_page[1] == 'top_magazin'){
		
		$select_top_magazin = mysql_query("SELECT id_magazin, count(id_magazin) from my_magazini GROUP BY `id_magazin` ORDER BY count(id_magazin) DESC");
			while($select_top_magazin_obr = mysql_fetch_assoc($select_top_magazin)){
			echo '<div class="top_magazin">';
			echo '<div class="magazin_name_obertka">';
			//	echo '</br>'.$select_top_magazin_obr['id_magazin'].' ';
			//	echo $select_top_magazin_obr['count(id_magazin)'].' ';
				$select_top_magazin_name = mysql_query("SELECT * FROM magazin where id = '".$select_top_magazin_obr['id_magazin']."'");
				$select_top_magazin_name_obr = mysql_fetch_assoc($select_top_magazin_name);
				
			echo '<div class="magaz_photo"><a href="index.php?magazin='.$select_top_magazin_name_obr['id'].'"><img id="" src="'.$select_top_magazin_name_obr['url_photo'].'"></a></div>';
			
			echo '<div class="magaz_nazva"><a href="index.php?magazin='.$select_top_magazin_name_obr['id'].'">'.$select_top_magazin_name_obr['name_magazin'].'</a></div>';
			
			/*--------------------количество подписчиков у данных магазинов----------------------------------------------*/
			$select_sum_podpisziki_magazina = mysql_query("SELECT count(id_user) FROM my_magazini where id_magazin = '".$select_top_magazin_name_obr['id']."'");
			$select_sum_podpisziki_magazina_obr = mysql_fetch_assoc($select_sum_podpisziki_magazina);
			echo '<div class="magaz_podposchiki">'.$select_sum_podpisziki_magazina_obr['count(id_user)'].' подписчиков'.'</div>
			<div class="virov"></div>';
			/*--------------------количество подписчиков у данных магазинов-----------------//---------------------------*/

			
			if(isset($_COOKIE['login']) && isset($_COOKIE['password'])){
				$select_proverka_na_podpisku = mysql_query("SELECT * FROM my_magazini WHERE id_user = '".$_COOKIE['id']."' and id_magazin = '".$select_top_magazin_name_obr['id']."'");
					$select_proverka_na_podpisku_obr = mysql_fetch_assoc($select_proverka_na_podpisku);
					if($select_proverka_na_podpisku_obr == 0){
					//	echo'<div id="podpis"><button onclick="podpisatsa_na_usera_ajax('.$_COOKIE['id'].','.$select_obr['id'].')"><i class="">Подписаться</i></button></a></br></div>';
						echo '<div class="podpisatsa_na_usera_knopka"><a name="modal" href="#dialog" onclick="podpisatsa_na_magazin_ajax('.$_COOKIE['id'].','.$select_top_magazin_name_obr['id'].')">Подписаться</a></br></div>';
						echo'<script>
						$(document).ready(function() {
							$(".podpisatsa_na_usera_knopka").click(function() {
							$(this).html("Вы подписались на магазин");
							 $(this).addClass("podpisatsa_na_usera_knopka_nazata");
							});
						});
						</script>';
					}
					else{
					//	echo'<div id=""><button onclick="otpisatsia_ot_usera_ajax('.$_COOKIE['id'].','.$select_obr['id'].')"><i class="">Отписаться</i></button></a></br></div>';
						echo '<div class="otpisatsia_ot_usera_knopka"><a name="modal" href="#dialog" onclick="otpisatsia_ot_magazina_ajax('.$_COOKIE['id'].','.$select_top_magazin_name_obr['id'].')">Отписаться</a></br></div>';
						echo'<script>
						$(document).ready(function() {
							$(".otpisatsia_ot_usera_knopka").click(function() {
							$(this).html("Отписка произведена");
							 $(this).addClass("otpisatsia_ot_usera_knopka_nazata");
							});
						});
						</script>';
		}
	}
	
			echo '</div>';
			echo '<div class="veszi_iz_magazina">';
				$select_top_magazin_content = mysql_query("SELECT * FROM tovar where name_magazin = '".$select_top_magazin_obr['id_magazin']."' LIMIT 4");
				while($select_top_magazin_content_obr = mysql_fetch_assoc($select_top_magazin_content)){
			echo '<div class="kartinka_tovara_iz_magazina">';
				
				echo'<a href="index.php?'.$select_top_magazin_content_obr['id'].'"><img id="veszi_usera_kartinka" src="'.$select_top_magazin_content_obr['photo_tovara'].'"></a>';

			echo '</div>';
				}
			echo '</div>';	
		echo '</div>';
			}
		
	}

	
	if($url_page_user_proverka[1] == 'polzovatel'){
	
			$select = mysql_query("SELECT * FROM users WHERE id = '".$_COOKIE['id']."'");
				$select_obr = mysql_fetch_assoc($select);	
					echo '<div class="obertka_dannix_usera_na_staranize_usera">';
					echo '<div class="photo_usera_na_ego_sranize"><img id="photo_usera_na_ego_sranize_img" src="'.$select_obr['photo_user'].'"></br></div>';
				echo '<div class="dannie_usera_nastranize_usera">';
				//	echo $select_obr['id'].'</br>';
				//	echo $select_obr['name'].'</br>';
				//	echo $select_obr['last_name'].'</br>';
					echo '<div class="stroki_dannix_usera">Login: '.$select_obr['login'].'</div></br>';
				//	echo $select_obr['password'].'</br>';
					echo '<div class="stroki_dannix_usera">e-mail: '.$select_obr['mail'].'</div></br>';
				//	echo $select_obr['reg_date'].'</br>';
					echo '<div class="stroki_dannix_usera">country: '.$select_obr['country'].'</div></br>';
					echo '<div class="stroki_dannix_usera">city: '.$select_obr['city'].'</div></br>';
					
				
					/*тут должна быть кнопка для редакта админки*/
				echo '<div class="redact_dannie_polzovatelia"><a id="redact_dannie_polzovatelia_a" name="modal" href="#dialog" onclick="redact_dannie_polzovatelia_ajax('.$_COOKIE['id'].')">(редактировать профиль)</a></br></div><p>';	
				
				echo '</div>';	
			echo '</div>';
			
			echo'<div class="user_albom_heder">	
			<div class="user_albom_menu_left"></div>
			<div id="nav">
			<ul class="user_albom_menu">
					<li class="user_albom_podmenu">';
					$select_colvo_veszey = mysql_query("SELECT count(id_tovar) from my_veszi where id_user = '".$_COOKIE['id']."'");
					$select_colvo_veszey_obr = mysql_fetch_assoc($select_colvo_veszey);
				echo'<a class="user_albom_podmenu_a" href="index.php?polzovatel%'.$_COOKIE['id'].'!veszi">вещи ('.$select_colvo_veszey_obr['count(id_tovar)'].')</a>	
					</li>
					<li class="user_albom_podmenu">';
					$select_colvo_albomov = mysql_query("SELECT count(name_content) from my_album where id_user = '".$_COOKIE['id']."' and parent_id = 0");
					$select_colvo_albomov_obr = mysql_fetch_assoc($select_colvo_albomov);
				echo'<a class="user_albom_podmenu_a" href="index.php?polzovatel%'.$_COOKIE['id'].'!albom">альбом ('.$select_colvo_albomov_obr['count(name_content)'].')</a>	
					</li>
					<li class="user_albom_podmenu">';
					$select_colvo_podpisok = mysql_query("SELECT count(id_user_podpisnoy) from podpiski where id_user_podpiszik = '".$_COOKIE['id']."'");
					$select_colvo_podpisok_obr = mysql_fetch_assoc($select_colvo_podpisok);
				echo'<a class="user_albom_podmenu_a" href="index.php?polzovatel%'.$_COOKIE['id'].'!podpiski">подписки ('.$select_colvo_podpisok_obr['count(id_user_podpisnoy)'].')</a>	
					</li>
					<li class="user_albom_podmenu">';
					$select_colvo_podpisok_na_magazin = mysql_query("SELECT count(id_magazin) from my_magazini where id_user = '".$_COOKIE['id']."'");
					$select_colvo_podpisok_na_magazin_obr = mysql_fetch_assoc($select_colvo_podpisok_na_magazin);
				echo'<a class="user_albom_podmenu_a" href="index.php?polzovatel%'.$_COOKIE['id'].'!magazini">магазины ('.$select_colvo_podpisok_na_magazin_obr['count(id_magazin)'].')</a>	
					</li>
					<li class="user_albom_podmenu">';
					$select_colvo_podpiszikov = mysql_query("SELECT count(id_user_podpisnoy) from podpiski where id_user_podpisnoy = '".$_COOKIE['id']."'");
					$select_colvo_podpiszikov_obr = mysql_fetch_assoc($select_colvo_podpiszikov);
				echo'<a class="user_albom_podmenu_a" href="index.php?polzovatel%'.$_COOKIE['id'].'!podpisziki">подписчики ('.$select_colvo_podpiszikov_obr['count(id_user_podpisnoy)'].')</a>	
					</li>
			</ul>
			</div>';
			echo'<script>
				$(function() {
					var pgurl = window.location.href.substr(window.location.href.lastIndexOf("/")+1);
					 $(".user_albom_podmenu_a").each(function(){
						  if($(this).attr("href") == pgurl || $(this).attr("href") == "" )
						  $(this).addClass("active");
					 })
				});
			</script>';
			
		echo'<div class="user_albom_menu_right"></div>
			</div>';	
			
			echo'<div class="user_albom_content">';
					
			$url_page_user_content = explode("!", url());
			
			$url_page_user_content_album_name = explode(";", url());
			
			if($url_page_user_content[1] == 'veszi'){
		
			//	echo 'Проверочка тут уже есть список вещей'.'</br>';
				
				$select = mysql_query("SELECT * from my_veszi where id_user = '".$_COOKIE['id']."'");
				while($select_obr = mysql_fetch_assoc($select)){
			
				
					$select_tovar = mysql_query("SELECT * FROM tovar where id = '".$select_obr['id_tovar']."'");
					while($select_tovar_obr = mysql_fetch_assoc($select_tovar)){				
						echo '<div class="show_random_tovar">
						<div id="show_random_tovar_photo_tovara"><a href="index.php?'.$select_tovar_obr['id'].'"><img id="show_image_tovar_gl_str" src="'.$select_tovar_obr['photo_tovara'].'"></a></div>
						<div id="show_random_tovar_name" name="name_content"><a href="index.php?'.$select_tovar_obr['id'].'">"'.$select_tovar_obr['name_content'].'"</a></div>';
				//	echo '<div id=""><button onclick="del_vesz_on_my_page_ajax('.$_COOKIE['id'].','.$select_tovar_obr['id'].')"><i class="">Удалить с моей страницы</i></button></a></br></div>';		
				echo '<div class="del_vesz_on_my_page_knopka"><a name="modal" href="#dialog" onclick="del_vesz_on_my_page_ajax('.$_COOKIE['id'].','.$select_tovar_obr['id'].')">Удалить с моей страницы</a></br></div><p>';	
				echo'<script>
				$(document).ready(function() {
					$(".del_vesz_on_my_page_knopka").click(function() {
					$(this).html("Удалено со страницы");
					 $(this).addClass("del_vesz_on_my_page_knopka_nazata");
					});
				});
			</script>';
	
	
				echo '<div class="add_vesz_v_album_knopka"><a name="modal" href="#dialog" onclick="add_vesz_v_album_ajax('.$select_tovar_obr['id'].')">Сохранить в альбом</a></br></div>';
				
		echo '</div>';
						
		


						
					}
				}
			}
			
			if($url_page_user_content[1] == 'albom'){
			
			//	echo 'Проверочка тут будут альбомы';
				echo $url_page_user_content_album_name[1];

		echo'<div id="creat_albums"><a class=""  name="modal" href="#dialog" onclick="create_album()" >Создать новый альбом</a></div>';

		$select_album = mysql_query("SELECT * FROM my_album WHERE id_user = '".$_COOKIE['id']."' and parent_id = 0");
		while($select_album_obr = mysql_fetch_assoc($select_album)){
		echo '<div class="album_obertka">';	
			echo '<div class="album_name_obertka">';
			//	echo $select_album_obr['id'].'</br>';
			//	echo $select_album_obr['name_content'].'</br>';
				echo'<div class="name_albums"><a class="" href="index.php?polzovatel%'.$_COOKIE['id'].'!albom;'.$select_album_obr['id'].'">'.$select_album_obr['name_content'].'</a></div></br>';
				echo '<p>';
				echo'<div class="delete_album_knopka"><a class=""  name="modal" href="#dialog" onclick="delete_album('.$select_album_obr['id'].')" >Удалить альбом</a></div>';
				echo'<script>
				$(document).ready(function() {
					$(".delete_album_knopka").click(function() {
					$(this).html("Альбом удален");
					 $(this).addClass("delete_album_knopka_nazata");
					});
				});
			</script>';
				
			echo '</div>';
			echo '<div class="album_obertka_veszi_all">';
				$select_album_content_id = mysql_query("SELECT * FROM my_album WHERE parent_id = '".$select_album_obr['id']."' LIMIT 3");
				while($select_album_content_id_obr = mysql_fetch_assoc($select_album_content_id)){
					
					$select_album_content_name = mysql_query("SELECT * FROM tovar WHERE id = '".$select_album_content_id_obr['name_content']."'");
					$select_album_content_name_obr = mysql_fetch_assoc($select_album_content_name);
			echo '<div class="album_obertka_veszi_one">';
				echo'<a href="index.php?'.$select_album_content_name_obr['id'].'"><img id="veszi_usera_kartinka" src="'.$select_album_content_name_obr['photo_tovara'].'"></a>';
			echo '</div>';	
				}
				echo '</div>';	
			echo '</div>';	
			}
					
		
	}
		
		if($url_page_user_content_album_name[1] != 0){
			
			$select_album_otdelno = mysql_query("SELECT * FROM my_album where id = '".$url_page_user_content_album_name[1]."'");
			$select_album_otdelno_obr = mysql_fetch_assoc($select_album_otdelno);
				echo '<div class="album_otdelno_obertka_all">';
				echo '<div class="album_otdelno_obertka_name_album">';
				echo $select_album_otdelno_obr['name_content'];
				echo '</div>';
				echo '<div class="album_otdelno_obertka_name_content_obertka">';
				$select_album_otdelno_content = mysql_query("SELECT * FROM my_album WHERE parent_id = '".$select_album_otdelno_obr['id']."'");
				while($select_album_otdelno_content_obr = mysql_fetch_assoc($select_album_otdelno_content)){
				//echo $select_album_otdelno_content_obr['name_content'];
				$select_album_otdelno_content_nazvanie = mysql_query("SELECT * FROM tovar WHERE id = '".$select_album_otdelno_content_obr['name_content']."'");
				$select_album_otdelno_content_nazvanie_obr = mysql_fetch_assoc($select_album_otdelno_content_nazvanie);
				
				echo '<div class="album_otdelno_obertka_name_content">';
				
				echo'<a href="index.php?'.$select_album_otdelno_content_nazvanie_obr['id'].'"><img id="veszi_usera_kartinka" src="'.$select_album_otdelno_content_nazvanie_obr['photo_tovara'].'"></a>';
				
				//echo'<div id=""><button class="del_iz_album" onclick="del_vesz_iz_album('.$select_album_otdelno_content_nazvanie_obr['id'].', '.$select_album_otdelno_obr['id'].', '.$select_album_otdelno_content_nazvanie_obr['name_content'].')"><i class="">Удалить из альбома</i></button></a></br></div>';

				echo '<div class="del_vesz_iz_album_knopka"><a name="modal" href="#dialog" onclick="del_vesz_iz_album('.$select_album_otdelno_content_nazvanie_obr['id'].', '.$select_album_otdelno_obr['id'].')">Удалить из альбома</a></br></div>';
				echo'<script>
				$(document).ready(function() {
					$(".del_vesz_iz_album_knopka").click(function() {
					$(this).html("Удалено из альбома");
					 $(this).addClass("del_vesz_iz_album_knopka_nazata");
					});
				});
			</script>';
				echo '</div>';
				
				
			}
			echo '</div>';
			echo '</div>';
		}

/*----------------------------------------------------------------------------*/	
			if($url_page_user_content[1] == 'podpiski'){
			
				//echo 'Проверочка тут будут подписки</br>';
				
				$select = mysql_query("SELECT * FROM podpiski where id_user_podpiszik = '".$_COOKIE['id']."'");		
				while($select_obr = mysql_fetch_assoc($select)){
				//echo $select_obr['id_user_podpisnoy'].'</br>';
			echo'<div class="obertka_podpisok">';
				echo'<div class="user_iz_podpisok">';
				$select_user_name = mysql_query("SELECT * FROM users where id = '".$select_obr['id_user_podpisnoy']."'");
				$select_user_name_obr = mysql_fetch_assoc($select_user_name);
			
				if($select_user_name_obr['id'] != $_COOKIE['id']){
					
					
					echo '<div class="user_photo_3"><a href="index.php?user%'.$select_user_name_obr['id'].'"><img id="" src="'.$select_user_name_obr['photo_user'].'"></a></div>';
					echo '<div class="user_link_3"><a href="index.php?user%'.$select_user_name_obr['id'].'">'.$select_user_name_obr['login'].'</br></a></div>';
				}
				else{		
					
					echo '<div class="user_photo_3"><a href="index.php?user%'.$select_user_name_obr['id'].'"><img id="" src="'.$select_user_name_obr['photo_user'].'"></a></div>';
					echo '<div class="user_link_3"><a href="index.php?polzovatel%'.$select_user_name_obr['id'].'">'.$select_user_name_obr['login'].'</br></a></div>';
				}
				
			/*--------------------количество подписчиков у данных юзеров----------------------------------------------*/
			$select_sum_podpisziki_usera = mysql_query("SELECT count(id_user_podpisnoy) FROM podpiski where id_user_podpisnoy = '".$select_user_name_obr['id']."'");
			$select_sum_podpisziki_usera_obr = mysql_fetch_assoc($select_sum_podpisziki_usera);
			echo '<div class="podpiski">'.$select_sum_podpisziki_usera_obr['count(id_user_podpisnoy)'].' подписчиков'.'</div><div class="line_all"></div>';
			/*--------------------количество подписчиков у данных юзеров-----------------//---------------------------*/

					/*------проверка на подписан или нет---------------*/
					$select_proverka_na_podpisku = mysql_query("SELECT * FROM podpiski WHERE id_user_podpiszik = '".$_COOKIE['id']."' and id_user_podpisnoy = '".$select_user_name_obr['id']."'");
					$select_proverka_na_podpisku_obr = mysql_fetch_assoc($select_proverka_na_podpisku);
					
					if($select_proverka_na_podpisku_obr == 0){
					//	echo'<div id="podpis"><button onclick="podpisatsa_na_usera_ajax('.$_COOKIE['id'].','.$select_obr_3['id'].')"><i class="">Подписаться</i></button></a></br></div>';
						echo '<div id=""><a name="modal" href="#dialog" onclick="podpisatsa_na_usera_ajax('.$_COOKIE['id'].','.$select_user_name_obr['id'].')">Подписаться</a></br></div>';
					}
					else{
					//	echo'<div id=""><button onclick="otpisatsia_ot_usera_ajax('.$_COOKIE['id'].','.$select_obr_3['id'].')"><i class="">Отписаться</i></button></a></br></div>';
						echo '<div class="otpisatsia_ot_usera_knopka"><a name="modal" href="#dialog" onclick="otpisatsia_ot_usera_ajax('.$_COOKIE['id'].','.$select_user_name_obr['id'].')">Отписаться</a></br></div>';
						echo'<script>
						$(document).ready(function() {
							$(".otpisatsia_ot_usera_knopka").click(function() {
							$(this).html("Отписка произведена");
							 $(this).addClass("otpisatsia_ot_usera_knopka_nazata");
							});
						});
						</script>';
					}
					/*------проверка на подписан или нет-----//--------*/
				
				
				echo'</div>';
				echo'<div class="veszi_usera_podpisok">';
					$select_my_veszi = mysql_query("SELECT * FROM my_veszi WHERE id_user = '".$select_obr['id_user_podpisnoy']."' and id_user != '".$_COOKIE['id']."' ORDER BY `id` DESC limit 3");
					while($select_my_veszi_obr = mysql_fetch_assoc($select_my_veszi)){
					//echo $select_my_veszi_obr['id_tovar'].'</br>';
					echo'<div class="vesz_iz_podpisok_usera">';
								$select_veszi_tovar = mysql_query("SELECT * FROM tovar WHERE id = '".$select_my_veszi_obr['id_tovar']."'");
								$select_veszi_tovar_obr = mysql_fetch_assoc($select_veszi_tovar);
								echo'<a href="index.php?'.$select_veszi_tovar_obr['id'].'"><img id="veszi_usera_kartinka" src="'.$select_veszi_tovar_obr['photo_tovara'].'"></a>';

					echo'</div>';
					}
					
					echo'</div>';
				echo'</div>';
			}
		
		}			
			
			
		if($url_page_user_content[1] == 'magazini'){
			
			//echo 'Магазины';
			
		$select = mysql_query("SELECT * FROM my_magazini where id_user = '".$_COOKIE['id']."'");		
				while($select_obr = mysql_fetch_assoc($select)){
				//echo $select_obr['id_user_podpisnoy'].'</br>';
			echo'<div class="obertka_podpisok">';
				echo'<div class="user_iz_podpisok">';
				$select_name_magazin = mysql_query("SELECT * FROM magazin where id = '".$select_obr['id_magazin']."'");
				$select_name_magazin_obr = mysql_fetch_assoc($select_name_magazin);
			
			echo '<div class="fotka_magaza"><a href="index.php?magazin='.$select_name_magazin_obr['id'].'"><img id="" src="'.$select_name_magazin_obr['url_photo'].'"></a></div>';
			echo '<div class="name_magaza"><a href="index.php?magazin='.$select_name_magazin_obr['id'].'">'.$select_name_magazin_obr['name_magazin'].'</a></div>';
			
			echo '<div id="show_random_tovar_name_magazin"><a href="index.php?magazin='.$select_name_magazin_obr['id'].'">'.$select_name_magazin_obr['name_magazin'].'</a></div>'; // !!!!!!!!!!!!это юрл магазина!!!!!!!!!
			
			/*--------------------количество подписчиков у данных магазинов----------------------------------------------*/
			$select_sum_podpisziki_magazina = mysql_query("SELECT count(id_user) FROM my_magazini where id_magazin = '".$select_name_magazin_obr['id']."'");
			$select_sum_podpisziki_magazina_obr = mysql_fetch_assoc($select_sum_podpisziki_magazina);
			echo '<div class="podpiski">'.$select_sum_podpisziki_magazina_obr['count(id_user)'].' подписчиков</div>';
			/*--------------------количество подписчиков у данных магазинов-----------------//---------------------------*/

					/*------проверка на подписан или нет---------------*/
					$select_proverka_na_podpisku = mysql_query("SELECT * FROM my_magazini WHERE id_user = '".$_COOKIE['id']."' and id_magazin = '".$select_name_magazin_obr['id']."'");
					$select_proverka_na_podpisku_obr = mysql_fetch_assoc($select_proverka_na_podpisku);
					
					if($select_proverka_na_podpisku_obr == 0){
					
						echo '<div id=""><a name="modal" href="#dialog" onclick="podpisatsa_na_usera_ajax('.$_COOKIE['id'].','.$select_user_name_obr['id'].')">Подписаться</a></br></div>';
					}
					else{
					
						echo '<div class="otpisatsia_ot_usera_knopka"><a name="modal" href="#dialog" onclick="otpisatsia_ot_magazina_ajax('.$_COOKIE['id'].','.$select_name_magazin_obr['id'].')">Отписаться</a></br></div>';
						echo'<script>
						$(document).ready(function() {
							$(".otpisatsia_ot_usera_knopka").click(function() {
							$(this).html("Отписка произведена");
							 $(this).addClass("otpisatsia_ot_usera_knopka_nazata");
							});
						});
						</script>';
					}
					/*------проверка на подписан или нет-----//--------*/
				
				
				echo'</div>';
				echo'<div class="veszi_usera_podpisok">';
					$select_veszi_iz_magazina = mysql_query("SELECT * FROM tovar WHERE name_magazin = '".$select_name_magazin_obr['id']."' and parent_id !=0 limit 3");
					while($select_veszi_iz_magazina_obr = mysql_fetch_assoc($select_veszi_iz_magazina)){
					echo'<div class="vesz_iz_podpisok_usera">';
						echo'<a href="index.php?'.$select_veszi_iz_magazina_obr['id'].'"><img id="veszi_usera_kartinka" src="'.$select_veszi_iz_magazina_obr['photo_tovara'].'"></a>';
					echo'</div>';
					}
					
					echo'</div>';
				echo'</div>';
			
			}
			
			
		}
			
			
			if($url_page_user_content[1] == 'podpisziki'){
			
				//echo '1Проверочка тут будут подписчики</br>';
				
				$select = mysql_query("SELECT * FROM podpiski where id_user_podpisnoy = '".$_COOKIE['id']."'");		
				while($select_obr = mysql_fetch_assoc($select)){
				//echo $select_obr['id_user_podpiszik'];
			echo'<div class="obertka_podpisok">';
				echo'<div class="user_iz_podpisok">';
				$select_user_name = mysql_query("SELECT * FROM users where id = '".$select_obr['id_user_podpiszik']."'");
				$select_user_name_obr = mysql_fetch_assoc($select_user_name);

				if($select_user_name_obr['id'] != $_COOKIE['id']){
					
					echo '<div class="user_photo_3"><a href="index.php?user%'.$select_user_name_obr['id'].'"><img id="" src="'.$select_user_name_obr['photo_user'].'"></a></div>';
					echo '<div class="user_link_3"><a href="index.php?user%'.$select_user_name_obr['id'].'">'.$select_user_name_obr['login'].'</a></div>';
				}
				else{
					
					echo '<div class="user_photo_3"><a href="index.php?polzovatel%'.$select_user_name_obr['id'].'"><img id="" src="'.$select_user_name_obr['photo_user'].'"></a></div>';
					echo '<div class="user_link_3"><a href="index.php?polzovatel%'.$select_user_name_obr['id'].'">'.$select_user_name_obr['login'].'</a></div>';
				}
				
			/*--------------------количество подписчиков у данных юзеров----------------------------------------------*/
			$select_sum_podpisziki_usera = mysql_query("SELECT count(id_user_podpisnoy) FROM podpiski where id_user_podpisnoy = '".$select_user_name_obr['id']."'");
			$select_sum_podpisziki_usera_obr = mysql_fetch_assoc($select_sum_podpisziki_usera);
			echo '<div class="podpiski">'.$select_sum_podpisziki_usera_obr['count(id_user_podpisnoy)'].' подписчиков</div>';
			/*--------------------количество подписчиков у данных юзеров-----------------//---------------------------*/

				echo'</div>';
				
				echo'<div class="veszi_usera_podpisok">';
					$select_my_veszi = mysql_query("SELECT * FROM my_veszi WHERE id_user = '".$select_obr['id_user_podpiszik']."' and id_user != '".$_COOKIE['id']."' ORDER BY `id` DESC limit 3");
					while($select_my_veszi_obr = mysql_fetch_assoc($select_my_veszi)){
					//echo $select_my_veszi_obr['id_tovar'].'</br>';
						echo'<div class="vesz_iz_podpisok_usera">';
								$select_veszi_tovar = mysql_query("SELECT * FROM tovar WHERE id = '".$select_my_veszi_obr['id_tovar']."'");
								$select_veszi_tovar_obr = mysql_fetch_assoc($select_veszi_tovar);
								echo'<a href="index.php?'.$select_veszi_tovar_obr['id'].'"><img id="veszi_usera_kartinka" src="'.$select_veszi_tovar_obr['photo_tovara'].'"></a>';
						echo'</div>';
					}	
				echo'</div>';
			echo'</div>';
				
				} 
				
			}
			
		
	}
	
	
	if($url_page_user_proverka[1] == 'user'){
		
			$select = mysql_query("SELECT * FROM users WHERE id = '".$url_page_user[1]."'");
				$select_obr = mysql_fetch_assoc($select);	
					echo '<div class="obertka_dannix_usera_na_staranize_usera">';
					echo '<div class="photo_usera_na_ego_sranize"><img id="photo_usera_na_ego_sranize_img" src="'.$select_obr['photo_user'].'"></br></div>';
				echo '<div class="dannie_usera_nastranize_usera">';
				//	echo $select_obr['id'].'</br>';
				//	echo $select_obr['name'].'</br>';
				//	echo $select_obr['last_name'].'</br>';
					echo '<div class="stroki_dannix_usera">Login: '.$select_obr['login'].'</div></br>';
				//	echo $select_obr['password'].'</br>';
					echo '<div class="stroki_dannix_usera">e-mail: '.$select_obr['mail'].'</div></br>';
				//	echo $select_obr['reg_date'].'</br>';
					echo '<div class="stroki_dannix_usera">country: '.$select_obr['country'].'</div></br>';
					echo '<div class="stroki_dannix_usera">city: '.$select_obr['city'].'</div></br>';
					
						
			
			
					$select_proverka_na_podpisku = mysql_query("SELECT * FROM podpiski WHERE id_user_podpiszik = '".$_COOKIE['id']."' and id_user_podpisnoy = '".$select_obr['id']."'");
					$select_proverka_na_podpisku_obr = mysql_fetch_assoc($select_proverka_na_podpisku);
					if($select_proverka_na_podpisku_obr == 0){
					//	echo'<div id="podpis"><button onclick="podpisatsa_na_usera_ajax('.$_COOKIE['id'].','.$select_obr['id'].')"><i class="">Подписаться</i></button></a></br></div>';
	
						echo '<div class="podpisatsa_na_usera_knopka"><a name="modal" href="#dialog" onclick="podpisatsa_na_usera_ajax('.$_COOKIE['id'].','.$select_obr['id'].')">Подписаться</a></br></div>';
						echo'<script>
						$(document).ready(function() {
							$(".podpisatsa_na_usera_knopka").click(function() {
							$(this).html("Вы подписались на пользователя '.$select_obr['login'].'");
							 $(this).addClass("podpisatsa_na_usera_knopka_nazata");
							});
						});
						</script>';
					}
					else{
					//	echo'<div id=""><button onclick="otpisatsia_ot_usera_ajax('.$_COOKIE['id'].','.$select_obr['id'].')"><i class="">Отписаться</i></button></a></br></div>';
						echo '<div class="otpisatsia_ot_usera_knopka"><a name="modal" href="#dialog" onclick="otpisatsia_ot_usera_ajax('.$_COOKIE['id'].','.$select_obr['id'].')">Отписаться</a></br></div>';
						echo'<script>
						$(document).ready(function() {
							$(".otpisatsia_ot_usera_knopka").click(function() {
							$(this).html("Отписка произведена");
							 $(this).addClass("otpisatsia_ot_usera_knopka_nazata");
							});
						});
						</script>';
					}
			echo '</div>';	
		echo '</div>';
				
			echo'<div class="user_albom_heder">	
			<div class="user_albom_menu_left"></div>			
			<ul class="user_albom_menu">
					<li class="user_albom_podmenu">';
				$select_colvo_veszey = mysql_query("SELECT count(id_tovar) from my_veszi where id_user = '".$select_obr['id']."'");
				$select_colvo_veszey_obr = mysql_fetch_assoc($select_colvo_veszey);	
				echo'<a class="user_albom_podmenu_a" href="index.php?user%'.$select_obr['id'].'!veszi">вещи ('.$select_colvo_veszey_obr['count(id_tovar)'].')</a>	
					</li>
					
					<li class="user_albom_podmenu">';
					$select_colvo_albomov = mysql_query("SELECT count(name_content) from my_album where id_user = '".$select_obr['id']."' and parent_id = 0");
					$select_colvo_albomov_obr = mysql_fetch_assoc($select_colvo_albomov);
				echo'<a class="user_albom_podmenu_a" href="index.php?user%'.$select_obr['id'].'!albom">альбом ('.$select_colvo_albomov_obr['count(name_content)'].')</a>	
					</li>
					
					<li class="user_albom_podmenu">';
					$select_colvo_podpisok = mysql_query("SELECT count(id_user_podpisnoy) from podpiski where id_user_podpiszik = '".$select_obr['id']."'");
					$select_colvo_podpisok_obr = mysql_fetch_assoc($select_colvo_podpisok);	
				echo'<a class="user_albom_podmenu_a" href="index.php?user%'.$select_obr['id'].'!podpiski">подписки ('.$select_colvo_podpisok_obr['count(id_user_podpisnoy)'].')</a>	
					</li>
					
					<li class="user_albom_podmenu">';
					$select_colvo_podpisok_na_magazin = mysql_query("SELECT count(id_magazin) from my_magazini where id_user = '".$select_obr['id']."'");
					$select_colvo_podpisok_na_magazin_obr = mysql_fetch_assoc($select_colvo_podpisok_na_magazin);
				echo'<a class="user_albom_podmenu_a" href="index.php?user%'.$select_obr['id'].'!magazini">магазины ('.$select_colvo_podpisok_na_magazin_obr['count(id_magazin)'].')</a>	
					</li>
					
					<li class="user_albom_podmenu">';
					$select_colvo_podpiszikov = mysql_query("SELECT count(id_user_podpisnoy) from podpiski where id_user_podpisnoy = '".$select_obr['id']."'");
					$select_colvo_podpiszikov_obr = mysql_fetch_assoc($select_colvo_podpiszikov);
				echo'<a class="user_albom_podmenu_a" href="index.php?user%'.$select_obr['id'].'!podpisziki">подписчики ('.$select_colvo_podpiszikov_obr['count(id_user_podpisnoy)'].')</a>	
					</li>
			</ul>';
			
			echo'<script>
				$(function() {
					var pgurl = window.location.href.substr(window.location.href.lastIndexOf("/")+1);
					 $(".user_albom_podmenu_a").each(function(){
						  if($(this).attr("href") == pgurl || $(this).attr("href") == "" )
						  $(this).addClass("active");
					 })
				});
			</script>';
			
			echo '<div class="user_albom_menu_right"></div>			
			</div>';	
			
			echo'<div class="user_albom_content">';
					
			$url_page_user_content = explode("!", url());
			
			$url_page_user_content_album_name = explode(";", url());
			
			if($url_page_user_content[1] == 'veszi'){
			
				//echo 'Проверочка тут уже есть список вещей'.'</br>';
				
				$select = mysql_query("SELECT * from my_veszi where id_user = '".$select_obr['id']."'");
				while($select_obr = mysql_fetch_assoc($select)){
			
				
					$select_tovar = mysql_query("SELECT * FROM tovar where id = '".$select_obr['id_tovar']."'");
					while($select_tovar_obr = mysql_fetch_assoc($select_tovar)){				
						echo '<div class="show_random_tovar">
						<div id="show_random_tovar_photo_tovara"><a href="index.php?'.$select_tovar_obr['id'].'"><img id="show_image_tovar_gl_str" src="'.$select_tovar_obr['photo_tovara'].'"></a></div>
						<div id="show_random_tovar_name"><a href="index.php?'.$select_tovar_obr['id'].'">"'.$select_tovar_obr['name_content'].'"</a></div>
						</div>';			
					}
				}
			}
			
			if($url_page_user_content[1] == 'albom'){
			
				echo 'Проверочка тут будут альбомы';
				echo $url_page_user_content_album_name[1];
		
			$select_album = mysql_query("SELECT * FROM my_album WHERE id_user = '".$select_obr['id']."' and parent_id = 0");
			while($select_album_obr = mysql_fetch_assoc($select_album)){
			echo '<div class="album_obertka">';	
				echo '<div class="album_name_obertka">';
				//	echo $select_album_obr['id'].'</br>';
				//	echo $select_album_obr['name_content'].'</br>';
					echo'<div class="name_albums"><a class="" href="index.php?user%'.$select_obr['id'].'!albom;'.$select_album_obr['id'].'">'.$select_album_obr['name_content'].'</a></div></br>';
				
				echo '</div>';
				echo '<div class="album_obertka_veszi_all">';
					$select_album_content_id = mysql_query("SELECT * FROM my_album WHERE parent_id = '".$select_album_obr['id']."' LIMIT 3");
					while($select_album_content_id_obr = mysql_fetch_assoc($select_album_content_id)){
						
						$select_album_content_name = mysql_query("SELECT * FROM tovar WHERE id = '".$select_album_content_id_obr['name_content']."'");
						$select_album_content_name_obr = mysql_fetch_assoc($select_album_content_name);
				echo '<div class="album_obertka_veszi_one">';
					echo'<a href="index.php?'.$select_album_content_name_obr['id'].'"><img id="veszi_usera_kartinka" src="'.$select_album_content_name_obr['photo_tovara'].'"></a>';
				echo '</div>';	
					}
					echo '</div>';	
				echo '</div>';	
				}
	
		}
		
		if($url_page_user_content_album_name[1] != 0){
			
			$select_album_otdelno = mysql_query("SELECT * FROM my_album where id = '".$url_page_user_content_album_name[1]."'");
			$select_album_otdelno_obr = mysql_fetch_assoc($select_album_otdelno);
				echo '<div class="album_otdelno_obertka_all">';
				echo '<div class="album_otdelno_obertka_name_album">';
				echo $select_album_otdelno_obr['name_content'];
				echo '</div>';
				echo '<div class="album_otdelno_obertka_name_content_obertka">';
				$select_album_otdelno_content = mysql_query("SELECT * FROM my_album WHERE parent_id = '".$select_album_otdelno_obr['id']."'");
				while($select_album_otdelno_content_obr = mysql_fetch_assoc($select_album_otdelno_content)){
				//echo $select_album_otdelno_content_obr['name_content'];
				$select_album_otdelno_content_nazvanie = mysql_query("SELECT * FROM tovar WHERE id = '".$select_album_otdelno_content_obr['name_content']."'");
				$select_album_otdelno_content_nazvanie_obr = mysql_fetch_assoc($select_album_otdelno_content_nazvanie);
				
				echo '<div class="album_otdelno_obertka_name_content">';
				
				echo'<a href="index.php?'.$select_album_otdelno_content_nazvanie_obr['id'].'"><img id="veszi_usera_kartinka" src="'.$select_album_otdelno_content_nazvanie_obr['photo_tovara'].'"></a>';
				
				echo '</div>';
				
				
			}
			echo '</div>';
			echo '</div>';
		}
			
			if($url_page_user_content[1] == 'podpiski'){
			
				echo 'Проверочка тут будут подписки</br>';
				
				$select = mysql_query("SELECT * FROM podpiski where id_user_podpiszik = '".$select_obr['id']."'");		
				while($select_obr = mysql_fetch_assoc($select)){
				//echo $select_obr['id_user_podpisnoy'].'</br>';
				echo'<div class="obertka_podpisok">';
				echo'<div class="user_iz_podpisok">';
				$select_user_name = mysql_query("SELECT * FROM users where id = '".$select_obr['id_user_podpisnoy']."'");
				$select_user_name_obr = mysql_fetch_assoc($select_user_name);
				
				if($select_user_name_obr['id'] != $_COOKIE['id']){
				
					echo '<div class="user_photo_3"><a href="index.php?user%'.$select_user_name_obr['id'].'"><img id="" src="'.$select_user_name_obr['photo_user'].'"></a></div>';
					echo '<div class="user_link_3"><a href="index.php?user%'.$select_user_name_obr['id'].'">'.$select_user_name_obr['login'].'</br></a></div>';

				}
				else{			
					
					echo '<div class="user_photo_3"><a href="index.php?user%'.$select_user_name_obr['id'].'"><img id="" src="'.$select_user_name_obr['photo_user'].'"></a></div>';
					echo '<div class="user_link_3"><a href="index.php?polzovatel%'.$select_user_name_obr['id'].'">"'.$select_user_name_obr['login'].'"</br></a></div>';
				}
				
				/*--------------------количество подписчиков у данных юзеров----------------------------------------------*/
				$select_sum_podpisziki_usera = mysql_query("SELECT count(id_user_podpisnoy) FROM podpiski where id_user_podpisnoy = '".$select_user_name_obr['id']."'");
				$select_sum_podpisziki_usera_obr = mysql_fetch_assoc($select_sum_podpisziki_usera);
				echo '<div class="podpiski">'.$select_sum_podpisziki_usera_obr['count(id_user_podpisnoy)'].' подписчиков'.'</div><div class="line_all"></div>';
				/*--------------------количество подписчиков у данных юзеров-----------------//---------------------------*/
		
				echo '</div>';
				echo'<div class="veszi_usera_podpisok">';
					$select_my_veszi = mysql_query("SELECT * FROM my_veszi WHERE id_user = '".$select_obr['id_user_podpisnoy']."' ORDER BY `id` DESC limit 3");
					while($select_my_veszi_obr = mysql_fetch_assoc($select_my_veszi)){
					//echo $select_my_veszi_obr['id_tovar'].'</br>';
						echo'<div class="vesz_iz_podpisok_usera">';
								$select_veszi_tovar = mysql_query("SELECT * FROM tovar WHERE id = '".$select_my_veszi_obr['id_tovar']."'");
								$select_veszi_tovar_obr = mysql_fetch_assoc($select_veszi_tovar);
								echo'<a href="index.php?'.$select_veszi_tovar_obr['id'].'"><img id="veszi_usera_kartinka" src="'.$select_veszi_tovar_obr['photo_tovara'].'"></a>';

						echo'</div>';
					}
					echo'</div>';
				echo '</div>';
				
				}
				
			
			}
			
			if($url_page_user_content[1] == 'magazini'){
			
			echo 'Магазины';
			
		$select = mysql_query("SELECT * FROM my_magazini where id_user = '".$select_obr['id']."'");		
				while($select_obr = mysql_fetch_assoc($select)){
				//echo $select_obr['id_user_podpisnoy'].'</br>';
			echo'<div class="obertka_podpisok">';
				echo'<div class="user_iz_podpisok">';
				$select_name_magazin = mysql_query("SELECT * FROM magazin where id = '".$select_obr['id_magazin']."'");
				$select_name_magazin_obr = mysql_fetch_assoc($select_name_magazin);
			
			echo '<div class="fotka_magaza"><a href="index.php?magazin='.$select_name_magazin_obr['id'].'"><img id="" src="'.$select_name_magazin_obr['url_photo'].'"></a></div>';
			
			echo '<div class="name_magaza"><a href="index.php?magazin='.$select_name_magazin_obr['id'].'">'.$select_name_magazin_obr['name_magazin'].'</a></br></div>';
			
			echo '<div id="show_random_tovar_name_magazin"><a href="index.php?magazin='.$select_name_magazin_obr['id'].'">'.$select_name_magazin_obr['name_magazin'].'</a></div>'; // !!!!!!!!!!!!это юрл магазина!!!!!!!!!
			
			/*--------------------количество подписчиков у данных магазинов----------------------------------------------*/
			$select_sum_podpisziki_magazina = mysql_query("SELECT count(id_user) FROM my_magazini where id_magazin = '".$select_name_magazin_obr['id']."'");
			$select_sum_podpisziki_magazina_obr = mysql_fetch_assoc($select_sum_podpisziki_magazina);
			echo '<div class="podpiski">'.$select_sum_podpisziki_magazina_obr['count(id_user)'].' подписчиков'.'</div><div class="line_all"></div>';
			/*--------------------количество подписчиков у данных магазинов-----------------//---------------------------*/
				
				
				echo'</div>';
				echo'<div class="veszi_usera_podpisok">';
					$select_veszi_iz_magazina = mysql_query("SELECT * FROM tovar WHERE name_magazin = '".$select_name_magazin_obr['id']."' and parent_id !=0 limit 3");
					while($select_veszi_iz_magazina_obr = mysql_fetch_assoc($select_veszi_iz_magazina)){
					echo'<div class="vesz_iz_podpisok_usera">';
						echo'<a href="index.php?'.$select_veszi_iz_magazina_obr['id'].'"><img id="veszi_usera_kartinka" src="'.$select_veszi_iz_magazina_obr['photo_tovara'].'"></a>';
					echo'</div>';
					}
					
					echo'</div>';
				echo'</div>';
			
			}
			
			
		}
			
			
			if($url_page_user_content[1] == 'podpisziki'){
			
				echo 'Проверочка тут будут подписчики</br>';
				
				$select = mysql_query("SELECT * FROM podpiski where id_user_podpisnoy = '".$select_obr['id']."'");		
				while($select_obr = mysql_fetch_assoc($select)){
				//echo $select_obr['id_user_podpiszik'];
				echo'<div class="obertka_podpisok">';
				echo'<div class="user_iz_podpisok">';	
				$select_user_name = mysql_query("SELECT * FROM users where id = '".$select_obr['id_user_podpiszik']."'");
				$select_user_name_obr = mysql_fetch_assoc($select_user_name);
				
				if($select_user_name_obr['id'] != $_COOKIE['id']){
					echo '<div class="user_photo_3"><a href="index.php?user%'.$select_user_name_obr['id'].'"><img id="" src="'.$select_user_name_obr['photo_user'].'"></a></div>';
					echo '<div class="user_link_3"><a href="index.php?user%'.$select_user_name_obr['id'].'">'.$select_user_name_obr['login'].'</br></a></div>';
					
				}
				else{
					echo '<div class="user_photo_3"><a href="index.php?user%'.$select_user_name_obr['id'].'"><img id="" src="'.$select_user_name_obr['photo_user'].'"></a></div>';
					echo '<div class="user_link_3"><a href="index.php?polzovatel%'.$select_user_name_obr['id'].'">'.$select_user_name_obr['login'].'</br></a></div>';
				}
				
				/*--------------------количество подписчиков у данных юзеров----------------------------------------------*/
				$select_sum_podpisziki_usera = mysql_query("SELECT count(id_user_podpisnoy) FROM podpiski where id_user_podpisnoy = '".$select_user_name_obr['id']."'");
				$select_sum_podpisziki_usera_obr = mysql_fetch_assoc($select_sum_podpisziki_usera);
				echo '<div class="podpiski">'.$select_sum_podpisziki_usera_obr['count(id_user_podpisnoy)'].' подписчиков'.'</div><div class="line_all"></div>';
			
				/*--------------------количество подписчиков у данных юзеров-----------------//---------------------------*/	
				
				echo'</div>';
				echo'<div class="veszi_usera_podpisok">';
					$select_my_veszi = mysql_query("SELECT * FROM my_veszi WHERE id_user = '".$select_obr['id_user_podpiszik']."' ORDER BY `id` DESC limit 3");
					while($select_my_veszi_obr = mysql_fetch_assoc($select_my_veszi)){
					//echo $select_my_veszi_obr['id_tovar'].'</br>';
						echo'<div class="vesz_iz_podpisok_usera">';
								$select_veszi_tovar = mysql_query("SELECT * FROM tovar WHERE id = '".$select_my_veszi_obr['id_tovar']."'");
								$select_veszi_tovar_obr = mysql_fetch_assoc($select_veszi_tovar);
								echo'<a href="index.php?'.$select_veszi_tovar_obr['id'].'"><img id="veszi_usera_kartinka" src="'.$select_veszi_tovar_obr['photo_tovara'].'"></a>';

						echo'</div>';	
					}
					echo'</div>';
					
				echo'</div>';
				}
			
			}
			
		// echo'</div>';
			
							
	}
	
}

function top_user(){
		
		$select_2 = mysql_query("SELECT id_user_podpisnoy, count(id_user_podpisnoy) from podpiski GROUP BY `id_user_podpisnoy` ORDER BY count(id_user_podpisnoy) DESC LIMIT 10");
			while($select_obr_2 = mysql_fetch_assoc($select_2)){
			echo '<div class="top_zelovek">';
			   echo'<div class="user_top_obertka">';
			 
				
				echo '<div class="podpischiki_top">'.$select_obr_2['count(id_user_podpisnoy)'].'&nbsp; подписчиков'.'</div>';
			
				$select_3 = mysql_query("SELECT * FROM users WHERE id = '".$select_obr_2['id_user_podpisnoy']."'");
				while($select_obr_3 = mysql_fetch_assoc($select_3)){
		
				if($select_obr_3['id'] != $_COOKIE['id']){
						
					
						echo '<div class="name_top_ludi">'.$select_obr_3['login'].'</div>';
					
						echo '<div class="user_photo"><img id="user_photo_kartinka" src="'.$select_obr_3['photo_user'].'"></div>';

						
				}
				else{
					
						
						echo $select_obr_3['login'];
						
						echo '<div class="user_photo"><img id="user_photo_kartinka" src="'.$select_obr_3['photo_user'].'"></div>';
						
				}
				
				echo'</div>';
				
				echo'<div class="veszi_usera_obertka">';
				
				
				
					$select_id_tovar_usera = mysql_query("SELECT * from my_veszi WHERE id_user = '".$select_obr_3['id']."' LIMIT 4");
						while($select_id_tovar_usera_obr = mysql_fetch_assoc($select_id_tovar_usera)){
							//echo $select_id_tovar_usera_obr['id_tovar'].' ';
							$select_name_tovara = mysql_query("SELECT * FROM tovar WHERE id = '".$select_id_tovar_usera_obr['id_tovar']."' and parent_id !=0");
							while($select_name_tovara_obr = mysql_fetch_assoc($select_name_tovara)){
								echo'<div class="veszi_usera">';
								echo'<a href="index.php?'.$select_name_tovara_obr['id'].'"><img id="veszi_usera_kartinka" src="'.$select_name_tovara_obr['photo_tovara'].'"></a>';
								echo '</div>';
							}
						}			
			echo '</div>';
			}
			echo '</div>';
		}
		
			
		
}
function top_magazin(){
					
		$select_top_magazin = mysql_query("SELECT id_magazin, count(id_magazin) from my_magazini GROUP BY `id_magazin` ORDER BY count(id_magazin) DESC");
			while($select_top_magazin_obr = mysql_fetch_assoc($select_top_magazin)){
			echo '<div class="top_magazin">';
			echo '<div class="magazin_name_obertka">';
			//	echo '</br>'.$select_top_magazin_obr['id_magazin'].' ';
			//	echo $select_top_magazin_obr['count(id_magazin)'].' ';
				$select_top_magazin_name = mysql_query("SELECT * FROM magazin where id = '".$select_top_magazin_obr['id_magazin']."'");
				$select_top_magazin_name_obr = mysql_fetch_assoc($select_top_magazin_name);
				
			echo '<div class="magaz_photo"><a href="index.php?magazin='.$select_top_magazin_name_obr['id'].'"><img id="" src="'.$select_top_magazin_name_obr['url_photo'].'"></a></div>';
			
			echo '<div class="magaz_nazva"><a href="index.php?magazin='.$select_top_magazin_name_obr['id'].'">'.$select_top_magazin_name_obr['name_magazin'].'</a></div><br/>';
			
			/*--------------------количество подписчиков у данных магазинов----------------------------------------------*/
			$select_sum_podpisziki_magazina = mysql_query("SELECT count(id_user) FROM my_magazini where id_magazin = '".$select_top_magazin_name_obr['id']."'");
			$select_sum_podpisziki_magazina_obr = mysql_fetch_assoc($select_sum_podpisziki_magazina);
				echo '<div class="magaz_podposchiki">'.$select_sum_podpisziki_magazina_obr['count(id_user)'].' подписчиков'.'</div>
			<div class="virov"></div>';
			/*--------------------количество подписчиков у данных магазинов-----------------//---------------------------*/

	
			echo '</div>';
			echo '<div class="veszi_iz_magazina">';
				$select_top_magazin_content = mysql_query("SELECT * FROM tovar where name_magazin = '".$select_top_magazin_obr['id_magazin']."' LIMIT 4");
				while($select_top_magazin_content_obr = mysql_fetch_assoc($select_top_magazin_content)){
			echo '<div class="kartinka_tovara_iz_magazina">';
				
				echo'<a href="index.php?'.$select_top_magazin_content_obr['id'].'"><img id="veszi_usera_kartinka" src="'.$select_top_magazin_content_obr['photo_tovara'].'"></a>';

			echo '</div>';
				}
			echo '</div>';	
		echo '</div>';
			}
}


/*------------------подписка и отписка------------------------------------*/
function user_podpiska($id_user_podpiszik, $id_user_podpisnoy){

	$id_user_podpiszik = $_POST['id_user_podpiszik'];	
	$id_user_podpisnoy = $_POST['id_user_podpisnoy'];
	
	if(mysql_query("INSERT INTO podpiski (id_user_podpiszik, id_user_podpisnoy) VALUES ('".$id_user_podpiszik."','".$id_user_podpisnoy."')")){
	
		echo 'Подписка произведена';
	}
}

function user_otpiska($id_user_podpiszik, $id_user_podpisnoy){

	$id_user_podpiszik = $_POST['id_user_podpiszik'];	
	$id_user_podpisnoy = $_POST['id_user_podpisnoy'];
	
	if(mysql_query("DELETE FROM podpiski WHERE id_user_podpiszik = '".$id_user_podpiszik."' and id_user_podpisnoy = '".$id_user_podpisnoy."'")){
	
		echo 'Отписка от юзера произведена';
	}
}
/*------------------подписка и отписка---------------//-------------------*/



function logo(){

 $logo ='<div class="logo"><a href="http://'.$_SERVER['SERVER_NAME'].'"><img src="../images/logo.png"/></a></div>';
return($logo);
}

function headers(){

if (!isset($_COOKIE['login']) && !isset($_COOKIE['password'])){
echo '<div class="line_top"><div class="top_all"><div class="top_all_center">'.logo().'<a class="prisoedenitsa"  name="modal" href="#dialog" onclick="registration()" >Присоединиться</a>
<a href="#" class="o_nas">О нас <i class="fa fa-angle-down"></i></a><a href="#" class="o_nas">Фото редактора <i class="fa fa-angle-down"></i></a><a class="voiti"  name="modal" href="#dialog" onclick="loginisation()" >Войти</a>
</div></div></div>';
?>
<div class="pod_filtr"><div class="top_all_center"><div class="menu_"><?php filtr(); top();?></div></div></div>
<?php

}

if (isset($_COOKIE['login']) && isset($_COOKIE['password'])){
echo '<div class="line_top_2"><div class="top_all"><div class="top_all_center">'.logo().'<a href="#" class="o_nas_2">О нас <i class="fa fa-angle-down"></i></a><a href="#" class="o_nas">Фото редактора <i class="fa fa-angle-down"></i>
<a class="exit" href="#dialog" onclick="exit_ajax()">Выход</a><div class="avatarka_na_paneli">'.show_avatarka($_COOKIE['id']).'</div></div></div>';
?>
<div class="pod_filtr"><div class="top_all_center"><div class="menu_"><?php filtr(); top(); menu_user_page();?></div></div></div>
<?php
}

}

function contents(){
?>					
					<!--МОДАЛЬНОЕ ОКНО-->
					<script>
					$(document).ready(function() {   
					$('a[name=modal]').click(function(e) {
					e.preventDefault();
					var id = $(this).attr('href');
					var maskHeight = $(document).height();
					var maskWidth = $(window).width();
					$('#mask').css({'width':maskWidth,'height':maskHeight});
					$('#mask').fadeIn(1000); 
					$('#mask').fadeTo("slow",0.8); 
					var winH = $(window).height();
					var winW = $(window).width();
					$(id).css('top',  winH/2-$(id).height()/2);
					$(id).css('left', winW/2-$(id).width());
					$(id).fadeIn(2000); 
					});
					$('.window .close').click(function (e) { 
					e.preventDefault();
					$('#mask, .window').hide();
					}); 
					$('#mask').click(function () {
					$(this).hide();
					$('.window').hide();
					}); 
				   });  
				  	</script>
					 
					<div class="test"></div>
					<div id="boxes">  
						<div id="dialog" class="window"> 
							<div class="top">
								<a href="#" class="link close"  alt="Закрыть окно" title="Закрыть окно"/>закрыть</a>
								
							</div>
							<div class="content">Текст в модальном окне2.</div>
						</div>
					</div>
					
					

					<!-- Маска, затемняющая фон -->
					
					<!--\\\\МОДАЛЬНОЕ ОКНО-->
					
<?php
	if (isset($_SESSION['id']) || (isset($_COOKIE['login']) && isset($_COOKIE['password'])))
		{
						
			$cookie_login = $_COOKIE['login']; 
			$admin_auntintiphikacia = admin_auntintiphikacia($cookie_login);
			
				
			if ($admin_auntintiphikacia)
				{
					
					
					?>
					<div class="admin_all_page">
					
						<div class="left_admin_panel">
							<div class="zagolovok_blokov_admin">Админ меню</div>
							<div class="block_admin_content">
								<div class="one"><a class="showe_tabl_users" href="#dialog" onclick="showe_tabl_users()"><i class="fa fa-server"></i>Меню юзеров</a></div>
								
								<div class="two"><a class="add_tovar" href="#dialog" onclick="add_tovar_ajax()"><i class="fa fa-th-large"></i>Добавление товара</a></div>
								
								<div class="tree"><a class="view_and_redact_rubric" href="#dialog" onclick="view_and_add_rubric_ajax()"><i class="fa fa-th-list"></i>Соз и ред рубрик</a></div>
								
								<div class="for"><a class="view_and_add_magazin" href="#dialog" onclick="view_and_add_magazin_ajax()"><i class="fa fa-tasks"></i>Соз и ред магазина</a></div>
								
								<div class="for"><a class="view_and_add_magazin" href="#dialog" onclick="view_and_add_and_redact_statei_ajax()"><i class="fa fa-indent"></i>Соз и ред статей</a></div>
							</div>
						</div>
						<?php
						/* 
						echo 'admin <br/>';
						echo $_COOKIE['login'].'<br/>';
						echo $_COOKIE['password'].'<br/>';
						echo $_COOKIE['id'].'<br/>';
						echo $_SESSION['id'].'<br/>'; 
						*/
						?>
						
						
						
						<div style="width:950px;" class="right_admin_panel" id="messege">
						
						
						</div>
						<?php
						}
						
						else 
							{	
							
							/*
							 	echo 'polzovatel <br/>';
								echo $_COOKIE['login'].'<br/>';
								echo $_COOKIE['password'].'<br/>';
								echo $_COOKIE['id'].'<br/>';
								echo $_SESSION['id'].'<br/></p>';
							*/	 
								
							} 
						
						
						}											
					
						?>
						
					</div>
					<div class="show_random_tovar_div_obertka">
			<?php
				if (isset($_SESSION['id']) || (isset($_COOKIE['login']) && isset($_COOKIE['password'])))
				{
										
					$cookie_login = $_COOKIE['login']; 
					$admin_auntintiphikacia = admin_auntintiphikacia($cookie_login);
							
								
					if ($admin_auntintiphikacia)
						{
							
							
							$url_page = explode("?", url());
	
							if($url_page[1]!=0){
										
								straniza_tovara();
								
									
							}
							
							$url_page_magazin_id = explode("=", url());
							
							if($url_page_magazin_id[1] != 0){
							
								straniza_magazina();
							
							}
							
							$url_page_2 = explode("&", url());
							
							if($url_page_2){
							
								probniy_filtr_sel();
							
							}
							
						
							if(!isset($url_page[1]) or !isset($url_page_2)){
							
								show_random_tovar_admin();
								
							}

							if(!isset($url_page_user[1])){
							
								//probniy_filtr_sel();
								user();
							
							}
					}
						
					
							
					else
						{
							$url_page = explode("?", url());
							
							
	
							if($url_page[1]!=0){
										
								straniza_tovara();	
									
							}
							
							$url_page_magazin_id = explode("=", url());
							
							if($url_page_magazin_id[1] != 0){
							
								straniza_magazina();
							
							}
							/*
							if($url_page[1] == 'magazin'){
										
								straniza_magazina();	
							
							}
							*/
							$url_page_2 = explode("&", url());
							
							if($url_page_2){
							
								probniy_filtr_sel();
							
							}
							
							
							if(!isset($url_page[1]) or !isset($url_page_2)){
							
								show_random_tovar_user();
								
							}

							
							if(!isset($url_page_user[1])){
							
								//probniy_filtr_sel();
								user();
							
							}
																
						}
						
				}
					
					else
						{
							$url_page = explode("?", url());
	
							if($url_page[1]!=0){
										
								straniza_tovara();	
									
							}
							
							$url_page_2 = explode("&", url());
							
							if($url_page_2){
							
								probniy_filtr_sel();
							
							}
							
							$url_page_magazin_id = explode("=", url());
							
							if($url_page_magazin_id[1] != 0){
							
								straniza_magazina();
							
							}
							
							
							if(!isset($url_page[1]) or !isset($url_page_2)){
							
								show_random_tovar_user();
								
								
							}

							if($url_page[1] == 'top_user'){
						
								top_user();
						
							}
							
							
							if($url_page[1] == 'top_magazin'){
							
								top_magazin();
								
							}
						}
					?>
					</div>
<?php

}

function footer(){

}

?>
