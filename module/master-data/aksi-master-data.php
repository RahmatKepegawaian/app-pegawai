<?php

require_once ('../../conf/conf.php');
require_once ('../../libs/aes-encrypt/function.php');
$URI = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;
$url = decode($URI);
$page = isset($url['module']) ? $url['module'] : null;
$action = isset($url['act']) ? $url['act'] : null;
$id = isset($url['id']) ? $url['id'] : null;
//pegawai pns
if ($page == 'master-data' and $action == 'add-master-pegawai-pns') {
    $id_user = nokiamat("id_user", "tm_pegawai");
    $tgl_masuk = date('Y-m-d', strtotime($_POST['tgl_masuk']));
    $bpjs_ks = isset($_POST['bpjs_ks']) ? $_POST['bpjs_ks'] : null;
    $bpjs_jkk = isset($_POST['bpjs_jkk']) ? $_POST['bpjs_jkk'] : null;
    $bpjs_ijht = isset($_POST['bpjs_ijht']) ? $_POST['bpjs_ijht'] : null;
    $bpjs_jp = isset($_POST['bpjs_jp']) ? $_POST['bpjs_jp'] : null;
    $password = paramEncrypt('123456');
    bukainput("insert into tm_user set "
            . "id_user='$id_user',"
            . "password='$password',"
            . "id_level='$_POST[id_level]'");
    bukainput("insert into tm_pegawai set "
            . "id_user='$id_user',"
            . "nip='$_POST[nip]',"
            . "nik='-',"
            . "nama_pegawai='$_POST[nama_pegawai]',"
            . "tempat_lahir='-',"
            . "tgl_lahir='1990-01-01',"
            . "jk='-',"
            . "alamat='-',"
            . "no_hp_wa='$_POST[no_hp_wa]',"
            . "no_hp_sms='$_POST[no_hp_sms]',"
            . "npwp='-',"
            . "no_rek='-',"
            . "pendidikan='$_POST[pendidikan]',"
            . "tgl_masuk='$tgl_masuk',"
            . "status_nikah='$_POST[status_nikah]',"
            . "rumpun='$_POST[rumpun]',"
            . "pajak='$_POST[pajak]',"
            . "id_unit='$_POST[id_unit]',"
            . "bpjs_ks='$bpjs_ks',"
            . "bpjs_jkk='$bpjs_jkk',"
            . "bpjs_ijht='$bpjs_ijht',"
            . "bpjs_jp='$bpjs_jp',"
            . "id_kasatpel = '$_POST[id_kasatpel]', "
            . "foto = '-', "
            . "status_pegawai = 'PNS', "
            . "sub_bagian = '$_POST[sub_bagian]', "
            . "log_finger = '$_POST[log_finger]', "
            . "status = 'AKTIF'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-pegawai-pns'));
} elseif ($page == 'master-data' and $action == 'update-master-pegawai-pns') {
    $tgl_masuk = FormatTgl('Y-m-d', $_POST['tgl_masuk']);
    $bpjs_ks = isset($_POST['bpjs_ks']) ? $_POST['bpjs_ks'] : null;
    $bpjs_jkk = isset($_POST['bpjs_jkk']) ? $_POST['bpjs_jkk'] : null;
    $bpjs_ijht = isset($_POST['bpjs_ijht']) ? $_POST['bpjs_ijht'] : null;
    $bpjs_jp = isset($_POST['bpjs_jp']) ? $_POST['bpjs_jp'] : null;
    $status = isset($_POST['status']) ? $_POST['status'] : null;
    echo $tgl_masuk;
    bukainput("update tm_pegawai set "
            . "nip = '$_POST[nip]', "
            . "nik = '$_POST[nik]', "
            . "nama_pegawai = '$_POST[nama_pegawai]', "
            . "no_hp_wa = '$_POST[no_hp_wa]', "
            . "no_hp_sms = '$_POST[no_hp_sms]', "
            . "tgl_masuk = '$tgl_masuk', "
            . "id_unit = '$_POST[id_unit]', "
            . "status_nikah = '$_POST[status_nikah]', "
            . "pajak = '$_POST[pajak]', "
            . "pendidikan = '$_POST[pendidikan]', "
            . "rumpun = '$_POST[rumpun]', "
            . "log_finger = '$_POST[log_finger]', "
            . "bpjs_ks = '$bpjs_ks', "
            . "bpjs_jkk = '$bpjs_jkk', "
            . "bpjs_ijht = '$bpjs_ijht', "
            . "bpjs_jp = '$bpjs_jp', "
            . "id_kasatpel = '$_POST[id_kasatpel]', "
            . "sub_bagian = '$_POST[sub_bagian]', "
            . "status = '$status' "
            . "where id_user = '$id'");
    bukainput("update tm_user set id_level = '$_POST[id_level]' where id_user = '$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-pegawai-pns'));
} elseif ($page == 'master-data' and $action == 'delete-master-pegawai-pns') {
    hapusinput("delete from tm_skp where kd_skp = '$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-pegawai-pns'));
} elseif ($page == 'master-data' and $action == 'reset-master-pegawai-pns') {
    $password = paramEncrypt('123456');
    bukainput("update tm_user set "
            . "password='$password' "
            . " where id_user='$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-pegawai-pns'));
}

//pegawai non pns
elseif ($page == 'master-data' and $action == 'add-master-pegawai-non-pns') {
    $id_user = nokiamat("id_user", "tm_pegawai");
    $tgl_masuk = date('Y-m-d', strtotime($_POST['tgl_masuk']));
    $bpjs_ks = isset($_POST['bpjs_ks']) ? $_POST['bpjs_ks'] : null;
    $bpjs_jkk = isset($_POST['bpjs_jkk']) ? $_POST['bpjs_jkk'] : null;
    $bpjs_ijht = isset($_POST['bpjs_ijht']) ? $_POST['bpjs_ijht'] : null;
    $bpjs_jp = isset($_POST['bpjs_jp']) ? $_POST['bpjs_jp'] : null;
    $password = paramEncrypt('123456');
    bukainput("insert into tm_user set "
            . "id_user = '$id_user', "
            . "password = '$password', "
            . "id_level = '$_POST[id_level]'");
    bukainput("insert into tm_pegawai set "
            . "id_user = '$id_user', "
            . "nip = '$_POST[nip]', "
            . "nik = '-', "
            . "nama_pegawai = '$_POST[nama_pegawai]', "
            . "tempat_lahir = '-', "
            . "tgl_lahir = '1990-01-01', "
            . "jk = '-', "
            . "alamat = '-', "
            . "no_hp_wa='$_POST[no_hp_wa]',"
            . "no_hp_sms='$_POST[no_hp_sms]',"
			. "alamat_domisili = '-', "
            . "npwp = '-', "
            . "no_rek = '-', "
            . "pendidikan = '$_POST[pendidikan]', "
            . "tgl_masuk = '$tgl_masuk', "
            . "status_nikah = '$_POST[status_nikah]', "
            . "rumpun = '$_POST[rumpun]', "
            . "pajak = '$_POST[pajak]', "
            . "id_unit = '$_POST[id_unit]', "
            . "bpjs_ks = '$bpjs_ks', "
            . "bpjs_jkk = '$bpjs_jkk', "
            . "bpjs_ijht = '$bpjs_ijht', "
            . "bpjs_jp = '$bpjs_jp', "
            . "no_bpjs = '-', "
            . "foto = '-', "
            . "id_kasatpel = '$_POST[id_kasatpel]', "
            . "status_pegawai = 'NON PNS', "
            . "sub_bagian = '$_POST[sub_bagian]', "
            . "log_finger = '$_POST[log_finger]', "
            . "agama = '-', "
            . "status = 'AKTIF'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-pegawai-non-pns'));
} elseif ($page == 'master-data' and $action == 'update-master-pegawai-non-pns') {
    $tgl_masuk = FormatTgl('Y-m-d', $_POST['tgl_masuk']);
    $bpjs_ks = isset($_POST['bpjs_ks']) ? $_POST['bpjs_ks'] : null;
    $bpjs_jkk = isset($_POST['bpjs_jkk']) ? $_POST['bpjs_jkk'] : null;
    $bpjs_ijht = isset($_POST['bpjs_ijht']) ? $_POST['bpjs_ijht'] : null;
    $bpjs_jp = isset($_POST['bpjs_jp']) ? $_POST['bpjs_jp'] : null;
    $status = isset($_POST['status']) ? 'AKTIF' : 'NONAKTIF';
    bukainput("update tm_pegawai set "
            . "nip = '$_POST[nip]', "
            . "nik = '$_POST[nik]', "
            . "nama_pegawai = '$_POST[nama_pegawai]', "
            . "no_hp_wa = '$_POST[no_hp_wa]', "
            . "no_hp_sms = '$_POST[no_hp_sms]', "
            . "tgl_masuk = '$tgl_masuk', "
            . "id_unit = '$_POST[id_unit]', "
            . "status_nikah = '$_POST[status_nikah]', "
            . "pajak = '$_POST[pajak]', "
            . "pendidikan = '$_POST[pendidikan]', "
            . "rumpun = '$_POST[rumpun]', "
            . "log_finger = '$_POST[log_finger]', "
            . "bpjs_ks = '$bpjs_ks', "
            . "bpjs_jkk = '$bpjs_jkk', "
            . "bpjs_ijht = '$bpjs_ijht', "
            . "bpjs_jp = '$bpjs_jp', "
            . "id_kasatpel = '$_POST[id_kasatpel]', "
            . "sub_bagian = '$_POST[sub_bagian]', "
            . "status = '$status' "
            . "where id_user = '$id'");
    bukainput("update tm_user set id_level = '$_POST[id_level]' where id_user = '$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-pegawai-non-pns'));
} elseif ($page == 'master-data' and $action == 'delete-master-pegawai-pns') {
    hapusinput("delete from tm_skp where kd_skp = '$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-pegawai-non-pns'));
} elseif ($page == 'master-data' and $action == 'reset-master-pegawai-non-pns') {
    $password = paramEncrypt('123456');
    bukainput("update tm_user set "
            . "password='$password' "
            . " where id_user='$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-pegawai-non-pns'));
} elseif ($page == 'master-data' and $action == 'reset-master-pegawai-pns') {
    $password = paramEncrypt('123456');
    bukainput("update tm_user set "
            . "password='$password' "
            . " where id_user='$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-pegawai-pns'));
}

//pegawai pjlp
elseif ($page == 'master-data' and $action == 'add-master-pegawai-pjlp') {
    $id_user = nokiamat("id_user", "tm_pegawai");
    $tgl_masuk = date('Y-m-d', strtotime($_POST['tgl_masuk']));
    $bpjs_ks = isset($_POST['bpjs_ks']) ? $_POST['bpjs_ks'] : null;
    $bpjs_jkk = isset($_POST['bpjs_jkk']) ? $_POST['bpjs_jkk'] : null;
    $bpjs_ijht = isset($_POST['bpjs_ijht']) ? $_POST['bpjs_ijht'] : null;
    $bpjs_jp = isset($_POST['bpjs_jp']) ? $_POST['bpjs_jp'] : null;
    $password = paramEncrypt('123456');
    bukainput("insert into tm_user set "
            . "id_user = '$id_user', "
            . "password = '$password', "
            . "id_level = '$_POST[id_level]'");
    bukainput("insert into tm_pegawai set "
            . "id_user = '$id_user', "
            . "nip = '$_POST[nip]', "
            . "nik = '-', "
            . "nama_pegawai = '$_POST[nama_pegawai]', "
            . "tempat_lahir = '-', "
            . "tgl_lahir = '1990-01-01', "
            . "jk = '-', "
            . "alamat = '-', "
            . "no_hp_wa='$_POST[no_hp_wa]',"
            . "no_hp_sms='$_POST[no_hp_sms]',"
			. "alamat_domisili = '-', "
            . "npwp = '-', "
            . "no_rek = '-', "
            . "pendidikan = '$_POST[pendidikan]', "
            . "tgl_masuk = '$tgl_masuk', "
            . "status_nikah = '$_POST[status_nikah]', "
            . "rumpun = '$_POST[rumpun]', "
            . "pajak = '$_POST[pajak]', "
            . "id_unit = '$_POST[id_unit]', "
            . "bpjs_ks = '$bpjs_ks', "
            . "bpjs_jkk = '$bpjs_jkk', "
            . "bpjs_ijht = '$bpjs_ijht', "
            . "bpjs_jp = '$bpjs_jp', "
            . "no_bpjs = '-', "
            . "foto = '-', "
            . "id_kasatpel = '$_POST[id_kasatpel]', "
            . "status_pegawai = 'PJLP', "
            . "sub_bagian = '$_POST[sub_bagian]', "
            . "log_finger = '$_POST[log_finger]', "
            . "agama = '-', "
            . "status = 'AKTIF'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-pegawai-pjlp'));
} elseif ($page == 'master-data' and $action == 'update-master-pegawai-pjlp') {
    $tgl_masuk = FormatTgl('Y-m-d', $_POST['tgl_masuk']);
    $bpjs_ks = isset($_POST['bpjs_ks']) ? $_POST['bpjs_ks'] : null;
    $bpjs_jkk = isset($_POST['bpjs_jkk']) ? $_POST['bpjs_jkk'] : null;
    $bpjs_ijht = isset($_POST['bpjs_ijht']) ? $_POST['bpjs_ijht'] : null;
    $bpjs_jp = isset($_POST['bpjs_jp']) ? $_POST['bpjs_jp'] : null;
    $status = isset($_POST['status']) ? $_POST['status'] : null;
    bukainput("update tm_pegawai set "
            . "nip = '$_POST[nip]', "
            . "nik = '$_POST[nik]', "
            . "nama_pegawai = '$_POST[nama_pegawai]', "
            . "no_hp_wa = '$_POST[no_hp_wa]', "
            . "no_hp_sms = '$_POST[no_hp_sms]', "
            . "tgl_masuk = '$tgl_masuk', "
            . "id_unit = '$_POST[id_unit]', "
            . "status_nikah = '$_POST[status_nikah]', "
            . "pajak = '$_POST[pajak]', "
            . "pendidikan = '$_POST[pendidikan]', "
            . "rumpun = '$_POST[rumpun]', "
            . "log_finger = '$_POST[log_finger]', "
            . "bpjs_ks = '$bpjs_ks', "
            . "bpjs_jkk = '$bpjs_jkk', "
            . "bpjs_ijht = '$bpjs_ijht', "
            . "bpjs_jp = '$bpjs_jp', "
            . "id_kasatpel = '$_POST[id_kasatpel]', "
            . "sub_bagian = '$_POST[sub_bagian]', "
            . "status = '$status' "
            . "where id_user = '$id'");
    bukainput("update tm_user set id_level = '$_POST[id_level]' where id_user = '$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-pegawai-pjlp'));
} elseif ($page == 'master-data' and $action == 'delete-master-pegawai-pjlp') {
    hapusinput("delete from tm_skp where kd_skp = '$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-pegawai-pjlp'));
} elseif ($page == 'master-data' and $action == 'reset-master-pegawai-pjlp') {
    $password = paramEncrypt('123456');
    bukainput("update tm_user set "
            . "password='$password' "
            . " where id_user='$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-pegawai-pjlp'));
} elseif ($page == 'master-data' and $action == 'reset-master-pegawai-pjlp') {
    $password = paramEncrypt('123456');
    bukainput("update tm_user set "
            . "password='$password' "
            . " where id_user='$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-pegawai-pjlp'));
}

//pegawai spesialis
elseif ($page == 'master-data' and $action == 'add-master-pegawai-spesialis') {
    $id_user = nokiamat("id_user", "tm_pegawai");
    $tgl_masuk = date('Y-m-d', strtotime($_POST['tgl_masuk']));
    $bpjs_ks = isset($_POST['bpjs_ks']) ? $_POST['bpjs_ks'] : null;
    $bpjs_jkk = isset($_POST['bpjs_jkk']) ? $_POST['bpjs_jkk'] : null;
    $bpjs_ijht = isset($_POST['bpjs_ijht']) ? $_POST['bpjs_ijht'] : null;
    $bpjs_jp = isset($_POST['bpjs_jp']) ? $_POST['bpjs_jp'] : null;
    $password = paramEncrypt('123456');
    bukainput("insert into tm_user set "
            . "id_user = '$id_user', "
            . "password = '$password', "
            . "id_level = '$_POST[id_level]'");
    bukainput("insert into tm_pegawai set "
            . "id_user = '$id_user', "
            . "nip = '$_POST[nip]', "
            . "nik = '-', "
            . "nama_pegawai = '$_POST[nama_pegawai]', "
            . "tempat_lahir = '-', "
            . "tgl_lahir = '1990-01-01', "
            . "jk = '-', "
            . "alamat = '-', "
            . "no_hp_wa='$_POST[no_hp_wa]',"
            . "no_hp_sms='$_POST[no_hp_sms]',"
			. "alamat_domisili = '-', "
            . "npwp = '-', "
            . "no_rek = '-', "
            . "pendidikan = '$_POST[pendidikan]', "
            . "tgl_masuk = '$tgl_masuk', "
            . "status_nikah = '$_POST[status_nikah]', "
            . "rumpun = '$_POST[rumpun]', "
            . "pajak = '$_POST[pajak]', "
            . "id_unit = '$_POST[id_unit]', "
            . "bpjs_ks = '$bpjs_ks', "
            . "bpjs_jkk = '$bpjs_jkk', "
            . "bpjs_ijht = '$bpjs_ijht', "
            . "bpjs_jp = '$bpjs_jp', "
            . "no_bpjs = '-', "
            . "foto = '-', "
            . "id_kasatpel = '$_POST[id_kasatpel]', "
            . "status_pegawai = 'SPESIALIS', "
            . "sub_bagian = '$_POST[sub_bagian]', "
            . "log_finger = '$_POST[log_finger]', "
            . "agama = '-', "
            . "status = 'AKTIF'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-pegawai-spesialis'));
} elseif ($page == 'master-data' and $action == 'update-master-pegawai-spesialis') {
    $tgl_masuk = FormatTgl('Y-m-d', $_POST['tgl_masuk']);
    $bpjs_ks = isset($_POST['bpjs_ks']) ? $_POST['bpjs_ks'] : null;
    $bpjs_jkk = isset($_POST['bpjs_jkk']) ? $_POST['bpjs_jkk'] : null;
    $bpjs_ijht = isset($_POST['bpjs_ijht']) ? $_POST['bpjs_ijht'] : null;
    $bpjs_jp = isset($_POST['bpjs_jp']) ? $_POST['bpjs_jp'] : null;
    $status = isset($_POST['status']) ? $_POST['status'] : null;
    bukainput("update tm_pegawai set "
            . "nip = '$_POST[nip]', "
            . "nik = '$_POST[nik]', "
            . "nama_pegawai = '$_POST[nama_pegawai]', "
            . "no_hp_wa = '$_POST[no_hp_wa]', "
            . "no_hp_sms = '$_POST[no_hp_sms]', "
            . "tgl_masuk = '$tgl_masuk', "
            . "id_unit = '$_POST[id_unit]', "
            . "status_nikah = '$_POST[status_nikah]', "
            . "pajak = '$_POST[pajak]', "
            . "pendidikan = '$_POST[pendidikan]', "
            . "rumpun = '$_POST[rumpun]', "
            . "log_finger = '$_POST[log_finger]', "
            . "bpjs_ks = '$bpjs_ks', "
            . "bpjs_jkk = '$bpjs_jkk', "
            . "bpjs_ijht = '$bpjs_ijht', "
            . "bpjs_jp = '$bpjs_jp', "
            . "id_kasatpel = '$_POST[id_kasatpel]', "
            . "sub_bagian = '$_POST[sub_bagian]', "
            . "status = '$status' "
            . "where id_user = '$id'");
    bukainput("update tm_user set id_level = '$_POST[id_level]' where id_user = '$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-pegawai-spesialis'));
} elseif ($page == 'master-data' and $action == 'delete-master-pegawai-spesialis') {
    hapusinput("delete from tm_skp where kd_skp = '$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-pegawai-spesialis'));
} elseif ($page == 'master-data' and $action == 'reset-master-pegawai-spesialis') {
    $password = paramEncrypt('123456');
    bukainput("update tm_user set "
            . "password='$password' "
            . " where id_user='$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-pegawai-spesialis'));
} elseif ($page == 'master-data' and $action == 'reset-master-pegawai-spesialis') {
    $password = paramEncrypt('123456');
    bukainput("update tm_user set "
            . "password='$password' "
            . " where id_user='$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-pegawai-spesialis'));
}

//master data skp
elseif ($page == 'master-data' and $action == 'add-master-data-skp') {
    $autonumber = notahun('kd_skp', 'tm_skp');
    bukainput("insert into tm_skp set kd_skp = '$autonumber', skp = '$_POST[skp]', waktu = '$_POST[waktu]'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-skp'));
} elseif ($page == 'master-data' and $action == 'update-master-data-skp') {
    bukainput("update tm_skp set skp = '$_POST[skp]', waktu = '$_POST[waktu]' where kd_skp = '$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-skp'));
} elseif ($page == 'master-data' and $action == 'delete-master-data-skp') {
    hapusinput("delete from tm_skp where kd_skp = '$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-skp'));
}

//master data bagian
elseif ($page == 'master-data' and $action == 'add-master-data-bagian') {
    $autonumber = autonomer('tm_unit', 'id_unit', 'UNT-', '10');
    bukainput("insert into tm_unit set id_unit = '$autonumber', nama_unit = '$_POST[nama_unit]', id_petugas = '$_POST[id_petugas]'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-bagian'));
} elseif ($page == 'master-data' and $action == 'update-master-data-bagian') {
    bukainput("update tm_unit set nama_unit = '$_POST[nama_unit]', id_petugas = '$_POST[id_petugas]' where id_unit = '$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-bagian'));
} elseif ($page == 'master-data' and $action == 'delete-master-data-bagian') {
    hapusinput("delete from tm_unit where id_unit = '$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-bagian'));
}

//master data kasatpel
elseif ($page == 'master-data' and $action == 'add-master-data-kasatpel') {
    $autonumber = autonomer('tm_kasatpel', 'id_kasatpel', 'KSP-', '10');
    bukainput("insert into tm_kasatpel set id_kasatpel = '$autonumber', nama_kasatpel = '$_POST[nama_kasatpel]'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-kasatpel'));
} elseif ($page == 'master-data' and $action == 'update-master-data-kasatpel') {
    bukainput("update tm_kasatpel set nama_kasatpel = '$_POST[nama_kasatpel]' where id_kasatpel = '$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-kasatpel'));
} elseif ($page == 'master-data' and $action == 'delete-master-data-kasatpel') {
    hapusinput("delete from tm_kasatpel where id_kasatpel = '$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-kasatpel'));
}

//master data honor shidt
elseif ($page == 'master-data' and $action == 'add-master-data-honor-shift') {
    $autonumber = autonomer('tm_honor_shift', 'id_petugas', 'SHF-', '10');
    bukainput("insert into tm_honor_shift set id_petugas = '$autonumber', petugas = '$_POST[petugas]', hks = '$_POST[hks]', hkm = '$_POST[hkm]', hlp = '$_POST[hlp]', hls = '$_POST[hls]'"
            . ", hlm = '$_POST[hlm]', hrp = '$_POST[hrp]', hrs = '$_POST[hrs]', hrm = '$_POST[hrm]'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-shifting'));
} elseif ($page == 'master-data' and $action == 'update-master-data-honor-shift') {
    bukainput("update tm_honor_shift set petugas = '$_POST[petugas]', hks = '$_POST[hks]', hkm = '$_POST[hkm]', hlp = '$_POST[hlp]', hls = '$_POST[hls]'"
            . ", hlm = '$_POST[hlm]', hrp = '$_POST[hrp]', hrs = '$_POST[hrs]', hrm = '$_POST[hrm]' where id_petugas = '$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-shifting'));
} elseif ($page == 'master-data' and $action == 'delete-master-data-honor-shift') {
    hapusinput("delete from tm_honor_shift where id_petugas = '$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-shifting'));
}

//master data hari kerja
elseif ($page == 'master-data' and $action == 'add-master-data-hari-kerja') {
    $autonumber = notahun('id_hari_kerja', 'tm_hari_kerja');
    $date = date('Y-m-d', strtotime("1-" . $_POST['bulan'] . "-" . $_POST['tahun']));
    bukainput("insert into tm_hari_kerja set id_hari_kerja = '$autonumber', bulan = '$date', hari = '$_POST[hari]'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-hari-kerja'));
} elseif ($page == 'master-data' and $action == 'update-master-data-hari-kerja') {
    $date = date('Y-m-d', strtotime("1-" . $_POST['bulan'] . "-" . $_POST['tahun']));
    bukainput("update tm_hari_kerja set bulan = '$date', hari = '$_POST[hari]' where id_hari_kerja = '$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-hari-kerja'));
} elseif ($page == 'master-data' and $action == 'delete-master-data-hari-kerja') {
    hapusinput("delete from tm_hari_kerja where id_hari_kerja = '$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-hari-kerja'));
}

//master data penyerapan
elseif ($page == 'master-data' and $action == 'add-master-data-penyerapan') {
    $autonumber = $_POST['bulan'] . $_POST['tahun'];
    $date = date('Y-m-d', strtotime("1-" . $_POST['bulan'] . "-" . $_POST['tahun']));
    bukainputcek("insert into tm_penyerapan set id_penyerapan = '$autonumber', bulan = '$date', penyerapan = '$_POST[penyerapan]'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-penyerapan'));
} elseif ($page == 'master-data' and $action == 'update-master-data-penyerapan') {
    $date = date('Y-m-d', strtotime("1-" . $_POST['bulan'] . "-" . $_POST['tahun']));
    bukainput("update tm_penyerapan set penyerapan = '$_POST[penyerapan]' where id_penyerapan = '$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-penyerapan'));
} elseif ($page == 'master-data' and $action == 'delete-master-data-penyerapan') {
    hapusinput("delete from tm_penyerapan where id_penyerapan = '$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-penyerapan'));
}

//master data sanksi
elseif ($page == 'master-data' and $action == 'add-master-data-sanksi') {
    bukainput("insert into tm_sanksi set id_sanksi = '', nama_sanksi = '$_POST[nama_sanksi]', masa_aktif = '$_POST[masa_aktif]', nilai_sanksi = '$_POST[nilai_sanksi]'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-sanksi'));
} elseif ($page == 'master-data' and $action == 'update-master-data-sanksi') {
    bukainput("update tm_sanksi set nama_sanksi = '$_POST[nama_sanksi]', masa_aktif = '$_POST[masa_aktif]', nilai_sanksi = '$_POST[nilai_sanksi]' where id_sanksi = '$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-sanksi'));
} elseif ($page == 'master-data' and $action == 'delete-master-data-sanksi') {
    hapusinput("delete from tm_sanksi where id_sanksi = '$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-data-sanksi'));
}

//import data pegawai
elseif ($page == 'master-data' and $action == 'upload-data-pegawai') {
    if (isset($_POST['import'])) { // Jika user mengklik tombol Import
        $nama_file_baru = 'data.xlsx';

        // Load librari PHPExcel nya
        require_once '../../libs/PHPExcel/PHPExcel.php';

        $excelreader = new PHPExcel_Reader_Excel2007();
        $loadexcel = $excelreader->load('../../tmp/' . $nama_file_baru); // Load file excel yang tadi diupload ke folder tmp
        $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true, true);
        $numrow = 1;
        foreach ($sheet as $row) {
            // Ambil data pada excel sesuai Kolom
            $id_user = isset($row['A']) ? $row['A'] : null; // Ambil data id
            $nip = isset($row['B']) ? $row['B'] : null; // Ambil data nip
            $nik = isset($row['C']) ? $row['C'] : null;
            $nama = isset($row['D']) ? $row['D'] : null;
            $tempat_lahir = isset($row['E']) ? $row['E'] : null;
            $tgl_lahir = isset($row['F']) ? $row['F'] : null;
            $jk = isset($row['G']) ? $row['G'] : null;
            $alamat = isset($row['H']) ? $row['H'] : null;
            $npwp = isset($row['I']) ? $row['I'] : null;
            $norek = isset($row['J']) ? $row['J'] : null;
            $pendidikan = isset($row['K']) ? $row['K'] : null;
            $tmt = isset($row['L']) ? $row['L'] : null;
            $status_nikah = isset($row['M']) ? $row['M'] : null;
            $rumpun = isset($row['N']) ? $row['N'] : null;
            $pajak = isset($row['O']) ? $row['O'] : null;
            $id_unit = isset($row['P']) ? $row['P'] : null;
            $bpjs_ks = isset($row['Q']) ? $row['Q'] : null;
            $bpjs_jkk = isset($row['R']) ? $row['R'] : null;
            $bpjs_ijht = isset($row['S']) ? $row['S'] : null;
            $bpjs_jp = isset($row['T']) ? $row['T'] : null;
            $foto = isset($row['U']) ? $row['U'] : null;
            $status_kepegawaian = isset($row['V']) ? $row['V'] : null;
            $sub_bagian = isset($row['W']) ? $row['W'] : null;
            $log_finger = isset($row['X']) ? $row['X'] : null;
            $aktif = isset($row['Y']) ? $row['Y'] : null;
            $id_level = isset($row['Z']) ? $row['Z'] : null;
            $id_kasatpel = isset($row['AA']) ? $row['AA'] : null;
            $password = paramEncrypt('123456');

            // Cek jika semua data tidak diisi
            if (empty($id_user) && empty($nip) && empty($nik) && empty($nama) && empty($tempat_lahir) && empty($tgl_lahir) && empty($jk) && empty($alamat) && empty($npwp) && empty($norek) && empty($pendidikan) && empty($tmt) && empty($status_nikah) && empty($rumpun) && empty($pajak) && empty($id_unit) && empty($bpjs_ks) && empty($bpjs_jkk) && empty($bpjs_jp) && empty($foto) && empty($status_kepegawaian) && empty($sub_bagian) && empty($log_finger) && empty($aktif) && empty($id_level) && empty($id_kasatpel))
                continue; // Lewat data pada baris ini (masuk ke looping selanjutnya / baris selanjutnya)




                
// Cek $numrow apakah lebih dari 1
            // Artinya karena baris pertama adalah nama-nama kolom
            // Jadi dilewat saja, tidak usah diimport
            if ($numrow > 1) {

                bukainput2("insert into tm_user set id_user = '$id_user', password = '$password', id_level = '$id_level'");
                bukainput2("insert into tm_pegawai set "
                        . "id_user = '$id_user', "
                        . "nip = '$nip', "
                        . "nik = '$nik', "
                        . "nama_pegawai = '$nama', "
                        . "tempat_lahir = '$tempat_lahir', "
                        . "tgl_lahir = '$tgl_lahir', "
                        . "jk = '$jk', "
                        . "alamat = '$alamat', "
                        . "npwp = '$npwp', "
                        . "no_rek = '$norek', "
                        . "pendidikan = '$pendidikan', "
                        . "tgl_masuk = '$tmt', "
                        . "status_nikah = '$status_nikah', "
                        . "rumpun = '$rumpun', "
                        . "pajak = '$pajak', "
                        . "id_unit = '$id_unit', "
                        . "bpjs_ks = '$bpjs_ks', "
                        . "bpjs_jkk = '$bpjs_jkk', "
                        . "bpjs_ijht = '$bpjs_ijht', "
                        . "bpjs_jp = '$bpjs_jp', "
                        . "no_bpjs = '-', "
                        . "foto = '-', "
                        . "id_kasatpel = '$id_kasatpel', "
                        . "status_pegawai = '$status_kepegawaian', "
                        . "sub_bagian = '$sub_bagian', "
                        . "log_finger = '$log_finger', "
                        . "agama = '-', "
                        . "status = 'AKTIF'");
            }

            $numrow++; // Tambah 1 setiap kali looping
        }
    }
    echo "<script>alert('data berhasil di Upload !')'</script>";
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=upload-data-pegawai'));
}

//master data skp
elseif ($page == 'master-data' and $action == 'add-master-data-skp') {
    $autonumber = notahun('kd_skp', 'tm_skp');
    bukainput("insert into tm_skp set kd_skp='$autonumber', skp='$_POST[skp]', waktu='$_POST[waktu]'");
    header('location:../../page-view?' . paramEncrypt('module = master-data&act = list-master-data-skp'));
} elseif ($page == 'master-data' and $action == 'update-master-data-skp') {
    bukainput("update tm_skp set skp='$_POST[skp]',waktu='$_POST[skp]' where kd_skp='$id'");
    header('location:../../page-view?' . paramEncrypt('module = master-data&act = list-master-data-skp'));
} elseif ($page == 'master-data' and $action == 'delete-master-data-skp') {
    hapusinput("delete from tm_skp where kd_skp='$id'");
    header('location:../../page-view?' . paramEncrypt('module = master-data&act = list-master-data-skp'));
}

//master data skp
elseif ($page == 'master-data' and $action == 'add-master-data-izin-belajar') {
    $autonumber = notahun('id_belajar', 'tm_izin_belajar');
    $tanggal = FormatTgl('Y-m-d', $_POST['tanggal_izin_belajar']);
    bukainput("insert into tm_izin_belajar set id_belajar='$autonumber', id_user='$_POST[id_user]', nama_univ='$_POST[nama_univ]', alamat_univ='$_POST[alamat_univ]',"
            . "pendidikan_sebelum='$_POST[pendidikan_sebelum]', pendidikan_sesudah='$_POST[pendidikan_sesudah]', jurusan='$_POST[jurusan]', akreditasi='$_POST[akreditasi]',"
            . "jenis_peningkatan='$_POST[jenis_peningkatan]', no_izin='$_POST[no_izin]', tanggal_izin_belajar='$tanggal'");
    header('location:../../page-view?' . paramEncrypt('module = master-data&act = list-master-izin-belajar'));
} elseif ($page == 'master-data' and $action == 'update-master-data-izin-belajar') {
    $tanggal = FormatTgl('Y-m-d', $_POST['tanggal_izin_belajar']);
    bukainput("update tm_izin_belajar set id_user='$_POST[id_user]', nama_univ='$_POST[nama_univ]', alamat_univ='$_POST[alamat_univ]',"
            . "pendidikan_sebelum='$_POST[pendidikan_sebelum]', pendidikan_sesudah='$_POST[pendidikan_sesudah]', jurusan='$_POST[jurusan]', akreditasi='$_POST[akreditasi]',"
            . "jenis_peningkatan='$_POST[jenis_peningkatan]', no_izin='$_POST[no_izin]', tanggal_izin_belajar='$tanggal' where id_belajar='$id'");
    header('location:../../page-view?' . paramEncrypt('module = master-data&act = list-master-izin-belajar'));
} elseif ($page == 'master-data' and $action == 'delete-master-data-izin-belajar') {
    hapusinput("delete from tm_izin_belajar where id_belajar='$id'");
    header('location:../../page-view?' . paramEncrypt('module = master-data&act = list-master-izin-belajar'));
}

//master data shidt
elseif ($page == 'master-data' and $action == 'add-master-data-absensi') {
    $autonumber = autonomer('tm_shift', 'id_absensi', 'ABS-', '10');
    foreach($_POST['list_unit_terpilih'] as $row){
        
        bukainput("INSERT INTO tt_jadwalpegawai_unit_shift (id_absensi, id_unit) VALUES ('".$autonumber."', '".$row."')");
    };
    bukainput("insert into tm_shift set id_absensi='$autonumber', nama_shift='$_POST[nama_shift]', desc_shift='$_POST[desc_shift]', working_time_minute='$_POST[working_time_minute]', hex_color_shift='".str_replace('#', '', $_POST['hex_color_shift'])."', jam_masuk='$_POST[jam_masuk]', jam_pulang='$_POST[jam_pulang]', ai='$_POST[ai]', bi='$_POST[bi]', ao='$_POST[ao]', bo='$_POST[bo]', shift_tipe='$_POST[shift_tipe]' ");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-absensi'));
} elseif ($page == 'master-data' and $action == 'update-master-data-absensi') {
    
    bukainput("DELETE FROM tt_jadwalpegawai_unit_shift WHERE id_absensi = '".$id."'");
    foreach($_POST['list_unit_terpilih'] as $row){
        
        bukainput("INSERT INTO tt_jadwalpegawai_unit_shift (id_absensi, id_unit) VALUES ('".$id."', '".$row."')");
    };
    bukainput("update tm_shift set nama_shift='$_POST[nama_shift]', desc_shift='$_POST[desc_shift]', working_time_minute='$_POST[working_time_minute]', hex_color_shift='".str_replace('#', '', $_POST['hex_color_shift'])."', jam_masuk='$_POST[jam_masuk]', jam_pulang='$_POST[jam_pulang]', ai='$_POST[ai]', bi='$_POST[bi]', ao='$_POST[ao]', bo='$_POST[bo]', shift_tipe='$_POST[shift_tipe]' where id_absensi='$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-absensi'));
} elseif ($page == 'master-data' and $action == 'delete-master-data-absensi') {
    hapusinput("delete from tm_shift where id_absensi='$id'");
    hapusinput("DELETE FROM tt_jadwalpegawai_unit_shift WHERE id_absensi = '".$id."'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-absensi'));
}

//set spj
elseif ($page == 'master-data' and $action == 'add-set-spj') {
    bukainput("insert into set_spj set id_spj='SPJ-00001', ppk_keuangan='$_POST[ppk_keuangan]', bendahara_pengeluaran='$_POST[bendahara_pengeluaran]' ");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=set-spj'));
} elseif ($page == 'master-data' and $action == 'update-set-spj') {
    bukainput("update set_spj set ppk_keuangan='$_POST[ppk_keuangan]', bendahara_pengeluaran='$_POST[bendahara_pengeluaran]' where id_spj='$id' ");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=set-spj'));
}

//grade
elseif ($page == 'master-data' and $action == 'update-grade') {
    bukainput("update tm_level set grade_kinerja='$_POST[grade_kinerja]',grade_prilaku='$_POST[grade_prilaku]' where id_level='$id'");
    header('location:../../page-view?' . paramEncrypt('module=master-data&act=list-master-grade'));
}

//master data ketidakhadiran shift
elseif($page == 'master-data' and $action == 'add-master-data-shift-ketidakhadiran') {
    $autonumber = autonomer('tm_shift_ketidakhadiran', 'id_ketidakhadiran', 'AKT-', '10');
    bukainput("INSERT INTO tm_shift_ketidakhadiran (id_ketidakhadiran, nama_ketidakhadiran, desc_ketidakhadiran, id_ketidakhadiran_tipe, hex_color_ketidakhadiran, is_show_for_pj) VALUES ('".$autonumber."', '".$_POST['ketidakhadiran_nama']."', '".$_POST['ketidakhadiran_desc']."', '".$_POST['ketidakhadian_tipe']."', '".$_POST['ketidakhadiran_hexcolor']."', '".$_POST['is_show_for_pj']."') ");
    header('location:../../page-view?'.paramEncrypt('module=master-data&act=list-master-shift-ketidakhadiran'));
} elseif($page == 'master-data' and $action == 'edit-master-data-shift-ketidakhadiran') {
    bukainput("UPDATE tm_shift_ketidakhadiran SET nama_ketidakhadiran = '".$_POST['ketidakhadiran_nama']."', desc_ketidakhadiran='".$_POST['ketidakhadiran_desc']."', hex_color_ketidakhadiran = '".$_POST['ketidakhadiran_hexcolor']."', is_show_for_pj = '".$_POST['is_show_for_pj']."' WHERE id_ketidakhadiran = '".$id."' ");
    header('location:../../page-view?'.paramEncrypt('module=master-data&act=list-master-shift-ketidakhadiran'));
} elseif($page == 'master-data' and $action == 'delete-master-data-shift-ketidakhadiran') {
    bukaquery("DELETE FROM tm_shift_ketidakhadiran WHERE id_ketidakhadiran = '".$id."' ");
    header('location:../../page-view?'.paramEncrypt('module=master-data&act=list-master-shift-ketidakhadiran'));
}

//master data hari libur
elseif($page == 'master-data' and $action == 'add-master-hari-libur') {
    
    $autonumber = notahun('id_hari_libur', 'tm_hari_libur');
    bukainput("INSERT INTO tm_hari_libur (id_hari_libur, tanggal, cuti_bersama, keterangan, id_kepegawaian, created) VALUES ('".$autonumber."', '".date_format(date_create($_POST['tanggal']), 'Y-m-d')."', '$_POST[cuti_bersama]', '".$_POST['keterangan']."', '".$_POST['id_kepegawaian']."', NOW())");
    header('location:../../page-view?'.paramEncrypt('module=master-data&act=list-master-hari-libur'));
} elseif($page == 'master-data' and $action == 'edit-master-hari-libur') {
    
    bukainput("UPDATE tm_hari_libur SET keterangan = '".$_POST['keterangan']."', cuti_bersama = '".$_POST['cuti_bersama']."' WHERE id_hari_libur = '".$id."' ");
    header('location:../../page-view?'.paramEncrypt('module=master-data&act=list-master-hari-libur'));
} elseif($page == 'master-data' and $action == 'delete-master-hari-libur') {
    
    bukainput("DELETE FROM tm_hari_libur WHERE id_hari_libur = '".$id."' ");
    header('location:../../page-view?'.paramEncrypt('module=master-data&act=list-master-hari-libur'));
}

//master data hari raya
elseif($page == 'master-data' and $action == 'add-master-hari-raya') {
    
    $autonumber = notahun('id_hari_raya', 'tm_hari_raya');
    bukainput("INSERT INTO tm_hari_raya (id_hari_raya, tanggal, keterangan, id_kepegawaian, created) VALUES ('".$autonumber."', '".date_format(date_create($_POST['tanggal']), 'Y-m-d')."', '".$_POST['keterangan']."', '".$_POST['id_kepegawaian']."', NOW())");
    header('location:../../page-view?'.paramEncrypt('module=master-data&act=list-master-hari-raya'));
} elseif($page == 'master-data' and $action == 'edit-master-hari-raya') {
    
    bukainput("UPDATE tm_hari_raya SET keterangan = '".$_POST['keterangan']."' WHERE id_hari_raya = '".$id."' ");
    header('location:../../page-view?'.paramEncrypt('module=master-data&act=list-master-hari-raya'));
} elseif($page == 'master-data' and $action == 'delete-master-hari-raya') {
    
    bukainput("DELETE FROM tm_hari_raya WHERE id_hari_raya = '".$id."' ");
    header('location:../../page-view?'.paramEncrypt('module=master-data&act=list-master-hari-raya'));
}

//master kuota cuti
elseif($page == 'master-data' and $action == 'add-kuota-cuti') {
    
    $sql = bukaquery2("
        SELECT
            a.id_kuota
        FROM tm_hari_kuota_cuti AS a
        WHERE a.tanggal = '".date_format(date_create($_POST['tanggal']), 'Y-m-d')."'
            AND a.id_ketidakhadiran = '".$_POST['id_ketidakhadiran']."'
    ");
    $exist = fetch_array($sql)['id'];

    if($exist == NULL) {

        bukainput("INSERT INTO tm_hari_kuota_cuti (tanggal, hari, id_ketidakhadiran, kuota, id_kepegawaian, created) 
            VALUES ('".date_format(date_create($_POST['tanggal']), 'Y-m-d')."', '".$_POST['hari']."', '".$_POST['id_ketidakhadiran']."', '".$_POST['kuota']."', '".$_POST['id_kepegawaian']."', NOW())");
    }
    
    header('location:../../page-view?'.paramEncrypt('module=master-data&act=list-master-kuota-cuti'));
} elseif($page == 'master-data' and $action == 'edit-kuota-cuti') {
    
    bukainput("UPDATE tm_hari_kuota_cuti SET kuota = '".$_POST['kuota']."' WHERE id_kuota = '".$id."' ");
    header('location:../../page-view?'.paramEncrypt('module=master-data&act=list-master-kuota-cuti'));
} elseif($page == 'master-data' and $action == 'delete-kuota-cuti') {
    
    bukainput("DELETE FROM tm_hari_kuota_cuti WHERE id_kuota = '".$id."' ");
    header('location:../../page-view?'.paramEncrypt('module=master-data&act=list-master-kuota-cuti'));
}
?>