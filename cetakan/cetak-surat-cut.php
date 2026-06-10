<?php

include '../conf/conf.php';
require('../libs/fpdf181/fpdf.php');
require_once ('../libs/aes-encrypt/function.php');
session_start();
$id = isset($_GET['id']) ? $_GET['id'] : null;
//cek apakah masi ada session login
//if (!isset($_SESSION['nip'])) {
//    header('location:index.php');
//}
//if (!isset($_SESSION['id_level'])) {
//    header('location:index.php');
//}
//if ($_GET['id'] == '' OR $cek == '' ) {
//    header('location:error.php');
//}
//query tampil
// $tm_surat = fetch_array(bukaquery("
prd("
SELECT tm_pegawai.nip,tm_pegawai.id_user, tm_pegawai.nama_pegawai, tm_cuti.nip_pengganti,tm_cuti.nip_pj,tm_cuti.nip_kasie, tm_cuti.tgl_permohonan, tm_cuti.jenis_cuti, tm_cuti.periode_cuti, tm_cuti.alamat_cuti,
                            tm_cuti.no_tlp, tm_cuti.alasan_cuti,tm_cuti.jumlah_hari,tm_cuti.tahun_cuti, tm_pegawai.id_unit, tm_pegawai.sub_bagian, tm_pegawai.id_kasatpel, tm_level.nama_level, tm_pegawai.tgl_masuk, tm_unit.nama_unit, tm_pegawai.sub_bagian
                            FROM tm_cuti
                            inner JOIN tm_pegawai on tm_cuti.id_user=tm_pegawai.id_user
                            INNER JOIN tm_unit ON tm_pegawai.id_unit=tm_unit.id_unit
                            INNER JOIN tm_user ON tm_pegawai.id_user=tm_user.id_user
                            INNER JOIN tm_level ON tm_user.id_level=tm_level.id_level
                            where tm_cuti.id_cuti='$id'");
$tanggal1 = date('Y-m-d', strtotime(substr($tm_surat['periode_cuti'], 0, 10)));
$tanggal2 = date('Y-m-d', strtotime(substr($tm_surat['periode_cuti'], 13, 10)));
//maping cuti
if ($tm_surat['jenis_cuti'] == 'CUTI TAHUNAN') {
    $cuti_tahunan = 'YA';
    $cuti_besar = '-';
    $cuti_sakit = '-';
    $cuti_melahirkan = '-';
    $cuti_alasan_penting = '-';
    $cuti_tanggungan_negara = '-';
} elseif ($tm_surat['jenis_cuti'] == 'CUTI BESAR') {
    $cuti_tahunan = '-';
    $cuti_besar = 'YA';
    $cuti_sakit = '-';
    $cuti_melahirkan = '-';
    $cuti_alasan_penting = '-';
    $cuti_tanggungan_negara = '-';
} elseif ($tm_surat['jenis_cuti'] == 'CUTI SAKIT') {
    $cuti_tahunan = '-';
    $cuti_besar = '-';
    $cuti_sakit = 'YA';
    $cuti_melahirkan = '-';
    $cuti_alasan_penting = '-';
    $cuti_tanggungan_negara = '-';
} elseif ($tm_surat['jenis_cuti'] == 'CUTI MELAHIRKAN') {
    $cuti_tahunan = '-';
    $cuti_besar = '-';
    $cuti_sakit = '-';
    $cuti_melahirkan = 'YA';
    $cuti_alasan_penting = '-';
    $cuti_tanggungan_negara = '-';
} elseif ($tm_surat['jenis_cuti'] == 'CUTI KARENA ALASAN PENTING') {
    $cuti_tahunan = '-';
    $cuti_besar = '-';
    $cuti_sakit = '-';
    $cuti_melahirkan = '-';
    $cuti_alasan_penting = 'YA';
    $cuti_tanggungan_negara = '-';
} elseif ($tm_surat['jenis_cuti'] == 'CUTI DI LUAR TANGGUNGAN NEGARA') {
    $cuti_tahunan = '-';
    $cuti_besar = '-';
    $cuti_sakit = '-';
    $cuti_melahirkan = '-';
    $cuti_alasan_penting = '-';
    $cuti_tanggungan_negara = 'YA';
}
$jumlah_sisa = getOne("SELECT (12-(sum(tm_cuti.jumlah_hari))) as jumlah
                                from tm_cuti
                                where tm_cuti.id_user='$tm_surat[id_user]' and tm_cuti.tahun_cuti='$tm_surat[tahun_cuti]' and jenis_cuti='CUTI TAHUNAN'");
//kop surat
$kop = fetch_array(bukaquery("SELECT * from setup"));

class PDF extends FPDF {

// Page header
    function Header() {
// Logo
//        $this->Image('../../img/logo.png', 7, 10, 30);
//
//// Query kop surat
//        $kop = mysql_fetch_array(bukaquery("select m_skpd.organisasi, m_skpd.alamat, m_skpd.tlp, m_skpd.fax, m_skpd.website, m_skpd.email FROM m_skpd where m_skpd.kd_skpd='$_SESSION[kd_skpd]'"));
//
//// Title
//        $this->Cell(20, 5, '', 0, 0, 'C');
//        $this->SetFont('Arial', '', 14);
//        $this->Cell(170, 5, 'PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA', 0, 1, 'C');
//        $this->SetFont('Arial', '', 13);
//        $this->Cell(20, 5, '', 0, 0, 'C');
//        $this->Cell(170, 5, 'DINAS KESEHATAN', 0, 1, 'C');
//        $this->SetFont('Arial', 'B', 12);
//        $this->Cell(20, 5, '', 0, 0, 'C');
//        $this->Cell(170, 5, strtoupper($kop['organisasi']), 0, 1, 'C');
//        $this->SetFont('Arial', '', 11);
//        $this->Cell(20, 5, '', 0, 0, 'C');
//        $this->Cell(170, 5, $kop['alamat'] . ' Tlp ' . $kop['tlp'] . ' Faksimile ' . $kop['fax'] . '', 0, 1, 'C');
//        $this->Cell(20, 5, '', 0, 0, 'C');
//        $this->Cell(170, 5, 'Website : ' . $kop['website'] . ' Email ' . $kop['email'], 0, 1, 'C');
//        $this->SetFont('Arial', '', 11);
//        $this->Cell(20, 5, '', 0, 0, 'C');
//        $this->Cell(170, 5, 'Jakarta', 0, 1, 'C');
//        $this->SetFont('Arial', '', 11);
//        $this->Cell(20, 5, '', 0, 0, 'C');
//        $this->Cell(170, 5, 'Kode Pos 10650 ', 0, 1, 'R');
// Line break
        $this->Ln(1);

        $this->SetFont('Times', 'BU', 10);
        for ($i = 0; $i < 1; $i++) {
            //$this->Cell(190, 0, '', 1, 1, 'C');
        }
        $this->Ln(1);
    }

// Page footer
    function Footer() {
// Position at 1.5 cm from bottom
        $this->SetY(-15);
// Arial italic 8
        $this->SetFont('Arial', 'I', 8);
// Page number
//        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    var $widths;
    var $aligns;

    function SetWidths($w) {
//Set the array of column widths
        $this->widths = $w;
    }

    function SetAligns($a) {
//Set the array of column alignments
        $this->aligns = $a;
    }

    function Row($data) {
//Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i][0]));
        $h = 5 * $nb;
//Issue a page break first if needed
        $this->CheckPageBreak($h);
//Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($data[$i][1]) ? $data[$i][1] : 'L';
//Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
//Draw the border
            $this->Rect($x, $y, $w, $h);
//Print the text
            $this->MultiCell($w, 5, $data[$i][0], 0, $a);
//Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
//Go to the next line
        $this->Ln($h);
    }

    function Row1($data) {
//Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i][0]));
        $h = 5 * $nb;
//Issue a page break first if needed
        $this->CheckPageBreak($h);
//Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($data[$i][1]) ? $data[$i][1] : 'L';
//Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
//Draw the border
// $this->Rect($x,$y,$w,$h);
//Print the text
            $this->MultiCell($w, 5, $data[$i][0], 0, $a);
//Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
//Go to the next line
        $this->Ln($h);
    }

    function Row2($data) {
//Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i][0]));
        $h = 3 * $nb;
//Issue a page break first if needed
        $this->CheckPageBreak($h);
//Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($data[$i][1]) ? $data[$i][1] : 'L';
//Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
//Draw the border
// $this->Rect($x,$y,$w,$h);
//Print the text
            $this->MultiCell($w, 2, $data[$i][0], 0, $a);
//Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
//Go to the next line
        $this->Ln($h);
    }

    function th($data) {
//Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i][0]));
        $h = 8 * $nb;
//Issue a page break first if needed
        $this->CheckPageBreak($h);
//Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($data[$i][1]) ? $data[$i][1] : 'L';
//Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
//Draw the border
            $this->Rect($x, $y, $w, $h);
//Print the text
            $this->MultiCell($w, 8, $data[$i][0], 0, $a);
//Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
//Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h) {
//If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w, $txt) {
//Computes the number of lines a MultiCell of width w will take
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l+=$cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }

}

// ambil dari url

$pdf = new PDF('P', 'mm', array(210,330));
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Image('../img/jayaraya.png', 10, 12, 27);
$pdf->Cell(20, 5, '', 0, 0, 'C');
$pdf->SetFont('Arial', '', 13);
$pdf->Cell(170, 5, 'PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(20, 5, '', 0, 0, 'C');
$pdf->Cell(170, 5, 'DINAS KESEHATAN', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(20, 5, '', 0, 0, 'C');
$pdf->Cell(170, 5, strtoupper($kop['nama_instansi']), 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(20, 5, '', 0, 0, 'C');
$pdf->Cell(170, 5, $kop['alamat_kop'] . ' Tlp ' . $kop['tlp'] . ' Faksimile ' . $kop['fax'] . '', 0, 1, 'C');
$pdf->Cell(20, 4, '', 0, 0, 'C');
$pdf->Cell(170, 4, 'Website : ' . $kop['website'] . ' Email ' . $kop['email'], 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(20, 4, '', 0, 0, 'C');
$pdf->Cell(170, 4, 'JAKARTA', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(20, 4, '', 0, 0, 'C');
$pdf->Cell(170, 4, 'Kode Pos ' . $kop['kode_pos'], 0, 1, 'R');
$pdf->Cell(190, 0, '', 1, 1, 'C');
$pdf->Ln(2);

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(125, 4, '', 0, 0, 'C');
$pdf->MultiCell(65, 4, 'Jakarta ' . konversiTanggal($tm_surat['tgl_permohonan']), 0, 'L');
$pdf->Ln(1);
$pdf->Cell(145, 4, '', 0, 0, 'C');
$pdf->MultiCell(65, 4, 'Kepada ', 0, 'L');
$pdf->Cell(125, 4, '', 0, 0, 'C');
$pdf->Cell(7, 4, 'Yth.', 0, 0, 'L');
$pdf->MultiCell(58, 4, 'Direktur RSUD Tanah Abang', 0, 'L');
$pdf->Cell(132, 4, '', 0, 0, 'C');
$pdf->MultiCell(58, 4, 'di Jakarta', 0, 'L');
$pdf->Ln();

$pdf->SetFont('Arial', '', 9);
$pdf->Cell(190, 4, 'FORMULIR PERMINTAAN DAN PEMBERIAN CUTI', 0, 1, 'C');
$pdf->Cell(90, 4, 'Nomor : ', 0, 0, 'R');
$pdf->Ln(4);

//data pegawai
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(190, 4, 'I. DATA PEGAWAI', 1, 1, 'L');
$pdf->Cell(20, 5, 'Nama', 1, 0, 'L');
$pdf->Cell(100, 5, $tm_surat['nama_pegawai'], 1, 0, 'L');
$pdf->Cell(25, 5, 'NIP', 1, 0, 'L');
$pdf->Cell(45, 5, $tm_surat['nip'], 1, 1, 'L');

$pdf->Cell(20, 5, 'Jabatan', 1, 0, 'L');
$pdf->Cell(100, 5, ucwords(strtolower($tm_surat['nama_level'])), 1, 0, 'L');
$pdf->Cell(25, 5, 'Masa Kerja', 1, 0, 'L');
$pdf->Cell(45, 5, MasaKerjaPenyebut($tm_surat['tgl_masuk']), 1, 1, 'L');

$pdf->Cell(20, 5, 'Unit Kerja', 1, 0, 'L');
$pdf->Cell(170, 5, ucwords(strtolower($kop['nama_instansi'])), 1, 1, 'L');
$pdf->Ln(2);

//jenis cuti
$pdf->Cell(190, 4, 'II. JENIS CUTI YANG DI AMBIL', 1, 1, 'L');
$pdf->Cell(45, 4, '1. Cuti Tahunan', 1, 0, 'L');
$pdf->Cell(50, 4, $cuti_tahunan, 1, 0, 'L');
$pdf->Cell(65, 4, '2. Cuti Besar', 1, 0, 'L');
$pdf->Cell(30, 4, $cuti_besar, 1, 1, 'L');

$pdf->Cell(45, 4, '3. Cuti Sakit', 1, 0, 'L');
$pdf->Cell(50, 4, $cuti_sakit, 1, 0, 'L');
$pdf->Cell(65, 4, '4. Cuti Melahirkan', 1, 0, 'L');
$pdf->Cell(30, 4, $cuti_melahirkan, 1, 1, 'L');

$pdf->Cell(45, 4, '5. Cuti Alasan Penting', 1, 0, 'L');
$pdf->Cell(50, 4, $cuti_alasan_penting, 1, 0, 'L');
$pdf->Cell(65, 4, '6. Cuti di Luar Tanggungan Negara', 1, 0, 'L');
$pdf->Cell(30, 4, $cuti_tanggungan_negara, 1, 1, 'L');
$pdf->Ln(2);

//alsan cuti
$pdf->Cell(190, 4, 'III. ALASAN CUTI', 1, 1, 'L');
$pdf->MultiCell(190, 4, $tm_surat['alasan_cuti'], 1, 'J');
$pdf->Ln(2);

//lamanya cuti
$pdf->Cell(190, 4, 'IV. LAMANYA CUTI', 1, 1, 'L');
$pdf->Cell(45, 4, 'Selama', 1, 0, 'L');
$pdf->Cell(50, 4, $tm_surat['jumlah_hari'] . " Hari", 1, 0, 'L');
$pdf->Cell(30, 4, 'Mulai Tanggal', 1, 0, 'L');
$pdf->Cell(65, 4, konversiTanggalBulan(getOne("select tanggal from tm_hari_cuti where id_cuti='$id' order by tanggal asc")) ." ".FormatTgl('Y',getOne("select tanggal from tm_hari_cuti where id_cuti='$id' order by tanggal asc")) ." s/d " . konversiTanggalBulan(getOne("select tanggal from tm_hari_cuti where id_cuti='$id' order by tanggal desc"))." ". FormatTgl('Y',getOne("select tanggal from tm_hari_cuti where id_cuti='$id' order by tanggal desc")), 1, 1, 'L');
$pdf->Ln(2);

//catatan cuti
$pdf->Cell(190, 4, 'V. CATATAN CUTI', 1, 1, 'L');
$pdf->Cell(60, 4, '1. CUTI TAHUNAN', 1, 0, 'L');
$pdf->Cell(35, 4, '', 1, 0, 'L');
$pdf->Cell(75, 4, '2. CUTI BESAR', 1, 0, 'L');
$pdf->Cell(20, 4, '', 1, 1, 'L');

$pdf->Cell(30, 4, 'Tahun', 1, 0, 'L');
$pdf->Cell(30, 4, 'Sisa', 1, 0, 'L');
$pdf->Cell(35, 4, 'Keterangan', 1, 0, 'L');
$pdf->Cell(75, 4, '3. CUTI SAKIT', 1, 0, 'L');
$pdf->Cell(20, 4, '', 1, 1, 'L');

$pdf->Cell(30, 4, 'N-2', 1, 0, 'L');
$pdf->Cell(30, 4, '', 1, 0, 'L');
$pdf->Cell(35, 4, '', 1, 0, 'L');
$pdf->Cell(75, 4, '4. CUTI MELAHIRKAN', 1, 0, 'L');
$pdf->Cell(20, 4, '', 1, 1, 'L');

$pdf->Cell(30, 4, 'N-1', 1, 0, 'L');
$pdf->Cell(30, 4, '', 1, 0, 'L');
$pdf->Cell(35, 4, '', 1, 0, 'L');
$pdf->Cell(75, 4, '5. CUTI KARENA ALASAN PENTING', 1, 0, 'L');
$pdf->Cell(20, 4, '', 1, 1, 'L');

$pdf->Cell(30, 4, 'N', 1, 0, 'L');
$pdf->Cell(30, 4, $jumlah_sisa . " Hari", 1, 0, 'L');
$pdf->Cell(35, 4, '', 1, 0, 'L');
$pdf->Cell(75, 4, '6. CUTI DILUAR TANGGUNGAN NEGARA', 1, 0, 'L');
$pdf->Cell(20, 4, '', 1, 1, '');
$pdf->Ln(2);

//alamat selama cuti
$pdf->Cell(190, 4, 'VI. ALAMAT SELAMA MENJALANKAN CUTI', 1, 1, 'L');
$pdf->MultiCell(190, 4, $tm_surat['alamat_cuti'], 1, 'J');
$pdf->Cell(30, 4, 'No Tlp', 1, 0, 'L');
$pdf->Cell(160, 4, $tm_surat['no_tlp'], 1, 1, 'L');
//ttd
$pdf->Cell(105, 5, 'Selama Menjalankan Cuti, Tugas Saya di Serahkan Kepada', 'LR', 0, 'C');
$pdf->Cell(85, 5, 'Hormat saya,', 'R', 1, 'C');
$pdf->Cell(105, 10, '', 'LR', 0, 'C');
$pdf->Cell(85, 10, '', 'R', 1, 'C');
$pdf->Cell(105, 5, getOne("select nama_pegawai from tm_pegawai where nip='$tm_surat[nip_pengganti]'"), 'LR', 0, 'C');
$pdf->Cell(85, 5, $tm_surat['nama_pegawai'], 'R', 1, 'C');
$pdf->Cell(105, 5, "NIP. " . $tm_surat['nip_pengganti'], 'LBR', 0, 'C');
$pdf->Cell(85, 5, "NIP. " . $tm_surat['nip'], 'LBR', 1, 'C');
$pdf->Ln(3);

//pertimbangan alasan langsung
$pdf->Cell(190, 4, 'VII. PERTIMBANGAN ATASAN LANGSUNG', 1, 1, 'L');
$pdf->Cell(45, 4, 'DISETUJUI', 1, 0, 'L');
$pdf->Cell(50, 4, "PERUBAHAN", 1, 0, 'L');
$pdf->Cell(45, 4, 'DITANGGUHKAN', 1, 0, 'L');
$pdf->Cell(50, 4, "TIDAK DISETUJUI" , 1, 1, 'L');
$pdf->Cell(45, 5, "", 1, 0, 'L');
$pdf->Cell(50, 5, "", 1, 0, 'L');
$pdf->Cell(45, 5, "", 1, 0, 'L');
$pdf->Cell(50, 5, "" , 1, 1, 'L');
////ttd
$pdf->Cell(95, 5, 'Atasan Langsung', 'LR', 0, 'C');
$pdf->Cell(95, 5, 'Kepala ' . $tm_surat['sub_bagian'], 'LR', 1, 'C');
$pdf->Cell(95, 5, '', 'LR', 0, 'C');
$pdf->Cell(95, 5, '', 'LR', 1, 'C');
$pdf->Cell(95, 5, getOne("select nama_pegawai from tm_pegawai where nip='$tm_surat[nip_pj]'"), 'LR', 0, 'C');
$pdf->Cell(95, 5, getOne("select nama_pegawai from tm_pegawai where nip='$tm_surat[nip_kasie]'"), 'LR', 1, 'C');
$pdf->Cell(95, 5, "NIP. ".$tm_surat['nip_pj'], 'LBR', 0, 'C');
$pdf->Cell(95, 5, "NIP. ".$tm_surat['nip_kasie'], 'LBR', 1, 'C');
$pdf->Ln(2);
//pertimbangan jabatan berwenang
$pdf->Cell(190, 4, 'VII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI', 1, 1, 'L');
$pdf->Cell(45, 4, 'DISETUJUI', 1, 0, 'L');
$pdf->Cell(50, 4, "PERUBAHAN", 1, 0, 'L');
$pdf->Cell(45, 4, 'DITANGGUHKAN', 1, 0, 'L');
$pdf->Cell(50, 4, "TIDAK DISETUJUI", 1, 1, 'L');
$pdf->Cell(45, 5, "", 1, 0, 'L');
$pdf->Cell(50, 5, "", 1, 0, 'L');
$pdf->Cell(45, 5, "", 1, 0, 'L');
$pdf->Cell(50, 5, "", 1, 1, 'L');
//ttd
$pdf->Cell(95, 5, '', 'LR', 0, 'C');
$pdf->Cell(95, 5, 'Direktur RSUD Tanah Abang', 'LR', 1, 'C');
$pdf->Cell(95, 8, '', 'LR', 0, 'C');
$pdf->Cell(95, 8, '', 'LR', 1, 'C');
$pdf->Cell(95, 5, getOne("select nama_pegawai from tm_pegawai where nip='$tm_surat[nip_kasie]'"), 'LR', 0, 'C');
$pdf->Cell(95, 5, $kop['direktur'], 'LR', 1, 'C');
$pdf->Cell(95, 5, $tm_surat['nip_kasie'], 'LBR', 0, 'C');
$pdf->Cell(95, 5, "NIP. " . $kop['nip_direktur'], 'LBR', 1, 'C');
$pdf->Ln(3);
$pdf->Output();
?>
