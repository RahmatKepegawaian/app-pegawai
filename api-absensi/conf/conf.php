<?php
require_once 'setting.php';


function php()
{
    global $version_php;
    return $version_php;
}

function host()
{
    global $db_hostname;
    return $db_hostname;
}

function fetch_array($sql)
{
    if (php() <= 6) {
        $while = mysql_fetch_array($sql);
    } else {
        $while = mysqli_fetch_array($sql);
    }
    return $while;
}

function real_escape($value)
{
    if (php() <= 6) {
        return mysql_real_escape_string($value);
    } else {
        return mysqli_real_escape_string($value);
    }
}

function connect($db_hostname, $db_username, $db_password)
{
    if (php() <= 6) {
        return mysql_connect($db_hostname, $db_username, $db_password);
    } else {
        return mysqli_connect($db_hostname, $db_username, $db_password);
    }
}

function select_db($db_name)
{
    if (php() <= 6) {
        return mysql_select_db($db_name);
    } else {
        return mysqli_select_db($db_name);
    }
}

function bukakoneksi()
{
    global $db_hostname, $db_username, $db_password, $db_name;
    $konektor = connect($db_hostname, $db_username, $db_password)
        or die("<font color=red><h3>Not Connected ..!!</h3></font>");
    $db_select = select_db($db_name)
        or die("<font color=red><h3>Cannot chose database..!!</h3></font>");
}

$sqlinjectionchars = array("=", "-", "'", "\"", "+"); //tambah sendiri
//function

function cleankar($dirty)
{
    if (get_magic_quotes_gpc()) {
        $clean = real_escape(stripslashes($dirty));
    } else {
        $clean = real_escape($dirty);
    }
    return preg_replace('/[^a-zA-Z0-9\s_ ]/', '', $clean);
}

function safe_query($format)
{
    if (php() <= 6) {
        $args = array_slice(func_get_args(), 1);
        $args = array_map('mysql_safe_string', $args);
        $query = vsprintf($format, $args);
        return mysql_query($query);
    } else {
        $args = array_slice(func_get_args(), 1);
        $args = array_map('mysqli_safe_string', $args);
        $query = vsprintf($format, $args);
        return mysqli_query($query);
    }
}

function validUrl($url)
{
    $format = "/^(http|https):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(([0-9]{1,5})?\/.*)?$/";
    $url = strtolower($url);
    if (preg_match($format, $url))
        return true;
    else
        return false;
}

function validangka($angka)
{
    if (!is_numeric($angka)) {
        return 0;
    } else {
        return $angka;
    }
}

