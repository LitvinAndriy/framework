<?php
	
		$fio = $_POST['mytext'];
		
		$karta[2] = "двойка";
		$karta[4] = "четыри";
		$karta[6] = "шестерка";
		$karta[9] = "девятка";
		$karta[10] = "десятка";
		$karta[11] = "валет";
		$karta[12] = "дама";
		$karta[13] = "король";
		$karta[14] = "туз";
		
		echo $karta[$fio];
	
	
?>