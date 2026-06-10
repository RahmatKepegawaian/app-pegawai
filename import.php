<?php

/*
  -- Source Code from My Notes Code (www.mynotescode.com)
  --
  -- Follow Us on Social Media
  -- Facebook : http://facebook.com/mynotescode/
  -- Twitter  : http://twitter.com/code_notes
  -- Google+  : http://plus.google.com/118319575543333993544
  --
  -- Terimakasih telah mengunjungi blog kami.
  -- Jangan lupa untuk Like dan Share catatan-catatan yang ada di blog kami.
 */

// Load file koneksi.php
include "koneksi.php";

if (isset($_POST['import'])) { // Jika user mengklik tombol Import
    $nama_file_baru = 'data.xlsx';

    // Load librari PHPExcel nya
    require_once 'libs/PHPExcel/PHPExcel.php';

    $excelreader = new PHPExcel_Reader_Excel2007();
    $loadexcel = $excelreader->load('tmp/' . $nama_file_baru); // Load file excel yang tadi diupload ke folder tmp
    $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true, true);

    // Buat query Insert
    $sql = $pdo->prepare("INSERT INTO siswa VALUES(:nis,:nama,:jk,:telp,:alamat)");

    $numrow = 1;
    foreach ($sheet as $row) {
        // Ambil data pada excel sesuai Kolom
        $id_user = $row['A']; // Ambil data id
        $nip = $row['B']; // Ambil data nip
        $nik = $row['C'];
        $nama = $row['D'];
        $tempat_lahir = $row['E'];
        $tgl_lahir = $row['F'];
        $jk = $row['G'];
        $alamat = $row['H'];
        $npwp = $row['I'];
        $norek = $row['J'];
        $pendidikan = $row['K'];
        $tmt = $row['L'];
        $status_nikah = $row['M'];
        $rumpun = $row['N'];
        $pajak = $row['O'];
        $id_unit = $row['P'];
        $bpjs_ks = $row['Q'];
        $bpjs_jkk = $row['R'];
        $bpjs_ijht = $row['S'];
        $bpjs_jp = $row['T'];
        $foto = $row['U'];
        $status_kepegawaian = $row['V'];
        $sub_bagian = $row['W'];
        $log_finger = $row['X'];
        $aktif = $row['Y'];

        // Cek jika semua data tidak diisi
        if (empty($nis) && empty($nama) && empty($jenis_kelamin) && empty($telp) && empty($alamat))
            continue; // Lewat data pada baris ini (masuk ke looping selanjutnya / baris selanjutnya)

            
// Cek $numrow apakah lebih dari 1
        // Artinya karena baris pertama adalah nama-nama kolom
        // Jadi dilewat saja, tidak usah diimport
        if ($numrow > 1) {
            // Proses simpan ke Database
            $sql->bindParam(':nis', $nis);
            $sql->bindParam(':nama', $nama);
            $sql->bindParam(':jk', $jenis_kelamin);
            $sql->bindParam(':telp', $telp);
            $sql->bindParam(':alamat', $alamat);
            $sql->execute(); // Eksekusi query insert
        }

        $numrow++; // Tambah 1 setiap kali looping
    }
}

header('location: index.php'); // Redirect ke halaman awal
?>
