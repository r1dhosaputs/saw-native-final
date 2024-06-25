<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$ada_error = false;

try {
	$stmt = $koneksi->query("SELECT * FROM kriteria;");
	$kriteria = $stmt->fetchAll(PDO::FETCH_ASSOC);

	if (count($kriteria) <= 0) {
		$ada_error = 'Maaf, data kosong dan tidak dapat menghapus.';
	} else {
		$koneksi->exec("DELETE FROM kriteria;");
		$koneksi->exec("DELETE FROM sub_kriteria;");
		$koneksi->exec("DELETE FROM penilaian;");
		redirect_to('list-sub-kriteria.php?status=sukses-hapus');
	}
} catch (PDOException $e) {
	$ada_error = 'Kesalahan database: ' . $e->getMessage();
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
