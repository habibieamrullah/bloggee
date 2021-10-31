<?php include("config.php"); ?>

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
						<li onclick="tampilkanhalaman('pengaturan')" style='cursor: pointer;'>Pengaturan Website</li>
					</ul>
					
					
					
					<?php
		
						$sitedata = array();
						
						$data = "";
						if(file_exists($filedb))
							$data = file_get_contents($filedb);
						if($data != "")
							$sitedata = json_decode($data);
						
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
						
						<button onclick="simpanpengaturan()">Simpan</button>
					</div>
					
					
					
					
					
					
					
					<script>
						
						var sitedata = <?php echo json_encode($sitedata) ?>;
						
						//If the site is empty, set the initial data structure
						if(sitedata.length == 0){
							sitedata = {
								posts : [],
								settings : {},
							}
						}else{
							$("#judulwebsite").val(sitedata.settings.judul);
							$("#urlsitus").val(sitedata.settings.urlsitus);
						}
						
						
						
						function listposts(){
							$("#listposts").html("");
							
							var nomorurut = 1;
							if(sitedata.posts != undefined){
								if(sitedata.posts.length > 0){
									for(var i = 0; i < sitedata.posts.length; i++){
										$("#listposts").append(nomorurut + ". " + sitedata.posts[i].judul + " (ID# " + sitedata.posts[i].id + ") |<span style='color: green; cursor: pointer;' onclick='edititem(" + i + ")'> edit</span> | <span style='color: red; cursor: pointer;' onclick='hapusitem(" + i + ")'>hapus</span> <br>");
										
										nomorurut++;
									}
								}
							}
						}
						
						listposts();
						
						function tambahitem(){
							var iditem;
							
							if(sitedata.posts.length == 0){
								iditem = 0;
							}else{
								iditem = sitedata.posts[sitedata.posts.length-1].id + 1;
							}
							
							var judul = $("#judul").val();
							var tanggal = $("#tanggal").val();
							var konten = $("#konten").val();
							
							
							sitedata.posts.push({
								"id" : iditem,
								"judul" : judul,
								"tanggal" : tanggal,
								"konten" : konten,
							});
							
							kirimdata();
						}
						
						function kirimdata(){
							$.post("async.php", {
								"json" : JSON.stringify(sitedata),
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
							sitedata.posts.splice(idx, 1);
							kirimdata();
						}
						
						function tampilkanhalaman(hal){
							$(".halaman").hide();
							$("#" + hal).show();
						}
						
						tampilkanhalaman('daftartulisan');
						
						function edititem(idx){
							tampilkanhalaman('editdata');
							$("#editjudul").val(sitedata.posts[idx].judul);
							$("#edittanggal").val(sitedata.posts[idx].tanggal);
							$("#editkonten").val(sitedata.posts[idx].konten);
							$("#tombolsimpan").attr("onclick", "simpandatabaru("+idx+")");
						}
						
						function simpandatabaru(idx){
							var judulbaru = $("#editjudul").val();
							var tanggalbaru = $("#edittanggal").val();
							var kontenbaru = $("#editkonten").val();
							sitedata.posts[idx].judul = judulbaru;
							sitedata.posts[idx].tanggal = tanggalbaru;
							sitedata.posts[idx].konten = kontenbaru;
							kirimdata();
						}
						
						function simpanpengaturan(){
							var judulwebsite = $("#judulwebsite").val();
							var urlsitus = $("#urlsitus").val();
							
							sitedata.settings.judul = judulwebsite;
							sitedata.settings.urlsitus = urlsitus;
							
							kirimdata();

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