<?php include("config.php"); ?>
<?php

$sitedata = array();
			
$data = "";
if(file_exists($filedb))
	$data = file_get_contents($filedb);
if($data != "")
	$sitedata = json_decode($data);

?>

<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $sitedata->settings->judul ?></title>
		<script src="jquery.min.js"></script>
	</head>
	<body>
	
		<h1><a href="<?php echo $sitedata->settings->urlsitus ?>"><?php echo $sitedata->settings->judul ?></a></h1>
	
		<?php
		
			
			if(isset($_GET["post"])){
				
				$postid = $_GET["post"];
				
				foreach($sitedata->posts as $x) {
					if($x->id == $postid){
						?>
						<h2><?php echo $x->judul ?></h2>
						<p><?php echo $x->tanggal ?></p>
						<div><?php echo $x->konten ?></div>
						<?php
					}
				}

			}else{
				foreach($sitedata->posts as $x) {
					echo "<div><a href='?post=" . $x->id . "'>" . $x->judul . "</a></div>";
				}
			}
			
		?>
	
	</body>
</html>