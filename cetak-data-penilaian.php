<?php
require_once('includes/init.php');

$user_role = get_role();
if ($user_role == 'admin') {
	try {
		// Query untuk mengambil data kriteria dan alternatif
		$stmt_kriteria = $koneksi->query("SELECT * FROM kriteria");
		$stmt_alternatif = $koneksi->query("SELECT * FROM alternatif");

		// Fetch semua data kriteria dan alternatif
		$kriterias = $stmt_kriteria->fetchAll(PDO::FETCH_ASSOC);
		$alternatifs = $stmt_alternatif->fetchAll(PDO::FETCH_ASSOC);

		// Inisialisasi array untuk penyimpanan penilaian
		$penilaian = array();

		// Loop untuk mengambil data penilaian
		foreach ($kriterias as $kriteria) {
			foreach ($alternatifs as $alternatif) {
				$id_alternatif = $alternatif['id_alternatif'];
				$id_kriteria = $kriteria['id_kriteria'];

				// Query untuk mendapatkan nama sub kriteria
				$stmt_penilaian = $koneksi->prepare("SELECT sub_kriteria.nama 
                                                     FROM penilaian 
                                                     JOIN sub_kriteria 
                                                     ON penilaian.nilai = sub_kriteria.id_sub_kriteria 
                                                     WHERE penilaian.id_alternatif = :id_alternatif 
                                                     AND penilaian.id_kriteria = :id_kriteria");
				$stmt_penilaian->bindParam(':id_alternatif', $id_alternatif, PDO::PARAM_INT);
				$stmt_penilaian->bindParam(':id_kriteria', $id_kriteria, PDO::PARAM_INT);
				$stmt_penilaian->execute();

				// Ambil hasil query
				$data = $stmt_penilaian->fetch(PDO::FETCH_ASSOC);

				// Simpan nilai atau 0 jika tidak ada data
				$p = !empty($data['nama']) ? $data['nama'] : 0;

				// Simpan nilai dalam array penilaian
				$penilaian[$id_kriteria][$id_alternatif] = $p;
			}
		}

		// Tutup koneksi SQLite
		$koneksi = null;

		// Output HTML untuk tampilan cetak
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
				<h3 style="display: inline-block; vertical-align: middle; margin-left: 10px;">Data Penilaian</h3>
			</div>

			<table border="1" width="100%" cellspacing="0">
				<thead>
					<tr align="center">
						<th width="5%" rowspan="2">No</th>
						<th>Nama Alternatif</th>
						<?php foreach ($kriterias as $kriteria) : ?>
							<th><?= $kriteria['nama'] ?></th>
						<?php endforeach ?>
					</tr>
				</thead>
				<tbody>
					<?php
					$no = 1;
					foreach ($alternatifs as $alternatif) : ?>
						<tr align="center">
							<td><?= $no; ?></td>
							<td align="left"><?= $alternatif['nama'] ?></td>
							<?php foreach ($kriterias as $kriteria) :
								$id_alternatif = $alternatif['id_alternatif'];
								$id_kriteria = $kriteria['id_kriteria'];
							?>
								<td><?= isset($penilaian[$id_kriteria][$id_alternatif]) ? $penilaian[$id_kriteria][$id_alternatif] : '' ?></td>
							<?php endforeach ?>
						</tr>
					<?php
						$no++;
					endforeach
					?>
				</tbody>
			</table>

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

	} catch (PDOException $e) {
		echo "Koneksi database gagal: " . $e->getMessage();
		die();
	}
} else {
	header('Location: login.php');
}
?>