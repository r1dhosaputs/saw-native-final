<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$page = "Penilaian";
require_once('template/header.php');

if (isset($_POST['tambah'])) :
	// var_dump($_POST);
	// return;
	$id_alternatif = $_POST['id_alternatif'];
	$id_kriteria = $_POST['id_kriteria'];
	$nilai = $_POST['nilai'];

	if (!$id_kriteria) {
		$errors[] = 'ID kriteria tidak boleh kosong';
	}
	if (!$id_alternatif) {
		$errors[] = 'ID Alternatif kriteria tidak boleh kosong';
	}
	if (!$nilai) {
		$errors[] = 'Nilai kriteria tidak boleh kosong';
	}

	if (empty($errors)) {
		try {
			$koneksi->beginTransaction();

			$i = 0;
			foreach ($nilai as $key) {
				$stmt = $koneksi->prepare("INSERT INTO penilaian (id_alternatif, id_kriteria, nilai) VALUES (:id_alternatif, :id_kriteria, :nilai)");
				$stmt->bindParam(':id_alternatif', $id_alternatif);
				$stmt->bindParam(':id_kriteria', $id_kriteria[$i]);
				$stmt->bindParam(':nilai', $key);
				$stmt->execute();
				$i++;
			}

			$koneksi->commit();

			$sts[] = 'Data berhasil disimpan';
		} catch (Exception $e) {
			$koneksi->rollBack();
			$sts[] = 'Data gagal disimpan: ' . $e->getMessage();
		}
	}
endif;

// if (isset($_POST['edit'])) {

// 	$id_alternatif = $_POST['id_alternatif'];
// 	$id_kriteria = $_POST['id_kriteria'];
// 	$nilai = $_POST['nilai'];
// 	$tahap2 = $_POST['tahap'];
// 	$tahun2 = $_POST['tahun'];

// 	if (!$id_kriteria) {
// 		$errors[] = 'ID kriteria tidak boleh kosong';
// 	}
// 	if (!$id_alternatif) {
// 		$errors[] = 'ID Alternatif kriteria tidak boleh kosong';
// 	}
// 	if (!$nilai) {
// 		$errors[] = 'Nilai kriteria tidak boleh kosong';
// 	}

// 	if (empty($errors)) {
// 		try {
// 			$koneksi->beginTransaction();

// 			$stmt = $koneksi->prepare("DELETE FROM penilaian WHERE tahap = :tahap AND tahun = :tahun AND id_alternatif = :id_alternatif");
// 			$stmt->bindParam(':tahap', $tahap);
// 			$stmt->bindParam(':tahun', $tahun);
// 			$stmt->bindParam(':id_alternatif', $id_alternatif);
// 			$stmt->execute();

// 			$i = 0;
// 			foreach ($nilai as $key) {
// 				$stmt = $koneksi->prepare("INSERT INTO penilaian (id_alternatif, id_kriteria, nilai) VALUES (:id_alternatif, :id_kriteria, :nilai)");
// 				$stmt->bindParam(':id_alternatif', $id_alternatif);
// 				$stmt->bindParam(':id_kriteria', $id_kriteria[$i]);
// 				$stmt->bindParam(':nilai', $key);
// 				$stmt->execute();
// 				$i++;
// 			}

// 			$stmt2 = $koneksi->prepare("UPDATE alternatif SET tahap = :tahap, tahun = :tahun WHERE id_alternatif = :id_alternatif");
// 			$stmt2->bindParam(':tahap', $tahap);
// 			$stmt2->bindParam(':tahun', $tahun);
// 			$stmt2->bindParam(':id_alternatif', $id_alternatif);
// 			$stmt2->execute();

// 			$koneksi->commit();

// 			$sts[] = 'Data berhasil diupdate';
// 		} catch (Exception $e) {
// 			$koneksi->rollBack();
// 			$sts[] = 'Data gagal diupdate: ' . $e->getMessage();
// 		}
// 	}
// }

