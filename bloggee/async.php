<?php include("config.php"); ?>
<?php

if(isset($_POST["adminusername"]) && isset($_POST["adminpassword"])){
	if($_POST["adminusername"] == $username && $_POST["adminpassword"] == $password){
		
		if(isset($_POST["json"])){
		
			$data = $_POST["json"];
			$myfile = fopen($filedb, "w") or die("Error!");
			fwrite($myfile, $data);
			fclose($myfile);	
			
		}
		
		if(isset($_POST["kontentulisan"])){
			$kontentulisan = $_POST["kontentulisan"];
			$idtulisan = $_POST["idtulisan"];
			$filedir = str_replace(".txt", "", $filedb);
			if(!file_exists($filedir))
				mkdir($filedir);
			$myfile = fopen($filedir . "/" . $idtulisan . ".txt", "w") or die("Error!");
			fwrite($myfile, $kontentulisan);
			fclose($myfile);
		}
		
		if(isset($_POST["hapustulisan"])){
			$id = $_POST["hapustulisan"];
			
			$idtulisan = $_POST["idtulisan"];
			$filedir = str_replace(".txt", "", $filedb);
			$filetulisan = $filedir . "/" . $id . ".txt";
			
			if(file_exists($filetulisan))
				unlink($filetulisan);
		}
		
	}
}

if(isset($_POST["lihattulisan"])){
	$id = $_POST["lihattulisan"];
	$filedir = str_replace(".txt", "", $filedb);
	$data = "";
	if(file_exists($filedir . "/" . $id . ".txt"))
		$data = file_get_contents($filedir . "/" . $id . ".txt");
	echo $data;
}