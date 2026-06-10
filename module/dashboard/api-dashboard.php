<?php

require_once ('../../conf/conf.php');
require_once ('../../libs/aes-encrypt/function.php');

class ApiDashboard {

    function post_slipgaji_request_user($id_user, $id_penilaian) {

        // ambil id_slipgaji_order
        $sql = bukaquery2("
            SELECT
                a.id_slipgaji_order
            FROM tm_slipgaji_order a
            WHERE a.is_start_option_keuangan = '1'
        ");
        $id_slipgaji_order = fetch_array($sql)['id_slipgaji_order'];

        // ambil id_keuangan
        $sql = bukaquery2("
            SELECT
                a.slipgaji_receiver_req, b.no_hp_wa
            FROM setup a
                INNER JOIN tm_pegawai b ON a.slipgaji_receiver_req = b.id_user
        ");
        $result = fetch_array($sql);
        $id_keuangan = $result['slipgaji_receiver_req'];
        $no_keuangan = $result['no_hp_wa'];

        // ambil nama_pegawai dari pengirim
        $sql = bukaquery2("
            SELECT
                a.nama_pegawai
            FROM tm_pegawai a
            WHERE a.id_user = '".$id_user."'
        ");
        $nm_pengirim = fetch_array($sql)['nama_pegawai'];

        // insert into tt_slipgaji_req
        bukaquery2("
            INSERT INTO tt_slipgaji_req (id_user, id_penilaian, id_keuangan, id_slipgaji_order, created)
            VALUES ('".$id_user."', '".$id_penilaian."', '".$id_keuangan."', '".$id_slipgaji_order."', NOW())
        ");

        // kirim notif ke wa id_keuangan
        $pesan = "E-Kinerja Non-PNS RSUDTA\n\nAnda memiliki permintaan Slip Gaji dari ".$nm_pengirim.".\n\n(ini adalah pesan otomatis)\n".$this->get_current_datetime();
        $this->send_notif_wa($no_keuangan, $pesan);

        return array(
            "status" => 1,
            "message" => "Berhasil mengirim permintaan Slip Gaji"
        );
    }

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
            'status' => '401',
            'message' => 'Invalid Action'
        );
    }
}
$api = new ApiDashboard();
$action = isset($_GET['action']) ? $_GET['action'] : null;
switch ($action) {
    case 'post_slipgaji_request_user':
        echo json_encode($api->post_slipgaji_request_user(
            $_POST['id_user'],  
            $_POST['id_penilaian']
        ));
        break;
    
    default:
        echo json_encode($api->invalid_action());
        break;
}
?>