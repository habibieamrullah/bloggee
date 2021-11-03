<?php 

include("config.php"); 
include("functions.php"); 

$datasitus = array();
			
$data = "";
if(file_exists($filedb)){
	$data = file_get_contents($filedb);
}
if($data != ""){
	$datasitus = json_decode($data);
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Bloggee - Admin Panel</title>
		
		<link rel="stylesheet" href="lib/fa/css/font-awesome.min.css">
		
		
		<?php 
		$currenttheme = "earlyadmin";
		if(isset($datasitus->pengaturan->themeAdmin))
			$currenttheme = $datasitus->pengaturan->themeAdmin;
		?>
		<link rel="stylesheet" href="themes/admin/<?php echo $currenttheme ?>/style.css">
		
		<script src="lib/jquery.min.js"></script>
		
		<link rel="stylesheet" href="lib/jquery-ui/jquery-ui.min.css">
		<script src="lib/jquery-ui/jquery-ui.js"></script>
		<script src="lib/tinymce/tinymce.min.js"></script>
		
	</head>
	
	<body>
		
		<?php
		
		if(isset($_GET["logout"])){
			
			unset($_SESSION["webadmin"]);
			?>
			
			<div id="adminloginparent">
				<div id="adminlogincell">
					<div id="adminlogin">
						<div style="text-align: left">
						
							<!-- admin logout -->
							<h2>Anda berhasil keluar</h2>
							<p>Tunggu sejenak, Anda akan diarahkan ke halaman Login.</p>
							<script>
								setTimeout(function(){
									
									location.href = location.href.replace(location.search, "");
									
								}, 2000);
							</script>
							
						</div>
					</div>
				</div>
			</div>
			
			
			<?php
			
		}else{
		
			if(isset($_POST["username"]) && isset($_POST["password"]) ){
				
				?>
				<div id="adminloginparent">
					<div id="adminlogincell">
						<div id="adminlogin">
							<div style="text-align: left">
								<?php
								
								if($_POST["username"] == $username && $_POST["password"] == $password){
									//login oke
									$_SESSION["webadmin"] = $username;
									?>
									
									<!-- login success -->
									<h2>Login sukses!</h2>
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
								
								?>
							</div>
						</div>
					</div>
				</div>
				<?php
				
				
			}else{
				if(isset($_SESSION["webadmin"])){
					//berarti admin sudah login
					
		
				
					
					
					?>
					
					<div id="adminpanelparent">
						<div id="adminpanel">
							<div id="menu">
								<h1><i class="fa fa-tachometer" aria-hidden="true"></i> Bloggee</h1>
								
								
								
								<div class="leftmenuitem" onclick="tampilkanhalaman('daftartulisan')" style='cursor: pointer;'><i class="fa fa-list" aria-hidden="true"></i>Daftar Tulisan</div>
								<div class="leftmenuitem" onclick="tampilkanhalaman('tambahdata')" style='cursor: pointer;'><i class="fa fa-plus" aria-hidden="true"></i>Tambah Tulisan</div>
								<div class="leftmenuitem" onclick="tampilkanhalaman('galerigambar')" style='cursor: pointer;'><i class="fa fa-image" aria-hidden="true"></i>Galeri Gambar</div>
								<div class="leftmenuitem" onclick="tampilkanhalaman('theme')" style='cursor: pointer;'><i class="fa fa-paint-brush" aria-hidden="true"></i>Tampilan</div>
								<div class="leftmenuitem" onclick="tampilkanhalaman('pengaturan')" style='cursor: pointer;'><i class="fa fa-cogs" aria-hidden="true"></i>Pengaturan</div>
								
								<div class="leftmenuitem"><a href="?logout"><i class="fa fa-sign-out" aria-hidden="true"></i>Keluar</a></div>
							</div>
							<div id="admincontent">
								<div id="daftartulisan" class="halaman">
									<h2>Daftar Tulisan</h2>
									<div id="listtulisan"></div>
								</div>
								
								<div id="tambahdata" class="halaman">
									<h2>Tambah Tulisan</h2>
									
									<label>Judul Tulisan</label>
									<input id="judul">
									
									<label>Tanggal</label>
									<input id="tanggal" class="datepicker">
									
									<label>Gambar Andalan</label>
									<input id="gambarandalan" class="inputgambarandalan" onclick="tampilkangalerimedia()" readonly>
									
									<label>Sekilas</label>
									<textarea id="sekilas"></textarea>
									
									<label>Konten</label>
									<textarea id="editkonten" class="texteditor"></textarea>
									<br>
									
									<button class='submitbutton' onclick="tambahtulisan()">Tambah Item</button>
								</div>
								
								<div id="editdata" class="halaman">
									<h2>Edit Tulisan</h2>
									
									<label>Judul Tulisan</label>
									<input id="editjudul">
									
									<label>Tanggal</label>
									<input id="edittanggal" class="datepickeredit">
									
									<label>Gambar Andalan</label>
									<input id="editgambarandalan" class="inputgambarandalan" onclick="tampilkangalerimedia()" readonly>
									
									<label>Sekilas</label>
									<textarea id="editsekilas"></textarea>
									
									<label>Konten</label>
									<textarea id="editkonten" class="texteditor"></textarea>
									
									<br>
									
									<button class='submitbutton' id="tombolsimpan">Simpan</button>
								</div>
								
								<div id="theme" class="halaman">
									<h2>Tampilan dan Tema</h2>
									<h3>Tema Situs</h3>
									<?php
									$folder = "themes/client/";
									echo "<div style='margin-bottom: 20px;'>";
									foreach(scandir($folder) as $theme){
										if($theme != "." && $theme != ".."){
											
											if($datasitus->pengaturan->themeClient == $theme)
												$border = " border: 2px solid green;";
											else
												$border = "";
											?>
											<div style="display: inline-block; width: 214px; height: 128px; background-image: url(<?php echo $folder . $theme . "/screenshot.jpg" ?>); background-size: cover; background-repeat: no-repeat; background-position: center center;<?php echo $border ?>" onclick="settheme('<?php echo $theme ?>')"></div>
											<?php
										}	
										
									}
									echo "</div>";
									?>
									
									<h3>Tema Admin</h3>
									<?php
									$folder = "themes/admin/";
									echo "<div>";
									foreach(scandir($folder) as $theme){
										if($theme != "." && $theme != ".."){
											if($datasitus->pengaturan->themeAdmin == $theme)
												$border = " border: 2px solid green;";
											else
												$border = "";
											?>
											<div style="display: inline-block; width: 214px; height: 128px; background-image: url(<?php echo $folder . $theme . "/screenshot.jpg" ?>); background-size: cover; background-repeat: no-repeat; background-position: center center;<?php echo $border ?>" onclick="setadmintheme('<?php echo $theme ?>')"></div>
											<?php
										}	
										
									}
									echo "</div>";
									?>
								</div>
								
								<div id="pengaturan" class="halaman">
									<h2>Pengaturan Website</h2>
									
									<label>Judul Website</label>
									<input id="judulwebsite">
									
									<label>URL Situs</label>
									<input id="urlsitus">
									
									<label>Teks Footer</label>
									<input id="teksfooter">
									
									<button class='submitbutton' onclick="simpanpengaturan()">Simpan</button>
								</div>
								
								
								<div id="galerigambar" class="halaman">
									<h2>Galeri Gambar</h2>
									
									<?php
									
									if(isset($_FILES["filegambar"])){
										
										$namagambarbaru = uniqid();
										$gambarbaru = gee_uploadAndResize($namagambarbaru, "filegambar", "uploads/", 256);
										
										echo "Gambar berhasil diupload: " . $gambarbaru;
										

										
										?>
										
										<script>
											$("document").ready(function(){
												tampilkanhalaman("galerigambar");
											});
										</script>
										<?php
									}
									
									$folder = "uploads";
									echo "<div id='daftargambar'>";
									foreach(scandir($folder) as $gambar){
										if($gambar != "." && $gambar != ".."){
											?>
											<div style="display: inline-block; width: 128px; height: 128px; background-image: url(uploads/<?php echo $gambar ?>); background-size: cover; background-repeat: no-repeat; background-position: center center;" onclick="pilihgambarini('<?php echo $gambar ?>')"></div>
											<?php
										}	
										
									}
									echo "</div>";
									?>
									
									<form method="post" enctype="multipart/form-data">
										<input type="file" name="filegambar" accept="image/*">
										<input class='submitbutton' type="submit" value="Unggah">
									</form>
								</div>
							</div>
						</div>
					</div>
					
					
					<script>
						
						var datasitus = <?php echo json_encode($datasitus) ?>;
						var defaultbaseurl = "<?php echo gee_getbaseurl() ?>";
						
						var adminusername = "<?php echo $username ?>";
						var adminpassword = "<?php echo $password ?>";
						
						//If the site is empty, set the initial data structure
						if(datasitus.length == 0){
							datasitus = {
								tulisan : [],
								pengaturan : {
									judul : "Another Bloggee Blog",
									urlsitus : defaultbaseurl,
									themeAdmin : "earlyadmin",
									themeClient : "earlyclient",
									teksfooter : "Powered by <a href='https://habibieamrullah.github.io/bloggee/'>Bloggee</a>",
								},
							}
						}
						
						$("#judulwebsite").val(datasitus.pengaturan.judul);
						$("#urlsitus").val(datasitus.pengaturan.urlsitus);
						$("#teksfooter").val(datasitus.pengaturan.teksfooter);
						
						
						
						function listtulisan(){
							
							$("#listtulisan").html("");
							
							var nomorurut = 1;
							if(datasitus.tulisan != undefined){
								if(datasitus.tulisan.length > 0){
									var datalisttulisan = "<table class='admintable'><tr><th>No</th><th>Judul</th><th>Lihat</th><th>Edit</th><th>Hapus</th>";
									for(var i = datasitus.tulisan.length-1; i >= 0; i--){
										
										datalisttulisan += "<tr><td>" + nomorurut + "</td><td>" + datasitus.tulisan[i].judul + "</td><td><span><a href='" + datasitus.pengaturan.urlsitus + "?post=" + datasitus.tulisan[i].id + "' target='_blank'>Lihat</a></span></td><td><span style='color: green; cursor: pointer;' onclick='edititem(" + i + ")'> edit</span></td><td><span style='color: red; cursor: pointer;' onclick='hapusitem(" + i + ")'>hapus</span></td></tr>";
										
										nomorurut++;
									}
								}
								
								$("#listtulisan").html(datalisttulisan);
							}
						}
						
						listtulisan();
						
						function tambahtulisan(){
							var idartikel;
							
							if(datasitus.tulisan.length == 0){
								idartikel = 0;
							}else{
								idartikel = datasitus.tulisan[datasitus.tulisan.length-1].id + 1;
							}
							
							var judul = $("#judul").val();
							var tanggal = $("#tanggal").val();
							var gambarandalan = $("#gambarandalan").val();
							var sekilas = $("#sekilas").val();
							var konten = tinymce.activeEditor.getContent();
							
							
							datasitus.tulisan.push({
								"id" : idartikel,
								"judul" : judul,
								"tanggal" : tanggal,
								"gambarandalan" : gambarandalan,
								"sekilas" : sekilas,
							});
							
							kirimdata(konten, idartikel);
						}
						
						function kirimdata(kontentulisan, idtulisan){
							$.post("async.php", {
								"json" : JSON.stringify(datasitus),
								"adminusername" : adminusername,
								"adminpassword" : adminpassword,
							}, function(data){
								
								if(typeof kontentulisan != "undefined"){
									$.post("async.php", {
										"adminusername" : adminusername,
										"adminpassword" : adminpassword,
										"kontentulisan" : kontentulisan,
										"idtulisan" : idtulisan,
									}, function(data){
										console.log("Posted!");
									});
								}
								
								listtulisan();
								tampilkanhalaman("daftartulisan");
								$("#judul").val("");
								$("#gambarandalan").val("");
								$("#tanggal").val("");
								$("#sekilas").val("");
								tinymce.activeEditor.setContent("");								
							});
						}
						
						
						function hapusitem(idx){
							$.post("async.php", {
								"hapustulisan" : datasitus.tulisan[idx].id,
								"adminusername" : adminusername,
								"adminpassword" : adminpassword,
							}, function(data){
								datasitus.tulisan.splice(idx, 1);
								kirimdata();
							});
						}
						
						function tampilkanhalaman(hal){
							$(".halaman").hide();
							$("#" + hal).show();
							if(hal == "tambahdata"){
								$("#tanggal").datepicker().datepicker('setDate', 'today');
							}
						}
						
						tampilkanhalaman('daftartulisan');
						
						function edititem(idx){
							tampilkanhalaman('editdata');
							$("#editjudul").val(datasitus.tulisan[idx].judul);
							$("#edittanggal").val(datasitus.tulisan[idx].tanggal);
							$("#editgambarandalan").val(datasitus.tulisan[idx].gambarandalan);
							$("#editsekilas").val(datasitus.tulisan[idx].sekilas);
							$("#editkonten").val("");
							$("#tombolsimpan").attr("onclick", "simpandatabaru("+idx+")");
							$.post("async.php", {
								"lihattulisan" : datasitus.tulisan[idx].id,
							}, function(data){
								tinymce.activeEditor.setContent(data);
							});
						}
						
						function simpandatabaru(idx){
							var judulbaru = $("#editjudul").val();
							var tanggalbaru = $("#edittanggal").val();
							var sekilasbaru = $("#editsekilas").val();
							var kontenbaru = tinymce.activeEditor.getContent();
							var gambarandalanbaru = $("#editgambarandalan").val();
							datasitus.tulisan[idx].judul = judulbaru;
							datasitus.tulisan[idx].tanggal = tanggalbaru;
							datasitus.tulisan[idx].sekilas = sekilasbaru;
							datasitus.tulisan[idx].gambarandalan = gambarandalanbaru;
							
							kirimdata(kontenbaru, datasitus.tulisan[idx].id);
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
							$("body").append("<div id='popupgalerimedia'>" + $("#daftargambar").html() + "<button class='submitbutton' onclick='tutuppopupgm()'>Tutup</button></div>");
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
						});
						
						function settheme(theme){
							datasitus.pengaturan.themeClient = theme;
							kirimdata();
							setTimeout(function(){
								location.reload();
							}, 1000);
						}
						
						function setadmintheme(theme){
							datasitus.pengaturan.themeAdmin = theme;
							kirimdata();
							setTimeout(function(){
								location.reload();
							}, 1000);
						}
						
					</script>
					
					
					
					
					
					<?php
					
				}else{
					
					//tampilkan form login
					
					?>
					
					<div id="adminloginparent">
						<div id="adminlogincell">
							<div id="adminlogin">
								<div style="text-align: left">
								
									<!-- admin login -->
									<h2>Admin Login</h2>
									<form method="post">
										<label>Username</label>
										<input name="username" type="text" autofocus>
										<label>Password</label>
										<input name="password" type="password">
										
										<div style="text-align: center;">
											<input class="submitbutton" type="submit" value="Login">
										</div>
									</form>
									
								</div>
							</div>
						</div>
					</div>
					<?php
				}
			}
		}
		
		?>
		
		<script>
		tinymce.init({ 
			selector : '.texteditor' , 
			plugins : 'directionality, code, link', 
			toolbar: "ltr rtl | alignleft aligncenter alignright alignjustify | outdent indent | sizeselect | bold italic | fontselect | fontsizeselect", 
			relative_urls: false, 
			remove_script_host : true, 
			statusbar: false,
			convert_newlines_to_brs : true
		});

		</script>
	</body>
</html>