<?php
session_start();
if (!isset($_SESSION["login"])) {
    echo "<script>
			alert('login dulu');
			document.location.href = 'login.php';
			</script>";
    exit;
}

$title = 'Daftar Pegawai';
include 'layout/header.php';

$data_pegawai = select("SELECT * FROM pegawai ORDER BY id_pegawai DESC");

// Agregasi data untuk chart distribusi jabatan
$chart_jabatan = [];
foreach ($data_pegawai as $p) {
    $chart_jabatan[$p['jabatan']] = ($chart_jabatan[$p['jabatan']] ?? 0) + 1;
}
?>

      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-people me-1"></i> Data Pegawai</h3>
              </div>
              <div class="col-sm-6 text-sm-end">
                <span class="text-secondary">Data Pegawai</span>
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
                <h5 class="card-title mb-0">Tabel Data Pegawai</h5>
              </div>
              <div class="card-body">
                <table class="table table-hover align-middle">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama</th>
                      <th>Jabatan</th>
                      <th>Email</th>
                      <th>Telepon</th>
                      <th>Alamat</th>
                    </tr>
                  </thead>
                  <tbody id="live_data">
                    <?php $no = 1; ?>
                    <?php foreach ($data_pegawai as $pegawai) : ?>
                    <tr>
                      <td><?= $no++; ?></td>
                      <td><?= $pegawai['nama']; ?></td>
                      <td><?= $pegawai['jabatan']; ?></td>
                      <td><?= $pegawai['email']; ?></td>
                      <td><?= $pegawai['telepon']; ?></td>
                      <td><?= $pegawai['alamat']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>

            <!--begin::Chart Row Pegawai-->
            <div class="row mt-4">
              <div class="col-md-8 col-lg-6 mb-3">
                <div class="card h-100">
                  <div class="card-header bg-info text-white">
                    <h6 class="card-title mb-0"><i class="bi bi-bar-chart-fill me-1"></i> Distribusi Pegawai per Jabatan</h6>
                  </div>
                  <div class="card-body">
                    <canvas id="chartJabatan" height="220"></canvas>
                  </div>
                </div>
              </div>
            </div>
            <!--end::Chart Row Pegawai-->

          </div>
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
  // Data awal dari render PHP (first paint), selanjutnya di-update via polling realtime
  const dataJabatanAwal = {
    labels: <?= json_encode(array_keys($chart_jabatan)); ?>,
    values: <?= json_encode(array_values($chart_jabatan)); ?>
  };

  const chartJabatan = new Chart(document.getElementById('chartJabatan'), {
    type: 'bar',
    data: {
      labels: dataJabatanAwal.labels,
      datasets: [{
        label: 'Jumlah Pegawai',
        data: dataJabatanAwal.values,
        backgroundColor: '#0dcaf0'
      }]
    },
    options: {
      indexAxis: 'y',
      responsive: true,
      animation: { duration: 300 },
      plugins: { legend: { display: false } },
      scales: { x: { beginAtZero: true, ticks: { precision: 0 } } }
    }
  });

  function getPegawai() {
    $.ajax({
      url: "realtime-pegawai.php",
      type: "GET",
      dataType: "json",
      success: function(response) {
        // update tabel
        $('#live_data').html(response.rows);

        // update chart tanpa reload/flicker
        chartJabatan.data.labels = response.chart_labels;
        chartJabatan.data.datasets[0].data = response.chart_values;
        chartJabatan.update();
      }
    });
  }

  $(document).ready(function() {
    getPegawai();              // ambil data pertama kali
    setInterval(getPegawai, 5000); // lalu polling tiap 5 detik supaya realtime
  });
</script>

<?php include 'layout/footer.php'; ?>