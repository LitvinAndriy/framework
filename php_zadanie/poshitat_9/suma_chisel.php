<?php
	
		$fio = $_POST['mytext'];
	if($fio < 10000 and 0 < $fio){
		echo'число нормальное!';
		
		
	}else{echo'число перевышает лимит!';}
	
?>