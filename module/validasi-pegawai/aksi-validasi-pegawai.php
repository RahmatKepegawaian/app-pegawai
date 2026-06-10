<?php

require_once ('../../conf/conf.php');
require_once ('../../libs/aes-encrypt/function.php');
$URI = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;
$url = decode($URI);
$page = isset($url['module']) ? $url['module'] : null;
$action = isset($url['act']) ? $url['act'] : null;
$id = isset($url['id']) ? $url['id'] : null;
//untuk validasi kinerja
if ($page == 'validasi-pegawai' and $action == 'validasi') {
    $potong = explode('-', $id);
    $id_user = $potong[0];
    $id_kinerja = $potong[1];
    bukainput("update tt_kinerja set validasi='Y' where id_kinerja='$id_kinerja'");
    header('location:../../page-view?' . paramEncrypt('module=validasi-pegawai&act=list-validasi-kinerja-pegawai&id=' . $id_user));
} elseif ($page == 'validasi-pegawai' and $action == 'validasi-all') {
    $tgl = TanggalAkhirBulanKemarin();
    $bln_sebelumnya = FormatTgl('m', $tgl);
    $thn = FormatTgl('Y', $tgl);
    bukainput("update tt_kinerja set validasi='Y' where Month(tt_kinerja.tanggal_kinerja)='$bln_sebelumnya' and Year(tt_kinerja.tanggal_kinerja)='$thn' and id_user='$id'");
    header('location:../../page-view?' . paramEncrypt('module=validasi-pegawai&act=list-validasi-kinerja-pegawai&id=' . $id));
} elseif ($page == 'validasi-pegawai' and $action == 'batal-validasi') {
    $potong = explode('-', $id);
    $id_user = $potong[0];
    $id_kinerja = $potong[1];
    bukainput("update tt_kinerja set validasi='T' where id_kinerja='$id_kinerja'");
    header('location:../../page-view?' . paramEncrypt('module=validasi-pegawai&act=list-validasi-kinerja-pegawai&id=' . $id_user));
}

//untuk validasi waktu pengurangan
elseif ($page == 'validasi-pegawai' and $action == 'add-waktu-pengurangan') {
    $autonumber = nokiamat('id_waktu_k', 'tm_waktu_k');
    $date = TanggalAkhirBulanKemarin();
//    $autonumber = "WKTK-" . substr($id, 5) . FormatTgl('Ymd', $date);
    bukainput("insert into tm_waktu_k set id_waktu_k='$autonumber', id_user='$id', sakit1='$_POST[sakit1]', sakit2='$_POST[sakit2]', "
            . "alpha='$_POST[alpha]', izin='$_POST[izin]', telat='$_POST[telat]', ct_sakit_k='$_POST[ct_sakit_k]', ct_alasan_k='$_POST[ct_alasan_k]', "
            . "ct_persalinan_k='$_POST[ct_persalinan_k]', izin_setengah_hari='$_POST[izin_setengah_hari]', meninggal='$_POST[meninggal]', date_k='$date'");
    header('location:../../page-view?' . paramEncrypt('module=validasi-pegawai&act=list-validasi-pegawai'));
    // echo "id_waktu_k='$autonumber', id_user='$id', sakit1='$_POST[sakit1]', sakit2='$_POST[sakit2]', "
    // . "alpha='$_POST[alpha]', izin='$_POST[izin]', telat='$_POST[telat]', ct_sakit_k='$_POST[ct_sakit_k]', ct_alasan_k='$_POST[ct_alasan_k]', "
    // . "ct_persalinan_k='$_POST[ct_persalinan_k]', izin_setengah_hari='$_POST[izin_setengah_hari]', meninggal='$_POST[meninggal]', date_k='$date'";
} elseif ($page == 'validasi-pegawai' and $action == 'update-waktu-pengurangan') {
    bukainput("update tm_waktu_k set sakit1='$_POST[sakit1]', sakit2='$_POST[sakit2]', "
            . "alpha='$_POST[alpha]', izin='$_POST[izin]', telat='$_POST[telat]', ct_sakit_k='$_POST[ct_sakit_k]', ct_alasan_k='$_POST[ct_alasan_k]', "
            . "ct_persalinan_k='$_POST[ct_persalinan_k]', izin_setengah_hari='$_POST[izin_setengah_hari]', meninggal='$_POST[meninggal]' where id_waktu_k='$id'");
    header('location:../../page-view?' . paramEncrypt('module=validasi-pegawai&act=list-validasi-pegawai'));
}

