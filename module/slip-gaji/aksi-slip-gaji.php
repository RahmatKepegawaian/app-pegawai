<?php
require_once ('../../conf/conf.php');
require_once ('../../libs/aes-encrypt/function.php');

$URI = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;
$url = decode($URI);
$page = isset($url['module']) ? $url['module'] : null;
$action = isset($url['act']) ? $url['act'] : null;
$id = isset($url['id']) ? $url['id'] : null;

// slip-gaji
if($page == 'slip-gaji' and $action = 'update-status-keuangan') {

    // update atau insert ke tt_pph21
    $is_exist = fetch_array(bukaquery2("
        SELECT
            COUNT(a.id_pph21) AS res
        FROM tt_pph21 a
        WHERE a.id_user = '".$_POST['id_pegawai']."'
            AND a.bulan = '".$_POST['month']."'
            AND a.tahun = '".$_POST['year']."'
    "))['res'];

    // apbaila sudah ada, maka update. selain itu insert
    if($is_exist != 0) {

        bukaquery2("
            UPDATE tt_pph21
            SET pph21 = '".$_POST['value_pph21']."', id_keuangan = '".$_POST['id_keuangan']."', created = NOW()
            WHERE id_user = '".$_POST['id_pegawai']."' AND bulan = ".$_POST['month']." AND tahun = ".$_POST['year']."
        ");
    } else {

        bukaquery2("
            INSERT INTO tt_pph21 (id_user, id_keuangan, pph21, bulan, tahun, created)
            VALUES ('".$_POST['id_pegawai']."', '".$_POST['id_keuangan']."', '".$_POST['value_pph21']."', ".$_POST['month'].", ".$_POST['year'].", NOW())
        ");
    }

    
    // update status
    bukainput("
        UPDATE
            tt_slipgaji_req
        SET id_slipgaji_order = '".$_POST['id_slipgaji_order_upd']."'
        WHERE id_slipgaji_request = '".$id."'
    ");

    // kirim notif ke pengirim
    $desc_status = getOne("
        SELECT
            a.desc_status
        FROM tm_slipgaji_order a
        WHERE a.id_slipgaji_order = '".$_POST['id_slipgaji_order_upd']."'
    ");

    $nohp = $_POST['no_pengirim'];
    $pesan = "E-Kinerja Non-PNS RSUDTA\n\nStatus Permintaan Slp Gaji anda telah diperbarui menjadi *".$desc_status."*\n\n(ini adalah pesan otomatis)\n".date('Y-m-d H:i:s')."";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url_api_notif_wa);
    curl_setopt($ch, CURLOPT_PORT, 8041);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'nohp' => $nohp,
        'pesan' => $pesan
    ));
    curl_exec($ch);
    curl_close($ch);


    header('location:../../page-view?' . paramEncrypt('module=slip-gaji&act=list-req-slip-gaji-keuangan'));
}
?>