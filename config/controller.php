<?php 
function select($query){
	global $db;
	$result = mysqli_query($db, $query);
	$rows = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$rows[] = $row;
	}
	return $rows;
	}

function create_barang($post)
{
    global $db;

    $nama= strip_tags($post['nama']);
    $jumlah= strip_tags($post['jumlah']);
    $harga= strip_tags($post['harga']);
    $barcode= rand(1000000000, 9999999999); 

    $query = "INSERT INTO barang VALUES(null, '$nama', '$jumlah', '$harga', CURRENT_TIMESTAMP(), '$barcode')";
    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}

function update_barang($post)
{
    global $db;

    $id_barang= strip_tags($post['id_barang']);
    $nama= strip_tags($post['nama']);
    $jumlah= strip_tags($post['jumlah']);
    $harga= strip_tags($post['harga']);

    $query = "UPDATE barang SET nama='$nama', jumlah='$jumlah', harga='$harga' WHERE id_barang='$id_barang'";
    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
    
}

function delete_barang($id_barang)
{
    global $db;
    $query = "DELETE FROM barang WHERE id_barang = $id_barang";
    mysqli_query($db, $query);
    return mysqli_affected_rows($db);

}

function create_mahasiswa($post)
{
    global $db;

    $nama= strip_tags($post['nama']);
    $prodi= strip_tags($post['prodi']);
    $jk= strip_tags($post['jk']);
    $telepon= strip_tags($post['telepon']);
    $alamat= strip_tags($post['alamat']);
    $email= strip_tags($post['email']);
    $foto= upload_file();

    if (!$foto) {
        return false;
    }

    $query = "INSERT INTO mahasiswa VALUES(null, '$nama', '$prodi', '$jk', '$telepon','$email','$foto', '$alamat'";
    mysqli_query($db, $query);
    return mysqli_affected_rows($db);

}

function upload_file() 
{
    $namaFile = $_FILES['foto']['name'];
    $ukuranFile = $_FILES['foto']['size'];
    $error = $_FILES['foto']['error'];
    $tmpName = $_FILES['foto']['tmp_name'];

    $extensifileValid = ['jpg', 'jpeg', 'png'];
    $extensifile = explode('.', $namaFile);
    $extensifile = strtolower(end($extensifile));

    if (!in_array($extensifile, $extensifileValid)) {
        echo"<script>
			alert('Format file tidak valid');
			document.location.href = 'tambah-mahasiswa.php';
			</script>";
        die();
	    }
    if ($ukuranFile>2048000){
        echo"<script>
			alert('Format file tidak valid');
			document.location.href = 'tambah-mahasiswa.php';
			</script>";
        die();
	    }
    
    $namaFileBaru = uniqid();
    $namaFileBaru .= '-';
    $namaFileBaru .= $extensifile;

    move_uploaded_file($tmpName, 'assets/img/' . $namaFileBaru);
    return $namaFileBaru;
}
function update_mahasiswa($post)
{
    global $db;

    $id_mahasiswa= strip_tags($post['id_mahasiswa']);
    $nama= strip_tags($post['nama']);
    $prodi= strip_tags($post['prodi']);
    $jk= strip_tags($post['jk']);
    $telepon= strip_tags($post['telepon']);
    $alamat= strip_tags($post['alamat']);
    $email= strip_tags($post['email']);
    $fotoLama = strip_tags($post['fotoLama']);

    if ($_FILES['foto']['error'] == 4){
        $foto = $fotoLama;
    } else {
        $foto = upload_file();
    }


    $query = "UPDATE mahasiswa SET nama='$nama', prodi='$prodi', jk='$jk', telepon='$telepon', email='$email', foto='$foto', alamat='$alamat' WHERE id_mahasiswa='$id_mahasiswa'";
    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
    
}

function delete_mahasiswa($id_mahasiswa)
{
    global $db;
    $foto = select("SELECT * FROM mahasiswa WHERE id_mahasiswa = $id_mahasiswa")[0];
    unlink("assets/img/". $foto['foto']);   
    $query = "DELETE FROM mahasiswa WHERE id_mahasiswa = $id_mahasiswa";
    mysqli_query($db, $query);
    return mysqli_affected_rows($db);

}

function create_akun($post)
{
    global $db;

    $nama= $post['nama'];
    $username= $post['username'];
    $email= strip_tags($post['email']);
    $password= $post['password'];
    $level= strip_tags($post['level']);

    $password = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO akun VALUES(null, '$nama', '$username', '$email', '$password', '$level')";
    mysqli_query($db, $query);
    return mysqli_affected_rows($db);

}

function delete_akun($id_akun)
{
    global $db;
    $query = "DELETE FROM akun WHERE id_akun = $id_akun";
    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}

function update_akun($post)
{
    global $db;

    $id_akun = $post['id_akun'];
    $nama = $post['nama'];
    $username = $post['username'];
    $email = strip_tags($post['email']);
    $password = password_hash($post['password'], PASSWORD_DEFAULT);
    $level = strip_tags($post['level']);

    $query = "UPDATE akun SET
                nama='$nama',
                username='$username',
                email='$email',
                password='$password',
                level='$level'
              WHERE id_akun='$id_akun'";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}