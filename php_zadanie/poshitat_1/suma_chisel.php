<?php
$text = $_POST['mytext'];
$text_1 = preg_split('//', $text, -1, PREG_SPLIT_NO_EMPTY);
$rez = array_sum($text_1);
echo 'Сума всех чисел равняется = '.$rez;


	
















?>