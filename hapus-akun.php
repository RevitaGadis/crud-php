<?php 
	session_start();
		if (!isset($_SESSION["login"])) {
			echo "<script>
				alert('login dulu');
				document.location.href = 'login.php';
				</script>";
			exit;
		}
		if ($_SESSION["level"] != 1) {
			echo "<script>
					alert('Perhatian anda tidak punya hak akses');
					document.location.href = 'crud-modal.php';
				</script>";
			exit;
		}
    include 'config/app.php'; 
    $id_akun = (int)$_GET['id_akun'];
	if(delete_akun($id_akun) > 0){
		echo"<script>
		alert('Data akun berhasil dihapus');
		document.location.href = 'crud-modal.php';
		</script>";
	} else {
		echo"<script>
		alert('Data akun gagal dihapus');
		document.location.href = 'crud-modal.php';
		</script>";
	}
?>