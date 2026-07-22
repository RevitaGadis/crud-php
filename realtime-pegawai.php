<?php

include "config/app.php";

$data_pegawai = select("SELECT * FROM pegawai ORDER BY id_pegawai DESC");

$chart_jabatan = [];
foreach ($data_pegawai as $p) {
    $chart_jabatan[$p['jabatan']] = ($chart_jabatan[$p['jabatan']] ?? 0) + 1;
}

ob_start();
$no = 1;
foreach ($data_pegawai as $pegawai) :
?>
<tr>
    <td><?= $no++; ?></td>
    <td><?= $pegawai['nama']; ?></td>
    <td><?= $pegawai['jabatan']; ?></td>
    <td><?= $pegawai['email']; ?></td>
    <td><?= $pegawai['telepon']; ?></td>
    <td><?= $pegawai['alamat']; ?></td>
</tr>
<?php
endforeach;
$rows = ob_get_clean();

header('Content-Type: application/json');
echo json_encode([
    'rows'          => $rows,
    'chart_labels'  => array_keys($chart_jabatan),
    'chart_values'  => array_values($chart_jabatan),
]);