<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
// $ada_error = false;
// $query = mysqli_query($koneksi,"SELECT * FROM alternatif;");
// $cek = mysqli_num_rows($query);

// if($cek <= 0) {
// 	$ada_error = 'Maaf, data kosong dan tidak dapat menghapus.';
// } else {
// 	mysqli_query($koneksi,"TRUNCATE TABLE alternatif;");
// 	mysqli_query($koneksi,"TRUNCATE TABLE penilaian;");
// 	redirect_to('list-alternatif.php?status=sukses-hapus');
// }

$ada_error = false;

try {
	$query = $koneksi->query("SELECT COUNT(*) FROM alternatif");
	$cek = $query->fetchColumn();

	if ($cek <= 0) {
		$ada_error = 'Maaf, data kosong dan tidak dapat menghapus.';
	} else {
		$koneksi->exec("DELETE FROM alternatif");
		$koneksi->exec("DELETE FROM penilaian");
		redirect_to('list-alternatif.php?status=sukses-hapus');
	}
} catch (PDOException $e) {
	$ada_error = "Terjadi kesalahan database: " . $e->getMessage();
}
?>

<?php
$page = "Alternatif";
require_once('template/header.php');
?>
	<?php if ($ada_error) : ?>
		<?php echo '<div class="alert alert-danger">' . $ada_error . '</div>'; ?>	
	<?php endif; ?>
<?php
require_once('template/footer.php');
?>
