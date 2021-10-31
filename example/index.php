<?php include("config.php"); ?>

<!DOCTYPE html>
<html>
	<head>
		<title>Bloggee</title>
		<script src="jquery.min.js"></script>
	</head>
	<body>
	
		<?php
		
			$item = array();
			
			$data = "";
			if(file_exists($filedb))
				$data = file_get_contents($filedb);
			if($data != "")
				$item = json_decode($data);
			
			
			
			if(isset($_GET["post"])){
				
				$postid = $_GET["post"];
				
				foreach($item as $x) {
					if($x->id == $postid){
						?>
						<h1><?php echo $x->judul ?></h1>
						<p><?php echo $x->tanggal ?></p>
						<div><?php echo $x->konten ?></div>
						<?php
					}
				}

			}else{
				foreach($item as $x) {
					echo "<div><a href='?post=" . $x->id . "'>" . $x->judul . "</a></div>";
				}
			}
			
		?>
	
	</body>
</html>