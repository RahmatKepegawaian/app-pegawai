<?php
switch ((isset($url['act']) ? $url['act'] : '')) {
    default :

        break;

    case "home":
        ?>
        <div class="box">
            <div class="box-header with-border">  
                <h3 class="box-title fa fa-dashboard">  Dashboard </h3>               
            </div>
            <div class="box box-success">
                <div class="box-body">
                    <h3 class="box-title">Sistem Informasi Pegawai RSUD XXX</h3>
                </div>
            </div>
        </div>

        <?php
        break;

    case"dashboard":

        //cek kadarluasa STR
        $str = getOne("SELECT CONCAT_WS('-',SUBSTRING(periode, 20, 4),SUBSTRING(periode, 17, 2),SUBSTRING(periode, 14, 2)) as kadarluasa FROM tm_str where id_user='$id_user' ORDER BY id_str DESC");
        $sip = getOne("SELECT CONCAT_WS('-',SUBSTRING(periode, 20, 4),SUBSTRING(periode, 17, 2),SUBSTRING(periode, 14, 2)) as kadarluasa FROM tm_sip where id_user='$id_user' ORDER BY id_sip DESC");
        $spk = getOne("SELECT CONCAT_WS('-',SUBSTRING(periode, 20, 4),SUBSTRING(periode, 17, 2),SUBSTRING(periode, 14, 2)) as kadarluasa FROM tm_spk where id_user='$id_user' ORDER BY id_spk DESC");
        $rkk = getOne("SELECT CONCAT_WS('-',SUBSTRING(periode, 20, 4),SUBSTRING(periode, 17, 2),SUBSTRING(periode, 14, 2)) as kadarluasa FROM tm_rkk where id_user='$id_user' ORDER BY id_rkk DESC");
        $acls = getOne("SELECT CONCAT_WS('-',SUBSTRING(periode, 20, 4),SUBSTRING(periode, 17, 2),SUBSTRING(periode, 14, 2)) as kadarluasa FROM tm_acls where id_user='$id_user' ORDER BY id_acls DESC");
        $atls = getOne("SELECT CONCAT_WS('-',SUBSTRING(periode, 20, 4),SUBSTRING(periode, 17, 2),SUBSTRING(periode, 14, 2)) as kadarluasa FROM tm_atls where id_user='$id_user' ORDER BY id_atls DESC");
        $btcls = getOne("SELECT CONCAT_WS('-',SUBSTRING(periode, 20, 4),SUBSTRING(periode, 17, 2),SUBSTRING(periode, 14, 2)) as kadarluasa FROM tm_btcls where id_user='$id_user' ORDER BY id_btcls DESC");
        $apn = getOne("SELECT CONCAT_WS('-',SUBSTRING(periode, 20, 4),SUBSTRING(periode, 17, 2),SUBSTRING(periode, 14, 2)) as kadarluasa FROM tm_apn where id_user='$id_user' ORDER BY id_apn DESC");
        $phelebethomy = getOne("SELECT CONCAT_WS('-',SUBSTRING(periode, 20, 4),SUBSTRING(periode, 17, 2),SUBSTRING(periode, 14, 2)) as kadarluasa FROM tm_phelebethomy where id_user='$id_user' ORDER BY id_phelebethomy DESC");
        if (HitungHari(date('Y-m-d'), $str) <= 180 OR HitungHari(date('Y-m-d'), $sip) <= 180 OR HitungHari(date('Y-m-d'), $spk) <= 180 OR HitungHari(date('Y-m-d'), $rkk) <= 180 OR HitungHari(date('Y-m-d'), $acls) <= 180 OR HitungHari(date('Y-m-d'), $atls) <= 180
                OR HitungHari(date('Y-m-d'), $btcls) <= 180 OR HitungHari(date('Y-m-d'), $apn) <= 180 OR HitungHari(date('Y-m-d'), $phelebethomy) <= 180) {
            ?>
            <!-- Pemberitahuan-->
            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header bg-aqua">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title fa fa-info-circle "> PEMBERITAHUAN</h4>
                        </div>

                        <div class="modal-body">
                            Hai,  <?php echo $nama_pegawai; ?>.. <br><br><p> Mohon segera perbarui data-data kamu di bawah ini :</p>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <th>DOKUMEN</th>
                                <th>SISA MASA AKTIF</th>
                                </thead>
                                <?php
                                if (HitungHari(date('Y-m-d'), $str) <= 180) {
                                    echo "<tr>"
                                    . "<td><b>STR</b></td>"
                                    . "<td><b>" . (date('Y-m-d') > $str ? 'Sudah Kadaluarsa' : (HitungHari(date('Y-m-d'), $str)).' Hari') . "</b></td>"
                                    . "</tr>";
                                }
                                if (HitungHari(date('Y-m-d'), $sip) <= 180) {
                                    echo "<tr>"
                                    . "<td><b>SIP</b></td>"
                                    . "<td><b>" . (date('Y-m-d') > $sip ? 'Sudah Kadaluarsa' : (HitungHari(date('Y-m-d'), $sip)).' Hari') . "</b></td>"
                                    . "</tr>";
                                }
                                if (HitungHari(date('Y-m-d'), $spk) <= 180) {
                                    echo "<tr>"
                                    . "<td><b>SPK</b></td>"
                                    . "<td><b>" . (date('Y-m-d') > $spk ? 'Sudah Kadaluarsa' : (HitungHari(date('Y-m-d'), $spk)).' Hari') . "</b></td>"
                                    . "</tr>";
                                }
                                if (HitungHari(date('Y-m-d'), $rkk) <= 180) {
                                    echo "<tr>"
                                    . "<td><b>RKK</b></td>"
                                    . "<td><b>" . (date('Y-m-d') > $rkk ? 'Sudah Kadaluarsa' : (HitungHari(date('Y-m-d'), $rkk)).' Hari') . "</b></td>"
                                    . "</tr>";
                                }
                                if (HitungHari(date('Y-m-d'), $acls) <= 180) {
                                    echo "<tr>"
                                    . "<td><b>ACLS</b></td>"
                                    . "<td><b>" . (date('Y-m-d') > $acls ? 'Sudah Kadaluarsa' : (HitungHari(date('Y-m-d'), $acls)).' Hari') . "</b></td>"
                                    . "</tr>";
                                }
                                if (HitungHari(date('Y-m-d'), $atls) <= 180) {
                                    echo "<tr>"
                                    . "<td><b>ATLS</b></td>"
                                    . "<td><b>" . (date('Y-m-d') > $atls ? 'Sudah Kadaluarsa' : (HitungHari(date('Y-m-d'), $atls)).' Hari') . "</b></td>"
                                    . "</tr>";
                                }
                                if (HitungHari(date('Y-m-d'), $btcls) <= 180) {
                                    echo "<tr>"
                                    . "<td><b>BTCLS</b></td>"
                                    . "<td><b>" . (date('Y-m-d') > $btcls ? 'Sudah Kadaluarsa' : (HitungHari(date('Y-m-d'), $btcls)).' Hari') . "</b></td>"
                                    . "</tr>";
                                }
                                if (HitungHari(date('Y-m-d'), $apn) <= 180) {
                                    echo "<tr>"
                                    . "<td><b>APN</b></td>"
                                    . "<td><b>" . (date('Y-m-d') > $apn ? 'Sudah Kadaluarsa' : (HitungHari(date('Y-m-d'), $apn)).' Hari') . "</b></td>"
                                    . "</tr>";
                                }
                                if (HitungHari(date('Y-m-d'), $phelebethomy) <= 180) {
                                    echo "<tr>"
                                    . "<td><b>PHELEBETHOMY</b></td>"
                                    . "<td><b>" . (date('Y-m-d') > $phelebethomy ? 'Sudah Kadaluarsa' : (HitungHari(date('Y-m-d'), $phelebethomy)).' Hari') . "</b></td>"
                                    . "</tr>";
                                }
                                ?>
                            </table>
                            <br><p>Jangan lupa upload kembali data yang terbaru di SIMPEG kamu, dan berikan Hard Copy ke Kepegawaian RSUD Tanah Abang, Terima Kasih :)</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>


        <div class="box">
            <div class="box-header with-border">  
                <h3 class="box-title fa fa-dashboard">Dashboard</h3>               
            </div>
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">INDIKATOR KINERJA PEGAWAI</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <!-- Grafik -->
                    <div class="chart">
                        <?php $tampil_grafik = 'yes'; ?>
                        <div id='container'></div> 
                    </div>
                    <!-- Tutup Grafik -->
                    <!-- Hukuman -->
                    <div class="x_panel">
                        <!-- Riwayat Pendidikan -->
                        
                        <!-- Riwayat Pendidikan -->
                        <div class="panel-group" id="dropdown">
                            <div class="panel panel-warning">
                                <div class="panel-heading">
                                    <h4 class="panel-title fa fa-calendar-check-o">
                                        <a data-toggle="collapse" data-parent="#dropdown" href="#collapse2">
                                            REPORT KEHADIRAN</a>
                                    </h4>
                                </div>
                                <div id="collapse2" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="box-body table-responsive">
                                            <table id="example2" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Bulan</th>
                                                        <th>Sakit < 2 hr</th>
                                                        <th>Sakit > 2 Hr</th>
                                                        <th>Alpha</th>
                                                        <th>Izin</th>
                                                        <th>Telat</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $no = 0;
                                                    $tm_kehadiran = bukaquery("SELECT tm_waktu_k.sakit1, tm_waktu_k.sakit2, tm_waktu_k.alpha, tm_waktu_k.izin, tm_waktu_k.telat, tm_waktu_k.date_k  FROM tm_waktu_k where tm_waktu_k.id_user='$id_user' and year(tm_waktu_k.date_k)='$thn_now'");
                                                    while ($kehadiran = fetch_array($tm_kehadiran)) {
                                                        $no++;
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $no; ?></td>
                                                            <td><?php echo konversiBulanTahun($kehadiran['date_k']); ?></td>
                                                            <td><?php echo $kehadiran['sakit1'] ?></td>
                                                            <td><?php echo $kehadiran['sakit2'] ?></td> 
                                                            <td><?php echo $kehadiran['alpha'] ?></td>   
                                                            <td><?php echo $kehadiran['izin'] ?></td> 
                                                            <td style="background-color: <?php
																if ($kehadiran['telat'] > 0) {
																	echo '#e48080';}?>;"><?php echo $kehadiran['telat'] ?></td> 
                                                        </tr>                                  
                                                        <?php
                                                    }
                                                    ?>    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <!-- Tutup Hukuman -->
                </div>
                <!-- /.box-body -->
            </div>
        </div>
            
        <div class="col-12">
            <div class="box">           
                <div class="box-header">
                    <h3 class="box-title danger">AGENDA</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <?php $tampil_agenda_rapat = 'yes'; ?>
                    <div class="box-body">   
                        <div id="agenda"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        break;
}
?>
<script>
    function post_request_slip_gaji(button_id, id_user, id_penilaian) {

        console.log('button_id '+button_id);
        console.log('id_user '+id_user);
        console.log('id_penilaian '+id_penilaian);

        $(button_id).html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>");

        $.ajax({
            url: "<?php echo $url_api_dashboard; ?>?action=post_slipgaji_request_user",
            method: "POST",
            data: {
                id_user: id_user,
                id_penilaian: id_penilaian
            },
            dataType: "JSON",
            success: function(response) {

                console.log(response);

                if(response.status == 1) {

                    swal({
                        title: "Permintaan Slip Gaji berhasil",
                        type: "success"
                    }, function() {
                        window.location.reload();
                    });
                } else {

                    $(button_id).html("Request");
                    swal("Permintaan Slip Gaji Gagal", "", "error");
                    console.log("Kode : POSTREQSLIPGAJI. Status != 1");
                }
            }, 
            error: function(error) {

                $(button_id).html("Request");
                swal("Permintaan Slip Gaji Gagal", "", "error");
                console.log("Kode : POSTREQSLIPGAJI. Gagal mengirim permintaan "+error.status+"-"+error.statusText);
                
            }
        });
    }
</script>
        
