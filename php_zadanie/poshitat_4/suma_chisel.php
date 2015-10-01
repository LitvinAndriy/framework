<?php
	$a = array();
	for($i = 0; $i < 20; ++$i){
    $a[] = array(rand(1,100));}
	
	$b = array_unique(call_user_func_array('array_merge', $a));
	
print_r($b);
		$min= min($b);
		$max = max($b);

foreach($b as $key => $value){
	
	if($min == $value){
	$key_min = $key;
		
	}
	if($max == $value){
		$key_max = $key;
		
	}}
	
	list($b[$key_min], $b[$key_max]) = array( $b[$key_max], $b[$key_min]);
	print_r($b);
	echo $key_max.'</br>';
	echo $key_min.'</br>';
	
	
	

	

?>