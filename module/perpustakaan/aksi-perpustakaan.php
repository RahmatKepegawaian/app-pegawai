<?php

require_once ('../../conf/conf.php');
require_once ('../../libs/aes-encrypt/function.php');
$URI = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;
$url = decode($URI);
$page = isset($url['module']) ? $url['module'] : null;
$action = isset($url['act']) ? $url['act'] : null;
$id = isset($url['id']) ? $url['id'] : null;

if($page == 'perpustakaan' and $action == 'add-pedoman') {
    $autonumber = nokiamat('id_pedoman', 'tm_pedoman');
    $ekstensi_diperbolehkan = array('pdf');
    $nama = $_FILES['file']['name'];
    $x = explode('.', $nama);
    $nama_baru = $autonumber  . "_" . round(microtime(true)) . '.' . end($x);
    $ekstensi = strtolower(end($x));
    $ukuran = $_FILES['file']['size'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $folder = "../../files/pedoman/";
    if ($nama != '') {
        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            if ($ukuran < 5242880) {
                bukainputcek("insert into tm_pedoman set id_pedoman='$autonumber', no_pedoman='$_POST[no_pedoman]',tentang='$_POST[tentang]',deskripsi='$_POST[deskripsi]', id_unit='$_POST[id_unit]', id_user='$_POST[id_user]', status='1', file='$nama_baru'");
                move_uploaded_file($file_tmp, $folder . $nama_baru);
                header('location:../../page-view?' . paramEncrypt('module=perpustakaan&act=pedoman'));
            } else {
                echo "<script>alert('GAGAL !! Upload file, mungkin terlalu besar, tidak diperbolehkan lebih dari 5MB !!'); window.location = 'javascript:history.go(-1)'</script>";
            }
        } else {
            echo "<script>alert('File hanya diperbolehkan berformat PDF !!'); window.location = 'javascript:history.go(-1)'</script>";
        }
    } else {
        echo "<script>alert('Maaf anda belum upload file !!'); window.location = 'javascript:history.go(-1)'</script>";
    }
} elseif ($page == 'perpustakaan' and $action == 'add-panduan') {
    $autonumber = nokiamat('id_panduan', 'tm_panduan');
    $ekstensi_diperbolehkan = array('pdf');
    $nama = $_FILES['file']['name'];
    $x = explode('.', $nama);
    $nama_baru = $autonumber  . "_" . round(microtime(true)) . '.' . end($x);
    $ekstensi = strtolower(end($x));
    $ukuran = $_FILES['file']['size'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $folder = "../../files/panduan/";
    if ($nama != '') {
        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            if ($ukuran < 5242880) {
                bukainputcek("insert into tm_panduan set id_panduan='$autonumber', no_panduan='$_POST[no_panduan]',tentang='$_POST[tentang]',deskripsi='$_POST[deskripsi]', id_unit='$_POST[id_unit]', id_user='$_POST[id_user]', status='1', file='$nama_baru'");
                move_uploaded_file($file_tmp, $folder . $nama_baru);
                header('location:../../page-view?' . paramEncrypt('module=perpustakaan&act=panduan'));
            } else {
                echo "<script>alert('GAGAL !! Upload file, mungkin terlalu besar, tidak diperbolehkan lebih dari 5MB !!'); window.location = 'javascript:history.go(-1)'</script>";
            }
        } else {
            echo "<script>alert('File hanya diperbolehkan berformat PDF !!'); window.location = 'javascript:history.go(-1)'</script>";
        }
    } else {
        echo "<script>alert('Maaf anda belum upload file !!'); window.location = 'javascript:history.go(-1)'</script>";
    }
} elseif ($page == 'perpustakaan' and $action == 'add-spo') {
    $autonumber = nokiamat('id_spo', 'tm_spo');
    $ekstensi_diperbolehkan = array('pdf');
    $nama = $_FILES['file']['name'];
    $x = explode('.', $nama);
    $nama_baru = $autonumber  . "_" . round(microtime(true)) . '.' . end($x);
    $ekstensi = strtolower(end($x));
    $ukuran = $_FILES['file']['size'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $folder = "../../files/spo/";
    if ($nama != '') {
        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            if ($ukuran < 5242880) {
                bukainputcek("insert into tm_spo set id_spo='$autonumber', no_spo='$_POST[no_spo]',tentang='$_POST[tentang]',deskripsi='$_POST[deskripsi]', id_unit='$_POST[id_unit]', id_user='$_POST[id_user]', status='1', file='$nama_baru'");
                move_uploaded_file($file_tmp, $folder . $nama_baru);
                header('location:../../page-view?' . paramEncrypt('module=perpustakaan&act=spo'));
            } else {
                echo "<script>alert('GAGAL !! Upload file, mungkin terlalu besar, tidak diperbolehkan lebih dari 5MB !!'); window.location = 'javascript:history.go(-1)'</script>";
            }
        } else {
            echo "<script>alert('File hanya diperbolehkan berformat PDF !!'); window.location = 'javascript:history.go(-1)'</script>";
        }
    } else {
        echo "<script>alert('Maaf anda belum upload file !!'); window.location = 'javascript:history.go(-1)'</script>";
    }
} elseif ($page == 'perpustakaan' and $action == 'add-sk') {
    $tanggal = FormatTgl('Y-m-d', $_POST['tanggal_sk']);
    $autonumber = nokiamat('id_sk', 'tm_sk');
    $ekstensi_diperbolehkan = array('pdf');
    $nama = $_FILES['file']['name'];
    $x = explode('.', $nama);
    $nama_baru = $autonumber  . "_" . round(microtime(true)) . '.' . end($x);
    $ekstensi = strtolower(end($x));
    $ukuran = $_FILES['file']['size'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $folder = "../../files/sk/";
    if ($nama != '') {
        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            if ($ukuran < 5242880) {
                bukainputcek("insert into tm_sk set id_sk='$autonumber', no_sk='$_POST[no_sk]',tanggal_sk='$tanggal', tentang='$_POST[tentang]',deskripsi='$_POST[deskripsi]', status='1', file='$nama_baru'");
                move_uploaded_file($file_tmp, $folder . $nama_baru);
                header('location:../../page-view?' . paramEncrypt('module=perpustakaan&act=kebijakan'));
            } else {
                echo "<script>alert('GAGAL !! Upload file, mungkin terlalu besar, tidak diperbolehkan lebih dari 5MB !!'); window.location = 'javascript:history.go(-1)'</script>";
            }
        } else {
            echo "<script>alert('File hanya diperbolehkan berformat PDF !!'); window.location = 'javascript:history.go(-1)'</script>";
        }
    } else {
        echo "<script>alert('Maaf anda belum upload file !!'); window.location = 'javascript:history.go(-1)'</script>";
    }
} elseif ($page == 'perpustakaan' and $action == 'add-peraturan') {
    $autonumber = nokiamat('id_peraturan', 'tm_peraturan');
    $ekstensi_diperbolehkan = array('pdf');
    $nama = $_FILES['file']['name'];
    $x = explode('.', $nama);
    $nama_baru = $autonumber  . "_" . round(microtime(true)) . '.' . end($x);
    $ekstensi = strtolower(end($x));
    $ukuran = $_FILES['file']['size'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $folder = "../../files/peraturan/";
    if ($nama != '') {
        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            if ($ukuran < 32428800) {                
                bukainputcek("insert into tm_peraturan set id_peraturan='$autonumber', no_peraturan='$_POST[no_peraturan]',tentang='$_POST[tentang]', jenis='$_POST[jenis]', status='1', file='$nama_baru'");
                move_uploaded_file($file_tmp, $folder . $nama_baru);
                header('location:../../page-view?' . paramEncrypt('module=perpustakaan&act=peraturan'));
            } else {
                echo "<script>alert('GAGAL !! Upload file, mungkin terlalu besar, tidak diperbolehkan lebih dari 9MB !!'); window.location = 'javascript:history.go(-1)'</script>";
            }
        } else {
            echo "<script>alert('File hanya diperbolehkan berformat PDF !!'); window.location = 'javascript:history.go(-1)'</script>";
        }
    } else {
        echo "<script>alert('Maaf anda belum upload file !!'); window.location = 'javascript:history.go(-1)'</script>";
    }
}

