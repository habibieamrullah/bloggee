<?php include("config.php"); ?>
<?php

if(isset($_POST["adminusername"]) && isset($_POST["adminpassword"])){
	if($_POST["adminusername"] == $username && $_POST["adminpassword"] == $password){
		if(isset($_POST["json"])){
		
			$data = $_POST["json"];
			$myfile = fopen("data.txt", "w") or die("Error!");
			fwrite($myfile, $data);
			fclose($myfile);	
			
		}
	}
}