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
    $id_barang = (int)$_GET['id_barang'];
	if(delete_barang($id_barang) > 0){
		echo"<script>
		alert('Data barang berhasil dihapus');
		document.location.href = 'index.php';
		</script>";
	} else {
		echo"<script>
		alert('Data barang gagal dihapus');
		document.location.href = 'index.php';
		</script>";
	}
?>