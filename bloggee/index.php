<?php include("config.php"); ?>
<?php

$datasitus = array();
			
$data = "";
if(file_exists($filedb))
	$data = file_get_contents($filedb);
if($data != ""){
	$datasitus = json_decode($data);
	?>
	<!DOCTYPE html>
	<html>
		<head>
			<title><?php echo $datasitus->pengaturan->judul ?></title>
			<script src="lib/jquery.min.js"></script>
		</head>
		<body>
		
			<h1><a href="<?php echo $datasitus->pengaturan->urlsitus ?>"><?php echo $datasitus->pengaturan->judul ?></a></h1>
		
			<?php
			
				
				if(isset($_GET["post"])){
					
					$postid = $_GET["post"];
					
					foreach($datasitus->tulisan as $x) {
						if($x->id == $postid){
							?>
							<h2><?php echo $x->judul ?></h2>
							<?php
							if(isset($x->gambarandalan)){
								if($x->gambarandalan != ""){
									echo "<img src='uploads/" .$x->gambarandalan. "'>";
								}
							}
							?>
							<p><?php echo $x->tanggal ?></p>
							<div><?php echo $x->konten ?></div>
							<?php
						}
					}

				}else{
					foreach($datasitus->tulisan as $x) {
						echo "<div><a href='?post=" . $x->id . "'>" . $x->judul . "</a></div>";
					}
				}
				
			?>
			
			<!-- footer -->
			<div class="footer">
				<?php echo $datasitus->pengaturan->teksfooter ?>
			</div>
		
		</body>
	</html>
	<?php
}else{
	echo "Welcome! Edit your config.php file then login to Admin Panel to start creating :D";
}

?>

