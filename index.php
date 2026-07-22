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

// Default: nilai filter kosong (dipakai untuk mengisi ulang form setelah submit)
$tgl_awal_raw  = '';
$tgl_akhir_raw = '';

if (isset($_POST['filter'])) {
    $tgl_awal_raw  = strip_tags($_POST['tgl_awal']);
    $tgl_akhir_raw = strip_tags($_POST['tgl_akhir']);

    $tgl_awal  = $tgl_awal_raw . " 00:00:00";
    $tgl_akhir = $tgl_akhir_raw . " 23:59:59";

    global $db;
    $stmt = mysqli_prepare($db, "SELECT * FROM barang WHERE tanggal BETWEEN ? AND ? ORDER BY id_barang DESC");
    mysqli_stmt_bind_param($stmt, "ss", $tgl_awal, $tgl_akhir);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data_barang = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data_barang[] = $row;
    }
    mysqli_stmt_close($stmt);
} else {
    $data_barang = select("SELECT * FROM barang ORDER BY id_barang DESC");
}

// Agregasi data untuk chart
$chart_nama   = [];
$chart_jumlah = [];
$chart_bulan  = []; // key: Y-m (untuk sorting kronologis)
foreach ($data_barang as $b) {
    $chart_nama[]   = $b['nama'];
    $chart_jumlah[] = (int) $b['jumlah'];

    $bulanKey = date('Y-m', strtotime($b['tanggal']));
    $nilai    = $b['jumlah'] * $b['harga'];
    $chart_bulan[$bulanKey] = ($chart_bulan[$bulanKey] ?? 0) + $nilai;
}
ksort($chart_bulan, SORT_STRING);

$chart_bulan_label = [];
$chart_bulan_nilai = [];
foreach ($chart_bulan as $key => $nilai) {
    $chart_bulan_label[] = date('M Y', strtotime($key . '-01'));
    $chart_bulan_nilai[] = $nilai;
}
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
                      <td><?= htmlspecialchars($barang['nama']); ?></td>
                      <td><?= $barang['jumlah']; ?></td>
                      <td>Rp.<?= number_format($barang['harga'], 0, ',', '.'); ?></td>
                      <td class="text-center">
                        <img alt="barcode" src="barcode.php?codetype=Code128&size15&text=<?= urlencode($barang['barcode']); ?>&print=true">
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

            <!--begin::Chart Row Barang-->
            <div class="row mt-4">
              <div class="col-md-6 mb-3">
                <div class="card h-100">
                  <div class="card-header bg-success text-white">
                    <h6 class="card-title mb-0"><i class="bi bi-bar-chart-fill me-1"></i> Jumlah Stok per Barang</h6>
                  </div>
                  <div class="card-body">
                    <canvas id="chartStok" height="220"></canvas>
                  </div>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="card h-100">
                  <div class="card-header bg-primary text-white">
                    <h6 class="card-title mb-0"><i class="bi bi-graph-up me-1"></i> Nilai Barang per Bulan</h6>
                  </div>
                  <div class="card-body">
                    <canvas id="chartNilaiBulan" height="220"></canvas>
                  </div>
                </div>
              </div>
            </div>
            <!--end::Chart Row Barang-->

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

<!-- Modal Ubah & Hapus -->
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
            <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($barang['nama']); ?>" placeholder="Nama Barang..." required>
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
        <p>Yakin ingin menghapus data barang: <strong><?= htmlspecialchars($barang['nama']); ?></strong>?</p>
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
<div class="modal fade" id="modalFilter" tabindex="-1" aria-labelledby="modalFilterLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalFilterLabel"><i class="fas fa-search me-1"></i> Filter Data Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="" method="post">

                    <div class="mb-3">
                        <label for="tgl_awal" class="form-label">Tanggal Awal</label>
                        <input type="date" name="tgl_awal" id="tgl_awal" class="form-control" value="<?= htmlspecialchars($tgl_awal_raw); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="tgl_akhir" class="form-label">Tanggal Akhir</label>
                        <input type="date" name="tgl_akhir" id="tgl_akhir" class="form-control" value="<?= htmlspecialchars($tgl_akhir_raw); ?>">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-success btn-sm" name="filter">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
  const dataStok = {
    labels: <?= json_encode(array_map('htmlspecialchars_decode', $chart_nama)); ?>,
    values: <?= json_encode($chart_jumlah); ?>
  };
  const dataNilaiBulan = {
    labels: <?= json_encode($chart_bulan_label); ?>,
    values: <?= json_encode($chart_bulan_nilai); ?>
  };

  new Chart(document.getElementById('chartStok'), {
    type: 'bar',
    data: {
      labels: dataStok.labels,
      datasets: [{
        label: 'Jumlah Stok',
        data: dataStok.values,
        backgroundColor: '#198754'
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
  });

  new Chart(document.getElementById('chartNilaiBulan'), {
    type: 'line',
    data: {
      labels: dataNilaiBulan.labels,
      datasets: [{
        label: 'Nilai Barang (Rp)',
        data: dataNilaiBulan.values,
        borderColor: '#0d6efd',
        backgroundColor: 'rgba(13,110,253,0.15)',
        fill: true,
        tension: 0.3
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function (value) {
              return 'Rp' + value.toLocaleString('id-ID');
            }
          }
        }
      }
    }
  });
</script>

<?php include 'layout/footer.php'; ?>