if (isset($_POST['edit'])) {

	// var_dump($_POST);
	// echo json_encode($_POST);


	$id_alternatif = $_POST['id_alternatif'];
	$id_kriteria = $_POST['id_kriteria'];
	$nilai = $_POST['nilai'];
	$tahap2 = $_POST['tahap'];
	$tahun2 = $_POST['tahun'];

	$errors = [];
	if (!$id_kriteria) {
		$errors[] = 'ID kriteria tidak boleh kosong';
	}
	if (!$id_alternatif) {
		$errors[] = 'ID Alternatif tidak boleh kosong';
	}
	if (!$nilai) {
		$errors[] = 'Nilai kriteria tidak boleh kosong';
	}


	if (empty($errors)) {
		try {
			$koneksi->beginTransaction();

			$stmt = $koneksi->prepare("DELETE FROM penilaian WHERE tahap = :tahap AND tahun = :tahun AND id_alternatif = :id_alternatif");
			$stmt->bindParam(':tahap', $tahap2);
			$stmt->bindParam(':tahun', $tahun2);
			$stmt->bindParam(':id_alternatif', $id_alternatif);
			$stmt->execute();

			echo 'deleted';
			exit();

			$i = 0;
			foreach ($nilai as $key) {
				$stmt = $koneksi->prepare("INSERT INTO penilaian (id_alternatif, id_kriteria, nilai, tahap, tahun) VALUES (:id_alternatif, :id_kriteria, :nilai, :tahap, :tahun)");
				$stmt->bindParam(':id_alternatif', $id_alternatif);
				$stmt->bindParam(':id_kriteria', $id_kriteria[$i]);
				$stmt->bindParam(':nilai', $key);
				$stmt->bindParam(':tahap', $tahap2);
				$stmt->bindParam(':tahun', $tahun2);
				$stmt->execute();
				$i++;
			}

			$stmt2 = $koneksi->prepare("UPDATE alternatif SET tahap = :tahap, tahun = :tahun WHERE id_alternatif = :id_alternatif");
			$stmt2->bindParam(':tahap', $tahap2);
			$stmt2->bindParam(':tahun', $tahun2);
			$stmt2->bindParam(':id_alternatif', $id_alternatif);
			$stmt2->execute();

			$koneksi->commit();

			$sts[] = 'Data berhasil diupdate';
		} catch (Exception $e) {
			$koneksi->rollBack();
			$sts[] = 'Data gagal diupdate: ' . $e->getMessage();
		}
	} else {
		foreach ($errors as $error) {
			$sts[] = $error;
		}
	}
}



?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-edit"></i> Data Penilaian</h1>
</div>

<?php if (!empty($sts)) : ?>
	<div class="alert alert-info">
		<?php foreach ($sts as $st) : ?>
			<?php echo $st; ?>
		<?php endforeach; ?>
	</div>
<?php
endif;

$status = isset($_GET['status']) ? $_GET['status'] : '';
$msg = '';
switch ($status):
	case 'sukses-baru':
		$msg = 'Data berhasil disimpan';
		break;
	case 'sukses-hapus':
		$msg = 'Data behasil dihapus';
		break;
	case 'sukses-edit':
		$msg = 'Data behasil diupdate';
		break;
endswitch;
?>