//delete
elseif ($page == 'perpustakaan' and $action == 'delete-pedoman') {
    $file = getOne("select tm_pedoman.file from tm_pedoman where id_pedoman='$id'");    
    hapusinput("delete from tm_pedoman where id_pedoman='$id'");
    unlink("../../files/pedoman/" . $file);
    header('location:../../page-view?' . paramEncrypt('module=perpustakaan&act=panduan'));
} elseif ($page == 'perpustakaan' and $action == 'delete-panduan') {
    $file = getOne("select tm_panduan.file from tm_panduan where id_panduan='$id'");    
    hapusinput("delete from tm_panduan where id_panduan='$id'");
    unlink("../../files/panduan/" . $file);
    header('location:../../page-view?' . paramEncrypt('module=perpustakaan&act=panduan'));
} elseif ($page == 'perpustakaan' and $action == 'delete-spo') {
    $file = getOne("select tm_spo.file from tm_spo where id_spo='$id'");    
    hapusinput("delete from tm_spo where id_spo='$id'");
    unlink("../../files/spo/" . $file);
    header('location:../../page-view?' . paramEncrypt('module=perpustakaan&act=spo'));
} elseif ($page == 'perpustakaan' and $action == 'delete-sk') {
    $file = getOne("select tm_sk.file from tm_sk where id_sk='$id'");    
    hapusinput("delete from tm_sk where id_sk='$id'");
    unlink("../../files/sk/" . $file);
    header('location:../../page-view?' . paramEncrypt('module=perpustakaan&act=kebijakan'));
} elseif ($page == 'perpustakaan' and $action == 'delete-peraturan') {
    $file = getOne("select tm_peraturan.file from tm_peraturan where id_peraturan='$id'");    
    hapusinput("delete from tm_peraturan where id_peraturan='$id'");
    unlink("../../files/peraturan/" . $file);
    header('location:../../page-view?' . paramEncrypt('module=perpustakaan&act=peraturan'));
}
?>