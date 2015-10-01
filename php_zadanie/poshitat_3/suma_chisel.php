<?php
for($i=20; $i<40; $i++){
	
	$r = fmod($i, 5);
	if($r == 0){
		 $iSum+=$i;
	}

}
echo $iSum;

















?>