//untuk validasi waktu penambahan
elseif ($page == 'validasi-pegawai' and $action == 'add-waktu-penambahan') {
    $autonumber = nokiamat('id_waktu_t', 'tm_waktu_t');
    $date = TanggalAkhirBulanKemarin();
//    $autonumber = "WKTP-" . substr($id, 5) . FormatTgl('Ymd', $date);
    bukainput("insert into tm_waktu_t set id_waktu_t='$autonumber', id_user='$id', "
            . "ct_sakit_t='$_POST[ct_sakit_t]', ct_alasan_t='$_POST[ct_alasan_t]', ct_tahunan_t='$_POST[ct_tahunan_t]', diklat='$_POST[diklat]', "
            . "spd='$_POST[spd]', haji='$_POST[haji]', date_t='$date'");
    header('location:../../page-view?' . paramEncrypt('module=validasi-pegawai&act=list-validasi-pegawai'));
} elseif ($page == 'validasi-pegawai' and $action == 'update-waktu-penambahan') {
    bukainput("update tm_waktu_t set ct_sakit_t='$_POST[ct_sakit_t]', ct_alasan_t='$_POST[ct_alasan_t]', ct_tahunan_t='$_POST[ct_tahunan_t]', diklat='$_POST[diklat]', "
            . "spd='$_POST[spd]', haji='$_POST[haji]' where id_waktu_t='$id'");
    header('location:../../page-view?' . paramEncrypt('module=validasi-pegawai&act=list-validasi-pegawai'));
}

//untuk validasi waktu shift
elseif ($page == 'validasi-pegawai' and $action == 'add-waktu-shifting') {
    $autonumber = nokiamat('id_waktu_s', 'tm_waktu_s');
    $date = TanggalAkhirBulanKemarin();
//    $autonumber = "WKTS-" . substr($id, 5) . FormatTgl('Ymd', $date);
    bukainput("insert into tm_waktu_s set id_waktu_s='$autonumber', id_user='$id', "
            . "j_hks='$_POST[j_hks]', j_hkm='$_POST[j_hkm]', j_hlp='$_POST[j_hlp]', j_hls='$_POST[j_hls]', "
            . "j_hlm='$_POST[j_hlm]', j_hrp='$_POST[j_hrp]', j_hrs='$_POST[j_hrs]', j_hrm='$_POST[j_hrm]', j_ns='$_POST[j_ns]', date_s='$date'");
    header('location:../../page-view?' . paramEncrypt('module=validasi-pegawai&act=list-validasi-pegawai'));
} elseif ($page == 'validasi-pegawai' and $action == 'update-waktu-shifting') {
    bukainput("update tm_waktu_s set j_hks='$_POST[j_hks]', j_hkm='$_POST[j_hkm]', j_hlp='$_POST[j_hlp]', j_hls='$_POST[j_hls]', "
            . "j_hlm='$_POST[j_hlm]', j_hrp='$_POST[j_hrp]', j_hrs='$_POST[j_hrs]', j_hrm='$_POST[j_hrm]', j_ns='$_POST[j_ns]' where id_waktu_s='$id'");
    header('location:../../page-view?' . paramEncrypt('module=validasi-pegawai&act=list-validasi-pegawai'));
}

//untuk validasi kedisiplinan
elseif ($page == 'validasi-pegawai' and $action == 'add-kedisiplinan') {
    $autonumber = nokiamat('id_disiplin', 'tm_kedisiplinan');
    $date = TanggalAkhirBulanKemarin();
//    $autonumber = "VKDP-" . substr($id, 5) . FormatTgl('Ymd', $date);
    bukainput("insert into tm_kedisiplinan set id_disiplin='$autonumber', id_user='$id', "
            . "d_diri='$_POST[d_diri]', d_penampilan='$_POST[d_penampilan]', d_seragam='$_POST[d_seragam]', d_alat='$_POST[d_alat]', "
            . "d_ruangan='$_POST[d_ruangan]', d_sarana='$_POST[d_sarana]',  date_d='$date'");
    header('location:../../page-view?' . paramEncrypt('module=validasi-pegawai&act=list-validasi-pegawai'));
} elseif ($page == 'validasi-pegawai' and $action == 'update-kedisiplinan') {
    bukainput("update tm_kedisiplinan set d_diri='$_POST[d_diri]', d_penampilan='$_POST[d_penampilan]', d_seragam='$_POST[d_seragam]', d_alat='$_POST[d_alat]', "
            . "d_ruangan='$_POST[d_ruangan]', d_sarana='$_POST[d_sarana]' where id_disiplin='$id'");
    header('location:../../page-view?' . paramEncrypt('module=validasi-pegawai&act=list-validasi-pegawai'));
}

