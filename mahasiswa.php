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

if (isset($_POST['tambah'])) {
    if (create_mahasiswa($_POST) > 0) {
        echo "<script>
			alert('Data mahasiswa berhasil ditambahkan');
			document.location.href = 'mahasiswa.php';
			</script>";
    } else {
        echo "<script>
			alert('Data mahasiswa gagal ditambahkan');
			document.location.href = 'mahasiswa.php';
			</script>";
    }
}

if (isset($_POST['ubah'])) {
    if (update_mahasiswa($_POST) > 0) {
        echo "<script>
			alert('Data mahasiswa berhasil diubah');
			document.location.href = 'mahasiswa.php';
			</script>";
    } else {
        echo "<script>
			alert('Data mahasiswa gagal diubah');
			document.location.href = 'mahasiswa.php';
			</script>";
    }
}

$data_mahasiswa = select("SELECT * FROM mahasiswa ORDER BY id_mahasiswa DESC");

// Agregasi data untuk chart
$chart_prodi = [];
$chart_jk    = [];
foreach ($data_mahasiswa as $m) {
    $chart_prodi[$m['prodi']] = ($chart_prodi[$m['prodi']] ?? 0) + 1;
    $chart_jk[$m['jk']]       = ($chart_jk[$m['jk']] ?? 0) + 1;
}
?>

      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-people me-1"></i> Data Mahasiswa</h3>
              </div>
              <div class="col-sm-6 text-sm-end">
                <span class="text-secondary">Data Mahasiswa</span>
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
                <h5 class="card-title mb-0">Tabel Data Mahasiswa</h5>
              </div>
              <div class="card-body">
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
                  <i class="bi bi-plus-lg me-1"></i> Tambah
                </button>
                <a href="download-excel-mahasiswa.php" class="btn btn-success mb-3 text-decoration-none">
                  <i class="bi bi-file-earmark-excel me-1"></i> Download Excel
                </a>
                <a href="download-pdf-mahasiswa.php" class="btn btn-danger mb-3 text-decoration-none">
                  <i class="bi bi-file-earmark-pdf me-1"></i> Download PDF
                </a>

                <table class="table table-hover align-middle" id="tabel">
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
                    <?php $no = 1; ?>
                    <?php foreach ($data_mahasiswa as $mahasiswa) : ?>
                    <tr>
                      <td><?= $no++; ?></td>
                      <td><?= $mahasiswa['nama']; ?></td>
                      <td><?= $mahasiswa['prodi']; ?></td>
                      <td><?= $mahasiswa['jk']; ?></td>
                      <td><?= $mahasiswa['telepon']; ?></td>
                      <td class="text-center">
                        <a href="detail-mahasiswa.php?id_mahasiswa=<?= $mahasiswa['id_mahasiswa']; ?>" class="btn btn-primary btn-sm">
                          <i class="bi bi-eye"></i> Detail
                        </a>
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalUbah<?= $mahasiswa['id_mahasiswa']; ?>">
                          <i class="bi bi-pencil-square"></i> Ubah
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $mahasiswa['id_mahasiswa']; ?>">
                          <i class="bi bi-trash"></i> Hapus
                        </button>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>

            <!--begin::Chart Row Mahasiswa-->
            <div class="row mt-4">
              <div class="col-md-6 mb-3">
                <div class="card h-100">
                  <div class="card-header bg-primary text-white">
                    <h6 class="card-title mb-0"><i class="bi bi-pie-chart-fill me-1"></i> Distribusi Mahasiswa per Prodi</h6>
                  </div>
                  <div class="card-body">
                    <canvas id="chartProdi" height="220"></canvas>
                  </div>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="card h-100">
                  <div class="card-header bg-info text-white">
                    <h6 class="card-title mb-0"><i class="bi bi-bar-chart-fill me-1"></i> Distribusi Mahasiswa per Jenis Kelamin</h6>
                  </div>
                  <div class="card-body">
                    <canvas id="chartJk" height="220"></canvas>
                  </div>
                </div>
              </div>
            </div>
            <!--end::Chart Row Mahasiswa-->

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
        <h5 class="modal-title" id="modalTambahLabel"><i class="bi bi-plus-lg me-1"></i> Tambah Mahasiswa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="" method="post" enctype="multipart/form-data">
        <div class="modal-body">
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
            <input type="file" class="form-control" id="foto" name="foto" onchange="previewImg(this, '.img-preview-tambah')" required>
            <img src="" alt="" class="img-thumbnail img-preview-tambah mt-2" width="100px">
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
<?php foreach ($data_mahasiswa as $mahasiswa) : ?>
<div class="modal fade" id="modalUbah<?= $mahasiswa['id_mahasiswa']; ?>" tabindex="-1" aria-labelledby="modalUbahLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalUbahLabel"><i class="bi bi-pencil-square me-1"></i> Ubah Mahasiswa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <input type="hidden" name="id_mahasiswa" value="<?= $mahasiswa['id_mahasiswa']; ?>">
          <input type="hidden" name="fotoLama" value="<?= $mahasiswa['foto']; ?>">
          <div class="mb-3">
            <label class="form-label">Nama Mahasiswa</label>
            <input type="text" class="form-control" name="nama" value="<?= $mahasiswa['nama']; ?>" placeholder="Nama Mahasiswa..." required>
          </div>
          <div class="mb-3">
            <label class="form-label">Prodi</label>
            <?php $prodi = $mahasiswa['prodi']; ?>
            <select name="prodi" class="form-control" required>
              <option value="Teknik Informatika" <?= $prodi == 'Teknik Informatika' ? 'selected' : null ?>>Teknik Informatika</option>
              <option value="Teknik Mesin" <?= $prodi == 'Teknik Mesin' ? 'selected' : null ?>>Teknik Mesin</option>
              <option value="Teknik Listrik" <?= $prodi == 'Teknik Listrik' ? 'selected' : null ?>>Teknik Listrik</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Jenis Kelamin</label>
            <?php $jk = $mahasiswa['jk']; ?>
            <select name="jk" class="form-control" required>
              <option value="Laki-laki" <?= $jk == 'Laki-laki' ? 'selected' : null ?>>Laki-laki</option>
              <option value="Perempuan" <?= $jk == 'Perempuan' ? 'selected' : null ?>>Perempuan</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Telepon</label>
            <input type="number" class="form-control" name="telepon" value="<?= $mahasiswa['telepon']; ?>" placeholder="Nomor Telepon..." required>
          </div>
          <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea class="form-control" name="alamat" placeholder="Alamat..." required><?= $mahasiswa['alamat']; ?></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?= $mahasiswa['email']; ?>" placeholder="Email..." required>
          </div>
          <div class="mb-3">
            <label class="form-label">Foto</label>
            <input type="file" class="form-control" name="foto" onchange="previewImg(this, '.img-preview-<?= $mahasiswa['id_mahasiswa']; ?>')">
            <p class="mt-2">
              <small>Gambar Sebelumnya</small><br>
              <img src="assets/img/<?= $mahasiswa['foto']; ?>" alt="foto" class="img-preview-<?= $mahasiswa['id_mahasiswa']; ?>" width="100px">
            </p>
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

