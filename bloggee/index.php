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
			
			<link rel="stylesheet" href="themes/frontend/style.css">
		</head>
		<body>
		
			<div id="main">
		
				<h1 align="center" style="margin: 50px;"><a href="<?php echo $datasitus->pengaturan->urlsitus ?>"><?php echo $datasitus->pengaturan->judul ?></a></h1>
			
				<?php
				
					
					if(isset($_GET["post"])){
						
						$postid = $_GET["post"];
						
						foreach($datasitus->tulisan as $x) {
							if($x->id == $postid){
								?>
								<div class="singlepost" style="display: table; width: 100%;">
									<div style="display: table-cell; vertical-align: top; width: 256px; padding: 20px;">
										<?php
										if(isset($x->gambarandalan)){
											if($x->gambarandalan != ""){
												echo "<img src='uploads/" .$x->gambarandalan. "' style='width: 100%;'>";
											}
										}
										?>
									</div>
									<div style="display: table-cell; vertical-align: top; padding: 20px; padding-left: 0px;">
										<h2><?php echo $x->judul ?></h2>
									
										<p><?php echo $x->tanggal ?></p>
										<div><?php echo $x->konten ?></div>
									</div>
									
									
								</div>
								<?php
							}
						}

					}else{
						
						?>
						<div style="text-align: center; columns: 3;">
							<?php
							
							foreach($datasitus->tulisan as $x) {
								?>
								<div class="postthumb">
									<img src="uploads/<?php echo $x->gambarandalan ?>" style="width: 100%;">
									<div style="padding: 20px;">
										<p style="color: #c17e41; font-size: 12px; font-weight: bold;"><?php echo $x->tanggal ?></p>
										<h2><a href="?post=<?php echo $x->id ?>"><?php echo  $x->judul ?></a></h2>
										<div><?php echo substr($x->konten, 0, 40) ?> ...</div>
									</div>
								</div>
								<?php
							}
							?>
						</div>
						<?php
					}
					
				?>
				
				<!-- footer -->
				<div class="footer">
					<?php echo $datasitus->pengaturan->teksfooter ?>
				</div>
			</div>
		</body>
	</html>
	<?php
}else{
	echo "Welcome! Edit your config.php file then login to Admin Panel to start creating :D";
}

?>