function antisqlinjection($hal)
{
    /* if(!get_magic_quotes_gpc()){
      $_GET = array_map('mysql_real_escape_string', $_GET);
      $_POST = array_map('mysql_real_escape_string', $_POST);
      $_COOKIE = array_map('mysql_real_escape_string', $_COOKIE);
      }else{
      $_GET = array_map('stripslashes', $_GET);
      $_POST = array_map('stripslashes', $_POST);
      $_COOKIE = array_map('stripslashes', $_COOKIE);
      $_GET = array_map('mysql_real_escape_string', $_GET);
      $_POST = array_map('mysql_real_escape_string', $_POST);
      $_COOKIE = array_map('mysql_real_escape_string', $_COOKIE);
      }
      if (strlen($_SERVER['REQUEST_URI']) > 255 || strpos($_SERVER['REQUEST_URI'], "concat") ||
      strpos($_SERVER['REQUEST_URI'], "union") || strpos($_SERVER['REQUEST_URI'], "base64") ||
      strpos($_SERVER['REQUEST_URI'], "'")||strpos($_SERVER['REQUEST_URI'], "/")||
      strpos($_SERVER['REQUEST_URI'], "*")||strpos($_SERVER['REQUEST_URI'], ";")||
      strpos($_SERVER['REQUEST_URI'], "/*")||strpos($_SERVER['REQUEST_URI'], "\\")||
      strpos($_SERVER['REQUEST_URI'], "}")||strpos($_SERVER['REQUEST_URI'], "$")||
      strpos($_SERVER['REQUEST_URI'], "{")||strpos($_SERVER['REQUEST_URI'], "@")||
      strpos($_SERVER['REQUEST_URI'], "[")||strpos($_SERVER['REQUEST_URI'], "]")||
      strpos($_SERVER['REQUEST_URI'], "(")||strpos($_SERVER['REQUEST_URI'], ")")||
      strpos($_SERVER['REQUEST_URI'], "|")||strpos($_SERVER['REQUEST_URI'], ",")||
      strpos($_SERVER['REQUEST_URI'], "<")||strpos($_SERVER['REQUEST_URI'], ">")||
      strpos($_SERVER['REQUEST_URI'], "`")||strpos($_SERVER['REQUEST_URI'], ":")||
      strpos($_SERVER['REQUEST_URI'], "+")||strpos($_SERVER['REQUEST_URI'], "-")||
      strpos($_SERVER['REQUEST_URI'], "^")||strpos($_SERVER['REQUEST_URI'], "#")||
      strpos($_SERVER['REQUEST_URI'], "!")||strpos($_SERVER['REQUEST_URI'], "-")||
      strpos($_SERVER['REQUEST_URI'], "='")||strpos($_SERVER['REQUEST_URI'], "=/")) {
      echo "<b>Harus disetujui : <br/>
      Dilarang keras melakukan hacking/membajak Software/Web ini dengan cara apapun.<br/>
      Bagi yang sengaja melakukan hacking/membajak softaware ini,<br/>
      kami sumpahi sial 1000 turunan,miskin sampai 500 turunan.<br/>
      Selalu mendapat kecelakaan sampai 400 turunan. Anak pertama<br/>
      nya cacat tidak punya kaki sampai 300 turunan. Susah cari jodoh<br/>
      sampai umur 50 tahun sampai 200 turunan. Ya Alloh maafkan kami <br/>
      karena telah berdoa buruk, semua ini kami lakukan karena kami ti<br/>
      dak pernah rela karya kami dihack/dibajak..</b> ";
      Zet($hal);
      @header("HTTP/1.1 414 Request-URI Too Long");
      @header("Status: 414 Request-URI Too Long");
      @header("Connection: Close");
      @exit;
      } */
}

function reportsqlinjection()
{
    /* if(!get_magic_quotes_gpc()){
      $_GET = array_map('mysql_real_escape_string', $_GET);
      $_POST = array_map('mysql_real_escape_string', $_POST);
      $_COOKIE = array_map('mysql_real_escape_string', $_COOKIE);
      }else{
      $_GET = array_map('stripslashes', $_GET);
      $_POST = array_map('stripslashes', $_POST);
      $_COOKIE = array_map('stripslashes', $_COOKIE);
      $_GET = array_map('mysql_real_escape_string', $_GET);
      $_POST = array_map('mysql_real_escape_string', $_POST);
      $_COOKIE = array_map('mysql_real_escape_string', $_COOKIE);
      }
      if (strlen($_SERVER['REQUEST_URI']) > 255 || strpos($_SERVER['REQUEST_URI'], "concat") ||
      strpos($_SERVER['REQUEST_URI'], "union") || strpos($_SERVER['REQUEST_URI'], "base64") ||
      strpos($_SERVER['REQUEST_URI'], "'")||strpos($_SERVER['REQUEST_URI'], "/")||
      strpos($_SERVER['REQUEST_URI'], "*")||strpos($_SERVER['REQUEST_URI'], ";")||
      strpos($_SERVER['REQUEST_URI'], "/*")||strpos($_SERVER['REQUEST_URI'], "\\")||
      strpos($_SERVER['REQUEST_URI'], "}")||strpos($_SERVER['REQUEST_URI'], "$")||
      strpos($_SERVER['REQUEST_URI'], "{")||strpos($_SERVER['REQUEST_URI'], "@")||
      strpos($_SERVER['REQUEST_URI'], "[")||strpos($_SERVER['REQUEST_URI'], "]")||
      strpos($_SERVER['REQUEST_URI'], "(")||strpos($_SERVER['REQUEST_URI'], ")")||
      strpos($_SERVER['REQUEST_URI'], "|")||strpos($_SERVER['REQUEST_URI'], ",")||
      strpos($_SERVER['REQUEST_URI'], "<")||strpos($_SERVER['REQUEST_URI'], ">")||
      strpos($_SERVER['REQUEST_URI'], "`")||strpos($_SERVER['REQUEST_URI'], ":")||
      strpos($_SERVER['REQUEST_URI'], "+")||strpos($_SERVER['REQUEST_URI'], "-")||
      strpos($_SERVER['REQUEST_URI'], "^")||strpos($_SERVER['REQUEST_URI'], "#")||
      strpos($_SERVER['REQUEST_URI'], "!")||strpos($_SERVER['REQUEST_URI'], "-")||
      strpos($_SERVER['REQUEST_URI'], "='")||strpos($_SERVER['REQUEST_URI'], "=/")) {
      echo "<b>Harus disetujui : <br/>
      Dilarang keras melakukan hacking/membajak Software/Web ini dengan cara apapun.<br/>
      Bagi yang sengaja melakukan hacking/membajak softaware ini,<br/>
      kami sumpahi sial 1000 turunan,miskin sampai 500 turunan.<br/>
      Selalu mendapat kecelakaan sampai 400 turunan. Anak pertama<br/>
      nya cacat tidak punya kaki sampai 300 turunan. Susah cari jodoh<br/>
      sampai umur 50 tahun sampai 200 turunan. Ya Alloh maafkan kami <br/>
      karena telah berdoa buruk, semua ini kami lakukan karena kami ti<br/>
      dak pernah rela karya kami dihack/dibajak..</b> ";
      echo"<meta http-equiv='refresh' content='2;?'>";
      @header("HTTP/1.1 414 Request-URI Too Long");
      @header("Status: 414 Request-URI Too Long");
      @header("Connection: Close");
      @exit;
      } */
}

