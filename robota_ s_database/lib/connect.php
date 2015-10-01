<?php
@mysql_connect("localhost", "root", "", "andrej")
	or die ("Ошибка подключения к базе данных");
@mysql_select_db("andrej");
?>