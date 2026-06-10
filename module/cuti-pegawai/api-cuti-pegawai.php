<?php

use Carbon\Unit;

use function PHPSTORM_META\type;

session_start();

require_once('../../conf/conf.php');
require_once('../../libs/aes-encrypt/function.php');

$rule_pengurangan_sisa_cuti_tahunan = array(
    'AKT-000012' => array( // cuti tahunan
        array(
            'month' => 1,
            'presentase_sisa_cuti' => 100
        ),
        array(
            'month' => 2,
            'presentase_sisa_cuti' => 50
        )
    )
);

error_reporting(1);       // Nonaktifkan semua error
ini_set('display_errors', 1);  // Jangan tampilkan error ke browser

class ApiCutiPegawai
{

    function get_detail_pegawai($id_user, $sub_bagian, $id_unit)
    {
        $sub_bagian = str_replace('_', ' ', $sub_bagian);

        $sql = bukaquery2("
            SELECT
                a.nip, a.nama_pegawai, a.id_unit, a.tgl_masuk, a.no_hp_wa, a.alamat,
                b.nama_unit, d.nm_jabatan
            FROM tm_pegawai a
                INNER JOIN tm_unit b ON a.id_unit = b.id_unit
                LEFT JOIN tt_jabatan_pegawai c ON a.id_user = c.id_user
                LEFT JOIN tm_jabatan d ON c.id_jabatan = d.id_jabatan
            WHERE a.id_user = '" . $id_user . "'
        ");
        $res_data_pegawai = fetch_array($sql);

        // unit yang jumlah pegawainya hanya 1
        // UNT-000022 ATEM
        // UNT-000026 PETUGAS KAMAR JENAZAH
        // UNT-000039 PETUGAS GAS MEDIK
        $unit_sepi = ['UNT-000022', 'UNT-000026', 'UNT-000039'];

        $list_pengganti_cuti = array();

        if (in_array($id_unit, $unit_sepi) !== false) {
            $sql = bukaquery2("select id_user, nama_pegawai from tm_pegawai where id_unit in ('UNT-000022', 'UNT-000026', 'UNT-000039') and id_user != '$id_user' and status = 'aktif'");
            while ($res = fetch_array($sql)) {
                array_push($list_pengganti_cuti, array(
                    "id_user" => $res['id_user'],
                    "nama_pegawai" => $res['nama_pegawai']
                ));
            }
        } else if ($_SESSION['level'] == 'kasatpel') {
            $sql = bukaquery2("SELECT tm_pegawai.id_user, nama_pegawai FROM tm_pegawai INNER JOIN tm_user ON tm_pegawai.id_user = tm_user.id_user INNER JOIN tm_level ON tm_user.id_level = tm_level.id_level  WHERE tm_pegawai.`status` = 'aktif'  AND tm_level.nama_level = 'kasatpel' AND tm_pegawai.id_user <> $id_user  ORDER BY nama_pegawai");
            while ($res = fetch_array($sql)) {
                array_push($list_pengganti_cuti, array(
                    "id_user" => $res['id_user'],
                    "nama_pegawai" => $res['nama_pegawai']
                ));
            }
        } else {
            $sql = bukaquery2("
                SELECT 
                    a.id_user, a.nama_pegawai 
                FROM tm_pegawai a
                    INNER JOIN tm_user b ON a.id_user = b.id_user
                    INNER JOIN tm_level c ON b.id_level = c.id_level
                WHERE 
                    a.status = 'AKTIF'
                    AND a.id_user != '$id_user'
                ORDER BY c.`level` DESC, a.nama_pegawai
            ");
            while ($res = fetch_array($sql)) {
                array_push($list_pengganti_cuti, array(
                    "id_user" => $res['id_user'],
                    "nama_pegawai" => $res['nama_pegawai']
                ));
            }
        }


        $list_pj = array();
        if (in_array($id_unit, $unit_sepi) !== false) {
            $sql = bukaquery2("select id_user, nama_pegawai from tm_pegawai where id_unit = 'UNT-000022' and status = 'aktif'");
            while ($res = fetch_array($sql)) {
                array_push($list_pj, array(
                    "id_user" => $res['id_user'],
                    "nama_pegawai" => $res['nama_pegawai']
                ));
            }
        } else if ($_SESSION['level'] == 'kasatpel') {
            $sql = bukaquery2("SELECT tm_pegawai.id_user, nama_pegawai FROM tm_pegawai INNER JOIN tm_user ON tm_pegawai.id_user = tm_user.id_user INNER JOIN tm_level ON tm_user.id_level = tm_level.id_level  WHERE tm_pegawai.`status` = 'aktif'  AND tm_level.nama_level = 'kasatpel'  ORDER BY nama_pegawai");
            while ($res = fetch_array($sql)) {
                array_push($list_pj, array(
                    "id_user" => $res['id_user'],
                    "nama_pegawai" => $res['nama_pegawai']
                ));
            }
        } else {
            $sql = bukaquery2("
                SELECT
                    a.id_user, a.nama_pegawai
                FROM tm_pegawai a
                    INNER JOIN tm_user b ON a.id_user = b.id_user
                    INNER JOIN tm_level c ON b.id_level = c.id_level AND c.level >= '3'
                WHERE a.status = 'AKTIF'
                ORDER BY c.`level`, a.nama_pegawai
            ");
            while ($row = fetch_array($sql)) {
                array_push($list_pj, array(
                    'id_user' => $row['id_user'],
                    'nama_pegawai' => $row['nama_pegawai']
                ));
            }
        }


        // Unit bawahan umum
        // UNT-000028 PETUGAS KEAMANAN
        // UNT-000027 CLEANING SERVICE
        // UNT-000009 DRIVER
        $unit_tambahan_umum = ['UNT-000028', 'UNT-000027', 'UNT-000009'];

        if (in_array($id_unit, $unit_tambahan_umum) !== false) {
            $sql = bukaquery2("select id_user, nama_pegawai from tm_pegawai where id_user = '2309040001' and status = 'aktif'");
            while ($res = fetch_array($sql)) {
                array_push($list_pj, array(
                    "id_user" => $res['id_user'],
                    "nama_pegawai" => $res['nama_pegawai']
                ));
            }
        }


        $list_ksp = array();
        // level kasatpel
        $sql = bukaquery2("
        SELECT
            a.id_user,
            a.nama_pegawai 
        FROM
            tm_pegawai a
            INNER JOIN tm_user b ON a.id_user = b.id_user
            INNER JOIN tm_level c ON b.id_level = c.id_level 
            AND c.nama_level = 'KASATPEL'
            INNER JOIN tm_pegawai d ON a.id_kasatpel = d.id_kasatpel
            INNER JOIN tm_user e ON d.id_user = e.id_user
            INNER JOIN tm_level f ON e.id_level = f.id_level 
        WHERE
            a.`status` = 'AKTIF' 
            AND b.id_level = 'LVL-000006' 
            AND d.id_user = '$id_user' 
        ORDER BY
            a.nama_pegawai
        ");
        while ($row = fetch_array($sql)) {
            array_push($list_ksp, array(
                "id_user" => $row['id_user'],
                "nama_pegawai" => $row['nama_pegawai']
            ));
        }

        $list_kasie = array();
        $sql = bukaquery2("
            SELECT
                a.id_user,
                a.nama_pegawai 
            FROM
                tm_pegawai a
                INNER JOIN tm_user b ON a.id_user = b.id_user
                INNER JOIN tm_level c ON b.id_level = c.id_level 
                AND ( c.nama_level = 'KASIE' OR c.nama_level = 'KASUBBAG TU' )
                INNER JOIN tm_pegawai d ON a.sub_bagian = d.sub_bagian
                INNER JOIN tm_user e ON d.id_user = e.id_user
                INNER JOIN tm_level f ON e.id_level = f.id_level 
            WHERE
                a.`status` = 'AKTIF' 
                and d.`status` = 'AKTIF' 
                AND d.id_user = '$id_user' 
            ORDER BY
                a.nama_pegawai
        ");
        while ($row = fetch_array($sql)) {
            array_push($list_kasie, array(
                "id_user" => $row['id_user'],
                "nama_pegawai" => $row['nama_pegawai']
            ));
        }

        $list_ktu = array();
        $sql = bukaquery2("
            SELECT
                a.id_user, a.nama_pegawai, a.id_unit,
                c.id_level, c.`level`, c.nama_level
            FROM tm_pegawai a
                INNER JOIN tm_user b ON a.id_user = b.id_user
                INNER JOIN tm_level c ON b.id_level = c.id_level AND c.nama_level = 'KASUBBAG TU'
            WHERE a.`status` = 'AKTIF'
            ORDER BY c.`level`
        ");
        while ($row = fetch_array($sql)) {
            array_push($list_ktu, array(
                "id_user" => $row['id_user'],
                "nama_pegawai" => $row['nama_pegawai']
            ));
        }

        $list_direktur = array();
        $sql = bukaquery2("
            SELECT
                a.id_user, a.nama_pegawai, a.id_unit,
                c.id_level, c.`level`, c.nama_level
            FROM tm_pegawai a
                INNER JOIN tm_user b ON a.id_user = b.id_user
                INNER JOIN tm_level c ON b.id_level = c.id_level
            WHERE a.`status` = 'AKTIF'
                AND b.id_level = 'LVL-000011'
            ORDER BY c.`level`
        ");
        while ($row = fetch_array($sql)) {
            array_push($list_direktur, array(
                "id_user" => $row['id_user'],
                "nama_pegawai" => $row['nama_pegawai']
            ));
        }

        if (!isset($res_data_pegawai)) {
            return array(
                'status' => 0,
                'message' => 'Data tidak ditemukan'
            );
        } else {

            return array(
                'status' => 1,
                'message' => 'data ditemukan',
                'data' => array(
                    "data_pegawai" => array(
                        'nip' => $res_data_pegawai['nip'],
                        'nama_pegawai' => $res_data_pegawai['nama_pegawai'],
                        'alamat' => $res_data_pegawai['alamat'],
                        'nm_unit' => $res_data_pegawai['nama_unit'],
                        'no_hp_wa' => $res_data_pegawai['no_hp_wa'],
                        'tgl_masuk' => $res_data_pegawai['tgl_masuk'],
                        'nm_jabatan' => $res_data_pegawai['nm_jabatan'],
                    ),
                    "opsi_pengganti_cuti" => $list_pengganti_cuti,
                    "opsi_pj" => $list_pj,
                    "list_ksp" => $list_ksp,
                    "opsi_kasie" => $list_kasie,
                    "opsi_ktu" => $list_ktu,
                    "opsi_direktur" => $list_direktur
                )
            );
        }
    }

    function get_opsi_cuti_pegawai($id_user, $selected_year, $current_year, $current_month, $jns_cuti)
    {
        $opsi_cuti = array();
        $opsi_cuti_temp = array();

        $jns_cuti = empty($jns_cuti) ? null : "  ";
        $sql = bukaquery("
            SELECT
                a.id_ketidakhadiran AS ketidakhadiran_id, 
                a.desc_ketidakhadiran, 
                LOWER(a.nama_ketidakhadiran) AS nama_ketidakhadiran_lower,
                '0' AS kuota_cuti,
                IFNULL((SELECT SUM(a.jumlah_hari) FROM tm_cuti a WHERE a.id_user = '" . $id_user . "' AND a.tahun_cuti = '" . $selected_year . "' AND a.id_ketidakhadiran = ketidakhadiran_id), 0) AS jml_cuti
            FROM tm_shift_ketidakhadiran a
            WHERE a.is_show_for_cuti_options <> '0'
            ORDER BY a.is_show_for_cuti_options
        ");
        while ($row = fetch_assoc($sql)) array_push($opsi_cuti_temp, $row);

        foreach ($opsi_cuti_temp as $value) {

            $klausa_tambahan = $value['ketidakhadiran_id'] == 'AKT-000012' ? "AND (id_ketidakhadiran = 'AKT-000012' OR id_ketidakhadiran = 'AKT-000018')" : "AND id_ketidakhadiran = '$value[ketidakhadiran_id]'";

            $sql = bukaquery2("
                SELECT
                    tahun_cuti,
                    tm_hari_cuti.tanggal hari_cuti
                FROM tm_cuti
                INNER JOIN tm_hari_cuti ON tm_cuti.id_cuti = tm_hari_cuti.id_cuti  
                WHERE
                    id_user = '$id_user' 
                    AND tm_cuti.aktif = 1
                    AND tahun_cuti = '$selected_year'
                    $klausa_tambahan
            ");
            $sisa_cuti = [];
            while ($data = fetch_assoc($sql)) {
                $date = new DateTime($data['hari_cuti']);
                $bln = $date->format('n');

                if ($bln > 2)
                    array_push($sisa_cuti, $data['hari_cuti']);
            }

            usort($sisa_cuti, function ($a, $b) {
                return strtotime($a) - strtotime($b);
            });

            $sisa_cuti = $value['kuota_cuti'] - count($sisa_cuti);

            array_push($opsi_cuti, array(
                'id_ketidakhadiran' => $value['ketidakhadiran_id'],
                'desc_ketidakhadiran' => $value['desc_ketidakhadiran'],
                'tahun_cuti' => $selected_year,
                'sisa_cuti' => $sisa_cuti
            ));
        }

        return array(
            "status" => 1,
            "message" => "Data ditemukan",
            "data" => array(
                "opsi_cuti" => $opsi_cuti
            )
        );
    }

    /**
     * @description untuk mendapatkan sisa (angka) cuti pegawai 
     * @param id_ketidakhadiran, id_user, tahun
     */
    function get_sisa_cuti_pegawai_by_opsi($id_ketidakhadiran, $id_user, $selected_year, $current_year, $current_month)
    {

        global $rule_pengurangan_sisa_cuti_tahunan;
        $presentase_sisa_cuti = 1;

        // perhitungan total cuti, akan bergantung pada kalkulasi dibawah
        if ($selected_year != $current_year) {

            foreach ($rule_pengurangan_sisa_cuti_tahunan as $key => $pengurangan_cuti_month) {

                if ($key == $id_ketidakhadiran) {

                    foreach ($pengurangan_cuti_month as $pengurangan_cuti) {

                        // jika bulan yang dipilih user, ada di pengaturan ya ambil
                        if ($pengurangan_cuti['month'] == (int) $current_month) {
                            $presentase_sisa_cuti = ($pengurangan_cuti['presentase_sisa_cuti'] / 100);
                            continue 2; // keluar loop
                        }

                        // jika tidak ada, jadi 0 yangartinya sudah hangus
                        $presentase_sisa_cuti = 0;
                    }
                }
            }
        }

        // hitung total kuota cuti pegawai dari tm_kuota_cuti yang diinput kepegawaian
        // berdasarkan tahun dan id_ketidakhadiran
        // jumlah kuota juga dipengaruhi oleh $presentase_sisa_cuti
        $sql = bukaquery("
            SELECT
                SUM(a.kuota_cuti) AS total_kuota
            FROM tm_kuota_cuti AS a
            WHERE a.id_user = '".$id_user."'
            AND a.id_ketidakhadiran = '".$id_ketidakhadiran."'
            AND (YEAR(a.range_cuti_start) = '".$selected_year."' OR YEAR(a.range_cuti_end) = '".$selected_year."')
        ");
        $total_kuota = floor((int) fetch_array($sql)['total_kuota'] * $presentase_sisa_cuti);

        // hitung cuti yang sudah diambil oleh user di tm_cuti dan tm_hari_cuti
        $sql = bukaquery("
            SELECT
                COUNT(a.id_cuti) AS total_cuti
            FROM tm_cuti AS a
                LEFT JOIN tm_hari_cuti AS b ON a.id_cuti = b.id_cuti
            WHERE a.id_user = '".$id_user."'
                AND a.id_ketidakhadiran = '".$id_ketidakhadiran."'
                AND a.tahun_cuti = '".$selected_year."'
        ");
        $total_cuti = (int) fetch_array($sql)['total_cuti'];

        // sisa cuti
        $sisa_cuti = floor($total_kuota - $total_cuti);

        $desc_ketidakhadiran = fetch_array(bukaquery("
            SELECT
                a.desc_ketidakhadiran
            FROM tm_shift_ketidakhadiran AS a
            WHERE a.id_ketidakhadiran = '".$id_ketidakhadiran."'
        "))['desc_ketidakhadiran'];

        return array(
            "status" => 1,
            "message" => "Data ditemukan",
            "data" => array(
                "opsi_cuti" => array(
                    'id_ketidakhadiran' => $id_ketidakhadiran,
                    'desc_ketidakhadiran' => $desc_ketidakhadiran,
                    'kuota_cuti' => $total_kuota,
                    'sisa_cuti' => $sisa_cuti
                )
            )
        );
        exit;
        
        
        // DIBAWAH INI KODINGAN HABIP 

        // 
        global $rule_pengurangan_sisa_cuti_tahunan;

        $opsi_cuti = array();
        $opsi_cuti_temp = bukaquery("
            SELECT
                a.id_ketidakhadiran AS ketidakhadiran_id, 
                a.desc_ketidakhadiran, 
                a.count_cuti AS kuota_cuti, 
                IFNULL((SELECT SUM(a.jumlah_hari) FROM tm_cuti a WHERE a.id_user = '" . $id_user . "' AND a.tahun_cuti = '" . $selected_year . "' AND a.id_ketidakhadiran = ketidakhadiran_id
                ), 0) AS jml_cuti
            FROM tm_shift_ketidakhadiran a
            WHERE a.is_show_for_cuti_options <> 0
                AND a.id_ketidakhadiran = '" . $id_ketidakhadiran . "'
            ORDER BY a.desc_ketidakhadiran
        ");


        // Dapetin tgl awal dan tgl akhir dari periode cuti 90 hari
        $kuota_cpcb = 0;
        $kuota_cpcb_terpakai = 0;
        $pc = [];
        $list_tgl_cpcb = [];
        if ($id_ketidakhadiran == 'AKT-000017') {

            // hitung jumlah cpcb yang udah diambil
            $q = "
                SELECT count(tm_cuti.id_cuti) count
                FROM tm_cuti
                INNER JOIN tm_hari_cuti ON tm_cuti.id_cuti = tm_hari_cuti.id_cuti
                WHERE
                    id_user = '$id_user' 
                    AND id_ketidakhadiran = 'AKT-000017' 
                    AND tahun_cuti = year(now())
            ";
            $q = bukaquery2($q);
            $hari_cuti = fetch_assoc($q);

            // jumlah cpcb yang udah diambil
            $kuota_cpcb_terpakai = $hari_cuti['count'];


            $hari_liburs = bukaquery2("
                SELECT
                    tanggal
                FROM
                    (
                    SELECT
                        tanggal 
                    FROM
                        tm_hari_libur 
                    WHERE
                        YEAR ( tanggal ) = YEAR (
                        now()) 
                        AND tanggal BETWEEN now() - INTERVAL 61 DAY 
                        AND now() 
                        AND cuti_bersama = 'ya' 
                    ) AS a UNION
                    (
                    SELECT
                        tm_hari_libur.tanggal 
                    FROM
                        tm_hari_libur 
                    WHERE
                        YEAR ( tanggal ) = YEAR (
                        now()) 
                        AND tanggal BETWEEN now() - INTERVAL 61 DAY 
                    AND now()) 
                ORDER BY
                    tanggal
            ");

            $list_tgl_cpcb = [];
            while ($hari_libur = fetch_assoc($hari_liburs)) {
                $jml_cuti = 0;
                $jml_log = 0;

                $tanggal = new DateTime($hari_libur['tanggal']);
                $tahun = $tanggal->format('Y');
                $bulan = $tanggal->format('n');
                $hari = $tanggal->format('j');

                $q = "
                    SELECT count(tanggal) jml_log 
                    FROM tm_pegawai
                    INNER JOIN log ON tm_pegawai.log_finger = log.USER
                    WHERE
                        tm_pegawai.id_user = '$id_user' 
                        AND YEAR ( tanggal ) = '$tahun' 
                        AND MONTH ( tanggal ) = '$bulan' 
                        AND DAY ( tanggal ) = '$hari'
                ";

                $jml_log = bukaquery2($q);
                $jml_log = fetch_assoc($jml_log)['jml_log'];

                if ($jml_log > 0)
                    $list_tgl_cpcb[] = $tanggal->format('Y-m-d');

                $kuota_cpcb = $jml_log > 0 ? $kuota_cpcb + 1 : $kuota_cpcb;
            }
        }
        $list_tgl_cpcb = implode('<br>', $list_tgl_cpcb);
        // prd($list_tgl_cpcb);





        foreach ($opsi_cuti_temp as $value) {

            if ($id_ketidakhadiran == 'AKT-000017')
                $value['kuota_cuti'] = $value['kuota_cuti'] + $kuota_cpcb - $kuota_cpcb_terpakai;
            else {
                $sql = bukaquery2("
                    SELECT tm_hari_cuti.tanggal hari_cuti
                    FROM tm_cuti 
                    INNER JOIN tm_hari_cuti ON tm_cuti.id_cuti = tm_hari_cuti.id_cuti
                    WHERE
                        id_user = '$id_user' 
                        AND tahun_cuti = '$selected_year'
                        AND id_ketidakhadiran = '$id_ketidakhadiran'
                ");
                $sisa_cuti = [];
                while ($data = fetch_assoc($sql)) {
                    array_push($sisa_cuti, $data['hari_cuti']);
                }

                usort($sisa_cuti, function ($a, $b) {
                    return strtotime($a) - strtotime($b);
                });

                $value['sisa_cuti'] = $value['kuota_cuti'] - count($sisa_cuti);
            }

            $presentase_sisa_cuti = 100;
            // jika tahun yg dipilih < tahun saat ini,
            // berarti mencari cuti di tahun sebelumnya
            if ($selected_year < $current_year) {

                foreach ($rule_pengurangan_sisa_cuti_tahunan as $key => $pengurangan_cuti_month) {
                    if ($key == $value['ketidakhadiran_id']) {

                        foreach ($pengurangan_cuti_month as $pengurangan_cuti) {

                            if ($pengurangan_cuti['month'] == $current_month) {
                                $presentase_sisa_cuti = $pengurangan_cuti['presentase_sisa_cuti'];
                            }
                        }
                    }
                }
            }

            array_push($opsi_cuti, array(
                'id_ketidakhadiran' => $value['ketidakhadiran_id'],
                'desc_ketidakhadiran' => $value['desc_ketidakhadiran'],
                'kuota_cuti' => $value['kuota_cuti'],
                'sisa_cuti' => $selected_year == $current_year
                    ? (int) $value['kuota_cuti'] - (int) $value['jml_cuti']
                    : (
                        $selected_year < $current_year && $presentase_sisa_cuti != 100
                        ? FLOOR(((int) $value['kuota_cuti'] - (int) $value['jml_cuti']) * ($presentase_sisa_cuti / 100))
                        : (int) $value['kuota_cuti'] - (int) $value['jml_cuti']
                    ),
            ));
        }

        // Jika yang diajukan bukan cuti tahunan maka sisa cuti = kuota cuti
        $opsi_cuti[0]['sisa_cuti'] = $opsi_cuti[0]['id_ketidakhadiran'] != 'AKT-000012' ? $opsi_cuti[0]['kuota_cuti'] : $opsi_cuti[0]['sisa_cuti'];

        return array(
            "status" => 1,
            "message" => "Data ditemukan",
            "data" => array(
                "opsi_cuti" => $opsi_cuti
            )
        );
    }

    function post_pengajuan_cuti($id_user, $list_tgl_cuti, $periode_thn_cuti, $id_ketidakhadiran, $sisa_cuti, $alasan_cuti, $alamat_cuti, $nohp_cuti, $iduser_pengganti, $iduser_pj, $iduser_ksp, $iduser_kasie, $iduser_ktu, $iduser_direktur, $aksi_form, $id_cuti_lama)
    {
        global $url_api_notif_wa;
        $list_tgl_cuti = str_replace('/', '-', $list_tgl_cuti);
        
        $ltc = explode(',', $list_tgl_cuti);
        foreach ($ltc as $key => $value) {
            $ltc_temp = DateTime::createFromFormat('m-d-Y', $value);
            $ltc[$key] = $ltc_temp->format('d-m-Y');
        }
        $list_tgl_cuti = implode(',', $ltc);

        // check dlu apakah sisa cuti mencukupi permintaan dari post value nya
        if ($id_ketidakhadiran != 'AKT-000018' 
            && $id_ketidakhadiran != 'AKT-000005' 
            && count(explode(",", $list_tgl_cuti)) > $sisa_cuti
            && $sisa_cuti != "∞" // jika susa cuti unlimited, skip
        ) {

            return array(
                "status" => 0,
                "message" => "Sisa Cuti tidak mencukupi. Kode : 1"
            );
        } else {

            // check dulu apakah cuti yg diambil di tanggal krusial. karna di tanggal krusial, jumlah cuti nya dibatasi
            // cuti yg dihitung hanya yg sudah diacc pj dan belum diacc pj
            $kuota_cuti_sudah_penuh = false;
            $tgl_kuota_cuti_sudah_penuh = "";
            $list_tgl_cuti_temp = "";
            $list_tgl_cuti_temp_2 = explode(",", $list_tgl_cuti);
            for ($i = 0; $i < count($list_tgl_cuti_temp_2); $i++) {
                $abc_temp = new DateTime($list_tgl_cuti_temp_2[$i]);
                $abc_temp = $abc_temp->format('Y-m-d');

                $list_tgl_cuti_temp .= "'" . $abc_temp . "'";
                if ($i + 1 < count($list_tgl_cuti_temp_2)) $list_tgl_cuti_temp .= ",";
            }

            $sql = bukaquery2("
                SELECT
                    a.tanggal AS tgl_hari, a.kuota,
                    (
                        SELECT COUNT(a.id_cuti)
                        FROM tm_hari_cuti a INNER JOIN tm_cuti b ON a.id_cuti = b.id_cuti
                        WHERE a.tanggal = tgl_hari AND b.acc_pj <> 'T'
                    ) AS jml_pengajuan_cuti
                FROM tm_hari_kuota_cuti a
                WHERE a.tanggal IN (" . $list_tgl_cuti_temp . ")
            ");
            while ($row = fetch_array($sql))
                if ($row['kuota'] < $row['jml_pengajuan_cuti'] + 1) {
                    $tgl_kuota_cuti_sudah_penuh = $row['tgl_hari'];
                    $kuota_cuti_sudah_penuh = true;
                }

            if ($kuota_cuti_sudah_penuh) {

                return array(
                    'status' => 0,
                    'message' => 'Kuota Cuti tertanggal ' . $this->change_format_date($tgl_kuota_cuti_sudah_penuh, 'd-m-Y') . " sudah penuh !!"
                );
            } else {

                // check juga sisa cuti nya dari database. untuk mencegah double pengajuan cuti
                $res_sisa_cuti = $this->get_sisa_cuti_pegawai_by_opsi($id_ketidakhadiran, $id_user, $periode_thn_cuti, $this->change_format_date($list_tgl_cuti, "Y"), $this->change_format_date($list_tgl_cuti, "n"));

                // $sisa_cuti_ku = $res_sisa_cuti['data']['opsi_cuti'][0]['id_ketidakhadiran'] == 'AKT-000018' ? 10 : $res_sisa_cuti['data']['opsi_cuti'][0]['sisa_cuti'];
                // $sisa_cuti_ku = $res_sisa_cuti['data']['opsi_cuti'][0]['id_ketidakhadiran'] == 'AKT-000005' ? 90 : $sisa_cuti_ku;

                if ($res_sisa_cuti['status'] != 1 && false) {

                    return array(
                        "status" => 0,
                        "message" => "Cari data Sisa Cuti error. Hubungi SIMRS"
                    );
                } else if (count(explode(",", $list_tgl_cuti)) > $sisa_cuti_ku && false) {

                    return array(
                        "status" => 0,
                        "message" => "Sisa Cuti tidak mencukupi. Kode : 2"
                    );
                } else {

                    $id_cuti = notahun('id_cuti', 'tm_cuti');
                    $tgl_permohonan = date('Y-m-d'); // tanggal permohonan cuti diambil di hari permintaan cuti dibuat

                    $tgl_cuti_explode = explode(',', $list_tgl_cuti);
                    sort($tgl_cuti_explode);
                    $list_tgl_cuti = implode(',', $tgl_cuti_explode);

                    // cuti persalinan
                    if ($id_ketidakhadiran == 'AKT-000005') {
                        $tgl_cuti_explode = explode(',', $list_tgl_cuti);
                        $tgl_cuti_persalinan = $tgl_cuti_explode[0];

                        $tgl_cuti_persalinan_awal = new DateTime($tgl_cuti_persalinan);
                        $tgl_cuti_persalinan_awal2 = $tgl_cuti_persalinan_awal;
                        $tgl_cuti_persalinan_awal = $tgl_cuti_persalinan_awal->format('Y-m-d');

                        $tgl_cuti_persalinan_awal2->add(new DateInterval('P89D'));
                        $tgl_cuti_persalinan_selesai = $tgl_cuti_persalinan_awal2->format('Y-m-d');


                        $listtglpersalinan = array();
                        $interval = new DateInterval('P1D');
                        $realEnd = new DateTime($tgl_cuti_persalinan_selesai);
                        $realEnd->add($interval);
                        $period = new DatePeriod(new DateTime($tgl_cuti_persalinan_awal), $interval, $realEnd);

                        foreach ($period as $date) {
                            $listtglpersalinan[] = $date->format('d-m-Y');
                        }
                        $list_tgl_cuti = implode(',', $listtglpersalinan);
                    }

                    // wajib melampirkan bukti jika cuti yang diambil Cudak dan CAP
                    if (in_array($id_ketidakhadiran, ['AKT-000011', 'AKT-000018']) === true) {

                        if(empty($_FILES['bukti1']['name']) && empty($_FILES['bukti2']['name'])) {
                            echo "<h2>Anda BELUM melampirkan Bukti !! </h2>";
                            return;
                        }
                    }

                    // simpan ke tm_cuti
                    if (in_array($id_ketidakhadiran, ['AKT-000011', 'AKT-000018']) !== false) {
                        $iduser_kepeg = bukaquery2("
                            SELECT
                                p.id_user,
                                p.nama_pegawai 
                            FROM tm_pegawai p
                            INNER JOIN tm_user u ON p.id_user = u.id_user AND p.STATUS = 'aktif'
                            INNER JOIN tm_level l ON u.id_level = l.id_level 
                            WHERE
                                l.id_level = 'LVL-000013'
                        ")->fetch_assoc()['id_user'];

                        bukaquery2("
                            INSERT INTO tm_cuti(id_cuti, tgl_permohonan, id_user, id_ketidakhadiran, jumlah_hari, tahun_cuti, alamat_cuti, no_tlp, alasan_cuti, id_user_kepegawaian, id_user_pengganti, id_user_pj, id_user_kasatpel, id_user_kasie, id_user_ktu, id_user_direktur) 
                            VALUES ('$id_cuti', now(), '$id_user', '$id_ketidakhadiran', '" . count(explode(",", $list_tgl_cuti)) . "', '$periode_thn_cuti', '$alamat_cuti', '$nohp_cuti', '$alasan_cuti', '$iduser_kepeg', '$iduser_pengganti', '$iduser_pj', '$iduser_ksp', '$iduser_kasie', '$iduser_ktu', '$iduser_direktur')
                        ");
                    } else
                        bukaquery2("
                            INSERT INTO tm_cuti(id_cuti, tgl_permohonan, id_user, id_ketidakhadiran, jumlah_hari, tahun_cuti, alamat_cuti, no_tlp, alasan_cuti, id_user_pengganti, id_user_pj, id_user_kasatpel, id_user_kasie, id_user_ktu, id_user_direktur, acc_kepegawaian, tgl_acc_kepegawaian) 
                            VALUES ('$id_cuti', now(), '$id_user', '$id_ketidakhadiran', '" . count(explode(",", $list_tgl_cuti)) . "', '$periode_thn_cuti', '$alamat_cuti', '$nohp_cuti', '$alasan_cuti', '$iduser_pengganti', '$iduser_pj', '$iduser_ksp', '$iduser_kasie', '$iduser_ktu', '$iduser_direktur', 'Y', now())
                        ");


                    if ($id_user == $iduser_pj || $id_user == $iduser_ksp) {
                        $cekid = bukaquery2("
                            SELECT id_cuti, id_user 
                            FROM tm_cuti 
                            WHERE id_user = '$id_user' 
                            ORDER BY id_cuti DESC
                            limit 1
                        ")->fetch_assoc();

                        if ($id_user == $iduser_pj)
                            bukaquery2("
                                update tm_cuti
                                set acc_pj = 'Y', tgl_acc_pj = now()
                                where id_cuti = $cekid[id_cuti]
                            ");

                        if ($id_user == $iduser_ksp)
                            bukaquery2("
                                update tm_cuti
                                set acc_kasatpel = 'Y', tgl_acc_kasatpel = now()
                                where id_cuti = $cekid[id_cuti]
                            ");
                    }


                    // simpan tanggal-tanggal cuti nya ke tm_cuti_hari dengan id_cuti diambil dari tm_cuti
                    foreach (explode(",", $list_tgl_cuti) as $value) {
                        bukaquery2("
                            INSERT INTO tm_hari_cuti (id_cuti, tanggal)
                            VALUES ('" . $id_cuti . "', '" . $this->change_format_date($value, "Y-m-d") . "')
                        ");
                    }

                    $sql = bukaquery2("
                        SELECT a.no_hp_wa
                        FROM tm_pegawai a
                        WHERE a.id_user = '" . $iduser_pengganti . "'
                    ");
                    $res = fetch_array($sql);
                    $nohp_pengganti = $res['no_hp_wa'];


                    // kirim notif ke iduser_pengganti
                    $pesan = "E-Kinerja Non-PNS RSUDTA\n\nAnda memiliki permintaan pergantian dinas untuk Cuti.\n\n(ini adalah pesan otomatis)\n" . date('Y-m-d H:i:s') . "";


                    // $curlHandle = curl_init();
                    // curl_setopt($curlHandle, CURLOPT_URL, $url_api_notif_wa);
                    // curl_setopt($curlHandle, CURLOPT_POSTFIELDS, "nohp=" . $nohp_pengganti . "&pesan=" . $pesan);
                    // curl_setopt($curlHandle, CURLOPT_HEADER, 0);
                    // curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
                    // curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
                    // curl_setopt($curlHandle, CURLOPT_POST, 1);
                    // $output = curl_exec($curlHandle);
                    // curl_close($curlHandle);

                    // return array(
                    //     "status" => 1,
                    //     "message" => "berhasil"
                    // );


                    // mulai upload
                    $buktikosong = [];
                    foreach ($_FILES as $labeldokumen => $fileku) {

                        if (empty($fileku['name'])) {
                            $buktikosong[] = $labeldokumen;
                            continue;
                        }


                        $name = basename($fileku['name']);
                        $explode = explode('.', $name);
                        $ext = end($explode);
                        $info = getimagesize($fileku['tmp_name']);
                        $mime = $info['mime'];
                        $datagambar = addslashes(file_get_contents($fileku['tmp_name']));

                        $dokumenke = str_replace('bukti', '', $labeldokumen);
                        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg', 'pdf');
                        $ukuran = $fileku['size'];

                        if (in_array($ext, $ekstensi_diperbolehkan) === true) {
                            if ($ukuran <= '5242880') {
                                $kolom = "buk$dokumenke";
                                $kolom2 = "mime$dokumenke";
                                bukaquery2("update tm_cuti set $kolom = '$datagambar', $kolom2 = '$mime' where id_cuti = $id_cuti");
                            } else {
                                echo "<script>alert('GAGAL !! Upload Logo, mungkin terlalu besar, tidak diperbolehkan lebih dari 5MB !!'); window.location = 'javascript:history.go(-1)'</script>";
                            }
                        } else {
                            echo "<script>alert('File hanya diperbolehkan berformat JPEG dan PNG!!'); window.location = 'javascript:history.go(-1)'</script>";
                        }
                    }
                    // pre($_FILES);
                    // prd($buktikosong);


                    if ($aksi_form == 'edit_pengajuan_cuti') {

                        $buktinya['bukti1'] = "c1.bukti1 = c2.bukti1,
                                c1.buk1 = c2.buk1,
                                c1.mime1 = c2.mime1";
                        $buktinya['bukti2'] = "c1.bukti2 = c2.bukti2,
                                c1.buk2 = c2.buk2,
                                c1.mime2 = c2.mime2";

                        foreach ($buktikosong as $val)
                            $klausas[] = $buktinya[$val];

                        $klausa = count($buktikosong) == 1 ? $klausas[0] : null;
                        $klausa = count($buktikosong) == 2 ? implode(',', $klausas) : $klausa;
                        // prd($klausa);


                        if (!empty($klausa))
                            $query = "
                                UPDATE tm_cuti c1
                                INNER JOIN tm_cuti c2 ON c2.id_cuti = $id_cuti_lama 
                                SET 
                                    $klausa
                                WHERE
                                    c1.id_cuti = $id_cuti
                            ";
                        // else if (empty($klausa))
                        //     $query = "
                        //         UPDATE tm_cuti
                        //         SET 
                        //             bukti1 = null,
                        //             buk1 = null,
                        //             mime1 = null,
                        //             bukti2 = null,
                        //             buk2 = null,
                        //             mime2 = null
                        //         WHERE
                        //             id_cuti = $id_cuti
                        //     ";

                        // prd($query);
                        // prd(123);

                        bukaquery2("
                            delete tm_cuti.*, tm_hari_cuti.* 
                            FROM tm_cuti
                            INNER JOIN tm_hari_cuti ON tm_cuti.id_cuti = tm_hari_cuti.id_cuti 
                            WHERE
                                tm_cuti.id_cuti = $id_cuti_lama
                        ");
                    }

                    header("location: ../../page-view?" . paramEncrypt("module=cuti-pegawai&act=list-data-pengajuan-cuti-pegawai"));
                }
            }
        }
    }

    function get_form_update_cuti($id_cuti, $id_ketidakhadiran)
    {

        $sql = bukaquery2("
            SELECT
                tm_cuti.id_cuti,
                tm_pegawai.id_user,
                nama_pegawai,
                tm_pegawai.id_unit,
                bukti1,
                buk1,
                mime1,
                tahun_cuti,
                alasan_cuti,
                tm_jabatan.nm_jabatan,
                tm_pegawai.sub_bagian,
                tm_pegawai.nip,
                tm_pegawai.alamat,
                tm_unit.nama_unit,
                tm_pegawai.no_hp_wa,
                tm_pegawai.tgl_masuk,
                tgl_permohonan,
                id_ketidakhadiran,
                jumlah_hari,
                alamat_cuti,
                no_tlp,
                id_user_kepegawaian,
                id_user_pengganti,
                id_user_pj,
                id_user_kasatpel,
                id_user_kasie,
                id_user_ktu,
            GROUP_CONCAT(tm_hari_cuti.tanggal) hari_cuti
            FROM tm_cuti
            INNER JOIN tm_pegawai ON tm_cuti.id_user = tm_pegawai.id_user
            INNER JOIN tm_unit ON tm_pegawai.id_unit = tm_unit.id_unit
            LEFT JOIN tt_jabatan_pegawai ON tm_pegawai.id_user = tt_jabatan_pegawai.id_user
            LEFT JOIN tm_jabatan ON tt_jabatan_pegawai.id_jabatan = tm_jabatan.id_jabatan
            INNER JOIN tm_hari_cuti ON tm_cuti.id_cuti = tm_hari_cuti.id_cuti 
            WHERE
                tm_cuti.id_cuti = $id_cuti
        ");
        $res_data_pegawai = fetch_assoc($sql);
        $id_unit = $res_data_pegawai['id_unit'];
        $id_user = $res_data_pegawai['id_user'];
        $sub_bagian = $res_data_pegawai['sub_bagian'];
        $tahun_cuti = $res_data_pegawai['tahun_cuti'];

        // unit yang jumlah pegawainya hanya 1
        // UNT-000022 ATEM
        // UNT-000026 PETUGAS KAMAR JENAZAH
        // UNT-000039 PETUGAS GAS MEDIK
        $unit_sepi = ['UNT-000022', 'UNT-000026', 'UNT-000039'];

        $list_pengganti_cuti = array();
        if (in_array($id_unit, $unit_sepi) !== false) {
            $sql = bukaquery2("select id_user, nama_pegawai from tm_pegawai where id_unit in ('UNT-000022', 'UNT-000026', 'UNT-000039') and id_user != '$id_user' and status = 'aktif'");
            while ($res = fetch_array($sql)) {
                array_push($list_pengganti_cuti, array(
                    "id_user" => $res['id_user'],
                    "nama_pegawai" => $res['nama_pegawai']
                ));
            }
        } else if ($_SESSION['level'] == 'kasatpel') {
            $sql = bukaquery2("SELECT tm_pegawai.id_user, nama_pegawai FROM tm_pegawai INNER JOIN tm_user ON tm_pegawai.id_user = tm_user.id_user INNER JOIN tm_level ON tm_user.id_level = tm_level.id_level  WHERE tm_pegawai.`status` = 'aktif'  AND tm_level.nama_level = 'kasatpel' AND tm_pegawai.id_user <> $id_user  ORDER BY nama_pegawai");
            while ($res = fetch_array($sql)) {
                array_push($list_pengganti_cuti, array(
                    "id_user" => $res['id_user'],
                    "nama_pegawai" => $res['nama_pegawai']
                ));
            }
        } else {
            $sql = bukaquery2("
                SELECT 
                    a.id_user, a.nama_pegawai 
                FROM tm_pegawai a
                    INNER JOIN tm_user b ON a.id_user = b.id_user
                    INNER JOIN tm_level c ON b.id_level = c.id_level
                WHERE 
                    a.status = 'AKTIF'
                    AND a.id_user != '$id_user'
                    AND a.sub_bagian = '" . $sub_bagian . "'
                    AND a.id_unit = '" . $id_unit . "'
                ORDER BY c.`level` DESC, a.nama_pegawai
            ");
            while ($res = fetch_array($sql)) {
                array_push($list_pengganti_cuti, array(
                    "id_user" => $res['id_user'],
                    "nama_pegawai" => $res['nama_pegawai']
                ));
            }
        }


        $list_pj = array();
        if (in_array($id_unit, $unit_sepi) !== false) {
            $sql = bukaquery2("select id_user, nama_pegawai from tm_pegawai where id_unit = 'UNT-000022' and status = 'aktif'");
            while ($res = fetch_array($sql)) {
                array_push($list_pj, array(
                    "id_user" => $res['id_user'],
                    "nama_pegawai" => $res['nama_pegawai']
                ));
            }
        } else if ($_SESSION['level'] == 'kasatpel') {
            $sql = bukaquery2("SELECT tm_pegawai.id_user, nama_pegawai FROM tm_pegawai INNER JOIN tm_user ON tm_pegawai.id_user = tm_user.id_user INNER JOIN tm_level ON tm_user.id_level = tm_level.id_level  WHERE tm_pegawai.`status` = 'aktif'  AND tm_level.nama_level = 'kasatpel'  ORDER BY nama_pegawai");
            while ($res = fetch_array($sql)) {
                array_push($list_pj, array(
                    "id_user" => $res['id_user'],
                    "nama_pegawai" => $res['nama_pegawai']
                ));
            }
        } else {
            $sql = bukaquery2("
                SELECT
                    a.id_user, a.nama_pegawai
                FROM tm_pegawai a
                    INNER JOIN tm_user b ON a.id_user = b.id_user
                    INNER JOIN tm_level c ON b.id_level = c.id_level
                WHERE a.status = 'AKTIF'
                    AND (c.nama_level = 'PJ' or c.nama_level = 'KOORDINATOR KEPEGAWAIAN')
                    -- AND a.sub_bagian = '" . $sub_bagian . "'
                    AND a.id_unit = '" . $id_unit . "'
                ORDER BY c.`level`, a.nama_pegawai
            ");
            while ($row = fetch_array($sql)) {
                array_push($list_pj, array(
                    'id_user' => $row['id_user'],
                    'nama_pegawai' => $row['nama_pegawai']
                ));
            }
        }


        // Unit bawahan umum
        // UNT-000028 PETUGAS KEAMANAN
        // UNT-000027 CLEANING SERVICE
        // UNT-000009 DRIVER
        $unit_tambahan_umum = ['UNT-000028', 'UNT-000027', 'UNT-000009'];

        if (in_array($id_unit, $unit_tambahan_umum) !== false) {
            $sql = bukaquery2("select id_user, nama_pegawai from tm_pegawai where id_user = '2309040001' and status = 'aktif'");
            while ($res = fetch_array($sql)) {
                array_push($list_pj, array(
                    "id_user" => $res['id_user'],
                    "nama_pegawai" => $res['nama_pegawai']
                ));
            }
        }


        $list_ksp = array();
        // level kasatpel
        $sql = bukaquery2("
        SELECT
            a.id_user,
            a.nama_pegawai 
        FROM
            tm_pegawai a
            INNER JOIN tm_user b ON a.id_user = b.id_user
            INNER JOIN tm_level c ON b.id_level = c.id_level 
            AND c.nama_level = 'KASATPEL'
            INNER JOIN tm_pegawai d ON a.id_kasatpel = d.id_kasatpel
            INNER JOIN tm_user e ON d.id_user = e.id_user
            INNER JOIN tm_level f ON e.id_level = f.id_level 
        WHERE
            a.`status` = 'AKTIF' 
            AND b.id_level = 'LVL-000006' 
            AND d.id_user = '$id_user' 
        ORDER BY
            a.nama_pegawai
        ");
        while ($row = fetch_array($sql)) {
            array_push($list_ksp, array(
                "id_user" => $row['id_user'],
                "nama_pegawai" => $row['nama_pegawai']
            ));
        }

        $list_kasie = array();
        $sql = bukaquery2("
            SELECT
                a.id_user,
                a.nama_pegawai 
            FROM
                tm_pegawai a
                INNER JOIN tm_user b ON a.id_user = b.id_user
                INNER JOIN tm_level c ON b.id_level = c.id_level 
                AND ( c.nama_level = 'KASIE' OR c.nama_level = 'KASUBBAG TU' )
                INNER JOIN tm_pegawai d ON a.sub_bagian = d.sub_bagian
                INNER JOIN tm_user e ON d.id_user = e.id_user
                INNER JOIN tm_level f ON e.id_level = f.id_level 
            WHERE
                a.`status` = 'AKTIF' 
                and d.`status` = 'AKTIF' 
                AND d.id_user = '$id_user' 
            ORDER BY
                a.nama_pegawai
        ");
        while ($row = fetch_array($sql)) {
            array_push($list_kasie, array(
                "id_user" => $row['id_user'],
                "nama_pegawai" => $row['nama_pegawai']
            ));
        }

        $list_ktu = array();
        $sql = bukaquery2("
            SELECT
                a.id_user, a.nama_pegawai, a.id_unit,
                c.id_level, c.`level`, c.nama_level
            FROM tm_pegawai a
                INNER JOIN tm_user b ON a.id_user = b.id_user
                INNER JOIN tm_level c ON b.id_level = c.id_level AND c.nama_level = 'KASUBBAG TU'
            WHERE a.`status` = 'AKTIF'
            ORDER BY c.`level`
        ");
        while ($row = fetch_array($sql)) {
            array_push($list_ktu, array(
                "id_user" => $row['id_user'],
                "nama_pegawai" => $row['nama_pegawai']
            ));
        }

        $list_direktur = array();
        $sql = bukaquery2("
            SELECT
                a.id_user, a.nama_pegawai, a.id_unit,
                c.id_level, c.`level`, c.nama_level
            FROM tm_pegawai a
                INNER JOIN tm_user b ON a.id_user = b.id_user
                INNER JOIN tm_level c ON b.id_level = c.id_level
            WHERE a.`status` = 'AKTIF'
                AND b.id_level = 'LVL-000011'
            ORDER BY c.`level`
        ");
        while ($row = fetch_array($sql)) {
            array_push($list_direktur, array(
                "id_user" => $row['id_user'],
                "nama_pegawai" => $row['nama_pegawai']
            ));
        }


        $hari_cuti = array();
        $sql = bukaquery2("
            SELECT tanggal 
            FROM tm_hari_cuti 
            WHERE id_cuti = '$id_cuti'
        ");
        while ($row = fetch_assoc($sql)) {
            $tglku = new DateTime($row['tanggal']);
            $tglku = $tglku->format('m/d/Y');
            array_push(
                $hari_cuti,
                $tglku,
            );
        }

        // if ($res_data_pegawai['id_ketidakhadiran'] == 'AKT-000005')
        //     $hari_cuti = $hari_cuti[0];
        // else
        //     $hari_cuti = implode(",", $hari_cuti);

        if (!isset($res_data_pegawai)) {
            return array(
                'status' => 0,
                'message' => 'Data tidak ditemukan'
            );
        } else {

            return array(
                'status' => 1,
                'message' => 'data ditemukan',
                'data' => array(
                    "data_pegawai" => array(
                        'id_user' => $res_data_pegawai['id_user'],
                        'nip' => $res_data_pegawai['nip'],
                        'nama_pegawai' => $res_data_pegawai['nama_pegawai'],
                        'alamat' => $res_data_pegawai['alamat'],
                        'nm_unit' => $res_data_pegawai['nama_unit'],
                        'no_hp_wa' => $res_data_pegawai['no_hp_wa'],
                        'tgl_masuk' => $res_data_pegawai['tgl_masuk'],
                        'nm_jabatan' => $res_data_pegawai['nm_jabatan'],
                    ),
                    "opsi_pengganti_cuti" => $list_pengganti_cuti,
                    "opsi_pj" => $list_pj,
                    "list_ksp" => $list_ksp,
                    "opsi_kasie" => $list_kasie,
                    "opsi_ktu" => $list_ktu,
                    "opsi_direktur" => $list_direktur,
                    'tahun_cuti' => $res_data_pegawai['tahun_cuti'],
                    'id_ketidakhadiran' => $res_data_pegawai['id_ketidakhadiran'],
                    'alasan_cuti' => $res_data_pegawai['alasan_cuti'],
                    'hari_cuti' => $hari_cuti,
                    'id_user_pengganti' => $res_data_pegawai['id_user_pengganti'],
                    'id_user_pj' => $res_data_pegawai['id_user_pj'],
                    'id_user_kasatpel' => $res_data_pegawai['id_user_kasatpel'],
                    'id_user_kasie' => $res_data_pegawai['id_user_kasie'],
                    'id_user_ktu' => $res_data_pegawai['id_user_ktu'],
                )
            );
        }
    }

    /**
     * @description verifikasi untuk memastikan cuti yg akan diajukan, valid atau mencukupi
     */
    function get_hasil_verifikasi_cuti(
        $id_user, 
        $selected_year, 
        $current_year, 
        $id_ketidakhadiran
    ) {

        $current_month = date('m');

        // penambahan atau pengurangan cuti yang diberikan oleh kepegawaian
        $message_3 = "";
        $penambahan_pengurangan_cuti = 0;
        $sql = bukaquery2("
        SELECT
            a.jenis, a.jumlah
        FROM tm_penambahan_cuti AS a
        WHERE a.id_user = '$id_user' 
            AND a.id_ketidakhadiran = '$id_ketidakhadiran'
            AND a.tahun_cuti = '$selected_year'
            AND a.active = '1'
        ");
        while ($row = fetch_array($sql)) {

            if (in_array($row['jenis'], ['1', '2'])) {
                $sign = $row['jenis'] == '1' ? '+' : '-';
                $jumlah = (int) $row['jumlah'];

                $message_3 .= " {$sign}{$jumlah},";
                $penambahan_pengurangan_cuti += ($sign == '+' ? $jumlah : -$jumlah);
            }
        }

       $message_3 = $message_3 !== "" ? "Cuti anda{$message_3} oleh Kepegawaian" : "";


        // cek berdasarkan id_ketidakhadiran
        // AKT-000017 CPCB = didapatkan berdasarkan tm_hari_libur.cuti_bersama = ya dan terdeteksi absen di log. dan periode cutinya harus lebih dari log absen.
        // AKT-000012 Cuti Tahunan = maksimal 12 hari per tahun, minimal pilih hari 3 hari kedepan.
        // AKT-000018 Cuti Mendadak = mengurangi kuota cuti tahun, hanya bisa ambil 1 hari di hari yang sama dengan cuti diajukan.
        // AKT-000011 Cuti Alasan Penting = tidak mengurangi kuota apapun. bisa diambil backdate dari hari ini, berapa hari bebas
        // AKT-000005 Persalinan = tidak mengurangi kuota apapaun. bisa diambil kapans aja, berapa hari bebas

        // cpcb
        if($id_ketidakhadiran == 'AKT-000017') {

            $message = "";
            // ambil data tm_hari_libur.cuti_bersama
            $list_cutibersama = array();
            $sql = bukaquery2("
                SELECT
                    a.id_hari_libur, a.tanggal, a.keterangan
                FROM tm_hari_libur AS a
                WHERE YEAR(a.tanggal) = '".$selected_year."'
                    AND a.cuti_bersama = 'ya'
                GROUP BY a.tanggal
            ");
            while ($row = fetch_assoc($sql)) {

                array_push($list_cutibersama, array(
                    'id_hari_libur' => $row['id_hari_libur'],
                    'tanggal' => $row['tanggal'],
                    'keterangan' => $row['keterangan']
                ));
            }            

            // ambil history cpcb pegawai di e-cuti
            $list_cutibersama_diambil = array();
            $sql = bukaquery2("
                SELECT
                    a.tgl_permohonan, a.jumlah_hari, b.tanggal, a.alasan_cuti, c.desc_ketidakhadiran
                FROM tm_cuti AS a
                    INNER JOIN tm_hari_cuti AS b ON a.id_cuti = b.id_cuti
                    INNER JOIN tm_shift_ketidakhadiran AS c ON a.id_ketidakhadiran = c.id_ketidakhadiran
                WHERE a.id_user = '".$id_user."'
                AND a.id_ketidakhadiran = 'AKT-000017'
                AND a.tahun_cuti = '".$selected_year."'
                AND a.aktif = '1'
            ");
            while ($row = fetch_assoc($sql)) {

                array_push($list_cutibersama_diambil, array(
                    "tgl_permohonan" => $row['tgl_permohonan'],
                    "jumlah_hari" => $row['jumlah_hari'],
                    "tanggal" => $row['tanggal'],
                    "alasan_cuti" => $row['alasan_cuti'],
                    "desc_ketidakhadiran" => $row['desc_ketidakhadiran']
                ));
            }
            
            // ambil list absensi pegawai
            $list_absensi = array();
            $log_finger = fetch_assoc(bukaquery("
                SELECT
                    a.log_finger
                FROM tm_pegawai AS a
                WHERE a.id_user = '".$id_user."'")
            )['log_finger'];
            $sql = bukaquery("
                SELECT
                    a.tanggal
                FROM log AS a
                WHERE a.user = '".$log_finger."' AND YEAR(a.tanggal) = '".$selected_year."'
                ORDER BY a.tanggal
            ");
            while ($row = fetch_assoc($sql)) $list_absensi[] = $row['tanggal'];

            // cari tanggal dimana pegawai masuk saat cutber
            $list_masuk_saat_cuber = array();
            foreach ($list_cutibersama as $cutibersama) {
                
                // ambil juga tanggal besok dari cuber. 
                // untuk jaga2 pegawai masuk shift malem (yakan absen pulang besok-nya cok)
                $tglbesok_cutibersama = (new DateTime($cutibersama['tanggal']))->modify('+1 day')->format('Y-m-d');

                foreach ($list_absensi as $index => $absensi) {
                    
                    // ambil absen di hari cuber
                    if(substr($absensi, 0, 10) == $cutibersama['tanggal']) {

                        $list_masuk_saat_cuber[] = $absensi;
                        unset($list_absensi[$index]);
                    }

                    // ambil absen hari esok nya
                    if(substr($absensi, 0, 10) == $tglbesok_cutibersama) {

                        $list_masuk_saat_cuber[] = $absensi;
                        unset($list_absensi[$index]);
                    }
                }
                $list_absensi = array_values($list_absensi);
            }
            $list_masuk_saat_cuber = array_values($list_masuk_saat_cuber);

            // buat list_masuk_saat_cuber dalam bentuk grouping tanggal, biar lebih mudah mencari nanti
            $absen_by_date = [];
            foreach ($list_masuk_saat_cuber as $absen) {
                
                $dt = new DateTime($absen);
                $tanggal = $dt->format('Y-m-d');
                $absen_by_date[$tanggal][] = $dt;
            }
            
            // kalkulasikan jumlah kuota cpcb yang akan didapatkan pegawai
            // cara menghitungnya :
            // 1. jika shift pagi (absen_masuk jam 4-9, trs absen_pulang lagi ya minimal 4jam dari absen_masuk) dapat 1
            // 2. jika shift siang (absen_masuk jam 11-16, trs absen_pulang lagi ya minimal 4jam dari absen_masuk) dapat 1
            // 3. jika shift malam (absen_masuk jam 18, trs absen_pulang lagi besok hari maksimal jam 11) dapat 1
            $kuota_cpcb = 0;
            $message_2 = "";
            foreach ($list_cutibersama as $cuti) {

                $hari_ini = $cuti['tanggal'];
                $besok = ((new DateTime($hari_ini))->modify('+1 day'))->format('Y-m-d');

                $absensi_hari_ini = $absen_by_date[$hari_ini] ?? [];
                $absensi_besok = $absen_by_date[$besok] ?? [];
                
                usort($absensi_hari_ini, fn($a, $b) => $a <=> $b); // urutkan absensi
                usort($absensi_besok, fn($a, $b) => $a <=> $b); // urutkan absensi

                // sebagai flag, biar ga double seandaianya iseng absen 2x
                $cuti_terhitung = false;

                // shift pagi
                foreach ($absensi_hari_ini as $i => $absen_masuk) {
                    
                    $jam = (int) $absen_masuk->format('H');
                    if($jam >= 4 && $jam < 9) { // shift masuk dari jam 4 sampe jam 9

                        foreach (array_slice($absensi_hari_ini, $i + 1) as $absen_pulang) { // cari absen_pulang mulai array selanjutnya
                            
                            if($absen_pulang->getTimestamp() - $absen_masuk->getTimestamp() >= 4 * 3600) { // jika pegawai absen lagi lebih dari 4 jam, dihitung masuk kerja
                                
                                // print_r("masuk shift pagi ".$absen_masuk->format('Y-m-d H:i:s'));
                                // print_r("<br>");
                                $kuota_cpcb++;
                                $cuti_terhitung = true;
                                $message_2 .= "\n-Pagi ".$cuti['keterangan']." ".$cuti['tanggal'];
                                break 2;
                            }
                        }
                    }
                }

                // shift sore
                if($cuti_terhitung == false) {

                    foreach ($absensi_hari_ini as $i => $absen_masuk) {

                        $jam = (int) $absen_masuk->format('H');
                        if($jam >= 11 && $jam < 16) { // shift masuk dari jam 11 sampe jam 16

                            foreach (array_slice($absensi_hari_ini, $i + 1) as $absen_pulang) { // cari absen_pulang mulai array selanjutnya
                                
                                if($absen_pulang->getTimestamp() - $absen_masuk->getTimestamp() >= 4 * 3600) { // jika pegawai absen lagi lebih dari 4 jam, dihitung masuk kerja

                                    // print_r("masuk shift sore ".$absen_masuk->format('Y-m-d H:i:s'));
                                    // print_r("<br>");
                                    $kuota_cpcb++;
                                    $cuti_terhitung = true;
                                    $message_2 .= "\n-Sore ".$cuti['keterangan']." ".$cuti['tanggal'];
                                    break 2;
                                }
                            }
                        }
                    }
                }

                // shift malem
                if($cuti_terhitung == false) {

                    foreach ($absensi_hari_ini as $i => $absen_masuk) {
                        
                        $jam = (int) $absen_masuk->format('H');
                        if($jam >= 18) { // shift masuk dari jam 18

                            foreach ($absensi_besok as $absen_pulang) {
                                
                                $jam_pulang = (int) $absen_pulang->format('H');
                                if($jam_pulang < 11) { // shift pulang dari array absensi_besok dan absen kurang dari jam 11

                                    // print_r("masuk shift malem ".$absen_masuk->format('Y-m-d H:i:s'));
                                    // print_r("<br>");
                                    $kuota_cpcb++;
                                    $cuti_terhitung = true;
                                    $message_2 .= "\n-Malam ".$cuti['keterangan']." ".$cuti['tanggal'];
                                    break 2;
                                }
                            }
                        }
                    }
                }
            }
            
            // hitung sisa kuota cpcb 
            // dari kuota_cpcb dikurang list_cutibersama_diambil
            $sisa_kuota_cpcb = (int) ($kuota_cpcb - count($list_cutibersama_diambil));
            $sisa_kuota_cpcb += $penambahan_pengurangan_cuti; // hitung total dengan penambahan/pengurangan cuti oleh kepegawaian

            // jika kuota cpbp 0, ga muncul tanggal
            $list_tanggal = [];
            if($sisa_kuota_cpcb != 0) {

                $start = new DateTime();
                $start = (new DateTime())->modify('+0 days');
                $end = (new DateTime())->modify('+2 months');
                
                while ($start <= $end) {

                    $list_tanggal[] = $start->format('m/d/Y');
                    $start->modify('+1 day');
                }
            }

            // $start = new DateTime();
            // $end = new DateTime();
            // $list_tanggal = [
            //     $start->format('m/d/Y')
            // ];

            $message .= "Kuota CPCB: ".$kuota_cpcb;
            $message .= "\nAnda sudah ambil CPCB: ".count($list_cutibersama_diambil);
            $message .= "\n".$message_3;
            $message .= "\nSisa CPCB: ".$sisa_kuota_cpcb;
            $message .= "\n\n\nDetail Masuk CP: ".$message_2;

            $message .= "\n\nDetail CPCB:";
            foreach ($list_cutibersama_diambil as $key => $cuti) {
                
                $message .= "\n- ".$cuti['desc_ketidakhadiran']." ".$cuti['tanggal']." ".$cuti['alasan_cuti'];
            }
            
            return array(
                'status' => 1,
                'message' => $message,
                'message_2' => '',
                'periode_cuti_setting' => array(
                    'tanggal_tersedia' => $list_tanggal,
                    'kuota_cuti' => $sisa_kuota_cpcb
                )
            );
        }

        // cuti tahunan
        if($id_ketidakhadiran == 'AKT-000012') {

            global $rule_pengurangan_sisa_cuti_tahunan;
            $presentase_sisa_cuti = 1;
            $message = "";

            // perhitungan total cuti, akan bergantung pada kalkulasi dibawah
            if ($selected_year != $current_year) {

                foreach ($rule_pengurangan_sisa_cuti_tahunan as $key => $pengurangan_cuti_month) {

                    if ($key == $id_ketidakhadiran) {

                        foreach ($pengurangan_cuti_month as $pengurangan_cuti) {

                            // jika bulan yang dipilih user, ada di pengaturan ya ambil
                            if ($pengurangan_cuti['month'] == (int) $current_month) {
                                $presentase_sisa_cuti = ($pengurangan_cuti['presentase_sisa_cuti'] / 100);
                                continue 2; // keluar loop
                            }

                            // jika tidak ada, jadi 0 yangartinya sudah hangus
                            $presentase_sisa_cuti = 0;
                        }
                    }
                }
            }

            // ambil kuota cuti dari tm_shift_ketidakhadiran
            $sql = bukaquery("SELECT a.count_cuti FROM tm_shift_ketidakhadiran AS a WHERE a.id_ketidakhadiran = 'AKT-000012'");
            $kuota_cuti = (int) fetch_array($sql)['count_cuti'];

            // hitung cuti (cuti tahunan dan cuti mendadak) yang sudah diambil pegawai berdasarkan tahun dipilih
            $sql = bukaquery("
                SELECT
                    a.tgl_permohonan, a.jumlah_hari, b.tanggal, a.alasan_cuti, c.desc_ketidakhadiran
                FROM tm_cuti AS a
                    INNER JOIN tm_hari_cuti AS b ON a.id_cuti = b.id_cuti
                    INNER JOIN tm_shift_ketidakhadiran AS c ON a.id_ketidakhadiran = c.id_ketidakhadiran
                WHERE a.id_user = '".$id_user."'
                    AND (
                        a.id_ketidakhadiran = 'AKT-000012'
                        OR a.id_ketidakhadiran = 'AKT-000018'
                    )
                    AND a.tahun_cuti = '".$selected_year."'
                    AND a.aktif = '1'
                ORDER BY b.tanggal
            ");
            $list_cuti = array();
            while ($row = fetch_assoc($sql)) {

                array_push($list_cuti, array(
                    "tgl_permohonan" => $row['tgl_permohonan'],
                    "jumlah_hari" => $row['jumlah_hari'],
                    "tanggal" => $row['tanggal'],
                    "alasan_cuti" => $row['alasan_cuti'],
                    "desc_ketidakhadiran" => $row['desc_ketidakhadiran']
                ));
            }
            $total_cuti = count($list_cuti);

            // hitung sisa cuti pegawai dengan presentase
            $sisa_cuti = floor(($kuota_cuti - $total_cuti) * $presentase_sisa_cuti);
            $sisa_cuti += $penambahan_pengurangan_cuti; // hitung total dengan penambahan/pengurangan cuti oleh kepegawaian

            $message .= "\nKuota Cuti Pertahun: ".$kuota_cuti." dgn persentase ".($presentase_sisa_cuti * 100)."%";
            $message .= "\nCuti Anda Gunakan: ".$total_cuti;
            $message .= "\n".$message_3;
            $message .= "\nSisa Cuti : ".$sisa_cuti;
            $message .= "\n";
            foreach ($list_cuti as $key => $cuti) {
                
                $message .= "\n- ".$cuti['desc_ketidakhadiran']." ".$cuti['tanggal']." ".$cuti['alasan_cuti'];
            }

            $start = new DateTime();
            $start = (new DateTime())->modify('+3 days');
            $end = (new DateTime())->modify('+2 months');
            $list_tanggal = [];

            while ($start <= $end) {

                $list_tanggal[] = $start->format('m/d/Y');
                $start->modify('+1 day');
            }

            return array(
                'status' => 1,
                'message' => $message,
                'message_2' => '',
                'periode_cuti_setting' => array(
                    'tanggal_tersedia' => $list_tanggal,
                    'kuota_cuti' => $sisa_cuti
                )
            );
        }

        // cuti mendadak
        if($id_ketidakhadiran == 'AKT-000018') {

            global $rule_pengurangan_sisa_cuti_tahunan;
            $presentase_sisa_cuti = 1;
            $message = "";

            // perhitungan total cuti, akan bergantung pada kalkulasi dibawah
            if ($selected_year != $current_year) {

                foreach ($rule_pengurangan_sisa_cuti_tahunan as $key => $pengurangan_cuti_month) {

                    if ($key == $id_ketidakhadiran) {

                        foreach ($pengurangan_cuti_month as $pengurangan_cuti) {

                            // jika bulan yang dipilih user, ada di pengaturan ya ambil
                            if ($pengurangan_cuti['month'] == (int) $current_month) {
                                $presentase_sisa_cuti = ($pengurangan_cuti['presentase_sisa_cuti'] / 100);
                                continue 2; // keluar loop
                            }

                            // jika tidak ada, jadi 0 yangartinya sudah hangus
                            $presentase_sisa_cuti = 0;
                        }
                    }
                }
            }

            // ambil kuota cuti dari tm_shift_ketidakhadiran
            $sql = bukaquery("SELECT a.count_cuti FROM tm_shift_ketidakhadiran AS a WHERE a.id_ketidakhadiran = 'AKT-000012'");
            $kuota_cuti = (int) fetch_array($sql)['count_cuti'];

            // hitung cuti (cuti tahunan dan cuti mendadak) yang sudah diambil pegawai berdasarkan tahun dipilih
            $sql = bukaquery("
                SELECT
                    a.tgl_permohonan, a.jumlah_hari, b.tanggal, a.alasan_cuti, c.desc_ketidakhadiran
                FROM tm_cuti AS a
                    INNER JOIN tm_hari_cuti AS b ON a.id_cuti = b.id_cuti
                    INNER JOIN tm_shift_ketidakhadiran AS c ON a.id_ketidakhadiran = c.id_ketidakhadiran
                WHERE a.id_user = '".$id_user."'
                    AND (
                        a.id_ketidakhadiran = 'AKT-000012'
                        OR a.id_ketidakhadiran = 'AKT-000018'
                    )
                    AND a.tahun_cuti = '".$selected_year."'
                    AND a.aktif = '1'
                ORDER BY b.tanggal
            ");
            $list_cuti = array();
            while ($row = fetch_assoc($sql)) {

                array_push($list_cuti, array(
                    "tgl_permohonan" => $row['tgl_permohonan'],
                    "jumlah_hari" => $row['jumlah_hari'],
                    "tanggal" => $row['tanggal'],
                    "alasan_cuti" => $row['alasan_cuti'],
                    "desc_ketidakhadiran" => $row['desc_ketidakhadiran']
                ));
            }
            $total_cuti = count($list_cuti);

            // hitung sisa cuti pegawai dengan presentase
            $sisa_cuti = floor(($kuota_cuti - $total_cuti) * $presentase_sisa_cuti);
            $sisa_cuti += $penambahan_pengurangan_cuti; // hitung total dengan penambahan/pengurangan cuti oleh kepegawaian

            $message .= "\nKuota Cuti Pertahun: ".$kuota_cuti." dgn persentase ".($presentase_sisa_cuti * 100)."%";
            $message .= "\nCuti Anda Gunakan: ".$total_cuti;
            $message .= "\n".$message_3;
            $message .= "\nSisa Cuti : ".$sisa_cuti;
            $message .= "\n";
            foreach ($list_cuti as $key => $cuti) {
                
                $message .= "\n- ".$cuti['desc_ketidakhadiran']." ".$cuti['tanggal']." ".$cuti['alasan_cuti'];
            }

            $start = new DateTime();
            $end = new DateTime();
            $list_tanggal = [
                $start->format('m/d/Y')
            ];

            return array(
                'status' => 1,
                'message' => $message,
                'message_2' => 'PERHATIAN. Cuti Mendadak hanya bisa diambil untuk HARI INI dan harus MELAMPIRKAN BUKTI !',
                'periode_cuti_setting' => array(
                    'tanggal_tersedia' => $list_tanggal,
                    'kuota_cuti' => $sisa_cuti
                )
            );
        }

        // cuti alasan penting
        if($id_ketidakhadiran == 'AKT-000011') {

            $start = new DateTime();
            $start = (new DateTime())->modify('-1 month');
            $end = (new DateTime())->modify('+3 days');
            $list_tanggal = [];

            while ($start <= $end) {

                $list_tanggal[] = $start->format('m/d/Y');
                $start->modify('+1 day');
            }

            return array(
                'status' => 1,
                'message' => 'ok',
                'message_2' => 'PERHATIAN. Cuti Alasan harus MELAMPIRKAN BUKTI dan ALASAN CUTI yang SESUAI !',
                'periode_cuti_setting' => array(
                    'tanggal_tersedia' => $list_tanggal,
                    'kuota_cuti' => '∞'
                )
            );
        }

        // cuti persalinan
        if($id_ketidakhadiran == 'AKT-000005') {

            $start = new DateTime();
            $end = (new DateTime())->modify('+3 months');
            $list_tanggal = [];

            while ($start <= $end) {

                $list_tanggal[] = $start->format('m/d/Y');
                $start->modify('+1 day');
            }

            return array(
                'status' => 1,
                'message' => 'ok',
                'message_2' => '',
                'periode_cuti_setting' => array(
                    'tanggal_tersedia' => $list_tanggal,
                    'kuota_cuti' => 0
                )
            );
        }

        return array(
            'status' => 0,
            'message' => 'cuti tidak ditemukan',
            'periode_cuti_setting' => array(
                'tanggal_tersedia' => array(),
                'kuota_cuti' => 0
            )
        );
    }

    function post_penambahan_cuti($tahun, $cuti, $jenis_penambahan, $jumlah, $keterangan, $id_user, $id_pegawaian) {
        
        bukaquery2("
            INSERT INTO tm_penambahan_cuti(id_user, id_ketidakhadiran, tahun_cuti, jenis, jumlah, id_kepegawaian, keterangan) 
            VALUES ('$id_user', '$cuti', '$tahun', '$jenis_penambahan', '$jumlah', '$id_pegawaian', '$keterangan')
        ");

        return array(
            'status' => 1,
            'message' => 'ok'
        );
    }

    function delete_penambahan_cuti($id) {
        
        bukaquery2("
            UPDATE tm_penambahan_cuti
            SET active = '0'
            WHERE id = '$id'
        ");

        return array(
            'status' => 1,
            'message' => 'ok'
        );
    }

    /**
     * @description untuk mendapatkan summary / rekapitulasi
     */
    function get_summary_cuti_pegawai($sub_bagian, $selected_year) {

        // ambil seluruh data cuti pegawai yang aktif
        $sql = bukaquery2("
            SELECT
                a.id_user, a.nama_pegawai, a.sub_bagian
            FROM tm_pegawai AS a
            WHERE a.`status` = 'AKTIF'
            AND (
                a.status_pegawai = 'NON PNS' 
                OR a.status_pegawai = 'PJLP'
                OR a.status_pegawai = 'SPESIALIS'
                OR a.status_pegawai = 'SPESIALIS-PARTTIME'
            )
            AND a.sub_bagian = '".$sub_bagian."'
            ORDER BY a.nama_pegawai
        ");
        $list_pegawai = array();
        while ($row = fetch_assoc($sql)) {

            array_push($list_pegawai, array(
                "id_user" => $row['id_user'],
                "nama_pegawai" => $row['nama_pegawai'],
                "sub_bagian" => $row['sub_bagian']
            ));
        }

        // ambil seluruh data jenis ketidakhadiran
        $sql = bukaquery2("
            SELECT
                a.id_ketidakhadiran, a.nama_ketidakhadiran
            FROM tm_shift_ketidakhadiran AS a
            WHERE a.id_ketidakhadiran = 'AKT-000010'
            OR a.id_ketidakhadiran = 'AKT-000012'
            OR a.id_ketidakhadiran = 'AKT-000017'
        ");
        $list_ketidakhadiran = array();
        while ($row = fetch_assoc($sql)) {

            array_push($list_ketidakhadiran, array(
                "id_ketidakhadiran" => $row['id_ketidakhadiran'],
                "nama_ketidakhadiran" => $row['nama_ketidakhadiran']
            ));
        }

        // cari sisa cutinya pakai function get_hasil_verifikasi_cuti
        foreach ($list_pegawai as $key_pegawai => $pegawai) {
            
            foreach ($list_ketidakhadiran as $key => $ketidakhadiran) {
                
                $res = $this->get_hasil_verifikasi_cuti(
                    $pegawai['id_user'],
                    $selected_year,
                    $selected_year,
                    $ketidakhadiran['id_ketidakhadiran']
                );

                $list_pegawai[$key_pegawai]['list_cuti'][$key] = array(
                    'nama_ketidakhadiran' => $ketidakhadiran['nama_ketidakhadiran'],
                    'sisa_cuti' => isset($res['periode_cuti_setting']['kuota_cuti'])
                        ? $res['periode_cuti_setting']['kuota_cuti']
                        : 0
                );
            }
        }

        return array(
            'status' => 1,
            'message' => 'ok',
            'list_cuti_pegawai' => $list_pegawai
        );
    }

    function change_format_date($date, $format)
    {

        return date($format, strtotime($date));
    }

    function invalid_action()
    {
        return array(
            'status' => '401',
            'message' => 'Invalid Action'
        );
    }
}

$api = new ApiCutiPegawai();
$action = isset($_GET['action']) ? $_GET['action'] : null;
$action = isset($_POST['action']) ? $_POST['action'] : $action;

switch ($action) {
    case 'get_detail_pegawai':

        echo json_encode($api->get_detail_pegawai(
            $_GET['id_user'],
            $_GET['sub_bagian'],
            $_GET['id_unit']
        ));
        break;
    case 'get_opsi_cuti_pegawai':

        echo json_encode($api->get_opsi_cuti_pegawai(
            $_GET['id_user'],
            $_GET['selected_year'],
            $_GET['current_year'],
            $_GET['current_month'],
            $_GET['jns_cuti']
        ));
        break;
    case 'get_sisa_cuti_pegawai_by_opsi':

        echo json_encode($api->get_sisa_cuti_pegawai_by_opsi(
            $_GET['id_ketidakhadiran'],
            $_GET['id_user'],
            $_GET['selected_year'],
            $_GET['current_year'],
            $_GET['current_month']
        ));
        break;
    case 'post_pengajuan_cuti':

        echo json_encode($api->post_pengajuan_cuti(
            $_POST['id_user'],
            $_POST['list_tgl_cuti'],
            $_POST['tahun'],
            $_POST['jns_cuti_permohonan'],
            $_POST['sisa_cuti'],
            $_POST['alasan_cuti'],
            $_POST['alamat_cuti'],
            $_POST['no_tlp'],
            $_POST['id_user_pengganti'],
            $_POST['id_user_pj'],
            $_POST['id_user_kasatpel'],
            $_POST['id_user_kasie'],
            $_POST['id_user_ktu'],
            $_POST['id_user_direktur'],
            $action,
            null,
        ));
        break;
    case 'edit_pengajuan_cuti':

        echo json_encode($api->post_pengajuan_cuti(
            $_POST['id_user'],
            $_POST['list_tgl_cuti'],
            $_POST['list_tgl_cutimendadak'],
            $_POST['list_tgl_cuticap'],
            $_POST['tahun'],
            $_POST['jns_cuti_permohonan'],
            $_POST['sisa_cuti'],
            $_POST['alasan_cuti'],
            $_POST['alamat_cuti'],
            $_POST['no_tlp'],
            $_POST['id_user_pengganti'],
            $_POST['id_user_pj'],
            $_POST['id_user_kasatpel'],
            $_POST['id_user_kasie'],
            $_POST['id_user_ktu'],
            $_POST['id_user_direktur'],
            $action,
            $_POST['id_cuti_lama'],
        ));
        break;
    case 'get_form_update_cuti':

        echo json_encode($api->get_form_update_cuti(
            $_GET['id_cuti'],
            $_GET['id_ketidakhadiran']
        ));
        break;
    case 'get_hasil_verifikasi_cuti':

        echo json_encode($api->get_hasil_verifikasi_cuti(
            $_POST['id_user'],
            $_POST['selected_year'],
            $_POST['current_year'],
            $_POST['id_ketidakhadiran']
        ));
        break;
    case 'get_summary_cuti_pegawai':

        echo json_encode($api->get_summary_cuti_pegawai(
            $_POST['sub_bagian'],
            $_POST['selected_year']
        ));
        break;
    case 'post_penambahan_cuti':

        echo json_encode($api->post_penambahan_cuti(
            $_POST['tahun'],
            $_POST['cuti'],
            $_POST['jenis_penambahan'],
            $_POST['jumlah'],
            $_POST['keterangan'],
            $_POST['id_user'],
            $_POST['id_pegawaian']
        ));
        break;
    case 'delete_penambahan_cuti':

        echo json_encode($api->delete_penambahan_cuti(
            $_POST['id']
        ));
        break;
    default:
        echo json_encode($api->invalid_action());
        break;
}
