<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$ada_error = false;
$result = '';

$id_sub_kriteria = (isset($_GET['id'])) ? trim($_GET['id']) : '';

if (!$id_sub_kriteria) {
	$ada_error = 'Maaf, data tidak dapat diproses.';
} else {
	try {

		// Debugging: Verifikasi struktur tabel
		$result = $koneksi->query("PRAGMA table_info(sub_kriteria)");
		$columns = $result->fetchAll(PDO::FETCH_ASSOC);

		echo '<pre>';
		print_r($columns);
		echo '</pre>';

		$result = $koneksi->query("PRAGMA table_info(penilaian)");
		$columns = $result->fetchAll(PDO::FETCH_ASSOC);

		echo '<pre>';
		print_r($columns);
		echo '</pre>';

		// Proses penghapusan data
		$stmt = $koneksi->prepare("SELECT * FROM sub_kriteria WHERE id_sub_kriteria = :id_sub_kriteria");
		$stmt->bindParam(':id_sub_kriteria', $id_sub_kriteria);
		$stmt->execute();

		$sub_kriteria = $stmt->fetch(PDO::FETCH_ASSOC);

		if (!$sub_kriteria) {
			$ada_error = 'Maaf, data tidak dapat diproses.';
		} else {
			$stmt = $koneksi->prepare("DELETE FROM sub_kriteria WHERE id_sub_kriteria = :id_sub_kriteria");
			$stmt->bindParam(':id_sub_kriteria', $id_sub_kriteria);
			$stmt->execute();

			// $stmt = $koneksi->prepare("DELETE FROM penilaian WHERE id_sub_kriteria = :id_sub_kriteria");
			// $stmt->bindParam(':id_sub_kriteria', $id_sub_kriteria);
			// $stmt->execute();

			redirect_to('list-sub-kriteria.php?status=sukses-hapus');
		}
	} catch (PDOException $e) {
		$ada_error = 'Kesalahan database: ' . $e->getMessage();
	}
}
?>

<?php
$page = "Sub Kriteria";
require_once('template/header.php');
?>
    <?php if ($ada_error) : ?>
        <?php echo '<div class="alert alert-danger">' . $ada_error . '</div>'; ?>    
    <?php endif; ?>
<?php
require_once('template/footer.php');
?>
