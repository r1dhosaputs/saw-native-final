<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$page = "Alternatif";
require_once('template/header.php');

?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-users"></i> Data Peserta</h1>

	<a href="tambah-alternatif.php" class="btn btn-success"> <i class="fa fa-plus"></i> Tambah Data </a>
</div>

<?php
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

if ($msg) :
	echo '<div class="alert alert-info">' . $msg . '</div>';
endif;
?>

<div class="card shadow mb-4">
	<!-- /.card-header -->
	<div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
		<h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Daftar Data Peserta</h6>

		<a onclick="return confirm ('Apakah anda yakin untuk meghapus semua data ini?')" href="hapus-all-peserta.php" class="btn btn-sm btn-danger"> <i class="fa fa-trash"></i> Hapus Semua Data </a>
	</div>

	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead class="bg-danger text-white">
					<tr align="center">
						<th width="5%">No</th>
						<th>Nama</th>
						<th>No Peserta</th>
						<th>NIK Pengurus</th>
						<th>ID Keluarga DTKS</th>
						<th>Alamat</th>
						<th width="15%">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$no = 0;
					$stmt = $koneksi->prepare("SELECT * FROM alternatif WHERE tahap = :tahap AND tahun = :tahun");
					$stmt->bindParam(':tahap', $tahap);
					$stmt->bindParam(':tahun', $tahun);
					$stmt->execute();
					$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

					foreach ($data as $row) :
						$no++;
					?><tr align="center">
							<td><?php echo $no; ?></td>
							<td align="left"><?php echo htmlspecialchars($row['nama']); ?></td>
							<td><?= htmlspecialchars($row['no_peserta']); ?></td>
							<td><?= htmlspecialchars($row['nik_pengurus']); ?></td>
							<td><?= htmlspecialchars($row['id_keluarga_dtks']); ?></td>
							<td><?= htmlspecialchars($row['alamat']); ?></td>
							<td>
								<div class="btn-group" role="group">
									<a data-toggle="tooltip" data-placement="bottom" title="Edit Data" href="edit-alternatif.php?id=<?= htmlspecialchars($row['id_alternatif']); ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
									<a data-toggle="tooltip" data-placement="bottom" title="Hapus Data" href="hapus-alternatif.php?id=<?php echo htmlspecialchars($row['id_alternatif']); ?>" onclick="return confirm('Apakah anda yakin untuk meghapus data ini')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php
require_once('template/footer.php');
?>