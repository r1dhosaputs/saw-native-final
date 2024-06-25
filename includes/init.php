<?php
session_start();
require_once('konek-db.php');
require_once('functions.php');

// $perintah = "SELECT * FROM periode";
// $ambildata = mysqli_query($koneksi, $perintah);
// $periode = mysqli_fetch_array($ambildata);
// $tahap = $periode['tahap']; 
// $tahun = $periode['tahun'];

$perintah = "SELECT * FROM periode";
$ambildata = $koneksi->query($perintah);
$periode = $ambildata->fetch(PDO::FETCH_ASSOC);

$tahap = $periode['tahap'];
$tahun = $periode['tahun'];
?>