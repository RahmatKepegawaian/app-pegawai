<?php

include '../conf/conf.php';
require('../libs/fpdf181/fpdf.php');
require_once ('../libs/aes-encrypt/function.php');

session_start();

// get id_keuangan
$id_keuangan = getOne("
    SELECT a.slipgaji_receiver_req
    FROM setup a
");

// if session is not exist or id_user not registered as id_keuangan
// redirect to login
if(!isset($_SESSION['id_user']) || $_SESSION['id_user'] != $id_keuangan) {
    header('location:index.php');
}
$id = isset($_GET['id']) ? $_GET['id'] : null;

// get data from url
$data = explode("-", $id);
$tmtcek = $data[1] . "-" . $data[0] . "-02";
$bulan_post = $data[0];
$tahun_post = $data[1];
$id_user = $data[2];

$slip_bulan = mktime(0, 0, 0, date("m"), date("d"), date("Y"));

$pegawai = mysqli_fetch_array(bukaquery("
    SELECT 
        tm_pegawai.id_user, tm_pegawai.nip, tm_pegawai.nama_pegawai, tm_pegawai.tgl_masuk, tm_penilaian.pendidikan,
        tm_unit.nama_unit, tm_penilaian.bpjs_ks, (tm_penilaian.gaji_bruto*tm_penilaian.bpjs_ks)+(tm_penilaian.gaji_bruto*tm_penilaian.bpjs_jkk)+(tm_penilaian.gaji_bruto*tm_penilaian.bpjs_ijht)+(tm_penilaian.gaji_bruto*tm_penilaian.bpjs_jp) AS potongan_bpjs,
        tm_level.nama_level, tm_penilaian.nskp+tm_penilaian.nprilaku+(tm_penyerapan.penyerapan*0.2) AS bhu, tm_penilaian.gaji_pokok, tm_penilaian.gaji_bruto, tm_penilaian.tunjangan, tm_penilaian.tunjangan_val, tm_penilaian.masa_kerja, 
        ((tm_waktu_s.j_hks*tm_honor_shift.hks)+(tm_waktu_s.j_hkm*tm_honor_shift.hkm)+(tm_waktu_s.j_hlp*tm_honor_shift.hlp)+(tm_waktu_s.j_hls*tm_honor_shift.hls)+(tm_waktu_s.j_hlm*tm_honor_shift.hlm)+(tm_waktu_s.j_hrp*tm_honor_shift.hrp)+(tm_waktu_s.j_hrs*tm_honor_shift.hrs)+(tm_waktu_s.j_hrm*tm_honor_shift.hrm)) AS honor,
        ((tm_waktu_k.alpha*0.05)*tm_penilaian.tunjangan_val)+((tm_waktu_k.sakit1*0.01)*tm_penilaian.tunjangan_val)+((tm_waktu_k.sakit2*0.02)*tm_penilaian.tunjangan_val)+((tm_waktu_k.izin*0.025)*tm_penilaian.tunjangan_val)+(((tm_waktu_k.telat/450)*0.025)*tm_penilaian.tunjangan_val) AS potongan_absensi,
        tm_penilaian.bpjs_jkk, tm_penilaian.bpjs_ijht, tm_penilaian.bpjs_jp,tm_penilaian.pajak, tm_penilaian.tanggal_penilaian
    FROM tm_penilaian
        INNER JOIN tm_pegawai ON tm_penilaian.id_user = tm_pegawai.id_user
        INNER JOIN tm_unit ON tm_pegawai.id_unit = tm_unit.id_unit
        INNER JOIN tm_honor_shift ON tm_unit.id_petugas = tm_honor_shift.id_petugas
        INNER JOIN tm_user ON tm_user.id_user = tm_pegawai.id_user
        INNER JOIN tm_level ON tm_user.id_level = tm_level.id_level
        INNER JOIN tm_waktu_k ON tm_penilaian.id_waktu_k = tm_waktu_k.id_waktu_k
        INNER JOIN tm_waktu_s ON tm_penilaian.id_waktu_s = tm_waktu_s.id_waktu_s
        INNER JOIN tm_waktu_t ON tm_penilaian.id_waktu_t = tm_waktu_t.id_waktu_t
        INNER JOIN tm_penyerapan ON tm_penilaian.id_penyerapan = tm_penyerapan.id_penyerapan
    WHERE tm_pegawai.status_pegawai='NON PNS'
        AND tm_pegawai.id_user='".$id_user."'
        AND Month(tm_penilaian.tanggal_penilaian)='".$bulan_post."'
        AND Year(tm_penilaian.tanggal_penilaian)='".$tahun_post."'
    GROUP BY tm_penilaian.id_user
    ORDER BY tm_pegawai.tgl_masuk ASC
"));

$cek_data_cuti_melahirkan = getOne("
    SELECT 
        a.id_cuti
    FROM tm_cuti a
    WHERE a.id_user = '".$pegawai['id_user']."'
        AND a.id_ketidakhadiran = 'AKT-000005'
    ORDER BY a.id_cuti DESC
");

if ($cek_data_cuti_melahirkan != '') {
    $mulai = getOne("select tanggal from tm_hari_cuti where id_cuti='$cek_data_cuti_melahirkan' order by tanggal ASC");
    $selesai = getOne("select tanggal from tm_hari_cuti where id_cuti='$cek_data_cuti_melahirkan' order by tanggal DESC");
    if (strtotime($tmtcek) >= strtotime($mulai) AND strtotime($tmtcek) <= strtotime($selesai)) {
        $tunjangan_val = $pegawai['potongan_bpjs'];
        $pph21 = 0;
    } else {
        $tunjangan_val = $pegawai['tunjangan_val'];
        $hitung_pph21 = PPH21($pegawai['gaji_bruto'] * $pegawai['bpjs_ijht'], $pegawai['gaji_bruto'] * $pegawai['bpjs_jp'], BiayaJabatan($pegawai['gaji_bruto'], $tunjangan_val, $pegawai['honor']), $pegawai['gaji_bruto'], $tunjangan_val, $pegawai['honor'], NilaiStatusPajak($pegawai['pajak']));
        if ($hitung_pph21 < 0) {
            $pph21 = 0;
        } else {
            $pph21 = $hitung_pph21;
        }
    }
} else {    
    $tunjangan_val = $pegawai['tunjangan_val'];
    $hitung_pph21 = PPH21($pegawai['gaji_bruto'] * $pegawai['bpjs_ijht'], $pegawai['gaji_bruto'] * $pegawai['bpjs_jp'], BiayaJabatan($pegawai['gaji_bruto'], $tunjangan_val, $pegawai['honor']), $pegawai['gaji_bruto'], $tunjangan_val, $pegawai['honor'], NilaiStatusPajak($pegawai['pajak']));
    if ($hitung_pph21 < 0) {
        $pph21 = 0;
    } else {
        $pph21 = $hitung_pph21;
    }
}
$nilaikinerja = (100-$pegawai['bhu'])*$pegawai['tunjangan']/100;
$bpjsketenagakerjaan = ($pegawai['gaji_bruto']*0.0024)+($pegawai['gaji_bruto']*0.0030)+($pegawai['gaji_bruto']*0.057)+($pegawai['gaji_bruto']*0.03);
$bpjsKesehatan = $pegawai['gaji_bruto']*0.01;
list($thn, $bln, $tgl) = explode('-', $pegawai['tanggal_penilaian']);
$pph212 = getOne("select pph21 from tt_pph21 where tt_pph21.bulan='$bln' and tt_pph21.tahun = '$thn' and tt_pph21.id_user='$pegawai[id_user]'");

$set_bendahara = fetch_array(bukaquery("
    SELECT
        b.nip,
        b.nama_pegawai
    FROM setup a
    INNER JOIN tm_pegawai b ON a.bendahara_pengeluaran = b.id_user
"));
$set_pejabat = fetch_array(bukaquery("SELECT tm_pegawai.nama_pegawai, tm_pegawai.nip FROM tm_pegawai where id_user in (select set_spj.ppk_keuangan from set_spj)"));

$kop = mysqli_fetch_array(bukaquery("SELECT setup.nama_instansi, setup.alamat_kop, setup.tlp, setup.fax, setup.email, setup.website, setup.kode_pos, setup.logo from setup"));
$pengasilankotor = $pegawai['gaji_bruto'] + $pegawai['tunjangan'] + $pegawai['honor'];
$pengurangan = $pegawai['potongan_absensi'] + $nilaikinerja + $bpjsketenagakerjaan +$bpjsKesehatan + $pph212;

class PDF extends FPDF {

    // Page header
    function Header() {
    
        // Line break
        $this->Ln(1);

        $this->SetFont('Times', 'BU', 12);
        for ($i = 0; $i < 1; $i++) {
            $this->Cell(190, 0, '', 0, 1, 'C');
        }
        $this->Ln(5);
    }

    // Page footer
    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
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
            $l += $cw[$c];
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

$pdf = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Image('../img/jayaraya.png', 7, 15, 30);
$pdf->Cell(20, 5, '', 0, 0, 'C');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(160, 5, 'PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(20, 5, '', 0, 0, 'C');
$pdf->Cell(160, 5, 'DINAS KESEHATAN', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(20, 5, '', 0, 0, 'C');
$pdf->Cell(160, 5, strtoupper($kop['nama_instansi']), 0, 1, 'C');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(20, 5, '', 0, 0, 'C');
$pdf->Cell(160, 5, $kop['alamat_kop'] . ' Tlp ' . $kop['tlp'] . ' Faksimile ' . $kop['fax'] . '', 0, 1, 'C');
$pdf->Cell(20, 5, '', 0, 0, 'C');
$pdf->Cell(170, 5, 'Website : ' . $kop['website'] . ' Email ' . $kop['email'], 0, 1, 'C');
$pdf->Cell(20, 5, '', 0, 0, 'C');
$pdf->Cell(160, 5, 'Jakarta', 0, 1, 'C');
$pdf->Cell(20, 5, '', 0, 0, 'C');
$pdf->Cell(160, 5, 'Kode Pos ' . $kop['kode_pos'], 0, 1, 'R');
$pdf->Cell(190, 0, '', 1, 1, 'C');
$pdf->Ln(1);

// set font
$pdf->SetFont('Arial', 'B', 16);

// set penomoran

$pdf->Cell(20, 8, '', 0, 0, 'C');
$pdf->Cell(160, 8, 'KETERANGAN PENGHASILAN', 0, 1, 'C');
$pdf->Cell(1, 0, '', 0, 0, 'C');
$pdf->Cell(190, 0, '', 1, 1, 'C');
$pdf->Ln(5);$pdf->Ln(5);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(35, 5, 'NIP', 0, 0, 'L');
$pdf->Cell(3, 5, ': ', 0, 0, 'L');
$pdf->Cell(20, 5, $pegawai['nip'], 0, 1, 'L');
$pdf->Cell(35, 5, 'NAMA', 0, 0, 'L');
$pdf->Cell(3, 5, ': ', 0, 0, 'L');
$pdf->Cell(20, 5, $pegawai['nama_pegawai'], 0, 1, 'L');
$pdf->Cell(35, 5, 'JABATAN', 0, 0, 'L');
$pdf->Cell(3, 5, ': ', 0, 0, 'L');
$pdf->Cell(20, 5, ucwords(strtolower($pegawai['nama_unit'])), 0, 1, 'L');
$pdf->Cell(35, 5, 'BULAN KERJA', 0, 0, 'L');
$pdf->Cell(3, 5, ': ', 0, 0, 'L');
$pdf->Cell(20, 5, '1 BULAN ('.strtoupper(konversiBulanTahun($tmtcek)).')', 0, 1, 'L');
$pdf->Cell(1, 3, '', 0, 0, 'C');
$pdf->Cell(190, 3, '', 0, 1, 'C');
$pdf->Cell(1, 0, '', 0, 0, 'C');
$pdf->Cell(190, 0, '', 1, 1, 'C');
$pdf->Ln(5);


$pdf->SetFont('Arial', 'BU', 10);
$pdf->Cell(45, 5, 'PENGHASILAN', 0, 0, 'L');
$pdf->Cell(43, 5, '', 0, 0, 'C');
$pdf->Cell(45, 5, 'POTONGAN', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(2);
$pdf->Cell(45, 5, 'Gaji Bruto', 0, 0, 'L');
$pdf->Cell(3, 5, ': ', 0, 0, 'L');
$pdf->Cell(20, 5, 'Rp. ' . formatDuit2($pegawai['gaji_bruto']), 0, 0, 'L');
$pdf->Cell(20, 5, '', 0, 0, 'C');
$pdf->Cell(48, 5, 'Potongan Absensi', 0, 0, 'L');
$pdf->Cell(3, 5, ': ', 0, 0, 'L');
$pdf->Cell(20, 5, 'Rp. ' . formatDuit2($pegawai['potongan_absensi']), 0, 1, 'L');

$pdf->Cell(45, 5, 'Tunjangan Kinerja', 0, 0, 'L');
$pdf->Cell(3, 5, ': ', 0, 0, 'L');
$pdf->Cell(20, 5, 'Rp. ' . formatDuit2($pegawai['tunjangan']), 0, 0, 'L');
$pdf->Cell(20, 5, '', 0, 0, 'C');
$pdf->Cell(30, 5, 'Presentase kinerja', 0, 0, 'L');
$pdf->Cell(18, 5, '('.$pegawai['bhu'].' %)', 0, 0, 'L');
$pdf->Cell(3, 5, ': ', 0, 0, 'L');
$pdf->Cell(20, 5, 'Rp. ' . formatDuit2($nilaikinerja), 0, 1, 'L');

$pdf->Cell(45, 5, 'Upah Jasa Pelayanan Shift', 0, 0, 'L');
$pdf->Cell(3, 5, ': ', 0, 0, 'L');
$pdf->Cell(20, 5, 'Rp. ' . formatDuit2($pegawai['honor']), 0, 0, 'L');
$pdf->Cell(20, 5, '', 0, 0, 'C');
$pdf->Cell(48, 5, 'BPJS Kesehatan', 0, 0, 'L');
$pdf->Cell(3, 5, ': ', 0, 0, 'L');
$pdf->Cell(20, 5, 'Rp. '.formatDuit2($bpjsKesehatan), 0, 1, 'L');

$pdf->Cell(45, 5, '', 0, 0, 'L');
$pdf->Cell(3, 5, ' ', 0, 0, 'L');
$pdf->Cell(20, 5, '', 0, 0, 'L');
$pdf->Cell(20, 5, '', 0, 0, 'C');
$pdf->Cell(48, 5, 'BPJS Ketenagakerjaan', 0, 0, 'L');
$pdf->Cell(3, 5, ': ', 0, 0, 'L');
$pdf->Cell(20, 5, 'Rp. '.formatDuit2($bpjsketenagakerjaan), 0, 1, 'L');

$pdf->Cell(45, 5, '', 0, 0, 'L');
$pdf->Cell(3, 5, ' ', 0, 0, 'L');
$pdf->Cell(20, 5, '', 0, 0, 'L');
$pdf->Cell(20, 5, '', 0, 0, 'C');
$pdf->Cell(48, 5, 'PPh21', 0, 0, 'L');
$pdf->Cell(3, 5, ': ', 0, 0, 'L');
$pdf->Cell(20, 5, 'Rp. '.formatDuit2($pph212), 0, 1, 'L');
$pdf->Ln(5);

$pdf->Cell(1, 0, '', 0, 0, 'C');
$pdf->Cell(190, 0, '', 1, 1, 'C');
$pdf->Ln(2);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(45, 5, 'Total Pendapatan', 0, 0, 'L');
$pdf->Cell(3, 5, ': ', 0, 0, 'L');
$pdf->Cell(20, 5, 'Rp. ' . formatDuit2($pengasilankotor), 0, 0, 'L');
$pdf->Cell(20, 5, '', 0, 0, 'C');
$pdf->Cell(48, 5, 'Total Potongan', 0, 0, 'L');
$pdf->Cell(3, 5, ': ', 0, 0, 'L');
$pdf->Cell(20, 5, 'Rp. ' . formatDuit2($pengurangan), 0, 1, 'L');
$pdf->Ln(2  );


$pdf->SetFont('Arial', '', 8);
$pdf->Cell(1, 0, '', 0, 0, 'C');
$pdf->Cell(190, 0, '', 1, 1, 'C');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(35, 10, 'Penerimaan Bersih', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(3, 10, ': ', 0, 0, 'L');
$pdf->Cell(147, 10, 'Rp. ' . formatDuit2($pengasilankotor - $pengurangan) . ' (Total Pendapatan - Total Potongan)', 0, 1, 'L');
$pdf->Cell(1, 0, '', 0, 0, 'C');
$pdf->MultiCell(190, 7, 'Terbilang :' . penyebut(formatDuit2Terbilang(($pengasilankotor - $pengurangan))) . ' Rupiah', 1, 'J');
$pdf->Ln(30);
//ttd
$pdf->SetFont('Arial', '', 10);
// $pdf->Cell(70, 5, 'Mengetahui', 0, 0, 'C');
$pdf->Cell(115, 5, '', 0, 0, 'C');
$pdf->Cell(70, 5, 'Jakarta, ' . konversiTanggal(date('Y-m-d', $slip_bulan)), 0, 1, 'C');


// $pdf->Cell(70, 5, 'Pejabat Penatausahaan Keuangan', 0, 0, 'C');
$pdf->Cell(115, 5, '', 0, 0, 'C');
$pdf->Cell(70, 5, 'Bendahara Pengeluaran', 0, 1, 'C');


// $pdf->Cell(70, 5, ucwords(strtolower($kop['nama_instansi'])), 0, 0, 'C');
$pdf->Cell(115, 5, '', 0, 0, 'C');
$pdf->Cell(70, 5, ucwords(strtolower($kop['nama_instansi'])), 0, 1, 'C');

$pdf->Ln(30);


// $pdf->Cell(70, 5, $set_pejabat['nama_pegawai'], 0, 0, 'C');
$pdf->Cell(115, 5, '', 0, 0, 'C');
$pdf->Cell(70, 5, $set_bendahara['nama_pegawai'], 0, 1, 'C');

// $pdf->Cell(70, 5, "NIP. " . $set_pejabat['nip'], 0, 0, 'C');
$pdf->Cell(115, 5, '', 0, 0, 'C');
$pdf->Cell(70, 5, "NIP. " . $set_bendahara['nip'], 0, 1, 'C');



$pdf->Output();
?>
