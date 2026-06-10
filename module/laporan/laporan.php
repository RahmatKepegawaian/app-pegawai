<?php
$id = isset($url['id']) ? $url['id'] : null;
$dpa = isset($url['dpa']) ? $url['dpa'] : null;
switch ((isset($url['act']) ? $url['act'] : '')) {
    default :
        echo"default";
        header("location:error404");
        break;

    case "laporan-gaji-bruto-pegawai":
        ?>
        <div class="box">
            <div class="box-header with-border">  
                <h3 class="box-title fa fa-list">  LAPORAN GAJI BRUTO PEGAWAI</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>                
            </div>
            <div class="box-body"> 
                <form action="" method="post">
                    <div class="form-group">
                        <table align="Left">
                            <tr> 
                                <td>
                                    <label>Bulan</label>              
                                </td>
                                <td>
                                    <label>Tahun</label> 
                                </td>
                                <td></td>
                            </tr>
                            <tr> 
                                <td>
                                    <select class="form-control select2" name="bulan"  data-placeholder="-Pilih Bulan-"  required>
                                        <?php loadBln('-Pilih Bulan-'); ?>               
                                    </select></td>
                                <td>
                                    <select class="form-control select2" name="tahun"  data-placeholder="-Pilih Tahun-"  required>
                                        <?php loadThn('-Pilih Tahun-'); ?>               
                                    </select>  
                                </td>
                                <td>
                                    <button type="submit" name="search" name="submit" value="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>
                <br><br><br><br><hr>
                <?php
                $bulan_post = isset($_POST['bulan']) ? $_POST['bulan'] : null;
                $tahun_post = isset($_POST['tahun']) ? $_POST['tahun'] : null;
                $tmtcek = $tahun_post . "-" . $bulan_post . "-02";
                if ($bulan_post == '' OR $tahun_post == '') {
                    ?>
                    <div class="box-header with-border">  
                        <h3 class="box-title fa fa-list"> <label>Bulan dan Tahun Tidak Sesuai</label></h3>
                        <div class="box-tools pull-right">
                        </div>                
                    </div>
                <?php } else {
                    ?>
                    <div class="box-header with-border">  
                        <h3 class="box-title fa fa-list"> <label align="center">Laporan <?php echo konversiBulanTahun($tmtcek); ?></label></h3>
                        <div class="box-tools pull-right">
                        </div>                         
                    </div>
                    <br>
                    <form action="cetak-spj-gaji" method="post" target="_Blank">                        
                        <table>
                            <tr>
                                <td><input type="hidden" class="form-control" name="bulan" width="10px" value="<?php echo $bulan_post; ?>" readonly required></td>
                                <td><input type="hidden" class="form-control" name="tahun" value="<?php echo $tahun_post; ?>" readonly required></td>
                                <td>
                                    <button type="submit" name="submit" title="Print SPJ" class="btn btn-info"><i class="fa fa-print"> Print SPJ</i></button>   
                                </td>
                            </tr>
                        </table>
                    </form>
                <?php }
                ?>

                <div class="box-body table-responsive">
                    <table id="laporan" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>Nama Pegawai</th>
                                <th>TMT</th>
                                <th>Masa Kerja</th>
                                <th>Pendidikan</th>
                                <th>Status Pernikahan</th>
                                <th>Besaran Penghasilan Pokok/Bulan</th>
                                <th>Jumlah BRUTO Sebelum Pajak</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $status = 'eror';
                            if ($bulan_post == '-Pilih Bulan-' OR $tahun_post == 'Tidak Boleh') {
                                $status = 'eror';
                            } else {
                                $status = 'AKTIF';
                            }
                            $sql_pegawai = bukaquery("SELECT tm_pegawai.id_user, tm_pegawai.nip, tm_pegawai.nama_pegawai, tm_pegawai.tgl_masuk, tm_pegawai.pendidikan,
                                            tm_unit.id_unit, tm_unit.nama_unit, tm_pegawai.status_nikah, tm_pegawai.sub_bagian,tm_pegawai.pajak, tm_pegawai.rumpun,tm_pegawai.log_finger, 
                                            tm_level.nama_level, tm_level.id_level 
                                            FROM
                                            tm_pegawai
                                            INNER JOIN tm_unit ON tm_pegawai.id_unit = tm_unit.id_unit
                                            INNER JOIN tm_user ON tm_user.id_user = tm_pegawai.id_user
                                            INNER JOIN tm_level ON tm_user.id_level = tm_level.id_level
                                            where (tm_pegawai.status_pegawai='NON PNS' OR tm_pegawai.status_pegawai='PJLP' OR tm_pegawai.status_pegawai='SPESIALIS') and tm_pegawai.status='$status' and tm_pegawai.tgl_masuk <= '$tmtcek'");
                            while ($row = fetch_array($sql_pegawai)) {
                                $no++;
                                $bruto_penilaian = getOne("SELECT tm_penilaian.gaji_bruto FROM tm_penilaian where id_user='$row[id_user]' and Month(tm_penilaian.tanggal_penilaian)='$bulan_post' and Year(tm_penilaian.tanggal_penilaian)='$tahun_post'");
                                ?><!-- Tutup Edit Modal permintaan -->
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo "'" . $row['nip']; ?></td>
                                    <td><?php echo $row['nama_pegawai']; ?></td>
                                    <td><?php echo konversiTanggal(FormatTgl('Y-m-d', $row['tgl_masuk'])); ?></td>
                                    <td><?php echo PenyebutNilaiMasaKerjaCari($row['tgl_masuk'], $tmtcek); ?></td>
                                    <td><?php echo $row['pendidikan']; ?></td>
                                    <td><?php echo $row['status_nikah']; ?></td>
                                    <td><?php echo formatDuit2(GajiPokokLaporan($row['pendidikan'], $row['tgl_masuk'], $tmtcek)); ?></td>
                                    <td>
                                        <?php
                                        //cek nilai bruto jika ada nilai dari penilaian maka akan di tampilkan nilai yang dipenilaian
                                        if ($bruto_penilaian == '') {
                                            echo formatDuit2(GajiBruto(GajiPokokLaporan($row['pendidikan'], $row['tgl_masuk'], $tmtcek), NilaiStatusKawin($row['status_nikah'])));
                                        } else {
                                            echo formatDuit2($bruto_penilaian);
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo 'cetak-slip-gaji-' . $bulan_post . '-' . $tahun_post . '-' . $row['id_user']; ?>" target="_blank"><span title="Cetak Slip Gaji" class="btn-xs btn fa fa-file-pdf-o">  </span>                         
                                            </button>
                                        </a>
                                    </td>
                                </tr>                                  
                                <?php
                            }
                            ?>    
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
        break;

    case "laporan-tunjangan-pegawai":
        ?>
        <div class="box">
            <div class="box-header with-border">  
                <h3 class="box-title fa fa-list">  LAPORAN TUNJANGAN PEGAWAI</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>                
            </div>
            <div class="box-body"> 
                <form action="" method="post">
                    <div class="form-group">
                        <table align="Left">
                            <tr> 
                                <td>
                                    <label>Bulan</label>              
                                </td>
                                <td>
                                    <label>Tahun</label> 
                                </td>
                                <td></td>
                            </tr>
                            <tr> 
                                <td>
                                    <select class="form-control select2" name="bulan"  data-placeholder="-Pilih Bulan-"  required>
                                        <?php loadBln('-Pilih Bulan-'); ?>               
                                    </select></td>
                                <td>
                                    <select class="form-control select2" name="tahun"  data-placeholder="-Pilih Tahun-"  required>
                                        <?php loadThn('-Pilih Tahun-'); ?>               
                                    </select>  
                                </td>
                                <td>
                                    <button type="submit" name="search" name="submit" value="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>
                <br><br><br><br><hr>
                <?php
                $bulan_post = isset($_POST['bulan']) ? $_POST['bulan'] : null;
                $tahun_post = isset($_POST['tahun']) ? $_POST['tahun'] : null;
                $tmtcek = $tahun_post . "-" . $bulan_post . "-02";
                if ($bulan_post == '-Pilih Bulan-' OR $tahun_post == 'Tidak Sesuai') {
                    ?>
                    <div class="box-header with-border">  
                        <h3 class="box-title fa fa-list"> <label>Bulan dan Tahun Tidak Sesuai</label></h3>
                        <div class="box-tools pull-right">
                        </div>                
                    </div>
                <?php } else {
                    ?>
                    <div class="box-header with-border">  
                        <h3 class="box-title fa fa-list"> <label align="center">Laporan Tunjangan Pegawai Bulan <?php echo konversiBulanTahun($tmtcek); ?></label></h3>
                        <div class="box-tools pull-right">
                        </div>                
                    </div>
                <?php }
                ?>
                <div class="box-body table-responsive">
                    <table id="laporan" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Slip Gaji</th>
                                <th>NIP</th>
                                <th>Nama Pegawai</th>
                                <th>Norek</th>
                                <th>NPWP</th>
                                <th>Status Nikah</th>
                                <th>Pendidikan</th>
                                <th>Rumpun</th>
                                <th>Masa Kerja</th>
                                <th>Gapok</th>
                                <th>Bruto</th>
                                <th>Tunjangan Murni</th>
                                <th>BHU</th>
                                <th>HukDis</th>
                                <th>Presentase HukDis</th>
                                <th style="background-color: lightblue;">Tunj. Val BRUTO</th><!--EXT Putra-->
                                <th>Alpha/Hari</th>
                                <th>Sakit 1-2/Hari</th>
                                <th>Sakit >2/Hari</th>
                                <th>Izin/Hari</th>
                                <th>Telat/Menit</th>
                                <th>Alpha/Rupiah</th>
                                <th>Sakit 1-2/Rupiah</th>
                                <th>Sakit >2/Rupiah</th>
                                <th>Izin/Rupiah</th>
                                <th>Telat/Rupiah</th>
                                <th style="background-color: indianred;">Tot. Pot. Kehadiran</th><!--EXT Putra-->
								<!--<th style="background-color: lime;">Tunj. Val NETTO</th><!--EXT Putra-->
                                <th>Tot. Honor Shift</th>
                                <th>Tot. Pend. Pot. Kehadiran </th>
                                <th>BPJS Kes</th>
                                <th>Biaya Jab</th>
                                <th>JKK & JKM</th>
                                <th>IJHT</th>
                                <th>JP</th>
                                <th>Tot. Pend. di Pot. BPJS</th>
                                <th>PPH 21</th>
                                <th>Tot. Pend. Pot. PPH 21</th>
                                <th>Jumlah Sudah Di Bayarkan</th>
                                <th style="background-color: lightgreen;">Jumlah Yang Belum Di Bayarkan</th><!--EXT Putra-->
        <!--                                <th>Jumlah BRUTO Sebelum Pajak</th>
                                <th>Detail</th>-->
								</tr>
                                <!--EXT Putra-->
								<tr>
								<td>1</td>
								<td>2</td>
								<td>3</td>
								<td>4</td>
								<td>5</td>
								<td>6</td>
								<td>7</td>
								<td>8</td>
								<td>9</td>
								<td>10</td>
								<td>11</td>
								<td>12</td>
								<td>13</td>
								<td>14</td>
								<td>15</td>
								<td>16</td>
								<td style="background-color: lightblue;">17</td>
								<td>18</td>
								<td>19</td>
								<td>20</td>
								<td>21</td>
								<td>22</td>
								<td>23</td>
								<td>24</td>
								<td>25</td>
								<td>26</td>
								<td>27</td>
								<td style="background-color: indianred;">28 (28-13)</td>
								<!--<td style="background-color: lime;"><pre>29(28-17)</pre></td><!--EXT Putra-->
								<td>29</td>
								<td>30</td>
								<td>31</td>
								<td>32</td>
								<td>33</td>
								<td>34</td>
								<td>35</td>
								<td>36</td>
								<td>37</td>
								<td>38</td>
								<td>39</td>
								<td style="background-color: lightgreen;">40</td>
								</tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $status = 'eror';
                            if ($bulan_post == 0 OR $tahun_post == 0) {
                                $status = 'eror';
                            } else {
                                $status = 'AKTIF';
                            }
                            $sql_pegawai = bukaquery("SELECT tm_pegawai.id_user, tm_pegawai.nip, tm_pegawai.no_rek, tm_pegawai.npwp, tm_pegawai.nama_pegawai, tm_pegawai.tgl_masuk, tm_penilaian.pendidikan, tm_unit.nama_unit, tm_penilaian.status_nikah,
                                        tm_pegawai.sub_bagian, tm_penilaian.bpjs_ks, tm_penilaian.bpjs_jkk, tm_penilaian.bpjs_ijht, tm_penilaian.bpjs_jp,
                                        (tm_penilaian.gaji_bruto*tm_penilaian.bpjs_ks)+(tm_penilaian.gaji_bruto*tm_penilaian.bpjs_jkk)+(tm_penilaian.gaji_bruto*tm_penilaian.bpjs_ijht)+(tm_penilaian.gaji_bruto*tm_penilaian.bpjs_jp) as potongan_bpjs,
                                        tm_level.nama_level,
                                        tm_penilaian.nskp+tm_penilaian.nprilaku+(tm_penyerapan.penyerapan*0.2) as bhu,
                                        tm_penilaian.id_sanksi, tm_penilaian.gaji_pokok, tm_penilaian.gaji_bruto, tm_penilaian.tunjangan, tm_penilaian.tunjangan_val, tm_penilaian.masa_kerja, tm_penilaian.rumpun, tm_penilaian.pajak, tm_waktu_k.sakit1,
                                        tm_waktu_k.sakit2, tm_waktu_k.alpha, tm_waktu_k.izin, tm_waktu_k.izin_setengah_hari, tm_waktu_k.telat,
                                        ((tm_waktu_s.j_hks*tm_honor_shift.hks)+(tm_waktu_s.j_hkm*tm_honor_shift.hkm)+(tm_waktu_s.j_hlp*tm_honor_shift.hlp)+(tm_waktu_s.j_hls*tm_honor_shift.hls)+(tm_waktu_s.j_hlm*tm_honor_shift.hlm)+(tm_waktu_s.j_hrp*tm_honor_shift.hrp)
                                        +(tm_waktu_s.j_hrs*tm_honor_shift.hrs)+(tm_waktu_s.j_hrm*tm_honor_shift.hrm)) As honor,
                                        ((tm_waktu_k.alpha*0.05)*tm_penilaian.tunjangan_val)+((tm_waktu_k.sakit1*0.01)*tm_penilaian.tunjangan_val)+((tm_waktu_k.sakit2*0.02)*tm_penilaian.tunjangan_val)+((tm_waktu_k.izin*0.025)*tm_penilaian.tunjangan_val)+(((tm_waktu_k.telat/450)*0.025)*tm_penilaian.tunjangan_val) as potongan_absensi,
                                        tm_penyerapan.penyerapan
                                        FROM tm_penilaian
                                        INNER JOIN tm_pegawai ON tm_penilaian.id_user = tm_pegawai.id_user
                                        INNER JOIN tm_unit ON tm_pegawai.id_unit = tm_unit.id_unit
                                        INNER JOIN tm_honor_shift ON tm_unit.id_petugas = tm_honor_shift.id_petugas
                                        INNER JOIN tm_user ON tm_user.id_user = tm_pegawai.id_user
                                        INNER JOIN tm_level ON tm_user.id_level = tm_level.id_level
                                        INNER JOIN tm_waktu_k ON tm_penilaian.id_waktu_k = tm_waktu_k.id_waktu_k
                                        INNER JOIN tm_waktu_s ON tm_penilaian.id_waktu_s = tm_waktu_s.id_waktu_s
                                        INNER JOIN tm_waktu_t ON tm_penilaian.id_waktu_t = tm_waktu_t.id_waktu_t
                                        INNER JOIN tm_penyerapan ON tm_penilaian.id_penyerapan = tm_penyerapan.id_penyerapan
                                        where (tm_pegawai.status_pegawai='NON PNS' OR tm_pegawai.status_pegawai='PJLP' OR tm_pegawai.status_pegawai='SPESIALIS') and tm_pegawai.status='$status' and
                                        Month(tm_penilaian.tanggal_penilaian)='$bulan_post' and Year(tm_penilaian.tanggal_penilaian)='$tahun_post' GROUP BY tm_penilaian.id_user order by tm_pegawai.tgl_masuk ASC");
                            while ($row = fetch_array($sql_pegawai)) {
                                $no++;
                                //$bruto_penilaian = getOne("SELECT tm_penilaian.gaji_bruto FROM tm_penilaian where id_user='$row[id_user]' and Month(tm_penilaian.tanggal_penilaian)='$bulan_post'");
                                $cek_data_cuti_melahirkan = getOne("select tm_cuti.id_cuti from tm_cuti where tm_cuti.id_user='$row[id_user]' and tm_cuti.id_ketidakhadiran='AKT-000005' order by id_cuti DESC");
                                if ($cek_data_cuti_melahirkan != '') {
                                    $mulai = getOne("select tanggal from tm_hari_cuti where id_cuti='$cek_data_cuti_melahirkan' order by tanggal ASC");
                                    $selesai = getOne("select tanggal from tm_hari_cuti where id_cuti='$cek_data_cuti_melahirkan' order by tanggal DESC");
                                    if (strtotime($tmtcek) >= strtotime($mulai) AND strtotime($tmtcek) <= strtotime($selesai)) {
                                        $tunjangan_val = $row['potongan_bpjs'];
                                        $pph21 = 0;
                                        $tunjangan = 0;
                                    } else {
                                        $tunjangan_val = $row['tunjangan_val'];
                                        $hitung_pph21 = PPH21($row['gaji_bruto'] * $row['bpjs_ijht'], $row['gaji_bruto'] * $row['bpjs_jp'], BiayaJabatan($row['gaji_bruto'], $tunjangan_val, $row['honor']), $row['gaji_bruto'], $tunjangan_val, $row['honor'], NilaiStatusPajak($row['pajak']));
                                        if ($hitung_pph21 < 0) {
                                            $pph21 = 0;
                                        } else {
                                            $pph21 = $hitung_pph21;
                                        }
                                        $tunjangan = (($row['gaji_bruto'] + $tunjangan_val - $row['potongan_absensi'] + $row['honor']) - $row['potongan_bpjs']) - $pph21 - $row['gaji_bruto'];
                                    }
                                } else {
                                    $tunjangan_val = $row['tunjangan_val'];
                                    $hitung_pph21 = PPH21($row['gaji_bruto'] * $row['bpjs_ijht'], $row['gaji_bruto'] * $row['bpjs_jp'], BiayaJabatan($row['gaji_bruto'], $tunjangan_val, $row['honor']), $row['gaji_bruto'], $tunjangan_val, $row['honor'], NilaiStatusPajak($row['pajak']));
                                    if ($hitung_pph21 < 0) {
                                        $pph21 = 0;
                                    } else {
                                        $pph21 = $hitung_pph21;
                                    }
                                    $tunjangan = (($row['gaji_bruto'] + $tunjangan_val - $row['potongan_absensi'] + $row['honor']) - $row['potongan_bpjs']) - $pph21 - $row['gaji_bruto'];
                                }
                                ?><!-- Tutup Edit Modal permintaan -->
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td>
                                        <a href="<?php echo 'cetak-slip-gaji1-' . $bulan_post . '-' . $tahun_post . '-' . $row['id_user']; ?>" target="_blank"><span title="Cetak Slip Gaji" class="btn-xs btn fa fa-file-pdf-o">  </span>                         
                                            </button>
                                        </a>
                                    </td>
                                    <td><?php echo "'" . $row['nip']; ?></td>
                                    <td><?php echo $row['nama_pegawai']; ?></td>
                                    <td><?php echo $row['no_rek']; ?></td>
                                    <td><?php echo $row['npwp']; ?></td>
                                    <td><?php echo $row['status_nikah']; ?></td>
                                    <td><?php echo $row['pendidikan']; ?></td>
                                    <td><?php echo $row['rumpun']; ?></td>
                                    <td><?php echo $row['masa_kerja']; ?></td>
                                    <td><?php echo formatDuit2($row['gaji_pokok']); ?></td>
                                    <td><?php echo formatDuit2($row['gaji_bruto']); ?></td>
                                    <td><?php echo formatDuit2($row['tunjangan']); ?></td>
                                    <td><?php echo $row['bhu'] . "%"; ?></td>
                                    <td>
                                        <?php
                                        $sanksi=  fetch_array(bukaquery("select tm_sanksi.nama_sanksi,tm_sanksi.nilai_sanksi from tm_sanksi where tm_sanksi.id_sanksi='$row[id_sanksi]'"));
                                        if ( $sanksi['nama_sanksi']== '') {
                                            echo '-';
                                        } else {
                                            echo $sanksi['nama_sanksi'];
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($sanksi['nilai_sanksi'] == '') {
                                            echo '-';
                                        } else {
                                            echo $sanksi['nilai_sanksi'];
                                        }
                                        ?>
                                    </td>
                                    <td style="background-color: lightblue;"><?php echo formatDuit2($tunjangan_val); ?></td>
                                    <td><?php echo $row['alpha'] . " Hari"; ?></td>
                                    <td><?php echo $row['sakit1'] . " Hari"; ?></td>
                                    <td><?php echo $row['sakit2'] . " Hari"; ?></td>
                                    <td><?php echo $row['izin'] . " Hari"; ?></td>
                                    <td><?php echo $row['telat'] . " Menit"; ?></td>
                                    <td><?php echo formatDuit2(($row['alpha'] * 0.05) * $tunjangan_val); ?></td>
                                    <td><?php echo formatDuit2(($row['sakit1'] * 0.01) * $tunjangan_val); ?></td>
                                    <td><?php echo formatDuit2(($row['sakit2'] * 0.02) * $tunjangan_val); ?></td>
                                    <td><?php echo formatDuit2(($row['izin'] * 0.025) * $tunjangan_val); ?></td>
                                    <td><?php echo formatDuit2((($row['telat'] / 450) * 0.025) * $tunjangan_val); ?></td>
                                    <td style="background-color: indianred;"><?php echo formatDuit2($row['potongan_absensi']); ?></td><!--Ext Putra-->
									<!--<td style="background-color: lime;"><?php echo formatDuit2( $tunjangan_val - $row['potongan_absensi'] ); ?></td><!--Ext Putra-->
                                    <td><?php echo formatDuit2($row['honor']); ?></td>
                                    <td><?php echo formatDuit2($row['gaji_bruto'] + $tunjangan_val - $row['potongan_absensi'] + $row['honor']); ?></td>
                                    <td><?php echo formatDuit2($row['gaji_bruto'] * $row['bpjs_ks']); ?></td>
                                    <td><?php echo formatDuit2(BiayaJabatan($row['gaji_bruto'], $tunjangan_val, $row['honor'])); ?></td>
                                    <td><?php echo formatDuit2($row['gaji_bruto'] * $row['bpjs_jkk']); ?></td>
                                    <td><?php echo formatDuit2($row['gaji_bruto'] * $row['bpjs_ijht']); ?></td>
                                    <td><?php echo formatDuit2($row['gaji_bruto'] * $row['bpjs_jp']); ?></td>
                                    <td><?php echo formatDuit2(($row['gaji_bruto'] + $tunjangan_val - $row['potongan_absensi'] + $row['honor']) - $row['potongan_bpjs']); ?></td>
                                    <td><?php
                                        if ($pph21 < 0) {
                                            echo '0';
                                        } else {
                                            echo formatDuit2($pph21);
                                        }
                                        ?></td>
                                    <td><?php echo formatDuit2((($row['gaji_bruto'] + $tunjangan_val - $row['potongan_absensi'] + $row['honor']) - $row['potongan_bpjs']) - $pph21); ?></td>
                                    <td><?php echo formatDuit2($row['gaji_bruto']); ?></td>
									<!--Ext Putra-->
                                    <td style="background-color: lightgreen;"><?php
                                        if ($tunjangan <= 0) {
                                            echo '0';
                                        } else {
                                            echo formatDuit2(((($row['gaji_bruto'] + $tunjangan_val - $row['potongan_absensi'] + $row['honor']) - $row['potongan_bpjs']) - $pph21)-$row['gaji_bruto']);
                                        }
                                        ?></td>
                                </tr>                                  
                                <?php
                            }
                            ?>    
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
        break;

    case "laporan-penilaian-pegawai":
        ?>
        <div class="box">
            <div class="box-header with-border">  
                <h3 class="box-title fa fa-list">  LAPORAN PENILAIAN PEGAWAI</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>                
            </div>
            <div class="box-body"> 
                <form action="" method="post">
                    <div class="form-group">
                        <table align="Left">
                            <tr> 
                                <td>
                                    <label>Bulan</label>              
                                </td>
                                <td>
                                    <label>Tahun</label> 
                                </td>
                                <td></td>
                            </tr>
                            <tr> 
                                <td>
                                    <select class="form-control select2" name="bulan"  data-placeholder="-Pilih Bulan-"  required>
                                        <?php loadBln('-Pilih Bulan-'); ?>               
                                    </select></td>
                                <td>
                                    <select class="form-control select2" name="tahun"  data-placeholder="-Pilih Tahun-"  required>
                                        <?php loadThn('-Pilih Tahun-'); ?>               
                                    </select>  
                                </td>
                                <td>
                                    <button type="submit" name="search" name="submit" value="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>
                <br><br><br><br><hr>
                <?php
                $bulan_post = isset($_POST['bulan']) ? $_POST['bulan'] : null;
                $tahun_post = isset($_POST['tahun']) ? $_POST['tahun'] : null;
                $tmtcek = $tahun_post . "-" . $bulan_post . "-01";
                if ($bulan_post == '-Pilih Bulan-' OR $tahun_post == 'Tidak Sesuai') {
                    ?>
                    <div class="box-header with-border">  
                        <h3 class="box-title fa fa-list"> <label>Bulan dan Tahun Tidak Sesuai</label></h3>
                        <div class="box-tools pull-right">
                        </div>                
                    </div>
                <?php } else {
                    ?>
                    <div class="box-header with-border">  
                        <h3 class="box-title fa fa-list"> <label align="center">Laporan Penilaian Pegawai Bulan <?php echo konversiBulanTahun($tmtcek); ?></label></h3>
                        <div class="box-tools pull-right">
                        </div>                
                    </div>
                <?php }
                ?>
                <div class="box-body table-responsive">
                    <table id="laporan" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>Nama Pegawai</th>
                                <th>Nilai Kinerja</th>
                                <th>Nilai Prilaku</th>
                                <th>Nilai Penyerapan</th>
                                <th>BHU</th>
                                <th>HukDis</th>
                                <th>Presentase HukDis</th>
                                <th>Alpha/Hari</th>
                                <th>Sakit 1-2/Hari</th>
                                <th>Sakit >2/Hari</th>
                                <th>Izin/Hari</th>
                                <th>Telat/Menit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $status = 'eror';
                            if ($bulan_post == '-Pilih Bulan-' OR $tahun_post == 'Tidak Boleh') {
                                $status = 'eror';
                            } else {
                                $status = 'AKTIF';
                            }
                            //and tm_pegawai.sub_bagian='$kasie'
                            $cek_hak_akses_validasi = fetch_array(bukaquery("select tm_level.menu_val_pj, tm_level.menu_val_kasatpel, tm_level.menu_val_kasie from tm_level where tm_level.id_level='$idlevel'"));
                            if ($cek_hak_akses_validasi['menu_val_kasatpel'] == '1') {
                                $sql_pegawai = bukaquery("SELECT tm_pegawai.id_user, tm_pegawai.nama_pegawai,tm_penilaian.nskp,tm_penilaian.nprilaku, tm_pegawai.nip, tm_penilaian.nskp+tm_penilaian.nprilaku+(tm_penyerapan.penyerapan*0.2) as bhu, (tm_penyerapan.penyerapan*0.2) as penyerapan,
                                tm_penilaian.id_sanksi, tm_waktu_k.sakit1, tm_waktu_k.sakit2, tm_waktu_k.alpha, tm_waktu_k.izin, tm_waktu_k.izin_setengah_hari, tm_waktu_k.telat 
                                FROM tm_penilaian
                                INNER JOIN tm_pegawai ON tm_penilaian.id_user = tm_pegawai.id_user
                                INNER JOIN tm_waktu_k ON tm_penilaian.id_waktu_k = tm_waktu_k.id_waktu_k
                                INNER JOIN tm_waktu_t ON tm_penilaian.id_waktu_t = tm_waktu_t.id_waktu_t
                                INNER JOIN tm_penyerapan ON tm_penilaian.id_penyerapan = tm_penyerapan.id_penyerapan
                                where (tm_pegawai.status_pegawai='NON PNS' OR tm_pegawai.status_pegawai='PJLP' OR tm_pegawai.status_pegawai='SPESIALIS') and tm_pegawai.status='$status' AND tm_pegawai.id_kasatpel = '$kasatpel' and
                                Month(tm_penilaian.tanggal_penilaian)='$bulan_post' and Year(tm_penilaian.tanggal_penilaian)='$tahun_post'  order by tm_pegawai.tgl_masuk ASC");
                   
                            }else{
                                $sql_pegawai = bukaquery("SELECT tm_pegawai.id_user, tm_pegawai.nama_pegawai,tm_penilaian.nskp,tm_penilaian.nprilaku, tm_pegawai.nip, tm_penilaian.nskp+tm_penilaian.nprilaku+(tm_penyerapan.penyerapan*0.2) as bhu, (tm_penyerapan.penyerapan*0.2) as penyerapan,
                                            tm_penilaian.id_sanksi, tm_waktu_k.sakit1, tm_waktu_k.sakit2, tm_waktu_k.alpha, tm_waktu_k.izin, tm_waktu_k.izin_setengah_hari, tm_waktu_k.telat 
                                            FROM tm_penilaian
                                            INNER JOIN tm_pegawai ON tm_penilaian.id_user = tm_pegawai.id_user
                                            INNER JOIN tm_waktu_k ON tm_penilaian.id_waktu_k = tm_waktu_k.id_waktu_k
                                            INNER JOIN tm_waktu_t ON tm_penilaian.id_waktu_t = tm_waktu_t.id_waktu_t
                                            INNER JOIN tm_penyerapan ON tm_penilaian.id_penyerapan = tm_penyerapan.id_penyerapan
                                            where (tm_pegawai.status_pegawai='NON PNS' OR tm_pegawai.status_pegawai='PJLP' OR tm_pegawai.status_pegawai='SPESIALIS') and tm_pegawai.status='$status' and
                                            Month(tm_penilaian.tanggal_penilaian)='$bulan_post' and Year(tm_penilaian.tanggal_penilaian)='$tahun_post'  order by tm_pegawai.tgl_masuk ASC");
                                // echo $bulan_post .' + '.$tahun_post .'+ '. $status;
                            }
                            while ($row = fetch_array($sql_pegawai)) {
                                $no++;
                                ?><!-- Tutup Edit Modal permintaan -->
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo "'" . $row['nip']; ?></td>
                                    <td><?php echo $row['nama_pegawai']; ?></td> 
                                    <td><?php echo $row['nskp']; ?></td> 
                                    <td><?php echo $row['nprilaku']; ?></td> 
                                    <td><?php echo $row['penyerapan']; ?></td> 
                                    <td><?php echo $row['bhu'] . "%"; ?></td>
                                    <td>
                                        <?php
                                        if (getOne("select tm_sanksi.nama_sanksi from tm_sanksi where tm_sanksi.id_sanksi='$row[id_sanksi]'") == '') {
                                            echo '-';
                                        } else {
                                            echo getOne("select tm_sanksi.nama_sanksi from tm_sanksi where tm_sanksi.id_sanksi='$row[id_sanksi]'");
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (getOne("select tm_sanksi.nilai_sanksi from tm_sanksi where tm_sanksi.id_sanksi='$row[id_sanksi]'") == '') {
                                            echo '-';
                                        } else {
                                            echo getOne("select tm_sanksi.nilai_sanksi from tm_sanksi where tm_sanksi.id_sanksi='$row[id_sanksi]'");
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $row['alpha'] . " Hari"; ?></td>
                                    <td><?php echo $row['sakit1'] . " Hari"; ?></td>
                                    <td><?php echo $row['sakit2'] . " Hari"; ?></td>
                                    <td><?php echo $row['izin'] . " Hari"; ?></td>
                                    <td><?php echo $row['telat'] . " Menit"; ?></td>
                                </tr>                                  
                                <?php
                            }
                            ?>    
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
        break;

    case "lap-data-upload":
        ?>
        <div class="box">
            <div class="box-header with-border">  
                <h3 class="box-title fa fa-list">  CEK DATA UPLOAD PEGAWAI NON PNS</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>                
            </div>
            <div class="box-body">  
                <p></p>                
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="laporan" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>Nama Pegawai</th>
                                <th>KTP</th>
                                <th>KK</th>
                                <th>Ijazah</th>
                                <th>STR</th>
                                <th>SIP</th>
                                <th>ACLS</th>
                                <th>ATLS</th>
                                <th>BTCLS</th>
                                <th>APN</th>
                                <th>PHELEBHETOMY</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $list_pegawai = bukaquery("SELECT tm_pegawai.nip, tm_pegawai.id_user, tm_pegawai.nama_pegawai 
                                            FROM
                                            tm_pegawai                                           
                                            where tm_pegawai.status_pegawai='NON PNS'");
                            while ($row = fetch_array($list_pegawai)) {
                                $no++;
                                ?>                               
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $row['nip']; ?></td>
                                    <td><?php echo $row['nama_pegawai']; ?></td>
                                    <td><?php echo getOne("select count(*) from tm_ktp where tm_ktp.id_user='$row[id_user]'"); ?></td>
                                    <td><?php echo getOne("select count(*) from tm_kk where tm_kk.id_user='$row[id_user]'"); ?></td>
                                    <td><?php echo getOne("select count(*) from tm_ijazah where tm_ijazah.id_user='$row[id_user]'"); ?></td>
                                    <td><?php echo getOne("select count(*) from tm_str where tm_str.id_user='$row[id_user]'"); ?></td>
                                    <td><?php echo getOne("select count(*) from tm_sip where tm_sip.id_user='$row[id_user]'"); ?></td>
                                    <td><?php echo getOne("select count(*) from tm_acls where tm_acls.id_user='$row[id_user]'"); ?></td>
                                    <td><?php echo getOne("select count(*) from tm_atls where tm_atls.id_user='$row[id_user]'"); ?></td>
                                    <td><?php echo getOne("select count(*) from tm_btcls where tm_btcls.id_user='$row[id_user]'"); ?></td>
                                    <td><?php echo getOne("select count(*) from tm_apn where tm_apn.id_user='$row[id_user]'"); ?></td>
                                    <td><?php echo getOne("select count(*) from tm_phelebethomy where tm_phelebethomy.id_user='$row[id_user]'"); ?></td>
                                </tr>                                  
                                <?php
                            }
                            ?>    
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
        break;

    case "rekap-shift-pegawai":
        ?>
        <div class="box">
            <div class="box-header with-border">  
                <h3 class="box-title fa fa-list">  REKAPITULASI SHIFT PEGAWAI BULAN <?php echo strtoupper((konversiBulanTahun(TanggalAkhirBulanKemarin()))); ?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>                
            </div>
            <div class="box-body">
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="laporan" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>Nama Pegawai</th>
                                <th>Jumlah Hari Kerja Sore</th>
                                <th>Jumlah Hari Kerja Malam</th>
                                <th>Jumlah Hari Libur Pagi</th>
                                <th>Jumlah Hari Libur Sore</th>
                                <th>Jumlah Hari Libur Malam</th>
                                <th>umlah Hari Raya Pagi</th>
                                <th>Jumlah Hari Raya Sore</th>
                                <th>Jumlah Hari Raya Malam</th>
                                <th>Jumlah Hari Non Shift</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $tmtcek = TanggalAkhirBulanKemarin();
                            $sql_pegawai = bukaquery("SELECT
                                                        tm_waktu_s.*,
                                                        tm_pegawai.nama_pegawai,
                                                        tm_pegawai.nip
                                                    FROM
                                                        tm_waktu_s 
                                                    INNER JOIN tm_pegawai ON tm_waktu_s.id_user = tm_pegawai.id_user
                                                    WHERE
                                                        MONTH ( tm_waktu_s.date_s )= '$bln_sebelumnya' 
                                                        AND YEAR ( tm_waktu_s.date_s )= '$thn'");
                            while ($row = fetch_array($sql_pegawai)) {
                                $no++;
                                ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td>'<?php echo "'" . $row['nip']; ?></td>
                                    <td><?php echo $row['nama_pegawai']; ?></td>
                                    <td><?php echo $row['j_hks']; ?></td>
                                    <td><?php echo $row['j_hkm']; ?></td>
                                    <td><?php echo $row['j_hlp']; ?></td>
                                    <td><?php echo $row['j_hls']; ?></td>
                                    <td><?php echo $row['j_hlm']; ?></td>
                                    <td><?php echo $row['j_hrp']; ?></td>
                                    <td><?php echo $row['j_hrs']; ?></td>
                                    <td><?php echo $row['j_hrm']; ?></td>
                                    <td><?php echo $row['j_ns']; ?></td>
                                    
                                </tr>                                  
                                <?php
                            }
                            ?>    
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
        break;
    case "laporan-shift-pegawai":
        ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list">&nbsp;PILIH PERIODE</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <h4> Pilih Tanggal</h4>
                    <div>
                        <table>
                            <tr>
                                <td><label>Bulan</label></td>
                                <td><label>Tahun</label></td>
                                <td><label>Unit</label></td>
                            </tr>
                            <tr>
                                <td><select class="form-control select2" id="laporan-shift-pegawai-pilih-bln" name="laporan-shift-pegawai-pilih-bln" required><?php loadBln('-Pilih Bulan'); ?></select></td>
                                <td><select class="form-control select2" id="laporan-shift-pegawai-pilih-thn" name="laporan-shift-pegawai-pilih-thn" required><?php loadThn('-Pilih Tahun'); ?></select></td>
                                <td><select class="form-control select2" id="laporan-shift-pegawai-pilih-unit" name="laporan-shift-pegawai-pilih-unit" required><?php loadUnitByLevel($idlevel, $id_unit); ?></select></td>
                                <td>
                                    <button class="btn btn-info btn-group-lg" value="Cari" onclick="laporan_shift_pegawai_search();" id="laporan-shift-pegawai-search" name="laporan-shift-pegawai-search">
                                        <i class="fa fa-search">&nbsp;&nbsp;Cari</i>
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list">&nbsp;TABEL REKAPITULASI SHIFT</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body" id="laporan-shift-pegawai-box">
            </div>
        </div>
        <?php
        break;
}
?>
<script src="libs/jquery/jquery.min.js"></script>
<script>
    // laporan-shift-pegawai

    function remove_leading_zero(value) {
        return value.replace(/^0+(?!\.|$)/, '');
    }

    function get_day_by_date(date) {
        var d = new Date(date);
        return ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu",][d.getDay()];
    }

    function laporan_shift_pegawai_search() {

        console.log('laporan_shift_pegawai_search init');
        
        $("#laporan-shift-pegawai-search").html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>").prop('disabled', true);

        $("#laporan-shift-pegawai-box").html("");
        $("#laporan-shift-pegawai-box").append("<table id='laporan-shift-pegawai-table' name='laporan-shift-pegawai-table' class='table table-bordered table-striped'><thead></thead><tbody></tbody></table>");

        var month = remove_leading_zero($('#laporan-shift-pegawai-pilih-bln').val());
        var year = $('#laporan-shift-pegawai-pilih-thn').val();
        var unit = $('#laporan-shift-pegawai-pilih-unit').val();

        console.log('url :'+"<?php echo $url_api_laporan; ?>?action=get_precense_by_idunit_month_year_from_shift&id_unit="+unit+"&month="+month+"&year="+year);

        $.ajax({
            type: "GET",
            url: "<?php echo $url_api_laporan; ?>?action=get_precense_by_idunit_month_year_from_shift&id_unit="+unit+"&month="+month+"&year="+year,
            dataType: "JSON",
            success: function(response) {

                if(response.status == 1) {

                    var thead_value = '<tr>';
                    var initialized_thead = false;

                    console.log('count :'+response.data.data.length);

                    for (let i = 0; i < response.data.data.length; i++) {
                        
                        var tbody_value = '<tr>';
                        var total_keterlambatan = 0;
                        var total_pulangcepat = 0;
                        
                        thead_value += '<th>No</th>';
                        thead_value += '<th>Nama Pegawai</th>';
                        thead_value += '<th>Terlambat Datang</th>';
                        thead_value += '<th>Pulang Cepat</th>';

                        tbody_value += '<td>'+(i + 1)+'</td>';
                        tbody_value += '<td>'+response.data.data[i].profile.nama_pegawai+'</td>';
                        tbody_value += '<td>'+response.data.data[i].profile.keterlambatan_total+'</td>';
                        tbody_value += '<td>'+response.data.data[i].profile.pulangcepat_total+'</td>';

                        for (let j = 0; j < response.data.days_in_month; j++) {
                            
                            if(!initialized_thead) {

                                thead_value += '<th>'+response.data.data[i].shifts[j].date+', '+get_day_by_date(response.data.data[i].shifts[j].date)+'</th>';
                            }

                            tbody_value += '<td>'+response.data.data[i].shifts[j].nama_shift+' ('+response.data.data[i].shifts[j].absensi_masuk+' - '+response.data.data[i].shifts[j].absensi_pulang+')</td>';

                        }

                        thead_value += '</tr>';
                        tbody_value += '</tr>';

                        if(!initialized_thead) {

                            $('#laporan-shift-pegawai-table thead').append(thead_value);
                            initialized_thead = true;
                        }

                        $('#laporan-shift-pegawai-table tbody').append(tbody_value);
                    }

                    if(!$.fn.dataTable.isDataTable('#laporan-shift-pegawai-table')) {

                        $('#laporan-shift-pegawai-table').DataTable({
                            responsive: true,
                            autoWidth: true,
                            scrollX: true,
                            dom: 'lBfrtip',
                            buttons: [
                                "copy", "excel"
                            ]
                        });
                    }
                } else {

                    console.log("Kode : LAPSHFT1. Kode Status = 0.")
                    console.log(response);
                }

                $("#laporan-shift-pegawai-search").html('<i class="fa fa-search">&nbsp;&nbsp;Cari</i>').prop('disabled', false);
            },
            error: function(errorMsg) {

                $("#laporan-shift-pegawai-search").html('<i class="fa fa-search">&nbsp;&nbsp;Cari</i>').prop('disabled', false);
                console.log("Kode : LAPSHFT1. Gagl mengirim permintaan. "+errorMsg.status+"-"+errorMsg.statusText);
                
            }

        });
    }
    // end laporan-shift-pegawai
</script>


