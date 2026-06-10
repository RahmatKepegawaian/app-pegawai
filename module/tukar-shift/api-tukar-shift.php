<?php

require_once ('../../conf/conf.php');
require_once ('../../libs/aes-encrypt/function.php');

class ApiTukarShift {
    
    function get_validation_status_add($id_sender, $id_unit, $month, $year) {

        $sql = bukaquery2("
            SELECT 
                IF(COUNT(a.id_jadwalkerja_shift_validation) > 0, true, false) AS result
            FROM tm_jadwalpegawai_shift_validation a
            WHERE a.month = ".$month."
                AND a.year = ".$year." 
                AND a.id_unit = '".$id_unit."' 
                AND a.answered = 1
        ");
        $bool_add_tukar_shift = fetch_array($sql)['result'];

        if($bool_add_tukar_shift) {

            $res = array();

            $sql = bukaquery2("
                SELECT
                    a.id_jadwalkerja_shift, a.date, 
                    a.id_absensi, b.desc_shift, b.jam_masuk, b.jam_pulang
                FROM tm_jadwalpegawai_shift_m a
                    INNER JOIN tm_shift b ON a.id_absensi = b.id_absensi
                WHERE a.id_user = '".$id_sender."'
                    AND MONTH(a.date) = ".$month."
                    AND YEAR(a.date) = ".$year."
                    AND a.id_unit = '".$id_unit."'
                    AND a.id_absensi <> ''
                    AND a.id_absensi IS NOT NULL
                ORDER BY a.date
            ");

            while ($row = fetch_assoc($sql)) {
                array_push($res, $row);
            }

            return array(
                "status" => count($res) != 0 ? 1 : 0,
                "message" => count($res) != 0 ? "Data ditemukan" : "Data tidak ditemukan",
                "data" => $res
            );


        } else {

            return array(
                "status" => 0,
                "message" => "Tukar Shift belum diijinkan untuk bulan ini"
            );
        }
    }

    function get_list_user_tukarshift($month, $year, $id_unit, $id_sender) {

        $res = array();

        $sql = bukaquery2("
            SELECT
                a.id_user, b.nama_pegawai
            FROM tm_jadwalpegawai_shift_m a
                INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                INNER JOIN tm_user c ON b.id_user = c.id_user
            WHERE a.id_unit = '".$id_unit."'
                AND a.month = '".$month."'
                AND a.year = '".$year."'
                AND c.id_level = 'LVL-000003'
                AND a.id_user <> '".$id_sender."'
            GROUP BY a.id_user
            ORDER BY b.nama_pegawai
        ");

        while ($row = fetch_assoc($sql)) {
            array_push($res, $row);
        }

        return array(
            "status" => count($res) != 0 ? 1 : 0,
            "message" => count($res) != 0 ? "Data Ditemukan" : "Data tidak ditemukan",
            "data" => $res
        );
    }

    function get_list_shift_tukarshift($id_unit, $month, $year, $id_receiver) {

        $res = array();

        $sql = bukaquery2("
            SELECT
                a.id_jadwalkerja_shift, a.date, 
                b.desc_shift, b.jam_masuk, b.jam_pulang
            FROM tm_jadwalpegawai_shift_m a
                INNER JOIN tm_shift b ON a.id_absensi = b.id_absensi
            WHERE a.id_unit = '".$id_unit."'
                AND a.`month` = ".$month."
                AND a.`year` = ".$year."
                AND a.id_user= '".$id_receiver."'
                AND a.id_absensi <> ''
                AND a.id_absensi IS NOT NULL
            ORDER BY a.date
        ");

        while ($row = fetch_assoc($sql)) {
            array_push($res, $row);
        }

        return array(
            "status" => count($res) != 0 ? 1 : 0,
            "message" => count($res) != 0 ? "Data Ditemukan" : "Data tidak ditemukan",
            "data" => $res
        );
    }

    function get_log_tukarshift($id_tukarshift) {

        $result = array();

        $sql = bukaquery2("
            SELECT
                a.data, a.created
            FROM tt_tukarshift_log a
            WHERE a.id_tukarshift = '".$id_tukarshift."'
            ORDER BY a.created DESC
        ");

        while ($row = fetch_array($sql)) {

            array_push($result, array(
                "keterangan" => unserialize($row['data'])['keterangan'],
                "created" => $row['created']
            ));
        }
        // exit;

        return array(
            "status" => count($result) != 0 ? 1 : 0,
            "message" => count($result) != 0 ? "Data ditemukan" : "Data tidak ditemukan",
            "data" => $result
        );
    }

    function post_answer_validation_by_receiver($id_tukarshift_validation, $answer) {

        // upate tt_tukarshift_validation. sesuai dengan jawabannya. 1 atau 2
        bukaquery2("
            UPDATE 
                tt_tukarshift_validation
            SET answered = '".$answer."', timestamp_answer = NOW()
            WHERE id_tukarshift_validation = '".$id_tukarshift_validation."'
        ");

        // ambil data yang diperlukan
        $sql = bukaquery2("
            SELECT
                a.id_tukarshift, d.id_jadwalkerja_shift_1, d.id_jadwalkerja_shift_2, d.id_tukarshift_status,
                a.id_receiver, b.nama_pegawai AS nm_receiver, b.no_hp_wa AS no_wa_receiver,
                a.id_sender, c.nama_pegawai AS nm_sender, c.no_hp_wa AS no_wa_sender, c.id_unit AS unit_sender, c.id_kasatpel AS kasatpel_sender
            FROM tt_tukarshift_validation a
                INNER JOIN tm_pegawai b ON a.id_receiver = b.id_user
                INNER JOIN tm_pegawai c ON a.id_sender = c.id_user
                INNER JOIN tt_tukarshift d ON a.id_tukarshift = d.id_tukarshift
            WHERE a.id_tukarshift_validation = '".$id_tukarshift_validation."'
        ");
        $res = fetch_array($sql);
        $id_tukarshift = $res['id_tukarshift'];
        $id_tukarshift_status = $res['id_tukarshift_status'];
        $id_jadwalkerja_shift_1 = $res['id_jadwalkerja_shift_1'];
        $id_jadwalkerja_shift_2 = $res['id_jadwalkerja_shift_2'];
        $id_sender = $res['id_sender'];
        $unit_sender = $res['unit_sender'];
        $kasatpel_sender = $res['kasatpel_sender'];
        $nm_sender = $res['nm_sender'];
        $no_wa_sender = $res['no_wa_sender'];
        $nm_receiver = $res['nm_receiver'];

        // dibedakan berdasarkan jawaban yg diberikan receiver
        switch($answer) {
            case '2': // ditolak
                // update berdasarkan tipe status ditolak dan end process
                bukaquery2("
                    UPDATE tt_tukarshift
                    SET id_tukarshift_status = '4'
                    WHERE id_tukarshift = '".$id_tukarshift."'
                ");

                // masukkan notif ke tt_tukarshift_notification
                $notif_log = serialize(array(
                    "id_tukarshift" => $id_tukarshift,
                    "nm_sender" => $nm_receiver,
                    "keterangan" => "Permintaan Tukar Dinas ditolak"
                ));
                bukaquery2("
                    INSERT INTO tt_tukarshift_notification 
                        (id_user_receiver, data)
                    VALUES 
                        ('".$id_tukarshift."', '".$notif_log."')
                ");

                // kirim notifikasi wa ke pengirim
                $pesan = "E-Kinerja Non-PNS RSUDTA\n\nPermintaan Pergantian Dinas ditolak oleh ".$nm_receiver.".\n\n(ini adalah pesan otomatis)\n".$this->get_current_datetime()."";
                $this->send_notif_wa($no_wa_sender, $pesan);

                // masukkan ke tt_tukarshift_log
                // perihal pengiriman permintaan tukar shift
                $array_log = serialize(array(
                    "keterangan" => "Permintaan Tukar Dinas ditolak oleh ".$nm_receiver
                ));
                bukaquery2("
                    INSERT INTO tt_tukarshift_log
                        (id_tukarshift, data)
                    VALUES 
                        ('".$id_tukarshift."', '".$array_log."')
                ");
                break;
            case '1': // diterima
                // ambil id_tukarshift_status yg nilai ordernya lebih besar dari id_tukarshift_order saat ini
                $sql = bukaquery2("
                    SELECT
                        a.id_tukarshift_status, a.id_level_receiver
                    FROM tm_tukarshift_order a
                    WHERE a.`order` > ( SELECT
                            a.order
                        FROM tm_tukarshift_order a
                        WHERE a.id_tukarshift_status = '".$id_tukarshift_status."' )
                        AND a.end_order = '0'
                        AND a.active_process = '1'
                    ORDER BY a.order
                    LIMIT 1
                ");
                $res = fetch_array($sql);

                // jika tidak null, berarti masih ada proses selanjutnya
                // apabila null berarti tidak ada lg proses selanjutnya. yg artinya receiver ini merupakan tingakan tertinggi dlm proses tukar-shift
                if(isset($res)) {

                    $id_tukarshift_status_next = $res['id_tukarshift_status'];
                    $id_level_receiver = $res['id_level_receiver'];

                    // ambil data id_user selanjutnya
                    switch ($id_level_receiver) {
                        case 'LVL-000007': // pj
                            $sql = bukaquery2("
                                SELECT
                                    a.id_user, a.no_hp_wa
                                FROM tm_pegawai a
                                    INNER JOIN tm_user b ON a.id_user = b.id_user
                                WHERE a.id_unit = '".$unit_sender."'
                                    AND b.id_level = '".$id_level_receiver."'
                            ");
                            break;
                        case 'LVL-000006': // kasatpel
                            $sql = bukaquery2("
                                SELECT
                                    a.id_user, a.no_hp_wa
                                FROM tm_pegawai a
                                    INNER JOIN tm_user b ON a.id_user = b.id_user
                                WHERE a.id_kasatpel = '".$kasatpel_sender."'
                                    AND b.id_level = '".$id_level_receiver."'
                            ");
                            break;
                    }
                    $res = fetch_array($sql);
                    $id_receiver_next = $res['id_user'];
                    $no_wa_receiver_next = $res['no_hp_wa'];

                    // update ke tt_tukarshift
                    bukaquery2("
                        UPDATE tt_tukarshift
                        SET id_tukarshift_status = '".$id_tukarshift_status_next."'
                        WHERE id_tukarshift = '".$id_tukarshift."'
                    ");
                    
                    // kirim permintaan validasi ke id_user selanjutnya. tt_tukarshift_validation
                    bukaquery2("
                        INSERT INTO tt_tukarshift_validation 
                            (id_sender, id_receiver, id_tukarshift, answered, notes, timestamp_request)
                        VALUES
                            ('".$id_sender."', '".$id_receiver_next."', '".$id_tukarshift."', 0, '-', NOW())
                    ");

                    // kirim notifikasi wa ke pengirim bahwa telah disetujui oleh penerima saat ini
                    $pesan = "E-Kinerja Non-PNS RSUDTA\n\nPermintaan Tukar Dinas telah disetujui oleh ".$nm_receiver.".\n\n(ini adalah pesan otomatis)\n".$this->get_current_datetime()."";
                    $this->send_notif_wa($no_wa_sender, $pesan);

                    // kirim notifikasi wa ke penerima selanjutnya
                    $pesan = "E-Kinerja Non-PNS RSUDTA\n\nAnda memiliki notifikasi permintaan Validasi Tukar Dinas dari ".$nm_sender.".\n\n(ini adalah pesan otomatis)\n".$this->get_current_datetime()."";
                    $this->send_notif_wa($no_wa_receiver_next, $pesan);

                    // masukkan notif ke tt_tukarshift_notification
                    $notif_log = serialize(array(
                        "id_tukarshift" => $id_tukarshift,
                        "nm_sender" => $nm_sender,
                        "keterangan" => "Permintaan Tukar Dinas dari ".$nm_sender
                    ));
                    bukaquery2("
                        INSERT INTO tt_tukarshift_notification 
                            (id_user_receiver, data)
                        VALUES 
                            ('".$id_tukarshift."', '".$notif_log."')
                    ");

                    // masukkan ke tt_tukarshift_log
                    // perihal pengiriman permintaan tukar shift
                    $array_log = serialize(array(
                        "keterangan" => "Permintaan Tukar Dinas diterima oleh ".$nm_receiver
                    ));
                    bukaquery2("
                        INSERT INTO tt_tukarshift_log
                            (id_tukarshift, data)
                        VALUES 
                            ('".$id_tukarshift."', '".$array_log."')
                    ");
                } else {

                    // update status ke tt_tukarshift dgn is_tukarshift_status = 5
                    bukaquery2("
                        UPDATE tt_tukarshift
                        SET id_tukarshift_status = '5'
                        WHERE id_tukarshift = '".$id_tukarshift."'
                    ");

                    // tukar shift di tm_jadwalpegawai_shift_m
                    // ditukar data dari id_jadwalkerja_shift_1 dan id_jadwalkerja_shift_2

                    // ambil data id_jadwalkerja_shift 1
                    $sql_shift_1 = bukaquery2("
                        SELECT
                            a.id_absensi AS id_absensi_1, a.id_absensi_tipe AS id_absensi_tipe_1
                        FROM tm_jadwalpegawai_shift_m a
                        WHERE a.id_jadwalkerja_shift = '".$id_jadwalkerja_shift_1."'
                    ");
                    $res_shift = fetch_array($sql_shift_1);
                    $temp_id_absensi_1 = $res_shift['id_absensi_1'];
                    $temp_id_absensi_tipe_1 = $res_shift['id_absensi_tipe_1'];

                    // ambil data id_jadwalkerja_shift 2
                    $sql_shift_1 = bukaquery2("
                        SELECT
                            a.id_absensi AS id_absensi_2, a.id_absensi_tipe AS id_absensi_tipe_2
                        FROM tm_jadwalpegawai_shift_m a
                        WHERE a.id_jadwalkerja_shift = '".$id_jadwalkerja_shift_2."'
                    ");
                    $res_shift = fetch_array($sql_shift_1);
                    $temp_id_absensi_2 = $res_shift['id_absensi_2'];
                    $temp_id_absensi_tipe_2 = $res_shift['id_absensi_tipe_2'];

                    // update tm_jadwalpegawai_shift_m dimana id_jadwalkerja_shift ke 2
                    // dengan data id_jadwalkerja_shift ke 1
                    bukaquery2("
                        UPDATE 
                            tm_jadwalpegawai_shift_m
                        SET id_penanggung_jawab = '".$id_sender."', 
                            id_absensi = '".$temp_id_absensi_1."', 
                            id_absensi_tipe = '".$temp_id_absensi_tipe_1."'
                        WHERE id_jadwalkerja_shift = '".$id_jadwalkerja_shift_2."'
                    ");

                    // update tm_jadwalpegawai_shift_m dimana id_jadwalkerja_shift ke 1
                    // dengan data id_jadwalkerja_shift ke 2
                    bukaquery2("
                        UPDATE 
                            tm_jadwalpegawai_shift_m
                        SET id_penanggung_jawab = '".$id_sender."', 
                            id_absensi = '".$temp_id_absensi_2."', 
                            id_absensi_tipe = '".$temp_id_absensi_tipe_2."'
                        WHERE id_jadwalkerja_shift = '".$id_jadwalkerja_shift_1."'
                    ");

                    // kirim notifikasi wa ke pengirim bahwa permintaan tukar dinas telah disetujui
                    $pesan = "E-Kinerja Non-PNS RSUDTA\n\nPermintaan Tukar Dinas anda telah disetujui.\n\n(ini adalah pesan otomatis)\n".$this->get_current_datetime()."";
                    $this->send_notif_wa($no_wa_sender, $pesan);

                    // masukkan notif ke tt_tukarshift_notification
                    $notif_log = serialize(array(
                        "id_tukarshift" => $id_tukarshift,
                        "nm_sender" => $nm_sender,
                        "keterangan" => "Permintaan Tukar Dinas dari ".$nm_sender
                    ));
                    bukaquery2("
                        INSERT INTO tt_tukarshift_notification 
                            (id_user_receiver, data)
                        VALUES 
                            ('".$id_tukarshift."', '".$notif_log."')
                    ");

                    // masukkan ke tt_tukarshift_log
                    // perihal pengiriman permintaan tukar shift
                    $array_log = serialize(array(
                        "keterangan" => "Permintaan Tukar Dinas diterima oleh ".$nm_receiver
                    ));
                    bukaquery2("
                        INSERT INTO tt_tukarshift_log
                            (id_tukarshift, data)
                        VALUES 
                            ('".$id_tukarshift."', '".$array_log."')
                    ");
                }
                break;
        }

        return array(
            "status" => 1,
            "message" => "Penerima Menjawab Permintaan Pertukaran Shift Berhasil"
        );

    }

    function submit_permintaan_tukarshift($id_sender, $id_receiver, $id_jadwalkerja_shift_1, $id_jadwalkerja_shift_2, $keperluan) {

        // ambil nama_pegawai id_sender
        $sql = bukaquery2("
            SELECT a.nama_pegawai, a.no_hp_wa FROM tm_pegawai a WHERE a.id_user = '".$id_sender."'
        ");
        $res = fetch_array($sql);
        $nm_sender = $res['nama_pegawai'];
        $no_hp_wa = $res['no_hp_wa'];

        // ambil nama_pegawai id_receiver
        $sql = bukaquery2("
            SELECT a.nama_pegawai FROM tm_pegawai a WHERE a.id_user = '".$id_receiver."'
        ");
        $nm_receiver = fetch_array($sql)['nama_pegawai'];

        // cari default statusnya
        $sql = bukaquery2("
            SELECT
                a.id_tukarshift_status
            FROM tm_tukarshift_order a
            WHERE a.start_order = '1'
        ");
        $status_default = fetch_array($sql)['id_tukarshift_status'];

        $sql = bukaquery2("
            SELECT
                COUNT(a.id_tukarshift) AS res
            FROM tt_tukarshift a
            WHERE a.id_sender = '".$id_sender."'
                AND a.id_jadwalkerja_shift_1 = '".$id_jadwalkerja_shift_1."'
                AND a.id_tukarshift_status = '".$status_default."'
        ");
        $res = fetch_array($sql)['res'];

        // apabila sudah terdapat permintaan validasi dgn shift diajukan, maka ditolak
        if($res == 0) {

            $id = nokiamat("id_tukarshift", "tt_tukarshift");

            bukaquery2("
                INSERT INTO tt_tukarshift (id_tukarshift, id_sender, id_receiver, id_jadwalkerja_shift_1, id_jadwalkerja_shift_2, keperluan, id_tukarshift_status, created)
                VALUES
                ('".$id."', '".$id_sender."', '".$id_receiver."', '".$id_jadwalkerja_shift_1."', '".$id_jadwalkerja_shift_2."', '".$keperluan."', '".$status_default."', NOW())
            ");

            // masukkan ke tt_tukarshift_log
            // perihal pengiriman permintaan tukar shift
            $array_log = serialize(array(
                "keterangan" => "Permintaan Tukar Dinas dikirim oleh ".$nm_sender
            ));
            bukaquery2("
                INSERT INTO tt_tukarshift_log
                    (id_tukarshift, data)
                VALUES 
                    ('".$id."', '".$array_log."')
            ");

            // kirim notif ke wa
            $pesan = "E-Kinerja Non-PNS RSUDTA\n\nAnda memiliki notifikasi permintaan Tukar Dinas dari ".$nm_sender.".\n\n(ini adalah pesan otomatis)\n".$this->get_current_datetime()."";
            $this->send_notif_wa($no_hp_wa, $pesan);

            // masukkan notif ke tt_tukarshift_notification
            $notif_log = serialize(array(
                "id_tukarshift" => $id,
                "nm_sender" => $nm_sender,
                "keterangan" => "Permintaan Tukar Dinas dari ".$nm_sender
            ));
            bukaquery2("
                INSERT INTO tt_tukarshift_notification 
                    (id_user_receiver, data)
                VALUES 
                    ('".$id."', '".$notif_log."')
            ");

            // kirim permintaan validasi ke id_receiver. tt_tukarshift_validation
            bukaquery2("
                INSERT INTO tt_tukarshift_validation 
                    (id_sender, id_receiver, id_tukarshift, answered, notes, timestamp_request)
                VALUES
                    ('".$id_sender."', '".$id_receiver."', '".$id."', 0, '-', NOW())
            ");

            // masukkan ke tt_tukarshift_log
            // perihal validasi permintaan tukar shift
            $array_log = serialize(array(
                "keterangan" => "Menunggu Jawaban Permintaan Tukar Dinas dari ".$nm_receiver
            ));
            bukaquery2("
                INSERT INTO tt_tukarshift_log
                    (id_sender, id_receiver, id_tukarshift, data)
                VALUES 
                    ('".$id_sender."', '".$id_receiver."', '".$id."', '".$array_log."')
            ");
            
            return array(
                "status" => 1,
                "message" => "Permintaan Pertukaran Dinas telah diririm"
            );
        } else {

            return array(
                "status" => 0,
                "message" => "Permitaan Tukar Dinas Ditolak. Sudah ada permintaan yang sama sebelumnya."
            );
        }
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
$api = new ApiTukarShift();
$action = isset($_GET['action']) ? $_GET['action'] : null;

switch ($action) {
    case 'get_validation_status_add':
        echo json_encode($api->get_validation_status_add(
            $_GET['id_sender'],
            $_GET['id_unit'],
            $_GET['month'],
            $_GET['year']
        ));
        break;
    case 'get_list_user_tukarshift':
        echo json_encode($api->get_list_user_tukarshift(
            $_GET['month'],
            $_GET['year'],
            $_GET['id_unit'],
            $_GET['id_sender']
        ));
        break;
    case 'get_list_shift_tukarshift':
        echo json_encode($api->get_list_shift_tukarshift(
            $_GET['id_unit'],
            $_GET['month'],
            $_GET['year'],
            $_GET['id_receiver']
        ));
        break;
    case 'submit_permintaan_tukarshift':
        echo json_encode($api->submit_permintaan_tukarshift(
            $_POST['id_sender'],
            $_POST['id_receiver'],
            $_POST['id_jadwalkerja_shift_1'],
            $_POST['id_jadwalkerja_shift_2'],
            $_POST['keperluan']
        ));
        break;
    case 'post_answer_validation_by_receiver':
        echo json_encode($api->post_answer_validation_by_receiver(
            $_POST['id_tukarshift_validation'],
            $_POST['answer']
        ));
        break;
    case 'get_log_tukarshift':
        echo json_encode($api->get_log_tukarshift(
            $_GET['id_tukarshift']
        ));
        break;
    default:
        echo json_encode($api->invalid_action());
        break;
}
?>