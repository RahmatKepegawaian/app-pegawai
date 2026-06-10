<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Sistem Informasi Kepegawaian</title>
        <link rel="shortcut icon" href="img/icon.png" />
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="libs/bootstrap/dist/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="libs/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="libs/Ionicons/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="libs/dist/css/AdminLTE.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="libs/iCheck/square/blue.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body class="hold-transition register-page">
        <div class="register-box">
            <div class="register-logo">
                <a href="../../index2.html"><b>SET-UP</b> REGISTATION</a>
            </div>
            <?php
            require_once ('conf/conf.php');
            require_once ('libs/aes-encrypt/function.php');
            if (getOne("select setup.kode_skpd from setup") != '') {
                header('location:login');
            } else {
                if (isset($_POST['regis']) != '') {
                    $pass = isset($_POST['retype_pass']) ? $_POST['retype_pass'] : null;
                    $retype_pass = isset($_POST['pass']) ? $_POST['pass'] : null;
                    if ($pass == $retype_pass) {
                        $kode = paramEncrypt(isset($_POST['kode_skpd']) ? $_POST['kode_skpd'] : null);
                        $nama = isset($_POST['nama_instansi']) ? $_POST['nama_instansi'] : null;
                        $alamat = isset($_POST['alamat_kop']) ? $_POST['alamat_kop'] : null;
                        $email = isset($_POST['email']) ? $_POST['email'] : null;
                        $pass = paramEncrypt(isset($_POST['pass']) ? $_POST['pass'] : null);
                        $id = paramEncrypt('bismillahirrohmanirrohim');
                        echo $alamat;
                        bukainput("insert into setup set id='$id', kode_skpd='$kode', nama_instansi='$nama', alamat_kop='$alamat', email='$email', "
                                . "tlp='', fax='', website='', kode_pos='', logo='', password='$pass', direktur='', nip_direktur='', tutup_kinerja='-', validasi_pj='-', validasi_kasie='-'");
                        header('location:login');
                    } else {
                        echo "<script>window.alert('Maf password anda tidak sama');window.location.href='signup';</script>";
                    }
                } else {
                    
                }
            }
            ?>
            <div class="register-box-body">
                <p class="login-box-msg">New E-Kinerja</p>

                <form action="" method="post">
                    <div class="form-group has-feedback">
                        <input type="text" name="kode_skpd" class="form-control" placeholder="Kode SKPD" required autofocus>
                        <span class="glyphicon glyphicon-asterisk form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="text" name="nama_instansi" class="form-control" placeholder="Nama Instansi" required>
                        <span class="glyphicon glyphicon-hospital form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <textarea class="form-control" name="alamat_kop" placeholder="Alamat instansi" rows="3" required></textarea>
                        <span class="glyphicon glyphicon-edit form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="pass" class="form-control" placeholder="Password" required>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="retype_pass" class="form-control" placeholder="Retype password" required>
                        <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                    </div>
                    <div class="row">       
                        <!-- /.col -->
                        <div class="col-xs-4">
                            <button type="submit" name="regis" value="regis" class="btn btn-primary btn-block btn-flat">Register</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <br>
                <a href="" class="text-center">Copyright © 2018 by Fauzan Ozan</a>
            </div>
            <!-- /.form-box -->
        </div>
        <!-- /.register-box -->

        <!-- jQuery 3 -->
        <script src="libs/jquery/dist/jquery.min.js"></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="libs/bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- iCheck -->
        <script src="libs/iCheck/icheck.min.js"></script>
        <script>
            $(function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' /* optional */
                });
            });
        </script>
    </body>
</html>