function tutupkoneksi()
{
    global $konektor;
    if (php() <= 6) {
        mysql_close($konektor);
    } else {
        mysqli_close($konektor);
    }
}

function bukaquery($sql)
{
    bukakoneksi();
    if (php() <= 6) {
        $result = mysql_query($sql)
            or die(mysql_error() . "<br/><font color=red><b>hmmmmmmm.....??????????</b>");
    } else {
        $result = mysqli_query($sql)
            or die(mysqli_error() . "<br/><font color=red><b>hmmmmmmm.....??????????</b>");
    }
    return $result;
}

function bukaquery2($sql)
{
    bukakoneksi();
    if (php() <= 6) {
        $result = mysql_query($sql);
    } else {
        $result = mysqli_query($sql);
    }
    return $result;
}

function read($sql)
{
    bukakoneksi();
    if (php() <= 6) {
        $result = mysql_query($sql);
    } else {
        $result = mysqli_query($sql);;
    }
    return $result;
}

function bukainput($sql)
{
    bukakoneksi();
    if (php() <= 6) {
        $result = mysql_query($sql)
            //or die(mysql_error()."<br/><font color=red><b>Gagal</b>, Ada data dengan primary key yang sama !");
            or die("<br/><script>alert('Gagal, Ada data dengan primary key yang sama !');window.location = 'javascript:history.go(-1)'</script>");
    } else {
        $result = mysqli_query($sql)
            //or die(mysql_error()."<br/><font color=red><b>Gagal</b>, Ada data dengan primary key yang sama !");
            or die("<br/><script>alert('Gagal, Ada data dengan primary key yang sama !');window.location = 'javascript:history.go(-1)'</script>");
    }
    return $result;
}

function bukainput2($sql)
{
    bukakoneksi();
    if (php() <= 6) {
        $result = mysql_query($sql)
            //or die(mysql_error()."<br/><font color=red><b>Gagal</b>, Ada data dengan primary key yang sama !");
            or die("<br/><script>alert('Gagal, Ada data dengan primary key yang sama !');window.location = 'javascript:history.go(-2)'</script>");
    } else {
        $result = mysqli_query($sql)
            //or die(mysql_error()."<br/><font color=red><b>Gagal</b>, Ada data dengan primary key yang sama !");
            or die("<br/><script>alert('Gagal, Ada data dengan primary key yang sama !');window.location = 'javascript:history.go(-2)'</script>");
    }
    return $result;
}

function bukainputcek($sql)
{
    bukakoneksi();
    if (php() <= 6) {
        $result = mysql_query($sql)
            //or die(mysql_error()."<br/><font color=red><b>Gagal</b>, Ada data dengan primary key yang sama !");
            or die("<br/><script>alert('Gagal, Data Ini Sudah Pernah Di Buat !');window.location = 'javascript:history.go(-1)'</script>");
    } else {
        $result = mysqli_query($sql)
            //or die(mysql_error()."<br/><font color=red><b>Gagal</b>, Ada data dengan primary key yang sama !");
            or die("<br/><script>alert('Gagal, Data Ini Sudah Pernah Di Buat !');window.location = 'javascript:history.go(-1)'</script>");
    }
    return $result;
}

