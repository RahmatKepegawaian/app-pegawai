<?php

require_once 'setting.php';

function host_odbc() {
    global $db_hostname_odbc;
    return $db_hostname_odbc;
}

function bukakoneksiODBC() {
    global $db_hostname_odbc, $_odbcdb_username, $db_password_odbc;
    $konektor = odbc_connect($db_hostname_odbc, '', '') or die("<font color=red><h3>Not Connected ..!!</h3></font>");
    return $konektor;
}

function fetch_array_ODBC($sql) {
    return odbc_fetch_array($sql);
}

function bukaqueryODBC($sql) {
    $result = odbc_exec(bukakoneksiODBC(),$sql)
            or die(odbc_error() . "<br/><font color=red><b>hmmmmmmm.....??????????</b>");
    return $result;
}

function getOneODBC($sql) {
    $hasil = bukaqueryODBC($sql);
    list($result) = odbc_fetch_array($hasil);
    return $result;
}

?>
