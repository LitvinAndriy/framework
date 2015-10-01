<?php
$text = $_POST['mytext'];
$text_2 = $_POST['mytext_2'];
 $y = preg_split('//', $text, -1, PREG_SPLIT_NO_EMPTY);
$z = array_count_values($y);
echo 'в етом числе цыфра ('.$text_2.') повторилась '.$z[$text_2].' рас';
//print_r($z);
















?>