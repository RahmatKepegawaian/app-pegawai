<?php

$koneksi = odbc_connect("ekin","ekin","");
if($koneksi){
    echo "koneksi berhasil";
}else{
    echo "gagal";
}
?>