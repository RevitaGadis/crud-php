<?php 
	session_start();
	if (!isset($_SESSION["login"])) {
			echo "<script>
				alert('login dulu');
				document.location.href = 'login.php';
				</script>";
			exit;
		}
	$title = 'Tambah Mahasiswa';
	include 'layout/header.php'; 
	if(isset($_POST['tambah'])) {
		if(create_mahasiswa($_POST) > 0){
			echo"<script>
			alert('Data mahasiswa berhasil ditambahkan');
			document.location.href = 'mahasiswa.php';
			</script>";
		} else {
			echo"<script>
			alert('Data mahasiswa gagal ditambahkan');
			document.location.href = 'mahasiswa.php';
			</script>";
		}
	}
?>
	<div class="container mt-5">
		<h1>Tambah Data Mahasiswa</h1>
		<form action="" method="post" enctype="multipart/form-data">
			<div class="mb-3">
				<label for="nama" class="form-label">Nama Mahasiswa</label>
				<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Mahasiswa..." required>
			</div>
			<div class="mb-3">
				<label for="prodi" class="form-label">Prodi</label>
				<select name="prodi" class="form-control" required>
					<option value="Teknik Informatika">Teknik Informatika</option>
					<option value="Teknik Mesin">Teknik Mesin</option>
					<option value="Teknik Listrik">Teknik Listrik</option>
				</select>
			</div>
			<div class="mb-3">
				<label for="jk" class="form-label">Jenis Kelamin</label>
				<select name="jk" class="form-control" required>
					<option value="Laki-laki">Laki-laki</option>
					<option value="Perempuan">Perempuan</option>
				</select>
			</div>
			<div class="mb-3">
				<label for="telepon" class="form-label">Telepon</label>
				<input type="number" class="form-control" id="telepon" name="telepon" placeholder="Nomor Telepon..." required>
			</div>
			<div class="mb-3">
				<label for="alamat" class="form-label">Alamat</label>
				<textarea class="form-control" id="alamat" name="alamat" placeholder="Alamat..." required></textarea>
			</div>
			<div class="mb-3">
				<label for="email" class="form-label">Email</label>
				<input type="email" class="form-control" id="email" name="email" placeholder="Email..." required>
			</div>
			<div class="mb-3">
				<label for="foto" class="form-label">Foto</label>
				<input type="file" class="form-control" id="foto" name="foto" placeholder="Foto..." onchange="previewImg()">
				<img src="" alt="" class="img-thumbnail img-preview" width="100px">
			</div>
			<button type="submit" class="btn btn-primary" name="tambah">Tambah</button>
		</form>
	</div>

	<script>
		function previewImg() {
			const foto = document.querySelector('#foto');
			const imgPreview =document.querySelector('.img-preview');

			const fileFoto = new FileReader();
			fileFoto.readAsDataURL(foto.files[0]);

			fileFoto.onload = function(e) {
				imgPreview.src = e.target.result;
			}
		}
	</script>

	<?php include 'layout/footer.php'; ?>
    