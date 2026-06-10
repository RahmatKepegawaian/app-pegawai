<?php

require_once ('../../conf/conf.php');
require_once ('../../libs/aes-encrypt/function.php');

class ApiSimpeg {

    function put_profile_pegawai(
        $nik, $nama_pegawai, $alamat, $alamat_domisili, $no_hp_wa, $no_hp_sms, 
        $tempat_lahir, $tgl_lahir, $no_rek, $npwp, $no_bpjs, $jk, $agama, $id_user) {

        bukaquery2("
            UPDATE tm_pegawai 
            SET nik = '".$nik."', nama_pegawai = '".$nama_pegawai."', alamat = '".$alamat."',
                alamat_domisili = '".$alamat_domisili."', tempat_lahir = '".$tempat_lahir."', tgl_lahir = '".FormatTgl('Y-m-d', $tgl_lahir)."',
                no_rek = '".$no_rek."', no_hp_wa = '".$no_hp_wa."', no_hp_sms = '".$no_hp_sms."',
                npwp = '".$npwp."', no_bpjs = '".$no_bpjs."', jk = '".$jk."', agama = '".$agama."'
            WHERE id_user='".$id_user."'
        ");

        return array(
            "status" => 1,
            "message" => "Update Profil Pegawai Berhasil"
        ); 
    }

    function put_fotoprofile_pegawai($file, $id_user) {

        if(isset($file['name'])) {

            // ambil nama file
            $file_name = $file['name'];

            // lokasi penyimpanan yang akan digunakan
            $location = "../../img/".$file_name;

            // ambil ekstensi foto
            $image_file_type = pathinfo($location, PATHINFO_EXTENSION);
            $image_file_type = strtolower($image_file_type);

            // daftar eksentensi yang diperbolehkan
            $valid_extensions = array(
                "jpg",
                "jpeg",
                "png"
            );

            // file yg diupload hanya boleh berekstensi jpg, jpeg, png
            if(in_array(strtolower($image_file_type), $valid_extensions)) {

                // ukuran foto tidak boleh melebihi 5MB
                if($file['size'] <= '5242880') {

                    if(move_uploaded_file($file['tmp_name'], $location)) {

                        $sql = bukaquery2("
                            SELECT
                                a.foto
                            FROM tm_pegawai a
                            WHERE a.id_user = '".$id_user."'
                        ");
                        $old_photo = fetch_array($sql)['foto'];

                        // hapus foto profil yg lama
                        unlink("../../img/".$old_photo);

                        // perbarui database pegawai di tm_pegawai
                        bukaquery2("
                            UPDATE tm_pegawai
                            SET foto = '".$file_name."'
                            WHERE id_user = '".$id_user."'
                        ");

                        return array(
                            "status" => 1,
                            "message" => "Foto Berhasil Diupload"
                        );
                    } else {

                        return array(
                            "status" => 0,
                            "message" => "Gagal Memindahkan File. Silahkan Diulang. ".$_FILES["FILE"]["error"]
                        );
                    }
                } else {

                    return array(
                        "status" => 0,
                        "message" => "Ukuran Foto Terlalu Besar. Ukuran Foto Tidak Boleh Melebihhi 5MB."
                    );
                }
            } else {

                return array(
                    "status" => 0,
                    "message" => "Ekstensi Foto tidak valid. Hanya diperbolehkan JPG, JPEG, PNG."
                );
            }

        } else {

            return array(
                "status" => 0,
                "message" => "File Foto Kosong"
            );
        }
    }

    function invalid_action() {
        return array(
            'status' => '401',
            'message' => 'Invalid Action'
        );
    }
}

$api = new ApiSimpeg();
$action = isset($_GET['action']) ? $_GET['action'] : null;
switch ($action) {
    case 'put_profile_pegawai':
        echo json_encode($api->put_profile_pegawai(
            $_POST['nik'],
            $_POST['nama_pegawai'],
            $_POST['alamat'],
            $_POST['alamat_domisili'],
            $_POST['no_hp_wa'],
            $_POST['no_hp_sms'],
            $_POST['tempat_lahir'],
            $_POST['tgl_lahir'],
            $_POST['no_rek'],
            $_POST['npwp'],
            $_POST['no_bpjs'],
            $_POST['jk'],
            $_POST['agama'],
            $_POST['id_user']
        ));
        break;
    case 'put_fotoprofile_pegawai':
        echo json_encode($api->put_fotoprofile_pegawai(
            $_FILES['foto_profil'],
            $_POST['id_user']
        ));
        break;
    default:
        echo json_encode($api->invalid_action());
        break;
}
?>