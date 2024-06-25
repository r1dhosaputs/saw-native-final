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
			<img src="assets\img\logo.png" width="70px" style="display: inline; vertical-align: middle;">
			<h3 style="display: inline-block; vertical-align: middle; margin-left: 10px;">Program Keluarga Harapan (PKH) <br> Kelurahan Kemuning <br> <span style="font-size: 12px!important; margin-top: 1px;">Jl. Kartini RT. 021 RW. 005, Banjarbaru</span></h4>
				<hr style="border-width: 3px; border-color: black!important; border-style: solid;">
				<h3 style="display: inline-block; vertical-align: middle; margin-left: 10px;">Data Peserta</h4>
		</div>

		<table width="100%" cellspacing="0" cellpadding="5" border="1">
			<thead>
				<tr align="center">
					<th>No</th>
					<th>Nama</th>
					<th>No Peserta</th>
					<th>NIK Pengurus</th>
					<th>ID Keluarga DTKS</th>
					<th>Alamat</th>
				</tr>
			</thead>
			<tbody>
				<?php
				// $no=0;
				// $query = mysqli_query($koneksi,"SELECT * FROM alternatif");			
				// while($data = mysqli_fetch_array($query)):
				// $no++;
				$no = 0;
				$query = "SELECT * FROM alternatif";
				$stmt = $koneksi->query($query);

				while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) :
					$no++;
				?>
					<tr align="center">
						<td><?php echo $no; ?></td>
						<td align="left"><?php echo $data['nama']; ?></td>
						<td><?php echo $data['no_peserta']; ?></td>
						<td><?php echo $data['nik_pengurus']; ?></td>
						<td><?php echo $data['id_keluarga_dtks']; ?></td>
						<td><?php echo $data['alamat']; ?></td>
					</tr>
				<?php endwhile; ?>
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