//untuk validasi kompetensi
elseif ($page == 'validasi-pegawai' and $action == 'add-kompetensi') {
    $autonumber = nokiamat('id_kompetensi', 'tm_kompetensi');
    $date = TanggalAkhirBulanKemarin();
//    $autonumber = "VKMP-" . substr($id, 5) . FormatTgl('Ymd', $date);
    bukainput("insert into tm_kompetensi set id_kompetensi='$autonumber', id_user='$id', "
            . "menganalisa1='$_POST[menganalisa1]', menganalisa2='$_POST[menganalisa2]', komunikasi1='$_POST[komunikasi1]', komunikasi2='$_POST[komunikasi2]', "
            . "kerjasama1='$_POST[kerjasama1]', kerjasama2='$_POST[kerjasama2]', kecerdasan1='$_POST[kecerdasan1]', kecerdasan2='$_POST[kecerdasan2]', kecerdasan3='$_POST[kecerdasan3]', "
            . "fokus1='$_POST[fokus1]', fokus2='$_POST[fokus2]', fokus3='$_POST[fokus3]', tanggung1='$_POST[tanggung1]', tanggung2='$_POST[tanggung2]', tanggung3='$_POST[tanggung3]', "
            . "tanggung4='$_POST[tanggung4]', orientasi_k1='$_POST[orientasi_k1]', orientasi_k2='$_POST[orientasi_k2]', inisiatif1='$_POST[inisiatif1]', inisiatif2='$_POST[inisiatif2]', "
            . "disiplin1='$_POST[disiplin1]', disiplin2='$_POST[disiplin2]', disiplin3='$_POST[disiplin3]', orientasi_p1='$_POST[orientasi_p1]', orientasi_p2='$_POST[orientasi_p2]', "
            . "orientasi_p3='$_POST[orientasi_p3]', date_kompetensi='$date'");
    header('location:../../page-view?' . paramEncrypt('module=validasi-pegawai&act=list-validasi-pegawai'));
} elseif ($page == 'validasi-pegawai' and $action == 'update-kompetensi') {
    bukainput("update tm_kompetensi set menganalisa1='$_POST[menganalisa1]', menganalisa2='$_POST[menganalisa2]', komunikasi1='$_POST[komunikasi1]', komunikasi2='$_POST[komunikasi2]', "
            . "kerjasama1='$_POST[kerjasama1]', kerjasama2='$_POST[kerjasama2]', kecerdasan1='$_POST[kecerdasan1]', kecerdasan2='$_POST[kecerdasan2]', kecerdasan3='$_POST[kecerdasan3]', "
            . "fokus1='$_POST[fokus1]', fokus2='$_POST[fokus2]', fokus3='$_POST[fokus3]', tanggung1='$_POST[tanggung1]', tanggung2='$_POST[tanggung2]', tanggung3='$_POST[tanggung3]', "
            . "tanggung4='$_POST[tanggung4]', orientasi_k1='$_POST[orientasi_k1]', orientasi_k2='$_POST[orientasi_k2]', inisiatif1='$_POST[inisiatif1]', inisiatif2='$_POST[inisiatif2]', "
            . "disiplin1='$_POST[disiplin1]', disiplin2='$_POST[disiplin2]', disiplin3='$_POST[disiplin3]', orientasi_p1='$_POST[orientasi_p1]', orientasi_p2='$_POST[orientasi_p2]', "
            . "orientasi_p3='$_POST[orientasi_p3]' where id_kompetensi='$id'");
    header('location:../../page-view?' . paramEncrypt('module=validasi-pegawai&act=list-validasi-pegawai'));
}

