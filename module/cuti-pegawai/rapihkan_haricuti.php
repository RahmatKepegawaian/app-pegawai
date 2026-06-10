<?php

include '../../conf/conf.php';
session_start();

$query = bukaquery2("select id_cuti, periode_cuti, jumlah_hari from tm_cuti order by id_cuti asc");

while($data = fetch_assoc($query)) {
    $explode = explode(',', $data['periode_cuti']);

    foreach ($explode as $value) {
        $value = str_replace('/', '-', $value);
        $dt = new DateTime($value);
        $tanggal = $dt->format('Y-m-d');

        bukaquery2("insert into tm_hari_cuti(id_cuti, tanggal) values($data[id_cuti], '$tanggal')");
    }

    echo "$data[id_cuti] => $data[jumlah_hari] SUDAH <br>";
}