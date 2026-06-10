<?php

require_once ('../../conf/conf.php');
require_once ('../../libs/aes-encrypt/function.php');
error_reporting(1);
ini_set('display_errors', 1);
class ApiMasterData {

    function act_generate_shift($id_unit, $month, $year, $id_penanggungjawab) {

        $list_pegawai_by_id_unit = $this->get_list_pegawai_by_id_unit($id_unit);
        
        // jika list pegawainya 0, berarti tidak ada pegawai di dalem unit tsb
        if ($list_pegawai_by_id_unit['status'] == 1) {

            $list_pegawai_in_existingshift = array();
            $sql = bukaquery2("
                SELECT
                    a.id_user
                FROM tm_jadwalpegawai_shift_m a
                WHERE a.id_unit = '".$id_unit."'
                    AND a.`month` = '".$month."'
                    AND a.`year` = '".$year."'
                GROUP BY a.id_user
            ");
            while ($row = fetch_assoc($sql)) array_push($list_pegawai_in_existingshift, $row['id_user']);

            // jika jumlah pegawai di dalam shift existing LEBIH SEDIKIT daripada jumlah seharusnya. mungkin pegawai tsb di-generate-kan oleh kepeg sbg pegawai non-shift
            // maka dari list_pegawai_by_id_unit ada pegawai yg harus di-generate-kan selain yg sudah di-generate
            if(count($list_pegawai_in_existingshift) < count($list_pegawai_by_id_unit['data']) || true) {

                $list_datacuti = array();

                // pegawai yg sudah tergenerate, di-eleminasi
                $list_pegawai_need_generated = array_values(array_diff($list_pegawai_by_id_unit['data'], $list_pegawai_in_existingshift));

                $final_query = "INSERT INTO tm_jadwalpegawai_shift_m ( id_unit, MONTH, YEAR, id_penanggung_jawab, id_user, id_ketidakhadiran, date, created ) VALUES ";
                $days_in_month = $this->get_days_in_month($month, $year); 

                for ($i=0; $i < count($list_pegawai_need_generated); $i++) {
                    
                    // ambil data cuti pegawai yg sudah diacc sampai dengan ktu
                    $sql_datacuti = bukaquery("
                        SELECT 
                            a.periode_cuti, a.id_ketidakhadiran
                        FROM tm_cuti a
                        WHERE a.id_user = '".$list_pegawai_need_generated[$i]."' 
                            AND a.acc_pengganti = 'Y' 
                            AND a.acc_pj = 'Y' 
                            AND a.acc_kasatpel = 'Y' 
                            AND a.acc_ktu = 'Y'
                    ");
                    while ($row_datacuti = fetch_assoc($sql_datacuti)) {

                        foreach (explode(",", $row_datacuti['periode_cuti']) as $value_datacuti) {

                            array_push($list_datacuti, array(
                                "tgl_cuti" => konversiBulanTahun_2($value_datacuti),
                                "id_ketidakhadiran" => $row_datacuti['id_ketidakhadiran']
                            ));
                        }
                    }

                    for ($day = 1; $day <= $days_in_month; $day++) {

                        $id_ketidakhadiran = '';
                        
                        // cek apakah tgl tersebut, pegawai telah mengajukan cuti.
                        // apabila ya, maka id_ketidakhadiran langsung terisi
                        for ($j=0; $j < count($list_datacuti); $j++) { 

                            if(FormatTgl("yyyy-mm-dd", $list_datacuti[$j]['tgl_cuti']) == FormatTgl("yyyy-mm-dd", $year."-".$month."-".$day)) {

                                $id_ketidakhadiran = $list_datacuti[$j]['id_ketidakhadiran']; // ambil id_ketidakhadirannnya
                                unset($list_datacuti[$j]); // hapus dari array supaya semkain mempersingkat waktu
                                $list_datacuti = array_values($list_datacuti); // re-index array
                                break;
                            }
                        }

                        $final_query .= "('".$id_unit."', ".$month.", ".$year.", '".$id_penanggungjawab."', '".$list_pegawai_need_generated[$i]."', '".$id_ketidakhadiran."', '".$year."-".$month."-".$day."', NOW())";
                        
    
                        if($day != $days_in_month || $i+1 < count($list_pegawai_need_generated)) {
                            $final_query .= ", ";
                        }
                    }
                }
                
                if(isset($final_query)) {

                    //insert ke db
                    bukainput2($final_query);

                    // ambil list cuti pegawai
                    $sql = bukaquery2("
                        SELECT
                            a.id_cuti, b.tanggal, a.id_user, a.id_ketidakhadiran
                        FROM tm_cuti AS a
                            INNER JOIN tm_hari_cuti AS b ON a.id_cuti = b.id_cuti
                            INNER JOIN tm_pegawai AS c ON a.id_user = c.id_user
                        WHERE YEAR(b.tanggal) = '".$year."' 
                            AND MONTH(b.tanggal) = '".$month."'
                            AND c.id_unit = '".$id_unit."'
                            AND a.acc_direktur = 'Y' 
                            AND a.aktif = '1'
                        GROUP BY a.id_cuti, b.tanggal
                    ");

                    // update di tm_jadwalpegawai_shift_m pegawai jika ada cuti yang sudah di-acc direktur
                    while ($row = fetch_assoc($sql)) {

                        bukaquery2("
                            UPDATE tm_jadwalpegawai_shift_m
                            SET id_absensi = '', id_absensi_tipe = '', id_ketidakhadiran = '".$row['id_ketidakhadiran']."'
                            WHERE year = '".$year."'
                                AND month = '".$month."'
                                AND id_user = '".$row['id_user']."'
                                AND date = '".$row['tanggal']."'
                        ");
                    }
    
                    //insert log
                    $array_log = serialize(array("keterangan" => "Jadwal Shift Digenerate"));
                    bukaquery2("INSERT INTO tm_jadwalpegawai_shift_log (id_unit, month, year, data) VALUES('".$id_unit."', ".$month.", ".$year.", '".$array_log."')");
                    
                    return array(
                        "status" => 1,
                        "message" => "Generate shift berhasil",
                    );
                } else {
    
                    return array(
                        "status" => 0,
                        "message" => "Generate Gagal. Query gagal",
                    );
                }
            } else {
                return array(
                    "status" => 1,
                    "message" => "Jadwal Shift Pegawai telah seluruhnya di-generate"
                );
            }
        } else {
            return array(
                "status" => 0,
                "message" => "Generate Gagal. Data Pegawai tidak ditemukan",
            );
        }
    }

    function act_generate_nonshift($id_penanggung_jawab, $month, $year) {

        $list_pegawai = array();
        $list_unit = array();
        $list_hari_raya = array();
        $default_id_absensi_weekday = "";
        $default_id_absensi_friday = "";

        // ambil list pegawai
        // dimana ada 3 kategori
        // 1. seluruh pegawai (user, pj, kepegawaian, keuangan kecuali kasatpel) dan tipe unitnya Non-Shift yg ada di tm_jadwalpegawai_shift_m
        // 2. seluruh pegawai komite
        // 3. seluruh kasatpel yang bertipe non-pns dan aktif dari seluruh unit
        $sql = bukaquery2("
            SELECT
                a.id_unit, a.id_user
            FROM (
                (
                    SELECT
                            a.id_unit, a.nama_unit, b.id_user, b.nama_pegawai
                    FROM tm_unit a
                            INNER JOIN tm_pegawai b ON a.id_unit = b.id_unit
                            INNER JOIN tm_user c ON b.id_user	 = c.id_user
                    WHERE a.id_petugas = 'SHF-000004'
                            AND a.id_unit <> 'UNT-000020'
                            AND b.`status` = 'AKTIF'
                            AND b.status_pegawai <> 'PNS'
                            AND c.id_level <> 'LVL-000006'
                    ORDER BY a.nama_unit, c.id_level DESC, b.nama_pegawai
                )
                UNION
                (
                    SELECT
                            c.id_unit, d.nama_unit, b.id_user, c.nama_pegawai
                    FROM tm_komite a
                            INNER JOIN tt_komite_anggota b ON a.id_komite = b.id_komite
                            INNER JOIN tm_pegawai c ON b.id_user = c.id_user
                            INNER JOIN tm_unit d ON c.id_unit = d.id_unit
                    WHERE a.active_status = '1'
                            AND a.full_time = '1'
                            AND b.active_status = '1'
                            AND b.full_time = '1'
                            AND c.status = 'AKTIF'
                            AND c.status_pegawai <> 'PNS'
                )
                UNION
                (
                    SELECT
                            a.id_unit, d.nama_unit, a.id_user, b.nama_pegawai
                    FROM tm_jadwalpegawai_shift_m a
                            INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                                    AND b.`status` = 'AKTIF'
                                    AND b.status_pegawai <> 'PNS'
                            INNER JOIN tm_user c ON b.id_user = c.id_user
                                    AND c.id_level = 'LVL-000006'
                            INNER JOIN tm_unit d ON a.id_unit = d.id_unit
                    GROUP BY a.id_user
                    ORDER BY b.nama_pegawai
                )
            ) AS a
                LEFT JOIN (
                    SELECT
                        DISTINCT a.id_unit, a.nama_unit, a.id_user, a.nama_pegawai
                    FROM 
                    (
                        (
                            SELECT
                                DISTINCT a.id_unit, a.nama_unit, b.id_user, d.nama_pegawai
                            FROM tm_unit a
                                INNER JOIN tm_jadwalpegawai_shift_m b ON a.id_unit = b.id_unit
                                INNER JOIN tm_user c ON b.id_user = c.id_user
                                INNER JOIN tm_pegawai d ON b.id_user = d.id_user
                            WHERE a.id_petugas = 'SHF-000004'
                                AND b.month = '".$month."'
                                AND b.year = '".$year."'
                                AND c.id_level <> 'LVL-000006'
                            ORDER BY a.nama_unit, c.id_level, d.nama_pegawai
                        )
                        UNION
                        (
                            SELECT
                                c.id_unit, e.nama_unit,
                                c.id_user, d.nama_pegawai
                            FROM tm_komite a
                                INNER JOIN tt_komite_anggota b ON a.id_komite = b.id_komite
                                LEFT JOIN tm_jadwalpegawai_shift_m c ON b.id_user = c.id_user
                                    AND c.month = '".$month."'
                                    AND c.year = '".$year."'
                                INNER JOIN tm_pegawai d ON c.id_user = d.id_user
                                INNER JOIN tm_unit e ON d.id_unit = e.id_unit
                                INNER JOIN tm_user f ON c.id_user = f.id_user     
                            WHERE a.active_status = '1'
                                AND a.full_time = '1'
                                AND b.active_status = '1'
                                AND b.full_time = '1'
                            ORDER BY e.nama_unit, f.id_level, d.nama_pegawai
                        )
                        UNION
                        (
                            SELECT
                                a.id_unit, d.nama_unit, 
                                a.id_user, b.nama_pegawai
                            FROM tm_jadwalpegawai_shift_m a
                                INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                                    AND b.`status` = 'AKTIF'
                                    AND b.status_pegawai <> 'PNS'
                                INNER JOIN tm_user c ON b.id_user = c.id_user
                                    AND c.id_level = 'LVL-000006'
                                INNER JOIN tm_unit d ON a.id_unit = d.id_unit
                            WHERE a.month = '".$month."'
                                AND a.year = '".$year."'
                            GROUP BY a.id_user
                            ORDER BY b.nama_pegawai
                        )
                    ) AS a
                ) AS b ON a.id_user = b.id_user AND a.id_unit = b.id_unit
            
            WHERE b.id_user IS NULL
        ");
        while ($row = fetch_assoc($sql)) {

            array_push($list_pegawai, $row);
            array_push($list_unit, $row['id_unit']); // diisi untuk kebutuhan log
        }

        // ambil data hari raya & hari libur
        $sql = bukaquery2("
            (SELECT a.tanggal FROM tm_hari_raya a WHERE YEAR(a.tanggal) = '".$year."'  AND MONTH(a.tanggal) = '".$month."')
            UNION
            (SELECT a.tanggal FROM tm_hari_libur a WHERE YEAR(a.tanggal) = '".$year."'  AND MONTH(a.tanggal) = '".$month."')
        ");
        while ($row = fetch_assoc($sql)) {
            array_push($list_hari_raya, $row['tanggal']);
        }

        // ambil default id_absensi untuk wweekday (SENIN - KAMIS)
        $default_id_absensi_weekday = getOne("
            SELECT
                a.id_absensi
            FROM tm_shift a
            WHERE a.nonshift_default_weekday = 1
        ");

        // ambil default id_absensi untuk JUMAT
        $default_id_absensi_friday = getOne("
            SELECT
                a.id_absensi
            FROM tm_shift a
            WHERE a.nonshift_default_friday = 1
        ");

        // buat query finalnya berdasarkan jumlah hari di dalam 1 bulan
        $final_query = "INSERT INTO tm_jadwalpegawai_shift_m(id_unit, month, year, id_penanggung_jawab, id_user, id_absensi, id_absensi_tipe, id_ketidakhadiran, date, created) VALUES ";
        $days_in_month = $this->get_days_in_month($month, $year);

        for ($i=0; $i < count($list_pegawai); $i++) {
            
            for ($date=1; $date <= $days_in_month; $date++) {

                $full_date = date("Y-m-d", strtotime($year."-".$month."-".$date));
                $day_name = date('l', strtotime($year."-".$month."-".$date));
                
                if($day_name == 'Saturday' || $day_name == 'Sunday' || in_array($full_date, $list_hari_raya)) {

                    $final_query .= "('".$list_pegawai[$i]['id_unit']."', '".$month."', '".$year."', '".$id_penanggung_jawab."', '".$list_pegawai[$i]['id_user']."', '', '', '', '".$full_date."', NOW())";
                } else if($day_name == 'Friday') {

                    $final_query .= "('".$list_pegawai[$i]['id_unit']."', '".$month."', '".$year."', '".$id_penanggung_jawab."', '".$list_pegawai[$i]['id_user']."', '".$default_id_absensi_friday."', 'ABT-000009', '', '".$full_date."', NOW())";
                } else {

                    $final_query .= "('".$list_pegawai[$i]['id_unit']."', '".$month."', '".$year."', '".$id_penanggung_jawab."', '".$list_pegawai[$i]['id_user']."', '".$default_id_absensi_weekday."', 'ABT-000009', '', '".$full_date."', NOW())";
                }

                if($date != $days_in_month || $i+1 < count($list_pegawai)) {

                    $final_query .= ", ";
                }
            }
        }

        // insert ke database jika data pegawai tidak kosong
        if(count($list_pegawai) != 0) bukaquery2($final_query);

        // ambil list cuti pegawai
        $sql = bukaquery2("
            SELECT
                a.id_cuti, b.tanggal, a.id_user, a.id_ketidakhadiran
            FROM tm_cuti AS a
                INNER JOIN tm_hari_cuti AS b ON a.id_cuti = b.id_cuti
                INNER JOIN tm_pegawai AS c ON a.id_user = c.id_user
            WHERE YEAR(b.tanggal) = '".$year."' 
                AND MONTH(b.tanggal) = '".$month."'
                AND a.acc_direktur = 'Y' 
                AND a.aktif = '1'
            GROUP BY a.id_cuti, b.tanggal
        ");

        // update di tm_jadwalpegawai_shift_m pegawai jika ada cuti yang sudah di-acc direktur
        // khusus yg buat kepegawaian, update semua shift pegawai
        while ($row = fetch_assoc($sql)) {

            bukaquery2("
                UPDATE tm_jadwalpegawai_shift_m
                SET id_absensi = '', id_absensi_tipe = '', id_ketidakhadiran = '".$row['id_ketidakhadiran']."'
                WHERE year = '".$year."'
                    AND month = '".$month."'
                    AND id_user = '".$row['id_user']."'
                    AND date = '".$row['tanggal']."'
            ");
        }

        // insert log berdasarkan list_unit
        $array_log = serialize(array(
            "keterangan" => "Jadwal Non Shift Digenerate"
        ));
        for ($i=0; $i < count($list_unit); $i++) { 
            
            bukaquery2("INSERT INTO tm_jadwalpegawai_shift_log (id_unit, month, year, data) VALUES('".$list_unit[$i]."', ".$month.", ".$year.", '".$array_log."')");
        }

        return array(
            "status" => 1,
            "message" => "Generate jadwal Non Shift berhasil"
        );
    }

    function act_submit_pengaturan_shift($id_unit, $month, $year) {

        // id_pj berdasarkan yg generate jadwal
        $sql = bukaquery2("SELECT
                a.id_penanggung_jawab, b.nama_pegawai
            FROM tm_jadwalpegawai_shift_m a
                INNER JOIN tm_pegawai b ON a.id_penanggung_jawab = b.id_user
            WHERE a.id_unit = '".$id_unit."'
                AND a.`month` = '".$month."' 
                AND a.`year` = '".$year."'
            GROUP BY a.id_unit, a.`month`, a.`year`");
        $temp_sql = fetch_array($sql);
        $id_pj = $temp_sql['id_penanggung_jawab'];
        $nm_pj = $temp_sql['nama_pegawai'];

        // ambil data sub_bagian dan id_kasatpel berdasarkan unit.
        // case tertentu dgn PJ sementara. maka diambil yg sub_bagian id_ksp nya yg paling banyak
        $sql = bukaquery2("SELECT
                a.sub_bagian, a.id_kasatpel
            FROM tm_pegawai a
            WHERE a.id_unit = '".$id_unit."'
            GROUP BY a.sub_bagian, a.id_kasatpel
            ORDER BY COUNT(a.id_user) DESC
            LIMIT 1
        ");
        $data_subbagian_ksp = fetch_array($sql);

        // ambil data ksp yg sesuai ketentuan
        $list_ksp = array();
        $sql = bukaquery2("SELECT 
                a.id_user, a.nama_pegawai, a.no_hp_wa
            FROM tm_pegawai a
                INNER JOIN tm_user b ON a.id_user = b.id_user
                INNER JOIN tm_level c ON b.id_level = c.id_level
            WHERE a.sub_bagian = '".$data_subbagian_ksp['sub_bagian']."'
                AND a.id_kasatpel = '".$data_subbagian_ksp['id_kasatpel']."'
                AND c.id_level = 'LVL-000006'
                AND a.`status` = 'AKTIF'
            ORDER BY a.nama_pegawai ASC
        ");
        while ($row = fetch_array($sql)) {    
        
            array_push($list_ksp, array(
                'id_user' => $row['id_user'],
                'nama_pegawai' => $row['nama_pegawai'],
                'no_hp_wa' => $row['no_hp_wa']
            ));
        };

        // jika ksp nya tidak ada, berarti tidak bisa divalidasi
        if(count($list_ksp) == 0) {

            return array(
                "status" => 0,
                "message" => "Data Kasatpel Anda tidak ditemukan. Hubungi Kepegawaian"
            );
        } else if(count($list_ksp) > 1) { // jika ksp nya > 1, berarti tidak bisa divalidasi karna ambigu

            return array(
                "status" => 0,
                "message" => "Data Kasatpel Anda Ditemukan Duplikat ".count($list_ksp)." data. Hubungi Kepegawaian"
            );
        } else {

            // ambil nama unit
            $sql = bukaquery2("
                SELECT 
                    a.nama_unit
                FROM tm_unit a
                WHERE a.id_unit = '".$id_unit."'
            ");
            $nama_unit = fetch_array($sql)['nama_unit'];

            //ubah flag submitted : 1, editable : 0
            bukaquery2("UPDATE tm_jadwalpegawai_shift_m 
                SET submitted = '1', editable = '0'
                WHERE id_unit = '".$id_unit."' AND month = ".$month." AND year = ".$year." "); 
                
            $id_user = $list_ksp[0]['id_user'];
            $no_hp_wa = $list_ksp[0]['no_hp_wa'];
            // $id_satpel = $this->get_idsatpel_by_iduser($id_user)['data'];

            // hapus dahulu permintaan validasi nya
            bukaquery2("DELETE FROM tm_jadwalpegawai_shift_validation WHERE `month` = '".$month."' AND `year` = '".$year."' AND id_unit = '".$id_unit."' ");

            // kirim permintaan validasi
            $this->post_shift_validation($id_unit, $month, $year, $id_pj, $id_user);

            //insert log
            $array_log = serialize(array("keterangan" => "Jadwal Shift Disubmit oleh ".$nm_pj));
            bukaquery2("INSERT INTO tm_jadwalpegawai_shift_log (id_unit, month, year, data) VALUES('".$id_unit."', ".$month.", ".$year.", '".$array_log."')");

            // kirim notifikasi wa ke satpel
            $pesan = "E-Kinerja Non-PNS RSUDTA\n\nAnda memiliki notifikasi permintaan validasi shift dari unit ".$nama_unit.".\n\n(ini adalah pesan otomatis)\n".$this->get_current_datetime()."";
            $this->send_notif_wa($no_hp_wa, $pesan);

            return array(
                "status" => 1,
                "message" => "Silahkan refresh halaman ini (A7)"
            );
        }
    }

    function act_save_jadwal_nonshift($id_kepegawaian, $month, $year) {

        $sql = bukaquery2("
            SELECT
                SUM(a.unsubmitted) AS unsubmitted
            FROM 
                ((SELECT
                    COUNT(a.id_unit) AS unsubmitted
                FROM (
                    SELECT
                        c.id_unit
                    FROM tm_komite a
                        INNER JOIN tt_komite_anggota b ON a.id_komite = b.id_komite
                        INNER JOIN tm_pegawai c ON b.id_user = c.id_user
                        INNER JOIN tm_jadwalpegawai_shift_m d ON c.id_unit = d.id_unit
                            AND d.month = '".$month."'
                            AND d.year = '".$year."'
                    GROUP BY c.id_unit
                ) AS a)
                UNION
                (SELECT COUNT(a.id_jadwalkerja_shift) AS unsubmitted
                FROM tm_jadwalpegawai_shift_m a
                WHERE a.id_unit IN (
                    SELECT
                            a.id_unit
                    FROM tm_unit a
                    WHERE a.id_petugas IN ('SHF-000004', 'SHF-000001'))
                AND a.month = '".$month."'
                AND a.year = '".$year."'
                AND a.submitted = 0
                AND a.editable = 1)
            ) AS a 
        ");

        $unsubmitted = fetch_array($sql)['unsubmitted'];

        if($unsubmitted != "0") {


            // ambil nama kepegawaian
            $sql = bukaquery2("
                SELECT a.nama_pegawai FROM tm_pegawai a WHERE a.id_user = '".$id_kepegawaian."'
            ");
            $nama_kepegawaian = fetch_array($sql)['nama_pegawai'];

            // set submitted = 1, editable = 0
            // berdasarkan tipe unit Non Shift
            bukaquery2("
                UPDATE tm_jadwalpegawai_shift_m
                SET submitted = 1, editable = 0, id_penanggung_jawab = '".$id_kepegawaian."'
                WHERE id_unit IN (
                    SELECT
                        a.id_unit
                    FROM (
                        (SELECT
                            a.id_unit
                        FROM tm_unit a
                        WHERE a.id_petugas IN ('SHF-000004', 'SHF-000001'))
                        UNION
                        (SELECT
                            c.id_unit
                        FROM tm_komite a
                            INNER JOIN tt_komite_anggota b ON a.id_komite = b.id_komite
                            INNER JOIN tm_pegawai c ON b.id_user = c.id_user
                            INNER JOIN tm_jadwalpegawai_shift_m d ON c.id_unit = d.id_unit
                            AND d.month = '".$month."'
                            AND d.year = '".$year."'
                        GROUP BY c.id_unit)
                    ) AS a
                )
                AND month = '".$month."'
                AND year = '".$year."'
            ");

            // delete fromtm_jadwalpegawai_shift_validation first
            bukaquery2("
                DELETE
                FROM tm_jadwalpegawai_shift_validation
                WHERE month = '".$month."' AND year = '".$year."'
                    AND id_unit IN (
                        SELECT
                                a.id_unit
                            FROM (
                            (SELECT
                                    a.id_unit
                            FROM tm_unit a
                            WHERE a.id_petugas IN ('SHF-000004', 'SHF-000001'))
                            UNION
                            (SELECT
                            c.id_unit
                            FROM tm_komite a
                            INNER JOIN tt_komite_anggota b ON a.id_komite = b.id_komite
                            INNER JOIN tm_pegawai c ON b.id_user = c.id_user
                            INNER JOIN tm_jadwalpegawai_shift_m d ON c.id_unit = d.id_unit
                                    AND d.month = '".$month."'
                                    AND d.year = '".$year."'
                            GROUP BY c.id_unit)
                        ) AS a
                    )
            ");

            // insert into tm_jadwalpegawai_shift_validation
            $sql = bukaquery2("
                (SELECT
                        a.id_unit
                FROM tm_unit a
                WHERE a.id_petugas IN ('SHF-000004', 'SHF-000001'))
                UNION
                (SELECT
                    c.id_unit
                FROM tm_komite a
                    INNER JOIN tt_komite_anggota b ON a.id_komite = b.id_komite
                    INNER JOIN tm_pegawai c ON b.id_user = c.id_user
                    INNER JOIN tm_jadwalpegawai_shift_m d ON c.id_unit = d.id_unit
                        AND d.month = '".$month."'
                        AND d.year = '".$year."'
                    GROUP BY c.id_unit)
            ");
            while ($row = fetch_array($sql)) {

                bukaquery2("INSERT INTO tm_jadwalpegawai_shift_validation (id_unit, month, year, id_user_sender, id_user_receiver, answered, notes, timestamp_request, timestamp_read, timestamp_answer) VALUES 
                ('".$row['id_unit']."', ".$month.", ".$year.", '".$id_kepegawaian."', '".$id_kepegawaian."', 1, '-', NOW(), NOW(), NOW())");
            }


            // insert log
            $array_log = serialize(array("keterangan" => "Jadwal Shift Disimpan oleh ".$nama_kepegawaian));
            $sql = bukaquery2("
                (SELECT
                        a.id_unit
                FROM tm_unit a
                WHERE a.id_petugas IN ('SHF-000004', 'SHF-000001'))
                UNION
                (SELECT
                    c.id_unit
                FROM tm_komite a
                    INNER JOIN tt_komite_anggota b ON a.id_komite = b.id_komite
                    INNER JOIN tm_pegawai c ON b.id_user = c.id_user
                    INNER JOIN tm_jadwalpegawai_shift_m d ON c.id_unit = d.id_unit
                        AND d.month = '".$month."'
                        AND d.year = '".$year."'
                    GROUP BY c.id_unit)
            ");
            while ($row = fetch_array($sql)) {

                bukaquery2("INSERT INTO tm_jadwalpegawai_shift_log (id_unit, month, year, data) VALUES('".$row['id_unit']."', ".$month.", ".$year.", '".$array_log."')");
            }

            return array(
                "status" => 1,
                "message" => "Silahkan refresh halaman ini"
            );

        } else {
            return array(
                "status" => 0,
                "message" => "Jadwal Pegawai Non Shift telah disubmit sebelumnya"
            );
        }
    }

    function post_shift_validation($id_unit, $month, $year, $id_user_sender, $id_user_receiver) {
        bukaquery2("INSERT INTO tm_jadwalpegawai_shift_validation (id_unit, month, year, id_user_sender, id_user_receiver, timestamp_request) VALUES 
            ('".$id_unit."', ".$month.", ".$year.", '".$id_user_sender."', '".$id_user_receiver."', NOW())");

        return array(
            "status" => 1,
            "message" => "Berhasil tambah"
        );
    }

    function post_long_shift($parent_id_jadwalkerja_shift, $id_penanggung_jawab, $id_absensi) {

        // ambil data yg dibutuhkan
        $sql = bukaquery2("
            SELECT
                a.id_unit,
                a.id_user,
                a.month,
                a.year,
                a.date
            FROM tm_jadwalpegawai_shift_m a
            WHERE a.id_jadwalkerja_shift = '".$parent_id_jadwalkerja_shift."'
        ");
        $row = fetch_array($sql);
        $id_unit = $row['id_unit'];
        $id_user = $row['id_user'];
        $month = $row['month'];
        $year = $row['year'];
        $date = $row['date'];

        // ambil tipe_shiftnya berdasarkan id_absensi terbaru
        $sql = bukaquery2("
            SELECT
                a.shift_tipe
            FROM tm_shift a
            WHERE a.id_absensi = '".$id_absensi."'
        ");
        $shift_tipe = fetch_array($sql)['shift_tipe'];

        // check tipe shift ada yg sama
        $sql = bukaquery2("
            SELECT
                IF(COUNT(a.id_absensi) > 0, false, true) AS result 
            FROM tm_jadwalpegawai_shift_m a
                INNER JOIN tm_shift b ON a.id_absensi = b.id_absensi
            WHERE a.id_unit = '".$id_unit."'
                AND a.id_user = '".$id_user."'
                AND a.month = ".$month."
                AND a.year = ".$year."
                AND a.date = '".$date."'
                AND b.shift_tipe = '".$shift_tipe."'
        ");
        $valid_long_shift = fetch_array($sql)['result'];

        // apabila sudah ada tipe_shift yg sama. maka ditolak
        if($valid_long_shift) {

            $day_name = date('l', strtotime($date));

            $sql = bukaquery2("
                SELECT SUM(a.res) AS res
                FROM (
                    (SELECT IF(COUNT(a.id_hari_raya) > 0, true, false) AS res FROM tm_hari_raya a WHERE a.tanggal = '".$date."')
                    UNION
                    (SELECT IF(COUNT(a.id_hari_libur) > 0, true, false) AS res FROM tm_hari_libur a WHERE a.tanggal = '".$date."')
                ) AS a
            ");

            $is_holiday = fetch_array($sql)['res'];

            if($is_holiday) {

                $sql = bukaquery2("SELECT 
                        b.id_absensi_tipe, b.nama_shift_tipe, b.desc_shift_tipe
                    FROM tm_shift a
                        LEFT JOIN tm_shift_tipe b ON a.shift_tipe = b.shift_tipe
                    WHERE a.id_absensi = '".$id_absensi."' 
                        AND b.shift_hari_tipe = 'RAYA'
                ");
            } else if($day_name == 'Sunday') {

                $sql = bukaquery2("SELECT 
                        b.id_absensi_tipe, b.nama_shift_tipe, b.desc_shift_tipe
                    FROM tm_shift a
                        LEFT JOIN tm_shift_tipe b ON a.shift_tipe = b.shift_tipe
                    WHERE a.id_absensi = '".$id_absensi."' 
                        AND b.shift_hari_tipe = 'LIBUR'
                ");
            } else {

                $sql = bukaquery2("SELECT 
                        b.id_absensi_tipe, b.nama_shift_tipe, b.desc_shift_tipe
                    FROM tm_shift a
                        LEFT JOIN tm_shift_tipe b ON a.shift_tipe = b.shift_tipe
                    WHERE a.id_absensi = '".$id_absensi."' 
                        AND b.shift_hari_tipe = 'KERJA'
                ");
            }
            $result = fetch_array($sql);

            $id_absensi_tipe = count($result) != 0 ? $result['id_absensi_tipe'] : null;
            $nama_shift_tipe = count($result) != 0 ? $result['nama_shift_tipe'] : null;
            $desc_shift_tipe = count($result) != 0 ? $result['desc_shift_tipe'] : null;

            // insert into tm_jadwalpegawai_shift_m
            bukaquery2("
                INSERT INTO tm_jadwalpegawai_shift_m (id_unit, month, year, id_penanggung_jawab, id_user, id_absensi, id_absensi_tipe, id_ketidakhadiran, date, created)
                VALUES
                ('".$id_unit."', '".$month."', '".$year."', '".$id_penanggung_jawab."', '".$id_user."', '".$id_absensi."', '".$id_absensi_tipe."', '', '".$date."', NOW())
            ");

            $sql = bukaquery2("
                SELECT
                    a.id_jadwalkerja_shift
                FROM tm_jadwalpegawai_shift_m a
                WHERE a.id_unit = '".$id_unit."'
                    AND a.month = ".$month."
                    AND a.year = ".$year."
                    AND a.id_user = '".$id_user."'
                    AND a.id_absensi = '".$id_absensi."'
                    AND a.date = '".$date."'
            ");
            $id_jadwalkerja_shift = fetch_array($sql)['id_jadwalkerja_shift'];

            return array(
                "status" => 1,
                "message" => "Berhasil tambah long shift",
                "update_tipe" => 1,
                "absensi_tipe" => array(
                    "id_absensi_tipe" => $id_absensi_tipe,
                    "nama_shift_tipe" => $nama_shift_tipe,
                    "desc_shift_tipe" => $desc_shift_tipe
                ),
                "id_jadwalkerja_shift" => (int) $id_jadwalkerja_shift,
                "id_penanggung_jawab" => $id_penanggung_jawab
            );

        } else {

            return array(
                "status" => 0,
                "message" => "Permintaan long shift ditolak. Sudah ada shift untuk ".$shift_tipe
            );
        }
    }

    function delete_jadwal_shift($id_unit, $month, $year) {

        // delete from tm_jadwalpegawai_shift_m
        bukaquery2("
            DELETE FROM tm_jadwalpegawai_shift_m
            WHERE id_unit = '".$id_unit."'
                AND month = '".$month."'
                AND year = '".$year."'
        ");

        // delete from tm_jadwalpegawai_shift_validation
        bukaquery2("
            DELETE FROM tm_jadwalpegawai_shift_validation
            WHERE id_unit = '".$id_unit."'
                AND month = '".$month."'
                AND year = '".$year."'
        ");

        return array(
            "status" => 1,
            "message" => "Hapus Jadwal Shift Berhasil"
        );
    }

    function get_data_pengaturan_shift($id_unit, $month, $year) {
        $result = array();
        $res_shift_options = array();
        $res_tipeshift_options = array();
        $res_ketidakhadiranshift_options = array();
        $bool_shift_editable_final = true; // default true
        $bool_shift_submitable_final = true; // default true
        $bool_shift_deleteable_final = false; // default false

        $last_date_in_month = strtotime(TanggalAkhirBulanTertentu($month, $year));
        $current_date = strtotime(TanggalSekarang());

        $sql = bukaquery2("
            SELECT
                a.id_user,
                b.nama_pegawai,
                a.id_unit,
                c.nama_unit
            FROM tm_jadwalpegawai_shift_m a
                INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                INNER JOIN tm_unit c ON a.id_unit = c.id_unit
                INNER JOIN tm_user d ON a.id_user = d.id_user
            WHERE a.id_unit = '".$id_unit."'
                AND a.month = ".$month."
                AND a.year = ".$year."
            GROUP BY a.id_user
            ORDER BY d.id_level DESC, b.nama_pegawai
        ");

        while ($row_user = fetch_assoc($sql)) {

            $temp_shift = array();

            $sql_shift = bukaquery2("
                SELECT 
                    a.id_jadwalkerja_shift, a.id_penanggung_jawab, a.id_user, b.nama_pegawai, b.id_unit, c.nama_unit, 
                    IFNULL(a.id_absensi, '') AS id_absensi, IFNULL(d.nama_shift, '') AS nama_shift, d.hex_color_shift, IFNULL(d.desc_shift, '') AS desc_shift, IFNULL(d.jam_masuk, '') AS jam_masuk, IFNULL(d.jam_pulang, '') AS jam_pulang, d.shift_tipe,
                    IFNULL(a.id_absensi_tipe, '') AS id_absensi_tipe, IFNULL(f.nama_shift_tipe, '') AS nama_shift_tipe, IFNULL(f.desc_shift_tipe, '') AS desc_shift_tipe,
                    IFNULL(a.id_ketidakhadiran, '') AS id_ketidakhadiran, IFNULL(e.nama_ketidakhadiran, '') AS nama_ketidakhadiran, e.desc_ketidakhadiran, e.hex_color_ketidakhadiran,
                    a.date
                FROM tm_jadwalpegawai_shift_m a
                    LEFT JOIN tm_pegawai b USING (id_user)
                    INNER JOIN tm_unit c ON b.id_unit = c.id_unit
                    LEFT JOIN tm_shift d ON a.id_absensi = d.id_absensi
                    LEFT JOIN tm_shift_ketidakhadiran e ON a.id_ketidakhadiran = e.id_ketidakhadiran
                    LEFT JOIN tm_shift_tipe f ON a.id_absensi_tipe = f.id_absensi_tipe
                    INNER JOIN tm_user g ON a.id_user = g.id_user
                WHERE a.id_unit = '".$id_unit."' 
                    AND a.month	 = ".$month."
                    AND a.year = ".$year."
                    AND a.id_user = '".$row_user['id_user']."'
                    ORDER BY a.date, d.shift_tipe
            ");

            while ($row_shift = fetch_assoc($sql_shift)) {
                
                array_push($temp_shift, array(
                    "id_jadwalkerja_shift" => $row_shift['id_jadwalkerja_shift'],
                    "id_penanggung_jawab" => $row_shift['id_penanggung_jawab'],
                    "id_user" => $row_shift['id_user'],
                    "nama_pegawai" => $row_shift['nama_pegawai'],
                    "id_unit" => $row_shift['id_unit'],
                    "nama_unit" => $row_shift['nama_unit'],
                    "id_absensi" => $row_shift['id_absensi'] != '' ? $row_shift['id_absensi'] : $row_shift['id_ketidakhadiran'],
                    "nama_absensi" => $row_shift['nama_shift'] != '' ? $row_shift['nama_shift'] : $row_shift['nama_ketidakhadiran'],
                    "hex_color_absensi" => $row_shift['hex_color_shift'] != '' ? $row_shift['hex_color_shift'] : $row_shift['hex_color_ketidakhadiran'],
                    "desc_absensi" => $row_shift['desc_shift'] != '' ? $row_shift['desc_shift'] : $row_shift['desc_ketidakhadiran'],
                    "id_absensi_tipe" => $row_shift['id_absensi_tipe'],
                    "nama_shift_tipe" => $row_shift['nama_shift_tipe'],
                    "desc_shift_tipe" => $row_shift['desc_shift_tipe'],
                    "shift_aktif" => $row_shift['id_absensi'] != '' && $row_shift['id_ketidakhadiran'] == '',
                    "jam_masuk" => $row_shift['jam_masuk'],
                    "jam_pulang" => $row_shift['jam_pulang'],
                    "date" => $row_shift['date'],
                    "day" => date('D', strtotime($row_shift['date']))
                ));
            }

            array_push($result, array(
                "id_user" => $row_user['id_user'],
                "nama_pegawai" => $row_user['nama_pegawai'],
                "id_unit" => $row_user['id_unit'],
                "nama_unit" => $row_user['nama_unit'],
                "shift" => $temp_shift
            ));
        }

        // PJ boleh edit shift
        // ketika editable di masternya masih true dan masih dalma bulan yg sama atau bulan selanjutnya
        $sql = bukaquery2("
            SELECT
                a.editable
            FROM tm_jadwalpegawai_shift_m a
            WHERE a.id_unit = '".$id_unit."' 
                AND a.month = ".$month." 
                AND a.year = ".$year."
            GROUP BY a.month, a.year
        ");
        $bool_shift_editable_by_master = fetch_array($sql)['editable'] == 1;
        $bool_shift_editable_by_date = $current_date < $last_date_in_month;
        $bool_shift_editable_final = $bool_shift_editable_by_master && $bool_shift_editable_by_date || true;

        // PJ boleh mengirimkan permitaan validasi shift
        // ketika masih dalam bulan yg sama atau bulan selanjutnya
        $bool_shift_submitable_final = $current_date < $last_date_in_month || true;

        // PJ boleh hapus shift
        // apabila statusnya belum disubmit atau sudah divalidasi atau sudah ditolak
        // status 0 : belum divalidasi (masih butuh validasi), 1 : sudah divalidasi, 2 : direvisi, 3 : belum disubmit
        
        $sql = bukaquery2("
            SELECT
                IFNULL(b.answered, 3) AS res
            FROM tm_jadwalpegawai_shift_m a
                LEFT JOIN tm_jadwalpegawai_shift_validation b ON a.id_unit = b.id_unit
                    AND b.month = '".$month."'
                    AND b.year = '".$year."'
            WHERE a.id_unit = '".$id_unit."'
                AND a.month = '".$month."'
                AND a.year = '".$year."'
            ORDER BY b.timestamp_request DESC
            LIMIT 1
        ");
        $res_shift_m = (int) fetch_array($sql)['res'];
        
        // hapus shift diperbolehkan apabila belum direkapitulasi
        $sql = bukaquery2("
            SELECT
                IF(COUNT(a.id_jadwalpegawai_absensi_rekap) <> 0, 0, 1) AS res
            FROM tm_jadwalpegawai_absensi_rekap a
            WHERE a.id_unit = '".$id_unit."'
                AND a.month = '".$month."'
                AND a.year = '".$year."'
        ");
        $res_rekap_absensi = (int) fetch_array($sql)['res'];

        // yg tidak boleh dihapus hanya saat status 0
        $bool_shift_deleteable_final = ($res_shift_m == 1 || $res_shift_m == 2 || $res_shift_m == 3) && $res_rekap_absensi == 1;

        // get list option shift aktif
        $sql = bukaquery2("
            SELECT
                a.id_absensi, b.nama_shift, b.desc_shift, b.hex_color_shift, b.jam_masuk, b.jam_pulang
            FROM tt_jadwalpegawai_unit_shift a
                INNER JOIN tm_shift b ON a.id_absensi = b.id_absensi
            WHERE a.id_unit = '".$id_unit."'
            ORDER BY b.shift_tipe, b.desc_shift
        ");

        while ($row = fetch_assoc($sql)) {
            array_push($res_shift_options, $row);
        }

        // get list option shift tipe
        $sql = bukaquery2("SELECT a.id_absensi_tipe, a.nama_shift_tipe, a.desc_shift_tipe
            FROM tm_shift_tipe a
            ORDER BY a.shift_tipe, a.shift_hari_tipe
        ");

        while ($row = fetch_assoc($sql)) {
            array_push($res_tipeshift_options, $row);
        }

        // get list option shift ketidakhadiran
        $sql = bukaquery2("SELECT a.id_ketidakhadiran, a.nama_ketidakhadiran, a.desc_ketidakhadiran, a.hex_color_ketidakhadiran
            FROM tm_shift_ketidakhadiran a
            WHERE a.is_show_for_pj = 1
            ORDER BY a.id_ketidakhadiran_tipe, a.nama_ketidakhadiran
        ");


        while ($row = fetch_assoc($sql)) {
            array_push($res_ketidakhadiranshift_options, $row);
        }

        return array(
            "status" => count($result) != 0 ? 1 : 0,
            "message" => count($result) != 0 ? "Data ditemukan" : "Data tidak ditemukan",
            "data" => array(
                "days_in_month" => $this->get_days_in_month($month, $year),
                "date_in_month" => $this->get_dates_in_month($month, $year),
                "data" =>$result,
                "bool_shift_editable" => $bool_shift_editable_final,
                "bool_shift_submitable" => $bool_shift_submitable_final,
                "bool_shift_deleteable" => $bool_shift_deleteable_final,
                "shift_options" => $res_shift_options,
                "tipeshift_options" => $res_tipeshift_options,
                "ketidakhadiranshift_options" => $res_ketidakhadiranshift_options
            )
        );
    }

    function get_data_pengaturan_nonshift($month, $year, $id_unit) {

        // check apakah dipilih all (unit) atau tidak berarti untuk unit non shifting saja
        if($id_unit == 'all') {

            $result = array();
            $res_shift_options = array();
            $res_tipeshift_options = array();
            $res_ketidakhadiranshift_options = array();
            $bool_shift_editable_final = true; // default true
            $bool_shift_submitable_final = true; // default true
            $last_date_in_month = strtotime(TanggalAkhirBulanTertentu($month, $year));
            $current_date = strtotime(TanggalSekarang());

            // ambil list pegawai
            // dimana ada 3 kategori
            // 1. seluruh pegawai (user, pj, kepegawaian, keuangan kecuali kasatpel) dan tipe unitnya Non-Shift yg ada di tm_jadwalpegawai_shift_m
            // 2. seluruh pegawai komite
            // 3. seluruh kasatpel yang bertipe non-pns dan aktif dari seluruh unit
            $sql = bukaquery2("
                SELECT
                    DISTINCT a.id_unit, a.nama_unit, a.id_user, a.nama_pegawai
                FROM 
                (
                    (
                        SELECT
                            DISTINCT a.id_unit, a.nama_unit, b.id_user, d.nama_pegawai
                        FROM tm_unit a
                            INNER JOIN tm_jadwalpegawai_shift_m b ON a.id_unit = b.id_unit
                            INNER JOIN tm_user c ON b.id_user = c.id_user
                            INNER JOIN tm_pegawai d ON b.id_user = d.id_user
                        WHERE a.id_petugas = 'SHF-000004'
                            AND b.month = '".$month."'
                            AND b.year = '".$year."'
                            AND c.id_level <> 'LVL-000006'
                        ORDER BY a.nama_unit, c.id_level, d.nama_pegawai
                    )
                    UNION
                    (
                        SELECT
                            c.id_unit, e.nama_unit,
                            c.id_user, d.nama_pegawai
                        FROM tm_komite a
                            INNER JOIN tt_komite_anggota b ON a.id_komite = b.id_komite
                            LEFT JOIN tm_jadwalpegawai_shift_m c ON b.id_user = c.id_user
                                AND c.month = '".$month."'
                                AND c.year = '".$year."'
                            INNER JOIN tm_pegawai d ON d.id_user = c.id_user
                            INNER JOIN tm_unit e ON e.id_unit = d.id_unit
                            INNER JOIN tm_user f ON f.id_user = c.id_user
                        WHERE a.active_status = '1'
                            AND a.full_time = '1'
                            AND b.active_status = '1'
                            AND b.full_time = '1'
                        ORDER BY e.nama_unit, f.id_level, d.nama_pegawai
                    )
                    UNION
                    (
                        SELECT
                            a.id_unit, d.nama_unit, 
                            a.id_user, b.nama_pegawai
                        FROM tm_jadwalpegawai_shift_m a
                            INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                                AND b.`status` = 'AKTIF'
                                AND b.status_pegawai <> 'PNS'
                            INNER JOIN tm_user c ON b.id_user = c.id_user
                                AND c.id_level = 'LVL-000006'
                            INNER JOIN tm_unit d ON a.id_unit = d.id_unit
                        WHERE a.month = '".$month."'
                            AND a.year = '".$year."'
                        GROUP BY a.id_user
                        ORDER BY b.nama_pegawai
                    )
                ) AS a
            ");

            while ($row_user = fetch_assoc($sql)) {

                $temp_shift = array();

                $sql_shift = bukaquery2("
                    SELECT 
                        a.id_jadwalkerja_shift, a.id_penanggung_jawab, a.id_user, b.nama_pegawai, b.id_unit, c.nama_unit, 
                        IFNULL(a.id_absensi, '') AS id_absensi, IFNULL(d.nama_shift, '') AS nama_shift, d.hex_color_shift, IFNULL(d.desc_shift, '') AS desc_shift, IFNULL(d.jam_masuk, '') AS jam_masuk, IFNULL(d.jam_pulang, '') AS jam_pulang, d.shift_tipe,
                        IFNULL(a.id_absensi_tipe, '') AS id_absensi_tipe, IFNULL(f.nama_shift_tipe, '') AS nama_shift_tipe, IFNULL(f.desc_shift_tipe, '') AS desc_shift_tipe,
                        IFNULL(a.id_ketidakhadiran, '') AS id_ketidakhadiran, IFNULL(e.nama_ketidakhadiran, '') AS nama_ketidakhadiran, e.desc_ketidakhadiran, e.hex_color_ketidakhadiran,
                        a.date
                    FROM tm_jadwalpegawai_shift_m a
                        LEFT JOIN tm_pegawai b USING (id_user)
                        INNER JOIN tm_unit c ON b.id_unit = c.id_unit
                        LEFT JOIN tm_shift d ON a.id_absensi = d.id_absensi
                        LEFT JOIN tm_shift_ketidakhadiran e ON a.id_ketidakhadiran = e.id_ketidakhadiran
                        LEFT JOIN tm_shift_tipe f ON a.id_absensi_tipe = f.id_absensi_tipe
                        INNER JOIN tm_user g ON a.id_user = g.id_user
                    WHERE a.id_unit = '".$row_user['id_unit']."' 
                        AND a.month	 = ".$month."
                        AND a.year = ".$year."
                        AND a.id_user = '".$row_user['id_user']."'
                        ORDER BY a.date, d.shift_tipe
                ");

                while ($row_shift = fetch_array($sql_shift)) {
                
                    array_push($temp_shift, array(
                        "id_jadwalkerja_shift" => $row_shift['id_jadwalkerja_shift'],
                        "id_penanggung_jawab" => $row_shift['id_penanggung_jawab'],
                        "id_user" => $row_shift['id_user'],
                        "nama_pegawai" => $row_shift['nama_pegawai'],
                        "id_unit" => $row_shift['id_unit'],
                        "nama_unit" => $row_shift['nama_unit'],
                        "id_absensi" => $row_shift['id_absensi'] != '' ? $row_shift['id_absensi'] : $row_shift['id_ketidakhadiran'],
                        "nama_absensi" => $row_shift['nama_shift'] != '' ? $row_shift['nama_shift'] : $row_shift['nama_ketidakhadiran'],
                        "hex_color_absensi" => $row_shift['hex_color_shift'] != '' ? $row_shift['hex_color_shift'] : $row_shift['hex_color_ketidakhadiran'],
                        "desc_absensi" => $row_shift['desc_shift'] != '' ? $row_shift['desc_shift'] : $row_shift['desc_ketidakhadiran'],
                        "id_absensi_tipe" => $row_shift['id_absensi_tipe'],
                        "nama_shift_tipe" => $row_shift['nama_shift_tipe'],
                        "desc_shift_tipe" => $row_shift['desc_shift_tipe'],
                        "shift_aktif" => $row_shift['id_absensi'] != '' && $row_shift['id_ketidakhadiran'] == '',
                        "jam_masuk" => $row_shift['jam_masuk'],
                        "jam_pulang" => $row_shift['jam_pulang'],
                        "date" => $row_shift['date'],
                        "day" => date('D', strtotime($row_shift['date']))
                    ));
                }

                array_push($result, array(
                    "id_user" => $row_user['id_user'],
                    "nama_pegawai" => "(".$row_user['nama_unit'].")<br><br>".$row_user['nama_pegawai'],
                    "id_unit" => $row_user['id_unit'],
                    "nama_unit" => $row_user['nama_unit'],
                    "shift" => $temp_shift
                ));
            }

            // Kepegawaian boleh edit shift
            // ketika editable di masternya masih true dan masih dalam bulan yg sama atau bulan selanjutnya
            $sql = bukaquery2("
                    SELECT
                    b.editable
                FROM tm_unit a
                    INNER JOIN tm_jadwalpegawai_shift_m b ON a.id_unit = b.id_unit
                WHERE a.id_petugas = 'SHF-000004'
                    AND b.`month` = ".$month."
                    AND b.`year` = ".$year."
                GROUP BY b.`month`, b.`year`
            ");
            $bool_shift_editable_by_master = fetch_array($sql)['editable'] == 1;
            $bool_shift_editable_by_date = $current_date < $last_date_in_month || true;
            $bool_shift_editable_final = $bool_shift_editable_by_master && $bool_shift_editable_by_date;


            // kepegawian boleh mennyimpan shiftnya
            // ketika masih dalam bulan yg sama atau bulan selanjutnya
            $bool_shift_submitable_final = $current_date < $last_date_in_month || true;

            // get list option shift aktif
            $sql = bukaquery2("SELECT a.id_absensi, a.nama_shift, a.desc_shift, a.hex_color_shift, a.jam_masuk, a.jam_pulang
                FROM tm_shift a
                ORDER BY a.shift_tipe, a.desc_shift
            ");

            while ($row = fetch_assoc($sql)) {
                array_push($res_shift_options, $row);
            }

            // get list option shift tipe
            $sql = bukaquery2("SELECT a.id_absensi_tipe, a.nama_shift_tipe, a.desc_shift_tipe
                FROM tm_shift_tipe a
                ORDER BY a.shift_tipe, a.shift_hari_tipe
            ");

            while ($row = fetch_assoc($sql)) {
                array_push($res_tipeshift_options, $row);
            }

            // get list option shift ketidakhadiran
            $sql = bukaquery2("SELECT a.id_ketidakhadiran, a.nama_ketidakhadiran, a.desc_ketidakhadiran, a.hex_color_ketidakhadiran
                FROM tm_shift_ketidakhadiran a
                WHERE a.is_show_for_pj = 1
                ORDER BY a.id_ketidakhadiran_tipe, a.nama_ketidakhadiran
            ");

            while ($row = fetch_assoc($sql)) {
                array_push($res_ketidakhadiranshift_options, $row);
            }

            return array(
                "status" => count($result) != 0 ? 1 : 0,
                "message" => count($result) != 0 ? "Data ditemukan" : "Data tidak ditemukan",
                "data" => array(
                    "days_in_month" => $this->get_days_in_month($month, $year),
                    "date_in_month" => $this->get_dates_in_month($month, $year),
                    "data" =>$result,
                    "bool_shift_editable" => $bool_shift_editable_final,
                    "bool_shift_submitable" => $bool_shift_submitable_final,
                    "bool_generateable" => true, // always true
                    "shift_options" => $res_shift_options,
                    "tipeshift_options" => $res_tipeshift_options,
                    "ketidakhadiranshift_options" => $res_ketidakhadiranshift_options
                )
            );
            
        } else {

            $result = array();
            $res_shift_options = array();
            $res_tipeshift_options = array();
            $res_ketidakhadiranshift_options = array();
            $bool_shift_editable_final = true; // default true
            $bool_shift_submitable_final = true; // default true

            $last_date_in_month = strtotime(TanggalAkhirBulanTertentu($month, $year));
            $current_date = strtotime(TanggalSekarang());

            $sql = bukaquery2("
                SELECT
                    a.id_user,
                    b.nama_pegawai,
                    a.id_unit,
                    c.nama_unit
                FROM tm_jadwalpegawai_shift_m a
                    INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                    INNER JOIN tm_unit c ON a.id_unit = c.id_unit
                    INNER JOIN tm_user d ON a.id_user = d.id_user
                WHERE a.id_unit = '".$id_unit."'
                    AND a.month = ".$month."
                    AND a.year = ".$year."
                GROUP BY a.id_user
                ORDER BY d.id_level DESC, b.nama_pegawai
            ");

            while ($row_user = fetch_assoc($sql)) {

                $temp_shift = array();
    
                $sql_shift = bukaquery2("
                    SELECT 
                        a.id_jadwalkerja_shift, a.id_penanggung_jawab, a.id_user, b.nama_pegawai, b.id_unit, c.nama_unit, 
                        IFNULL(a.id_absensi, '') AS id_absensi, IFNULL(d.nama_shift, '') AS nama_shift, d.hex_color_shift, IFNULL(d.desc_shift, '') AS desc_shift, IFNULL(d.jam_masuk, '') AS jam_masuk, IFNULL(d.jam_pulang, '') AS jam_pulang, d.shift_tipe,
                        IFNULL(a.id_absensi_tipe, '') AS id_absensi_tipe, IFNULL(f.nama_shift_tipe, '') AS nama_shift_tipe, IFNULL(f.desc_shift_tipe, '') AS desc_shift_tipe,
                        IFNULL(a.id_ketidakhadiran, '') AS id_ketidakhadiran, IFNULL(e.nama_ketidakhadiran, '') AS nama_ketidakhadiran, e.desc_ketidakhadiran, e.hex_color_ketidakhadiran,
                        a.date
                    FROM tm_jadwalpegawai_shift_m a
                        LEFT JOIN tm_pegawai b USING (id_user)
                        INNER JOIN tm_unit c ON b.id_unit = c.id_unit
                        LEFT JOIN tm_shift d ON a.id_absensi = d.id_absensi
                        LEFT JOIN tm_shift_ketidakhadiran e ON a.id_ketidakhadiran = e.id_ketidakhadiran
                        LEFT JOIN tm_shift_tipe f ON a.id_absensi_tipe = f.id_absensi_tipe
                        INNER JOIN tm_user g ON a.id_user = g.id_user
                    WHERE a.id_unit = '".$id_unit."' 
                        AND a.month	 = ".$month."
                        AND a.year = ".$year."
                        AND a.id_user = '".$row_user['id_user']."'
                        ORDER BY a.date, d.shift_tipe
                ");
    
                while ($row_shift = fetch_assoc($sql_shift)) {
                    
                    array_push($temp_shift, array(
                        "id_jadwalkerja_shift" => $row_shift['id_jadwalkerja_shift'],
                        "id_penanggung_jawab" => $row_shift['id_penanggung_jawab'],
                        "id_user" => $row_shift['id_user'],
                        "nama_pegawai" => $row_shift['nama_pegawai'],
                        "id_unit" => $row_shift['id_unit'],
                        "nama_unit" => $row_shift['nama_unit'],
                        "id_absensi" => $row_shift['id_absensi'] != '' ? $row_shift['id_absensi'] : $row_shift['id_ketidakhadiran'],
                        "nama_absensi" => $row_shift['nama_shift'] != '' ? $row_shift['nama_shift'] : $row_shift['nama_ketidakhadiran'],
                        "hex_color_absensi" => $row_shift['hex_color_shift'] != '' ? $row_shift['hex_color_shift'] : $row_shift['hex_color_ketidakhadiran'],
                        "desc_absensi" => $row_shift['desc_shift'] != '' ? $row_shift['desc_shift'] : $row_shift['desc_ketidakhadiran'],
                        "id_absensi_tipe" => $row_shift['id_absensi_tipe'],
                        "nama_shift_tipe" => $row_shift['nama_shift_tipe'],
                        "desc_shift_tipe" => $row_shift['desc_shift_tipe'],
                        "shift_aktif" => $row_shift['id_absensi'] != '' && $row_shift['id_ketidakhadiran'] == '',
                        "jam_masuk" => $row_shift['jam_masuk'],
                        "jam_pulang" => $row_shift['jam_pulang'],
                        "date" => $row_shift['date'],
                        "day" => date('D', strtotime($row_shift['date']))
                    ));
                }
    
                array_push($result, array(
                    "id_user" => $row_user['id_user'],
                    "nama_pegawai" => $row_user['nama_pegawai'],
                    "id_unit" => $row_user['id_unit'],
                    "nama_unit" => $row_user['nama_unit'],
                    "shift" => $temp_shift
                ));
            }

            // Kepegawaian boleh edit shift
            // ketika editable di masternya masih true dan masih dalma bulan yg sama atau bulan selanjutnya
            $sql = bukaquery2("
                SELECT
                    a.editable
                FROM tm_jadwalpegawai_shift_m a
                WHERE a.id_unit = '".$id_unit."' 
                    AND a.month = ".$month." 
                    AND a.year = ".$year."
                GROUP BY a.month, a.year
            ");
            $bool_shift_editable_by_master = fetch_array($sql)['editable'] == 1;
            $bool_shift_editable_by_date = $current_date < $last_date_in_month;
            $bool_shift_editable_final = $bool_shift_editable_by_master && $bool_shift_editable_by_date || true;

            // Kepegawaian boleh mengirimkan permitaan validasi shift
            // ketika masih dalam bulan yg sama atau bulan selanjutnya
            $bool_shift_submitable_final = $current_date < $last_date_in_month || true;

            // get list option shift aktif
            $sql = bukaquery2("
                SELECT
                    a.id_absensi, b.nama_shift, b.desc_shift, b.hex_color_shift, b.jam_masuk, b.jam_pulang
                FROM tt_jadwalpegawai_unit_shift a
                    INNER JOIN tm_shift b ON a.id_absensi = b.id_absensi
                WHERE a.id_unit = '".$id_unit."'
                ORDER BY b.shift_tipe, b.desc_shift
            ");

            while ($row = fetch_assoc($sql)) {
                array_push($res_shift_options, $row);
            }

            // get list option shift tipe
            $sql = bukaquery2("SELECT a.id_absensi_tipe, a.nama_shift_tipe, a.desc_shift_tipe
                FROM tm_shift_tipe a
                ORDER BY a.shift_tipe, a.shift_hari_tipe
            ");

            while ($row = fetch_assoc($sql)) {
                array_push($res_tipeshift_options, $row);
            }

            // get list option shift ketidakhadiran
            $sql = bukaquery2("SELECT a.id_ketidakhadiran, a.nama_ketidakhadiran, a.desc_ketidakhadiran, a.hex_color_ketidakhadiran
                FROM tm_shift_ketidakhadiran a
                WHERE a.is_show_for_pj = 1
                ORDER BY a.id_ketidakhadiran_tipe, a.nama_ketidakhadiran
            ");


            while ($row = fetch_assoc($sql)) {
                array_push($res_ketidakhadiranshift_options, $row);
            }

            return array(
                "status" => count($result) != 0 ? 1 : 0,
                "message" => count($result) != 0 ? "Data ditemukan" : "Data tidak ditemukan",
                "data" => array(
                    "days_in_month" => $this->get_days_in_month($month, $year),
                    "date_in_month" => $this->get_dates_in_month($month, $year),
                    "data" =>$result,
                    "bool_shift_editable" => $bool_shift_editable_final,
                    "bool_shift_submitable" => $bool_shift_submitable_final,
                    "shift_options" => $res_shift_options,
                    "tipeshift_options" => $res_tipeshift_options,
                    "ketidakhadiranshift_options" => $res_ketidakhadiranshift_options
                )
            );

        }
    }

    function get_list_pegawai_by_id_unit($id_unit) {
        $result = array();

        $sql = bukaquery2("SELECT a.id_user
        FROM tm_pegawai a 
            INNER JOIN tm_user b USING (id_user)
        WHERE a.id_unit = '".$id_unit."'
            AND a.status = 'AKTIF'
            AND a.status_pegawai <> 'PNS'
            AND (b.id_level = 'LVL-000003' OR b.id_level = 'LVL-000007')
        ORDER BY b.id_level DESC, a.sub_bagian, a.nama_pegawai");

        while ($row = fetch_array($sql)) {
            array_push($result, $row['id_user']);
        }
        
        return array(
            "status" => count($result) != 0 ? 1 : 0,
            "message" => count($result) != 0 ? "Data pegawai ditemukan" : "Data pegawai tidak ditemukan",
            "data" => $result
        );
    }

    function get_days_in_month($month, $year) {
        return (int) cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    function get_dates_in_month($month, $year) {
        $array = array();
        $period = new DatePeriod(
            new DateTime($year.'-'.$month.'-01'),
            new DateInterval('P1D'),
            new DateTime(TanggalAwalBulanSelanjutnya($month, $year))
        );

        foreach($period as $date) {
            $array[] = $date->format('Y-m-d');
        }

        return $array;
    }

    function get_shift_option() {
        $result = array();

        $sql = bukaquery2("SELECT a.id_absensi, a.nama_shift, a.jam_masuk, a.jam_pulang
            FROM tm_shift a
            ORDER BY a.jam_masuk ASC");

        while ($row = fetch_array($sql)) {
            array_push($result, array(
                "id_absensi" => $row['id_absensi'],
                "nama_shift" => $row['nama_shift'],
                "jam_masuk" => $row['jam_masuk'],
                "jam_pulang" => $row['jam_pulang']
            ));
        }

        return array(
            "status" => count($result) != 0 ? 1 : 0,
            "message" => count($result) != 0 ? "Data ditemukan" : "Data tidak ditemukan",
            "data" =>$result
        );
    }

    function get_idsatpel_by_iduser($id_user) {
        $result = array();

        $sql = bukaquery2("SELECT a.sub_bagian, a.id_kasatpel, c.level
            FROM tm_pegawai a
                INNER JOIN tm_user b ON a.id_user = b.id_user
                INNER JOIN tm_level c ON b.id_level = c.id_level
            WHERE a.id_user = '".$id_user."'"); // get pj data first
        
        $result = fetch_array($sql);

        $sql = bukaquery2("SELECT a.id_user AS id_satpel
            FROM tm_pegawai a
                INNER JOIN tm_user b ON a.id_user = b.id_user
                INNER JOIN tm_level c ON b.id_level = c.id_level
            WHERE a.sub_bagian = '".$result['sub_bagian']."'
                AND a.id_kasatpel = '".$result['id_kasatpel']."' 
                AND c.level < ".$result['level']."
            ORDER BY c.level DESC
            LIMIT 1"); // get kasatpel/satpel data based from pj data
        
        $result = fetch_array($sql);

        return array(
            "status" => count($result) != 0 ? 1 : 0,
            "message" => count($result) != 0 ? "Data ditemukan" : "Data tidak ditemukan",
            "data" => $result['id_satpel']
        );     
    }

    function get_idkasie_by_iduser($id_user) {
        $result = array();

        $id_satpel = $this->get_idsatpel_by_iduser($id_user)['data']; // get id kasatpel/kasie by pj data

        $sql = bukaquery2("SELECT a.sub_bagian, c.level
            FROM tm_pegawai a
                INNER JOIN tm_user b ON a.id_user = b.id_user
                INNER JOIN tm_level c ON b.id_level = c.id_level
            WHERE a.id_user = '".$id_satpel."'"); // get satpel data
        
        $result = fetch_array($sql);

        $sql = bukaquery2("SELECT a.id_user, a.nama_pegawai, c.level, c.nama_level
            FROM tm_pegawai a
                INNER JOIN tm_user b ON a.id_user = b.id_user
                INNER JOIN tm_level c ON b.id_level = c.id_level
            WHERE a.sub_bagian = '".$result['sub_bagian']."'
                AND c.level < ".$result['level']."
            ORDER BY c.level DESC"); // get kasie data
        
        $result = mysqli_fetch_array($sql)[0];

        return array(
            "status" => isset($result) != 0 ? 1 : 0,
            "message" => isset($result) != 0 ? "Data ditemukan" : "Data tidak ditemukan",
            "data" =>$result
        );
    }

    function get_log_pengaturan_shift($id_unit, $month, $year) {
        $result = array();

        $sql = bukaquery2("SELECT a.data, a.created
            FROM tm_jadwalpegawai_shift_log a
            WHERE a.id_unit = '".$id_unit."'
                AND a.month = ".$month."
                AND a.year = ".$year."
            ORDER BY a.created DESC");

        while ($row = fetch_array($sql)) {
            array_push($result, array(
                "log" => unserialize($row['data']),
                "created" => $row['created']
            ));
        }
        
        return array(
            "status" => count($result) != 0 ? 1 : 0,
            "message" => count($result) != 0 ? "Data log pengaturan shift ditemukan" : "Data log pengaturan shift tidak ditemukan",
            "data" => $result
        );
    }

    function get_log_pengaturan_nonshift($month, $year) {
        $result = array();

        $sql = bukaquery2("SELECT a.data, a.created
            FROM tm_jadwalpegawai_shift_log a
            WHERE a.id_unit = (SELECT
                a.id_unit
                FROM tm_unit a
                WHERE a.id_petugas = 'SHF-000004'
                LIMIT 1)
                AND a.month = ".$month."
                AND a.year = ".$year."
            ORDER BY a.created DESC");

        while ($row = fetch_array($sql)) {
            array_push($result, array(
                "log" => unserialize($row['data']),
                "created" => $row['created']
            ));
        }
        
        return array(
            "status" => count($result) != 0 ? 1 : 0,
            "message" => count($result) != 0 ? "Data log pengaturan shift ditemukan" : "Data log pengaturan shift tidak ditemukan",
            "data" => $result
        );
    }

    function get_list_permintaan_validasi_jadwalshift($id_user) {
        $result = array();

        $sql = bukaquery2("
            SELECT 
                a.id_jadwalkerja_shift_validation, a.year, a.month, b.sub_bagian, a.id_unit, c.nama_unit, a.id_user_sender, 
                b.nama_pegawai AS nama_user_sender, a.answered, a.timestamp_request
            FROM tm_jadwalpegawai_shift_validation a
                            INNER JOIN tm_pegawai b ON a.id_user_sender = b.id_user
                            INNER JOIN tm_unit c ON a.id_unit = c.id_unit
            WHERE a.id_user_receiver = '".$id_user."' AND a.answered = 0
            ORDER BY a.year DESC, a.month DESC, a.id_unit
        ");

        while ($row = fetch_array($sql)) {
            array_push($result, array(
                "id_jadwalkerja_shift_validation" => $row['id_jadwalkerja_shift_validation'],
                "id_unit" => $row['id_unit'],
                "sub_bagian" => $row['sub_bagian'],
                "nama_unit" => $row['nama_unit'],
                "id_user_sender" => $row['id_user_sender'],
                "nama_user_sender" => $row['nama_user_sender'],
                "month" => $row['month'],
                "month_name" => $this->get_month_name((int) $row['month'] - 1),
                "year" => $row['year'],
                "answered" => (int) $row['answered'],
                "timestamp_request" => $row['timestamp_request'],
                "url_detail" => paramEncrypt('module=master-data&act=detail-permintaan-validasi-jadwalshift&id_validation='.$row['id_jadwalkerja_shift_validation'].'&sft_bln_slctd='.$row['month']."&sft_year_slctd=".$row['year']."&sft_unit_slctd=".$row['id_unit'])
            ));
        }

        return array(
            "status" => count($result) != 0 ? 1 : 0,
            "message" => count($result) != 0 ? "Data ditemukan" : "Data tidak ditemukan",
            "data" =>$result
        );
    }

    function get_detail_permintaan_validasi_jadwalshift($id_unit, $month, $year) {

        $result = array();

        $sql = bukaquery2("SELECT 
                a.answered
            FROM tm_jadwalpegawai_shift_validation a
            WHERE a.id_unit = '".$id_unit."' 
                AND a.month = ".$month."
                AND a.year = '".$year."'
            ORDER BY a.timestamp_request DESC 
            LIMIT 1 
        ");
        
        $validation_status = (int) fetch_array($sql)['answered'];

        $sql = bukaquery2("
            SELECT
                a.id_user,
                b.nama_pegawai,
                a.id_unit,
                c.nama_unit
            FROM tm_jadwalpegawai_shift_m a
                INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                INNER JOIN tm_unit c ON a.id_unit = c.id_unit
            WHERE a.id_unit = '".$id_unit."'
                AND a.month = ".$month."
                AND a.year = ".$year."
            GROUP BY a.id_user
            ORDER BY b.nama_pegawai
        ");

        while ($row_user = fetch_assoc($sql)) {

            $temp_shift = array();

            $sql_shift = bukaquery2("
                SELECT 
                    a.id_jadwalkerja_shift, a.id_penanggung_jawab, a.id_user, b.nama_pegawai, b.id_unit, c.nama_unit, 
                    IFNULL(a.id_absensi, '') AS id_absensi, IFNULL(d.nama_shift, '') AS nama_shift, d.hex_color_shift, IFNULL(d.desc_shift, '') AS desc_shift, IFNULL(d.jam_masuk, '') AS jam_masuk, IFNULL(d.jam_pulang, '') AS jam_pulang, d.shift_tipe,
                    IFNULL(a.id_absensi_tipe, '') AS id_absensi_tipe, IFNULL(f.nama_shift_tipe, '') AS nama_shift_tipe, IFNULL(f.desc_shift_tipe, '') AS desc_shift_tipe,
                    IFNULL(a.id_ketidakhadiran, '') AS id_ketidakhadiran, IFNULL(e.nama_ketidakhadiran, '') AS nama_ketidakhadiran, e.desc_ketidakhadiran, e.hex_color_ketidakhadiran,
                    a.date
                FROM tm_jadwalpegawai_shift_m a
                    LEFT JOIN tm_pegawai b USING (id_user)
                    INNER JOIN tm_unit c ON b.id_unit = c.id_unit
                    LEFT JOIN tm_shift d ON a.id_absensi = d.id_absensi
                    LEFT JOIN tm_shift_ketidakhadiran e ON a.id_ketidakhadiran = e.id_ketidakhadiran
                    LEFT JOIN tm_shift_tipe f ON a.id_absensi_tipe = f.id_absensi_tipe
                    INNER JOIN tm_user g ON a.id_user = g.id_user
                WHERE a.id_unit = '".$id_unit."' 
                    AND a.month	 = ".$month."
                    AND a.year = ".$year."
                    AND a.id_user = '".$row_user['id_user']."'
                    ORDER BY a.date, d.shift_tipe
            ");

            while ($row_shift = fetch_assoc($sql_shift)) {
                
                array_push($temp_shift, array(
                    "id_jadwalkerja_shift" => $row_shift['id_jadwalkerja_shift'],
                    "id_penanggung_jawab" => $row_shift['id_penanggung_jawab'],
                    "id_user" => $row_shift['id_user'],
                    "nama_pegawai" => $row_shift['nama_pegawai'],
                    "id_unit" => $row_shift['id_unit'],
                    "nama_unit" => $row_shift['nama_unit'],
                    "id_absensi" => $row_shift['id_absensi'] != '' ? $row_shift['id_absensi'] : $row_shift['id_ketidakhadiran'],
                    "nama_absensi" => $row_shift['nama_shift'] != '' ? $row_shift['nama_shift'] : $row_shift['nama_ketidakhadiran'],
                    "hex_color_absensi" => $row_shift['hex_color_shift'] != '' ? $row_shift['hex_color_shift'] : $row_shift['hex_color_ketidakhadiran'],
                    "desc_absensi" => $row_shift['desc_shift'] != '' ? $row_shift['desc_shift'] : $row_shift['desc_ketidakhadiran'],
                    "id_absensi_tipe" => $row_shift['id_absensi_tipe'],
                    "nama_shift_tipe" => $row_shift['nama_shift_tipe'],
                    "desc_shift_tipe" => $row_shift['desc_shift_tipe'],
                    "shift_aktif" => $row_shift['id_absensi'] != '' && $row_shift['id_ketidakhadiran'] == '',
                    "jam_masuk" => $row_shift['jam_masuk'],
                    "jam_pulang" => $row_shift['jam_pulang'],
                    "date" => $row_shift['date'],
                    "day" => date('D', strtotime($row_shift['date']))
                ));
            }

            array_push($result, array(
                "id_user" => $row_user['id_user'],
                "nama_pegawai" => $row_user['nama_pegawai'],
                "id_unit" => $row_user['id_unit'],
                "nama_unit" => $row_user['nama_unit'],
                "shift" => $temp_shift
            ));
        }

        

        $sql = bukaquery2("
            SELECT
                a.id_penanggung_jawab, c.nama_pegawai,
                a.id_unit, b.nama_unit
            FROM tm_jadwalpegawai_shift_m a
                INNER JOIN tm_unit b ON a.id_unit = b.id_unit
                INNER JOIN tm_pegawai c ON a.id_penanggung_jawab = c.id_user
            WHERE a.id_unit = '".$id_unit."'
                AND a.month = ".$month."
                AND a.year = ".$year."
            LIMIT 1
        ");
        $row = fetch_array($sql);

        $nama_unit = $row['nama_unit'];
        $id_penanggung_jawab = $row['id_penanggung_jawab'];
        $nama_penanggung_jawab = $row['nama_pegawai'];

        return array(
            "status" => count($result) != 0 && isset($validation_status) ? 1 : 0,
            "message" => count($result) != 0 && isset($validation_status) ? "Data ditemukan" : "Data tidak ditemukan",
            "data" => array(
                "shift_data" => array(
                    "id_unit" => $id_unit,
                    "nama_unit" => $nama_unit,
                    "id_penanggung_jawab" => $id_penanggung_jawab,
                    "nama_penanggung_jawab" => $nama_penanggung_jawab,
                    "month" => $month,
                    "month_name" => $this->get_month_name((int) $month - 1),
                    "year" => $year,
                    "data" => $result,
                    "days_in_month" => $this->get_days_in_month($month, $year),
                    "date_in_month" => $this->get_dates_in_month($month, $year)
                ),
                "validation_status" => $validation_status,
            )
        );
    }

    function get_shift_data($id_jadwalkerja_shift) {
        
        $res_shift_data = array();
        $res_shift_option = array();
        $res_ketidakhadiran_option = array();
        $res_tipe_shift_option = array();

        //ambil data shift by id_jadwalkerja_shift
        $sql = bukaquery2("SELECT 
                a.id_absensi, a.id_absensi_tipe, a.id_ketidakhadiran, a.submitted, a.editable
            FROM tm_jadwalpegawai_shift_m a
            WHERE a.id_jadwalkerja_shift = '".$id_jadwalkerja_shift."'
        ");
        $res_shift_data = fetch_array($sql);
        
        // ambil data shift option
        $sql = bukaquery2("SELECT a.id_absensi, a.nama_shift, a.jam_masuk, a.jam_pulang, a.hex_color_shift
            FROM tm_shift a
            ORDER BY a.jam_masuk ASC");

        while ($row = fetch_array($sql)) {
            array_push($res_shift_option, array(
                "id_absensi" => $row['id_absensi'],
                "nama_shift" => $row['nama_shift'],
                "jam_masuk" => $row['jam_masuk'],
                "jam_pulang" => $row['jam_pulang'],
                "hex_color_shift" => $row['hex_color_shift']
            ));
        }

        //ambil data ketidakhadiran option
        $sql = bukaquery2("SELECT 
                a.id_ketidakhadiran, a.nama_ketidakhadiran, a.desc_ketidakhadiran, a.hex_color_ketidakhadiran
            FROM tm_shift_ketidakhadiran a
            ORDER BY a.nama_ketidakhadiran
        ");

        while ($row = fetch_array($sql)) {
            array_push($res_ketidakhadiran_option, array(
                "id_ketidakhadiran" => $row['id_ketidakhadiran'],
                "nama_ketidakhadiran" => $row['nama_ketidakhadiran'],
                "desc_ketidakhadiran" => $row['desc_ketidakhadiran'],
                "hex_color_shift" => $row['hex_color_ketidakhadiran']
            ));
        }

        //ambil data tipe shift option
        $sql = bukaquery2("SELECT 
                a.id_absensi_tipe, a.nama_shift_tipe, a.desc_shift_tipe
            FROM tm_shift_tipe a
            ORDER BY a.shift_tipe, a.nama_shift_tipe
        ");

        while ($row = fetch_array($sql)) {
            array_push($res_tipe_shift_option, array(
                "id_absensi_tipe" => $row['id_absensi_tipe'],
                "nama_shift_tipe" => $row['nama_shift_tipe'],
                "desc_shift_tipe" => $row['desc_shift_tipe']
            ));
        }

        return array(
            "status" => 1,
            "message" => "Data ditemukan",
            "data" => array(
                "shift_data" => array(
                    "id_absensi" => $res_shift_data['id_absensi'],
                    "id_absensi_tipe" => $res_shift_data['id_absensi_tipe'],
                    "id_ketidakhadiran" => $res_shift_data['id_ketidakhadiran'],
                    "submitted" => (int) $res_shift_data['submitted'],
                    "editable" => (int) $res_shift_data['editable']
                ),
                "shift_option" => $res_shift_option,
                "ketidakhadiran_option" => $res_ketidakhadiran_option,
                "tipe_shift_option" => $res_tipe_shift_option
            )
        );
    }

    function get_month_name($int_month) {
        return array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')[$int_month];
    }

    function get_rekapitulasi_shift_by_pj($id_unit, $year, $month) {

        $result = array();

        $final_query = "SELECT a.id_user AS user_id, b.nama_pegawai,";

        $sql = bukaquery2("
            SELECT
                a.shift_tipe, LOWER(a.shift_tipe) AS name
            FROM tm_shift a
            GROUP BY a.shift_tipe
            ORDER BY a.shift_tipe
        ");
        while ($row = fetch_array($sql)) {

            $final_query .= " IFNULL((
                SELECT
                    COUNT(a.id_jadwalkerja_shift)
                FROM tm_jadwalpegawai_shift_m a
                    INNER JOIN tm_shift b ON a.id_absensi = b.id_absensi
                WHERE a.id_user = user_id
                    AND a.`month` = ".$month."
                    AND a.`year` = ".$year."
                    AND a.id_unit = '".$id_unit."'
                    AND b.shift_tipe = '".$row['shift_tipe']."'
                GROUP BY b.shift_tipe
            ), 0) AS `".$row['name']."`,";
        }

        $final_query .= " IFNULL((
            SELECT
                COUNT(a.id_jadwalkerja_shift)
            FROM tm_jadwalpegawai_shift_m a
            WHERE a.id_user = user_id
                AND a.`month` = ".$month."
                AND a.`year` = ".$year."
                AND a.id_unit = '".$id_unit."'
                AND (a.id_absensi IS NULL OR a.id_absensi = '')
        ), 0) AS libur
        ";
        
        $final_query .= " FROM tm_jadwalpegawai_shift_m a
                INNER JOIN tm_pegawai b ON a.id_user = b.id_user
            WHERE a.`month` = ".$month."
                AND a.`year` = ".$year."
                AND a.id_unit = '".$id_unit."'
            GROUP BY a.id_user
            ORDER BY b.nama_pegawai
        ";

        $sql = bukaquery2($final_query);
        while ($row = fetch_assoc($sql)) {
            array_push($result, $row);
        }

        return array(
            "status" => count($result) != 0 ? 1 : 0,
            "message" => count($result) != 0 ? "Data ditemukan" : "Data tidak ditemukan",
            "data" => $result
        );
    }

    function get_list_hari_raya($month, $year) {

        $result = array();

        // ambil list hari raya
        $sql = bukaquery2("SELECT a.tanggal, a.keterangan
            FROM tm_hari_raya a
            WHERE MONTH(a.tanggal) = ".$month." AND YEAR(a.tanggal) = ".$year."
            ORDER BY a.tanggal
        ");

        while ($row = fetch_array($sql)) {
            array_push($result, array(
                
                "tanggal" => date_format(date_create($row['tanggal']), "d-m-Y"),
                "keterangan" => " Hari Raya ".$row['keterangan'],
            ));
        }

        // ambil list hari libur
        $sql = bukaquery2("SELECT a.tanggal, a.keterangan
            FROM tm_hari_libur a
            WHERE MONTH(a.tanggal) = ".$month." AND YEAR(a.tanggal) = ".$year."
            ORDER BY a.tanggal
        ");

        while ($row = fetch_array($sql)) {
            array_push($result, array(
                
                "tanggal" => date_format(date_create($row['tanggal']), "d-m-Y"),
                "keterangan" => "Hari Libur ".$row['keterangan'],
            ));
        }

        return array(
            "status" => count($result) != 0 ? 1 : 0,
            "message" => count($result) != 0 ? "Data Hari Raya Ditermukan" : "Data Hari Raya Tidak Ditermukan",
            "data" => $result
        );
    }

    function get_absensi_unit($id_unit, $month, $year) {

        $result_final = array();
        $default_minute_penalty = 255;

        $sql = bukaquery2("
            SELECT
                a.id_user
            FROM tm_jadwalpegawai_shift_m a
                INNER JOIN tm_user b ON a.id_user = b.id_user
                INNER JOIN tm_pegawai c ON a.id_user = c.id_user
            WHERE a.id_unit = '".$id_unit."'
                AND a.`month` = ".$month."
                AND a.`year` = ".$year."
            GROUP BY a.id_user
            ORDER BY b.id_level DESC, c.nama_pegawai
        ");

        while ($row = fetch_array($sql)) {
            
            $temp_sql = array();
           
            $sql_2 = bukaquery2("
                (SELECT
                    b.nama_pegawai, a.date,
                    a.id_absensi, c.desc_shift,
                    g.desc_shift_tipe,
                    IFNULL(c.jam_masuk, '') AS jam_masuk_absensi_aktif, IFNULL(c.jam_pulang, '') AS jam_pulang_absensi_aktif,
                    IFNULL(a.id_ketidakhadiran, '') AS id_ketidakhadiran, f.desc_ketidakhadiran,
                    1 AS shift_aktif,
                    IFNULL(d.tanggal, '') AS absensi_masuk, FLOOR(GREATEST(0, IFNULL(TIME_TO_SEC(TIMEDIFF(TIME(d.tanggal), TIME(c.jam_masuk)))/60, '".$default_minute_penalty."'))) AS keterlambatan, 
                    IFNULL(e.tanggal, '') AS absensi_pulang, FLOOR(GREATEST(0, IFNULL(TIME_TO_SEC(TIMEDIFF(TIME(c.jam_pulang), TIME(e.tanggal)))/60, '".$default_minute_penalty."'))) AS pulang_cepat
                FROM tm_jadwalpegawai_shift_m a
                    INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                    LEFT JOIN tm_shift c ON a.id_absensi = c.id_absensi
                    LEFT JOIN log d ON b.log_finger = d.`user`
                                    AND DATE(d.tanggal) = a.date
                                    AND TIME(d.tanggal) > SUBTIME(c.bi, '00:00:01')
                                    AND TIME(d.tanggal) < ADDTIME(c.ai, '00:30:00')
                    LEFT JOIN log e ON b.log_finger = e.`user`
                                    AND DATE(e.tanggal) = a.date
                                    AND TIME(e.tanggal) > SUBTIME(c.bo, '00:30:00')
                                    AND TIME(e.tanggal) < ADDTIME(c.ao, '00:00:01')
                    LEFT JOIN tm_shift_ketidakhadiran f ON a.id_ketidakhadiran = f.id_ketidakhadiran
                    LEFT JOIN tm_shift_tipe g ON a.id_absensi_tipe = g.id_absensi_tipe
                WHERE a.id_unit = '".$id_unit."'
                    AND a.id_user = '".$row['id_user']."'
                    AND a.`month` = ".$month."
                    AND a.`year` = ".$year."
                    AND (c.shift_tipe = 'PAGI' OR c.shift_tipe = 'SORE')
                ORDER BY a.id_unit, a.month, a.year, a.id_user, a.date, d.tanggal, e.tanggal DESC)
                UNION
                (SELECT
                    b.nama_pegawai, a.date,
                    a.id_absensi, c.desc_shift,
                    g.desc_shift_tipe,
                    IFNULL(c.jam_masuk, '') AS jam_masuk_absensi_aktif, IFNULL(c.jam_pulang, '') AS jam_pulang_absensi_aktif,
                    IFNULL(a.id_ketidakhadiran, '') AS id_ketidakhadiran, f.desc_ketidakhadiran,
                    1 AS shift_aktif,
                    IFNULL(d.tanggal, '') AS absensi_masuk, FLOOR(GREATEST(0, IFNULL(TIME_TO_SEC(TIMEDIFF(TIME(d.tanggal), TIME(c.jam_masuk)))/60, '".$default_minute_penalty."'))) AS keterlambatan, 
                    IFNULL(e.tanggal, '') AS absensi_pulang, FLOOR(GREATEST(0, IFNULL(TIME_TO_SEC(TIMEDIFF(TIME(c.jam_pulang), TIME(e.tanggal)))/60, '".$default_minute_penalty."'))) AS pulang_cepat
                FROM tm_jadwalpegawai_shift_m a
                    INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                    LEFT JOIN tm_shift c ON a.id_absensi = c.id_absensi
                    LEFT JOIN log d ON b.log_finger = d.`user`
                                    AND DATE(d.tanggal) = a.date
                                    AND TIME(d.tanggal) > SUBTIME(c.bi, '00:00:01')
                                    AND TIME(d.tanggal) < ADDTIME(c.ai, '00:30:00')
                    LEFT JOIN log e ON b.log_finger = e.`user`
                                    AND DATE(e.tanggal) = DATE_ADD(a.date, INTERVAL 1 DAY)
                                    AND TIME(e.tanggal) > SUBTIME(c.bo, '00:30:00')
                                    AND TIME(e.tanggal) < ADDTIME(c.ao, '00:00:01')
                    LEFT JOIN tm_shift_ketidakhadiran f ON a.id_ketidakhadiran = f.id_ketidakhadiran
                    LEFT JOIN tm_shift_tipe g ON a.id_absensi_tipe = g.id_absensi_tipe
                WHERE a.id_unit = '".$id_unit."'
                    AND a.id_user = '".$row['id_user']."'
                    AND a.`month` = ".$month."
                    AND a.`year` = ".$year."
                    AND c.shift_tipe = 'MALAM'
                ORDER BY a.id_unit, a.month, a.year, a.id_user, a.date, d.tanggal, e.tanggal DESC)
                UNION
                (SELECT
                    b.nama_pegawai, a.date,
                    a.id_absensi, c.desc_shift,
                    g.desc_shift_tipe,
                    IFNULL(c.jam_masuk, '') AS jam_masuk_absensi_aktif, IFNULL(c.jam_pulang, '') AS jam_pulang_absensi_aktif,
                    IFNULL(a.id_ketidakhadiran, '') AS id_ketidakhadiran, f.desc_ketidakhadiran,
                    1 AS shift_aktif,
                    IFNULL(d.tanggal, '') AS absensi_masuk, FLOOR(GREATEST(0, IFNULL(TIME_TO_SEC(TIMEDIFF(TIME(d.tanggal), TIME(c.jam_masuk)))/60, '".$default_minute_penalty."'))) AS keterlambatan, 
                    IFNULL(e.tanggal, '') AS absensi_pulang, FLOOR(GREATEST(0, IFNULL(TIME_TO_SEC(TIMEDIFF(TIME(c.jam_pulang), TIME(e.tanggal)))/60, '".$default_minute_penalty."'))) AS pulang_cepat
                FROM tm_jadwalpegawai_shift_m a
                    INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                    LEFT JOIN tm_shift c ON a.id_absensi = c.id_absensi
                    LEFT JOIN log d ON b.log_finger = d.`user`
                                    AND DATE(d.tanggal) = a.date
                                    AND TIME(d.tanggal) > SUBTIME(c.bi, '00:00:01')
                                    AND TIME(d.tanggal) < ADDTIME(c.ai, '00:30:00')
                    LEFT JOIN log e ON b.log_finger = e.`user`
                                    AND DATE(e.tanggal) = DATE_ADD(a.date, INTERVAL 1 DAY)
                                    AND TIME(e.tanggal) > SUBTIME(c.bo, '00:30:00')
                                    AND TIME(e.tanggal) < ADDTIME(c.ao, '00:00:01')
                    LEFT JOIN tm_shift_ketidakhadiran f ON a.id_ketidakhadiran = f.id_ketidakhadiran
                    LEFT JOIN tm_shift_tipe g ON a.id_absensi_tipe = g.id_absensi_tipe
                WHERE a.id_unit = '".$id_unit."'
                    AND a.id_user = '".$row['id_user']."'
                    AND a.`month` = ".$month."
                    AND a.`year` = ".$year."
                    AND c.shift_tipe IS NULL
                ORDER BY a.id_unit, a.month, a.year, a.id_user, a.date, d.tanggal, e.tanggal DESC )
                ORDER BY date, id_absensi
            ");

            while ($row_2 = fetch_assoc($sql_2)) {
                array_push($temp_sql, $row_2);
            }

            //akibat kegagalan dalam grouping data di query, maka data grouping dilakukan di aplikasi
            for ($i=0; $i < count($temp_sql); $i++) {

                //akibat kegagalan dalam grouping data di query, maka data grouping dilakukan di aplikasi
                if($i == 0 // apabila index 0
                    || $temp_sql[$i]['date'] != $temp_sql[$i == 0 ? 0 : $i-1]['date'] // apabila tanggalnya berbeda dengan sebelumnya
                    || ($temp_sql[$i]['date'] == $temp_sql[$i == 0 ? 0 : $i-1]['date'] && $temp_sql[$i]['id_absensi'] != $temp_sql[$i == 0 ? 0 : $i-1]['id_absensi']) // apabila tanggalnya samaan tapi id_absensinya beda. berarti longshift
                ) {

                    array_push($result_final, $temp_sql[$i]);
                }
            }
        }

        return array(
            "status" => count($result_final) != 0 ? 1 : 0,
            "message" => count($result_final) != 0 ? "Data Ditermukan" : "Data Tidak Ditermukan",
            "data" => $result_final
        );

    }

    function get_list_pegawai() {

        $list_pegawai = array();
        $sql = bukaquery2("
            SELECT 
                a.id_user, a.nama_pegawai
            FROM tm_pegawai a 
            WHERE a.`status` = 'AKTIF' AND a.nama_pegawai <> '' AND a.nama_pegawai <> '-'
            ORDER BY a.nama_pegawai
        ");
        while ($row = fetch_assoc($sql)) {
            
            array_push($list_pegawai, array(
                'id_user' => $row['id_user'],
                'nama_pegawai' => $row['nama_pegawai']
            ));
        }

        return array(
            "status" => count($list_pegawai) != 0 ? 1 : 0,
            "message" => count($list_pegawai) != 0 ? "Data Ditemukan" : "Data Tidak Ditermukan",
            "data" => $list_pegawai
        );
    }

    function put_jadwalshift($id_penanggungjawab, $id_jadwalkerja_shift, $id_absensi, $id_ketidakhadiran) {
        
        if($id_absensi != null && $id_ketidakhadiran == null) {
            
            // get day name by date
            $sql = bukaquery2("SELECT a.date
                FROM tm_jadwalpegawai_shift_m a
                WHERE a.id_jadwalkerja_shift = '".$id_jadwalkerja_shift."'
            ");

            $date = fetch_array($sql)['date'];

            $day_name = date('l', strtotime($date));

            $sql = bukaquery2("SELECT IF(COUNT(a.id_hari_raya) > 0, true, false) AS res 
                FROM tm_hari_raya a
                WHERE a.tanggal = '".$date."'
            ");
            $is_hariraya = fetch_array($sql)['res'];

            $sql = bukaquery2("SELECT IF(COUNT(a.id_hari_libur) > 0, true, false) AS res
                FROM tm_hari_libur a
                WHERE a.tanggal = '".$date."'
            ");
            $is_harilibur = fetch_array($sql)['res'];

            if($is_hariraya) {

                $sql = bukaquery2("SELECT 
                        b.id_absensi_tipe, b.nama_shift_tipe, b.desc_shift_tipe
                    FROM tm_shift a
                        LEFT JOIN tm_shift_tipe b ON a.shift_tipe = b.shift_tipe
                    WHERE a.id_absensi = '".$id_absensi."' 
                        AND b.shift_hari_tipe = 'RAYA'
                ");
            } else if($is_harilibur || $day_name == 'Sunday') {

                $sql = bukaquery2("SELECT 
                        b.id_absensi_tipe, b.nama_shift_tipe, b.desc_shift_tipe
                    FROM tm_shift a
                        LEFT JOIN tm_shift_tipe b ON a.shift_tipe = b.shift_tipe
                    WHERE a.id_absensi = '".$id_absensi."' 
                        AND b.shift_hari_tipe = 'LIBUR'
                ");
            } else {

                $sql = bukaquery2("SELECT 
                        b.id_absensi_tipe, b.nama_shift_tipe, b.desc_shift_tipe
                    FROM tm_shift a
                        LEFT JOIN tm_shift_tipe b ON a.shift_tipe = b.shift_tipe
                    WHERE a.id_absensi = '".$id_absensi."' 
                        AND b.shift_hari_tipe = 'KERJA'
                ");
            }
            $result = fetch_array($sql);

            $id_absensi_tipe = count($result) != 0 ? $result['id_absensi_tipe'] : null;
            $nama_shift_tipe = count($result) != 0 ? $result['nama_shift_tipe'] : null;
            $desc_shift_tipe = count($result) != 0 ? $result['desc_shift_tipe'] : null;

            bukaquery2("UPDATE tm_jadwalpegawai_shift_m SET id_absensi = '".$id_absensi."', id_absensi_tipe = '".$id_absensi_tipe."', id_ketidakhadiran = '', id_penanggung_jawab = '".$id_penanggungjawab."' 
            WHERE id_jadwalkerja_shift = '".$id_jadwalkerja_shift."'");

            return array(
                "status" => 1,
                "message" => "Berhasil update",
                "update_tipe" => 1,
                "absensi_tipe" => array(
                    "id_absensi_tipe" => $id_absensi_tipe,
                    "nama_shift_tipe" => $nama_shift_tipe,
                    "desc_shift_tipe" => $desc_shift_tipe
                )
            );
        } else if($id_absensi == null && $id_ketidakhadiran != null) {

            $sql = bukaquery2("
                SELECT
                    a.date, a.id_user, a.id_unit, a.month, a.year
                FROM tm_jadwalpegawai_shift_m a
                WHERE a.id_jadwalkerja_shift = '".$id_jadwalkerja_shift."'
            ");
            $row = fetch_array($sql);
            $date = $row['date'];
            $id_user = $row['id_user'];
            $id_unit = $row['id_unit'];
            $month = $row['month'];
            $year = $row['year'];

            bukaquery2("
                DELETE
                FROM tm_jadwalpegawai_shift_m
                WHERE id_unit = '".$id_unit."' 
                    AND month = ".$month."
                    AND year = ".$year." 
                    AND id_user = '".$id_user."' 
                    AND date = '".$date."' 
                    AND id_jadwalkerja_shift <> '".$id_jadwalkerja_shift."'
            ");

            bukaquery2("UPDATE tm_jadwalpegawai_shift_m
                SET id_absensi = '', id_absensi_tipe = '', id_ketidakhadiran = '".$id_ketidakhadiran."', id_penanggung_jawab = '".$id_penanggungjawab."'
                WHERE id_jadwalkerja_shift = '".$id_jadwalkerja_shift."'
            ");

            return array(
                "status" => 1,
                "message" => "Berhasil update",
                "update_tipe" => 0
            );
        } else {

            return array(
                "status" => 400,
                "message" => "Parameter tidak valid"
            );
        }
    }

    function put_jadwalshift_tipeshit($id_jadwalkerja_shift, $id_absensi_tipe, $id_penanggung_jawab) {

        bukaquery2("UPDATE tm_jadwalpegawai_shift_m
            SET id_absensi_tipe = '".$id_absensi_tipe."', id_penanggung_jawab = '".$id_penanggung_jawab."'
            WHERE id_jadwalkerja_shift = '".$id_jadwalkerja_shift."'
        ");

        return array(
            "status" => 1,
            "message" => "Berhasil update"
        );
    }

    function put_validation_jadwalshit($id_jadwalkerja_shift_validation, $answer, $notes, $id_penanggung_jawab, $id_user) {
        
        // ambil nomor hp PJ
        $sql = bukaquery2("
            SELECT
                a.no_hp_wa
            FROM tm_pegawai a
            WHERe a.id_user = '".$id_penanggung_jawab."'
        ");
        $no_hp_wa_pj = fetch_array($sql)['no_hp_wa'];

        //ambil data yg dibutuhkan
        $sql = bukaquery2("SELECT 
                a.id_unit, a.month, a.year, a.id_user_sender, a.id_user_receiver,
                b.nama_pegawai AS nm_validator
            FROM tm_jadwalpegawai_shift_validation a
                INNER JOIN tm_pegawai b ON a.id_user_receiver = b.id_user
                INNER JOIN tm_user c ON b.id_user = c.id_user
            WHERE a.id_jadwalkerja_shift_validation = '".$id_jadwalkerja_shift_validation."'
        ");
        $result = fetch_array($sql);

        switch ($answer) {
            case '1': //shift diterima
                //update tm_jadwalpegawai_shift_validation
                bukaquery2("UPDATE
                    tm_jadwalpegawai_shift_validation
                SET answered = ".$answer.", notes = '".$notes."', timestamp_answer = NOW()
                WHERE id_jadwalkerja_shift_validation = '".$id_jadwalkerja_shift_validation."'
                ");

                //set submitted = 1 & editable = 1
                bukaquery2("UPDATE tm_jadwalpegawai_shift_m
                    SET submitted = 1, editable = 1
                    WHERE id_unit = '".$result['id_unit']."' AND month = ".$result['month']." AND year = ".$result['year']."
                ");
                
                //masukkan ke tm_jadwalpegawai_shift_log array("keterangan" => "Jadwal Shift Divalidasi Oleh Bu A (Satpel N)" , "catatan" => "-")
                $array_log = serialize(array(
                    "keterangan" => "Jadwal Shift Divalidasi oleh ".$result['nm_validator'], 
                    "catatan" => $notes
                ));
                bukaquery2("INSERT INTO tm_jadwalpegawai_shift_log (id_unit, month, year, data) VALUES('".$result['id_unit']."', ".$result['month'].", ".$result['year'].", '".$array_log."')");

                //masukkan ke tm_jadwalpegawai_shift_notification
                $array_notification = serialize(array(
                    "unit" => $result['id_unit'],
                    "month" => $result['month'],
                    "year" => $result['year'],
                    "keterangan" => "Jadwal Shift ".$result['month']." - ".$result['year']." divalidasi"
                ));
                bukaquery2("INSERT INTO tm_jadwalpegawai_shift_notification (id_user_receiver, data) VALUES('".$id_penanggung_jawab."', '".$array_notification."')");
                break;

            case '2': //shift ditolak
                //update tm_jadwalpegawai_shift_validation
                bukaquery2("UPDATE
                    tm_jadwalpegawai_shift_validation
                SET answered = ".$answer.", notes = '".$notes."', timestamp_answer = NOW()
                WHERE id_jadwalkerja_shift_validation = '".$id_jadwalkerja_shift_validation."'
                ");

                //set submitted = 0 & editable = 1
                bukaquery2("UPDATE tm_jadwalpegawai_shift_m
                    SET submitted = 0, editable = 1
                    WHERE id_unit = '".$result['id_unit']."' AND month = ".$result['month']." AND year = ".$result['year']."
                ");
                
                //masukkan ke tm_jadwalpegawai_shift_log array("keterangan" => "Jadwal Shift Divalidasi Oleh Bu A (Satpel N)" , "catatan" => "-")
                $array_log = serialize(array(
                    "keterangan" => "Jadwal Shift Ditolak oleh ".$result['nm_validator'], 
                    "catatan" => $notes
                ));
                bukaquery2("INSERT INTO tm_jadwalpegawai_shift_log (id_unit, month, year, data) VALUES('".$result['id_unit']."', ".$result['month'].", ".$result['year'].", '".$array_log."')");

                //masukkan ke tm_jadwalpegawai_shift_notification
                $array_notification = serialize(array(
                    "unit" => $result['id_unit'],
                    "month" => $result['month'],
                    "year" => $result['year'],
                    "keterangan" => "Jadwal Shift ".$result['month']." - ".$result['year']." ditolak"
                ));
                bukaquery2("INSERT INTO tm_jadwalpegawai_shift_notification (id_user_receiver, data) VALUES('".$id_penanggung_jawab."', '".$array_notification."')");
                break;
        }
        
        // kirim notifikasi wa ke pj unit
        $pesan = "E-Kinerja Non-PNS RSUDTA\n\nAnda memiliki notifikasi shift dari ".$result['nm_validator'].".\n\n(ini adalah pesan otomatis)\n".$this->get_current_datetime()."";
        $this->send_notif_wa($no_hp_wa_pj, $pesan);

        return array(
            "status" => 1,
            "message" => "Validasi Shift oleh Kasatpel Selesai"
        );
    }

    function put_validation_timestamp_read($id_jadwalkerja_shift_validation) {

        bukaquery2("UPDATE
            tm_jadwalpegawai_shift_validation
            SET timestamp_read = NOW()
            WHERE id_jadwalkerja_shift_validation = '".$id_jadwalkerja_shift_validation."'
        ");

        return array(
            "status" => 1,
            "message" => "Berhasil update"
        );
    }

    function put_jadwalshift_clear($id_jadwalkerja_shift, $id_penanggung_jawab) {

        $sql = bukaquery2("
            SELECT
                a.id_unit, a.date, a.month, a.year, a.id_user
            FROM tm_jadwalpegawai_shift_m a
            WHERE a.id_jadwalkerja_shift = '".$id_jadwalkerja_shift."'
        ");
        $row = fetch_array($sql);
        $date = $row['date'];
        $id_unit = $row['id_unit'];
        $month = $row['month'];
        $year = $row['year'];
        $id_user = $row['id_user'];

        $sql = bukaquery2("
            SELECT
                IF(COUNT(a.id_jadwalkerja_shift) > 1, true, false) AS res
            FROM tm_jadwalpegawai_shift_m a
            WHERE a.id_unit = '".$id_unit."'
                AND a.month = ".$month."
                AND a.year = ".$year."
                AND a.id_user = '".$id_user."'
                AND a.date = '".$date."'
        ");
        $is_longshift = fetch_array($sql)['res'];

        if($is_longshift) {

            bukaquery2("
                DELETE FROM tm_jadwalpegawai_shift_m
                WHERE id_jadwalkerja_shift = '".$id_jadwalkerja_shift."'
            ");
        } else {

            bukaquery2("UPDATE tm_jadwalpegawai_shift_m
                SET id_absensi = '', id_absensi_tipe = '', id_ketidakhadiran = '', id_penanggung_jawab = '".$id_penanggung_jawab."'
                WHERE id_jadwalkerja_shift = '".$id_jadwalkerja_shift."'
            ");
        }
        
        return array(
            "status" => 1,
            "message" => "Berhasil update",
            "is_longshift" => (bool) $is_longshift
        );
    }

    function get_current_datetime() {
        return date('Y-m-d H:i:s');
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

    function invalid_action() {
        return array(
            'status' => '401',
            'message' => 'Invalid Action'
        );
    }
}
$apiMasterData = new ApiMasterData();
$action = isset($_GET['action']) ? $_GET['action'] : null; 
switch ($action) {
    case 'get_data_pengaturan_shift':
        echo json_encode($apiMasterData->get_data_pengaturan_shift(
            $_GET['id_unit'],
            $_GET['month'],
            $_GET['year']
        ));
    break;
    
    case 'get_list_pegawai_by_id_unit':
        echo json_encode($apiMasterData->get_list_pegawai_by_id_unit(
            $_GET['id_unit']
        ));
    break;
    case 'act_generate_shift':
        echo json_encode($apiMasterData->act_generate_shift(
            $_POST['id_unit'],
            $_POST['month'],
            $_POST['year'],
            $_POST['id_penanggungjawab']
        ));
    break;
    case 'act_submit_pengaturan_shift':
        echo json_encode($apiMasterData->act_submit_pengaturan_shift(
            $_POST['id_unit'],
            $_POST['month'],
            $_POST['year']
        ));
    break;
    case 'get_shift_option':
        echo json_encode($apiMasterData->get_shift_option());
    break;
    case 'put_jadwalshift':
        echo json_encode($apiMasterData->put_jadwalshift(
            $_POST['id_penanggungjawab'],
            $_POST['id_jadwalkerja_shift'],
            $_POST['id_absensi'],
            $_POST['id_ketidakhadiran']
        ));
    break;
    case 'get_idsatpel_by_iduser':
        echo json_encode($apiMasterData->get_idsatpel_by_iduser(
            $_GET['id_user']
        ));
    break;
    case 'get_idkasie_by_iduser':
        echo json_encode($apiMasterData->get_idkasie_by_iduser(
            $_GET['id_user']
        ));
    break;
    case 'post_shift_validation':
        echo json_encode($apiMasterData->post_shift_validation(
            $_POST['id_unit'],
            $_POST['month'],
            $_POST['year'],
            $_POST['id_user_sender'],
            $_POST['id_user_receiver']
        ));
    break;
    case 'get_log_pengaturan_shift':
        echo json_encode($apiMasterData->get_log_pengaturan_shift(
            $_GET['id_unit'],
            $_GET['month'],
            $_GET['year']
        ));
    break;
    case 'get_list_permintaan_validasi_jadwalshift':
        echo json_encode($apiMasterData->get_list_permintaan_validasi_jadwalshift(
            $_GET['id_user']
        ));
    break;
    case 'get_detail_permintaan_validasi_jadwalshift':
        echo json_encode($apiMasterData->get_detail_permintaan_validasi_jadwalshift(
            $_GET['id_unit'],
            $_GET['month'],
            $_GET['year']
        ));
        break;
    case 'get_shift_data':
        echo json_encode($apiMasterData->get_shift_data(
            $_GET['id_jadwalkerja_shift']
        ));
        break;
    case 'put_validation_jadwalshit':
        echo json_encode($apiMasterData->put_validation_jadwalshit(
            $_POST['id_jadwalkerja_shift_validation'],
            $_POST['answer'],
            $_POST['notes'],
            $_POST['id_penanggung_jawab'],
            $_POST['id_user']
        ));
        break;
    case 'put_validation_timestamp_read':
        echo json_encode($apiMasterData->put_validation_timestamp_read(
            $_GET['id_jadwalkerja_shift_validation']
        ));
        break;
    case 'put_jadwalshift_tipeshit':
        echo json_encode($apiMasterData->put_jadwalshift_tipeshit(
            $_POST['id_jadwalkerja_shift'],
            $_POST['id_absensi_tipe'],
            $_POST['id_penanggung_jawab']
        ));
        break;
    case 'put_jadwalshift_clear':
        echo json_encode($apiMasterData->put_jadwalshift_clear(
            $_POST['id_jadwalkerja_shift'],
            $_POST['id_penanggung_jawab']
        ));
        break;
    case 'get_rekapitulasi_shift_by_pj':
        echo json_encode($apiMasterData->get_rekapitulasi_shift_by_pj(
            $_GET['id_unit'],
            $_GET['year'],
            $_GET['month']
        ));
        break;
    case 'get_list_hari_raya':
        echo json_encode($apiMasterData->get_list_hari_raya(
            $_GET['month'],
            $_GET['year']
        ));
        break;
    case 'post_long_shift':
        echo json_encode($apiMasterData->post_long_shift(
            $_POST['parent_id_jadwalkerja_shift'],
            $_POST['id_penanggung_jawab'],
            $_POST['id_absensi']
        ));
        break;
    case 'get_absensi_unit':
        echo json_encode($apiMasterData->get_absensi_unit(
            $_GET['id_unit'],
            $_GET['month'],
            $_GET['year']
        ));
        break;
    case 'act_generate_nonshift':
        echo json_encode($apiMasterData->act_generate_nonshift(
            $_POST['id_penanggung_jawab'],
            $_POST['month'],
            $_POST['year']
        ));
        break;
    case 'get_data_pengaturan_nonshift':
        echo json_encode($apiMasterData->get_data_pengaturan_nonshift(
            $_GET['month'],
            $_GET['year'],
            $_GET['id_unit']
        ));
        break;
    case 'get_log_pengaturan_nonshift':
        echo json_encode($apiMasterData->get_log_pengaturan_nonshift(
            $_GET['month'],
            $_GET['year']
        ));
        break;
    case 'act_save_jadwal_nonshift':
        echo json_encode($apiMasterData->act_save_jadwal_nonshift(
            $_POST['id_kepegawaian'],
            $_POST['month'],
            $_POST['year']
        ));
        break;
    case 'delete_jadwal_shift':
        echo json_encode($apiMasterData->delete_jadwal_shift(
            $_POST['id_unit'],
            $_POST['month'],
            $_POST['year']
        ));
        break;
    case 'get_list_pegawai':
        echo json_encode($apiMasterData->get_list_pegawai());
        break;
    default:
        echo json_encode($apiMasterData->invalid_action());
        break;
}

?>