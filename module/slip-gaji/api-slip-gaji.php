<?php

require_once ('../../conf/conf.php');
require_once ('../../libs/aes-encrypt/function.php');

class ApiSlipGaji {

    function send_notif_wa($nohp, $pesan) {

        global $url_api_notif_wa;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_api_notif_wa);
        curl_setopt($ch, CURLOPT_PORT, 8041);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            'nohp' => $nohp,
            'pesan' => $pesan
        ));

        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $result;
    }

    function get_current_datetime() {
        return date('Y-m-d H:i:s');
    }

    function invalid_action() {
        return array(
            "status" => 401,
            "message" => "Invalid Action"
        );
    }
}

$api = new ApiSlipGaji();
$action = isset($_GET['action']) ? $_GET['action'] : null;
switch ($action) {
    default:
        echo json_encode($api->invalid_action());
        break;
}
?>