<div class="card shadow mb-4">
	<!-- /.card-header -->
	<div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
		<h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Daftar Data Penilaian</h6>

		<a onclick="return confirm ('Apakah anda yakin untuk meghapus semua data ini?')" href="hapus-all-penilaian.php" class="btn btn-sm btn-danger"> <i class="fa fa-trash"></i> Hapus Semua Data </a>
	</div>

	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead class="bg-danger text-white">
					<tr align="center">
						<th width="5%">No</th>
						<th>Alternatif</th>
						<th>Tahap</th>
						<th>Tahun</th>
						<th width="15%">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$no = 1;
					$stmt = $koneksi->prepare("SELECT * FROM alternatif WHERE tahap = :tahap AND tahun = :tahun");
					$stmt->bindParam(':tahap', $tahap);
					$stmt->bindParam(':tahun', $tahun);
					$stmt->execute();
					$AllData = $stmt->fetchAll(PDO::FETCH_ASSOC);

					foreach ($AllData as $data) {
					?>
						<tr align="center">
							<td><?= $no ?></td>
							<td align="left"><?= htmlspecialchars($data['nama']) ?></td>
							<td align="left">
								<?= $data['tahap'] == 1 ? "Januari - Maret" : ""; ?>
								<?= $data['tahap'] == 2 ? "April - Juni" : ""; ?>
								<?= $data['tahap'] == 3 ? "Juli - September" : ""; ?>
								<?= $data['tahap'] == 4 ? "Oktober - Desember" : ""; ?>
							</td>
							<td align="left"><?= htmlspecialchars($data['tahun']) ?></td>

							<?php
							$id_alternatif = $data['id_alternatif'];
							$stmt_penilaian = $koneksi->prepare("SELECT * FROM penilaian WHERE id_alternatif = :id_alternatif");
							$stmt_penilaian->bindParam(':id_alternatif', $id_alternatif);
							$stmt_penilaian->execute();
							$cek_tombol = $stmt_penilaian->fetchAll(PDO::FETCH_ASSOC);
							?>
							<td>
								<?= $data['id_alternatif'] ?>
								<?php if ($cek_tombol == 0) { ?>
									<a data-toggle="modal" href="#set<?= htmlspecialchars($data['id_alternatif']) ?>" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Input</a>
								<?php } else { ?>
									<a data-toggle="modal" href="#edit<?= htmlspecialchars($data['id_alternatif']) ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a>
								<?php } ?>
							</td>
						</tr>


						<!-- Modal Edit -->
						<div class="modal fade" id="edit<?= $data['id_alternatif'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">

										<h5 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i> Edit Penilaian</h5>
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									</div>

									<form action="" method="post">
										<div class="modal-body">


											<div class="form-group mb-2">
												<label class="font-weight-bold" for="staticEmail2">Tahap</label>
												<select class="form-control" name="tahap">
													<option value="" <?= $data['tahap'] == 0 ? 'selected' : '' ?>> Pilih Tahap </option>
													<option value="1" <?= $data['tahap'] == 1 ? 'selected' : '' ?>>Januari - Maret</option>
													<option value="2" <?= $data['tahap'] == 2 ? 'selected' : '' ?>>April - Juni</option>
													<option value="3" <?= $data['tahap'] == 3 ? 'selected' : '' ?>>Juli - September</option>
													<option value="4" <?= $data['tahap'] == 4 ? 'selected' : '' ?>>Oktober - Desember</option>
												</select>
											</div>

											<div class="form-group mb-2">
												<label class="font-weight-bold" for="staticEmail2">Tahun</label>
												<select class="form-control" name="tahun">
													<option value=""> Pilih Tahun </option>
													<?php $tahun = date('Y');
													for ($i = 2023; $i < $tahun + 5; $i++) { ?>
														<option value="<?php echo $i ?>" <?= $data['tahun'] == $i ? 'selected' : '' ?>><?php echo $i ?></option>
													<?php } ?>
												</select>
											</div>
											<div class="form-group mb-2">
												<?php
												try {
													// First query to fetch data from 'periode'
													$stmt = $koneksi->query("SELECT * FROM periode");
													$periode = $stmt->fetch(PDO::FETCH_ASSOC);
													$tahap = $periode['tahap'];
													$tahun = $periode['tahun'];

													// Second query to fetch data from 'kriteria'
													$stmt2 = $koneksi->prepare("SELECT * FROM kriteria WHERE tahap = :tahap AND tahun = :tahun ORDER BY kode_kriteria ASC");
													$stmt2->execute([':tahap' => $tahap, ':tahun' => $tahun]);
													while ($d = $stmt2->fetch(PDO::FETCH_ASSOC)) {
														$id_kriteria = $d['id_kriteria'];
														// Assuming $data['id_alternatif'] is already defined in your context
												?>
														<input type="text" name="id_alternatif" value="<?= $data['id_alternatif'] ?>" hidden>
														<input type="text" name="id_kriteria[]" value="<?= $d['id_kriteria'] ?>" hidden>
														<div class="form-group">
															<label class="font-weight-bold">(<?= $d['kode_kriteria'] ?>) <?= $d['nama'] ?></label>
															<select name="nilai[]" class="form-control" required>
																<option value="">--Pilih--</option>
																<?php
																// Query to fetch data from 'sub_kriteria'
																$stmt3 = $koneksi->prepare("SELECT * FROM sub_kriteria WHERE tahap = :tahap AND tahun = :tahun AND id_kriteria = :id_kriteria ORDER BY nilai ASC");
																$stmt3->execute([
																	':tahap' => $tahap,
																	':tahun' => $tahun,
																	':id_kriteria' => $id_kriteria
																]);
																while ($d3 = $stmt3->fetch(PDO::FETCH_ASSOC)) {
																?>
																

																	<option value="<?= $d3['id_sub_kriteria'] ?>" <?php if (!empty($d4['nilai']) && $d3['id_sub_kriteria'] == $d4['nilai']) {
																														echo "selected";
																													} ?>><?= $d3['nama'] ?></option>
																<?php
																}
																?>
															</select>
														</div>
												<?php
													}
												} catch (PDOException $e) {
													// Handle any errors
													echo "Connection failed: " . $e->getMessage();
												}
												?>

											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
												<button type="submit" name="edit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
											</div>
									</form>
								</div>
							</div>
						</div>
					<?php
						$no++;
					}
					?>

		</div>



		</tbody>
		</table>
	</div>
</div>
</div>

<?php
require_once('template/footer.php');
?>