//untuk validasi penilaian
elseif ($page == 'validasi-pegawai' and $action == 'add-penilaian') {
    $autonumber = nokiamat('id_penilaian', 'tm_penilaian');
    $date = TanggalAkhirBulanKemarin();
//    $autonumber = "VPNL-" . substr($id, 5) . FormatTgl('Ymd', $date);
    if ($_POST['tunjangan_val'] == '') {
        echo "<script>window.alert('Mav anda belum memasukan Penilaian, silakan klik simpan jika nilai validasi sudah di konversi ke Rupiah !');close();</script>";
    } elseif ($_POST['id_penyerapan'] == '') {
        echo "<script>window.alert('Mav Penyerapan belum di input, silakan Hubungi Keuangan !');close();</script>";
    } else {
        if (getOne("select count(*) as cek from tm_penilaian where tm_penilaian.id_user='$id' and tm_penilaian.tanggal_penilaian='$date'") == 0) {
            bukainputval("insert into tm_penilaian set id_penilaian='$autonumber', id_user='$id', "
                    . "nskp='$_POST[nskp]', nprilaku='$_POST[nprilaku]', id_sanksi='$_POST[id_sanksi]', id_penyerapan='$_POST[id_penyerapan]', "
                    . "id_waktu_s='$_POST[id_waktu_s]', id_waktu_k='$_POST[id_waktu_k]', id_waktu_t='$_POST[id_waktu_t]',gaji_pokok='$_POST[gaji_pokok]', gaji_bruto='$_POST[gaji_bruto]',"
                    . "tunjangan='$_POST[tunjangan]', tunjangan_val='$_POST[tunjangan_val]', masa_kerja='$_POST[masa_kerja]', rumpun='$_POST[rumpun]', pajak='$_POST[pajak]',"
                    . "penilai='$_POST[penilai]', pendidikan='$_POST[pendidikan]', status_nikah='$_POST[status_nikah]',bpjs_ks='$_POST[bpjs_ks]',bpjs_jkk='$_POST[bpjs_jkk]', bpjs_ijht='$_POST[bpjs_ijht]', bpjs_jp='$_POST[bpjs_jp]', tanggal_penilaian='$date', date_real=now()");
            header('location:../../page-view?' . paramEncrypt('module=validasi-pegawai&act=list-validasi-pegawai'));
        } else {
            header('location:../../page-view?' . paramEncrypt('module=validasi-pegawai&act=list-validasi-pegawai'));
        }
    }
    //untuk validasi penilaian
}elseif ($page == 'validasi-pegawai' and $action == 'add-penilaian-pjlp') {
    $autonumber = nokiamat('id_penilaian', 'tm_penilaian_pjlp');
    $date = TanggalAkhirBulanKemarin();
//    $autonumber = "VPNL-" . substr($id, 5) . FormatTgl('Ymd', $date);
    if ($_POST['nprilaku'] == '' OR $_POST['npenyerapan'] == '' OR $_POST['nskp'] == '') {
        echo "<script>window.alert('Mav anda belum memasukan Penilaian');window.location.href='../../page-view?" . paramEncrypt('module=validasi-pegawai&act=list-validasi-pjlp') . "';</script>";
    } else {
        if (getOne("select count(*) as cek from tm_penilaian_pjlp where tm_penilaian_pjlp.id_user='$id' and tm_penilaian_pjlp.tanggal_penilaian='$date'") == 0) {
            bukainputval("insert into tm_penilaian_pjlp set id_penilaian='$autonumber', id_user='$id', "
                    . "nabsensi='$_POST[nskp]', nkinerja='$_POST[npenyerapan]', nkepatuhan='$_POST[nprilaku]', "
                    . "penilai='$_POST[penilai]', tanggal_penilaian='$date', date_real=now()");
            header('location:../../page-view?' . paramEncrypt('module=validasi-pegawai&act=list-validasi-pjlp'));
        } else {
            header('location:../../page-view?' . paramEncrypt('module=validasi-pegawai&act=list-validasi-pjlp'));
        }
    }
} elseif ($page == 'validasi-pegawai' and $action == 'update-penilaian') {
    if ($_POST['tunjangan_val'] == '') {
        echo "<script>window.alert('Mav anda belum memasukan Penilaian, silakan klik simpan jika nilai validasi sudah di konversi ke Rupiah !');close();</script>";
    } elseif ($_POST['id_penyerapan'] == '') {
        echo "<script>window.alert('Mav Penyerapan belum di input, silakan Hubungi Keuangan !');close();</script>";
    } else {
        bukainput("update tm_penilaian set nskp='$_POST[nskp]', nprilaku='$_POST[nprilaku]', id_sanksi='$_POST[id_sanksi]', id_penyerapan='$_POST[id_penyerapan]', "
                . "id_waktu_s='$_POST[id_waktu_s]', id_waktu_k='$_POST[id_waktu_k]', id_waktu_t='$_POST[id_waktu_t]',gaji_pokok='$_POST[gaji_pokok]', gaji_bruto='$_POST[gaji_bruto]',"
                . "tunjangan='$_POST[tunjangan]', tunjangan_val='$_POST[tunjangan_val]', masa_kerja='$_POST[masa_kerja]', rumpun='$_POST[rumpun]', pajak='$_POST[pajak]',"
                . "penilai='$_POST[penilai]', pendidikan='$_POST[pendidikan]', status_nikah='$_POST[status_nikah]', bpjs_ks='$_POST[bpjs_ks]', bpjs_jkk='$_POST[bpjs_jkk]', bpjs_ijht='$_POST[bpjs_ijht]', bpjs_jp='$_POST[bpjs_jp]', date_real=now() where id_penilaian='$id'");
        header('location:../../page-view?' . paramEncrypt('module=validasi-pegawai&act=list-validasi-pegawai'));
    }
}
?>


