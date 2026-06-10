<?php
session_start();
require_once('../../conf/conf.php');
require_once('../../libs/aes-encrypt/function.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ApiAbsensi
{

    function get_rekapitulasi_absensi_pegawai($month, $year)
    {

        $result = array();

        $sql = bukaquery2("SELECT 
            a.id_user, b.nip, b.nama_pegawai, b.log_finger, a.id_unit, d.nama_unit, f.nama_level,
            a.id_kepegawaian, c.nip AS nip_kepegawaian, c.nama_pegawai AS nm_kepegawaian,
            a.k_jml_sakit_1hari, a.k_jml_sakit_2hari, a.k_jml_alpha, a.k_jml_izin, a.k_jml_izin_sethari, a.k_jml_telat, a.k_jml_plng_cepat, a.k_jml_cuti_sakit, a.k_jml_cuti_alsnpenting, a.k_jml_cuti_prslnan, a.k_jml_meninggal,
            a.t_jml_cuti_sakit, a.t_jml_cuti_alsnpenting, a.t_jml_cuti_thnan, a.t_jml_diklat, a.t_jml_spd, a.t_jml_haji, 
            a.jml_hari_kerja,
            a.updated
        FROM tm_jadwalpegawai_absensi_rekap a
            INNER JOIN tm_pegawai b ON a.id_user = b.id_user
            INNER JOIN tm_pegawai c ON a.id_kepegawaian = c.id_user
            INNER JOIN tm_unit d ON a.id_unit = d.id_unit
			INNER JOIN tm_user e ON a.id_user = e.id_user
			INNER JOIN tm_level f ON e.id_level = f.id_level
        WHERE a.month = " . $month . "
            AND a.year = " . $year . "
        ORDER BY d.nama_unit, b.nama_pegawai
        ");

        while ($row = fetch_array($sql)) {

            array_push($result, array(
                "id_user" => $row['id_user'],
                "nip" => $row['nip'],
                "nama_pegawai" => $row['nama_pegawai'],
                "log_finger" => $row['log_finger'],
                "id_unit" => $row['id_unit'],
                "nama_unit" => $row['nama_unit'],
                "nama_level" => $row['nama_level'],
                "id_kepegawaian" => $row['id_kepegawaian'],
                "nip_kepegawaian" => $row['nip_kepegawaian'],
                "nm_kepegawaian" => $row['nm_kepegawaian'],
                "k_jml_sakit_1hari" => $row['k_jml_sakit_1hari'],
                "k_jml_sakit_2hari" => $row['k_jml_sakit_2hari'],
                "k_jml_alpha" => $row['k_jml_alpha'],
                "k_jml_izin" => $row['k_jml_izin'],
                "k_jml_izin_sethari" => $row['k_jml_izin_sethari'],
                "k_jml_telat" => $row['k_jml_telat'],
                "k_jml_plng_cepat" => $row['k_jml_plng_cepat'],
                "k_jml_cuti_sakit" => $row['k_jml_cuti_sakit'],
                "k_jml_cuti_alsnpenting" => $row['k_jml_cuti_alsnpenting'],
                "k_jml_cuti_prslnan" => $row['k_jml_cuti_prslnan'],
                "k_jml_meninggal" => $row['k_jml_meninggal'],
                "t_jml_cuti_sakit" => $row['t_jml_cuti_sakit'],
                "t_jml_cuti_alsnpenting" => $row['t_jml_cuti_alsnpenting'],
                "t_jml_cuti_thnan" => $row['t_jml_cuti_thnan'],
                "t_jml_diklat" => $row['t_jml_diklat'],
                "t_jml_spd" => $row['t_jml_spd'],
                "t_jml_haji" => $row['t_jml_haji'],
                "jml_hari_kerja" => $row['jml_hari_kerja'],
                "updated" => $row['updated'],
                "url_target" => paramEncrypt('module=absensi-pegawai&act=detail-absensi-pegawai&id_unit=' . $row['id_unit'] . '&id_user=' . $row['id_user'] . '&log_finger=' . $row['log_finger'] . '&bulan=' . $month . '&tahun=' . $year)
            ));
        }

        $sql = bukaquery2("SELECT 
        		a.accepted
			FROM tm_jadwalpegawai_absensi_session a
			WHERE a.month = " . $month . "
				AND a.year = " . $year . "
            ORDER BY timestamp_generated DESC
            LIMIT 1
		");

        return array(
            "status" => count($result) != 0 ? 1 : 0,
            "message" => count($result) != 0 ? "Data ditemukan" : "Data tidak ditemukan",
            "data" => array(
                "accepted" => (int) fetch_array($sql)['accepted'],
                "absensi" => $result
            )
        );
    }

    function get_shift_validation_status_by_unit($id_unit, $month, $year)
    {

        $sql = bukaquery2("SELECT 
                a.answered
            FROM tm_jadwalpegawai_shift_validation a
            WHERE a.id_unit = '" . $id_unit . "'
                AND a.month = " . $month . "
                AND a.year = " . $year . "
            ORDER BY a.timestamp_answer DESC
            LIMIT 1
        ");

        $result = fetch_array($sql)['answered'];

        return array(
            "status" => isset($result) ? 1 : 0,
            "message" => isset($result) ? "Data ditemukan" : "Data tidak ditemukan",
            "data" => $result,
        );
    }

    function get_list_unit_rekapitulasi_absensi_pegawai($month, $year)
    {
        $result = array();

        $sql = bukaquery2("SELECT 
    			a.id_unit, b.nama_unit, c.timestamp_generated, c.timestamp_accepted
			FROM tm_jadwalpegawai_absensi_rekap a
				LEFT JOIN tm_unit B on a.id_unit = b.id_unit
				INNER JOIN tm_jadwalpegawai_absensi_session c ON a.id_jadwalpegawai_absensi_rekap_session = c.id_jadwalpegawai_absensi_rekap_session
			WHERE a.month = " . $month . " AND a.year = " . $year . "
			GROUP BY a.id_unit
		");

        while ($row = fetch_array($sql)) {
            array_push($result, array(
                "id_unit" => $row['id_unit'],
                "nama_unit" => $row['nama_unit'],
                "timestamp_generated" => $row['timestamp_generated'],
                "timestamp_accepted" => $row['timestamp_accepted']
            ));
        }

        return array(
            "status" => count($result) != 0 ? 1 : 0,
            "message" => count($result) != 0 ? "Data ditemukan" : "Data tidak ditemukan",
            "data" => $result
        );
    }

    function get_rekapitulasi_absensi_pegawai_by_iduser($id_unit, $id_user, $month, $year)
    {
        $result = array();

        $sql = bukaquery2("SELECT a.k_jml_sakit_1hari, a.k_jml_sakit_2hari, a.k_jml_alpha, a.k_jml_izin, a.k_jml_izin_sethari, a.k_jml_telat, a.k_jml_plng_cepat, a.k_jml_cuti_sakit, a.k_jml_cuti_alsnpenting, a.k_jml_cuti_prslnan, a.k_jml_meninggal, a.t_jml_cuti_sakit, a.t_jml_cuti_alsnpenting, a.t_jml_cuti_thnan, a.t_jml_diklat, a.t_jml_spd, a.t_jml_haji
			FROM tm_jadwalpegawai_absensi_rekap a
			WHERE a.id_unit = '" . $id_unit . "' AND a.id_user = '" . $id_user . "' AND a.month = " . $month . " AND a.year = " . $year . "
		");

        while ($row = fetch_array($sql)) {
            array_push($result, array(
                "k_jml_sakit_1hari" => $row['k_jml_sakit_1hari'],
                "k_jml_sakit_2hari" => $row['k_jml_sakit_2hari'],
                "k_jml_alpha" => $row['k_jml_alpha'],
                "k_jml_izin" => $row['k_jml_izin'],
                "k_jml_izin_sethari" => $row['k_jml_izin_sethari'],
                "k_jml_telat" => $row['k_jml_telat'],
                "k_jml_plng_cepat" => $row['k_jml_plng_cepat'],
                "k_jml_cuti_sakit" => $row['k_jml_cuti_sakit'],
                "k_jml_cuti_alsnpenting" => $row['k_jml_cuti_alsnpenting'],
                "k_jml_cuti_prslnan" => $row['k_jml_cuti_prslnan'],
                "k_jml_meninggal" => $row['k_jml_meninggal'],
                "t_jml_cuti_sakit" => $row['t_jml_cuti_sakit'],
                "t_jml_cuti_alsnpenting" => $row['t_jml_cuti_alsnpenting'],
                "t_jml_cuti_thnan" => $row['t_jml_cuti_thnan'],
                "t_jml_diklat" => $row['t_jml_diklat'],
                "t_jml_spd" => $row['t_jml_spd'],
                "t_jml_haji" => $row['t_jml_haji']
            ));
        }

        return array(
            "status" => count($result) != 0 ? 1 : 0,
            "message" => count($result) != 0 ? "Data ditemukan" : "Data tidak ditemukan",
            "data" => $result
        );
    }

    function get_detail_absensi_pegawai_by_id_user($id_unit, $id_user, $month, $year)
    {
        $result = array();

        $sql = bukaquery2("SELECT 
    		a.date, a.id_absensi, a.id_ketidakhadiran, a.jam_masuk_absensi_aktif, a.jam_pulang_absensi_aktif, a.absensi_masuk, a.absensi_pulang, a.keterlambatan, a.pulang_cepat
			FROM tm_jadwalpegawai_absensi_detail a
			WHERE a.id_unit = '" . $id_unit . "' AND a.id_user = '" . $id_user . "' AND a.month = " . $month . " AND a.year = " . $year . "
		");

        while ($row = fetch_array($sql)) {
            array_push($result, array(
                "date" => $row['date'],
                "id_absensi" => $row['id_absensi'],
                "id_ketidakhadiran" => $row['id_ketidakhadiran'],
                "jam_masuk_absensi_aktif" => $row['jam_masuk_absensi_aktif'],
                "jam_pulang_absensi_aktif" => $row['jam_pulang_absensi_aktif'],
                "absensi_masuk" => $row['absensi_masuk'],
                "absensi_pulang" => $row['absensi_pulang'],
                "keterlambatan" => $row['keterlambatan'],
                "pulang_cepat" => $row['pulang_cepat']
            ));
        }

        return array(
            "status" => count($result) != 0 ? 1 : 0,
            "message" => count($result) != 0 ? "Data ditemukan" : "Data tidak ditemukan",
            "data" => $result
        );
    }

    function get_options_edit_shift_detail_absensi_pegawai()
    {

        $res_shift_options = array();
        $res_shift_tipe_options = array();
        $res_ketidakhadiran_options = array();

        $sql = bukaquery2("SELECT a.id_absensi, a.nama_shift, a.desc_shift, a.jam_masuk, a.jam_pulang FROM tm_shift a ORDER BY a.jam_masuk, a.jam_pulang");
        while ($row = fetch_array($sql)) {

            array_push($res_shift_options, array(
                "id_absensi" => $row['id_absensi'],
                "nama_shift" => $row['nama_shift'],
                "desc_shift" => $row['desc_shift'],
                "jam_masuk" => $row['jam_masuk'],
                "jam_pulang" => $row['jam_pulang']
            ));
        }

        $sql = bukaquery2("SELECT a.id_absensi_tipe, a.nama_shift_tipe, a.desc_shift_tipe FROM tm_shift_tipe a ORDER BY a.shift_tipe, a.nama_shift_tipe");
        while ($row = fetch_array($sql)) {

            array_push($res_shift_tipe_options, array(
                "id_absensi_tipe" => $row['id_absensi_tipe'],
                "nama_shift_tipe" => $row['nama_shift_tipe'],
                "desc_shift_tipe" => $row['desc_shift_tipe']
            ));
        }

        $sql = bukaquery2("SELECT a.id_ketidakhadiran, a.nama_ketidakhadiran, a.desc_ketidakhadiran FROM tm_shift_ketidakhadiran a ORDER BY a.id_ketidakhadiran_tipe, a.nama_ketidakhadiran");
        while ($row = fetch_array($sql)) {

            array_push($res_ketidakhadiran_options, array(
                "id_ketidakhadiran" => $row['id_ketidakhadiran'],
                "nama_ketidakhadiran" => $row['nama_ketidakhadiran'],
                "desc_ketidakhadiran" => $row['desc_ketidakhadiran']
            ));
        }

        return array(
            "status" => 1,
            "message" => "Data ditemukan",
            "data" => array(
                "shift_options" => $res_shift_options,
                "shift_tipe_options" => $res_shift_tipe_options,
                "ketidakhadiran_options" => $res_ketidakhadiran_options
            )
        );
    }

    function get_log_kehadiran_by_logfinger_date($log_finger, $date, $id_unit, $id_user)
    {

        $res_list_log = array();
        $res_list_keterangan_absensi = array();
        $res_keterlambatan = 0;
        $res_keterangan_keterlambatan = "";
        $res_pulangcepat = 0;
        $res_keterangan_pulangcepat = "";

        $sql = bukaquery2("SELECT
    			a.tanggal, a.status
			FROM log a
			WHERE a.user = '" . $log_finger . "'
				AND ( DATE(a.tanggal) = SUBDATE(DATE('" . $date . "'), 1) 
				OR DATE(a.tanggal) = '" . $date . "'
				OR DATE(a.tanggal) = ADDDATE(DATE('" . $date . "'), 1) )
			ORDER BY a.tanggal
		");

        while ($row = fetch_assoc($sql)) {

            array_push($res_list_log, $row);
        }

        $sql = bukaquery2("
            SELECT
                a.keterlambatan, a.keterangan_keterlambatan,
                a.pulang_cepat, a.keterangan_pulangcepat
            FROM tm_jadwalpegawai_absensi_detail a
            WHERE a.id_unit = '" . $id_unit . "'
                AND a.id_user = '" . $id_user . "'
                AND a.date = '" . $date . "'
        ");

        $temp = fetch_array($sql);
        $res_keterlambatan = $temp['keterlambatan'];
        $res_keterangan_keterlambatan = $temp['keterangan_keterlambatan'];
        $res_pulangcepat = $temp['pulang_cepat'];
        $res_keterangan_pulangcepat = $temp['keterangan_pulangcepat'];

        $sql = bukaquery2("
            SELECT
                a.id_keterangan, a.keterangan
            FROM tm_absensi_ket_kepegawaian a
            ORDER BY a.keterangan
        ");

        while ($row = fetch_assoc($sql)) {

            array_push($res_list_keterangan_absensi, $row);
        }

        return array(
            "status" => 1,
            "message" => "Data ditemukan",
            "list_log" => $res_list_log,
            "keterlambatan" => (int) $res_keterlambatan,
            "ket_keterlambatan" => $res_keterangan_keterlambatan,
            "pulang_cepat" => (int) $res_pulangcepat,
            "ket_pulangcepat" => $res_keterangan_pulangcepat,
            "list_keterangan_absensi" => $res_list_keterangan_absensi
        );
    }

    function get_bool_valid_generate_absensi_pegawai($month, $year)
    {

        $bool_time = strtotime(TanggalAkhirBulanKemarin()) > strtotime($year . "-" . $month . "-1");

        $sql = bukaquery2("SELECT IF(COUNT(a.id_jadwalkerja_shift_validation) = 0, false, true) AS res
            FROM tm_jadwalpegawai_shift_validation a
            WHERE a.month = " . $month . "
                AND a.year = " . $year . "
                AND a.answered = 1
        ");
        $bool_shift_unit = fetch_array($sql)['res'];

        return array(
            "status" => $bool_time && $bool_shift_unit ? 1 : 0,
            "message" => $bool_time && $bool_shift_unit
                ? "Permintaan dapat diproses"
                : (!$bool_shift_unit
                    ? "Data unit tidak tersedia untuk diproses"
                    : (!$bool_time
                        ? "Sistem hanya dapat memproses absensi BULAN SEBELUMNYA"
                        : "Sistem hanya dapat memproses absensi BULAN SEBELUMNYA dan Data Unit tidak tersedia untuk diproses"))
        );
    }

    function get_unit_rekapitulasi_absensi_pegawai($month, $year)
    {

        $result = array();

        $sql = bukaquery2("
            SELECT
                b.id_unit, b.nama_unit, IFNULL(f.created, '') AS created
            FROM tm_pegawai a
                INNER JOIN tm_unit b ON a.id_unit = b.id_unit
                INNER JOIN tm_honor_shift c ON b.id_petugas = c.id_petugas
                INNER JOIN tm_user d ON a.id_user = d.id_user
                INNER JOIN tm_level e ON d.id_level = e.id_level
                LEFT JOIN tm_jadwalpegawai_absensi_rekap f ON a.id_unit = f.id_unit
            WHERE a.`status` = 'AKTIF'
                AND a.status_pegawai = 'NON PNS'
                AND a.id_unit <> 'UNT-000020'
                AND ( f.month = " . $month . " OR f.month IS NULL)
                AND ( f.year = " . $year . " OR f.year IS NULL)
            GROUP BY b.id_unit
            ORDER BY b.nama_unit
        ");

        while ($row = fetch_assoc($sql)) {

            array_push($result, $row);
        }

        return array(
            "status" => count($result) != 0 ? 1 : 0,
            "message" => count($result) != 0 ? "Data ditemukan" : "Data tidak ditemukan",
            "data" => $result
        );
    }

    function get_status_unit_rekapitulasi_absensi_pegawai($month, $year)
    {

        $result = array();
        $result_peg = array();

        $sql = bukaquery2("
            SELECT 
                a.unit_id,
                a.nama_unit,
                a.is_generated,
                a.is_submitted,
                a.kasie_response,
                a.validasi_absen
            FROM 
            (
                SELECT
                    a.id_unit AS unit_id, a.nama_unit,
                    (
                        SELECT
                            IF(COUNT(a.id_jadwalkerja_shift) > 0, 1, 0)
                        FROM tm_jadwalpegawai_shift_m a
                        WHERE a.id_unit = unit_id
                            AND a.`month` = '" . $month . "'
                            AND a.year = '" . $year . "'
                    ) AS is_generated,
                    IFNULL((
                        SELECT
                            a.submitted
                        FROM tm_jadwalpegawai_shift_m a
                        WHERE a.id_unit = unit_id
                            AND a.`month` = '" . $month . "'
                            AND a.year = '" . $year . "'
                        ORDER BY a.submitted DESC
                        LIMIT 1
                    ), 0) AS is_submitted,
                    IFNULL((
                        SELECT
                            a.answered
                        FROM tm_jadwalpegawai_shift_validation a
                        WHERE a.id_unit =  unit_id
                            AND a.month = '" . $month . "'
                            AND a.year = '" . $year . "'
                        ORDER BY a.timestamp_request DESC
                        LIMIT 1
                    ), 0) AS kasie_response,
                    IFNULL((
                        SELECT
                            IF(COUNT(a.id_jadwalpegawai_absensi_rekap) > 0, 1, 0)
                        FROM tm_jadwalpegawai_absensi_rekap a
                        WHERE a.id_unit = unit_id
                            AND a.month = '" . $month . "'
                            AND a.year = '" . $year . "'
                    ), 0) AS validasi_absen
                FROM tm_unit a
            ) AS a
            ORDER BY a.validasi_absen DESC, a.kasie_response DESC, a.nama_unit
        ");

        while ($row = fetch_array($sql)) {

            array_push($result, array(
                "id_unit" => $row['unit_id'],
                "nama_unit" => $row['nama_unit'],
                "is_generated" => (int) $row['is_generated'] == 1
                    ? "Sudah"
                    : "Belum",
                "is_submitted" => (int) $row['is_submitted'] == 1
                    ? "Sudah"
                    : "Belum",
                "kasie_response" => (int) $row['kasie_response'] == 0
                    ? "Belum Dijawab"
                    : ((int) $row['kasie_response'] == 1
                        ? "Sudah Divalidasi"
                        : "Ditolak"
                    ),
                "validasi_absen" => (int) $row['validasi_absen'] == 0
                    ? "Belum Ditarik"
                    : "Sudah Ditarik"
            ));
        }

        $sql = bukaquery("
            SELECT
                a.id_user, a.nama_pegawai
            FROM tm_pegawai AS a
            WHERE a.status = 'AKTIF'
        ");

        while($row = fetch_array($sql)) {

            array_push($result_peg, array(
                'id_user' => $row['id_user'],
                'nama_pegawai' => $row['nama_pegawai']
            ));
        }

        return array(
            "status" => 1,
            "message" => "Data ditemukan",
            "data" => $result,
            "data_pegawai" => $result_peg
        );
    }

    function get_status_duplikat_absensi_pegawai($month, $year, $list_unit_serialized_temp)
    {
        $result = array();

        $list_unit = "(";
        $list_unit_serialized = json_decode(stripslashes($list_unit_serialized_temp));
        for ($i = 0; $i < count($list_unit_serialized); $i++) {

            $list_unit .= "'" . $list_unit_serialized[$i] . "'";
            if ($i + 1 < count($list_unit_serialized)) $list_unit .= ", ";
        }
        $list_unit .= ")";

        $sql = bukaquery2("
            SELECT
                a.id_user, 
                c.nama_pegawai,
                a.id_unit,
                b.nama_unit
            FROM tm_jadwalpegawai_shift_m a
                INNER JOIN tm_unit b ON a.id_unit = b.id_unit
                INNER JOIN tm_pegawai c ON a.id_user = c.id_user
            WHERE a.id_user IN (
                SELECT
                    a.id_user
                FROM (
                    SELECT
                        a.id_user, a.id_unit
                    FROM tm_jadwalpegawai_shift_m a
                    WHERE a.`month` = '" . $month . "'
                        AND a.`year` = '" . $year . "'
                    GROUP BY a.id_user, a.id_unit
                ORDER BY a.id_user, a.id_unit
                ) AS a
                GROUP BY a.id_user
                HAVING COUNT(a.id_unit) > 1
            )
                AND a.`month` = '" . $month . "'
                AND a.`year` = '" . $year . "'
                AND a.id_unit IN " . $list_unit . "
            GROUP BY a.id_user, a.id_unit
        ");

        while ($row = fetch_array($sql)) {

            array_push($result, array(
                "id_unit" => $row['id_unit'],
                "nama_pegawai" => $row['nama_pegawai'],
                "id_unit" => $row['id_unit'],
                "nama_unit" => $row['nama_unit']
            ));
        }

        return array(
            "status" => count($result) > 0 ? 1 : 0,
            "message" => count($result) > 0 ? "Data ditemukan" : "Data tidak ditemukan",
            "data" => $result,
        );
    }

    function get_absensi_live_jadwal_by_iduser_idunit_month_year($id_user, $id_unit, $date_start, $date_end)
    {

        $arr = array();
        $default_minute_penalty = 225; // default pinalti akibat tidak absensi masuk/pulang (menit). Tidak berlaku apabila counting_as_isolman = 1

        // jika id_user dan id_unit all, tampilkan semua pegawai
        if ($id_unit == 'all') {

            return array(
                'status' => 0,
                'message' => 'Mohon pilih UNIT !'
            );
        } else if ($id_user == 'all') { // jika id_user yg dikirim all, berarti tampilkan seluruh pegawai yg ada di unit tsb
            $sql = bukaquery2("
                SELECT
                    a.id_user,
                    c.nama_pegawai,
                    c.log_finger,
                    a.id_absensi, a.date, b.jam_masuk, b.jam_pulang,
                    d.tanggal AS absen_masuk, FLOOR(GREATEST(0, IFNULL(TIME_TO_SEC(TIMEDIFF(TIME(d.tanggal), TIME(b.jam_masuk)))/60, IF(b.counting_as_isolman = 1 OR a.id_absensi = '', 0, " . $default_minute_penalty . ")))) AS keterlambatan,
                    e.tanggal AS absen_pulang, FLOOR(GREATEST(0, IFNULL(TIME_TO_SEC(TIMEDIFF(TIME(b.jam_pulang), TIME(e.tanggal)))/60, IF(b.counting_as_isolman = 1 OR a.id_absensi = '', 0, " . $default_minute_penalty . ")))) AS pulang_cepat
                FROM tm_jadwalpegawai_shift_m a
                    LEFT JOIN tm_shift b ON a.id_absensi = b.id_absensi
                    INNER JOIN tm_pegawai c ON a.id_user = c.id_user
                    LEFT JOIN log d ON c.log_finger = d.`user`
                        AND DATE(d.tanggal) = a.date
                        AND TIME(d.tanggal) > SUBTIME(b.bi, '00:00:01')
                        AND TIME(d.tanggal) < ADDTIME(b.ai, '00:30:00')
                    LEFT JOIN log e ON c.log_finger = e.`user`
                        AND DATE(e.tanggal) = a.date
                        AND TIME(e.tanggal) > SUBTIME(b.bo, '00:30:00')
                        AND TIME(e.tanggal) < ADDTIME(b.ao, '00:00:01')
                WHERE a.id_unit = '" . $id_unit . "'
                    AND a.date BETWEEN '" . $date_start . "' AND '" . $date_end . "'
                GROUP BY a.id_user, a.date
                ORDER BY c.nama_pegawai, a.date
            ");
        } else {

            $sql = bukaquery2("
                SELECT
                    a.id_user,
                    c.nama_pegawai,
                    c.log_finger,
                    a.id_absensi, a.date, b.jam_masuk, b.jam_pulang,
                    d.tanggal AS absen_masuk, FLOOR(GREATEST(0, IFNULL(TIME_TO_SEC(TIMEDIFF(TIME(d.tanggal), TIME(b.jam_masuk)))/60, IF(b.counting_as_isolman = 1 OR a.id_absensi = '', 0, " . $default_minute_penalty . ")))) AS keterlambatan,
                    e.tanggal AS absen_pulang, FLOOR(GREATEST(0, IFNULL(TIME_TO_SEC(TIMEDIFF(TIME(b.jam_pulang), TIME(e.tanggal)))/60, IF(b.counting_as_isolman = 1 OR a.id_absensi = '', 0, " . $default_minute_penalty . ")))) AS pulang_cepat
                FROM tm_jadwalpegawai_shift_m a
                    LEFT JOIN tm_shift b ON a.id_absensi = b.id_absensi
                    INNER JOIN tm_pegawai c ON a.id_user = c.id_user
                    LEFT JOIN log d ON c.log_finger = d.`user`
                        AND DATE(d.tanggal) = a.date
                        AND TIME(d.tanggal) > SUBTIME(b.bi, '00:00:01')
                        AND TIME(d.tanggal) < ADDTIME(b.ai, '00:30:00')
                    LEFT JOIN log e ON c.log_finger = e.`user`
                        AND DATE(e.tanggal) = a.date
                        AND TIME(e.tanggal) > SUBTIME(b.bo, '00:30:00')
                        AND TIME(e.tanggal) < ADDTIME(b.ao, '00:00:01')
                WHERE a.id_unit = '" . $id_unit . "'
                    AND a.id_user = '" . $id_user . "'
                    AND a.date BETWEEN '" . $date_start . "' AND '" . $date_end . "'
                GROUP BY a.id_user, a.date
                ORDER BY c.nama_pegawai, a.date
            ");
        }

        while ($row = fetch_array($sql)) {

            array_push($arr, array(
                "id_user" => $row['id_user'],
                "nama_pegawai" => $row['nama_pegawai'],
                "tanggal" => $row['date'],
                "jam_masuk" => $row['jam_masuk'],
                "jam_pulang" => $row['jam_pulang'],
                "absen_masuk" => $row['absen_masuk'],
                "absen_pulang" => $row['absen_pulang'],
                "telat" => (int) $row['keterlambatan'],
                "pulang_cepat" => (int) $row['pulang_cepat']
            ));
        }

        return array(
            'status' => 1,
            'message' => 'data ditemukan',
            'data' => $arr
        );
    }

    function get_absensi_live_by_iduser_idunit_month_year($id_user, $id_unit, $date_start, $date_end)
    {

        $arr = array();
        $default_minute_penalty = 225; // default pinalti akibat tidak absensi masuk/pulang (menit). Tidak berlaku apabila counting_as_isolman = 1

        // jika id_user yg dipilih all, berarti ditampilkan seluruh pegawai dalam 1 unit
        if ($id_user == 'all') {

            $sql = bukaquery2("
                SELECT
                    a.id_user, a.nama_pegawai, b.tanggal, b.status
                FROM tm_pegawai a
                    LEFT JOIN log b ON a.log_finger = b.user
                WHERE a.id_unit = '" . $id_unit . "'
                    AND DATE(b.tanggal) BETWEEN '" . $date_start . "' AND '" . $date_end . "'
                ORDER BY a.nama_pegawai, b.tanggal
            ");
        } else {

            $sql = bukaquery2("
                SELECT
                    a.id_user, a.nama_pegawai, b.tanggal, b.status
                FROM tm_pegawai a
                    LEFT JOIN log b ON a.log_finger = b.user
                WHERE a.id_unit = '" . $id_unit . "'
                    AND DATE(b.tanggal) BETWEEN '" . $date_start . "' AND '" . $date_end . "'
                    AND a.id_user = '" . $id_user . "'
                ORDER BY a.nama_pegawai, b.tanggal
            ");
        }


        while ($row = fetch_array($sql)) {

            array_push($arr, array(
                "id_user" => $row['id_user'],
                "nama_pegawai" => $row['nama_pegawai'],
                "tanggal" => $row['tanggal'],
                "status" => $row['status']
            ));
        }

        return array(
            'status' => 1,
            'message' => 'data ditemukan',
            'data' => $arr
        );
    }

    function get_pegawai_by_idunit_shiftm($id_unit, $month, $year)
    {

        $arr = array();
        $sql = bukaquery2("
            SELECT
                a.id_user, b.nama_pegawai
            FROM tm_jadwalpegawai_shift_m a
                INNER JOIN tm_pegawai b ON a.id_user = b.id_user
            WHERE a.`month` = '" . $month . "'
                AND a.`year` = '" . $year . "'
                AND a.id_unit = '" . $id_unit . "'
            GROUP BY a.id_user
            ORDER BY b.nama_pegawai
        ");

        while ($row = fetch_array($sql)) {

            array_push($arr, array(
                "id_user" => $row['id_user'],
                "nama_pegawai" => $row['nama_pegawai']
            ));
        }

        return array(
            'status' => count($arr) != 0 ? 1 : 0,
            'message' => count($arr) != 0 ? 'data ditemukan' : 'data tidak ditemukan',
            'data' => $arr
        );
    }

    function put_accepted_rekap_absensi_pegawai($id_kepegawaian, $month, $year)
    {

        bukaquery2("UPDATE tm_jadwalpegawai_absensi_session
			SET id_kepegawaian_accepted = '" . $id_kepegawaian . "', accepted = 1, timestamp_accepted = NOW()
			WHERE month = " . $month . " AND year = " . $year . "
		");

        return array(
            "status" => 1,
            "message" => "Update Absensi Pegawai Selesai"
        );
    }

    function put_shift_detail_absensi_pegawai($id_kepegawaian, $id_unit, $id_user, $log_finger, $month, $year, $date, $id_absensi, $id_absensi_tipe, $id_ketidakhadiran, $is_libur)
    {

        if ($is_libur == '1') {

            // tm_jadwalpegawai_absensi_detail diset menjadi null
            bukaquery2("UPDATE tm_jadwalpegawai_absensi_detail
                SET id_absensi = '', id_absensi_tipe = '', id_ketidakhadiran = '', shift_aktif = 0, id_kepegawaian = '" . $id_kepegawaian . "'
                WHERE id_unit = '" . $id_unit . "' AND month = " . $month . " AND year = " . $year . " AND id_user = '" . $id_user . "' AND date = '" . $date . "'
            ");

            // perhitungan ulang rekapitulasi absensi pegawai dari tm_jadwalpegawai_absensi_rekap
            $query_rekap_shift_pegawai = "";
            // buat query insert select u tm_jadwalpegawai_absensi_rekap
            $query_rekap_shift_pegawai = "SELECT a.id_unit AS unit_id, a.id_user AS user_id, (SELECT SUM(a.keterlambatan) FROM tm_jadwalpegawai_absensi_detail a WHERE a.id_unit = '" . $id_unit . "' AND a.month = " . $month . " AND a.year = " . $year . " AND a.id_user = '" . $id_user . "') AS jml_telat, (SELECT SUM( a.pulang_cepat ) FROM tm_jadwalpegawai_absensi_detail a WHERE a.id_unit = '" . $id_unit . "' AND a.month = " . $month . " AND a.year = " . $year . " AND a.id_user = '" . $id_user . "') AS jml_pulang_cepat, ";

            // ambil data ketidakhadiran shift
            $arr_shift_ketidakhadiran = array();
            $sql = bukaquery2("SELECT a.id_ketidakhadiran, REPLACE(LOWER(a.id_ketidakhadiran), '-', '') AS id_ketidakhadiran_lower FROM tm_shift_ketidakhadiran a");

            // convert jadi array
            while ($row = fetch_array($sql)) {
                array_push($arr_shift_ketidakhadiran, array(
                    $row['id_ketidakhadiran'],
                    $row['id_ketidakhadiran_lower']
                ));
            }

            // buat subquery u mendapatkan jumlah ketidakhadiran berdasarkan tm_shift_ketidakhadiran
            for ($i = 0; $i < count($arr_shift_ketidakhadiran); $i++) {

                $query_rekap_shift_pegawai .= "(SELECT COUNT(a.date) AS jml FROM tm_jadwalpegawai_absensi_detail a WHERE a.id_unit = unit_id AND a.month = " . $month . " AND a.year = " . $year . " AND a.id_user = user_id AND a.id_ketidakhadiran = '" . $arr_shift_ketidakhadiran[$i][0] . "') AS jml_" . $arr_shift_ketidakhadiran[$i][1] . ", ";
            }

            // ambil data tm_shift_tipe
            $arr_shift_shift_tipe = array();
            $sql = bukaquery2("SELECT a.id_absensi_tipe, REPLACE(LOWER(a.id_absensi_tipe), '-', '') AS id_absensi_tipe_lower FROM tm_shift_tipe a");

            // convert jadi array
            while ($row = fetch_array($sql)) {

                array_push($arr_shift_shift_tipe, array(
                    $row['id_absensi_tipe'],
                    $row['id_absensi_tipe_lower']
                ));
            }

            // buat subquery u mendapatkan jumlah ketidakhadiran berdasarkan tm_shift_ketidakhadiran
            for ($i = 0; $i < count($arr_shift_shift_tipe); $i++) {

                $query_rekap_shift_pegawai .= "(SELECT COUNT(a.date) AS jml FROM tm_jadwalpegawai_absensi_detail a WHERE a.id_unit = unit_id AND a.month = " . $month . " AND a.year = " . $year . " AND a.id_user = user_id AND a.id_absensi_tipe = '" . $arr_shift_shift_tipe[$i][0] . "') AS jml_" . $arr_shift_shift_tipe[$i][1] . ", ";
            }
            // subquery u menghitung jumlah hari kerja dari tm_jadwalpegawai_absensi_detail
            $query_rekap_shift_pegawai .= " (SELECT COUNT(a.id_absensi) FROM tm_jadwalpegawai_absensi_detail a WHERE a.id_absensi IN (SELECT a.id_absensi FROM tm_shift a WHERE a.counting_work = 1) AND a.id_unit = unit_id AND a.month = " . $month . " AND a.year = " . $year . " AND a.id_user = user_id GROUP BY a.id_user) AS jml_hari_kerja, ";

            //subquery untuk menghitung jumlah menit kerja dari tm_jadwal
            $query_rekap_shift_pegawai .= "(SELECT SUM(b.working_time_minute) FROM tm_jadwalpegawai_absensi_detail a LEFT JOIN tm_shift b ON a.id_absensi = b.id_absensi WHERE a.id_unit = unit_id AND a.month = " . $month . " AND a.year = " . $year . " AND a.id_user = user_id AND (a.id_absensi <> '' AND a.id_absensi IS NOT NULL)) AS jml_menit_kerja ";

            // from, join dan groupby dari table tm_jadwalpegawai_absensi_detail
            $query_rekap_shift_pegawai .= " FROM tm_jadwalpegawai_absensi_detail a WHERE a.month = " . $month . " AND a.year = " . $year . " AND a.id_user = '" . $id_user . "' GROUP BY a.id_user";

            // eksekusi query
            $sql =  bukaquery2($query_rekap_shift_pegawai);
            $result = fetch_array($sql);

            bukaquery2("UPDATE tm_jadwalpegawai_absensi_rekap
                SET 
                k_jml_alpha = '" . $result['jml_akt000001'] . "', k_jml_sakit_1hari = '" . $result['jml_akt000002'] . "', k_jml_sakit_2hari = '" . $result['jml_akt000003'] . "', k_jml_izin = '" . $result['jml_akt000004'] . "', k_jml_cuti_sakit = '" . $result['jml_akt000005'] . "', k_jml_izin_sethari = '" . $result['jml_akt000008'] . "', k_jml_meninggal = '" . $result['jml_akt000009'] . "', k_jml_telat = '" . $result['jml_telat'] . "', k_jml_plng_cepat = '" . $result['jml_pulang_cepat'] . "',
                t_jml_cuti_sakit = '" . $result['jml_akt000010'] . "', t_jml_cuti_alsnpenting = '" . $result['jml_akt000011'] . "', t_jml_cuti_thnan = '" . $result['jml_akt000012'] . "', t_jml_diklat = '" . $result['jml_akt000013'] . "', t_jml_spd = '" . $result['jml_akt000014'] . "', t_jml_haji = '" . $result['jml_akt000015'] . "',
                s_jml_hks = '" . $result['jml_abt000001'] . "', s_jml_hkm = '" . $result['jml_abt000002'] . "', s_jml_hlp = '" . $result['jml_abt000003'] . "', s_jml_hls = '" . $result['jml_abt000004'] . "', s_jml_hlm = '" . $result['jml_abt000005'] . "', s_jml_hrp = '" . $result['jml_abt000006'] . "', s_jml_hrs = '" . $result['jml_abt000007'] . "', s_jml_hrm = '" . $result['jml_abt000008'] . "', s_jml_ns = '" . $result['jml_abt000009'] . "', 
                jml_hari_kerja = '" . $result['jml_hari_kerja'] . "' , jml_menit_kerja = '" . $result['jml_menit_kerja'] . "'
                WHERE id_unit = '" . $id_unit . "' AND month = " . $month . " AND year = " . $year . " AND id_user = '" . $id_user . "'
            ");
        } else {

            bukaquery2("UPDATE tm_jadwalpegawai_absensi_detail
                SET id_absensi = '" . $id_absensi . "', id_absensi_tipe = '" . $id_absensi_tipe . "', id_ketidakhadiran = '" . $id_ketidakhadiran . "', shift_aktif = " . ($id_absensi != '' ? 1 : 0) . ", id_kepegawaian = '" . $id_kepegawaian . "'
                WHERE id_unit = '" . $id_unit . "' AND month = " . $month . " AND year = " . $year . " AND id_user = '" . $id_user . "' AND date = '" . $date . "'
            ");


            $sql = bukaquery2("SELECT 
                    a.date, a.id_absensi, 
                    b.bi, b.jam_masuk, b.ai, 
                    b.bo, b.jam_pulang, b.ao, 
                    IFNULL(c.tanggal, '') AS absensi_masuk, GREATEST(IFNULL(TIME_TO_SEC(TIMEDIFF(TIME(c.tanggal), TIME(b.jam_masuk)))/60, ''), 0) AS keterlambatan,
                    IFNULL(d.tanggal, '') AS absensi_pulang, GREATEST(IFNULL(TIME_TO_SEC(TIMEDIFF(TIME(b.jam_pulang), TIME(d.tanggal)))/60, ''), 0) AS pulang_cepat
                FROM tm_jadwalpegawai_absensi_detail a
                    INNER JOIN tm_shift b ON a.id_absensi = b.id_absensi
                    LEFT JOIN log c ON c.user = '" . $log_finger . "'
                        AND DATE(c.tanggal) = '" . $date . "'
                        AND TIME(c.tanggal) > SUBTIME(b.bi, '00:00:01')
                        AND TIME(c.tanggal) < ADDTIME(b.ai, '00:30:00')
                    LEFT JOIN log d ON d.user = '" . $log_finger . "'
                        AND DATE(d.tanggal) = '" . $date . "'
                        AND TIME(d.tanggal) > SUBTIME(b.bo, '00:30:00')
                        AND TIME(d.tanggal) < ADDTIME(b.ao, '00:00:01')
                WHERE a.id_unit = '" . $id_unit . "'
                    AND a.month = " . $month . "
                    AND a.year = " . $year . "
                    AND a.id_user = '" . $id_user . "'
                    AND a.date = '" . $date . "'
                ORDER BY c.tanggal ASC, d.tanggal DESC
                LIMIT 1
            ");

            $result = fetch_array($sql);


            $temp_keterlambatan = isset($result['keterlambatan']) ? $result['keterlambatan'] : 0;
            $temp_pulang_cepat = isset($result['pulang_cepat']) ? $result['pulang_cepat'] : 0;


            bukaquery2("UPDATE tm_jadwalpegawai_absensi_detail
                SET jam_masuk_absensi_aktif = '" . $result['jam_masuk'] . "', jam_pulang_absensi_aktif = '" . $result['jam_pulang'] . "', absensi_masuk = '" . $result['absensi_masuk'] . "', absensi_pulang = '" . $result['absensi_pulang'] . "', keterlambatan = '" . $temp_keterlambatan . "', pulang_cepat = '" . $temp_pulang_cepat . "'
                WHERE id_unit = '" . $id_unit . "' AND month = " . $month . " AND year = " . $year . " AND id_user = '" . $id_user . "' AND date = '" . $date . "'
            ");

            // perhitungan ulang rekapitulasi absensi pegawai dari tm_jadwalpegawai_absensi_rekap
            $query_rekap_shift_pegawai = "";
            // buat query insert select u tm_jadwalpegawai_absensi_rekap
            $query_rekap_shift_pegawai = "SELECT a.id_unit AS unit_id, a.id_user AS user_id, (SELECT SUM(a.keterlambatan) FROM tm_jadwalpegawai_absensi_detail a WHERE a.id_unit = '" . $id_unit . "' AND a.month = " . $month . " AND a.year = " . $year . " AND a.id_user = '" . $id_user . "') AS jml_telat, (SELECT SUM( a.pulang_cepat ) FROM tm_jadwalpegawai_absensi_detail a WHERE a.id_unit = '" . $id_unit . "' AND a.month = " . $month . " AND a.year = " . $year . " AND a.id_user = '" . $id_user . "') AS jml_pulang_cepat, ";

            // ambil data ketidakhadiran shift
            $arr_shift_ketidakhadiran = array();
            $sql = bukaquery2("SELECT a.id_ketidakhadiran, REPLACE(LOWER(a.id_ketidakhadiran), '-', '') AS id_ketidakhadiran_lower FROM tm_shift_ketidakhadiran a");

            // convert jadi array
            while ($row = fetch_array($sql)) {
                array_push($arr_shift_ketidakhadiran, array(
                    $row['id_ketidakhadiran'],
                    $row['id_ketidakhadiran_lower']
                ));
            }

            // buat subquery u mendapatkan jumlah ketidakhadiran berdasarkan tm_shift_ketidakhadiran
            for ($i = 0; $i < count($arr_shift_ketidakhadiran); $i++) {

                $query_rekap_shift_pegawai .= "(SELECT COUNT(a.date) AS jml FROM tm_jadwalpegawai_absensi_detail a WHERE a.id_unit = unit_id AND a.month = " . $month . " AND a.year = " . $year . " AND a.id_user = user_id AND a.id_ketidakhadiran = '" . $arr_shift_ketidakhadiran[$i][0] . "') AS jml_" . $arr_shift_ketidakhadiran[$i][1] . ", ";
            }

            // ambil data tm_shift_tipe
            $arr_shift_shift_tipe = array();
            $sql = bukaquery2("SELECT a.id_absensi_tipe, REPLACE(LOWER(a.id_absensi_tipe), '-', '') AS id_absensi_tipe_lower FROM tm_shift_tipe a");

            // convert jadi array
            while ($row = fetch_array($sql)) {

                array_push($arr_shift_shift_tipe, array(
                    $row['id_absensi_tipe'],
                    $row['id_absensi_tipe_lower']
                ));
            }

            // buat subquery u mendapatkan jumlah ketidakhadiran berdasarkan tm_shift_ketidakhadiran
            for ($i = 0; $i < count($arr_shift_shift_tipe); $i++) {

                $query_rekap_shift_pegawai .= "(SELECT COUNT(a.date) AS jml FROM tm_jadwalpegawai_absensi_detail a WHERE a.id_unit = unit_id AND a.month = " . $month . " AND a.year = " . $year . " AND a.id_user = user_id AND a.id_absensi_tipe = '" . $arr_shift_shift_tipe[$i][0] . "') AS jml_" . $arr_shift_shift_tipe[$i][1] . ", ";
            }
            // subquery u menghitung jumlah hari kerja dari tm_jadwalpegawai_absensi_detail
            $query_rekap_shift_pegawai .= " (SELECT COUNT(a.id_absensi) FROM tm_jadwalpegawai_absensi_detail a WHERE a.id_absensi IN (SELECT a.id_absensi FROM tm_shift a WHERE a.counting_work = 1) AND a.id_unit = unit_id AND a.month = " . $month . " AND a.year = " . $year . " AND a.id_user = user_id GROUP BY a.id_user) AS jml_hari_kerja, ";

            //subquery untuk menghitung jumlah menit kerja dari tm_jadwal
            $query_rekap_shift_pegawai .= "(SELECT SUM(b.working_time_minute) FROM tm_jadwalpegawai_absensi_detail a LEFT JOIN tm_shift b ON a.id_absensi = b.id_absensi WHERE a.id_unit = unit_id AND a.month = " . $month . " AND a.year = " . $year . " AND a.id_user = user_id AND (a.id_absensi <> '' AND a.id_absensi IS NOT NULL)) AS jml_menit_kerja ";

            // from, join dan groupby dari table tm_jadwalpegawai_absensi_detail
            $query_rekap_shift_pegawai .= " FROM tm_jadwalpegawai_absensi_detail a WHERE a.month = " . $month . " AND a.year = " . $year . " AND a.id_user = '" . $id_user . "' GROUP BY a.id_user";

            // eksekusi query
            $sql =  bukaquery2($query_rekap_shift_pegawai);
            $result = fetch_array($sql);

            bukaquery2("UPDATE tm_jadwalpegawai_absensi_rekap
                SET 
                k_jml_alpha = '" . $result['jml_akt000001'] . "', k_jml_sakit_1hari = '" . $result['jml_akt000002'] . "', k_jml_sakit_2hari = '" . $result['jml_akt000003'] . "', k_jml_izin = '" . $result['jml_akt000004'] . "', k_jml_cuti_sakit = '" . $result['jml_akt000005'] . "', k_jml_izin_sethari = '" . $result['jml_akt000008'] . "', k_jml_meninggal = '" . $result['jml_akt000009'] . "', k_jml_telat = '" . $result['jml_telat'] . "', k_jml_plng_cepat = '" . $result['jml_pulang_cepat'] . "',
                t_jml_cuti_sakit = '" . $result['jml_akt000010'] . "', t_jml_cuti_alsnpenting = '" . $result['jml_akt000011'] . "', t_jml_cuti_thnan = '" . $result['jml_akt000012'] . "', t_jml_diklat = '" . $result['jml_akt000013'] . "', t_jml_spd = '" . $result['jml_akt000014'] . "', t_jml_haji = '" . $result['jml_akt000015'] . "',
                s_jml_hks = '" . $result['jml_abt000001'] . "', s_jml_hkm = '" . $result['jml_abt000002'] . "', s_jml_hlp = '" . $result['jml_abt000003'] . "', s_jml_hls = '" . $result['jml_abt000004'] . "', s_jml_hlm = '" . $result['jml_abt000005'] . "', s_jml_hrp = '" . $result['jml_abt000006'] . "', s_jml_hrs = '" . $result['jml_abt000007'] . "', s_jml_hrm = '" . $result['jml_abt000008'] . "', s_jml_ns = '" . $result['jml_abt000009'] . "', 
                jml_hari_kerja = '" . $result['jml_hari_kerja'] . "' , jml_menit_kerja = '" . $result['jml_menit_kerja'] . "'
                WHERE id_unit = '" . $id_unit . "' AND month = " . $month . " AND year = " . $year . " AND id_user = '" . $id_user . "'
            ");
        }

        return array(
            "status" => 1,
            "message" => "Perbarui shift selesai"
        );
    }

    function put_absensimasuk_detail_absensi_pegawai($id_kepegawaian, $id_user, $id_unit, $month, $year, $date, $absensi_masuk_selected)
    {

        $sql = bukaquery2("SELECT 
				a.jam_masuk_absensi_aktif, 
				GREATEST(IFNULL(TIME_TO_SEC(TIMEDIFF(TIME('" . $absensi_masuk_selected . "'), TIME(a.jam_masuk_absensi_aktif)))/60, ''), 0) AS keterlambatan
			FROM tm_jadwalpegawai_absensi_detail a
			WHERE a.id_unit = '" . $id_unit . "'
				AND a.month = " . $month . "
				AND a.year = " . $year . "
				AND a.id_user = '" . $id_user . "'
				AND a.date = '" . $date . "'
		");

        $result = fetch_array($sql);

        bukaquery2("UPDATE tm_jadwalpegawai_absensi_detail
			SET absensi_masuk = '" . $absensi_masuk_selected . "', 
				keterlambatan = " . $result['keterlambatan'] . ", 
				id_kepegawaian = '" . $id_kepegawaian . "'
			WHERE id_unit = '" . $id_unit . "'
				AND month = " . $month . "
				AND year = " . $year . "
				AND id_user = '" . $id_user . "'
				AND date = '" . $date . "' 
		");

        bukaquery2("UPDATE tm_jadwalpegawai_absensi_rekap
			SET k_jml_telat = (SELECT SUM(a.keterlambatan)
				FROM tm_jadwalpegawai_absensi_detail a 
				WHERE a.id_unit = '" . $id_unit . "'
					AND a.month = " . $month . " 
					AND a.year = " . $year . "
					AND a.id_user = '" . $id_user . "')
				, id_kepegawaian = '" . $id_kepegawaian . "'
			WHERE id_unit = '" . $id_unit . "'
				AND month = " . $month . " 
				AND year = " . $year . "
				AND id_user = '" . $id_user . "'
		");

        return array(
            "status" => 1,
            "message" => "Absensi masuk pegawai berhasil diperbarui"
        );
    }

    function put_absensipulang_detail_absensi_pegawai($id_kepegawaian, $id_user, $id_unit, $month, $year, $date, $absensi_pulang_selected)
    {

        $sql = bukaquery2("SELECT 
				a.jam_pulang_absensi_aktif, 
				GREATEST(IFNULL(TIME_TO_SEC(TIMEDIFF(TIME(a.jam_pulang_absensi_aktif), TIME('" . $absensi_pulang_selected . "')))/60, ''), 0) AS pulang_cepat
			FROM tm_jadwalpegawai_absensi_detail a
			WHERE a.id_unit = '" . $id_unit . "'
				AND a.month = " . $month . "
				AND a.year = " . $year . "
				AND a.id_user = '" . $id_user . "'
				AND a.date = '" . $date . "'
		");

        $result = fetch_array($sql);

        bukaquery2("UPDATE tm_jadwalpegawai_absensi_detail
			SET absensi_pulang = '" . $absensi_pulang_selected . "', 
				pulang_cepat = " . $result['pulang_cepat'] . ", 
				id_kepegawaian = '" . $id_kepegawaian . "'
			WHERE id_unit = '" . $id_unit . "'
				AND month = " . $month . "
				AND year = " . $year . "
				AND id_user = '" . $id_user . "'
				AND date = '" . $date . "' 
		");

        bukaquery2("UPDATE tm_jadwalpegawai_absensi_rekap
			SET k_jml_plng_cepat = (SELECT SUM(a.pulang_cepat)
				FROM tm_jadwalpegawai_absensi_detail a 
				WHERE a.id_unit = '" . $id_unit . "'
					AND a.month = " . $month . " 
					AND a.year = " . $year . "
					AND a.id_user = '" . $id_user . "')
				, id_kepegawaian = '" . $id_kepegawaian . "'
			WHERE id_unit = '" . $id_unit . "'
				AND month = " . $month . " 
				AND year = " . $year . "
				AND id_user = '" . $id_user . "'
		");

        return array(
            "status" => 1,
            "message" => "Absensi pulang pegawai berhasil diperbarui"
        );
    }

    function put_keterlambatan_absensi_pegawai($id_kepegawaian, $id_user, $id_unit, $month, $year, $date, $keterlambatan_old, $keterlambatan_new, $keterangan)
    {

        $sql = bukaquery2("
            SELECT
                a.id_jadwalkerja_absensi
            FROM tm_jadwalpegawai_absensi_detail a
            WHERE id_unit = '" . $id_unit . "'
                AND month = " . $month . "
                AND year = " . $year . "
                AND id_user = '" . $id_user . "'
                AND date = '" . $date . "'
        ");

        $id_jadwalkerja_absensi = fetch_array($sql)['id_jadwalkerja_absensi'];

        bukaquery2("
            INSERT INTO tm_jadwalpegawai_absensi_telat_log 
            (id_jadwalkerja_absensi, keterlambatan_old, keterlambatan_new, keterangan, id_kepegawaian)
            VALUES
            ('" . $id_jadwalkerja_absensi . "', '" . $keterlambatan_old . "', '" . $keterlambatan_new . "', '" . $keterangan . "', '" . $id_kepegawaian . "')
        ");

        bukaquery2("
            UPDATE
                tm_jadwalpegawai_absensi_detail
            SET keterlambatan = keterlambatan - " . $keterlambatan_old . " + " . $keterlambatan_new . ", keterangan_keterlambatan = '" . $keterangan . "', id_kepegawaian = '" . $id_kepegawaian . "'
            WHERE id_unit = '" . $id_unit . "'
                AND month = " . $month . "
                AND year = " . $year . "
                AND id_user = '" . $id_user . "'
                AND date = '" . $date . "'
        ");

        bukaquery2("
            UPDATE
                tm_jadwalpegawai_absensi_rekap 
            SET k_jml_telat = k_jml_telat - " . $keterlambatan_old . " + " . $keterlambatan_new . ", id_kepegawaian = '" . $id_kepegawaian . "'
            WHERE id_unit = '" . $id_unit . "'
                AND month = " . $month . "
                AND year = " . $year . "
                AND id_user = '" . $id_user . "'
        ");

        return array(
            "status" => 1,
            "message" => "Keterlambatan Pegawai berhasil diperbarui"
        );
    }

    function put_pulangcepat_absensi_pegawai($id_kepegawaian, $id_user, $id_unit, $month, $year, $date, $pulangcepat_old, $pulangcepat_new, $keterangan)
    {

        $sql = bukaquery2("
            SELECT
                a.id_jadwalkerja_absensi
            FROM tm_jadwalpegawai_absensi_detail a
            WHERE id_unit = '" . $id_unit . "'
                AND month = " . $month . "
                AND year = " . $year . "
                AND id_user = '" . $id_user . "'
                AND date = '" . $date . "'
        ");

        $id_jadwalkerja_absensi = fetch_array($sql)['id_jadwalkerja_absensi'];

        bukaquery2("
            INSERT INTO tm_jadwalpegawai_absensi_plgcpt_log 
            (id_jadwalkerja_absensi, plng_cepat_old, plng_cepat_new, keterangan, id_kepegawaian)
            VALUES
            ('" . $id_jadwalkerja_absensi . "', '" . $pulangcepat_old . "', '" . $pulangcepat_new . "', '" . $keterangan . "', '" . $id_kepegawaian . "')
        ");

        bukaquery2("
            UPDATE
                tm_jadwalpegawai_absensi_detail
            SET pulang_cepat = pulang_cepat - " . $pulangcepat_old . " + " . $pulangcepat_new . ", keterangan_pulangcepat = '" . $keterangan . "', id_kepegawaian = '" . $id_kepegawaian . "'
            WHERE id_unit = '" . $id_unit . "'
                AND month = " . $month . "
                AND year = " . $year . "
                AND id_user = '" . $id_user . "'
                AND date = '" . $date . "'
        ");

        bukaquery2("
            UPDATE
                tm_jadwalpegawai_absensi_rekap 
            SET k_jml_plng_cepat = k_jml_plng_cepat - " . $pulangcepat_old . " + " . $pulangcepat_new . ", id_kepegawaian = '" . $id_kepegawaian . "'
            WHERE id_unit = '" . $id_unit . "'
                AND month = " . $month . "
                AND year = " . $year . "
                AND id_user = '" . $id_user . "'
        ");

        return array(
            "status" => 1,
            "message" => "Absensi pulang pegawai berhasil diperbarui"
        );
    }


    function update_absensi_pegawai($id_kepegawaian, $month, $year)
    {

        bukaquery2("UPDATE tm_jadwalpegawai_absensi_session
			SET id_kepegawaian_accepted = '" . $id_kepegawaian . "', accepted = '1', timestamp_accepted = NOW()
			WHERE `month` = '" . $month . "' AND `year` = '" . $year . "'
		");

        /// get date from tm_jadwalpegawai_absensi_rekap
        $sql = bukaquery2("
            SELECT 
                a.id_user, 
                a.k_jml_sakit_1hari, a.k_jml_sakit_2hari, a.k_jml_alpha, a.k_jml_izin, a.k_jml_izin_sethari, a.k_jml_telat, a.k_jml_plng_cepat,a.k_jml_cuti_sakit, a.k_jml_cuti_alsnpenting, a.k_jml_cuti_prslnan, a.k_jml_meninggal, 
                a.t_jml_cuti_sakit, a.t_jml_cuti_alsnpenting, a.t_jml_cuti_thnan, a.t_jml_diklat, a.t_jml_spd, a.t_jml_haji, 
                a.s_jml_hks, a.s_jml_hkm, a.s_jml_hlp, a.s_jml_hls, a.s_jml_hlm, a.s_jml_hrp, a.s_jml_hrs, a.s_jml_hrm, a.s_jml_ns,
			NOW() AS now
			FROM tm_jadwalpegawai_absensi_rekap a
			WHERE a.month = " . $month . " AND a.year = " . $year . "
		");

        // get the last date in selected month
        $date_update = date('Y-m-t', mktime(0, 0, 0, $month, date("d"), $year));

        // insert into tm_waktu_k, tm_waktu_t, tm_waktu_s
        while ($row = fetch_array($sql)) {

            // TM_WAKTU_T
            $id_waktu_t = getOne("
                SELECT
                    a.id_waktu_t
                FROM tm_waktu_t a
                WHERE a.id_user = '" . $row['id_user'] . "'
                    AND date_t = '" . $date_update . "'
            ");

            // digunakan apablla kondisi update tapi ada yg tertinggal (sehingga harus insert)
            if (isset($id_waktu_t)) {

                // update to tm_waktu_t
                bukaquery2("
                    UPDATE
                        tm_waktu_t
                    SET ct_sakit_t = '" . $row['t_jml_cuti_sakit'] . "', ct_alasan_t = '" . $row['t_jml_cuti_alsnpenting'] . "',
                        ct_tahunan_t = '" . $row['t_jml_cuti_thnan'] . "', diklat = '" . $row['t_jml_diklat'] . "',
                        spd = '" . $row['t_jml_spd'] . "', haji = '" . $row['t_jml_haji'] . "'
                    WHERE id_waktu_t = '" . $id_waktu_t . "'
                ");
            } else {

                // insert to tm_waktu_t
                bukaquery2("
                    INSERT INTO tm_waktu_t (id_waktu_t, id_user, ct_sakit_t, ct_alasan_t, ct_tahunan_t, diklat, spd, haji, date_t)
                    VALUES ('" . nokiamat('id_waktu_t', 'tm_waktu_t') . "', '" . $row['id_user'] . "', '" . $row['t_jml_cuti_sakit'] . "', '" . $row['t_jml_cuti_alsnpenting'] . "', '" . $row['t_jml_cuti_thnan'] . "', '" . $row['t_jml_diklat'] . "', '" . $row['t_jml_spd'] . "', '" . $row['t_jml_haji'] . "', '" . $date_update . "')
                ");
            }


            // TM_WAKTU_S
            $id_waktu_s = getOne("
                SELECT
                    a.id_waktu_s
                FROM tm_waktu_s a
                WHERE a.id_user = '" . $row['id_user'] . "'
                    AND date_s = '" . $date_update . "'
            ");

            // digunakan apablla kondisi update tapi ada yg tertinggal (sehingga harus insert)
            if (isset($id_waktu_s)) {

                // update to tm_waktu_s
                bukaquery2("
                    UPDATE
                        tm_waktu_s
                    SET j_hks = '" . $row['s_jml_hks'] . "', j_hkm = '" . $row['s_jml_hkm'] . "', j_hlp = '" . $row['s_jml_hlp'] . "',
                        j_hls = '" . $row['s_jml_hls'] . "', j_hlm = '" . $row['s_jml_hlm'] . "', j_hrp = '" . $row['s_jml_hrp'] . "',
                        j_hrs = '" . $row['s_jml_hrs'] . "', j_hrm = '" . $row['s_jml_hrm'] . "', j_ns = '" . $row['s_jml_ns'] . "'
                    WHERE id_waktu_s = '" . $id_waktu_s . "'
                ");
            } else {

                // insert to tm_waktu_s
                bukaquery2("
                    INSERT INTO tm_waktu_s (id_waktu_s, id_user, j_hks, j_hkm, j_hlp, j_hls, j_hlm, j_hrp, j_hrs, j_hrm, j_ns, date_s)
                    VALUES ('" . nokiamat('id_waktu_s', 'tm_waktu_s') . "', '" . $row['id_user'] . "', '" . $row['s_jml_hks'] . "', '" . $row['s_jml_hkm'] . "', '" . $row['s_jml_hlp'] . "', '" . $row['s_jml_hls'] . "', '" . $row['s_jml_hlm'] . "', '" . $row['s_jml_hrp'] . "', '" . $row['s_jml_hrs'] . "', '" . $row['s_jml_hrm'] . "', '" . $row['s_jml_ns'] . "', '" . $date_update . "')
                ");
            }


            // TM_WAKTU_K
            $id_waktu_k = getOne("
                SELECT
                    a.id_waktu_k
                FROM tm_waktu_k a
                WHERE a.id_user = '" . $row['id_user'] . "'
                    AND date_k = '" . $date_update . "'
            ");

            // digunakan apablla kondisi update tapi ada yg tertinggal (sehingga harus insert)
            if (isset($id_waktu_k)) {

                // update to tm_waktu_k
                bukaquery2("
                    UPDATE
                        tm_waktu_k
                    SET sakit1 = '" . $row['k_jml_sakit_1hari'] . "', sakit2 = '" . $row['k_jml_sakit_2hari'] . "', alpha = '" . $row['k_jml_alpha'] . "', izin = '" . $row['k_jml_izin'] . "',
                        izin_setengah_hari = '" . $row['k_jml_izin_sethari'] . "', telat = '" . $row['k_jml_telat'] . "', pulang_cepat = '" . $row['k_jml_plng_cepat'] . "', ct_sakit_k = '" . $row['k_jml_cuti_sakit'] . "',
                        ct_alasan_k = '" . $row['k_jml_cuti_alsnpenting'] . "', ct_persalinan_k = '" . $row['k_jml_cuti_prslnan'] . "', meninggal = '" . $row['k_jml_meninggal'] . "'
                    WHERE id_waktu_k = '" . $id_waktu_k . "'
                ");
            } else {

                // insert to tm_waktu_k
                bukaquery2("
                    INSERT INTO tm_waktu_k (id_waktu_k, id_user, sakit1, sakit2, alpha, izin, izin_setengah_hari, telat, pulang_cepat, ct_sakit_k, ct_alasan_k, ct_persalinan_k, meninggal, date_k)
                    VALUES ('" . nokiamat('id_waktu_k', 'tm_waktu_k') . "', '" . $row['id_user'] . "', '" . $row['k_jml_sakit_1hari'] . "', '" . $row['k_jml_sakit_2hari'] . "', '" . $row['k_jml_alpha'] . "', '" . $row['k_jml_izin'] . "', '" . $row['k_jml_izin_sethari'] . "', '" . $row['k_jml_telat'] . "', '" . $row['k_jml_plng_cepat'] . "', '" . $row['k_jml_cuti_sakit'] . "', '" . $row['k_jml_cuti_alsnpenting'] . "', '" . $row['k_jml_cuti_prslnan'] . "', '" . $row['k_jml_meninggal'] . "', '" . $date_update . "')
                ");
            }
        }

        return array(
            "status" => 1,
            "message" => "Update rekapitulasi selesai"
        );
    }

    function insert_log_absensi_pegawai($id_kepegawaian, $month, $year)
    {

        // insert log into tm_jadwalpegawai_shift_log
        $sql = bukaquery2("SELECT a.nama_pegawai FROM tm_pegawai a WHERE a.id_user = '" . $id_kepegawaian . "' "); // ambil data nama pegawai
        $array_log = serialize(array(
            "keterangan" => "Shift direkapitulasi oleh " . fetch_array($sql)['nama_pegawai'],
            "catatan" => "-"
        ));

        $sql = bukaquery2("SELECT a.id_unit
			FROM tm_jadwalpegawai_absensi_rekap a
			WHERE a.month = " . $month . " AND a.year = " . $year . "
			GROUP BY id_unit
		");

        while ($row = fetch_array($sql)) {

            //masukkan ke tm_jadwalpegawai_shift_log array("keterangan" => "Jadwal Shift Direkapitulasi oleh A" , "catatan" => "-")
            bukaquery2("INSERT INTO tm_jadwalpegawai_shift_log (id_unit, month, year, data) VALUES('" . $row['id_unit'] . "', " . $month . ", " . $year . ", '" . $array_log . "')");
        }

        return array(
            "status" => 1,
            "message" => "Generate Log dan Notif Absensi Pegawai Selesai"
        );
    }

    
    function rekap_cuti($month, $year, $id_unit)
    {
        // cari tm_jadwalpegawai_absensi_detail yang dihitung sebagai ALPHA AKT-000001
        // cari apakah di tanggal tsb, pegawai cuti (tm_hari_cuti)
        // jika iya, ubah ALPHA menjadi cuti

        // list absensi alpha
        $list_pegawai_alpha = array();
        $sql = bukaquery2("
            SELECT
                a.id_jadwalkerja_absensi, a.date, a.id_user
            FROM tm_jadwalpegawai_absensi_detail AS a
            WHERE a.`month` = '".$month."' 
                AND a.`year` = '".$year."' 
                AND a.id_unit = '".$id_unit."'
                AND a.id_ketidakhadiran = 'AKT-000001'
            ORDER BY a.id_user
        ");
        while ($row = fetch_assoc($sql)) {
            array_push($list_pegawai_alpha, $row);
        }

        // list cuti pegawai
        $list_cuti_pegawai = array();
        $sql = bukaquery2("
            SELECT
                a.id_user, b.tanggal, a.id_ketidakhadiran
            FROM tm_cuti AS a
                INNER JOIN tm_hari_cuti AS b ON a.id_cuti = b.id_cuti
            WHERE a.aktif = '1' 
                AND a.acc_pj IN ('Y', '-')
                AND YEAR(b.tanggal) = '".$year."' 
                AND MONTH(b.tanggal) = '".$month."'
            ORDER BY a.id_user, b.tanggal
        ");
        while ($row = fetch_assoc($sql)) {
            array_push($list_cuti_pegawai, $row);
        }

        foreach($list_pegawai_alpha AS $pegawai) {

            foreach ($list_cuti_pegawai as $cuti) {
                
                if(
                    $pegawai['id_user'] == $cuti['id_user'] &&
                    $pegawai['date'] == $cuti['tanggal']
                ) {

                    // update ALPHA menjadi cuti
                    bukaquery2("
                        UPDATE tm_jadwalpegawai_absensi_detail
                        SET id_ketidakhadiran = '".$cuti['id_ketidakhadiran']."', shift_aktif = '0', absensi_masuk = '', absensi_pulang = '', keterlambatan = '0', pulang_cepat = '0'
                        WHERE id_jadwalkerja_absensi = '".$pegawai['id_jadwalkerja_absensi']."'
                    ");
                }
            }
        }

        // hitung ulang dari tm_jadwalpegawai_absensi_detail
        // untuk mendapatkan jumlah alpha, hks, hkm, hlp, hls, hlm, hrp, hrs, hrm, ns dan jumlah hari kerja
        // dan juga menghitung ulang keterlambatan dan pulang cepat
        $list_iduser = "'".implode("', '", array_values(array_unique(array_column($list_pegawai_alpha, 'id_user'))))."'";

        // inisial query
        $query_pencarian_alpha_pegawai = "SELECT a.id_user AS user_id, a.id_unit AS unit_id, ";

        // query jml_alpha
        $query_pencarian_alpha_pegawai .= "
            IFNULL((
                SELECT
                    COUNT(a.date) AS jml
                FROM tm_jadwalpegawai_absensi_detail a
                WHERE a.id_unit = unit_id
                    AND a.month = " . $month . "
                    AND a.year = " . $year . "
                    AND a.id_user = user_id
                    AND a.id_ketidakhadiran = 'AKT-000001'), 0
            ) AS jml_akt000001, ";

        // subquery untuk mendapatkan jumlah alpha, hks, hkm, hlp, hls, hlm, hrp, hrs, hrm, ns dan jumlah hari kerja
        $sql = bukaquery2("
            SELECT
                a.id_absensi_tipe,
                REPLACE(LOWER(a.id_absensi_tipe), '-', '') AS id_absensi_tipe_lower
            FROM tm_shift_tipe a
        ");
        while ($row = fetch_array($sql)) {

            $query_pencarian_alpha_pegawai .= "
                (
                SELECT
                    COUNT(a.date) AS jml 
                FROM tm_jadwalpegawai_absensi_detail a
                WHERE a.id_unit = unit_id
                    AND a.month = " . $month . "
                    AND a.year = " . $year . "
                    AND a.id_user = user_id
                    AND a.id_absensi_tipe = '" . $row['id_absensi_tipe'] . "'
                ) AS jml_" . $row['id_absensi_tipe_lower'] . ", ";
        }

        // subquery untuk menghitung jumlah hari kerja
        $query_pencarian_alpha_pegawai .= "IFNULL((
                SELECT
                    COUNT(a.id_absensi)
                FROM tm_jadwalpegawai_absensi_detail a
                WHERE a.id_absensi IN (SELECT a.id_absensi FROM tm_shift a WHERE a.counting_work = 1) 
                    AND a.id_unit = unit_id 
                    AND a.month = " . $month . " 
                    AND a.year = " . $year . " 
                    AND a.id_user = user_id 
                GROUP BY a.id_user
        ), 0) AS jml_hari_kerja ";

        // lanjutan querynya
        $query_pencarian_alpha_pegawai .= "FROM
                tm_jadwalpegawai_absensi_detail a
            WHERE a.id_unit = '".$id_unit."'
                AND a.month = " . $month . "
                AND a.year = " . $year . "
                AND a.id_user IN (".$list_iduser.")
            GROUP BY a.id_user
        ";
        $sql = bukaquery2($query_pencarian_alpha_pegawai);

        while ($row = fetch_array($sql)) {

            $id_unit = $row['unit_id'];
            $id_user = $row['user_id'];
            $jml_alpha = $row['jml_akt000001'];
            $jml_hks = $row['jml_abt000001'];
            $jml_hkm = $row['jml_abt000002'];
            $jml_hlp = $row['jml_abt000003'];
            $jml_hls = $row['jml_abt000004'];
            $jml_hlm = $row['jml_abt000005'];
            $jml_hrp = $row['jml_abt000006'];
            $jml_hrs = $row['jml_abt000007'];
            $jml_hrm = $row['jml_abt000008'];
            $jml_hns = $row['jml_abt000009'];
            $jml_hari_kerja = $row['jml_hari_kerja'];

            // update ke tm_jadwalpegawai_absensi_rekap
            bukaquery2("
                UPDATE
                    tm_jadwalpegawai_absensi_rekap
                SET k_jml_alpha = '" . $jml_alpha . "',
                    s_jml_hks = '" . $jml_hks . "', s_jml_hkm = '" . $jml_hkm . "', s_jml_hlp = '" . $jml_hlp . "', s_jml_hls = '" . $jml_hls . "', s_jml_hlm = '" . $jml_hlm . "', s_jml_hrp = '" . $jml_hrp . "', s_jml_hrs = '" . $jml_hrs . "', s_jml_hrm = '" . $jml_hrm . "', s_jml_ns = '" . $jml_hns . "',
                    jml_hari_kerja = '" . $jml_hari_kerja . "'
                WHERE id_user = '" . $id_user . "'
                    AND id_unit = '" . $id_unit . "'
                    AND month = " . $month . "
                    AND year = " . $year . "
            ");
        }

        // hitung ulang keterlambatan dan pulang cepat
        // dan perbarui di tm_jadwalpegawai_absensi_rekap
        $sql = bukaquery2("
            SELECT
                a.id_user,
                SUM(a.keterlambatan) AS total_keterlambatan, 
                SUM(a.pulang_cepat) AS total_pulang_cepat
            FROM tm_jadwalpegawai_absensi_detail a
            WHERE a.id_unit = '" . $id_unit . "'
                AND a.`month` = '" . $month . "'
                AND a.`year` = '" . $year . "'
            GROUP BY a.id_user
        ");
        while ($row = fetch_array($sql)) {

            bukaquery2("
            UPDATE
                tm_jadwalpegawai_absensi_rekap
            SET k_jml_telat = " . $row['total_keterlambatan'] . ", k_jml_plng_cepat = " . $row['total_pulang_cepat'] . "
            WHERE id_user = '" . $row['id_user'] . "'
                AND id_unit = '" . $id_unit . "'
                AND month = '" . $month . "'
                AND year = '" . $year . "'
            ");
        }

        return array(
            "status" => 1,
            "message" => "Rekap Cuti Selesai"
        );
    }

    function generate_listuser_rekapitulasi_shift_absensi_pegawai($id_kepegawaian, $month, $year, $list_unit_serialized_temp)
    {

        $res_id_unit = array();
        $res_list_iduser = array();
        $id_unit_spesialis = 'UNT-000003'; // unit dokter spesialis
        $generate_unit_spesialis = false; // default false, akan true apabila didalam list_unit_serialize ada id_unit nya

        // create session id by month-year
        bukaquery2("INSERT INTO tm_jadwalpegawai_absensi_session (month, year, id_kepegawaian_generated, timestamp_generated)
		VALUES (" . $month . ", " . $year . ", '" . $id_kepegawaian . "', NOW())");

        // get session id
        $sql = bukaquery2("SELECT 
                a.id_jadwalpegawai_absensi_rekap_session
            FROM tm_jadwalpegawai_absensi_session a
            WHERE a.month = '" . $month . "' AND a.year = '" . $year . "'
            ORDER BY id_jadwalpegawai_absensi_rekap_session DESC
            LIMIT 1
        ");
        $id_session = fetch_array($sql)['id_jadwalpegawai_absensi_rekap_session'];

        $list_unit_serialized = json_decode(stripslashes($list_unit_serialized_temp));

        // apabila dalam list_unit terdapat id_unit_spesialis karena mekanisme nariknya berbeda
        // dipisahkan dari list_unit dan diset true generate_unit_spesialis
        if (($index = array_search($id_unit_spesialis, $list_unit_serialized)) !== FALSE) {

            $generate_unit_spesialis = true;
            unset($list_unit_serialized[$index]);
            $list_unit_serialized = array_values($list_unit_serialized);
        }

        $list_unit = "(";
        for ($i = 0; $i < count($list_unit_serialized); $i++) {

            $list_unit .= "'" . $list_unit_serialized[$i] . "'";
            if ($i + 1 < count($list_unit_serialized)) $list_unit .= ", ";
        }
        $list_unit .= ")";

        // jika list_unit kosong, berarti yg ditarik absen hanya spesialis
        // tidak ada list_user dan list_unit yg diberikan hanya flag generate_unit_spesialis jadi true
        if (count($list_unit_serialized) != 0) {

            $sql_list_pegawai = bukaquery2("SELECT 
                    a.id_unit, a.id_user
                FROM tm_jadwalpegawai_shift_m a
                    LEFT JOIN tm_jadwalpegawai_shift_validation b 
                        ON a.id_unit = b.id_unit
                        AND a.month = b.month 
                        AND a.year = b.year
                WHERE a.id_unit IN " . $list_unit . "
                    AND a.month = '" . $month . "'
                    AND a.year = '" . $year . "' 
                    AND a.submitted = '1' 
                    AND b.answered = '1'
                GROUP BY a.id_user
                ORDER BY a.id_unit, a.id_user
            ");

            while ($pegawai = fetch_array($sql_list_pegawai)) {
                array_push($res_list_iduser, array(
                    "id_unit" => $pegawai['id_unit'],
                    "id_user" => $pegawai['id_user']
                ));
            }
        }


        return array(
            "status" => 1,
            "message" => "Generate Rekap Absensi Pegawai Shift Selesai",
            "id_session" => $id_session,
            "list_id_unit" => $list_unit_serialized,
            "list_id_user" => $res_list_iduser,
            "generate_unit_spesialis" => $generate_unit_spesialis
        );
    }

    function generate_rekapitulasi_shift_absensi_pegawai($id_kepegawaian, $id_user, $id_unit, $month, $year, $id_session)
    {


        // buat query insert select u tm_jadwalpegawai_absensi_rekap
        $query_rekap_shift_pegawai = "SELECT a.id_unit AS unit_id, a.id_user AS user_id, 0 AS jml_telat, ";

        // ambil data ketidakhadiran shift
        $arr_shift_ketidakhadiran = array();
        $sql = bukaquery2("SELECT a.id_ketidakhadiran, REPLACE(LOWER(a.id_ketidakhadiran), '-', '') AS id_ketidakhadiran_lower FROM tm_shift_ketidakhadiran a");

        // convert jadi array
        while ($row = fetch_array($sql)) {
            array_push($arr_shift_ketidakhadiran, array(
                $row['id_ketidakhadiran'],
                $row['id_ketidakhadiran_lower']
            ));
        }

        // buat subquery u mendapatkan jumlah ketidakhadiran berdasarkan tm_shift_ketidakhadiran
        for ($i = 0; $i < count($arr_shift_ketidakhadiran); $i++) {

            $query_rekap_shift_pegawai .= "(SELECT COUNT(a.date) AS jml FROM tm_jadwalpegawai_shift_m a WHERE a.id_unit = unit_id AND a.month = " . $month . " AND a.year = " . $year . " AND a.id_user = user_id AND a.id_ketidakhadiran = '" . $arr_shift_ketidakhadiran[$i][0] . "') AS jml_" . $arr_shift_ketidakhadiran[$i][1] . ", ";
        }

        // ambil data tm_shift_tipe
        $arr_shift_shift_tipe = array();
        $sql = bukaquery2("SELECT a.id_absensi_tipe, REPLACE(LOWER(a.id_absensi_tipe), '-', '') AS id_absensi_tipe_lower FROM tm_shift_tipe a");

        // convert jadi array
        while ($row = fetch_array($sql)) {

            array_push($arr_shift_shift_tipe, array(
                $row['id_absensi_tipe'],
                $row['id_absensi_tipe_lower']
            ));
        }

        // buat subquery u mendapatkan jumlah shift pagi, sore, malam berdasarkan tm_shift_tipe
        for ($i = 0; $i < count($arr_shift_shift_tipe); $i++) {

            $query_rekap_shift_pegawai .= "(SELECT COUNT(a.date) AS jml FROM tm_jadwalpegawai_shift_m a WHERE a.id_unit = unit_id AND a.month = " . $month . " AND a.year = " . $year . " AND a.id_user = user_id AND a.id_absensi_tipe = '" . $arr_shift_shift_tipe[$i][0] . "') AS jml_" . $arr_shift_shift_tipe[$i][1] . ", ";
        }


        // subquery untuk menghitung jumlah hari kerja
        $query_rekap_shift_pegawai .= "IFNULL((SELECT COUNT(a.id_absensi) FROM tm_jadwalpegawai_shift_m a WHERE a.id_absensi IN (SELECT a.id_absensi FROM tm_shift a WHERE a.counting_work = 1) AND a.id_unit = unit_id AND a.month = " . $month . " AND a.year = " . $year . " AND a.id_user = user_id GROUP BY a.id_user), 0) AS jml_hari_kerja, ";

        // subquery untuk menghitung jumlah menit kerja
        $query_rekap_shift_pegawai .= "IFNULL((SELECT SUM(b.working_time_minute) FROM tm_jadwalpegawai_shift_m a LEFT JOIN tm_shift b ON a.id_absensi = b.id_absensi WHERE a.id_unit = unit_id AND a.month = " . $month . " AND a.year = " . $year . " AND a.id_user = user_id AND (a.id_absensi <> '' AND a.id_absensi IS NOT NULL)), 0)AS jml_menit_kerja, ";
        // lanjutan querynya
        $query_rekap_shift_pegawai .= "NOW() AS created FROM tm_jadwalpegawai_shift_m a 
            LEFT JOIN tm_jadwalpegawai_shift_validation b ON a.id_unit = b.id_unit AND a.month = b.month AND a.year = b.year
            WHERE a.id_user = '" . $id_user . "' 
                AND a.id_unit = '" . $id_unit . "' 
                AND a.month = " . $month . " 
                AND a.year = " . $year . " 
                AND a.submitted = 1 
                AND b.answered = 1 
            GROUP BY a.id_user 
            ORDER BY a.id_unit, a.id_user";

        $sql_rekap_shift_pegawai = bukaquery2($query_rekap_shift_pegawai);
        $res_rekap_shift_pegawai = fetch_array($sql_rekap_shift_pegawai);

        $id_jadwalpegawai_absensi_rekap_session = $id_session;
        $id_unit = $res_rekap_shift_pegawai['unit_id'];
        $month = $month;
        $year = $year;
        $id_user = $res_rekap_shift_pegawai['user_id'];
        $id_kepegawaian = $id_kepegawaian;
        $k_jml_telat = $res_rekap_shift_pegawai['jml_telat'];
        $k_jml_alpha = $res_rekap_shift_pegawai['jml_akt000001'];
        $k_jml_sakit_1hari = $res_rekap_shift_pegawai['jml_akt000002'];
        $k_jml_sakit_2hari = $res_rekap_shift_pegawai['jml_akt000003'];
        $k_jml_izin = $res_rekap_shift_pegawai['jml_akt000004'];
        $k_jml_cuti_prslnan = $res_rekap_shift_pegawai['jml_akt000005'];
        $k_jml_izin_sethari = $res_rekap_shift_pegawai['jml_akt000008'];
        $k_jml_meninggal = $res_rekap_shift_pegawai['jml_akt000009'];
        $t_jml_cuti_sakit = $res_rekap_shift_pegawai['jml_akt000010'];
        $t_jml_cuti_alsnpenting = $res_rekap_shift_pegawai['jml_akt000011'];
        $t_jml_cuti_thnan = $res_rekap_shift_pegawai['jml_akt000012'];
        $t_jml_diklat = ((int)$res_rekap_shift_pegawai['jml_akt000013']) + ((int) $res_rekap_shift_pegawai['jml_akt000016']);
        $t_jml_spd = $res_rekap_shift_pegawai['jml_akt000014'];
        $t_jml_haji = $res_rekap_shift_pegawai['jml_akt000015'];
        $s_jml_hks = $res_rekap_shift_pegawai['jml_abt000001'];
        $s_jml_hkm = $res_rekap_shift_pegawai['jml_abt000002'];
        $s_jml_hlp = $res_rekap_shift_pegawai['jml_abt000003'];
        $s_jml_hls = $res_rekap_shift_pegawai['jml_abt000004'];
        $s_jml_hlm = $res_rekap_shift_pegawai['jml_abt000005'];
        $s_jml_hrp = $res_rekap_shift_pegawai['jml_abt000006'];
        $s_jml_hrs = $res_rekap_shift_pegawai['jml_abt000007'];
        $s_jml_hrm = $res_rekap_shift_pegawai['jml_abt000008'];
        $s_jml_ns = $res_rekap_shift_pegawai['jml_abt000009'];
        $jml_hari_kerja = $res_rekap_shift_pegawai['jml_hari_kerja'];
        $jml_menit_kerja = $res_rekap_shift_pegawai['jml_menit_kerja'];
        $created = $res_rekap_shift_pegawai['created'];

        bukaquery2("INSERT INTO tm_jadwalpegawai_absensi_rekap (id_jadwalpegawai_absensi_rekap_session, id_unit, month, year, id_user, id_kepegawaian, 
        k_jml_telat, k_jml_alpha, k_jml_sakit_1hari, k_jml_sakit_2hari, k_jml_izin, k_jml_cuti_prslnan, k_jml_izin_sethari, k_jml_meninggal, t_jml_cuti_sakit, t_jml_cuti_alsnpenting, t_jml_cuti_thnan, t_jml_diklat,
        t_jml_spd, t_jml_haji, s_jml_hks, s_jml_hkm, s_jml_hlp, s_jml_hls, s_jml_hlm, s_jml_hrp, s_jml_hrs, s_jml_hrm, s_jml_ns, jml_hari_kerja, jml_menit_kerja, created)
        VALUES('" . $id_jadwalpegawai_absensi_rekap_session . "', '" . $id_unit . "', '" . $month . "', '" . $year . "', '" . $id_user . "', '" . $id_kepegawaian . "', 
        '" . $k_jml_telat . "', '" . $k_jml_alpha . "', '" . $k_jml_sakit_1hari . "', '" . $k_jml_sakit_2hari . "', '" . $k_jml_izin . "', '" . $k_jml_cuti_prslnan . "', '" . $k_jml_izin_sethari . "', '" . $k_jml_meninggal . "', '" . $t_jml_cuti_sakit . "', '" . $t_jml_cuti_alsnpenting . "', '" . $t_jml_cuti_thnan . "', '" . $t_jml_diklat . "', '" . $t_jml_spd . "', '" . $t_jml_haji . "', 
        '" . $s_jml_hks . "', '" . $s_jml_hkm . "', '" . $s_jml_hlp . "', '" . $s_jml_hls . "', '" . $s_jml_hlm . "', '" . $s_jml_hrp . "', '" . $s_jml_hrs . "', '" . $s_jml_hrm . "', '" . $s_jml_ns . "', 
        '" . $jml_hari_kerja . "', '" . $jml_menit_kerja . "', '" . $created . "')");


        return array(
            "status" => 1,
            "message" => "Generate Rekap Absensi Per Pegawai Shift Selesai"
        );
    }

    function generate_detail_shift_absensi_pegawai($id_kepegawaian, $month, $year, $id_unit, $list_pegawai)
    {

        $current_year = date('Y');
        $temp_sql = array();
        $temp_grouping = array();
        $default_minute_penalty = 225; // default pinalti akibat tidak absensi masuk/pulang (menit). Tidak berlaku apabila counting_as_isolman = 1
        
        // list_pegawai digunakan untuk memfilter
        $where_condition_pegawai = '';
        if (!empty($list_pegawai)) {
            // Trim untuk jaga-jaga ada spasi, lalu bungkus
            $where_condition_pegawai = "AND a.id_user IN ('" . str_replace(',', "','", trim($list_pegawai)) . "') ";
        }

        // get session id
        $sql = bukaquery2("SELECT 
                a.id_jadwalpegawai_absensi_rekap_session
            FROM tm_jadwalpegawai_absensi_session a
            WHERE a.month = " . $month . " AND a.year = " . $year . "
            ORDER BY id_jadwalpegawai_absensi_rekap_session DESC
            LIMIT 1
        ");

        $session_id = fetch_array($sql)['id_jadwalpegawai_absensi_rekap_session'];

        // clear data tm_jadwalpegawai_absensi_detail
        bukaquery2("DELETE a FROM tm_jadwalpegawai_absensi_detail a
        WHERE a.`month` = '" . $month . "' AND a.`year` = '" . $year . "' 
        AND a.id_unit = '" . $id_unit . "' ".$where_condition_pegawai);

        // rekapitulasi berdasarkan tipe shift PAGI & SORE
        $sql = bukaquery2("SELECT
                a.id_unit, a.id_user, b.log_finger, b.nama_pegawai, a.date,
                IFNULL(a.id_absensi, '') AS id_absensi, IFNULL(a.id_absensi_tipe, '') AS id_absensi_tipe,
                IFNULL(c.jam_masuk, '') AS jam_masuk_absensi_aktif, IFNULL(c.jam_pulang, '') AS jam_pulang_absensi_aktif,
                IFNULL(a.id_ketidakhadiran, '') AS id_ketidakhadiran,
                1 AS shift_aktif, c.counting_as_isolman,
                IFNULL(d.tanggal, '') AS absensi_masuk, FLOOR(GREATEST(0, IFNULL(TIME_TO_SEC(TIMEDIFF(TIME(d.tanggal), TIME(c.jam_masuk)))/60, IF(c.counting_as_isolman = 1, 0, " . $default_minute_penalty . ")))) AS keterlambatan, 
                IFNULL(e.tanggal, '') AS absensi_pulang, FLOOR(GREATEST(0, IFNULL(TIME_TO_SEC(TIMEDIFF(TIME(c.jam_pulang), TIME(e.tanggal)))/60, IF(c.counting_as_isolman = 1, 0, " . $default_minute_penalty . ")))) AS pulang_cepat
            FROM tm_jadwalpegawai_shift_m a
                INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                LEFT JOIN tm_shift c ON a.id_absensi = c.id_absensi
                LEFT JOIN log d ON b.log_finger = d.`user`
                    AND DATE(d.tanggal) = a.date
                    AND TIME(d.tanggal) > SUBTIME(c.bi, '00:00:01')
                    AND TIME(d.tanggal) < ADDTIME(c.ai, '00:10:00')
                LEFT JOIN log e ON b.log_finger = e.`user`
                    AND DATE(e.tanggal) = a.date
                    AND TIME(e.tanggal) > SUBTIME(c.bo, '00:10:00')
                    AND TIME(e.tanggal) < ADDTIME(c.ao, '00:00:01')
                LEFT JOIN tm_shift_ketidakhadiran f ON a.id_ketidakhadiran = f.id_ketidakhadiran
            WHERE a.id_unit = '" . $id_unit . "'
                AND a.`month` = '" . $month . "'
                AND a.`year` = '" . $year . "'
                ". $where_condition_pegawai ."
				AND (c.shift_tipe = 'PAGI' OR c.shift_tipe = 'SORE')
            ORDER BY a.id_unit, a.month, a.year, a.id_user, a.date, d.tanggal, e.tanggal DESC
        ");
        while ($row = fetch_assoc($sql)) {
            array_push($temp_sql, $row);
        }

        //akibat kegagalan dalam grouping data di query, maka data grouping dilakukan di aplikasi
        for ($i = 0; $i < count($temp_sql); $i++) {

            //akibat kegagalan dalam grouping data di query, maka data grouping dilakukan di aplikasi
            if (
                $i == 0 // apabila index 0
                || $temp_sql[$i]['date'] != $temp_sql[$i == 0 ? 0 : $i - 1]['date'] // apabila tanggalnya berbeda dengan sebelumnya
                || ($temp_sql[$i]['date'] == $temp_sql[$i == 0 ? 0 : $i - 1]['date'] && $temp_sql[$i]['id_absensi'] != $temp_sql[$i == 0 ? 0 : $i - 1]['id_absensi']) // apabila tanggalnya samaan tapi id_absensinya beda. berarti longshift
            ) {

                array_push($temp_grouping, $temp_sql[$i]);
            }
        }

        //untuk mendapatkan jumlah telat dan pulang cepat perbulan dan insert into tm_jadwalpegawai_absensi_detail
        for ($i = 0; $i < count($temp_grouping); $i++) {

            $temp_id_unit = $temp_grouping[$i]['id_unit'];
            $temp_id_user = $temp_grouping[$i]['id_user'];
            $temp_date = $temp_grouping[$i]['date'];
            $temp_id_absensi = $temp_grouping[$i]['id_absensi'];
            $temp_id_absensi_tipe = $temp_grouping[$i]['id_absensi_tipe'];
            $temp_id_ketidakhadiran = $temp_grouping[$i]['id_ketidakhadiran'];
            $temp_shift_aktif = $temp_grouping[$i]['shift_aktif'];
            $temp_jam_masuk_absensi_aktif = $temp_grouping[$i]['jam_masuk_absensi_aktif'];
            $temp_jam_pulang_absensi_aktif = $temp_grouping[$i]['jam_pulang_absensi_aktif'];
            $temp_absensi_masuk = $temp_grouping[$i]['absensi_masuk'];
            $temp_absensi_pulang = $temp_grouping[$i]['absensi_pulang'];
            $temp_keterlambatan = $temp_grouping[$i]['keterlambatan'];
            $temp_pulang_cepat = $temp_grouping[$i]['pulang_cepat'];

            // insert into tm_jadwalpegawai_absensi_detail
            bukainput2("INSERT INTO 
                    tm_jadwalpegawai_absensi_detail (id_jadwalpegawai_absensi_rekap_session, id_unit, month, year, date, id_user, id_absensi, id_absensi_tipe, id_ketidakhadiran, shift_aktif, jam_masuk_absensi_aktif, jam_pulang_absensi_aktif, absensi_masuk, absensi_pulang, keterlambatan, pulang_cepat, id_kepegawaian, created) 
                VALUES 
                    ('" . $session_id . "', '" . $temp_id_unit . "', " . $month . ", " . $year . ", '" . $temp_date . "', '" . $temp_id_user . "', '" . $temp_id_absensi . "', '" . $temp_id_absensi_tipe . "', '" . $temp_id_ketidakhadiran . "', " . $temp_shift_aktif . ", '" . $temp_jam_masuk_absensi_aktif . "', '" . $temp_jam_pulang_absensi_aktif . "', '" . $temp_absensi_masuk . "', '" . $temp_absensi_pulang . "', " . $temp_keterlambatan . ", " . $temp_pulang_cepat . ", '" . $id_kepegawaian . "', NOW())
            ");

            // update penambahan menit telat & pulang cepat di tm_jadwalpegawai_absensi_rekap
            bukaquery2("UPDATE 
                    tm_jadwalpegawai_absensi_rekap 
                SET k_jml_telat = k_jml_telat + '" . $temp_keterlambatan . "', k_jml_plng_cepat = k_jml_plng_cepat + '" . $temp_pulang_cepat . "' 
                WHERE id_unit = '" . $temp_id_unit . "' 
                    AND month = " . $month . " 
                    AND year = " . $year . " 
                    AND id_user = '" . $temp_id_user . "'
            ");
        }

        $temp_sql = array(); // clear array
        $temp_grouping = array(); // clear array
        $row = array(); // clear array
        // selesai rekapitulasi berdasarkan tipe shift PAGI & SORE


        // rekapitulasi berdasarkan tipe shift MALAM
        $sql = bukaquery2("SELECT
                a.id_unit, a.id_user, b.log_finger, b.nama_pegawai, a.date,
                IFNULL(a.id_absensi, '') AS id_absensi, IFNULL(a.id_absensi_tipe, '') AS id_absensi_tipe,
                IFNULL(c.jam_masuk, '') AS jam_masuk_absensi_aktif, IFNULL(c.jam_pulang, '') AS jam_pulang_absensi_aktif,
                IFNULL(a.id_ketidakhadiran, '') AS id_ketidakhadiran,
				1 AS shift_aktif, c.counting_as_isolman,
                IFNULL(d.tanggal, '') AS absensi_masuk, FLOOR(GREATEST(0, IFNULL(TIME_TO_SEC(TIMEDIFF(TIME(d.tanggal), TIME(c.jam_masuk)))/60, IF(c.counting_as_isolman = 1, 0, " . $default_minute_penalty . ")))) AS keterlambatan, 
                IFNULL(e.tanggal, '') AS absensi_pulang, FLOOR(GREATEST(0, IFNULL(TIME_TO_SEC(TIMEDIFF(TIME(c.jam_pulang), TIME(e.tanggal)))/60, IF(c.counting_as_isolman = 1, 0, " . $default_minute_penalty . ")))) AS pulang_cepat
            FROM tm_jadwalpegawai_shift_m a
                INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                LEFT JOIN tm_shift c ON a.id_absensi = c.id_absensi
                LEFT JOIN log d ON b.log_finger = d.`user`
                    AND DATE(d.tanggal) = a.date
                    AND TIME(d.tanggal) > SUBTIME(c.bi, '00:00:01')
                    AND TIME(d.tanggal) < ADDTIME(c.ai, '00:10:00')
                LEFT JOIN log e ON b.log_finger = e.`user`
                    AND DATE(e.tanggal) = DATE_ADD(a.date, INTERVAL 1 DAY)
                    AND TIME(e.tanggal) > SUBTIME(c.bo, '00:10:00')
                    AND TIME(e.tanggal) < ADDTIME(c.ao, '00:00:01')
                LEFT JOIN tm_shift_ketidakhadiran f ON a.id_ketidakhadiran = f.id_ketidakhadiran
            WHERE a.id_unit = '" . $id_unit . "'
                AND a.`month` = '" . $month . "'
                AND a.`year` = '" . $year . "'
                ". $where_condition_pegawai ."
				AND c.shift_tipe = 'MALAM'
            ORDER BY a.id_unit, a.month, a.year, a.id_user, a.date, d.tanggal, e.tanggal DESC
        ");
        while ($row = fetch_assoc($sql)) {
            array_push($temp_sql, $row);
        }

        //akibat kegagalan dalam grouping data di query, maka data grouping dilakukan di aplikasi
        for ($i = 0; $i < count($temp_sql); $i++) {

            //akibat kegagalan dalam grouping data di query, maka data grouping dilakukan di aplikasi
            if (
                $i == 0 // apabila index 0
                || $temp_sql[$i]['date'] != $temp_sql[$i == 0 ? 0 : $i - 1]['date'] // apabila tanggalnya berbeda dengan sebelumnya
                || ($temp_sql[$i]['date'] == $temp_sql[$i == 0 ? 0 : $i - 1]['date'] && $temp_sql[$i]['id_absensi'] != $temp_sql[$i == 0 ? 0 : $i - 1]['id_absensi']) // apabila tanggalnya samaan tapi id_absensinya beda. berarti longshift
            ) {

                array_push($temp_grouping, $temp_sql[$i]);
            }
        }

        //untuk mendapatkan jumlah telat dan pulang cepat perbulan dan insert into tm_jadwalpegawai_absensi_detail
        for ($i = 0; $i < count($temp_grouping); $i++) {

            $temp_id_unit = $temp_grouping[$i]['id_unit'];
            $temp_id_user = $temp_grouping[$i]['id_user'];
            $temp_date = $temp_grouping[$i]['date'];
            $temp_id_absensi = $temp_grouping[$i]['id_absensi'];
            $temp_id_absensi_tipe = $temp_grouping[$i]['id_absensi_tipe'];
            $temp_id_ketidakhadiran = $temp_grouping[$i]['id_ketidakhadiran'];
            $temp_shift_aktif = $temp_grouping[$i]['shift_aktif'];
            $temp_jam_masuk_absensi_aktif = $temp_grouping[$i]['jam_masuk_absensi_aktif'];
            $temp_jam_pulang_absensi_aktif = $temp_grouping[$i]['jam_pulang_absensi_aktif'];
            $temp_absensi_masuk = $temp_grouping[$i]['absensi_masuk'];
            $temp_absensi_pulang = $temp_grouping[$i]['absensi_pulang'];
            $temp_keterlambatan = $temp_grouping[$i]['keterlambatan'];
            $temp_pulang_cepat = $temp_grouping[$i]['pulang_cepat'];

            // insert into tm_jadwalpegawai_absensi_detail
            bukainput2("INSERT INTO 
                    tm_jadwalpegawai_absensi_detail (id_jadwalpegawai_absensi_rekap_session, id_unit, month, year, date, id_user, id_absensi, id_absensi_tipe, id_ketidakhadiran, shift_aktif, jam_masuk_absensi_aktif, jam_pulang_absensi_aktif, absensi_masuk, absensi_pulang, keterlambatan, pulang_cepat, id_kepegawaian, created) 
                VALUES 
                    ('" . $session_id . "', '" . $temp_id_unit . "', " . $month . ", " . $year . ", '" . $temp_date . "', '" . $temp_id_user . "', '" . $temp_id_absensi . "', '" . $temp_id_absensi_tipe . "', '" . $temp_id_ketidakhadiran . "', " . $temp_shift_aktif . ", '" . $temp_jam_masuk_absensi_aktif . "', '" . $temp_jam_pulang_absensi_aktif . "', '" . $temp_absensi_masuk . "', '" . $temp_absensi_pulang . "', " . $temp_keterlambatan . ", " . $temp_pulang_cepat . ", '" . $id_kepegawaian . "', NOW())
            ");

            // update penambahan menit telat & pulang cepat di tm_jadwalpegawai_absensi_rekap
            bukaquery2("UPDATE 
                    tm_jadwalpegawai_absensi_rekap 
                SET k_jml_telat = k_jml_telat + '" . $temp_keterlambatan . "', k_jml_plng_cepat = k_jml_plng_cepat + '" . $temp_pulang_cepat . "' 
                WHERE id_unit = '" . $temp_id_unit . "' 
                    AND month = " . $month . " 
                    AND year = " . $year . " 
                    AND id_user = '" . $temp_id_user . "'
            ");
        }

        $temp_sql = array(); // clear array
        $temp_grouping = array(); // clear array
        $row = array(); // clear array
        // selesai rekapitulasi berdasarkan tipe shift MALAM


        // rekapitulasi berdasarkan tipe shift ABSEN NON AKTIF (cuti, libur, dkk)
        $sql = bukaquery2("SELECT
                a.id_unit, a.id_user, b.log_finger, b.nama_pegawai, a.date,
                IFNULL(a.id_absensi, '') AS id_absensi, IFNULL(a.id_absensi_tipe, '') AS id_absensi_tipe,
                IFNULL(c.jam_masuk, '') AS jam_masuk_absensi_aktif, IFNULL(c.jam_pulang, '') AS jam_pulang_absensi_aktif,
                IFNULL(a.id_ketidakhadiran, '') AS id_ketidakhadiran,
                0 AS shift_aktif,
                IFNULL(d.tanggal, '') AS absensi_masuk, FLOOR(GREATEST(0, IFNULL(TIME_TO_SEC(TIMEDIFF(TIME(d.tanggal), TIME(c.jam_masuk)))/60, ''))) AS keterlambatan, 
                IFNULL(e.tanggal, '') AS absensi_pulang, FLOOR(GREATEST(0, IFNULL(TIME_TO_SEC(TIMEDIFF(TIME(c.jam_pulang), TIME(e.tanggal)))/60, ''))) AS pulang_cepat
            FROM tm_jadwalpegawai_shift_m a
                INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                LEFT JOIN tm_shift c ON a.id_absensi = c.id_absensi
                LEFT JOIN log d ON b.log_finger = d.`user`
                    AND DATE(d.tanggal) = a.date
                    AND TIME(d.tanggal) > SUBTIME(c.bi, '00:00:01')
                    AND TIME(d.tanggal) < ADDTIME(c.ai, '00:10:00')
                LEFT JOIN log e ON b.log_finger = e.`user`
                    AND DATE(e.tanggal) = a.date
                    AND TIME(e.tanggal) > SUBTIME(c.bo, '00:10:00')
                    AND TIME(e.tanggal) < ADDTIME(c.ao, '00:00:01')
                LEFT JOIN tm_shift_ketidakhadiran f ON a.id_ketidakhadiran = f.id_ketidakhadiran
            WHERE a.id_unit = '" . $id_unit . "'
                AND a.`month` = '" . $month . "'
                AND a.`year` = '" . $year . "'
                ". $where_condition_pegawai ."
				AND c.shift_tipe IS NULL
            ORDER BY a.id_unit, a.month, a.year, a.id_user, a.date, d.tanggal, e.tanggal DESC
        ");
        while ($row = fetch_assoc($sql)) {
            array_push($temp_sql, $row);
        }

        //akibat kegagalan dalam grouping data di query, maka data grouping dilakukan di aplikasi
        for ($i = 0; $i < count($temp_sql); $i++) {

            //akibat kegagalan dalam grouping data di query, maka data grouping dilakukan di aplikasi
            if (
                $i == 0 // apabila index 0
                || $temp_sql[$i]['date'] != $temp_sql[$i == 0 ? 0 : $i - 1]['date'] // apabila tanggalnya berbeda dengan sebelumnya
                || ($temp_sql[$i]['date'] == $temp_sql[$i == 0 ? 0 : $i - 1]['date'] && $temp_sql[$i]['id_absensi'] != $temp_sql[$i == 0 ? 0 : $i - 1]['id_absensi']) // apabila tanggalnya samaan tapi id_absensinya beda. berarti longshift
            ) {

                array_push($temp_grouping, $temp_sql[$i]);
            }
        }

        //untuk mendapatkan jumlah telat dan pulang cepat perbulan dan insert into tm_jadwalpegawai_absensi_detail
        for ($i = 0; $i < count($temp_grouping); $i++) {

            $temp_id_unit = $temp_grouping[$i]['id_unit'];
            $temp_id_user = $temp_grouping[$i]['id_user'];
            $temp_date = $temp_grouping[$i]['date'];
            $temp_id_absensi = $temp_grouping[$i]['id_absensi'];
            $temp_id_absensi_tipe = $temp_grouping[$i]['id_absensi_tipe'];
            $temp_id_ketidakhadiran = $temp_grouping[$i]['id_ketidakhadiran'];
            $temp_shift_aktif = $temp_grouping[$i]['shift_aktif'];
            $temp_jam_masuk_absensi_aktif = $temp_grouping[$i]['jam_masuk_absensi_aktif'];
            $temp_jam_pulang_absensi_aktif = $temp_grouping[$i]['jam_pulang_absensi_aktif'];
            $temp_absensi_masuk = $temp_grouping[$i]['absensi_masuk'];
            $temp_absensi_pulang = $temp_grouping[$i]['absensi_pulang'];
            $temp_keterlambatan = $temp_grouping[$i]['keterlambatan'];
            $temp_pulang_cepat = $temp_grouping[$i]['pulang_cepat'];

            // insert into tm_jadwalpegawai_absensi_detail
            bukainput2("INSERT INTO 
                    tm_jadwalpegawai_absensi_detail (id_jadwalpegawai_absensi_rekap_session, id_unit, month, year, date, id_user, id_absensi, id_absensi_tipe, id_ketidakhadiran, shift_aktif, jam_masuk_absensi_aktif, jam_pulang_absensi_aktif, absensi_masuk, absensi_pulang, keterlambatan, pulang_cepat, id_kepegawaian, created) 
                VALUES 
                    ('" . $session_id . "', '" . $temp_id_unit . "', " . $month . ", " . $year . ", '" . $temp_date . "', '" . $temp_id_user . "', '" . $temp_id_absensi . "', '" . $temp_id_absensi_tipe . "', '" . $temp_id_ketidakhadiran . "', " . $temp_shift_aktif . ", '" . $temp_jam_masuk_absensi_aktif . "', '" . $temp_jam_pulang_absensi_aktif . "', '" . $temp_absensi_masuk . "', '" . $temp_absensi_pulang . "', " . $temp_keterlambatan . ", " . $temp_pulang_cepat . ", '" . $id_kepegawaian . "', NOW())
            ");

            // update penambahan menit telat & pulang cepat di tm_jadwalpegawai_absensi_rekap
            bukaquery2("UPDATE 
                    tm_jadwalpegawai_absensi_rekap 
                SET k_jml_telat = k_jml_telat + '" . $temp_keterlambatan . "', k_jml_plng_cepat = k_jml_plng_cepat + '" . $temp_pulang_cepat . "' 
                WHERE id_unit = '" . $temp_id_unit . "' 
                    AND month = " . $month . " 
                    AND year = " . $year . " 
                    AND id_user = '" . $temp_id_user . "'
            ");
        }

        // ********************************
        // permintaan kepegawaian -mas rahmat. diakhir tahun, setiap sisa hari dianggap tidak ada telat pulang cepat
        // ********************************
        $sql = bukaquery2("SELECT a.enable_hitung_absensi_akhirtahun FROM setup a");
        if ((fetch_array($sql)['enable_hitung_absensi_akhirtahun']) == 1) {

            $sql = bukaquery2("
                SELECT
                    a.id_jadwalkerja_absensi, a.`date`
                FROM tm_jadwalpegawai_absensi_detail a
                WHERE a.id_unit = '" . $id_unit . "'
                    AND a.`month` = " . $month . "
                    AND a.`year` = " . $year . "
                    ". $where_condition_pegawai ."
                    AND (
                        a.`date` LIKE '" . $current_year . "-".$month."-2%' 
                        OR a.`date` LIKE '" . $current_year . "-".$month."-3%'
                    )
                    AND a.shift_aktif = 1
                    AND (
                        a.keterlambatan = '" . $default_minute_penalty . "'
                        OR a.pulang_cepat = '" . $default_minute_penalty . "'
                    )
            ");

            while ($row = fetch_array($sql)) {

                bukaquery2("
                    UPDATE
                        tm_jadwalpegawai_absensi_detail
                    SET keterlambatan = 0, pulang_cepat = 0,
                        id_kepegawaian = '" . $id_kepegawaian . "'
                    WHERE id_jadwalkerja_absensi = '" . $row['id_jadwalkerja_absensi'] . "'
                ");
            }
        }

        // ********************************
        // permintaan kepegawaian -mas rahmat. apabila di dalam shift aktif, pegawai tidak terdeteksi absensi masuk & pulang, diset menjadi alpha
        // ********************************
        // cari dari tm_jadwalpegawai_absensi_detail
        // dimana shift_aktif = 1 & keterlambatan = 225 & pulang_cepat = 225
        $sql = bukaquery2("
            SELECT
                a.id_jadwalkerja_absensi
            FROM tm_jadwalpegawai_absensi_detail a
            WHERE a.id_unit = '" . $id_unit . "'
                AND a.`month` = " . $month . "
                AND a.`year` = " . $year . "
                ". $where_condition_pegawai ."
                AND a.shift_aktif = 1
                AND a.keterlambatan = '" . $default_minute_penalty . "'
                AND a.pulang_cepat = '" . $default_minute_penalty . "'
        ");

        while ($row = fetch_array($sql)) {

            // update data di tm_jadwalpegawai_absensi_detail menjadi alpha
            // dan keterlambatan, pulang_cepat = 0
            bukaquery2("
                UPDATE
                    tm_jadwalpegawai_absensi_detail
                SET id_absensi = '', id_absensi_tipe = '', id_ketidakhadiran = 'AKT-000001', shift_aktif = 0, 
                    jam_masuk_absensi_aktif = '00:00:00', jam_pulang_absensi_aktif = '00:00:00',
                    absensi_masuk = '0000-00-00 00:00:00', absensi_pulang = '0000-00-00 00:00:00',
                    keterlambatan = 0, pulang_cepat = 0,
                    id_kepegawaian = '" . $id_kepegawaian . "'
                WHERE id_jadwalkerja_absensi = '" . $row['id_jadwalkerja_absensi'] . "'
            ");
        }


        // hitung ulang dari tm_jadwalpegawai_absensi_detail
        // untuk mendapatkan jumlah alpha, hks, hkm, hlp, hls, hlm, hrp, hrs, hrm, ns dan jumlah hari kerja
        // dan juga menghitung ulang keterlambatan dan pulang cepat

        // inisial query
        $query_pencarian_alpha_pegawai = "SELECT a.id_user AS user_id, a.id_unit AS unit_id, ";

        // query jml_alpha
        $query_pencarian_alpha_pegawai .= "
            IFNULL((
                SELECT
                    COUNT(a.date) AS jml
                FROM tm_jadwalpegawai_absensi_detail a
                WHERE a.id_unit = unit_id
                    AND a.month = " . $month . "
                    AND a.year = " . $year . "
                    AND a.id_user = user_id
                    AND a.id_ketidakhadiran = 'AKT-000001'), 0
            ) AS jml_akt000001, ";

        // subquery untuk mendapatkan jumlah alpha, hks, hkm, hlp, hls, hlm, hrp, hrs, hrm, ns dan jumlah hari kerja
        $sql = bukaquery2("
            SELECT
                a.id_absensi_tipe,
                REPLACE(LOWER(a.id_absensi_tipe), '-', '') AS id_absensi_tipe_lower
            FROM tm_shift_tipe a
        ");
        while ($row = fetch_array($sql)) {

            $query_pencarian_alpha_pegawai .= "
                (
                SELECT
                    COUNT(a.date) AS jml 
                FROM tm_jadwalpegawai_absensi_detail a
                WHERE a.id_unit = unit_id
                    AND a.month = " . $month . "
                    AND a.year = " . $year . "
                    AND a.id_user = user_id
                    AND a.id_absensi_tipe = '" . $row['id_absensi_tipe'] . "'
                ) AS jml_" . $row['id_absensi_tipe_lower'] . ", ";
        }

        // subquery untuk menghitung jumlah hari kerja
        $query_pencarian_alpha_pegawai .= "IFNULL((
                SELECT
                    COUNT(a.id_absensi)
                FROM tm_jadwalpegawai_absensi_detail a
                WHERE a.id_absensi IN (SELECT a.id_absensi FROM tm_shift a WHERE a.counting_work = 1) 
                    AND a.id_unit = unit_id 
                    AND a.month = " . $month . " 
                    AND a.year = " . $year . " 
                    AND a.id_user = user_id 
                GROUP BY a.id_user
        ), 0) AS jml_hari_kerja ";

        // lanjutan querynya
        $query_pencarian_alpha_pegawai .= "FROM
                tm_jadwalpegawai_absensi_detail a
            WHERE a.id_unit = '" . $id_unit . "'
                AND a.month = " . $month . "
                AND a.year = " . $year . "
                ". $where_condition_pegawai ."
            GROUP BY a.id_user
        ";
        $sql = bukaquery2($query_pencarian_alpha_pegawai);

        while ($row = fetch_array($sql)) {

            $id_user = $row['user_id'];
            $jml_alpha = $row['jml_akt000001'];
            $jml_hks = $row['jml_abt000001'];
            $jml_hkm = $row['jml_abt000002'];
            $jml_hlp = $row['jml_abt000003'];
            $jml_hls = $row['jml_abt000004'];
            $jml_hlm = $row['jml_abt000005'];
            $jml_hrp = $row['jml_abt000006'];
            $jml_hrs = $row['jml_abt000007'];
            $jml_hrm = $row['jml_abt000008'];
            $jml_hns = $row['jml_abt000009'];
            $jml_hari_kerja = $row['jml_hari_kerja'];

            // update ke tm_jadwalpegawai_absensi_rekap
            bukaquery2("
                UPDATE
                    tm_jadwalpegawai_absensi_rekap
                SET id_kepegawaian = '" . $id_kepegawaian . "', k_jml_alpha = '" . $jml_alpha . "',
                    s_jml_hks = '" . $jml_hks . "', s_jml_hkm = '" . $jml_hkm . "', s_jml_hlp = '" . $jml_hlp . "', s_jml_hls = '" . $jml_hls . "', s_jml_hlm = '" . $jml_hlm . "', s_jml_hrp = '" . $jml_hrp . "', s_jml_hrs = '" . $jml_hrs . "', s_jml_hrm = '" . $jml_hrm . "', s_jml_ns = '" . $jml_hns . "',
                    jml_hari_kerja = '" . $jml_hari_kerja . "'
                WHERE id_user = '" . $id_user . "'
                    AND id_unit = '" . $id_unit . "'
                    AND month = " . $month . "
                    AND year = " . $year . "
            ");
        }

        // hitung ulang keterlambatan dan pulang cepat
        // dan perbarui di tm_jadwalpegawai_absensi_rekap
        $sql = bukaquery2("
            SELECT
                a.id_user,
                SUM(a.keterlambatan) AS total_keterlambatan, 
                SUM(a.pulang_cepat) AS total_pulang_cepat
            FROM tm_jadwalpegawai_absensi_detail a
            WHERE a.id_unit = '" . $id_unit . "'
                AND a.`month` = '" . $month . "'
                AND a.`year` = '" . $year . "'
                ". $where_condition_pegawai ."
            GROUP BY a.id_user
        ");
        while ($row = fetch_array($sql)) {

            bukaquery2("
            UPDATE
                tm_jadwalpegawai_absensi_rekap
            SET k_jml_telat = " . $row['total_keterlambatan'] . ", k_jml_plng_cepat = " . $row['total_pulang_cepat'] . ",
                id_kepegawaian = '" . $id_kepegawaian . "'
            WHERE id_user = '" . $row['id_user'] . "'
                AND id_unit = '" . $id_unit . "'
                AND month = '" . $month . "'
                AND year = '" . $year . "'
            ");
        }

        return array(
            "status" => 1,
            "message" => "Generate Detail Absensi Pegawai Shift Selesai",
            "id_unit" => $id_unit
        );
    }

    function generate_rekapitulasi_nonshift_absensi_pegawai($id_kepegawaian, $month, $year)
    {

        $count_work_day = 0;
        $arr_work_day = $this->get_work_day_in_month($month, $year); // get works day in month
        $arr_holiday = $this->get_holiday_in_month($month, $year); // get holiday in month

        // remove work day by holiday date
        for ($i = 0; $i < count($arr_holiday); $i++) if (($key = array_search($arr_holiday[$i], $arr_work_day)) !== false) array_splice($arr_work_day, $key, 1);
        $count_work_day = count($arr_work_day);

        $last_date = TanggalAkhirBulanKemarin();

        $sql = bukaquery2("SELECT 
                a.id_jadwalpegawai_absensi_rekap_session
            FROM tm_jadwalpegawai_absensi_session a
            WHERE a.month = " . $month . " AND a.year = " . $year . "
            ORDER BY id_jadwalpegawai_absensi_rekap_session DESC
            LIMIT 1
        ");

        $session_id = fetch_array($sql)['id_jadwalpegawai_absensi_rekap_session'];

        // rekapitulasi berdasarkan tipe unit NON SHIFT (tm_unit.id_petugas = 'SHF-000004')
        $sql = bukaquery2("SELECT a.id_unit, b.id_user, b.log_finger
			FROM tm_unit a
				LEFT JOIN tm_pegawai b ON a.id_unit = b.id_unit
				INNER JOIN tm_user c ON b.id_user = c.id_user
				INNER JOIN tm_level d ON c.id_level = d.id_level
			WHERE a.id_petugas = 'SHF-000004' 
				AND a.id_unit <> 'UNT-000020'
				AND a.id_unit <> ''
				AND b.status_pegawai = 'NON PNS'
				AND b.status = 'AKTIF'
				AND b.tgl_masuk <= '" . $last_date . "'
			ORDER BY a.nama_unit, b.nama_pegawai
		");

        // insert into tm_jadwalpegawai_absensi_rekap
        while ($row = fetch_array($sql)) {
            // insert into tm_jadwalpegawai_absensi_rekap
            bukaquery2("INSERT INTO tm_jadwalpegawai_absensi_rekap (id_jadwalpegawai_absensi_rekap_session, id_unit, month, year, id_user, id_kepegawaian, 
                    k_jml_telat, k_jml_alpha, k_jml_sakit_1hari, k_jml_sakit_2hari, k_jml_izin, k_jml_cuti_prslnan, k_jml_izin_sethari, k_jml_meninggal, t_jml_cuti_sakit, t_jml_cuti_alsnpenting, t_jml_cuti_thnan, t_jml_diklat,
                    t_jml_spd, t_jml_haji, s_jml_hks, s_jml_hkm, s_jml_hlp, s_jml_hls, s_jml_hlm, s_jml_hrp, s_jml_hrs, s_jml_hrm, s_jml_ns, jml_hari_kerja, jml_menit_kerja, created)
                VALUES ('" . $session_id . "', '" . $row['id_unit'] . "', '" . $month . "', '" . $year . "', '" . $row['id_user'] . "', '" . $id_kepegawaian . "', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, " . $count_work_day . ", 0, NOW())
            ");
        }

        return array(
            "status" => 1,
            "message" => "Generate Rekap Absensi Pegawai Non Shift Selesai",
        );
    }

    function generate_detail_nonshift_absensi_pegawai($id_kepegawaian, $month, $year)
    {

        $arr_off_day = array(); // get total off day by holiday and weekend
        $arr_work_day = $this->get_work_day_in_month($month, $year); // get works day in month
        $arr_holiday = $this->get_holiday_in_month($month, $year); // get holiday in month
        $arr_weekend = $this->get_weekend_in_month($month, $year); // get weekend in month
        $default_minute_penalty = 225; // default pinalti akibat tidak absensi masuk/pulang (menit)

        $arr_off_day_temp = array_merge_recursive($arr_weekend, $arr_holiday);
        $last_date = TanggalAkhirBulanKemarin();
        asort($arr_off_day_temp);

        foreach ($arr_off_day_temp as $key => $value) {

            if (!in_array($value, $arr_off_day)) {
                array_push($arr_off_day, $value);
            }
        }

        // remove work day by holiday date
        for ($i = 0; $i < count($arr_holiday); $i++) if (($key = array_search($arr_holiday[$i], $arr_work_day)) !== false) array_splice($arr_work_day, $key, 1);

        $sql = bukaquery2("SELECT 
                a.id_jadwalpegawai_absensi_rekap_session
            FROM tm_jadwalpegawai_absensi_session a
            WHERE a.month = " . $month . " AND a.year = " . $year . "
            ORDER BY id_jadwalpegawai_absensi_rekap_session DESC
            LIMIT 1
        ");

        $session_id = fetch_array($sql)['id_jadwalpegawai_absensi_rekap_session'];

        // rekapitulasi berdasarkan tipe unit NON SHIFT (tm_unit.id_petugas = 'SHF-000004')
        $sql = bukaquery2("SELECT a.id_unit, b.id_user, b.log_finger
            FROM tm_unit a
                LEFT JOIN tm_pegawai b ON a.id_unit = b.id_unit
                INNER JOIN tm_user c ON b.id_user = c.id_user
                INNER JOIN tm_level d ON c.id_level = d.id_level
            WHERE a.id_petugas = 'SHF-000004' 
                AND a.id_unit <> 'UNT-000020'
                AND a.id_unit <> ''
                AND b.status_pegawai = 'NON PNS'
                AND b.status = 'AKTIF'
                AND b.tgl_masuk <= '" . $last_date . "'
            ORDER BY a.nama_unit, b.nama_pegawai
        ");

        // looping berdasarkan data pegawai
        while ($row = fetch_array($sql)) {

            $temp_count_workday = 0;
            $temp_keterlambatan_total = 0;
            $temp_pulangcepat_total = 0;
            $temp_k_alpha = 0;
            $temp_k_sakit1 = 0;
            $temp_k_sakit2 = 0;
            $temp_k_izin = 0;
            $temp_k_izin_set_hari = 0;
            $temp_k_cuti_sakit = 0;
            $temp_k_cuti_alasan = 0;
            $temp_k_cuti_persalinan = 0;
            $temp_k_meninggal = 0;
            $temp_t_cuti_sakit = 0;
            $temp_t_cuti_alasan = 0;
            $temp_t_cuti_tahunan = 0;
            $temp_t_cuti_diklat = 0;
            $temp_t_cuti_spd = 0;
            $temp_t_cuti_haji = 0;
            $arr_cuti_user = array();

            // get list cuti from tm_cuti where cuti accepted
            // untuk digunakan dipengecekan HARI KERJA
            // dari fitur E-Cuti
            $sql_cuti = bukaquery2("
                SELECT
                    b.tanggal, a.id_ketidakhadiran
                FROM tm_cuti a
                    LEFT JOIN tm_hari_cuti b ON a.id_cuti = b.id_cuti
                WHERE a.id_user = '" . $row['id_user'] . "'
                    AND a.acc_pengganti = 'Y'
                    AND a.acc_pj = 'Y'
                    AND a.acc_kasatpel = 'Y'
                    AND a.acc_kasie = 'Y'
                GROUP BY b.tanggal
                ORDER BY b.tanggal
            ");
            while ($row_cuti = fetch_assoc($sql_cuti)) {
                $arr_cuti_user[$row_cuti['tanggal']] = $row_cuti['id_ketidakhadiran'];
            }

            // check berdasarkan HARI KERJA
            for ($i = 0; $i < count($arr_work_day); $i++) {

                // check apakah user cuti pada hari2 kerja
                // kalau ya dihitung akan masuk waktu penambahan mana
                // kalau tidak buat data absensinya
                if (array_key_exists($arr_work_day[$i], $arr_cuti_user)) {

                    $temp_idketidakhadiran = "";

                    // dicari berdasarkan tm_shift_ketidakhadiran
                    // dicari untuk mendapatkan data tm_waktu_t
                    switch ($arr_cuti_user[$arr_work_day[$i]]) {
                        case 'AKT-000010':
                            $temp_idketidakhadiran = "AKT-000010";
                            $temp_t_cuti_sakit++;
                            break;
                        case 'CUTI MELAHIRKAN':
                            break;
                        case 'AKT-000011':
                            $temp_idketidakhadiran = "AKT-000011";
                            $temp_t_cuti_alasan++;
                            break;
                        case 'AKT-000012':
                            $temp_idketidakhadiran = "AKT-000012";
                            $temp_t_cuti_tahunan++;
                            break;
                        case 'AKT-000013':
                            $temp_idketidakhadiran = "AKT-000013";
                            $temp_t_cuti_diklat++;
                            break;
                        case 'AKT-000014':
                            $temp_idketidakhadiran = "AKT-000014";
                            $temp_t_cuti_spd++;
                            break;
                        case 'AKT-000015':
                            $temp_idketidakhadiran = "AKT-000015";
                            $temp_t_cuti_haji++;
                            break;
                        case 'AKT-000016':
                            $temp_idketidakhadiran = "AKT-000016";
                            $temp_t_cuti_diklat++;
                            break;
                        case 'AKT-000017':
                            $temp_idketidakhadiran = "AKT-000017";
                            $temp_t_cuti_tahunan++;
                            break;
                    }

                    // insert into tm_jadwalpegawai_absensi_detail
                    bukaquery2("
                        INSERT INTO tm_jadwalpegawai_absensi_detail 
                            (id_jadwalpegawai_absensi_rekap_session, id_unit, month, year, id_user, date, id_ketidakhadiran, shift_aktif, id_kepegawaian, created)
                        VALUES
                            ('" . $session_id . "', '" . $row['id_unit'] . "', " . $month . ", " . $year . ", '" . $row['id_user'] . "', '" . $arr_work_day[$i] . "', '" . $temp_idketidakhadiran . "', 0, '" . $id_kepegawaian . "', NOW())
                    ");
                } else { // user tidak cuti

                    // increase count work day
                    $temp_count_workday++;

                    $sql_detail = bukaquery2("
                        SELECT
                            a.jam_masuk, a.jam_pulang, a.absensi_masuk, a.absensi_pulang,
                            FLOOR(GREATEST(0, IFNULL(TIME_TO_SEC(TIMEDIFF(TIME(a.absensi_masuk), TIME(a.jam_masuk)))/60, " . $default_minute_penalty . "))) AS keterlambatan,
                            FLOOR(GREATEST(0, IFNULL(TIME_TO_SEC(TIMEDIFF(TIME(a.jam_pulang), TIME(a.absensi_pulang)))/60, " . $default_minute_penalty . "))) AS pulang_cepat
                        FROM (
                                SELECT
                                        a.jam_masuk, a.bi, a.ai,
                                        a.jam_pulang, a.bo, a.ao,
                                ( SELECT a.tanggal AS absensi_masuk
                                        FROM log a
                                        WHERE a.user = '" . $row['log_finger'] . "'
                                        AND DATE(a.tanggal) = '" . $arr_work_day[$i] . "'
                                        AND TIME(a.tanggal) > SUBTIME(bi, '00:00:01')
                                        AND TIME(a.tanggal) < ADDTIME(ai, '00:30:00')
                                        ORDER BY a.tanggal ASC
                                        LIMIT 1
                                ) AS absensi_masuk,
                                ( SELECT a.tanggal AS absensi_pulang
                                        FROM log a
                                        WHERE a.user = '" . $row['log_finger'] . "'
                                        AND DATE(a.tanggal) = '" . $arr_work_day[$i] . "'
                                        AND TIME(a.tanggal) > SUBTIME(bo, '00:30:00')
                                        AND TIME(a.tanggal) < ADDTIME(ao, '00:00:01')
                                        ORDER BY a.tanggal DESC
                                        LIMIT 1
                                ) AS absensi_pulang
                                FROM tm_shift a
                                WHERE a.id_absensi = 'ABS-000001'
                            ) AS a
                    ");

                    $row_detail = fetch_array($sql_detail);

                    $temp_keterlambatan_total += (int) $row_detail['keterlambatan'];
                    $temp_pulangcepat_total += (int) $row_detail['pulang_cepat'];
                    $temp_absensi_masuk = isset($row_detail['absensi_masuk']) ? $row_detail['absensi_masuk'] : '0000-00-00 00:00:00';
                    $temp_absensi_pulang = isset($row_detail['absensi_pulang']) ? $row_detail['absensi_pulang'] : '0000-00-00 00:00:00';

                    // insert into tm_jadwalpegawai_absensi_detail
                    bukaquery2("INSERT INTO tm_jadwalpegawai_absensi_detail 
                        (id_jadwalpegawai_absensi_rekap_session, id_unit, month, year, id_user, date, id_absensi, id_absensi_tipe, shift_aktif,
                        jam_masuk_absensi_aktif, jam_pulang_absensi_aktif, absensi_masuk, absensi_pulang, keterlambatan, pulang_cepat, id_kepegawaian, created)
                        VALUES 
                        ('" . $session_id . "', '" . $row['id_unit'] . "', " . $month . ", " . $year . ", '" . $row['id_user'] . "', '" . $arr_work_day[$i] . "', 'ABS-000001', 'ABT-000009', 1, '" . $row_detail['jam_masuk'] . "', '" . $row_detail['jam_pulang'] . "', '" . $temp_absensi_masuk . "', '" . $temp_absensi_pulang . "', '" . $row_detail['keterlambatan'] . "', '" . $row_detail['pulang_cepat'] . "', '" . $id_kepegawaian . "', NOW())
                    ");
                }
            }

            // check berdasarkan HARI LIBUR
            foreach ($arr_off_day as $key => $value) {

                bukaquery2("INSERT INTO tm_jadwalpegawai_absensi_detail 
					(id_jadwalpegawai_absensi_rekap_session, id_unit, month, year, id_user, date, id_kepegawaian, created)
					VALUES 
					(" . $session_id . ", '" . $row['id_unit'] . "', " . $month . ", " . $year . ", '" . $row['id_user'] . "', '" . $value . "', '" . $id_kepegawaian . "', NOW())
				");
            }

            // update into tm_jadwalpegawai_absensi_rekap
            bukaquery2("UPDATE tm_jadwalpegawai_absensi_rekap
                SET k_jml_alpha = " . $temp_k_alpha . ", 
                    k_jml_sakit_1hari = " . $temp_k_sakit1 . ", 
                    k_jml_sakit_2hari = " . $temp_k_sakit2 . ", 
                    k_jml_izin = " . $temp_k_izin . ", 
                    k_jml_cuti_sakit = " . $temp_k_cuti_sakit . ", 
                    k_jml_cuti_alsnpenting = " . $temp_k_cuti_alasan . ", 
                    k_jml_cuti_prslnan = " . $temp_k_cuti_persalinan . ", 
                    k_jml_izin_sethari = " . $temp_k_izin_set_hari . ", 
                    k_jml_meninggal = " . $temp_k_meninggal . ", 
                    k_jml_telat = " . $temp_keterlambatan_total . ", 
                    k_jml_plng_cepat = " . $temp_pulangcepat_total . ", 
                    t_jml_cuti_sakit = " . $temp_t_cuti_sakit . ", 
                    t_jml_cuti_alsnpenting = " . $temp_t_cuti_alasan . ", 
                    t_jml_cuti_thnan = " . $temp_t_cuti_tahunan . ", 
                    t_jml_diklat = " . $temp_t_cuti_diklat . ", 
                    t_jml_spd = " . $temp_t_cuti_spd . ", 
                    t_jml_haji = " . $temp_t_cuti_haji . ", 
                    jml_hari_kerja = " . $temp_count_workday . " 
                WHERE id_unit = '" . $row['id_unit'] . "' AND month = " . $month . " AND year = " . $year . " AND id_user = '" . $row['id_user'] . "'
            ");
        }

        return array(
            "status" => 1,
            "message" => "Generate Detail Absensi Pegawai Non Shift Selesai"
        );
    }

    function generate_rekapitulasi_detail_dokter_spesialis_absensi_pegawai($id_kepegawaian, $month, $year)
    {

        global $url_jadwal_dr_spesialis_bynik;

        $arr_hari_raya = array();

        $default_opsi_shift = "ABS-000032";
        $default_opsi_tipeshift = "ABT-000009";
        $default_minute_penalty = 225;
        $id_unit = 'UNT-000003'; // unit dokter spesialis
        $bumper_time = '02:00:00';

        $sql = bukaquery2("
            SELECT 
                a.id_jadwalpegawai_absensi_rekap_session
            FROM tm_jadwalpegawai_absensi_session a
            WHERE a.month = " . $month . "
                AND a.year = " . $year . "
            ORDER BY a.id_jadwalpegawai_absensi_rekap_session DESC
            LIMIT 1
        ");
        $session_id = fetch_array($sql)['id_jadwalpegawai_absensi_rekap_session'];

        $sql = bukaquery2("
            ( SELECT a.tanggal FROM tm_hari_raya a WHERE MONTH(a.tanggal) = '" . $month . "' AND YEAR(a.tanggal) = '" . $year . "' ORDER BY a.tanggal )
        ");
        // $sql = bukaquery2("
        //     ( SELECT a.tanggal FROM tm_hari_raya a WHERE MONTH(a.tanggal) = '" . $month . "' AND YEAR(a.tanggal) = '" . $year . "' ORDER BY a.tanggal )
        //     UNION
        //     ( SELECT a.tanggal FROM tm_hari_libur a WHERE MONTH(a.tanggal) = '" . $month . "' AND YEAR(a.tanggal) = '" . $year . "' )
        // ");
        while ($row = fetch_assoc($sql)) {
            array_push($arr_hari_raya, $row['tanggal']);
        }

        $sql = bukaquery2("
            SELECT
                a.id_user, a.nik, a.nama_pegawai, a.log_finger
            FROM tm_pegawai a
            WHERE a.id_unit = '" . $id_unit . "'
                AND a.`status` = 'AKTIF'
            GROUP BY a.nik
            ORDER BY a.nama_pegawai
        ");

        while ($row = fetch_array($sql)) {

            // jika NIK tidak valid, skip saja
            if (strlen($row['nik']) <= 5) {
                continue;
            }
            // insert into tm_jadwalpegawai_absensi_rekap
            // sebagai inisiasi dan untuk menyimpan telat, pulang cepat, dll
            bukaquery2("
                INSERT INTO tm_jadwalpegawai_absensi_rekap (id_jadwalpegawai_absensi_rekap_session, id_unit, month, year, id_user, id_kepegawaian, created)
                VALUES ('" . $session_id . "', '" . $id_unit . "', '" . $month . "', '" . $year . "', '" . $row['id_user'] . "', '" . $id_kepegawaian . "', NOW())
            ");

            // hit ke api sentral
            // untuk mencari data dari database sik
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url_jadwal_dr_spesialis_bynik . "/" . $row['nik']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = json_decode(curl_exec($ch), true);
            curl_close($ch);

            // apabila jadwal ditemukan dari server SIK
            // maka dilakukan perhitungan
            if ($result['status'] == 1) {

                $arr_dayname_jadwal_praktek = array();
                $jml_hari_kerja = 0;
                $k_jml_alpha = 0;
                $k_jml_telat = 0;
                $k_jml_plng_cepat = 0;
                $s_jml_ns = 0;

                // insert ke arr_dayname_jadwal_praktek
                // untuk digunakan in_array
                for ($i = 0; $i < count($result['data']); $i++) {
                    array_push($arr_dayname_jadwal_praktek, $result['data'][$i]['hari_kerja']);
                }

                $days_in_month = $this->get_days_in_month($month, $year);

                for ($i = 1; $i <= $days_in_month; $i++) {

                    $date = $year . "-" . sprintf('%02d', $month) . "-" . sprintf("%02d", $i);

                    // apabila tanggal ini ditemukan dalam arr hari raya
                    // maka dianggap hari libur, kalau tidak, dilakukan perhitungan
                    if (!in_array($date, $arr_hari_raya)) {

                        // ambil nama hari nya
                        $day_name_id = strtoupper(hariindo($date));

                        // untuk mencari bahwa day_name ada di dalam array
                        // kalau ditemukan, lanjut
                        if (in_array($day_name_id, $arr_dayname_jadwal_praktek)) {

                            // dilooping berdasarkan jumlah jadwal prakteknya
                            // untuk mencari prakteknya
                            for ($j = 0; $j < count($result['data']); $j++) {

                                // apabila ditemukan jadwal prakteknya. berdasarkan nama hari nya
                                // maka tambahkan jumlah hari kerja, jumlah shift, jumlah telat dan jumlah pulang cepatnya
                                // dan dianggal alpha apabila di hari praktek, tidak ditemukan absensi pulang atau masuknya
                                if ($day_name_id == $result['data'][$j]['hari_kerja']) {

                                    $sql_absensi_masuk = bukaquery2("
                                        SELECT
                                            a.tanggal AS absensi_masuk, 
                                            FLOOR(GREATEST(IFNULL(TIME_TO_SEC(TIMEDIFF(TIME(a.tanggal), TIME('" . $result['data'][$j]['jam_mulai'] . "')))/60, '" . $default_minute_penalty . "'), 0)) AS keterlambatan
                                        FROM log a
                                        WHERE a.user = '" . $row['log_finger'] . "'
                                            AND DATE(a.tanggal) = '" . $date . "'
                                            AND TIME(a.tanggal) > SUBTIME('" . $result['data'][$j]['jam_mulai'] . "', '" . $bumper_time . "')
                                            AND TIME(a.tanggal) < ADDTIME('" . $result['data'][$j]['jam_mulai'] . "', '" . $bumper_time . "')
                                        ORDER BY a.tanggal ASC
                                        LIMIT 1
                                    ");
                                    $res_absensi_masuk = fetch_array($sql_absensi_masuk);

                                    $sql_absensi_pulang = bukaquery2("
                                        SELECT
                                            a.tanggal AS absensi_pulang, 
                                            FLOOR(GREATEST(IFNULL(TIME_TO_SEC(TIMEDIFF(TIME('" . $result['data'][$j]['jam_selesai'] . "'), TIME(a.tanggal)))/60, '" . $default_minute_penalty . "'), 0)) AS pulang_cepat
                                        FROM log a
                                        WHERE a.user = '" . $row['log_finger'] . "'
                                            AND DATE(a.tanggal) = '" . $date . "'
                                            AND TIME(a.tanggal) < ADDTIME('" . $result['data'][$j]['jam_selesai'] . "', '" . $bumper_time . "')
                                            AND TIME(a.tanggal) > SUBTIME('" . $result['data'][$j]['jam_selesai'] . "', '" . $bumper_time . "')
                                        ORDER BY a.tanggal DESC
                                        LIMIT 1
                                    ");

                                    $res_absensi_pulang = fetch_array($sql_absensi_pulang);

                                    $temp_absensi_masuk = isset($res_absensi_masuk['absensi_masuk']) ? $res_absensi_masuk['absensi_masuk'] : "0000-00-00 00:00:00";

                                    $temp_k_jml_telat = $temp_absensi_masuk != "0000-00-00 00:00:00"
                                        ? $res_absensi_masuk['keterlambatan']
                                        : $default_minute_penalty;

                                    $temp_absensi_pulang = isset($res_absensi_pulang['absensi_pulang']) ? $res_absensi_pulang['absensi_pulang'] : "0000-00-00 00:00:00";

                                    $temp_k_jml_plng_cepat = $temp_absensi_pulang != "0000-00-00 00:00:00"
                                        ? $res_absensi_pulang['pulang_cepat']
                                        : $default_minute_penalty;

                                    if (
                                        $temp_absensi_masuk == "0000-00-00 00:00:00"
                                        && $temp_absensi_pulang == "0000-00-00 00:00:00"
                                    ) {
                                        // apabila ditemukan tidak absen masuk dan pulang
                                        // maka dianggap alpha
                                        $k_jml_alpha++;

                                        // insert into tm_jadwalpegawai_absensi_detail sebagai ALPHA
                                        bukaquery2("
                                            INSERT INTO tm_jadwalpegawai_absensi_detail (id_jadwalpegawai_absensi_rekap_session, id_unit, `month`, `year`, id_user, date, id_absensi, id_absensi_tipe, id_ketidakhadiran, shift_aktif, jam_masuk_absensi_aktif, jam_pulang_absensi_aktif, absensi_masuk, absensi_pulang, keterlambatan, pulang_cepat, id_kepegawaian, created)
                                            VALUES ('" . $session_id . "', '" . $id_unit . "', '" . $month . "', '" . $year . "', '" . $row['id_user'] . "', '" . $date . "', '', '', 'AKT-000001', 0, '00:00:00', '00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '0', '" . $id_kepegawaian . "', NOW())
                                        ");
                                    } else {

                                        // tambahkan jumlah hari kerja dan jumlah shift non-shiftnya
                                        $jml_hari_kerja++;
                                        $s_jml_ns++;

                                        // tambahkan telat dan pulang cepatnya
                                        $k_jml_telat = $k_jml_telat + $temp_k_jml_telat;
                                        $k_jml_plng_cepat = $k_jml_plng_cepat + $temp_k_jml_plng_cepat;

                                        // insert into tm_jadwalpegawai_absensi_detail sebagai Non Shift hari kerja
                                        bukaquery2("
                                            INSERT INTO tm_jadwalpegawai_absensi_detail (id_jadwalpegawai_absensi_rekap_session, id_unit, `month`, `year`, id_user, date, id_absensi, id_absensi_tipe, id_ketidakhadiran, shift_aktif, jam_masuk_absensi_aktif, jam_pulang_absensi_aktif, absensi_masuk, absensi_pulang, keterlambatan, pulang_cepat, id_kepegawaian, created)
                                            VALUES ('" . $session_id . "', '" . $id_unit . "', '" . $month . "', '" . $year . "', '" . $row['id_user'] . "', '" . $date . "', '" . $default_opsi_shift . "', '" . $default_opsi_tipeshift . "', '', 2, '" . $result['data'][$j]['jam_mulai'] . "', '" . $result['data'][$j]['jam_selesai'] . "', '" . $temp_absensi_masuk . "', '" . $temp_absensi_pulang . "', '" . $temp_k_jml_telat . "', '" . $temp_k_jml_plng_cepat . "', '" . $id_kepegawaian . "', NOW())
                                        ");
                                    }
                                }
                            }
                        } else { // kalau tidak ditemukan, cukup diinsert "kosong" di tm_jadwalpegawai_absensi_detail

                            bukaquery2("
                                INSERT INTO 
                                    tm_jadwalpegawai_absensi_detail (id_jadwalpegawai_absensi_rekap_session, id_unit, month, year, id_user, date, id_absensi, id_absensi_tipe, id_ketidakhadiran, shift_aktif, id_kepegawaian, created)
                                VALUES ('" . $session_id . "', '" . $id_unit . "', " . $month . ", " . $year . ", '" . $row['id_user'] . "', '" . $date . "', '', '', '', 0, '" . $id_kepegawaian . "', NOW())
                            ");
                        }
                    } else {

                        // dianggap hari raya libur
                        // diinsert "kosong" di tm_jadwalpegawai_absensi_detail 
                        bukaquery2("
                            INSERT INTO 
                                tm_jadwalpegawai_absensi_detail (id_jadwalpegawai_absensi_rekap_session, id_unit, month, year, id_user, date, id_absensi, id_absensi_tipe, id_ketidakhadiran, shift_aktif, id_kepegawaian, created)
                            VALUES ('" . $session_id . "', '" . $id_unit . "', " . $month . ", " . $year . ", '" . $row['id_user'] . "', '" . $date . "', '', '', '', 0, '" . $id_kepegawaian . "', NOW())
                        ");
                    }
                }

                // update into tm_jadwalpegawai_absensi_rekap
                bukaquery2("
                    UPDATE tm_jadwalpegawai_absensi_rekap
                    SET k_jml_alpha = '" . $k_jml_alpha . "', 
                        s_jml_ns = '" . $s_jml_ns . "',
                        jml_hari_kerja = '" . $jml_hari_kerja . "',
                        k_jml_telat = '" . $k_jml_telat . "',
                        k_jml_plng_cepat = '" . $k_jml_plng_cepat . "',
                        id_kepegawaian = '" . $id_kepegawaian . "'
                    WHERE id_unit = '" . $id_unit . "'
                        AND month = '" . $month . "'
                        AND year = '" . $year . "'
                        AND id_user = '" . $row['id_user'] . "'
                ");
            } else {

                $days_in_month = $this->get_days_in_month($month, $year);
                for ($i = 1; $i <= $days_in_month; $i++) {

                    $date = $year . "-" . sprintf('%02d', $month) . "-" . sprintf("%02d", $i);

                    bukaquery2("
                        INSERT INTO 
                            tm_jadwalpegawai_absensi_detail (id_jadwalpegawai_absensi_rekap_session, id_unit, month, year, id_user, date, id_absensi, id_absensi_tipe, id_ketidakhadiran, shift_aktif, id_kepegawaian, created)
                        VALUES ('" . $session_id . "', '" . $id_unit . "', " . $month . ", " . $year . ", '" . $row['id_user'] . "', '" . $date . "', '', '', '', 0, '" . $id_kepegawaian . "', NOW())
                    ");
                }
            }
        }

        return array(
            "status" => 1,
            "message" => "Generate Absensi Dokter Spesialis telah selesai"
        );
    }

    function generate_rekapitulasi_shift_absensi_pegawai_perunit($id_kepegawaian, $month, $year, $id_unit, $id_user)
    {
        $query_rekap_shift_pegawai = "";

        // ambil session_id sebelumnya
        // get session id
        $sql = bukaquery2("SELECT 
                a.id_jadwalpegawai_absensi_rekap_session
            FROM tm_jadwalpegawai_absensi_session a
            WHERE a.month = " . $month . " AND a.year = " . $year . "
            ORDER BY id_jadwalpegawai_absensi_rekap_session DESC
            LIMIT 1
        ");
        $id_session = fetch_array($sql)['id_jadwalpegawai_absensi_rekap_session'];

        // hapus dahulu dari rekap berdasarkan id_user yg dikirim. apabila all, berarti seluruh pegawai di unit
        if ($id_user == 'all') {

            bukaquery2("DELETE FROM tm_jadwalpegawai_absensi_rekap WHERE `month` = '" . $month . "' AND `year` = '" . $year . "' AND id_unit = '" . $id_unit . "'");
        } else {

            bukaquery2("DELETE FROM tm_jadwalpegawai_absensi_rekap WHERE `month` = '" . $month . "' AND `year` = '" . $year . "' AND id_unit = '" . $id_unit . "' AND id_user = '" . $id_user . "'");
        }

        // buat query insert select u tm_jadwalpegawai_absensi_rekap
        $query_rekap_shift_pegawai = "SELECT a.id_unit AS unit_id, a.id_user AS user_id, 0 AS jml_telat, ";

        // ambil data ketidakhadiran shift
        $arr_shift_ketidakhadiran = array();
        $sql = bukaquery2("SELECT a.id_ketidakhadiran, REPLACE(LOWER(a.id_ketidakhadiran), '-', '') AS id_ketidakhadiran_lower FROM tm_shift_ketidakhadiran a");

        // convert jadi array
        while ($row = fetch_array($sql)) {
            array_push($arr_shift_ketidakhadiran, array(
                $row['id_ketidakhadiran'],
                $row['id_ketidakhadiran_lower']
            ));
        }

        // buat subquery u mendapatkan jumlah ketidakhadiran berdasarkan tm_shift_ketidakhadiran
        for ($i = 0; $i < count($arr_shift_ketidakhadiran); $i++) {

            $query_rekap_shift_pegawai .= "(SELECT COUNT(a.date) AS jml FROM tm_jadwalpegawai_shift_m a WHERE a.id_unit = unit_id AND a.month = " . $month . " AND a.year = " . $year . " AND a.id_user = user_id AND a.id_ketidakhadiran = '" . $arr_shift_ketidakhadiran[$i][0] . "') AS jml_" . $arr_shift_ketidakhadiran[$i][1] . ", ";
        }

        // ambil data tm_shift_tipe
        $arr_shift_shift_tipe = array();
        $sql = bukaquery2("SELECT a.id_absensi_tipe, REPLACE(LOWER(a.id_absensi_tipe), '-', '') AS id_absensi_tipe_lower FROM tm_shift_tipe a");

        // convert jadi array
        while ($row = fetch_array($sql)) {

            array_push($arr_shift_shift_tipe, array(
                $row['id_absensi_tipe'],
                $row['id_absensi_tipe_lower']
            ));
        }

        // buat subquery u mendapatkan jumlah shift pagi, sore, malam berdasarkan tm_shift_tipe
        for ($i = 0; $i < count($arr_shift_shift_tipe); $i++) {

            $query_rekap_shift_pegawai .= "(SELECT COUNT(a.date) AS jml FROM tm_jadwalpegawai_shift_m a WHERE a.id_unit = unit_id AND a.month = " . $month . " AND a.year = " . $year . " AND a.id_user = user_id AND a.id_absensi_tipe = '" . $arr_shift_shift_tipe[$i][0] . "') AS jml_" . $arr_shift_shift_tipe[$i][1] . ", ";
        }


        // subquery untuk menghitung jumlah hari kerja
        $query_rekap_shift_pegawai .= "(SELECT COUNT(a.id_absensi) FROM tm_jadwalpegawai_shift_m a WHERE a.id_absensi IN (SELECT a.id_absensi FROM tm_shift a WHERE a.counting_work = 1) AND a.id_unit = unit_id AND a.month = " . $month . " AND a.year = " . $year . " AND a.id_user = user_id GROUP BY a.id_user) AS jml_hari_kerja, ";

        // subquery untuk menghitung jumlah menit kerja
        $query_rekap_shift_pegawai .= "(SELECT SUM(b.working_time_minute) FROM tm_jadwalpegawai_shift_m a LEFT JOIN tm_shift b ON a.id_absensi = b.id_absensi WHERE a.id_unit = unit_id AND a.month = " . $month . " AND a.year = " . $year . " AND a.id_user = user_id AND (a.id_absensi <> '' AND a.id_absensi IS NOT NULL)) AS jml_menit_kerja, ";
        // lanjutan querynya berdasarkan id_user. apabila all, berarti cari utk seluruh pegawai dalam unit
        if ($id_user == 'all') {

            $query_rekap_shift_pegawai .= "NOW() AS created FROM tm_jadwalpegawai_shift_m a LEFT JOIN tm_jadwalpegawai_shift_validation b ON a.id_unit = b.id_unit AND a.month = b.month AND a.year = b.year WHERE a.month = '" . $month . "' AND a.year = '" . $year . "' AND a.id_unit = '" . $id_unit . "' GROUP BY a.id_user ORDER BY a.id_unit, a.id_user";
        } else {

            $query_rekap_shift_pegawai .= "NOW() AS created FROM tm_jadwalpegawai_shift_m a LEFT JOIN tm_jadwalpegawai_shift_validation b ON a.id_unit = b.id_unit AND a.month = b.month AND a.year = b.year WHERE a.month = '" . $month . "' AND a.year = '" . $year . "' AND a.id_unit = '" . $id_unit . "' AND a.id_user = '" . $id_user . "' GROUP BY a.id_user ORDER BY a.id_unit, a.id_user";
        }

        // eksekusi query
        $sql = bukaquery2($query_rekap_shift_pegawai);
        while ($row = fetch_array($sql)) {

            $id_jadwalpegawai_absensi_rekap_session = $id_session;
            $id_unit_res = $row['unit_id'];
            $month_res = $month;
            $year_res = $year;
            $id_user_res = $row['user_id'];
            $id_kepegawaian_res = $id_kepegawaian;
            $k_jml_telat = $row['jml_telat'];
            $k_jml_alpha = $row['jml_akt000001'];
            $k_jml_sakit_1hari = $row['jml_akt000002'];
            $k_jml_sakit_2hari = $row['jml_akt000003'];
            $k_jml_izin = $row['jml_akt000004'];
            $k_jml_cuti_prslnan = $row['jml_akt000005'];
            $k_jml_izin_sethari = $row['jml_akt000008'];
            $k_jml_meninggal = $row['jml_akt000009'];
            $t_jml_cuti_sakit = $row['jml_akt000010'];
            $t_jml_cuti_alsnpenting = $row['jml_akt000011'];
            $t_jml_cuti_thnan = $row['jml_akt000012'];
            $t_jml_diklat = ((int)$row['jml_akt000013']) + ((int) $row['jml_akt000016']);
            $t_jml_spd = $row['jml_akt000014'];
            $t_jml_haji = $row['jml_akt000015'];
            $s_jml_hks = $row['jml_abt000001'];
            $s_jml_hkm = $row['jml_abt000002'];
            $s_jml_hlp = $row['jml_abt000003'];
            $s_jml_hls = $row['jml_abt000004'];
            $s_jml_hlm = $row['jml_abt000005'];
            $s_jml_hrp = $row['jml_abt000006'];
            $s_jml_hrs = $row['jml_abt000007'];
            $s_jml_hrm = $row['jml_abt000008'];
            $s_jml_ns = $row['jml_abt000009'];
            $jml_hari_kerja = $row['jml_hari_kerja'];
            $jml_menit_kerja = $row['jml_menit_kerja'];
            $created = $row['created'];

            bukaquery2("INSERT INTO tm_jadwalpegawai_absensi_rekap (id_jadwalpegawai_absensi_rekap_session, id_unit, month, year, id_user, id_kepegawaian, 
            k_jml_telat, k_jml_alpha, k_jml_sakit_1hari, k_jml_sakit_2hari, k_jml_izin, k_jml_cuti_prslnan, k_jml_izin_sethari, k_jml_meninggal, t_jml_cuti_sakit, t_jml_cuti_alsnpenting, t_jml_cuti_thnan, t_jml_diklat,
            t_jml_spd, t_jml_haji, s_jml_hks, s_jml_hkm, s_jml_hlp, s_jml_hls, s_jml_hlm, s_jml_hrp, s_jml_hrs, s_jml_hrm, s_jml_ns, jml_hari_kerja, jml_menit_kerja, created)
            VALUES('" . $id_jadwalpegawai_absensi_rekap_session . "', '" . $id_unit_res . "', '" . $month_res . "', '" . $year_res . "', '" . $id_user_res . "', '" . $id_kepegawaian_res . "', 
            '" . $k_jml_telat . "', '" . $k_jml_alpha . "', '" . $k_jml_sakit_1hari . "', '" . $k_jml_sakit_2hari . "', '" . $k_jml_izin . "', '" . $k_jml_cuti_prslnan . "', '" . $k_jml_izin_sethari . "', '" . $k_jml_meninggal . "', '" . $t_jml_cuti_sakit . "', '" . $t_jml_cuti_alsnpenting . "', '" . $t_jml_cuti_thnan . "', '" . $t_jml_diklat . "', '" . $t_jml_spd . "', '" . $t_jml_haji . "', 
            '" . $s_jml_hks . "', '" . $s_jml_hkm . "', '" . $s_jml_hlp . "', '" . $s_jml_hls . "', '" . $s_jml_hlm . "', '" . $s_jml_hrp . "', '" . $s_jml_hrs . "', '" . $s_jml_hrm . "', '" . $s_jml_ns . "', 
            '" . $jml_hari_kerja . "', '" . $jml_menit_kerja . "', '" . $created . "')");
        }

        // list pegawai yg telah diperbarui. apabila all, berarti pegawai dalam unit
        $list_pegawai = array();
        if ($id_user == 'all') {

            $sql = bukaquery2("
                SELECT
                    a.id_user
                FROM tm_jadwalpegawai_shift_m a
                WHERE a.`month` = '" . $month . "'
                    AND a.`year` = '" . $year . "'
                    AND a.id_unit = '" . $id_unit . "'
                GROUP BY a.id_user
            ");

            while ($row = fetch_array($sql)) {

                array_push($list_pegawai, $row['id_user']);
            }
        } else {

            array_push($list_pegawai, $id_user);
        }

        return array(
            "status" => 1,
            "message" => "Generate Rekap Absensi Pegawai Shift Per Unit Selesai",
            "id_unit" => $id_unit,
            "list_pegawai" => $list_pegawai
        );
    }

    function delete_absensi_pegawai($month, $year, $list_unit_serialized_temp, $list_pegawai_serialized_temp)
    {
        $list_unit = json_decode(stripslashes($list_unit_serialized_temp));
        $list_pegawai = json_decode(stripslashes($list_pegawai_serialized_temp));

        // pertama-tama pastikan unit yang di list_pegawai tidak ada juga di list_unit
        foreach ($list_pegawai as $key => $pegawai) {

            // jika ada dan list_user tidak kosong, hapus dari list_unit
            // jika tidak ada, skip
            if(($key_2 = array_search($pegawai->id_unit, $list_unit)) !== false) {
                if($pegawai->list_user != '') {
                    unset($list_unit[$key_2]);
                } else {
                    unset($list_pegawai[$key]);
                }
            }
        }

        $list_unit =  array_values($list_unit);
        $list_pegawai =  array_values($list_pegawai);

        foreach ($list_unit as $id_unit) {

            // clear data tm_jadwalpegawai_absensi_rekap
            bukaquery2("DELETE FROM tm_jadwalpegawai_absensi_rekap WHERE month = " . $month . " AND year = " . $year . " AND id_unit='" . $id_unit . "' ");

            // clear data tm_jadwalpegawai_absensi_detail
            bukaquery2("DELETE FROM tm_jadwalpegawai_absensi_detail WHERE month = " . $month . " AND year = " . $year . " AND id_unit='" . $id_unit . "' ");
            
        }

        foreach ($list_pegawai as $pegawai) {

            foreach (explode(',', $pegawai->list_user) as $key => $id_user) {

                // clear data tm_jadwalpegawai_absensi_rekap    
                bukaquery2("DELETE FROM tm_jadwalpegawai_absensi_rekap WHERE month = " . $month . " AND year = " . $year . " AND id_unit='" . $pegawai->id_unit . "' AND id_user='" . $id_user ."' ");
                // clear data tm_jadwalpegawai_absensi_detail
                bukaquery2("DELETE FROM tm_jadwalpegawai_absensi_detail WHERE month = " . $month . " AND year = " . $year . " AND id_unit='" . $pegawai->id_unit . "' AND id_user='" . $id_user ."' ");
            }
        }

        return array(
            "status" => 1,
            "message" => "Hapus Absensi Pegawai Selesai"
        );
    }

    function delete_absensi_pegawai_by_date($month, $year)
    {

        // clear data tm_jadwalpegawai_absensi_rekap
        bukaquery2("DELETE FROM tm_jadwalpegawai_absensi_rekap WHERE month = " . $month . " AND year = " . $year . "");

        // clear data tm_jadwalpegawai_absensi_rekap
        bukaquery2("DELETE FROM tm_jadwalpegawai_absensi_detail WHERE month = " . $month . " AND year = " . $year . "");

        return array(
            "status" => 1,
            "message" => "Hapus Absensi Pegawai Selesai"
        );
    }

    function get_work_day_in_month($month, $year)
    {

        $arr_work_day = array();

        for ($i = 1; $i <= $this->get_days_in_month($month, $year); $i++) {

            $date = $year . "-" . $month . "-" . $i;
            $day_name = date('l', strtotime($date));

            if ($day_name != "Saturday" && $day_name != "Sunday") {

                array_push($arr_work_day, date_format(date_create($date), 'Y-m-d'));
            }
        }

        return $arr_work_day;
    }

    function get_weekend_in_month($month, $year)
    {

        $arr_weekend = array();

        for ($i = 1; $i <= $this->get_days_in_month($month, $year); $i++) {

            $date = $year . "-" . $month . "-" . $i;
            $day_name = date('l', strtotime($date));

            if ($day_name == "Saturday" || $day_name == "Sunday") {

                array_push($arr_weekend, date_format(date_create($date), 'Y-m-d'));
            }
        }

        return $arr_weekend;
    }

    function get_list_pegawai_by_idunit($id_unit)
    {

        $arr = array();

        $sql = bukaquery2("
            SELECT
                a.id_user, a.nama_pegawai
            FROM tm_pegawai a
            WHERE a.id_unit = '" . $id_unit . "'
            ORDER BY a.nama_pegawai
        ");

        array_push($arr, array(
            'id_user' => 'all',
            'nama_pegawai' => '-Seluruh Pegawai-',
        ));
        while ($row = fetch_array($sql)) {

            array_push($arr, array(
                'id_user' => $row['id_user'],
                'nama_pegawai' => $row['nama_pegawai'],
            ));
        }

        return array(
            'status' => 1,
            'message' => 'data ditemukan',
            'data' => $arr
        );
    }

    function get_cleaning_absen($id_user, $bulan, $tahun)
    {
        $sql = bukaquery2("
            SELECT
                j.id_jadwalkerja_absensi, 
                j.date, 
                j.id_absensi, 
                j.id_absensi_tipe, 
                j.id_ketidakhadiran, 
                sh.desc_ketidakhadiran,
                j.absensi_masuk, 
                j.absensi_pulang, 
                j.keterlambatan, 
                j.pulang_cepat,
                s.jam_masuk,
                s.jam_pulang,
                j.id_unit,
                j.id_user,
                p.log_finger
            FROM
                tm_jadwalpegawai_absensi_detail j
                INNER JOIN tm_pegawai p ON j.id_user = p.id_user 
                LEFT JOIN tm_shift s ON j.id_absensi = s.id_absensi 
                LEFT JOIN tm_shift_ketidakhadiran sh ON j.id_ketidakhadiran = sh.id_ketidakhadiran 
            WHERE
                j.shift_aktif = 1
                AND j.id_user = '$id_user' 
                AND YEAR = '$tahun' 
                AND MONTH = '$bulan'
        ");

        $id_unit = null;
        $id_user = null;
        $log_finger = null;

        while ($data = $sql->fetch_assoc()) {
            $id_unit = $data['id_unit'];
            $id_user =  $data['id_user'];
            $log_finger =  $data['log_finger'];

            $hari = new DateTime($data['date']);
            $hari = $hari->format('l');

            if (in_array($hari, ['saturday', 'sunday']) === false && empty($data['id_ketidakhadiran'])) {
                $tglam1 = strtotime($data['absensi_masuk']);
                $tglam2 = strtotime("$data[date] $data[jam_masuk]");
                $cek_absensi_masuk = $tglam1 - $tglam2;
                $tglap1 = strtotime($data['absensi_pulang']);
                $tglap2 = strtotime("$data[date] $data[jam_pulang]");
                $cek_absensi_pulang = $tglap1 - $tglap2;

                // cek absen masuk 
                // cek absen masuk kurang dari regulasi
                if (empty($data['absensi_masuk'])) {
                    $tgl_hari_ini = new DateTime("$data[date] $data[jam_masuk]");
                    $interval = new DateInterval('PT1M');
                    // $interval->invert = 1;
                    $tgl_hari_ini->add($interval);
                    $tgl_hari_ini = $tgl_hari_ini->format('Y-m-d H:i:' . rand(1, 50));

                    bukaquery2("UPDATE tm_jadwalpegawai_absensi_detail SET absensi_masuk = '$tgl_hari_ini', keterlambatan = 1 WHERE id_jadwalkerja_absensi = $data[id_jadwalkerja_absensi]");
                    $data['absensi_masuk'] = $tgl_hari_ini;
                }

                // cek absen pulang 
                // cek absen masuk kurang dari regulasi
                if (empty($data['absensi_pulang'])) {
                    $tgl_hari_ini = new DateTime("$data[date] $data[jam_pulang]");
                    $interval = new DateInterval('PT15M');
                    // $interval->invert = 1;
                    $tgl_hari_ini->add($interval);
                    $tgl_hari_ini = $tgl_hari_ini->format('Y-m-d H:i:' . rand(1, 50));

                    bukaquery2("UPDATE tm_jadwalpegawai_absensi_detail SET absensi_pulang = '$tgl_hari_ini', pulang_cepat = 0 WHERE id_jadwalkerja_absensi = $data[id_jadwalkerja_absensi]");
                    $data['absensi_pulang'] = $tgl_hari_ini;
                }
            }
        }


        $sum = bukaquery2("SELECT sum( keterlambatan ) keterlambatan, sum( pulang_cepat ) pulang_cepat FROM tm_jadwalpegawai_absensi_detail WHERE id_user = '$id_user' and year = '$tahun' and month = '$bulan'")->fetch_assoc();
        bukaquery2("UPDATE tm_jadwalpegawai_absensi_rekap SET k_jml_telat = $sum[keterlambatan], k_jml_plng_cepat = $sum[pulang_cepat] WHERE id_user = '$id_user' and year = '$tahun' and month = '$bulan'");

        header('location:../../page-view?' . paramEncrypt("module=absensi-pegawai&act=detail-absensi-pegawai&id_unit=$id_unit&id_user=$id_user&log_finger=$log_finger&bulan=$bulan&tahun=$tahun"));
        die();
    }


    /** 
    based tm_hari_raya
     */
    function get_holiday_in_month($month, $year)
    {

        $arr_holiday = array();

        $sql = bukaquery2("
            ( SELECT a.tanggal FROM tm_hari_raya a WHERE MONTH(a.tanggal) = '" . $month . "' AND YEAR(a.tanggal) = '" . $year . "' ORDER BY a.tanggal )
            UNION
            ( SELECT a.tanggal FROM tm_hari_libur a WHERE MONTH(a.tanggal) = '" . $month . "' AND YEAR(a.tanggal) = '" . $year . "' )
        ");

        while ($row = fetch_array($sql)) {

            array_push($arr_holiday, $row['tanggal']);
        }

        return $arr_holiday;
    }

    function get_days_in_month($month, $year)
    {
        return (int) cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    /**
     * untuk mendapatkan list tanggal dari range tanggal
     * @param string tanggal awal format Y-m-d
     * @param string tanggal akhir format Y-m-d
     * @return array
     */
    function get_list_date_by_daterange($since, $until)
    {

        $dates = array();
        $current = strtotime($since);
        $last = strtotime($until);

        while ($current <= $last) {

            $dates[] = date('Y-m-d', $current);
            $current = strtotime('+1 day', $current);
        }

        return $dates;
    }

    function invalid_action()
    {
        return array(
            "status" => 401,
            "message" => "Invalid Action"
        );
    }
}

$apiAbsensi = new ApiAbsensi();
$action = isset($_GET['action']) ? $_GET['action'] : null;
switch ($action) {
    case 'get_shift_validation_status_by_unit':
        echo json_encode($apiAbsensi->get_shift_validation_status_by_unit(
            $_GET['id_unit'],
            $_GET['month'],
            $_GET['year']
        ));
        break;
    case 'generate_listuser_rekapitulasi_shift_absensi_pegawai':
        echo json_encode($apiAbsensi->generate_listuser_rekapitulasi_shift_absensi_pegawai(
            $_POST['id_kepegawaian'],
            $_POST['month'],
            $_POST['year'],
            $_POST['list_unit_serialized']
        ));
        break;
    case 'generate_rekapitulasi_shift_absensi_pegawai':
        echo json_encode($apiAbsensi->generate_rekapitulasi_shift_absensi_pegawai(
            $_POST['id_kepegawaian'],
            $_POST['id_user'],
            $_POST['id_unit'],
            $_POST['month'],
            $_POST['year'],
            $_POST['id_session']
        ));
        break;
    case 'generate_rekapitulasi_shift_absensi_pegawai_perunit':
        echo json_encode($apiAbsensi->generate_rekapitulasi_shift_absensi_pegawai_perunit(
            $_POST['id_kepegawaian'],
            $_POST['month'],
            $_POST['year'],
            $_POST['id_unit'],
            $_POST['id_user']
        ));
        break;

    case 'generate_detail_shift_absensi_pegawai':
        // $_POST['id_kepegawaian'] = '2011100002';
        // $_POST['month'] = 4;
        // $_POST['year'] = 2025;
        // $_POST['id_unit'] = 'UNT-000022';

        echo json_encode($apiAbsensi->generate_detail_shift_absensi_pegawai(
            $_POST['id_kepegawaian'],
            $_POST['month'],
            $_POST['year'],
            $_POST['id_unit'],
            $_POST['list_pegawai']
        ));
        break;

    case 'get_rekapitulasi_absensi_pegawai':
        echo json_encode($apiAbsensi->get_rekapitulasi_absensi_pegawai(
            $_GET['month'],
            $_GET['year']
        ));
        break;

    case 'get_list_unit_rekapitulasi_absensi_pegawai':
        echo json_encode($apiAbsensi->get_list_unit_rekapitulasi_absensi_pegawai(
            $_GET['month'],
            $_GET['year']
        ));
        break;

    case 'put_accepted_rekap_absensi_pegawai':
        echo json_encode($apiAbsensi->put_accepted_rekap_absensi_pegawai(
            $_POST['id_kepegawaian'],
            $_POST['month'],
            $_POST['year']
        ));
        break;

    case 'put_shift_detail_absensi_pegawai':
        echo json_encode($apiAbsensi->put_shift_detail_absensi_pegawai(
            $_POST['id_kepegawaian'],
            $_POST['id_unit'],
            $_POST['id_user'],
            $_POST['log_finger'],
            $_POST['month'],
            $_POST['year'],
            $_POST['date'],
            $_POST['id_absensi'],
            $_POST['id_absensi_tipe'],
            $_POST['id_ketidakhadiran'],
            $_POST['is_libur']
        ));
        break;

    case 'get_options_edit_shift_detail_absensi_pegawai':
        echo json_encode($apiAbsensi->get_options_edit_shift_detail_absensi_pegawai());
        break;

    case 'get_log_kehadiran_by_logfinger_date':
        echo json_encode($apiAbsensi->get_log_kehadiran_by_logfinger_date(
            $_GET['log_finger'],
            $_GET['date'],
            $_GET['id_unit'],
            $_GET['id_user']
        ));
        break;

    case 'put_absensimasuk_detail_absensi_pegawai':
        echo json_encode($apiAbsensi->put_absensimasuk_detail_absensi_pegawai(
            $_POST['id_kepegawaian'],
            $_POST['id_user'],
            $_POST['id_unit'],
            $_POST['month'],
            $_POST['year'],
            $_POST['date'],
            $_POST['absensi_masuk_selected']
        ));
        break;

    case 'put_absensipulang_detail_absensi_pegawai':
        echo json_encode($apiAbsensi->put_absensipulang_detail_absensi_pegawai(
            $_POST['id_kepegawaian'],
            $_POST['id_user'],
            $_POST['id_unit'],
            $_POST['month'],
            $_POST['year'],
            $_POST['date'],
            $_POST['absensi_pulang_selected']
        ));
        break;
    case 'put_keterlambatan_absensi_pegawai':
        echo json_encode($apiAbsensi->put_keterlambatan_absensi_pegawai(
            $_POST['id_kepegawaian'],
            $_POST['id_user'],
            $_POST['id_unit'],
            $_POST['month'],
            $_POST['year'],
            $_POST['date'],
            $_POST['keterlambatan_old'],
            $_POST['keterlambatan_new'],
            $_POST['keterangan']
        ));
        break;
    case 'put_pulangcepat_absensi_pegawai':
        echo json_encode($apiAbsensi->put_pulangcepat_absensi_pegawai(
            $_POST['id_kepegawaian'],
            $_POST['id_user'],
            $_POST['id_unit'],
            $_POST['month'],
            $_POST['year'],
            $_POST['date'],
            $_POST['pulangcepat_old'],
            $_POST['pulangcepat_new'],
            $_POST['keterangan']
        ));
        break;

    case 'generate_rekapitulasi_nonshift_absensi_pegawai':
        echo json_encode($apiAbsensi->generate_rekapitulasi_nonshift_absensi_pegawai(
            $_POST['id_kepegawaian'],
            $_POST['month'],
            $_POST['year']
        ));
        break;

    case 'delete_absensi_pegawai':
        echo json_encode($apiAbsensi->delete_absensi_pegawai(
            $_POST['month'],
            $_POST['year'],
            $_POST['list_unit_serialized'],
            $_POST['list_pegawai_serialized']
        ));
        break;

    case 'delete_absensi_pegawai_by_date':
        echo json_encode($apiAbsensi->delete_absensi_pegawai_by_date(
            $_POST['month'],
            $_POST['year']
        ));
        break;

    case 'get_bool_valid_generate_absensi_pegawai':
        echo json_encode($apiAbsensi->get_bool_valid_generate_absensi_pegawai(
            $_GET['month'],
            $_GET['year']
        ));
        break;

    case 'generate_detail_nonshift_absensi_pegawai':
        echo json_encode($apiAbsensi->generate_detail_nonshift_absensi_pegawai(
            $_POST['id_kepegawaian'],
            $_POST['month'],
            $_POST['year']
        ));
        break;

    case 'insert_log_absensi_pegawai':
        echo json_encode($apiAbsensi->insert_log_absensi_pegawai(
            $_POST['id_kepegawaian'],
            $_POST['month'],
            $_POST['year']
        ));
        break;

    case 'rekap_cuti':
        echo json_encode($apiAbsensi->rekap_cuti(
            $_POST['month'],
            $_POST['year'],
            $_POST['id_unit']
        ));
        break;

    case 'get_unit_rekapitulasi_absensi_pegawai':
        echo json_encode($apiAbsensi->get_unit_rekapitulasi_absensi_pegawai(
            $_GET['month'],
            $_GET['year']
        ));
        break;

    case 'generate_rekapitulasi_detail_dokter_spesialis_absensi_pegawai':
        echo json_encode($apiAbsensi->generate_rekapitulasi_detail_dokter_spesialis_absensi_pegawai(
            $_POST['id_kepegawaian'],
            $_POST['month'],
            $_POST['year']
        ));
        break;

    case 'get_status_unit_rekapitulasi_absensi_pegawai':
        echo json_encode($apiAbsensi->get_status_unit_rekapitulasi_absensi_pegawai(
            $_GET['month'],
            $_GET['year']
        ));
        break;
    case 'update_absensi_pegawai':
        echo json_encode($apiAbsensi->update_absensi_pegawai(
            $_POST['id_kepegawaian'],
            $_POST['month'],
            $_POST['year']
        ));
        break;
    case 'get_status_duplikat_absensi_pegawai':
        echo json_encode($apiAbsensi->get_status_duplikat_absensi_pegawai(
            $_POST['month'],
            $_POST['year'],
            $_POST['list_unit_serialized']
        ));
        break;
    case 'get_absensi_live_jadwal_by_iduser_idunit_month_year':
        echo json_encode($apiAbsensi->get_absensi_live_jadwal_by_iduser_idunit_month_year(
            $_GET['id_user'],
            $_GET['id_unit'],
            $_GET['date_start'],
            $_GET['date_end']
        ));
        break;
    case 'get_absensi_live_by_iduser_idunit_month_year':
        echo json_encode($apiAbsensi->get_absensi_live_by_iduser_idunit_month_year(
            $_GET['id_user'],
            $_GET['id_unit'],
            $_GET['date_start'],
            $_GET['date_end']
        ));
        break;
    case 'get_pegawai_by_idunit_shiftm':
        echo json_encode($apiAbsensi->get_pegawai_by_idunit_shiftm(
            $_GET['id_unit'],
            $_GET['month'],
            $_GET['year']
        ));
        break;

    case 'get_list_pegawai_by_idunit':
        echo json_encode($apiAbsensi->get_list_pegawai_by_idunit(
            $_GET['id_unit']
        ));
        break;

    case 'get_cleaning_absen':
        echo json_encode($apiAbsensi->get_cleaning_absen(
            $_GET['id_user'],
            $_GET['bulan'],
            $_GET['tahun']
        ));
        break;


    default:
        echo json_encode($apiAbsensi->invalid_action());
        break;
}
