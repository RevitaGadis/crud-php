<body>
	<?php 
    session_start();
    if (!isset($_SESSION["login"])) {
			echo "<script>
				alert('login dulu');
				document.location.href = 'login.php';
				</script>";
			exit;
		}
		$title = 'Daftar Akun';
		include 'layout/header.php';
        $data_akun = select("SELECT * FROM akun ORDER BY id_akun ASC");
        $id_akun = $_SESSION['id_akun'];
        $data_bylogin = select("SELECT * FROM akun WHERE id_akun = $id_akun");
        if(isset($_POST['tambah'])) {
            if(create_akun($_POST) > 0){
                echo"<script>
                alert('Data akun berhasil ditambahkan');
                document.location.href = 'crud-modal.php';
                </script>";
            } else {
                echo"<script>
                alert('Data akun gagal ditambahkan');
                document.location.href = 'crud-modal.php';
                </script>";
            }
	    }
        if(isset($_POST['ubah'])) {
            if(update_akun($_POST) > 0){
                echo"<script>
                alert('Data akun berhasil diubah');
                document.location.href = 'crud-modal.php';
                </script>";
            } else {
                echo"<script>
                alert('Data akun gagal diubah');
                document.location.href = 'crud-modal.php';
                </script>";
            }
	    }
	?>
	<div class="container mt-5">
		<h1>Data Akun</h1>
		<hr>
        <?php if ($_SESSION['level'] == 1) : ?>
		    <button class="btn btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah</button>
        <?php endif; ?>
		<table class="table table-bordered table-striped" id="tabel">
			<thead>
				<tr>
					<th>No</th>
					<th>Nama</th>
					<th>Username</th>
					<th>Email</th>
					<th>Password</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
			<?php $no = 1; ?>
            <?php if ($_SESSION['level'] == 1) : ?>
                <?php foreach ($data_akun as $akun) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $akun['nama']; ?></td>
                        <td><?= $akun['username']; ?></td>
                        <td><?= $akun['email']; ?></td>
                        <td>Password Ter-enkripsi</td>
                        <td width="15%" class="text-center">
                            <button class="btn btn-success"
                                data-bs-toggle="modal"
                                data-bs-target="#modalUbah<?= $akun['id_akun']; ?>">
                                Ubah
                            </button>

                            <button class="btn btn-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#modalHapus<?= $akun['id_akun']; ?>">
                                Hapus
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <?php foreach ($data_bylogin as $akun) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $akun['nama']; ?></td>
                        <td><?= $akun['username']; ?></td>
                        <td><?= $akun['email']; ?></td>
                        <td>Password Ter-enkripsi</td>
                        <td width="15%" class="text-center">
                            <button class="btn btn-success"
                                data-bs-toggle="modal"
                                data-bs-target="#modalUbah<?= $akun['id_akun']; ?>">
                                Ubah
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
			</tbody>
		</table>
	</div>

<!-- Modal -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="exampleModalLabel">Tambah Akun</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" method="post">
        <div class="modal-body">
			<div class="mb-3">
				<label for="nama" class="form-label">Nama</label>
				<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Akun..." required>
			</div>
			<div class="mb-3">
				<label for="username" class="form-label">Username</label>
				<input type="text" class="form-control" id="username" name="username" placeholder="Username..." required>
			</div>
			<div class="mb-3">
				<label for="email" class="form-label">Email</label>
				<input type="email" class="form-control" id="email" name="email" placeholder="Email..." required>
			</div>
            <div class="mb-3">
				<label for="password" class="form-label">Password</label>
				<input type="password" class="form-control" id="password" name="password" placeholder="Password..." required>
			</div>
            <?php if ($_SESSION['level'] == 1) : ?>
            <div class="mb-3">
                <label for="level">Level</label>
                <select name="level" id="level" class="form-control" required>
                    <option value="1">Admin</option>
                    <option value="2">Operator Barang</option>
                    <option value="3">Operator Mahasiswa</option>
                </select>
            </div>
        <?php else : ?>
            <input type="hidden" name="level" value="<?= $_SESSION['level']; ?>">
        <?php endif; ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
            <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
        </div>
        </form>
        </div>
    </div>
    </div>

    <?php if ($_SESSION['level'] == 1) : ?>
    <?php foreach ($data_akun as $akun) : ?>
        <div class="modal fade" id="modalHapus<?=$akun['id_akun']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="exampleModalLabel">Hapus Akun</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Yakin ingin menghapus data akun : <?= $akun['nama']; ?> .?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                <a href="hapus-akun.php?id_akun=<?= $akun['id_akun']; ?>"class="btn btn-danger">Hapus</a>
            </div>
            </div>
        </div>
        </div>
    <?php endforeach; ?>
    <?php endif; ?>

   <?php $data_modal = ($_SESSION['level'] == 1) ? $data_akun : $data_bylogin;?>
   <?php foreach ($data_modal as $akun) : ?>
    <div class="modal fade" id="modalUbah<?=$akun['id_akun']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header bg-success text-white">
            <h5 class="modal-title" id="exampleModalLabel">Ubah Data Akun</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" method="post">
        <div class="modal-body">
            <input type="hidden" name="id_akun" value="<?= $akun['id_akun'];?>">
			<div class="mb-3">
				<label for="nama" class="form-label">Nama</label>
				<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Akun..." value="<?= $akun['nama'];?>" required>
			</div>
			<div class="mb-3">
				<label for="username" class="form-label">Username</label>
				<input type="text" class="form-control" id="username" name="username" placeholder="Username..." value="<?= $akun['username'];?>" required>
			</div>
			<div class="mb-3">
				<label for="email" class="form-label">Email</label>
				<input type="email" class="form-control" id="email" name="email" placeholder="Email..." value="<?= $akun['email'];?>" required>
			</div>
            <div class="mb-3">
				<label for="password" class="form-label">Password <small>(Masukkan password baru/lama)</small></label>
				<input type="password" class="form-control" id="password" name="password" placeholder="Password..." required minlength="6">
			</div>
            <?php if ($_SESSION['level'] == 1) : ?>
            <div class="mb-3">
                <label for="level" class="form-label">Level</label>
                <?php $level = $akun['level']; ?>
                <select name="level" class="form-control" required>
                    <option value="1" <?= $level == '1' ? 'selected' : '' ?>>Admin</option>
                    <option value="2" <?= $level == '2' ? 'selected' : '' ?>>Operator Barang</option>
                    <option value="3" <?= $level == '3' ? 'selected' : '' ?>>Operator Mahasiswa</option>
                </select>
            </div>
            <?php else : ?>
            <input type="hidden" name="level" value="<?= $akun['level']; ?>">
            <?php endif; ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
            <button type="submit" name="ubah" class="btn btn-success">Ubah</button>
        </div>
        </form>
        </div>
    </div>
    </div>
    <?php endforeach; ?>

	<?php include 'layout/footer.php'; ?>
    