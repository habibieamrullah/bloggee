<?php include("config.php"); ?>

<!DOCTYPE html>
<html>
	<head>
		<title>Bloggee - Admin Panel</title>
		<link rel="stylesheet" href="admin.css">
		<script src="jquery.min.js"></script>
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
					</ul>
					
					
					
					<?php
		
						$item = array();
						
						$data = "";
						if(file_exists($filedb))
							$data = file_get_contents($filedb);
						if($data != "")
							$item = json_decode($data);
						
					?>
					
					<div id="daftartulisan" class="halaman">
						<h2>Daftar Tulisan</h2>
						<div id="listitem"></div>
					</div>
					
					<div id="tambahdata" class="halaman">
						<h2>Tambah Tulisan</h2>
						<label>Judul Tulisan</label>
						<input id="judul">
						<label>Konten</label>
						<textarea id="konten"></textarea>
						<button onclick="tambahitem()">Tambah Item</button>
					</div>
					
					<div id="editdata" class="halaman">
						<h2>Edit Tulisan</h2>
						<label>Judul Tulisan</label>
						<input id="editjudul">
						<label>Konten</label>
						<textarea id="editkonten"></textarea>
						<button id="tombolsimpan">Simpan</button>
					</div>
					
					
					
					
					
					
					
					
					<script>
						
						var item = <?php echo json_encode($item) ?>;
						
						function listitem(){
							$("#listitem").html("");
							
							var nomorurut = 1;
							
							for(var i = 0; i < item.length; i++){
								$("#listitem").append(nomorurut + ". " + item[i].judul + " (ID# " + item[i].id + ") |<span style='color: green; cursor: pointer;' onclick='edititem(" + i + ")'> edit</span> | <span style='color: red; cursor: pointer;' onclick='hapusitem(" + i + ")'>hapus</span> <br>");
								
								nomorurut++;
							}
						}
						
						listitem();
						
						function tambahitem(){
							var iditem;
							
							if(item.length == 0){
								iditem = 0;
							}else{
								iditem = item[item.length-1].id + 1;
							}
							
							var judul = $("#judul").val();
							var konten = $("#konten").val();
							
							
							item.push({
								"id" : iditem,
								"judul" : judul,
								"konten" : konten,
							});
							
							kirimdata();
						}
						
						function kirimdata(){
							$.post("async.php", {
								"json" : JSON.stringify(item),
								"adminusername" : "<?php echo $username ?>",
								"adminpassword" : "<?php echo $password ?>",
							}, function(data){
								listitem();
								tampilkanhalaman('daftartulisan');
							});
						}
						
						
						function hapusitem(idx){
							item.splice(idx, 1);
							kirimdata();
						}
						
						function tampilkanhalaman(hal){
							$(".halaman").hide();
							$("#" + hal).show();
						}
						
						tampilkanhalaman('daftartulisan');
						
						function edititem(idx){
							tampilkanhalaman('editdata');
							$("#editjudul").val(item[idx].judul);
							$("#editkonten").val(item[idx].konten);
							$("#tombolsimpan").attr("onclick", "simpandatabaru("+idx+")");
						}
						
						function simpandatabaru(idx){
							var judulbaru = $("#editjudul").val();
							var kontenbaru = $("#editkonten").val();
							item[idx].judul = judulbaru;
							item[idx].konten = kontenbaru;
							kirimdata();
						}
						
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