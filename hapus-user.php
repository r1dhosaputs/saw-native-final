<?php
require_once('includes/init.php');
cek_login($role = array(1));


$ada_error = false;
$result = '';

$id_user = (isset($_GET['id'])) ? trim($_GET['id']) : '';

if (!$id_user) {
	$ada_error = 'Maaf, data tidak dapat diproses.';
} else {
	$stmt = $koneksi->prepare("SELECT * FROM user WHERE id_user = :id_user");
	$stmt->bindParam(':id_user', $id_user);
	$stmt->execute();
	$cek = $stmt->fetch(PDO::FETCH_ASSOC);

	if (!$cek) {
		$ada_error = 'Maaf, data tidak dapat diproses.';
	} else {
		$stmt = $koneksi->prepare("DELETE FROM user WHERE id_user = :id_user");
		$stmt->bindParam(':id_user', $id_user);
		$stmt->execute();
		redirect_to('list-user.php?status=sukses-hapus');
	}
}
?>

<?php
$page = "User";
require_once('template/header.php');
?>
    <?php if ($ada_error) : ?>
        <?php echo '<div class="alert alert-danger">' . $ada_error . '</div>'; ?>    
    <?php endif; ?>
<?php
require_once('template/footer.php');
?>