function hapusinput($sql)
{
    bukakoneksi();
    if (php() <= 6) {
        $result = mysql_query($sql)
            or die("<br/><script>alert('Gagal, Data masih dipakai di tabel lain !');window.location = 'javascript:history.go(-1)'</script>");
    } else {
        $result = mysqli_query($sql)
            or die("<br/><script>alert('Gagal, Data masih dipakai di tabel lain !');window.location = 'javascript:history.go(-1)'</script>");
    }
    return $result;
}


function konversiTgl($tanggal)
{
    list($thn, $bln, $tgl) = explode('-', $tanggal);
    $tmp = $tgl . "-" . $bln . "-" . $thn;
    return $tmp;
}

function konversiBulan($bln)
{
    switch ($bln) {
        case "01":
            $bulan = "Januari";
            break;
        case "02":
            $bulan = "Februari";
            break;
        case "03":
            $bulan = "Maret";
            break;
        case "04":
            $bulan = "April";
            break;
        case "05":
            $bulan = "Mei";
            break;
        case "06":
            $bulan = "Juni";
            break;
        case "07":
            $bulan = "Juli";
            break;
        case "08":
            $bulan = "Agustus";
            break;
        case "09":
            $bulan = "September";
            break;
        case "10":
            $bulan = "Oktober";
            break;
        case "11":
            $bulan = "Nopember";
            break;
        case "12":
            $bulan = "Desember";
            break;
        default:
            $bulan = "Tidak Sesuai";
    }
    return $bulan;
}

function TanggalAkhirBulanKemarin()
{
    $tampil = mktime(0, 0, 0, date("m") - 1, date("d"), date("Y"));
    return date('Y-m-t', $tampil);
}

function konversiTanggal($tanggal)
{
    list($thn, $bln, $tgl) = explode('-', $tanggal);
    $tmp = $tgl . " " . konversiBulan($bln) . " " . $thn;
    return $tmp;
}

function konversiBulanTahun($tanggal)
{
    list($thn, $bln, $tgl) = explode('-', $tanggal);
    $tmp = konversiBulan($bln) . " " . $thn;
    return $tmp;
}

function konversiTanggalBulan($tanggal)
{
    list($thn, $bln, $tgl) = explode('-', $tanggal);
    $tmp = $tgl . " " . konversiBulan($bln);
    return $tmp;
}

function formatDuit($duit)
{
    return "Rp. " . number_format($duit, 0, ",", ".") . ",-";
}

function formatDuit2($duit)
{
    return number_format($duit, 0, ",", ".") . "";
}

function formatDuit3($duit)
{
    return number_format($duit, 2, ",", ".") . "";
}

function formatDec($duit)
{
    return round($duit, 3);
}

function formatPembulatan($duit)
{
    return round($duit, 0);
}

function formatDuit2Terbilang($duit)
{
    return round($duit, 0);
}

function formatRound($duit)
{
    return str_replace(".", ",", round($duit, 5));
}

function JumlahBaris($result)
{
    if (php() <= 6) {
        return mysql_num_rows($result);
    } else {
        return mysqli_num_rows($result);
    }
}

function getOne($sql)
{
    $hasil = bukaquery($sql);
    list($result) = fetch_array($hasil);
    return $result;
}

function kelamin($jk)
{
    if ($jk == 'P') {
        $result = "Perempuan";
    } else {
        $result = "Laki-Laki";
    }
    return $result;
}

function cekKosong($sql)
{
    if (php() <= 6) {
        $jum = mysql_num_rows($sql);
    } else {
        $jum = mysqli_num_rows($sql);
    }
    if ($jum == 0)
        return true;
    else
        return false;
}

function loadTgl()
{
    echo "<option>-&nbsp</option>";
    for ($tgl = 1; $tgl <= 31; $tgl++) {
        $tgl_leng = strlen($tgl);
        if ($tgl_leng == 1)
            $i = "0" . $tgl;
        else
            $i = $tgl;
        echo "<option value=$i>$i</option>";
    }
}

function loadTglnow()
{
    $tglsekarang = date('d');
    echo "<option>" . $tglsekarang . "</option>";
    for ($tgl = 1; $tgl <= 31; $tgl++) {
        $tgl_leng = strlen($tgl);
        if ($tgl_leng == 1)
            $i = "0" . $tgl;
        else
            $i = $tgl;
        echo "<option value=$i>$i</option>";
    }
}

