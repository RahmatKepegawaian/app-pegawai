<?php
$aksi = "module/simpeg/aksi-simpeg?";
$id = isset($url['id']) ? $url['id'] : null;
$dpa = isset($url['dpa']) ? $url['dpa'] : null;
switch ((isset($url['act']) ? $url['act'] : '')) {
    default :
        echo"default";
        header('location:error.php');
        break;
    case "ganti-password":
        ?>
        <div class="box">
            <div class="box-header with-border">  
                <h3 class="box-title fa fa-lock">  GANTI PASSWORD</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>                
            </div>
            <div class="box-body">  
                <div class="form-group">
                    <div class="col-xs-3 col-md-4"> 
                        <form action="<?php echo $aksi . paramEncrypt('module=spv&act=ganti-password-spv&id=' . paramDecrypt($superuser) . ''); ?>" method="post">
                            <div class="form-group has-feedback">
                                <input type="password" name="pass_lama" class="form-control" placeholder="Password Lama" required>
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <input type="password" name="pass_baru" class="form-control" placeholder="Password baru" required>
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <input type="password" name="retype_pass" class="form-control" placeholder="Retype password" required>
                                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                            </div>
                            <input type="submit" class="btn btn-warning" value="Update">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
        break;

    case "profile-update":
        ?>
        <div class="box">

            <div class="box-header with-border">  
                <h3 class="box-title fa fa-user-circle">  DATA PEGAWAI</h3> 
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div> 

            </div>
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab">BIODATA</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <div class="box-body">  
                            <?php $biodata = fetch_array(bukaquery("SELECT tm_pegawai.nik, tm_pegawai.nip, tm_pegawai.no_rek, tm_pegawai.tempat_lahir, tm_pegawai.agama, tm_pegawai.npwp,tm_pegawai.jk, 
                                            tm_pegawai.nama_pegawai, tm_pegawai.tgl_masuk, tm_pegawai.pendidikan, tm_pegawai.alamat, tm_pegawai.no_hp_wa, tm_pegawai.no_hp_sms, tm_pegawai.alamat_domisili, tm_pegawai.no_bpjs,tm_pegawai.tgl_lahir,tm_pegawai.foto,
                                            tm_unit.id_unit, tm_unit.nama_unit, tm_pegawai.status_nikah, tm_pegawai.sub_bagian,tm_pegawai.pajak, tm_pegawai.rumpun,tm_pegawai.log_finger, 
                                            tm_pegawai.bpjs_ks, tm_pegawai.bpjs_jkk,tm_pegawai.status, tm_pegawai.bpjs_ijht, tm_pegawai.bpjs_jp, tm_level.nama_level, tm_level.id_level 
                                            FROM
                                            tm_pegawai
                                            INNER JOIN tm_unit ON tm_pegawai.id_unit = tm_unit.id_unit
                                            INNER JOIN tm_user ON tm_user.id_user = tm_pegawai.id_user
                                            INNER JOIN tm_level ON tm_user.id_level = tm_level.id_level
                                            where tm_pegawai.id_user='$id_user'")); ?>                           
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-3 col-md-4"> 
                                        <label>NIK</label>
                                        <input type="text" class="form-control" value="<?php echo $biodata['nik']; ?>" readonly>
                                        <label>NAMA PEGAWAI</label>
                                        <input type="text" class="form-control" value="<?php echo $nama_pegawai; ?>" readonly>
                                        <label>ALAMAT</label>
                                        <textarea class="form-control" placeholder="Alamat" title="Alamat" readonly><?php echo $biodata['alamat']; ?></textarea>
										<label>ALAMAT DOMISILI</label>
                                        <textarea class="form-control" placeholder="Alamat Domisili" title="Alamat Domisili" readonly><?php echo $biodata['alamat_domisili']; ?></textarea>
                                        <label>No. Handphone (WA)</label>
                                        <input type="text" class="form-control " value="<?php echo $biodata['no_hp_wa']; ?>" readonly> 
                                        <label>No. Handphone (SMS)</label>
                                        <input type="text" class="form-control " value="<?php echo $biodata['no_hp_sms']; ?>" readonly> 
                                        <label>NO REKENING </label>
                                        <input type="text" class="form-control " value="<?php echo $biodata['no_rek']; ?>" readonly> 
                                        <label>NPWP</label>
                                        <input type="text" class="form-control" value="<?php echo $biodata['npwp']; ?>" readonly>
                                        <label>GAPOK</label>
                                        <input type="text" class="form-control" value="<?php echo formatDuit(GajiPokok($biodata['pendidikan'], $biodata['tgl_masuk'])); ?>" readonly>
                                        <label>GAJI BRUTO</label>
                                        <input type="text" class="form-control" value="<?php echo formatDuit(GajiBruto(GajiPokok($biodata['pendidikan'], $biodata['tgl_masuk']), NilaiStatusKawin($biodata['status_nikah']))); ?>" readonly>
                                        <br><br>   
                                        [ <span data-toggle="modal" data-target="#modal-update-dataprofil" title="Update" class="btn-xs btn-primary fa fa-edit"> Update Data Profil</span> ]
                                        [ <span data-toggle="modal" data-target="#modal-update-fotoprofil" title="Update" class="btn-xs btn-warning fa fa-edit"> Ganti Foto Profil</span> ]
                                        [ <span data-toggle="modal" data-target="#modal-ganti-password-<?php echo $id_user; ?>" title="Perubahan" class="btn-xs btn-danger fa fa-edit"> Ganti Password</span> ]
                                    </div>
                                    <div class="col-xs-8 col-md-4"> 
                                        <div class="col-xs-6">
                                            <label>TEMPAT LAHIR</label>
                                            <input type="text" class="form-control " value="<?php echo $biodata['tempat_lahir']; ?>" readonly> 
                                            <label>JENIS KELAMIN</label>
                                            <input type="text" class="form-control" value="<?php echo kelamin($biodata['jk']); ?>" readonly>
                                            <label>TANGGAL MASUK</label>
                                            <input type="text" class="form-control" value="<?php echo FormatTgl('d/m/Y', $biodata['tgl_masuk']); ?>" readonly>
                                            <br>
                                            <label>PENDIDIDIKAN</label>
                                            <input type="text" class="form-control" value="<?php echo $biodata['pendidikan']; ?>" readonly>
                                            <label>RUMPUN JABATAN</label>
                                            <input type="text" class="form-control" value="<?php echo $biodata['rumpun']; ?>" readonly>
                                            <label>STATUS NIKAH</label>
                                            <input type="text" class="form-control" value="<?php echo $biodata['status_nikah']; ?>" readonly>
                                            <label>BAGIAN/UNIT</label>
                                            <input type="text" class="form-control" value="<?php echo $biodata['nama_unit']; ?>" readonly>
                                        </div>
                                        <div class="col-xs-4">
                                            <label>TANGGAL LAHIR </label>
                                            <input type="text" class="form-control " value="<?php echo FormatTgl('d/m/Y', $biodata['tgl_lahir']); ?>" readonly> 
                                            <label>AGAMA</label>
                                            <input type="text" class="form-control" value="<?php echo $biodata['agama']; ?>" readonly>
                                            <label>MASA KERJA</label>
                                            <input type="text" class="form-control" value="<?php echo MasaKerjaPenyebut($biodata['tgl_masuk']); ?>" readonly>
                                            <br>
                                        </div>
                                        <div class="col-xs-2">
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
                                            <img src="img/male.jpg" style="align:center" class="img-rounded" width="450px" height="450px" alt="" > 
                                        </div>
                                        <div class="col-xs-4">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--modal update profil-->
                            <div class="modal fade" id="modal-update-dataprofil" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title fa fa-user-circle" id="myModalLabel"> Update Data Profil</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <input type="hidden" id="id_user" name="id_user" value="<?php echo $id_user; ?>">
                                                    <label>NIK</label>
                                                    <input type="text" class="form-control" id="nik" name="nik" value="<?php echo $biodata['nik']; ?>" required>
                                                    <label>NAMA PEGAWAI</label>
                                                    <input type="text" class="form-control" id="nama_pegawai" name="nama_pegawai" value="<?php echo $nama_pegawai; ?>" required>
                                                    <label>ALAMAT</label>
                                                    <textarea class="form-control" id="alamat" name="alamat" placeholder="Alamat" title="Alamat" required><?php echo $biodata['alamat']; ?></textarea> 
                                                    <label>ALAMAT DOMISILI</label>
                                                    <textarea class="form-control" id="alamat_domisili" name="alamat_domisili" placeholder="Alamat Domisili" title="Alamat Domisili" required><?php echo $biodata['alamat_domisili']; ?></textarea> 
                                                    <label>NO. HANDPHONE (WA)</label>
                                                    <input type="number" class="form-control " id="no_hp_wa" name="no_hp_wa" value="<?php echo $biodata['no_hp_wa']; ?>"  required> 
                                                    <label>NO. HANDPHONE (SMS)</label>
                                                    <input type="number" class="form-control " id="no_hp_sms" name="no_hp_sms" value="<?php echo $biodata['no_hp_sms']; ?>"  required> 
                                                    <label>TEMPAT LAHIR</label>
                                                    <input type="text" class="form-control " id="tempat_lahir" name="tempat_lahir" value="<?php echo $biodata['tempat_lahir']; ?>"  required> 
                                                    <label>TANGGAL LAHIR (bulan/tanggal/tahun)</label>
                                                    <input type="text" class="form-control " id="tgl_lahir" name="tgl_lahir" value="<?php echo FormatTgl('m/d/Y', $biodata['tgl_lahir']); ?>" id="tanggal_lahir1"  required> 
                                                    <label>NO REKENING </label>
                                                    <input type="text" class="form-control " id="no_rek" name="no_rek" value="<?php echo $biodata['no_rek']; ?>" required> 
                                                    <label>NPWP</label>
                                                    <input type="text" class="form-control" id="npwp" name="npwp" value="<?php echo $biodata['npwp']; ?>"  required>
                                                    <label>NO BPJS</label>
                                                    <input type="text" class="form-control" id="no_bpjs" name="no_bpjs" value="<?php echo $biodata['no_bpjs']; ?>"  required>
                                                    <label>JENIS KELAMIN</label>
                                                    <?php echo UpdateEnumDropdown("tm_pegawai", "jk", $biodata['jk'], ""); ?>
                                                    <label>AGAMA</label>
                                                    <?php echo UpdateEnumDropdown("tm_pegawai", "agama", $biodata['agama'], ""); ?>
                                                </div>                                                   
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-warning" autofocus onclick="put_profile_pegawai();">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--tutup modal-->

                            <!-- modal update foto profil -->
                            <div class="modal fade" id="modal-update-fotoprofil" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title fa fa-user-circle" id="myModalLabel"> Update Foto Profil</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <input type="hidden" value="<?php echo $id_user; ?>" id="profile-update_id-user" name="profile-update_id-user">
                                                <input type="file" id="foto_profil" name="foto_profil">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-warning" onclick="put_fotoprofil_pegawai();" id="profile-update_btn-upload" name="profile-update_btn-upload">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal update foto profil -->

                            <!--modal ganti password-->
                            <div class="modal fade" id="modal-ganti-password-<?php echo $id_user; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=ganti-password&id=' . $id_user . ''); ?>" method="POST" role="form">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title fa fa-lock" id="myModalLabel"> Ganti Password</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <label>PASSWORD LAMA</label>
                                                        <input type="password" class="form-control" placeholder="Password" name="password_lama" required>
                                                        <label>PASSWORD BARU</label>     
                                                        <input type="password" class="form-control" placeholder="Password" name="password_baru" required>
                                                    </div>                                                   
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-warning" autofocus>Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!--tutup modal-->
                        </div>
                    </div>                    
                </div>
            </div>

            <!-- SIMPEG-->
            <div class="margin row">
                <div class="x_panel">
                    <!-- Riwayat Pendidikan -->
                    <div class="panel-group" id="dropdown">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h4 class="panel-title fa  fa-hospital-o">
                                    <a data-toggle="collapse" data-parent="#dropdown" href="#collapse1">
                                        SIMPEG</a>
                                </h4>
                            </div>
                            <div id="collapse1" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <div class="nav-tabs-custom">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#tab_11" data-toggle="tab"> RIWAYAT PENDIDIKAN</a></li>
                                            <li><a href="#tab_12" data-toggle="tab">DAFTAR KELUARGA</a></li>
                                            <li><a href="#tab_13" data-toggle="tab">RIWAYAT PENEMPATAN</a></li>
                                            <li><a href="#tab_14" data-toggle="tab">RIWAYAT DIKLAT</a></li>
                                            <li><a href="#tab_15" data-toggle="tab">RIWAYAT PENINGKATAN PENDIDIKAN</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab_11">
                                                <button type="button" class="btn btn-warning fa fa-plus" data-toggle="modal" data-target="#modal-add-riwayat-pendidikan">
                                                    Tambah Riwayat Pendidikan
                                                </button>
                                                <br><br>
                                                <!--Modal Add SKP -->
                                                <div class="modal fade" id="modal-add-riwayat-pendidikan">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title fa fa-university"> Tambah Riwayat Pendidikan</h4>
                                                            </div>
                                                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=simpeg&act=add-riwayat-pendidikan&id=' . $id_user . ''); ?>" method="post"> 
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label>Pendidikan</label>
                                                                        <?php echo enumDropdown("tm_riwayat_pend", "pendidikan", "", "-Pilih Pendidikan-"); ?>
                                                                        <label>Nama Sekolahan</label>
                                                                        <input type="text" class="form-control" name="nama_sekolah" placeholder="Pendidikan" required>
                                                                        <label>Jurusan</label>
                                                                        <input type="text" class="form-control" name="jurusan" placeholder="Jurusan" required>
                                                                        <label>Kota</label>
                                                                        <input type="text" class="form-control" name="kota" placeholder="kota" required>
                                                                        <label>Periode</label>
                                                                        <div class = "input-group">
                                                                            <div class = "input-group-addon">
                                                                                <i class = "fa fa-calendar"></i>
                                                                            </div>
                                                                            <input type = "text" name = "periode" class = "form-control pull-right" id = "reservation" required>
                                                                        </div>
                                                                        <label>No.Ijazah</label>
                                                                        <input type="text" class="form-control" name="no_ijazah" placeholder="No.Ijazah" required>
                                                                        <label>Tanggal Ijazah</label>
                                                                        <input type="text" class="form-control" name="tgl_ijazah" id="datepicker1" placeholder="mm/dd/yyyy" required> 
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
                                                <div class="table-responsive">
                                                    <table id="example2" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Nama</th>
                                                                <th>Pendidikan</th>
                                                                <th>Nama Sekolah</th>
                                                                <th>Jurusan</th>
                                                                <th>Periode</th>
                                                                <th>No.Ijazah</th>
                                                                <th>Tgl Ijazah</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_riwayat_pend = bukaquery($idlevel == 'LVL-000007' // tampil seluruh data pegawai apabila dia PJ
                                                            ? "SELECT
                                                                a.id_unit, a.id_user, a.nama_pegawai,
                                                                b.id_riwayat_pend, b.pendidikan, b.nama_sekolah, b.kota, b.periode, b.jurusan, b.no_ijazah, b.tgl_ijazah
                                                                FROM tm_pegawai a
                                                                    INNER JOIN tm_riwayat_pend b ON a.id_user = b.id_user
                                                                WHERE a.id_unit = '".$id_unit."'  ORDER BY a.id_unit, a.nama_pegawai, b.pendidikan"
                                                            : "SELECT 
                                                                b.id_unit, b.id_user, b.nama_pegawai,
                                                                a.id_riwayat_pend, a.pendidikan, a.nama_sekolah, a.kota, a.periode, a.jurusan, a.no_ijazah, a.tgl_ijazah
                                                                
                                                                FROM tm_riwayat_pend a
                                                                INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                                                                WHERE a.id_user='".$id_user."'
                                                                ORDER BY b.id_unit, b.nama_pegawai, a.pendidikan");

                                                            while ($row = fetch_array($sql_riwayat_pend)) {
                                                                $no++;
                                                                ?>
                                                                <!-- Edit Modal SKP -->
                                                            <div class="modal fade" id="modal-ubah-<?php echo $row['id_riwayat_pend']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=update-riwayat-pendidikan&id=' . $row['id_riwayat_pend'] . ''); ?>" method="POST" role="form">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Update Riwayat Pendidikan</h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <div class="form-group">
                                                                                        <label>Pendidikan</label>
                                                                                        <?php echo UpdateEnumDropdown("tm_riwayat_pend", "pendidikan", $row['pendidikan'], ""); ?>
                                                                                        <label>Nama Sekolah</label>
                                                                                        <input type="text" class="form-control" name="nama_sekolah" value="<?php echo $row['nama_sekolah']; ?>" placeholder="Nama Sekolah" required>   
                                                                                        <label>Jurusan</label>
                                                                                        <input type="text" class="form-control" name="jurusan" value="<?php echo $row['jurusan']; ?>" placeholder="Jurusan" required>
                                                                                        <label>Kota</label>
                                                                                        <input type="text" class="form-control" name="kota" value="<?php echo $row['kota']; ?>" placeholder="kota" required>
                                                                                        <label>Periode (Tgl/Bln/Thn - tgl/Bln/Thn)</label>
                                                                                        <div class = "input-group">
                                                                                            <div class = "input-group-addon">
                                                                                                <i class = "fa fa-calendar"></i>
                                                                                            </div>
                                                                                            <input type = "text" name = "periode" value="<?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?>" class = "form-control pull-right" data-inputmask='"mask": "99-99-9999 - 99-99-9999","placeholder":"dd-mm-yyyy - dd-mm-yyyy"' data-mask required>
                                                                                        </div>
                                                                                        <label>No. Ijazah</label>
                                                                                        <input type="text" class="form-control" name="no_ijazah" value="<?php echo $row['no_ijazah']; ?>" placeholder="No Ijazah" required> 
                                                                                        <label>Tanggal Ijazah (Tgl/Bln/Thn)</label>
                                                                                        <input type="text" class="form-control" name="tgl_ijazah" value="<?php echo FormatTgl('d-m-Y', $row['tgl_ijazah']) ?>" data-inputmask='"mask": "99-99-9999","placeholder":"dd-mm-yyyy"' data-mask required> 
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
                                                            <div class="modal fade" id="modal-hapus-<?php echo $row['id_riwayat_pend']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=delete-riwayat-pendidikan&id=' . $row['id_riwayat_pend'] . ''); ?>" method="POST" role="form">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Delete Data master SKP</h4>
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
                                                                <td><?php echo $row['nama_pegawai'] ?></td>
                                                                <td><?php echo $row['pendidikan'] ?></td>
                                                                <td><?php echo $row['nama_sekolah']; ?></td>
                                                                <td><?php echo $row['jurusan']; ?></td>
                                                                <td><?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?></td>
                                                                <td><?php echo $row['no_ijazah']; ?> </td>
                                                                <td><?php echo FormatTgl('d-m-Y', $row['tgl_ijazah']); ?></td>
                                                                <td>
                                                                    <span data-toggle="modal" data-target="#modal-ubah-<?php echo $row['id_riwayat_pend']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                                                    <span data-toggle="modal" data-target="#modal-hapus-<?php echo $row['id_riwayat_pend']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
                                                                </td>
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_12">
                                                <button type="button" class="btn btn-info fa fa-plus" data-toggle="modal" data-target="#modal-add-keluarga">
                                                    Tambah Keluarga
                                                </button>
                                                <br><br>
                                                <!--Modal Add SKP -->
                                                <div class="modal fade" id="modal-add-keluarga">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title fa fa-users"> Tambah Keluarga</h4>
                                                            </div>
                                                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=simpeg&act=add-keluarga&id=' . $id_user . ''); ?>" method="post"> 
                                                                <div class="modal-body">
                                                                    <div class="form-group">       
                                                                        <label>NIK</label>
                                                                        <input type="text" class="form-control" name="nik" placeholder="NIK" required>
                                                                        <label>Nama Keluarga</label>
                                                                        <input type="text" class="form-control" name="nama_keluarga" placeholder="Nama" required>
                                                                        <label>Hubungan</label>
                                                                        <?php echo enumDropdown("tm_keluarga", "hubungan", "", "-Pilih Hubungan-"); ?>
                                                                        <label>Tanggal lahir</label>
                                                                        <input type="text" class="form-control" name="tanggal_lahir" id="datepicker4" placeholder="mm/dd/yyyy" required> 
                                                                        <label>Jenis Kelamin</label>
                                                                        <?php echo enumDropdown("tm_keluarga", "jk", "", "-Pilih Jenis Kelamin-"); ?>
                                                                        <label>Pendidikan</label>
                                                                        <?php echo enumDropdown("tm_keluarga", "pendidikan", "", "-Pilih Pendidikan-"); ?>                                                        
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
                                                <div class="table-responsive">
                                                    <table id="example3" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>NIK</th>
                                                                <th>Nama</th>
                                                                <th>Hubungan</th>
                                                                <th>Tanggal Lahir</th>
                                                                <th>Umur</th>
                                                                <th>Jenis Kelamin</th>
                                                                <th>Pendidikan</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_keluarga = bukaquery("SELECT * FROM tm_keluarga where id_user='$id_user'");
                                                            while ($row = fetch_array($sql_keluarga)) {
                                                                $no++;
                                                                ?>
                                                                <!-- Edit Modal SKP -->
                                                            <div class="modal fade" id="modal-ubah-keluarga-<?php echo $row['id_fams']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=update-keluarga&id=' . $row['id_fams'] . ''); ?>" method="POST" role="form">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Update Keuarga</h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <div class="form-group">
                                                                                        <label>NIK</label>
                                                                                        <input type="text" class="form-control" value="<?php echo $row['nik']; ?>" name="nik" placeholder="NIK" required>
                                                                                        <label>Nama Keluarga</label>
                                                                                        <input type="text" class="form-control" value="<?php echo $row['nama_keluarga']; ?>" name="nama_keluarga" placeholder="Nama" required>
                                                                                        <label>Hubungan</label>
                                                                                        <?php echo UpdateEnumDropdown("tm_keluarga", "hubungan", $row['hubungan'], ""); ?>
                                                                                        <label>Tanggal lahir</label>
                                                                                        <input type="text" class="form-control" value="<?php echo FormatTgl('d-m-Y', $row['tgl_lahir']); ?>" name="tgl_lahir" data-inputmask='"mask": "99-99-9999","placeholder":"dd-mm-yyyy"' data-mask required> 
                                                                                        <label>Jenis Kelamin</label>
                                                                                        <?php echo UpdateEnumDropdown("tm_keluarga", "jk", $row['jk'], ""); ?>
                                                                                        <label>Pendidikan</label>
                                                                                        <?php echo UpdateEnumDropdown("tm_keluarga", "pendidikan", $row['pendidikan'], ""); ?>     
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
                                                            <div class="modal fade" id="modal-hapus-keluarga-<?php echo $row['id_fams']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=delete-keluarga&id=' . $row['id_fams'] . ''); ?>" method="POST" role="form">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Delete Data master SKP</h4>
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
                                                                <td><?php echo $row['nik'] ?></td>
                                                                <td><?php echo $row['nama_keluarga']; ?></td>
                                                                <td><?php echo $row['hubungan']; ?></td>
                                                                <td><?php echo FormatTgl('d-m-Y', $row['tgl_lahir']); ?></td>
                                                                <td><?php echo MasaKerjaPenyebut($row['tgl_lahir']); ?></td>                                            
                                                                <td><?php echo kelamin($row['jk']); ?></td>
                                                                <td><?php echo $row['pendidikan']; ?> </td>                                            
                                                                <td>
                                                                    <span data-toggle="modal" data-target="#modal-ubah-keluarga-<?php echo $row['id_fams']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                                                    <span data-toggle="modal" data-target="#modal-hapus-keluarga-<?php echo $row['id_fams']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
                                                                </td>
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_13">
                                                <button type="button" class="btn btn-warning fa fa-plus" data-toggle="modal" data-target="#modal-add-riwayat-penempatan">
                                                    Tambah Riwayat Penempatan
                                                </button>
                                                <br><br>
                                                <!--Modal Add SKP -->
                                                <div class="modal fade" id="modal-add-riwayat-penempatan">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title fa fa-history"> Tambah Riwayat Penempatan</h4>
                                                            </div>
                                                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=simpeg&act=add-riwayat-penempatan&id=' . $id_user . ''); ?>" method="post"> 
                                                                <div class="modal-body">
                                                                    <div class="form-group">       
                                                                        <label>No SK</label>
                                                                        <input type="text" class="form-control" name="no_sk" placeholder="No SK" required>
                                                                        <label>Unit/Bagian</label>
                                                                        <select class="form-control select2" name="id_unit"  data-placeholder="-Pilih Unit/Bagian-" style="width: 100%;" required>
                                                                            <option selected="selected" value="">-Pilih Unit/Bagian-</option>
                                                                            <?php
                                                                            $tm_unit = bukaquery("select tm_unit.id_unit,tm_unit.nama_unit from tm_unit");
                                                                            while ($row = fetch_array($tm_unit)) {
                                                                                echo"<option value=" . $row['id_unit'] . ">" . $row['nama_unit'] . "</option>";
                                                                            }
                                                                            ?>               
                                                                        </select>
                                                                        <label>Periode</label>
                                                                        <div class = "input-group">
                                                                            <div class = "input-group-addon">
                                                                                <i class = "fa fa-calendar"></i>
                                                                            </div>
                                                                            <input type = "text" name = "periode" class = "form-control pull-right" id = "reservation1" required>
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
                                                <div class="table-responsive">
                                                    <table id="example4" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>No.SK</th>
                                                                <th>Nama Unit/Bagian</th>
                                                                <th>Periode</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_riwayat_penempatan = bukaquery("SELECT tm_riwayat_penempatan.id_riwayat_penempatan, tm_riwayat_penempatan.no_sk, tm_riwayat_penempatan.periode, tm_riwayat_penempatan.id_unit, tm_unit.nama_unit "
                                                                    . "FROM tm_riwayat_penempatan "
                                                                    . "inner join tm_unit on tm_unit.id_unit=tm_riwayat_penempatan.id_unit "
                                                                    . "where id_user='$id_user'");
                                                            while ($row = fetch_array($sql_riwayat_penempatan)) {
                                                                $no++;
                                                                ?>
                                                                <!-- Edit Modal SKP -->
                                                            <div class="modal fade" id="modal-ubah-riwayat-penempatan-<?php echo $row['id_riwayat_penempatan']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=update-riwayat-penempatan&id=' . $row['id_riwayat_penempatan'] . ''); ?>" method="POST" role="form">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Update Riwayat Penempatan</h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <div class="form-group">
                                                                                        <label>No SK</label>
                                                                                        <input type="text" class="form-control" value="<?php echo $row['no_sk']; ?>" name="no_sk" placeholder="No SK" required>
                                                                                        <label>Nama Unit/Bagian</label>
                                                                                        <select class="form-control select2" name="id_unit"  data-placeholder="-Pilih Unit/Bagian-" style="width: 100%;" required>
                                                                                            <option selected="selected" value="">-Pilih Unit/Bagian-</option>
                                                                                            <?php
                                                                                            $tm_unit = bukaquery("select tm_unit.id_unit,tm_unit.nama_unit from tm_unit ");
                                                                                            while ($unt = fetch_array($tm_unit)) {
                                                                                                if ($row['id_unit'] == $unt['id_unit']) {
                                                                                                    echo"<option value=" . $unt['id_unit'] . " selected=" . $row['id_unit'] . ">" . $unt['nama_unit'] . "</option>";
                                                                                                } else {
                                                                                                    echo"<option value=" . $unt['id_unit'] . ">" . $unt['nama_unit'] . "</option>";
                                                                                                }
                                                                                            }
                                                                                            ?>               
                                                                                        </select>
                                                                                        <label>Periode</label>
                                                                                        <div class = "input-group">
                                                                                            <div class = "input-group-addon">
                                                                                                <i class = "fa fa-calendar"></i>
                                                                                            </div>
                                                                                            <input type = "text" name = "periode" value="<?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?>" class = "form-control pull-right" data-inputmask='"mask": "99-99-9999 - 99-99-9999","placeholder":"dd-mm-yyyy - dd-mm-yyyy"' data-mask required>
                                                                                        </div>                                                                               
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
                                                            <div class="modal fade" id="modal-hapus-riwayat-penempatan-<?php echo $row['id_riwayat_penempatan']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=delete-riwayat-penempatan&id=' . $row['id_riwayat_penempatan'] . ''); ?>" method="POST" role="form">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Delete Data master SKP</h4>
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
                                                                <td><?php echo $row['no_sk']; ?></td>
                                                                <td><?php echo $row['nama_unit']; ?></td>
                                                                <td><?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?></td>                                          
                                                                <td>
                                                                    <span data-toggle="modal" data-target="#modal-ubah-riwayat-penempatan-<?php echo $row['id_riwayat_penempatan']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                                                    <span data-toggle="modal" data-target="#modal-hapus-riwayat-penempatan-<?php echo $row['id_riwayat_penempatan']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
                                                                </td>
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>  
                                            </div>
                                            <div class="tab-pane" id="tab_14">
                                                <button type="button" class="btn btn-warning fa fa-plus" data-toggle="modal" data-target="#modal-add-riwayat-diklat">
                                                    Tambah Riwayat Diklat
                                                </button>
                                                <br><br>
                                                <!--Modal Add SKP -->
                                                <div class="modal fade" id="modal-add-riwayat-diklat">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title fa fa-history"> Tambah Riwayat Diklat</h4>
                                                            </div>
                                                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=simpeg&act=add-riwayat-diklat&id=' . $id_user . ''); ?>" method="post"> 
                                                                <div class="modal-body">
                                                                    <div class="form-group">       
                                                                        <label>Nama Pelatihan</label>
                                                                        <input type="text" class="form-control" name="nama_pelatihan" placeholder="Nama Pelatiahn" required>
                                                                        <label>Instansi Pelatihan</label>
                                                                        <input type="text" class="form-control" name="instansi_pelatihan" placeholder="Pelaksana" required>
                                                                        <label>Lokasi Pelatihan</label>
                                                                        <input type="text" class="form-control" name="lokasi" placeholder="Lokasi" required>
                                                                        <label>Alamat Pelatihan</label>
                                                                        <textarea rows="3" class="form-control" name="alamat_pelatihan" placeholder="Alamat Pelatihan" required></textarea>
                                                                        <label>Periode Pelatihan</label>
                                                                        <div class = "input-group">
                                                                            <div class = "input-group-addon">
                                                                                <i class = "fa fa-calendar"></i>
                                                                            </div>
                                                                            <input type = "text" name = "periode" class = "form-control pull-right" id = "reservation3" required>
                                                                        </div>  
                                                                        <label>Total Jam</label>
                                                                        <input type="number" class="form-control" name="total_jam" placeholder="Total Jam Pelatihan" required>
                                                                        <label>Jenis Diklat</label>
                                                                        <?php echo enumDropdown("tm_riwayat_diklat", "jenis_diklat", "", "-Pilih Jenis-"); ?>
                                                                        <label>No Sertifikat</label>
                                                                        <input type="text" class="form-control" name="no_sertifikat" placeholder="No Sertifikat" required>
                                                                        <label>Status Terakreditasi</label>
                                                                        <?php echo enumDropdown("tm_riwayat_diklat", "status_akreditasi", "", "-Pilih Status-"); ?>
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
                                                <div class="table-responsive">
                                                    <table id="example5" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Nama Pelatihan</th>
                                                                <th>Lokasi Pelatihan</th>
                                                                <th>Periode</th>
                                                                <th>Jenis Diklat</th>
                                                                <th>No Sertifikat</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_riwayat_diklat = bukaquery("SELECT * from tm_riwayat_diklat where id_user='$id_user'");
                                                            while ($row = fetch_array($sql_riwayat_diklat)) {
                                                                $no++;
                                                                ?>
                                                                <!-- Edit Modal SKP -->
                                                            <div class="modal fade" id="modal-ubah-riwayat-diklat-<?php echo $row['id_riwayat_diklat']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=update-riwayat-diklat&id=' . $row['id_riwayat_diklat'] . ''); ?>" method="POST" role="form">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Update Riwayat Diklat</h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <div class="form-group">
                                                                                        <label>Nama Pelatihan</label>
                                                                                        <input type="text" class="form-control" name="nama_pelatihan" value="<?php echo $row['nama_pelatihan']; ?>" placeholder="Nama Pelatiahn" required>
                                                                                        <label>Instansi Pelatihan</label>
                                                                                        <input type="text" class="form-control" name="instansi_pelatihan" value="<?php echo $row['instansi_pelatihan']; ?>" placeholder="Pelaksana" required>
                                                                                        <label>Lokasi Pelatihan</label>
                                                                                        <input type="text" class="form-control" name="lokasi" value="<?php echo $row['lokasi']; ?>" placeholder="Lokasi" required>
                                                                                        <label>Alamat Pelatihan</label>
                                                                                        <textarea rows="3" class="form-control" name="alamat_pelatihan" placeholder="Alamat Pelatihan" required><?php echo $row['alamat_pelatihan']; ?></textarea>
                                                                                        <label>Periode Pelatihan</label>
                                                                                        <div class = "input-group">
                                                                                            <div class = "input-group-addon">
                                                                                                <i class = "fa fa-calendar"></i>
                                                                                            </div>
                                                                                            <input type = "text" name = "periode" value="<?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?>" class = "form-control pull-right" data-inputmask='"mask": "99-99-9999 - 99-99-9999","placeholder":"dd-mm-yyyy - dd-mm-yyyy"' data-mask required>
                                                                                        </div>  
                                                                                        <label>Total Jam</label>
                                                                                        <input type="number" class="form-control" name="total_jam" value="<?php echo $row['total_jam']; ?>" placeholder="Total Jam Pelatihan" required>
                                                                                        <label>Jenis Diklat</label>
                                                                                        <?php echo UpdateEnumDropdown("tm_riwayat_diklat", "jenis_diklat", $row['jenis_diklat'], ""); ?>
                                                                                        <label>No Sertifikat</label>
                                                                                        <input type="text" class="form-control" name="no_sertifikat" value="<?php echo $row['no_sertifikat']; ?>" placeholder="No Sertifikat" required>
                                                                                        <label>Status Terakreditasi</label>
                                                                                        <?php echo UpdateEnumDropdown("tm_riwayat_diklat", "status_akreditasi", $row['status_akreditasi'], ""); ?>                                                                               
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
                                                            <div class="modal fade" id="modal-hapus-riwayat-diklat-<?php echo $row['id_riwayat_diklat']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=delete-riwayat-diklat&id=' . $row['id_riwayat_diklat'] . ''); ?>" method="POST" role="form">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Delete Data master SKP</h4>
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
                                                                <td><?php echo $row['nama_pelatihan'] . " <p>(" . $row['instansi_pelatihan'] . ")</p>"; ?></td>
                                                                <td><?php echo $row['lokasi'] . " <p>(Alamat : " . $row['alamat_pelatihan'] . ")</p>"; ?></td>
                                                                <td><?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))) . " (" . $row['total_jam'] . " Jam)"; ?></td>
                                                                <td><?php echo $row['jenis_diklat']; ?></td>
                                                                <td><?php echo $row['no_sertifikat'] . " <p>Status Terakreditasi (" . $row['status_akreditasi'] . ")</p>"; ?></td>
                                                                <td>
                                                                    <span data-toggle="modal" data-target="#modal-ubah-riwayat-diklat-<?php echo $row['id_riwayat_diklat']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                                                    <?php if ($row['config'] == 1) { ?>
                                                                        <span data-toggle="modal" data-target="#modal-hapus-riwayat-diklat-<?php echo $row['id_riwayat_diklat']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
                                                                        <?php
                                                                    } else {
                                                                        
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>  
                                            </div>
                                            <div class="tab-pane" id="tab_15">                                                
                                                <!-- /.box-header -->
                                                <div class="table-responsive">
                                                    <table id="example6" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>No Izin Belajar</th>
                                                                <th>Nama Akademik/Univ</th>
                                                                <th>Jenis Peningkatan</th>
                                                                <th>Peningkatan</th>
                                                                <th>Akreditasi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_izin_belajar = bukaquery("SELECT * from tm_izin_belajar where id_user='$id_user'");
                                                            while ($row = fetch_array($sql_izin_belajar)) {
                                                                $no++;
                                                                ?>                                                                
                                                                <tr>
                                                                    <td><?php echo $no; ?></td>
                                                                    <td><?php echo $row['no_izin']; ?></td>
                                                                    <td><?php echo $row['nama_univ'] . " <p> Alamat : " . $row['alamat_univ'] . "</p>"; ?></td>
                                                                    <td><?php echo $row['jenis_peningkatan']; ?></td>
                                                                    <td><?php echo "Dari " . $row['pendidikan_sebelum'] . " Ke " . $row['pendidikan_sesudah'] . " " . $row['jurusan']; ?></td>
                                                                    <td><?php echo $row['akreditasi']; ?></td>
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
                        </div>
                    </div>
                </div>
                <!-- upload file-->
                <div class="x_panel">
                    <!-- Riwayat Pendidikan -->
                    <div class="panel-group" id="dropdown">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h4 class="panel-title fa  fa-upload">
                                    <a data-toggle="collapse" data-parent="#dropdown" href="#collapse2">
                                        UPLOAD FILE</a>
                                </h4>
                            </div>
                            <div id="collapse2" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <div class="nav-tabs-custom">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#tab_22" data-toggle="tab">KTP</a></li>
                                            <li><a href="#tab_23" data-toggle="tab">KK</a></li>
                                            <li><a href="#tab_24" data-toggle="tab">IJAZAH</a></li>
                                            <li><a href="#tab_28" data-toggle="tab">CV</a></li>
                                            <li><a href="#tab_25" data-toggle="tab">STR</a></li>
                                            <li><a href="#tab_16" data-toggle="tab">SIP</a></li>
                                            <li><a href="#tab_26" data-toggle="tab">SPK</a></li>
                                            <li><a href="#tab_27" data-toggle="tab">RKK</a></li>
                                            <li><a href="#tab_17" data-toggle="tab">ACLS</a></li>
                                            <li><a href="#tab_18" data-toggle="tab">ATLS</a></li>
                                            <li><a href="#tab_19" data-toggle="tab">BTCLS</a></li>
                                            <li><a href="#tab_20" data-toggle="tab">APN</a></li>
                                            <li><a href="#tab_21" data-toggle="tab">PHELEBOTOMY</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane" id="tab_16">
                                                <button type="button" class="btn btn-warning fa fa-plus" data-toggle="modal" data-target="#modal-add-riwayat-sip">
                                                    Tambah Riwayat SIP
                                                </button>
                                                <br><br>
                                                <!--Modal Add SKP -->
                                                <div class="modal fade" id="modal-add-riwayat-sip">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title fa fa-university"> Tambah Riwayat SIP</h4>
                                                            </div>
                                                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=simpeg&act=add-riwayat-sip&id=' . $id_user . ''); ?>" method="post" enctype="multipart/form-data"> 
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label>No SIP</label>
                                                                        <input type="text" class="form-control" name="no_sip" placeholder="No SIP" required>
                                                                        <label>Periode SIP</label>
                                                                        <div class = "input-group">
                                                                            <div class = "input-group-addon">
                                                                                <i class = "fa fa-calendar"></i>
                                                                            </div>
                                                                            <input type = "text" name = "periode" class = "form-control pull-right" data-inputmask='"mask": "99-99-9999 - 99-99-9999","placeholder":"dd-mm-yyyy - dd-mm-yyyy"' data-mask required>
                                                                        </div>
                                                                        <label>UPLOAD FILE (.png, .jpg, .pdf)</label>
                                                                        <input type="file" class="form-control" name="file"  >
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
                                                <div class="table-responsive">
                                                    <table id="example7" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No SIP</th>
                                                                <th>Nama</th>
                                                                <th>Periode SIP</th>
                                                                <th>File</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_riwayat_sip = bukaquery($idlevel == 'LVL-000007' // tampil seluruh data pegawai apabila dia PJ
                                                            ? "SELECT
                                                                a.id_unit, a.id_user, a.nama_pegawai,
                                                                b.id_sip, b.no_sip, b.periode, b.file_sip
                                                                FROM tm_pegawai a
                                                                    INNER JOIN tm_sip b ON a.id_user = b.id_user
                                                                WHERE a.id_unit = '".$id_unit."'"
                                                            : "SELECT 
                                                                b.id_unit, b.id_user, b.nama_pegawai,
                                                                a.id_sip, a.no_sip, a.periode, a.file_sip
                                                                FROM tm_sip a
                                                                INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                                                                WHERE a.id_user='$id_user'
                                                                ORDER BY a.id_sip DESC");
                                                            while ($row = fetch_array($sql_riwayat_sip)) {
                                                                $no++;
                                                                ?>
                                                                <!-- Edit Modal SKP -->
                                                            <div class="modal fade" id="modal-ubah-sip-<?php echo $row['id_sip']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=update-riwayat-sip&id=' . $row['id_sip'] . ''); ?>" method="POST" role="form" enctype="multipart/form-data">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Update Riwayat SIP</h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <div class="form-group">
                                                                                        <label>No SIP</label>
                                                                                        <input type="text" class="form-control" name="no_sip" value="<?php echo $row['no_sip']; ?>" placeholder="No SIP" required>
                                                                                        <label>Periode SIP</label>
                                                                                        <div class = "input-group">
                                                                                            <div class = "input-group-addon">
                                                                                                <i class = "fa fa-calendar"></i>
                                                                                            </div>
                                                                                            <input type = "text" name = "periode" value="<?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?>" class = "form-control pull-right" data-inputmask='"mask": "99-99-9999 - 99-99-9999","placeholder":"dd-mm-yyyy - dd-mm-yyyy"' data-mask required>
                                                                                        </div>
                                                                                        <label>UPLOAD FILE (.png, .jpg, .pdf)</label>
                                                                                        <input type="file" class="form-control" name="file" >
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
                                                            <div class="modal fade" id="modal-hapus-sip-<?php echo $row['id_sip']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=delete-riwayat-sip&id=' . $row['id_sip'] . ''); ?>" method="POST" role="form">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Delete Data SIP</h4>
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
                                                                <td><?php echo $row['no_sip'] ?></td>
                                                                <td><?php echo $row['nama_pegawai'] ?></td>
                                                                <td><?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?></td>
                                                                <td><a href="simpeg/<?php echo $row['file_sip']; ?>" target="_blank"><?php echo $row['file_sip']; ?></a></td>
                                                                <td>
                                                                    <span data-toggle="modal" data-target="#modal-ubah-sip-<?php echo $row['id_sip']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                                                    <span data-toggle="modal" data-target="#modal-hapus-sip-<?php echo $row['id_sip']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
                                                                </td>
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_17">
                                                <button type="button" class="btn btn-warning fa fa-plus" data-toggle="modal" data-target="#modal-add-riwayat-acls">
                                                    Tambah Riwayat ACLS
                                                </button>
                                                <br><br>
                                                <!--Modal Add SKP -->
                                                <div class="modal fade" id="modal-add-riwayat-acls">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title fa fa-university"> Tambah Riwayat ACLS</h4>
                                                            </div>
                                                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=simpeg&act=add-riwayat-acls&id=' . $id_user . ''); ?>" method="post" enctype="multipart/form-data"> 
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label>No ACLS</label>
                                                                        <input type="text" class="form-control" name="no_acls" placeholder="No SIP" required>
                                                                        <label>Periode ACLS</label>
                                                                        <div class = "input-group">
                                                                            <div class = "input-group-addon">
                                                                                <i class = "fa fa-calendar"></i>
                                                                            </div>
                                                                            <input type = "text" name = "periode" class = "form-control pull-right" data-inputmask='"mask": "99-99-9999 - 99-99-9999","placeholder":"dd-mm-yyyy - dd-mm-yyyy"' data-mask required>
                                                                        </div>
                                                                        <label>UPLOAD FILE </label>
                                                                        <input type="file" class="form-control" name="file" >
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
                                                <div class="table-responsive">
                                                    <table id="example8" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No ACLS</th>
                                                                <th>Nama</th>
                                                                <th>Periode ACLS</th>
                                                                <th>File</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            
                                                            $sql_riwayat_acls = bukaquery($idlevel == 'LVL-000007' // tampil seluruh data pegawai apabila dia PJ
                                                            ? "SELECT
                                                                a.id_unit, a.id_user, a.nama_pegawai,
                                                                b.id_acls, b.no_acls, b.periode, b.file_acls
                                                                FROM tm_pegawai a
                                                                    INNER JOIN tm_acls b ON a.id_user = b.id_user
                                                                WHERE a.id_unit = '".$id_unit."'"
                                                            : "SELECT 
                                                                b.id_unit, b.id_user, b.nama_pegawai,
                                                                a.id_acls, a.no_acls, a.periode, a.file_acls
                                                                FROM tm_acls a
                                                                INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                                                                WHERE a.id_user='$id_user'
                                                                ORDER BY a.id_acls DESC");
                                                            while ($row = fetch_array($sql_riwayat_acls)) {
                                                                $no++;
                                                                ?>
                                                                <!-- Edit Modal SKP -->
                                                            <div class="modal fade" id="modal-ubah-acls-<?php echo $row['id_acls']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=update-riwayat-acls&id=' . $row['id_acls'] . ''); ?>" method="POST" role="form" enctype="multipart/form-data">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Update Riwayat ACLS</h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <div class="form-group">
                                                                                        <label>No ACLS</label>
                                                                                        <input type="text" class="form-control" name="no_acls" value="<?php echo $row['no_acls']; ?>" placeholder="No SIP" required>
                                                                                        <label>Periode ACLS</label>
                                                                                        <div class = "input-group">
                                                                                            <div class = "input-group-addon">
                                                                                                <i class = "fa fa-calendar"></i>
                                                                                            </div>
                                                                                            <input type = "text" name = "periode" value="<?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?>" class = "form-control pull-right" data-inputmask='"mask": "99-99-9999 - 99-99-9999","placeholder":"dd-mm-yyyy - dd-mm-yyyy"' data-mask required>
                                                                                        </div>
                                                                                        <label>UPLOAD FILE </label>
                                                                                        <input type="file" class="form-control" name="file" >
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
                                                            <div class="modal fade" id="modal-hapus-acls-<?php echo $row['id_acls']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=delete-riwayat-acls&id=' . $row['id_acls'] . ''); ?>" method="POST" role="form" enctype="multipart/form-data">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Delete Data ACLS</h4>
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
                                                                <td><?php echo $row['no_acls'] ?></td>
                                                                <td><?php echo $row['nama_pegawai'] ?></td>
                                                                <td><?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?></td>
                                                                <td><a href="simpeg/<?php echo $row['file_acls']; ?>" target="_blank"><?php echo $row['file_acls']; ?></a></td>
                                                                <td>
                                                                    <span data-toggle="modal" data-target="#modal-ubah-acls-<?php echo $row['id_acls']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                                                    <span data-toggle="modal" data-target="#modal-hapus-acls-<?php echo $row['id_acls']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
                                                                </td>
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_18">
                                                <button type="button" class="btn btn-warning fa fa-plus" data-toggle="modal" data-target="#modal-add-riwayat-atls">
                                                    Tambah Riwayat ATLS
                                                </button>
                                                <br><br>
                                                <!--Modal Add SKP -->
                                                <div class="modal fade" id="modal-add-riwayat-atls">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title fa fa-university"> Tambah Riwayat ATLS</h4>
                                                            </div>
                                                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=simpeg&act=add-riwayat-atls&id=' . $id_user . ''); ?>" method="post" enctype="multipart/form-data"> 
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label>No ATLS</label>
                                                                        <input type="text" class="form-control" name="no_atls" placeholder="No SIP" required>
                                                                        <label>Periode ATLS</label>
                                                                        <div class = "input-group">
                                                                            <div class = "input-group-addon">
                                                                                <i class = "fa fa-calendar"></i>
                                                                            </div>
                                                                            <input type = "text" name = "periode" class = "form-control pull-right" data-inputmask='"mask": "99-99-9999 - 99-99-9999","placeholder":"dd-mm-yyyy - dd-mm-yyyy"' data-mask required>
                                                                        </div>
                                                                        <label>UPLOAD FILE </label>
                                                                        <input type="file" class="form-control" name="file" >
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
                                                <div class="table-responsive">
                                                    <table id="example9" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No ATLS</th>
                                                                <th>Nama</th>
                                                                <th>Periode ATLS</th>
                                                                <th>File</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_riwayat_atls = bukaquery($idlevel == 'LVL-000007' // tampil seluruh data pegawai apabila dia PJ
                                                            ? "SELECT
                                                                a.id_unit, a.id_user, a.nama_pegawai,
                                                                b.id_atls, b.no_atls, b.periode, b.file_atls
                                                                FROM tm_pegawai a
                                                                    INNER JOIN tm_atls b ON a.id_user = b.id_user
                                                                WHERE a.id_unit = '".$id_unit."'"
                                                            : "SELECT 
                                                                b.id_unit, b.id_user, b.nama_pegawai,
                                                                a.id_atls, a.no_atls, a.periode, a.file_atls
                                                                FROM tm_atls a
                                                                INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                                                                WHERE a.id_user='$id_user'
                                                                ORDER BY a.id_atls DESC");
                                                            while ($row = fetch_array($sql_riwayat_atls)) {
                                                                $no++;
                                                                ?>
                                                                <!-- Edit Modal SKP -->
                                                            <div class="modal fade" id="modal-ubah-atls-<?php echo $row['id_atls']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=update-riwayat-atls&id=' . $row['id_atls'] . ''); ?>" method="POST" role="form" enctype="multipart/form-data">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Update Riwayat ATLS</h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <div class="form-group">
                                                                                        <label>No ATLS</label>
                                                                                        <input type="text" class="form-control" name="no_atls" value="<?php echo $row['no_atls']; ?>" placeholder="No SIP" required>
                                                                                        <label>Periode ATLS</label>
                                                                                        <div class = "input-group">
                                                                                            <div class = "input-group-addon">
                                                                                                <i class = "fa fa-calendar"></i>
                                                                                            </div>
                                                                                            <input type = "text" name = "periode" value="<?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?>" class = "form-control pull-right" data-inputmask='"mask": "99-99-9999 - 99-99-9999","placeholder":"dd-mm-yyyy - dd-mm-yyyy"' data-mask required>
                                                                                        </div>
                                                                                        <label>UPLOAD FILE </label>
                                                                                        <input type="file" class="form-control" name="file" >
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
                                                            <div class="modal fade" id="modal-hapus-atls-<?php echo $row['id_atls']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=delete-riwayat-atls&id=' . $row['id_atls'] . ''); ?>" method="POST" role="form">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Delete Data ATLS</h4>
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
                                                                
                                                                <td><?php echo $row['no_atls'] ?></td>
                                                                <td><?php echo $row['nama_pegawai'] ?></td>
                                                                <td><?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?></td>
                                                                <td><a href="simpeg/<?php echo $row['file_atls']; ?>" target="_blank"><?php echo $row['file_atls']; ?></a></td>
                                                                <td>
                                                                    <span data-toggle="modal" data-target="#modal-ubah-atls-<?php echo $row['id_atls']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                                                    <span data-toggle="modal" data-target="#modal-hapus-atls-<?php echo $row['id_atls']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
                                                                </td>
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_19">
                                                <button type="button" class="btn btn-warning fa fa-plus" data-toggle="modal" data-target="#modal-add-riwayat-btcls">
                                                    Tambah Riwayat BCTLS
                                                </button>
                                                <br><br>
                                                <!--Modal Add SKP -->
                                                <div class="modal fade" id="modal-add-riwayat-btcls">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title fa fa-university"> Tambah Riwayat BTCLS</h4>
                                                            </div>
                                                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=simpeg&act=add-riwayat-btcls&id=' . $id_user . ''); ?>" method="post" enctype="multipart/form-data"> 
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label>No BTCLS</label>
                                                                        <input type="text" class="form-control" name="no_btcls" placeholder="No BTCLS" required>
                                                                        <label>Periode BTCLS</label>
                                                                        <div class = "input-group">
                                                                            <div class = "input-group-addon">
                                                                                <i class = "fa fa-calendar"></i>
                                                                            </div>
                                                                            <input type = "text" name = "periode" class = "form-control pull-right" data-inputmask='"mask": "99-99-9999 - 99-99-9999","placeholder":"dd-mm-yyyy - dd-mm-yyyy"' data-mask required>
                                                                        </div>
                                                                        <label>UPLOAD FILE </label>
                                                                        <input type="file" class="form-control" name="file" >
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
                                                <div class="table-responsive">
                                                    <table id="example10" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No BTCLS</th>
                                                                <th>Nama</th>
                                                                <th>Periode BTCLS</th>
                                                                <th>File</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_riwayat_btcls = bukaquery($idlevel == 'LVL-000007' // tampil seluruh data pegawai apabila dia PJ
                                                            ? "SELECT
                                                                a.id_unit, a.id_user, a.nama_pegawai,
                                                                b.id_btcls, b.no_btcls, b.periode, b.file_btcls
                                                                FROM tm_pegawai a
                                                                    INNER JOIN tm_btcls b ON a.id_user = b.id_user
                                                                WHERE a.id_unit = '".$id_unit."'"
                                                            : "SELECT 
                                                                b.id_unit, b.id_user, b.nama_pegawai,
                                                                a.id_btcls, a.no_btcls, a.periode, a.file_btcls
                                                                FROM tm_btcls a
                                                                INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                                                                WHERE a.id_user='$id_user'
                                                                ORDER BY a.id_btcls DESC");
                                                            while ($row = fetch_array($sql_riwayat_btcls)) {
                                                                $no++;
                                                                ?>
                                                                <!-- Edit Modal SKP -->
                                                            <div class="modal fade" id="modal-ubah-btcls-<?php echo $row['id_btcls']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=update-riwayat-btcls&id=' . $row['id_btcls'] . ''); ?>" method="POST" role="form" enctype="multipart/form-data">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Update Riwayat BTCLS</h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <div class="form-group">
                                                                                        <label>No BTCLS</label>
                                                                                        <input type="text" class="form-control" name="no_btcls" value="<?php echo $row['no_btcls']; ?>" placeholder="No SIP" required>
                                                                                        <label>Periode BTCLS</label>
                                                                                        <div class = "input-group">
                                                                                            <div class = "input-group-addon">
                                                                                                <i class = "fa fa-calendar"></i>
                                                                                            </div>
                                                                                            <input type = "text" name = "periode" value="<?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?>" class = "form-control pull-right" data-inputmask='"mask": "99-99-9999 - 99-99-9999","placeholder":"dd-mm-yyyy - dd-mm-yyyy"' data-mask required>
                                                                                        </div>
                                                                                        <label>UPLOAD FILE </label>
                                                                                        <input type="file" class="form-control" name="file" >
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
                                                            <div class="modal fade" id="modal-hapus-btcls-<?php echo $row['id_btcls']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=delete-riwayat-btcls&id=' . $row['id_btcls'] . ''); ?>" method="POST" role="form">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Delete Data BTCLS</h4>
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
                                                                <td><?php echo $row['no_btcls'] ?></td>
                                                                <td><?php echo $row['nama_pegawai']; ?></td>
                                                                <td><?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?></td>
                                                                <td><a href="simpeg/<?php echo $row['file_btcls']; ?>" target="_blank"><?php echo $row['file_btcls']; ?></a></td>
                                                                <td>
                                                                    <span data-toggle="modal" data-target="#modal-ubah-btcls-<?php echo $row['id_btcls']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                                                    <span data-toggle="modal" data-target="#modal-hapus-btcls-<?php echo $row['id_btcls']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
                                                                </td>
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_20">                                                
                                                <button type="button" class="btn btn-warning fa fa-plus" data-toggle="modal" data-target="#modal-add-riwayat-apn">
                                                    Tambah Riwayat APN
                                                </button>
                                                <br><br>
                                                <!--Modal Add SKP -->
                                                <div class="modal fade" id="modal-add-riwayat-apn">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title fa fa-university"> Tambah Riwayat APN</h4>
                                                            </div>
                                                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=simpeg&act=add-riwayat-apn&id=' . $id_user . ''); ?>" method="post" enctype="multipart/form-data"> 
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label>No APN</label>
                                                                        <input type="text" class="form-control" name="no_apn" placeholder="No APN" required>
                                                                        <label>Periode APN</label>
                                                                        <div class = "input-group">
                                                                            <div class = "input-group-addon">
                                                                                <i class = "fa fa-calendar"></i>
                                                                            </div>
                                                                            <input type = "text" name = "periode" class = "form-control pull-right" data-inputmask='"mask": "99-99-9999 - 99-99-9999","placeholder":"dd-mm-yyyy - dd-mm-yyyy"' data-mask required>
                                                                        </div>
                                                                        <label>UPLOAD FILE </label>
                                                                        <input type="file" class="form-control" name="file" >
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
                                                <div class="table-responsive">
                                                    <table id="example12" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No APN</th>
                                                                <th>Nama</th>
                                                                <th>Periode APN</th>
                                                                <th>File</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_riwayat_apn = bukaquery($idlevel == 'LVL-000007' // tampil seluruh data pegawai apabila dia PJ
                                                            ? "SELECT
                                                                a.id_unit, a.id_user, a.nama_pegawai,
                                                                b.id_apn, b.no_apn, b.periode, b.file_apn
                                                                FROM tm_pegawai a
                                                                    INNER JOIN tm_apn b ON a.id_user = b.id_user
                                                                WHERE a.id_unit = '".$id_unit."'"
                                                            : "SELECT 
                                                                b.id_unit, b.id_user, b.nama_pegawai,
                                                                a.id_apn, a.no_apn, a.periode, a.file_apn
                                                                FROM tm_apn a
                                                                INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                                                                WHERE a.id_user='$id_user'
                                                                ORDER BY a.id_apn DESC");
                                                            while ($row = fetch_array($sql_riwayat_apn)) {
                                                                $no++;
                                                                ?>
                                                                <!-- Edit Modal SKP -->
                                                            <div class="modal fade" id="modal-ubah-apn-<?php echo $row['id_apn']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=update-riwayat-apn&id=' . $row['id_apn'] . ''); ?>" method="POST" role="form" enctype="multipart/form-data">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Update Riwayat APN</h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <div class="form-group">
                                                                                        <label>No APN</label>
                                                                                        <input type="text" class="form-control" name="no_apn" value="<?php echo $row['no_apn']; ?>" placeholder="No SIP" required>
                                                                                        <label>Periode APN</label>
                                                                                        <div class = "input-group">
                                                                                            <div class = "input-group-addon">
                                                                                                <i class = "fa fa-calendar"></i>
                                                                                            </div>
                                                                                            <input type = "text" name = "periode" value="<?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?>" class = "form-control pull-right" data-inputmask='"mask": "99-99-9999 - 99-99-9999","placeholder":"dd-mm-yyyy - dd-mm-yyyy"' data-mask required>
                                                                                        </div>
                                                                                        <label>UPLOAD FILE </label>
                                                                                        <input type="file" class="form-control" name="file" >
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
                                                            <div class="modal fade" id="modal-hapus-apn-<?php echo $row['id_apn']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=delete-riwayat-apn&id=' . $row['id_apn'] . ''); ?>" method="POST" role="form">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Delete Data APN</h4>
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
                                                                <td><?php echo $row['no_apn'] ?></td>
                                                                <td><?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?></td>
                                                                <td><a href="simpeg/<?php echo $row['file_apn']; ?>" target="_blank"><?php echo $row['file_apn']; ?></a></td>
                                                                <td>
                                                                    <span data-toggle="modal" data-target="#modal-ubah-apn-<?php echo $row['id_apn']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                                                    <span data-toggle="modal" data-target="#modal-hapus-apn-<?php echo $row['id_apn']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
                                                                </td>
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_21">                                                
                                                <button type="button" class="btn btn-warning fa fa-plus" data-toggle="modal" data-target="#modal-add-riwayat-phelebethomy">
                                                    Tambah Riwayat Phelebethomy
                                                </button>
                                                <br><br>
                                                <!--Modal Add SKP -->
                                                <div class="modal fade" id="modal-add-riwayat-phelebethomy">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title fa fa-university"> Tambah Riwayat Phelebethomy</h4>
                                                            </div>
                                                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=simpeg&act=add-riwayat-phelebethomy&id=' . $id_user . ''); ?>" method="post" enctype="multipart/form-data"> 
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label>No Phelebethomy</label>
                                                                        <input type="text" class="form-control" name="no_phelebethomy" placeholder="No PHELEBETHOMY" required>
                                                                        <label>Periode Phelebethomy</label>
                                                                        <div class = "input-group">
                                                                            <div class = "input-group-addon">
                                                                                <i class = "fa fa-calendar"></i>
                                                                            </div>
                                                                            <input type = "text" name = "periode" class = "form-control pull-right" data-inputmask='"mask": "99-99-9999 - 99-99-9999","placeholder":"dd-mm-yyyy - dd-mm-yyyy"' data-mask required>
                                                                        </div>
                                                                        <label>UPLOAD FILE </label>
                                                                        <input type="file" class="form-control" name="file" >
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
                                                <div class="table-responsive">
                                                    <table id="example11" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No Phelebethomy</th>
                                                                <th>Periode Phelebethomy</th>
                                                                <th>File</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_riwayat_phelebethomy = bukaquery($idlevel == 'LVL-000007' // tampil seluruh data pegawai apabila dia PJ
                                                            ? "SELECT
                                                                a.id_unit, a.id_user, a.nama_pegawai,
                                                                b.id_phelebethomy, b.no_phelebethomy, b.periode, b.file_phelebethomy
                                                                FROM tm_pegawai a
                                                                    INNER JOIN tm_phelebethomy b ON a.id_user = b.id_user
                                                                WHERE a.id_unit = '".$id_unit."'"
                                                            : "SELECT 
                                                                b.id_unit, b.id_user, b.nama_pegawai,
                                                                a.id_phelebethomy, a.no_phelebethomy, a.periode, a.file_phelebethomy
                                                                FROM tm_phelebethomy a
                                                                INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                                                                WHERE a.id_user='$id_user'
                                                                ORDER BY a.id_phelebethomy DESC");
                                                            while ($row = fetch_array($sql_riwayat_phelebethomy)) {
                                                                $no++;
                                                                ?>
                                                                <!-- Edit Modal SKP -->
                                                            <div class="modal fade" id="modal-ubah-phelebethomy-<?php echo $row['id_phelebethomy']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=update-riwayat-phelebethomy&id=' . $row['id_phelebethomy'] . ''); ?>" method="POST" role="form" enctype="multipart/form-data">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Update Riwayat PHELEBETHOMY</h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <div class="form-group">
                                                                                        <label>No Phelebethomy</label>
                                                                                        <input type="text" class="form-control" name="no_phelebethomy" value="<?php echo $row['no_phelebethomy']; ?>" placeholder="No SIP" required>
                                                                                        <label>Periode Phelebethomy</label>
                                                                                        <div class = "input-group">
                                                                                            <div class = "input-group-addon">
                                                                                                <i class = "fa fa-calendar"></i>
                                                                                            </div>
                                                                                            <input type = "text" name = "periode" value="<?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?>" class = "form-control pull-right" data-inputmask='"mask": "99-99-9999 - 99-99-9999","placeholder":"dd-mm-yyyy - dd-mm-yyyy"' data-mask required>
                                                                                        </div>
                                                                                        <label>UPLOAD FILE </label>
                                                                                        <input type="file" class="form-control" name="file" >
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
                                                            <div class="modal fade" id="modal-hapus-phelebethomy-<?php echo $row['id_phelebethomy']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=delete-riwayat-phelebethomy&id=' . $row['id_phelebethomy'] . ''); ?>" method="POST" role="form">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Delete Data PHELEBETHOMY</h4>
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
                                                                <td><?php echo $row['no_phelebethomy'] ?></td>
                                                                <td><?= $row['nama_pegawai']; ?></td>
                                                                <td><?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?></td>
                                                                <td><a href="simpeg/<?php echo $row['file_phelebethomy']; ?>" target="_blank"><?php echo $row['file_phelebethomy']; ?></a></td>
                                                                <td>
                                                                    <span data-toggle="modal" data-target="#modal-ubah-phelebethomy-<?php echo $row['id_phelebethomy']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                                                    <span data-toggle="modal" data-target="#modal-hapus-phelebethomy-<?php echo $row['id_phelebethomy']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
                                                                </td>
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane active" id="tab_22">
                                                <button type="button" class="btn btn-success fa fa-upload" data-toggle="modal" data-target="#modal-add-ktp">
                                                    Upload KTP
                                                </button>
                                                <br><br>
                                                <!--Modal Add SKP -->
                                                <div class="modal fade" id="modal-add-ktp">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title fa fa-upload"> Upload KTP</h4>
                                                            </div>
                                                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=simpeg&act=add-ktp&id=' . $id_user . ''); ?>" method="post" enctype="multipart/form-data"> 
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label>No NIK</label>
                                                                        <input type="text" class="form-control" name="no_nik" placeholder="No NIK" required>
                                                                        <label>UPLOAD FILE </label>
                                                                        <input type="file" class="form-control" name="file" >
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
                                                <div class="table-responsive">
                                                    <table id="example13" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No NIK</th>
                                                                <th>Nama</th>
                                                                <th>File</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;

                                                            $sql_ktp = bukaquery($idlevel == 'LVL-000007' // tampil seluruh data pegawai apabila dia PJ
                                                            ? "SELECT
                                                                a.id_unit, a.id_user, a.nama_pegawai,
                                                                b.no_nik, b.file_ktp
                                                                FROM tm_pegawai a
                                                                    INNER JOIN tm_ktp b ON a.id_user = b.id_user
                                                                WHERE a.id_unit = '".$id_unit."'"
                                                            : "SELECT 
                                                                b.id_unit, b.id_user, b.nama_pegawai,
                                                                a.no_nik, a.file_ktp
                                                                FROM tm_ktp a
                                                                INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                                                                WHERE a.id_user='$id_user'
                                                                ORDER BY a.no_nik DESC");
                                                            while ($row = fetch_array($sql_ktp)) {
                                                                $no++;
                                                                ?>
                                                                <!-- Edit Modal SKP -->
                                                            <div class="modal fade" id="modal-ubah-ktp-<?php echo $row['no_nik']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=update-ktp&id=' . $row['no_nik'] . ''); ?>" method="POST" role="form" enctype="multipart/form-data">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Update KK</h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <div class="form-group">
                                                                                        <label>No NIK</label>
                                                                                        <input type="text" class="form-control" name="no_nik" value="<?php echo $row['no_nik']; ?>" placeholder="No NIK" required>
                                                                                        <label>UPLOAD FILE </label>
                                                                                        <input type="file" class="form-control" name="file" >
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
                                                            <div class="modal fade" id="modal-hapus-ktp-<?php echo $row['no_nik']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=delete-ktp&id=' . $row['no_nik'] . ''); ?>" method="POST" role="form">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Delete Data KTP</h4>
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
                                                                <td><?php echo $row['no_nik'] ?></td>
                                                                <td><?= $row['nama_pegawai']; ?></td>
                                                                <td><a href="simpeg/<?php echo $row['file_ktp']; ?>" target="_blank"><?php echo $row['file_ktp']; ?></a></td>
                                                                <td>
                                                                    <span data-toggle="modal" data-target="#modal-ubah-ktp-<?php echo $row['no_nik']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                                                    <span data-toggle="modal" data-target="#modal-hapus-ktp-<?php echo $row['no_nik']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
                                                                </td>
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_23">
                                                <button type="button" class="btn btn-success fa fa-upload" data-toggle="modal" data-target="#modal-add-kk">
                                                    Upload KK
                                                </button>
                                                <br><br>
                                                <!--Modal Add SKP -->
                                                <div class="modal fade" id="modal-add-kk">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title fa fa-upload"> Upload KK</h4>
                                                            </div>
                                                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=simpeg&act=add-kk&id=' . $id_user . ''); ?>" method="post" enctype="multipart/form-data"> 
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label>No KK</label>
                                                                        <input type="text" class="form-control" name="no_kk" placeholder="No KK" required>
                                                                        <label>UPLOAD FILE </label>
                                                                        <input type="file" class="form-control" name="file" >
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
                                                <div class="table-responsive">
                                                    <table id="example14" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No KK</th>
                                                                <th>Nama</th>
                                                                <th>File</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_kk = bukaquery($idlevel == 'LVL-000007' // tampil seluruh data pegawai apabila dia PJ
                                                            ? "SELECT
                                                                a.id_unit, a.id_user, a.nama_pegawai,
                                                                b.no_kk, b.file_kk
                                                                FROM tm_pegawai a
                                                                    INNER JOIN tm_kk b ON a.id_user = b.id_user
                                                                WHERE a.id_unit = '".$id_unit."'"
                                                            : "SELECT 
                                                                b.id_unit, b.id_user, b.nama_pegawai,
                                                                a.no_kk, a.file_kk
                                                                FROM tm_kk a
                                                                INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                                                                WHERE a.id_user='$id_user'
                                                                ORDER BY a.no_kk DESC");
                                                            while ($row = fetch_array($sql_kk)) {
                                                                $no++;
                                                                ?>
                                                                <!-- Edit Modal SKP -->
                                                            <div class="modal fade" id="modal-ubah-kk-<?php echo $row['no_kk']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=update-kk&id=' . $row['no_kk'] . ''); ?>" method="POST" role="form" enctype="multipart/form-data">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Update KK</h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <div class="form-group">
                                                                                        <label>No KK</label>
                                                                                        <input type="text" class="form-control" name="no_kk" value="<?php echo $row['no_kk']; ?>" placeholder="No NIK" required>
                                                                                        <label>UPLOAD FILE </label>
                                                                                        <input type="file" class="form-control" name="file" >
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
                                                            <div class="modal fade" id="modal-hapus-kk-<?php echo $row['no_kk']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=delete-kk&id=' . $row['no_kk'] . ''); ?>" method="POST" role="form">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Delete Data KK</h4>
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
                                                                <td><?php echo $row['no_kk'] ?></td>
                                                                <td><?= $row['nama_pegawai']; ?></td>
                                                                <td><a href="simpeg/<?php echo $row['file_kk']; ?>" target="_blank"><?php echo $row['file_kk']; ?></a></td>
                                                                <td>
                                                                    <span data-toggle="modal" data-target="#modal-ubah-kk-<?php echo $row['no_kk']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                                                    <span data-toggle="modal" data-target="#modal-hapus-kk-<?php echo $row['no_kk']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
                                                                </td>
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_24">                                                
                                                <button type="button" class="btn btn-success fa fa-upload" data-toggle="modal" data-target="#modal-add-ijazah">
                                                    Upload Ijazah
                                                </button>
                                                <br><br>
                                                <!--Modal Add SKP -->
                                                <div class="modal fade" id="modal-add-ijazah">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title fa fa-university"> Upload Ijazah</h4>
                                                            </div>
                                                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=simpeg&act=add-ijazah&id=' . $id_user . ''); ?>" method="post" enctype="multipart/form-data"> 
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label>Pilih Sekolah</label>
                                                                        <select class="form-control select2" name="id_riwayat_pend"  data-placeholder="-Pilih Sekolah-" style="width: 100%;" required>
                                                                            <option selected="selected" value="">-Pilih Sekolah-</option>
                                                                            <?php
                                                                            $tm_sekolah = bukaquery("select tm_riwayat_pend.id_riwayat_pend,tm_riwayat_pend.nama_sekolah from tm_riwayat_pend where tm_riwayat_pend.id_user='$id_user'");
                                                                            while ($row = fetch_array($tm_sekolah)) {
                                                                                echo"<option value=" . $row['id_riwayat_pend'] . ">" . $row['nama_sekolah'] . "</option>";
                                                                            }
                                                                            ?>               
                                                                        </select>
                                                                        <label>UPLOAD FILE </label>
                                                                        <input type="file" class="form-control" name="file" >
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
                                                <div class="table-responsive">
                                                    <table id="example16" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No Ijazah</th>
                                                                <th>Nama Sekolah</th>
                                                                <th>Pendidikan</th>
                                                                <th>File</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_ijazah = bukaquery("SELECT tm_ijazah.id_riwayat_pend, tm_riwayat_pend.no_ijazah, tm_riwayat_pend.nama_sekolah, tm_riwayat_pend.pendidikan, tm_ijazah.file_ijazah
                                                                        FROM tm_ijazah
                                                                        INNER JOIN tm_riwayat_pend ON tm_ijazah.id_riwayat_pend=tm_riwayat_pend.id_riwayat_pend where tm_ijazah.id_user='$id_user'");
                                                            while ($row = fetch_array($sql_ijazah)) {
                                                                $no++;
                                                                ?>
                                                                <!-- Edit Modal SKP -->
                                                            <div class="modal fade" id="modal-ubah-ijazah-<?php echo $row['id_riwayat_pend']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=update-ijazah&id=' . $row['id_riwayat_pend'] . ''); ?>" method="POST" role="form" enctype="multipart/form-data">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Update Upload Ijazah</h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <div class="form-group">
                                                                                        <label>Nama Sekolah</label>
                                                                                        <select class="form-control select2" name="id_riwayat_pend"  data-placeholder="-Pilih Nama Sekolah-" style="width: 100%;" required>
                                                                                            <option selected="selected" value="">-Pilih Nama Sekolah-</option>
                                                                                            <?php
                                                                                            $tm_sekolah = bukaquery("select tm_riwayat_pend.id_riwayat_pend,tm_riwayat_pend.nama_sekolah from tm_riwayat_pend where tm_riwayat_pend.id_user='$id_user' ");
                                                                                            while ($unt = fetch_array($tm_sekolah)) {
                                                                                                if ($row['id_riwayat_pend'] == $unt['id_riwayat_pend']) {
                                                                                                    echo"<option value=" . $unt['id_riwayat_pend'] . " selected=" . $row['id_riwayat_pend'] . ">" . $unt['nama_sekolah'] . "</option>";
                                                                                                } else {
                                                                                                    echo"<option value=" . $unt['id_riwayat_pend'] . ">" . $unt['nama_sekolah'] . "</option>";
                                                                                                }
                                                                                            }
                                                                                            ?>               
                                                                                        </select>  
                                                                                        <label>UPLOAD FILE </label>
                                                                                        <input type="file" class="form-control" name="file" >
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
                                                            <div class="modal fade" id="modal-hapus-ijazah-<?php echo $row['id_riwayat_pend']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=delete-ijazah&id=' . $row['id_riwayat_pend'] . ''); ?>" method="POST" role="form">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Delete Data Ijazah</h4>
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
                                                                <td><?php echo $row['no_ijazah'] ?></td>
                                                                <td><?php echo $row['nama_sekolah'] ?></td>
                                                                <td><?php echo $row['pendidikan'] ?></td>
                                                                <td><a href="simpeg/<?php echo $row['file_ijazah']; ?>" target="_blank"><?php echo $row['file_ijazah']; ?></a></td>
                                                                <td>
                                                                    <span data-toggle="modal" data-target="#modal-ubah-ijazah-<?php echo $row['id_riwayat_pend']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                                                    <span data-toggle="modal" data-target="#modal-hapus-ijazah-<?php echo $row['id_riwayat_pend']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
                                                                </td>
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_25">                                                
                                                <button type="button" class="btn btn-warning fa fa-plus" data-toggle="modal" data-target="#modal-add-riwayat-str">
                                                    Tambah Riwayat STR
                                                </button>
                                                <br><br>
                                                <!--Modal Add SKP -->
                                                <div class="modal fade" id="modal-add-riwayat-str">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title fa fa-university"> Tambah Riwayat STR</h4>
                                                            </div>
                                                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=simpeg&act=add-riwayat-str&id=' . $id_user . ''); ?>" method="post" enctype="multipart/form-data"> 
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label>No STR</label>
                                                                        <input type="text" class="form-control" name="no_str" placeholder="No STR" required>
                                                                        <label>Periode STR</label>
                                                                        <div class = "input-group">
                                                                            <div class = "input-group-addon">
                                                                                <i class = "fa fa-calendar"></i>
                                                                            </div>
                                                                            <input type = "text" name = "periode" class = "form-control pull-right" data-inputmask='"mask": "99-99-9999 - 99-99-9999","placeholder":"dd-mm-yyyy - dd-mm-yyyy"' data-mask required>
                                                                        </div>
                                                                        <label>UPLOAD FILE  (.png, .jpg, .pdf)</label>
                                                                        <input type="file" class="form-control" name="file" >
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
                                                <div class="table-responsive">
                                                    <table id="example15" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No STR</th>
                                                                <th>Nama</th>
                                                                <th>Periode STR</th>
                                                                <th>File</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_riwayat_str = bukaquery($idlevel == 'LVL-000007' // tampil seluruh data pegawai apabila dia PJ
                                                            ? "SELECT
                                                                a.id_unit, a.id_user, a.nama_pegawai,
                                                                b.id_str, b.no_str, b.periode, b.file_str
                                                                FROM tm_pegawai a
                                                                    INNER JOIN tm_str b ON a.id_user = b.id_user
                                                                WHERE a.id_unit = '".$id_unit."'"
                                                            : "SELECT 
                                                                b.id_unit, b.id_user, b.nama_pegawai,
                                                                a.id_str, a.no_str, a.periode, a.file_str
                                                                FROM tm_str a
                                                                INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                                                                WHERE a.id_user='$id_user'
                                                                ORDER BY a.id_str DESC");
                                                            while ($row = fetch_array($sql_riwayat_str)) {
                                                                $no++;
                                                                ?>
                                                                <!-- Edit Modal SKP -->
                                                            <div class="modal fade" id="modal-ubah-str-<?php echo $row['id_str']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=update-riwayat-str&id=' . $row['id_str'] . ''); ?>" method="POST" role="form" enctype="multipart/form-data">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Update Riwayat STR (.png, .jpg, .pdf)</h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <div class="form-group">
                                                                                        <label>No STR</label>
                                                                                        <input type="text" class="form-control" name="no_str" value="<?php echo $row['no_str']; ?>" placeholder="No SIP" required>
                                                                                        <label>Periode STR</label>
                                                                                        <div class = "input-group">
                                                                                            <div class = "input-group-addon">
                                                                                                <i class = "fa fa-calendar"></i>
                                                                                            </div>
                                                                                            <input type = "text" name = "periode" value="<?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?>" class = "form-control pull-right" data-inputmask='"mask": "99-99-9999 - 99-99-9999","placeholder":"dd-mm-yyyy - dd-mm-yyyy"' data-mask required>
                                                                                        </div>
                                                                                        <label>UPLOAD FILE (.png, .jpg, .pdf)</label>
                                                                                        <input type="file" class="form-control" name="file" >
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
                                                            <div class="modal fade" id="modal-hapus-str-<?php echo $row['id_str']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=delete-riwayat-str&id=' . $row['id_str'] . ''); ?>" method="POST" role="form">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Delete Data STR</h4>
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
                                                                <td><?php echo $row['no_str'] ?></td>
                                                                <td><?= $row['nama_pegawai']; ?></td>
                                                                <td><?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?></td>
                                                                <td><a href="simpeg/<?php echo $row['file_str']; ?>" target="_blank"><?php echo $row['file_str']; ?></a></td>
                                                                <td>
                                                                    <span data-toggle="modal" data-target="#modal-ubah-str-<?php echo $row['id_str']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                                                    <span data-toggle="modal" data-target="#modal-hapus-str-<?php echo $row['id_str']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
                                                                </td>
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_26">
                                                <button type="button" class="btn btn-warning fa fa-plus" data-toggle="modal" data-target="#modal-add-riwayat-spk">
                                                    Tambah Riwayat SPK
                                                </button>
                                                <br><br>
                                                <!-- Modal Add SPK -->
                                                <div class="modal fade" id="modal-add-riwayat-spk">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title fa fa-university"> Tambah Riwayat SPK</h4>
                                                            </div>
                                                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=simpeg&act=add-riwayat-spk&id=' . $id_user . ''); ?>" method="post" enctype="multipart/form-data"> 
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label>No SPK</label>
                                                                        <input type="text" class="form-control" name="no_spk" placeholder="No SPK" required>
                                                                        <label>Periode SPK</label>
                                                                        <div class = "input-group">
                                                                            <div class = "input-group-addon">
                                                                                <i class = "fa fa-calendar"></i>
                                                                            </div>
                                                                            <input type = "text" name = "periode" class = "form-control pull-right" data-inputmask='"mask": "99-99-9999 - 99-99-9999","placeholder":"dd-mm-yyyy - dd-mm-yyyy"' data-mask required>
                                                                        </div>
                                                                        <label>UPLOAD FILE (.png, .jpg, .pdf)</label>
                                                                        <input type="file" class="form-control" name="file" >
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
                                                <!-- Tutup Modal SPK -->
                                                <div class="table-responsive">
                                                    <table id="example15" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No. SPK</th>
                                                                <th>Nama</th>
                                                                <th>Periode SPK</th>
                                                                <th>File</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_riwayat_spk = bukaquery($idlevel == 'LVL-000007' // tampil seluruh data pegawai apabila dia PJ
                                                            ? "SELECT
                                                                a.id_unit, a.id_user, a.nama_pegawai,
                                                                b.id_spk, b.no_spk, b.periode, b.file_spk
                                                                FROM tm_pegawai a
                                                                    INNER JOIN tm_spk b ON a.id_user = b.id_user
                                                                WHERE a.id_unit = '".$id_unit."'"
                                                            : "SELECT 
                                                                b.id_unit, b.id_user, b.nama_pegawai,
                                                                a.id_spk, a.no_spk, a.periode, a.file_spk
                                                                FROM tm_spk a
                                                                INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                                                                WHERE a.id_user='$id_user'
                                                                ORDER BY a.id_spk DESC");
                                                            while($row = fetch_array($sql_riwayat_spk)) {
                                                                $no++;
                                                                ?>
                                                                <!-- Modal ubah SPK -->
                                                                <div class="modal fade" id="modal-ubah-spk-<?= $row['id_spk']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <form action="<?= $aksi.paramEncrypt('module=simpeg&act=update-riwayat-spk&id='.$row['id_spk'].''); ?>" method="POST" role="form" enctype="multipart/form-data">
                                                                                <div class="modal-header">
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                    <h4 class="modal-title" id="myModalLabel">Update Riwayat SPK</h4>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div class="form-group">
                                                                                        <div class="form-group">
                                                                                            <label>No SPK</label>
                                                                                            <input type="text" class="form-control" name="no_spk" value="<?= $row['no_spk']; ?>" placeholder="No SPK" required>
                                                                                            <label>Periode SPK</label>
                                                                                            <div class="input-group">
                                                                                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                                                                                <input type="text" name="periode" value="<?= date('d-m-Y', strtotime(substr($row['periode'], 0, 10))).' s/d '.date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?>" class="form-control pull-right" data-inputmask='"mask": "99-99-9999 - 99-99-9999","placeholder":"dd-mm-yyyy - dd-mm-yyyy"' data-mask required>
                                                                                            </div>
                                                                                            <label>UPLOAD FILE (.png, .jpg, .pdf)</label>
                                                                                            <input type="file" class="form-control" name="file">
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
                                                                <!-- /end Modal ubah SPK -->
                                                                <!-- Modal hapus SPK -->
                                                                <div class="modal fade" id="modal-hapus-spk-<?php echo $row['id_spk']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=delete-riwayat-spk&id=' . $row['id_spk'] . ''); ?>" method="POST" role="form">
                                                                                <div class="modal-header">
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                    <h4 class="modal-title" id="myModalLabel">Delete Data SPK</h4>
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
                                                                <!-- /end Modal hapus SPK -->
                                                                <tr>
                                                                    <td><?= $row['no_spk']; ?></td>
                                                                    <td><?= $row['nama_pegawai']; ?></td>
                                                                    <td><?= date('d-m-Y', strtotime(substr($row['periode'], 0, 10))).' s/d '.date('d-m-Y', strtotime(substr($row['periode'], 13, 10)));  ?></td>
                                                                    <td><a href="simpeg/<?= $row['file_spk']; ?>" target="_blank"><?= $row['file_spk']; ?></a></td>
                                                                    <td>
                                                                        <span data-toggle="modal" data-target="#modal-ubah-spk-<?= $row['id_spk']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                                                        <span data-toggle="modal" data-target="#modal-hapus-spk-<?= $row['id_spk']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_27">                                                
                                                <button type="button" class="btn btn-warning fa fa-plus" data-toggle="modal" data-target="#modal-add-riwayat-rkk">
                                                    Tambah Riwayat RKK
                                                </button>
                                                <br><br>
                                                <!--Modal Add RKK -->
                                                <div class="modal fade" id="modal-add-riwayat-rkk">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title fa fa-university"> Tambah Riwayat RKK</h4>
                                                            </div>
                                                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=simpeg&act=add-riwayat-rkk&id=' . $id_user . ''); ?>" method="post" enctype="multipart/form-data"> 
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label>No RKK</label>
                                                                        <input type="text" class="form-control" name="no_rkk" placeholder="No RKK" required>
                                                                        <label>Periode RKK</label>
                                                                        <div class = "input-group">
                                                                            <div class = "input-group-addon">
                                                                                <i class = "fa fa-calendar"></i>
                                                                            </div>
                                                                            <input type = "text" name = "periode" class = "form-control pull-right" data-inputmask='"mask": "99-99-9999 - 99-99-9999","placeholder":"dd-mm-yyyy - dd-mm-yyyy"' data-mask required>
                                                                        </div>
                                                                        <label>UPLOAD FILE (.png, .jpg, .pdf)</label>
                                                                        <input type="file" class="form-control" name="file" >
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
                                                <!-- Tutup Modal RKK -->
                                                <!-- /.box-header -->
                                                <div class="table-responsive">
                                                    <table id="example15" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No RKK</th>
                                                                <th>Nama</th>
                                                                <th>Periode RKK</th>
                                                                <th>File</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_riwayat_rkk = bukaquery($idlevel == 'LVL-000007' // tampil seluruh data pegawai apabila dia PJ
                                                            ? "SELECT
                                                                a.id_unit, a.id_user, a.nama_pegawai,
                                                                b.id_rkk, b.no_rkk, b.periode, b.file_rkk
                                                                FROM tm_pegawai a
                                                                    INNER JOIN tm_rkk b ON a.id_user = b.id_user
                                                                WHERE a.id_unit = '".$id_unit."'"
                                                            : "SELECT 
                                                                b.id_unit, b.id_user, b.nama_pegawai,
                                                                a.id_rkk, a.no_rkk, a.periode, a.file_rkk
                                                                FROM tm_rkk a
                                                                INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                                                                WHERE a.id_user='$id_user'
                                                                ORDER BY a.id_rkk DESC");
                                                            while ($row = fetch_array($sql_riwayat_rkk)) {
                                                                $no++;
                                                                ?>
                                                                <!-- Edit Modal RKK -->
                                                                <div class="modal fade" id="modal-ubah-rkk-<?php echo $row['id_rkk']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=update-riwayat-rkk&id=' . $row['id_rkk'] . ''); ?>" method="POST" role="form" enctype="multipart/form-data">
                                                                                <div class="modal-header">
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                    <h4 class="modal-title" id="myModalLabel">Update Riwayat RKK</h4>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div class="form-group">
                                                                                        <div class="form-group">
                                                                                            <label>No RKK</label>
                                                                                            <input type="text" class="form-control" name="no_rkk" value="<?php echo $row['no_rkk']; ?>" placeholder="No RKK" required>
                                                                                            <label>Periode RKK</label>
                                                                                            <div class = "input-group">
                                                                                                <div class = "input-group-addon">
                                                                                                    <i class = "fa fa-calendar"></i>
                                                                                                </div>
                                                                                                <input type = "text" name = "periode" value="<?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?>" class = "form-control pull-right" data-inputmask='"mask": "99-99-9999 - 99-99-9999","placeholder":"dd-mm-yyyy - dd-mm-yyyy"' data-mask required>
                                                                                            </div>
                                                                                            <label>UPLOAD FILE (.png, .jpg, .pdf)</label>
                                                                                            <input type="file" class="form-control" name="file" >
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
                                                                <div class="modal fade" id="modal-hapus-rkk-<?php echo $row['id_rkk']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=delete-riwayat-rkk&id=' . $row['id_rkk'] . ''); ?>" method="POST" role="form">
                                                                                <div class="modal-header">
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                    <h4 class="modal-title" id="myModalLabel">Delete Data RKK</h4>
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
                                                                    <td><?php echo $row['no_rkk'] ?></td>
                                                                    <td><?php echo $row['nama_pegawai']; ?></td>
                                                                    <td><?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?></td>
                                                                    <td><a href="simpeg/<?php echo $row['file_rkk']; ?>" target="_blank"><?php echo $row['file_rkk']; ?></a></td>
                                                                    <td>
                                                                        <span data-toggle="modal" data-target="#modal-ubah-rkk-<?php echo $row['id_rkk']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                                                        <span data-toggle="modal" data-target="#modal-hapus-rkk-<?php echo $row['id_rkk']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_28">
                                                <button type="button" class="btn btn-warning fa fa-plus" data-toggle="modal" data-target="#modal-add-cv">
                                                    Tambah CV
                                                </button>
                                                <br><br>
                                                <!--Modal Add CV -->
                                                <div class="modal fade" id="modal-add-cv">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title fa fa-university"> Tambah CV</h4>
                                                            </div>
                                                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=simpeg&act=add-cv&id=' . $id_user . ''); ?>" method="post" enctype="multipart/form-data"> 
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label>UPLOAD FILE (.pdf)</label>
                                                                        <input type="file" class="form-control" name="file" >
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
                                                <!-- Tutup Modal CV -->
                                                <!-- /.box-header -->
                                                <div class="table-responsive">
                                                    <table id="example15" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Nama</th>
                                                                <th>File</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_riwayat_cv = bukaquery($idlevel == 'LVL-000007' // tampil seluruh data pegawai apabila dia PJ
                                                            ? "SELECT
                                                                a.id_unit, a.id_user, a.nama_pegawai,
                                                                b.id_cv, b.file_cv
                                                                FROM tm_pegawai a
                                                                    INNER JOIN tm_cv b ON a.id_user = b.id_user
                                                                WHERE a.id_unit = '".$id_unit."'"
                                                            : "SELECT 
                                                                b.id_unit, b.id_user, b.nama_pegawai,
                                                                a.id_cv, a.file_cv
                                                                FROM tm_cv a
                                                                INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                                                                WHERE a.id_user='$id_user'
                                                                ORDER BY a.id_cv DESC");
                                                            while ($row = fetch_array($sql_riwayat_cv)) {
                                                                $no++;
                                                                ?>
                                                                <!-- Edit Modal CV -->
                                                                <div class="modal fade" id="modal-ubah-cv-<?php echo $row['id_cv']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=update-cv&id=' . $row['id_cv'] . ''); ?>" method="POST" role="form" enctype="multipart/form-data">
                                                                                <div class="modal-header">
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                    <h4 class="modal-title" id="myModalLabel">Update CV</h4>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div class="form-group">
                                                                                        <div class="form-group">
                                                                                            <label>UPLOAD FILE (.png, .jpg, .pdf)</label>
                                                                                            <input type="file" class="form-control" name="file" >
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
                                                                <div class="modal fade" id="modal-hapus-cv-<?php echo $row['id_cv']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <form action="<?php echo $aksi . paramEncrypt('module=simpeg&act=delete-cv&id=' . $row['id_cv'] . ''); ?>" method="POST" role="form">
                                                                                <div class="modal-header">
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                    <h4 class="modal-title" id="myModalLabel">Delete Data CV</h4>
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
                                                                    <td><?php echo $row['nama_pegawai']; ?></td>
                                                                    <td><a href="simpeg/<?php echo $row['file_cv']; ?>" target="_blank"><?php echo $row['file_cv']; ?></a></td>
                                                                    <td>
                                                                        <span data-toggle="modal" data-target="#modal-ubah-cv-<?php echo $row['id_cv']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                                                        <span data-toggle="modal" data-target="#modal-hapus-cv-<?php echo $row['id_cv']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        <?php
        break;
        
    case "profile-pegawai":
        ?>
        <div class="box">

            <div class="box-header with-border">  
                <h3 class="box-title fa fa-user-circle">  DATA PEGAWAI </h3> 
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div> 

            </div>
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab">BIODATA</a></li>
                    <li class=""> <a href="?<?php echo paramEncrypt('module=master-data&act=list-master-data-pegawai-non-pns'); ?>" ><span class="fa fa-undo"> Kembali</span></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <div class="box-body">  
                            <?php $biodata = fetch_array(bukaquery("SELECT tm_pegawai.nik, tm_pegawai.nip, tm_pegawai.no_rek, tm_pegawai.tempat_lahir, tm_pegawai.agama, tm_pegawai.npwp,tm_pegawai.jk, 
                                            tm_pegawai.nama_pegawai, tm_pegawai.tgl_masuk, tm_pegawai.pendidikan, tm_pegawai.alamat, tm_pegawai.no_bpjs,tm_pegawai.tgl_lahir,tm_pegawai.foto,
                                            tm_unit.id_unit, tm_unit.nama_unit, tm_pegawai.status_nikah, tm_pegawai.sub_bagian,tm_pegawai.pajak, tm_pegawai.rumpun,tm_pegawai.log_finger, 
                                            tm_pegawai.bpjs_ks, tm_pegawai.bpjs_jkk,tm_pegawai.status, tm_pegawai.bpjs_ijht, tm_pegawai.bpjs_jp, tm_level.nama_level, tm_level.id_level 
                                            FROM
                                            tm_pegawai
                                            INNER JOIN tm_unit ON tm_pegawai.id_unit = tm_unit.id_unit
                                            INNER JOIN tm_user ON tm_user.id_user = tm_pegawai.id_user
                                            INNER JOIN tm_level ON tm_user.id_level = tm_level.id_level
                                            where tm_pegawai.id_user='$id'")); ?>                           
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-3 col-md-4"> 
                                        <label>NIK</label>
                                        <input type="text" class="form-control" value="<?php echo $biodata['nik']; ?>" readonly>
                                        <label>NAMA PEGAWAI</label>
                                        <input type="text" class="form-control" value="<?php echo $biodata['nama_pegawai']; ?>" readonly>
                                        <label>ALAMAT</label>
                                        <textarea class="form-control" placeholder="Alamat" title="Alamat" readonly><?php echo $biodata['alamat']; ?></textarea>
                                        <label>NO REKENING </label>
                                        <input type="text" class="form-control " value="<?php echo $biodata['no_rek']; ?>" readonly> 
                                        <label>NPWP</label>
                                        <input type="text" class="form-control" value="<?php echo $biodata['npwp']; ?>" readonly>
                                        <label>GAPOK</label>
                                        <input type="text" class="form-control" value="<?php echo formatDuit(GajiPokok($biodata['pendidikan'], $biodata['tgl_masuk'])); ?>" readonly>
                                        <label>GAJI BRUTO</label>
                                        <input type="text" class="form-control" value="<?php echo formatDuit(GajiBruto(GajiPokok($biodata['pendidikan'], $biodata['tgl_masuk']), NilaiStatusKawin($biodata['status_nikah']))); ?>" readonly>
                                        <br><br> 
                                    </div>
                                    <div class="col-xs-8 col-md-4"> 
                                        <div class="col-xs-6">
                                            <label>TEMPAT LAHIR</label>
                                            <input type="text" class="form-control " value="<?php echo $biodata['tempat_lahir']; ?>" readonly> 
                                            <label>JENIS KELAMIN</label>
                                            <input type="text" class="form-control" value="<?php echo kelamin($biodata['jk']); ?>" readonly>
                                            <label>TANGGAL MASUK</label>
                                            <input type="text" class="form-control" value="<?php echo FormatTgl('d/m/Y', $biodata['tgl_masuk']); ?>" readonly>
                                            <br>
                                            <label>PENDIDIDIKAN</label>
                                            <input type="text" class="form-control" value="<?php echo $biodata['pendidikan']; ?>" readonly>
                                            <label>RUMPUN JABATAN</label>
                                            <input type="text" class="form-control" value="<?php echo $biodata['rumpun']; ?>" readonly>
                                            <label>STATUS NIKAH</label>
                                            <input type="text" class="form-control" value="<?php echo $biodata['status_nikah']; ?>" readonly>
                                            <label>BAGIAN/UNIT</label>
                                            <input type="text" class="form-control" value="<?php echo $biodata['nama_unit']; ?>" readonly>
                                        </div>
                                        <div class="col-xs-4">
                                            <label>TANGGAL LAHIR </label>
                                            <input type="text" class="form-control " value="<?php echo FormatTgl('d/m/Y', $biodata['tgl_lahir']); ?>" readonly> 
                                            <label>AGAMA</label>
                                            <input type="text" class="form-control" value="<?php echo $biodata['agama']; ?>" readonly>
                                            <label>MASA KERJA</label>
                                            <input type="text" class="form-control" value="<?php echo MasaKerjaPenyebut($biodata['tgl_masuk']); ?>" readonly>
                                            <br>
                                        </div>
                                        <div class="col-xs-2">
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
                                            <img src="img/male.jpg" style="align:center" class="img-rounded" width="450px" height="450px" alt="" > 
                                        </div>
                                        <div class="col-xs-4">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <di
                        </div>
                    </div>                    
                </div>
            </div>

            <!-- SIMPEG-->
            <div class="margin row">
                <div class="x_panel">
                    <!-- Riwayat Pendidikan -->
                    <div class="panel-group" id="dropdown">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h4 class="panel-title fa  fa-hospital-o">
                                    <a data-toggle="collapse" data-parent="#dropdown" href="#collapse1">
                                        SIMPEG</a>
                                </h4>
                            </div>
                            <div id="collapse1" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <div class="nav-tabs-custom">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#tab_11" data-toggle="tab"> RIWAYAT PENDIDIKAN</a></li>
                                            <li><a href="#tab_12" data-toggle="tab">DAFTAR KELUARGA</a></li>
                                            <li><a href="#tab_13" data-toggle="tab">RIWAYAT PENEMPATAN</a></li>
                                            <li><a href="#tab_14" data-toggle="tab">RIWAYAT DIKLAT</a></li>
                                            <li><a href="#tab_15" data-toggle="tab">RIWAYAT PENINGKATAN PENDIDIKAN</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab_11">
                                                <!-- /.box-header -->
                                                <div class="table-responsive">
                                                    <table id="example2" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Pendidikan</th>
                                                                <th>Nama Sekolah</th>
                                                                <th>Jurusan</th>
                                                                <th>Periode</th>
                                                                <th>No.Ijazah</th>
                                                                <th>Tgl Ijazah</th>
                                                                
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_riwayat_pend = bukaquery("SELECT * FROM tm_riwayat_pend where id_user='$id' order by tm_riwayat_pend.tgl_ijazah asc");
                                                            while ($row = fetch_array($sql_riwayat_pend)) {
                                                                $no++;
                                                                ?>                                                              
                                                           
                                                            <tr>
                                                                <td><?php echo $row['pendidikan'] ?></td>
                                                                <td><?php echo $row['nama_sekolah']; ?></td>
                                                                <td><?php echo $row['jurusan']; ?></td>
                                                                <td><?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?></td>
                                                                <td><?php echo $row['no_ijazah']; ?> </td>
                                                                <td><?php echo FormatTgl('d-m-Y', $row['tgl_ijazah']); ?></td>
                                                                
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_12">
                                                <!-- /.box-header -->
                                                <div class="table-responsive">
                                                    <table id="example3" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>NIK</th>
                                                                <th>Nama</th>
                                                                <th>Hubungan</th>
                                                                <th>Tanggal Lahir</th>
                                                                <th>Umur</th>
                                                                <th>Jenis Kelamin</th>
                                                                <th>Pendidikan</th>
                                                                
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_keluarga = bukaquery("SELECT * FROM tm_keluarga where id_user='$id'");
                                                            while ($row = fetch_array($sql_keluarga)) {
                                                                $no++;
                                                                ?>
                                                              
                                                            <tr>
                                                                <td><?php echo $row['nik'] ?></td>
                                                                <td><?php echo $row['nama_keluarga']; ?></td>
                                                                <td><?php echo $row['hubungan']; ?></td>
                                                                <td><?php echo FormatTgl('d-m-Y', $row['tgl_lahir']); ?></td>
                                                                <td><?php echo MasaKerjaPenyebut($row['tgl_lahir']); ?></td>                                            
                                                                <td><?php echo kelamin($row['jk']); ?></td>
                                                                <td><?php echo $row['pendidikan']; ?> </td>                                            
                                                               
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_13">
                                                
                                                <!-- /.box-header -->
                                                <div class="table-responsive">
                                                    <table id="example4" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>No.SK</th>
                                                                <th>Nama Unit/Bagian</th>
                                                                <th>Periode</th>
                                                                
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_riwayat_penempatan = bukaquery("SELECT tm_riwayat_penempatan.id_riwayat_penempatan, tm_riwayat_penempatan.no_sk, tm_riwayat_penempatan.periode, tm_riwayat_penempatan.id_unit, tm_unit.nama_unit "
                                                                    . "FROM tm_riwayat_penempatan "
                                                                    . "inner join tm_unit on tm_unit.id_unit=tm_riwayat_penempatan.id_unit "
                                                                    . "where id_user='$id'");
                                                            while ($row = fetch_array($sql_riwayat_penempatan)) {
                                                                $no++;
                                                                ?>
                                                               
                                                            <tr>
                                                                <td><?php echo $no; ?></td>
                                                                <td><?php echo $row['no_sk']; ?></td>
                                                                <td><?php echo $row['nama_unit']; ?></td>
                                                                <td><?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?></td>                                          
                                                               
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>  
                                            </div>
                                            <div class="tab-pane" id="tab_14">
                                                
                                                <!-- /.box-header -->
                                                <div class="table-responsive">
                                                    <table id="example5" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Nama Pelatihan</th>
                                                                <th>Lokasi Pelatihan</th>
                                                                <th>Periode</th>
                                                                <th>Jenis Diklat</th>
                                                                <th>No Sertifikat</th>
                                                                
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_riwayat_diklat = bukaquery("SELECT * from tm_riwayat_diklat where id_user='$id'");
                                                            while ($row = fetch_array($sql_riwayat_diklat)) {
                                                                $no++;
                                                                ?>
                                                              
                                                            <tr>
                                                                <td><?php echo $no; ?></td>
                                                                <td><?php echo $row['nama_pelatihan'] . " <p>(" . $row['instansi_pelatihan'] . ")</p>"; ?></td>
                                                                <td><?php echo $row['lokasi'] . " <p>(Alamat : " . $row['alamat_pelatihan'] . ")</p>"; ?></td>
                                                                <td><?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))) . " (" . $row['total_jam'] . " Jam)"; ?></td>
                                                                <td><?php echo $row['jenis_diklat']; ?></td>
                                                                <td><?php echo $row['no_sertifikat'] . " <p>Status Terakreditasi (" . $row['status_akreditasi'] . ")</p>"; ?></td>
                                                                
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>  
                                            </div>
                                            <div class="tab-pane" id="tab_15">                                                
                                                <!-- /.box-header -->
                                                <div class="table-responsive">
                                                    <table id="example6" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>No Izin Belajar</th>
                                                                <th>Nama Akademik/Univ</th>
                                                                <th>Jenis Peningkatan</th>
                                                                <th>Peningkatan</th>
                                                                <th>Akreditasi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_izin_belajar = bukaquery("SELECT * from tm_izin_belajar where id_user='$id'");
                                                            while ($row = fetch_array($sql_izin_belajar)) {
                                                                $no++;
                                                                ?>                                                                
                                                                <tr>
                                                                    <td><?php echo $no; ?></td>
                                                                    <td><?php echo $row['no_izin']; ?></td>
                                                                    <td><?php echo $row['nama_univ'] . " <p> Alamat : " . $row['alamat_univ'] . "</p>"; ?></td>
                                                                    <td><?php echo $row['jenis_peningkatan']; ?></td>
                                                                    <td><?php echo "Dari " . $row['pendidikan_sebelum'] . " Ke " . $row['pendidikan_sesudah'] . " " . $row['jurusan']; ?></td>
                                                                    <td><?php echo $row['akreditasi']; ?></td>
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
                        </div>
                    </div>
                </div>
                <!-- upload file-->
                <div class="x_panel">
                    <!-- Riwayat Pendidikan -->
                    <div class="panel-group" id="dropdown">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h4 class="panel-title fa  fa-upload">
                                </h4>
                            </div>
                            <div id="collapse2" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <div class="nav-tabs-custom">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#tab_22" data-toggle="tab">KTP</a></li>
                                            <li><a href="#tab_23" data-toggle="tab">KK</a></li>
                                            <li><a href="#tab_24" data-toggle="tab">IJAZAH</a></li>
                                            <li><a href="#tab_25" data-toggle="tab">STR</a></li>
                                            <li><a href="#tab_26" data-toggle="tab">SPK</a></li>
                                            <li><a href="#tab_27" data-toggle="tab">RKK</a></li>
                                            <li><a href="#tab_16" data-toggle="tab">SIP</a></li>
                                            <li><a href="#tab_17" data-toggle="tab">ACLS</a></li>
                                            <li><a href="#tab_18" data-toggle="tab">ATLS</a></li>
                                            <li><a href="#tab_19" data-toggle="tab">BTCLS</a></li>
                                            <li><a href="#tab_20" data-toggle="tab">APN</a></li>
                                            <li><a href="#tab_21" data-toggle="tab">PHELEBOTOMY</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane" id="tab_16">
                                               
                                                <!-- /.box-header -->
                                                <div class="table-responsive">
                                                    <table id="example7" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No SIP</th>
                                                                <th>Periode SIP</th>
                                                                <th>File</th>
                                                               
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_riwayat_sip = bukaquery("SELECT * FROM tm_sip where id_user='$id' order by tm_sip.id_sip desc");
                                                            while ($row = fetch_array($sql_riwayat_sip)) {
                                                                $no++;
                                                                ?>                                                               
                                                           
                                                            <tr>
                                                                <td><?php echo $row['no_sip'] ?></td>
                                                                <td><?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?></td>
                                                                <td><a href="simpeg/<?php echo $row['file_sip']; ?>" target="_blank"><?php echo $row['file_sip']; ?></a></td>
                                                              
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_17">
                                                
                                                <!-- /.box-header -->
                                                <div class="table-responsive">
                                                    <table id="example8" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No ACLS</th>
                                                                <th>Periode ACLS</th>
                                                                <th>File</th>
                                                                
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_riwayat_acls = bukaquery("SELECT * FROM tm_acls where id_user='$id' order by tm_acls.id_acls desc");
                                                            while ($row = fetch_array($sql_riwayat_acls)) {
                                                                $no++;
                                                                ?>
                                                                
                                                            <tr>
                                                                <td><?php echo $row['no_acls'] ?></td>
                                                                <td><?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?></td>
                                                                <td><a href="simpeg/<?php echo $row['file_acls']; ?>" target="_blank"><?php echo $row['file_acls']; ?></a></td>
                                                               
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_18">
                                               
                                                <!-- /.box-header -->
                                                <div class="table-responsive">
                                                    <table id="example9" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No ATLS</th>
                                                                <th>Periode ATLS</th>
                                                                <th>File</th>
                                                               
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_riwayat_atls = bukaquery("SELECT * FROM tm_atls where id_user='$id' order by tm_atls.id_atls desc");
                                                            while ($row = fetch_array($sql_riwayat_atls)) {
                                                                $no++;
                                                                ?>
                                                                
                                                            <tr>
                                                                <td><?php echo $row['no_atls'] ?></td>
                                                                <td><?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?></td>
                                                                <td><a href="simpeg/<?php echo $row['file_atls']; ?>" target="_blank"><?php echo $row['file_atls']; ?></a></td>
                                                                
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_19">
                                                
                                                <!-- /.box-header -->
                                                <div class="table-responsive">
                                                    <table id="example10" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No BTCLS</th>
                                                                <th>Periode BTCLS</th>
                                                                <th>File</th>
                                                                
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_riwayat_btcls = bukaquery("SELECT * FROM tm_btcls where id_user='$id' order by tm_btcls.id_btcls desc");
                                                            while ($row = fetch_array($sql_riwayat_btcls)) {
                                                                $no++;
                                                                ?>
                                                              
                                                            <tr>
                                                                <td><?php echo $row['no_btcls'] ?></td>
                                                                <td><?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?></td>
                                                                <td><a href="simpeg/<?php echo $row['file_btcls']; ?>" target="_blank"><?php echo $row['file_btcls']; ?></a></td>
                                                                
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_20"> 
                                                
                                                <!-- /.box-header -->
                                                <div class="table-responsive">
                                                    <table id="example12" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No APN</th>
                                                                <th>Periode APN</th>
                                                                <th>File</th>
                                                               
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_riwayat_apn = bukaquery("SELECT * FROM tm_apn where id_user='$id' order by tm_apn.id_apn desc");
                                                            while ($row = fetch_array($sql_riwayat_apn)) {
                                                                $no++;
                                                                ?>
                                                                
                                                            <tr>
                                                                <td><?php echo $row['no_apn'] ?></td>
                                                                <td><?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?></td>
                                                                <td><a href="simpeg/<?php echo $row['file_apn']; ?>" target="_blank"><?php echo $row['file_apn']; ?></a></td>
                                                                
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_21">   
                                               
                                                <!-- /.box-header -->
                                                <div class="table-responsive">
                                                    <table id="example11" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No Phelebethomy</th>
                                                                <th>Periode Phelebethomy</th>
                                                                <th>File</th>
                                                                
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_riwayat_phelebethomy = bukaquery("SELECT * FROM tm_phelebethomy where id_user='$id' order by tm_phelebethomy.id_phelebethomy desc");
                                                            while ($row = fetch_array($sql_riwayat_phelebethomy)) {
                                                                $no++;
                                                                ?>
                                                             
                                                            <tr>
                                                                <td><?php echo $row['no_phelebethomy'] ?></td>
                                                                <td><?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?></td>
                                                                <td><a href="simpeg/<?php echo $row['file_phelebethomy']; ?>" target="_blank"><?php echo $row['file_phelebethomy']; ?></a></td>
                                                                
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane active" id="tab_22">
                                                
                                                <!-- /.box-header -->
                                                <div class="table-responsive">
                                                    <table id="example13" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No NIK</th>
                                                                <th>File</th>
                                                               
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_ktp = bukaquery("SELECT * FROM tm_ktp where id_user='$id' order by tm_ktp.no_nik desc");
                                                            while ($row = fetch_array($sql_ktp)) {
                                                                $no++;
                                                                ?>
                                                               
                                                            <tr>
                                                                <td><?php echo $row['no_nik'] ?></td>
                                                                <td><a href="simpeg/<?php echo $row['file_ktp']; ?>" target="_blank"><?php echo $row['file_ktp']; ?></a></td>
                                                                
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_23">
                                                
                                                <!-- /.box-header -->
                                                <div class="table-responsive">
                                                    <table id="example14" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No KK</th>
                                                                <th>File</th>
                                                                
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_kk = bukaquery("SELECT * FROM tm_kk where id_user='$id' order by tm_kk.no_kk desc");
                                                            while ($row = fetch_array($sql_kk)) {
                                                                $no++;
                                                                ?>
                                                              
                                                            <tr>
                                                                <td><?php echo $row['no_kk'] ?></td>
                                                                <td><a href="simpeg/<?php echo $row['file_kk']; ?>" target="_blank"><?php echo $row['file_kk']; ?></a></td>
                                                                
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_24">   
                                               
                                                <!-- /.box-header -->
                                                <div class="table-responsive">
                                                    <table id="example16" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No Ijazah</th>
                                                                <th>Nama Sekolah</th>
                                                                <th>Pendidikan</th>
                                                                <th>File</th>
                                                                
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_ijazah = bukaquery("SELECT tm_ijazah.id_riwayat_pend, tm_riwayat_pend.no_ijazah, tm_riwayat_pend.nama_sekolah, tm_riwayat_pend.pendidikan, tm_ijazah.file_ijazah
                                                                        FROM tm_ijazah
                                                                        INNER JOIN tm_riwayat_pend ON tm_ijazah.id_riwayat_pend=tm_riwayat_pend.id_riwayat_pend where tm_ijazah.id_user='$id'");
                                                            while ($row = fetch_array($sql_ijazah)) {
                                                                $no++;
                                                                ?>
                                                           
                                                            <tr>
                                                                <td><?php echo $row['no_ijazah'] ?></td>
                                                                <td><?php echo $row['nama_sekolah'] ?></td>
                                                                <td><?php echo $row['pendidikan'] ?></td>
                                                                <td><a href="simpeg/<?php echo $row['file_ijazah']; ?>" target="_blank"><?php echo $row['file_ijazah']; ?></a></td>
                                                                
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_25">  
                                                <!-- /.box-header -->
                                                <div class="table-responsive">
                                                    <table id="example15" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No STR</th>
                                                                <th>Periode STR</th>
                                                                <th>File</th>
                                                               
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_riwayat_str = bukaquery("SELECT * FROM tm_str where id_user='$id' order by tm_str.id_str desc");
                                                            while ($row = fetch_array($sql_riwayat_str)) {
                                                                $no++;
                                                                ?>
                                                             
                                                            <tr>
                                                                <td><?php echo $row['no_str'] ?></td>
                                                                <td><?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?></td>
                                                                <td><a href="simpeg/<?php echo $row['file_str']; ?>" target="_blank"><?php echo $row['file_str']; ?></a></td>
                                                                
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_26">  
                                                <!-- /.box-header -->
                                                <div class="table-responsive">
                                                    <table id="example15" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No SPK</th>
                                                                <th>Periode SPK</th>
                                                                <th>File</th>
                                                               
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_riwayat_spk = bukaquery("SELECT * FROM tm_spk where id_user='$id' order by tm_spk.id_str desc");
                                                            while ($row = fetch_array($sql_riwayat_spk)) {
                                                                $no++;
                                                                ?>
                                                             
                                                            <tr>
                                                                <td><?php echo $row['no_spk'] ?></td>
                                                                <td><?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?></td>
                                                                <td><a href="simpeg/<?php echo $row['file_str']; ?>" target="_blank"><?php echo $row['file_str']; ?></a></td>
                                                                
                                                            </tr>                                  
                                                            <?php
                                                        }
                                                        ?>    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_27">  
                                                <!-- /.box-header -->
                                                <div class="table-responsive">
                                                    <table id="example15" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No RKK</th>
                                                                <th>Periode RKK</th>
                                                                <th>File</th>
                                                               
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            $sql_riwayat_rkk = bukaquery("SELECT * FROM tm_rkk where id_user='$id' order by tm_rkk.id_str desc");
                                                            while ($row = fetch_array($sql_riwayat_rkk)) {
                                                                $no++;
                                                                ?>
                                                             
                                                            <tr>
                                                                <td><?php echo $row['no_rkk'] ?></td>
                                                                <td><?php echo date('d-m-Y', strtotime(substr($row['periode'], 0, 10))) . " s/d " . date('d-m-Y', strtotime(substr($row['periode'], 13, 10))); ?></td>
                                                                <td><a href="simpeg/<?php echo $row['file_rkk']; ?>" target="_blank"><?php echo $row['file_rkk']; ?></a></td>
                                                                
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
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        <?php
        break;
}
?>
<script src="libs/jquery/jquery.min.js"></script>
<script>

    // fungsi untuk profile-update
    function put_profile_pegawai() {

        var id_user = $('#id_user').val();
        var nik = $('#nik').val();
        var nama_pegawai = $('#nama_pegawai').val();
        var alamat = $('#alamat').val();
        var alamat_domisili = $('#alamat_domisili').val();
        var no_hp_wa = $('#no_hp_wa').val();
        var no_hp_sms = $('#no_hp_sms').val();
        var tempat_lahir = $('#tempat_lahir').val();
        var tgl_lahir = $('#tgl_lahir').val();
        var no_rek = $('#no_rek').val();
        var npwp = $('#npwp').val();
        var no_bpjs = $('#no_bpjs').val();
        var jk = $('#jk').val();
        var agama = $('#agama').val();

        $.ajax({
            url: "<?php echo $url_api_simpeg; ?>?action=put_profile_pegawai",
            type: "POST",
            data: {
                nik: nik,
                nama_pegawai: nama_pegawai,
                alamat: alamat,
                alamat_domisili: alamat_domisili,
                no_hp_wa: no_hp_wa,
                no_hp_sms: no_hp_sms,
                tempat_lahir: tempat_lahir,
                tgl_lahir: tgl_lahir,
                no_rek: no_rek,
                npwp: npwp,
                no_bpjs: no_bpjs,
                jk: jk,
                agama: agama,
                id_user: id_user
            },
            dataType: "JSON",
            success: function(response) {

                console.log('response '+response);
                swal({
                    title: "Berhasil Memperbarui Data Profil",
                    text: "",
                    type: "success"
                },
                function(){
                    location.reload();
                });
            },
            error: function(error) {

                console.log('Kode : PUTDATAPROFIL01. Gagal mengirim permintaan. '+erorr.status+'-'+error.statusText);
                swal('Gagal Memperbarui Data Profil', 'Silahkan dicoba kembali dalam beberapa saat', 'error');
            }
        });
    }

    function put_fotoprofil_pegawai() {

        var id_user = $('#profile-update_id-user').val();
        var form_data = new FormData();
        var files = $('#foto_profil')[0].files;

        if(files.length  > 0) {

            // ukuran foto tidak boleh melebihi 5MB
            if(files.length <= 5242880) {

                $('#profile-update_btn-upload').html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;Mohon Tunggu").prop('disabled', true);

                form_data.append('foto_profil', files[0]);
                form_data.append('id_user', id_user);

                console.log('prose upload '+"<?php echo $url_api_simpeg; ?>?action=put_fotoprofile_pegawai");
                for (var pair of form_data.entries()) {
                    console.log(pair[0]+ ', ' + pair[1]); 
                }

                $.ajax({
                    url: "<?php echo $url_api_simpeg; ?>?action=put_fotoprofile_pegawai",
                    type: "POST",
                    data: form_data,
                    contentType: false,
                    processData: false,
                    dataType: "JSON",
                    success: function(response) {
                        console.log('response');
                        console.log(response);

                        if(response.status == 1) {

                            console.log("status = 1");
                            console.log(response.message);
                            swal({
                                title: "Berhasil Memperbarui Foto Profil",
                                text: "",
                                type: "success"
                            },
                            function(){
                                location.reload();
                            });
                        } else {

                            $('#profile-update_btn-upload').html("Update").prop('disabled', false);
                            console.log('PUTFOTOPROFIL01. Status != 1. '+response.message);
                            swal("Gagal Memperbarui Foto Profil", response.message, "error");
                        }
                    },
                    error: function(error) {

                        $('#profile-update_btn-upload').html("Update").prop('disabled', false);
                        console.log('PUTFOTOPROFIL01. '+error.status+'-'+error.statusText);
                        swal("Gagal Memperbarui Foto Profil", "Silahkan Diulang Dalam Beberapa Saat.", "error");
                    }
                });
            } else {

                console.log('PUTFOTOPROFIL01. Ukuran Foto Melebihi 5MB');
                swal("Ukuran Foto Terlalu Besar", "Ukuran Foto Tidak Boleh Melebihi 5MB", "error");
            }
        } else {

            console.log('PUTFOTOPROFIL01. Foto Kosong');
            swal("Foto Belum Dipilih", "", "error");
        }
    }
    // end fungsi untuk profile-update
</script>

