<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$ada_error = false;
$result = '';

$id_alternatif = (isset($_GET['id'])) ? trim($_GET['id']) : '';

// if(!$id_alternatif) {
// 	$ada_error = 'Maaf, data tidak dapat diproses.';
// } else {
// 	$query = mysqli_query($koneksi,"SELECT * FROM alternatif WHERE id_alternatif = '$id_alternatif'");
// 	$cek = mysqli_num_rows($query);

// 	if($cek <= 0) {
// 		$ada_error = 'Maaf, data tidak dapat diproses.';
// 	} else {
// 		mysqli_query($koneksi,"DELETE FROM alternatif WHERE id_alternatif = '$id_alternatif';");
// 		mysqli_query($koneksi,"DELETE FROM penilaian WHERE id_alternatif = '$id_alternatif';");
// 		mysqli_query($koneksi,"DELETE FROM hasil WHERE id_alternatif = '$id_alternatif';");
// 		redirect_to('list-alternatif.php?status=sukses-hapus');
// 	}
// }

if (!$id_alternatif) {
	$ada_error = 'Maaf, data tidak dapat diproses.';
} else {
	try {
		$stmt = $koneksi->prepare("SELECT COUNT(*) FROM alternatif WHERE id_alternatif = :id_alternatif");
		$stmt->bindParam(':id_alternatif', $id_alternatif);
		$stmt->execute();
		$cek = $stmt->fetchColumn();

		if ($cek <= 0) {
			$ada_error = 'Maaf, data tidak dapat diproses.';
		} else {
			$koneksi->beginTransaction();

			$stmt = $koneksi->prepare("DELETE FROM alternatif WHERE id_alternatif = :id_alternatif");
			$stmt->bindParam(':id_alternatif', $id_alternatif);
			$stmt->execute();

			$stmt = $koneksi->prepare("DELETE FROM penilaian WHERE id_alternatif = :id_alternatif");
			$stmt->bindParam(':id_alternatif', $id_alternatif);
			$stmt->execute();

			$stmt = $koneksi->prepare("DELETE FROM hasil WHERE id_alternatif = :id_alternatif");
			$stmt->bindParam(':id_alternatif', $id_alternatif);
			$stmt->execute();

			$koneksi->commit();

			redirect_to('list-alternatif.php?status=sukses-hapus');
		}
	} catch (PDOException $e) {
		$koneksi->rollBack(); // Rollback jika terjadi kesalahan
		$ada_error = 'Maaf, data tidak dapat diproses. Terjadi kesalahan database: ' . $e->getMessage();
	}
}

// Tampilkan pesan error jika ada
// if ($ada_error) {
// 	echo $ada_error;
// }

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