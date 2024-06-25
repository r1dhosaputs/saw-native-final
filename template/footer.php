	</div>
	</div>
	</div>

	<a class="scroll-to-top rounded" href="#page-top">
	  <i class="fas fa-angle-up"></i>
	</a>

	<!-- Logout Modal-->
	<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
	        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">×</span>
	        </button>
	      </div>
	      <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
	      <div class="modal-footer">
	        <button class="btn btn-warning" type="button" data-dismiss="modal"><i class="fas fa-fw fa-times mr-1"></i>Cancel</button>
	        <a class="btn btn-danger" href="logout.php"><i class="fas fa-fw fa-sign-out-alt mr-1"></i>Logout</a>
	      </div>
	    </div>
	  </div>
	</div>

	<?php

  if (isset($_POST['simpan-periode'])) {
    // Ambil nilai dari form
    $tahap = $_POST['tahap'];
    $tahun = $_POST['tahun'];

    if ($tahun != 0 || $tahap != 0) {
      // Siapkan query
      $query = "UPDATE periode SET tahap = :tahap, tahun = :tahun";

      try {
        // Eksekusi query
        $stmt = $koneksi->prepare($query);
        $stmt->bindParam(':tahap', $tahap);
        $stmt->bindParam(':tahun', $tahun);
        $result = $stmt->execute();

        // Periksa apakah query berhasil dieksekusi
        if ($result) {
          // Jika berhasil, tampilkan SweetAlert untuk memberikan notifikasi
          echo '<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>';
          echo '<script>';
          echo 'swal("Sukses!", "Data berhasil diperbarui", "success").then(function() { window.location = "halaman_berikutnya.php"; });';
          echo '</script>';
        } else {
          // Jika gagal, tampilkan SweetAlert untuk memberikan notifikasi
          echo '<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>';
          echo '<script>';
          echo 'swal("Gagal!", "Gagal memperbarui data", "error");';
          echo '</script>';
        }
      } catch (PDOException $e) {
        echo "Kesalahan pada query: " . $e->getMessage();
        echo '<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>';
        echo '<script>';
        echo 'swal("Gagal!", "Terjadi kesalahan pada query", "error");';
        echo '</script>';
      }
    } else {
      echo '<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>';
      echo '<script>';
      echo 'swal("Gagal!", "Nilai tahap atau tahun tidak boleh nol", "error");';
      echo '</script>';
    }
  }
  ?>

	<div class="modal fade" id="periode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Ubah / Cari Periode PKH</h5>
	        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">×</span>
	        </button>
	      </div>


	      <div class="modal-body">
	        <?php
          // $query = mysqli_query($koneksi, "SELECT * FROM periode");
          // $data = mysqli_fetch_array($query);
          $query = "SELECT * FROM periode";
          $stmt = $koneksi->query($query);
          $data = $stmt->fetch(PDO::FETCH_ASSOC);
          ?>
	        <form method="POST" action="">
	          <div class="form-group">
	            <label for="tahap" class="">Tahap</label>
	            <select class="form-control" name="tahap">
	              <option value="0" <?= $data['tahap'] == 0 ? 'selected' : ''  ?>> Pilih Tahap </option>
	              <option value="1" <?= $data['tahap'] == 1 ? 'selected' : ''  ?>>Januari - Maret</option>
	              <option value="2" <?= $data['tahap'] == 2 ? 'selected' : ''  ?>>April - Juni</option>
	              <option value="3" <?= $data['tahap'] == 3 ? 'selected' : ''  ?>>Juli - September</option>
	              <option value="4" <?= $data['tahap'] == 4 ? 'selected' : ''  ?>>Oktober - Desember</option>
	            </select>
	          </div>


	          <div class="form-group">
	            <label for="tahun" class="">Tahun</label>
	            <select class="form-control" name="tahun">
	              <option value="0"> Pilih Tahun </option>
	              <?php $tahun = date('Y');
                for ($i = 2023; $i < $tahun + 5; $i++) { ?>
	                <option value="<?php echo $i ?>" <?= $data['tahun'] == $i ? 'selected' : ''  ?>><?php echo $i ?></option>
	              <?php } ?>
	            </select>
	          </div>
	          <button name="simpan-periode" type="submit" class="btn btn-info"><i class="fas fa-save"></i> Simpan</button>

	        </form>












	      </div>
	    </div>
	  </div>
	</div>


	<!-- Bootstrap core JavaScript-->
	<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

	<!-- Core plugin JavaScript-->
	<script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>

	<!-- Custom scripts for all pages-->
	<script src="assets/js/sb-admin-2.min.js"></script>

	<!-- Page level plugins -->
	<script src="assets/vendor/chart.js/Chart.min.js"></script>

	<!-- Page level plugins -->
	<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>
	<script src="assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

	<!-- Page level custom scripts -->
	<script src="assets/js/demo/datatables-demo.js"></script>

	<script>
	  $(function() {
	    $('[data-toggle="tooltip"]').tooltip()
	  })
	</script>

	</body>

	</html>
	<?php
  if (isset($pdo)) {
    // Tutup Koneksi
    $pdo = null;
  }
  ?>