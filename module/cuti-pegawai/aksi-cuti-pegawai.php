<?php
session_start();

require_once('../../conf/conf.php');
require_once('../../libs/aes-encrypt/function.php');

$URI = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;
$url = decode($URI);
$action = isset($url['act']) ? $url['act'] : null;
$id = $_POST['id'];
$link = $_POST['link'];

$kunci_wa_blast = true;
$kunci_wa_blast = false;


//untuk cuti pegawai
if ($action == 'asesmen-cuti') {
    global $url_api_notif_wa;

    $acc = $_POST['acc'];
    $kolom_acc = $_POST['kolom_acc'];
    $kolom_tgl_acc = $_POST['kolom_tgl_acc'];
    $jenis_cuti = $_POST['jenis_cuti'];
    $reject_notes = isset($_POST['reject_notes']) ? ", alasan = '$_POST[reject_notes]', aktif = 0" : null;
    $reject_nots = isset($_POST['reject_notes']) ? $_POST['reject_notes'] : null;
    $no_surti = !empty($_POST['no_surti']) ? ", no_surti='$_POST[no_surti]'" : null;
    $tgl_acc = !empty($_POST['tgl_acc']) ? $_POST['tgl_acc'] : null;
    $jam_acc = !empty($_POST['jam_acc']) ? $_POST['jam_acc'] : null;
    $tgl_jam_acc = $tgl_acc == null || $jam_acc == null 
        ? date('Y-m-d H:i:s')
        : date('Y-m-d H:i:s', strtotime($tgl_acc .' '. $jam_acc));
    $link_header = $_POST['link_header'];

    bukaquery2("
        update tm_cuti 
        set 
            $kolom_acc = '$acc', 
            $kolom_tgl_acc = '$tgl_jam_acc'
            $reject_notes 
            $no_surti
        where id_cuti='$id'
    ");

    if($acc == 'T') {
        // jika acc = T, update status cuti jadi tidak aktif
        bukaquery2("UPDATE tm_cuti SET aktif = 0 WHERE id_cuti = '$id'");
    }

    // jika kolom acc_direktur jadi Y. update tm_jadwalpegawai_shift_m jadi cuti
    if($kolom_acc == 'acc_direktur' && $acc == 'Y') {

        // cari list tanggal cuti nya dan id_ketidakhadiran
        $sql = bukaquery2("
            SELECT
                a.tanggal, b.id_ketidakhadiran, b.id_user
            FROM tm_hari_cuti AS a
                INNER JOIN tm_cuti AS b ON b.id_cuti = a.id_cuti
            WHERE a.id_cuti = '".$id."'
        ");
        while ($row = fetch_assoc($sql)) {
            
            // update di tm_jadwalpegawai_shift_m jadi sesuai ketidakhadiran
            bukaquery("
                UPDATE tm_jadwalpegawai_shift_m
                SET id_absensi = '', id_absensi_tipe = '', id_ketidakhadiran = '".$row['id_ketidakhadiran']."'
                WHERE id_user = '".$row['id_user']."'
                    AND date = '".$row['tanggal']."'
            ");
        }
    }

    $notes = serialize(array(
        "acc" => $acc,
        "jenis_cuti" => $jenis_cuti,
        "reject_nots" => $reject_nots,
        "catatan" => ""
    ));
    bukaquery2("INSERT INTO tm_cuti_validasi_log(id_cuti, id_user, `data`) VALUES($id, $_POST[id_user], '$notes')");


    if ($kunci_wa_blast) {
        // kirim notif ke pengirim dan pj
        $sql = bukaquery2("
            SELECT
                b.no_hp_wa AS wa_pj,
                c.nama_pegawai AS nm_pengirim,
                c.no_hp_wa AS no_pengirim,
                d.nama_pegawai AS nm_pengganti
            FROM tm_cuti a
                INNER JOIN tm_pegawai b ON a.id_user_pj = b.id_user
                INNER JOIN tm_pegawai c ON a.id_user = c.id_user
                INNER JOIN tm_pegawai d ON a.id_user_pengganti = d.id_user
            WHERE a.id_cuti = '" . $id . "'
        ");
        $res = fetch_array($sql);
        $nm_pengirim = $res['nm_pengirim'];
        $nohp_pj = $res['wa_pj'];
        $nohp_pengirim = $res['no_pengirim'];
        $nm_pengganti = $res['nm_pengganti'];



        // kirim notif ke id_user_pj
        // bahwa memiliki permintaan validasi
        $pesan = "E-Kinerja Non-PNS RSUDTA\n\nAnda memiliki notifikasi permintaan validasi Tukar Dinas dari " . $nm_pengirim . ".\n\n(ini adalah pesan otomatis)\n" . date('Y-m-d H:i:s') . "";
        wa_blast($nohp_pj, $pesan, $url_api_notif_wa);

        // kiriim notif ke id_user
        // bahwa permintaan telah disetujui oleh pengganti

        $sql = bukaquery2("
            SELECT
                c.nama_pegawai AS nm_pengirim,
                c.no_hp_wa AS no_pengirim,
                b.no_hp_wa AS wa_pj,
                b.nama_pegawai AS nm_pj,
                d.nama_pegawai AS nm_pengganti
            FROM tm_cuti a
                INNER JOIN tm_pegawai b ON a.id_user_pj = b.id_user
                INNER JOIN tm_pegawai c ON a.id_user = c.id_user
                INNER JOIN tm_pegawai d ON a.id_user_pengganti = d.id_user
            WHERE a.id_cuti = '" . $id . "'
        ");

        $pesan = "E-Kinerja Non-PNS RSUDTA\n\nPermintaan Tukar Dinas anda telah disetujui oleh " . $nm_pengganti . ".\n\n(ini adalah pesan otomatis)\n" . date('Y-m-d H:i:s') . "";
        wa_blast($nohp_pengirim, $pesan, $url_api_notif_wa);
    }

    header('location:../../page-view?' . paramEncrypt($link_header));
    die();
} elseif ($action == 'delete-pengajuan-cuti') {
    bukaquery2("
        DELETE c.*, hc.* 
        FROM tm_cuti c
        INNER JOIN tm_hari_cuti hc ON c.id_cuti = hc.id_cuti 
        WHERE c.id_cuti = $id
    ");

    if ($link == 'semua')
        header('location:../../page-view?' . paramEncrypt('module=cuti-pegawai&act=list-data-semua-cuti-pegawai'));
    else
        header('location:../../page-view?' . paramEncrypt('module=cuti-pegawai&act=list-data-pengajuan-cuti-pegawai'));
}


function wa_blast($nohp, $pesan, $url)
{
    // $curlHandle = curl_init();
    // curl_setopt($curlHandle, CURLOPT_URL, $url);
    // curl_setopt($curlHandle, CURLOPT_POSTFIELDS, "nohp=" . $nohp . "&pesan=" . $pesan);
    // curl_setopt($curlHandle, CURLOPT_HEADER, 0);
    // curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
    // curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
    // curl_setopt($curlHandle, CURLOPT_POST, 1);
    // $output = curl_exec($curlHandle);
    // curl_close($curlHandle);

    // return $output;
    return null;
}
