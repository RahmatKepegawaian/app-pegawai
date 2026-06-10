<?php 
require_once ('../../conf/conf.php');
require_once ('../../libs/aes-encrypt/function.php');

class ApiValidasiPegawai {

    function get_list_pegawai_by_id_unit($sub_bagian, $cur_id_user) {
        $result_plain = array();
        $result_encrypt = array();
        $tmtcek = TanggalAkhirBulanKemarin();
        $bln_sebelumnya = FormatTgl('m', $tmtcek);
        $thn_sebelumnya = FormatTgl('yy', $tmtcek);

        $sql = bukaquery2("SELECT 
                a.id_user AS iduser, a.nip, a.nama_pegawai, a.id_unit, a.foto, a.sub_bagian, a.id_kasatpel, c.id_level, c.nama_level, d.nama_unit, 
                (SELECT COUNT(a.id_waktu_k) FROM tm_waktu_k a WHERE MONTH(a.date_k) = '".$bln_sebelumnya."' AND YEAR(a.date_k) = '".$thn_sebelumnya."' AND a.id_user = iduser) AS input_waktu_kurang,
                (SELECT COUNT(a.id_waktu_t) FROM tm_waktu_t a WHERE MONTH(a.date_t) = '".$bln_sebelumnya."' AND YEAR(a.date_t) = '".$thn_sebelumnya."' AND a.id_user = iduser) AS input_waktu_tambah,
                (SELECT COUNT(a.id_waktu_s) FROM tm_waktu_s a WHERE MONTH(a.date_s) = '".$bln_sebelumnya."' AND YEAR(a.date_s) = '".$thn_sebelumnya."' AND a.id_user = iduser) AS input_waktu_shift,
                (SELECT COUNT(a.id_disiplin) FROM tm_kedisiplinan a WHERE MONTH(a.date_d) = '".$bln_sebelumnya."' AND YEAR(a.date_d) = '".$thn_sebelumnya."' AND a.id_user = iduser) AS input_kedisiplinan,
                (SELECT COUNT(a.id_kompetensi) FROM tm_kompetensi a WHERE MONTH(a.date_kompetensi) = '".$bln_sebelumnya."' AND YEAR(a.date_kompetensi) = '".$thn_sebelumnya."' AND a.id_user = iduser) AS input_kompetensi,
                (SELECT COUNT(a.id_penilaian) FROM tm_penilaian a WHERE MONTH(a.tanggal_penilaian) = '".$bln_sebelumnya."' AND YEAR(a.tanggal_penilaian) = '".$thn_sebelumnya."' AND a.id_user = iduser) AS input_penilaian_manajemen,
                (SELECT IF(
                    (SELECT COUNT(a.id_kinerja) FROM tt_kinerja a WHERE MONTH(a.tanggal_kinerja) = '".$bln_sebelumnya."' AND YEAR(a.tanggal_kinerja) = '".$thn_sebelumnya."' AND a.id_user = iduser AND a.validasi = 'Y' ) =  
                    (SELECT COUNT(a.id_kinerja) FROM tt_kinerja a WHERE MONTH(a.tanggal_kinerja) = '".$bln_sebelumnya."' AND YEAR(a.tanggal_kinerja) = '".$thn_sebelumnya."' AND a.id_user = iduser), 1, 0
                )) AS input_validasi_kinerja
            FROM tm_pegawai a
                INNER JOIN tm_user b ON a.id_user = b.id_user
                INNER JOIN tm_level c ON b.id_level = c.id_level
                INNER JOIN tm_unit d ON a.id_unit = d.id_unit
            WHERE a.sub_bagian = '".$sub_bagian."' 
                AND a.id_user != '".$cur_id_user."'
                AND a.tgl_masuk <= '".$tmtcek."'
                and a.status='AKTIF' 
                and a.id_unit != 'UNT-000027' 
                and a.id_unit != 'UNT-000028' 
            ORDER BY c.level, d.nama_unit, a.nama_pegawai");

        while ($row = fetch_array($sql)) {
            array_push($result_plain, array(
                "iduser" => $row['iduser'],
                "nip" => $row['nip'],
                "nama_pegawai" => $row['nama_pegawai'],
                "id_unit" => $row['id_unit'],
                "foto" => $row['foto'],
                "sub_bagian" => $row['sub_bagian'],
                "id_kasatpel" => $row['id_kasatpel'],
                "id_level" => $row['id_level'],
                "nama_level" => $row['nama_level'],
                "nama_unit" => $row['nama_unit'],
                "input_waktu_kurang" => (int) $row['input_waktu_kurang'],
                "input_waktu_tambah" => (int) $row['input_waktu_tambah'],
                "input_waktu_shift" => (int) $row['input_waktu_shift'],
                "input_kedisiplinan" => (int) $row['input_kedisiplinan'],
                "input_kompetensi" => (int) $row['input_kompetensi'],
                "input_penilaian_manajemen" => (int) $row['input_penilaian_manajemen'],
                "input_validasi_kinerja" => (int) $row['input_validasi_kinerja']
            ));

            array_push($result_encrypt, array(
                'url_validasi_waktu_pengurangan' => paramEncrypt('module=validasi-pegawai&act=validasi-waktu-pengurangan&id='.$row['iduser']),
                'url_validasi_waktu_penambahan' => paramEncrypt('module=validasi-pegawai&act=validasi-waktu-penambahan&id='.$row['iduser']),
                'url_validasi_waktu_shift' => paramEncrypt('module=validasi-pegawai&act=validasi-waktu-shift&id='.$row['iduser']),
                'url_validasi_kedisiplian' => paramEncrypt('module=validasi-pegawai&act=validasi-kedisiplinan&id='.$row['iduser']),
                'url_validasi_kompetensi' => paramEncrypt('module=validasi-pegawai&act=validasi-kompetensi&id='.$row['iduser']),
                'url_kinerja_pegawai' => paramEncrypt('module=validasi-pegawai&act=list-validasi-kinerja-pegawai&id='.$row['iduser']),
                'url_validasi_manajemen' => $row['input_penilaian_manajemen'] == 0 ? paramEncrypt('module=validasi-pegawai&act=hasil-validasi-management&id='.$row['iduser']): paramEncrypt('module=validasi-pegawai&act=hasil-validasi-management-update&id='.$row['iduser']),
                'url_skp_tahunan' => '',
                'url_hasil_validasi' => paramEncrypt('module=validasi-pegawai&act=hasil-validasi-management&id='.$row['iduser'])
            ));
        }
        
        return array(
            "status" => count($result_plain) != 0 ? 1 : 0,
            "message" => count($result_plain) != 0 ? "Data pegawai ditemukan" : "Data pegawai tidak ditemukan",
            "data" => array(
                "plain" => $result_plain,
                "url_action" => $result_encrypt
            )
        );
    }

    function invalid_action() {
        return array(
            'status' => '401',
            'message' => 'Invalid Action'
        );
    }
}

$apiValidasiPegawai = new ApiValidasiPegawai();
$action = isset($_GET['action']) ? $_GET['action'] : null;
switch ($action) {
    case 'get_list_pegawai_by_id_unit':
        echo json_encode($apiValidasiPegawai->get_list_pegawai_by_id_unit(
            $_GET['sub_bagian'],
            $_GET['cur_id_user']
        ));
        break;
    
    default:
        echo json_encode($apiMasterData->invalid_action());
        break;
}
?>