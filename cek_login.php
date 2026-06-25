<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Sistem Informasi Kepegawaian</title>
        <!-- <link rel="shortcut icon" href="img/icon.png" /> -->
        <!-- Tell the browser to be responsive to screen width -->
    </head>
    <body>
        <?php
        require_once ('conf/conf.php');
        require_once ('libs/aes-encrypt/function.php');
        
        // print_r(paramEncrypt('module=dashboard&act=dashboard'));
        // exit;
        $username = isset($_POST['username']) ? $_POST['username'] : null;
        $pass = paramEncrypt(isset($_POST['password']) ? $_POST['password'] : null);

        $userspv = paramEncrypt(isset($_POST['username']) ? $_POST['username'] : null);

        // var_dump($userspv);
        // var_dump("<br>");
        // var_dump($username);
        // var_dump("<br>");
        // var_dump($pass);
        // var_dump("<br>");
        // var_dump("select nama_instansi from setup where kode_skpd='$userspv' and password='$pass'");
        // var_dump("<br>");
        // var_dump("SELECT tm_user.id_level, tm_level.nama_level FROM tm_pegawai inner join tm_user on tm_pegawai.id_user=tm_user.id_user inner join tm_level on tm_user.id_level=tm_level.id_level WHERE tm_pegawai.status='AKTIF' and ((tm_pegawai.nip='$username' AND tm_user.password='$pass') OR (tm_pegawai.nip='$username' AND tm_user.password_backup='$pass'))");
        // exit;

        // pastikan username dan password adalah berupa huruf atau angka.
        $spv = getOne("select nama_instansi from setup where kode_skpd='$userspv' and password='$pass'");
        $login = fetch_assoc(bukaquery2("SELECT tm_user.id_level, tm_level.nama_level FROM tm_pegawai inner join tm_user on tm_pegawai.id_user=tm_user.id_user inner join tm_level on tm_user.id_level=tm_level.id_level WHERE tm_pegawai.status='AKTIF' and ((tm_pegawai.nip='$username' AND tm_user.password='$pass') OR (tm_pegawai.nip='$username' AND tm_user.password_backup='$pass'))"));
        $id_unit = getOne("SELECT tm_pegawai.id_unit FROM tm_pegawai inner join tm_user on tm_pegawai.id_user=tm_user.id_user WHERE tm_pegawai.status='AKTIF' and ((tm_pegawai.nip='$username' AND tm_user.password='$pass') OR (tm_pegawai.nip='$username' AND tm_user.password_backup='$pass'))");
        $id_user = getOne("SELECT tm_pegawai.id_user FROM tm_pegawai inner join tm_user on tm_pegawai.id_user=tm_user.id_user WHERE tm_pegawai.status='AKTIF' and ((tm_pegawai.nip='$username' AND tm_user.password='$pass') OR (tm_pegawai.nip='$username' AND tm_user.password_backup='$pass'))");
        $nip = getOne("SELECT tm_pegawai.nip FROM tm_pegawai WHERE nip='$username' and status='AKTIF'");
        if ($login == '' and $spv == '') {
            header('location:login?error=' . base64_encode('Username dan Password Invalid !!!'));
            exit();
        } else {
            session_start();
            $_SESSION['nip'] = $nip;
            $_SESSION['id_level'] = $login['id_level'];
            $_SESSION['level'] = strtolower($login['nama_level']);
            $_SESSION['superuser'] = $spv;
            $_SESSION['id_unit'] = $id_unit ?: '';
            $_SESSION['id_user'] = $id_user ?: '';
            
            if ($login != '') {                
            $cek = getOne("select status_pegawai from tm_pegawai where nip='$nip'");
                if ($cek == 'PNS') {
                    header('location:page-view?' . paramEncrypt('module=dashboard&act=home'));
                } else {
                    header('location:page-view?' . paramEncrypt('module=dashboard&act=dashboard'));
                }
            }
            if ($spv != '') {
                header('location:page-view?' . paramEncrypt('module=dashboard&act=home'));
            }
        }
        ?>
    </body>
</html>
