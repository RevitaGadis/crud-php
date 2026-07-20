<?php 
	session_start();
	if (!isset($_SESSION["login"])) {
			echo "<script>
				alert('login dulu');
				document.location.href = 'login.php';
				</script>";
			exit;
		}
	$title = 'Ubah Mahasiswa';
    include 'layout/header.php'; 
    $id_mahasiswa = (int)$_GET['id_mahasiswa'];
    $mahasiswa = select("SELECT * FROM mahasiswa WHERE id_mahasiswa = $id_mahasiswa")[0];
	if(isset($_POST['ubah'])) {
		if(update_mahasiswa($_POST) > 0){
			echo"<script>
			alert('Data mahasiswa berhasil diubah');
			document.location.href = 'mahasiswa.php';
			</script>";
		} else {
			echo"<script>
			alert('Data mahasiswa gagal diubah');
			document.location.href = 'mahasiswa.php';
			</script>";
		}
	}
?>
	<div class="container mt-5">
		<h1>Ubah Mahasiswa</h1>
		<form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_mahasiswa" value="<?= $mahasiswa['id_mahasiswa'];?>">
			<input type="hidden" name="fotoLama" value="<?= $mahasiswa['foto'];?>">
			<div class="mb-3">
				<label for="nama" class="form-label">Nama Mahasiswa</label>
				<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Mahasiswa..."  value="<?= $mahasiswa['nama'];?>" required>
			</div>
			<div class="mb-3">
				<label for="prodi" class="form-label">Prodi</label>
				<select name="prodi" class="form-control" required>
					<?php $prodi = $mahasiswa['prodi'];?>
					<option value="Teknik Informatika" <?= $prodi == 'Teknik Informatika' ? 'selected' : null?>>Teknik Informatika</option>
					<option value="Teknik Mesin" <?= $prodi == 'Teknik Mesin' ? 'selected' : null?>>Teknik Mesin</option>
					<option value="Teknik Listrik" <?= $prodi == 'Teknik Listrik' ? 'selected' : null?>>Teknik Listrik</option>
				</select>
			</div>
			<div class="mb-3">
				<label for="jk" class="form-label">Jenis Kelamin</label>
				<select name="jk" class="form-control" required>
					<?php $jk = $mahasiswa['jk'];?>
					<option value="Laki-laki" <?= $jk == 'Laki-laki' ? 'selected' : null?>>Laki-laki</option>
					<option value="Perempuan" <?= $jk == 'Perempuan' ? 'selected' : null?>>Perempuan</option>
				</select>
			</div>
			<div class="mb-3">
				<label for="telepon" class="form-label">Telepon</label>
				<input type="number" class="form-control" id="telepon" name="telepon" placeholder="Nomor Telepon..." value="<?= $mahasiswa['telepon'];?>" required>
			</div>
			<div class="mb-3">
				<label for="email" class="form-label">Email</label>
				<input type="email" class="form-control" id="email" name="email" placeholder="Email..." value="<?= $mahasiswa['email'];?>" required>
			</div>
			<div class="mb-3">
				<label for="foto" class="form-label">Foto</label>
				<input type="file" class="form-control" id="foto" name="foto" placeholder="Foto..." onchange="previewImg()">
				<p>
					<small>Gambar Sebelumnya</small>
					<img src="assets/img/<?= $mahasiswa['foto'];?>" alt="foto" width="100px">
				</p>
			</div>
			<button type="submit" class="btn btn-primary" name="ubah">Ubah</button>
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
    