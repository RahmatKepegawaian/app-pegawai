<?php

require_once('conf/conf.php');
require_once('libs/aes-encrypt/function.php');

class ApiMedia {

    function act_click_notification_pj($id_jadwalkerja_shift_notification, $id_unit, $month, $year) {
        
        //update read_status and click_time in tm_jadwalpegawai_shift_notification
        bukaquery2("UPDATE tm_jadwalpegawai_shift_notification
            SET read_status = 1, click_time = NOW()
            WHERE id_jadwalkerja_shift_notification = ".$id_jadwalkerja_shift_notification."
        ");

        //redirect to set-jadwalshift pages
        header("location:page-view?".paramEncrypt("module=master-data&act=set-jadwalshift&sft_unit_slctd=".$id_unit."&sft_bln_slctd=".$month."&sft_year_slctd=".$year));
    }

    function invalid_action() {
        return array(
            'status' => '401',
            'message' => 'Invalid Action'
        );
    }
}

$apiMedia = new ApiMedia();
$uri = isset($_SERVER['REQUEST_URI']) ? decode($_SERVER['REQUEST_URI']) : null;
switch ($uri['action']) {
    case 'act_click_notification_pj':
        $apiMedia->act_click_notification_pj(
            $uri['id_notification'],
            $uri['id_unit'],
            $uri['month'],
            $uri['year']
        );
        break;
    
    default:
        echo json_encode($apiMedia->invalid_action());
        break;
}
?>