<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$ada_error = false;
$result = '';

$id_kriteria = (isset($_GET['id'])) ? trim($_GET['id']) : '';

if (!$id_kriteria) {
	$ada_error = 'Maaf, data tidak dapat diproses.';
} else {
	// $query = mysqli_query($koneksi,"SELECT * FROM kriteria WHERE id_kriteria = '$id_kriteria'");
	// $cek = mysqli_num_rows($query);

	// if($cek <= 0) {
	// 	$ada_error = 'Maaf, data tidak dapat diproses.';
	// } else {
	// 	mysqli_query($koneksi,"DELETE FROM kriteria WHERE id_kriteria = '$id_kriteria';");
	// 	mysqli_query($koneksi,"DELETE FROM sub_kriteria WHERE id_kriteria = '$id_kriteria';");
	// 	redirect_to('list-kriteria.php?status=sukses-hapus');
	// }

	$stmt = $koneksi->prepare("SELECT * FROM kriteria WHERE id_kriteria = :id_kriteria");
	$stmt->bindParam(':id_kriteria', $id_kriteria, PDO::PARAM_STR);
	$stmt->execute();

	// Check if a row was found
	$data = $stmt->fetch(PDO::FETCH_ASSOC); // Use fetch() with PDO::FETCH_ASSOC
	$cek = ($data !== false) ? 1 : 0;

	if ($cek <= 0) {
		$ada_error = 'Maaf, data tidak dapat diproses.';
	} else {
		$koneksi->exec("DELETE FROM kriteria WHERE id_kriteria = '$id_kriteria'"); // Menggunakan exec untuk query non-SELECT
		$koneksi->exec("DELETE FROM sub_kriteria WHERE id_kriteria = '$id_kriteria'");
		redirect_to('list-kriteria.php?status=sukses-hapus');
	}
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