function loadTgl2()
{
    //echo "<option>-&nbsp</option>";
    for ($tgl = 1; $tgl <= 31; $tgl++) {
        $tgl_leng = strlen($tgl);
        if ($tgl_leng == 1)
            $i = "0" . $tgl;
        else
            $i = $tgl;
        echo "<option value=$i>$i</option>";
    }
}

function loadBln($placeholder)
{
    echo "<option>$placeholder</option>";
    for ($bln = 1; $bln <= 12; $bln++) {
        $bln_leng = strlen($bln);
        if ($bln_leng == 1)
            $i = "0" . $bln;
        else
            $i = $bln;
        echo "<option value=$i>" . konversiBulan($i) . "</option>";
    }
}

function updateloadBln($x)
{
    for ($bln = 1; $bln <= 12; $bln++) {
        $bln_leng = strlen($bln);
        $i = "0" . $bln;
        if ($x == $i) {
            echo "<option value=" . $x . " selected=" . $x . ">" . konversiBulan($i) . "</option>";
        } else {
            //            $i = $bln;
            echo "<option value=" . $i . ">" . konversiBulan($i) . "</option>";
        }
    }
}

function loadBlnnow()
{
    $blnsekarang = date('m');
    echo "<option>$blnsekarang</option>";
    for ($bln = 1; $bln <= 12; $bln++) {
        $bln_leng = strlen($bln);
        if ($bln_leng == 1)
            $i = "0" . $bln;
        else
            $i = $bln;
        echo "<option value=$i>$i</option>";
    }
}

function loadBln2()
{
    //echo "<option>$placeholder</option>";
    for ($bln = 1; $bln <= 12; $bln++) {
        $bln_leng = strlen($bln);
        if ($bln_leng == 1)
            $i = "0" . $bln;
        else
            $i = $bln;
        echo "<option value=$i>$i</option>";
    }
}

function loadThn()
{
    $thnini = date('Y');
    echo "<option>-&nbsp</option>";
    for ($thn = $thnini; $thn >= 2015; $thn--) {
        $thn_leng = strlen($thn);
        if ($thn_leng == 1)
            $i = "0" . $thn;
        else
            $i = $thn;
        echo "<option value=$i>$i</option>";
    }
}

function loadThnnow()
{
    $thnini = date('Y');
    //echo "<option>-&nbsp</option>";
    for ($thn = $thnini; $thn >= 1960; $thn--) {
        $thn_leng = strlen($thn);
        if ($thn_leng == 1)
            $i = "0" . $thn;
        else
            $i = $thn;
        echo "<option value=$i>$i</option>";
    }
}

function loadThn2()
{
    $thnini = date('Y');
    echo "<option>-&nbsp</option>";
    for ($thn = $thnini + 30; $thn >= 1960; $thn--) {
        $thn_leng = strlen($thn);
        if ($thn_leng == 1)
            $i = "0" . $thn;
        else
            $i = $thn;
        echo "<option value=$i>$i</option>";
    }
}

function loadThn3()
{
    $thnini = date('Y');
    //echo "<option>-&nbsp</option>";
    for ($thn = $thnini + 30; $thn >= 1960; $thn--) {
        $thn_leng = strlen($thn);
        if ($thn_leng == 1)
            $i = "0" . $thn;
        else
            $i = $thn;
        echo "<option value=$i>$i</option>";
    }
}

function loadThn4()
{
    $thnini = date('Y');
    //echo "<option>-&nbsp</option>";
    for ($thn = $thnini; $thn >= 1960; $thn--) {
        $thn_leng = strlen($thn);
        if ($thn_leng == 1)
            $i = "0" . $thn;
        else
            $i = $thn;
        echo "<option value=$i>$i</option>";
    }
}

function loadJam()
{
    //echo "<option selected>-----&nbsp</option>";
    for ($jam = 0; $jam <= 23; $jam++) {
        $jam_leng = strlen($jam);
        if ($jam_leng == 1)
            $i = "0" . $jam;
        else
            $i = $jam;
        echo "<option value=$i>$i</option>";
    }
}

