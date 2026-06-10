<?php

date_default_timezone_set('Asia/Jakarta');
//koneksi database
$db_hostname = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "ekincpy";

$user_odbc = 'ekin';
$pass_odbc = '';

//version php
$version_php = "5";

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

function simpanLog($sql)
{
    bukakoneksi();
    if (php() <= 6) {
        $result = mysql_query($sql)
            //or die(mysql_error()."<br/><font color=red><b>Gagal</b>, Ada data dengan primary key yang sama !");
            or die();
    } else {
        $result = mysqli_query($sql)
            //or die(mysql_error()."<br/><font color=red><b>Gagal</b>, Ada data dengan primary key yang sama !");
            or die();
    }
    return $result;
}

function bukainputval($sql)
{
    bukakoneksi();
    if (php() <= 6) {
        $result = mysql_query($sql)
            //or die(mysql_error()."<br/><font color=red><b>Gagal</b>, Ada data dengan primary key yang sama !");
            or die("<br/><script>alert('Maaf ada beberapa data yang belum di validasi PJ, silakah hub Pj terkait !');window.location = 'javascript:history.go(-1)'</script>");
    } else {
        $result = mysqli_query($sql)
            //or die(mysql_error()."<br/><font color=red><b>Gagal</b>, Ada data dengan primary key yang sama !");
            or die("<br/><script>alert('Maaf ada beberapa data yang belum di validasi PJ, silakah hub Pj terkait !');window.location = 'javascript:history.go(-1)'</script>");
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

function Tambah($tabelname, $attrib, $pesan)
{

    $command = bukainput("INSERT INTO " . $tabelname . " VALUES (" . $attrib . ")");
    echo "<img src='images/simpan.gif' />&nbsp;&nbsp; Data $pesan berhasil disimpan";
    return $command;
}

function Tambah2($tabelname, $attrib, $pesan)
{

    $command = bukainput("INSERT INTO " . $tabelname . " VALUES (" . $attrib . ")");
    echo "<img src='images/simpan.gif' />&nbsp;&nbsp; <font size='9'>Data $pesan berhasil disimpan</font>";
    return $command;
}

function InsertData($tabelname, $attrib)
{
    $command = bukaquery("INSERT INTO " . $tabelname . " VALUES (" . $attrib . ")");
    return $command;
}

function InsertData2($tabelname, $attrib)
{
    $command = bukaquery2("INSERT INTO " . $tabelname . " VALUES (" . $attrib . ")");
    return $command;
}

function EditData($tabelname, $attrib)
{
    $command = bukaquery("UPDATE " . $tabelname . " SET " . $attrib . " ");
    return $command;
}

function Ubah($tabelname, $attrib, $pesan)
{
    $command = bukaquery("UPDATE " . $tabelname . " SET " . $attrib . " ");
    echo "<img src='images/simpan.gif' />&nbsp;&nbsp; Data $pesan berhasil diubah";
    return $command;
}

function edit($sql)
{
    bukakoneksi();
    if (php() <= 6) {
        $result = mysql_query($sql);
    } else {
        $result = mysqli_query($sql);
    }
    return $result;
}

function Ubah2($tabelname, $attrib)
{
    $command = bukaquery("UPDATE " . $tabelname . " SET " . $attrib . " ");
    return $command;
}

function Hapus($tabelname, $param, $hal)
{
    $sql = "DELETE FROM " . $tabelname . " WHERE " . $param . " ";
    $command = hapusinput($sql);
    Zet($hal);
    return $command;
}

function Hapus2($tabelname, $param)
{
    $sql = "DELETE FROM " . $tabelname . " WHERE " . $param . " ";
    $command = hapusinput($sql);
    return $command;
}

function HapusAll($tabelname)
{
    $sql = "DELETE FROM " . $tabelname;
    $command = bukaquery($sql);
    return $command;
}

function deletegb($sql)
{
    $_sql = $sql;
    $hasil = bukaquery($_sql);
    if (php() <= 6) {
        $baris = mysql_fetch_row($hasil);
    } else {
        $baris = mysqli_fetch_row($hasil);
    }
    $gb = $baris[0];
    $hapus = unlink($gb);
}

function JSRedirect($url)
{
    echo "<html><head><title></title><meta http-equiv='refresh' content='1;URL=$url'></head><body></body></html>";
}

function Zet($url)
{
    echo "<html><head><title></title><meta http-equiv='refresh' content='0;URL=$url'></head><body></body></html>";
}

function JurusKibasNaga()
{
    $id = $_SERVER['REMOTE_ADDR'];
    $sql = bukaquery("DELETE FROM tmp WHERE ID='$id'");
    return $sql;
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

function formatDecDigit($duit, $digit)
{
    return round($duit, $digit);
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
    for ($thn = $thnini; $thn >= 2017; $thn--) {
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

function autonomer($table, $field, $inisial, $panjang)
{
    $qry = bukaquery("SELECT max(" . $field . ") FROM " . $table);
    $row = fetch_array($qry);
    if ($row[0] == "") {
        $angka = 0;
    } else {
        $angka = substr($row[0], strlen($inisial));
    }
    $angka++;
    $angka = strval($angka);
    $tmp = "";
    for ($i = 1; $i <= ($panjang - strlen($inisial) - strlen($angka)); $i++) {
        $tmp = $tmp . "0";
    }
    return $inisial . $tmp . $angka;
}

function nokiamat($id, $table)
{
    $tgl = date('ymd');
    $query = "SELECT MAX(RIGHT($id, 4)) as max_id FROM $table Where $id LIKE '%$tgl%'";
    $id_max = getOne($query);
    $sort_num = $id_max;
    $sort_num++;
    $new_code = $tgl . sprintf("%04s", $sort_num++);
    return $new_code;
}

function nokiamat1($id, $table)
{
    $tgl = date('ymd');
    $query = "SELECT MAX(RIGHT($id, 10)) as max_id FROM $table Where $id LIKE '%$tgl%'";
    $id_max = getOne($query);
    $sort_num = $id_max;
    $sort_num++;
    $new_code = $tgl . sprintf("%04s", $sort_num++);
    return $new_code;
}

function notahun($id, $table)
{
    $tgl = date('Y');
    $query = "SELECT MAX(RIGHT($id, 4)) as max_id FROM $table Where $id LIKE '%$tgl%'";
    $id_max = getOne($query);
    $sort_num = $id_max;
    $sort_num++;
    $new_code = $tgl . sprintf("%04s", $sort_num++);
    return $new_code;
}

function Terbilang($x)
{
    $abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    if ($x < 12)
        return " " . $abil[$x];
    elseif ($x < 20)
        return Terbilang($x - 10) . "belas";
    elseif ($x < 100)
        return Terbilang($x / 10) . " puluh" . Terbilang($x % 10);
    elseif ($x < 200)
        return " seratus" . Terbilang($x - 100);
    elseif ($x < 1000)
        return Terbilang($x / 100) . " ratus" . Terbilang($x % 100);
    elseif ($x < 2000)
        return " seribu" . Terbilang($x - 1000);
    elseif ($x < 1000000)
        return Terbilang($x / 1000) . " ribu" . Terbilang($x % 1000);
    elseif ($x < 1000000000)
        return Terbilang($x / 1000000) . " juta" . Terbilang($x % 1000000);
}

function penyebut($nilai)
{
    $nilai = abs($nilai);
    $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
    $temp = "";
    if ($nilai < 12) {
        $temp = " " . $huruf[$nilai];
    } else if ($nilai < 20) {
        $temp = penyebut($nilai - 10) . " Belas";
    } else if ($nilai < 100) {
        $temp = penyebut($nilai / 10) . " Puluh" . penyebut($nilai % 10);
    } else if ($nilai < 200) {
        $temp = " seratus" . penyebut($nilai - 100);
    } else if ($nilai < 1000) {
        $temp = penyebut($nilai / 100) . " Ratus" . penyebut($nilai % 100);
    } else if ($nilai < 2000) {
        $temp = " seribu" . penyebut($nilai - 1000);
    } else if ($nilai < 1000000) {
        $temp = penyebut($nilai / 1000) . " Ribu" . penyebut($nilai % 1000);
    } else if ($nilai < 1000000000) {
        $temp = penyebut($nilai / 1000000) . " Juta" . penyebut($nilai % 1000000);
    } else if ($nilai < 1000000000000) {
        $temp = penyebut($nilai / 1000000000) . " Milyar" . penyebut(fmod($nilai, 1000000000));
    } else if ($nilai < 1000000000000000) {
        $temp = penyebut($nilai / 1000000000000) . " Trilyun" . penyebut(fmod($nilai, 1000000000000));
    }
    return $temp;
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

function enumDropdown($table_name, $column_name, $echo = false, $placeholder)
{
    $selectDropdown = "<select class='form-control select2' data-placeholder='$placeholder' style='width: 100%;' name='$column_name' required=''>";
    $selectDropdown .= "<option selected='selected' value=''>$placeholder</option>";
    $result = bukaquery("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS
       WHERE TABLE_NAME = '$table_name' AND COLUMN_NAME = '$column_name'")
        or die(mysql_error());

    $row = fetch_array($result);
    $enumList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE']) - 6))));

    foreach ($enumList as $value)
        $selectDropdown .= "<option value=\"$value\">$value</option>";

    $selectDropdown .= "</select>";

    if ($echo)
        echo $selectDropdown;

    return $selectDropdown;
}

function UpdateEnumDropdown($table_name, $column_name, $record, $echo = false)
{
    $result = bukaquery("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS
       WHERE TABLE_NAME = '$table_name' AND COLUMN_NAME = '$column_name'")
        or die(mysql_error());

    $row = fetch_array($result);
    $enumList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE']) - 6))));

    $selectDropdown = "<select class='form-control select2' data-placeholder='' style='width: 100%;' name='$column_name' required=''>";

    foreach ($enumList as $value)
        if ($value == $record) {
            $selectDropdown .= "<option value=\"$value\" selected=" . $record . ">$value</option>";
        } else {
            $selectDropdown .= "<option value=\"$value\">$value</option>";
        }

    $selectDropdown .= "</select>";

    if ($echo)
        echo $selectDropdown;

    return $selectDropdown;
}

function TanggalAkhirBulan()
{
    $tampil = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
    return date('Y-m-t', $tampil);
}

function BulanKemarin()
{
    $tampil = mktime(0, 0, 0, date("m") - 1, date("d"), date("Y"));
    return date('m-Y', $tampil);
}

function BulanSekarang()
{
    $tampil = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
    return date('m-Y', $tampil);
}

function BulanDepan()
{
    $tampil = mktime(0, 0, 0, date("m") + 1, date("d"), date("Y"));
    return date('m-Y', $tampil);
}

function TahunDepan()
{
    $tampil = mktime(0, 0, 0, date("m"), date("d"), date("Y") + 1);
    return date('Y', $tampil);
}

function TanggalAkhirBulanKemarin()
{
    $tampil = mktime(0, 0, 0, date("m") - 1, date("d"), date("Y"));
    return date('Y-m-t', $tampil);
}

function TanggalAwalBulanKemarin()
{
    $tampil = mktime(0, 0, 0, date("m") - 1, date("d"), date("Y"));
    return date('Y-m-01', $tampil);
}

function MasaKerjaPenyebut($tmt)
{
    $tmp = new datetime($tmt);
    $today = new datetime();
    $masa = $today->diff($tmp);
    $tahun = $masa->y;
    $bulan = $masa->m;
    $bulanjalan = $bulan + 1;
    return $masa->y . ' Tahun ' . $bulanjalan . ' Bulan';
}

function MasaKerjaPenyebutValidasi($tmt)
{
    $tmp = new datetime($tmt);
    $today = new datetime();
    $masa = $today->diff($tmp);
    $tahun = $masa->y;
    $bulan = $masa->m;
    $bulanjalan = $bulan;
    return $masa->y . ' Tahun ' . $bulanjalan . ' Bulan';
}

function MasaKerja($tmt)
{
    $tmp = new datetime($tmt);
    $today = new datetime();
    $masa = $today->diff($tmp);
    return $masa->y;
}

function NilaiMasaKerja($tmt)
{
    $tmp = new datetime($tmt);
    $today = new datetime();
    $masa = $today->diff($tmp);
    $tahun = $masa->y;
    $bulan = $masa->m;
    $bulanjalan = $bulan + 1;
    $mk = ($tahun * 12) + $bulanjalan;
    return $mk;
}

function NilaiMasaKerjaCariValidasi($tmt, $tmtcek)
{
    $tmp = new datetime($tmt);
    $today = new datetime($tmtcek);
    $masa = $today->diff($tmp);
    $tahun = $masa->y;
    $bulan = $masa->m;
    $bulanjalan = $bulan;
    $mk = ($tahun * 12) + $bulanjalan;
    return $mk;
}

function NilaiMasaKerjaCari($tmt, $tmtcek)
{
    $tmp = new datetime($tmt);
    $today = new datetime($tmtcek);
    $masa = $today->diff($tmp);
    $tahun = $masa->y;
    $bulan = $masa->m;
    $bulanjalan = $bulan + 1;
    $mk = ($tahun * 12) + $bulanjalan;
    return $mk;
}

function PenyebutNilaiMasaKerjaCari($tmt, $tmtcek)
{
    $tmp = new datetime($tmt);
    $today = new datetime($tmtcek);
    $masa = $today->diff($tmp);
    $tahun = $masa->y;
    $bulan = $masa->m;
    $bulanjalan = $bulan + 1;
    return $masa->y . ' Tahun ' . $bulanjalan . ' Bulan';
}

function NilaiStatusKawin($status)
{
    $nilai_status = $status;

    switch ($nilai_status) {
        case 'LAJANG':
            $ns = 1;
            break;

        case 'MENIKAH':
            $ns = 1.1;
            break;

        case 'MENIKAH ANAK 1':
            $ns = 1.12;
            break;

        case 'MENIKAH ANAK 2':
            $ns = 1.14;
            break;

        case 'JANDA / DUDA ANAK 1':
            $ns = 1.02;
            break;

        case 'JANDA / DUDA ANAK 2':
            $ns = 1.04;
            break;


        default:
            $ns = "-";
            break;
    }

    return $ns;
}

function NilaiStatusPajak($pajak)
{
    $nilai_pajak = $pajak;

    switch ($nilai_pajak) {
        case 'Tk/0':
            $np = 54000000;
            break;

        case 'K/0':
            $np = 58500000;
            break;

        case 'K/1':
            $np = 63000000;
            break;

        case 'K/2':
            $np = 67500000;
            break;

        case 'K/3':
            $np = 72000000;
            break;

        case 'Tk/1':
            $np = 58500000;
            break;

        case 'Tk/2':
            $np = 63000000;
            break;

        case 'Tk/3':
            $np = 67500000;
            break;

        default:
            $np = 0;
            break;
    }

    return $np;
}

function NilaiStatusRumpun($rumpun)
{
    $nilai_rumpun = $rumpun;

    switch ($nilai_rumpun) {
        case 'Dokter Spesialis Bedah':
            $nr = 4;
            break;

        case 'Dokter Spesialis Non Bedah':
            $nr = 2.5;
            break;

        case 'Dokter Spesialis Penunjang':
            $nr = 1.8;
            break;

        case 'Dokter Umum / Dokter Gigi':
            $nr = 1.6;
            break;

        case 'Apoteker / Ners':
            $nr = 1.5;
            break;

        case 'Radiologi/Analis/DIV/DIII Kes':
            $nr = 0.8;
            break;

        case 'Teknis Tingkat Ahli':
            $nr = 1.5;
            break;

        case 'Teknis Tingkat Terampil':
            $nr = 1;
            break;

        case 'Administrasi Tingkat Ahli':
            $nr = 0.7;
            break;

        case 'Administrasi Tingkat Terampil':
            $nr = 0.6;
            break;

        case 'Operasional Tingkat Ahli':
            $nr = 0.5;
            break;

        case 'Operasional Tingkat Terampil':
            $nr = 0.4;
            break;

        case 'Pelayanan Tingkat Ahli':
            $nr = 0.3;
            break;

        case 'Pelayanan Tingkat Terampil':
            $nr = 0.2;
            break;

        default:
            $nr = "-";
            break;
    }

    return $nr;
}

function GajiPokok($pendidikan, $tmt)
{
    $mk = NilaiMasaKerja($tmt);
    if ($pendidikan == "") {
        echo "TMT Belum Di Input";
    } elseif ($pendidikan == 'SD' and $mk <= 3) {
        $gapok = 2719298 * 0.75;
    } elseif ($pendidikan == 'SD' and $mk <= 24) {
        $gapok = 2719298;
    } elseif ($pendidikan === 'SD' and $mk >= 25 and $mk <= 48) {
        $gapok = 2787281;
    } elseif ($pendidikan === 'SD' and $mk >= 49 and $mk <= 72) {
        $gapok = 2856963;
    } elseif ($pendidikan == 'SD' and $mk >= 73 and $mk <= 96) {
        $gapok = 2928387;
    } elseif ($pendidikan === 'SD' and $mk >= 97 and $mk <= 120) {
        $gapok = 3001596;
    } elseif ($pendidikan === 'SD' and $mk >= 121 and $mk <= 144) {
        $gapok = 3076636;
    } elseif ($pendidikan == 'SD' and $mk >= 145 and $mk <= 168) {
        $gapok = 3153552;
    } elseif ($pendidikan === 'SD' and $mk >= 169 and $mk <= 192) {
        $gapok = 3232391;
    } elseif ($pendidikan === 'SD' and $mk >= 193 and $mk <= 216) {
        $gapok = 33313201;
    } elseif ($pendidikan == 'SD' and $mk >= 217 and $mk <= 240) {
        $gapok = 33396031;
    } elseif ($pendidikan === 'SD' and $mk >= 241 and $mk <= 264) {
        $gapok = 3480932;
    } elseif ($pendidikan === 'SD' and $mk >= 265 and $mk <= 288) {
        $gapok = 3567955;
    } elseif ($pendidikan == 'SD' and $mk >= 289 and $mk <= 312) {
        $gapok = 3657154;
    } elseif ($pendidikan === 'SD' and $mk >= 313 and $mk <= 336) {
        $gapok = 3748583;
    } elseif ($pendidikan === 'SD' and $mk >= 337 and $mk <= 360) {
        $gapok = 3842297;
    } elseif ($pendidikan == 'SD' and $mk > 361 and $mk <= 384) {
        $gapok = 3938355;
    } elseif ($pendidikan == 'SD' and $mk > 385 and $mk <= 408) {
        $gapok = 4036814;
    } elseif ($pendidikan == 'SMP' and $mk <= 3) {
        $gapok = 3263158 * 0.75;
    } elseif ($pendidikan == 'SMP' and $mk <= 24) {
        $gapok = 3263158;
    } elseif ($pendidikan === 'SMP' and $mk >= 25 and $mk <= 48) {
        $gapok = 3344737;
    } elseif ($pendidikan === 'SMP' and $mk >= 49 and $mk <= 72) {
        $gapok = 3428355;
    } elseif ($pendidikan == 'SMP' and $mk >= 73 and $mk <= 96) {
        $gapok = 3514064;
    } elseif ($pendidikan === 'SMP' and $mk >= 97 and $mk <= 120) {
        $gapok = 3601916;
    } elseif ($pendidikan === 'SMP' and $mk >= 121 and $mk <= 144) {
        $gapok = 3691964;
    } elseif ($pendidikan == 'SMP' and $mk >= 145 and $mk <= 168) {
        $gapok = 3784263;
    } elseif ($pendidikan === 'SMP' and $mk >= 169 and $mk <= 192) {
        $gapok = 3878869;
    } elseif ($pendidikan === 'SMP' and $mk >= 193 and $mk <= 216) {
        $gapok = 3975841;
    } elseif ($pendidikan == 'SMP' and $mk >= 217 and $mk <= 240) {
        $gapok = 4075237;
    } elseif ($pendidikan === 'SMP' and $mk >= 241 and $mk <= 264) {
        $gapok = 4177118;
    } elseif ($pendidikan === 'SMP' and $mk >= 265 and $mk <= 288) {
        $gapok = 4281546;
    } elseif ($pendidikan == 'SMP' and $mk >= 289 and $mk <= 312) {
        $gapok = 4388585;
    } elseif ($pendidikan === 'SMP' and $mk >= 313 and $mk <= 336) {
        $gapok = 4498299;
    } elseif ($pendidikan === 'SMP' and $mk >= 337 and $mk <= 360) {
        $gapok = 4610757;
    } elseif ($pendidikan == 'SMP' and $mk >= 361 and $mk <= 384) {
        $gapok = 4726026;
    } elseif ($pendidikan == 'SMP' and $mk >= 385 and $mk <= 408) {
        $gapok = 4844176;
    } elseif ($pendidikan == 'SLTA' and $mk <= 3) {
        $gapok = 3807018 * 0.75;
    } elseif ($pendidikan == 'SLTA' and $mk <= 24) {
        $gapok = 3807018;
    } elseif ($pendidikan === 'SLTA' and $mk >= 25 and $mk <= 48) {
        $gapok = 3902193;
    } elseif ($pendidikan === 'SLTA' and $mk >= 49 and $mk <= 72) {
        $gapok = 3999748;
    } elseif ($pendidikan == 'SLTA' and $mk >= 73 and $mk <= 96) {
        $gapok = 4099742;
    } elseif ($pendidikan === 'SLTA' and $mk >= 97 and $mk <= 120) {
        $gapok = 4202235;
    } elseif ($pendidikan === 'SLTA' and $mk >= 121 and $mk <= 144) {
        $gapok = 4307291;
    } elseif ($pendidikan == 'SLTA' and $mk >= 145 and $mk <= 168) {
        $gapok = 4414973;
    } elseif ($pendidikan === 'SLTA' and $mk >= 169 and $mk <= 192) {
        $gapok = 4525348;
    } elseif ($pendidikan === 'SLTA' and $mk >= 193 and $mk <= 216) {
        $gapok = 4638481;
    } elseif ($pendidikan == 'SLTA' and $mk >= 217 and $mk <= 240) {
        $gapok = 4754443;
    } elseif ($pendidikan === 'SLTA' and $mk >= 241 and $mk <= 264) {
        $gapok = 4873304;
    } elseif ($pendidikan === 'SLTA' and $mk >= 265 and $mk <= 288) {
        $gapok = 4995137;
    } elseif ($pendidikan == 'SLTA' and $mk >= 289 and $mk <= 312) {
        $gapok = 5120015;
    } elseif ($pendidikan === 'SLTA' and $mk >= 313 and $mk <= 336) {
        $gapok = 5248016;
    } elseif ($pendidikan === 'SLTA' and $mk >= 337 and $mk <= 360) {
        $gapok = 5379216;
    } elseif ($pendidikan == 'SLTA' and $mk >= 361 and $mk <= 384) {
        $gapok = 5513697;
    } elseif ($pendidikan == 'SLTA' and $mk > 385 and $mk <= 408) {
        $gapok = 5651539;
    } elseif ($pendidikan == 'D III / D IV' and $mk <= 3) {
        $gapok = 4078947 * 0.75;
    } elseif ($pendidikan == 'D III / D IV' and $mk <= 24) {
        $gapok = 4078947;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 25 and $mk <= 48) {
        $gapok = 4180921;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 49 and $mk <= 72) {
        $gapok = 4285444;
    } elseif ($pendidikan == 'D III / D IV' and $mk >= 73 and $mk <= 96) {
        $gapok = 4392580;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 97 and $mk <= 120) {
        $gapok = 4502395;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 121 and $mk <= 144) {
        $gapok = 4614955;
    } elseif ($pendidikan == 'D III / D IV' and $mk >= 145 and $mk <= 168) {
        $gapok = 4730328;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 169 and $mk <= 192) {
        $gapok = 4848587;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 193 and $mk <= 216) {
        $gapok = 4969801;
    } elseif ($pendidikan == 'D III / D IV' and $mk >= 217 and $mk <= 240) {
        $gapok = 5094046;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 241 and $mk <= 264) {
        $gapok = 5221937;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 265 and $mk <= 288) {
        $gapok = 5351932;
    } elseif ($pendidikan == 'D III / D IV' and $mk >= 289 and $mk <= 312) {
        $gapok = 5485731;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 313 and $mk <= 336) {
        $gapok = 5622874;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 337 and $mk <= 360) {
        $gapok = 5763446;
    } elseif ($pendidikan == 'D III / D IV' and $mk > 361 and $mk <= 384) {
        $gapok = 5907532;
    } elseif ($pendidikan == 'D III / D IV' and $mk >= 385 and $mk <= 408) {
        $gapok = 6055220;
    } elseif ($pendidikan == 'S1' and $mk <= 3) {
        $gapok = 4350877 * 0.75;
    } elseif ($pendidikan == 'S1' and $mk <= 24) {
        $gapok = 4350877;
    } elseif ($pendidikan === 'S1' and $mk >= 25 and $mk <= 48) {
        $gapok = 4459649;
    } elseif ($pendidikan === 'S1' and $mk >= 49 and $mk <= 72) {
        $gapok = 4571140;
    } elseif ($pendidikan == 'S1' and $mk >= 73 and $mk <= 96) {
        $gapok = 4685419;
    } elseif ($pendidikan === 'S1' and $mk >= 97 and $mk <= 120) {
        $gapok = 4802554;
    } elseif ($pendidikan === 'S1' and $mk >= 121 and $mk <= 144) {
        $gapok = 4922618;
    } elseif ($pendidikan == 'S1' and $mk >= 145 and $mk <= 168) {
        $gapok = 5045684;
    } elseif ($pendidikan === 'S1' and $mk >= 169 and $mk <= 192) {
        $gapok = 5171826;
    } elseif ($pendidikan === 'S1' and $mk >= 193 and $mk <= 216) {
        $gapok = 5301121;
    } elseif ($pendidikan == 'S1' and $mk >= 217 and $mk <= 240) {
        $gapok = 5433649;
    } elseif ($pendidikan === 'S1' and $mk >= 241 and $mk <= 264) {
        $gapok = 5569491;
    } elseif ($pendidikan === 'S1' and $mk >= 265 and $mk <= 288) {
        $gapok = 5708728;
    } elseif ($pendidikan == 'S1' and $mk >= 289 and $mk <= 312) {
        $gapok = 5851446;
    } elseif ($pendidikan === 'S1' and $mk >= 313 and $mk <= 336) {
        $gapok = 5997732;
    } elseif ($pendidikan === 'S1' and $mk >= 337 and $mk <= 360) {
        $gapok = 6147676;
    } elseif ($pendidikan == 'S1' and $mk >= 361 and $mk <= 384) {
        $gapok = 6301367;
    } elseif ($pendidikan == 'S1' and $mk >= 385 and $mk <= 408) {
        $gapok = 6458902;
    } elseif ($pendidikan == 'S2 / dr./ drg./ Apoteker/ Ners' and $mk <= 3) {
        $gapok = 4622807 * 0.75;
    } elseif ($pendidikan == 'S2 / dr./ drg./ Apoteker/ Ners' and $mk <= 24) {
        $gapok = 4622807;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 25 and $mk <= 48) {
        $gapok = 4738377;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 49 and $mk <= 72) {
        $gapok = 4856837;
    } elseif ($pendidikan == 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 73 and $mk <= 96) {
        $gapok = 4978258;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 97 and $mk <= 120) {
        $gapok = 5102714;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 121 and $mk <= 144) {
        $gapok = 5230282;
    } elseif ($pendidikan == 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 145 and $mk <= 168) {
        $gapok = 5361039;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 169 and $mk <= 192) {
        $gapok = 5495065;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 193 and $mk <= 216) {
        $gapok = 5632441;
    } elseif ($pendidikan == 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 217 and $mk <= 240) {
        $gapok = 5773253;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 241 and $mk <= 264) {
        $gapok = 5917584;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 265 and $mk <= 288) {
        $gapok = 6065523;
    } elseif ($pendidikan == 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 289 and $mk <= 312) {
        $gapok = 6217161;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 313 and $mk <= 336) {
        $gapok = 6372591;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 337 and $mk <= 360) {
        $gapok = 6531905;
    } elseif ($pendidikan == 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 361 and $mk <= 384) {
        $gapok = 6695203;
    } elseif ($pendidikan == 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 385 and $mk <= 408) {
        $gapok = 6862583;
    } elseif ($pendidikan == 'S3 / dr. Spesialis' and $mk <= 3) {
        $gapok = 4894737 * 0.75;
    } elseif ($pendidikan == 'S3 / dr. Spesialis' and $mk <= 24) {
        $gapok = 4894737;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 25 and $mk <= 48) {
        $gapok = 5017105;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 49 and $mk <= 72) {
        $gapok = 5142533;
    } elseif ($pendidikan == 'S3 / dr. Spesialis' and $mk >= 73 and $mk <= 96) {
        $gapok = 5271096;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 97 and $mk <= 120) {
        $gapok = 5402874;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 121 and $mk <= 144) {
        $gapok = 5537945;
    } elseif ($pendidikan == 'S3 / dr. Spesialis' and $mk >= 145 and $mk <= 168) {
        $gapok = 5676394;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 169 and $mk <= 192) {
        $gapok = 5818304;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 193 and $mk <= 216) {
        $gapok = 5963762;
    } elseif ($pendidikan == 'S3 / dr. Spesialis' and $mk >= 217 and $mk <= 240) {
        $gapok = 6112856;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 241 and $mk <= 264) {
        $gapok = 6265677;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 265 and $mk <= 288) {
        $gapok = 6422319;
    } elseif ($pendidikan == 'S3 / dr. Spesialis' and $mk >= 289 and $mk <= 312) {
        $gapok = 6582877;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 313 and $mk <= 336) {
        $gapok = 6747449;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 337 and $mk <= 360) {
        $gapok = 6916135;
    } elseif ($pendidikan == 'S3 / dr. Spesialis' and $mk >= 361 and $mk <= 384) {
        $gapok = 7089038;
    } elseif ($pendidikan == 'S3 / dr. Spesialis' and $mk >= 385 and $mk <= 408) {
        $gapok = 7266264;
    }
    return $gapok;
}

function GajiPokokValidasi($pendidikan, $tmt, $tmtcek)
{
    $mk = NilaiMasaKerjaCariValidasi($tmt, $tmtcek);
    if ($pendidikan == "") {
        echo "TMT Belum Di Input";
    } elseif ($pendidikan == 'SD' and $mk <= 3) {
        $gapok = 2719298 * 0.75;
    } elseif ($pendidikan == 'SD' and $mk <= 24) {
        $gapok = 2719298;
    } elseif ($pendidikan === 'SD' and $mk >= 25 and $mk <= 48) {
        $gapok = 2787281;
    } elseif ($pendidikan === 'SD' and $mk >= 49 and $mk <= 72) {
        $gapok = 2856963;
    } elseif ($pendidikan == 'SD' and $mk >= 73 and $mk <= 96) {
        $gapok = 2928387;
    } elseif ($pendidikan === 'SD' and $mk >= 97 and $mk <= 120) {
        $gapok = 3001596;
    } elseif ($pendidikan === 'SD' and $mk >= 121 and $mk <= 144) {
        $gapok = 3076636;
    } elseif ($pendidikan == 'SD' and $mk >= 145 and $mk <= 168) {
        $gapok = 3153552;
    } elseif ($pendidikan === 'SD' and $mk >= 169 and $mk <= 192) {
        $gapok = 3232391;
    } elseif ($pendidikan === 'SD' and $mk >= 193 and $mk <= 216) {
        $gapok = 33313201;
    } elseif ($pendidikan == 'SD' and $mk >= 217 and $mk <= 240) {
        $gapok = 33396031;
    } elseif ($pendidikan === 'SD' and $mk >= 241 and $mk <= 264) {
        $gapok = 3480932;
    } elseif ($pendidikan === 'SD' and $mk >= 265 and $mk <= 288) {
        $gapok = 3567955;
    } elseif ($pendidikan == 'SD' and $mk >= 289 and $mk <= 312) {
        $gapok = 3657154;
    } elseif ($pendidikan === 'SD' and $mk >= 313 and $mk <= 336) {
        $gapok = 3748583;
    } elseif ($pendidikan === 'SD' and $mk >= 337 and $mk <= 360) {
        $gapok = 3842297;
    } elseif ($pendidikan == 'SD' and $mk > 361 and $mk <= 384) {
        $gapok = 3938355;
    } elseif ($pendidikan == 'SD' and $mk > 385 and $mk <= 408) {
        $gapok = 4036814;
    } elseif ($pendidikan == 'SMP' and $mk <= 3) {
        $gapok = 3263158 * 0.75;
    } elseif ($pendidikan == 'SMP' and $mk <= 24) {
        $gapok = 3263158;
    } elseif ($pendidikan === 'SMP' and $mk >= 25 and $mk <= 48) {
        $gapok = 3344737;
    } elseif ($pendidikan === 'SMP' and $mk >= 49 and $mk <= 72) {
        $gapok = 3428355;
    } elseif ($pendidikan == 'SMP' and $mk >= 73 and $mk <= 96) {
        $gapok = 3514064;
    } elseif ($pendidikan === 'SMP' and $mk >= 97 and $mk <= 120) {
        $gapok = 3601916;
    } elseif ($pendidikan === 'SMP' and $mk >= 121 and $mk <= 144) {
        $gapok = 3691964;
    } elseif ($pendidikan == 'SMP' and $mk >= 145 and $mk <= 168) {
        $gapok = 3784263;
    } elseif ($pendidikan === 'SMP' and $mk >= 169 and $mk <= 192) {
        $gapok = 3878869;
    } elseif ($pendidikan === 'SMP' and $mk >= 193 and $mk <= 216) {
        $gapok = 3975841;
    } elseif ($pendidikan == 'SMP' and $mk >= 217 and $mk <= 240) {
        $gapok = 4075237;
    } elseif ($pendidikan === 'SMP' and $mk >= 241 and $mk <= 264) {
        $gapok = 4177118;
    } elseif ($pendidikan === 'SMP' and $mk >= 265 and $mk <= 288) {
        $gapok = 4281546;
    } elseif ($pendidikan == 'SMP' and $mk >= 289 and $mk <= 312) {
        $gapok = 4388585;
    } elseif ($pendidikan === 'SMP' and $mk >= 313 and $mk <= 336) {
        $gapok = 4498299;
    } elseif ($pendidikan === 'SMP' and $mk >= 337 and $mk <= 360) {
        $gapok = 4610757;
    } elseif ($pendidikan == 'SMP' and $mk >= 361 and $mk <= 384) {
        $gapok = 4726026;
    } elseif ($pendidikan == 'SMP' and $mk >= 385 and $mk <= 408) {
        $gapok = 4844176;
    } elseif ($pendidikan == 'SLTA' and $mk <= 3) {
        $gapok = 3807018 * 0.75;
    } elseif ($pendidikan == 'SLTA' and $mk <= 24) {
        $gapok = 3807018;
    } elseif ($pendidikan === 'SLTA' and $mk >= 25 and $mk <= 48) {
        $gapok = 3902193;
    } elseif ($pendidikan === 'SLTA' and $mk >= 49 and $mk <= 72) {
        $gapok = 3999748;
    } elseif ($pendidikan == 'SLTA' and $mk >= 73 and $mk <= 96) {
        $gapok = 4099742;
    } elseif ($pendidikan === 'SLTA' and $mk >= 97 and $mk <= 120) {
        $gapok = 4202235;
    } elseif ($pendidikan === 'SLTA' and $mk >= 121 and $mk <= 144) {
        $gapok = 4307291;
    } elseif ($pendidikan == 'SLTA' and $mk >= 145 and $mk <= 168) {
        $gapok = 4414973;
    } elseif ($pendidikan === 'SLTA' and $mk >= 169 and $mk <= 192) {
        $gapok = 4525348;
    } elseif ($pendidikan === 'SLTA' and $mk >= 193 and $mk <= 216) {
        $gapok = 4638481;
    } elseif ($pendidikan == 'SLTA' and $mk >= 217 and $mk <= 240) {
        $gapok = 4754443;
    } elseif ($pendidikan === 'SLTA' and $mk >= 241 and $mk <= 264) {
        $gapok = 4873304;
    } elseif ($pendidikan === 'SLTA' and $mk >= 265 and $mk <= 288) {
        $gapok = 4995137;
    } elseif ($pendidikan == 'SLTA' and $mk >= 289 and $mk <= 312) {
        $gapok = 5120015;
    } elseif ($pendidikan === 'SLTA' and $mk >= 313 and $mk <= 336) {
        $gapok = 5248016;
    } elseif ($pendidikan === 'SLTA' and $mk >= 337 and $mk <= 360) {
        $gapok = 5379216;
    } elseif ($pendidikan == 'SLTA' and $mk >= 361 and $mk <= 384) {
        $gapok = 5513697;
    } elseif ($pendidikan == 'SLTA' and $mk > 385 and $mk <= 408) {
        $gapok = 5651539;
    } elseif ($pendidikan == 'D III / D IV' and $mk <= 3) {
        $gapok = 4078947 * 0.75;
    } elseif ($pendidikan == 'D III / D IV' and $mk <= 24) {
        $gapok = 4078947;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 25 and $mk <= 48) {
        $gapok = 4180921;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 49 and $mk <= 72) {
        $gapok = 4285444;
    } elseif ($pendidikan == 'D III / D IV' and $mk >= 73 and $mk <= 96) {
        $gapok = 4392580;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 97 and $mk <= 120) {
        $gapok = 4502395;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 121 and $mk <= 144) {
        $gapok = 4614955;
    } elseif ($pendidikan == 'D III / D IV' and $mk >= 145 and $mk <= 168) {
        $gapok = 4730328;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 169 and $mk <= 192) {
        $gapok = 4848587;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 193 and $mk <= 216) {
        $gapok = 4969801;
    } elseif ($pendidikan == 'D III / D IV' and $mk >= 217 and $mk <= 240) {
        $gapok = 5094046;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 241 and $mk <= 264) {
        $gapok = 5221937;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 265 and $mk <= 288) {
        $gapok = 5351932;
    } elseif ($pendidikan == 'D III / D IV' and $mk >= 289 and $mk <= 312) {
        $gapok = 5485731;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 313 and $mk <= 336) {
        $gapok = 5622874;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 337 and $mk <= 360) {
        $gapok = 5763446;
    } elseif ($pendidikan == 'D III / D IV' and $mk > 361 and $mk <= 384) {
        $gapok = 5907532;
    } elseif ($pendidikan == 'D III / D IV' and $mk >= 385 and $mk <= 408) {
        $gapok = 6055220;
    } elseif ($pendidikan == 'S1' and $mk <= 3) {
        $gapok = 4350877 * 0.75;
    } elseif ($pendidikan == 'S1' and $mk <= 24) {
        $gapok = 4350877;
    } elseif ($pendidikan === 'S1' and $mk >= 25 and $mk <= 48) {
        $gapok = 4459649;
    } elseif ($pendidikan === 'S1' and $mk >= 49 and $mk <= 72) {
        $gapok = 4571140;
    } elseif ($pendidikan == 'S1' and $mk >= 73 and $mk <= 96) {
        $gapok = 4685419;
    } elseif ($pendidikan === 'S1' and $mk >= 97 and $mk <= 120) {
        $gapok = 4802554;
    } elseif ($pendidikan === 'S1' and $mk >= 121 and $mk <= 144) {
        $gapok = 4922618;
    } elseif ($pendidikan == 'S1' and $mk >= 145 and $mk <= 168) {
        $gapok = 5045684;
    } elseif ($pendidikan === 'S1' and $mk >= 169 and $mk <= 192) {
        $gapok = 5171826;
    } elseif ($pendidikan === 'S1' and $mk >= 193 and $mk <= 216) {
        $gapok = 5301121;
    } elseif ($pendidikan == 'S1' and $mk >= 217 and $mk <= 240) {
        $gapok = 5433649;
    } elseif ($pendidikan === 'S1' and $mk >= 241 and $mk <= 264) {
        $gapok = 5569491;
    } elseif ($pendidikan === 'S1' and $mk >= 265 and $mk <= 288) {
        $gapok = 5708728;
    } elseif ($pendidikan == 'S1' and $mk >= 289 and $mk <= 312) {
        $gapok = 5851446;
    } elseif ($pendidikan === 'S1' and $mk >= 313 and $mk <= 336) {
        $gapok = 5997732;
    } elseif ($pendidikan === 'S1' and $mk >= 337 and $mk <= 360) {
        $gapok = 6147676;
    } elseif ($pendidikan == 'S1' and $mk >= 361 and $mk <= 384) {
        $gapok = 6301367;
    } elseif ($pendidikan == 'S1' and $mk >= 385 and $mk <= 408) {
        $gapok = 6458902;
    } elseif ($pendidikan == 'S2 / dr./ drg./ Apoteker/ Ners' and $mk <= 3) {
        $gapok = 4622807 * 0.75;
    } elseif ($pendidikan == 'S2 / dr./ drg./ Apoteker/ Ners' and $mk <= 24) {
        $gapok = 4622807;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 25 and $mk <= 48) {
        $gapok = 4738377;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 49 and $mk <= 72) {
        $gapok = 4856837;
    } elseif ($pendidikan == 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 73 and $mk <= 96) {
        $gapok = 4978258;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 97 and $mk <= 120) {
        $gapok = 5102714;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 121 and $mk <= 144) {
        $gapok = 5230282;
    } elseif ($pendidikan == 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 145 and $mk <= 168) {
        $gapok = 5361039;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 169 and $mk <= 192) {
        $gapok = 5495065;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 193 and $mk <= 216) {
        $gapok = 5632441;
    } elseif ($pendidikan == 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 217 and $mk <= 240) {
        $gapok = 5773253;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 241 and $mk <= 264) {
        $gapok = 5917584;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 265 and $mk <= 288) {
        $gapok = 6065523;
    } elseif ($pendidikan == 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 289 and $mk <= 312) {
        $gapok = 6217161;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 313 and $mk <= 336) {
        $gapok = 6372591;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 337 and $mk <= 360) {
        $gapok = 6531905;
    } elseif ($pendidikan == 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 361 and $mk <= 384) {
        $gapok = 6695203;
    } elseif ($pendidikan == 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 385 and $mk <= 408) {
        $gapok = 6862583;
    } elseif ($pendidikan == 'S3 / dr. Spesialis' and $mk <= 3) {
        $gapok = 4894737 * 0.75;
    } elseif ($pendidikan == 'S3 / dr. Spesialis' and $mk <= 24) {
        $gapok = 4894737;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 25 and $mk <= 48) {
        $gapok = 5017105;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 49 and $mk <= 72) {
        $gapok = 5142533;
    } elseif ($pendidikan == 'S3 / dr. Spesialis' and $mk >= 73 and $mk <= 96) {
        $gapok = 5271096;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 97 and $mk <= 120) {
        $gapok = 5402874;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 121 and $mk <= 144) {
        $gapok = 5537945;
    } elseif ($pendidikan == 'S3 / dr. Spesialis' and $mk >= 145 and $mk <= 168) {
        $gapok = 5676394;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 169 and $mk <= 192) {
        $gapok = 5818304;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 193 and $mk <= 216) {
        $gapok = 5963762;
    } elseif ($pendidikan == 'S3 / dr. Spesialis' and $mk >= 217 and $mk <= 240) {
        $gapok = 6112856;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 241 and $mk <= 264) {
        $gapok = 6265677;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 265 and $mk <= 288) {
        $gapok = 6422319;
    } elseif ($pendidikan == 'S3 / dr. Spesialis' and $mk >= 289 and $mk <= 312) {
        $gapok = 6582877;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 313 and $mk <= 336) {
        $gapok = 6747449;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 337 and $mk <= 360) {
        $gapok = 6916135;
    } elseif ($pendidikan == 'S3 / dr. Spesialis' and $mk >= 361 and $mk <= 384) {
        $gapok = 7089038;
    } elseif ($pendidikan == 'S3 / dr. Spesialis' and $mk >= 385 and $mk <= 408) {
        $gapok = 7266264;
    }
    return $gapok;
}

function GajiPokokLaporan($pendidikan, $tmt, $tmtcek)
{
    $mk = NilaiMasaKerjaCari($tmt, $tmtcek);
    if ($pendidikan == "") {
        echo "TMT Belum Di Input";
    } elseif ($pendidikan == 'SD' and $mk <= 3) {
        $gapok = 2719298 * 0.75;
    } elseif ($pendidikan == 'SD' and $mk <= 24) {
        $gapok = 2719298;
    } elseif ($pendidikan === 'SD' and $mk >= 25 and $mk <= 48) {
        $gapok = 2787281;
    } elseif ($pendidikan === 'SD' and $mk >= 49 and $mk <= 72) {
        $gapok = 2856963;
    } elseif ($pendidikan == 'SD' and $mk >= 73 and $mk <= 96) {
        $gapok = 2928387;
    } elseif ($pendidikan === 'SD' and $mk >= 97 and $mk <= 120) {
        $gapok = 3001596;
    } elseif ($pendidikan === 'SD' and $mk >= 121 and $mk <= 144) {
        $gapok = 3076636;
    } elseif ($pendidikan == 'SD' and $mk >= 145 and $mk <= 168) {
        $gapok = 3153552;
    } elseif ($pendidikan === 'SD' and $mk >= 169 and $mk <= 192) {
        $gapok = 3232391;
    } elseif ($pendidikan === 'SD' and $mk >= 193 and $mk <= 216) {
        $gapok = 33313201;
    } elseif ($pendidikan == 'SD' and $mk >= 217 and $mk <= 240) {
        $gapok = 33396031;
    } elseif ($pendidikan === 'SD' and $mk >= 241 and $mk <= 264) {
        $gapok = 3480932;
    } elseif ($pendidikan === 'SD' and $mk >= 265 and $mk <= 288) {
        $gapok = 3567955;
    } elseif ($pendidikan == 'SD' and $mk >= 289 and $mk <= 312) {
        $gapok = 3657154;
    } elseif ($pendidikan === 'SD' and $mk >= 313 and $mk <= 336) {
        $gapok = 3748583;
    } elseif ($pendidikan === 'SD' and $mk >= 337 and $mk <= 360) {
        $gapok = 3842297;
    } elseif ($pendidikan == 'SD' and $mk > 361 and $mk <= 384) {
        $gapok = 3938355;
    } elseif ($pendidikan == 'SD' and $mk > 385 and $mk <= 408) {
        $gapok = 4036814;
    } elseif ($pendidikan == 'SMP' and $mk <= 3) {
        $gapok = 3263158 * 0.75;
    } elseif ($pendidikan == 'SMP' and $mk <= 24) {
        $gapok = 3263158;
    } elseif ($pendidikan === 'SMP' and $mk >= 25 and $mk <= 48) {
        $gapok = 3344737;
    } elseif ($pendidikan === 'SMP' and $mk >= 49 and $mk <= 72) {
        $gapok = 3428355;
    } elseif ($pendidikan == 'SMP' and $mk >= 73 and $mk <= 96) {
        $gapok = 3514064;
    } elseif ($pendidikan === 'SMP' and $mk >= 97 and $mk <= 120) {
        $gapok = 3601916;
    } elseif ($pendidikan === 'SMP' and $mk >= 121 and $mk <= 144) {
        $gapok = 3691964;
    } elseif ($pendidikan == 'SMP' and $mk >= 145 and $mk <= 168) {
        $gapok = 3784263;
    } elseif ($pendidikan === 'SMP' and $mk >= 169 and $mk <= 192) {
        $gapok = 3878869;
    } elseif ($pendidikan === 'SMP' and $mk >= 193 and $mk <= 216) {
        $gapok = 3975841;
    } elseif ($pendidikan == 'SMP' and $mk >= 217 and $mk <= 240) {
        $gapok = 4075237;
    } elseif ($pendidikan === 'SMP' and $mk >= 241 and $mk <= 264) {
        $gapok = 4177118;
    } elseif ($pendidikan === 'SMP' and $mk >= 265 and $mk <= 288) {
        $gapok = 4281546;
    } elseif ($pendidikan == 'SMP' and $mk >= 289 and $mk <= 312) {
        $gapok = 4388585;
    } elseif ($pendidikan === 'SMP' and $mk >= 313 and $mk <= 336) {
        $gapok = 4498299;
    } elseif ($pendidikan === 'SMP' and $mk >= 337 and $mk <= 360) {
        $gapok = 4610757;
    } elseif ($pendidikan == 'SMP' and $mk >= 361 and $mk <= 384) {
        $gapok = 4726026;
    } elseif ($pendidikan == 'SMP' and $mk >= 385 and $mk <= 408) {
        $gapok = 4844176;
    } elseif ($pendidikan == 'SLTA' and $mk <= 3) {
        $gapok = 3807018 * 0.75;
    } elseif ($pendidikan == 'SLTA' and $mk <= 24) {
        $gapok = 3807018;
    } elseif ($pendidikan === 'SLTA' and $mk >= 25 and $mk <= 48) {
        $gapok = 3902193;
    } elseif ($pendidikan === 'SLTA' and $mk >= 49 and $mk <= 72) {
        $gapok = 3999748;
    } elseif ($pendidikan == 'SLTA' and $mk >= 73 and $mk <= 96) {
        $gapok = 4099742;
    } elseif ($pendidikan === 'SLTA' and $mk >= 97 and $mk <= 120) {
        $gapok = 4202235;
    } elseif ($pendidikan === 'SLTA' and $mk >= 121 and $mk <= 144) {
        $gapok = 4307291;
    } elseif ($pendidikan == 'SLTA' and $mk >= 145 and $mk <= 168) {
        $gapok = 4414973;
    } elseif ($pendidikan === 'SLTA' and $mk >= 169 and $mk <= 192) {
        $gapok = 4525348;
    } elseif ($pendidikan === 'SLTA' and $mk >= 193 and $mk <= 216) {
        $gapok = 4638481;
    } elseif ($pendidikan == 'SLTA' and $mk >= 217 and $mk <= 240) {
        $gapok = 4754443;
    } elseif ($pendidikan === 'SLTA' and $mk >= 241 and $mk <= 264) {
        $gapok = 4873304;
    } elseif ($pendidikan === 'SLTA' and $mk >= 265 and $mk <= 288) {
        $gapok = 4995137;
    } elseif ($pendidikan == 'SLTA' and $mk >= 289 and $mk <= 312) {
        $gapok = 5120015;
    } elseif ($pendidikan === 'SLTA' and $mk >= 313 and $mk <= 336) {
        $gapok = 5248016;
    } elseif ($pendidikan === 'SLTA' and $mk >= 337 and $mk <= 360) {
        $gapok = 5379216;
    } elseif ($pendidikan == 'SLTA' and $mk >= 361 and $mk <= 384) {
        $gapok = 5513697;
    } elseif ($pendidikan == 'SLTA' and $mk > 385 and $mk <= 408) {
        $gapok = 5651539;
    } elseif ($pendidikan == 'D III / D IV' and $mk <= 3) {
        $gapok = 4078947 * 0.75;
    } elseif ($pendidikan == 'D III / D IV' and $mk <= 24) {
        $gapok = 4078947;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 25 and $mk <= 48) {
        $gapok = 4180921;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 49 and $mk <= 72) {
        $gapok = 4285444;
    } elseif ($pendidikan == 'D III / D IV' and $mk >= 73 and $mk <= 96) {
        $gapok = 4392580;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 97 and $mk <= 120) {
        $gapok = 4502395;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 121 and $mk <= 144) {
        $gapok = 4614955;
    } elseif ($pendidikan == 'D III / D IV' and $mk >= 145 and $mk <= 168) {
        $gapok = 4730328;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 169 and $mk <= 192) {
        $gapok = 4848587;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 193 and $mk <= 216) {
        $gapok = 4969801;
    } elseif ($pendidikan == 'D III / D IV' and $mk >= 217 and $mk <= 240) {
        $gapok = 5094046;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 241 and $mk <= 264) {
        $gapok = 5221937;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 265 and $mk <= 288) {
        $gapok = 5351932;
    } elseif ($pendidikan == 'D III / D IV' and $mk >= 289 and $mk <= 312) {
        $gapok = 5485731;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 313 and $mk <= 336) {
        $gapok = 5622874;
    } elseif ($pendidikan === 'D III / D IV' and $mk >= 337 and $mk <= 360) {
        $gapok = 5763446;
    } elseif ($pendidikan == 'D III / D IV' and $mk > 361 and $mk <= 384) {
        $gapok = 5907532;
    } elseif ($pendidikan == 'D III / D IV' and $mk >= 385 and $mk <= 408) {
        $gapok = 6055220;
    } elseif ($pendidikan == 'S1' and $mk <= 3) {
        $gapok = 4350877 * 0.75;
    } elseif ($pendidikan == 'S1' and $mk <= 24) {
        $gapok = 4350877;
    } elseif ($pendidikan === 'S1' and $mk >= 25 and $mk <= 48) {
        $gapok = 4459649;
    } elseif ($pendidikan === 'S1' and $mk >= 49 and $mk <= 72) {
        $gapok = 4571140;
    } elseif ($pendidikan == 'S1' and $mk >= 73 and $mk <= 96) {
        $gapok = 4685419;
    } elseif ($pendidikan === 'S1' and $mk >= 97 and $mk <= 120) {
        $gapok = 4802554;
    } elseif ($pendidikan === 'S1' and $mk >= 121 and $mk <= 144) {
        $gapok = 4922618;
    } elseif ($pendidikan == 'S1' and $mk >= 145 and $mk <= 168) {
        $gapok = 5045684;
    } elseif ($pendidikan === 'S1' and $mk >= 169 and $mk <= 192) {
        $gapok = 5171826;
    } elseif ($pendidikan === 'S1' and $mk >= 193 and $mk <= 216) {
        $gapok = 5301121;
    } elseif ($pendidikan == 'S1' and $mk >= 217 and $mk <= 240) {
        $gapok = 5433649;
    } elseif ($pendidikan === 'S1' and $mk >= 241 and $mk <= 264) {
        $gapok = 5569491;
    } elseif ($pendidikan === 'S1' and $mk >= 265 and $mk <= 288) {
        $gapok = 5708728;
    } elseif ($pendidikan == 'S1' and $mk >= 289 and $mk <= 312) {
        $gapok = 5851446;
    } elseif ($pendidikan === 'S1' and $mk >= 313 and $mk <= 336) {
        $gapok = 5997732;
    } elseif ($pendidikan === 'S1' and $mk >= 337 and $mk <= 360) {
        $gapok = 6147676;
    } elseif ($pendidikan == 'S1' and $mk >= 361 and $mk <= 384) {
        $gapok = 6301367;
    } elseif ($pendidikan == 'S1' and $mk >= 385 and $mk <= 408) {
        $gapok = 6458902;
    } elseif ($pendidikan == 'S2 / dr./ drg./ Apoteker/ Ners' and $mk <= 3) {
        $gapok = 4622807 * 0.75;
    } elseif ($pendidikan == 'S2 / dr./ drg./ Apoteker/ Ners' and $mk <= 24) {
        $gapok = 4622807;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 25 and $mk <= 48) {
        $gapok = 4738377;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 49 and $mk <= 72) {
        $gapok = 4856837;
    } elseif ($pendidikan == 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 73 and $mk <= 96) {
        $gapok = 4978258;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 97 and $mk <= 120) {
        $gapok = 5102714;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 121 and $mk <= 144) {
        $gapok = 5230282;
    } elseif ($pendidikan == 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 145 and $mk <= 168) {
        $gapok = 5361039;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 169 and $mk <= 192) {
        $gapok = 5495065;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 193 and $mk <= 216) {
        $gapok = 5632441;
    } elseif ($pendidikan == 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 217 and $mk <= 240) {
        $gapok = 5773253;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 241 and $mk <= 264) {
        $gapok = 5917584;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 265 and $mk <= 288) {
        $gapok = 6065523;
    } elseif ($pendidikan == 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 289 and $mk <= 312) {
        $gapok = 6217161;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 313 and $mk <= 336) {
        $gapok = 6372591;
    } elseif ($pendidikan === 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 337 and $mk <= 360) {
        $gapok = 6531905;
    } elseif ($pendidikan == 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 361 and $mk <= 384) {
        $gapok = 6695203;
    } elseif ($pendidikan == 'S2 / dr./ drg./ Apoteker/ Ners' and $mk >= 385 and $mk <= 408) {
        $gapok = 6862583;
    } elseif ($pendidikan == 'S3 / dr. Spesialis' and $mk <= 3) {
        $gapok = 4894737 * 0.75;
    } elseif ($pendidikan == 'S3 / dr. Spesialis' and $mk <= 24) {
        $gapok = 4894737;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 25 and $mk <= 48) {
        $gapok = 5017105;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 49 and $mk <= 72) {
        $gapok = 5142533;
    } elseif ($pendidikan == 'S3 / dr. Spesialis' and $mk >= 73 and $mk <= 96) {
        $gapok = 5271096;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 97 and $mk <= 120) {
        $gapok = 5402874;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 121 and $mk <= 144) {
        $gapok = 5537945;
    } elseif ($pendidikan == 'S3 / dr. Spesialis' and $mk >= 145 and $mk <= 168) {
        $gapok = 5676394;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 169 and $mk <= 192) {
        $gapok = 5818304;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 193 and $mk <= 216) {
        $gapok = 5963762;
    } elseif ($pendidikan == 'S3 / dr. Spesialis' and $mk >= 217 and $mk <= 240) {
        $gapok = 6112856;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 241 and $mk <= 264) {
        $gapok = 6265677;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 265 and $mk <= 288) {
        $gapok = 6422319;
    } elseif ($pendidikan == 'S3 / dr. Spesialis' and $mk >= 289 and $mk <= 312) {
        $gapok = 6582877;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 313 and $mk <= 336) {
        $gapok = 6747449;
    } elseif ($pendidikan === 'S3 / dr. Spesialis' and $mk >= 337 and $mk <= 360) {
        $gapok = 6916135;
    } elseif ($pendidikan == 'S3 / dr. Spesialis' and $mk >= 361 and $mk <= 384) {
        $gapok = 7089038;
    } elseif ($pendidikan == 'S3 / dr. Spesialis' and $mk >= 385 and $mk <= 408) {
        $gapok = 7266264;
    }
    return $gapok;
}

function GajiBruto($gapok, $nilai_status_kawin)
{
    $hasil = $gapok * $nilai_status_kawin;
    return $hasil;
}

function NamaHukDis($id_user)
{
    $masa_aktif = fetch_array(bukaquery("select tm_sanksi.masa_aktif, tm_sanksi.nama_sanksi, tt_hukuman.tgl_hukuman, tt_hukuman.aktif_hukuman from tt_hukuman inner join tm_sanksi on tt_hukuman.id_sanksi=tm_sanksi.id_sanksi where tt_hukuman.id_user='$id_user' "
        . "order by tt_hukuman.tgl_hukuman desc"));
    $bulannya = NilaiMasaKerja($masa_aktif['aktif_hukuman']);
    if ($bulannya >= 1 and $bulannya <= $masa_aktif['masa_aktif']) {
        $sanksi = $masa_aktif['nama_sanksi'];
    } else {
        $sanksi = '-';
    }
    return $sanksi;
}

function NilaiHukDis($id_user)
{
    $masa_aktif = fetch_array(bukaquery("select tm_sanksi.masa_aktif, tm_sanksi.nilai_sanksi, tt_hukuman.tgl_hukuman, tt_hukuman.aktif_hukuman from tt_hukuman inner join tm_sanksi on tt_hukuman.id_sanksi=tm_sanksi.id_sanksi where tt_hukuman.id_user='$id_user' "
        . "order by tt_hukuman.tgl_hukuman desc"));
    $bulannya = NilaiMasaKerja($masa_aktif['aktif_hukuman']);
    if ($bulannya >= 1 and $bulannya <= $masa_aktif['masa_aktif']) {
        $sanksi = $masa_aktif['nilai_sanksi'];
    } else {
        $sanksi = '0';
    }
    return $sanksi;
}

function IdHukDis($id_user)
{
    $masa_aktif = fetch_array(bukaquery("select tm_sanksi.masa_aktif, tm_sanksi.id_sanksi, tt_hukuman.tgl_hukuman, tt_hukuman.aktif_hukuman from tt_hukuman inner join tm_sanksi on tt_hukuman.id_sanksi=tm_sanksi.id_sanksi where tt_hukuman.id_user='$id_user' "
        . "order by tt_hukuman.tgl_hukuman desc"));
    $bulannya = NilaiMasaKerja($masa_aktif['aktif_hukuman']);
    if ($bulannya >= 1 and $bulannya <= $masa_aktif['masa_aktif']) {
        $sanksi = $masa_aktif['id_sanksi'];
    } else {
        $sanksi = '';
    }
    return $sanksi;
}

function NilDis($id, $bln_sebelumnya, $thn)
{
    return getOne("SELECT (((((tm_kedisiplinan.d_diri + tm_kedisiplinan.d_penampilan + tm_kedisiplinan.d_seragam)/3)*0.15) +(((tm_kedisiplinan.d_alat +
                tm_kedisiplinan.d_ruangan + tm_kedisiplinan.d_sarana)/3)*0.15))*10) as point FROM tm_kedisiplinan
                where Month(tm_kedisiplinan.date_d)='$bln_sebelumnya' and Year(tm_kedisiplinan.date_d)='$thn' and id_user='$id'");
}

function NilKomp($id, $bln_sebelumnya, $thn)
{
    return getOne("SELECT(((tm_kompetensi.menganalisa1+tm_kompetensi.menganalisa2)/2)+((tm_kompetensi.komunikasi1+tm_kompetensi.komunikasi2)/2)+((tm_kompetensi.kerjasama1+tm_kompetensi.kerjasama2)/2)+((tm_kompetensi.kecerdasan1+
            tm_kompetensi.kecerdasan2+tm_kompetensi.kecerdasan3)/3)+((tm_kompetensi.fokus1+tm_kompetensi.fokus2+tm_kompetensi.fokus3)/3)+((tm_kompetensi.tanggung1+tm_kompetensi.tanggung2+tm_kompetensi.tanggung3+
            tm_kompetensi.tanggung4)/4)+((tm_kompetensi.orientasi_k1+tm_kompetensi.orientasi_k2)/2)+((tm_kompetensi.inisiatif1+tm_kompetensi.inisiatif2)/2)+((tm_kompetensi.disiplin1+tm_kompetensi.disiplin2+tm_kompetensi.disiplin3)/3)+((tm_kompetensi.orientasi_p1+
            tm_kompetensi.orientasi_p2+tm_kompetensi.orientasi_p3)/3))*0.7 as point
            FROM tm_kompetensi 
            where Month(tm_kompetensi.date_kompetensi)='$bln_sebelumnya' and Year(tm_kompetensi.date_kompetensi)='$thn' and id_user='$id'");
}

function NilKinerja($id, $bln_sebelumnya, $thn)
{
    $waktu_kerja = getOne("select hari from tm_hari_kerja where Month(tm_hari_kerja.bulan)='$bln_sebelumnya' and Year(tm_hari_kerja.bulan)='$thn'");
    $durasi_kinerja = getOne("select sum(TIMESTAMPDIFF(MINUTE,(waktu_mulai),(waktu_akhir))) as total_durasi from tt_kinerja where Month(tt_kinerja.tanggal_kinerja)='$bln_sebelumnya' and Year(tt_kinerja.tanggal_kinerja)='$thn' and id_user='$id' and validasi='Y' ");
    $waktu_kurang = getOne("SELECT (tm_waktu_k.sakit1*300)+(tm_waktu_k.sakit2*300)+(tm_waktu_k.alpha*600)+(tm_waktu_k.izin*300)+(tm_waktu_k.izin_setengah_hari*150)+(tm_waktu_k.telat*1)+(tm_waktu_k.ct_sakit_k*240)+(tm_waktu_k.ct_alasan_k*300)+(tm_waktu_k.ct_persalinan_k*300)+(tm_waktu_k.meninggal*300) AS total 
                    FROM tm_waktu_k
                    where Month(tm_waktu_k.date_k)='$bln_sebelumnya' and Year(tm_waktu_k.date_k)='$thn' and id_user='$id'");
    $waktu_tambah = getOne("SELECT (tm_waktu_t.ct_sakit_t*60)+(tm_waktu_t.ct_alasan_t*300)+(tm_waktu_t.ct_tahunan_t*300)+(tm_waktu_t.diklat*300)+(tm_waktu_t.spd*300)+(tm_waktu_t.haji*300) as point
                    FROM tm_waktu_t
                    where Month(tm_waktu_t.date_t)='$bln_sebelumnya' and Year(tm_waktu_t.date_t)='$thn' and id_user='$id'");
    $waktu_shift = getOne("SELECT tm_waktu_s.j_hks+tm_waktu_s.j_hkm+tm_waktu_s.j_hlp+tm_waktu_s.j_hls+tm_waktu_s.j_hlm+tm_waktu_s.j_hrp+tm_waktu_s.j_hrs+tm_waktu_s.j_hrm+tm_waktu_s.j_ns as jumlah
                    FROM tm_waktu_s
                    where Month(tm_waktu_s.date_s)='$bln_sebelumnya' and Year(tm_waktu_s.date_s)='$thn' and id_user='$id'");
    if ($waktu_shift == '' or $waktu_shift <= $waktu_kerja) {
        $waktu_kerjanya = $waktu_kerja;
    } else {
        $waktu_kerjanya = $waktu_shift;
    }
    $max = ($waktu_kerjanya * 300) - ($waktu_tambah - $waktu_kurang);
    return (($waktu_shift * 300) / $max) * 100;
}

function BiayaJabatan($bruto, $tun_val, $honor)
{
    $hitung = ($bruto * 0.03) + $tun_val + $bruto + $honor;
    $hasil = $hitung * 0.05;
    if ($hasil > '500000') {
        $result = '500000';
    } else {
        $result = $hasil;
    }
    return $result;
}

function PPH21($bpjs_ijht, $bpjs_jp, $biaya_jabatan, $bruto, $tunjangan_val, $honor, $ptkp)
{
    $hitung = ($bruto * 0.03) + $tunjangan_val + $bruto + $honor;
    $pot_bpjsabsensi = $bpjs_ijht + $bpjs_jp + $biaya_jabatan;
    $gaji_tun_sebelum_pjk = $hitung - $pot_bpjsabsensi;
    $besaran_gaji_tun_setahun = $gaji_tun_sebelum_pjk * 12;
    $peng_pajak_disetahunkan = $besaran_gaji_tun_setahun - $ptkp;
    if ($peng_pajak_disetahunkan <= 50000000) {
        $pph21_setahun = $peng_pajak_disetahunkan * 0.05;
        $pph21_perbulan = $pph21_setahun / 12;
    } elseif ($peng_pajak_disetahunkan <= 250000000) {
        $a = '50000000';
        $b = $peng_pajak_disetahunkan - $a;
        $c = $a * 0.05;
        $d = $b * 0.15;
        $pph21_setahun = $c + $d;
        $pph21_perbulan = $pph21_setahun / 12;
    } elseif ($peng_pajak_disetahunkan <= 500000000) {
        $a = '50000000';
        $a1 = '250000000';
        $b = $peng_pajak_disetahunkan - $a;
        $e = $b - $a1;
        $c = $a * 0.05;
        $d = $a1 * 0.15;
        $f = $e * 0.25;
        $pph21_setahun = $c + $d + $f;
        $pph21_perbulan = $pph21_setahun / 12;
    } else {
        $pph21_perbulan = 0;
    }
    return $pph21_perbulan;
}

function HitungHari($tanggal1, $tanggal2)
{
    $dt1 = strtotime($tanggal1);
    $dt2 = strtotime($tanggal2);
    $diff = abs($dt2 - $dt1);
    $hari = $diff / 86400; // 86400 detik sehari
    return $hari;
}

function HitungHariMinggu($tanggal1, $tanggal2)
{
    $dari = $tanggal1;
    $sampai = $tanggal2;
    while (strtotime($dari) < strtotime($sampai)) {
        $dari = mktime(0, 0, 0, date("m", strtotime($dari)), date("d", strtotime($dari)) + 1, date("Y", strtotime($dari)));
        $dari = date("Y-m-d ", $dari);
        $hari = hariindo($dari);
        if ($hari == 'Minggu') {
            $nilai = 1;
        } else {
            $nilai = 0;
        }
        $jumlah[] = $nilai;
    }
    return array_sum($jumlah);
}

function HitungTelat($bulan_absensi, $nip, $id_unit)
{
    $biodata = fetch_array(bukaquery("select log_finger,nama_pegawai from tm_pegawai where nip='$nip'"));
    $from = FormatTgl('00-m-Y', $bulan_absensi);
    $to = FormatTgl('t-m-Y', $bulan_absensi);
    while (strtotime($from) < strtotime($to)) {
        $from = mktime(0, 0, 0, date("m", strtotime($from)), date("d", strtotime($from)) + 1, date("Y", strtotime($from)));
        $from = date("Y-m-d ", $from);
        $tanggalan = konversitanggal($from);
        $tanggalmin = date('Y-m-d', strtotime("-1 day", strtotime($from)));
        $tanggalplus = date('Y-m-d', strtotime("+1 day", strtotime($from)));
        $in = getOne("SELECT
                    log.tanggal,
                    log.`status`
                    FROM
                    log INNER JOIN tm_pegawai ON tm_pegawai.log_finger=log.`user`
                                                where tm_pegawai.log_finger='$biodata[log_finger]' AND log.tanggal LIKE '%$from%' and log.status='I'");
        $out = getOne("SELECT
                    log.tanggal,
                    log.`status`
                    FROM
                    log INNER JOIN tm_pegawai ON tm_pegawai.log_finger=log.`user`
                    where tm_pegawai.log_finger='$biodata[log_finger]' AND log.tanggal LIKE '%$from%' and log.status='O'");
        if (FormatTgl('H:i:s', $in) < '23:59:00' and FormatTgl('H:i:s', $in > '18:00:00')) {
            $out1 = getOne("SELECT
                            log.tanggal,
                            log.`status`
                            FROM
                            log INNER JOIN tm_pegawai ON tm_pegawai.log_finger=log.`user`
                            where tm_pegawai.log_finger='$biodata[log_finger]' AND log.tanggal LIKE '%$tanggalplus%' order by log.tanggal ASC");
            if ($in != '') {
                $absen_in = FormatTgl('H:i:s', $in);
                $status = getOne("select tm_shift.nama_shift from set_shift inner join tm_shift on set_shift.id_absensi=tm_shift.id_absensi where tm_shift.bi < '$absen_in' and tm_shift.ai > '$absen_in' and set_shift.id_unit='$id_unit'");
                $jam_masuk = getOne("select tm_shift.jam_masuk from set_shift inner join tm_shift on set_shift.id_absensi=tm_shift.id_absensi where tm_shift.bi < '$absen_in' and tm_shift.ai > '$absen_in' and set_shift.id_unit='$id_unit'");
                if ($absen_in >= $jam_masuk) {
                    $jam_finger = (is_string($absen_in) ? strtotime($absen_in) : $absen_in);
                    $jam_in = (is_string($jam_finger) ? strtotime($jam_masuk) : $jam_masuk);
                    $hitung = $jam_finger - $jam_in;
                    $jam = floor($hitung / (60 * 60));
                    $telat_in = floor(($hitung - $jam * (60 * 60)) / 60);
                } else {
                    $telat_in = '';
                }
            } else {
                $absen_in = '-';
                $status = '-';
                $jam_masuk = '';
            }
            if ($out1 != '') {
                $absen_out = FormatTgl('H:i:s', $out1);
                $jam_pulang = getOne("select tm_shift.jam_pulang from set_shift inner join tm_shift on set_shift.id_absensi=tm_shift.id_absensi where tm_shift.bo < '$absen_out' and tm_shift.ao > '$absen_out' and set_shift.id_unit='$id_unit'");
                if ($absen_out <= $jam_pulang) {
                    $jam_finger = (is_string($absen_out) ? strtotime($absen_out) : $absen_out);
                    $jam_out = (is_string($jam_pulang) ? strtotime($jam_pulang) : $jam_pulang);
                    $hitung = $jam_out - $jam_finger;
                    $jam = floor($hitung / (60 * 60));
                    $telat_out = floor(($hitung - $jam * (60 * 60)) / 60);
                } else {
                    $telat_out = '';
                }
            } else {
                $absen_out = '-';
                $jam_pulang = '';
            }
            //jika normal
        } else {
            if ($in != '') {
                $absen_in = FormatTgl('H:i:s', $in);
                $status = getOne("select tm_shift.nama_shift from set_shift inner join tm_shift on set_shift.id_absensi=tm_shift.id_absensi where tm_shift.bi < '$absen_in' and tm_shift.ai > '$absen_in' and set_shift.id_unit='$id_unit'");
                $jam_masuk = getOne("select tm_shift.jam_masuk from set_shift inner join tm_shift on set_shift.id_absensi=tm_shift.id_absensi where tm_shift.bi < '$absen_in' and tm_shift.ai > '$absen_in' and set_shift.id_unit='$id_unit'");
            } else {
                $absen_in = '-';
                $status = '-';
                $jam_masuk = '';
            }
            if ($out != '' and $absen_in != '-') {
                $absen_out = FormatTgl('H:i:s', $out);
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
            $jam = floor($hitung / (60 * 60));
            $telat_in = floor(($hitung - $jam * (60 * 60)) / 60);
        } else {
            $telat_in = '0';
        }
        //hitung pulang cepet
        if ($absen_out <= $jam_pulang) {
            $jam_finger = (is_string($absen_out) ? strtotime($absen_out) : $absen_out);
            $jam_out = (is_string($jam_pulang) ? strtotime($jam_pulang) : $jam_pulang);
            $hitung = $jam_out - $jam_finger;
            $jam = floor($hitung / (60 * 60));
            $telat_out = floor(($hitung - $jam * (60 * 60)) / 60);
        } else {
            $telat_out = '0';
        }
        $telat = $telat_in + $telat_out;
        if ($telat == '0' or $telat > '5') {
        } else {
            $telat = $telat;
        }
        $totelat[] = $telat;
    }
    return array_sum($totelat);
}

function konversi_nip($nip, $batas = " ")
{
    $nip = trim($nip, " ");
    $panjang = strlen($nip);

    if ($panjang == 18) {
        $sub[] = substr($nip, 0, 8); // tanggal lahir
        $sub[] = substr($nip, 8, 6); // tanggal pengangkatan
        $sub[] = substr($nip, 14, 1); // jenis kelamin
        $sub[] = substr($nip, 3, 3); // nomor urut

        return $sub[0] . $batas . $sub[1] . $batas . $sub[2] . $batas . $sub[3];
    } elseif ($panjang == 15) {
        $sub[] = substr($nip, 0, 8); // tanggal lahir
        $sub[] = substr($nip, 8, 6); // tanggal pengangkatan
        $sub[] = substr($nip, 14, 1); // jenis kelamin

        return $sub[0] . $batas . $sub[1] . $batas . $sub[2];
    } elseif ($panjang == 9) {
        $sub = str_split($nip, 3);

        return $sub[0] . $batas . $sub[1] . $batas . $sub[2];
    } else {
        return $nip;
    }
}

function getDatesFromRange($start, $end, $format = 'Y-m-d')
{
    // Declare an empty array
    $array = array();

    // Variable that store the date interval
    // of period 1 day
    $interval = new DateInterval('P1D');
    $realEnd = new DateTime($end);
    prd($realEnd);
    $realEnd->add($interval);
    $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

    // Use loop to store date into array
    foreach ($period as $date) {
        $array[] = $date->format($format);
    }

    // Return the array elements
    return $array;
}
