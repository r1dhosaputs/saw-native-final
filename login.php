<?php require_once('includes/init.php'); ?>

<?php
// $errors = array();
// $username = isset($_POST['username']) ? trim($_POST['username']) : '';
// $password = isset($_POST['username']) ? trim($_POST['password']) : '';

// if(isset($_POST['submit'])):

// 	// Validasi
// 	if(!$username) {
// 		$errors[] = 'Username tidak boleh kosong';
// 	}
// 	if(!$password) {
// 		$errors[] = 'Password tidak boleh kosong';
// 	}

// 	if(empty($errors)):
// 		$query = mysqli_query($koneksi,"SELECT * FROM user WHERE username = '$username'");
// 		$cek = mysqli_num_rows($query);
// 		$data = mysqli_fetch_array($query);

// 		if($cek > 0){
// 			$hashed_password = sha1($password);
// 			if($data['password'] === $hashed_password) {
// 				$_SESSION["user_id"] = $data["id_user"];
// 				$_SESSION["username"] = $data["username"];
// 				$_SESSION["role"] = $data["role"];
// 				redirect_to("dashboard.php");
// 			} else {
// 				$errors[] = 'Username atau password salah!';
// 			}
// 		} else {
// 			$errors[] = 'Username atau password salah!';
// 		}

// 	endif;

// endif;	

// sqlite query

$errors = array();
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

if (isset($_POST['submit'])) {
    // Validasi
    if (empty($username)) {
        $errors[] = 'Username tidak boleh kosong';
    }
    if (empty($password)) {
        $errors[] = 'Password tidak boleh kosong';
    }

    // Proses login jika tidak ada error validasi
    if (empty($errors)) {
        $stmt = $koneksi->prepare("SELECT * FROM user WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            // Verifikasi password
            $hashed_password = sha1($password); // Sesuaikan dengan algoritma hash yang Anda gunakan di SQLite
            if ($data['password'] === $hashed_password) {
                // Set session
                $_SESSION["user_id"] = $data["id_user"];
                $_SESSION["username"] = $data["username"];
                $_SESSION["role"] = $data["role"];
                redirect_to("dashboard.php");
            } else {
                $errors[] = 'Username atau password salah!';
            }
        } else {
            $errors[] = 'Username atau password salah!';
        }
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Sistem Pendukung Keputusan Metode SAW</title>

    <!-- Custom fonts for this template-->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />

    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet" />
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
</head>

<body class="bg-gradient-danger">
    <nav class="navbar navbar-expand-lg navbar-dark bg-white shadow-lg pb-1 pt-3 font-weight-bold">
        <div class="container">
            <a class="navbar-brand text-danger" style="font-weight: 900;" href="login.php"> <i class="fa fa-database mr-2 rotate-n-15"></i> Sistem Pendukung Keputusan Metode SAW</a>
        </div>
    </nav>

    <div class="container">
        <!-- Outer Row -->
        <div class="row d-plex justify-content-between mt-5">
            <div class="col-xl-6 col-lg-6 col-md-6 mt-5">
                <div class="card bg-none o-hidden border-0 my-5 text-white" style="background: none;">
                    <div class="text-center card-body p-0">
                        <!-- <h4 style="font-weight: 800;">Sistem Pendukung Keputusan Metode SAW</h4>
                            <p class="pt-4">
                                Sistem Pendukung Keputusan (SPK) merupakan suatu sistem informasi spesifik yang ditujukan untuk membantu manajemen dalam mengambil keputusan yang berkaitan dengan persoalan yang bersifat semi terstruktur.
                            </p>
                            <p>
                                Simple Additive Weighting (SAW) adalah salah satu Metode Fuzzy Multiple Attribute Decision Making (FMADM) yang mampu menyelesaikan masalah multiple attribute decision making dengan cara membobotkan semua kriteria dan alternatif yang menghasilkan nilai referensi yang tepat.
                            </p> -->

                        <img class="mb-4" style="border-radius: 10px;" src="/SPK-SAW-NATIVE/assets/img/login.png" width="300px">
                    </div>
                </div>
            </div>

            <div class="col-xl-5 col-lg-5 col-md-1 mt-1">
                <div class="card o-hidden border-0 shadow-lg my-1">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-1">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mt-2 mb-4">SELAMAT DATANG</h1>
                                        <img class="mb-4" style="border-radius: 10px;" src="/SPK-SAW-NATIVE/assets/img/logo.png" width="100px">
                                        <h1 class="h4 text-gray-900 mb-4">Program Keluarga Harapan (PKH)</h1>
                                        <h1 class="h4 text-gray-900 mb-4">Kelurahan Kemuning</h1>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-1">
                                    <?php if (!empty($errors)) : ?>
                                        <?php foreach ($errors as $error) : ?>
                                            <div class="alert alert-danger text-center"><?php echo $error; ?></div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                    <form class="user" action="login.php" method="post">
                                        <div class="form-group">
                                            <input required autocomplete="off" type="text" value="<?php echo htmlentities($username); ?>" class="form-control form-control-user" id="exampleInputUser" placeholder="Username" name="username" />
                                        </div>
                                        <div class="form-group">
                                            <input required autocomplete="off" type="password" class="form-control form-control-user" id="exampleInputPassword" name="password" placeholder="Password" />
                                        </div>
                                        <button name="submit" type="submit" class="btn btn-danger btn-user btn-block"><i class="fas fa-fw fa-sign-in-alt mr-1"></i> Masuk</button>
                                        <div class="mb-3"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="assets/js/sb-admin-2.min.js"></script>
</body>

</html>