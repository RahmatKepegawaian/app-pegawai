<?php

require_once ('../../conf/conf.php');
require_once ('../../libs/aes-encrypt/function.php');
$URI = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;
$url = decode($URI);
$page = isset($url['module']) ? $url['module'] : null;
$action = isset($url['act']) ? $url['act'] : null;

// tukar-shift
if($page == 'tukar-shift' and $action == "add-tukar-shift") {

    $id = nokiamat("id_tukarshift", "tt_tukarshift");
    $id_sender = $_POST['tukarshift_idsender'];
    $id_receiver = $_POST['tukarshift_idreceiver'];
    $id_jadwalkerja_shift_1 = $_POST['tukarshift_shift_1'];
    $id_jadwalkerja_shift_2 = $_POST['tukarshift_shift_2'];
    $keperluan = $_POST['tukarshift_keperluan'];
    bukaquery2("
        INSERT INTO tt_tukarshift (id_tukarshift, id_sender, id_receiver, id_jadwalkerja_shift_1, id_jadwalkerja_shift_2, keperluan, status, created)
        VALUES
        ('".$id."', '".$id_sender."', '".$id_receiver."', '".$id_jadwalkerja_shift_1."', '".$id_jadwalkerja_shift_2."', '".$keperluan."', 1, NOW())
    ");
    header('location:../../page-view?' . paramEncrypt('module=tukar-shift&act=tukar-shift'));
} else if($page == 'tukar-shift' and $action == "edit-tukar-shift") {

} else if($page == 'tukar-shift' and $action == "delete-tukar-shift") {

    // hapus di tt_tukarshift
    bukaquery2("
        DELETE
        FROM tt_tukarshift 
        WHERE id_tukarshift = '".$_POST['tukar-shift_id']."'
    ");

    // hapus di tt_tukarshift_validation
    bukaquery2("
        DELETE
        FROM tt_tukarshift_validation
        WHERE id_tukarshift = '".$_POST['tukar-shift_id']."'
    ");

    // hpaus di tt_tukarshift_log
    bukaquery2("
        DELETE
        FROM tt_tukarshift_log
        WHERE id_tukarshift = '".$_POST['tukar-shift_id']."'
    ");
    
    header('location:../../page-view?' . paramEncrypt('module=tukar-shift&act=tukar-shift-add'));
}
?>