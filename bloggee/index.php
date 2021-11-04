<?php include("config.php"); ?>
<?php include("functions.php"); ?>
<?php
	$gee_datasitus = array();
	$gee_jsonsitus = "";
	if(file_exists($filedb))
		$gee_jsonsitus = file_get_contents($filedb);
	if($gee_jsonsitus != ""){
		$gee_datasitus = json_decode($gee_jsonsitus);
		$gee_theme = "earlyclient";
		if(isset($gee_datasitus->pengaturan->themeClient))
			$gee_theme = $gee_datasitus->pengaturan->themeClient;
		$gee_urlsitus = $gee_datasitus->pengaturan->urlsitus;
		$gee_urltheme = $gee_urlsitus . "/themes/client/" . $gee_theme;
		$gee_urlupload = $gee_urlsitus . "/uploads/";
		include("themes/client/" . $gee_theme . "/index.php");
	}else{
		echo "Welcome! Edit your config.php file then login to Admin Panel to start creating :D";
	}
?>