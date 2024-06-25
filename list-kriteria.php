<?php
require_once('includes/init.php');
cek_login($role = array(1));
$page = "Kriteria";
require_once('template/header.php');
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-cube"></i> Data Kriteria</h1>

	<a href="tambah-kriteria.php" class="btn btn-success"> <i class="fa fa-plus"></i> Tambah Data </a>
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
		<h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Daftar Data Kriteria</h6>

		<a onclick="return confirm ('Apakah anda yakin untuk meghapus semua data ini?')" href="hapus-all-kriteria.php" class="btn btn-sm btn-danger"> <i class="fa fa-trash"></i> Hapus Semua Data </a>
	</div>

	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead class="bg-danger text-white">
					<tr align="center">
						<th>No</th>
						<th>Kode Kriteria</th>
						<th>Nama Kriteria</th>
						<th>Type</th>
						<th>Bobot</th>
						<th width="15%">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$no = 1;

					try {
						// Menggunakan PDO untuk menyiapkan dan mengeksekusi query SELECT
						$stmt = $koneksi->prepare("SELECT * FROM kriteria WHERE tahap = :tahap AND tahun = :tahun ORDER BY kode_kriteria ASC");
						$stmt->bindParam(':tahap', $tahap);
						$stmt->bindParam(':tahun', $tahun);
						$stmt->execute();

						// Mengambil data menggunakan fetchAll untuk mengambil semua baris sekaligus
						$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
						// var_dump($rows);
						foreach ($rows as $data) {
					?>
							<tr align="center">
								<td><?php echo $no; ?></td>
								<td><?php echo htmlspecialchars($data['kode_kriteria']); ?></td>
								<td align="left"><?php echo htmlspecialchars($data['nama']); ?></td>
								<td><?php echo htmlspecialchars($data['type']); ?></td>
								<td><?php echo htmlspecialchars($data['bobot']); ?></td>
								<td>
									<div class="btn-group" role="group">
										<a data-toggle="tooltip" data-placement="bottom" title="Edit Data" href="edit-kriteria.php?id=<?php echo $data['id_kriteria']; ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
										<a data-toggle="tooltip" data-placement="bottom" title="Hapus Data" href="hapus-kriteria.php?id=<?php echo $data['id_kriteria']; ?>" onclick="return confirm('Apakah anda yakin untuk menghapus data ini')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
									</div>
								</td>
							</tr>
					<?php
							$no++;
						}
					} catch (PDOException $e) {
						// Menangani kesalahan PDO
						echo 'Terjadi kesalahan: ' . $e->getMessage();
					}
					?>

				</tbody>
			</table>
		</div>
	</div>
</div>

<?php
require_once('template/footer.php');
?>