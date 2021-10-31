<?php 

include("config.php"); 
include("functions.php"); 

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Bloggee - Admin Panel</title>
		<link rel="stylesheet" href="admin.css">
		<script src="jquery.min.js"></script>
		
		<link rel="stylesheet" href="jquery-ui/jquery-ui.min.css">
		<script src="jquery-ui/jquery-ui.js"></script>
	</head>
	
	<body>
		
		<?php
		
		if(isset($_GET["logout"])){
			
			unset($_SESSION["webadmin"]);
			echo "<h2>Anda berhasil keluar</h2>";
			?>
			<p>Tunggu sejenak, Anda akan diarahkan ke halaman Login.</p>
			<script>
				setTimeout(function(){
					
					location.href = location.href.replace(location.search, "");
					
				}, 2000);
			</script>
			<?php
			
		}else{
		
			if(isset($_POST["username"]) && isset($_POST["password"]) ){
				
				if($_POST["username"] == $username && $_POST["password"] == $password){
					//login oke
					$_SESSION["webadmin"] = $username;
					echo "<h2>Login sukses!</h2>";
					?>
					<p>Tunggu sejenak, Anda akan diarahkan ke halaman Admin Panel.</p>
					<script>
						setTimeout(function(){
							
							location.href = location.href;
							
						}, 2000);
					</script>
					<?php
					
				}else{
					//login gagal
					echo "<h2>Login gagal!</h2><p>Silahkan coba lagi.</p>";
				}
				
				
			}else{
				if(isset($_SESSION["webadmin"])){
					//berarti admin sudah login
					?>
					<h1>Admin Panel</h1>
					<p>Klik <a href="?logout">di sini</a> untuk logout.</p>
					
					<ul>
						<li onclick="tampilkanhalaman('daftartulisan')" style='cursor: pointer;'>Daftar Tulisan</li>
						<li onclick="tampilkanhalaman('tambahdata')" style='cursor: pointer;'>Tambah Tulisan</li>
						<li onclick="tampilkanhalaman('galerigambar')" style='cursor: pointer;'>Galeri Gambar</li>
						<li onclick="tampilkanhalaman('pengaturan')" style='cursor: pointer;'>Pengaturan Website</li>
					</ul>
					
					
					
					<?php
		
						$datasitus = array();
						
						$data = "";
						if(file_exists($filedb))
							$data = file_get_contents($filedb);
						if($data != "")
							$datasitus = json_decode($data);
						
					?>
					
					<div id="daftartulisan" class="halaman">
						<h2>Daftar Tulisan</h2>
						<div id="listposts"></div>
					</div>
					
					<div id="tambahdata" class="halaman">
						<h2>Tambah Tulisan</h2>
						
						<label>Judul Tulisan</label>
						<input id="judul">
						
						<label>Tanggal</label>
						<input id="tanggal" class="datepicker">
						
						<label>Gambar Andalan</label>
						<input id="gambarandalan" class="inputgambarandalan" onclick="tampilkangalerimedia()" readonly>
						
						<label>Konten</label>
						<textarea id="konten"></textarea>
						
						<button onclick="tambahitem()">Tambah Item</button>
					</div>
					
					<div id="editdata" class="halaman">
						<h2>Edit Tulisan</h2>
						
						<label>Judul Tulisan</label>
						<input id="editjudul">
						
						<label>Tanggal</label>
						<input id="edittanggal" class="datepickeredit">
						
						<label>Gambar Andalan</label>
						<input id="editgambarandalan" class="inputgambarandalan" onclick="tampilkangalerimedia()" readonly>
						
						<label>Konten</label>
						<textarea id="editkonten"></textarea>
						
						<button id="tombolsimpan">Simpan</button>
					</div>
					
					
					<div id="pengaturan" class="halaman">
						<h2>Pengaturan Website</h2>
						
						<label>Judul Website</label>
						<input id="judulwebsite">
						
						<label>URL Situs</label>
						<input id="urlsitus">
						
						<label>Teks Footer</label>
						<input id="teksfooter">
						
						<button onclick="simpanpengaturan()">Simpan</button>
					</div>
					
					<div id="galerigambar" class="halaman">
						<h2>Galeri Gambar</h2>
						
						<?php
						
						if(isset($_FILES["filegambar"])){
							
							$namagambarbaru = uniqid();
							$gambarbaru = uploadAndResize($namagambarbaru, "filegambar", "uploads/", 256);
							
							echo "Gambar berhasil diupload: " . $gambarbaru;
							

							
							?>
							
							<script>
								setTimeout(function(){
									tampilkanhalaman("galerigambar");
								},1000);
								
							</script>
							<?php
						}
						
						$folder = "uploads";
						echo "<div id='daftargambar'>";
						foreach(scandir($folder) as $gambar){
							if($gambar != "." && $gambar != ".."){
								?>
								<img src="<?php echo $folder . "/" . $gambar ?>" style="width: 128px;" onclick="pilihgambarini('<?php echo $gambar ?>')">
								<?php
							}	
							
						}
						echo "</div>";
						?>
						
						<form method="post" enctype="multipart/form-data">
							<input type="file" name="filegambar" accept="image/*">
							<input type="submit" value="Unggah">
						</form>
					</div>
					
					
					
					
					
					
					
					<script>
						
						var datasitus = <?php echo json_encode($datasitus) ?>;
						
						//If the site is empty, set the initial data structure
						if(datasitus.length == 0){
							datasitus = {
								posts : [],
								pengaturan : {
									teksfooter : "Powered by <a href='https://habibieamrullah.github.io/bloggee/'>Bloggee</a>",
								},
							}
						}else{
							$("#judulwebsite").val(datasitus.pengaturan.judul);
							$("#urlsitus").val(datasitus.pengaturan.urlsitus);
							$("#teksfooter").val(datasitus.pengaturan.teksfooter);
						}
						
						
						
						function listposts(){
							$("#listposts").html("");
							
							var nomorurut = 1;
							if(datasitus.posts != undefined){
								if(datasitus.posts.length > 0){
									for(var i = 0; i < datasitus.posts.length; i++){
										$("#listposts").append(nomorurut + ". " + datasitus.posts[i].judul + " (ID# " + datasitus.posts[i].id + ") |<span style='color: green; cursor: pointer;' onclick='edititem(" + i + ")'> edit</span> | <span style='color: red; cursor: pointer;' onclick='hapusitem(" + i + ")'>hapus</span> <br>");
										
										nomorurut++;
									}
								}
							}
						}
						
						listposts();
						
						function tambahitem(){
							var iditem;
							
							if(datasitus.posts.length == 0){
								iditem = 0;
							}else{
								iditem = datasitus.posts[datasitus.posts.length-1].id + 1;
							}
							
							var judul = $("#judul").val();
							var tanggal = $("#tanggal").val();
							var gambarandalan = $("#gambarandalan").val();
							var konten = $("#konten").val();
							
							
							datasitus.posts.push({
								"id" : iditem,
								"judul" : judul,
								"tanggal" : tanggal,
								"gambarandalan" : gambarandalan,
								"konten" : konten,
							});
							
							kirimdata();
						}
						
						function kirimdata(){
							$.post("async.php", {
								"json" : JSON.stringify(datasitus),
								"adminusername" : "<?php echo $username ?>",
								"adminpassword" : "<?php echo $password ?>",
							}, function(data){
								listposts();
								tampilkanhalaman('daftartulisan');
								$("#judul").val("");
								$("#tanggal").val("");
								$("#konten").val("");
							});
						}
						
						
						function hapusitem(idx){
							datasitus.posts.splice(idx, 1);
							kirimdata();
						}
						
						function tampilkanhalaman(hal){
							$(".halaman").hide();
							$("#" + hal).show();
						}
						
						tampilkanhalaman('daftartulisan');
						
						function edititem(idx){
							tampilkanhalaman('editdata');
							$("#editjudul").val(datasitus.posts[idx].judul);
							$("#edittanggal").val(datasitus.posts[idx].tanggal);
							$("#editgambarandalan").val(datasitus.posts[idx].gambarandalan);
							$("#editkonten").val(datasitus.posts[idx].konten);
							$("#tombolsimpan").attr("onclick", "simpandatabaru("+idx+")");
						}
						
						function simpandatabaru(idx){
							var judulbaru = $("#editjudul").val();
							var tanggalbaru = $("#edittanggal").val();
							var kontenbaru = $("#editkonten").val();
							var gambarandalanbaru = $("#editgambarandalan").val();
							datasitus.posts[idx].judul = judulbaru;
							datasitus.posts[idx].tanggal = tanggalbaru;
							datasitus.posts[idx].konten = kontenbaru;
							datasitus.posts[idx].gambarandalan = gambarandalanbaru;
							kirimdata();
						}
						
						function simpanpengaturan(){
							var judulwebsite = $("#judulwebsite").val();
							var urlsitus = $("#urlsitus").val();
							var teksfooter = $("#teksfooter").val();
							
							datasitus.pengaturan.judul = judulwebsite;
							datasitus.pengaturan.urlsitus = urlsitus;
							datasitus.pengaturan.teksfooter = teksfooter;
							
							kirimdata();

						}
						
						function tampilkangalerimedia(){
							$("body").append("<div id='popupgalerimedia'>" + $("#daftargambar").html() + "<button onclick='tutuppopupgm()'>Tutup</button></div>");
						}
						
						function tutuppopupgm(){
							$("#popupgalerimedia").remove();
						}
						
						function pilihgambarini(gambar){
							$(".inputgambarandalan").val(gambar);
							tutuppopupgm();
						}
						
						
						$(function() {
							$(".datepickeredit").datepicker();
							$(".datepicker").datepicker().datepicker('setDate', 'today');
						});
						
					</script>
					
					
					
					<?php
					
				}else{
					
					//tampilkan form login
					
					?>
					
					<h2>Admin Login</h2>
					<form method="post">
						<label>Username</label>
						<input name="username" type="text">
						<label>Password</label>
						<input name="password" type="password">
						
						<input type="submit" value="Login">
					</form>
					
					<?php
				}
			}
		}
		
		?>
		
	</body>
</html>