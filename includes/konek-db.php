<?php
// $koneksi = mysqli_connect("localhost","root","","spk-saw-native");

// // Check connection
// if (mysqli_connect_errno()){
// 	echo "Koneksi database gagal : " . mysqli_connect_error();
// }

$database_file = 'Z:\laragon\www\SPK-SAW-NATIVE-ORI\Database\spk-saw.sqlite';

try {
	$koneksi = new PDO("sqlite:" . $database_file);
	$koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	echo "Koneksi database gagal: " . $e->getMessage();
	die();
}