<div class="modal fade" id="modalHapus<?= $mahasiswa['id_mahasiswa']; ?>" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalHapusLabel"><i class="bi bi-trash me-1"></i> Hapus Mahasiswa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Yakin ingin menghapus data mahasiswa: <strong><?= $mahasiswa['nama']; ?></strong>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
        <a href="hapus-mahasiswa.php?id_mahasiswa=<?= $mahasiswa['id_mahasiswa']; ?>" class="btn btn-danger">Hapus</a>
      </div>
    </div>
  </div>
</div>
<?php endforeach; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
  // Data chart dikirim dari PHP (hasil query data_mahasiswa)
  const dataProdi = {
    labels: <?= json_encode(array_keys($chart_prodi)); ?>,
    values: <?= json_encode(array_values($chart_prodi)); ?>
  };
  const dataJk = {
    labels: <?= json_encode(array_keys($chart_jk)); ?>,
    values: <?= json_encode(array_values($chart_jk)); ?>
  };

  new Chart(document.getElementById('chartProdi'), {
    type: 'doughnut',
    data: {
      labels: dataProdi.labels,
      datasets: [{
        data: dataProdi.values,
        backgroundColor: ['#0d6efd', '#6610f2', '#6f42c1', '#0dcaf0', '#20c997']
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { position: 'bottom' } }
    }
  });

  new Chart(document.getElementById('chartJk'), {
    type: 'bar',
    data: {
      labels: dataJk.labels,
      datasets: [{
        label: 'Jumlah Mahasiswa',
        data: dataJk.values,
        backgroundColor: ['#0dcaf0', '#fd7e14']
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
  });

  function previewImg(input, targetSelector) {
    const imgPreview = document.querySelector(targetSelector);
    if (input.files && input.files[0]) {
      const fileFoto = new FileReader();
      fileFoto.onload = function (e) {
        imgPreview.src = e.target.result;
      }
      fileFoto.readAsDataURL(input.files[0]);
    }
  }
</script>

<?php include 'layout/footer.php'; ?>