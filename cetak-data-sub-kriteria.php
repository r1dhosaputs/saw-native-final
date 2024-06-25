<?php
require_once('includes/init.php');

$user_role = get_role();
if ($user_role == 'admin') {
?>

	<html>

	<head>
		<title>Sistem Pendukung Keputusan Metode SAW</title>
	</head>

	<body onload="window.print();">

		<div style="width:100%;margin:0 auto;text-align:center;">
			<img src="assets/img/logo.png" width="70px" style="display: inline; vertical-align: middle;">
			<h3 style="display: inline-block; vertical-align: middle; margin-left: 10px;">Program Keluarga Harapan (PKH) <br> Kelurahan Kemuning <br> <span style="font-size: 12px!important; margin-top: 1px;">Jl. Kartini RT. 021 RW. 005, Banjarbaru</span></h3>
			<hr style="border-width: 3px; border-color: black!important; border-style: solid;">
			<h3 style="display: inline-block; vertical-align: middle; margin-left: 10px;">Data Sub Kriteria</h3>
		</div>

		<?php
		try {
			$query_kriteria = "SELECT * FROM kriteria ORDER BY kode_kriteria ASC";
			$stmt_kriteria = $koneksi->query($query_kriteria);

			while ($data = $stmt_kriteria->fetch(PDO::FETCH_ASSOC)) {
		?>
				<h3><?= $data['nama'] . " (" . $data['kode_kriteria'] . ")" ?></h3>
				<table border="1" width="100%" cellspacing="0">
					<thead class="bg-danger text-white">
						<tr align="center">
							<th width="5%">No</th>
							<th>Nama Sub Kriteria</th>
							<th width="15%">Nilai</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						$id_kriteria = $data['id_kriteria'];

						// Query untuk mendapatkan data sub_kriteria dari SQLite
						$query_sub_kriteria = "SELECT * FROM sub_kriteria WHERE id_kriteria = :id_kriteria ORDER BY nilai DESC";
						$stmt_sub_kriteria = $koneksi->prepare($query_sub_kriteria);
						$stmt_sub_kriteria->bindParam(':id_kriteria', $id_kriteria, PDO::PARAM_INT);
						$stmt_sub_kriteria->execute();

						while ($d = $stmt_sub_kriteria->fetch(PDO::FETCH_ASSOC)) {
						?>
							<tr align="center">
								<td><?= $no ?></td>
								<td align="left"><?= $d['nama'] ?></td>
								<td><?= $d['nilai'] ?></td>
							</tr>
						<?php
							$no++;
						}
						?>
					</tbody>
				</table>
				<br />
		<?php
			}

			// Tutup koneksi SQLite
			$koneksi = null;
		} catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
		?>

		<table width="100%">
			<tr>
				<td></td>
				<td width="200px">
					<p style="margin-top: 1cm;">Banjarbaru, <?php echo date("d M Y") ?> <br> Kepala Kelurahan Kemuning</p>
					<div style="margin-top: 3cm;">
						DONY FAJAR SAPUTRA, S. STP., M.Si
					</div>
				</td>
			</tr>
		</table>

	</body>

	</html>

<?php
} else {
	header('Location: login.php');
}
?>