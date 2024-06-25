<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$errors = array();
$sukses = false;

$nama = (isset($_POST['nama'])) ? trim($_POST['nama']) : '';
$no_peserta = (isset($_POST['no_peserta'])) ? trim($_POST['no_peserta']) : '';
$nik_pengurus = (isset($_POST['nik_pengurus'])) ? trim($_POST['nik_pengurus']) : '';
$id_keluarga_dtks = (isset($_POST['id_keluarga_dtks'])) ? trim($_POST['id_keluarga_dtks']) : '';
$alamat = (isset($_POST['alamat'])) ? trim($_POST['alamat']) : '';

if (isset($_POST['submit'])) :

	// Validasi
	if (!$nama) {
		$errors[] = 'Nama tidak boleh kosong';
	}

	if (!$no_peserta) {
		$errors[] = 'no_peserta tidak boleh kosong';
	}

	if (!$nik_pengurus) {
		$errors[] = 'nik_pengurus tidak boleh kosong';
	} else {
		if (strlen($nik_pengurus) != 16) {
			$errors[] = 'NIK pengurus harus terdiri dari 16 angka';
		}
	}

	if (!$id_keluarga_dtks) {
		$errors[] = 'id_keluarga_dtks tidak boleh kosong';
	}

	if (!$alamat) {
		$errors[] = 'alamat tidak boleh kosong';
	}

	// Jika lolos validasi lakukan hal di bawah ini
	// if(empty($errors)):
	// 	$simpan = mysqli_query($koneksi,"INSERT INTO alternatif (id_alternatif, nama, no_peserta, nik_pengurus, id_keluarga_dtks, alamat, tahap, tahun) VALUES ('', '$nama', '$no_peserta', '$nik_pengurus', '$id_keluarga_dtks', '$alamat', '$tahap', '$tahun')");
	// 	if($simpan) {
	// 		redirect_to('list-alternatif.php?status=sukses-baru');
	// 	}else{
	// 		$errors[] = 'Data gagal disimpan';
	// 	}
	// endif;

	// new
	if (empty($errors)) :
		// Prepare the SQL statement
		$stmt = $koneksi->prepare("INSERT INTO alternatif (nama, no_peserta, nik_pengurus, id_keluarga_dtks, alamat, tahap, tahun) VALUES (:nama, :no_peserta, :nik_pengurus, :id_keluarga_dtks, :alamat, :tahap, :tahun)");

		// Bind the parameters
		$stmt->bindParam(':nama', $nama);
		$stmt->bindParam(':no_peserta', $no_peserta);
		$stmt->bindParam(':nik_pengurus', $nik_pengurus);
		$stmt->bindParam(':id_keluarga_dtks', $id_keluarga_dtks);
		$stmt->bindParam(':alamat', $alamat);
		$stmt->bindParam(':tahap', $tahap);
		$stmt->bindParam(':tahun', $tahun);

		// Execute the statement
		if ($stmt->execute()) {
			redirect_to('list-alternatif.php?status=sukses-baru');
		} else {
			$errors[] = 'Data gagal disimpan';
		}
	endif;


endif;

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

<form action="tambah-alternatif.php" method="post">
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-danger"><i class="fas fa-fw fa-plus"></i> Tambah Data Alternatif</h6>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="form-group col-md-12">
					<label class="font-weight-bold">Nama</label>
					<input autocomplete="off" type="text" name="nama" required value="<?php echo $nama; ?>" class="form-control" />
				</div>

				<div class="form-group col-md-12">
					<label class="font-weight-bold">No Peserta</label>
					<input autocomplete="off" type="number" name="no_peserta" required value="<?php echo $no_peserta; ?>" class="form-control" />
				</div>

				<div class="form-group col-md-12">
					<label class="font-weight-bold">NIK Pengurus</label>
					<input autocomplete="off" type="number" name="nik_pengurus" required value="<?php echo $nik_pengurus; ?>" class="form-control" />
				</div>

				<div class="form-group col-md-12">
					<label class="font-weight-bold">ID Keluarga DTKS</label>
					<input autocomplete="off" type="number" name="id_keluarga_dtks" required value="<?php echo $id_keluarga_dtks; ?>" class="form-control" />
				</div>

				<div class="form-group col-md-12">
					<label class="font-weight-bold">Alamat</label>
					<input autocomplete="off" type="text" name="alamat" required value="<?php echo $alamat; ?>" class="form-control" />
				</div>
			</div>
		</div>
		<div class="card-footer text-right">
			<button name="submit" value="submit" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
			<button type="reset" class="btn btn-info"><i class="fa fa-sync-alt"></i> Reset</button>
		</div>
	</div>
</form>

<?php
require_once('template/footer.php');
?>