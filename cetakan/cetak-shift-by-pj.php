<?php
require_once '../conf/conf.php';
require_once '../libs/fpdf181/fpdf.php';
require_once '../libs/aes-encrypt/function.php';

class PDF extends FPDF {

    public $url_api_master_data;
    public $id_unit;
    public $month;
    public $year;
    public $widths;
    public $aligns;

    function __construct($url_api_master_data, $id_unit, $month, $year) {
        parent::__construct();
        $this->url_api_master_data = $url_api_master_data;
        $this->id_unit = $id_unit;
        $this->month = $month;
        $this->year = $year;
    }

    function SetWidths($w) {
        $this->widths = $w;
    }

    function SetAligns($a) {
        $this->aligns = $a;
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

    function konversiBulan($bln) {
        switch ($bln) {
            case "1": $bulan = "JANUARI";
                break;
            case "2": $bulan = "FEBRUARI";
                break;
            case "3": $bulan = "MARET";
                break;
            case "4": $bulan = "APRIL";
                break;
            case "5": $bulan = "MEI";
                break;
            case "6": $bulan = "JUNI";
                break;
            case "7": $bulan = "JULI";
                break;
            case "8": $bulan = "AGUSTUS";
                break;
            case "9": $bulan = "SEPTEMBER";
                break;
            case "10": $bulan = "OKTOBER";
                break;
            case "11": $bulan = "NOVEMBER";
                break;
            case "12": $bulan = "DESEMBER";
                break;
            default : $bulan = "-";
        }
        return $bulan;
    }

    function get_data() {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url_api_master_data."?action=get_data_pengaturan_shift&id_unit=".$this->id_unit."&month=".$this->month."&year=".$this->year);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = json_decode(curl_exec($ch), true);
        return $result;
    }

    function get_nama_unit($id_unit) {

        $sql = bukaquery2("
            SELECT
                nama_unit
            FROM tm_unit
            WHERE id_unit = '".$id_unit."'
        ");

        return fetch_array($sql)['nama_unit'];
    }

    function generate_header_size($days_in_month) {

        $width_nm = 50;
        $width_date = 8;

        $result = array($width_nm);
        for ($i=0; $i < $days_in_month; $i++) { 
            array_push($result, $width_date);
        }
        return $result;
    }

    function generate_head_table($days_in_month) {

        $result = array();
        array_push(
            $result, array(
                "NAMA", "C"
        ));
        for ($i=1; $i <= $days_in_month; $i++) { 
            array_push($result, array(
                $i, "C"
            ));
        }

        return $result;
    }

    function start() {

        $data = $this->get_data();
        $nama_unit = $this->get_nama_unit($this->id_unit);
        $nama_bulan = $this->konversiBulan($this->month);
        $arr_head_size = $this->generate_header_size($data['data']['days_in_month']);
        $arr_head_table = $this->generate_head_table($data['data']['days_in_month']);

        $this->AliasNbPages();
        $this->AddPage('L');
        $this->Image('../img/icon.png', 60, 10, 30, 30);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 30, 'JADWAL DINAS '.$nama_unit.' BULAN '.$nama_bulan.' '.$this->year, 0, 1, 'C');
        $this->SetFont('Arial', 'B', 11);
        $this->Ln(4);
        $this->SetWidths($arr_head_size);
        $this->Row($arr_head_table);
        $this->SetFont('Arial', '', 11);
        for ($i=0; $i < count($data['data']['data']); $i++) { 
            
            $result = array();

            array_push($result, array(
                $data['data']['data'][$i]['nama_pegawai'], "L"
            ));

            $next_date = "";
            $temp = "";

            for ($j=0; $j < count($data['data']['data'][$i]['shift']); $j++) {
                
                $next_date = $data['data']['data'][$i]['shift'][($j+1 < count($data['data']['data'][$i]['shift']) ) ? ($j + 1) : $j]['date'];
                $date = $data['data']['data'][$i]['shift'][$j]['date'];

                // apabila tanggal hari ini sama dengan tanggal besok
                // berarti longshift
                if($date == $next_date && $j != 0 && ($j+1 <count($data['data']['data'][$i]['shift']))) {

                    $temp .= $data['data']['data'][$i]['shift'][$j]['nama_absensi'];
                } else {

                    $temp .= $data['data']['data'][$i]['shift'][$j]['nama_absensi'];

                    array_push($result, array(
                        $temp, "C"
                    ));
    
                    $temp = "";
                }
            }

            $this->Row($result);
        }

        $this->Ln();
        $this->SetFont('Arial', '', 7);
        $this->Cell(0, 7, 'Keterangan', 0, 1);
        $pembagi = ceil(count($data['data']['shift_options']) / 2);

        for ($i=0; $i < $pembagi; $i++) {
            
            $this->Cell(6, 4, $data['data']['shift_options'][$i]['nama_shift'], 0, 0, 'L');
            $this->Cell(50, 4, ": ".$data['data']['shift_options'][$i]['desc_shift']." (".$data['data']['shift_options'][$i]['jam_masuk']."-".$data['data']['shift_options'][$i]['jam_pulang'].")", 0, 0, 'L');

            if($i + $pembagi < count($data['data']['shift_options'])) {

                $this->Cell(6, 4, $data['data']['shift_options'][$i + $pembagi]['nama_shift'], 0, 0, 'L');
                $this->Cell(50, 4, ": ".$data['data']['shift_options'][$i + $pembagi]['desc_shift']." (".$data['data']['shift_options'][$i]['jam_masuk']."-".$data['data']['shift_options'][$i]['jam_pulang'].")", 0, 1, 'L');
            }
            
        }
        $this->Output();
    }
}

$id_unit = $_GET['id_unit'];
$month = $_GET['month'];
$year = $_GET['year'];

$pdf = new PDF($url_api_master_data, $id_unit, $month, $year);
$pdf->start();
?>