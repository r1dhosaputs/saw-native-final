<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$ada_error = false;

// Menghapus data dari tabel penilaian menggunakan SQLite
$query = $koneksi->query("SELECT * FROM penilaian");
$cek = $query->fetchAll();

if (count($cek) <= 0) {
	$ada_error = 'Maaf, data kosong dan tidak dapat menghapus.';
} else {
	// Mengosongkan tabel penilaian (TRUNCATE TABLE) pada SQLite
	$koneksi->exec("DELETE FROM penilaian");
	redirect_to('list-penilaian.php?status=sukses-hapus');
}

$page = "Penilaian";
require_once('template/header.php');
?>

<?php if ($ada_error) : ?>
	<div class="alert alert-danger"><?php echo $ada_error; ?></div>
<?php endif; ?>

<?php require_once('template/footer.php'); ?>