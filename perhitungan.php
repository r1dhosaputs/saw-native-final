<?php
require_once('includes/init.php');

$user_role = get_role();
if ($user_role == 'admin') {

	$page = "Perhitungan";
	require_once('template/header.php');

	// mysqli_query($koneksi,"TRUNCATE TABLE hasil;");

	// $kriterias = mysqli_query($koneksi,"SELECT * FROM kriteria WHERE tahap = '$tahap' AND tahun = '$tahun'");
	// $alternatifs = mysqli_query($koneksi,"SELECT * FROM alternatif WHERE tahap = '$tahap' AND tahun = '$tahun'");


	// // Gunakan kondisional WHERE dalam kueri SQL
	// if (!empty($_GET['tahap']) && !empty($_GET['tahun'])) {
	//     // Jika keduanya diisi
	//     $query = "SELECT * FROM alternatif";
	//     $query .= " WHERE tahap = '$_GET[tahap]' AND tahun = '$_GET[tahun]'";
	//     $alternatifs = mysqli_query($koneksi, $query);
	// } elseif (!empty($_GET['tahap'])) {
	//     // Jika hanya tahap yang diisi
	//     $query = "SELECT * FROM alternatif";
	//     $query .= " WHERE tahap = '$_GET[tahap]'";
	//     $alternatifs = mysqli_query($koneksi, $query);
	// } elseif (!empty($_GET['tahun'])) {
	//     // Jika hanya tahun yang diisi
	//     $query = "SELECT * FROM alternatif";
	//     $query .= " WHERE tahun = '$_GET[tahun]'";
	//     $alternatifs = mysqli_query($koneksi, $query);
	// }

	try {
		// Mengosongkan tabel hasil
		$koneksi->exec("DELETE FROM hasil");

		// Mengambil data kriteria
		$stmt_kriteria = $koneksi->prepare("SELECT * FROM kriteria WHERE tahap = :tahap AND tahun = :tahun");
		$stmt_kriteria->bindParam(':tahap', $tahap, PDO::PARAM_INT);
		$stmt_kriteria->bindParam(':tahun', $tahun, PDO::PARAM_INT);
		$stmt_kriteria->execute();
		$kriterias = $stmt_kriteria->fetchAll(PDO::FETCH_ASSOC);

		// Menangani parameter query string
		$query = "SELECT * FROM alternatif";
		$conditions = [];
		$params = [];

		if (!empty($_GET['tahap'])) {
			$conditions[] = "tahap = :tahap";
			$params[':tahap'] = $_GET['tahap'];
		}

		if (!empty($_GET['tahun'])) {
			$conditions[] = "tahun = :tahun";
			$params[':tahun'] = $_GET['tahun'];
		}

		if (!empty($conditions)) {
			$query .= " WHERE " . implode(" AND ", $conditions);
		}

		$stmt_alternatif = $koneksi->prepare($query);
		foreach ($params as $key => &$val) {
			$stmt_alternatif->bindParam($key, $val);
		}
		$stmt_alternatif->execute();
		$alternatifs = $stmt_alternatif->fetchAll(PDO::FETCH_ASSOC);

		// Pastikan untuk menangani $tahap dan $tahun di tempat lain dalam skrip ini atau meneruskan nilainya dari parameter query string
		$tahap = isset($_GET['tahap']) ? $_GET['tahap'] : null;
		$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : null;
	} catch (PDOException $e) {
		echo "Error: " . $e->getMessage();
	}


	//Matrix Keputusan (X)
	$matriks_x = array();
	foreach ($kriterias as $kriteria) :
		foreach ($alternatifs as $alternatif) :

			$id_alternatif = $alternatif['id_alternatif'];
			$id_kriteria = $kriteria['id_kriteria'];
			$q4 = mysqli_query($koneksi, "SELECT sub_kriteria.nilai FROM penilaian JOIN sub_kriteria WHERE penilaian.nilai=sub_kriteria.id_sub_kriteria AND penilaian.id_alternatif='$id_alternatif' AND penilaian.id_kriteria='$id_kriteria' ");
			$data = mysqli_fetch_array($q4);
			if (!empty($data['nilai'])) {
				$nilai = $data['nilai'];
			} else {
				$nilai = 0;
			}

			$matriks_x[$id_kriteria][$id_alternatif] = $nilai;
		endforeach;
	endforeach;

	// $mtariks_r = array();
	// foreach ($kriterias as $kriteria) :
	// 	foreach ($alternatifs as $alternatif) :
	// 		$id_alternatif = $alternatif['id_alternatif'];
	// 		$id_kriteria = $kriteria['id_kriteria'];
	// 		$nilai = $matriks_x[$id_kriteria][$id_alternatif];
	// 		$type_kriteria = $kriteria['type'];

	// 		$nilai_max = @(max($matriks_x[$id_kriteria]));
	// 		$nilai_min = @(min($matriks_x[$id_kriteria]));

	// 		if ($type_kriteria == 'Benefit') :
	// 			$r = $nilai / $nilai_max;
	// 		elseif ($type_kriteria == 'Cost') :
	// 			$r = $nilai_min / $nilai;
	// 		endif;

	// 		if (is_nan($r)) {
	// 			$r = 0;
	// 		}

	// 		$mtariks_r[$id_kriteria][$id_alternatif] = $r;
	// 	endforeach;
	// endforeach;
	$matriks_x = array();

	try {
		foreach ($kriterias as $kriteria) {
			foreach ($alternatifs as $alternatif) {

				$id_alternatif = $alternatif['id_alternatif'];
				$id_kriteria = $kriteria['id_kriteria'];

				$stmt = $koneksi->prepare("
                SELECT sub_kriteria.nilai 
                FROM penilaian 
                JOIN sub_kriteria 
                ON penilaian.nilai = sub_kriteria.id_sub_kriteria 
                WHERE penilaian.id_alternatif = :id_alternatif 
                AND penilaian.id_kriteria = :id_kriteria
            ");
				$stmt->bindParam(':id_alternatif', $id_alternatif);
				$stmt->bindParam(':id_kriteria', $id_kriteria);
				$stmt->execute();
				$data = $stmt->fetch(PDO::FETCH_ASSOC);

				if (!empty($data['nilai'])) {
					$nilai = $data['nilai'];
				} else {
					$nilai = 0;
				}

				$matriks_x[$id_kriteria][$id_alternatif] = $nilai;
			}
		}
	} catch (PDOException $e) {
		echo "Error: " . $e->getMessage();
	}


	$mtariks_rb = array();
	$total_nilai = array();
	foreach ($alternatifs as $alternatif) :
		$id_alternatif = $alternatif['id_alternatif'];
		$t = 0;
		foreach ($kriterias as $kriteria) :
			$id_kriteria = $kriteria['id_kriteria'];

			$r = $mtariks_r[$id_kriteria][$id_alternatif];
			$bobot = $kriteria['bobot'];
			$k = $r * $bobot;

			$mtariks_rb[$id_kriteria][$id_alternatif] = $k;
			$t += $k;
		endforeach;
		$total_nilai[$id_alternatif] = $t;
	endforeach;
?>

	<div class="card mb-3">
		<div class="card-header bg-danger text-white">
			Filter Data Perhitungan
		</div>

		<div class="card-body">
			<form class="form-inline">
				<div class="form-group mb-2">
					<label for="staticEmail2">Tahap</label>
					<select class="form-control ml-3" name="tahap">
						<option value=""> Pilih Tahap </option>
						<option value="1">Januari - Maret</option>
						<option value="2">April - Juni</option>
						<option value="3">Juli - September</option>
						<option value="4">Oktober - Desember</option>
					</select>
				</div>
				<div class="form-group mb-2 ml-5">
					<label for="staticEmail2">Tahun</label>
					<select class="form-control ml-3" name="tahun">
						<option value=""> Pilih Tahun </option>
						<?php $tahun = date('Y');
						for ($i = 2023; $i < $tahun + 5; $i++) { ?>
							<option value="<?php echo $i ?>"><?php echo $i ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group mb-2 ml-5">
					<label for="staticEmail2" class="mr-2">Kouta Penerima PKH </label>
					<input type="number" name="kouta" class="form-control">
				</div>

				<button type="submit" class="btn btn-primary mb-2 ml-auto" name="proses"><i class="fas fa-eye"></i> Proses</button>
			</form>
		</div>
	</div>


	<?php if (isset($_GET['tahap']) && isset($_GET['tahun'])) : ?>
		<div class="alert alert-info">
			Menampilkan Data Perhitungan Tahap: <span class="font-weight-bold"><?php echo $_GET['tahap'] ?></span> Tahun: <span class="font-weight-bold"><?php echo $_GET['tahun'] ?></span>
		</div>
	<?php endif; ?>



	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-calculator"></i> Data Perhitungan</h1>
	</div>


	<div class="card shadow mb-4">
		<!-- /.card-header -->
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Matrix Keputusan (X)</h6>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" width="100%" cellspacing="0">
					<thead class="bg-danger text-white">
						<tr align="center">
							<th width="5%" rowspan="2">No</th>
							<th>Nama Alternatif</th>
							<?php foreach ($kriterias as $kriteria) : ?>
								<th><?= $kriteria['kode_kriteria'] ?></th>
							<?php endforeach ?>
						</tr>
					</thead>
					<tbody>
						<?php if (isset($_GET['proses'])) : ?>
							<?php
							$no = 1;
							foreach ($alternatifs as $alternatif) : ?>
								<tr align="center">
									<td><?= $no; ?></td>
									<td align="left"><?= $alternatif['nama'] ?></td>
									<?php
									foreach ($kriterias as $kriteria) :
										$id_alternatif = $alternatif['id_alternatif'];
										$id_kriteria = $kriteria['id_kriteria'];
										echo '<td>';
										echo $matriks_x[$id_kriteria][$id_alternatif];
										echo '</td>';
									endforeach
									?>
								</tr>
							<?php
								$no++;
							endforeach
							?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="card shadow mb-4">
		<!-- /.card-header -->
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Matriks Ternormalisasi (R)</h6>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" width="100%" cellspacing="0">
					<thead class="bg-danger text-white">
						<tr align="center">
							<th width="5%" rowspan="2">No</th>
							<th>Nama Alternatif</th>
							<?php foreach ($kriterias as $kriteria) : ?>
								<th><?= $kriteria['kode_kriteria'] ?></th>
							<?php endforeach ?>
						</tr>
					</thead>
					<tbody>
						<?php if (isset($_GET['proses'])) : ?>
							<?php
							$no = 1;
							foreach ($alternatifs as $alternatif) : ?>
								<tr align="center">
									<td><?= $no; ?></td>
									<td align="left"><?= $alternatif['nama'] ?></td>
									<?php
									foreach ($kriterias as $kriteria) :
										$id_alternatif = $alternatif['id_alternatif'];
										$id_kriteria = $kriteria['id_kriteria'];
										echo '<td>';
										echo $mtariks_r[$id_kriteria][$id_alternatif];
										echo '</td>';
									endforeach;
									?>
								</tr>
							<?php
								$no++;
							endforeach
							?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="card shadow mb-4">
		<!-- /.card-header -->
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Bobot Preferensi (W)</h6>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" width="100%" cellspacing="0">
					<thead class="bg-danger text-white">
						<tr align="center">
							<?php foreach ($kriterias as $key) : ?>
								<th><?= $key['kode_kriteria'] ?> (<?= $key['type'] ?>)</th>
							<?php endforeach ?>
						</tr>
					</thead>
					<tbody>
						<?php if (isset($_GET['proses'])) : ?>
							<?php if (isset($alternatifs) && count($alternatifs) > 0) : ?>
								<tr align="center">
									<?php foreach ($kriterias as $key) : ?>
										<td>
											<?php echo $key['bobot']; ?>
										</td>
									<?php endforeach; ?>
								</tr>
							<?php endif; ?>
						<?php endif; ?>

					</tbody>
				</table>
			</div>
		</div>
	</div>


	<div class="card shadow mb-4">
		<!-- /.card-header -->
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Menghitung Nilai Akhir (Vi)</h6>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" width="100%" cellspacing="0">
					<thead class="bg-danger text-white">
						<tr align="center">
							<th width="5%" rowspan="2">No</th>
							<th>Nama Alternatif</th>
							<th>Perhitungan</th>
							<th>Total Nilai</th>
						</tr>
					</thead>
					<tbody>
						<?php if (isset($_GET['proses'])) : ?>
							<?php
							$no = 1;
							foreach ($alternatifs as $alternatif) : ?>
								<tr align="center">
									<td><?= $no; ?></td>
									<td align="left"><?= $alternatif['nama'] ?></td>
									<td>SUM
										<?php
										foreach ($kriterias as $kriteria) :
											$id_alternatif = $alternatif['id_alternatif'];
											$id_kriteria = $kriteria['id_kriteria'];
											echo "(" . $kriteria['bobot'] . "x" . $mtariks_r[$id_kriteria][$id_alternatif] . ")";
										endforeach;
										?>
									</td>
									<td><?= $total_nilai[$id_alternatif] ?></td>
								</tr>
							<?php
								$no++;
								$stmt = $koneksi->prepare("INSERT INTO hasil (id_alternatif, nilai) VALUES (:id_alternatif, :nilai)");
								$stmt->bindParam(':id_alternatif', $alternatif['id_alternatif']);
								$stmt->bindParam(':nilai', $total_nilai[$id_alternatif]);
								$stmt->execute();
							endforeach;
							?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

<?php
	require_once('template/footer.php');
} else {
	header('Location: login.php');
}
?>