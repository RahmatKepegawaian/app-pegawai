<?php

$page = isset($url['module']) ? $url['module'] : null;
if ($page == "dashboard") {
    include "module/dashboard/dashboard.php";
} elseif ($page == "absensi-pegawai") {
    include "module/absensi/absensi-pegawai.php";
} elseif ($page == "kinerja-pegawai") {
    include "module/kinerja-pegawai/kinerja-pegawai.php";
} elseif ($page == "kinerja-pjlp") {
    include "module/kinerja-pjlp/kinerja-pegawai.php";
} elseif ($page == "simpeg") {
    include "module/simpeg/simpeg.php";
} elseif ($page == "master-data") {
    include "module/master-data/master-data.php";
} elseif ($page == "validasi-pegawai") {
    include "module/validasi-pegawai/validasi-pegawai.php";
} elseif ($page == "cuti-pegawai") {
    include "module/cuti-pegawai/cuti-pegawai.php";
} elseif ($page == "form-surat-peringatan") {
    include "module/form-sp/form-sp.php";
} elseif ($page == "form-surat-tugas") {
    include "module/form-tugas/form-tugas.php";
} elseif ($page == "diklat") {
    include "module/diklat/diklat.php";
} elseif ($page == "laporan") {
    include "module/laporan/laporan.php";
} elseif ($page == "perpustakaan") {
    include "module/perpustakaan/perpustakaan.php";
} elseif ($page == "configurasi") {
    include "module/hak-akses/hak-akses.php";
} elseif ($page == "helpdesk") {
    include "module/helpdesk/helpdesk.php";
}elseif ($page == "agenda-rapat") {
    include "module/agenda-rapat/agenda-rapat.php";
}elseif ($page == "tukar-shift") {
    include "module/tukar-shift/tukar-shift.php";
}elseif ($page == 'slip-gaji') {
    include "module/slip-gaji/slip-gaji.php";
}elseif ($page == 'auto-update') {
    include "module/autoupdate/auto-update.php";
}  else {
    include"eror.php";
}
?>