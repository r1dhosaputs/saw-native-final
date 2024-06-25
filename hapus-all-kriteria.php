<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$ada_error = false;

// $query = mysqli_query($koneksi,"SELECT * FROM kriteria;");
// $cek = mysqli_num_rows($query);

// if($cek <= 0) {
// 	$ada_error = 'Maaf, data kosong dan tidak dapat menghapus.';
// } else {
// 	mysqli_query($koneksi,"TRUNCATE TABLE kriteria;");
// 	mysqli_query($koneksi,"TRUNCATE TABLE sub_kriteria;");
// 	redirect_to('list-kriteria.php?status=sukses-hapus');
// }

// Menggunakan $koneksi yang sudah dibuat sebelumnya
$query = $koneksi->query("SELECT * FROM kriteria;");

// Periksa apakah ada baris yang dikembalikan

// Check if a row was found
$data = $query->fetchAll(PDO::FETCH_ASSOC); // Use fetch() with PDO::FETCH_ASSOC
$cek = ($data !== false) ? 1 : 0;

var_dump($data);

if ($cek <= 0) {
	$ada_error = 'Maaf, data kosong dan tidak dapat menghapus.';
} else {
	// echo 'yey';
	$koneksi->exec("DELETE FROM kriteria;");  // Menggunakan DELETE untuk SQLite
	$koneksi->exec("DELETE FROM sub_kriteria;");
	redirect_to('list-kriteria.php?status=sukses-hapus');
}

?>

<?php
$page = "Kriteria";
require_once('template/header.php');
?>
	<?php if ($ada_error) : ?>
		<?php echo '<div class="alert alert-danger">' . $ada_error . '</div>'; ?>	
	<?php endif; ?>
<?php
require_once('template/footer.php');
?>