function loadMenit()
{
    //echo "<option selected>-----&nbsp</option>";
    for ($menit = 0; $menit <= 60; $menit++) {
        $menit_leng = strlen($menit);
        if ($menit_leng == 1)
            $i = "0" . $menit;
        else
            $i = $menit;
        echo "<option value=$i>$i</option>";
    }
}



function hariindo($x)
{
    $hari = FormatTgl("D", $x);

    switch ($hari) {
        case 'Sun':
            $hari_ini = "Minggu";
            break;

        case 'Mon':
            $hari_ini = "Senin";
            break;

        case 'Tue':
            $hari_ini = "Selasa";
            break;

        case 'Wed':
            $hari_ini = "Rabu";
            break;

        case 'Thu':
            $hari_ini = "Kamis";
            break;

        case 'Fri':
            $hari_ini = "Jumat";
            break;

        case 'Sat':
            $hari_ini = "Sabtu";
            break;

        default:
            $hari_ini = "Tidak di ketahui";
            break;
    }

    return $hari_ini;
}

function FormatTgl($format, $tanggal)
{
    return date($format, strtotime($tanggal));
}

function konversitanggalteks($tanggal)
{
    list($thn, $bln, $tgl) = explode('-', $tanggal);
    $tmp = "tanggal " . penyebut($tgl) . " " . "bulan " . konversiBulan($bln) . " " . "tahun " . penyebut($thn) . " ";
    return $tmp;
}

