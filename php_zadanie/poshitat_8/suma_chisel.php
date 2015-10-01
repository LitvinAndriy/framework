<?php
$fio = $_POST['mytext'];

	for($i = 1; $i < 61; ++$i){
		//$arra = array([$i]$i);
		$names[$i]=$i;
		
	}
	
	 $z = $fio/5;
	 
		$y = explode('.',$z);
		
		
		  
	if($y[1] < 7 and $y[1] > 0){
		echo "зелений";
		
	}else{
		echo "красный";
	}
	
	
	
	
	
?>