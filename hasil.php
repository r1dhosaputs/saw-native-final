<?php
require_once('includes/init.php');

// hasillll
$user_role = get_role();
if ($user_role == 'admin' || $user_role == 'user') {

	$page = "Hasil";
	require_once('template/header.php');
?>

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-chart-area"></i> Data Hasil Akhir</h1>

		<a href="cetak.php?kouta=<?= isset($_GET['kouta']) ? $_GET['kouta'] : '' ?>&tahap=<?= isset($_GET['tahap']) ? $_GET['tahap'] : '' ?>&tahun=<?= isset($_GET['tahun']) ? $_GET['tahun'] : '' ?>" target="_blank" class="btn btn-primary"> <i class="fa fa-print"></i> Cetak Data </a>
	</div>

	<div class="card shadow mb-4">
		<!-- /.card-header -->
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Hasil Akhir Perankingan</h6>
			<?php
			if ($tahap = $_GET['tahap'] == 1) {
				$tahap = $_GET['tahap'] = "Januari - Maret";
			} else if ($tahap = $_GET['tahap'] == 2) {
				$tahap = $_GET['tahap'] = "April - Juni";
			} else if ($tahap = $_GET['tahap'] == 3) {
				$tahap = $_GET['tahap'] = "Juli - September";
			} else {
				$tahap = $_GET['tahap'] = "Oktober - Desember";
			}
			?>
			<br>
			Tahap : <?= $tahap  ?>
			Tahun : <?= $_GET['tahun']  ?>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" width="100%" cellspacing="0">
					<thead class="bg-danger text-white">
						<tr align="center">
							<th width="5%">No</th>
							<th>Nama Alternatif</th>
							<th>No Peserta</th>
							<th>NIK Pengurus</th>
							<th>ID Keluarga DTKS</th>
							<th>Alamat</th>
							<th>Nilai Vi</th>
							<th width="15%">Rank</th>
							<th>Keterangan</th>
					</thead>
					<tbody>
						<?php
						$no = 0;
						$stmt = $koneksi->prepare("SELECT * FROM hasil JOIN alternatif ON hasil.id_alternatif = alternatif.id_alternatif ORDER BY hasil.nilai DESC");
						$stmt->execute();
						$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

						foreach ($results as $data) {
							$no++;
						?>
							<tr align="center">
								<td><?php echo $no; ?></td>
								<td align="left"><?= $data['nama'] ?></td>
								<td><?php echo $data['no_peserta']; ?></td>
								<td><?php echo $data['nik_pengurus']; ?></td>
								<td><?php echo $data['id_keluarga_dtks']; ?></td>
								<td><?php echo $data['alamat']; ?></td>
								<td><?= number_format($data['nilai'], 4) ?></td>
								<td><?= $no; ?></td>
								<td><?php if ($no <= $_GET['kouta']) {
										echo "Layak";
									} else {
										echo "Tidak Layak";
									} ?></td>
							</tr>
						<?php
						}
						?>


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