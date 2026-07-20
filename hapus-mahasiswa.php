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
    include 'config/app.php'; 
    $id_mahasiswa = (int)$_GET['id_mahasiswa'];
	if(delete_mahasiswa($id_mahasiswa) > 0){
		echo"<script>
		alert('Data mahasiswa berhasil dihapus');
		document.location.href = 'mahasiswa.php';
		</script>";
	} else {
		echo"<script>
		alert('Data mahasiswa gagal dihapus');
		document.location.href = 'mahasiswa.php';
		</script>";
	}
?>