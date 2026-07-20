<?php
session_start();
if (!isset($_SESSION["login"])) {
    echo "<script>
			alert('login dulu');
			document.location.href = 'login.php';
			</script>";
    exit;
}
if ($_SESSION["level"] != 1 && $_SESSION["level"] != 2) {
    echo "<script>
				alert('Perhatian anda tidak punya hak akses');
				document.location.href = 'crud-modal.php';
			</script>";
    exit;
}

$title = 'Daftar Barang';
include 'layout/header.php';

if (isset($_POST['tambah'])) {
    if (create_barang($_POST) > 0) {
        echo "<script>
			alert('Data barang berhasil ditambahkan');
			document.location.href = 'index.php';
			</script>";
    } else {
        echo "<script>
			alert('Data barang gagal ditambahkan');
			document.location.href = 'index.php';
			</script>";
    }
}

if (isset($_POST['ubah'])) {
    if (update_barang($_POST) > 0) {
        echo "<script>
			alert('Data barang berhasil diubah');
			document.location.href = 'index.php';
			</script>";
    } else {
        echo "<script>
			alert('Data barang gagal diubah');
			document.location.href = 'index.php';
			</script>";
    }
}

$data_barang = select("SELECT * FROM barang ORDER BY id_barang ASC");
?>

      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-list-ul me-1"></i> Data Barang</h3>
              </div>
              <div class="col-sm-6 text-sm-end">
                <span class="text-secondary">Data Barang</span>
              </div>
            </div>
          </div>
        </div>
        <!--end::App Content Header-->

        <!--begin::App Content-->
        <div class="app-content">
          <div class="container-fluid">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title mb-0">Tabel Data Barang</h5>
              </div>
              <div class="card-body">
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
                  <i class="bi bi-plus-lg me-1"></i> Tambah
                </button>
                <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalFilter">
                  <i class="fas fa-search"></i> Filter Data
                </button>

                <table class="table table-hover align-middle" id="tabel">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama</th>
                      <th>Jumlah</th>
                      <th>Harga</th>
                      <th>Barcode</th>
                      <th>Tanggal</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($data_barang as $barang) : ?>
                    <tr>
                      <td><?= $no++; ?></td>
                      <td><?= $barang['nama']; ?></td>
                      <td><?= $barang['jumlah']; ?></td>
                      <td>Rp.<?= number_format($barang['harga'], 0, ',', '.'); ?></td>
                      <td class="text-center">
                        <img alt="barcode" src="barcode.php?codetype=Code128&size15&text=<?= $barang['barcode']; ?>&print=true">
                      </td>
                      <td><?= date("d/m/Y | H:i:s", strtotime($barang['tanggal'])); ?></td>
                      <td class="text-center">
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalUbah<?= $barang['id_barang']; ?>">
                          <i class="bi bi-pencil-square"></i> Ubah
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $barang['id_barang']; ?>">
                          <i class="bi bi-trash"></i> Hapus
                        </button>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalTambahLabel"><i class="bi bi-plus-lg me-1"></i> Tambah Barang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="" method="post">
        <div class="modal-body">
          <div class="mb-3">
            <label for="nama" class="form-label">Nama Barang</label>
            <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Barang..." required>
          </div>
          <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah</label>
            <input type="number" class="form-control" id="jumlah" name="jumlah" placeholder="Jumlah Barang..." required>
          </div>
          <div class="mb-3">
            <label for="harga" class="form-label">Harga Barang</label>
            <input type="number" class="form-control" id="harga" name="harga" placeholder="Harga Barang..." required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
          <button type="submit" class="btn btn-primary" name="tambah">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Ubah & Hapus (per baris) -->
<?php foreach ($data_barang as $barang) : ?>
<div class="modal fade" id="modalUbah<?= $barang['id_barang']; ?>" tabindex="-1" aria-labelledby="modalUbahLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalUbahLabel"><i class="bi bi-pencil-square me-1"></i> Ubah Barang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="" method="post">
        <div class="modal-body">
          <input type="hidden" name="id_barang" value="<?= $barang['id_barang']; ?>">
          <div class="mb-3">
            <label for="nama" class="form-label">Nama Barang</label>
            <input type="text" class="form-control" name="nama" value="<?= $barang['nama']; ?>" placeholder="Nama Barang..." required>
          </div>
          <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah</label>
            <input type="number" class="form-control" name="jumlah" value="<?= $barang['jumlah']; ?>" placeholder="Jumlah Barang..." required>
          </div>
          <div class="mb-3">
            <label for="harga" class="form-label">Harga Barang</label>
            <input type="number" class="form-control" name="harga" value="<?= $barang['harga']; ?>" placeholder="Harga Barang..." required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
          <button type="submit" class="btn btn-success" name="ubah">Ubah</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modalHapus<?= $barang['id_barang']; ?>" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalHapusLabel"><i class="bi bi-trash me-1"></i> Hapus Barang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Yakin ingin menghapus data barang: <strong><?= $barang['nama']; ?></strong>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
        <a href="hapus-barang.php?id_barang=<?= $barang['id_barang']; ?>" class="btn btn-danger">Hapus</a>
      </div>
    </div>
  </div>
</div>
<?php endforeach; ?>

<!-- Modal Filter -->
<div class="modal fade" id="modalFilter" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-primary">
                    Save changes
                </button>
            </div>

        </div>
    </div>
</div>

<?php include 'layout/footer.php'; ?>