function HitungTelat($nip, $id_unit)
{
    $tgl = TanggalAkhirBulanKemarin();
    $from = FormatTgl('00-m-Y', $tgl);
    $to = FormatTgl('t-m-Y', $tgl);
    $badgenumber = getOne("select log_finger from tm_pegawai where nip='$nip'");
    while (strtotime($from) < strtotime($to)) {
        $from = mktime(0, 0, 0, date("m", strtotime($from)), date("d", strtotime($from)) + 1, date("Y", strtotime($from)));
        $from = date("Y-m-d ", $from);
        $tanggalan = konversitanggal($from);
        $tanggalanya = FormatTgl('d/m/Y', $from);
        $tanggalmin = date('d/m/Y', strtotime("-1 day", strtotime($from)));
        $tanggalplus = date('d/m/Y', strtotime("+1 day", strtotime($from)));
        //query in out
        $in = fetch_array_ODBC(bukaqueryODBC("SELECT CHECKINOUT.CHECKTIME FROM USERINFO left join CHECKINOUT on USERINFO.USERID=CHECKINOUT.USERID  
                                    where USERINFO.Badgenumber='$badgenumber' and CHECKINOUT.CHECKTYPE='I' and  CHECKINOUT.CHECKTIME LIKE '%$tanggalanya%' order by CHECKINOUT.CHECKTIME ASC"));
        $out = fetch_array_ODBC(bukaqueryODBC("SELECT CHECKINOUT.CHECKTIME FROM USERINFO left join CHECKINOUT on USERINFO.USERID=CHECKINOUT.USERID  
                                    where USERINFO.Badgenumber='$badgenumber' and CHECKINOUT.CHECKTYPE='O' and CHECKINOUT.CHECKTIME LIKE '%$tanggalanya%' order by CHECKINOUT.CHECKTIME DESC"));
        //jika masuk malem
        if (FormatTgl('H:i:s', $in['CHECKTIME']) < '23:59:00' and FormatTgl('H:i:s', $in['CHECKTIME']) > '18:00:00') {
            $out1 = fetch_array_ODBC(bukaqueryODBC("SELECT CHECKINOUT.CHECKTIME FROM USERINFO left join CHECKINOUT on USERINFO.USERID=CHECKINOUT.USERID  
                                                where USERINFO.Badgenumber='$badgenumber' and CHECKINOUT.CHECKTIME LIKE '%$tanggalplus%' order by CHECKINOUT.CHECKTIME ASC"));
            if ($in['CHECKTIME'] != '') {
                $absen_in = FormatTgl('H:i:s', $in['CHECKTIME']);
                $status = getOne("select tm_shift.nama_shift from set_shift inner join tm_shift on set_shift.id_absensi=tm_shift.id_absensi where tm_shift.bi < '$absen_in' and tm_shift.ai > '$absen_in' and set_shift.id_unit='$id_unit'");
                $jam_masuk = getOne("select tm_shift.jam_masuk from set_shift inner join tm_shift on set_shift.id_absensi=tm_shift.id_absensi where tm_shift.bi < '$absen_in' and tm_shift.ai > '$absen_in' and set_shift.id_unit='$id_unit'");
                if ($absen_in >= $jam_masuk) {
                    $jam_finger = (is_string($absen_in) ? strtotime($absen_in) : $absen_in);
                    $jam_in = (is_string($jam_finger) ? strtotime($jam_masuk) : $jam_masuk);
                    $hitung = $jam_finger - $jam_in;
                    $telat_in = floor($hitung / 600);
                } else {
                    $telat_in = '';
                }
            } else {
                $absen_in = '-';
                $status = '-';
                $jam_masuk = '';
            }
            if ($out1['CHECKTIME'] != '') {
                $absen_out = FormatTgl('H:i:s', $out1['CHECKTIME']);
                $jam_pulang = getOne("select tm_shift.jam_pulang from set_shift inner join tm_shift on set_shift.id_absensi=tm_shift.id_absensi where tm_shift.bo < '$absen_out' and tm_shift.ao > '$absen_out' and set_shift.id_unit='$id_unit'");
                if ($absen_out <= $jam_pulang) {
                    $jam_finger = (is_string($absen_out) ? strtotime($absen_out) : $absen_out);
                    $jam_out = (is_string($jam_pulang) ? strtotime($jam_pulang) : $jam_pulang);
                    $hitung = $jam_out - $jam_finger;
                    $telat_out = floor($hitung / 600);
                } else {
                    $telat_out = '';
                }
            } else {
                $absen_out = '-';
                $jam_pulang = '';
            }
            //jika normal
        } else {
            if ($in['CHECKTIME'] != '') {
                $absen_in = FormatTgl('H:i:s', $in['CHECKTIME']);
                $status = getOne("select tm_shift.nama_shift from set_shift inner join tm_shift on set_shift.id_absensi=tm_shift.id_absensi where tm_shift.bi < '$absen_in' and tm_shift.ai > '$absen_in' and set_shift.id_unit='$id_unit'");
                $jam_masuk = getOne("select tm_shift.jam_masuk from set_shift inner join tm_shift on set_shift.id_absensi=tm_shift.id_absensi where tm_shift.bi < '$absen_in' and tm_shift.ai > '$absen_in' and set_shift.id_unit='$id_unit'");
            } else {
                $absen_in = '-';
                $status = '-';
                $jam_masuk = '';
            }
            if ($out['CHECKTIME'] != '' and $absen_in != '-') {
                $absen_out = FormatTgl('H:i:s', $out['CHECKTIME']);
                $jam_pulang = getOne("select tm_shift.jam_pulang from set_shift inner join tm_shift on set_shift.id_absensi=tm_shift.id_absensi where tm_shift.bo < '$absen_out' and tm_shift.ao > '$absen_out' and set_shift.id_unit='$id_unit'");
            } else {
                $absen_out = '-';
                $jam_pulang = '';
            }
        }

        //hitung telat jam masuk
        if ($absen_in >= $jam_masuk) {
            $jam_finger = (is_string($absen_in) ? strtotime($absen_in) : $absen_in);
            $jam_in = (is_string($jam_masuk) ? strtotime($jam_masuk) : $jam_masuk);
            $hitung = $jam_finger - $jam_in;
            $telat_in_hitung = floor($hitung / 60);
            if ($telat_in_hitung > 5) {
                $telat_in = $telat_in_hitung;
            } else {
                $telat_in = '';
            }
        }
        //hitung pulang cepet
        if ($absen_out <= $jam_pulang) {
            $jam_finger = (is_string($absen_out) ? strtotime($absen_out) : $absen_out);
            $jam_out = (is_string($jam_pulang) ? strtotime($jam_pulang) : $jam_pulang);
            $hitung = $jam_out - $jam_finger;
            $telat_out_hitung = floor($hitung / 60);
        } else {
            $telat_out = '';
        }
        $telat = $telat_in + $telat_out;
        $totelat[] = $telat;
    }
    return array_sum($totelat);
}

function log($id, $table)
{
    $tgl = date('ymd');
    $query = "SELECT MAX(RIGHT($id, 10)) as max_id FROM $table Where $id LIKE '%$tgl%'";
    $id_max = getOne($query);
    $sort_num = $id_max;
    $sort_num++;
    $new_code = $tgl . sprintf("%04s", $sort_num++);
    return $new_code;
}
