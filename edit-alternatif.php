<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$errors = array();
$sukses = false;

$ada_error = false;
$result = '';

$id_alternatif = (isset($_GET['id'])) ? trim($_GET['id']) : '';

if (isset($_POST['submit'])) :

	$nama = $_POST['nama'];
	$no_peserta = $_POST['no_peserta'];
	$nik_pengurus = $_POST['nik_pengurus'];
	$id_keluarga_dtks = $_POST['id_keluarga_dtks'];
	$alamat = $_POST['alamat'];

	// Validasi
	if (!$nama) {
		$errors[] = 'Nama tidak boleh kosong';
	}

	if (!$no_peserta) {
		$errors[] = 'no_peserta tidak boleh kosong';
	}

	if (strlen($nik_pengurus) != 16) {
		$errors[] = 'NIK Pengurus harus berisi 16 Angka.';
	}

	if (!$id_keluarga_dtks) {
		$errors[] = 'id_keluarga_dtks tidak boleh kosong';
	}

	if (!$alamat) {
		$errors[] = 'alamat tidak boleh kosong';
	}

	// Jika lolos validasi lakukan hal di bawah ini
	// if(empty($errors)):

	// 	$update = mysqli_query($koneksi,"UPDATE alternatif SET nama = '$nama', no_peserta='$no_peserta', nik_pengurus='$nik_pengurus', id_keluarga_dtks='$id_keluarga_dtks', alamat='$alamat' WHERE id_alternatif = '$id_alternatif'");
	// 	if($update) {
	// 		redirect_to('list-alternatif.php?status=sukses-edit');
	// 	}else{
	// 		$errors[] = 'Data gagal diupdate.';
	// 	}
	// endif;

	// new
	if (empty($errors)) {
		// Prepare the SQL statement
		$stmt = $koneksi->prepare("UPDATE alternatif SET nama = :nama, no_peserta = :no_peserta, nik_pengurus = :nik_pengurus, id_keluarga_dtks = :id_keluarga_dtks, alamat = :alamat WHERE id_alternatif = :id_alternatif");

		// Bind the parameters
		$stmt->bindParam(':nama', $nama);
		$stmt->bindParam(':no_peserta', $no_peserta);
		$stmt->bindParam(':nik_pengurus', $nik_pengurus);
		$stmt->bindParam(':id_keluarga_dtks', $id_keluarga_dtks);
		$stmt->bindParam(':alamat', $alamat);
		$stmt->bindParam(':id_alternatif', $id_alternatif, PDO::PARAM_INT);

		// Execute the statement
		if ($stmt->execute()) {
			redirect_to('list-alternatif.php?status=sukses-edit');
		} else {
			$errors[] = 'Data gagal diupdate.';
		}
	}


endif;
?>

<?php
$page = "Alternatif";
require_once('template/header.php');
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-users"></i> Data Alternatif</h1>

	<a href="list-alternatif.php" class="btn btn-secondary btn-icon-split"><span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
		<span class="text">Kembali</span>
	</a>
</div>

<?php if (!empty($errors)) : ?>
	<div class="alert alert-info">
		<?php foreach ($errors as $error) : ?>
			<?php echo $error; ?>
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<?php if ($sukses) : ?>
	<div class="alert alert-success">
		Data berhasil disimpan
	</div>
<?php elseif ($ada_error) : ?>
	<div class="alert alert-info">
		<?php echo $ada_error; ?>
	</div>
<?php else : ?>

	<form action="edit-alternatif.php?id=<?php echo $id_alternatif; ?>" method="post">
		<div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-danger"><i class="fas fa-fw fa-edit"></i> Edit Data Alternatif</h6>
			</div>
			<?php
			if (!$id_alternatif) {
			?>
				<div class="card-body">
					<div class="alert alert-danger">Data tidak ada</div>
				</div>
				<?php
			} else {
				// $data = mysqli_query($koneksi, "SELECT * FROM alternatif WHERE id_alternatif='$id_alternatif'");
				// $cek = mysqli_num_rows($data);

				$stmt = $koneksi->prepare("SELECT * FROM alternatif WHERE id_alternatif = :id_alternatif");
				$stmt->bindParam(':id_alternatif', $id_alternatif);
				$stmt->execute();
				$data = $stmt->fetch(PDO::FETCH_ASSOC);


				// var_dump($data);



				if ($data <= 0) {
				?>
					<div class="card-body">
						<div class="alert alert-danger">Data tidak ada</div>
					</div>
				<?php
				} else {
					// while ($d = mysqli_fetch_array($data)) {
					$d = $data;
				?>
					<div class="card-body">
						<div class="row">
							<div class="form-group col-md-12">
								<label class="font-weight-bold">Nama</label>
								<input autocomplete="off" type="text" name="nama" required value="<?php echo $d['nama']; ?>" class="form-control" />
							</div>

							<div class="form-group col-md-12">
								<label class="font-weight-bold">No Peserta</label>
								<input autocomplete="off" type="number" name="no_peserta" required value="<?php echo $d['no_peserta']; ?>" class="form-control" />
							</div>

							<div class="form-group col-md-12">
								<label class="font-weight-bold">NIK Pengurus</label>
								<input autocomplete="off" type="number" name="nik_pengurus" required value="<?php echo $d['nik_pengurus']; ?>" class="form-control" />
							</div>

							<div class="form-group col-md-12">
								<label class="font-weight-bold">ID Keluarga DTKS</label>
								<input autocomplete="off" type="number" name="id_keluarga_dtks" required value="<?php echo $d['id_keluarga_dtks']; ?>" class="form-control" />
							</div>

							<div class="form-group col-md-12">
								<label class="font-weight-bold">Alamat</label>
								<input autocomplete="off" type="text" name="alamat" required value="<?php echo $d['alamat']; ?>" class="form-control" />
							</div>
						</div>
					</div>

					<div class="card-footer text-right">
						<button name="submit" value="submit" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
						<button type="reset" class="btn btn-info"><i class="fa fa-sync-alt"></i> Reset</button>
					</div>
			<?php
				}
			}
			// }
			?>
		</div>
	</form>

<?php
endif;
require_once('template/footer.php');
?>