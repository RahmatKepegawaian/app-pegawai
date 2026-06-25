<?php
error_reporting(0);
require_once('conf/conf.php');
require_once('libs/aes-encrypt/function.php');

//setup setting waktu kinerja
$day = date('d');
$setup = fetch_array(bukaquery("select count(setup.id) as cek,setup.tutup_kinerja,setup.validasi_pj,setup.validasi_kasie from setup"));
if ($setup['cek'] == 0) {
    header('location:signup');
}
//tahun dan bulan kemarin
$tgl = TanggalAkhirBulanKemarin();
$bln_sebelumnya = FormatTgl('m', $tgl);
$thn = FormatTgl('Y', $tgl);
//tahun dan bulan berjalan
$thn_now = date('Y');
$bln_now = date('m');
//ambil session dan diganti ke variabel
$spv = isset($_SESSION['superuser']) ? $_SESSION['superuser'] : null;
$nip = isset($_SESSION['nip']) ? $_SESSION['nip'] : null;
$idlevel = isset($_SESSION['id_level']) ? $_SESSION['id_level'] : null;
$id_user = getOne("select tm_pegawai.id_user from tm_pegawai where nip='$nip'");
$nama_pegawai = getOne("select tm_pegawai.nama_pegawai from tm_pegawai where nip='$nip'");
$id_unit = getOne("select tm_pegawai.id_unit from tm_pegawai where nip='$nip'");
$kasie = getOne("select tm_pegawai.sub_bagian from tm_pegawai where nip='$nip'");
$kasatpel = getOne("select tm_pegawai.id_kasatpel from tm_pegawai where nip='$nip'");
$surat_tugas = getOne("SELECT count(tm_surat_tugas.id_surat) as jumlah FROM tm_surat_tugas 
                    inner JOIN tm_add_surtug ON tm_surat_tugas.id_surat=tm_add_surtug.id_surat
                    where tm_add_surtug.id_add='$id_user' and tm_add_surtug.`read`='0'");
//hak akses
$superuser = getOne("select setup.kode_skpd from setup where nama_instansi='$spv'");
$hak_akses = fetch_array(bukaquery("SELECT tm_level.menu_kepegawaian, tm_level.menu_diklat, tm_level.menu_keuangan, tm_level.menu_val_pj, tm_level.menu_val_kasatpel, tm_level.menu_val_kasie, tm_level.menu_laporan, tm_level.menu_helpdesk 
                                    FROM tm_level where id_level='$idlevel'"));
//Set URI
$URI = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;
$url = decode($URI);
$alert = isset($url['alert']) ? $url['alert'] : null;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Sistem Informasi Kepegawaian - Login</title>
    <!-- <link rel="shortcut icon" href="img/icon.png" /> -->
    <meta name="theme-color" content="#6700DF">
    <link rel="manifest" href="manifest.json">
    <link rel="apple-touch-icon" href="assets/icons/icon-96x96.png">
    <meta name="apple-mobile-web-app-status-bar" content="#FFFFFF">

    <!-- Google Font: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        .login-wrapper {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }

        .login-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 400px;
            padding: 2.5rem;
            position: relative;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h4 {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .login-header p {
            font-size: 0.9rem;
            color: #7f8c8d;
            font-weight: 500;
        }

        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            border: 1px solid #e0e0e0;
            background-color: #f9f9f9;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #6700DF;
            background-color: #ffffff;
        }

        .input-group-text {
            border-radius: 8px 0 0 8px;
            background-color: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-right: none;
            color: #95a5a6;
        }

        .form-control {
            border-left: none;
        }

        .btn-login {
            background-color: #6700DF;
            color: #ffffff;
            border-radius: 8px;
            padding: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background-color: #5100b3;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(103, 0, 223, 0.3);
        }

        .alert-custom {
            border-radius: 8px;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="login-card">
            
            <div class="login-header">
                <!-- Anda bisa mengaktifkan logo di sini dengan tag img -->
                <!-- <img src="img/male.jpg" width="70" alt="Logo" class="mb-3"> -->
                <h4>Sistem Informasi Pegawai</h4>
                <p><?php echo getOne("select nama_instansi from setup"); ?></p>
            </div>

            <div class="alert alert-info alert-custom alert-dismissible fade show text-center" role="alert">
                <i class="fas fa-info-circle me-1"></i> Silahkan login untuk mengakses aplikasi.
            </div>

            <?PHP if (isset($_GET['error'])) { ?>
                <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                    <strong><i class="fas fa-exclamation-triangle me-1"></i> Gagal!</strong> <?= base64_decode($_GET['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php } ?>

            <form action="cek-login" method="post" role="form">
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input class="form-control" placeholder="Masukkan NIP Anda" name="username" type="text" autofocus required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input class="form-control" placeholder="Masukkan Password" name="password" type="password" required>
                    </div>
                </div>

                <button type="submit" name="submit" class="btn btn-login w-100">
                    Sign In <i class="fas fa-arrow-right ms-1"></i>
                </button>
            </form>
            
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle (Includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="libs/jquery/jquery.min.js"></script>
    <script src="main.js"></script>
</body>

</html>