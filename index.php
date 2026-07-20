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
                <a href="tambah-barang.php" class="btn btn-primary mb-3 text-decoration-none">
                  <i class="bi bi-plus-lg me-1"></i> Tambah
                </a>

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
                        <a href="ubah-barang.php?id_barang=<?= $barang['id_barang']; ?>" class="btn btn-success btn-sm">
                          <i class="bi bi-pencil-square"></i> Ubah
                        </a>
                        <a href="hapus-barang.php?id_barang=<?= $barang['id_barang']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin data barang akan dihapus')">
                          <i class="bi bi-trash"></i> Hapus
                        </a>
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

<script>
  new DataTable('#tabel');
</script>

<?php include 'layout/footer.php'; ?>