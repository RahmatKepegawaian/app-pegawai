<?php

require_once ('../../conf/conf.php');
require_once ('../../libs/aes-encrypt/function.php');

class ApiLaporan {

    function get_precense_by_idunit_month_year_from_shift($id_unit, $month, $year) {
        $result = array();
        $temp_sql = array();
        $temp_grouping = array();

        $sql = bukaquery2("SELECT
                a.id_user, b.log_finger, b.nama_pegawai, a.date,
            	IFNULL(a.id_absensi, '') AS id_absensi, IFNULL(c.nama_shift, '') AS nama_shift, c.hex_color_shift, IFNULL(c.jam_masuk, '') AS jam_masuk_absensi_aktif, IFNULL(c.jam_pulang, '') AS jam_pulang_absensi_aktif,
                IFNULL(a.id_ketidakhadiran, '') AS id_ketidakhadiran, IFNULL(f.nama_ketidakhadiran, '') AS nama_ketidakhadiran, f.hex_color_ketidakhadiran,
            	IFNULL(d.tanggal, '') AS absensi_masuk, IFNULL(TIME_TO_SEC(TIMEDIFF(TIME(d.tanggal), TIME(c.jam_masuk)))/60, '') AS keterlambatan, 
            	IFNULL(e.tanggal, '') AS absensi_pulang, IFNULL(TIME_TO_SEC(TIMEDIFF(TIME(c.jam_pulang), TIME(e.tanggal)))/60, '') AS pulang_cepat
            FROM tm_jadwalpegawai_shift_m a
                INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                LEFT JOIN tm_shift c ON a.id_absensi = c.id_absensi
                LEFT JOIN log d ON b.log_finger = d.`user`
                    AND DATE(d.tanggal) = a.date
                    AND TIME(d.tanggal) > SUBTIME(c.bi, '00:00:01')
                    AND TIME(d.tanggal) < ADDTIME(c.ai, '04:00:00')
                LEFT JOIN log e ON b.log_finger = e.`user`
                    AND DATE(e.tanggal) = a.date
                    AND TIME(e.tanggal) > SUBTIME(c.bo, '04:00:00')
                    AND TIME(e.tanggal) < ADDTIME(c.ao, '00:00:01')
                LEFT JOIN tm_shift_ketidakhadiran f ON a.id_ketidakhadiran = f.id_ketidakhadiran
            WHERE a.id_unit = '".$id_unit."'
                AND a.`month` = '".$month."'
                AND a.`year` = '".$year."'
                AND a.id_user = '2008120001'
            ORDER BY b.nama_pegawai, a.date, d.tanggal ASC, e.tanggal DESC
        ");

        // menyimpan hasil sql ke dalam array
        while ($row = fetch_array($sql)) {
            array_push($temp_sql, array(
                'id_user' => $row['id_user'],
                'log_finger' => $row['log_finger'],
                'nama_pegawai' => $row['nama_pegawai'],
                'date' => $row['date'],
                'id_absensi' => $row['id_absensi'] != '' ? $row['id_absensi'] : $row['id_ketidakhadiran'],
                'nama_absensi' => $row['nama_shift'] != '' ? $row['nama_shift'] : $row['nama_ketidakhadiran'],
                'hex_color_absensi' => $row['hex_color_shift'] != '' ? $row['hex_color_shift'] : $row['hex_color_ketidakhadiran'],
                'jam_masuk_absensi_aktif' => $row['jam_masuk_absensi_aktif'],
                'jam_pulang_absensi_aktif' => $row['jam_pulang_absensi_aktif'],
                "shift_aktif" => $row['id_absensi'] != '' && $row['id_ketidakhadiran'] == '',
                'absensi_masuk' => $row['absensi_masuk'],
                'absensi_pulang' => $row['absensi_pulang'],
                'keterlambatan' => ((int) $row['keterlambatan']) > 0 ? (int) $row['keterlambatan'] : 0,
                'pulang_cepat' => ((int) $row['pulang_cepat']) > 0 ? (int) $row['pulang_cepat'] : 0,
            ));
        }

        for ($i=0; $i < count($temp_sql); $i++) {

            //akibat kegagalan dalam grouping data di query, maka data grouping dilakukan di aplikasi
            if($i == 0 || $temp_sql[$i]['date'] != $temp_sql[$i == 0 ? 0 : $i-1]['date']) {

                array_push($temp_grouping, $temp_sql[$i]);
            }
        }

        //data dirapikan berdasarkan pegawai
        for ($i = 0; $i < count($temp_grouping); $i = $i + $this->get_days_in_month($month, $year)) { 
            
            $temp_keterlambatan = 0;
            $temp_pulangcepat = 0;           
            $temp_shifts = array();
            
            for ($j=0; $j < $this->get_days_in_month($month, $year); $j++) { 
                 
                $temp_keterlambatan += ((int) $temp_grouping[($i + $j)]['keterlambatan']) > 0 ? (int) $temp_grouping[($i + $j)]['keterlambatan'] : 0;
                $temp_pulangcepat += ((int) $temp_grouping[($i + $j)]['pulang_cepat']) > 0 ? (int) $temp_grouping[($i + $j)]['pulang_cepat'] : 0;

                array_push($temp_shifts, array(
                    'date' => $temp_grouping[($i + $j)]['date'],
                    'id_absensi' => $temp_grouping[($i + $j)]['id_absensi'],
                    'nama_absensi' => $temp_grouping[($i + $j)]['nama_absensi'],
                    'hex_color_absensi' => $temp_grouping[($i + $j)]['hex_color_absensi'],
                    'shift_aktif' => $temp_grouping[($i + $j)]['shift_aktif'],
                    'jam_masuk_absensi_aktif' => $temp_grouping[($i + $j)]['jam_masuk_absensi_aktif'],
                    'jam_pulang_absensi_aktif' => $temp_grouping[($i + $j)]['jam_pulang_absensi_aktif'],
                    'absensi_masuk' => $temp_grouping[($i + $j)]['absensi_masuk'],
                    'absensi_pulang' => $temp_grouping[($i + $j)]['absensi_pulang'],
                    'keterlambatan' => ((int) $temp_grouping[($i + $j)]['keterlambatan']) > 0 ? (int) $temp_grouping[($i + $j)]['keterlambatan'] : 0,
                    'pulang_cepat' => ((int) $temp_grouping[($i + $j)]['pulang_cepat']) > 0 ? (int) $temp_grouping[($i + $j)]['pulang_cepat'] : 0,
                ));
            }

            $temp_profile = array(
                "id_user" => $temp_grouping[$i]['id_user'],
                "log_finger" => $temp_grouping[$i]['log_finger'],
                "nama_pegawai" => $temp_grouping[$i]['nama_pegawai'],
                "keterlambatan_total" => $temp_keterlambatan,
                "pulangcepat_total" => $temp_pulangcepat
            );

            array_push($result, array(
                "profile" => $temp_profile,
                "shifts" => $temp_shifts
            ));
        }

        return array(
            "status" => count($result) != 0 ? 1 : 0,
            "message" => count($result) != 0 ? "Data ditemukan" : "Data tidak ditemukan",
            "data" => array(
                "days_in_month" => $this->get_days_in_month($month, $year),
                "data" => $result
            )
        );

    }

    function get_days_in_month($month, $year) {
        return (int) cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    function invalid_action() {
        return array(
            'status' => '401',
            'message' => 'Invalid Action'
        );
    }
}

$apiLaporan = new ApiLaporan();
$action = isset($_GET['action']) ? $_GET['action'] : null;
switch ($action) {

    case 'get_precense_by_idunit_month_year_from_shift':
        echo json_encode($apiLaporan->get_precense_by_idunit_month_year_from_shift(
            $_GET['id_unit'],
            $_GET['month'],
            $_GET['year']
        ));
        break;
    default:
        echo json_encode($apiLaporan->invalid_action());
        break;
}
?>