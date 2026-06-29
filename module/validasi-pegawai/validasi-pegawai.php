<?php
$aksi = "module/validasi-pegawai/aksi-validasi-pegawai?";
// error_reporting(E_ALL);          // Laporkan semua jenis error
// ini_set('display_errors', 1);    // Tampilkan error di browser

$id = isset($url['id']) ? $url['id'] : null;
$dpa = isset($url['dpa']) ? $url['dpa'] : null;
$cek_hak_akses = mysqli_fetch_array(bukaquery("select tm_level.menu_val_pj,tm_level.menu_val_kasatpel,tm_level.menu_val_kasie from tm_level"
                . " inner join tm_user on tm_level.id_level=tm_user.id_level where tm_user.id_user='$id_user'"));
$return_view_as_table = $idlevel == 'LVL-000011' || $idlevel == 'LVL-000005'; // table view only for direktur or kasie

if ($cek_hak_akses['menu_val_pj'] == '1' OR $cek_hak_akses['menu_val_kasatpel'] == '1' OR $cek_hak_akses['menu_val_kasie'] == '1' OR $hak_akses['menu_kepegawaian'] == '1') {

    switch ((isset($url['act']) ? $url['act'] : '')) {
        default :
            header("location:error404");
            break;
        case "validasi-waktu-pengurangan":
            ?>
            <!--Modal Add waktu -->
            <div class="box">
                <div class="box-header with-border">  
                    <h3 class="box-title fa fa-clock-o">  VALIDASI WAKTU PENGURANGAN </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                title="Collapse">
                            <i class="fa fa-minus"></i></button>
                    </div>                
                </div>
                <div class="box-body">
                    <div class="col-md-8">
                        <?php
                        $read = $hak_akses['menu_kepegawaian'] == '1' ? "" : "readonly";
                        $pegawai = fetch_array(bukaquery("select tm_pegawai.id_unit,tm_pegawai.nip,tm_pegawai.nama_pegawai, tm_pegawai.jk, tm_pegawai.foto from tm_pegawai where tm_pegawai.id_user='$id' "));
                        $bulan = FormatTgl('m', TanggalAkhirBulanKemarin());
                        $tahun = FormatTgl('Y', TanggalAkhirBulanKemarin());
                        $url = $api_absensi . '?rest-api=cari&nip=' . $pegawai['nip'] . '&unit=' . $pegawai['id_unit'] . '&bulan=' . $bulan . '&tahun=' . $tahun;
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_HTTPGET, true);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $response_json = curl_exec($ch);
                        curl_close($ch);
                        $response = json_decode($response_json, true);
                        foreach ($response['data'] as $row) {
                            if ($row['telat'] == '0' or $row['telat'] <= getOne("select setup.dispensasi_absensi from setup")) {
                                $telat = '';
                            } else {
                                $telat = $row['telat'];
                            };
                            $totelat[] = $telat;
                        }

                        if (getOne("select id_waktu_k from tm_waktu_k where month(tm_waktu_k.date_k)='$bulan' and year(tm_waktu_k.date_k)='$tahun' and tm_waktu_k.id_user='$id'") == '') {
                            if ($hak_akses['menu_kepegawaian'] == '1') {
                                $link = $aksi . paramEncrypt('module=validasi-pegawai&act=add-waktu-pengurangan&id=' . $id . '&link=kepegawaian');
                            } else {
                                $link = $aksi . paramEncrypt('module=validasi-pegawai&act=add-waktu-pengurangan&id=' . $id . '');
                            }
                            ?>
                            <form role="form" action="<?php echo $link; ?>" method="post"> 
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama Pegawai <?php echo $bulan; ?></label>
                                        <input type="text" class="form-control" value="<?php echo $pegawai['nama_pegawai']; ?>" readonly>
                                        <label>Alpha</label>
                                        <input type="number" class="form-control" name="alpha" maxlength="2" placeholder="Hari" required>
                                        <label>Sakit < 2 Hari</label>
                                        <input type="number" class="form-control" name="sakit1" maxlength="2" placeholder="Hari" required>
                                        <label>Sakit > 2 Hari</label>
                                        <input type="number" class="form-control" name="sakit2"  maxlength="2" placeholder="Hari" required>                                                    
                                        <label>Izin</label>
                                        <input type="number" class="form-control" name="izin" maxlength="2" placeholder="Hari" required>
                                        <label>Telat</label>
                                        <input type="number" class="form-control" name="telat" maxlength="3" placeholder="Menit" required>
                                        <label>Cuti Sakit</label>
                                        <input type="number" class="form-control" name="ct_sakit_k" maxlength="2" placeholder="Hari" required>
                                        <label>Cuti Alasan Penting</label>
                                        <input type="number" class="form-control" name="ct_alasan_k" maxlength="2" placeholder="Hari" required>
                                        <label>Cuti Persalinan</label>
                                        <input type="number" class="form-control" name="ct_persalinan_k" maxlength="2" placeholder="Hari" required>
                                        <label>Izin Setengah Hari</label>
                                        <input type="number" class="form-control" name="izin_setengah_hari" maxlength="2" placeholder="Hari" required>
                                        <label>Meninggal</label>
                                        <input type="number" class="form-control" name="meninggal" maxlength="2" placeholder="Hari" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=list-validasi-pegawai'); ?>">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </a>
                                    <input type="submit" class="btn btn-primary" value="Simpan">
                                </div> 
                            </form>
                            <?php
                        } else {
                            $waktu_k = fetch_array(bukaquery("select * from tm_waktu_k where month(tm_waktu_k.date_k)='$bulan' and year(tm_waktu_k.date_k)='$tahun' and tm_waktu_k.id_user='$id'"));
                            if ($hak_akses['menu_kepegawaian'] == '1') {
                                $link = $aksi . paramEncrypt('module=validasi-pegawai&act=update-waktu-pengurangan&id=' . $waktu_k['id_waktu_k'] . '&link=kepegawaian');
                            } else {
                                $link = $aksi . paramEncrypt('module=validasi-pegawai&act=update-waktu-pengurangan&id=' . $waktu_k['id_waktu_k'] . '');
                            }
                            ?>
                            <form role="form" action="<?php echo $link; ?>" method="post"> 
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama Pegawai </label>
                                        <input type="text" class="form-control" value="<?php echo $pegawai['nama_pegawai']; ?>" readonly>
                                        <label>Alpha</label>
                                        <input type="number" class="form-control" value="<?php echo $waktu_k['alpha']; ?>" maxlength="2" name="alpha" placeholder="Hari" required>
                                        <label>Sakit < 2 Hari</label>
                                        <input type="number" class="form-control" value="<?php echo $waktu_k['sakit1']; ?>" maxlength="2" name="sakit1" placeholder="Hari" required>
                                        <label>Sakit > 2 Hari</label>
                                        <input type="number" class="form-control" value="<?php echo $waktu_k['sakit2']; ?>" maxlength="2" name="sakit2" placeholder="Hari" required>                                                    
                                        <label>Izin</label>
                                        <input type="number" class="form-control" value="<?php echo $waktu_k['izin']; ?>" maxlength="2" name="izin" placeholder="Hari" required>
                                        <label>Telat</label>
                                        <input type="number" class="form-control" value="<?php echo $waktu_k['telat']; ?>" maxlength="3" name="telat" placeholder="Menit" required>
                                        <label>Cuti Sakit</label>
                                        <input type="number" class="form-control" value="<?php echo $waktu_k['ct_sakit_k']; ?>" maxlength="2" name="ct_sakit_k" placeholder="Hari" required>
                                        <label>Cuti Alasan Penting</label>
                                        <input type="number" class="form-control" value="<?php echo $waktu_k['ct_alasan_k']; ?>" maxlength="2" name="ct_alasan_k" placeholder="Hari" required>
                                        <label>Cuti Persalinan</label>
                                        <input type="number" class="form-control" value="<?php echo $waktu_k['ct_persalinan_k']; ?>" maxlength="2" name="ct_persalinan_k" placeholder="Hari" required>
                                        <label>Izin Setengah hari</label>
                                        <input type="number" class="form-control" value="<?php echo $waktu_k['izin_setengah_hari']; ?>" maxlength="2" name="izin_setengah_hari" placeholder="Hari" required>
                                        <label>Meninggal</label>
                                        <input type="number" class="form-control" value="<?php echo $waktu_k['meninggal']; ?>" maxlength="2" name="meninggal" placeholder="Hari" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=list-validasi-pegawai'); ?>">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </a>
                                    <input type="submit" class="btn btn-warning" value="Update">
                                </div> 
                            </form>

                        <?php } ?> 
                    </div>
                    <div class="col-md-4">
                        <center>
                            <?php
                            if ($pegawai['foto'] == '-' OR $pegawai['foto'] == '') {
                                if ($pegawai['jk'] == 'L') {
                                    $foto = 'img/laki.png';
                                } else {
                                    $foto = 'img/perempuan.png';
                                }
                            } else {
                                $foto = "img/" . $pegawai['foto'];
                            }
                            ?>
                            <br><br>
                            <img src = "<?php echo $foto; ?>" width="500px" height="500px" class="img-squere" >
                        </center>
                    </div>
                </div>
            </div>
            <!-- Tutup Modal waktu -->
            <?php
            break;

        case "validasi-waktu-penambahan":
            ?>
            <!--Modal Add waktu -->
            <div class="box">
                <div class="box-header with-border">  
                    <h3 class="box-title fa fa-clock-o">  VALIDASI WAKTU PENAMBAHAN </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                title="Collapse">
                            <i class="fa fa-minus"></i></button>
                    </div>                
                </div>
                <div class="box-body">
                    <div class="col-md-8">
                        <?php
                        if ($hak_akses['menu_kepegawaian'] == '1') {
                            $link = $aksi . paramEncrypt('module=validasi-pegawai&act=add-waktu-penambahan&id=' . $id . '&link=kepegawaian');
                        } else {
                            $link = $aksi . paramEncrypt('module=validasi-pegawai&act=add-waktu-penambahan&id=' . $id . '');
                        }
                        $pegawai = fetch_array(bukaquery("select tm_pegawai.id_unit,tm_pegawai.nip,tm_pegawai.nama_pegawai, tm_pegawai.jk, tm_pegawai.foto from tm_pegawai where tm_pegawai.id_user='$id' "));
                        $id_waktu_t = getOne("select id_waktu_t from tm_waktu_t where month(tm_waktu_t.date_t)='$bln_sebelumnya' and year(tm_waktu_t.date_t)='$thn' and tm_waktu_t.id_user='$id'");
                        if ($id_waktu_t == '') {
                            ?>
                            <form role="form" action="<?php echo $link; ?>" method="post"> 
                                <div class="modal-body">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label>Nama Pegawai</label>
                                            <input type="text" class="form-control" value="<?php echo $pegawai['nama_pegawai']; ?>" readonly>
                                            <label>Cuti Sakit</label>
                                            <input type="number" class="form-control" name="ct_sakit_t" maxlength="2" placeholder="Hari" required>
                                            <label>Cuti Alasan Penting</label>
                                            <input type="number" class="form-control" name="ct_alasan_t" maxlength="2" placeholder="Hari" required>
                                            <label>Cuti Tahunan</label>
                                            <input type="number" class="form-control" name="ct_tahunan_t" value="<?php echo getOne("SELECT COUNT(tm_hari_cuti.id_cuti) as jumlah_hari
                                                                                                                                    FROM tm_cuti
                                                                                                                                    inner join tm_pegawai on tm_cuti.id_user=tm_pegawai.id_user
                                                                                                                                    INNER JOIN tm_hari_cuti on tm_hari_cuti.id_cuti=tm_cuti.id_cuti                                                        
                                                                                                                                    where tm_pegawai.id_user='$id' and month(tm_hari_cuti.tanggal)='$bln_sebelumnya' and year(tm_hari_cuti.tanggal)='$thn' and tm_cuti.id_ketidakhadiran = 'AKT-000012'"); ?>" maxlength="2" placeholder="Hari" required>
                                            <label>Diklat</label>
                                            <input type="number" class="form-control" name="diklat" maxlength="2" placeholder="Hari" required>
                                            <label>SPD</label>
                                            <input type="number" class="form-control" name="spd" maxlength="2" placeholder="Hari" required>
                                            <label>Haji</label>
                                            <input type="number" class="form-control" name="haji" maxlength="2" placeholder="Hari" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=list-validasi-pegawai'); ?>">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </a>
                                    <input type="submit" class="btn btn-primary" value="Simpan">
                                </div> 
                            </form>
                            <?php
                        } else {
                            $waktu_t = fetch_array(bukaquery("select * from tm_waktu_t where id_user=" . $id . " and id_waktu_t='$id_waktu_t'"));
                            if ($hak_akses['menu_kepegawaian'] == '1') {
                                $link = $aksi . paramEncrypt('module=validasi-pegawai&act=update-waktu-penambahan&id=' . $id_waktu_t . '&link=kepegawaian');
                            } else {
                                $link = $aksi . paramEncrypt('module=validasi-pegawai&act=update-waktu-penambahan&id=' . $id_waktu_t . '');
                            }
                            ?>
                            <form role="form" action="<?php echo $link; ?>" method="post"> 
                                <div class="modal-body">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label>Nama Pegawai</label>
                                            <input type="text" class="form-control" value="<?php echo $pegawai['nama_pegawai']; ?>" readonly>
                                            <label>Cuti Sakit</label>
                                            <input type="number" class="form-control" value="<?php echo $waktu_t['ct_sakit_t']; ?>" name="ct_sakit_t" maxlength="2" placeholder="Hari" required>
                                            <label>Cuti Alasan Penting</label>
                                            <input type="number" class="form-control" value="<?php echo $waktu_t['ct_alasan_t']; ?>" name="ct_alasan_t" maxlength="2" placeholder="Hari" required>
                                            <label>Cuti Tahunan</label>
                                            <input type="number" class="form-control" value="<?php echo $waktu_t['ct_tahunan_t']; ?>" name="ct_tahunan_t" maxlength="2" placeholder="Hari" required>
                                            <label>Diklat</label>
                                            <input type="number" class="form-control" value="<?php echo $waktu_t['diklat']; ?>" name="diklat" maxlength="2" placeholder="Hari" required>
                                            <label>SPD</label>
                                            <input type="number" class="form-control" value="<?php echo $waktu_t['spd']; ?>" name="spd" maxlength="2" placeholder="Hari" required>
                                            <label>Haji</label>
                                            <input type="number" class="form-control" value="<?php echo $waktu_t['haji']; ?>" name="haji" maxlength="2" placeholder="Hari" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=list-validasi-pegawai'); ?>">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </a>
                                    <input type="submit" class="btn btn-warning" value="Update">
                                </div> 
                            </form>
                        <?php } ?> 
                    </div>
                    <div class="col-md-4">
                        <center>
                            <?php
                            if ($pegawai['foto'] == '-' OR $pegawai['foto'] == '') {
                                if ($pegawai['jk'] == 'L') {
                                    $foto = 'img/laki.png';
                                } else {
                                    $foto = 'img/perempuan.png';
                                }
                            } else {
                                $foto = "img/" . $pegawai['foto'];
                            }
                            ?>
                            <br><br>
                            <img src = "<?php echo $foto; ?>" width="300px" height="300px" class="img-squere" >
                        </center>
                    </div>
                </div>
            </div>
            <!-- Tutup Modal waktu -->
            <?php
            break;

        case "validasi-waktu-shift":
            ?>
            <!--Modal Add waktu -->
            <div class="box">
                <div class="box-header with-border">  
                    <h3 class="box-title fa fa-clock-o">  VALIDASI WAKTU SHIFTING </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                title="Collapse">
                            <i class="fa fa-minus"></i></button>
                    </div>                
                </div>
                <div class="box-body">
                    <div class="col-md-8">
                        <?php
                        if ($hak_akses['menu_kepegawaian'] == '1') {
                            $link = $aksi . paramEncrypt('module=validasi-pegawai&act=add-waktu-shifting&id=' . $id . '&link=kepegawaian');
                        } else {
                            $link = $aksi . paramEncrypt('module=validasi-pegawai&act=add-waktu-shifting&id=' . $id . '');
                        }
                        $pegawai = fetch_array(bukaquery("select tm_pegawai.id_unit,tm_pegawai.nip,tm_pegawai.nama_pegawai, tm_pegawai.jk, tm_pegawai.foto from tm_pegawai where tm_pegawai.id_user='$id' "));
                        $id_waktu_s = getOne("select id_waktu_s from tm_waktu_s where month(tm_waktu_s.date_s)='$bln_sebelumnya' and year(tm_waktu_s.date_s)='$thn' and tm_waktu_s.id_user='$id'");
                        if ($id_waktu_s == '') {
                            ?>
                            <form role="form" action="<?php echo $link; ?>" method="post"> 
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama Pegawai</label>
                                        <input type="text" class="form-control" value="<?php echo $pegawai['nama_pegawai']; ?>" readonly>
                                        <label>Jumlah Hari Kerja Sore</label>
                                        <input type="number" class="form-control" name="j_hks" maxlength="2" placeholder="Hari" required>
                                        <label>Jumlah Hari Kerja Malam</label>
                                        <input type="number" class="form-control" name="j_hkm" maxlength="2" placeholder="Hari" required>
                                        <label>Jumlah Hari Libur Pagi</label>
                                        <input type="number" class="form-control" name="j_hlp" maxlength="2" placeholder="Hari" required>
                                        <label>Jumlah Hari Libur Sore</label>
                                        <input type="number" class="form-control" name="j_hls" maxlength="2" placeholder="Hari" required>
                                        <label>Jumlah Hari Libur Malam</label>
                                        <input type="number" class="form-control" name="j_hlm" maxlength="2" placeholder="Hari" required>
                                        <label>Jumlah Hari Raya Pagi</label>
                                        <input type="number" class="form-control" name="j_hrp" maxlength="2" placeholder="Hari" required>
                                        <label>Jumlah Hari Raya Sore</label>
                                        <input type="number" class="form-control" name="j_hrs" maxlength="2" placeholder="Hari" required>
                                        <label>Jumlah Hari Raya Malam</label>
                                        <input type="number" class="form-control" name="j_hrm" maxlength="2" placeholder="Hari" required>
                                        <label>Jumlah Hari Non Shift</label>
                                        <input type="number" class="form-control" name="j_ns" maxlength="2" value="<?php echo getOne("select tm_hari_kerja.hari from tm_hari_kerja where month(tm_hari_kerja.bulan)='$bln_sebelumnya' and year(tm_hari_kerja.bulan)='$thn' "); ?>" placeholder="Hari" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=list-validasi-pegawai'); ?>">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </a>
                                    <input type="submit" class="btn btn-primary" value="Simpan">
                                </div> 
                            </form>
                            <?php
                        } else {
                            $waktu_s = fetch_array(bukaquery("select * from tm_waktu_s where id_user=" . $id . " and id_waktu_s='$id_waktu_s'"));
                            if ($hak_akses['menu_kepegawaian'] == '1') {
                                $link = $aksi . paramEncrypt('module=validasi-pegawai&act=update-waktu-shifting&id=' . $id_waktu_s . '&link=kepegawaian');
                            } else {
                                $link = $aksi . paramEncrypt('module=validasi-pegawai&act=update-waktu-shifting&id=' . $id_waktu_s . '');
                            }
                            ?>
                            <form role="form" action="<?php echo $link; ?>" method="post"> 
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama Pegawai</label>
                                        <input type="text" class="form-control" value="<?php echo $pegawai['nama_pegawai']; ?>" readonly>
                                        <label>Jumlah Hari Kerja Sore</label>
                                        <input type="number" class="form-control" value="<?php echo $waktu_s['j_hks']; ?>" name="j_hks" maxlength="2" placeholder="Hari" required>
                                        <label>Jumlah Hari Kerja Malam</label>
                                        <input type="number" class="form-control" value="<?php echo $waktu_s['j_hkm']; ?>" name="j_hkm" maxlength="2" placeholder="Hari" required>
                                        <label>Jumlah Hari Libur Pagi</label>
                                        <input type="number" class="form-control" value="<?php echo $waktu_s['j_hlp']; ?>" name="j_hlp" maxlength="2" placeholder="Hari" required>
                                        <label>Jumlah Hari Libur Sore</label>
                                        <input type="number" class="form-control" value="<?php echo $waktu_s['j_hls']; ?>" name="j_hls" maxlength="2" placeholder="Hari" required>
                                        <label>Jumlah Hari Libur Malam</label>
                                        <input type="number" class="form-control" value="<?php echo $waktu_s['j_hlm']; ?>" name="j_hlm" maxlength="2" placeholder="Hari" required>
                                        <label>Jumlah Hari Raya Pagi</label>
                                        <input type="number" class="form-control" value="<?php echo $waktu_s['j_hrp']; ?>" name="j_hrp" maxlength="2" placeholder="Hari" required>
                                        <label>Jumlah Hari Raya Sore</label>
                                        <input type="number" class="form-control" value="<?php echo $waktu_s['j_hrs']; ?>" name="j_hrs" maxlength="2" placeholder="Hari" required>
                                        <label>Jumlah Hari Raya Malam</label>
                                        <input type="number" class="form-control" value="<?php echo $waktu_s['j_hrm']; ?>" name="j_hrm" maxlength="2" placeholder="Hari" required>
                                        <label>Jumlah Hari Non Shift</label>
                                        <input type="number" class="form-control" value="<?php echo $waktu_s['j_ns']; ?>" name="j_ns" maxlength="2" placeholder="Hari" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=list-validasi-pegawai'); ?>">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </a>
                                    <input type="submit" class="btn btn-warning" value="Update">
                                </div> 
                            </form>
                        <?php } ?>   
                    </div>
                    <div class="col-md-4">
                        <center>
                            <?php
                            if ($pegawai['foto'] == '-' OR $pegawai['foto'] == '') {
                                if ($pegawai['jk'] == 'L') {
                                    $foto = 'img/laki.png';
                                } else {
                                    $foto = 'img/perempuan.png';
                                }
                            } else {
                                $foto = "img/" . $pegawai['foto'];
                            }
                            ?>
                            <br><br>
                            <img src = "<?php echo $foto; ?>" width="300px" height="300px" class="img-squere" >
                        </center>
                    </div>
                </div>
            </div>
            <!-- Tutup Modal waktu -->
            <?php
            break;

        case "validasi-kedisiplinan":
            ?>
            <!--Modal Add waktu -->
            <div class="box">
                <div class="box-header with-border">  
                    <h3 class="box-title fa fa-check-square-o">  VALIDASI KEDISPLINAN PEGAWAI </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                title="Collapse">
                            <i class="fa fa-minus"></i></button>
                    </div>                
                </div>
                <div class="box-body">
                    <div class="col-md-8">
                        <?php
                        if ($hak_akses['menu_kepegawaian'] == '1') {
                            $link = $aksi . paramEncrypt('module=validasi-pegawai&act=add-kedisiplinan&id=' . $id . '&link=kepegawaian');
                        } else {
                            $link = $aksi . paramEncrypt('module=validasi-pegawai&act=add-kedisiplinan&id=' . $id . '');
                        }
                        $pegawai = fetch_array(bukaquery("select tm_pegawai.id_unit,tm_pegawai.nip,tm_pegawai.nama_pegawai, tm_pegawai.jk, tm_pegawai.foto, tm_level.level
                            from tm_pegawai 
                                inner join tm_user ON tm_pegawai.id_user = tm_user.id_user
                                inner join tm_level ON tm_user.id_level = tm_level.id_level
                            where tm_pegawai.id_user='$id' "));
                        $validator_pegawai = fetch_array(bukaquery("SELECT a.id_level, a.nama_level FROM tm_level a WHERE a.level < ".$pegawai['level']." ORDER BY a.level DESC LIMIT 1"));
                        $hakakses_memvalidasi = $validator_pegawai['id_level'] == $_SESSION['id_level'] || true;
                        $id_disiplin = getOne("select id_disiplin from tm_kedisiplinan where month(tm_kedisiplinan.date_d)='$bln_sebelumnya' and year(tm_kedisiplinan.date_d)='$thn' and tm_kedisiplinan.id_user='$id'");
                        if ($id_disiplin == '') {
                            ?>
                            <form role="form" action="<?php echo $link; ?>" method="post"> 
                                <div class="modal-body">  
                                    <div class="box-body table-responsive">
                                        <div class="form-group"> 
                                            <div class="col-xs-8 col-md-4"> 
                                                <label>Nama Pegawai</label>
                                                <input type="text" class="form-control" value="<?php echo $pegawai['nama_pegawai']; ?>" readonly>
                                                <label>Kebersihan Diri</label>
                                                <input type="number" class="form-control" maxlength="2" name="d_diri" placeholder="Nilai 0-10" required>
                                                <label>Kerapihan Penampilan</label>
                                                <input type="number" class="form-control" maxlength="2" name="d_penampilan" placeholder="Nilai 0-10" required>
                                                <label>Kelengkapan Seragam</label>
                                                <input type="number" class="form-control"  maxlength="2" name="d_seragam" placeholder="Nilai 0-10" required>
                                                <Label>Parameter Penilaian</Label>
                                                <p>
                                                    Selalu Sesuai = 10<br>
                                                    Sering Sesuai = 8<br>
                                                    Terkadang Sesuai = 5
                                                </p>
                                            </div>
                                            <div class="col-xs-12 col-md-8">
                                                <label>Kebersihan Alat/Ruang Kerja</label>
                                                <input type="number" class="form-control" maxlength="2" name="d_alat" placeholder="Nilai 0-10" required>
                                                <label>Ruang/Alat Di Atur Rapih</label>
                                                <input type="number" class="form-control" maxlength="2" name="d_ruangan" placeholder="Nilai 0-10" required>
                                                <label>Merawat Sarana Kerja Teratur</label>
                                                <input type="number" class="form-control" maxlength="2" name="d_sarana" placeholder="Nilai 0-10" required>
                                                <Label>Parameter Penilaian </Label>
                                                <p>
                                                    Sesuai Standart = 10<br>
                                                    Jarang Sesuai Standart = 8<br>
                                                    Tidak Sesuai Standart = 5
                                                </p>
                                            </div>
                                        </div>
                                        <div class="form-group"> 
                                            <Label></Label>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <?php echo !$hakakses_memvalidasi ? '<label class="text-danger">Fitur untuk  Pegawai ini hanya diperbolehkan untuk '.$validator_pegawai['nama_level'].'</label>&nbsp;&nbsp;&nbsp;&nbsp;' : "" ?>
                                    <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=list-validasi-pegawai'); ?>" class="btn btn-default"> Close </a>
                                    <input type="submit" class="btn btn-primary" value="Simpan"  <?php echo !$hakakses_memvalidasi ? "disabled" : "" ?> >
                                </div> 
                                <div class="form-group">
                                    <br>
                                </div>
                            </form>
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
                                                        $tm_kehadiran = bukaquery("SELECT tm_waktu_k.sakit1, tm_waktu_k.sakit2, tm_waktu_k.alpha, tm_waktu_k.izin, tm_waktu_k.telat, tm_waktu_k.date_k  FROM tm_waktu_k where tm_waktu_k.id_user='$id' and year(tm_waktu_k.date_k)='$thn_now'");
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
                                                                <td><?php echo $kehadiran['telat'] ?></td>
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
                            <?php
                        } else {
                            $kedisplinan = fetch_array(bukaquery("select * from tm_kedisiplinan where id_user=" . $id . " and id_disiplin='$id_disiplin'"));
                            if ($hak_akses['menu_kepegawaian'] == '1') {
                                $link = $aksi . paramEncrypt('module=validasi-pegawai&act=update-kedisiplinan&id=' . $id_disiplin . '&link=kepegawaian');
                            } else {
                                $link = $aksi . paramEncrypt('module=validasi-pegawai&act=update-kedisiplinan&id=' . $id_disiplin . '');
                            }
                            ?>
                            <form role="form" action="<?php echo $link; ?>" method="post"> 
                                <div class="modal-body">
                                    <div class="form-group"> 
                                        <div class="box-body table-responsive">
                                            <div class="col-xs-2 col-md-4"> 
                                                <label>Nama Pegawai</label>
                                                <input type="text" class="form-control" value="<?php echo $pegawai['nama_pegawai']; ?>" readonly>
                                                <label>Kebersihan Diri</label>
                                                <input type="number" class="form-control" value="<?php echo $kedisplinan['d_diri']; ?>" maxlength="2" name="d_diri" placeholder="Nilai 0-10" required>
                                                <label>Kerapihan Penampilan</label>
                                                <input type="number" class="form-control" value="<?php echo $kedisplinan['d_penampilan']; ?>" maxlength="2" name="d_penampilan" placeholder="Nilai 0-10" required>
                                                <label>Kelengkapan Seragam</label>
                                                <input type="number" class="form-control" value="<?php echo $kedisplinan['d_seragam']; ?>" maxlength="2" name="d_seragam" placeholder="Nilai 0-10" required>
                                                <Label>Parameter Penilaian</Label>
                                                <p>
                                                    Selalu Sesuai = 10<br>
                                                    Sering Sesuai = 8<br>
                                                    Terkadang Sesuai = 5
                                                </p>
                                            </div>
                                            <div class="col-xs-4 col-md-8">
                                                <label>Kebersihan Alat/Ruang Kerja</label>
                                                <input type="number" class="form-control" value="<?php echo $kedisplinan['d_alat']; ?>" maxlength="2" name="d_alat" placeholder="Nilai 0-10" required>
                                                <label>Ruang/Alat Di Atur Rapih</label>
                                                <input type="number" class="form-control" value="<?php echo $kedisplinan['d_ruangan']; ?>" maxlength="2" name="d_ruangan" placeholder="Nilai 0-10" required>
                                                <label>Merawat Sarana Kerja Teratur</label>
                                                <input type="number" class="form-control" value="<?php echo $kedisplinan['d_sarana']; ?>" maxlength="2" name="d_sarana" placeholder="Nilai 0-10" required>
                                                <Label>Parameter Penilaian </Label>
                                                <p>
                                                    Sesuai Standart = 10<br>
                                                    Jarang Sesuai Standart = 8<br>
                                                    Tidak Sesuai Standart = 5
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <?php echo !$hakakses_memvalidasi ? '<label class="text-danger">Fitur untuk  Pegawai ini hanya diperbolehkan untuk '.$validator_pegawai['nama_level'].'</label>&nbsp;&nbsp;&nbsp;&nbsp;' : "" ?>
                                    <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=list-validasi-pegawai'); ?>" class="btn btn-default"> Close </a>
                                    <input type="submit" class="btn btn-warning" value="Update" <?php echo !$hakakses_memvalidasi ? "disabled" : "" ?> >
                                </div> 
                            </form>

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
                                                        $tm_kehadiran = bukaquery("SELECT tm_waktu_k.sakit1, tm_waktu_k.sakit2, tm_waktu_k.alpha, tm_waktu_k.izin, tm_waktu_k.telat, tm_waktu_k.date_k  FROM tm_waktu_k where tm_waktu_k.id_user='$id' and year(tm_waktu_k.date_k)='$thn_now'");
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
                                                                <td><?php echo $kehadiran['telat'] ?></td>
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
                        <?php } ?>   
                    </div>
                    <div class="col-md-4">
                        <center>
                            <?php
                            if ($pegawai['foto'] == '-' OR $pegawai['foto'] == '') {
                                if ($pegawai['jk'] == 'L') {
                                    $foto = 'img/laki.png';
                                } else {
                                    $foto = 'img/perempuan.png';
                                }
                            } else {
                                $foto = "img/" . $pegawai['foto'];
                            }
                            ?>
                            <img src = "<?php echo $foto; ?>" width="300px" height="300px" class="img-squere" >
                        </center>
                    </div>
                </div>
            </div>
            <!-- Tutup Modal waktu -->
            <?php
            break;

        case "validasi-kompetensi":
            ?>
            <!--Modal Add waktu -->
            <div class="box">
                <div class="box-header with-border">  
                    <h3 class="box-title fa fa-check-square-o">  VALIDASI K PEGAWAI </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                title="Collapse">
                            <i class="fa fa-minus"></i></button>
                    </div>                
                </div>
                <div class="box-body">
                    <div class="col-md-8">
                        <?php
                        $pegawai = fetch_array(bukaquery("select 
                            tm_pegawai.id_unit, tm_pegawai.nip, tm_pegawai.nama_pegawai, tm_pegawai.jk, tm_pegawai.foto, tm_level.level
                        from tm_pegawai
                            inner join tm_user ON tm_pegawai.id_user = tm_user.id_user
                            inner join tm_level ON tm_user.id_level = tm_level.id_level
                        where tm_pegawai.id_user='$id' "));
                        $validator_pegawai = fetch_array(bukaquery("SELECT a.id_level, a.nama_level FROM tm_level a WHERE a.level < ".$pegawai['level']." ORDER BY a.level DESC LIMIT 1"));
                        $hakakses_memvalidasi = $validator_pegawai['id_level'] == $_SESSION['id_level'] || true;
                        $id_kompetensi = getOne("select id_kompetensi from tm_kompetensi where month(tm_kompetensi.date_kompetensi)='$bln_sebelumnya' and year(tm_kompetensi.date_kompetensi)='$thn' and tm_kompetensi.id_user='$id'");
                        if ($id_kompetensi == '') {
                            ?>
                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=validasi-pegawai&act=add-kompetensi&id=' . $id . ''); ?>" method="post"> 
                                <div class="modal-body">                                                
                                    <div class="form-group"> 
                                        <div class="box-body table-responsive">
                                            <label>Nama Pegawai</label>
                                            <input type="text" class="form-control" value="<?php echo $pegawai['nama_pegawai']; ?>" readonly>
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <td colspan="3"><label>Petunjuk Pengisian:</label></td>
                                                </tr>
                                                <tr>
                                                    <td>1.</td>
                                                    <td colspan="2">Beri nilai kepada setiap indikator kompetensi dan Pilihan nilai adalah 1 sampai dengan 10, sesuai kategori di bawah. Jangan mengisi dengan angka lain</td>
                                                </tr>
                                                <tr>
                                                    <td>2.</td>
                                                    <td colspan="2">Nilai dari tiap point dirata-rata dan dikalikan bobot, untuk mendapatkan score untuk setiap kompetensi</td>
                                                </tr>
                                                <tr>
                                                    <td>3.</td>
                                                    <td colspan="2">Seluruh Score kompetensi dijumlahkan untuk mendapatkan score Competency</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><label>Kategori</label></td>
                                                    <td><label>score</label></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">Develop / Perlu Dikembangkan</td>
                                                    <td>1 - 4</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">Meets / Sesuai</td>
                                                    <td>5 - 9</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">Exceeds / Melebihi</td>
                                                    <td>10</td>
                                                </tr>                                                        
                                                <tr>
                                                    <td colspan="3"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"><label>KOMPETENSI</label></td>
                                                </tr>
                                                <tr>
                                                    <td><label>No</td>
                                                    <td colspan="2"><label>KEMAMPUAN MENGANALISA</label></td>
                                                </tr>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>Mampu melakukan pemecahan masalah secara sistematis</td>
                                                    <td><input type="number" class="form-control" maxlength="2" name="menganalisa1" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>2.</td>
                                                    <td>Mampu membuat beberapa analisis dan solusi pemecahan permasalahan</td>
                                                    <td><input type="number" class="form-control" maxlength="2" name="menganalisa2" placeholder="Nilai 0-10" required></td>
                                                </tr>

                                                <tr>
                                                    <td><label>No</label></td>
                                                    <td colspan="2"><label>KOMUNIKASI</label></td>
                                                </tr>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>Mampu memberikan pendapat/masukan kepada atasan, rekan kerja atau bawahan  secara sistematis dan akurat secara lisan dan tulisan</td>
                                                    <td><input type="number" class="form-control" maxlength="2" name="komunikasi1" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>2.</td>
                                                    <td>Mampu memahami keadaan situasi dan kondisi pada saat berkomunikasi (menyampaikan  pesan, ide dan gagasan)</td>
                                                    <td><input type="number" class="form-control" maxlength="2" name="komunikasi2" placeholder="Nilai 0-10" required></td>
                                                </tr>

                                                <tr>
                                                    <td><label>No</label></td>
                                                    <td colspan="2"><label>KERJASAMA</label></td>
                                                </tr>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>Mampu bekerjasama dengan unit-unit terkait</td>
                                                    <td><input type="number" class="form-control" maxlength="2" name="kerjasama1" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>2.</td>
                                                    <td>Tetap mendukung keputusan team sekalipun keputusan yang diambil tidak sesuai dengan keputusan pribadi</td>
                                                    <td><input type="number" class="form-control" maxlength="2" name="kerjasama2" placeholder="Nilai 0-10" required></td>
                                                </tr>

                                                <tr>
                                                    <td><label>No</label></td>
                                                    <td colspan="2"><label>KECERDASAN EMOSIONAL</label></td>
                                                </tr>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>Menunjukkan emosional yang stabil pada saat mendapat tekanan pekerjaan yang tinggi</td>
                                                    <td><input type="number" class="form-control" maxlength="2" name="kecerdasan1" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>2.</td>
                                                    <td>Peka terdahap perasaan orang lain</td>
                                                    <td><input type="number" class="form-control" maxlength="2" name="kecerdasan2" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>3.</td>
                                                    <td>Mampu menjaga situasi dan hubungan kerja yang baik dengan atasan, rekan kerja atau bawahan</td>
                                                    <td><input type="number" class="form-control" maxlength="2" name="kecerdasan3" placeholder="Nilai 0-10" required></td>
                                                </tr>

                                                <tr>
                                                    <td><label>No</label></td>
                                                    <td colspan="2"><label>FOKUS PADA HASIL KERJA</label></td>
                                                </tr>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>Mampu mencapai target kerja sesuai dengan tenggat waktu</td>
                                                    <td><input type="number" class="form-control" maxlength="2" name="fokus1" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>2.</td>
                                                    <td>Mampu merencanakan dan mengelola sumber daya untuk mencapai hasil yang memuaskan</td>
                                                    <td><input type="number" class="form-control" maxlength="2" name="fokus2" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>3.</td>
                                                    <td>Dapat mengatasi kendala dengan supervisi minimum</td>
                                                    <td><input type="number" class="form-control" maxlength="2" name="fokus3" placeholder="Nilai 0-10" required></td>
                                                </tr>

                                                <tr>
                                                    <td><label>No</label></td>
                                                    <td colspan="2"><label>TANGGUNG JAWAB</label></td>
                                                </tr>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>Berkomitmen menyelesaikan pekerjaan tanpa perlu dimonitor oleh atasan</td>
                                                    <td><input type="number" class="form-control" maxlength="2" name="tanggung1" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>2.</td>
                                                    <td>Dapat diandalkan menyelesaikan pekerjaan dengan mandiri dan tepat waktu</td>
                                                    <td><input type="number" class="form-control" maxlength="2" name="tanggung2" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>3.</td>
                                                    <td>Dapat terus melaksanakan  tugas  dalam situasi  tekanan</td>
                                                    <td><input type="number" class="form-control" maxlength="2" name="tanggung3" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>4.</td>
                                                    <td>Tidak mengelak dari tugas dan wewenang yang diberikan dan bersedia menerima konsekuensi yang timbul dari pekerjaannya</td>
                                                    <td><input type="number" class="form-control" maxlength="2" name="tanggung4" placeholder="Nilai 0-10" required></td>
                                                </tr>

                                                <tr>
                                                    <td><label>No</label></td>
                                                    <td colspan="2"><label>ORIENTASI TERHADAP KUALITAS</label></td>
                                                </tr>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>Memperhatikan standar kerja yang ditetapkan maupun sistem dan prosedur</td>
                                                    <td><input type="number" class="form-control" maxlength="2" name="orientasi_k1" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>2.</td>
                                                    <td>Selalu berusahan meningkatkan kualitas kerja dan mengembangkan sistem kerja yang baik(Continuous improvment)</td>
                                                    <td><input type="number" class="form-control" maxlength="2" name="orientasi_k2" placeholder="Nilai 0-10" required></td>
                                                </tr>

                                                <tr>
                                                    <td><label>No</label></td>
                                                    <td colspan="2"><label>INISIATIF</label></td>
                                                </tr>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>Mampu memberikan ide-ide yang secara langsung dapat diterapkan untuk perbaikan kualitas kerja</td>
                                                    <td><input type="number" class="form-control" maxlength="2" name="inisiatif1" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>2.</td>
                                                    <td>Mengambil tindakan proaktif secara mandiri bila ada masalah/situasi tertentu sesuai dengan kebutuhan dan aturan yang berlaku</td>
                                                    <td><input type="number" class="form-control" maxlength="2" name="inisiatif2" placeholder="Nilai 0-10" required></td>
                                                </tr>

                                                <tr>
                                                    <td><label>No</label></td>
                                                    <td colspan="2"><label>DISIPLIN</label></td>
                                                </tr>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>Melaksanakan dan mematuhi aturan yang sudah ditetapkan</td>
                                                    <td><input type="number" class="form-control" maxlength="2" name="disiplin1" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>2.</td>
                                                    <td>Tidak absen untuk alasan yang dibuat-buat</td>
                                                    <td><input type="number" class="form-control" maxlength="2" name="disiplin2" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>3.</td>
                                                    <td>Tidak suka terlambat</td>
                                                    <td><input type="number" class="form-control" maxlength="2" name="disiplin3" placeholder="Nilai 0-10" required></td>
                                                </tr>

                                                <tr>
                                                    <td><label>No</label></td>
                                                    <td colspan="2"><label>ORIENTASI PELANGGAN</label></td>
                                                </tr>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>Dapat memahami kebutuhan pelanggan</td>
                                                    <td><input type="number" class="form-control" maxlength="2" name="orientasi_p1" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>2.</td>
                                                    <td>Responsif terhadap permintaan dan keluhan pelanggan</td>
                                                    <td><input type="number" class="form-control" maxlength="2" name="orientasi_p2" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>3.</td>
                                                    <td>Membangun dan memelihara hubungan yang efektif dengan pelanggan</td>
                                                    <td><input type="number" class="form-control" maxlength="2" name="orientasi_p3" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="form-group"> 
                                        <Label></Label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <?php echo !$hakakses_memvalidasi ? '<label class="text-danger">Fitur untuk  Pegawai ini hanya diperbolehkan untuk '.$validator_pegawai['nama_level'].'</label>&nbsp;&nbsp;&nbsp;&nbsp;' : "" ?>
                                    <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=list-validasi-pegawai'); ?>" class="btn btn-default"> Close </a>
                                    <input type="submit" class="btn btn-primary" value="Simpan" <?php echo !$hakakses_memvalidasi ? "disabled" : "" ?> >
                                    </div> 
                                    <div class="form-group">
                                    <br>
                                </div>
                            </form>
                            <?php
                        } else {
                            $kompetensi = fetch_array(bukaquery("select * from tm_kompetensi where id_user=" . $id . " and id_kompetensi='$id_kompetensi'"));
                            ?>
                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=validasi-pegawai&act=update-kompetensi&id=' . $id_kompetensi . ''); ?>" method="post"> 
                                <div class="modal-body">
                                    <div class="form-group"> 
                                        <div class="box-body table-responsive">
                                            <label>Nama Pegawai</label>
                                            <input type="text" class="form-control" value="<?php echo $pegawai['nama_pegawai']; ?>" readonly>
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <td colspan="3"><label>Petunjuk Pengisian:</label></td>
                                                </tr>
                                                <tr>
                                                    <td>1.</td>
                                                    <td colspan="2">Beri nilai kepada setiap indikator kompetensi dan Pilihan nilai adalah 1 sampai dengan 10, sesuai kategori di bawah. Jangan mengisi dengan angka lain</td>
                                                </tr>
                                                <tr>
                                                    <td>2.</td>
                                                    <td colspan="2">Nilai dari tiap point dirata-rata dan dikalikan bobot, untuk mendapatkan score untuk setiap kompetensi</td>
                                                </tr>
                                                <tr>
                                                    <td>3.</td>
                                                    <td colspan="2">Seluruh Score kompetensi dijumlahkan untuk mendapatkan score Competency</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><label>Kategori</label></td>
                                                    <td><label>score</label></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">Develop / Perlu Dikembangkan</td>
                                                    <td>1 - 4</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">Meets / Sesuai</td>
                                                    <td>5 - 9</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">Exceeds / Melebihi</td>
                                                    <td>10</td>
                                                </tr>                                                        
                                                <tr>
                                                    <td colspan="3"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"><label>KOMPETENSI</label></td>
                                                </tr>
                                                <tr>
                                                    <td><label>No</td>
                                                    <td colspan="2"><label>KEMAMPUAN MENGANALISA</label></td>
                                                </tr>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>Mampu melakukan pemecahan masalah secara sistematis</td>
                                                    <td><input type="number" class="form-control" maxlength="2" value="<?php echo $kompetensi['menganalisa1']; ?>" name="menganalisa1" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>2.</td>
                                                    <td>Mampu membuat beberapa analisis dan solusi pemecahan permasalahan</td>
                                                    <td><input type="number" class="form-control" maxlength="2" value="<?php echo $kompetensi['menganalisa2']; ?>" name="menganalisa2" placeholder="Nilai 0-10" required></td>
                                                </tr>

                                                <tr>
                                                    <td><label>No</label></td>
                                                    <td colspan="2"><label>KOMUNIKASI</label></td>
                                                </tr>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>Mampu memberikan pendapat/masukan kepada atasan, rekan kerja atau bawahan  secara sistematis dan akurat secara lisan dan tulisan</td>
                                                    <td><input type="number" class="form-control" maxlength="2" value="<?php echo $kompetensi['komunikasi1']; ?>" name="komunikasi1" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>2.</td>
                                                    <td>Mampu memahami keadaan situasi dan kondisi pada saat berkomunikasi (menyampaikan  pesan, ide dan gagasan)</td>
                                                    <td><input type="number" class="form-control" maxlength="2" value="<?php echo $kompetensi['komunikasi2']; ?>" name="komunikasi2" placeholder="Nilai 0-10" required></td>
                                                </tr>

                                                <tr>
                                                    <td><label>No</label></td>
                                                    <td colspan="2"><label>KERJASAMA</label></td>
                                                </tr>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>Mampu bekerjasama dengan unit-unit terkait</td>
                                                    <td><input type="number" class="form-control" maxlength="2" value="<?php echo $kompetensi['kerjasama1']; ?>" name="kerjasama1" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>2.</td>
                                                    <td>Tetap mendukung keputusan team sekalipun keputusan yang diambil tidak sesuai dengan keputusan pribadi</td>
                                                    <td><input type="number" class="form-control" maxlength="2" value="<?php echo $kompetensi['kerjasama2']; ?>" name="kerjasama2" placeholder="Nilai 0-10" required></td>
                                                </tr>

                                                <tr>
                                                    <td><label>No</label></td>
                                                    <td colspan="2"><label>KECERDASAN EMOSIONAL</label></td>
                                                </tr>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>Menunjukkan emosional yang stabil pada saat mendapat tekanan pekerjaan yang tinggi</td>
                                                    <td><input type="number" class="form-control" maxlength="2" value="<?php echo $kompetensi['kecerdasan1']; ?>" name="kecerdasan1" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>2.</td>
                                                    <td>Peka terdahap perasaan orang lain</td>
                                                    <td><input type="number" class="form-control" maxlength="2" value="<?php echo $kompetensi['kecerdasan2']; ?>" name="kecerdasan2" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>3.</td>
                                                    <td>Mampu menjaga situasi dan hubungan kerja yang baik dengan atasan, rekan kerja atau bawahan</td>
                                                    <td><input type="number" class="form-control" maxlength="2" value="<?php echo $kompetensi['kecerdasan3']; ?>" name="kecerdasan3" placeholder="Nilai 0-10" required></td>
                                                </tr>

                                                <tr>
                                                    <td><label>No</label></td>
                                                    <td colspan="2"><label>FOKUS PADA HASIL KERJA</label></td>
                                                </tr>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>Mampu mencapai target kerja sesuai dengan tenggat waktu</td>
                                                    <td><input type="number" class="form-control" maxlength="2" value="<?php echo $kompetensi['fokus1']; ?>" name="fokus1" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>2.</td>
                                                    <td>Mampu merencanakan dan mengelola sumber daya untuk mencapai hasil yang memuaskan</td>
                                                    <td><input type="number" class="form-control" maxlength="2" value="<?php echo $kompetensi['fokus2']; ?>" name="fokus2" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>3.</td>
                                                    <td>Dapat mengatasi kendala dengan supervisi minimum</td>
                                                    <td><input type="number" class="form-control" maxlength="2" value="<?php echo $kompetensi['fokus3']; ?>" name="fokus3" placeholder="Nilai 0-10" required></td>
                                                </tr>

                                                <tr>
                                                    <td><label>No</label></td>
                                                    <td colspan="2"><label>TANGGUNG JAWAB</label></td>
                                                </tr>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>Berkomitmen menyelesaikan pekerjaan tanpa perlu dimonitor oleh atasan</td>
                                                    <td><input type="number" class="form-control" maxlength="2" value="<?php echo $kompetensi['tanggung1']; ?>" name="tanggung1" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>2.</td>
                                                    <td>Dapat diandalkan menyelesaikan pekerjaan dengan mandiri dan tepat waktu</td>
                                                    <td><input type="number" class="form-control" maxlength="2" value="<?php echo $kompetensi['tanggung2']; ?>" name="tanggung2" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>3.</td>
                                                    <td>Dapat terus melaksanakan  tugas  dalam situasi  tekanan</td>
                                                    <td><input type="number" class="form-control" maxlength="2" value="<?php echo $kompetensi['tanggung3']; ?>" name="tanggung3" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>4.</td>
                                                    <td>Tidak mengelak dari tugas dan wewenang yang diberikan dan bersedia menerima konsekuensi yang timbul dari pekerjaannya</td>
                                                    <td><input type="number" class="form-control" maxlength="2" value="<?php echo $kompetensi['tanggung4']; ?>" name="tanggung4" placeholder="Nilai 0-10" required></td>
                                                </tr>

                                                <tr>
                                                    <td><label>No</label></td>
                                                    <td colspan="2"><label>ORIENTASI TERHADAP KUALITAS</label></td>
                                                </tr>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>Memperhatikan standar kerja yang ditetapkan maupun sistem dan prosedur</td>
                                                    <td><input type="number" class="form-control" maxlength="2" value="<?php echo $kompetensi['orientasi_k1']; ?>" name="orientasi_k1" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>2.</td>
                                                    <td>Selalu berusahan meningkatkan kualitas kerja dan mengembangkan sistem kerja yang baik(Continuous improvment)</td>
                                                    <td><input type="number" class="form-control" maxlength="2" value="<?php echo $kompetensi['orientasi_k2']; ?>" name="orientasi_k2" placeholder="Nilai 0-10" required></td>
                                                </tr>

                                                <tr>
                                                    <td><label>No</label></td>
                                                    <td colspan="2"><label>INISIATIF</label></td>
                                                </tr>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>Mampu memberikan ide-ide yang secara langsung dapat diterapkan untuk perbaikan kualitas kerja</td>
                                                    <td><input type="number" class="form-control" maxlength="2" value="<?php echo $kompetensi['inisiatif1']; ?>" name="inisiatif1" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>2.</td>
                                                    <td>Mengambil tindakan proaktif secara mandiri bila ada masalah/situasi tertentu sesuai dengan kebutuhan dan aturan yang berlaku</td>
                                                    <td><input type="number" class="form-control" maxlength="2" value="<?php echo $kompetensi['inisiatif2']; ?>" name="inisiatif2" placeholder="Nilai 0-10" required></td>
                                                </tr>

                                                <tr>
                                                    <td><label>No</label></td>
                                                    <td colspan="2"><label>DISIPLIN</label></td>
                                                </tr>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>Melaksanakan dan mematuhi aturan yang sudah ditetapkan</td>
                                                    <td><input type="number" class="form-control" maxlength="2" value="<?php echo $kompetensi['disiplin1']; ?>" name="disiplin1" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>2.</td>
                                                    <td>Tidak absen untuk alasan yang dibuat-buat</td>
                                                    <td><input type="number" class="form-control" maxlength="2" value="<?php echo $kompetensi['disiplin2']; ?>" name="disiplin2" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>3.</td>
                                                    <td>Tidak suka terlambat</td>
                                                    <td><input type="number" class="form-control" maxlength="2" value="<?php echo $kompetensi['disiplin3']; ?>" name="disiplin3" placeholder="Nilai 0-10" required></td>
                                                </tr>

                                                <tr>
                                                    <td><label>No</label></td>
                                                    <td colspan="2"><label>ORIENTASI PELANGGAN</label></td>
                                                </tr>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>Dapat memahami kebutuhan pelanggan</td>
                                                    <td><input type="number" class="form-control" maxlength="2" value="<?php echo $kompetensi['orientasi_p1']; ?>" name="orientasi_p1" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>2.</td>
                                                    <td>Responsif terhadap permintaan dan keluhan pelanggan</td>
                                                    <td><input type="number" class="form-control" maxlength="2" value="<?php echo $kompetensi['orientasi_p2']; ?>" name="orientasi_p2" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                                <tr>
                                                    <td>3.</td>
                                                    <td>Membangun dan memelihara hubungan yang efektif dengan pelanggan</td>
                                                    <td><input type="number" class="form-control" maxlength="2" value="<?php echo $kompetensi['orientasi_p3']; ?>" name="orientasi_p3" placeholder="Nilai 0-10" required></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <?php echo !$hakakses_memvalidasi ? '<label class="text-danger">Fitur untuk  Pegawai ini hanya diperbolehkan untuk '.$validator_pegawai['nama_level'].'</label>&nbsp;&nbsp;&nbsp;&nbsp;' : "" ?>
                                    <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=list-validasi-pegawai'); ?>" class="btn btn-default"> Close </a>
                                    <input type="submit" class="btn btn-warning" value="Update" <?php echo !$hakakses_memvalidasi ? "disabled" : "" ?> >
                                </div> 
                            </form>
                        <?php } ?> 
                    </div>
                    <div class="col-md-4">
                        <center>
                            <?php
                            if ($pegawai['foto'] == '-' OR $pegawai['foto'] == '') {
                                if ($pegawai['jk'] == 'L') {
                                    $foto = 'img/laki.png';
                                } else {
                                    $foto = 'img/perempuan.png';
                                }
                            } else {
                                $foto = "img/" . $pegawai['foto'];
                            }
                            ?>
                            <br><br>
                            <img src = "<?php echo $foto; ?>" width="350px" height="350px" class="img-squere" >
                        </center>
                    </div>
                </div>
            </div>
            <!-- Tutup Modal waktu -->
            <?php
            break;

        case "hasil-validasi":
            ?>
            <!--Modal Add waktu -->
            <div class="box">
                <div class="box-header with-border">  
                    <h3 class="box-title fa fa-bullseye">  REFRENSI HASIL VALIDASI </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                title="Collapse">
                            <i class="fa fa-minus"></i></button>
                    </div>                
                </div>
                <div class="box-body">
                    <?php $biodata = fetch_array(bukaquery("SELECT tm_pegawai.nik, tm_pegawai.nama_pegawai, tm_pegawai.nip, tm_pegawai.no_rek, tm_pegawai.tempat_lahir, tm_pegawai.agama, tm_pegawai.npwp,tm_pegawai.jk, 
                                            tm_pegawai.nama_pegawai, tm_pegawai.tgl_masuk, tm_pegawai.pendidikan, tm_pegawai.alamat, tm_pegawai.no_bpjs,tm_pegawai.tgl_lahir,tm_pegawai.foto,
                                            tm_unit.id_unit, tm_unit.nama_unit, tm_pegawai.status_nikah, tm_pegawai.sub_bagian,tm_pegawai.pajak, tm_pegawai.rumpun,tm_pegawai.log_finger, 
                                            tm_pegawai.bpjs_ks, tm_pegawai.bpjs_jkk,tm_pegawai.status, tm_pegawai.bpjs_ijht, tm_pegawai.bpjs_jp, tm_level.nama_level, tm_level.id_level 
                                            FROM
                                            tm_pegawai
                                            INNER JOIN tm_unit ON tm_pegawai.id_unit = tm_unit.id_unit
                                            INNER JOIN tm_user ON tm_user.id_user = tm_pegawai.id_user
                                            INNER JOIN tm_level ON tm_user.id_level = tm_level.id_level
                                            where tm_pegawai.id_user=" . $id . "")); ?>                           
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-4 col-md-4"> 
                                <div class="col-xs-12">
                                    <label>NIP</label>
                                    <input type="text" class="form-control" value="<?php echo $biodata['nip']; ?>" readonly>
                                    <label>NAMA PEGAWAI</label>
                                    <input type="text" class="form-control" value="<?php echo $biodata['nama_pegawai']; ?>" readonly>
                                    <label>RUMPUN JABATAN</label>
                                    <input type="text" class="form-control" value="<?php echo $biodata['rumpun']; ?>" readonly>
                                    <label>MASA KERJA</label>
                                    <input type="text" class="form-control" value="<?php echo MasaKerjaPenyebut($biodata['tgl_masuk']); ?>" readonly>
                                    <label>PENDIDIDIKAN</label>
                                    <input type="text" class="form-control" value="<?php echo $biodata['pendidikan']; ?>" readonly>
                                    <label>HUKDIS</label>
                                    <input type="text" class="form-control" value="<?php echo NamaHukDis($id); ?>" readonly>
                                    <label>GAPOK</label>
                                    <input type="text" class="form-control" value="<?php echo formatDuit(GajiPokok($biodata['pendidikan'], $biodata['tgl_masuk'])); ?>" readonly>
                                    <label>GAJI BRUTO</label>
                                    <input type="text" class="form-control" value="<?php echo formatDuit(GajiBruto(GajiPokok($biodata['pendidikan'], $biodata['tgl_masuk']), NilaiStatusKawin($biodata['status_nikah']))); ?>" readonly>
                                </div>
                            </div>
                            <div class="col-xs-8 col-md-4"> 
                                <div class="col-xs-8">
                                    <label>JENIS KELAMIN</label>
                                    <input type="text" class="form-control" value="<?php echo kelamin($biodata['jk']); ?>" readonly>
                                    <label>TANGGAL MASUK</label>
                                    <input type="text" class="form-control" value="<?php echo FormatTgl('d/m/Y', $biodata['tgl_masuk']); ?>" readonly>
                                    <label>STATUS NIKAH</label>
                                    <input type="text" class="form-control" value="<?php echo $biodata['status_nikah']; ?>" readonly>
                                    <label>BAGIAN/UNIT</label>
                                    <input type="text" class="form-control" value="<?php echo $biodata['nama_unit']; ?>" readonly>
                                </div>                                                        
                                <div class="col-xs-4">
                                    <?php
                                    if ($biodata['foto'] == '-' OR $biodata['foto'] == '') {
                                        if ($biodata['jk'] == 'L') {
                                            $foto = 'img/laki.png';
                                        } else {
                                            $foto = 'img/perempuan.png';
                                        }
                                    } else {
                                        $foto = "img/" . $biodata['foto'];
                                    }
                                    ?>
                                    <img src="img/male.jpg" style="align:center" class="img-rounded" width="250" height="250" alt="" > 
                                    <br><br>
                                </div>                                                        
                                <div class="col-xs-16">
                                    <?php
                                    $refrensi_penyerapan = getOne("select penyerapan from tm_penyerapan where Month(tm_penyerapan.bulan)='$bln_sebelumnya' and Year(tm_penyerapan.bulan)='$thn' ");
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>  
                    <div class="col-md-12">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <th>UNSUR YANG DI NILAI</th>
                            <th>TOTAL CAPAIAN</th>
                            <th>X BOBOT</th>
                            <th>HASIL</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>a). Nilai Kinerja</td>
                                    <td><?php echo NilKinerja($id, $bln_sebelumnya, $thn); ?></td>
                                    <td> X &ensp;70%</td>
                                    <td><?php echo NilKinerja($id, $bln_sebelumnya, $thn) * 0.7; ?></td>
                                </tr>
                                <tr>
                                    <td>b). Nilai Penyerapan</td>
                                    <td><?php echo $refrensi_penyerapan ?></td>
                                    <td> X &ensp;20%</td>
                                    <td><?php echo $refrensi_penyerapan * 0.2; ?> </td>
                                </tr>
                                <tr>
                                    <td colspan="4">c). Nilai Prilaku</td>
                                </tr>
                                <tr>
                                    <td>&ensp;&ensp;&ensp; -Disiplin</td>
                                    <td><?php echo number_format(NilDis($id, $bln_sebelumnya, $thn)); ?> </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>&ensp;&ensp;&ensp; -Kompetensi</td>
                                    <td><?php echo number_format(NilKomp($id, $bln_sebelumnya, $thn)); ?> </td>
                                    <td>  </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>&ensp;&ensp;&ensp; Jumlah Nilai Perilaku (Disiplin+Kompetensi)</td>
                                    <td><?php echo number_format(NilDis($id, $bln_sebelumnya, $thn) + NilKomp($id, $bln_sebelumnya, $thn)); ?> </td>
                                    <td> X &ensp;10% </td>
                                    <td><?php echo number_format(NilDis($id, $bln_sebelumnya, $thn) + NilKomp($id, $bln_sebelumnya, $thn)) * 0.1; ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <b>JUMLAH <p>(Hasil Nilai Kinerja + Hasil Nilai Penyerapan + Hasil Nilai Prilaku)</p></b>
                                    </td>
                                    <td>
                                        <label><h2 class=""><u><i><?php echo formatDecDigit((NilKinerja($id, $bln_sebelumnya, $thn) * 0.7) + ($refrensi_penyerapan * 0.2) + ((NilDis($id, $bln_sebelumnya, $thn) + NilKomp($id, $bln_sebelumnya, $thn)) * 0.1), 1); ?></i></u></h2></label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=list-validasi-pegawai'); ?>">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </a>
                        <a href="#" onclick="close_window();return false;" class="btn btn-default"> Close </a>
                    </div>                   
                </div>
            </div>
            <!-- Tutup Modal waktu -->
            <?php
            break;

        case "hasil-validasi-management":
            ?>
            <!--Modal Add waktu -->
            <div class="box">
                <div class="box-header with-border">  
                    <h3 class="box-title fa fa-bullseye">  Refrensi Hasil Validasi Bulan <label><?php echo konversiBulan($bln_sebelumnya) . " Tahun " . $thn_now; ?></label> </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                title="Collapse">
                            <i class="fa fa-minus"></i></button>
                    </div>                
                </div>
                <div class="box-body">
                    <form role="form" name="autoSumForm" action="<?php echo $aksi . paramEncrypt('module=validasi-pegawai&act=add-penilaian&id=' . $id . ''); ?>" method="post"> 
                        <div class="modal-body">
                            <div class="box-body table-responsive">
                                <div class="form-group">
                                    <?php
                                    $biodata = fetch_array(bukaquery("SELECT tm_pegawai.nik, tm_pegawai.nama_pegawai, tm_pegawai.nip, tm_pegawai.no_rek, tm_pegawai.tempat_lahir, tm_pegawai.agama, tm_pegawai.npwp,tm_pegawai.jk, 
                                            tm_pegawai.nama_pegawai, tm_pegawai.tgl_masuk, tm_pegawai.pendidikan, tm_pegawai.alamat, tm_pegawai.no_bpjs,tm_pegawai.tgl_lahir,tm_pegawai.foto,
                                            tm_unit.id_unit, tm_unit.nama_unit, tm_pegawai.status_nikah, tm_pegawai.sub_bagian,tm_pegawai.pajak, tm_pegawai.rumpun,tm_pegawai.log_finger, 
                                            tm_pegawai.bpjs_ks, tm_pegawai.bpjs_jkk,tm_pegawai.status, tm_pegawai.bpjs_ijht, tm_pegawai.bpjs_jp, tm_level.nama_level, tm_level.id_level, tm_level.level, tm_level.grade_kinerja, tm_level.grade_prilaku 
                                            FROM
                                            tm_pegawai
                                            INNER JOIN tm_unit ON tm_pegawai.id_unit = tm_unit.id_unit
                                            INNER JOIN tm_user ON tm_user.id_user = tm_pegawai.id_user
                                            INNER JOIN tm_level ON tm_user.id_level = tm_level.id_level
                                            where tm_pegawai.id_user=" . $id . ""));
                                    $tmtcek = TanggalAkhirBulanKemarin();
                                    $validator_pegawai = fetch_array(bukaquery("SELECT a.id_level, a.nama_level FROM tm_level a WHERE a.level < ".$biodata['level']." ORDER BY a.level DESC LIMIT 1"));
                                    $hakakses_memvalidasi = $validator_pegawai['id_level'] == $_SESSION['id_level'] || true;
                                    ?>                           
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-4 col-md-4"> 
                                                <div class="col-xs-12">
                                                    <label>NIP</label>
                                                    <input type="text" class="form-control" value="<?php echo $biodata['nip']; ?>" readonly>
                                                    <label>NAMA PEGAWAI</label>
                                                    <input type="text" class="form-control" value="<?php echo $biodata['nama_pegawai']; ?>" readonly>
                                                    <label>RUMPUN JABATAN</label>
                                                    <input type="text" class="form-control" name="rumpun" value="<?php echo $biodata['rumpun']; ?>" readonly>
                                                    <label>MASA KERJA</label>
                                                    <input type="text" class="form-control" name="masa_kerja" value="<?php echo MasaKerjaPenyebutValidasi($biodata['tgl_masuk']); ?>" readonly>
                                                    <label>PENDIDIDIKAN</label>
                                                    <input type="text" class="form-control" name="pendidikan" value="<?php echo $biodata['pendidikan']; ?>" readonly>
                                                    <label>HUKDIS</label>
                                                    <input type="text" class="form-control" value="<?php echo NamaHukDis($id); ?>" readonly>                                                               

                                                </div>
                                            </div>
                                            <div class="col-xs-8 col-md-4"> 
                                                <div class="col-xs-8">
                                                    <label>JENIS KELAMIN</label>
                                                    <input type="text" class="form-control" value="<?php echo kelamin($biodata['jk']); ?>" readonly>
                                                    <label>TANGGAL MASUK</label>
                                                    <input type="text" class="form-control" value="<?php echo FormatTgl('d/m/Y', $biodata['tgl_masuk']); ?>" readonly>
                                                    <label>STATUS NIKAH</label>
                                                    <input type="text" class="form-control" value="<?php echo $biodata['status_nikah']; ?>" readonly>
                                                    <label>BAGIAN/UNIT</label>
                                                    <input type="text" class="form-control" value="<?php echo $biodata['nama_unit']; ?>" readonly>
                                                    <label>GAJI BRUTO</label>
                                                    <input type="text" class="form-control" value="<?php echo formatDuit(GajiBruto(GajiPokokValidasi($biodata['pendidikan'], $biodata['tgl_masuk'], $tmtcek), NilaiStatusKawin($biodata['status_nikah']))); ?>" readonly>
                                                    <label>TUNJANGAN</label>
                                                    <input type="text" class="form-control" value="<?php echo formatDuit(GajiPokokValidasi($biodata['pendidikan'], $biodata['tgl_masuk'], $tmtcek) * NilaiStatusRumpun($biodata['rumpun'])); ?>" readonly>
                                                </div>                                                        
                                                <div class="col-xs-4">
                                                    <?php
                                                    $tunjangan = GajiPokokValidasi($biodata['pendidikan'], $biodata['tgl_masuk'], $tmtcek) * NilaiStatusRumpun($biodata['rumpun']);
                                                    if ($biodata['foto'] == '-' OR $biodata['foto'] == '') {
                                                        if ($biodata['jk'] == 'L') {
                                                            $foto = 'img/laki.png';
                                                        } else {
                                                            $foto = 'img/perempuan.png';
                                                        }
                                                    } else {
                                                        $foto = "img/" . $biodata['foto'];
                                                    }
                                                    ?>
                                                    <img src="img/male.jpg" style="align:center" class="img-rounded" width="350" height="350" alt="" > 
                                                    <br><br>                                                                
                                                </div>                                                        
                                                <div class="col-xs-16">
                                                    <?php
                                                    $refrensi_penyerapan = fetch_array(bukaquery("select penyerapan,id_penyerapan from tm_penyerapan where Month(tm_penyerapan.bulan)='$bln_sebelumnya' and Year(tm_penyerapan.bulan)='$thn' "));
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>  
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <th>UNSUR YANG DI NILAI</th>
                                            <th colspan="2">TOTAL CAPAIAN</th>
                                            <th>HASIL</th>
                                        </thead>
                                        <tbody>
                                            <td>a). Nilai Kinerja</td>
                                            <td><?php echo NilKinerja($id, $bln_sebelumnya, $thn); ?>
                                            </td>
                                            <td> X &ensp;70%
                                            </td>
                                            <td>
                                                <div class="col-xs-8">
                                                    <?php
                                                    //grading
                                                    $grade1=$biodata['grade_kinerja'];
                                                    $grade2=$biodata['grade_prilaku'];
                                                    if($biodata['grade_kinerja'] == 0){
                                                        $grade_kinerja='70';
                                                    }else {
                                                        $grade_kinerja=$biodata['grade_kinerja'];
                                                    }
                                                    if($biodata['grade_prilaku'] == 0){
                                                        $grade_prilaku='10';
                                                    }else {
                                                        $grade_prilaku=$biodata['grade_prilaku'];
                                                    }
                                                    //cek cuti melahirkan
                                                    $cek_data_cuti_melahirkan = bukaquery2("
                                                        SELECT
                                                            a.id_cuti, a.tgl_permohonan, a.jumlah_hari
                                                        FROM tm_cuti AS a
                                                        WHERE a.id_user='$id' 
                                                            AND a.id_ketidakhadiran='AKT-000005' 
                                                        ORDER BY id_cuti DESC
                                                        LIMIT 1
                                                    ");
                                                    $cek_data_cuti_melahirkan = fetch_assoc($cek_data_cuti_melahirkan);
                                                    $aktif_cuti_melahirkan = false;
                                                    if($cek_data_cuti_melahirkan != NULL) {
                                                        $tgl_hari_ini = date('Y-m-d');
                                                        $tgl_terakhir_cuti = date('Y-m-d', strtotime($cek_data_cuti_melahirkan['tgl_permohonan'].' + '.$cek_data_cuti_melahirkan['jumlah_hari'].' days'));
                                                        
                                                        $aktif_cuti_melahirkan = strtotime($tgl_hari_ini) < strtotime($tgl_terakhir_cuti); //  jika tgl cuti melahirkan sudah lewat, bisa diisi lg validasinya
                                                        $cek_data_cuti_melahirkan = $cek_data_cuti_melahirkan['id_cuti']; // kembalikan variable menjadi berisi id_cuti
                                                    }
                                                    if ($aktif_cuti_melahirkan == true) {

                                                        $tmtcek1 = TanggalAwalBulanKemarin();
                                                        $mulai = getOne("select tanggal from tm_hari_cuti where id_cuti='$cek_data_cuti_melahirkan' order by tanggal ASC");
                                                        $selesai = getOne("select tanggal from tm_hari_cuti where id_cuti='$cek_data_cuti_melahirkan' order by tanggal DESC");
                                                        if (strtotime($tmtcek1) >= strtotime($mulai) AND strtotime($tmtcek1) <= strtotime($selesai)) {
                                                            ?>
                                                            <label>Sedang Cuti Melahirkan  </label>
                                                            <input type="number" class="form-control" id="txt1<?php echo $id; ?>" value="0" name="nskp" min="0" max="70"  onkeyup="sum<?php echo $id; ?>();" required readonly/></div>
                                                        <?php } else { ?>
                                                            <!-- <input type="number" class="form-control" id="txt1<?php echo $id; ?>" value="<?php echo NilKinerja($id, $bln_sebelumnya, $thn) * 0.7; ?>" name="nskp" min="0" max="<?php echo $grade_kinerja; ?>"  onkeyup="sum<?php echo $row['id_user']; ?>();" required/></div> -->
                                                            <input type="number" class="form-control" id="txt1<?php echo $id; ?>" value="<?php echo NilKinerja($id, $bln_sebelumnya, $thn) * 0.7; ?>" name="nskp" min="0" max="<?php echo $grade_kinerja; ?>"  onkeyup="" required/></div>
                                                        <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <!-- <input type="number" step="0.01" class="form-control" id="txt1<?php echo $id; ?>" value="<?php echo formatPembulatan(NilKinerja($id, $bln_sebelumnya, $thn) * 0.7); ?>" name="nskp" min="0" max="<?php echo $grade_kinerja; ?>"  onkeyup="sum<?php echo $id; ?>();" required/></div> -->
                                                        <input type="number" step="0.01" class="form-control" id="txt1<?php echo $id; ?>" value="<?php echo formatPembulatan(NilKinerja($id, $bln_sebelumnya, $thn) * 0.7); ?>" name="nskp" min="0" max="<?php echo $grade_kinerja; ?>"  onkeyup="" required/></div>
                                                    <?php }?>
                                            </td>
                                        </tbody>
                                        <tbody>
                                        <td>a). Nilai Penyerapan </td>
                                        <td><?php echo $refrensi_penyerapan['penyerapan']; ?>
                                        </td>
                                        <td> X &ensp;20%
                                        </td>
                                        <td>
                                            <div class="col-xs-8">
                                                <!--<input type="number" class="form-control" value="<?php // echo $refrensi_penyerapan['penyerapan'] * 0.2; ?>" name="npenyerapan" id="text2" onkeyup="sum();" readonly required>-->
                                                <input type="number" step="0.01" id="txt2<?php echo $id; ?>" class="form-control" value="<?php echo $refrensi_penyerapan['penyerapan'] * 0.2; ?>" name="npenyerapan" onkeyup="sum<?php echo $id; ?>();" readonly required/>
                                            </div>
                                        </td>
                                        </tbody>
                                        <tbody>
                                        <td>a). Nilai Prilaku</td>
                                        <td><?php echo number_format(NilDis($id, $bln_sebelumnya, $thn)) . "+" . number_format(NilKomp($id, $bln_sebelumnya, $thn)); ?>
                                        </td>
                                        <td> X &ensp;10% 
                                        </td>
                                        <td>
                                            <div class="col-xs-8">
                                                <!--<input type="number" class="form-control" value="<?php // echo number_format(NilDis($id, $bln_sebelumnya, $thn) + NilKomp($id, $bln_sebelumnya, $thn)) * 0.1; ?>" name="nprilaku" id="text3" onkeyup="sum();" required="">-->
                                                <input type="number" step="0.01" id="txt3<?php echo $id; ?>" class="form-control" value="<?php echo number_format(NilDis($id, $bln_sebelumnya, $thn) + NilKomp($id, $bln_sebelumnya, $thn)) * 0.1; ?>" name="nprilaku" onkeyup="sum<?php echo $id; ?>();" min="0" max="<?php echo $grade_prilaku; ?>" required autofocus/></div>
                                        </td>
                                        </tbody>
                                        <tbody>
                                        <td colspan="3">
                                            JUMLAH
                                        </td>
                                        <td>  
                                            <!-- cek -->
                                            <input type="hidden" id="txt_hukdis<?php echo $id; ?>" value="<?php echo NilaiHukDis($id); ?>" onkeyup="sum<?php echo $id; ?>();" />
                                            <input type="hidden" class="form-control" value="<?php echo GajiPokokValidasi($biodata['pendidikan'], $biodata['tgl_masuk'], $tmtcek); ?>" name="gaji_pokok" readonly>
                                            <input type="hidden" class="form-control" value="<?php echo GajiBruto(GajiPokokValidasi($biodata['pendidikan'], $biodata['tgl_masuk'], $tmtcek), NilaiStatusKawin($biodata['status_nikah'])); ?>" name="gaji_bruto" readonly>
                                            <input type="hidden" id="txt_tunjangan<?php echo $id; ?>" value="<?php echo $tunjangan; ?>" onkeyup="sum<?php echo $id; ?>();" name="tunjangan" readonly />
                                            <input type="hidden" class="form-control" value="<?php echo IdHukDis($id); ?>" name="id_sanksi" readonly>
                                            <input type="hidden" class="form-control" value="<?php echo $biodata['pajak']; ?>" name="pajak" readonly>
                                            <input type="hidden" class="form-control" value="<?php echo $nip; ?>" name="penilai" readonly>
                                            <input type="hidden" class="form-control" value="<?php echo $biodata['status_nikah']; ?>" name="status_nikah" readonly>
                                            <input type="hidden" class="form-control" value="<?php echo $biodata['bpjs_ks']; ?>" name="bpjs_ks" readonly>
                                            <input type="hidden" class="form-control" value="<?php echo $biodata['bpjs_jkk']; ?>" name="bpjs_jkk" readonly>
                                            <input type="hidden" class="form-control" value="<?php echo $biodata['bpjs_ijht']; ?>" name="bpjs_ijht" readonly>
                                            <input type="hidden" class="form-control" value="<?php echo $biodata['bpjs_jp']; ?>" name="bpjs_jp" readonly>
                                            <input type="hidden" class="form-control" value="<?php echo getOne("select id_waktu_s from tm_waktu_s where id_user='$id' and Month(tm_waktu_s.date_s)='$bln_sebelumnya' and Year(tm_waktu_s.date_s)='$thn' order by id_waktu_s desc limit 1"); ?>" name="id_waktu_s" readonly>
                                            <input type="hidden" class="form-control" value="<?php echo getOne("select id_waktu_k from tm_waktu_k where id_user='$id' and Month(tm_waktu_k.date_k)='$bln_sebelumnya' and Year(tm_waktu_k.date_k)='$thn' order by id_waktu_k desc limit 1"); ?>" name="id_waktu_k" readonly>
                                            <input type="hidden" class="form-control" value="<?php echo getOne("select id_waktu_t from tm_waktu_t where id_user='$id' and Month(tm_waktu_t.date_t)='$bln_sebelumnya' and Year(tm_waktu_t.date_t)='$thn' order by id_waktu_t desc limit 1"); ?>" name="id_waktu_t" readonly>
                                            <input type="hidden" class="form-control" value="<?php echo $refrensi_penyerapan['id_penyerapan']; ?>" name="id_penyerapan" readonly>
                                            <input type="hidden" class="form-control" id="tunjangan-val<?php echo $id; ?>" name="tunjangan_val" required/>
                                            <!-- tutup cek-->
                                            <div class="col-xs-3">
                                                <label>Nilai</label>
                                                <!--<input type="number" class="form-control"  name="jumlah" id="text4" readonly required="">-->                                                                    
                                                <input type="number" class="form-control"  name="jumlah" id="txt4<?php echo $id; ?>"  readonly required/>
                                            </div>
                                            <div class="col-xs-8">
                                                <label>Di Rupiahkan</label>
                                                <input type="text" class="form-control" id="tunjangan<?php echo $id; ?>" readonly required/>
                                            </div>
                                        </td>
                                        </tbody>
                                    </table>

                                    <script>
                                        function convertToRupiah(angka)
                                        {
                                            var rupiah = '';
                                            var angkarev = angka.toString().split('').reverse().join('');
                                            for (var i = 0; i < angkarev.length; i++)
                                                if (i % 3 == 0)
                                                    rupiah += angkarev.substr(i, 3) + '.';
                                            return 'Rp. ' + rupiah.split('', rupiah.length - 1).reverse().join('');
                                        }

                                        function sum<?php echo $id; ?>() {
                                            var txtFirstNumber<?php echo $id; ?> = document.getElementById('txt1<?php echo $id; ?>').value;
                                            var txtSecondNumber<?php echo $id; ?> = document.getElementById('txt2<?php echo $id; ?>').value;
                                            var txtTigaNumber<?php echo $id; ?> = document.getElementById('txt3<?php echo $id; ?>').value;
                                            var txtEmpatNumber<?php echo $id; ?> = document.getElementById('txt_tunjangan<?php echo $id; ?>').value;
                                            var txtLimaNumber<?php echo $id; ?> = document.getElementById('txt_hukdis<?php echo $id; ?>').value;
                                            var nilai_val = parseFloat(txtFirstNumber<?php echo $id; ?>) + parseFloat(txtSecondNumber<?php echo $id; ?>) + parseFloat(txtTigaNumber<?php echo $id; ?>);
                                            var tunjangan = (nilai_val * parseFloat(txtEmpatNumber<?php echo $id; ?>) / 100) - ((nilai_val * parseFloat(txtEmpatNumber<?php echo $id; ?>) / 100) * parseFloat(txtLimaNumber<?php echo $id; ?>));
                                            if (!isNaN(nilai_val)) {
                                                document.getElementById('txt4<?php echo $id; ?>').value = nilai_val;
                                                document.getElementById('tunjangan<?php echo $id; ?>').value = convertToRupiah(tunjangan.toFixed(0));
                                                document.getElementById('tunjangan-val<?php echo $id; ?>').value = tunjangan.toFixed(0);
                                            }
                                        }
                                    </script>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <?php echo !$hakakses_memvalidasi ? '<label class="text-danger">Fitur untuk  Pegawai ini hanya diperbolehkan untuk '.$validator_pegawai['nama_level'].'</label>&nbsp;&nbsp;&nbsp;&nbsp;' : "" ?>
                            <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=list-validasi-pegawai'); ?>" class="btn btn-default"> Close </a>
                            <input type="submit" class="btn btn-primary" value="Simpan" <?php echo !$hakakses_memvalidasi ? "disabled" : "" ?> >
                        </div>
                    </form>
                </div>
            </div>
            <!-- Tutup Modal waktu -->
            <?php
            break;

        case "hasil-validasi-management-update":
            $refrensi_penyerapan = fetch_array(bukaquery("select penyerapan,id_penyerapan from tm_penyerapan where Month(tm_penyerapan.bulan)='$bln_sebelumnya' and Year(tm_penyerapan.bulan)='$thn' "));
            $penilaian = fetch_array(bukaquery("select id_penilaian,nskp,nprilaku from tm_penilaian where Month(tm_penilaian.tanggal_penilaian)='$bln_sebelumnya' and Year(tm_penilaian.tanggal_penilaian)='$thn' and tm_penilaian.id_user='$id' "));
            $tmtcek = TanggalAkhirBulanKemarin();
            ?>
            <!--Modal Add waktu -->
            <div class="box">
                <div class="box-header with-border">  
                    <h3 class="box-title fa fa-bullseye">  Update Validasi Bulan <label><?php echo konversiBulan($bln_sebelumnya) . " Tahun " . $thn_now; ?></label> </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                title="Collapse">
                            <i class="fa fa-minus"></i></button>
                    </div>                
                </div>
                <div class="box-body">
                    <form role="form" name="autoSumForm" action="<?php echo $aksi . paramEncrypt('module=validasi-pegawai&act=update-penilaian&id=' . $penilaian['id_penilaian'] . ''); ?>" method="post"> 
                        <div class="modal-body">
                            <div class="box-body table-responsive">
                                <div class="form-group">
                                    <?php
                                    $biodata = fetch_array(bukaquery("SELECT tm_pegawai.id_user,tm_pegawai.nik, tm_pegawai.nama_pegawai, tm_pegawai.nip, tm_pegawai.no_rek, tm_pegawai.tempat_lahir, tm_pegawai.agama, tm_pegawai.npwp,tm_pegawai.jk, 
                                                    tm_pegawai.nama_pegawai, tm_pegawai.tgl_masuk, tm_pegawai.pendidikan, tm_pegawai.alamat, tm_pegawai.no_bpjs,tm_pegawai.tgl_lahir,tm_pegawai.foto,
                                                    tm_unit.id_unit, tm_unit.nama_unit, tm_pegawai.status_nikah, tm_pegawai.sub_bagian,tm_pegawai.pajak, tm_pegawai.rumpun,tm_pegawai.log_finger, 
                                                    tm_pegawai.bpjs_ks, tm_pegawai.bpjs_jkk,tm_pegawai.status, tm_pegawai.bpjs_ijht, tm_pegawai.bpjs_jp, tm_level.nama_level, tm_level.id_level 
                                                    FROM
                                                    tm_pegawai
                                                    INNER JOIN tm_unit ON tm_pegawai.id_unit = tm_unit.id_unit
                                                    INNER JOIN tm_user ON tm_user.id_user = tm_pegawai.id_user
                                                    INNER JOIN tm_level ON tm_user.id_level = tm_level.id_level
                                                    where tm_pegawai.id_user=" . $id . ""));
                                    ?>                           
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-4 col-md-4"> 
                                                <div class="col-xs-12">
                                                    <label>NIP</label>
                                                    <input type="text" class="form-control" value="<?php echo $biodata['nip']; ?>" readonly>
                                                    <label>NAMA PEGAWAI</label>
                                                    <input type="text" class="form-control" value="<?php echo $biodata['nama_pegawai']; ?>" readonly>
                                                    <label>RUMPUN JABATAN</label>
                                                    <input type="text" class="form-control" name="rumpun" value="<?php echo $biodata['rumpun']; ?>" readonly>
                                                    <label>MASA KERJA</label>
                                                    <input type="text" class="form-control" name="masa_kerja" value="<?php echo MasaKerjaPenyebutValidasi($biodata['tgl_masuk']); ?>" readonly>
                                                    <label>PENDIDIDIKAN</label>
                                                    <input type="text" class="form-control" name="pendidikan" value="<?php echo $biodata['pendidikan']; ?>" readonly>
                                                    <label>HUKDIS</label>
                                                    <input type="text" class="form-control" value="<?php echo NamaHukDis($id); ?>" readonly>                                                               

                                                </div>
                                            </div>
                                            <div class="col-xs-8 col-md-4"> 
                                                <div class="col-xs-8">
                                                    <label>JENIS KELAMIN</label>
                                                    <input type="text" class="form-control" value="<?php echo kelamin($biodata['jk']); ?>" readonly>
                                                    <label>TANGGAL MASUK</label>
                                                    <input type="text" class="form-control" value="<?php echo FormatTgl('d/m/Y', $biodata['tgl_masuk']); ?>" readonly>
                                                    <label>STATUS NIKAH</label>
                                                    <input type="text" class="form-control" value="<?php echo $biodata['status_nikah']; ?>" readonly>
                                                    <label>BAGIAN/UNIT</label>
                                                    <input type="text" class="form-control" value="<?php echo $biodata['nama_unit']; ?>" readonly>
                                                    <label>GAJI BRUTO</label>
                                                    <input type="text" class="form-control" value="<?php echo formatDuit(GajiBruto(GajiPokokValidasi($biodata['pendidikan'], $biodata['tgl_masuk'], $tmtcek), NilaiStatusKawin($biodata['status_nikah']))); ?>" readonly>
                                                    <label>TUNJANGAN</label>
                                                    <input type="text" class="form-control" value="<?php echo formatDuit(GajiPokokValidasi($biodata['pendidikan'], $biodata['tgl_masuk'], $tmtcek) * NilaiStatusRumpun($biodata['rumpun'])); ?>" readonly>
                                                </div>                                                        
                                                <div class="col-xs-4">
                                                    <?php
                                                    $tunjangan = GajiPokokValidasi($biodata['pendidikan'], $biodata['tgl_masuk'], $tmtcek) * NilaiStatusRumpun($biodata['rumpun']);
                                                    if ($biodata['foto'] == '-' OR $biodata['foto'] == '') {
                                                        if ($biodata['jk'] == 'L') {
                                                            $foto = 'img/laki.png';
                                                        } else {
                                                            $foto = 'img/perempuan.png';
                                                        }
                                                    } else {
                                                        $foto = "img/" . $biodata['foto'];
                                                    }
                                                    ?>
                                                    <img src="img/male.jpg" style="align:center" class="img-rounded" width="350" height="350" alt="" > 
                                                    <br><br>                                                                
                                                </div>                                                        
                                                <div class="col-xs-16">
                                                </div>
                                            </div>
                                        </div>
                                    </div>  
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                        <th>UNSUR YANG DI NILAI</th>
                                        <th colspan="2">TOTAL CAPAIAN</th>
                                        <th>HASIL</th>
                                        <th>PENILAIAN</th>
                                        </thead>
                                        <tbody>
                                        <td>a). Nilai Kinerja / Capaian Kinerja</td>
                                        <td><?php echo NilKinerja($id, $bln_sebelumnya, $thn); ?>
                                        </td>
                                        <td> X &ensp;70%
                                        </td>
                                        <td><?php echo NilKinerja($id, $bln_sebelumnya, $thn) * 0.7; ?></td>
                                        <td>
                                            <div class="col-xs-8">
                                                <!--<input type="number" class="form-control" value="<?php echo NilKinerja($id, $bln_sebelumnya, $thn) * 0.7; ?>" name="nskp" id="text1" onkeyup="sum();"  required="">-->
                                                <?php
                                                //cek cuti melahirkan
                                                $cek_data_cuti_melahirkan = bukaquery2("
                                                    SELECT
                                                        a.id_cuti, a.tgl_permohonan, a.jumlah_hari
                                                    FROM tm_cuti AS a
                                                    WHERE a.id_user='$id' 
                                                        AND a.id_ketidakhadiran='AKT-000005' 
                                                    ORDER BY id_cuti DESC
                                                    LIMIT 1
                                                ");
                                                $cek_data_cuti_melahirkan = fetch_assoc($cek_data_cuti_melahirkan);
                                                $aktif_cuti_melahirkan = false;
                                                if($cek_data_cuti_melahirkan != NULL) {
                                                    $tgl_hari_ini = date('Y-m-d');
                                                    $tgl_terakhir_cuti = date('Y-m-d', strtotime($cek_data_cuti_melahirkan['tgl_permohonan'].' + '.$cek_data_cuti_melahirkan['jumlah_hari'].' days'));
                                                    
                                                    $aktif_cuti_melahirkan = strtotime($tgl_hari_ini) < strtotime($tgl_terakhir_cuti); //  jika tgl cuti melahirkan sudah lewat, bisa diisi lg validasinya
                                                    $cek_data_cuti_melahirkan = $cek_data_cuti_melahirkan['id_cuti']; // kembalikan variable menjadi berisi id_cuti
                                                }
                                                if ($aktif_cuti_melahirkan == TRUE) {
                                                    $tmtcek1 = TanggalAwalBulanKemarin();
                                                    $mulai = getOne("select tanggal from tm_hari_cuti where id_cuti='$cek_data_cuti_melahirkan' order by tanggal ASC");
                                                    $selesai = getOne("select tanggal from tm_hari_cuti where id_cuti='$cek_data_cuti_melahirkan' order by tanggal DESC");
                                                    if (strtotime($tmtcek1) >= strtotime($mulai) AND strtotime($tmtcek1) <= strtotime($selesai)) {
                                                        ?>
                                                        <label>Sedang Cuti Melahirkan</label>
                                                        <input type="number" class="form-control" id="txt1update<?php echo $id; ?>" value="0" name="nskp" min="0" max="70"  onkeyup="sumupdate<?php echo $id; ?>();" required readonly/></div>
                                                <?php } else { ?>
                                                    <input type="number" class="form-control" id="txt1update<?php echo $id; ?>" value="<?php echo getOne("select nskp from tm_penilaian where Month(tm_penilaian.tanggal_penilaian)='$bln_sebelumnya' and Year(tm_penilaian.tanggal_penilaian)='$thn' and tm_penilaian.id_user='$id' "); ?>" name="nskp" min="0" max="70"  onkeyup="sumupdate<?php echo $id; ?>();" required/></div>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <input type="number" step="0.01" class="form-control" id="txt1update<?php echo $id; ?>" value="<?php echo $penilaian['nskp']; ?>" name="nskp" min="0" max="70"  onkeyup="sumupdate<?php echo $id; ?>();" required/></div>                                                         
                                            <?php }
                                            ?>
                                        </td>
                                        </tbody>
                                        <tbody>
                                        <td>b). Nilai Penyerapan / Nilai Capaian Serapan</td>
                                        <td><?php echo $refrensi_penyerapan['penyerapan']; ?>
                                        </td>
                                        <td> X &ensp;20%
                                        </td>
                                        <td></td>
                                        <td>
                                            <div class="col-xs-8">
                                                <input type="number" step="0.01" id="txt2update<?php echo $id; ?>" class="form-control" value="<?php echo $refrensi_penyerapan['penyerapan'] * 0.2; ?>" name="npenyerapan" onkeyup="sumupdate<?php echo $id; ?>();" readonly required/>
                                            </div>
                                        </td>
                                        </tbody>
                                        <tbody>
                                        <td>c). Nilai Prilaku</td>
                                        <td><?php echo number_format(NilDis($id, $bln_sebelumnya, $thn)) . "+" . number_format(NilKomp($id, $bln_sebelumnya, $thn)); ?>
                                        </td>
                                        <td> X &ensp;10%
                                        </td>
                                        <td><?php echo number_format(NilDis($id, $bln_sebelumnya, $thn) + NilKomp($id, $bln_sebelumnya, $thn)) * 0.1; ?></td>
                                        <td>
                                            <div class="col-xs-8">
                                                <input type="number" step="0.01" id="txt3update<?php echo $id; ?>" class="form-control" value="<?php echo $penilaian['nprilaku']; ?>" name="nprilaku" onkeyup="sumupdate<?php echo $id; ?>();" min="0" max="10" required autofocus/></div>
                                        </td>
                                        </tbody>
                                        <tbody>
                                        <td colspan="4">
                                            JUMLAH
                                        </td>
                                        <td>  
                                            <!-- cek -->
                                            <input type="hidden" id="txt_hukdisupdate<?php echo $id; ?>" value="<?php echo NilaiHukDis($id); ?>" onkeyup="sumupdate<?php echo $id; ?>();" />
                                            <input type="hidden" class="form-control" value="<?php echo GajiPokokValidasi($biodata['pendidikan'], $biodata['tgl_masuk'], $tmtcek); ?>" name="gaji_pokok" readonly>
                                            <input type="hidden" class="form-control" value="<?php echo GajiBruto(GajiPokokValidasi($biodata['pendidikan'], $biodata['tgl_masuk'], $tmtcek), NilaiStatusKawin($biodata['status_nikah'])); ?>" name="gaji_bruto" readonly>
                                            <input type="hidden" id="txt_tunjanganupdate<?php echo $id; ?>" value="<?php echo $tunjangan; ?>" onkeyup="sumupdate<?php echo $id; ?>();" name="tunjangan" readonly />
                                            <input type="hidden" class="form-control" value="<?php echo IdHukDis($id); ?>" name="id_sanksi" readonly>
                                            <input type="hidden" class="form-control" value="<?php echo $biodata['pajak']; ?>" name="pajak" readonly>
                                            <input type="hidden" class="form-control" value="<?php echo $nip; ?>" name="penilai" readonly>
                                            <input type="hidden" class="form-control" value="<?php echo $biodata['status_nikah']; ?>" name="status_nikah" readonly>
                                            <input type="hidden" class="form-control" value="<?php echo $biodata['bpjs_ks']; ?>" name="bpjs_ks" readonly>
                                            <input type="hidden" class="form-control" value="<?php echo $biodata['bpjs_jkk']; ?>" name="bpjs_jkk" readonly>
                                            <input type="hidden" class="form-control" value="<?php echo $biodata['bpjs_ijht']; ?>" name="bpjs_ijht" readonly>
                                            <input type="hidden" class="form-control" value="<?php echo $biodata['bpjs_jp']; ?>" name="bpjs_jp" readonly>
                                            <input type="hidden" class="form-control" value="<?php echo getOne("select id_waktu_s from tm_waktu_s where id_user='$id' and Month(tm_waktu_s.date_s)='$bln_sebelumnya' and Year(tm_waktu_s.date_s)='$thn' order by id_waktu_s desc limit 1"); ?>" name="id_waktu_s" readonly>
                                            <input type="hidden" class="form-control" value="<?php echo getOne("select id_waktu_k from tm_waktu_k where id_user='$id' and Month(tm_waktu_k.date_k)='$bln_sebelumnya' and Year(tm_waktu_k.date_k)='$thn' order by id_waktu_k desc limit 1"); ?>" name="id_waktu_k" readonly>
                                            <input type="hidden" class="form-control" value="<?php echo getOne("select id_waktu_t from tm_waktu_t where id_user='$id' and Month(tm_waktu_t.date_t)='$bln_sebelumnya' and Year(tm_waktu_t.date_t)='$thn' order by id_waktu_t desc limit 1"); ?>" name="id_waktu_t" readonly>
                                            <input type="hidden" class="form-control" value="<?php echo $refrensi_penyerapan['id_penyerapan']; ?>" name="id_penyerapan" readonly>
                                            <input type="hidden" class="form-control" id="tunjangan-valupdate<?php echo $id; ?>" name="tunjangan_val" required/>
                                            <!-- tutup cek-->
                                            <div class="col-xs-3">
                                                <label>Nilai Kinerja</label>
                                                <!--<input type="number" class="form-control"  name="jumlah" id="text4" readonly required="">-->                                                                    
                                                <input type="number" class="form-control"  name="jumlah" id="txt4update<?php echo $id; ?>" readonly required/>
                                            </div>
                                            <div class="col-xs-8">
                                                <label>Di Rupiahkan</label>
                                                <input type="text" class="form-control" id="tunjanganupdate<?php echo $id; ?>" readonly required/>
                                            </div>
                                        </td>
                                        </tbody>
                                    </table>

                                    <script>
                                        function convertToRupiah(angka)
                                        {
                                            var rupiah = '';
                                            var angkarev = angka.toString().split('').reverse().join('');
                                            for (var i = 0; i < angkarev.length; i++)
                                                if (i % 3 == 0)
                                                    rupiah += angkarev.substr(i, 3) + '.';
                                            return 'Rp. ' + rupiah.split('', rupiah.length - 1).reverse().join('');
                                        }

                                        function sumupdate<?php echo $id; ?>() {
                                            var txtFirstNumberupdate<?php echo $id; ?> = document.getElementById('txt1update<?php echo $id; ?>').value;
                                            var txtSecondNumberupdate<?php echo $id; ?> = document.getElementById('txt2update<?php echo $id; ?>').value;
                                            var txtTigaNumberupdate<?php echo $id; ?> = document.getElementById('txt3update<?php echo $id; ?>').value;
                                            var txtEmpatNumberupdate<?php echo $id; ?> = document.getElementById('txt_tunjanganupdate<?php echo $id; ?>').value;
                                            var txtLimaNumberupdate<?php echo $id; ?> = document.getElementById('txt_hukdisupdate<?php echo $id; ?>').value;
                                            var nilai_valupdate = parseFloat(txtFirstNumberupdate<?php echo $id; ?>) + parseFloat(txtSecondNumberupdate<?php echo $id; ?>) + parseFloat(txtTigaNumberupdate<?php echo $id; ?>);
                                            var tunjanganupdate = (nilai_valupdate * parseFloat(txtEmpatNumberupdate<?php echo $id; ?>) / 100) - ((nilai_valupdate * parseFloat(txtEmpatNumberupdate<?php echo $id; ?>) / 100) * parseFloat(txtLimaNumberupdate<?php echo $id; ?>));
                                            if (!isNaN(nilai_valupdate)) {
                                                document.getElementById('txt4update<?php echo $id; ?>').value = nilai_valupdate;
                                                document.getElementById('tunjanganupdate<?php echo $id; ?>').value = convertToRupiah(tunjanganupdate.toFixed(0));
                                                document.getElementById('tunjangan-valupdate<?php echo $id; ?>').value = tunjanganupdate.toFixed(0);
                                            }
                                        }
                                    </script>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=list-validasi-pegawai'); ?>" class="btn btn-default"> Close </a>
                            <input type="submit" class="btn btn-warning" value="Update">
                        </div>
                    </form>
                </div>
            </div>
            <!-- Tutup Modal waktu -->
            <?php
            break;

        case "list-validasi-pegawai":
            ?>
            <div class="box">
                <div class="box-header with-border">  
                    <h3 class="box-title fa fa-list">  LIST VALIDASI PEGAWAI </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                title="Collapse">
                            <i class="fa fa-minus"></i></button>
                    </div>                
                </div>
                <div class="box-body">  
                    <!-- /.box-header -->
                    <?php
                    $no = 0;
                    $tmtcek = TanggalAkhirBulanKemarin();
                    $cek_hak_akses_validasi = fetch_array(bukaquery("select tm_level.menu_val_pj, tm_level.menu_val_kasatpel, tm_level.menu_val_kasie, tm_level.menu_val_kepegawaian from tm_level where tm_level.id_level='$idlevel'"));
                    if ($cek_hak_akses_validasi['menu_val_pj'] == '1') {
                        $tm_pegawai = bukaquery("SELECT tm_unit.nama_unit, tm_pegawai.id_user, tm_pegawai.nip, tm_pegawai.id_unit, tm_pegawai.nama_pegawai, tm_pegawai.tgl_masuk, tm_pegawai.pendidikan,tm_pegawai.status_pegawai,tm_pegawai.jk, tm_pegawai.status, tm_pegawai.foto, tm_user.id_level, tm_level.nama_level   
                                            FROM tm_pegawai
                                            INNER JOIN tm_unit ON tm_pegawai.id_unit = tm_unit.id_unit
                                            INNER JOIN tm_user ON tm_pegawai.id_user = tm_user.id_user
                                            INNER JOIN tm_level ON tm_user.id_level = tm_level.id_level
                                            where tm_pegawai.id_user!='$id_user' 
                                                and tm_pegawai.id_unit='$id_unit' 
                                                and tm_pegawai.tgl_masuk <= '$tmtcek' 
                                                and tm_pegawai.status='AKTIF' 
                                                and (tm_pegawai.status_pegawai='NON PNS'OR tm_pegawai.status_pegawai='SPESIALIS' OR tm_pegawai.status_pegawai='PJLP')
                                                order by tm_level.level ASC, tm_unit.nama_unit ASC, tm_pegawai.nama_pegawai ASC");
                    }
                    if ($cek_hak_akses_validasi['menu_val_kasatpel'] == '1') {
                        $tm_pegawai = bukaquery("SELECT tm_unit.nama_unit, tm_pegawai.id_user, tm_pegawai.nip, tm_pegawai.id_unit, tm_pegawai.nama_pegawai, tm_pegawai.tgl_masuk, tm_pegawai.pendidikan,tm_pegawai.status_pegawai,tm_pegawai.jk, tm_pegawai.status, tm_pegawai.foto, tm_user.id_level, tm_level.nama_level   
                                            FROM tm_pegawai
                                            INNER JOIN tm_unit ON tm_pegawai.id_unit = tm_unit.id_unit
                                            INNER JOIN tm_user ON tm_pegawai.id_user = tm_user.id_user
                                            INNER JOIN tm_level ON tm_user.id_level = tm_level.id_level
                                            where tm_pegawai.id_user!='$id_user' 
                                                and tm_pegawai.id_kasatpel='$kasatpel'
                                                and tm_pegawai.tgl_masuk <= '$tmtcek' 
                                                and tm_pegawai.status='AKTIF' 
                                                and (tm_pegawai.status_pegawai='NON PNS'OR tm_pegawai.status_pegawai='SPESIALIS' OR tm_pegawai.status_pegawai='PJLP')
                                            order by tm_level.level ASC, tm_unit.nama_unit ASC, tm_pegawai.nama_pegawai ASC");
                    }
                    if ($cek_hak_akses_validasi['menu_val_kasie'] == '1') {
                        $tm_pegawai = bukaquery("SELECT tm_unit.nama_unit, tm_pegawai.id_user, tm_pegawai.nip, tm_pegawai.id_unit, tm_pegawai.nama_pegawai, tm_pegawai.tgl_masuk, tm_pegawai.pendidikan,tm_pegawai.status_pegawai,tm_pegawai.jk, tm_pegawai.status, tm_pegawai.foto, tm_user.id_level, tm_level.nama_level  
                                            FROM
                                            tm_pegawai
                                            INNER JOIN tm_unit ON tm_pegawai.id_unit = tm_unit.id_unit
                                            INNER JOIN tm_user ON tm_pegawai.id_user = tm_user.id_user
                                            INNER JOIN tm_level ON tm_user.id_level = tm_level.id_level
                                            where tm_pegawai.id_user!='$id_user' 
                                                and tm_pegawai.sub_bagian='$kasie' 
                                                and tm_pegawai.tgl_masuk <= '$tmtcek' 
                                                and tm_pegawai.status='AKTIF' 
                                                and (tm_pegawai.status_pegawai='NON PNS'OR tm_pegawai.status_pegawai='SPESIALIS' OR tm_pegawai.status_pegawai='PJLP')
                                            order by tm_level.level ASC, tm_unit.nama_unit ASC, tm_pegawai.nama_pegawai ASC");
                    }
                    if($cek_hak_akses_validasi['menu_val_kepegawaian'] == '1') {
                        $tm_pegawai = bukaquery("
                            SELECT 
                                tm_unit.nama_unit, tm_pegawai.id_user, tm_pegawai.nip, tm_pegawai.id_unit, tm_pegawai.nama_pegawai, tm_pegawai.tgl_masuk, tm_pegawai.pendidikan,tm_pegawai.status_pegawai,tm_pegawai.jk, tm_pegawai.status, tm_pegawai.foto, tm_user.id_level, tm_level.nama_level   
                            FROM tm_pegawai
                                INNER JOIN tm_unit ON tm_pegawai.id_unit = tm_unit.id_unit
                                INNER JOIN tm_user ON tm_pegawai.id_user = tm_user.id_user
                                INNER JOIN tm_level ON tm_user.id_level = tm_level.id_level
                            where tm_pegawai.tgl_masuk <= '".$tmtcek."' 
                                    and tm_pegawai.status='AKTIF' 
                                    and (tm_pegawai.status_pegawai='NON PNS'OR tm_pegawai.status_pegawai='SPESIALIS' OR tm_pegawai.status_pegawai='PJLP')
                            order by tm_level.level ASC, tm_unit.nama_unit ASC, tm_pegawai.nama_pegawai ASC
                        ");
                    }
                    while ($row = fetch_array($tm_pegawai)) {
                        $no++;
                        ?>                        
                        <!--thumbinal display>-->
                        <?php
                        if ($row['jk'] == 'L') {
                            $warna = 'bg-aqua-active';
                        } elseif ($row['jk'] == 'P') {
                            $warna = 'bg-fuchsia-active';
                        } else {
                            $warna = 'bg-gray-light';
                        }
                        ?>

                        <!--<div class = "row">-->

                        <div class="col-md-3">
                            <div class="box box-info box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title"></h3>

                                    <div class="box-tools pull-right">
                <!--                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>-->
                                    </div>
                                    <!-- /.box-tools -->
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="center-block"> 
                                        <center>
                                            <?php
                                                if ($row['jk'] == 'L') {
                                                    $foto = 'img/laki.png';
                                                } else {
                                                    $foto = 'img/perempuan.png';
                                                }
                                            
                                            ?>
                                            <!--<img src="<?php // echo $foto;                                           ?>" style="align:center" class="img-rounded" width="250" height="250" alt="" >--> 
                                            <img src = "<?php echo $foto; ?>" width="200" height="200" class="img-circle" alt = "Generic placeholder thumbnail">
                                            <p><br><label><?php echo $row['nama_pegawai']; ?></label></p>
                                            <?php echo $row['nip']; ?>
                                            <p><?php echo $row['nama_level'].' '.$row['nama_unit']; ?></p>
                                        </center>
                                        <?php
                                            $cv_waktu_penambahan = getOne("select count(id_waktu_k) from tm_waktu_k where month(tm_waktu_k.date_k)='$bln_sebelumnya' and year(tm_waktu_k.date_k)='$thn' and tm_waktu_k.id_user='$row[id_user]'");
                                            $cv_waktu_pengurangan = getOne("select count(id_waktu_t) from tm_waktu_t where month(tm_waktu_t.date_t)='$bln_sebelumnya' and year(tm_waktu_t.date_t)='$thn' and tm_waktu_t.id_user='$row[id_user]'");
                                            $cv_waktu_shift = getOne("select count(id_waktu_s) from tm_waktu_s where month(tm_waktu_s.date_s)='$bln_sebelumnya' and year(tm_waktu_s.date_s)='$thn' and tm_waktu_s.id_user='$row[id_user]'");
                                            $cv_kedisiplinan = getOne("select count(id_disiplin) from tm_kedisiplinan where month(tm_kedisiplinan.date_d)='$bln_sebelumnya' and year(tm_kedisiplinan.date_d)='$thn' and tm_kedisiplinan.id_user='$row[id_user]'");
                                            $cv_kompetensi = getOne("select count(id_kompetensi) from tm_kompetensi where month(tm_kompetensi.date_kompetensi)='$bln_sebelumnya' and year(tm_kompetensi.date_kompetensi)='$thn' and tm_kompetensi.id_user='$row[id_user]'");
                                            $cek_kevalidasian = $cv_waktu_penambahan + $cv_waktu_pengurangan + $cv_waktu_shift + $cv_kedisiplinan + $cv_kompetensi;
                                            $cek_tervalidasi_management = getOne("select id_penilaian from tm_penilaian where month(tm_penilaian.tanggal_penilaian)='$bln_sebelumnya' and year(tm_penilaian.tanggal_penilaian)='$thn' and tm_penilaian.id_user='$row[id_user]'");
                                        ?>
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            <th>
                                                <label>TARGET</label> 
                                            </th>
                                            <th>
                                                <label>VALIDASI </label> 
                                            </th>
                                            </thead>
                                            <tbody>
                                            <td>
                                                <span data-toggle="modal" data-target="#modal-skp-tahunan-<?php echo $row['id_user']; ?>" title="SKP TAHUNAN" class="btn-xs btn-info fa fa-bullseye"></span> 
                                            </td>
                                            <td>
                                                <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=list-validasi-kinerja-pegawai&id=' . $row['id_user']); ?>">
                                                    <i class="btn-xs btn-warning fa fa-line-chart" title="KINERJA PEGAWAI"> </i>
                                                </a>
                                                <!-- validasi waktu -->
                                                <br>
                                                <?php
                                                    if ($cv_waktu_penambahan != '0') {
                                                        ?>
                                                        <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=validasi-waktu-pengurangan&id=' . $row['id_user']); ?>">
                                                            <i title="VALIDASI WAKTU PENGURANGAN SELESAI" class="btn-xs btn-success fa fa-clock-o"> -</i>
                                                        </a>
                                                    <?php } else { ?>
                                                        <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=validasi-waktu-pengurangan&id=' . $row['id_user']); ?>">
                                                            <i title="VALIDASI WAKTU PENGURANGAN BELUM" class="btn-xs btn-danger fa fa-clock-o"> -</i>
                                                        </a>
                                                    <?php } ?>

                                                    <?php if ($cv_waktu_pengurangan != '0') { ?>
                                                        <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=validasi-waktu-penambahan&id=' . $row['id_user']); ?>">
                                                            <i title="VALIDASI WAKTU PENAMBAHAN SELESAI" class="btn-xs btn-success fa fa-clock-o"> +</i>
                                                        </a>
                                                    <?php } else { ?>
                                                        <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=validasi-waktu-penambahan&id=' . $row['id_user']); ?>">
                                                            <i title="VALIDASI WAKTU PENAMBAHAN BELUM" class="btn-xs btn-danger fa fa-clock-o"> +</i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if ($cv_waktu_shift != '0') { ?>
                                                        <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=validasi-waktu-shift&id=' . $row['id_user']); ?>">
                                                            <i ttitle="VALIDASI WAKTU SHIFT SELESAI" class="btn-xs btn-success fa fa-clock-o"> S</i>
                                                        </a>
                                                    <?php } else { ?>
                                                        <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=validasi-waktu-shift&id=' . $row['id_user']); ?>">
                                                            <i ttitle="VALIDASI WAKTU SHIFT SELESAI" class="btn-xs btn-danger fa fa-clock-o"> S</i>
                                                        </a>
                                                        <?php
                                                    }
                                                    ?>
                                                    <br>
                                                    <?php if($row['id_unit'] != 'UNT-000027' AND $row['id_user'] != 'UNT-000027' ){ ?>
                                                    <!-- validasi disiplin --> 
                                                    <?php if ($cv_kedisiplinan != '0') { ?>
                                                        <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=validasi-kedisiplinan&id=' . $row['id_user']); ?>">
                                                            <i title="VALIDASI KEDISPLINAN" class="btn-xs btn-success fa fa-thumbs-up"> </i>
                                                        </a>
                                                    <?php } else { ?>
                                                        <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=validasi-kedisiplinan&id=' . $row['id_user']); ?>">
                                                            <i title="VALIDASI KEDISPLINAN" class="btn-xs btn-danger fa fa-thumbs-up"> </i>
                                                        </a>
                                                    <?php } ?>
                                                    <!-- validasi kompetensi --> 
                                                    <?php if ($cv_kompetensi != '0') { ?>
                                                        <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=validasi-kompetensi&id=' . $row['id_user']); ?>">
                                                            <i title="VALIDASI KOMPETENSI" class="btn-xs btn-success fa fa-calendar-check-o"> </i>
                                                        </a>
                                                    <?php } else { ?>
                                                        <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=validasi-kompetensi&id=' . $row['id_user']); ?>">
                                                            <i title="VALIDASI KOMPETENSI" class="btn-xs btn-danger fa fa-calendar-check-o"> </i>
                                                        </a>
                                                    <?php } ?>
                                                    

                                                    <!-- Hasil Validasi --> 
                                                    <?php if ($cek_kevalidasian >= 5) { ?>
                                                        <?php
                                                        if ($hak_akses['menu_val_kasie'] == '1' || $hak_akses['menu_val_kasatpel'] == '1') {
                                                            if ($cek_tervalidasi_management == '') {
                                                                $modal = 'hasil-validasi-management';
                                                                $title = 'Hasil';
                                                                $warna = 'danger';
                                                            } else {
                                                                $modal = 'hasil-validasi-management-update';
                                                                $title = 'Update Hasil';
                                                                $warna = 'warning';
                                                            }
                                                        } else {
                                                            $modal = 'hasil-validasi';
                                                            $title = 'Hasil';
                                                            $warna = 'danger';
                                                        }
                                                        ?>
                                                        <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=' . $modal . '&id=' . $row['id_user']); ?>">
                                                            <i title="<?php echo $title; ?>" class="btn-xs btn-<?php echo $warna; ?> fa fa-check-square-o"> <?php echo $title; ?></i>
                                                        </a>
                                                    <?php }?>
                                                    <?php } ?>
                                                    <br>

                                                    <?php if($row['id_unit'] != 'UNT-000027' AND $row['id_user'] != 'UNT-000027' ){ ?>
                                                        
                                                    <?php } ?>

                                            </td>
                                            </tbody>
                                        </table> 
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>                       

                        <!--Modal view SKp Tahunan -->
                        <div class="modal fade " id="modal-skp-tahunan-<?php echo $row['id_user']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title fa fa-bullseye"> Target SKP Tahunan</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <div class="form-group"> 
                                                <div class="box-body table-responsive">
                                                    <table id="all" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>NIP</th>
                                                                <th>Nama SKP</th>
                                                                <th>Nama Bagian</th>
                                                                <th>Kuantitas <br>(Target)</th>
                                                                <th><p class="text-red">Kuantitas <br>(Pencapaian)</p></th>
                                                        <th>Kualitas <br> (Target)</th>
                                                        <th><p class="text-red">Kualitas <br>(Pencapaian)</p></th>
                                                        <th>Waktu <br> (Target)</th>
                                                        <th><p class="text-red">Waktu <br>(Pencapaian)</p></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            
                                                            $tt_skptahunan = bukaquery("SELECT tm_pegawai.nip, tm_unit.nama_unit, tm_skp.skp, tt_skptahunan.id_skp, tt_skptahunan.kd_skp, tt_skptahunan.kuantitas, tt_skptahunan.tgl_buat, tt_skptahunan.tgl_update, tm_skp.waktu
                                                        FROM tt_skptahunan
                                                        INNER JOIN tm_skp ON tt_skptahunan.kd_skp = tm_skp.kd_skp
                                                        INNER JOIN tm_pegawai ON tt_skptahunan.id_user = tm_pegawai.id_user
                                                        INNER JOIN tm_unit ON tm_pegawai.id_unit = tm_unit.id_unit
                                                        where tm_pegawai.id_user='$row[id_user]'");
                                                            while ($row = fetch_array($tt_skptahunan)) {
                                                                $no++;
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $no; ?></td>
                                                                    <td><?php echo $nip; ?></td>
                                                                    <td><?php echo $row['skp'] . " (" . $row['waktu'] . " Menit)"; ?></td>
                                                                    <td><?php echo $row['nama_unit'] ?></td>
                                                                    <td><?php echo $row['kuantitas']; ?></td>
                                                                    <td><?php echo getOne("select count(kd_skp) as kuantitas from tt_kinerja where kd_skp='$row[kd_skp]' and id_user='$id_user'"); ?></td>
                                                                    <td>100%</td>
                                                                    <td><?php echo number_format((getOne("select count(kd_skp) as kuantitas from tt_kinerja where kd_skp='$row[kd_skp]' and id_user='$id_user'") / $row['kuantitas'] * 100), 2) . "%"; ?></td>
                                                                    <td><?php echo $row['kuantitas'] * $row['waktu'] . " Menit"; ?></td>
                                                                    <td><?php echo getOne("select count(kd_skp) as kuantitas from tt_kinerja where kd_skp='$row[kd_skp]' and id_user='$id_user'") * $row['waktu'] . " Menit"; ?></td>
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
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Tutup Modal skp tahunan -->      
                        <?php
                    }
                    ?>    
                    </tbody>
                </div>
            </div>            
            <?php
            break;
        case "list-validasi-pjlp":
                ?>
                <div class="box">
                    <div class="box-header with-border">  
                        <h3 class="box-title fa fa-list">  LIST VALIDASI PEGAWAI </h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                    title="Collapse">
                                <i class="fa fa-minus"></i></button>
                        </div>                
                    </div>
                    <div class="box-body">  
                        <!-- /.box-header -->
                        <?php
                        $no = 0;
                        $tmtcek = TanggalAkhirBulanKemarin();
                        $cek_hak_akses_validasi = fetch_array(bukaquery("select tm_level.menu_val_pj, tm_level.menu_val_kasatpel, tm_level.menu_val_kasie from tm_level where tm_level.id_level='$idlevel'"));
                        if ($cek_hak_akses_validasi['menu_val_pj'] == '1') {
                            $tm_pegawai = bukaquery("SELECT tm_unit.nama_unit, tm_pegawai.id_user, tm_pegawai.nip, tm_pegawai.id_unit, tm_pegawai.nama_pegawai, tm_pegawai.tgl_masuk, tm_pegawai.pendidikan,tm_pegawai.status_pegawai,tm_pegawai.jk, tm_pegawai.status, tm_pegawai.foto  
                                                FROM
                                                tm_pegawai
                                                INNER JOIN tm_unit ON tm_pegawai.id_unit = tm_unit.id_unit
                                                where tm_pegawai.id_user!='$id_user' and tm_pegawai.id_unit='$id_unit' and tm_pegawai.status_pegawai='NON PNS' and tm_pegawai.tgl_masuk <= '$tmtcek' and tm_pegawai.status='AKTIF' order by tm_pegawai.nama_pegawai");
                        }
                        if ($cek_hak_akses_validasi['menu_val_kasatpel'] == '1') {
                            $tm_pegawai = bukaquery("SELECT tm_unit.nama_unit, tm_pegawai.id_user, tm_pegawai.nip, tm_pegawai.id_unit, tm_pegawai.nama_pegawai, tm_pegawai.tgl_masuk, tm_pegawai.pendidikan,tm_pegawai.status_pegawai,tm_pegawai.jk, tm_pegawai.status, tm_pegawai.foto  
                                                FROM
                                                tm_pegawai
                                                INNER JOIN tm_unit ON tm_pegawai.id_unit = tm_unit.id_unit
                                                where tm_pegawai.id_user!='$id_user' and tm_pegawai.id_kasatpel='$kasatpel' and tm_pegawai.status_pegawai='NON PNS' and tm_pegawai.tgl_masuk <= '$tmtcek' and tm_pegawai.status='AKTIF' and tm_pegawai.id_unit = 'UNT-000027' or tm_pegawai.id_unit = 'UNT-000028' order by tm_pegawai.nama_pegawai");
                        }
                        if ($cek_hak_akses_validasi['menu_val_kasie'] == '1') {
                            $tm_pegawai = bukaquery("SELECT tm_unit.nama_unit, tm_pegawai.id_user, tm_pegawai.nip, tm_pegawai.id_unit, tm_pegawai.nama_pegawai, tm_pegawai.tgl_masuk, tm_pegawai.pendidikan,tm_pegawai.status_pegawai,tm_pegawai.jk, tm_pegawai.status, tm_pegawai.foto  
                                                FROM
                                                tm_pegawai
                                                INNER JOIN tm_unit ON tm_pegawai.id_unit = tm_unit.id_unit
                                                where tm_pegawai.id_user!='$id_user' and tm_pegawai.sub_bagian='$kasie' and tm_pegawai.status_pegawai='NON PNS' and tm_pegawai.tgl_masuk <= '$tmtcek' and tm_pegawai.status='AKTIF' and tm_pegawai.id_unit = 'UNT-000027' or tm_pegawai.id_unit = 'UNT-000028' order by tm_pegawai.nama_pegawai");
                        }
                        while ($row = fetch_array($tm_pegawai)) {
                            $no++;
                            ?>                        
                            <!--thumbinal display>-->
                            <?php
                            if ($row['jk'] == 'L') {
                                $warna = 'bg-aqua-active';
                            } elseif ($row['jk'] == 'P') {
                                $warna = 'bg-fuchsia-active';
                            } else {
                                $warna = 'bg-gray-light';
                            }
                            ?>
    
                            <!--<div class = "row">-->
    
                            <div class="col-md-3">
                                <div class="box box-info box-solid">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"></h3>
    
                                        <div class="box-tools pull-right">
                                        <!--<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>-->
                                        </div>
                                        <!-- /.box-tools -->
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <div class="center-block"> 
                                            <center>
                                                <?php
                                                if ($row['jk'] == 'L') {
                                                    $foto = 'img/laki.png';
                                                } else {
                                                    $foto = 'img/perempuan.png';
                                                }
                                                ?>
                                                <!--<img src="<?php // echo $foto;                                           ?>" style="align:center" class="img-rounded" width="250" height="250" alt="" >--> 
                                                <img src = "<?php echo $foto; ?>" width="200" height="200" class="img-circle" alt = "Generic placeholder thumbnail">
                                                <p><br><label><?php echo $row['nama_pegawai']; ?></label></p>
                                                <?php echo $row['nip']; ?>
                                                <p><?php echo $row['nama_unit']; ?></p>
                                            </center>
                                            <?php
                                                $cv_waktu_penambahan = getOne("select count(id_waktu_k) from tm_waktu_k where month(tm_waktu_k.date_k)='$bln_sebelumnya' and year(tm_waktu_k.date_k)='$thn' and tm_waktu_k.id_user='$row[id_user]'");
                                                $cv_waktu_pengurangan = getOne("select count(id_waktu_t) from tm_waktu_t where month(tm_waktu_t.date_t)='$bln_sebelumnya' and year(tm_waktu_t.date_t)='$thn' and tm_waktu_t.id_user='$row[id_user]'");
                                                $cv_waktu_shift = getOne("select count(id_waktu_s) from tm_waktu_s where month(tm_waktu_s.date_s)='$bln_sebelumnya' and year(tm_waktu_s.date_s)='$thn' and tm_waktu_s.id_user='$row[id_user]'");
                                                $cv_kedisiplinan = getOne("select count(id_disiplin) from tm_kedisiplinan where month(tm_kedisiplinan.date_d)='$bln_sebelumnya' and year(tm_kedisiplinan.date_d)='$thn' and tm_kedisiplinan.id_user='$row[id_user]'");
                                                $cv_kompetensi = getOne("select count(id_kompetensi) from tm_kompetensi where month(tm_kompetensi.date_kompetensi)='$bln_sebelumnya' and year(tm_kompetensi.date_kompetensi)='$thn' and tm_kompetensi.id_user='$row[id_user]'");
                                                $cek_kevalidasian = $cv_waktu_penambahan + $cv_waktu_pengurangan + $cv_waktu_shift + $cv_kedisiplinan + $cv_kompetensi;
                                                $cek_tervalidasi_management = getOne("select id_penilaian from tm_penilaian_pjlp where month(tm_penilaian_pjlp.tanggal_penilaian)='$bln_sebelumnya' and year(tm_penilaian_pjlp.tanggal_penilaian)='$thn' and tm_penilaian_pjlp.id_user='$row[id_user]'");
                                            ?>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <th>
                                                    <label>TARGET</label> 
                                                </th>
                                                <th>
                                                    <label>VALIDASI </label> 
                                                </th>
                                                </thead>
                                                <tbody>
                                                <td>
                                                    <span data-toggle="modal" data-target="#modal-skp-tahunan-<?php echo $row['id_user']; ?>" title="SKP TAHUNAN" class="btn-xs btn-info fa fa-bullseye"></span> 
                                                </td>
                                                <td>
                                                    <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=list-validasi-kinerja-pegawai&id=' . $row['id_user']); ?>">
                                                        <i class="btn-xs btn-warning fa fa-line-chart" title="KINERJA PEGAWAI"> </i>
                                                    </a>
                                                    <!-- validasi waktu -->
                                                    <br>
                                                    <?php
                                                        if ($cv_waktu_penambahan != '0') {
                                                            ?>
                                                            <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=validasi-waktu-pengurangan&id=' . $row['id_user']); ?>">
                                                                <i title="VALIDASI WAKTU PENGURANGAN SELESAI" class="btn-xs btn-success fa fa-clock-o"> -</i>
                                                            </a>
                                                        <?php } else { ?>
                                                            <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=validasi-waktu-pengurangan&id=' . $row['id_user']); ?>">
                                                                <i title="VALIDASI WAKTU PENGURANGAN BELUM" class="btn-xs btn-danger fa fa-clock-o"> -</i>
                                                            </a>
                                                        <?php } ?>
    
                                                        <?php if ($cv_waktu_pengurangan != '0') { ?>
                                                            <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=validasi-waktu-penambahan&id=' . $row['id_user']); ?>">
                                                                <i title="VALIDASI WAKTU PENAMBAHAN SELESAI" class="btn-xs btn-success fa fa-clock-o"> +</i>
                                                            </a>
                                                        <?php } else { ?>
                                                            <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=validasi-waktu-penambahan&id=' . $row['id_user']); ?>">
                                                                <i title="VALIDASI WAKTU PENAMBAHAN BELUM" class="btn-xs btn-danger fa fa-clock-o"> +</i>
                                                            </a>
                                                        <?php } ?>
                                                        <?php if ($cv_waktu_shift != '0') { ?>
                                                            <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=validasi-waktu-shift&id=' . $row['id_user']); ?>">
                                                                <i ttitle="VALIDASI WAKTU SHIFT SELESAI" class="btn-xs btn-success fa fa-clock-o"> S</i>
                                                            </a>
                                                        <?php } else { ?>
                                                            <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=validasi-waktu-shift&id=' . $row['id_user']); ?>">
                                                                <i ttitle="VALIDASI WAKTU SHIFT SELESAI" class="btn-xs btn-danger fa fa-clock-o"> S</i>
                                                            </a>
                                                            <?php } ?>
                                                        <br>
                                                        <?php
                                                        if ($hak_akses['menu_val_pj'] == '1') {
                                                            if ($cek_tervalidasi_management == '') {
                                                                $modal = 'hasil-validasi-pjlp';
                                                                $title = 'Nilai';
                                                                $warna = 'danger';
                                                            } else {
                                                                $modal = 'hasil-validasi-pjlp';
                                                                $title = 'Nilai';
                                                                $warna = 'danger';
                                                            }
                                                        ?>
                                                        <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=' . $modal . '&id=' . $row['id_user']); ?>">
                                                            <i title="<?php echo $title; ?>" class="btn-xs btn-<?php echo $warna; ?> fa fa-check-square-o"> <?php echo $title; ?></i>
                                                        </a>
                                                        <?php } ?>
                                                        <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=rekap-nilai-pjlp&id=' . $row['id_user']); ?>">
                                                            <i title="Rekap Nilai" class="btn-xs btn-info ?> fa fa-check-square-o"> Rekap Nilai</i>
                                                        </a>
                                                </td>
                                                </tbody>
                                            </table> 
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->
                            </div>                       
    
                            <!--Modal view SKp Tahunan -->
                            <div class="modal fade " id="modal-skp-tahunan-<?php echo $row['id_user']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title fa fa-bullseye"> Target SKP Tahunan</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <div class="form-group"> 
                                                    <div class="box-body table-responsive">
                                                        <table id="all" class="table table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>NIP</th>
                                                                    <th>Nama SKP</th>
                                                                    <th>Nama Bagian</th>
                                                                    <th>Kuantitas <br>(Target)</th>
                                                                    <th><p class="text-red">Kuantitas <br>(Pencapaian)</p></th>
                                                            <th>Kualitas <br> (Target)</th>
                                                            <th><p class="text-red">Kualitas <br>(Pencapaian)</p></th>
                                                            <th>Waktu <br> (Target)</th>
                                                            <th><p class="text-red">Waktu <br>(Pencapaian)</p></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $no = 0;
                                                                
                                                                $tt_skptahunan = bukaquery("SELECT tm_pegawai.nip, tm_unit.nama_unit, tm_skp.skp, tt_skptahunan.id_skp, tt_skptahunan.kd_skp, tt_skptahunan.kuantitas, tt_skptahunan.tgl_buat, tt_skptahunan.tgl_update, tm_skp.waktu
                                                            FROM tt_skptahunan
                                                            INNER JOIN tm_skp ON tt_skptahunan.kd_skp = tm_skp.kd_skp
                                                            INNER JOIN tm_pegawai ON tt_skptahunan.id_user = tm_pegawai.id_user
                                                            INNER JOIN tm_unit ON tm_pegawai.id_unit = tm_unit.id_unit
                                                            where tm_pegawai.id_user='$row[id_user]'");
                                                                while ($row = fetch_array($tt_skptahunan)) {
                                                                    $no++;
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $no; ?></td>
                                                                        <td><?php echo $nip; ?></td>
                                                                        <td><?php echo $row['skp'] . " (" . $row['waktu'] . " Menit)"; ?></td>
                                                                        <td><?php echo $row['nama_unit'] ?></td>
                                                                        <td><?php echo $row['kuantitas']; ?></td>
                                                                        <td><?php echo getOne("select count(kd_skp) as kuantitas from tt_kinerja where kd_skp='$row[kd_skp]' and id_user='$id_user'"); ?></td>
                                                                        <td>100%</td>
                                                                        <td><?php echo number_format((getOne("select count(kd_skp) as kuantitas from tt_kinerja where kd_skp='$row[kd_skp]' and id_user='$id_user'") / $row['kuantitas'] * 100), 2) . "%"; ?></td>
                                                                        <td><?php echo $row['kuantitas'] * $row['waktu'] . " Menit"; ?></td>
                                                                        <td><?php echo getOne("select count(kd_skp) as kuantitas from tt_kinerja where kd_skp='$row[kd_skp]' and id_user='$id_user'") * $row['waktu'] . " Menit"; ?></td>
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
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Tutup Modal skp tahunan -->  

                            
                            
                            <?php
                        }
                        ?>    
                        </tbody>
                    </div>
                </div>            
                <?php
                break;
        case "hasil-validasi-pjlp":
                    ?>
                    <!--Modal Add waktu -->
                    <div class="box">
                        <div class="box-header with-border">  
                            <h3 class="box-title fa fa-bullseye">  Refrensi Hasil Validasi Bulan <label><?php echo konversiBulan($bln_sebelumnya) . " Tahun " . $thn_now; ?></label> </h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                        title="Collapse">
                                    <i class="fa fa-minus"></i></button>
                            </div>                
                        </div>
                        <div class="box-body">
                            <form role="form" name="autoSumForm" action="<?php echo $aksi . paramEncrypt('module=validasi-pegawai&act=add-penilaian-pjlp&id=' . $id . ''); ?>" method="post"> 
                                <div class="modal-body">
                                    <div class="box-body table-responsive">
                                        <div class="form-group">
                                            <?php
                                            $biodata = fetch_array(bukaquery("SELECT tm_pegawai.nik, tm_pegawai.nama_pegawai, tm_pegawai.nip, tm_pegawai.no_rek, tm_pegawai.tempat_lahir, tm_pegawai.agama, tm_pegawai.npwp,tm_pegawai.jk, 
                                                    tm_pegawai.nama_pegawai, tm_pegawai.tgl_masuk, tm_pegawai.pendidikan, tm_pegawai.alamat, tm_pegawai.no_bpjs,tm_pegawai.tgl_lahir,tm_pegawai.foto,
                                                    tm_unit.id_unit, tm_unit.nama_unit, tm_pegawai.status_nikah, tm_pegawai.sub_bagian,tm_pegawai.pajak, tm_pegawai.rumpun,tm_pegawai.log_finger, 
                                                    tm_pegawai.bpjs_ks, tm_pegawai.bpjs_jkk,tm_pegawai.status, tm_pegawai.bpjs_ijht, tm_pegawai.bpjs_jp, tm_level.nama_level, tm_level.id_level, tm_level.grade_kinerja, tm_level.grade_prilaku 
                                                    FROM
                                                    tm_pegawai
                                                    INNER JOIN tm_unit ON tm_pegawai.id_unit = tm_unit.id_unit
                                                    INNER JOIN tm_user ON tm_user.id_user = tm_pegawai.id_user
                                                    INNER JOIN tm_level ON tm_user.id_level = tm_level.id_level
                                                    where tm_pegawai.id_user=" . $id . ""));
                                            $nilai = fetch_array(bukaquery("select * from tm_penilaian_pjlp where month(tm_penilaian_pjlp.tanggal_penilaian)='$bln_sebelumnya' and year(tm_penilaian_pjlp.tanggal_penilaian)='$thn' and tm_penilaian_pjlp.id_user='$id'"));
                                            $tmtcek = TanggalAkhirBulanKemarin();
                                            ?>                           
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-xs-4 col-md-4"> 
                                                        <div class="col-xs-12">
                                                            <label>NIP</label>
                                                            <input type="text" class="form-control" value="<?php echo $biodata['nip']; ?>" readonly>
                                                            <label>NAMA PEGAWAI</label>
                                                            <input type="text" class="form-control" value="<?php echo $biodata['nama_pegawai']; ?>" readonly>
                                                            <label>RUMPUN JABATAN</label>
                                                            <input type="text" class="form-control" name="rumpun" value="<?php echo $biodata['rumpun']; ?>" readonly>
                                                            <label>MASA KERJA</label>
                                                            <input type="text" class="form-control" name="masa_kerja" value="<?php echo MasaKerjaPenyebutValidasi($biodata['tgl_masuk']); ?>" readonly>
                                                            <label>PENDIDIDIKAN</label>
                                                            <input type="text" class="form-control" name="pendidikan" value="<?php echo $biodata['pendidikan']; ?>" readonly>
                                                            <label>PENDIDIDIKAN</label>
                                                            <input type="text" class="form-control" value="<?php echo $biodata['pendidikan']; ?>" readonly>
                                                            <label>HUKDIS</label>
                                                            <input type="text" class="form-control" value="<?php echo NamaHukDis($id); ?>" readonly>                                                               
        
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-8 col-md-4"> 
                                                        <div class="col-xs-8">
                                                            <label>JENIS KELAMIN</label>
                                                            <input type="text" class="form-control" value="<?php echo kelamin($biodata['jk']); ?>" readonly>
                                                            <label>TANGGAL MASUK</label>
                                                            <input type="text" class="form-control" value="<?php echo FormatTgl('d/m/Y', $biodata['tgl_masuk']); ?>" readonly>
                                                            <label>STATUS NIKAH</label>
                                                            <input type="text" class="form-control" value="<?php echo $biodata['status_nikah']; ?>" readonly>
                                                            <label>BAGIAN/UNIT</label>
                                                            <input type="text" class="form-control" value="<?php echo $biodata['nama_unit']; ?>" readonly>
                                                            <label>GAJI BRUTO</label>
                                                            <input type="text" class="form-control" value="<?php echo formatDuit(GajiBruto(GajiPokokValidasi($biodata['pendidikan'], $biodata['tgl_masuk'], $tmtcek), NilaiStatusKawin($biodata['status_nikah']))); ?>" readonly>
                                                            <label>TUNJANGAN</label>
                                                            <input type="text" class="form-control" value="<?php echo formatDuit(GajiPokokValidasi($biodata['pendidikan'], $biodata['tgl_masuk'], $tmtcek) * NilaiStatusRumpun($biodata['rumpun'])); ?>" readonly>
                                                        </div>                                                        
                                                        <div class="col-xs-4">
                                                            <?php
                                                            $tunjangan = GajiPokokValidasi($biodata['pendidikan'], $biodata['tgl_masuk'], $tmtcek) * NilaiStatusRumpun($biodata['rumpun']);
                                                            if ($biodata['foto'] == '-' OR $biodata['foto'] == '') {
                                                                if ($biodata['jk'] == 'L') {
                                                                    $foto = 'img/laki.png';
                                                                } else {
                                                                    $foto = 'img/perempuan.png';
                                                                }
                                                            } else {
                                                                $foto = "img/" . $biodata['foto'];
                                                            }
                                                            ?>
                                                            <img src="img/male.jpg" style="align:center" class="img-rounded" width="350" height="350" alt="" > 
                                                            <br><br>                                                                
                                                        </div>                                                        
                                                        <div class="col-xs-16">
                                                            <?php
                                                            $refrensi_penyerapan = fetch_array(bukaquery("select penyerapan,id_penyerapan from tm_penyerapan where Month(tm_penyerapan.bulan)='$bln_sebelumnya' and Year(tm_penyerapan.bulan)='$thn' "));
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>  
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <th>UNSUR YANG DI NILAI</th>
                                                <th>Nilai</th>
                                                <th>Kategori</th>
                                                </thead>
                                                <tbody>
                                                <td>a). Nilai Disiplin Kehadiran</td>
                                                <td>
                                                    <div class="col-xs-8">
                                                        <input type="number" step="0.01" class="form-control" id="txt1<?php echo $id; ?>" value="<?php echo $nilai['nabsensi']; ?>" name="nskp" min="0" max="<?php echo $grade_kinerja; ?>"  onkeyup="sum<?php echo $id; ?>();" required/>
                                                    </div> 
                                                </td>
                                                <td>
                                                    <div class="col-xs-8">
                                                        <input type="text" class="form-control" id="kategori1<?php echo $id; ?>" readonly required/>
                                                    </div>
                                                </td>
                                                </tbody>
                                                <tbody>
                                                <td>b). Nilai Tanggung Jawab Penyelesaian Pekerjaaan </td>
                                                <td>
                                                    <div class="col-xs-8">
                                                        <!--<input type="number" class="form-control" value="<?php // echo $refrensi_penyerapan['penyerapan'] * 0.2; ?>" name="npenyerapan" id="text2" onkeyup="sum();" readonly required>-->
                                                        <input type="number" step="0.01" id="txt2<?php echo $id; ?>" class="form-control" value="<?php echo $nilai['nkinerja'] ?>" name="npenyerapan" onkeyup="sum<?php echo $id; ?>();" required/>
                                                    </div>
                                                </td>
                                                <td>
                                                <div class="col-xs-8">
                                                        <input type="text" class="form-control" id="kategori2<?php echo $id; ?>" readonly required/>
                                                    </div>
                                                </td>
                                                
                                                </tbody>
                                                <tbody>
                                                <td>c). Nilai Kepatuhan Terhadap Kewajiban Dan larangan</td>
                                                <td>
                                                <div class="col-xs-8">
                                                        <!--<input type="number" class="form-control" value="<?php // echo number_format(NilDis($id, $bln_sebelumnya, $thn) + NilKomp($id, $bln_sebelumnya, $thn)) * 0.1; ?>" name="nprilaku" id="text3" onkeyup="sum();" required="">-->
                                                        <input type="number" step="0.01" id="txt3<?php echo $id; ?>" class="form-control" value="<?php echo $nilai['nkepatuhan'] ?>" name="nprilaku" onkeyup="sum<?php echo $id; ?>();" min="0" max="<?php echo $grade_prilaku; ?>" required/>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-xs-8">
                                                        <input type="text" class="form-control" id="kategori3<?php echo $id; ?>" readonly required/>
                                                    </div>
                                                </td>
                                                </tbody>
                                                <tbody>
                                                <td>
                                                    JUMLAH
                                                </td>
                                                <td>  
                                                    <!-- cek -->
                                                    <input type="hidden" id="txt_hukdis<?php echo $id; ?>" value="<?php echo NilaiHukDis($id); ?>" onkeyup="sum<?php echo $id; ?>();" />
                                                    <input type="hidden" class="form-control" value="<?php echo GajiPokokValidasi($biodata['pendidikan'], $biodata['tgl_masuk'], $tmtcek); ?>" name="gaji_pokok" readonly>
                                                    <input type="hidden" class="form-control" value="<?php echo GajiBruto(GajiPokokValidasi($biodata['pendidikan'], $biodata['tgl_masuk'], $tmtcek), NilaiStatusKawin($biodata['status_nikah'])); ?>" name="gaji_bruto" readonly>
                                                    <input type="hidden" id="txt_tunjangan<?php echo $id; ?>" value="<?php echo $tunjangan; ?>" onkeyup="sum<?php echo $id; ?>();" name="tunjangan" readonly />
                                                    <input type="hidden" class="form-control" value="<?php echo IdHukDis($id); ?>" name="id_sanksi" readonly>
                                                    <input type="hidden" class="form-control" value="<?php echo $biodata['pajak']; ?>" name="pajak" readonly>
                                                    <input type="hidden" class="form-control" value="<?php echo $nip; ?>" name="penilai" readonly>
                                                    <input type="hidden" class="form-control" value="<?php echo $biodata['status_nikah']; ?>" name="status_nikah" readonly>
                                                    <input type="hidden" class="form-control" value="<?php echo $biodata['bpjs_ks']; ?>" name="bpjs_ks" readonly>
                                                    <input type="hidden" class="form-control" value="<?php echo $biodata['bpjs_jkk']; ?>" name="bpjs_jkk" readonly>
                                                    <input type="hidden" class="form-control" value="<?php echo $biodata['bpjs_ijht']; ?>" name="bpjs_ijht" readonly>
                                                    <input type="hidden" class="form-control" value="<?php echo $biodata['bpjs_jp']; ?>" name="bpjs_jp" readonly>
                                                    <input type="hidden" class="form-control" value="<?php echo getOne("select id_waktu_s from tm_waktu_s where id_user='$id' and Month(tm_waktu_s.date_s)='$bln_sebelumnya' and Year(tm_waktu_s.date_s)='$thn' order by id_waktu_s desc limit 1"); ?>" name="id_waktu_s" readonly>
                                                    <input type="hidden" class="form-control" value="<?php echo getOne("select id_waktu_k from tm_waktu_k where id_user='$id' and Month(tm_waktu_k.date_k)='$bln_sebelumnya' and Year(tm_waktu_k.date_k)='$thn' order by id_waktu_k desc limit 1"); ?>" name="id_waktu_k" readonly>
                                                    <input type="hidden" class="form-control" value="<?php echo getOne("select id_waktu_t from tm_waktu_t where id_user='$id' and Month(tm_waktu_t.date_t)='$bln_sebelumnya' and Year(tm_waktu_t.date_t)='$thn' order by id_waktu_t desc limit 1"); ?>" name="id_waktu_t" readonly>
                                                    <input type="hidden" class="form-control" value="<?php echo $refrensi_penyerapan['id_penyerapan']; ?>" name="id_penyerapan" readonly>
                                                    <input type="hidden" class="form-control" id="tunjangan-val<?php echo $id; ?>" name="tunjangan_val" required/>
                                                    <!-- tutup cek-->
                                                    <div class="col-xs-8">
                                                        <!--<input type="number" class="form-control"  name="jumlah" id="text4" readonly required="">-->                                                                    
                                                        <input type="number" class="form-control"  name="jumlah" id="txt4<?php echo $id; ?>"  readonly required/>
                                                    </div>
                                                </td>
                                                <td></td>
                                                </tbody>
                                            </table>
        
                                            <script>
                                                function convertToRupiah(angka)
                                                {
                                                    var rupiah = '';
                                                    var angkarev = angka.toString().split('').reverse().join('');
                                                    for (var i = 0; i < angkarev.length; i++)
                                                        if (i % 3 == 0)
                                                            rupiah += angkarev.substr(i, 3) + '.';
                                                    return 'Rp. ' + rupiah.split('', rupiah.length - 1).reverse().join('');
                                                }

                                                function convertToLetter(angka)
                                                {
                                                    
                                                    if(angka >= 75 ) {
                                                        return 'BAIK'                                                        
                                                    }else{
                                                        return 'BURUK'
                                                    }
                                                    
                                                }
        
                                                function sum<?php echo $id; ?>() {
                                                    var txtFirstNumber<?php echo $id; ?> = document.getElementById('txt1<?php echo $id; ?>').value;
                                                    var txtSecondNumber<?php echo $id; ?> = document.getElementById('txt2<?php echo $id; ?>').value;
                                                    var txtTigaNumber<?php echo $id; ?> = document.getElementById('txt3<?php echo $id; ?>').value;
                                                    var txtEmpatNumber<?php echo $id; ?> = document.getElementById('txt4<?php echo $id; ?>').value;
                                                    var txtLimaNumber<?php echo $id; ?> = document.getElementById('txt_hukdis<?php echo $id; ?>').value;
                                                    var nilai_val = parseFloat(txtFirstNumber<?php echo $id; ?>) + parseFloat(txtSecondNumber<?php echo $id; ?>) + parseFloat(txtTigaNumber<?php echo $id; ?>);
                                                    var tot_nilai = nilai_val/3;
                                                    var tunjangan = (nilai_val * parseFloat(txtEmpatNumber<?php echo $id; ?>) / 100) - ((nilai_val * parseFloat(txtEmpatNumber<?php echo $id; ?>) / 100) * parseFloat(txtLimaNumber<?php echo $id; ?>));
                                                    if (!isNaN(nilai_val)) {
                                                        document.getElementById('kategori1<?php echo $id; ?>').value = convertToLetter(txtFirstNumber<?php echo $id; ?>);
                                                        document.getElementById('kategori2<?php echo $id; ?>').value = convertToLetter(txtSecondNumber<?php echo $id; ?>);
                                                        document.getElementById('kategori3<?php echo $id; ?>').value = convertToLetter(txtTigaNumber<?php echo $id; ?>);
                                                        document.getElementById('txt4<?php echo $id; ?>').value = tot_nilai.toFixed(2);
                                                    }
                                                }
                                            </script>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=list-validasi-pjlp'); ?>">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </a>
                                    <input type="submit" class="btn btn-primary" value="Simpan">
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Tutup Modal waktu --> 
                    <?php
                    break;
        case "rekap-nilai-pjlp":
                        ?>
                        <div class="box">
                            <div class="box-header with-border">  
                                <h3 class="box-title fa fa-list">  REKAPITULASI NILAI PEGAWAI PJLP TAHUN <?php echo strtoupper((konversiTahun(TanggalAkhirBulanKemarin()))); ?></h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                            title="Collapse">
                                        <i class="fa fa-minus"></i></button>
                                </div>                
                            </div>
                            <div class="box-body">
                                <!-- /.box-header -->
                                <div class="box-body table-responsive">
                                    <table id="laporan-nilai" class="table table-bordered ">
                                        <thead>
                                            <tr >
                                                <th rowspan="3" style="text-align:center" >No</th>
                                                <th rowspan="3" style="text-align:center">Bulan</th>
                                                <th colspan="6" style="text-align:center">Unsur Penilaian</th>
                                                <th rowspan="3" style="text-align:center">Total Nilai</th>`
                                            </tr>
                                            <tr>
                                                <th colspan="2" style="text-align:center">Disipin kehadiran</th>
                                                <th colspan="2" style="text-align:center">Tanggung Jawab</th>
                                                <th colspan="2" style="text-align:center">kepatuhan</th>
                                                
                                            </tr>
                                            <tr>
                                                <th style="text-align:center">Nilai</th>
                                                <th style="text-align:center">Katagori</th>
                                                <th style="text-align:center">Nilai</th>
                                                <th style="text-align:center">Katagori</th>
                                                <th style="text-align:center">Nilai</th>
                                                <th style="text-align:center">Katagori</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                            $no = 0;
                                            $nilai = bukaquery("select * from tm_penilaian_pjlp where year(tm_penilaian_pjlp.tanggal_penilaian)='$thn' and tm_penilaian_pjlp.id_user='$id'");
                                            $nabsensi = 0; $nkinerja =0 ; $nkepatuhan =0;
                                            while ($row = fetch_array($nilai)) {
                                                $no++;
                                                $bulan = list($thn, $bln, $tgl) = explode('-', $row['tanggal_penilaian']);
                                                $nama_bulan = konversiBulan($bln);
                                                $nabsensi=$nabsensi + $row['nabsensi'];
                                                $nkinerja=$nkinerja + $row['nkinerja'];
                                                $nkepatuhan=$nkepatuhan + $row['nkepatuhan'];
                                        ?>
                                            <tr>
                                                <td style="text-align:center"><?php echo $no; ?></td>
                                                <td style="text-align:center"><?php echo $nama_bulan; ?></td>    
                                                <td style="text-align:center"><?php echo $row['nabsensi'];  ?></td>
                                                <td style="text-align:center"><?php echo konversiLetter($row['nabsensi'])?></td>
                                                <td style="text-align:center"><?php echo $row['nkinerja'];  ?></td>
                                                <td style="text-align:center"><?php echo konversiLetter($row['nkinerja'])?></td>
                                                <td style="text-align:center"><?php echo $row['nkepatuhan'];  ?></td>
                                                <td style="text-align:center"><?php echo konversiLetter($row['nkepatuhan'])?></td>    
                                                <td style="text-align:center"><?php $tot = ($row['nkepatuhan'] + $row['nabsensi'] + $row['nkinerja']) /3; echo number_format($tot,2)  ?></td>
                                            </tr>

                                        <?php
                                            $avg = $avg + $tot;        
                                            }
                                        ?>
                                            <tr>
                                                <td colspan="2" style="text-align:center">Total Nilai Rata-Rata</td>
                                                <td style="text-align:center"><?php echo $nabsensi/$no; ?></td>   
                                                <td style="text-align:center"><?php echo konversiLetter($nabsensi/$no)?></td>
                                                <td style="text-align:center"><?php echo $nkinerja/$no; ?></td>   
                                                <td style="text-align:center"><?php echo konversiLetter($nkinerja/$no)?></td>
                                                <td style="text-align:center"><?php echo $nkepatuhan/$no; ?></td>   
                                                <td style="text-align:center"><?php echo konversiLetter($nkepatuhan/$no)?></td>
                                                <td style="text-align:center"><?php $total = $avg/$no; echo number_format($total,2)?></td>
                                            </tr>

                                            <tr>
                                                <td colspan="8" style="text-align:right">Kategori Nilai</td>
                                                <td style="text-align:center" ><strong><?php echo konversiLetter($total)?></strong></td>
                                            </tr>
                                             
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php
                        break;

        case "list-validasi-kinerja-pegawai":
            ?>
            <div class="box">
                <div class="box-header with-border">  
                    <h3 class="box-title fa fa-list">  LIST DATA KINERJA PEGAWAI</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                title="Collapse">
                            <i class="fa fa-minus"></i></button>
                    </div>                
                </div>
                <div class="box-body"> 
                    <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=list-validasi-pegawai'); ?>">
                        <i class="btn btn-warning fa fa-undo"> Kembali</i>
                    </a>
                    <a href="<?php echo $aksi . paramEncrypt('module=validasi-pegawai&act=validasi-all&id=' . $id . ''); ?>">
                        <i class="btn btn-success fa fa-check-square-o"> Validasi All</i>
                    </a>
                    <label class=""></label>
                    <br>

                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id="kinerja" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
									<th>Nama</th>
                                    <th>NIP</th>
                                    <th>Nama SKP</th>
                                    <th>Uraian</th>
                                    <th>Jam Mulai</th>
                                    <th>Jam Selesai</th>
                                    <th>Durasi Pekerjaan</th>
                                    <th>Status</th>
                                    <th>Volume</th>
                                    <th>Tgl. Buat</th>
                                    <th>Validasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 0;
                                $tt_kinerja = bukaquery("SELECT tm_pegawai.nip,tm_pegawai.id_user,tm_pegawai.nama_pegawai, tm_skp.skp, tt_kinerja.id_kinerja, tt_kinerja.uraian, tt_kinerja.waktu_mulai, tt_kinerja.waktu_akhir, tt_kinerja.date, tm_skp.waktu, tt_kinerja.kd_skp, tt_kinerja.tanggal_kinerja, tt_kinerja.`status`, tt_kinerja.validasi 
                                                    FROM tt_kinerja
                                                    INNER JOIN tm_skp ON tt_kinerja.kd_skp = tm_skp.kd_skp
                                                    INNER JOIN tm_pegawai ON tt_kinerja.id_user = tm_pegawai.id_user
                                                    where Month(tt_kinerja.tanggal_kinerja)='$bln_sebelumnya' and Year(tt_kinerja.tanggal_kinerja)='$thn' and tt_kinerja.id_user=" . $id . " order by tt_kinerja.tanggal_kinerja, tt_kinerja.waktu_mulai ASC");
                                while ($row = fetch_array($tt_kinerja)) {
                                    $no++;
                                    ?>                                            
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo FormatTgl('d-m-Y', $row['tanggal_kinerja']); ?></td>
										<td><?php echo $row['nama_pegawai']; ?></td>
                                        <td><?php echo $row['nip']; ?></td>
                                        <td><?php echo $row['skp'] . " (" . $row['waktu'] . " Menit)"; ?></td>
                                        <td><?php echo $row['uraian'] ?></td>
                                        <td><?php echo $row['waktu_mulai']; ?></td>
                                        <td><?php echo $row['waktu_akhir']; ?></td>
                                        <td><?php echo floor(((is_string($row['waktu_mulai']) ? strtotime($row['waktu_akhir']) : $row['waktu_akhir']) - (is_string($row['waktu_mulai']) ? strtotime($row['waktu_mulai']) : $row['waktu_mulai'])) / 60) . " Menit"; ?></td>
                                        <td><?php echo $row['status']; ?></td>
                                        <td><?php echo formatDec(floor(((is_string($row['waktu_mulai']) ? strtotime($row['waktu_akhir']) : $row['waktu_akhir']) - (is_string($row['waktu_mulai']) ? strtotime($row['waktu_mulai']) : $row['waktu_mulai'])) / 60) / $row['waktu']); ?></td>
                                        <td><?php echo FormatTgl('d/m/y H:i:s', $row['date']); ?></td>
                                        <td>
                <?php if ($row['validasi'] == 'T') { ?>
                                                <a href="<?php echo $aksi . paramEncrypt('module=validasi-pegawai&act=validasi&id=' . $row['id_user'] . '-' . $row['id_kinerja']); ?>">
                                                    <i class="btn btn-danger"> Validasi</i>
                                                </a>
                <?php } else { ?>
                                                <i title="Tervalidasi" class="btn btn-primary"> Sudah Tervalidasi</i>
                                                <a href="<?php echo $aksi . paramEncrypt('module=validasi-pegawai&act=batal-validasi&id=' . $row['id_user'] . '-' . $row['id_kinerja']); ?>">
                                                    <i class="btn btn-warning"> Batalkan Validasi</i>
                                                </a>
                <?php } ?>
                                        </td>
                                    </tr>                                  
                                    <?php
                                }
                                ?>    
                            </tbody>
                            <thead>
                            <th></th>
                            <th colspan='7'>Jumlah Menit Tervalidasi</th>
                            <th colspan="5"><?php
                                if (getOne("select sum(TIMESTAMPDIFF(MINUTE,(waktu_mulai),(waktu_akhir))) as total_durasi from tt_kinerja where Month(tt_kinerja.tanggal_kinerja)='$bln_sebelumnya' and Year(tt_kinerja.tanggal_kinerja)='$thn' and id_user='$id' and validasi='Y' ") != '') {
                                    echo getOne("select sum(TIMESTAMPDIFF(MINUTE,(waktu_mulai),(waktu_akhir))) as total_durasi from tt_kinerja where Month(tt_kinerja.tanggal_kinerja)='$bln_sebelumnya' and Year(tt_kinerja.tanggal_kinerja)='$thn' and id_user='$id' and validasi='Y' ") . " Menit";
                                }
                                ?> </th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <?php
            break;
        case 'tabel-validasi-pegawai':
            ?>
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title fa fa-list">  PILIH UNIT</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                title="Collapse">
                            <i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <table>
                            <thead>
                                <tr>
                                    <td>Unit</td>
                                </tr>
                            </thead>
                            <tbody>
                                <td>
                                    <select class="form-control select2" id="tabel-validasi-pegawai-select-unit" name="tabel-validasi-pegawai-select-unit">
                                    <?php loadSubBagianByLevel($_SESSION['id_level'], $kasie); ?>
                                    </select>
                                </td>
                                <td>
                                    <button class="btn btn-info" id="set-tabel-validasi-pegawai-select-unit" name="set-tabel-validasi-pegawai-select-unit" value="Cari" onclick="search_unit_tabelvalidasi();">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </td>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title fa fa-list">  TABEL VALIDASI PEGAWAI</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <table id="tabel-validasi-pegawai" name="tabel-validasi-pegawai" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>NIP</th>
                                <th>Nama Pegawai</th>
                                <th>Sub Bagian</th>
                                <th>Unit</th>
                                <th>Level</th>
                                <th>Validasi Kinerja</th>
                                <th>Input Waktu Kurang</th>
                                <th>Input Waktu Tambah</th>
                                <th>Input Waktu Shift</th>
                                <th>Input Kedisiplinan</th>
                                <th>Input Kompetensi</th>
                                <th>Referensi Hasil Validasi</th>

                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div> 
            </div>
            
            <?php
            break;
    }
} else {
    header("location:error404");
}
?>
<script src="libs/jquery/jquery.min.js"></script>
<script>

    function close_window() {
        close();
    }

    function search_unit_tabelvalidasi() {

        var sub_bagian = $("#tabel-validasi-pegawai-select-unit").val();
        sub_bagian = encodeURI(sub_bagian);


        $("#set-tabel-validasi-pegawai-select-unit").html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>").prop('disabled', true);
        
        
        $.ajax({
            type: "GET",
            url: "<?php echo $url_api_validasi_pegawai; ?>?action=get_list_pegawai_by_id_unit&sub_bagian="+sub_bagian+"&cur_id_user=<?php echo $_SESSION['id_user']; ?>",
            dataType: "JSON",
            success: function(data) {

                if(data.status == 1) {
                    
                    $("#tabel-validasi-pegawai tbody").empty();
                    
                    for (var i = 0; i < data.data.plain.length; i++) {
                        
                        var no = i + 1;
                        var iduser = data.data.plain[i]['iduser'];
                        var nip = data.data.plain[i]['nip'];
                        var nama_pegawai = data.data.plain[i]['nama_pegawai'];
                        var id_unit = data.data.plain[i]['id_unit'];
                        var foto = data.data.plain[i]['foto'];
                        var sub_bagian = data.data.plain[i]['sub_bagian'];
                        var id_kasatpel = data.data.plain[i]['id_kasatpel'];
                        var id_level = data.data.plain[i]['id_level'];
                        var nama_level = data.data.plain[i]['nama_level'];
                        var nama_unit = data.data.plain[i]['nama_unit'];
                        var input_waktu_kurang = data.data.plain[i]['input_waktu_kurang'] == 0 ? "<a class='btn btn-xs btn-warning' href='?"+data.data.url_action[i]['url_validasi_waktu_pengurangan']+"' target='_blank'>Belum</a>": "<a class='btn btn-xs btn-success' href='?"+data.data.url_action[i]['url_validasi_waktu_pengurangan']+"' target='_blank'>Selesai</a>";
                        var input_waktu_tambah = data.data.plain[i]['input_waktu_tambah'] == 0 ? "<a class='btn btn-xs btn-warning' href='?"+data.data.url_action[i]['url_validasi_waktu_penambahan']+"' target='_blank'>Belum</a>": "<a class='btn btn-xs btn-success' href='?"+data.data.url_action[i]['url_validasi_waktu_penambahan']+"' target='_blank'>Selesai</a>";
                        var input_waktu_shift = data.data.plain[i]['input_waktu_shift'] == 0 ? "<a class='btn btn-xs btn-warning' href='?"+data.data.url_action[i]['url_validasi_waktu_shift']+"' target='_blank'>Belum</a>": "<a class='btn btn-xs btn-success' href='?"+data.data.url_action[i]['url_validasi_waktu_shift']+"' target='_blank'>Selesai</a>";
                        var input_kedisiplinan = data.data.plain[i]['input_kedisiplinan'] == 0 ? "<a class='btn btn-xs btn-warning' href='?"+data.data.url_action[i]['url_validasi_kedisiplian']+"' target='_blank'>Belum</a>": "<a class='btn btn-xs btn-success' href='?"+data.data.url_action[i]['url_validasi_kedisiplian']+"' target='_blank'>Selesai</a>";
                        var input_kompetensi = data.data.plain[i]['input_kompetensi'] == 0 ? "<a class='btn btn-xs btn-warning' href='?"+data.data.url_action[i]['url_validasi_kompetensi']+"' target='_blank'>Belum</a>": "<a class='btn btn-xs btn-success' href='?"+data.data.url_action[i]['url_validasi_kompetensi']+"' target='_blank'>Selesai</a>";
                        var input_penilaian_manajemen = data.data.plain[i]['input_penilaian_manajemen'] == 0 ? "<a class='btn btn-xs btn-warning' href='?"+data.data.url_action[i]['url_validasi_manajemen']+"' target='_blank'>Belum</a>": "<a class='btn btn-xs btn-success' href='?"+data.data.url_action[i]['url_validasi_manajemen']+"' target='_blank'>Selesai</a>";             
                        var input_validasi_kinerja = data.data.plain[i]['input_validasi_kinerja'] == 0 ? "<a class='btn btn-xs btn-warning' href='' target='_blank'>Belum</a>": "<a class='btn btn-xs btn-success' href='?"+data.data.url_action[i]['input_validasi_kinerja']+"' target='_blank'>Selesai</a>";       
                        var validasi_kinerja = data.data.plain[i]['validasi_kinerja'] == 0 ? "<a class='btn btn-xs btn-warning' href='?"+data.data.url_action[i]['url_kinerja_pegawai']+"' target='_blank'>Belum</a>": "<a class='btn btn-xs btn-success' href='?"+data.data.url_action[i]['url_kinerja_pegawai']+"' target='_blank'>Selesai</a>";

                        $('#tabel-validasi-pegawai tbody').append('<tr><td>'+no+'</td><td>'+nip+'</td><td>'+nama_pegawai+'</td><td>'+sub_bagian+'</td><td>'+nama_unit+'</td><td>'+nama_level+'</td><td>'+validasi_kinerja+'</td><td>'+input_waktu_kurang+'</td><td>'+input_waktu_tambah+'</td><td>'+input_waktu_shift+'</td><td>'+input_kedisiplinan+'</td><td>'+input_kompetensi+'</td><td>'+input_penilaian_manajemen+'</td></tr>');
                    }

                    if(!$.fn.dataTable.isDataTable('#tabel-validasi-pegawai')) {

                        $('#tabel-validasi-pegawai').DataTable({
                            "responsive": true,
                            "autoWidth": false,
                            "paging": true,
                            "scrollX": true
                        });
                    }
                } else {

                    console.log("Kode : SEARCHSUBBAGIAN1. Kode Status = 0.");
                    console.log(data);
                }
                $("#set-tabel-validasi-pegawai-select-unit").html("<i class='fa fa-search'></i>").prop('disabled', false);
            },
            error: function(errorMsg) {
                console.log("Kode : SEARCHSUBBAGIAN1. Gagal mengirim permintaan. "+errorMsg.status+"-"+errorMsg.statusText);
                $("#set-tabel-validasi-pegawai-select-unit").html("<i class='fa fa-search'></i>").prop('disabled', false);
            }
        });
    }

    function init_all_datatable() {
        $('#tabel-validasi-pegawai').DataTable({
            "responsive": true,
            "autoWidth": true,
            "paging": true,
            "scrollX": true
        });
    }

    $(document).ready(function() {
        
        // init_all_datatable();
    });


</script>



