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

// Agregasi data untuk chart distribusi level akun (khusus admin/level 1)
$chart_level = ['Admin' => 0, 'Operator Barang' => 0, 'Operator Mahasiswa' => 0];
foreach ($data_akun as $a) {
    if ($a['level'] == 1) {
        $chart_level['Admin']++;
    } elseif ($a['level'] == 2) {
        $chart_level['Operator Barang']++;
    } elseif ($a['level'] == 3) {
        $chart_level['Operator Mahasiswa']++;
    }
}

if (isset($_POST['tambah'])) {
    if (create_akun($_POST) > 0) {
        echo "<script>
                alert('Data akun berhasil ditambahkan');
                document.location.href = 'crud-modal.php';
                </script>";
    } else {
        echo "<script>
                alert('Data akun gagal ditambahkan');
                document.location.href = 'crud-modal.php';
                </script>";
    }
}
if (isset($_POST['ubah'])) {
    if (update_akun($_POST) > 0) {
        echo "<script>
                alert('Data akun berhasil diubah');
                document.location.href = 'crud-modal.php';
                </script>";
    } else {
        echo "<script>
                alert('Data akun gagal diubah');
                document.location.href = 'crud-modal.php';
                </script>";
    }
}
?>

      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-person-badge me-1"></i> Data Akun</h3>
              </div>
              <div class="col-sm-6 text-sm-end">
                <span class="text-secondary">Data Akun</span>
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
                <h5 class="card-title mb-0">Tabel Data Akun</h5>
              </div>
              <div class="card-body">
                <?php if ($_SESSION['level'] == 1) : ?>
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
                  <i class="bi bi-plus-lg me-1"></i> Tambah
                </button>
                <?php endif; ?>

                <table class="table table-hover align-middle" id="tabel">
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
                            <td class="text-center">
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalUbah<?= $akun['id_akun']; ?>">
                                    <i class="bi bi-pencil-square"></i> Ubah
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $akun['id_akun']; ?>">
                                    <i class="bi bi-trash"></i> Hapus
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
                            <td class="text-center">
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalUbah<?= $akun['id_akun']; ?>">
                                    <i class="bi bi-pencil-square"></i> Ubah
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>

            <?php if ($_SESSION['level'] == 1) : ?>
            <!--begin::Chart Row Akun-->
            <div class="row mt-4">
              <div class="col-md-6 col-lg-5 mb-3">
                <div class="card h-100">
                  <div class="card-header bg-dark text-white">
                    <h6 class="card-title mb-0"><i class="bi bi-pie-chart-fill me-1"></i> Distribusi Akun per Level</h6>
                  </div>
                  <div class="card-body">
                    <canvas id="chartLevel" height="220"></canvas>
                  </div>
                </div>
              </div>
            </div>
            <!--end::Chart Row Akun-->
            <?php endif; ?>

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
        <h5 class="modal-title" id="modalTambahLabel"><i class="bi bi-plus-lg me-1"></i> Tambah Akun</h5>
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
            <label for="level" class="form-label">Level</label>
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

<!-- Modal Hapus (hanya untuk level 1) -->
<?php if ($_SESSION['level'] == 1) : ?>
<?php foreach ($data_akun as $akun) : ?>
<div class="modal fade" id="modalHapus<?= $akun['id_akun']; ?>" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalHapusLabel"><i class="bi bi-trash me-1"></i> Hapus Akun</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Yakin ingin menghapus data akun: <strong><?= $akun['nama']; ?></strong>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
        <a href="hapus-akun.php?id_akun=<?= $akun['id_akun']; ?>" class="btn btn-danger">Hapus</a>
      </div>
    </div>
  </div>
</div>
<?php endforeach; ?>
<?php endif; ?>

<!-- Modal Ubah -->
<?php $data_modal = ($_SESSION['level'] == 1) ? $data_akun : $data_bylogin; ?>
<?php foreach ($data_modal as $akun) : ?>
<div class="modal fade" id="modalUbah<?= $akun['id_akun']; ?>" tabindex="-1" aria-labelledby="modalUbahLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalUbahLabel"><i class="bi bi-pencil-square me-1"></i> Ubah Data Akun</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="" method="post">
        <div class="modal-body">
          <input type="hidden" name="id_akun" value="<?= $akun['id_akun']; ?>">
          <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control" name="nama" placeholder="Nama Akun..." value="<?= $akun['nama']; ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" class="form-control" name="username" placeholder="Username..." value="<?= $akun['username']; ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" placeholder="Email..." value="<?= $akun['email']; ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password <small>(Masukkan password baru/lama)</small></label>
            <input type="password" class="form-control" name="password" placeholder="Password..." required minlength="6">
          </div>
          <?php if ($_SESSION['level'] == 1) : ?>
          <div class="mb-3">
            <label class="form-label">Level</label>
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

<script>
  new DataTable('#tabel');
</script>

<?php if ($_SESSION['level'] == 1) : ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
  const dataLevel = {
    labels: <?= json_encode(array_keys($chart_level)); ?>,
    values: <?= json_encode(array_values($chart_level)); ?>
  };

  new Chart(document.getElementById('chartLevel'), {
    type: 'pie',
    data: {
      labels: dataLevel.labels,
      datasets: [{
        data: dataLevel.values,
        // warna disamakan dengan tombol Admin=primary, Operator Barang=success, Operator Mahasiswa=danger
        backgroundColor: ['#0d6efd', '#198754', '#dc3545']
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { position: 'bottom' } }
    }
  });
</script>
<?php endif; ?>

<?php include 'layout/footer.php'; ?>