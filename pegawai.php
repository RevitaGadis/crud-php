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
          </div>
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->

<script>
    $('document').ready(function() {
        getPegawai()
    });

    function getPegawai()
    {
        $.ajax({
            url: "realtime-pegawai.php",
            type: "GET",
            success: function(response) {
                $('#live_data').html(response)
            }
        });
    }
</script>

<?php include 'layout/footer.php'; ?>