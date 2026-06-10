<?php

include '../../conf/conf.php';
session_start();

$id = isset($_GET['id']) ? $_GET['id'] : null;
$bc = isset($_GET['bc']) ? $_GET['bc'] : null;

$mime = $bc == 1 ? 'mime1' : 'mime2';
$bc = $bc == 1 ? 'buk1' : 'buk2';
$data = fetch_assoc(bukaquery2("select $bc bc, $mime mime from tm_cuti where id_cuti='$id'"));

header('Content-Type: '. $data['mime']);
echo $data["bc"];