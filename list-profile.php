<?php
require_once 'includes/init.php';

$user_role = get_role();
if ($user_role == 'admin' || $user_role == 'user') {
	$errors = array();
	$sukses = false;

	$ada_error = false;
	$result = '';

	$id_user = $_SESSION["user_id"];

	if (isset($_POST['submit'])) {
		$password = $_POST['password'];
		$password2 = $_POST['password2'];
		$nama = $_POST['nama'];
		$email = $_POST['email'];

		if (!$nama) {
			$errors[] = 'Nama tidak boleh kosong';
		}

		if (!$email) {
			$errors[] = 'Email tidak boleh kosong';
		}

		if (!$id_user) {
			$errors[] = 'Id User salah';
		}

		if ($password && ($password != $password2)) {
			$errors[] = 'Password harus sama keduanya';
		}

		if (empty($errors)) {
			try {
				$query = "UPDATE user SET nama = :nama, email = :email";
				$params = array(':nama' => $nama, ':email' => $email);

				if ($password) {
					$query .= ", password = :password";
					$pass = sha1($password);
					$params[':password'] = $pass;
				}

				$query .= " WHERE id_user = :id_user";
				$params[':id_user'] = $id_user;

				$stmt = $koneksi->prepare($query);
				$update = $stmt->execute($params);

				if ($update) {
					$errors[] = 'Data berhasil diupdate';
				} else {
					$errors[] = 'Data gagal diupdate';
				}
			} catch (PDOException $e) {
				echo "Kesalahan pada query: " . $e->getMessage();
				$errors[] = 'Terjadi kesalahan pada query';
			}
		}
	}

	$page = "Profile";
	require_once('template/header.php');
?>

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-user"></i> Data Profile</h1>
	</div>

	<?php if (!empty($errors)) : ?>
		<div class="alert alert-info">
			<?php foreach ($errors as $error) : ?>
				<?php echo $error; ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<form action="" method="post">
		<div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-danger"><i class="fas fa-fw fa-edit"></i> Edit Data Profile</h6>
			</div>
			<?php
			if (!$id_user) {
			?>
				<div class="card-body">
					<div class="alert alert-danger">Data tidak ada</div>
				</div>
				<?php
			} else {
				try {
					$query = "SELECT * FROM user WHERE id_user = :id_user";
					$stmt = $koneksi->prepare($query);
					$stmt->bindParam(':id_user', $id_user);
					$stmt->execute();
					$data = $stmt->fetch(PDO::FETCH_ASSOC);

					if (!$data) {
				?>
						<div class="card-body">
							<div class="alert alert-danger">Data tidak ada</div>
						</div>
					<?php
					} else {
					?>
						<div class="card-body">
							<div class="row">
								<div class="form-group col-md-6">
									<label class="font-weight-bold">Username</label>
									<input autocomplete="off" type="text" readonly required value="<?php echo $data['username']; ?>" class="form-control" />
								</div>

								<div class="form-group col-md-6">
									<label class="font-weight-bold">Password</label>
									<input autocomplete="off" type="password" name="password" class="form-control" />
								</div>

								<div class="form-group col-md-6">
									<label class="font-weight-bold">Ulangi Password</label>
									<input autocomplete="off" type="password" name="password2" class="form-control" />
								</div>

								<div class="form-group col-md-6">
									<label class="font-weight-bold">Nama</label>
									<input autocomplete="off" type="text" name="nama" required value="<?php echo $data['nama']; ?>" class="form-control" />
								</div>

								<div class="form-group col-md-6">
									<label class="font-weight-bold">E-Mail</label>
									<input autocomplete="off" type="email" name="email" required value="<?php echo $data['email']; ?>" class="form-control" />
								</div>
							</div>
						</div>
						<div class="card-footer text-right">
							<button name="submit" value="submit" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
							<button type="reset" class="btn btn-info"><i class="fa fa-sync-alt"></i> Reset</button>
						</div>
			<?php
					}
				} catch (PDOException $e) {
					echo "Kesalahan pada query: " . $e->getMessage();
					echo '<div class="alert alert-danger">Terjadi kesalahan pada query</div>';
				}
			}
			?>
		</div>
	</form>

<?php
	require_once('template/footer.php');
} else {
	header('Location: login.php');
}
?>