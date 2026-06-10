<?php
$aksi = "module/kinerja-pegawai/aksi-kinerja-pegawai?";
$id = isset($url['id']) ? $url['id'] : null;
$dpa = isset($url['dpa']) ? $url['dpa'] : null;
switch ((isset($url['act']) ? $url['act'] : '')) {
    default :
        echo"default";
        header("location:error404");
        break;

    case "list-data-skp-tahunan-pegawai":
        ?>
        <div class="box">
            <div class="box-header with-border">  
                <h3 class="box-title fa fa-list">  LIST DATA SKP TAHUNAN</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>                
            </div>
            <div class="box-body">                
                <button type="button" class="btn btn-info fa fa-plus" data-toggle="modal" data-target="#modal-add-skp-tahunan">
                    Buat Master Data SKP Tahunan
                </button>
                <br>
                <!--Modal Add SKP -->
                <div class="modal fade" id="modal-add-skp-tahunan">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title fa fa-plus"> Tambah Data SKP Tahunan</h4>
                            </div>
                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=kinerja-pjlp&act=add-skp-tahunan&id=' . $id_user . ''); ?>" method="post"> 
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Pilih SKP</label>
                                        <select class="form-control select2" name="kd_skp"  data-placeholder="-Pilih SKP-" style="width: 100%;" required>
                                            <option selected="selected" value="">-Pilih SKP-</option>
                                            <?php
                                            $tm_skp = bukaquery("select * from tm_skp_pjlp");
                                            while ($row = fetch_array($tm_skp)) {
                                                echo"<option value=" . $row['kd_skp'] . ">" . $row['skp'] . " [" . $row['waktu'] . " Menit]</option>";
                                            }
                                            ?>               
                                        </select>
                                        <label>Kuantitas</label>
                                        <input type="number" class="form-control" name="kuantitas" placeholder="Kuantitas" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <input type="submit" class="btn btn-primary" value="Simpan">
                                </div> 
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Tutup Modal Skp -->
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>Nama SKP</th>
                                <th>Nama Bagian</th>
                                <th>Kuantitas <br>(Target)</th>
                                <th><p class="text-red">Kuantitas <br>(Pencapaian)</p></th>
                        <th>Kualitas <br> (Target)</th>
                        <th><p class="text-red">Kualitas <br> (Pencapaian)</p></th>
                        <th>Capaian Waktu <br> (Target)</th>
                        <th><p class="text-red">Capaian Waktu <br> (Pencapaian)</p></th>
                        <th>Tgl. Buat</th>
                        <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $tt_skptahunan = bukaquery("SELECT tm_pegawai.nip, tm_unit.nama_unit, tm_skp_pjlp.skp, tt_skptahunan_pjlp.id_skp, tt_skptahunan_pjlp.kd_skp, tt_skptahunan_pjlp.kuantitas, tt_skptahunan_pjlp.tgl_buat, tt_skptahunan_pjlp.tgl_update, tm_skp_pjlp.waktu
                                                        FROM tt_skptahunan
                                                        INNER JOIN tm_skp ON tt_skptahunan_pjlp.kd_skp = tm_skp_pjlp.kd_skp
                                                        INNER JOIN tm_pegawai ON tt_skptahunan_pjlp.id_user = tm_pegawai.id_user
                                                        INNER JOIN tm_unit ON tm_pegawai.id_unit = tm_unit.id_unit
                                                        where tt_skptahunan_pjlp.id_user='$id_user'");
                            while ($row = fetch_array($tt_skptahunan)) {
                                $no++;
                                ?>
                                <!-- Edit Modal SKP -->
                            <div class="modal fade" id="modal-ubah-<?php echo $row['id_skp']; ?>"  role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="<?php echo $aksi . paramEncrypt('module=kinerja-pjlp&act=update-skp-tahunan&id=' . $row['id_skp'] . ''); ?>" method="POST" role="form">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel">Update SKP Tahunan</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <label>Pilih SKP</label>
                                                        <select class="form-control select2" name="kd_skp"  data-placeholder="-Pilih SKP-" style="width: 100%;" required>
                                                            <option selected="selected" value="">-Pilih SKP-</option>
                                                            <?php
                                                            $tm_skp = bukaquery("select * from tm_skp_pjlp ");
                                                            while ($skp = fetch_array($tm_skp)) {
                                                                if ($row['kd_skp'] == $skp['kd_skp']) {
                                                                    echo"<option value=" . $skp['kd_skp'] . " selected=" . $row['kd_skp'] . ">" . $skp['skp'] . " [" . $skp['waktu'] . " Menit]</option>";
                                                                } else {
                                                                    echo"<option value=" . $skp['kd_skp'] . ">" . $skp['skp'] . " [" . $skp['waktu'] . " Menit]</option>";
                                                                }
                                                            }
                                                            ?>               
                                                        </select>
                                                        <label>Kuantitas</label>
                                                        <input type="number" class="form-control" name="kuantitas" value="<?php echo $row['kuantitas']; ?>" required>
                                                    </div>                                                   
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="submit" name="update" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Tutup Edit Modal permintaan -->
                            <!-- Edit Modal SKP -->
                            <div class="modal fade" id="modal-hapus-<?php echo $row['id_skp']; ?>" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="<?php echo $aksi . paramEncrypt('module=kinerja-pjlp&act=delete-skp-tahunan&id=' . $row['id_skp'] . ''); ?>" method="POST" role="form">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel">Delete Data SKP Tahunan</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <label>Yakin Anda ingin Menghapus Data ini,klik Tombol Ya? Untuk Menghapusnya</label>
                                                    </div>                                                   
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                                <button type="submit" class="btn btn-danger" autofocus>Ya</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Tutup Edit Modal permintaan -->
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
                                <td><?php echo FormatTgl('d-m-Y', $row['tgl_buat']); ?></td>
                                <td>
                                    <span data-toggle="modal" data-target="#modal-ubah-<?php echo $row['id_skp']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                    <span data-toggle="modal" data-target="#modal-hapus-<?php echo $row['id_skp']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
                                </td>
                            </tr>                                  
                            <?php
                        }
                        ?>    
                        </tbody>
                        <thead>
                        <th colspan="8"></th>
                        <th colspan="4"><?php
                            $total_target = getOne("SELECT sum(tt_skptahunan.kuantitas * tm_skp.waktu) from tt_skptahunan INNER JOIN tm_skp ON tt_skptahunan.kd_skp = tm_skp.kd_skp where tt_skptahunan.id_user='$id_user'");
                            if ($total_target > 72000) {
                                echo $total_target . " Menit <p class='text-red'>Target anda Tidak Boleh Melebihi 72000 menit";
                            } else {
                                echo $total_target . " Menit";
                            }
                            ?></th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <?php
        break;

    case "list-data-kinerja-pegawai":
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
                <button type="button" class="btn btn-info fa fa-plus" data-toggle="modal" data-target="#modal-add-kinerja-utama">
                    Buat Kinerja Utama
                </button>
                <button type="button" class="btn btn-warning fa fa-plus" data-toggle="modal" data-target="#modal-add-kinerja-tambahan">
                    Buat Kinerja Tambahan
                </button>
                <a href="?<?php echo paramEncrypt('module=kinerja-pegawai&act=rekap-data-kinerja-pegawai'); ?>">
                    <button type="button" class="btn btn-instagram fa fa-search" title="cek rekapitulasi kinerja">                        
                    </button>
                </a>

                <!--Modal Add SKP -->
                <div class="modal fade" id="modal-add-kinerja-utama">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title fa fa-plus"> Tambah Kinerja Utama <?php echo $id_user; ?></h4>
                            </div>
                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=kinerja-pegawai&act=add-kinerja-utama&id=' . $id_user . ''); ?>" method="post"> 
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Tanggal</label>
                                        <input type="text" class="form-control" id="datepicker1" placeholder="mm/dd/yyyy" name="tanggal_kinerja" required readonly>
                                        <label>Pilih SKP</label>
                                        <select class="form-control select2" name="kd_skp"  data-placeholder="-Pilih SKP-" style="width: 100%;" required>
                                            <option selected="selected" value="">-Pilih SKP-</option>
                                            <?php
                                            $tt_skptahunan = bukaquery("SELECT tm_skp.skp, tm_skp.waktu, tm_skp.kd_skp FROM tt_skptahunan INNER JOIN tm_skp ON tt_skptahunan.kd_skp = tm_skp.kd_skp where tt_skptahunan.id_user=" . $id_user . "");
                                            while ($skptahuanan = fetch_array($tt_skptahunan)) {
                                                echo"<option value=" . $skptahuanan['kd_skp'] . ">" . $skptahuanan['skp'] . " [" . $skptahuanan['waktu'] . " Menit]</option>";
                                            }
                                            ?>               
                                        </select>
                                        <label>uraian</label>
                                        <textarea class="form-control" name="uraian" placeholder="uraian" rows="5" required></textarea>
                                        <label>Jam Mulai</label>
                                        <div class="input-group">
                                            <input class="form-control" name="waktu_mulai" id="jam-mulai-utama" value="" placeholder="00:00" readonly required>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-time"></span>
                                            </span>
                                        </div>
                                        <label>Jam Selesai</label>
                                        <div class="input-group">
                                            <input class="form-control" name="waktu_akhir" id="jam-selesai-utama" value="" placeholder="00:00" readonly required>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-time"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <input type="submit" class="btn btn-primary" value="Simpan">
                                </div> 
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Tutup Modal Skp -->
                <!--Modal Add SKP -->
                <div class="modal fade" id="modal-add-kinerja-tambahan">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title fa fa-plus"> Tambah Kinerja Tambahan</h4>
                            </div>
                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=kinerja-pegawai&act=add-kinerja-tambahan&id=' . $id_user . ''); ?>" method="post"> 
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Tanggal</label>
                                        <input type="text" class="form-control" id="datepicker2" placeholder="mm/dd/yyyy" name="tanggal_kinerja" required readonly>
                                        <label>Pilih SKP</label>
                                        <select class="form-control select2" name="kd_skp"  data-placeholder="-Pilih SKP-" style="width: 100%;" required>
                                            <option selected="selected" value="">-Pilih SKP-</option>
                                            <?php
                                            $tm_skp = bukaquery("select * from tm_skp");
                                            while ($row = fetch_array($tm_skp)) {
                                                echo"<option value=" . $row['kd_skp'] . ">" . $row['skp'] . " [" . $row['waktu'] . " Menit]</option>";
                                            }
                                            ?>               
                                        </select>
                                        <label>uraian</label>
                                        <textarea class="form-control" name="uraian" rows="5" placeholder="uraian" required></textarea>
                                        <label>Jam Mulai</label>
                                        <div class="input-group">
                                            <input class="form-control" name="waktu_mulai" id="jam-mulai-tambahan" value="" placeholder="00:00" readonly required>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-time"></span>
                                            </span>
                                        </div>
                                        <label>Jam Selesai</label>
                                        <div class="input-group">
                                            <input class="form-control" name="waktu_akhir" id="jam-selesai-tambahan" value="" placeholder="00:00" readonly required>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-time"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <input type="submit" class="btn btn-primary" value="Simpan">
                                </div> 
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Tutup Modal Skp -->
                <!-- /.box-header -->                
                <div class="box-body table-responsive">
                    <table id="kinerja" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No <?php echo $bulan_setup; ?></th>
                                <th>Tanggal</th>
                                <th>NIP</th>
                                <th>Nama SKP</th>
                                <th>Uraian</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                                <th>Durasi Pekerjaan</th>
                                <th>Status</th>
                                <th>Volume</th>
                                <th>Tgl. Buat</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            if ($day >= 1 and $day <= $setup['tutup_kinerja']) {
                                $bulan_setup = $bln_sebelumnya;
                                $tahun_setup = $thn;
                            } else {
                                $bulan_setup = $bln_now;
                                $tahun_setup = $thn_now;
                            }
                            $tt_kinerja = bukaquery("SELECT tm_pegawai.nip, tm_skp.skp, tt_kinerja.id_kinerja, tt_kinerja.uraian, tt_kinerja.waktu_mulai, tt_kinerja.waktu_akhir, tt_kinerja.date, tm_skp.waktu, tt_kinerja.kd_skp, tt_kinerja.tanggal_kinerja, tt_kinerja.`status`
                                                    FROM tt_kinerja
                                                    INNER JOIN tm_skp ON tt_kinerja.kd_skp = tm_skp.kd_skp
                                                    INNER JOIN tm_pegawai ON tt_kinerja.id_user = tm_pegawai.id_user
                                                    where month(tt_kinerja.tanggal_kinerja)='$bulan_setup' and year(tt_kinerja.tanggal_kinerja)='$tahun_setup' and tt_kinerja.id_user=" . $id_user . " order by tt_kinerja.tanggal_kinerja, tt_kinerja.waktu_mulai Desc");
                            while ($row = fetch_array($tt_kinerja)) {
                                $no++;
                                ?>

                                <!-- Edit Modal SKP -->
                            <div class="modal fade" id="modal-hapus-<?php echo $row['id_kinerja']; ?>" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="<?php echo $aksi . paramEncrypt('module=kinerja-pegawai&act=delete-kinerja&id=' . $row['id_kinerja'] . ''); ?>" method="POST" role="form">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel">Delete Data SKP Tahunan</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <label>Yakin Anda ingin Menghapus Data ini,klik Tombol Ya? Untuk Menghapusnya</label>
                                                    </div>                                                   
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                                <button type="submit" class="btn btn-danger" autofocus>Ya</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Tutup Edit Modal permintaan -->
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo FormatTgl('d-m-Y', $row['tanggal_kinerja']); ?></td>
                                <td><?php echo $row['nip']; ?></td>
                                <td><?php echo $row['skp'] . " (" . $row['waktu'] . " Menit)"; ?></td>
                                <td><?php echo $row['uraian'] ?></td>
                                <td><?php echo $row['waktu_mulai']; ?></td>
                                <td><?php echo $row['waktu_akhir']; ?></td>
                                <td><?php echo floor(((is_string($row['waktu_mulai']) ? strtotime($row['waktu_akhir']) : $row['waktu_akhir']) - (is_string($row['waktu_mulai']) ? strtotime($row['waktu_mulai']) : $row['waktu_mulai'])) / 60) . " Menit"; ?></td>
                                <td><?php echo $row['status']; ?></td>
                                <td><?php echo number_format(floor(((is_string($row['waktu_mulai']) ? strtotime($row['waktu_akhir']) : $row['waktu_akhir']) - (is_string($row['waktu_mulai']) ? strtotime($row['waktu_mulai']) : $row['waktu_mulai'])) / 60) / $row['waktu']); ?></td>
                                <td><?php echo FormatTgl('d/m/y H:i:s', $row['date']); ?></td>
                                <td>
                                    <!--<span data-toggle="modal" data-target="#modal-ubah-<?php echo $row['id_kinerja']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>--> 
                                    <a href="?<?php echo paramEncrypt('module=kinerja-pegawai&act=update-kinerja-pegawai&id=' . $row['id_kinerja'] . '') ?>"><button type="submit" class="btn-xs btn-warning fa fa-edit" ></button></a>
                                    <span data-toggle="modal" data-target="#modal-hapus-<?php echo $row['id_kinerja']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
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

    case "rekap-data-kinerja-pegawai":
        ?>
        <div class="box">
            <div class="box-header with-border">  
                <h3 class="box-title fa fa-list">  REKAPITULASI DATA KINERJA PEGAWAI</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>                
            </div>
            <div class="box-body">
                <a href="?<?php echo paramEncrypt('module=kinerja-pegawai&act=list-data-kinerja-pegawai'); ?>">
                    <button type="button" class="btn btn-warning fa fa-undo" title="Kembali"> Kembali                       
                    </button>
                </a>
                <br><br>
                <form action="" method="post">
                    <div class="form-group">
                        <table align="">
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
                <?php
                $bulan_post = isset($_POST['bulan']) ? $_POST['bulan'] : null;
                $tahun_post = isset($_POST['tahun']) ? $_POST['tahun'] : null;
                $tmtcek = FormatTgl("Y-m-d", $tahun_post . "-" . $bulan_post . "-01");
                if ($bulan_post == 0 OR $tahun_post == 0) {
                    ?>
                    <div class="box-header with-border">  
                        <h3 class="box-title fa fa-list"> <label>Bulan dan Tahun Tidak Sesuai</label></h3>
                        <div class="box-tools pull-right">
                        </div>                
                    </div>
                <?php } else {
                    ?>
                    <div class="box-header with-border">  
                        <h3 class="box-title fa fa-list center-block"> 
                            <label align="center">Rekapitulasi Kinerja <?php echo konversiBulanTahun($tmtcek); ?></label>
                        </h3>
                        <div class="box-tools pull-right">
                        </div>                         
                    </div>
                <?php }
                ?>
                <div class="box-body table-responsive">
                    <table id="kinerja" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No <?php echo $bulan_setup; ?></th>
                                <th>Tanggal</th>
                                <th>NIP</th>
                                <th>Nama SKP</th>
                                <th>Uraian</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                                <th>Durasi Pekerjaan</th>
                                <th>Status</th>
                                <th>Volume</th>
                                <th>Tgl. Buat</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $tt_kinerja = bukaquery("SELECT tm_pegawai.nip, tm_skp.skp, tt_kinerja.id_kinerja, tt_kinerja.uraian, tt_kinerja.waktu_mulai, tt_kinerja.waktu_akhir, tt_kinerja.date, tm_skp.waktu, tt_kinerja.kd_skp, tt_kinerja.tanggal_kinerja, tt_kinerja.`status`, tt_kinerja.validasi 
                                                    FROM tt_kinerja
                                                    INNER JOIN tm_skp ON tt_kinerja.kd_skp = tm_skp.kd_skp
                                                    INNER JOIN tm_pegawai ON tt_kinerja.id_user = tm_pegawai.id_user
                                                    where month(tt_kinerja.tanggal_kinerja)=" . FormatTgl('m', $tmtcek) . " and year(tt_kinerja.tanggal_kinerja)=" . FormatTgl('Y', $tmtcek) . " and tt_kinerja.id_user=" . $id_user . " order by tt_kinerja.tanggal_kinerja, tt_kinerja.waktu_mulai Desc");
                            while ($row = fetch_array($tt_kinerja)) {
                                $no++;
                                ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo FormatTgl('d-m-Y', $row['tanggal_kinerja']); ?></td>
                                    <td><?php echo $row['nip']; ?></td>
                                    <td><?php echo $row['skp'] . " (" . $row['waktu'] . " Menit)"; ?></td>
                                    <td><?php echo $row['uraian'] ?></td>
                                    <td><?php echo $row['waktu_mulai']; ?></td>
                                    <td><?php echo $row['waktu_akhir']; ?></td>
                                    <td><?php echo floor(((is_string($row['waktu_mulai']) ? strtotime($row['waktu_akhir']) : $row['waktu_akhir']) - (is_string($row['waktu_mulai']) ? strtotime($row['waktu_mulai']) : $row['waktu_mulai'])) / 60) . " Menit"; ?></td>
                                    <td><?php echo $row['status']; ?></td>
                                    <td><?php echo number_format(floor(((is_string($row['waktu_mulai']) ? strtotime($row['waktu_akhir']) : $row['waktu_akhir']) - (is_string($row['waktu_mulai']) ? strtotime($row['waktu_mulai']) : $row['waktu_mulai'])) / 60) / $row['waktu']); ?></td>
                                    <td><?php echo FormatTgl('d/m/y H:i:s', $row['date']); ?></td>
                                    <td>
                                        <?php if ($row['validasi'] == 'T') { ?>                                            
                                            <i title="Tidak di Validasi" class="fa fa-thumbs-o-down"></i>
                                        <?php } else { ?>
                                            <i title="Tervalidasi" class="fa fa-thumbs-o-up"></i>
                                        <?php } ?>
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

    case"update-kinerja-pegawai":
        $id = isset($url['id']) ? $url['id'] : null;
        $tt_kinerja = fetch_array(bukaquery("SELECT tm_pegawai.nip, tm_skp.skp, tt_kinerja.id_kinerja, tt_kinerja.uraian, tt_kinerja.waktu_mulai, tt_kinerja.waktu_akhir, tt_kinerja.date, tm_skp.waktu, tt_kinerja.kd_skp, tt_kinerja.tanggal_kinerja, tt_kinerja.`status`
                                                    FROM tt_kinerja
                                                    INNER JOIN tm_skp ON tt_kinerja.kd_skp = tm_skp.kd_skp
                                                    INNER JOIN tm_pegawai ON tt_kinerja.id_user = tm_pegawai.id_user
                                                    where tt_kinerja.id_kinerja='$id'"));
        $tanggal = date('m/d/Y', strtotime($tt_kinerja['tanggal_kontrak']));
        ?>
        <div class="box">
            <div class="box-header with-border">  
                <h3 class="box-title fa fa-edit">  UPDATE DATA KINERJA PEGAWAI</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>                
            </div>
            <div class="box-body">  
                <form action="<?php echo $aksi . paramEncrypt('module=kinerja-pegawai&act=update-kinerja-utama&id=' . $tt_kinerja['id_kinerja'] . ''); ?>" method="POST" role="form">
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="text" class="form-control" value="<?php echo FormatTgl('m/d/Y', $tt_kinerja['tanggal_kinerja']); ?>" name="tanggal_kinerja" readonly="" required="">
                        <label>Pilih SKP</label>
                        <select class="form-control select2" name="kd_skp"  data-placeholder="-Pilih SKP-" style="width: 100%;" required>
                            <option selected="selected" value="">-Pilih SKP-</option>
                            <?php
                            if ($tt_kinerja['status'] == 'Utama') {
                                $tm_skptahunan = bukaquery("SELECT tt_skptahunan.kd_skp, tm_skp.skp, tm_skp.waktu
                                                                                        FROM tt_skptahunan INNER JOIN tm_skp ON tt_skptahunan.kd_skp = tm_skp.kd_skp where tt_skptahunan.id_user=" . $id_user . " ");
                                while ($skp = fetch_array($tm_skptahunan)) {
                                    if ($tt_kinerja['kd_skp'] == $skp['kd_skp']) {
                                        echo"<option value=" . $skp['kd_skp'] . " selected=" . $tt_kinerja['kd_skp'] . ">" . $skp['skp'] . " [" . $skp['waktu'] . " Menit]</option>";
                                    } else {
                                        echo"<option value=" . $skp['kd_skp'] . ">" . $skp['skp'] . " [" . $skp['waktu'] . " Menit]</option>";
                                    }
                                }
                            } else {
                                $tm_skp = bukaquery("select * from tm_skp ");
                                while ($skp = fetch_array($tm_skp)) {
                                    if ($tt_kinerja['kd_skp'] == $skp['kd_skp']) {
                                        echo"<option value=" . $skp['kd_skp'] . " selected=" . $tt_kinerja['kd_skp'] . ">" . $skp['skp'] . " [" . $skp['waktu'] . " Menit]</option>";
                                    } else {
                                        echo"<option value=" . $skp['kd_skp'] . ">" . $skp['skp'] . " [" . $skp['waktu'] . " Menit]</option>";
                                    }
                                }
                            }
                            ?>               
                        </select>
                        <label>uraian</label>
                        <textarea class="form-control" name="uraian" rows="5"><?php echo $tt_kinerja['uraian']; ?></textarea>
                        <label>Jam Mulai</label>
                        <div class="input-group">
                            <input type="text"  class="form-control" name="waktu_mulai" value="<?php echo $tt_kinerja['waktu_mulai'] ?>" data-inputmask='"mask": "99:99:99"' data-mask required autofocus>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                        <label>Jam Selesai </label>
                        <div class="input-group">
                            <input type="text"  class="form-control"  name="waktu_akhir" value="<?php echo $tt_kinerja['waktu_akhir'] ?>" data-inputmask='"mask": "99:99:99"' data-mask required>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a href="?<?php echo paramEncrypt('module=kinerja-pegawai&act=list-data-kinerja-pegawai') ?>"><button type="button" class="btn btn-warning fa fa-undo" > Kembali</button></a>
                        <button type="submit" class="btn btn-primary fa fa-save"> Update</button>
                    </div>
                </form>
            </div>
        </div>
        <?php
        break;
}
?>


