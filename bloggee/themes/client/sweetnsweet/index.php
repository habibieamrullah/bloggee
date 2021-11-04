<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $gee_datasitus->pengaturan->judul ?></title>
		<script src="<?php echo $gee_urlsitus ?>/lib/jquery.min.js"></script>
		<link rel="stylesheet" href="<?php echo $gee_urltheme ?>/style.css">
	</head>
	<body>
	
	
		<h1 align="left" style="margin: 50px;"><a href="<?php echo $gee_urlsitus ?>"><?php echo $gee_datasitus->pengaturan->judul ?></a></h1>
	
		<?php
		
			//Single Post
			if(isset($_GET["post"])){
				
				$postperma = $_GET["post"];
				
				foreach($gee_datasitus->tulisan as $x) {
					if($x->perma == $postperma){
						?>
						
						<div class="singlepost" style="display: table; width: 100%;">
							<div style="display: table-cell; vertical-align: top; width: 256px; padding: 20px;">
								<?php
								if(isset($x->gambarandalan)){
									if($x->gambarandalan != ""){
										echo "<img src='" . $gee_urlupload . $x->gambarandalan. "' style='width: 100%;'>";
									}
								}
								?>
							</div>
							<div style="display: table-cell; vertical-align: top; padding: 20px; padding-left: 0px;">
								<h2><?php echo $x->judul ?></h2>
							
								<p><?php echo $x->tanggal ?></p>
								<div class="excerpt"><?php echo $x->sekilas ?></div>
								<div>
									<?php
									$data = file_get_contents(str_replace(".txt", "", $filedb) . "/" . $x->id . ".txt");
									echo $data;
									?>
								</div>

							</div>
						</div>
						
						<?php
					}
				}

			}else{
				//Home

				$firstpost = true;
				$index = count($gee_datasitus->tulisan);
				
				echo "<div id='lebarpenuh'>";
				
				while($index){
					$x = $gee_datasitus->tulisan[--$index];
					
					if($firstpost){
						//blok lebar penuh
						
						?>
						<div style="width: 100%; box-sizing: border-box; background-image: url(<?php echo $gee_urlupload . $x->gambarandalan ?>); background-size: cover; background-repeat: no-repeat; background-position: center center; padding: 50px;">

							<div style="background-color: white; width: 100%; max-width: 400px; padding: 40px; margin: 40px;">
								<p style="color: #c17e41; font-size: 12px; font-weight: bold;"><?php echo $x->tanggal ?></p>
								<h2><a href="?post=<?php echo $x->perma ?>"><?php echo  $x->judul ?></a></h2>
								<div>
									<?php 
										//echo substr($x->sekilas, 0, 40);
										echo $x->sekilas;
									?>
								</div>
							</div>
							
						</div>
						<?php
						
						$firstpost = false;
						echo "</div><div style='text-align: center;'>";
					}else{
						
					
						?>
						<div class="postthumb">
							<a href="?post=<?php echo $x->perma ?>">
								<div style="width: 100%; height: 300px; background-image: url(<?php echo $gee_urlupload . $x->gambarandalan ?>); background-size: cover; background-repeat: no-repeat; background-position: center center;"></div>
							</a>
							
							<div style="padding: 20px;">
								<p style="color: #c17e41; font-size: 12px; font-weight: bold;"><?php echo $x->tanggal ?></p>
								<h2><a href="?post=<?php echo $x->perma ?>"><?php echo  $x->judul ?></a></h2>
								<div>
									<?php 
										//echo substr($x->sekilas, 0, 40);
										echo $x->sekilas;
									?>
								</div>
							</div>
						</div>
						<?php
					}
				}
				
				echo "</div>";
					
			}
			
		?>
		
		<!-- footer -->
		<div class="footer">
			<?php echo $gee_datasitus->pengaturan->teksfooter ?>
		</div>

	</body>
</html>