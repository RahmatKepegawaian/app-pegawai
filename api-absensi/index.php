<?php

require_once 'conf/conf.php';
require_once 'conf/conf_absensi.php';
switch ((isset($_GET['rest-api']) ? $_GET['rest-api'] : '')) {
    default :
        echo "Selamat datang di WebService Rest Absensi";
        break;

    case "now":
        $nip = isset($_GET['nip']) ? $_GET['nip'] : '';
        $id_unit = isset($_GET['unit']) ? $_GET['unit'] : '';
        if ($nip == '' OR $id_unit == '') {
            header('location:index');
        } else {
            $date1 = date('00-m-Y');
            $date2 = date('t-m-Y');
            $from = $date1;
            $to = $date2;
            $badgenumber = getOne("select log_finger from tm_pegawai where nip='$nip'");
            $response = array();
            $response["data"] = array();
            while (strtotime($from) < strtotime($to)) {
                $from = mktime(0, 0, 0, date("m", strtotime($from)), date("d", strtotime($from)) + 1, date("Y", strtotime($from)));
                $from = date("Y-m-d ", $from);
                $tanggalan = konversitanggal($from);
                $tanggalanya = FormatTgl('j/n/Y', $from);
                $tanggalmin = date('j/n/Y', strtotime("-1 day", strtotime($from)));
                $tanggalplus = date('j/n/Y', strtotime("+1 day", strtotime($from)));
                //query in out
                $in = fetch_array_ODBC(bukaqueryODBC("SELECT CHECKINOUT.CHECKTIME FROM USERINFO left join CHECKINOUT on USERINFO.USERID=CHECKINOUT.USERID  
                                    where USERINFO.Badgenumber='$badgenumber' and CHECKINOUT.CHECKTYPE='I' and  CHECKINOUT.CHECKTIME LIKE '%$tanggalanya%' order by CHECKINOUT.CHECKTIME ASC"));
                $out = fetch_array_ODBC(bukaqueryODBC("SELECT CHECKINOUT.CHECKTIME FROM USERINFO left join CHECKINOUT on USERINFO.USERID=CHECKINOUT.USERID  
                                    where USERINFO.Badgenumber='$badgenumber' and CHECKINOUT.CHECKTYPE='O' and CHECKINOUT.CHECKTIME LIKE '%$tanggalanya%' order by CHECKINOUT.CHECKTIME DESC "));
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
                    $telat_in = floor($hitung / 60);
                } else {
                    $telat_in = '0';
                }
                //hitung pulang cepet
                if ($absen_out <= $jam_pulang) {
                    $jam_finger = (is_string($absen_out) ? strtotime($absen_out) : $absen_out);
                    $jam_out = (is_string($jam_pulang) ? strtotime($jam_pulang) : $jam_pulang);
                    $hitung = $jam_out - $jam_finger;
                    $telat_out = floor($hitung / 60);
                } else {
                    $telat_out = '0';
                }
                $telat = $telat_in + $telat_out;
                if ($telat == '0' OR $telat > '5') {
                    
                } else {
                    $telat = $telat;
                };
                //json
                $list = array(
                    'tanggal' => $tanggalan . " (" . hariindo($from) . ")",
                    'in' => $absen_in,
                    'out' => $absen_out,
                    'telat' => $telat,
                    'status' => $status
                );
                array_push($response["data"], $list);
            }
            header('Content-Type: application/json');
            echo json_encode($response);
        }
        break;

    case "cari":
        $nip = isset($_GET['nip']) ? $_GET['nip'] : '';
        $id_unit = isset($_GET['unit']) ? $_GET['unit'] : '';
        $bulan_post = isset($_GET['bulan']) ? $_GET['bulan'] : null;
        $tahun_post = isset($_GET['tahun']) ? $_GET['tahun'] : null;
        $tmtcek = FormatTgl('Y-m-d', $tahun_post . "-" . $bulan_post . "-01");
        if ($nip == '' OR $id_unit == '' OR $bulan_post == '' OR $tahun_post == '') {
            header('location:index');
        } else {
            $from = FormatTgl('00-m-Y', $tmtcek);
            $to = FormatTgl('t-m-Y', $tmtcek);
            $badgenumber = getOne("select log_finger from tm_pegawai where nip='$nip'");
            $response = array();
            $response["data"] = array();
            while (strtotime($from) < strtotime($to)) {
                $from = mktime(0, 0, 0, date("m", strtotime($from)), date("d", strtotime($from)) + 1, date("Y", strtotime($from)));
                $from = date("Y-m-d ", $from);
                $tanggalan = konversitanggal($from);
                $tanggalanya = FormatTgl('j/n/Y', $from);
                $tanggalmin = date('j/n/Y', strtotime("-1 day", strtotime($from)));
                $tanggalplus = date('j/n/Y', strtotime("+1 day", strtotime($from)));
                //query in out
                $in = fetch_array_ODBC(bukaqueryODBC("SELECT CHECKINOUT.CHECKTIME FROM USERINFO left join CHECKINOUT on USERINFO.USERID=CHECKINOUT.USERID  
                                    where USERINFO.Badgenumber='$badgenumber' and CHECKINOUT.CHECKTYPE='I' and  CHECKINOUT.CHECKTIME LIKE '%$tanggalanya%' order by CHECKINOUT.CHECKTIME ASC"));
                $out = fetch_array_ODBC(bukaqueryODBC("SELECT CHECKINOUT.CHECKTIME FROM USERINFO left join CHECKINOUT on USERINFO.USERID=CHECKINOUT.USERID  
                                    where USERINFO.Badgenumber='$badgenumber' and CHECKINOUT.CHECKTYPE='O' and CHECKINOUT.CHECKTIME LIKE '%$tanggalanya%' order by CHECKINOUT.CHECKTIME DESC "));
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
                            //$telat_in = floor($hitung / 600);
                        } else {
                            //$telat_in = '';
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
                            //$telat_out = floor($hitung / 600);
                        } else {
                            //$telat_out = '';
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
                    $telat_in = floor($hitung / 60);
                } else {
                    $telat_in = '0';
                }
                //hitung pulang cepet
                if ($absen_out <= $jam_pulang) {
                    $jam_finger = (is_string($absen_out) ? strtotime($absen_out) : $absen_out);
                    $jam_out = (is_string($jam_pulang) ? strtotime($jam_pulang) : $jam_pulang);
                    $hitung = $jam_out - $jam_finger;
                    $telat_out = floor($hitung / 60);
                } else {
                    $telat_out = '0';
                }
                $telat = $telat_in + $telat_out;
                if ($telat == '0') {
                    
                } else {
                    $telat = $telat;
                };
                //json
                $list = array(
                    'tanggal' => $tanggalanya,
                    'in' => $absen_in,
                    'out' => $absen_out,
                    'telat' => $telat,
                    'status' => $status
                );
                array_push($response["data"], $list);
            }
            header('Content-Type: application/json');
            echo json_encode($response);
        }
        break;

    case "telat":
        $nip = isset($_GET['nip']) ? $_GET['nip'] : '';
        $id_unit = isset($_GET['unit']) ? $_GET['unit'] : '';
        $telat_total = HitungTelat($nip, $id_unit);
        $response = array();
        $response["data"] = array();
        $list = array(
            'total_telat' => $telat_total,
        );
        array_push($response["data"], $list);
        ('Content-Type: application/json');
        echo json_encode($response);
        break;
}
?>
