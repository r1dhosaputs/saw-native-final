<?php
require_once('includes/init.php');

$user_role = get_role();
if ($user_role == 'admin' || $user_role == 'user') {
?>

	<html>

	<head>
		<title>Sistem Pendukung Keputusan Metode SAW</title>
	</head>

	<body onload="window.print();">

		<div style="width:100%;margin:0 auto;text-align:center;">
			<img src="assets\img\logo.png" width="70px" style="display: inline; vertical-align: middle;">
			<h3 style="display: inline-block; vertical-align: middle; margin-left: 10px;">Program Keluarga Harapan (PKH) <br> Kelurahan Kemuning <br> <span style="font-size: 12px!important; margin-top: 1px;">Jl. Kartini RT. 021 RW. 005, Banjarbaru</span></h4>
				<hr style="border-width: 3px; border-color: black!important; border-style: solid;">
				<h3 style="display: inline-block; vertical-align: middle; margin-left: 10px;">Laporan Penerima Bantuan Program Keluarga Harapan (PKH)</h4>
		</div>
		<br />
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
		<h4 style="text-align: left!important;">Tahap : <?= $tahap ?></h4>
		<h4 style="text-align: left!important;">Tahun : <?= $_GET['tahun'] ?></h4>
		<table width="100%" cellspacing="0" cellpadding="5" border="1">
			<thead>
				<tr align="center">
					<th>No</th>
					<th>Nama Alternatif</th>
					<th>No Peserta</th>
					<th>NIK Pengurus</th>
					<th>ID Keluarga DTKS</th>
					<th>Alamat</th>
					<th>Nilai Vi</th>
					<th width="15%">Rank</th>
					<th>Keterangan</th>
				</tr>
			</thead>
			<tbody>
				<?php
				// $no=0;
				// $query = mysqli_query($koneksi,"SELECT * FROM hasil JOIN alternatif ON hasil.id_alternatif=alternatif.id_alternatif ORDER BY hasil.nilai DESC");
				// while($data = mysqli_fetch_array($query)){
				// 	$no++;
				$query = "SELECT * FROM hasil JOIN alternatif ON hasil.id_alternatif = alternatif.id_alternatif ORDER BY hasil.nilai DESC";
				$stmt = $koneksi->query($query);

				$no = 0;
				while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
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

		<table width="100%">
			<tr>
				<td></td>
				<td width="200px">
					<p style="margin-top: 1cm;">Banjarbaru, <?php echo date("d M Y") ?> <br> Kepala Kelurahan Kemuning</p>
					<div style="margin-top: 3cm;">
						DONY FAJAR SAPUTRA, S. STP., M.Si
					</div>

					</div>

				</td>
			</tr>
		</table>
	</body>

	</body>

	</html>

<?php
} else {
	header('Location: login.php');
}
?>