<?php
require_once ('libs/aes-encrypt/function.php');
$value = $_GET['valuenya'];
echo "hasil : ".paramEncrypt($value);
?>