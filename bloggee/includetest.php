<?php 


function includeme(){
	
	$result = "";
	
	include("included.php");
	
	echo $result;
}

echo includeme();