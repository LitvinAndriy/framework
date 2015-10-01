<?php 
ini_set ("session.use_trans_sid", true);
session_start(); 
print_r($_COOKIE['id']);
print_r($_COOKIE['login']);
print_r($_COOKIE['password']);
?>