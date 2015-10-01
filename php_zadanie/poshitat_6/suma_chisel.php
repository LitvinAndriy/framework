<?php
	$fio = $_POST['mytext'];

	$expl = explode(" ",$fio);
	

	$I = mb_substr($expl[1],0,1,"UTF-8");
	$O = mb_substr($expl[2],0,1,"UTF-8");

	
	echo $expl[0].' '.$I.'.'.$O;
	

?>