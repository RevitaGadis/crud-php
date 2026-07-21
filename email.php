<?php
session_start();
if (!isset($_SESSION["login"])) {
    echo "<script>
			alert('login dulu');
			document.location.href = 'login.php';
			</script>";
    exit;
}

$title = 'Kirim Email';
include 'layout/header.php';
require 'email-proses.php';
?>

      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0"><i class="bi bi-envelope me-1"></i> Kirim Email</h3>
              </div>
              <div class="col-sm-6 text-sm-end">
                <span class="text-secondary">Kirim Email</span>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <form action="" method="post">
              <div class="mb-3">
                <label for="email_penerima" class="form-label fw-bold">Email Penerima</label>
                <input type="email" class="form-control" id="email_penerima" name="email_penerima" placeholder="Email penerima..." required>
              </div>

              <div class="mb-3">
                <label for="subject" class="form-label fw-bold">Subject</label>
                <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject..." required>
              </div>

              <div class="mb-3">
                <label for="pesan" class="form-label fw-bold">Pesan</label>
                <textarea class="form-control" id="pesan" name="pesan" rows="10" placeholder="Tulis pesan..." required></textarea>
              </div>

              <div class="text-end">
                <button type="submit" class="btn btn-primary" name="kirim">Kirim</button>
              </div>
            </form>
          </div>
        </div>
      </main>

<?php include 'layout/footer.php'; ?>