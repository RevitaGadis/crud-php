<?php
	session_start();
	if (!isset($_SESSION["login"])) {
			echo "<script>
				alert('login dulu');
				document.location.href = 'login.php';
				</script>";
			exit;
		}
    $title = 'Daftar Mahasiswa';
    include 'layout/header.php'; 
    $data_mahasiswa = select("SELECT * FROM mahasiswa ORDER BY id_mahasiswa DESC"); 
?>
<div class="container mt-5">
		<h1>Data Mahasiswa</h1>
		<hr>
		<button class="btn btn-primary mb-1"><a href="tambah-mahasiswa.php" class="text-white text-decoration-none">Tambah Data</a></button>
		<button class="btn btn-success mb-1"><i class="fas fa-file-excel"></i><a href="download-excel-mahasiswa.php" class="text-white text-decoration-none">Download Excel</a></button>
		<button class="btn btn-danger mb-1"><i class="fas fa-file-pdf"></i><a href="download-pdf-mahasiswa.php" class="text-white text-decoration-none">Download PDF</a></button>
		<table class="table table-bordered table-striped" id="tabel">
			<thead>
				<tr>
					<th>No</th>
					<th>Nama</th>
					<th>Prodi</th>
					<th>Jenis Kelamin</th>
					<th>Telepon</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
			<?php 
				$no = 1; ?>
			<?php foreach($data_mahasiswa as $mahasiswa) : ?>
				<tr>
					<td><?= $no++; ?></td>
					<td><?= $mahasiswa['nama']; ?></td>
					<td><?= $mahasiswa['prodi']; ?></td>
					<td><?=$mahasiswa['jk']; ?></td>
					<td><?= $mahasiswa['telepon']; ?></td>
					<td width="20%" class="text-center">
                        <a href="detail-mahasiswa.php?id_mahasiswa=<?= $mahasiswa['id_mahasiswa'];?>" class="btn btn-primary">Detail</a>
						<a href="ubah-mahasiswa.php?id_mahasiswa=<?= $mahasiswa['id_mahasiswa'];?>" class="btn btn-success">Ubah</a>
						<a href="hapus-mahasiswa.php?id_mahasiswa=<?= $mahasiswa['id_mahasiswa']; ?>" class="btn btn-danger" onclick="return confirm('Yakin data mahasiswa4 akan dihapus')">Hapus</a>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php include 'layout/footer.php'; ?>