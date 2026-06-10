<?php
// testing github
$aksi = "module/master-data/aksi-master-data?";
$id = isset($url['id']) ? $url['id'] : null;
$dpa = isset($url['dpa']) ? $url['dpa'] : null;
$pengaturanshift_idshiftvalidation = isset($url['id_validation']) ? $url['id_validation'] : null;
$pengaturanshift_bulan_selected = isset($url['sft_bln_slctd']) ? $url['sft_bln_slctd'] : null;
$pengaturanshift_year_selected = isset($url['sft_year_slctd']) ? $url['sft_year_slctd'] : null;
$pengaturanshift_unit_selected = isset($url['sft_unit_slctd']) ? $url['sft_unit_slctd'] : null;

switch ((isset($url['act']) ? $url['act'] : '')) {
    default:
        echo "default";
        header('location:error.php');
        break;

    case "upload-data-pegawai":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-upload"> UPLOAD DATA PEGAWAI</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <form method="post" action="" enctype="multipart/form-data" style="float:left">
                    <a href="format_upload_pegawai.xlsx" class="btn btn-default" style="float:right">
                        <span class="glyphicon glyphicon-download"></span>
                        Download Format
                    </a>
                    <br>
                    <div class="form-group">
                        <label>Import excel :</label>
                        <input type="file" name="file" class="form-control">
                    </div>
                    <button type="submit" name="preview" class="btn btn-success btn-sm">
                        <span class="glyphicon glyphicon-eye-open"></span> Preview
                    </button>
                </form>
            </div>
            <?php
            // Jika user telah mengklik tombol Preview
            if (isset($_POST['preview'])) {
                //$ip = ; // Ambil IP Address dari User
                $nama_file_baru = 'data.xlsx';

                // Cek apakah terdapat file data.xlsx pada folder tmp
                if (is_file('tmp/' . $nama_file_baru)) // Jika file tersebut ada
                    unlink('tmp/' . $nama_file_baru); // Hapus file tersebut

                $tipe_file = $_FILES['file']['type']; // Ambil tipe file yang akan diupload
                $tmp_file = $_FILES['file']['tmp_name'];

                // Cek apakah file yang diupload adalah file Excel 2007 (.xlsx)
                if ($tipe_file == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
                    // Upload file yang dipilih ke folder tmp
                    // dan rename file tersebut menjadi data{ip_address}.xlsx
                    // {ip_address} diganti jadi ip address user yang ada di variabel $ip
                    // Contoh nama file setelah di rename : data127.0.0.1.xlsx
                    move_uploaded_file($tmp_file, 'tmp/' . $nama_file_baru);

                    // Load librari PHPExcel nya
                    require_once 'libs/PHPExcel/PHPExcel.php';

                    $excelreader = new PHPExcel_Reader_Excel2007();
                    $loadexcel = $excelreader->load('tmp/' . $nama_file_baru); // Load file yang tadi diupload ke folder tmp
                    $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true, true);

                    // Buat sebuah tag form untuk proses import data ke database
                    $a = $aksi . paramEncrypt("module=master-data&act=upload-data-pegawai");
                    echo "<form method='post' action=" . $a . ">";

                    // Buat sebuah div untuk alert validasi kosong
                    echo "<div class='alert alert-danger' id='kosong'>
					Semua data belum diisi, Ada <span id='jumlah_kosong'></span> data yang belum diisi.
			  </div>";

                    echo "<div class='box-body'>
                        <div class='box-body table-responsive'>
                    <table class='table table-bordered table-striped'>
					<tr>
						<th colspan='26' class='text-center'>Preview Data</th>
					</tr>
					<tr>
						<th>Id User</th>
						<th>Nip</th>
						<th>NIK</th>
                                                <th>Nama Pegawai</th>
                                                <th>Tempat lahir</th>
                                                <th>Tgl Lahir</th>
                                                <th>JK</th>
                                                <th>Alamat</th>
                                                <th>NPWP</th>
                                                <th>Pendidikan</th>
                                                <th>TMT</th>
                                                <th>Status Nikah</th>
                                                <th>Rumpun</th>
                                                <th>Pajak</th>
                                                <th>Id Unit</th>
                                                <th>Bpjs ks</th>
                                                <th>Bpjs Jkk</th>
                                                <th>Bpjs Ijht</th>
                                                <th>Bpjs Jp</th>
                                                <th>Foto</th>
                                                <th>Status Kepegawaian</th>
						<th>SubBagian</th>
                                                <th>Log Finger</th>
						<th>Aktif</th>
                                                <th>Id Level</th>
                                                <th>Id Kasatpel</th>
					</tr>";

                    $numrow = 1;
                    $kosong = 0;
                    foreach ($sheet as $row) { // Lakukan perulangan dari data yang ada di excel
                        // Ambil data pada excel sesuai Kolom
                        $id_user = $row['A']; // Ambil data id
                        $nip = $row['B']; // Ambil data nip
                        $nik = $row['C'];
                        $nama = $row['D'];
                        $tempat_lahir = $row['E'];
                        $tgl_lahir = $row['F'];
                        $jk = $row['G'];
                        $alamat = $row['H'];
                        $npwp = $row['I'];
                        $norek = $row['J'];
                        $pendidikan = $row['K'];
                        $tmt = $row['L'];
                        $status_nikah = $row['M'];
                        $rumpun = $row['N'];
                        $pajak = $row['O'];
                        $id_unit = $row['P'];
                        $bpjs_ks = $row['Q'];
                        $bpjs_jkk = $row['R'];
                        $bpjs_ijht = $row['S'];
                        $bpjs_jp = $row['T'];
                        $foto = $row['U'];
                        $status_kepegawaian = $row['V'];
                        $sub_bagian = $row['W'];
                        $log_finger = $row['X'];
                        $aktif = $row['Y'];
                        $id_level = $row['Z'];
                        $id_kasatpel = $row['Z'];
                        // Cek jika semua data tidak diisi
                        if (empty($id_user) && empty($nip) && empty($nik) && empty($nama) && empty($tempat_lahir) && empty($tgl_lahir) && empty($jk) && empty($alamat) && empty($npwp) && empty($norek) && empty($pendidikan) && empty($tmt) && empty($status_nikah) && empty($rumpun) && empty($pajak) && empty($id_unit) && empty($bpjs_ks) && empty($bpjs_jkk) && empty($bpjs_jp) && empty($foto) && empty($status_kepegawaian) && empty($sub_bagian) && empty($log_finger) && empty($aktif) && empty($id_level) && empty($id_kasatpel))
                            continue; // Lewat data pada baris ini (masuk ke looping selanjutnya / baris selanjutnya)                       
                        // Cek $numrow apakah lebih dari 1
                        // Artinya karena baris pertama adalah nama-nama kolom
                        // Jadi dilewat saja, tidak usah diimport
                        if ($numrow > 1) {
                            // Validasi apakah semua data telah diisi
                            $id_user_td = (!empty($id_user)) ? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah
                            $nip_td = (!empty($nip)) ? "" : " style='background: #E07171;'";
                            $nik_td = (!empty($nik)) ? "" : " style='background: #E07171;'";
                            $nama_td = (!empty($nama)) ? "" : " style='background: #E07171;'";
                            $tempat_lahir_td = (!empty($tempat_lahir)) ? "" : " style='background: #E07171;'";
                            $tgl_lahir_td = (!empty($tgl_lahir)) ? "" : " style='background: #E07171;'";
                            $jk_td = (!empty($jk)) ? "" : " style='background: #E07171;'";
                            $alamat_td = (!empty($alamat)) ? "" : " style='background: #E07171;'";
                            $npwp_td = (!empty($npwp)) ? "" : " style='background: #E07171;'";
                            $norek_td = (!empty($norek)) ? "" : " style='background: #E07171;'";
                            $pendidikan_td = (!empty($pendidikan)) ? "" : " style='background: #E07171;'";
                            $tmt_td = (!empty($tmt)) ? "" : " style='background: #E07171;'";
                            $status_nikah_td = (!empty($status_nikah)) ? "" : " style='background: #E07171;'";
                            $rumpun_td = (!empty($rumpun)) ? "" : " style='background: #E07171;'";
                            $pajak_td = (!empty($pajak)) ? "" : " style='background: #E07171;'";
                            $id_unit_td = (!empty($id_unit)) ? "" : " style='background: #E07171;'";
                            $bpjs_ks_td = (!empty($bpjs_ks)) ? "" : " style='background: #E07171;'";
                            $bpjs_jkk_td = (!empty($bpjs_jkk)) ? "" : " style='background: #E07171;'";
                            $bpjs_ijht_td = (!empty($bpjs_ijht)) ? "" : " style='background: #E07171;'";
                            $bpjs_jp_td = (!empty($bpjs_jp)) ? "" : " style='background: #E07171;'";
                            $foto_td = (!empty($foto)) ? "" : " style='background: #E07171;'";
                            $status_kepegawaian_td = (!empty($status_kepegawaian)) ? "" : " style='background: #E07171;'";
                            $sub_bagian_td = (!empty($sub_bagian)) ? "" : " style='background: #E07171;'";
                            $log_finger_td = (!empty($log_finger)) ? "" : " style='background: #E07171;'";
                            $aktif_td = (!empty($aktif)) ? "" : " style='background: #E07171;'";
                            $id_level_td = (!empty($id_level)) ? "" : " style='background: #E07171;'";
                            $id_kasatpel_td = (!empty($id_kasatpel)) ? "" : " style='background: #E07171;'";
                            // Jika salah satu data ada yang kosong
                            if (empty($id_user) && empty($nip) && empty($nik) && empty($nama) && empty($tempat_lahir) && empty($tgl_lahir) && empty($jk) && empty($alamat) && empty($npwp) && empty($norek) && empty($pendidikan) && empty($tmt) && empty($status_nikah) && empty($rumpun) && empty($pajak) && empty($id_unit) && empty($bpjs_ks) && empty($bpjs_jkk) && empty($bpjs_jp) && empty($foto) && empty($status_kepegawaian) && empty($sub_bagian) && empty($log_finger) && empty($aktif) && empty($id_level) && empty($id_kasatpel)) {
                                $kosong++; // Tambah 1 variabel $kosong
                            }
                            echo "<tr>";
                            echo "<td" . $id_unit_td . ">" . $id_user . "</td>";
                            echo "<td" . $nip_td . ">" . $nip . "</td>";
                            echo "<td" . $nama_td . ">" . $nama . "</td>";
                            echo "<td" . $tempat_lahir_td . ">" . $tempat_lahir . "</td>";
                            echo "<td" . $tgl_lahir_td . ">" . $tgl_lahir . "</td>";
                            echo "<td" . $jk_td . ">" . $jk . "</td>";
                            echo "<td" . $alamat_td . ">" . $alamat . "</td>";
                            echo "<td" . $npwp_td . ">" . $npwp . "</td>";
                            echo "<td" . $norek_td . ">" . $norek . "</td>";
                            echo "<td" . $pendidikan_td . ">" . $pendidikan . "</td>";
                            echo "<td" . $tmt_td . ">" . $tmt . "</td>";
                            echo "<td" . $status_nikah_td . ">" . $status_nikah . "</td>";
                            echo "<td" . $rumpun_td . ">" . $rumpun . "</td>";
                            echo "<td" . $pajak_td . ">" . $pajak . "</td>";
                            echo "<td" . $id_unit_td . ">" . $id_unit . "</td>";
                            echo "<td" . $bpjs_ks_td . ">" . $bpjs_ks . "</td>";
                            echo "<td" . $bpjs_jkk_td . ">" . $bpjs_jkk . "</td>";
                            echo "<td" . $bpjs_ijht_td . ">" . $bpjs_ijht . "</td>";
                            echo "<td" . $bpjs_jp_td . ">" . $bpjs_jp . "</td>";
                            echo "<td" . $foto_td . ">" . $foto . "</td>";
                            echo "<td" . $status_kepegawaian_td . ">" . $status_kepegawaian . "</td>";
                            echo "<td" . $sub_bagian_td . ">" . $sub_bagian . "</td>";
                            echo "<td" . $log_finger_td . ">" . $log_finger . "</td>";
                            echo "<td" . $aktif_td . ">" . $aktif . "</td>";
                            echo "<td" . $id_level_td . ">" . $id_level . "</td>";
                            echo "<td" . $id_kasatpel_td . ">" . $id_kasatpel . "</td>";
                            echo "</tr>";
                        }

                        $numrow++; // Tambah 1 setiap kali looping
                    }

                    echo "</table> "
                        . "</div>";

                    // Cek apakah variabel kosong lebih dari 1
                    // Jika lebih dari 1, berarti ada data yang masih kosong
                    if ($kosong > 1) {
            ?>
                        <script>
                            $(document).ready(function() {
                                // Ubah isi dari tag span dengan id jumlah_kosong dengan isi dari variabel kosong
                                $("#jumlah_kosong").html('<?php echo $kosong; ?>');
                                $("#kosong").show(); // Munculkan alert validasi kosong
                            });
                        </script>
            <?php
                    } else { // Jika semua data sudah diisi
                        echo "<hr>";
                        // Buat sebuah tombol untuk mengimport data ke database
                        echo "<div class='form-group'><button type='submit' name='import' class='btn btn-primary'><span class='glyphicon glyphicon-upload'></span> Import</button></div>";
                    }

                    echo "</form></div>";
                } else { // Jika file yang diupload bukan File Excel 2007 (.xlsx)
                    // Munculkan pesan validasi
                    echo "<div class='box-body'><div class='alert alert-danger'>
					Hanya File Excel 2007 (.xlsx) yang diperbolehkan
					</div></div>";
                }
            }
            ?>

            <div class="box-body table-responsive">
                <table id="example" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIP</th>
                            <th>Nama Pegawai</th>
                            <th>Tanggal Masuk</th>
                            <th>Unit</th>
                            <th>Pendidikan</th>
                            <th>Status Nikah</th>
                            <th>Rumpun</th>
                            <th>Jabatan</th>
                            <th>Kepegawaian</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 0;
                        $list_pegawai = bukaquery("SELECT tm_pegawai.id_user, tm_pegawai.nip, tm_pegawai.nama_pegawai, tm_pegawai.tgl_masuk, tm_pegawai.pendidikan,tm_pegawai.status_pegawai,
                                            tm_unit.id_unit, tm_unit.nama_unit, tm_pegawai.status_nikah, tm_pegawai.sub_bagian,tm_pegawai.pajak, tm_pegawai.rumpun,tm_pegawai.log_finger, 
                                            tm_pegawai.bpjs_ks, tm_pegawai.bpjs_jkk,tm_pegawai.status, tm_pegawai.bpjs_ijht, tm_pegawai.bpjs_jp, tm_level.nama_level, tm_level.id_level 
                                            FROM
                                            tm_pegawai
                                            INNER JOIN tm_unit ON tm_pegawai.id_unit = tm_unit.id_unit
                                            INNER JOIN tm_user ON tm_user.id_user = tm_pegawai.id_user
                                            INNER JOIN tm_level ON tm_user.id_level = tm_level.id_level");
                        while ($row = fetch_array($list_pegawai)) {
                            $no++;
                        ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $row['nip']; ?></td>
                                <td><?php echo $row['nama_pegawai']; ?></td>
                                <td><?php echo konversiTanggal($row['tgl_masuk']); ?></td>
                                <td><?php echo $row['nama_unit']; ?></td>
                                <td><?php echo $row['pendidikan']; ?></td>
                                <td><?php echo ucwords(strtolower($row['status_nikah'])); ?></td>
                                <td><?php echo $row['rumpun']; ?></td>
                                <td><?php echo $row['nama_level']; ?></td>
                                <td><?php echo $row['status_pegawai']; ?></td>
                                <td>
                                    <?php if ($row['status'] == 'AKTIF') { ?>
                                        <span class="fa fa-check" title="Pegawai Aktif"> AKTIF</span>
                                    <?php } else { ?>
                                        <span class="fa fa-close" title="Pegawai NON Aktif"> NON-AKTIF</span>
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
    <?php
        break;

    case "list-master-data-pegawai-pns":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST DATA PEGAWAI PNS</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <button type="button" class="btn btn-info fa fa-plus" data-toggle="modal" data-target="#modal-add-pegawai-pns">
                    Buat Pegawai PNS
                </button>
                <p></p>


                <!--Modal Add SKP -->
                <div class="modal fade" id="modal-add-pegawai-pns">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title fa fa-plus"> Tambah Data Pegawai PNS</h4>
                            </div>
                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=master-data&act=add-master-pegawai-pns'); ?>" method="post">
                                <div class="modal-body">
                                    <div class="modal-body">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">NIP</label>
                                                <input type="number" name="nip" class="form-control" placeholder="Masukan NIP" required>
                                                <label class="control-label">Nama Pegawai</label>
                                                <input type="text" name="nama_pegawai" class="form-control" placeholder="Nama lengkap" required>
                                                <label class="control-label">No. Handphone (WA)</label>
                                                <input type="text" name="no_hp_wa" class="form-control" placeholder="No. Handphone WA Ex:08xxxxxxxxxx" required>
                                                <label class="control-label">No. Handphone (SMS)</label>
                                                <input type="text" name="no_hp_sms" class="form-control" placeholder="No. Handphone SMS Ex:08xxxxxxxxxx" required>
                                                <label class="control-label">Tanggak Masuk</label>
                                                <input type="text" class="form-control" id="datepicker" name="tgl_masuk" placeholder="dd/mm/yyyy" required>
                                                <label class="control-label">Unit/Bagian</label>
                                                <select class="form-control select2" name="id_unit" data-placeholder="-Pilih Unit/Bagian-" style="width: 100%;" required>
                                                    <option selected="selected" value="">-Pilih Unit/Bagian-</option>
                                                    <?php
                                                    $tm_unit = bukaquery("select tm_unit.id_unit,tm_unit.nama_unit from tm_unit");
                                                    while ($row = fetch_array($tm_unit)) {
                                                        echo "<option value=" . $row['id_unit'] . ">" . $row['nama_unit'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <label class="control-label">Sub Bagian<font color="red">*</font></label>
                                                <select class='form-control select2' data-placeholder='' style='width: 100%;' name='sub_bagian' required=''>
                                                    <?php
                                                    $tm_sub_bagian = bukaquery("
                                                            SELECT
                                                                a.sub_bagian
                                                            FROM tm_sub_bagian a
                                                        ");
                                                    while ($row = fetch_array($tm_sub_bagian)) {

                                                        echo "<option value='" . $row['sub_bagian'] . "' >" . $row['sub_bagian'] . "</option>";;
                                                    }
                                                    ?>
                                                </select>
                                                <label class="control-label">Kasatpel<font color="red">*</font></label>
                                                <select class="form-control select2" name="id_kasatpel" data-placeholder="-Pilih Kasatpel-" style="width: 100%;" required>
                                                    <option selected="selected" value="">-Pilih kasatpel-</option>
                                                    <?php
                                                    $tm_kasatpel = bukaquery("select tm_kasatpel.id_kasatpel,tm_kasatpel.nama_kasatpel from tm_kasatpel");
                                                    while ($row = fetch_array($tm_kasatpel)) {
                                                        echo "<option value=" . $row['id_kasatpel'] . ">" . $row['nama_kasatpel'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <label class="control-label">Level Petugas<font color="red">*</font></label>
                                                <select class="form-control select2" name="id_level" data-placeholder="-Level Petugas-" style="width: 100%;" required>
                                                    <option selected="selected" value="">-Pilih Level Petugas-</option>
                                                    <?php
                                                    $tm_unit = bukaquery("select tm_level.id_level,tm_level.nama_level from tm_level");
                                                    while ($row = fetch_array($tm_unit)) {
                                                        echo "<option value=" . $row['id_level'] . ">" . $row['nama_level'] . "</option>";
                                                    }
                                                    ?>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="control-label">Status Nikah</label>
                                                <?php echo enumDropdown("tm_pegawai", "status_nikah", "", "-Pilih Status Nikah-"); ?>
                                                <label class="control-label">PTKP</label>
                                                <?php echo enumDropdown("tm_pegawai", "pajak", "", "-Pilih Pajak-"); ?>
                                                <label class="control-label">Pendidikan</label>
                                                <?php echo enumDropdown("tm_pegawai", "pendidikan", "", "-Pilih Pendidikan-"); ?>
                                                <label class="control-label">Rumpun</label>
                                                <?php echo enumDropdown("tm_pegawai", "rumpun", "", "-Pilih Rumpun-"); ?>
                                                <label class="control-label">Acc Log Finger</label>
                                                <input type="text" name="log_finger" class="form-control" placeholder="Masukan Log finger Absensi" required>
                                                <label class="control-label">BPJS Kesehatan</label><br>
                                                <input type="checkbox" name="bpjs_ks" Value="0.02" class="flat"> BPJS Kesehatan <br>
                                                <label class="control-label">BPJS Ketenagakerjaan</label><br>
                                                <input type="checkbox" name="bpjs_jkk" Value="0.0054" class="flat"> JKK & JKM &nbsp;
                                                <input type="checkbox" name="bpjs_ijht" Value="0.057" class="flat"> IJHT &nbsp;
                                                <input type="checkbox" name="bpjs_jp" Value="0.03" class="flat"> JP &nbsp;<br>
                                                <label class="control-label">Status Karyawan</label><br>
                                                <input type="checkbox" name="status" value="AKTIF" class="js-switch" checked /> AKTIF
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"></label>
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
                    <table id="laporan" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>Nama Pegawai</th>
                                <th>Tanggal Masuk</th>
                                <th>Unit</th>
                                <th>Pendidikan</th>
                                <th>Status Nikah</th>
                                <th>Rumpun</th>
                                <th>Jabatan</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $list_pegawai = bukaquery("SELECT tm_pegawai.id_user, tm_pegawai.nip, tm_pegawai.nama_pegawai, tm_pegawai.tgl_masuk, tm_pegawai.pendidikan,
                                            tm_unit.id_unit, tm_unit.nama_unit, tm_pegawai.status_nikah, tm_pegawai.sub_bagian,tm_pegawai.pajak, tm_pegawai.rumpun,tm_pegawai.log_finger, 
                                            tm_pegawai.bpjs_ks, tm_pegawai.bpjs_jkk,tm_pegawai.status, tm_pegawai.bpjs_ijht, tm_pegawai.bpjs_jp, tm_level.nama_level, tm_level.id_level, tm_pegawai.id_kasatpel  
                                            FROM
                                            tm_pegawai
                                            INNER JOIN tm_unit ON tm_pegawai.id_unit = tm_unit.id_unit
                                            INNER JOIN tm_user ON tm_user.id_user = tm_pegawai.id_user
                                            INNER JOIN tm_level ON tm_user.id_level = tm_level.id_level
                                            where tm_pegawai.status_pegawai='PNS'
                                            ORDER BY FIELD(tm_pegawai.status, 'AKTIF', 'NONAKTIF', '') ASC, tm_pegawai.id_unit, tm_pegawai.nama_pegawai");
                            while ($row = fetch_array($list_pegawai)) {
                                $no++;
                            ?>
                                <!-- Edit Modal SKP -->
                                <div class="modal fade" id="modal-reset-<?php echo $row['id_user']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=reset-master-pegawai-pns&id=' . $row['id_user'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Reset Password</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label>Yakin Anda ingin Mereset Password Atas nama <b><?php echo $row['nama_pegawai']; ?></b>,klik Tombol Ya? Untuk Mereset Password</label>
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
                                    <td><?php echo $row['nip']; ?></td>
                                    <td><?php echo $row['nama_pegawai']; ?></td>
                                    <td><?php echo konversiTanggal($row['tgl_masuk']); ?></td>
                                    <td><?php echo $row['nama_unit']; ?></td>
                                    <td><?php echo $row['pendidikan']; ?></td>
                                    <td><?php echo ucwords(strtolower($row['status_nikah'])); ?></td>
                                    <td><?php echo $row['rumpun']; ?></td>
                                    <td><?php echo $row['nama_level']; ?></td>
                                    <td>
                                        <?php if ($row['status'] == 'AKTIF') { ?>
                                            <span class="fa fa-check" title="Pegawai Aktif"> AKTIF</span>
                                        <?php } else { ?>
                                            <span class="fa fa-close" title="Pegawai NON Aktif"> NON-AKTIF</span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <a href="?<?php echo paramEncrypt('module=master-data&act=update-data-pegawai&id=' . $row['id_user']); ?>">
                                            <i title="update Pegawai" class="btn-xs btn-warning fa fa-edit"></i>
                                        </a>
                                        <?php if ($superuser != '' or $_SESSION['id_level'] == 'LVL-000010') { ?>
                                            <span data-toggle="modal" data-target="#modal-reset-<?php echo $row['id_user']; ?>" title="Reset Pass" class="btn-xs btn-danger fa fa-refresh"> Reset Pass</span>
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

    case "list-master-data-pegawai-non-pns":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST DATA PEGAWAI NON PNS</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <button type="button" class="btn btn-info fa fa-plus" data-toggle="modal" data-target="#modal-add-pegawai-pns">
                    Buat Pegawai Non PNS
                </button>
                <p></p>
                <!--Modal Add SKP -->
                <div class="modal fade" id="modal-add-pegawai-pns">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title fa fa-plus"> Tambah Data Pegawai Non PNS</h4>
                            </div>
                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=master-data&act=add-master-pegawai-non-pns'); ?>" method="post">
                                <div class="modal-body">
                                    <div class="modal-body">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">NIP</label>
                                                <input type="number" name="nip" class="form-control" placeholder="Masukan NIP" required>
                                                <label class="control-label">Nama Pegawai</label>
                                                <input type="text" name="nama_pegawai" class="form-control" placeholder="Nama Lengkap" required>
                                                <label class="control-label">No. Handphone (WA)</label>
                                                <input type="text" name="no_hp_wa" class="form-control" placeholder="No. Handphone (WA) Ex:08xxxxxxxxxx" required>
                                                <label class="control-label">No. Handphone (SMS)</label>
                                                <input type="text" name="no_hp_sms" class="form-control" placeholder="No. Handphone (SMS) Ex:08xxxxxxxxxx" required>
                                                <label class="control-label">Tanggak Masuk</label>
                                                <input type="text" class="form-control" id="datepicker" name="tgl_masuk" placeholder="dd/mm/yyyy" required>
                                                <label class="control-label">Unit/Bagian</label>
                                                <select class="form-control select2" name="id_unit" data-placeholder="-Pilih Unit/Bagian-" style="width: 100%;" required>
                                                    <option selected="selected" value="">-Pilih Unit/Bagian-</option>
                                                    <?php
                                                    $tm_unit = bukaquery("select tm_unit.id_unit,tm_unit.nama_unit from tm_unit");
                                                    while ($row = fetch_array($tm_unit)) {
                                                        echo "<option value=" . $row['id_unit'] . ">" . $row['nama_unit'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <label class="control-label">Sub Bagian<font color="red">*</font></label>
                                                <select class='form-control select2' data-placeholder='' style='width: 100%;' name='sub_bagian' required=''>
                                                    <?php
                                                    $tm_sub_bagian = bukaquery("
                                                            SELECT
                                                                a.sub_bagian
                                                            FROM tm_sub_bagian a
                                                        ");
                                                    while ($row = fetch_array($tm_sub_bagian)) {

                                                        echo "<option value='" . $row['sub_bagian'] . "' >" . $row['sub_bagian'] . "</option>";;
                                                    }
                                                    ?>
                                                </select>
                                                <label class="control-label">Kasatpel<font color="red">*</font></label>
                                                <select class="form-control select2" name="id_kasatpel" data-placeholder="-Pilih Kasatpel-" style="width: 100%;" required>
                                                    <option selected="selected" value="">-Pilih Kasatpel-</option>
                                                    <?php
                                                    $tm_kasatpel = bukaquery("select tm_kasatpel.id_kasatpel,tm_kasatpel.nama_kasatpel from tm_kasatpel");
                                                    while ($row = fetch_array($tm_kasatpel)) {
                                                        echo "<option value=" . $row['id_kasatpel'] . ">" . $row['nama_kasatpel'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <label class="control-label">Level Petugas<font color="red">*</font></label>
                                                <select class="form-control select2" name="id_level" data-placeholder="-Level Petugas-" style="width: 100%;" required>
                                                    <option selected="selected" value="">-Pilih Level Petugas-</option>
                                                    <?php
                                                    $tm_unit = bukaquery("select tm_level.id_level,tm_level.nama_level from tm_level");
                                                    while ($row = fetch_array($tm_unit)) {
                                                        echo "<option value=" . $row['id_level'] . ">" . $row['nama_level'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="control-label">Status Nikah</label>
                                                <?php echo enumDropdown("tm_pegawai", "status_nikah", "", "-Pilih Status Nikah-"); ?>
                                                <label class="control-label">PTKP</label>
                                                <?php echo enumDropdown("tm_pegawai", "pajak", "", "-Pilih Pajak-"); ?>
                                                <label class="control-label">Pendidikan</label>
                                                <?php echo enumDropdown("tm_pegawai", "pendidikan", "", "-Pilih Pendidikan-"); ?>
                                                <label class="control-label">Rumpun</label>
                                                <?php echo enumDropdown("tm_pegawai", "rumpun", "", "-Pilih Rumpun-"); ?>
                                                <label class="control-label">Acc Log Finger</label>
                                                <input type="text" name="log_finger" class="form-control" placeholder="Masukan Log finger Absensi" required>
                                                <label class="control-label">BPJS Kesehatan</label><br>
                                                <input type="checkbox" name="bpjs_ks" Value="0.02" class="flat"> BPJS Kesehatan <br>
                                                <label class="control-label">BPJS Ketenagakerjaan</label><br>
                                                <input type="checkbox" name="bpjs_jkk" Value="0.0054" class="flat"> JKK & JKM &nbsp;
                                                <input type="checkbox" name="bpjs_ijht" Value="0.057" class="flat"> IJHT &nbsp;
                                                <input type="checkbox" name="bpjs_jp" Value="0.03" class="flat"> JP &nbsp;<br>
                                                <label class="control-label">Status Karyawan</label><br>
                                                <input type="checkbox" name="status" value="AKTIF" class="js-switch" checked /> AKTIF
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"></label>
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
                    <table id="laporan" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>NIK</th>
                                <th>Nama Pegawai</th>
                                <th>Tanggal Masuk</th>
                                <th>Unit</th>
                                <th>Pendidikan</th>
                                <th>Status Nikah</th>
                                <th>Rumpun</th>
                                <th>Jabatan</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $list_pegawai = bukaquery("SELECT tm_pegawai.id_user, tm_pegawai.nip, tm_pegawai.nik, tm_pegawai.nama_pegawai, tm_pegawai.tgl_masuk, tm_pegawai.pendidikan,
                                            tm_unit.id_unit, tm_unit.nama_unit, tm_pegawai.status_nikah, tm_pegawai.sub_bagian,tm_pegawai.pajak, tm_pegawai.rumpun,tm_pegawai.log_finger, 
                                            tm_pegawai.bpjs_ks, tm_pegawai.bpjs_jkk,tm_pegawai.status, tm_pegawai.bpjs_ijht, tm_pegawai.bpjs_jp, tm_level.nama_level, tm_level.id_level, tm_pegawai.id_kasatpel
                                            FROM
                                            tm_pegawai
                                            INNER JOIN tm_unit ON tm_pegawai.id_unit = tm_unit.id_unit
                                            INNER JOIN tm_user ON tm_user.id_user = tm_pegawai.id_user
                                            INNER JOIN tm_level ON tm_user.id_level = tm_level.id_level
                                            WHERE tm_pegawai.status_pegawai='NON PNS'
                                            ORDER BY FIELD(tm_pegawai.status, 'AKTIF', 'NONAKTIF', '') ASC, tm_pegawai.id_unit, tm_pegawai.nama_pegawai");
                            while ($row = fetch_array($list_pegawai)) {
                                $no++;
                            ?>
                                <!-- Edit Modal SKP -->
                                <div class="modal fade" id="modal-reset-<?php echo $row['id_user']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=reset-master-pegawai-non-pns&id=' . $row['id_user'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Reset Password</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label>Yakin Anda ingin Mereset Password Atas nama <b><?php echo $row['nama_pegawai']; ?></b>,klik Tombol Ya? Untuk Mereset Password</label>
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
                                    <td><?php echo $row['nip']; ?></td>
                                    <td><?php echo $row['nik']; ?></td>
                                    <td><?php echo $row['nama_pegawai']; ?></td>
                                    <td><?php echo konversiTanggal($row['tgl_masuk']); ?></td>
                                    <td><?php echo $row['nama_unit']; ?></td>
                                    <td><?php echo $row['pendidikan']; ?></td>
                                    <td><?php echo ucwords(strtolower($row['status_nikah'])); ?></td>
                                    <td><?php echo $row['rumpun']; ?></td>
                                    <td><?php echo $row['nama_level']; ?></td>
                                    <td>
                                        <?php if ($row['status'] == 'AKTIF') { ?>
                                            <span class="green fa fa-check" title="Pegawai Aktif"> </span>
                                        <?php } else { ?>
                                            <span class="fa fa-close danger" title="Pegawai NON Aktif"> </span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <a href="?<?php echo paramEncrypt('module=master-data&act=update-data-pegawai&id=' . $row['id_user']); ?>">
                                            <i title="update Pegawai" class="btn-xs btn-warning fa fa-edit"></i>
                                        </a>
                                        <a href="?<?php echo paramEncrypt('module=simpeg&act=profile-pegawai&id=' . $row['id_user'] . '') ?>"><span title="Profil" class="btn-xs btn-info fa fa-user"></span></a>
                                        <?php if ($superuser != '' or $_SESSION['id_level'] == 'LVL-000010') { ?>
                                            <span data-toggle="modal" data-target="#modal-reset-<?php echo $row['id_user']; ?>" title="Reset Pass" class="btn-xs btn-danger fa fa-refresh"> Reset Pass</span>
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
    case "list-master-data-pegawai-pjlp":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST DATA PEGAWAI PJLP</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <button type="button" class="btn btn-info fa fa-plus" data-toggle="modal" data-target="#modal-add-pegawai-pjlp">
                    Buat Pegawai PJLP
                </button>
                <p></p>
                <!--Modal Add SKP -->
                <div class="modal fade" id="modal-add-pegawai-pjlp">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title fa fa-plus"> Tambah Data Pegawai PJLP</h4>
                            </div>
                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=master-data&act=add-master-pegawai-pjlp'); ?>" method="post">
                                <div class="modal-body">
                                    <div class="modal-body">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">NIP</label>
                                                <input type="number" name="nip" class="form-control" placeholder="Masukan NIP" required>
                                                <label class="control-label">Nama Pegawai</label>
                                                <input type="text" name="nama_pegawai" class="form-control" placeholder="Nama Lengkap" required>
                                                <label class="control-label">No. Handphone (WA)</label>
                                                <input type="text" name="no_hp_wa" class="form-control" placeholder="No. Handphone (WA) Ex:08xxxxxxxxxx" required>
                                                <label class="control-label">No. Handphone (SMS)</label>
                                                <input type="text" name="no_hp_sms" class="form-control" placeholder="No. Handphone (SMS) Ex:08xxxxxxxxxx" required>
                                                <label class="control-label">Tanggak Masuk</label>
                                                <input type="text" class="form-control" id="datepicker" name="tgl_masuk" placeholder="dd/mm/yyyy" required>
                                                <label class="control-label">Unit/Bagian</label>
                                                <select class="form-control select2" name="id_unit" data-placeholder="-Pilih Unit/Bagian-" style="width: 100%;" required>
                                                    <option selected="selected" value="">-Pilih Unit/Bagian-</option>
                                                    <?php
                                                    $tm_unit = bukaquery("select tm_unit.id_unit,tm_unit.nama_unit from tm_unit");
                                                    while ($row = fetch_array($tm_unit)) {
                                                        echo "<option value=" . $row['id_unit'] . ">" . $row['nama_unit'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <label class="control-label">Sub Bagian<font color="red">*</font></label>
                                                <select class='form-control select2' data-placeholder='' style='width: 100%;' name='sub_bagian' required=''>
                                                    <?php
                                                    $tm_sub_bagian = bukaquery("
                                                            SELECT
                                                                a.sub_bagian
                                                            FROM tm_sub_bagian a
                                                        ");
                                                    while ($row = fetch_array($tm_sub_bagian)) {

                                                        echo "<option value='" . $row['sub_bagian'] . "' >" . $row['sub_bagian'] . "</option>";;
                                                    }
                                                    ?>
                                                </select>
                                                <label class="control-label">Kasatpel<font color="red">*</font></label>
                                                <select class="form-control select2" name="id_kasatpel" data-placeholder="-Pilih Kasatpel-" style="width: 100%;" required>
                                                    <option selected="selected" value="">-Pilih Kasatpel-</option>
                                                    <?php
                                                    $tm_kasatpel = bukaquery("select tm_kasatpel.id_kasatpel,tm_kasatpel.nama_kasatpel from tm_kasatpel");
                                                    while ($row = fetch_array($tm_kasatpel)) {
                                                        echo "<option value=" . $row['id_kasatpel'] . ">" . $row['nama_kasatpel'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <label class="control-label">Level Petugas<font color="red">*</font></label>
                                                <select class="form-control select2" name="id_level" data-placeholder="-Level Petugas-" style="width: 100%;" required>
                                                    <option selected="selected" value="">-Pilih Level Petugas-</option>
                                                    <?php
                                                    $tm_unit = bukaquery("select tm_level.id_level,tm_level.nama_level from tm_level");
                                                    while ($row = fetch_array($tm_unit)) {
                                                        echo "<option value=" . $row['id_level'] . ">" . $row['nama_level'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="control-label">Status Nikah</label>
                                                <?php echo enumDropdown("tm_pegawai", "status_nikah", "", "-Pilih Status Nikah-"); ?>
                                                <label class="control-label">PTKP</label>
                                                <?php echo enumDropdown("tm_pegawai", "pajak", "", "-Pilih Pajak-"); ?>
                                                <label class="control-label">Pendidikan</label>
                                                <?php echo enumDropdown("tm_pegawai", "pendidikan", "", "-Pilih Pendidikan-"); ?>
                                                <label class="control-label">Rumpun</label>
                                                <?php echo enumDropdown("tm_pegawai", "rumpun", "", "-Pilih Rumpun-"); ?>
                                                <label class="control-label">Acc Log Finger</label>
                                                <input type="text" name="log_finger" class="form-control" placeholder="Masukan Log finger Absensi" required>
                                                <label class="control-label">BPJS Kesehatan</label><br>
                                                <input type="checkbox" name="bpjs_ks" Value="0.02" class="flat"> BPJS Kesehatan <br>
                                                <label class="control-label">BPJS Ketenagakerjaan</label><br>
                                                <input type="checkbox" name="bpjs_jkk" Value="0.0054" class="flat"> JKK & JKM &nbsp;
                                                <input type="checkbox" name="bpjs_ijht" Value="0.057" class="flat"> IJHT &nbsp;
                                                <input type="checkbox" name="bpjs_jp" Value="0.03" class="flat"> JP &nbsp;<br>
                                                <label class="control-label">Status Karyawan</label><br>
                                                <input type="checkbox" name="status" value="AKTIF" class="js-switch" checked /> AKTIF
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"></label>
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
                    <table id="laporan" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>NIK</th>
                                <th>Nama Pegawai</th>
                                <th>Tanggal Masuk</th>
                                <th>Unit</th>
                                <th>Pendidikan</th>
                                <th>Status Nikah</th>
                                <th>Rumpun</th>
                                <th>Jabatan</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $list_pegawai = bukaquery("SELECT tm_pegawai.id_user, tm_pegawai.nip, tm_pegawai.nik, tm_pegawai.nama_pegawai, tm_pegawai.tgl_masuk, tm_pegawai.pendidikan,
                                            tm_unit.id_unit, tm_unit.nama_unit, tm_pegawai.status_nikah, tm_pegawai.sub_bagian,tm_pegawai.pajak, tm_pegawai.rumpun,tm_pegawai.log_finger, 
                                            tm_pegawai.bpjs_ks, tm_pegawai.bpjs_jkk,tm_pegawai.status, tm_pegawai.bpjs_ijht, tm_pegawai.bpjs_jp, tm_level.nama_level, tm_level.id_level, tm_pegawai.id_kasatpel
                                            FROM
                                            tm_pegawai
                                            INNER JOIN tm_unit ON tm_pegawai.id_unit = tm_unit.id_unit
                                            INNER JOIN tm_user ON tm_user.id_user = tm_pegawai.id_user
                                            INNER JOIN tm_level ON tm_user.id_level = tm_level.id_level
                                            WHERE tm_pegawai.status_pegawai='PJLP'
                                            ORDER BY FIELD(tm_pegawai.status, 'AKTIF', 'NONAKTIF', '') ASC, tm_pegawai.id_unit, tm_pegawai.nama_pegawai");
                            while ($row = fetch_array($list_pegawai)) {
                                $no++;
                            ?>
                                <!-- Edit Modal SKP -->
                                <div class="modal fade" id="modal-reset-<?php echo $row['id_user']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=reset-master-pegawai-pjlp&id=' . $row['id_user'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Reset Password</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label>Yakin Anda ingin Mereset Password Atas nama <b><?php echo $row['nama_pegawai']; ?></b>,klik Tombol Ya? Untuk Mereset Password</label>
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
                                    <td><?php echo $row['nip']; ?></td>
                                    <td><?php echo $row['nik']; ?></td>
                                    <td><?php echo $row['nama_pegawai']; ?></td>
                                    <td><?php echo konversiTanggal($row['tgl_masuk']); ?></td>
                                    <td><?php echo $row['nama_unit']; ?></td>
                                    <td><?php echo $row['pendidikan']; ?></td>
                                    <td><?php echo ucwords(strtolower($row['status_nikah'])); ?></td>
                                    <td><?php echo $row['rumpun']; ?></td>
                                    <td><?php echo $row['nama_level']; ?></td>
                                    <td>
                                        <?php if ($row['status'] == 'AKTIF') { ?>
                                            <span class="green fa fa-check" title="Pegawai Aktif"> </span>
                                        <?php } else { ?>
                                            <span class="fa fa-close danger" title="Pegawai NON Aktif"> </span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <a href="?<?php echo paramEncrypt('module=master-data&act=update-data-pegawai&id=' . $row['id_user']); ?>">
                                            <i title="update Pegawai" class="btn-xs btn-warning fa fa-edit"></i>
                                        </a>
                                        <a href="?<?php echo paramEncrypt('module=simpeg&act=profile-pegawai&id=' . $row['id_user'] . '') ?>"><span title="Profil" class="btn-xs btn-info fa fa-user"></span></a>
                                        <?php if ($superuser != '' or $_SESSION['id_level'] == 'LVL-000010') { ?>
                                            <span data-toggle="modal" data-target="#modal-reset-<?php echo $row['id_user']; ?>" title="Reset Pass" class="btn-xs btn-danger fa fa-refresh"> Reset Pass</span>
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
    case "list-master-data-pegawai-spesialis":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST DATA PEGAWAI DOKTER SPESIALIS</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <button type="button" class="btn btn-info fa fa-plus" data-toggle="modal" data-target="#modal-add-pegawai-spesialis">
                    Buat Pegawai Dokter Spesialis
                </button>
                <p></p>
                <!--Modal Add SKP -->
                <div class="modal fade" id="modal-add-pegawai-spesialis">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title fa fa-plus"> Tambah Data Pegawai Dokter Spesialis</h4>
                            </div>
                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=master-data&act=add-master-pegawai-spesialis'); ?>" method="post">
                                <div class="modal-body">
                                    <div class="modal-body">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">NIP</label>
                                                <input type="number" name="nip" class="form-control" placeholder="Masukan NIP" required>
                                                <label class="control-label">Nama Pegawai</label>
                                                <input type="text" name="nama_pegawai" class="form-control" placeholder="Nama Lengkap" required>
                                                <label class="control-label">No. Handphone (WA)</label>
                                                <input type="text" name="no_hp_wa" class="form-control" placeholder="No. Handphone (WA) Ex:08xxxxxxxxxx" required>
                                                <label class="control-label">No. Handphone (SMS)</label>
                                                <input type="text" name="no_hp_sms" class="form-control" placeholder="No. Handphone (SMS) Ex:08xxxxxxxxxx" required>
                                                <label class="control-label">Tanggak Masuk</label>
                                                <input type="text" class="form-control" id="datepicker" name="tgl_masuk" placeholder="dd/mm/yyyy" required>
                                                <label class="control-label">Unit/Bagian</label>
                                                <select class="form-control select2" name="id_unit" data-placeholder="-Pilih Unit/Bagian-" style="width: 100%;" required>
                                                    <option selected="selected" value="">-Pilih Unit/Bagian-</option>
                                                    <?php
                                                    $tm_unit = bukaquery("select tm_unit.id_unit,tm_unit.nama_unit from tm_unit");
                                                    while ($row = fetch_array($tm_unit)) {
                                                        echo "<option value=" . $row['id_unit'] . ">" . $row['nama_unit'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <label class="control-label">Sub Bagian<font color="red">*</font></label>
                                                <select class='form-control select2' data-placeholder='' style='width: 100%;' name='sub_bagian' required=''>
                                                    <?php
                                                    $tm_sub_bagian = bukaquery("
                                                            SELECT
                                                                a.sub_bagian
                                                            FROM tm_sub_bagian a
                                                        ");
                                                    while ($row = fetch_array($tm_sub_bagian)) {

                                                        echo "<option value='" . $row['sub_bagian'] . "' >" . $row['sub_bagian'] . "</option>";;
                                                    }
                                                    ?>
                                                </select>
                                                <label class="control-label">Kasatpel<font color="red">*</font></label>
                                                <select class="form-control select2" name="id_kasatpel" data-placeholder="-Pilih Kasatpel-" style="width: 100%;" required>
                                                    <option selected="selected" value="">-Pilih Kasatpel-</option>
                                                    <?php
                                                    $tm_kasatpel = bukaquery("select tm_kasatpel.id_kasatpel,tm_kasatpel.nama_kasatpel from tm_kasatpel");
                                                    while ($row = fetch_array($tm_kasatpel)) {
                                                        echo "<option value=" . $row['id_kasatpel'] . ">" . $row['nama_kasatpel'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <label class="control-label">Level Petugas<font color="red">*</font></label>
                                                <select class="form-control select2" name="id_level" data-placeholder="-Level Petugas-" style="width: 100%;" required>
                                                    <option selected="selected" value="">-Pilih Level Petugas-</option>
                                                    <?php
                                                    $tm_unit = bukaquery("select tm_level.id_level,tm_level.nama_level from tm_level");
                                                    while ($row = fetch_array($tm_unit)) {
                                                        echo "<option value=" . $row['id_level'] . ">" . $row['nama_level'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="control-label">Status Nikah</label>
                                                <?php echo enumDropdown("tm_pegawai", "status_nikah", "", "-Pilih Status Nikah-"); ?>
                                                <label class="control-label">PTKP</label>
                                                <?php echo enumDropdown("tm_pegawai", "pajak", "", "-Pilih Pajak-"); ?>
                                                <label class="control-label">Pendidikan</label>
                                                <?php echo enumDropdown("tm_pegawai", "pendidikan", "", "-Pilih Pendidikan-"); ?>
                                                <label class="control-label">Rumpun</label>
                                                <?php echo enumDropdown("tm_pegawai", "rumpun", "", "-Pilih Rumpun-"); ?>
                                                <label class="control-label">Acc Log Finger</label>
                                                <input type="text" name="log_finger" class="form-control" placeholder="Masukan Log finger Absensi" required>
                                                <label class="control-label">BPJS Kesehatan</label><br>
                                                <input type="checkbox" name="bpjs_ks" Value="0.02" class="flat"> BPJS Kesehatan <br>
                                                <label class="control-label">BPJS Ketenagakerjaan</label><br>
                                                <input type="checkbox" name="bpjs_jkk" Value="0.0054" class="flat"> JKK & JKM &nbsp;
                                                <input type="checkbox" name="bpjs_ijht" Value="0.057" class="flat"> IJHT &nbsp;
                                                <input type="checkbox" name="bpjs_jp" Value="0.03" class="flat"> JP &nbsp;<br>
                                                <label class="control-label">Status Karyawan</label><br>
                                                <input type="checkbox" name="status" value="AKTIF" class="js-switch" checked /> AKTIF
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"></label>
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
                    <table id="laporan" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>NIK</th>
                                <th>Nama Pegawai</th>
                                <th>Tanggal Masuk</th>
                                <th>Unit</th>
                                <th>Pendidikan</th>
                                <th>Status Nikah</th>
                                <th>Rumpun</th>
                                <th>Jabatan</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $list_pegawai = bukaquery("SELECT tm_pegawai.id_user, tm_pegawai.nip, tm_pegawai.nik, tm_pegawai.nama_pegawai, tm_pegawai.tgl_masuk, tm_pegawai.pendidikan,
                                            tm_unit.id_unit, tm_unit.nama_unit, tm_pegawai.status_nikah, tm_pegawai.sub_bagian,tm_pegawai.pajak, tm_pegawai.rumpun,tm_pegawai.log_finger, 
                                            tm_pegawai.bpjs_ks, tm_pegawai.bpjs_jkk,tm_pegawai.status, tm_pegawai.bpjs_ijht, tm_pegawai.bpjs_jp, tm_level.nama_level, tm_level.id_level, tm_pegawai.id_kasatpel
                                            FROM
                                            tm_pegawai
                                            INNER JOIN tm_unit ON tm_pegawai.id_unit = tm_unit.id_unit
                                            INNER JOIN tm_user ON tm_user.id_user = tm_pegawai.id_user
                                            INNER JOIN tm_level ON tm_user.id_level = tm_level.id_level
                                            WHERE tm_pegawai.status_pegawai='SPESIALIS'
                                            ORDER BY FIELD(tm_pegawai.status, 'AKTIF', 'NONAKTIF', '') ASC, tm_pegawai.nama_pegawai");
                            while ($row = fetch_array($list_pegawai)) {
                                $no++;
                            ?>
                                <!-- Edit Modal SKP -->
                                <div class="modal fade" id="modal-reset-<?php echo $row['id_user']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=reset-master-pegawai-spesialis&id=' . $row['id_user'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Reset Password</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label>Yakin Anda ingin Mereset Password Atas nama <b><?php echo $row['nama_pegawai']; ?></b>,klik Tombol Ya? Untuk Mereset Password</label>
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
                                    <td><?php echo $row['nip']; ?></td>
                                    <td><?php echo $row['nik']; ?></td>
                                    <td><?php echo $row['nama_pegawai']; ?></td>
                                    <td><?php echo konversiTanggal($row['tgl_masuk']); ?></td>
                                    <td><?php echo $row['nama_unit']; ?></td>
                                    <td><?php echo $row['pendidikan']; ?></td>
                                    <td><?php echo ucwords(strtolower($row['status_nikah'])); ?></td>
                                    <td><?php echo $row['rumpun']; ?></td>
                                    <td><?php echo $row['nama_level']; ?></td>
                                    <td>
                                        <?php if ($row['status'] == 'AKTIF') { ?>
                                            <span class="green fa fa-check" title="Pegawai Aktif"> </span>
                                        <?php } else { ?>
                                            <span class="fa fa-close danger" title="Pegawai NON Aktif"> </span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <a href="?<?php echo paramEncrypt('module=master-data&act=update-data-pegawai&id=' . $row['id_user']); ?>">
                                            <i title="update Pegawai" class="btn-xs btn-warning fa fa-edit"></i>
                                        </a>
                                        <a href="?<?php echo paramEncrypt('module=simpeg&act=profile-pegawai&id=' . $row['id_user'] . '') ?>"><span title="Profil" class="btn-xs btn-info fa fa-user"></span></a>
                                        <?php if ($superuser != '' or $_SESSION['id_level'] == 'LVL-000010') { ?>
                                            <span data-toggle="modal" data-target="#modal-reset-<?php echo $row['id_user']; ?>" title="Reset Pass" class="btn-xs btn-danger fa fa-refresh"> Reset Pass</span>
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
    case "list-sip-pegawai-non-pns":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST DATA SIP PEGAWAI</h3>
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
                                <th>No SIP</th>
                                <th>Periode</th>
                                <th>File SIP</th>
                                <th>Status</th>
                                <th>Menuju Kadaluarsa (hari)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $list_sip = bukaquery("
                                SELECT
                                    a.id_sip, a.nip, a.id_user, a.nama_pegawai, a.no_sip, a.periode, a.file_sip, a.periode_from, a.periode_to, a.date_now, a.selisih, a.expired
                                FROM (
                                    SELECT
                                        a.id_sip, a.nip, a.id_user, a.nama_pegawai, a.no_sip, a.periode, a.file_sip, a.periode_from, a.periode_to, a.date_now, a.selisih,
                                        IF(a.selisih > 180, 0, if(a.selisih = 0, 2, 1)) AS expired
                                    FROM (
                                        SELECT
                                            a.id_sip, a.id_user, b.nip, b.nama_pegawai, a.no_sip, a.periode, a.file_sip,
                                            STR_TO_DATE(SUBSTRING_INDEX(a.periode, ' - ', 1), '%d-%m-%Y') AS periode_from,
                                            STR_TO_DATE(SUBSTRING_INDEX(a.periode, ' - ', -1), '%d-%m-%Y') AS periode_to,
                                            DATE(NOW()) AS date_now,
                                            GREATEST(DATEDIFF(STR_TO_DATE(SUBSTRING_INDEX(a.periode, ' - ', -1), '%d-%m-%Y'), DATE(NOW())), 0) AS selisih
                                        FROM tm_sip a
                                            INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                                    ) AS a
                                ) AS a
                                LEFT JOIN (
                                    SELECT
                                        a.id_sip, a.nip, a.id_user, a.nama_pegawai, a.no_sip, a.periode, a.file_sip, a.periode_from, a.periode_to, a.date_now, a.selisih, 
                                        IF(a.selisih > 180, 0, if(a.selisih = 0, 2, 1)) AS expired
                                    FROM (
                                        SELECT
                                            a.id_sip, a.id_user, b.nip, b.nama_pegawai, a.no_sip, a.periode, a.file_sip,
                                            STR_TO_DATE(SUBSTRING_INDEX(a.periode, ' - ', 1), '%d-%m-%Y') AS periode_from,
                                            STR_TO_DATE(SUBSTRING_INDEX(a.periode, ' - ', -1), '%d-%m-%Y') AS periode_to,
                                            DATE(NOW()) AS date_now,
                                            GREATEST(DATEDIFF(STR_TO_DATE(SUBSTRING_INDEX(a.periode, ' - ', -1), '%d-%m-%Y'), DATE(NOW())), 0) AS selisih
                                        FROM tm_sip a
                                            INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                                    ) AS a
                                ) b ON a.id_user = b.id_user AND a.periode_to < b.periode_to
                                WHERE b.id_sip IS NULL
                                ORDER BY a.expired DESC, a.selisih ASC, a.nama_pegawai
                            ");
                            while ($row = fetch_array($list_sip)) {
                                $no++;
                            ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $row['nip']; ?></td>
                                    <td><?php echo $row['nama_pegawai']; ?></td>
                                    <td><?php echo $row['no_sip']; ?></td>
                                    <td><?php echo $row['periode']; ?></td>
                                    <td><a href="simpeg/<?php echo $row['file_sip']; ?>" target="_blank"><?php echo $row['file_sip']; ?></a></td>
                                    <td><?php echo $row['expired'] == '0'
                                            ? '<span class="label label-success">Aktif</span>'
                                            : (
                                                $row['expired'] == '1'
                                                ? '<span class="label label-warning">Aktif</span>'
                                                : '<span class="label label-danger">Tidak Aktif</span>'
                                            ); ?></td>
                                    <td><?php echo $row['selisih']; ?></td>
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
    case "list-str-pegawai-non-pns":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST DATA STR PEGAWAI</h3>
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
                                <th>No STR</th>
                                <th>Periode</th>
                                <th>File STR</th>
                                <th>Status</th>
                                <th>Menuju Kadaluarsa (hari)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $list_str = bukaquery("
                                SELECT
                                    a.id_str, a.nip, a.id_user, a.nama_pegawai, a.no_str, a.periode, a.file_str, a.periode_from, a.periode_to, a.date_now, a.selisih, a.expired
                                FROM (
                                    SELECT
                                        a.id_str, a.nip, a.id_user, a.nama_pegawai, a.no_str, a.periode, a.file_str, a.periode_from, a.periode_to, a.date_now, a.selisih,
                                        IF(a.selisih > 180, 0, if(a.selisih = 0, 2, 1)) AS expired
                                    FROM (
                                        SELECT
                                            a.id_str, a.id_user, b.nip, b.nama_pegawai, a.no_str, a.periode, a.file_str,
                                            STR_TO_DATE(SUBSTRING_INDEX(a.periode, ' - ', 1), '%d-%m-%Y') AS periode_from,
                                            STR_TO_DATE(SUBSTRING_INDEX(a.periode, ' - ', -1), '%d-%m-%Y') AS periode_to,
                                            DATE(NOW()) AS date_now,
                                            GREATEST(DATEDIFF(STR_TO_DATE(SUBSTRING_INDEX(a.periode, ' - ', -1), '%d-%m-%Y'), DATE(NOW())), 0) AS selisih
                                        FROM tm_str a
                                            INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                                    ) AS a
                                ) AS a
                                LEFT JOIN (
                                    SELECT
                                        a.id_str, a.nip, a.id_user, a.nama_pegawai, a.no_str, a.periode, a.file_str, a.periode_from, a.periode_to, a.date_now, a.selisih, 
                                        IF(a.selisih > 180, 0, if(a.selisih = 0, 2, 1)) AS expired
                                    FROM (
                                        SELECT
                                            a.id_str, a.id_user, b.nip, b.nama_pegawai, a.no_str, a.periode, a.file_str,
                                            STR_TO_DATE(SUBSTRING_INDEX(a.periode, ' - ', 1), '%d-%m-%Y') AS periode_from,
                                            STR_TO_DATE(SUBSTRING_INDEX(a.periode, ' - ', -1), '%d-%m-%Y') AS periode_to,
                                            DATE(NOW()) AS date_now,
                                            GREATEST(DATEDIFF(STR_TO_DATE(SUBSTRING_INDEX(a.periode, ' - ', -1), '%d-%m-%Y'), DATE(NOW())), 0) AS selisih
                                        FROM tm_str a
                                            INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                                    ) AS a
                                ) b ON a.id_user = b.id_user AND a.periode_to < b.periode_to
                                WHERE b.id_str IS NULL
                                ORDER BY a.expired DESC, a.selisih ASC, a.nama_pegawai
                            ");
                            while ($row = fetch_array($list_str)) {
                                $no++;
                            ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $row['nip']; ?></td>
                                    <td><?php echo $row['nama_pegawai']; ?></td>
                                    <td><?php echo $row['no_str']; ?></td>
                                    <td><?php echo $row['periode']; ?></td>
                                    <td><a href="simpeg/<?php echo $row['file_str']; ?>" target="_blank"><?php echo $row['file_str']; ?></a></td>
                                    <td><?php echo $row['expired'] == '0'
                                            ? '<span class="label label-success">Aktif</span>'
                                            : (
                                                $row['expired'] == '1'
                                                ? '<span class="label label-warning">Aktif</span>'
                                                : '<span class="label label-danger">Tidak Aktif</span>'
                                            ); ?></td>
                                    <td><?php echo $row['selisih']; ?></td>
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
    case "list-spk-pegawai-non-pns":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST DATA SPK PEGAWAI</h3>
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
                                <th>No SPK</th>
                                <th>Periode</th>
                                <th>File SPK</th>
                                <th>Status</th>
                                <th>Menuju Kadaluarsa (hari)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $list_spk = bukaquery("
                                SELECT
                                    a.id_spk, a.nip, a.id_user, a.nama_pegawai, a.no_spk, a.periode, a.file_spk, a.periode_from, a.periode_to, a.date_now, a.selisih, a.expired
                                FROM (
                                    SELECT
                                        a.id_spk, a.nip, a.id_user, a.nama_pegawai, a.no_spk, a.periode, a.file_spk, a.periode_from, a.periode_to, a.date_now, a.selisih,
                                        IF(a.selisih > 180, 0, if(a.selisih = 0, 2, 1)) AS expired
                                    FROM (
                                        SELECT
                                            a.id_spk, a.id_user, b.nip, b.nama_pegawai, a.no_spk, a.periode, a.file_spk,
                                            STR_TO_DATE(SUBSTRING_INDEX(a.periode, ' - ', 1), '%d-%m-%Y') AS periode_from,
                                            STR_TO_DATE(SUBSTRING_INDEX(a.periode, ' - ', -1), '%d-%m-%Y') AS periode_to,
                                            DATE(NOW()) AS date_now,
                                            GREATEST(DATEDIFF(STR_TO_DATE(SUBSTRING_INDEX(a.periode, ' - ', -1), '%d-%m-%Y'), DATE(NOW())), 0) AS selisih
                                        FROM tm_spk a
                                            INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                                    ) AS a
                                ) AS a
                                LEFT JOIN (
                                    SELECT
                                        a.id_spk, a.nip, a.id_user, a.nama_pegawai, a.no_spk, a.periode, a.file_spk, a.periode_from, a.periode_to, a.date_now, a.selisih, 
                                        IF(a.selisih > 180, 0, if(a.selisih = 0, 2, 1)) AS expired
                                    FROM (
                                        SELECT
                                            a.id_spk, a.id_user, b.nip, b.nama_pegawai, a.no_spk, a.periode, a.file_spk,
                                            STR_TO_DATE(SUBSTRING_INDEX(a.periode, ' - ', 1), '%d-%m-%Y') AS periode_from,
                                            STR_TO_DATE(SUBSTRING_INDEX(a.periode, ' - ', -1), '%d-%m-%Y') AS periode_to,
                                            DATE(NOW()) AS date_now,
                                            GREATEST(DATEDIFF(STR_TO_DATE(SUBSTRING_INDEX(a.periode, ' - ', -1), '%d-%m-%Y'), DATE(NOW())), 0) AS selisih
                                        FROM tm_spk a
                                            INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                                    ) AS a
                                ) b ON a.id_user = b.id_user AND a.periode_to < b.periode_to
                                WHERE b.id_spk IS NULL
                                ORDER BY a.expired DESC, a.selisih ASC, a.nama_pegawai
                            ");
                            while ($row = fetch_array($list_spk)) {
                                $no++;
                            ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $row['nip']; ?></td>
                                    <td><?php echo $row['nama_pegawai']; ?></td>
                                    <td><?php echo $row['no_spk']; ?></td>
                                    <td><?php echo $row['periode']; ?></td>
                                    <td><a href="simpeg/<?php echo $row['file_spk']; ?>" target="_blank"><?php echo $row['file_spk']; ?></a></td>
                                    <td><?php echo $row['expired'] == '0'
                                            ? '<span class="label label-success">Aktif</span>'
                                            : (
                                                $row['expired'] == '1'
                                                ? '<span class="label label-warning">Aktif</span>'
                                                : '<span class="label label-danger">Tidak Aktif</span>'
                                            ); ?></td>
                                    <td><?php echo $row['selisih']; ?></td>
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
    case "list-rkk-pegawai-non-pns":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST DATA RKK PEGAWAI</h3>
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
                                <th>No RKK</th>
                                <th>Periode</th>
                                <th>File RKK</th>
                                <th>Status</th>
                                <th>Menuju Kadaluarsa (hari)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $list_rkk = bukaquery("
                                SELECT
                                    a.id_rkk, a.nip, a.id_user, a.nama_pegawai, a.no_rkk, a.periode, a.file_rkk, a.periode_from, a.periode_to, a.date_now, a.selisih, a.expired
                                FROM (
                                    SELECT
                                        a.id_rkk, a.nip, a.id_user, a.nama_pegawai, a.no_rkk, a.periode, a.file_rkk, a.periode_from, a.periode_to, a.date_now, a.selisih,
                                        IF(a.selisih > 180, 0, if(a.selisih = 0, 2, 1)) AS expired
                                    FROM (
                                        SELECT
                                            a.id_rkk, a.id_user, b.nip, b.nama_pegawai, a.no_rkk, a.periode, a.file_rkk,
                                            STR_TO_DATE(SUBSTRING_INDEX(a.periode, ' - ', 1), '%d-%m-%Y') AS periode_from,
                                            STR_TO_DATE(SUBSTRING_INDEX(a.periode, ' - ', -1), '%d-%m-%Y') AS periode_to,
                                            DATE(NOW()) AS date_now,
                                            GREATEST(DATEDIFF(STR_TO_DATE(SUBSTRING_INDEX(a.periode, ' - ', -1), '%d-%m-%Y'), DATE(NOW())), 0) AS selisih
                                        FROM tm_rkk a
                                            INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                                    ) AS a
                                ) AS a
                                LEFT JOIN (
                                    SELECT
                                        a.id_rkk, a.nip, a.id_user, a.nama_pegawai, a.no_rkk, a.periode, a.file_rkk, a.periode_from, a.periode_to, a.date_now, a.selisih, 
                                        IF(a.selisih > 180, 0, if(a.selisih = 0, 2, 1)) AS expired
                                    FROM (
                                        SELECT
                                            a.id_rkk, a.id_user, b.nip, b.nama_pegawai, a.no_rkk, a.periode, a.file_rkk,
                                            STR_TO_DATE(SUBSTRING_INDEX(a.periode, ' - ', 1), '%d-%m-%Y') AS periode_from,
                                            STR_TO_DATE(SUBSTRING_INDEX(a.periode, ' - ', -1), '%d-%m-%Y') AS periode_to,
                                            DATE(NOW()) AS date_now,
                                            GREATEST(DATEDIFF(STR_TO_DATE(SUBSTRING_INDEX(a.periode, ' - ', -1), '%d-%m-%Y'), DATE(NOW())), 0) AS selisih
                                        FROM tm_rkk a
                                            INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                                    ) AS a
                                ) b ON a.id_user = b.id_user AND a.periode_to < b.periode_to
                                WHERE b.id_rkk IS NULL
                                ORDER BY a.expired DESC, a.selisih ASC, a.nama_pegawai
                            ");
                            while ($row = fetch_array($list_rkk)) {
                                $no++;
                            ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $row['nip']; ?></td>
                                    <td><?php echo $row['nama_pegawai']; ?></td>
                                    <td><?php echo $row['no_rkk']; ?></td>
                                    <td><?php echo $row['periode']; ?></td>
                                    <td><a href="simpeg/<?php echo $row['file_rkk']; ?>" target="_blank"><?php echo $row['file_rkk']; ?></a></td>
                                    <td><?php echo $row['expired'] == '0'
                                            ? '<span class="label label-success">Aktif</span>'
                                            : (
                                                $row['expired'] == '1'
                                                ? '<span class="label label-warning">Aktif</span>'
                                                : '<span class="label label-danger">Tidak Aktif</span>'
                                            ); ?></td>
                                    <td><?php echo $row['selisih']; ?></td>
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
    case "update-data-pegawai":
    ?>
        <!--Modal Add waktu -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-user"> UPDATE DATA PEGAWAI </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="col-md-8">
                    <?php $pegawai = fetch_array(bukaquery("SELECT tm_pegawai.id_user, tm_pegawai.nip, tm_pegawai.nik, tm_pegawai.nama_pegawai, tm_pegawai.no_hp_wa, tm_pegawai.no_hp_sms, tm_pegawai.tgl_masuk, tm_pegawai.pendidikan, tm_pegawai.status_pegawai,
                                            tm_unit.id_unit, tm_unit.nama_unit, tm_pegawai.status_nikah, tm_pegawai.sub_bagian,tm_pegawai.pajak, tm_pegawai.rumpun,tm_pegawai.log_finger, 
                                            tm_pegawai.bpjs_ks, tm_pegawai.bpjs_jkk,tm_pegawai.status, tm_pegawai.bpjs_ijht, tm_pegawai.bpjs_jp, tm_level.nama_level, tm_level.id_level, tm_pegawai.id_kasatpel
                                            FROM
                                            tm_pegawai
                                            INNER JOIN tm_unit ON tm_pegawai.id_unit = tm_unit.id_unit
                                            INNER JOIN tm_user ON tm_user.id_user = tm_pegawai.id_user
                                            INNER JOIN tm_level ON tm_user.id_level = tm_level.id_level
                                            where tm_pegawai.id_user='$id'"));
                    $url_target = $pegawai['status_pegawai'] == 'PNS'
                        ? $aksi . paramEncrypt('module=master-data&act=update-master-pegawai-pns&id=' . $id . '')
                        : $aksi . paramEncrypt('module=master-data&act=update-master-pegawai-non-pns&id=' . $id . '');
                    ?>
                    <form role="form" action="<?php echo $url_target; ?>" method="post">
                        <div class="modal-body">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="control-label">NIP</label>
                                    <input type="text" name="nip" value="<?php echo $pegawai['nip']; ?>" class="form-control" placeholder="Nip" required>
                                    <label class="control-label">NIK</label>
                                    <input type="text" name="nik" value="<?php echo $pegawai['nik']; ?>" class="form-control" placeholder="Nik" required>
                                    <label class="control-label">Nama Pegawai</label>
                                    <input type="text" name="nama_pegawai" value="<?php echo $pegawai['nama_pegawai']; ?>" class="form-control" placeholder="Nama Pegawai" required>
                                    <label class="control-label">No. Handphone (WA)</label>
                                    <input type="number" name="no_hp_wa" value="<?php echo $pegawai['no_hp_wa']; ?>" class="form-control" placeholder="No. Handphone (WA) Ex: 08xxxxxxxxxx" required>
                                    <label class="control-label">No. Handphone (SMS)</label>
                                    <input type="number" name="no_hp_sms" value="<?php echo $pegawai['no_hp_sms']; ?>" class="form-control" placeholder="No. Handphone (SMS) Ex: 08xxxxxxxxxx" required>
                                    <label class="control-label">TMT (Tgl/Bln/Thn)</label>
                                    <input type="text" name="tgl_masuk" value="<?php echo FormatTgl('d-m-Y', $pegawai['tgl_masuk']); ?>" class="form-control" data-inputmask='"mask": "99-99-9999","placeholder":"dd-mm-yyyy"' data-mask required>
                                    <label class="control-label">Unit/Bagian <font color="red">*</font></label>
                                    <select class="form-control select2" name="id_unit" data-placeholder="-Pilih Unit/Bagian-" style="width: 100%;" required>
                                        <option selected="selected" value="">-Pilih Unit/Bagian-</option>
                                        <?php
                                        $tm_unit = bukaquery("select tm_unit.id_unit,tm_unit.nama_unit from tm_unit ");
                                        while ($unt = fetch_array($tm_unit)) {
                                            if ($pegawai['id_unit'] == $unt['id_unit']) {
                                                echo "<option value=" . $unt['id_unit'] . " selected=" . $pegawai['id_unit'] . ">" . $unt['nama_unit'] . "</option>";
                                            } else {
                                                echo "<option value=" . $unt['id_unit'] . ">" . $unt['nama_unit'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <label class="control-label">Sub Bagian <font color="red">*</font></label>
                                    <select class='form-control select2' data-placeholder='' style='width: 100%;' name='sub_bagian' required=''>
                                        <?php
                                        $tm_sub_bagian = bukaquery("
                                            SELECT
                                                a.sub_bagian
                                            FROM tm_sub_bagian a
                                        ");
                                        while ($row = fetch_array($tm_sub_bagian)) {

                                            $selected = $row['sub_bagian'] == $pegawai['sub_bagian'] ? "selected" : "";
                                            echo "<option value='" . $row['sub_bagian'] . "' " . $selected . " >" . $row['sub_bagian'] . "</option>";;
                                        }
                                        ?>
                                    </select>
                                    <label class="control-label">Kasatpel <font color="red">*</font></label>
                                    <select class="form-control select2" name="id_kasatpel" data-placeholder="-Pilih Kasatpel-" style="width: 100%;" required>
                                        <option selected="selected" value="">-Pilih Kasatpel-</option>
                                        <?php
                                        $sql_kasatpel = bukaquery("SELECT tm_kasatpel.id_kasatpel, tm_kasatpel.nama_kasatpel FROM tm_kasatpel ORDER BY tm_kasatpel.nama_kasatpel DESC");
                                        while ($lvl = fetch_array($sql_kasatpel)) {
                                            if ($pegawai['id_kasatpel'] == $lvl['id_kasatpel']) {
                                                echo "<option value=" . $lvl['id_kasatpel'] . " selected=" . $pegawai['id_kasatpel'] . ">" . $lvl['nama_kasatpel'] . "</option>";
                                            } else {
                                                echo "<option value=" . $lvl['id_kasatpel'] . ">" . $lvl['nama_kasatpel'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <label class="control-label">Level Petugas <font color="red">*</font></label>
                                    <select class="form-control select2" name="id_level" data-placeholder="-Pilih Unit/Bagian-" style="width: 100%;" required>
                                        <option selected="selected" value="">-Pilih Level Petugas-</option>
                                        <?php
                                        $tm_level = bukaquery("select tm_level.id_level,tm_level.nama_level from tm_level ");
                                        while ($lvl = fetch_array($tm_level)) {
                                            if ($pegawai['id_level'] == $lvl['id_level']) {
                                                echo "<option value=" . $lvl['id_level'] . " selected=" . $pegawai['id_level'] . ">" . $lvl['nama_level'] . "</option>";
                                            } else {
                                                echo "<option value=" . $lvl['id_level'] . ">" . $lvl['nama_level'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Status Nikah</label>
                                    <?php echo UpdateEnumDropdown("tm_pegawai", "status_nikah", $pegawai['status_nikah'], ""); ?>
                                    <label class="control-label">PTKP</label>
                                    <?php echo UpdateEnumDropdown("tm_pegawai", "pajak", $pegawai['pajak'], ""); ?>
                                    <label class="control-label">Pendidikan</label>
                                    <?php echo UpdateEnumDropdown("tm_pegawai", "pendidikan", $pegawai['pendidikan'], ""); ?>
                                    <label class="control-label">Rumpun</label>
                                    <?php echo UpdateEnumDropdown("tm_pegawai", "rumpun", $pegawai['rumpun'], ""); ?>
                                    <label class="control-label">Acc Log Finger</label>
                                    <input type="text" name="log_finger" class="form-control" value="<?php echo $pegawai['log_finger']; ?>" placeholder="Masukan Log finger Absensi" required>
                                    <label class="control-label">BPJS Kesehatan</label><br>
                                    <input type="checkbox" name="bpjs_ks" Value="0.02" <?php
                                                                                        if ($pegawai['bpjs_ks'] == '0.02') {
                                                                                            echo "checked";
                                                                                        }
                                                                                        ?>> BPJS Kesehatan<br>
                                    <label class="control-label">BPJS Ketenagakerjaan</label><br>
                                    <input type="checkbox" name="bpjs_jkk" Value="0.0054" <?php
                                                                                            if ($pegawai['bpjs_jkk'] == '0.0054') {
                                                                                                echo "checked";
                                                                                            }
                                                                                            ?>> JKK & JKM &nbsp;
                                    <input type="checkbox" name="bpjs_ijht" Value="0.057" <?php
                                                                                            if ($pegawai['bpjs_ijht'] == '0.057') {
                                                                                                echo "checked";
                                                                                            }
                                                                                            ?>> IJHT &nbsp;
                                    <input type="checkbox" name="bpjs_jp" Value="0.03" <?php
                                                                                        if ($pegawai['bpjs_jp'] == '0.03') {
                                                                                            echo "checked";
                                                                                        }
                                                                                        ?>> JP &nbsp;<br>
                                    <label class="control-label">Status Karyawan</label><br>
                                    <input type="checkbox" name="status" class="js-switch" Value="AKTIF" <?php
                                                                                                            if ($pegawai['status'] == 'AKTIF') {
                                                                                                                echo "checked";
                                                                                                            }
                                                                                                            ?>>&nbsp; AKTIF
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label"></label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" onClick="history.go(-1);">Close</button>
                            <button type="submit" name="update" class="btn btn-primary">Update</button>
                        </div>
                </div
                    </form>
            </div>
        </div>
        <!-- Tutup Modal waktu -->
    <?php
        break;

    case "list-master-data-bagian":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST MASTER DATA BAGIAN</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <button type="button" class="btn btn-info fa fa-plus" data-toggle="modal" data-target="#modal-add-bagian">
                    Buat Master Data Bagian
                </button>
                <!--Modal Add SKP -->
                <div class="modal fade" id="modal-add-bagian">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title fa fa-plus"> Tambah Data Bagian</h4>
                            </div>
                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=master-data&act=add-master-data-bagian'); ?>" method="post">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama Bagian/Unit</label>
                                        <input type="text" class="form-control" name="nama_unit" placeholder="Nama Unit/Bagian" required>
                                        <label>kategori Petugas</label>
                                        <select class="form-control select2" name="id_petugas" data-placeholder="-Kategori Petugas-" style="width: 100%;" required>
                                            <option selected="selected" value="">-Pilih Kategori Petugas-</option>
                                            <?php
                                            $tm_honor_shift = bukaquery("select tm_honor_shift.id_petugas,tm_honor_shift.petugas from tm_honor_shift");
                                            while ($row = fetch_array($tm_honor_shift)) {
                                                echo "<option value=" . $row['id_petugas'] . ">" . $row['petugas'] . "</option>";
                                            }
                                            ?>
                                        </select>
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
                                <th>Nama Bagian/Unit</th>
                                <th>Kategori</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $sql_unit = bukaquery("SELECT tm_unit.id_unit,tm_unit.nama_unit, tm_unit.id_petugas, tm_honor_shift.petugas FROM tm_unit INNER JOIN tm_honor_shift ON tm_unit.id_petugas = tm_honor_shift.id_petugas where nama_unit!='-'");
                            while ($row = fetch_array($sql_unit)) {
                                $no++;
                            ?>
                                <!-- Edit Modal SKP -->
                                <div class="modal fade" id="modal-ubah-<?php echo $row['id_unit']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=update-master-data-bagian&id=' . $row['id_unit'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Update Data Master Bagian/Unit</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label>Nama bagian/Unit</label>
                                                            <input type="text" class="form-control" name="nama_unit" value="<?php echo $row['nama_unit']; ?>" placeholder="Nama Bagian/Unit" required>
                                                            <label>Kategori</label>
                                                            <select class="form-control select2" name="id_petugas" data-placeholder="-Kategori Petugas-" style="width: 100%;" required>
                                                                <option selected="selected" value="">-Pilih Kategori Petugas-</option>
                                                                <?php
                                                                $tm_honor_shift = bukaquery("select tm_honor_shift.id_petugas,tm_honor_shift.petugas from tm_honor_shift ");
                                                                while ($shift = fetch_array($tm_honor_shift)) {
                                                                    if ($row['id_petugas'] == $shift['id_petugas']) {
                                                                        echo "<option value=" . $shift['id_petugas'] . " selected=" . $row['id_petugas'] . ">" . $shift['petugas'] . "</option>";
                                                                    } else {
                                                                        echo "<option value=" . $shift['id_petugas'] . ">" . $shift['petugas'] . "</option>";
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
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
                                <div class="modal fade" id="modal-hapus-<?php echo $row['id_unit']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=delete-master-data-bagian&id=' . $row['id_unit'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Delete Data Bagian/Unit</h4>
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
                                    <td><?php echo $row['nama_unit']; ?></td>
                                    <td><?php echo $row['petugas']; ?></td>
                                    <td>
                                        <span data-toggle="modal" data-target="#modal-ubah-<?php echo $row['id_unit']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                        <span data-toggle="modal" data-target="#modal-hapus-<?php echo $row['id_unit']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
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

    case "list-master-data-kasatpel":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST MASTER DATA KASATPEL</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <button type="button" class="btn btn-info fa fa-plus" data-toggle="modal" data-target="#modal-add-kasatpel">
                    Buat Master Data Kasatpel
                </button>
                <!--Modal Add SKP -->
                <div class="modal fade" id="modal-add-kasatpel">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title fa fa-plus"> Tambah Data Kasatpel</h4>
                            </div>
                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=master-data&act=add-master-data-kasatpel'); ?>" method="post">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama Kasatpel</label>
                                        <input type="text" class="form-control" name="nama_kasatpel" placeholder="Nama Kasatpel" required>
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
                                <th>Nama Kasatpel</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $sql_kasatpel = bukaquery("SELECT * FROM tm_kasatpel where id_kasatpel !='-'");
                            while ($row = fetch_array($sql_kasatpel)) {
                                $no++;
                            ?>
                                <!-- Edit Modal SKP -->
                                <div class="modal fade" id="modal-ubah-<?php echo $row['id_kasatpel']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=update-master-data-kasatpel&id=' . $row['id_kasatpel'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Update Data Master Kasatpel</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label>Nama Kasatpel</label>
                                                            <input type="text" class="form-control" name="nama_kasatpel" value="<?php echo $row['nama_kasatpel']; ?>" placeholder="Nama Kasatpel" required>
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
                                <div class="modal fade" id="modal-hapus-<?php echo $row['id_kasatpel']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=delete-master-data-kasatpel&id=' . $row['id_kasatpel'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Delete Data Bagian/Unit</h4>
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
                                    <td><?php echo $row['nama_kasatpel']; ?></td>
                                    <td>
                                        <span data-toggle="modal" data-target="#modal-ubah-<?php echo $row['id_kasatpel']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                        <span data-toggle="modal" data-target="#modal-hapus-<?php echo $row['id_kasatpel']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
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

    case "list-master-data-shifting":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST MASTER DATA HONOR SHIFTING</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <button type="button" class="btn btn-info fa fa-plus" data-toggle="modal" data-target="#modal-add-shifting">
                    Buat Master Data Honor Shift
                </button>
                <!--Modal Add SKP -->
                <div class="modal fade" id="modal-add-shifting">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title fa fa-plus"> Tambah Data Honor Dhift</h4>
                            </div>
                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=master-data&act=add-master-data-honor-shift'); ?>" method="post">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Kategori Shift</label>
                                        <input type="text" class="form-control" name="petugas" placeholder="kategori petugas" required>
                                        <label>Hari Kerja Siang</label>
                                        <input type="number" class="form-control" name="hks" placeholder="Rp" required>
                                        <label>Hari Kerja Malam</label>
                                        <input type="number" class="form-control" name="hkm" placeholder="Rp" required>
                                        <label>Hari Libur pagi</label>
                                        <input type="number" class="form-control" name="hlp" placeholder="Rp" required>
                                        <label>Hari Libur Siang</label>
                                        <input type="number" class="form-control" name="hls" placeholder="Rp" required>
                                        <label>Hari Libur Malam</label>
                                        <input type="number" class="form-control" name="hlm" placeholder="Rp" required>
                                        <label>Hari Raya Pagi</label>
                                        <input type="number" class="form-control" name="hrp" placeholder="Rp" required>
                                        <label>Hari Raya Siang</label>
                                        <input type="number" class="form-control" name="hrs" placeholder="Rp" required>
                                        <label>Hari Raya Malam</label>
                                        <input type="number" class="form-control" name="hrm" placeholder="Rp" required>
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
                                <th>Nama Kategori</th>
                                <th>HKS</th>
                                <th>HKM</th>
                                <th>HLP</th>
                                <th>HLS</th>
                                <th>HLM</th>
                                <th>HRP</th>
                                <th>HRS</th>
                                <th>HRM</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $sql_unit = bukaquery("SELECT * from tm_honor_shift where petugas!='-'");
                            while ($row = fetch_array($sql_unit)) {
                                $no++;
                            ?>
                                <!-- Edit Modal SKP -->
                                <div class="modal fade" id="modal-ubah-<?php echo $row['id_petugas']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=update-master-data-honor-shift&id=' . $row['id_petugas'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Update Data Master Bagian/Unit</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label>kategori Petugas</label>
                                                            <input type="text" class="form-control" name="petugas" value="<?php echo $row['petugas']; ?>" placeholder="Kategori Petugas" required>
                                                            <label>Hari Kerja Siang</label>
                                                            <input type="number" class="form-control" name="hks" value="<?php echo $row['hks']; ?>" placeholder='Rp' required>
                                                            <label>Hari Kerja Malam</label>
                                                            <input type="number" class="form-control" name="hkm" value="<?php echo $row['hkm']; ?>" placeholder='Rp' required>
                                                            <label>Hari Libur Pagi</label>
                                                            <input type="number" class="form-control" name="hlp" value="<?php echo $row['hlp']; ?>" placeholder='Rp' required>
                                                            <label>Hari Libur Siang</label>
                                                            <input type="number" class="form-control" name="hls" value="<?php echo $row['hls']; ?>" placeholder='Rp' required>
                                                            <label>Hari Libur malam</label>
                                                            <input type="number" class="form-control" name="hlm" value="<?php echo $row['hlm']; ?>" placeholder='Rp' required>
                                                            <label>Hari Raya Pagi</label>
                                                            <input type="number" class="form-control" name="hrp" value="<?php echo $row['hrp']; ?>" placeholder='Rp' required>
                                                            <label>Hari Raya Sore</label>
                                                            <input type="number" class="form-control" name="hrs" value="<?php echo $row['hrs']; ?>" placeholder='Rp' required>
                                                            <label>Hari Raya Malam</label>
                                                            <input type="number" class="form-control" name="hrm" value="<?php echo $row['hrm']; ?>" placeholder='Rp' required>
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
                                <div class="modal fade" id="modal-hapus-<?php echo $row['id_petugas']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=delete-master-data-honor-shift&id=' . $row['id_petugas'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Update Data Bagian/Unit</h4>
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
                                    <td><?php echo $row['petugas']; ?></td>
                                    <td><?php echo formatDuit($row['hks']); ?></td>
                                    <td><?php echo formatDuit($row['hkm']); ?></td>
                                    <td><?php echo formatDuit($row['hlp']); ?></td>
                                    <td><?php echo formatDuit($row['hls']); ?></td>
                                    <td><?php echo formatDuit($row['hlm']); ?></td>
                                    <td><?php echo formatDuit($row['hrp']); ?></td>
                                    <td><?php echo formatDuit($row['hrs']); ?></td>
                                    <td><?php echo formatDuit($row['hrm']); ?></td>
                                    <td>
                                        <span data-toggle="modal" data-target="#modal-ubah-<?php echo $row['id_petugas']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                        <span data-toggle="modal" data-target="#modal-hapus-<?php echo $row['id_petugas']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
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

    case "list-master-data-hari-kerja":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST MASTER DATA HARI KERJA PEGAWAI</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <button type="button" class="btn btn-info fa fa-plus" data-toggle="modal" data-target="#modal-add-hari-kerja">
                    Buat Master Data Hari Kerja
                </button>
                <!--Modal Add SKP -->
                <div class="modal fade" id="modal-add-hari-kerja">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title fa fa-plus"> Tambah Data Hari kerja</h4>
                            </div>
                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=master-data&act=add-master-data-hari-kerja'); ?>" method="post">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <label>Bulan</label>
                                            <select class="form-control select2" name="bulan" data-placeholder="-Pilih Bulan-" style="width: 100%;" required>
                                                <?php loadBln('-Pilih Bulan-'); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Tahun</label>
                                            <input type="text" class="form-control" name="tahun" placeholder="" value="<?php echo date('Y'); ?>" readonly>
                                        </div>
                                        <div class="col-md-8">
                                            <p></p>
                                            <label>Jumlah Hari Kerja</label>
                                            <input type="number" class="form-control" name="hari" placeholder="" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"><br><br><br><br><br></label>
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
                                <th>Bulan</th>
                                <th>Jumlah Hari</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $sql_hari_kerja = bukaquery("select * from tm_hari_kerja order by bulan asc");
                            while ($row = fetch_array($sql_hari_kerja)) {
                                $no++;
                            ?>
                                <!-- Edit Modal SKP -->
                                <div class="modal fade" id="modal-ubah-<?php echo $row['id_hari_kerja']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=update-master-data-hari-kerja&id=' . $row['id_hari_kerja'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Update Data Hari Kerja</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <div class="col-md-4">
                                                                <label>Bulan</label>
                                                                <select class="form-control select2" name="bulan" data-placeholder="-Pilih Bulan-" style="width: 100%;" required>
                                                                    <?php updateloadBln(date('m', strtotime($row['bulan']))); ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label>Tahun</label>
                                                                <input type="text" class="form-control" name="tahun" placeholder="" value="<?php echo date('Y', strtotime($row['bulan'])); ?>" readonly>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <p></p>
                                                                <label>Jumlah Hari Kerja</label>
                                                                <input type="number" class="form-control" value="<?php echo $row['hari']; ?>" name="hari" placeholder="Masukan hari kerja" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label><br><br><br><br><br><br><br></label>
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
                                <div class="modal fade" id="modal-hapus-<?php echo $row['id_hari_kerja']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=delete-master-data-hari-kerja&id=' . $row['id_hari_kerja'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Hapus Master Data Hari kerja</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <text>Yakin Anda ingin Menghapus Data Bulan <b><?php echo konversiBulan(date('m', strtotime($row['bulan']))); ?> </b> ini,klik Tombol Ya? Untuk Menghapusnya</text>
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
                                    <td><?php echo konversiBulan(date('m', strtotime($row['bulan']))); ?></td>
                                    <td><?php echo $row['hari'] . " hari"; ?></td>
                                    <td>
                                        <span data-toggle="modal" data-target="#modal-ubah-<?php echo $row['id_hari_kerja']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                        <span data-toggle="modal" data-target="#modal-hapus-<?php echo $row['id_hari_kerja']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
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

    case "list-master-data-penyerapan":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST MASTER DATA NILAI PENYERAPAN</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <?php if (getOne("select count(id_penyerapan) as total from tm_penyerapan where year(tm_penyerapan.bulan)='$thn'") <= 12) { ?>
                    <button type="button" class="btn btn-info fa fa-plus" data-toggle="modal" data-target="#modal-add-penyerapan">
                        Buat Master Data Penyerapan
                    </button>
                <?php
                } else {
                }
                ?>
                <!--Modal Add SKP -->
                <div class="modal fade" id="modal-add-penyerapan">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title fa fa-plus"> Tambah Data Penyerapan</h4>
                            </div>
                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=master-data&act=add-master-data-penyerapan'); ?>" method="post">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <label>Bulan</label>
                                            <select class="form-control select2" name="bulan" data-placeholder="-Pilih Bulan-" style="width: 100%;" required>
                                                <?php loadBln('-Pilih Bulan-'); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Tahun</label>
                                            <input type="text" class="form-control" name="tahun" placeholder="" value="<?php echo date('Y'); ?>" readonly>
                                        </div>
                                        <div class="col-md-8">
                                            <p></p>
                                            <label>Nilai Penyerapan</label>
                                            <input type="number" class="form-control" name="penyerapan" placeholder="Angka Penyerapan Desimal" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"><br><br><br><br><br></label>
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
                                <th>Bulan</th>
                                <th>Nilai Penyerapan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $sql_penyerapan = bukaquery("select * from tm_penyerapan order by bulan asc");
                            while ($row = fetch_array($sql_penyerapan)) {
                                $no++;
                            ?>
                                <!-- Edit Modal SKP -->
                                <div class="modal fade" id="modal-ubah-<?php echo $row['id_penyerapan']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=update-master-data-penyerapan&id=' . $row['id_penyerapan'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Update Data Penyerapan</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <div class="col-md-4">
                                                                <label>Bulan</label>
                                                                <select class="form-control select2" name="bulan" data-placeholder="-Pilih Bulan-" style="width: 100%;" disabled required>
                                                                    <?php updateloadBln(date('m', strtotime($row['bulan']))); ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label>Tahun</label>
                                                                <input type="text" class="form-control" name="tahun" placeholder="" value="<?php echo date('Y', strtotime($row['bulan'])); ?>" readonly>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <p></p>
                                                                <label>Nilai Penyerapan</label>
                                                                <input type="decimal" class="form-control" value="<?php echo $row['penyerapan']; ?>" name="penyerapan" placeholder="Masukan hari kerja" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label"><br><br><br><br><br>
                                                            <p></p>
                                                        </label>
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
                                <div class="modal fade" id="modal-hapus-<?php echo $row['id_penyerapan']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=delete-master-data-penyerapan&id=' . $row['id_penyerapan'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Hapus Master Data Penyerapan</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <text>Yakin Anda ingin Menghapus Data Bulan <b><?php echo konversiBulan(date('m', strtotime($row['bulan']))); ?> </b> ini,klik Tombol Ya? Untuk Menghapusnya</text>
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
                                    <td><?php echo konversiBulan(date('m', strtotime($row['bulan']))) . " " . FormatTgl('Y', $row['bulan']); ?></td>
                                    <td><?php echo $row['penyerapan'] . " %"; ?></td>
                                    <td>
                                        <span data-toggle="modal" data-target="#modal-ubah-<?php echo $row['id_penyerapan']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                        <span data-toggle="modal" data-target="#modal-hapus-<?php echo $row['id_penyerapan']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
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

    case "list-master-data-sanksi":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST MASTER DATA SANKSI</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <button type="button" class="btn btn-info fa fa-plus" data-toggle="modal" data-target="#modal-add-sanksi">
                    Buat Master Data Sanksi
                </button>
                <!--Modal Add SKP -->
                <div class="modal fade" id="modal-add-sanksi">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title fa fa-plus"> Tambah Data Sanksi</h4>
                            </div>
                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=master-data&act=add-master-data-sanksi'); ?>" method="post">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <div class="col-md-8">
                                            <label>Nama Sanksi</label>
                                            <input type="text" class="form-control" name="nama_sanksi" placeholder="Nama Sanksi" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Masa Aktif Bulan</label>
                                            <input type="number" class="form-control" name="masa_aktif" placeholder="Angka" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Nilai Sanksi</label>
                                            <input type="number" class="form-control" name="nilai_sanksi" placeholder="Angka" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"></label>
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
                                <th>Nama Sanksi</th>
                                <th>Masa Aktif</th>
                                <th>Nilai Sanksi</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $sql_sanksi = bukaquery("select * from tm_sanksi order by nama_sanksi asc");
                            while ($row = fetch_array($sql_sanksi)) {
                                $no++;
                            ?>
                                <!-- Edit Modal SKP -->
                                <div class="modal fade" id="modal-ubah-<?php echo $row['id_sanksi']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=update-master-data-sanksi&id=' . $row['id_sanksi'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Update Data Penyerapan</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <div class="col-md-8">
                                                                <label>Nama Sanksi</label>
                                                                <input type="text" class="form-control" value="<?php echo $row['nama_sanksi']; ?>" name="nama_sanksi" placeholder="" required>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label>Masa Aktif Bulan</label>
                                                                <input type="number" class="form-control" value="<?php echo $row['masa_aktif']; ?>" name="masa_aktif" placeholder="" required>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label>Nilai Sanksi</label>
                                                                <input type="number" class="form-control" value="<?php echo $row['nilai_sanksi']; ?>" name="nilai_sanksi" placeholder="" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label"><br><br></label>
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
                                <div class="modal fade" id="modal-hapus-<?php echo $row['id_sanksi']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=delete-master-data-sanksi&id=' . $row['id_sanksi'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Hapus Master Data Master Sanksi</h4>
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
                                    <td><?php echo $row['nama_sanksi']; ?></td>
                                    <td><?php echo $row['masa_akttif'] . " Bulan"; ?></td>
                                    <td><?php echo $row['nilai_sanksi']; ?></td>
                                    <td>
                                        <span data-toggle="modal" data-target="#modal-ubah-<?php echo $row['id_sanksi']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                        <span data-toggle="modal" data-target="#modal-hapus-<?php echo $row['id_sanksi']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
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

    case "list-master-data-skp":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST MASTER DATA SKP</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <button type="button" class="btn btn-info fa fa-plus" data-toggle="modal" data-target="#modal-add-skp">
                    Buat Master Data SKP
                </button>
                <!--Modal Add SKP -->
                <div class="modal fade" id="modal-add-skp">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title fa fa-plus"> Tambah Data SKP</h4>
                            </div>
                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=master-data&act=add-master-data-skp'); ?>" method="post">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama SKP</label>
                                        <input type="text" class="form-control" name="skp" placeholder="Nama SKP" required>
                                        <label>Waktu Efektif</label>
                                        <input type="number" class="form-control" name="waktu" placeholder="Waktu Efektif" required>
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
                                <th>SKP</th>
                                <th>Waktu Efektif</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $sql_skp = bukaquery("SELECT * FROM tm_skp");
                            while ($row = fetch_array($sql_skp)) {
                                $no++;
                            ?>
                                <!-- Edit Modal SKP -->
                                <div class="modal fade" id="modal-ubah-<?php echo $row['kd_skp']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=update-master-data-skp&id=' . $row['kd_skp'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Update Data Master SKP</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label>Nama SKP</label>
                                                            <input type="text" class="form-control" name="skp" value="<?php echo $row['skp']; ?>" placeholder="Nama SKP" required>
                                                            <label>Waktu Efektif</label>
                                                            <input type="number" class="form-control" name="waktu" value="<?php echo $row['waktu']; ?>" placeholder="Waktu Efektif" required>
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
                                <div class="modal fade" id="modal-hapus-<?php echo $row['kd_skp']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=delete-master-data-skp&id=' . $row['kd_skp'] . ''); ?>" method="POST" role="form">
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
                                    <td><?php echo $row['skp']; ?></td>
                                    <td><?php echo $row['waktu']; ?></td>
                                    <td>
                                        <span data-toggle="modal" data-target="#modal-ubah-<?php echo $row['kd_skp']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                        <span data-toggle="modal" data-target="#modal-hapus-<?php echo $row['kd_skp']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
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

    case "list-master-izin-belajar":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST MASTER DATA IZIN BELAJAR PEGAWAI</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <button type="button" class="btn btn-info fa fa-plus" data-toggle="modal" data-target="#modal-add-izin-belajar">
                    Buat Izin Belajar
                </button>
                <!--Modal Add SKP -->
                <div class="modal fade" id="modal-add-izin-belajar">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title fa fa-plus"> Tambah Data Izin Belajar</h4>
                            </div>
                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=master-data&act=add-master-data-izin-belajar'); ?>" method="post">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama Pegawai</label>
                                        <select class="form-control select2" name="id_user" data-placeholder="-Pilih Nama Pegawai-" style="width: 100%;" required>
                                            <option selected="selected" value="">-Pilih Nama Pegawai-</option>
                                            <?php
                                            $tm_pegawai = bukaquery("select tm_pegawai.id_user,tm_pegawai.nama_pegawai from tm_pegawai where tm_pegawai.status='AKTIF' order by tm_pegawai.nama_pegawai asc");
                                            while ($row = fetch_array($tm_pegawai)) {
                                                echo "<option value=" . $row['id_user'] . ">" . $row['nama_pegawai'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                        <label>Jenis Peningkatan</label>
                                        <?php echo enumDropdown("tm_izin_belajar", "jenis_peningkatan", "", "-Pilih Jenis-"); ?>
                                        <label>Akademik/Univ</label>
                                        <input type="text" class="form-control" name="nama_univ" placeholder="Nama Akademik/Univ" required>
                                        <label>Alamat Akademik/Univ</label>
                                        <textarea class="form-control" name="alamat_univ" placeholder="Alamat" required></textarea>
                                        <label>Pendidikan Sebelum</label>
                                        <?php echo enumDropdown("tm_izin_belajar", "pendidikan_sebelum", "", "-Pilih Pendidikan-"); ?>
                                        <label>Pendidikan Lanjutan</label>
                                        <?php echo enumDropdown("tm_izin_belajar", "pendidikan_sesudah", "", "-Pilih Pendidikan-"); ?>
                                        <label>Jurusan</label>
                                        <input type="text" class="form-control" name="jurusan" placeholder="Nama Jurusan" required>
                                        <label>Akreditasi</label>
                                        <?php echo enumDropdown("tm_izin_belajar", "akreditasi", "", "-Pilih Akreditasi-"); ?>
                                        <label>No Izin</label>
                                        <input type="text" class="form-control" name="no_izin" placeholder="No Izin Belajar" required>
                                        <label>Tanggal Penerbitan</label>
                                        <input type="text" class="form-control" name="tanggal_izin_belajar" id="datepicker" placeholder="mm/dd/yyyy" required>
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
                                <th>No Izin Belajar</th>
                                <th>Nama Pegawai</th>
                                <th>Nama Akademik/Univ</th>
                                <th>Jenis Peningkatan</th>
                                <th>Peningkatan</th>
                                <th>Akreditasi</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $sql_izin_belajar = bukaquery("SELECT tm_izin_belajar.*, tm_pegawai.nama_pegawai FROM tm_izin_belajar inner join tm_pegawai on tm_izin_belajar.id_user=tm_pegawai.id_user "
                                . "order by tm_izin_belajar.tanggal_izin_belajar desc ");
                            while ($row = fetch_array($sql_izin_belajar)) {
                                $no++;
                            ?>
                                <!-- Edit Modal SKP -->
                                <div class="modal fade" id="modal-ubah-<?php echo $row['id_belajar']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=update-master-data-izin-belajar&id=' . $row['id_belajar'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Update Data Master Izin Belajar</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label>Nama Pegawai</label>
                                                            <select class="form-control select2" name="id_user" value="<?php echo $row['nama_pegawai']; ?>" data-placeholder="-Pilih Nama Pegawai-" style="width: 100%;" required>
                                                                <option selected="selected" value="">-Pilih Unit/Bagian-</option>
                                                                <?php
                                                                $tm_izin = bukaquery("select tm_pegawai.id_user,tm_pegawai.nama_pegawai from tm_pegawai where tm_pegawai.status='AKTIF' order by tm_pegawai.nama_pegawai asc ");
                                                                while ($izin = fetch_array($tm_izin)) {
                                                                    if ($row['id_user'] == $izin['id_user']) {
                                                                        echo "<option value=" . $izin['id_user'] . " selected=" . $row['id_user'] . ">" . $izin['nama_pegawai'] . "</option>";
                                                                    } else {
                                                                        echo "<option value=" . $izin['id_user'] . ">" . $izin['nama_pegawai'] . "</option>";
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                            <label>Jenis Peningkatan</label>
                                                            <?php echo UpdateEnumDropdown("tm_izin_belajar", "jenis_peningkatan", $row['jenis_peningkatan'], ""); ?>
                                                            <label>Akademik/Univ</label>
                                                            <input type="text" class="form-control" name="nama_univ" value="<?php echo $row['nama_univ']; ?>" placeholder="Nama Akademik/Univ" required>
                                                            <label>Alamat Akademik/Univ</label>
                                                            <textarea class="form-control" name="alamat_univ" placeholder="Alamat" required>value="<?php echo $row['alamat_univ']; ?>"</textarea>
                                                            <label>Pendidikan Sebelum</label>
                                                            <?php echo UpdateEnumDropdown("tm_izin_belajar", "pendidikan_sebelum", $row['pendidikan_sebelum'], ""); ?>
                                                            <label>Pendidikan Lanjutan</label>
                                                            <?php echo UpdateEnumDropdown("tm_izin_belajar", "pendidikan_sesudah", $row['pendidikan_sesudah'], ""); ?>
                                                            <label>Jurusan</label>
                                                            <input type="text" class="form-control" name="jurusan" value="<?php echo $row['jurusan']; ?>" placeholder="Nama Jurusan" required>
                                                            <label>Akreditasi</label>
                                                            <?php echo UpdateEnumDropdown("tm_izin_belajar", "akreditasi", $row['akreditasi'], ""); ?>
                                                            <label>No Izin</label>
                                                            <input type="text" class="form-control" name="no_izin" value="<?php echo $row['no_izin']; ?>" placeholder="No Izin Belajar" required>
                                                            <label>Tanggal Penerbitan (Tgl/Bln/Thn)</label>
                                                            <input type="text" class="form-control" name="tanggal_izin_belajar" value="<?php echo FormatTgl('d-m-Y', $row['tanggal_izin_belajar']); ?>" data-inputmask='"mask": "99-99-9999","placeholder":"dd-mm-yyyy"' data-mask required>
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
                                <div class="modal fade" id="modal-hapus-<?php echo $row['id_belajar']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=delete-master-data-izin-belajar&id=' . $row['id_belajar'] . ''); ?>" method="POST" role="form">
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
                                    <td><?php echo $row['no_izin'] . " , Tanggal Penerbitan : " . FormatTgl('d-m-Y', $row['tanggal_izin_belajar']) . ""; ?></td>
                                    <td><?php echo $row['nama_pegawai']; ?></td>
                                    <td><?php echo $row['nama_univ'] . " <p> Alamat : " . $row['alamat_univ'] . "</p>"; ?></td>
                                    <td><?php echo $row['jenis_peningkatan']; ?></td>
                                    <td><?php echo "Dari " . $row['pendidikan_sebelum'] . " Ke " . $row['pendidikan_sesudah'] . " " . $row['jurusan']; ?></td>
                                    <td><?php echo $row['akreditasi']; ?></td>
                                    <td>
                                        <span data-toggle="modal" data-target="#modal-ubah-<?php echo $row['id_belajar']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                        <span data-toggle="modal" data-target="#modal-hapus-<?php echo $row['id_belajar']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
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

    case "list-master-absensi":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST MASTER DATA SHIFT/ABSENSI</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <button type="button" class="btn btn-info fa fa-plus" data-toggle="modal" data-target="#modal-add-absensi">
                    Buat Absensi/Shift
                </button>
                <!--Modal Add SKP -->
                <div class="modal fade" id="modal-add-absensi">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title fa fa-plus"> Tambah Data Absensi/Shift</h4>
                            </div>
                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=master-data&act=add-master-data-absensi'); ?>" method="post">
                                <div class="modal-body">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nama Shift</label>
                                                <input type="text" class="form-control" name="nama_shift" placeholder="Nama Shift" required autofocus>
                                                <label>Deskripsi Shift</label>
                                                <input type="text" class="form-control" name="desc_shift" placeholder="Deskripsi Shift" required>
                                                <label>Hexa Color</label>
                                                <div class="input-group" id="add-shift-hxclor">
                                                    <input type="text" class="form-control" name="hex_color_shift" value="#000000">
                                                    <span class="input-group-addon"><i></i></span>
                                                </div>
                                                <label>Tipe Shift</label>
                                                <?php
                                                echo "<div><select class='form-control select2' name='shift_tipe' style='width:100%'>";
                                                echo "<option value='PAGI' " . ($row['shift_tipe'] == 'PAGI' ? "selected" : "") . ">PAGI</option>";
                                                echo "<option value='SORE' " . ($row['shift_tipe'] == 'SORE' ? "selected" : "") . ">SORE</option>";
                                                echo "<option value='MALAM' " . ($row['shift_tipe'] == 'MALAM' ? "selected" : "") . ">MALAM</option>";
                                                echo "</select></div>";
                                                ?>
                                                <label>Jam In</label>
                                                <input type="text" class="form-control" name="jam_masuk" data-inputmask='"mask": "99:99:99","placeholder":"hh:ii:ss"' data-mask required>
                                                <label>Sebelum In</label>
                                                <input type="text" class="form-control" name="bi" data-inputmask='"mask": "99:99:99","placeholder":"hh:ii:ss"' data-mask required>
                                                <label>Sesudah In</label>
                                                <input type="text" class="form-control" name="ai" data-inputmask='"mask": "99:99:99","placeholder":"hh:ii:ss"' data-mask required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Jam Out</label>
                                            <input type="text" class="form-control" name="jam_pulang" data-inputmask='"mask": "99:99:99","placeholder":"hh:ii:ss"' data-mask required>
                                            <label>Sebelum Out</label>
                                            <input type="text" class="form-control" name="bo" data-inputmask='"mask": "99:99:99","placeholder":"hh:ii:ss"' data-mask required>
                                            <label>Sesudah Out</label>
                                            <input type="text" class="form-control" name="ao" data-inputmask='"mask": "99:99:99","placeholder":"hh:ii:ss"' data-mask required>
                                            <label>Durasi Kerja (menit)</label>
                                            <input type="number" class="form-control" name="working_time_minute" placeholder="Durasi Kerja" maxlength="3" required>
                                            <label>Unit Yang Dapat Memilih Shift</label>
                                            <?php
                                            $sql_unit = bukaquery2("
                                                SELECT
                                                    a.id_unit, a.nama_unit
                                                FROM tm_unit a
                                                WHERE a.id_unit <> 'UNT-000020'
                                                ORDER BY a.nama_unit
                                            ");

                                            echo "<select id='list_unit_terpilih[]' name='list_unit_terpilih[]' class='form-control select2' multiple='multiple' tabindex='1' data-placeholder='-Pilih Unit-' style='width: 100%;'>";
                                            while ($row_unit = fetch_array($sql_unit)) {

                                                echo "<option value='" . $row_unit['id_unit'] . "'>" . $row_unit['nama_unit'] . "</option>";
                                            }
                                            echo "</select>";
                                            ?>
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
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Shift</th>
                                <th>Deskripsi Shift</th>
                                <th>Durari Kerja (menit)</th>
                                <th>Hex Color</th>
                                <th>Tipe Shift</th>
                                <th>In</th>
                                <th>Sebelum In</th>
                                <th>Sesudah In</th>
                                <th>Out</th>
                                <th>Sebelum Out</th>
                                <th>Sesudah Out</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $sql_absensi = bukaquery("SELECT 
                                    a.id_absensi, a.nama_shift, a.desc_shift, a.working_time_minute, a.hex_color_shift, 
                                    a.jam_masuk, a.jam_pulang, a.bi, a.ai, a.bo, a.ao, a.shift_tipe
                                FROM tm_shift a
                            ");
                            while ($row = fetch_array($sql_absensi)) {
                                $no++;
                            ?>
                                <!-- Edit Modal SKP -->
                                <div class="modal fade" id="modal-ubah-<?php echo $row['id_absensi']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=update-master-data-absensi&id=' . $row['id_absensi'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Update Data Master Absensi/Shift</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="container-fluid">
                                                        <div class="row">

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Nama Shift</label>
                                                                    <input type="text" class="form-control" name="nama_shift" value="<?php echo $row['nama_shift']; ?>" placeholder="Nama Shift" required autofocus>
                                                                    <label>Deskripsi Shift</label>
                                                                    <input type="text" class="form-control" name="desc_shift" value="<?php echo $row['desc_shift']; ?>" placeholder="Deskripsi Shift" required>
                                                                    <label>Hexa Color</label>
                                                                    <div class="input-group" id="edt-shift-hxclor">
                                                                        <input type="text" class="form-control" name="hex_color_shift" value="#<?php echo $row['hex_color_shift']; ?>">
                                                                        <span class="input-group-addon"><i></i></span>
                                                                    </div>
                                                                    <label>Tipe Shift</label>
                                                                    <?php
                                                                    echo "<div><select class='form-control select2' name='shift_tipe' style='width:100%'>";
                                                                    echo "<option value='PAGI' " . ($row['shift_tipe'] == 'PAGI' ? "selected" : "") . ">PAGI</option>";
                                                                    echo "<option value='SORE' " . ($row['shift_tipe'] == 'SORE' ? "selected" : "") . ">SORE</option>";
                                                                    echo "<option value='MALAM' " . ($row['shift_tipe'] == 'MALAM' ? "selected" : "") . ">MALAM</option>";
                                                                    echo "</select></div>";
                                                                    ?>
                                                                    <label>Jam In</label>
                                                                    <input type="text" class="form-control" name="jam_masuk" value="<?php echo $row['jam_masuk']; ?>" data-inputmask='"mask": "99:99:99","placeholder":"hh:ii:ss"' data-mask required>
                                                                    <label>Sebelum In</label>
                                                                    <input type="text" class="form-control" name="bi" value="<?php echo $row['bi']; ?>" data-inputmask='"mask": "99:99:99","placeholder":"hh:ii:ss"' data-mask required>
                                                                    <label>Sesudah In</label>
                                                                    <input type="text" class="form-control" name="ai" value="<?php echo $row['ai']; ?>" data-inputmask='"mask": "99:99:99","placeholder":"hh:ii:ss"' data-mask required>

                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Jam Out</label>
                                                                    <input type="text" class="form-control" name="jam_pulang" value="<?php echo $row['jam_pulang']; ?>" data-inputmask='"mask": "99:99:99","placeholder":"hh:ii:ss"' data-mask required>
                                                                    <label>Sebelum Out</label>
                                                                    <input type="text" class="form-control" name="bo" value="<?php echo $row['bo']; ?>" data-inputmask='"mask": "99:99:99","placeholder":"hh:ii:ss"' data-mask required>
                                                                    <label>Sesudah Out</label>
                                                                    <input type="text" class="form-control" name="ao" value="<?php echo $row['ao']; ?>" data-inputmask='"mask": "99:99:99","placeholder":"hh:ii:ss"' data-mask required>
                                                                    <label>Durasi Kerja (menit)</label>
                                                                    <input type="number" class="form-control" name="working_time_minute" value="<?php echo $row['working_time_minute']; ?>" placeholder="Durasi Kerja" maxlength="3" required>

                                                                    <label>Unit Yang Dapat Memilih Shift</label>
                                                                    <?php
                                                                    $sql_unit = bukaquery2("
                                                                        (
                                                                            SELECT
                                                                                a.id_unit, a.nama_unit, true AS selected
                                                                            FROM tm_unit a
                                                                                INNER JOIN tt_jadwalpegawai_unit_shift b ON a.id_unit = b.id_unit
                                                                            WHERE a.id_unit <> 'UNT-000020'
                                                                                AND b.id_absensi = '" . $row['id_absensi'] . "'
                                                                            ORDER BY a.nama_unit
                                                                        )
                                                                        UNION
                                                                        (
                                                                            SELECT
                                                                                a.id_unit, a.nama_unit, false AS selected
                                                                            FROM tm_unit a
                                                                                LEFT JOIN tt_jadwalpegawai_unit_shift b ON a.id_unit = b.id_unit
                                                                            WHERE a.id_unit <> 'UNT-000020'
                                                                                AND a.id_unit NOT IN (
                                                                                    SELECT
                                                                                        a.id_unit
                                                                                    FROM tm_unit a
                                                                                        INNER JOIN tt_jadwalpegawai_unit_shift b ON a.id_unit = b.id_unit
                                                                                    WHERE a.id_unit <> 'UNT-000020'
                                                                                        AND b.id_absensi = '" . $row['id_absensi'] . "'
                                                                                    ORDER BY a.nama_unit
                                                                                )
                                                                            GROUP BY a.id_unit
                                                                            ORDER BY a.nama_unit
                                                                        )
                                                                    ");

                                                                    echo "<select id='list_unit_terpilih[]' name='list_unit_terpilih[]' class='form-control select2' multiple='multiple' tabindex='1' data-placeholder='-Pilih Unit-' style='width: 100%;'>";
                                                                    while ($row_unit = fetch_array($sql_unit)) {

                                                                        echo "<option value='" . $row_unit['id_unit'] . "' " . ($row_unit['selected'] ? 'selected' : '') . ">" . $row_unit['nama_unit'] . "</option>";
                                                                    }
                                                                    echo "</select>";
                                                                    ?>
                                                                </div>
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
                                <div class="modal fade" id="modal-hapus-<?php echo $row['id_absensi']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=delete-master-data-absensi&id=' . $row['id_absensi'] . ''); ?>" method="POST" role="form">
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
                                    <td><?php echo $row['nama_shift']; ?></td>
                                    <td><?php echo $row['desc_shift']; ?></td>
                                    <td><?php echo $row['working_time_minute']; ?></td>
                                    <td><span class="label" style="background-color: #<?php echo $row['hex_color_shift']; ?>; color: black;"><?php echo $row['hex_color_shift']; ?></span></td>
                                    <td><?php echo $row['shift_tipe']; ?></td>
                                    <td><?php echo $row['jam_masuk']; ?></td>
                                    <td><?php echo $row['bi']; ?></td>
                                    <td><?php echo $row['ai']; ?></td>
                                    <td><?php echo $row['jam_pulang']; ?></td>
                                    <td><?php echo $row['bo']; ?></td>
                                    <td><?php echo $row['ao']; ?></td>
                                    <td>
                                        <span data-toggle="modal" data-target="#modal-ubah-<?php echo $row['id_absensi']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                        <span data-toggle="modal" data-target="#modal-hapus-<?php echo $row['id_absensi']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
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
    case "list-master-shift-ketidakhadiran":
    ?>
        <!-- Modal - Modal -->
        <div class="modal fade" id="list-master-shift-ketidakhadiran_modaltambah">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title fa fa-plus">Tambah Data Shift Ketidakhadiran</h4>
                    </div>
                    <form role="form" action="<?php echo $aksi . paramEncrypt('module=master-data&act=add-master-data-shift-ketidakhadiran'); ?>" method="POST">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Nama Shift Ketidakhadiran</label>
                                <input type="text" class="form-control" name="ketidakhadiran_nama" placeholder="Nama Shift Ketidakhadiran" required autofocus>
                                <label>Deskripsi Shift Ketidakhadiran</label>
                                <input type="text" class="form-control" name="ketidakhadiran_desc" placeholder="Deskripsi Shift Ketidakhadiran" required>
                                <label>Tipe Waktu Shift Ketidakharian</label>
                                <select class="form-control select2" style="width: 100%;" id="ketidakhadian_tipe" name="ketidakhadian_tipe">
                                    <?php loadTipeShiftKetidakhadiran(''); ?>
                                </select>
                                <label>Ditampilkan Sebagai Pilihan Ke PJ</label>
                                <select class="form-control select2" style="width: 100%;" id="is_show_for_pj" name="is_show_for_pj">
                                    <option value="0" selected>Tidak</option>
                                    <option value="1">Ya</option>
                                </select>
                                <label>Hex Color</label>
                                <input type="text" class="form-control" name="ketidakhadiran_hexcolor" placeholder="Hex Color" maxlength="6" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary" value="Simpan">
                        </div>
                    </form>
                    <div class="modal-body"></div>
                </div>
            </div>
        </div>
        <!-- End Modal - Modal -->

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST MASTER DATA KETIDAKHADIRAN</h3>
                <div class="box-tools pull-right">
                    <button type="submit" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <!-- <button type="button" class="btn btn-info fa fa-plus" data-toggle="modal" data-target="#list-master-shift-ketidakhadiran_modaltambah">
                    Buat Shift Ketidakhadiran
                </button> -->
                <div class="box-body table-responsive">
                    <table class="table table-bordered table-striped" id="example1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Shift Ketidakhadiran</th>
                                <th>Deskripsi Shift Ketidakhadiran</th>
                                <th>Tipe Waktu Shift Ketidakhadiran</th>
                                <th>Hex Color</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $sql = bukaquery2("SELECT a.id_ketidakhadiran, a.nama_ketidakhadiran, a.desc_ketidakhadiran, a.id_ketidakhadiran_tipe, b.nama_ketidakhadiran_tipe, a.hex_color_ketidakhadiran, a.is_show_for_pj FROM tm_shift_ketidakhadiran a LEFT JOIN tm_shift_ketidakhadiran_tipe b ON a.id_ketidakhadiran_tipe = b.id_ketidakhadiran_tipe");
                            while ($row = fetch_array($sql)) {
                                $no++;
                            ?>

                                <!-- Modal modal -->
                                <!-- Modal ubah -->
                                <div class="modal fade" id="list-master-shift-ketidakhadiran-modal-ubah-<?php echo $row['id_ketidakhadiran']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=edit-master-data-shift-ketidakhadiran&id=' . $row['id_ketidakhadiran']); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <h4 class="modal-title" id="myModalLabel">Update Data Shift Ketidakhadiran</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label>Nama Shift Ketidakhadiran</label>
                                                            <input type="text" class="form-control" value="<?php echo $row['nama_ketidakhadiran']; ?>" name="ketidakhadiran_nama" required autofocus>
                                                            <label>Deskripsi Shift Ketidakhadiran</label>
                                                            <input type="text" class="form-control" value="<?php echo $row['desc_ketidakhadiran']; ?>" name="ketidakhadiran_desc" required>
                                                            <label>Tipe Waktu Shift Ketidakhadiran</label>
                                                            <select class="form-control select2" style="width: 100%;" id="ketidakhadian_tipe" name="ketidakhadian_tipe">
                                                                <?php loadTipeShiftKetidakhadiran($row['id_ketidakhadiran_tipe']); ?>
                                                            </select>
                                                            <label>Ditampilkan Sebagai Pilihan Ke PJ</label>
                                                            <select class="form-control select2" style="width: 100%;" id="is_show_for_pj" name="is_show_for_pj">
                                                                <option value="0" <?php echo $row['is_show_for_pj'] == 0 ? "selected" : "" ?>>Tidak</option>
                                                                <option value="1" <?php echo $row['is_show_for_pj'] == 1 ? "selected" : "" ?>>Ya</option>
                                                            </select>
                                                            <label>Hexa Color</label>
                                                            <input type="text" class="form-control" value="<?php echo $row['hex_color_ketidakhadiran']; ?>" name="ketidakhadiran_hexcolor" required>
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

                                <!-- Modal hapus -->
                                <div class="modal fade" id="list-master-shift-ketidakhadiran-modal-hapus-<?php echo $row['id_ketidakhadiran']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=delete-master-data-shift-ketidakhadiran&id=' . $row['id_ketidakhadiran'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <h4 class="modal-title" id="myModalLabel">Hapus Master Data Ketidakhadiran</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label>Anda yakin ingin menghapus data ini ?</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
                                                    <button type="submit" class="btn btn-danger" autofocus>Ya</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- end Modal modal -->

                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $row['nama_ketidakhadiran']; ?></td>
                                    <td><?php echo $row['desc_ketidakhadiran']; ?></td>
                                    <td><?php echo $row['nama_ketidakhadiran_tipe']; ?></td>
                                    <td><span class="label" style="background-color: #<?php echo $row['hex_color_ketidakhadiran']; ?>; color: black;"><?php echo $row['hex_color_ketidakhadiran']; ?></span></td>
                                    <td>
                                        <span data-toggle="modal" data-target="#list-master-shift-ketidakhadiran-modal-ubah-<?php echo $row['id_ketidakhadiran']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
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
    case "list-master-hari-raya":
    ?>
        <!-- Modal - Modal -->
        <div class="modal fade" id="modal-add-hari-raya">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title fa fa-plus"> Tambah Data Hari Raya</h4>
                    </div>
                    <form role="form" action="<?php echo $aksi . paramEncrypt('module=master-data&act=add-master-hari-raya'); ?>" method="post">
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="hidden" class="form-control" name="id_kepegawaian" value="<?php echo $id_user; ?>">
                                <label>Tanggal</label>
                                <input type="text" class="form-control" id="datepicker" name="tanggal" placeholder="dd/mm/yyyy" required readonly>
                                <label>Keterangan</label>
                                <input type="text" class="form-control" name="keterangan" placeholder="Keterangan" required>
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

        <!-- End Modal - Modal -->

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST MASTER HARI RAYA</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <button type="button" class="btn btn-info fa fa-plus" data-toggle="modal" data-target="#modal-add-hari-raya">
                    Tambah Hari Raya
                </button>
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $sql = bukaquery2("SELECT
                                a.id_hari_raya, a.tanggal, a.keterangan
                            FROM tm_hari_raya a
                            ORDER BY a.tanggal desc, a.keterangan
                        ");
                            while ($row = fetch_array($sql)) {
                            ?>
                                <!-- Modal Edit -->
                                <div class="modal fade" id="modal-ubah-hari-raya-<?php echo $row['id_hari_raya']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=edit-master-hari-raya&id=' . $row['id_hari_raya'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Update Data Hari Raya</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label>Tanggal</label>
                                                            <input type="text" class="form-control" name="tanggal" value="<?php echo $row['tanggal']; ?>" readonly>
                                                            <label>Keterangan</label>
                                                            <input type="text" class="form-control" name="keterangan" value="<?php echo $row['keterangan']; ?>" placeholder="Deskripsi Shift" required autofocus>
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
                                <!-- End Modal Edit -->
                                <!-- Modal Delete -->
                                <div class="modal fade" id="modal-hapus-hari-raya-<?php echo $row['id_hari_raya']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=delete-master-hari-raya&id=' . $row['id_hari_raya'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Hapus Data Hari Raya</h4>
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
                                <!-- End Modal Delete -->
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo date("d-m-Y", strtotime($row['tanggal'])); ?></td>
                                    <td><?php echo $row['keterangan']; ?></td>
                                    <td>
                                        <span data-toggle="modal" data-target="#modal-ubah-hari-raya-<?php echo $row['id_hari_raya']; ?>" title="Ubah" class="btn btn-warning"><i class="fa fa-edit"></i>&nbsp;Ubah</span>
                                        <span data-toggle="modal" data-target="#modal-hapus-hari-raya-<?php echo $row['id_hari_raya']; ?>" title="Hapus" class="btn btn-danger"><i class="fa fa-trash"></i>&nbsp;Hapus</span>
                                    </td>
                                </tr>
                            <?php
                                $no++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php
        break;
    case "list-master-hari-libur":
    ?>
        <!-- Modal - Modal -->
        <div class="modal fade" id="modal-add-hari-libur">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title fa fa-plus"> Tambah Data Hari Libur</h4>
                    </div>
                    <form role="form" action="<?php echo $aksi . paramEncrypt('module=master-data&act=add-master-hari-libur'); ?>" method="post">
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="hidden" class="form-control" name="id_kepegawaian" value="<?php echo $id_user; ?>">
                                <label>Tanggal</label>
                                <input type="text" class="form-control" id="datepicker" name="tanggal" placeholder="dd/mm/yyyy" required readonly>
                                <label>Cuti Bersama</label>
                                <select class="form-control" name="cuti_bersama">
                                    <option value="tidak">Tidak</option>
                                    <option value="ya">Ya</option>
                                </select>
                                <label>Keterangan</label>
                                <input type="text" class="form-control" name="keterangan" placeholder="Keterangan" required>
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

        <!-- End Modal - Modal -->

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST MASTER HARI LIBUR</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <button type="button" class="btn btn-info fa fa-plus" data-toggle="modal" data-target="#modal-add-hari-libur">
                    Tambah Hari Libur
                </button>
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Cuti Bersama</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $sql = bukaquery2("SELECT
                                a.id_hari_libur, a.tanggal, a.cuti_bersama, a.keterangan
                            FROM tm_hari_libur a
                            ORDER BY a.tanggal desc, a.keterangan
                        ");
                            while ($row = fetch_array($sql)) {
                            ?>
                                <!-- Modal Edit -->
                                <div class="modal fade" id="modal-ubah-hari-libur-<?php echo $row['id_hari_libur']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=edit-master-hari-libur&id=' . $row['id_hari_libur'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Update Data Hari Libur</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label>Tanggal</label>
                                                            <input type="text" class="form-control" name="tanggal" value="<?php echo $row['tanggal']; ?>" readonly>
                                                            <label>Cuti Bersama</label>
                                                            <select class="form-control" name="cuti_bersama">
                                                                <option <?php echo $row['cuti_bersama'] == 'tidak' ? 'selected' : '' ?> value="tidak">Tidak</option>
                                                                <option <?php echo $row['cuti_bersama'] == 'ya' ? 'selected' : '' ?> value="ya">Ya</option>
                                                            </select>
                                                            <label>Keterangan</label>
                                                            <input type="text" class="form-control" name="keterangan" value="<?php echo $row['keterangan']; ?>" placeholder="Deskripsi Shift" required autofocus>
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
                                <!-- End Modal Edit -->
                                <!-- Modal Delete -->
                                <div class="modal fade" id="modal-hapus-hari-libur-<?php echo $row['id_hari_libur']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=delete-master-hari-libur&id=' . $row['id_hari_libur'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Hapus Data Hari Libur</h4>
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
                                <!-- End Modal Delete -->
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo date("d-m-Y", strtotime($row['tanggal'])); ?></td>
                                    <td><?php echo $row['keterangan']; ?></td>
                                    <td><?php echo $row['cuti_bersama']; ?></td>
                                    <td>
                                        <span data-toggle="modal" data-target="#modal-ubah-hari-libur-<?php echo $row['id_hari_libur']; ?>" title="Ubah" class="btn btn-warning"><i class="fa fa-edit"></i>&nbsp;Ubah</span>
                                        <span data-toggle="modal" data-target="#modal-hapus-hari-libur-<?php echo $row['id_hari_libur']; ?>" title="Hapus" class="btn btn-danger"><i class="fa fa-trash"></i>&nbsp;Hapus</span>
                                    </td>
                                </tr>
                            <?php
                                $no++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php
        break;
    case "set-spj":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> MAPING SPJ GAJI</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <?php if (getOne("select count(set_spj.id_spj) from set_spj") == 0) { ?>
                    <button type="button" class="btn btn-info fa fa-plus" data-toggle="modal" data-target="#modal-add-set-spj">
                        Buat Maping SPJ
                    </button>
                <?php } ?>
                <!--Modal Add SKP -->
                <div class="modal fade" id="modal-add-set-spj">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title fa fa-plus"> Setup SPJ</h4>
                            </div>
                            <form role="form" action="<?php echo $aksi . paramEncrypt('module=master-data&act=add-set-spj'); ?>" method="post">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama PPK Keuangan</label>
                                        <select class="form-control select2" name="ppk_keuangan" data-placeholder="-Pilih PPK Keuangan-" style="width: 100%;" required>
                                            <option selected="selected" value="">-Pilih Nama Pegawai-</option>
                                            <?php
                                            $tm_pegawai = bukaquery("select tm_pegawai.id_user,tm_pegawai.nama_pegawai from tm_pegawai where tm_pegawai.status='AKTIF' order by tm_pegawai.nama_pegawai asc");
                                            while ($row = fetch_array($tm_pegawai)) {
                                                echo "<option value=" . $row['id_user'] . ">" . $row['nama_pegawai'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                        <label>Nama Bendahara Pengeluaran</label>
                                        <select class="form-control select2" name="bendahara_pengeluaran" data-placeholder="-Pilih Bendahara-" style="width: 100%;" required>
                                            <option selected="selected" value="">-Pilih Nama Pegawai-</option>
                                            <?php
                                            $tm_pegawai = bukaquery("select tm_pegawai.id_user,tm_pegawai.nama_pegawai from tm_pegawai where tm_pegawai.status='AKTIF' order by tm_pegawai.nama_pegawai asc");
                                            while ($row = fetch_array($tm_pegawai)) {
                                                echo "<option value=" . $row['id_user'] . ">" . $row['nama_pegawai'] . "</option>";
                                            }
                                            ?>
                                        </select>
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
                                <th>PPK Keuangan</th>
                                <th>Bendahara Pengeluaran</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $sql_spj = bukaquery("SELECT * FROM set_spj ");
                            while ($row = fetch_array($sql_spj)) {
                                $no++;
                            ?>
                                <!-- Edit Modal SKP -->
                                <div class="modal fade" id="modal-ubah-<?php echo $row['id_spj']; ?>" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=update-set-spj&id=' . $row['id_spj'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Update Setup SPJ</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label>Nama PPK Keuangan</label>
                                                            <select class="form-control select2" name="ppk_keuangan" value="<?php echo getOne("select nama_pegawai from tm_pegawai where id_user='$row[ppk_keuangan]'"); ?>" data-placeholder="-Pilih PPK Keuangan-" style="width: 100%;" required>
                                                                <option selected="selected" value="">-Pilih PPK Keuangan-</option>
                                                                <?php
                                                                $tm_pegawai = bukaquery("select tm_pegawai.id_user,tm_pegawai.nama_pegawai from tm_pegawai where tm_pegawai.status='AKTIF' order by tm_pegawai.nama_pegawai asc ");
                                                                while ($pegawai = fetch_array($tm_pegawai)) {
                                                                    if ($row['ppk_keuangan'] == $pegawai['id_user']) {
                                                                        echo "<option value=" . $pegawai['id_user'] . " selected=" . $row['ppk_keuangan'] . ">" . $pegawai['nama_pegawai'] . "</option>";
                                                                    } else {
                                                                        echo "<option value=" . $pegawai['id_user'] . ">" . $pegawai['nama_pegawai'] . "</option>";
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                            <label>Nama Bendahara Pengeluaran</label>
                                                            <select class="form-control select2" name="bendahara_pengeluaran" value="<?php echo getOne("select nama_pegawai from tm_pegawai where id_user='$row[bendahara_pengeluaan]'"); ?>" data-placeholder="-Pilih Bendahara Pengeluaran-" style="width: 100%;" required>
                                                                <option selected="selected" value="">-Pilih Bendahara Pengeluaran-</option>
                                                                <?php
                                                                $tm_pegawai = bukaquery("select tm_pegawai.id_user,tm_pegawai.nama_pegawai from tm_pegawai where tm_pegawai.status='AKTIF' order by tm_pegawai.nama_pegawai asc ");
                                                                while ($pegawai = fetch_array($tm_pegawai)) {
                                                                    if ($row['bendahara_pengeluaran'] == $pegawai['id_user']) {
                                                                        echo "<option value=" . $pegawai['id_user'] . " selected=" . $row['bendahara_pengeluaran'] . ">" . $pegawai['nama_pegawai'] . "</option>";
                                                                    } else {
                                                                        echo "<option value=" . $pegawai['id_user'] . ">" . $pegawai['nama_pegawai'] . "</option>";
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
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
                                <tr>
                                    <td><?php echo getOne("select tm_pegawai.nama_pegawai from tm_pegawai where id_user='$row[ppk_keuangan]'"); ?></td>
                                    <td><?php echo getOne("select tm_pegawai.nama_pegawai from tm_pegawai where id_user='$row[bendahara_pengeluaran]'"); ?></td>
                                    <td>
                                        <span data-toggle="modal" data-target="#modal-ubah-<?php echo $row['id_spj']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
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

    case "list-master-grade":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST MASTER GRADE</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <?php
                $sql = bukaquery("select id_level,grade_kinerja,grade_prilaku,nama_level from tm_level");
                ?>
                <div class="box-body table-responsive">
                    <table id="example" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Jabatan</th>
                                <th>Grade Kinerja</th>
                                <th>Grade Prilaku</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            while ($row = mysqli_fetch_array($sql)) {
                                $no++;
                            ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $row['nama_level']; ?></td>
                                    <td><?php echo $row['grade_kinerja']; ?></td>
                                    <td><?php echo $row['grade_prilaku']; ?></td>
                                    <td>
                                        <button type="button" class="btn-xs btn-warning fa fa-edit" data-toggle="modal" title="Update" data-target="#modal-edit-<?php echo $row['id_level']; ?>"> </button>
                                    </td>
                                </tr>
                                <!--Modal Edit-->
                                <div class="modal fade" id="modal-edit-<?php echo $row['id_level']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=update-grade&id=' . $row['id_level'] . ''); ?>" role="form" method="post">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                                    </button>
                                                    <h4 class="modal-title" id="myModalLabel">Form Grade Jabatan</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label class="control-label">Nama Level</label>
                                                        <input type="text" name="nama_level" class="form-control" value="<?php echo $row['nama_level']; ?>" readonly>
                                                        <label class="control-label">Grade Kinerja</label>
                                                        <input type="text" name="grade_kinerja" class="form-control" value="<?php echo $row['grade_kinerja']; ?>" required>
                                                        <label class="control-label">Grade Prilaku</label>
                                                        <input type="text" name="grade_prilaku" class="form-control" value="<?php echo $row['grade_prilaku']; ?>" required>
                                                    </div>
                                                    <br>
                                                    <div class="form-group">
                                                        <label class="control-label"><br></label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default fa fa-close" data-dismiss="modal"> Close</button>
                                                    <button type="submit" class="btn btn-warning fa fa-save"> Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!--Tutup Modal Edit-->
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
    case "set-jadwalshift": // khusus PJ
    ?>

        <input type="hidden" id="set-jadwalshit_year" name="set-jadwalshit_year" value="<?php echo $pengaturanshift_year_selected ?>">
        <input type="hidden" id="set-jadwalshit_month" name="set-jadwalshit_month" value="<?php echo $pengaturanshift_bulan_selected ?>">
        <input type="hidden" id="set-jadwalshit_id-unit" name="set-jadwalshit_id-unit" value="<?php echo $pengaturanshift_unit_selected ?>">
        <input type="hidden" id="set-jadwalshift_id-kepegawaian" name="set-jadwalshift_id-kepegawaian" value="<?php echo $id_user; ?>">

        <!-- Modal-modal -->

        <div class="modal fade" id="modal-warning-submit-jadwalshift-by-pj" tabindex="-1" role="dialog" aria-label="myModalLabel1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Submit Pengaturan Shift</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Anda yakin submit pengaturan shift ini ?</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-warning" onclick="submit_jadwalshift_by_pj();">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- enf Modal-modal -->

        <div class="row">
            <div class="col-md-4">
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
                            <h4>Pilih Tanggal</h4>
                            <div class="row row-no-gutters">
                                <div class="col-lg-6">
                                    Bulan
                                    <br>
                                    <select class="form-control select2" id="set-jadwalshift-select-month" name="set-jadwalshift-select-month" data-placeholder="-Pilih Bulan-" onchange="select_event_default(this);">
                                        <?php loadBln('-Pilih Bulan-'); ?>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    Tahun
                                    <br>
                                    <select class="form-control select2" id="set-jadwalshift-select-year" name="set-jadwalshift-select-year" data-placeholder="-Pilih Tahun-" onchange="select_event_default(this);">
                                        <?php loadThn('-Pilih Tahun-'); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            Unit
                            <br>
                            <select class="form-control select2" id="set-jadwalshift-select-unit" name="set-jadwalshift-select-unit" data-placeholder="-Pilih Unit-" onchange="select_event_default(this);">
                                <?php loadUnitByLevel($idlevel, $id_unit, $kasatpel_pegawai, $kasie); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <br>
                            <button id='set-jadwalshift-search' name="set-jadwalshift-search" value="Cari" class="btn btn-info btn-btn-group-lg" onclick="search_periode();">
                                <i class="fa fa-search">&nbsp;&nbsp;Cari Jadwal Shift</i>
                            </button>
                            <button id="btn-generate-jadwalshift" name="btn-generate-jadwalshift" class="btn btn-success" onclick="generate_shift(`<?php echo $id_user; ?>`);" style="display: none;">
                                Buat/Tambah Jadwal Otomatis
                            </button>
                            <button id="btn-delete-jadwalshift" name="btn-delete-jadwalshift" class="btn btn-danger" onclick="delete_shift(`<?php echo $id_user; ?>`);" style="display: none;">
                                Hapus Jadwal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.col -->

            <div class="col-md-8">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title fa fa-list">&nbsp;LOG VALIDASI PENGATURAN SHIFT</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body" id="set-jadwalshift-box-table-log-validasi" name="set-jadwalshift-box-table-log-validasi">
                        <table id="set-jadwalshift-table-log-validasi" name="set-jadwalshift-table-log-validasi" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.col -->
        </div>

        <div class="box" id="box-tabel-shift">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list">&nbsp;TABEL PENGATURAN SHIFT</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="row box-body">
                <div class="col col-md-6">
                    <div id="set-jadwalshift_list-shift">
                    </div>
                    <div id="set-jadwalshift_hapusshift">
                    </div>
                    <div id="set-jadwalshift_list_hariraya">
                    </div>
                </div>
                <div class="col col-md-6">
                    <div class="box-body" id="set-jadwalshift_list-ketidakhadiranshift">
                    </div>
                </div>
            </div>

            <div class="box-body" id="box-print-set-jadwalshift" style="display: none;">
                <button class="btn btn-info btn-group-lg" value="Print" onclick="print_jadwalshift();">
                    <i class="fa fa-print">&nbsp;&nbsp;Print</i>
                </button>
            </div>


            <div class="box-body" id="box-set-jadwalshift-tabel">
            </div>



            <div class="box-body" id="box-submit-button">
                <span id="btn-submit-set-jadwalshift" name="btn-submit-set-jadwalshift" data-toggle="modal" data-target="#modal-warning-submit-jadwalshift-by-pj" style="display: none;" class="btn btn-block btn-warning btn-lg" title="Submit">SUBMIT</span>
            </div>
        </div>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list">&nbsp;REKAPITULASI SHIFT &nbsp;&nbsp;<button id="btn-rekap-shift-calculate" name="btn-rekap-shift-calculate" class="btn btn-sm btn-success" style="display: none;" onclick="get_rekapitulasi_shift_by_pj();">Hitung Ulang</button></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class='table table-bordered table-striped' id='rekap-shift-table' name='rekap-shift-table'>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Pegawai</th>
                            <th>Pagi</th>
                            <th>Sore</th>
                            <th>Malam</th>
                            <th>Libur</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

    <?php
        break;
    case "set-jadwal-nonshift"; // khusus kepegawaian
    ?>

        <!-- Modal-modal -->
        <div class="modal fade" id="modal-warning-submit-jadwalnonshift-by-kepegawaian" tabindex="-1" role="dialog" aria-label="myModalLabel1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Simpan Jadwal Pegawai Non Shift</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Anda yakin untuk menyimpan jadwal untuk pegawai non shfit ini ?</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-warning" onclick="save_jadwalnonshift_by_kepegawian(`<?php echo $id_user; ?>`);">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end Modal-modal -->

        <div class="row">

            <div class="col-md-4">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title fa fa-list">&nbsp;PILIH PERIODE</h3>
                        <div class="box-tools pull-rigth">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <h4>Pilih Tanggal</h4>
                            <div class="row row-no-gutters">
                                <div class="col-lg-6">
                                    Bulan
                                    <br>
                                    <select class="form-control select2" id="set-jadwal-noshift_select_month" name="set-jadwal-noshift_select_month" data-placeholder="-Pilih Bulan-" onchange="event_set_jadwal_nonshift(this);">
                                        <?php loadBln('-Pilih Bulan-'); ?>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    Tahun
                                    <br>
                                    <select class="form-control select2" id="set-jadwal-noshift_select_year" name="set-jadwal-noshift_select_year" data-placeholder="-Pilih Tahun-" onchange="event_set_jadwal_nonshift(this);">
                                        <?php loadThn('-Pilih Tahun-'); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>UNIT</label>
                            <select class="form-control select2" id="set-jadwal-nonshift_select_unit" name="set-jadwal-nonshift_select_unit" onchange="event_set_jadwal_nonshift(this);" style="width: 100%;" disabled>
                                <?php
                                loadUnitByLevel($idlevel, $id_unit, $kasatpel, $kasie);
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <button id="set-jadwal-nonshift_search" name="set-jadwal-nonshift_search" value="Cari" class="btn btn-info" onclick="search_jadwal_nonshift();">
                                <i class="fa fa-search">&nbsp;&nbsp;Cari Jadwal Non Shift</i>
                            </button>
                            <br>
                            <br>
                            <button id="set-jadwal-nonshift_generate" name="set-jadwal-nonshift_generate" class="btn btn-success" onclick="generate_jadwal_nonshift(`<?php echo $id_user; ?>`);" style="display: none;">
                                <i class="fa fa-plus">&nbsp;&nbsp;Buat/Tambah Jadwal Non Shift</i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title fa fa-list">&nbsp;LOG JADWAL PEGAWAI NON SHIFT</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body" id="box-set-jadwal-nonshift_log">
                        <table id="table-set-jadwal-nonshift_log" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list">&nbsp;TABEL JADWAL PEGAWAI NON SHIFT</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="row box-body">
                <div class="col col-md-6">
                    <div id="set-jadwal_nonshift_list-shift">
                    </div>
                    <div id="set-jadwal_nonshift_hapus-shift">
                    </div>
                    <div id="set-jadwal_nonshift_list-hariraya">
                    </div>
                </div>
                <div class="col col-md-6">
                    <div class="box-body" id="set-jadwal_nonshift_list-ketidakhadiranshift">
                    </div>
                </div>
            </div>

            <div class="box-body" id="box-set-jadwal_nonshift">
            </div>

            <div class="box-body">
                <span id="btn-save-set-jadwal_nonshift" name="btn-save-set-jadwal_nonshift" data-toggle="modal" data-target="#modal-warning-submit-jadwalnonshift-by-kepegawaian" style="display: none;" class="btn btn-block btn-warning btn-lg" title="Simpan Jadwal Non Shift ke Database">SIMPAN</span>
            </div>

        </div>
    <?php
        break;
    case "set-jadwal-spesialis": // khusus kepegawaian
        break;
    case "list-permintaan-validasi-jadwalshift":
    ?>
        <!-- modal-modal -->
        <!-- /end modal-modal -->

        <div class="box" id="box-table-permintaan-validasi-jadwalshift">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list">&nbsp;Permintaan Validasi Pengaturan Shift</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <table id="table-list-permintaan-validasi-jadwalshift" name="table-list-permintaan-validasi-jadwalshift" class="table table-bordered table-striped">
                    <thead>
                        <th>No.</th>
                        <th>Sub Bagian</th>
                        <th>Unit</th>
                        <th>Penanggung Jawab</th>
                        <th>Shift</th>
                        <th>Tanggal Disubmit</th>
                        <th>Aksi</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

    <?php
        break;
    case "detail-permintaan-validasi-jadwalshift":
    ?>

        <input type="hidden" id="pengaturan_idshiftvalidation" name="pengaturan_idshiftvalidation" value="<?php echo $pengaturanshift_idshiftvalidation; ?>">
        <input type="hidden" id="pengaturan_shift_bulan_selected" name="pengaturan_shift_bulan_selected" value="<?php echo $pengaturanshift_bulan_selected; ?>">
        <input type="hidden" id="pengaturan_shift_year_selected" name="pengaturan_shift_year_selected" value="<?php echo $pengaturanshift_year_selected; ?>">
        <input type="hidden" id="pengaturan_shift_unit_selected" name="pengaturan_shift_unit_selected" value="<?php echo $pengaturanshift_unit_selected; ?>">
        <input type="hidden" id="pengaturan_shift_id_user" name="pengaturan_shift_id_user" value="<?php echo $id_user; ?>">


        <!-- modal-modal -->
        <div class="modal fade" id="modal-permintaan-validasi-jadwalshift" name="modal-permintaan-validasi-jadwalshift">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h3 class="modal-title fa fa-calendar">&nbsp;VALIDASI PENGATURAN SHIFT</h3>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="modal-permintaan-validasi-jadwalshift-id" name="modal-permintaan-validasi-jadwalshift-id">
                        <input type="hidden" id="modal-permintaan-validasi-jadwalshift-id_user" name="modal-permintaan-validasi-jadwalshift-id_user">
                        <input type="hidden" id="modal-permintaan-validasi-jadwalshift-id_penanggung_jawab" name="modal-permintaan-validasi-jadwalshift-id_penanggung_jawab">
                        <div class="form-group" id="modal-permintaan-validasi-jadwalshift-profileunit"></div>
                        <div class="form-group">
                            <label>Keputusan</label>
                            <select class="form-control select2" style="width: 100%;" id="modal-permintaan-validasi-jadwalshift-answer" name="modal-permintaan-validasi-jadwalshift-answer"></select>
                        </div>
                        <div class="form-group">
                            <label>Catatan (opsional)</label>
                            <textarea class="form-control" rows="3" id="modal-permintaan-validasi-jadwalshift-notes" name="modal-permintaan-validasi-jadwalshift-notes">-</textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" id="modal-permintaan-validasi-jadwalshift-save" name="modal-permintaan-validasi-jadwalshift-save" class="btn btn-success" onclick="submit_validasi_jadwalshift();">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /end modal-modal -->

        <div class="box" style="display: none;">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list">&nbsp;LOG VALIDASI PENGATURAN SHIFT</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body" id="box-table-log-validasi" name="box-table-log-validasi">
                <table id="detail-permintaan-validasi-jadwalshift-log-validasi" name="detail-permintaan-validasi-jadwalshift-log-validasi" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list">&nbsp;DETAIL TABEL PENGATURAN SHIFT <?php echo strtoupper(konversiBulan($pengaturanshift_bulan_selected)) . " - " . $pengaturanshift_year_selected; ?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collaps" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="box-body" id="box-dtl-permintaan-vld">
            </div>

            <div class="box-body" id="box-btn-validasi">

            </div>
        </div>

    <?php
        break;

    case "list-shift-pegawai-all-unit":
    ?>

        <div class="row">
            <div class="col-md-4">
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
                            <h4>Pilih Tanggal</h4>
                            <div class="row row-no-gutters">
                                <div class="col-lg-6">
                                    Bulan
                                    <br>
                                    <select class="form-control select2" id="list-shift-pegawai-by-managerial-select-month" name="list-shift-pegawai-by-managerial-select-month" data-placeholder="-Pilih Bulan-">
                                        <?php loadBln('-Pilih Bulan-'); ?>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    Tahun
                                    <br>
                                    <select class="form-control select2" id="list-shift-pegawai-by-managerial-select-year" name="list-shift-pegawai-by-managerial-select-year" data-placeholder="-Pilih Tahun-">
                                        <?php loadThn('-Pilih Tahun-'); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            Unit
                            <br>
                            <select class="form-control select2" id="list-shift-pegawai-by-managerial-select-unit" name="list-shift-pegawai-by-managerial-select-unit" data-placeholder="-Pilih Unit-">
                                <?php loadUnitByLevel($idlevel, $id_unit, $kasatpel_pegawai, $kasie); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <br>
                            <button name="list-shift-pegawai-by-managerial-search" value="Cari" class="btn btn-info btn-btn-group-lg" onclick="search_periode_pegawai_all_unit(`<?php echo $idlevel; ?>`);">
                                <i class="fa fa-search">&nbsp;&nbsp;Cari Jadwal Shift</i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.col -->

            <div class="col-md-8">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title fa fa-list">&nbsp;LOG VALIDASI PENGATURAN SHIFT</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body" id="list-shift-pegawai-by-managerial-box-table-log-validasi" name="list-shift-pegawai-by-managerial-box-table-log-validasi">
                        <table id="list-shift-pegawai-by-managerial-table-log-validasi" name="list-shift-pegawai-by-managerial-table-log-validasi" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.col -->
        </div>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list">&nbsp;TABEL PENGATURAN SHIFT PEGAWAI</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="row box-body">
                <div class="col col-md-6">
                    <div id="list-shift-pegawai-all-unit_list-shift"></div>
                    <div id="list-shift-pegawai-all-unit_hapusshift"></div>
                    <div id="list-shift-pegawai-all-unit_list-hariraya"></div>
                </div>
                <div class="col col-md-6">
                    <div id="list-shift-pegawai-all-unit_list-ketidakhadiranshift">
                    </div>
                </div>
            </div>

            <div class="box-body" id="box-list-shift-pegawai-all-unit">
            </div>

        </div>

    <?php
        break;
    case "list-master-kuota-cuti":
    ?>
        <!-- Modal - Modal -->
        <div class="modal fade" id="modal-add-kuota-cuti">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title fa fa-plus"> Tambah Data Kuota Cuti</h4>
                    </div>
                    <form role="form" action="<?php echo $aksi . paramEncrypt('module=master-data&act=add-kuota-cuti'); ?>" method="post">
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="hidden" class="form-control" name="id_kepegawaian" value="<?php echo $id_user; ?>">
                                <label>Tanggal</label>
                                <input type="text" class="form-control" id="datepicker" name="tanggal" placeholder="dd/mm/yyyy" required readonly>
                                <label>Nama Hari Raya</label>
                                <input type="text" class="form-control" name="hari" placeholder="ex : HARI RAYA IDUL ADHA 2025" required>
                                <label>Cuti</label>
                                <select class="form-control" name="id_ketidakhadiran" id="id_ketidakhadiran" required>
                                    <option value="AKT-000012">Cuti Tahunan</option>
                                </select>
                                <label>Kuota</label>
                                <input type="number" class="form-control" name="kuota" placeholder="Jumlah Kuota Cuti" required>
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

        <!-- End Modal - Modal -->

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST MASTER KUOTA CUTI PER TANGGAL</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <button type="button" class="btn btn-info fa fa-plus" data-toggle="modal" data-target="#modal-add-kuota-cuti">
                    Tambah Kuota Cuti Per Tanggal
                </button>
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Hari Raya</th>
                                <th>Jenis Cuti</th>
                                <th>Jummlah Cuti Maksimal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $sql = bukaquery2("SELECT
                                    a.id_kuota, a.tanggal, a.hari, a.kuota,
                                    a.id_ketidakhadiran, b.nama_ketidakhadiran, b.desc_ketidakhadiran
                                FROM tm_hari_kuota_cuti a
                                    INNER JOIN tm_shift_ketidakhadiran b ON a.id_ketidakhadiran = b.id_ketidakhadiran
                                ORDER BY a.tanggal desc, a.hari
                            ");
                            while ($row = fetch_array($sql)) {
                            ?>
                                <!-- Modal Edit -->
                                <div class="modal fade" id="modal-ubah-kuota-cuti-<?php echo $row['id_kuota']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=edit-kuota-cuti&id=' . $row['id_kuota'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Update Kuota Cuti</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label>Tanggal</label>
                                                            <input type="text" class="form-control" value="<?php echo $row['tanggal']; ?>" readonly>
                                                            <label>Keterangan</label>
                                                            <input type="text" class="form-control" value="<?php echo $row['tanggal']; ?>" readonly>
                                                            <label>Jenis Cuti</label>
                                                            <input type="text" class="form-control" value="<?php echo $row['desc_ketidakhadiran']; ?>" readonly>
                                                            <label>Kuota</label>
                                                            <input type="number" class="form-control" name="kuota" value="<?php echo $row['kuota']; ?>" placeholder="Deskripsi Shift" required autofocus>
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
                                <!-- End Modal Edit -->
                                <!-- Modal Delete -->
                                <div class="modal fade" id="modal-hapus-kuota-cuti-<?php echo $row['id_kuota']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=master-data&act=delete-kuota-cuti&id=' . $row['id_kuota'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Hapus Kuota Cuti</h4>
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
                                <!-- End Modal Delete -->
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo date("d-m-Y", strtotime($row['tanggal'])); ?></td>
                                    <td><?php echo $row['hari']; ?></td>
                                    <td><?php echo $row['desc_ketidakhadiran']; ?></td>
                                    <td><?php echo $row['kuota']; ?></td>
                                    <td>
                                        <span data-toggle="modal" data-target="#modal-ubah-kuota-cuti-<?php echo $row['id_kuota']; ?>" title="Ubah" class="btn btn-warning"><i class="fa fa-edit"></i></span>
                                        <span data-toggle="modal" data-target="#modal-hapus-kuota-cuti-<?php echo $row['id_kuota']; ?>" title="Hapus" class="btn btn-danger"><i class="fa fa-trash"></i></span>
                                    </td>
                                </tr>
                            <?php
                                $no++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php
        break;
}
?>
<script src="libs/jquery/jquery.min.js"></script>
<script>
    //global variable
    var set_jadwalshift_editable = true;
    var set_jadwalshift_id_shift_selected = null;
    var set_jadwalshift_nama_shift_selected = null;
    var set_jadwalshift_hex_colorshift_selected = null;
    var set_jadwalshift_desc_selected = null;
    var set_jadwalshift_jam_masuk_selected = null;
    var set_jadwalshift_jam_pulang_selected = null;
    var set_jadwalshift_id_ketidakhadiran_selected = null;
    var set_jadwalshift_nama_ketidakhadiran_selected = null;
    var set_jadwalshift_hex_colorketidakhadiran_selected = null;
    var set_jadwalshift_desc_ketidakhadiran_selected = null;
    var set_jadwalshift_id_shift_tipe_selected = null;
    var set_jadwalshift_nama_shift_tipe_selected = null;
    var set_jadwalshift_desc_shift_tipe_selected = null;
    var set_jadwalshift_clear_shift_selected = false;

    // fungsi untuk list-permintaan-validasi-jadwalshift
    function get_list_permintaan_validasi_jadwalshift() {

        $.ajax({
            type: "GET",
            url: "<?php echo $url_api_master_data; ?>?action=get_list_permintaan_validasi_jadwalshift&id_user=<?php echo $id_user; ?>",
            dataType: "JSON",
            success: function(data) {

                if (data.status == 1) {

                    $('#table-list-permintaan-validasi-jadwalshift tbody').empty();
                    for (let i = 0; i < data.data.length; i++) {

                        var index = i + 1;
                        var id_validation = data.data[i]['id_jadwalkerja_shift_validation'];
                        var id_unit = data.data[i]['id_unit'];
                        var sub_bagian = data.data[i]['sub_bagian'];
                        var nama_unit = data.data[i]['nama_unit'];
                        var id_sender = data.data[i]['id_user_sender'];
                        var name_sender = data.data[i]['nama_user_sender'];
                        var month = data.data[i]['month'];
                        var month_name = data.data[i]['month_name'];
                        var year = data.data[i]['year'];
                        var shift_date = month_name + " - " + year;
                        var timestamp_request = data.data[i]['timestamp_request'];
                        var action_button = data.data[i]['answered'] == 0 ?
                            '<button type="button" class="btn btn-block btn-success" onclick="update_timestamp_read_validasi(`' + id_validation + '`, `?' + data.data[i]['url_detail'] + '`);" >Detail</button>' :
                            '<button type="button" class="btn btn-block btn-default" onclick="update_timestamp_read_validasi(`' + id_validation + '`, `?' + data.data[i]['url_detail'] + '`);">Detail</button>';

                        $('#table-list-permintaan-validasi-jadwalshift tbody')
                            .append('<tr><td>' + index + '</td><td>' + sub_bagian + '</td><td>' + nama_unit + '</td><td>' + name_sender + '</td><td>' + shift_date + '</td><td>' + timestamp_request + '</td><td>' + action_button + '</td></tr>');
                    }

                    if (!$.fn.dataTable.isDataTable('#table-list-permintaan-validasi-jadwalshift')) {

                        $('#table-list-permintaan-validasi-jadwalshift').DataTable({
                            "responsive": true,
                            "autoWidth": false,
                            "paging": true,
                            "scrollX": true
                        });
                    }
                } else {

                    console.log("Kode : GETLISTVLSHFT1. Kode Status = 0.");
                    console.log(data);
                }
            },
            error: function(errorMsg) {

                console.log("Kode : GETLISTVLSHFT1. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
            }
        });
    }

    function update_timestamp_read_validasi(id_jadwalkerja_shift_validation, url_detail) {

        $.ajax({
            type: "GET",
            url: "<?php echo $url_api_master_data; ?>?action=put_validation_timestamp_read&id_jadwalkerja_shift_validation=" + id_jadwalkerja_shift_validation,
            dataType: "JSON",
            complete: function() {

                //apapun hasilnya, tetap redirect ke detail-permintaan-validasi-jadwalshift
                window.location.replace(url_detail);
            }
        });
    }

    function get_detail_permintaan_validasi_jadwalshift() {

        console.log('get_detail_permintaan_validasi_jadwalshift init');

        $('#box-dtl-permintaan-vld').html("");
        $('#box-dtl-permintaan-vld').append('<table id="dtl-permintaan-vld-table" name="dtl-permintaan-vld-table" class="table table-bordered table-striped"><thead></thead><tbody></tbody></table>');

        $('#dtl-permintaan-vld-table tbody').empty();

        var month = $("#pengaturan_shift_bulan_selected").val();
        var year = $("#pengaturan_shift_year_selected").val();
        var id_unit = $("#pengaturan_shift_unit_selected").val();
        var id_user = $("#pengaturan_shift_id_user").val();
        var id_jadwalkerja_shift_validation = $("#pengaturan_idshiftvalidation").val();

        $.ajax({
            type: "GET",
            url: "<?php echo $url_api_master_data; ?>?action=get_detail_permintaan_validasi_jadwalshift&id_unit=" + id_unit + "&month=" + month + "&year=" + year,
            dataType: "JSON",
            success: function(response) {

                if (response.status == 1) {

                    var thead_value = "<tr><td><b>No</b></td><td><b>Nama</b></td>";

                    for (let i = 0; i < response.data.shift_data.date_in_month.length; i++) {

                        var style_font = get_day_by_date(response.data.shift_data.date_in_month[i]) == "MG" ? "color:red" : "color:black";
                        thead_value += "<td><b><p style='" + style_font + "'>" + (i + 1) + "(" + get_day_by_date(response.data.shift_data.date_in_month[i]) + ")</p></b></td>";
                    }

                    thead_value += "</tr>";
                    $('#dtl-permintaan-vld-table thead').append(thead_value);

                    for (let i = 0; i < response.data.shift_data.data.length; i++) {

                        var tbody_value = "<tr><td>" + (i + 1) + "</td><td>" + response.data.shift_data.data[i].nama_pegawai + "</td>";

                        for (let j = 0; j < response.data.shift_data.data[i].shift.length; j++) {

                            var date = response.data.shift_data.data[i].shift[j]['date'];
                            var date_prev = response.data.shift_data.data[i].shift[j != 0 ? (j - 1) : j]['date'];
                            var date_next = response.data.shift_data.data[i].shift[(j + 1) < response.data.shift_data.data[i].shift.length ? (j + 1) : j]['date'];
                            var id_absensi = response.data.shift_data.data[i].shift[j]['id_absensi'];
                            var nama_absensi = response.data.shift_data.data[i].shift[j]['nama_absensi'];
                            var desc_absensi = response.data.shift_data.data[i].shift[j]['desc_absensi'];
                            var jammasuk_absensi = response.data.shift_data.data[i].shift[j]['jam_masuk'];
                            var jampulang_absensi = response.data.shift_data.data[i].shift[j]['jam_pulang'];
                            var hex_color_absensi = response.data.shift_data.data[i].shift[j]['hex_color_absensi'];
                            var tooltip_absensi = desc_absensi + "(" + jammasuk_absensi + "-" + jampulang_absensi + ")";

                            tbody_value += "<td>";
                            if (id_absensi != "") { // apabila id_absensi tidak kosong, dianggap ada shiftnya

                                if (date == date_prev && j != 0) { // apabila tanggal saat ini sama dengan tanggal sebelumnya, dianggap longshift

                                    tbody_value = tbody_value.slice(0, -9);
                                    tbody_value += '<button type="button" class="btn btn-xs btn-block" title="' + tooltip_absensi + '" style="background-color: #' + hex_color_absensi + ';">' + nama_absensi + '</button>';
                                } else {

                                    tbody_value += '<button type="button" class="btn btn-xs btn-block" title="' + tooltip_absensi + '" style="background-color: #' + hex_color_absensi + ';">' + nama_absensi + '</button>';
                                }
                            } else { // id_absensi kosong, dianggap libur atau shift tidak aktif

                                tbody_value += '<button type="button" class="btn btn-xs btn-block">Libur</button>';
                            }

                            tbody_value += "</td>";

                        }

                        tbody_value += "</tr>";
                        $('#dtl-permintaan-vld-table tbody').append(tbody_value);
                    }

                    if (!$.fn.dataTable.isDataTable('#dtl-permintaan-vld-table')) {

                        $('#dtl-permintaan-vld-table').DataTable({
                            "responsive": true,
                            "autoWidth": false,
                            "scrollX": true
                        });
                    }

                    if (response.data.validation_status == 0) {

                        $('#box-btn-validasi')
                            .html('<button class="btn btn-block btn-success btn-lg"  id="dtl-permintaan-vld-btn" name="dtl-permintaan-vld-btn" onclick="act_validasi_jadwalshift(`' + id_jadwalkerja_shift_validation + '`, `' + response.data.shift_data['nama_unit'] + '`, `' + response.data.shift_data['id_penanggung_jawab'] + '`, `' + response.data.shift_data['nama_penanggung_jawab'] + '`, `' + response.data.shift_data['month_name'] + '`, `' + response.data.shift_data['year'] + '`, `' + id_user + '`);">Validasi</button>');
                    } else {

                        $('#box-btn-validasi')
                            .html('<button class="btn btn-block btn-default btn-lg"  id="dtl-permintaan-vld-btn" name="dtl-permintaan-vld-btn" disabled >Sudah Tervalidasi</button>');
                    }

                } else {

                    console.log("Kode : DTLPERMINTAANVLDSHIFT1. Kode Status = 0");
                    console.log(response);
                }
            },
            error: function(errorMsg) {

                console.log("Kode : DTLPERMINTAANVLDSHIFT1. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
            }
        });
    }

    function act_validasi_jadwalshift(id_jadwalkerja_shift_validation, nama_unit, id_penanggung_jawab, nama_penanggung_jawab, month_name, year, id_user) {

        $('#modal-permintaan-validasi-jadwalshift-profileunit').html("");
        $('#modal-permintaan-validasi-jadwalshift-profileunit').append('<tr><td>Unit</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>' + nama_unit + '</td></tr>');
        $('#modal-permintaan-validasi-jadwalshift-profileunit').append('<tr><td>Penanggung Jawab</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>' + nama_penanggung_jawab + '</td></tr>');
        $('#modal-permintaan-validasi-jadwalshift-profileunit').append('<tr><td>Shift Untuk</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>' + month_name + ' - ' + year + '</td></tr>');

        $('#modal-permintaan-validasi-jadwalshift-id').val(id_jadwalkerja_shift_validation);
        $('#modal-permintaan-validasi-jadwalshift-id_user').val(id_user);
        $('#modal-permintaan-validasi-jadwalshift-id_penanggung_jawab').val(id_penanggung_jawab);

        $('#modal-permintaan-validasi-jadwalshift-answer').empty();
        $('#modal-permintaan-validasi-jadwalshift-answer').append(new Option('Diterima', '1', true));
        $('#modal-permintaan-validasi-jadwalshift-answer').append(new Option('Ditolak', '2'));

        $('#modal-permintaan-validasi-jadwalshift').modal('show');
    }

    function submit_validasi_jadwalshift() {

        $('#modal-permintaan-validasi-jadwalshift-save').html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;Mohon Tunggu").prop('disabled', true);

        var id_jadwalkerja_shift_validation = $('#modal-permintaan-validasi-jadwalshift-id').val();
        var answer = $('#modal-permintaan-validasi-jadwalshift-answer option:selected').val();
        var notes = $('#modal-permintaan-validasi-jadwalshift-notes').val();
        var id_user = $('#modal-permintaan-validasi-jadwalshift-id_user').val();
        var id_penanggung_jawab = $('#modal-permintaan-validasi-jadwalshift-id_penanggung_jawab').val();

        $.ajax({
            type: "POST",
            url: "<?php echo $url_api_master_data; ?>?action=put_validation_jadwalshit",
            data: {
                id_jadwalkerja_shift_validation: id_jadwalkerja_shift_validation,
                answer: answer,
                notes: notes,
                id_penanggung_jawab: id_penanggung_jawab,
                id_user: id_user
            },
            dataType: "JSON",
            success: function(data) {

                $('#modal-permintaan-validasi-jadwalshift-save').html("Simpan").prop('disabled', false);

                $('#box-btn-validasi').html('<button class="btn btn-block btn-default btn-lg"  id="dtl-permintaan-vld-btn" name="dtl-permintaan-vld-btn" disabled >Sudah Tervalidasi</button>');

                $('#modal-permintaan-validasi-jadwalshift').modal('hide');

                swal("Validasi Jadwal Shift Berhasil", data.message, "success");
            },
            error: function(errorMsg) {

                $('#modal-permintaan-validasi-jadwalshift-save').html("Simpan").prop('disabled', false);

                swal("Validasi Jadwal Shift Gagal", "Permintaan Validasi Shift oleh Satpel Gagal", "error");

                console.log("Kode : VLDSHIFKST. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
            }
        });
    }
    // /end fungsi untuk list-permintaan-validasi-jadwalshift


    // fungsi utk set-jadwalshift
    var arr_pengaturan_shift;

    function remove_leading_zero(value) {
        return value.replace(/^0+(?!\.|$)/, '');
    }

    function adding_leading_zero(value) {
        return String(value).padStart(2, '0');
    }

    function select_event_default(selected) {
        $('#btn-generate-jadwalshift').hide();
        $('#btn-delete-jadwalshift').hide();
    }

    function get_day_by_date(date) {
        var d = new Date(date);
        return ["MG", "SN", "SL", "RB", "KM", "JM", "SB", ][d.getDay()];
    }

    function change_shift_cursor_event(button_id, shift_id, nama_shift, shift_color, shift_desc, shift_jammasuk, shift_jampulang, ketidakhadiran_id, nama_ketidakhadiran, ketidakhadiran_color, ketidakhadiran_desc) {

        // init value for shift or ketidakhadiran shift
        set_jadwalshift_id_shift_selected = shift_id;
        set_jadwalshift_nama_shift_selected = nama_shift;
        set_jadwalshift_hex_colorshift_selected = shift_color;
        set_jadwalshift_desc_selected = shift_desc;
        set_jadwalshift_jam_masuk_selected = shift_jammasuk;
        set_jadwalshift_jam_pulang_selected = shift_jampulang;
        set_jadwalshift_id_ketidakhadiran_selected = ketidakhadiran_id;
        set_jadwalshift_nama_ketidakhadiran_selected = nama_ketidakhadiran;
        set_jadwalshift_hex_colorketidakhadiran_selected = ketidakhadiran_color;
        set_jadwalshift_desc_ketidakhadiran_selected = ketidakhadiran_desc;

        // set null for tipeshift
        set_jadwalshift_id_shift_tipe_selected = null;
        set_jadwalshift_nama_shift_tipe_selected = null;

        // set false for clear shift
        set_jadwalshift_clear_shift_selected = false;
    }

    function change_shifttipe_cursor_event(button_id, shift_tipe_id, nama_shift_tipe, desc_shift_tipe) {

        // init value for tipeshift
        set_jadwalshift_id_shift_tipe_selected = shift_tipe_id;
        set_jadwalshift_nama_shift_tipe_selected = nama_shift_tipe;
        set_jadwalshift_desc_shift_tipe_selected = desc_shift_tipe;

        // set null for shift or ketidakhadiranshift
        set_jadwalshift_id_shift_selected = null;
        set_jadwalshift_nama_shift_selected = null;
        set_jadwalshift_hex_colorshift_selected = null;
        set_jadwalshift_desc_selected = null;
        set_jadwalshift_jam_masuk_selected = null;
        set_jadwalshift_jam_pulang_selected = null;
        set_jadwalshift_id_ketidakhadiran_selected = null;
        set_jadwalshift_nama_ketidakhadiran_selected = null;
        set_jadwalshift_hex_colorketidakhadiran_selected = null;
        set_jadwalshift_desc_ketidakhadiran_selected = null;

        // set false for clear shift
        set_jadwalshift_clear_shift_selected = false;
    }

    function change_clearshift_cursor_event(button_id) {

        set_jadwalshift_clear_shift_selected = true;

        set_jadwalshift_id_shift_selected = null;
        set_jadwalshift_nama_shift_selected = null;
        set_jadwalshift_hex_colorshift_selected = null;
        set_jadwalshift_id_ketidakhadiran_selected = null;
        set_jadwalshift_nama_ketidakhadiran_selected = null;
        set_jadwalshift_hex_colorketidakhadiran_selected = null;
    }

    function put_jadwalshift(buttons_id, id_penanggungjawab, id_jadwalkerja_shift) {

        // jika shift tahun, bulan dan unit ini masih diperbolehkan edit
        if (set_jadwalshift_editable) {

            // jika shift atau ketidakhadiran tidak kosong dan hapus shift false
            if ((set_jadwalshift_id_shift_selected !== null || set_jadwalshift_id_ketidakhadiran_selected !== null) && !set_jadwalshift_clear_shift_selected) {

                $(buttons_id).html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;" + set_jadwalshift_nama_shift_selected);

                $.ajax({
                    type: "POST",
                    url: "<?php echo $url_api_master_data; ?>?action=put_jadwalshift",
                    dataType: "JSON",
                    data: {
                        id_penanggungjawab: id_penanggungjawab,
                        id_jadwalkerja_shift: id_jadwalkerja_shift,
                        id_absensi: set_jadwalshift_id_shift_selected,
                        id_ketidakhadiran: set_jadwalshift_id_ketidakhadiran_selected
                    },
                    success: function(response) {

                        if (response.status == 1) {

                            if (response.update_tipe == 1) {

                                $(buttons_id).attr('title', set_jadwalshift_desc_selected + ' (' + set_jadwalshift_jam_masuk_selected + '-' + set_jadwalshift_jam_pulang_selected + ')').css('background-color', '#' + set_jadwalshift_hex_colorshift_selected).html(set_jadwalshift_nama_shift_selected);

                                $(buttons_id).next('button').remove();
                                $(buttons_id).after("<button id='btn_jadwalpegawai_shifttipe_" + id_jadwalkerja_shift + "' name='btn_jadwalpegawai_shifttipe_" + id_jadwalkerja_shift + "' class='btn btn-xs btn-block btn-primary' onclick='put_jadwalshift_shifttipe(`#`+this.id, `" + id_penanggungjawab + "`, `" + id_jadwalkerja_shift + "`);' title='" + response.absensi_tipe.desc_shift_tipe + "'>" + response.absensi_tipe.nama_shift_tipe + "</button>");

                                $('#btn_jadwalpegawai_shifttipe_' + id_jadwalkerja_shift).next('button').remove();
                                $('#btn_jadwalpegawai_shifttipe_' + id_jadwalkerja_shift).after("<button class='btn btn-xs btn-block btn-light' onclick='put_jadwalshift_longshift(`" + id_jadwalkerja_shift + "`, `" + id_penanggungjawab + "`);'><i class='fa fa-plus' style='color:grey'></i></button>");
                            } else {

                                $(buttons_id).attr('title', set_jadwalshift_desc_ketidakhadiran_selected).css('background-color', '#' + set_jadwalshift_hex_colorketidakhadiran_selected).addClass('p-3').html(set_jadwalshift_nama_ketidakhadiran_selected);
                                $(buttons_id).nextAll('button').remove();
                                $(buttons_id).prevAll('button').remove();
                            }
                        } else {

                            $(buttons_id).attr('title', '').html("Libur");
                            swal("Edit Shift Gagal", "", "error");
                            console.log("Kode : PUTSHFITIDABS2. Status = 0");
                        }
                    },
                    error: function(errorMsg) {

                        $(buttons_id).attr('title', '').html("Libur");
                        swal("Edit Shift Gagal", "", "error");
                        console.log("Kode : PUTSHFITIDABS1. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
                    }
                });
            } else if ((set_jadwalshift_id_shift_selected == null || set_jadwalshift_id_ketidakhadiran_selected == null) && set_jadwalshift_clear_shift_selected) {

                var prevnameshift_selected = $(buttons_id).text();

                $(buttons_id).html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>");

                $.ajax({
                    type: "POST",
                    url: "<?php echo $url_api_master_data; ?>?action=put_jadwalshift_clear",
                    data: {
                        id_jadwalkerja_shift: id_jadwalkerja_shift,
                        id_penanggung_jawab: id_penanggungjawab
                    },
                    dataType: "JSON",
                    success: function(response) {

                        if (response.is_longshift) {

                            $(buttons_id).next('button').remove();
                            $(buttons_id).remove();
                        } else {

                            $(buttons_id).attr('title', '').css('background-color', '').html("Libur");
                            $(buttons_id).next('button').remove();
                            $(buttons_id).next('button').remove();
                        }
                    },
                    error: function(errorMsg) {

                        $(buttons_id).attr('title', '').html(prevnameshift_selected);
                        swal("Edit Shift Gagal", "", "error");
                        console.log("Kode : CLRSHFITIDABS1. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
                    }
                });
            }
        }
    }

    function put_jadwalshift_longshift(parent_id_jadwalkerja_shift, id_penanggung_jawab) {

        // jika shift tahun, bulan dan unit ini masih diperbolehkan edit
        if (set_jadwalshift_editable) {

            // jika shift atau ketidakhadiran tidak kosong dan hapus shift false
            if ((set_jadwalshift_id_shift_selected !== null || set_jadwalshift_id_ketidakhadiran_selected !== null) && !set_jadwalshift_clear_shift_selected) {

                $.ajax({
                    type: "POST",
                    url: "<?php echo $url_api_master_data; ?>?action=post_long_shift",
                    dataType: "JSON",
                    data: {
                        parent_id_jadwalkerja_shift: parent_id_jadwalkerja_shift,
                        id_penanggung_jawab: id_penanggung_jawab,
                        id_absensi: set_jadwalshift_id_shift_selected,
                    },
                    success: function(response) {

                        if (response.status == 1) {

                            // remove button add longshift berdasarkan after shifttipe parentnya
                            $('#btn_jadwalpegawai_shifttipe_' + parent_id_jadwalkerja_shift).next('button').remove();

                            // add shift baru berdasarkan id dari json
                            $("#btn_jadwalpegawai_shifttipe_" + parent_id_jadwalkerja_shift).after("<button type='button' id='btn_edit_arr_pengaturan_shift_" + response.id_jadwalkerja_shift + "' name='btn_edit_arr_pengaturan_shift_" + response.id_jadwalkerja_shift + "' class='btn btn-xs btn-block' onclick='put_jadwalshift(`#`+this.id, `" + response.id_penanggung_jawab + "`, `" + response.id_jadwalkerja_shift + "`);' title='" + set_jadwalshift_desc_selected + "(" + set_jadwalshift_jam_masuk_selected + "-" + set_jadwalshift_jam_pulang_selected + ")" + "' style='background-color: #" + set_jadwalshift_hex_colorshift_selected + "'>" + set_jadwalshift_nama_shift_selected + "</button>");

                            // add shift tipenya
                            $('#btn_edit_arr_pengaturan_shift_' + response.id_jadwalkerja_shift).next('button').remove();
                            $('#btn_edit_arr_pengaturan_shift_' + response.id_jadwalkerja_shift).after("<button id='btn_jadwalpegawai_shifttipe_" + response.id_jadwalkerja_shift + "' name='btn_jadwalpegawai_shifttipe_" + response.id_jadwalkerja_shift + "' class='btn btn-xs btn-block btn-primary' onclick='put_jadwalshift_shifttipe(`#`+this.id, `" + response.id_penanggungjawab + "`, `" + response.id_jadwalkerja_shift + "`);' title='" + response.absensi_tipe.desc_shift_tipe + "'>" + response.absensi_tipe.nama_shift_tipe + "</button>");

                            // add button add longshift
                            $('#btn_jadwalpegawai_shifttipe_' + response.id_jadwalkerja_shift).after("<button class='btn btn-xs btn-block btn-light' onclick='put_jadwalshift_longshift(`" + response.id_jadwalkerja_shift + "`, `" + id_penanggung_jawab + "`);'><i class='fa fa-plus' style='color:grey'></i></button>");
                        } else {

                            swal(response.message, "", "error");
                            console.log("Kode : PUTLONGSHIFTABS2. Status = 0. Message = " + response.message);
                        }
                    },
                    error: function(errorMsg) {

                        swal("Permintaan Longshift Gagal", "", "error");
                        console.log("Kode : PUTLONGSHIFTABS1. Gagal mengirim permintaaan. " + errorMsg.status + "-" + errorMsg.statusText);
                    }
                });
            }
        }
    }

    function put_jadwalshift_shifttipe(buttons_id, id_penanggungjawab, id_jadwalkerja_shift) {

        // jika shift tahun, bulan dan unit ini masih diperbolehkan edit
        if (set_jadwalshift_editable) {

            if (set_jadwalshift_id_shift_tipe_selected !== null) {

                $(buttons_id).html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;" + set_jadwalshift_nama_shift_tipe_selected);

                $.ajax({
                    type: "POST",
                    url: "<?php echo $url_api_master_data; ?>?action=put_jadwalshift_tipeshit",
                    data: {
                        id_jadwalkerja_shift: id_jadwalkerja_shift,
                        id_absensi_tipe: set_jadwalshift_id_shift_tipe_selected,
                        id_penanggung_jawab: id_penanggungjawab
                    },
                    dataType: "JSON",
                    success: function(response) {

                        $(buttons_id).attr('title', set_jadwalshift_desc_shift_tipe_selected).html(set_jadwalshift_nama_shift_tipe_selected);
                    },
                    error: function(error) {

                        $(buttons_id).attr('title', '').html('-');
                        swal("Edit Tipe Shift Gagal", "", "error");
                        console.log("Kode : PUTTIPESHIFTIDABS1. Gagal mengirim permintaan. " + error.status + "-" + error.statusText);
                    }
                });
            }
        }
    }

    function search_periode() {

        $('#btn-submit-set-jadwalshift').hide();
        $('#btn-rekap-shift-calculate').hide();
        $('#box-print-set-jadwalshift').hide();
        $('#btn-generate-jadwalshift').hide();
        $('#btn-delete-jadwalshift').hide();

        $('#rekap-shift-table tbody').empty();
        $('#set-jadwalshift-table-log-validasi tbody').empty();

        $('#box-set-jadwalshift-tabel').html("");
        $('#box-set-jadwalshift-tabel').append('<table id="set-jadwalshift-tabel" name="set-jadwalshift-tabel" class="table table-bordered table-striped"><thead></thead><tbody></tbody></table>');

        $("#set-jadwalshift_list-shift").html("");
        $("#set-jadwalshift_list-ketidakhadiranshift").html("");
        $("#set-jadwalshift_hapusshift").html("");
        $("#set-jadwalshift_list_hariraya").html("");
        $('#set-jadwalshift-search').html('<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>&nbsp;Mohon Tunggu').prop('disabled', true);

        var month = remove_leading_zero($("#set-jadwalshift-select-month option:selected").val());
        var year = $("#set-jadwalshift-select-year option:selected").val();
        var id_unit = $("#set-jadwalshift-select-unit option:selected").val();

        $.ajax({
            type: "GET",
            url: "<?php echo $url_api_master_data; ?>?action=get_data_pengaturan_shift&id_unit=" + id_unit + "&month=" + month + "&year=" + year,
            dataType: "JSON",
            success: function(data) {

                if (data.status == 1) {

                    // initialize thead
                    var thead_value = '<tr><td><b>No.</b></td><td><b>Nama</b></td>';
                    for (let h = 0; h < data.data.date_in_month.length; h++) {

                        var style_font = get_day_by_date(data.data.date_in_month[h]) == 'MG' ? "color:red" : "color:black";

                        thead_value += '<td><b><p style="' + style_font + '">' + (h + 1) + '' + get_day_by_date(data.data.date_in_month[h]) + '</p></b></td>';
                    }
                    $("#set-jadwalshift-tabel thead").append(thead_value);

                    // initialize body
                    // dilooping berdasarkan jumlah user dalam unit
                    for (let i = 0; i < data.data.data.length; i++) {

                        var tbody_value = '<tr><td>' + (i + 1) + '</td><td>' + data.data.data[i]['nama_pegawai'] + '</td>';

                        // dilooping berdasarkan jumlah shift user dalam 1 bulan
                        for (let j = 0; j < data.data.data[i].shift.length; j++) {

                            var date = data.data.data[i].shift[j]['date'];
                            var date_next = data.data.data[i].shift[(j + 1) < data.data.data[i].shift.length ? (j + 1) : j]['date'];
                            var date_prev = data.data.data[i].shift[j != 0 ? (j - 1) : j]['date'];
                            var shift_aktif = data.data.data[i].shift[j]['shift_aktif'];
                            var id_absensi = data.data.data[i].shift[j]['id_absensi'];
                            var id_jadwalkerja_shift = data.data.data[i].shift[j]['id_jadwalkerja_shift'];
                            var id_penanggung_jawab = data.data.data[i].shift[j]['id_penanggung_jawab'];
                            var hex_color_absensi = data.data.data[i].shift[j]['hex_color_absensi'];
                            var nama_absensi = data.data.data[i].shift[j]['nama_absensi'];
                            var jammasuk_absensi = data.data.data[i].shift[j]['jam_masuk'];
                            var jampulang_absensi = data.data.data[i].shift[j]['jam_pulang'];
                            var desc_absensi = data.data.data[i].shift[j]['desc_absensi'];
                            var desc_shift_tipe = data.data.data[i].shift[j]['desc_shift_tipe'];
                            var nama_shift_tipe = data.data.data[i].shift[j]['nama_shift_tipe'];
                            var tooltip_absensi = desc_absensi + " (" + jammasuk_absensi + "-" + jampulang_absensi + ")";

                            tbody_value += "<td>";
                            if (id_absensi != "") { // apabila id_absensi tidak kosong, dianggap ada shiftnya

                                if (date == date_prev && j != 0) { // apabila tanggal saat ini sama dengan tanggal sebelumnya, dianggap longshift

                                    tbody_value = tbody_value.slice(0, -9);
                                    tbody_value += '<button id="btn_edit_arr_pengaturan_shift_' + id_jadwalkerja_shift + '" name="btn_edit_arr_pengaturan_shift_' + id_jadwalkerja_shift + '" type="button" class="btn btn-xs btn-block" onclick="put_jadwalshift(`#`+this.id, `<?php echo $id_user; ?>`, `' + id_jadwalkerja_shift + '`);" title="' + tooltip_absensi + '" style="background-color: #' + hex_color_absensi + '" >' + nama_absensi + '</i></button>' + (shift_aktif ? '<button id="btn_jadwalpegawai_shifttipe_' + id_jadwalkerja_shift + '" name="btn_jadwalpegawai_shifttipe_' + id_jadwalkerja_shift + '" class="btn btn-xs btn-block btn-primary" onclick="put_jadwalshift_shifttipe(`#`+this.id, `<?php echo $id_user; ?>`, `' + id_jadwalkerja_shift + '`);" title="' + desc_shift_tipe + '">' + nama_shift_tipe + '</button>' : '');
                                } else {

                                    tbody_value += '<button id="btn_edit_arr_pengaturan_shift_' + id_jadwalkerja_shift + '" name="btn_edit_arr_pengaturan_shift_' + id_jadwalkerja_shift + '" type="button" class="btn btn-xs btn-block" onclick="put_jadwalshift(`#`+this.id, `<?php echo $id_user; ?>`, `' + id_jadwalkerja_shift + '`);" title="' + tooltip_absensi + '" style="background-color: #' + hex_color_absensi + '" >' + nama_absensi + '</i></button>' + (shift_aktif ? '<button id="btn_jadwalpegawai_shifttipe_' + id_jadwalkerja_shift + '" name="btn_jadwalpegawai_shifttipe_' + id_jadwalkerja_shift + '" class="btn btn-xs btn-block btn-primary" onclick="put_jadwalshift_shifttipe(`#`+this.id, `<?php echo $id_user; ?>`, `' + id_jadwalkerja_shift + '`);" title="' + desc_shift_tipe + '">' + nama_shift_tipe + '</button>' : '');
                                }

                                // kalau (tanggal saat ini beda dengan tanggal besok atau diposisi tanggal terakhir) dan tipe shift aktif
                                // berarti ditambahkan tombol add longshift
                                if ((date != date_next || (j + 1) == data.data.data[i].shift.length) && shift_aktif) {

                                    tbody_value += '<button class="btn btn-xs btn-block btn-light" onclick="put_jadwalshift_longshift(`' + id_jadwalkerja_shift + '`, `<?php echo $id_user; ?>`);"><i class="fa fa-plus" style="color:grey"></i></button>';
                                }
                            } else { // id_absensi kosong, dianggap libur atau shift tidak aktif

                                tbody_value += '<button id="btn_edit_arr_pengaturan_shift_' + id_jadwalkerja_shift + '" name="btn_edit_arr_pengaturan_shift_' + id_jadwalkerja_shift + '" type="button" class="btn btn-xs btn-block" onclick="put_jadwalshift(`#`+this.id, `<?php echo $id_user; ?>`, `' + id_jadwalkerja_shift + '`);">Libur</button>';
                            }

                            tbody_value += '</td>';
                        }

                        tbody_value += '</tr>';
                        $("#set-jadwalshift-tabel tbody").append(tbody_value);
                    }

                    // initialize datatable shift
                    if (!$.fn.dataTable.isDataTable('#set-jadwalshift-tabel')) {

                        $('#set-jadwalshift-tabel').DataTable({
                            "responsive": true,
                            "autoWidth": false,
                            "scrollX": true,
                            "pageLength": 50
                        });
                    }

                    // add button print to excel

                    // check editable shift is allowed
                    if (data.data.bool_shift_editable) {

                        // set submitted to false
                        set_jadwalshift_editable = true;

                        // add shift options
                        $("#set-jadwalshift_list-shift").append("<h4>List Opsi Shift</h4>");
                        for (var i = 0; i < data.data.shift_options.length; i++) {

                            var id_absensi = data.data.shift_options[i]['id_absensi'];
                            var nama_shift = data.data.shift_options[i]['nama_shift'];
                            var color_shift = data.data.shift_options[i]['hex_color_shift'];
                            var desc_shift = data.data.shift_options[i]['desc_shift'];
                            var jam_masuk = data.data.shift_options[i]['jam_masuk'];
                            var jam_pulang = data.data.shift_options[i]['jam_pulang'];
                            var tooltip = desc_shift + ' (' + jam_masuk + '-' + jam_pulang + ') ';

                            $("#set-jadwalshift_list-shift").append("<button id='set-jadwalshift_" + id_absensi + "_options' style='background-color: #" + color_shift + "; margin-bottom: 10px; margin-right: 3px' class='btn btn-md active' onclick='change_shift_cursor_event(`#`+this.id, `" + id_absensi + "`, `" + nama_shift + "`, `" + color_shift + "`, `" + desc_shift + "`, `" + jam_masuk + "`, `" + jam_pulang + "`, null, null, null, null);' title='" + tooltip + "'>" + nama_shift + "</button>");
                        }

                        // add ketidakhadiran options
                        $("#set-jadwalshift_list-ketidakhadiranshift").append("<h4>List Opsi Ketidakhadiran</h4>");
                        for (var i = 0; i < data.data.ketidakhadiranshift_options.length; i++) {

                            var id_ketidakhadiran = data.data.ketidakhadiranshift_options[i]['id_ketidakhadiran'];
                            var nama_ketidakhadiran = data.data.ketidakhadiranshift_options[i]['nama_ketidakhadiran'];
                            var hex_color_ketidakhadiran = data.data.ketidakhadiranshift_options[i]['hex_color_ketidakhadiran'];
                            var desc_ketidakhadiran = data.data.ketidakhadiranshift_options[i]['desc_ketidakhadiran'];

                            $("#set-jadwalshift_list-ketidakhadiranshift").append("<button id='set-jadwalshift_" + id_ketidakhadiran + "_options' style='background-color: #" + hex_color_ketidakhadiran + "; margin-bottom: 10px; margin-right: 3px' class='btn btn-md active' onclick='change_shift_cursor_event(`#`+this.id, null, null, null, null, null, null, `" + id_ketidakhadiran + "`, `" + nama_ketidakhadiran + "`, `" + hex_color_ketidakhadiran + "`, `" + desc_ketidakhadiran + "`);' title='" + desc_ketidakhadiran + "'>" + nama_ketidakhadiran + "</button>&nbsp;");
                        }

                        // add hapus shift options
                        $("#set-jadwalshift_hapusshift").append("<h4>List Opsi Libur</h4>");
                        $("#set-jadwalshift_hapusshift").append("<button class='btn btn-md btn-second' onclick='change_clearshift_cursor_event(`#`+this.id);'>Libur</button>");
                    } else {

                        // set editable to true
                        set_jadwalshift_editable = false;
                    }

                    // check submitable shift is allowed
                    if (data.data.bool_shift_submitable) {

                        // show  submit shift
                        $("#btn-submit-set-jadwalshift").show();
                    } else {

                        // hide submit shift
                        $("#btn-submit-set-jadwalshift").hide();
                    }

                    // tampilkan tombol print jadwalshift
                    $('#box-print-set-jadwalshift').show();

                    // tampilkan tombol hapus
                    $('#btn-delete-jadwalshift').show();

                    //get log data
                    get_log_pengaturan_shift();

                    //get rekapitulasi data
                    get_rekapitulasi_shift_by_pj();

                    // get list hari raya
                    get_list_hari_raya();
                }

                // tampilkan tombol generate tambah
                $('#btn-generate-jadwalshift').show();

                $('#set-jadwalshift-search').html('<i class="fa fa-search">&nbsp;&nbsp;Cari Jadwal Shift</i>').prop('disabled', false);
            },
            error: function(errorMsg) {

                $('#set-jadwalshift-search').html('<i class="fa fa-search">&nbsp;&nbsp;Cari Jadwal Shift</i>').prop('disabled', false);
                console.log("Kode : SEARCHPERIODE1. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
                swal('Gagal Mengirim Permintaan Shift', 'Silahkan Dicoba kembali', 'error');
            }
        });
    }

    function search_selected_periode() {

        var set_jadwalshit_year = $('#set-jadwalshit_year').val();
        var set_jadwalshit_month = $('#set-jadwalshit_month').val();
        var set_jadwalshit_id_unit = $('#set-jadwalshit_id-unit').val();

        $('#set-jadwalshift-select-month').val(adding_leading_zero(set_jadwalshit_month));
        $('#set-jadwalshift-select-year').val(set_jadwalshit_year);
        $('#set-jadwalshift-select-unit').val(set_jadwalshit_id_unit);

        search_periode();
    }

    function generate_shift(current_id_user) {

        var month = remove_leading_zero($("#set-jadwalshift-select-month option:selected").val());
        var year = $("#set-jadwalshift-select-year option:selected").val();
        var id_unit = $("#set-jadwalshift-select-unit").val();

        $('#btn-generate-jadwalshift').html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;Mohon Tunggu").css("pointer-events", "none");

        $.ajax({
            type: "POST",
            url: "<?php echo $url_api_master_data; ?>?action=act_generate_shift",
            dataType: "JSON",
            data: {
                id_unit: id_unit,
                month: month,
                year: year,
                id_penanggungjawab: current_id_user
            },
            success: function(data) {
                if (data.status == 1) {

                    //after successfully generating shift record. aplication must 'searching' again to avoid mal-data.
                    search_periode();
                } else {
                    console.log("Kode : GENERATESHIFT1. Kode Status = 0.");
                    console.log(data);
                }

                $('#btn-generate-jadwalshift').html("Buat/Tambah Jadwal Otomatis").css("pointer-events", "auto");
            },
            error: function(errorMsg) {

                $('#btn-generate-jadwalshift').html("Buat/Tambah Jadwal Otomatis").css("pointer-events", "auto");
                console.log("Kode : GENERATESHIFT1. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
            }
        });
    }

    function submit_jadwalshift_by_pj() {

        $('#modal-warning-submit-jadwalshift-by-pj').modal('hide');
        $('#btn-submit-set-jadwalshift').html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;Mohon Tunggu").css("pointer-events", "none");

        var month = remove_leading_zero($("#set-jadwalshift-select-month option:selected").val());
        var year = $("#set-jadwalshift-select-year option:selected").val();
        var id_unit = $("#set-jadwalshift-select-unit").val();

        $.ajax({
            type: "POST",
            url: "<?php echo $url_api_master_data; ?>?action=act_submit_pengaturan_shift",
            dataType: "JSON",
            data: {
                id_unit: id_unit,
                month: month,
                year: year
            },
            success: function(data) {

                if (data.status == 1) {

                    $('#btn-submit-set-jadwalshift').hide();
                    $('#set-jadwalshift_list-shift').empty();
                    $('#set-jadwalshift_list-ketidakhadiranshift').empty();
                    $('#set-jadwalshift_hapusshift').empty();
                    $('#btn-submit-set-jadwalshift').html("Submit").css("pointer-events", "auto");
                    set_jadwalshift_editable = false;
                    swal("Submit Jadwal Shift Berhasil", data.message, "success");
                } else {

                    $('#btn-submit-set-jadwalshift').html("Submit").css("pointer-events", "auto");

                    swal("Submit Jadwal Shift Gagal", data.message, "error");

                    console.log("Kode : SBSHIFT1. Kode Status = 0.");
                    console.log(data);
                }
            },
            error: function(errorMsg) {
                $('#btn-submit-set-jadwalshift').html("Submit").css("pointer-events", "auto");

                swal("Submit Jadwal Shift Gagal", "Silahkan hubungi helpdesk", "error");

                console.log("Kode : SBSHIFT1. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
            }
        });
    }

    function get_log_pengaturan_shift() {

        $('#set-jadwalshift-box-table-log-validasi').html("");
        $('#set-jadwalshift-box-table-log-validasi').append('<table id="set-jadwalshift-table-log-validasi" name="set-jadwalshift-table-log-validasi" class="table table-bordered table-striped"><thead><tr><th>No.</th><th>Tanggal</th><th>Keterangan</th><th>Catatan</th></tr></thead><tbody></tbody></table>');

        var month = remove_leading_zero($("#set-jadwalshift-select-month option:selected").val());
        var year = $("#set-jadwalshift-select-year option:selected").val();
        var id_unit = $("#set-jadwalshift-select-unit option:selected").val();

        $.ajax({
            type: "GET",
            url: "<?php echo $url_api_master_data; ?>?action=get_log_pengaturan_shift&id_unit=" + id_unit + "&month=" + month + "&year=" + year,
            dataType: "JSON",
            success: function(data) {

                if (data.status == 1) {

                    for (let i = 0; i < data.data.length; i++) {

                        var index = i + 1;
                        var tanggal = data.data[i]['created'];
                        var keterangan = "keterangan" in data.data[i]['log'] ? data.data[i]['log']['keterangan'] : "-";
                        var catatan = "catatan" in data.data[i]['log'] ? data.data[i]['log']['catatan'] : "-";

                        $('#set-jadwalshift-table-log-validasi tbody').append('<tr><td>' + index + '</td><td>' + tanggal + '</td><td>' + keterangan + '</td><td>' + catatan + '</td></tr>');
                    }

                    if (!$.fn.dataTable.isDataTable('#table-log-validasi')) {

                        $('#set-jadwalshift-table-log-validasi').DataTable({
                            "responsive": true,
                            "autoWidth": false,
                            "paging": true,
                            "pageLength": 3,
                            "scrollX": true,
                            "searching": false
                        });
                    } else {

                        $('#set-jadwalshift-table-log-validasi').DataTable({
                            "destroy": true,
                            "responsive": true,
                            "autoWidth": false,
                            "paging": true,
                            "pageLength": 3,
                            "scrollX": true,
                            "searching": false
                        });
                    }
                } else {

                    console.log("Kode : GETLOGSHIFT1. Kode Status = 0.");
                    console.log(data);
                }
            },
            error: function(errorMsg) {
                console.log("Kode : GETLOGSHIFT1. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
            }
        });
    }

    function get_rekapitulasi_shift_by_pj() {

        $('#rekap-shift-table tbody').empty();

        var month = remove_leading_zero($("#set-jadwalshift-select-month option:selected").val());
        var year = $("#set-jadwalshift-select-year option:selected").val();
        var id_unit = $("#set-jadwalshift-select-unit option:selected").val();

        $.ajax({
            type: "GET",
            url: "<?php echo $url_api_master_data; ?>?action=get_rekapitulasi_shift_by_pj&id_unit=" + id_unit + "&month=" + month + "&year=" + year,
            dataType: "JSON",
            success: function(response) {

                if (response.status == 1) {

                    $("#rekap-shift-table tbody").empty();
                    for (let i = 0; i < response.data.length; i++) {

                        var nama_pegawai = response.data[i]['nama_pegawai'];
                        var pagi = response.data[i]['pagi'];
                        var sore = response.data[i]['sore'];
                        var malam = response.data[i]['malam'];
                        var libur = response.data[i]['libur'];

                        $('#rekap-shift-table tbody').append("<tr><td>" + (i + 1) + "</td><td>" + nama_pegawai + "</td><td>" + pagi + "</td><td>" + sore + "</td><td>" + malam + "</td><td>" + libur + "</td></tr>");
                    }

                    $('#btn-rekap-shift-calculate').show();
                } else {

                    swal("Gagal Mendapatkan Rekapitulasi Shift", "", "error");
                    console.log("Kode : GETRKPSHIFTPJ02. Status = 0");
                }
            },
            error: function(error) {

                swal("Gagal Mendapatkan Rekapitulasi Shift", "", "error");
                console.log("Kode : GETRKPSHIFTPJ01. Gagal mengirim permintaan. " + error.status + "-" + error.statusText);
            }
        });
    }

    function get_list_hari_raya() {

        var month = remove_leading_zero($("#set-jadwalshift-select-month option:selected").val());
        var year = $("#set-jadwalshift-select-year option:selected").val();

        $.ajax({
            type: "GET",
            url: "<?php echo $url_api_master_data; ?>?action=get_list_hari_raya&month=" + month + "&year=" + year,
            dataType: "JSON",
            success: function(response) {

                if (response.status) {
                    $("#set-jadwalshift_list_hariraya").append("<h4>Keterangan Hari Raya</h4><table id='table-list-hari-raya'></table>");

                    for (let i = 0; i < response.data.length; i++) {

                        $("#table-list-hari-raya").append("<tr><td>~&nbsp;&nbsp;</td><td>" + response.data[i]['tanggal'] + "</td><td>&nbsp;&nbsp;</td><td>" + response.data[i]['keterangan'] + "</td></tr>");
                    }
                } else {

                    console.log("Kode : GETHRRAYA1. Kode Status = 0");
                }
            },
            error: function(errorMsg) {
                console.log("Kode : GETHRRAYA1. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
            }
        });

    }

    function delete_shift(current_id_user) {

        var month = remove_leading_zero($("#set-jadwalshift-select-month option:selected").val());
        var year = $("#set-jadwalshift-select-year option:selected").val();
        var id_unit = $("#set-jadwalshift-select-unit option:selected").val();

        $.ajax({
            url: "<?php echo $url_api_master_data ?>?action=delete_jadwal_shift",
            type: "POST",
            data: {
                id_unit: id_unit,
                month: month,
                year: year
            },
            dataType: "JSON",
            success: function(response) {

                console.log(response);
                search_periode();
            },
            error: function(error) {

                console.log('Kode : DELSHIFT1. Gagal mengirim permintaan. ' + error.status + '-' + error.statusText);
                swal("Gagal Mengirim Permintaan Hapus Shift", "", "error");
            }
        });
    }

    function print_jadwalshift() {

        var month = remove_leading_zero($("#set-jadwalshift-select-month option:selected").val());
        var year = $("#set-jadwalshift-select-year option:selected").val();
        var id_unit = $("#set-jadwalshift-select-unit option:selected").val();

        window.open("<?php echo $url_app; ?>/cetakan/cetak-shift-by-pj.php?month=" + month + "&year=" + year + "&id_unit=" + id_unit, '_blank');
    }
    // end fungsi utk set-jadwalshift

    // fungsi untuk list-shift-pegawai-all-unit
    function search_periode_pegawai_all_unit(id_level) {

        $('#box-list-shift-pegawai-all-unit').html("");
        $('#box-list-shift-pegawai-all-unit').append('<table id="list-shift-pegawai-by-managerial-tabel" name="list-shift-pegawai-by-managerial-tabel" class="table table-bordered table-striped"><thead></thead><tbody></tbody></table>');

        $('#list-shift-pegawai-all-unit_list-shift').html("");
        $('#list-shift-pegawai-all-unit_hapusshift').html("");
        $('#list-shift-pegawai-all-unit_list-hariraya').html("");
        $('#list-shift-pegawai-all-unit_list-shift').html("");
        $('#list-shift-pegawai-all-unit_list-ketidakhadiranshift').html("");

        var month = remove_leading_zero($("#list-shift-pegawai-by-managerial-select-month option:selected").val());
        var year = $("#list-shift-pegawai-by-managerial-select-year option:selected").val();
        var id_unit = $("#list-shift-pegawai-by-managerial-select-unit option:selected").val();

        var bool_edit_shift_default = id_level == 'LVL-000013'; // diperbolehkan untuk koordinator kepegawaian

        $.ajax({
            type: "GET",
            url: "<?php echo $url_api_master_data; ?>?action=get_data_pengaturan_shift&id_unit=" + id_unit + "&month=" + month + "&year=" + year,
            dataType: "JSON",
            success: function(response) {

                if (response.status == 1) {

                    // initialize thead
                    var thead_value = '<tr><td><b>No.</b></td><td><b>Nama</b></td>';
                    for (let i = 0; i < response.data.date_in_month.length; i++) {

                        var style_font = get_day_by_date(response.data.date_in_month[i] == 'MG' ? 'color:red' : 'color:black');

                        thead_value += "<td><b><p style='" + style_font + "'>" + (i + 1) + "" + get_day_by_date(response.data.date_in_month[i]) + "</p></b></td>";
                    }
                    thead_value += "</tr>";
                    $("#list-shift-pegawai-by-managerial-tabel thead").append(thead_value);

                    // initialize tbody
                    // dilooping berdasarkan jumlah unit dalam unit
                    for (let i = 0; i < response.data.data.length; i++) {

                        var tbody_value = "<tr><td>" + (i + 1) + "</td><td>" + response.data.data[i]['nama_pegawai'] + "</td>";
                        for (let j = 0; j < response.data.data[i].shift.length; j++) {

                            var date = response.data.data[i].shift[j]['date'];
                            var date_next = response.data.data[i].shift[(j + 1) < response.data.data[i].shift.length ? (j + 1) : j]['date'];
                            var date_prev = response.data.data[i].shift[j != 0 ? (j - 1) : j]['date'];
                            var shift_aktif = response.data.data[i].shift[j]['shift_aktif'];
                            var id_absensi = response.data.data[i].shift[j]['id_absensi'];
                            var id_jadwalkerja_shift = response.data.data[i].shift[j]['id_jadwalkerja_shift'];
                            var id_penanggung_jawab = response.data.data[i].shift[j]['id_penanggung_jawab'];
                            var hex_color_absensi = response.data.data[i].shift[j]['hex_color_absensi'];
                            var nama_absensi = response.data.data[i].shift[j]['nama_absensi'];
                            var jammasuk_absensi = response.data.data[i].shift[j]['jam_masuk'];
                            var jampulang_absensi = response.data.data[i].shift[j]['jam_pulang'];
                            var desc_absensi = response.data.data[i].shift[j]['desc_absensi'];
                            var desc_shift_tipe = response.data.data[i].shift[j]['desc_shift_tipe'];
                            var nama_shift_tipe = response.data.data[i].shift[j]['nama_shift_tipe'];
                            var tooltip_absensi = desc_absensi + " (" + jammasuk_absensi + "-" + jampulang_absensi + ")";

                            tbody_value += "<td>";
                            if (id_absensi != "") { // apabila id_absensi tidak kosoong, dianggap ada shiftnya

                                if (date == date_prev && j != 0) { // apabila tanggal saat ini sama dengan sebelumnya, dianggap longshift

                                    tbody_value = tbody_value.slice(0, -9);
                                    tbody_value += "<button type='button' class='btn btn-xs btn-block' onclick='put_jadwalshift(`#`+this.id, `<?php echo $id_user; ?>`, `" + id_jadwalkerja_shift + "`);' id='btn_shift_all_unit_" + id_jadwalkerja_shift + "' name='btn_shift_all_unit_" + id_jadwalkerja_shift + "' title='" + tooltip_absensi + "' style='background-color: #" + hex_color_absensi + "'>" + nama_absensi + "</button>" + (shift_aktif ? "<button id='btn_tipeshift_all_unit_" + id_jadwalkerja_shift + "' name='btn_tipeshift_all_unit_" + id_jadwalkerja_shift + "' class='btn btn-xs btn-block btn-primary' title='" + desc_shift_tipe + "'>" + nama_shift_tipe + "</button>" : "");
                                } else {

                                    tbody_value += "<button type='button' class='btn btn-xs btn-block' onclick='put_jadwalshift(`#`+this.id, `<?php echo $id_user; ?>`, `" + id_jadwalkerja_shift + "`);' id='btn_shift_all_unit_" + id_jadwalkerja_shift + "' name='btn_shift_all_unit_" + id_jadwalkerja_shift + "' title='" + tooltip_absensi + "' style='background-color: #" + hex_color_absensi + "'>" + nama_absensi + "</button>" + (shift_aktif ? "<button id='btn_tipeshift_all_unit_" + id_jadwalkerja_shift + "' name='btn_tipeshift_all_unit_" + id_jadwalkerja_shift + "' class='btn btn-xs btn-block btn-primary' title='" + desc_shift_tipe + "'>" + nama_shift_tipe + "</button>" : "");
                                }

                                if (date != date_next // kalau tanggal saat ini beda dengan tanggal selanjutnya
                                    &&
                                    shift_aktif //  dan tipe shift aktif
                                    &&
                                    bool_edit_shift_default // dan boolean default edit diperbolehkan
                                ) {

                                    tbody_value += "<button class='btn btn-xs btn-block btn-light' onclick='put_jadwalshift_longshift(`" + id_jadwalkerja_shift + "`, `<?php echo $id_user; ?>`);'><i class='fa fa-plus' style='color:grey'></i></button>";
                                }
                            } else { // id_absensi kosong, dianggap libur atau shift tidak aktif

                                tbody_value += "<button id='btn_shift_all_unit_" + id_jadwalkerja_shift + "' name='btn_shift_all_unit_" + id_jadwalkerja_shift + "' type='button' class='btn btn-xs btn-block' onclick='put_jadwalshift(`#`+this.id, `<?php echo $id_user; ?>`, `" + id_jadwalkerja_shift + "`);'>Libur</button>";
                            }

                            tbody_value += "</td>";
                        }

                        tbody_value += "</tr>";
                        $("#list-shift-pegawai-by-managerial-tabel tbody").append(tbody_value);
                    }

                    if (!$.fn.dataTable.isDataTable('#list-shift-pegawai-by-managerial-tabel')) {

                        $('#list-shift-pegawai-by-managerial-tabel').DataTable({
                            "responsive": true,
                            "autoWidth": false,
                            "scrollX": true
                        });
                    }

                    //get log data
                    get_log_pengaturan_shift_all_unit();

                    // get list hari raya
                    get_list_hari_raya_all_unit();

                    // check bool_edit_shift_default
                    if (bool_edit_shift_default) {


                        // check editable shift is allowed
                        if (response.data.bool_shift_editable || true) {

                            // set editable to true
                            set_jadwalshift_editable = true;

                            // add shift options
                            $("#list-shift-pegawai-all-unit_list-shift").append("<h4>List Opsi Shift</h4>");
                            for (var i = 0; i < response.data.shift_options.length; i++) {

                                var id_absensi = response.data.shift_options[i]['id_absensi'];
                                var nama_shift = response.data.shift_options[i]['nama_shift'];
                                var color_shift = response.data.shift_options[i]['hex_color_shift'];
                                var desc_shift = response.data.shift_options[i]['desc_shift'];
                                var jam_masuk = response.data.shift_options[i]['jam_masuk'];
                                var jam_pulang = response.data.shift_options[i]['jam_pulang'];
                                var tooltip = desc_shift + ' (' + jam_masuk + '-' + jam_pulang + ') ';

                                $("#list-shift-pegawai-all-unit_list-shift").append("<button id='set-jadwalshift_" + id_absensi + "_options' style='background-color: #" + color_shift + "; margin-bottom: 10px; margin-right: 3px' class='btn btn-md active' onclick='change_shift_cursor_event(`#`+this.id, `" + id_absensi + "`, `" + nama_shift + "`, `" + color_shift + "`, `" + desc_shift + "`, `" + jam_masuk + "`, `" + jam_pulang + "`, null, null, null, null);' title='" + tooltip + "'>" + nama_shift + "</button>");
                            }

                            // add ketidakhadiran options
                            $("#list-shift-pegawai-all-unit_list-ketidakhadiranshift").append("<h4>List Opsi Ketidakhadiran</h4>");
                            for (var i = 0; i < response.data.ketidakhadiranshift_options.length; i++) {

                                var id_ketidakhadiran = response.data.ketidakhadiranshift_options[i]['id_ketidakhadiran'];
                                var nama_ketidakhadiran = response.data.ketidakhadiranshift_options[i]['nama_ketidakhadiran'];
                                var hex_color_ketidakhadiran = response.data.ketidakhadiranshift_options[i]['hex_color_ketidakhadiran'];
                                var desc_ketidakhadiran = response.data.ketidakhadiranshift_options[i]['desc_ketidakhadiran'];

                                $("#list-shift-pegawai-all-unit_list-ketidakhadiranshift").append("<button id='set-jadwalshift_" + id_ketidakhadiran + "_options' style='background-color: #" + hex_color_ketidakhadiran + "; margin-bottom: 10px; margin-right: 3px' class='btn btn-md active' onclick='change_shift_cursor_event(`#`+this.id, null, null, null, null, null, null, `" + id_ketidakhadiran + "`, `" + nama_ketidakhadiran + "`, `" + hex_color_ketidakhadiran + "`, `" + desc_ketidakhadiran + "`);' title='" + desc_ketidakhadiran + "'>" + nama_ketidakhadiran + "</button>&nbsp;");
                            }

                            // add hapus shift options
                            $("#list-shift-pegawai-all-unit_hapusshift").append("<h4>List Opsi Libur</h4>");
                            $("#list-shift-pegawai-all-unit_hapusshift").append("<button class='btn btn-md btn-second' onclick='change_clearshift_cursor_event(`#`+this.id);'>Libur</button>");
                        } else {

                            // set editable to false;
                            set_jadwalshift_editable = false;
                        }
                    }
                } else {

                    swal("Data Shift Pegawai tidak ditemukan", "", "error");
                    console.log("Kode : SEARCHPERIODE2. Kode Status = 0");
                }
            },
            error: function(errorMsg) {
                console.log("Kode : SEARCHPERIODE2. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
            }
        });
    }

    function get_log_pengaturan_shift_all_unit() {

        $('#list-shift-pegawai-by-managerial-box-table-log-validasi').html("");
        $('#list-shift-pegawai-by-managerial-box-table-log-validasi').append('<table id="list-shift-pegawai-by-managerial-table-log-validasi" name="list-shift-pegawai-by-managerial-table-log-validasi" class="table table-bordered table-striped"><thead><tr><th>No.</th><th>Tanggal</th><th>Keterangan</th><th>Catatan</th></tr></thead><tbody></tbody></table>');

        var month = remove_leading_zero($("#list-shift-pegawai-by-managerial-select-month option:selected").val());
        var year = $("#list-shift-pegawai-by-managerial-select-year option:selected").val();
        var id_unit = $("#list-shift-pegawai-by-managerial-select-unit option:selected").val();

        $.ajax({
            type: "GET",
            url: "<?php echo $url_api_master_data; ?>?action=get_log_pengaturan_shift&id_unit=" + id_unit + "&month=" + month + "&year=" + year,
            dataType: "JSON",
            success: function(data) {

                if (data.status == 1) {

                    for (let i = 0; i < data.data.length; i++) {

                        var index = i + 1;
                        var tanggal = data.data[i]['created'];
                        var keterangan = "keterangan" in data.data[i]['log'] ? data.data[i]['log']['keterangan'] : "-";
                        var catatan = "catatan" in data.data[i]['log'] ? data.data[i]['log']['catatan'] : "-";

                        $('#list-shift-pegawai-by-managerial-table-log-validasi tbody').append('<tr><td>' + index + '</td><td>' + tanggal + '</td><td>' + keterangan + '</td><td>' + catatan + '</td></tr>');
                    }

                    if (!$.fn.dataTable.isDataTable('#list-shift-pegawai-by-managerial-table-log-validasi')) {

                        $('#list-shift-pegawai-by-managerial-table-log-validasi').DataTable({
                            "responsive": true,
                            "autoWidth": false,
                            "paging": true,
                            "pageLength": 3,
                            "scrollX": true,
                            "searching": false
                        });
                    } else {

                        $('#list-shift-pegawai-by-managerial-table-log-validasi').DataTable({
                            "destroy": true,
                            "responsive": true,
                            "autoWidth": false,
                            "paging": true,
                            "pageLength": 3,
                            "scrollX": true,
                            "searching": false
                        });
                    }
                } else {

                    console.log("Kode : GETLOGSHIFT2. Kode Status = 0.");
                    console.log(data);
                }
            },
            error: function(errorMsg) {
                console.log("Kode : GETLOGSHIFT2. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
            }
        });
    }

    function get_list_hari_raya_all_unit() {

        var month = remove_leading_zero($("#list-shift-pegawai-by-managerial-select-month option:selected").val());
        var year = $("#list-shift-pegawai-by-managerial-select-year option:selected").val();

        $.ajax({
            type: "GET",
            url: "<?php echo $url_api_master_data; ?>?action=get_list_hari_raya&month=" + month + "&year=" + year,
            dataType: "JSON",
            success: function(response) {

                if (response.status) {
                    $("#list-shift-pegawai-all-unit_list-hariraya").append("<h4>Keterangan Hari Raya</h4><table id='table-list-hari-raya'></table>");

                    for (let i = 0; i < response.data.length; i++) {

                        $("#table-list-hari-raya").append("<tr><td>~&nbsp;&nbsp;</td><td>" + response.data[i]['tanggal'] + "</td><td>&nbsp;&nbsp;</td><td>" + response.data[i]['keterangan'] + "</td></tr>");
                    }
                } else {

                    console.log("Kode : GETHRRAYA1. Kode Status = 0");
                }
            },
            error: function(errorMsg) {
                console.log("Kode : GETHRRAYA1. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
            }
        });
    }
    // end fungsi untuk list-shift-pegawai-all-unit

    // fungsi untuk list-master-absensi'
    function init_colorpicker_master_absensi() {

        $('#add-shift-hxclor').colorpicker()

        $('#edt-shift-hxclor').colorpicker()
    }
    // end fungsi untuk list-master-absensi



    // fungsi untuk set-jadwal-nonshift
    function event_set_jadwal_nonshift(selected) {
        $('#set-jadwal-nonshift_generate').hide();
    }

    function get_list_hari_raya_nonshift() {

        var month = remove_leading_zero($('#set-jadwal-noshift_select_month option:selected').val());
        var year = $('#set-jadwal-noshift_select_year option:selected').val();

        $.ajax({
            type: "GET",
            url: "<?php echo $url_api_master_data; ?>?action=get_list_hari_raya&month=" + month + "&year=" + year,
            dataType: "JSON",
            success: function(response) {

                if (response.status) {
                    $("#set-jadwal_nonshift_list-hariraya").append("<h4>Keterangan Hari Raya & Libur</h4><table id='table-list-hari-raya'></table>");

                    for (let i = 0; i < response.data.length; i++) {

                        $("#table-list-hari-raya").append("<tr><td>~&nbsp;&nbsp;</td><td>" + response.data[i]['tanggal'] + "</td><td>&nbsp;&nbsp;</td><td>" + response.data[i]['keterangan'] + "</td></tr>");
                    }
                } else {

                    console.log("Kode : GETHRRAYA1. Kode Status = 0");
                }
            },
            error: function(errorMsg) {
                console.log("Kode : GETHRRAYA1. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
            }
        });
    }

    function get_log_pengaturan_nonshift() {

        $('#box-set-jadwal-nonshift_log').html("");
        $('#box-set-jadwal-nonshift_log').append('<table id="table-set-jadwal-nonshift_log" class="table table-bordered table-striped"><thead><tr><th>No.</th><th>Tanggal</th><th>Keterangan</th><th>Catatan</th></tr></thead><tbody></tbody></table>');

        var month = remove_leading_zero($("#set-jadwal-noshift_select_month option:selected").val());
        var year = $("#set-jadwal-noshift_select_year option:selected").val();

        $.ajax({
            type: "GET",
            url: "<?php echo $url_api_master_data; ?>?action=get_log_pengaturan_nonshift&month=" + month + "&year=" + year,
            dataType: "JSON",
            success: function(data) {

                if (data.status == 1) {

                    for (let i = 0; i < data.data.length; i++) {

                        var index = i + 1;
                        var tanggal = data.data[i]['created'];
                        var keterangan = "keterangan" in data.data[i]['log'] ? data.data[i]['log']['keterangan'] : "-";
                        var catatan = "catatan" in data.data[i]['log'] ? data.data[i]['log']['catatan'] : "-";

                        $('#table-set-jadwal-nonshift_log tbody').append('<tr><td>' + index + '</td><td>' + tanggal + '</td><td>' + keterangan + '</td><td>' + catatan + '</td></tr>');
                    }

                    if (!$.fn.dataTable.isDataTable('#table-log-validasi')) {

                        $('#table-set-jadwal-nonshift_log').DataTable({
                            "responsive": true,
                            "autoWidth": false,
                            "paging": true,
                            "pageLength": 2,
                            "scrollX": true,
                            "searching": false
                        });
                    } else {

                        $('#table-set-jadwal-nonshift_log').DataTable({
                            "destroy": true,
                            "responsive": true,
                            "autoWidth": false,
                            "paging": true,
                            "pageLength": 2,
                            "scrollX": true,
                            "searching": false
                        });
                    }
                } else {

                    console.log("Kode : GETLOGNONSHIFT1. Kode Status = 0.");
                    console.log(data);
                }
            },
            error: function(errorMsg) {
                console.log("Kode : GETLOGNONSHIFT1. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
            }
        });
    }

    function search_jadwal_nonshift() {

        $('#set-jadwal-nonshift_generate').hide();
        $('#btn-save-set-jadwal_nonshift').hide();

        $('#set-jadwal-nonshift_log tbody').empty();
        $('#table-set-jadwal-nonshift_log tbody').empty();

        $('#box-set-jadwal_nonshift').html("");
        $('#box-set-jadwal_nonshift').append("<table id='set-jadwal_nonshift_table' name='set-jadwal_nonshift_table' class='table table-bordered table-striped'><thead></thead><tbody></tbody></table>");

        $("#set-jadwal_nonshift_list-shift").html("");
        $("#set-jadwal_nonshift_hapus-shift").html("");
        $("#set-jadwal_nonshift_list-hariraya").html("");
        $("#set-jadwal_nonshift_list-ketidakhadiranshift").html("");

        $("#set-jadwal-nonshift_search").html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;Mohon Tunggu").prop('disabled', true);

        var month = remove_leading_zero($('#set-jadwal-noshift_select_month option:selected').val());
        var year = $('#set-jadwal-noshift_select_year option:selected').val();
        var unit = $('#set-jadwal-nonshift_select_unit option:selected').val();

        $.ajax({
            type: "GET",
            url: "<?php echo $url_api_master_data; ?>?action=get_data_pengaturan_nonshift&month=" + month + "&year=" + year + "&id_unit=" + unit,
            dataType: "JSON",
            success: function(response) {

                if (response.status == 1) {


                    // initialize thead
                    var thead_value = '<tr><td><b>No.</b></td><td><b>Nama</b></td>';
                    for (let h = 0; h < response.data.date_in_month.length; h++) {

                        var style_font = get_day_by_date(response.data.date_in_month[h]) == 'MG' ? 'color:red' : 'color:black';

                        thead_value += '<td><b><p style="' + style_font + '">' + (h + 1) + '' + get_day_by_date(response.data.date_in_month[h]) + '</p></b></td>';
                    }
                    thead_value += '</tr>';
                    $('#set-jadwal_nonshift_table thead').append(thead_value);

                    // initialize tbody
                    // dilooping berdasarkan jumlah user dalam unit
                    for (let i = 0; i < response.data.data.length; i++) {

                        var tbody_value = "<tr><td>" + (i + 1) + "</td><td>" + response.data.data[i]['nama_pegawai'] + "</td>";

                        // apabila pada object 'shift' tidak kosong
                        // berarti sudah di-generate
                        // apabila belum. diset kosong. supaya bisa digenerate oleh kepegawaian
                        if (response.data.data[i].shift.length != 0) {

                            for (let j = 0; j < response.data.data[i].shift.length; j++) {

                                var date = response.data.data[i].shift[j]['date'];
                                var date_next = response.data.data[i].shift[(j + 1) < response.data.data[i].shift.length ? (j + 1) : j]['date'];
                                var date_prev = response.data.data[i].shift[j != 0 ? (j - 1) : j]['date'];
                                var shift_aktif = response.data.data[i].shift[j]['shift_aktif'];
                                var id_absensi = response.data.data[i].shift[j]['id_absensi'];
                                var id_jadwalkerja_shift = response.data.data[i].shift[j]['id_jadwalkerja_shift'];
                                var id_penanggung_jawab = response.data.data[i].shift[j]['id_penanggung_jawab'];
                                var hex_color_absensi = response.data.data[i].shift[j]['hex_color_absensi'];
                                var nama_absensi = response.data.data[i].shift[j]['nama_absensi'];
                                var jammasuk_absensi = response.data.data[i].shift[j]['jam_masuk'];
                                var jampulang_absensi = response.data.data[i].shift[j]['jam_pulang'];
                                var desc_absensi = response.data.data[i].shift[j]['desc_absensi'];
                                var desc_shift_tipe = response.data.data[i].shift[j]['desc_shift_tipe'];
                                var nama_shift_tipe = response.data.data[i].shift[j]['nama_shift_tipe'];
                                var tooltip_absensi = desc_absensi + " (" + jammasuk_absensi + "-" + jampulang_absensi + ")";

                                tbody_value += "<td>";
                                if (id_absensi != "") { // apabila id_absensi tidak kosong, dianggap ada shiftnya

                                    if (date == date_prev && j != 0) { // apabila tanggal saat ini sama dengan tanggal sebelumnya, dianggap longshift

                                        tbody_value = tbody_value.slice(0, -9);
                                        tbody_value += "<button id='btn_set_jadwal_nonshift_" + id_jadwalkerja_shift + "' name='btn_set_jadwal_nonshift_" + id_jadwalkerja_shift + "' type='button' class='btn btn-xs btn-block' onclick='put_jadwalshift(`#`+this.id, `<?php echo $id_user; ?>`, `" + id_jadwalkerja_shift + "`);' title='" + tooltip_absensi + "' style='background-color: #" + hex_color_absensi + "'>" + nama_absensi + "</button>" + (shift_aktif ? "<button id='btn_set_jadwal_nonshift_shifttipe_" + id_jadwalkerja_shift + "' name='btn_set_jadwal_nonshift_shifttipe_" + id_jadwalkerja_shift + "' class='btn btn-xs btn-block btn-primary' onclick='put_jadwalshift_shifttipe(`#`+this.id, `<?php echo $id_user; ?>`, `" + id_jadwalkerja_shift + "`);' title='" + desc_shift_tipe + "' title='" + desc_shift_tipe + "'>" + nama_shift_tipe + "</button>" : "");
                                    } else {

                                        tbody_value += "<button id='btn_set_jadwal_nonshift_" + id_jadwalkerja_shift + "' name='btn_set_jadwal_nonshift_" + id_jadwalkerja_shift + "' type='button' class='btn btn-xs btn-block' onclick='put_jadwalshift(`#`+this.id, `<?php echo $id_user; ?>`, `" + id_jadwalkerja_shift + "`);' title='" + tooltip_absensi + "' style='background-color: #" + hex_color_absensi + "'>" + nama_absensi + "</button>" + (shift_aktif ? "<button id='btn_set_jadwal_nonshift_shifttipe_" + id_jadwalkerja_shift + "' name='btn_set_jadwal_nonshift_shifttipe_" + id_jadwalkerja_shift + "' class='btn btn-xs btn-block btn-primary' onclick='put_jadwalshift_shifttipe(`#`+this.id, `<?php echo $id_user; ?>`, `" + id_jadwalkerja_shift + "`);' title='" + desc_shift_tipe + "' title='" + desc_shift_tipe + "'>" + nama_shift_tipe + "</button>" : "");
                                    }

                                    // kalau (tanggal saat ini beda dengan tanggal besok atau diposisi tanggal terakhir) dan tipe shift aktif
                                    // berarti ditambahkan tombol add longshift
                                    if ((date != date_next || (j + 1) == response.data.data[i].shift.length) && shift_aktif) {

                                        tbody_value += '<button class="btn btn-xs btn-block btn-light" onclick="put_jadwalshift_longshift(`' + id_jadwalkerja_shift + '`, `<?php echo $id_user; ?>`);"><i class="fa fa-plus" style="color:grey"></i></button>';
                                    }
                                } else { // id_absensi kosong, dianggap libur atau shift tidak aktif

                                    tbody_value += "<button id='btn_set_jadwal_nonshift_" + id_jadwalkerja_shift + "' name=id='btn_set_jadwal_nonshift_" + id_jadwalkerja_shift + "' type='button' class='btn btn-xs btn-block' onclick='put_jadwalshift(`#`+this.id, `<?php echo $id_user; ?>`, `" + id_jadwalkerja_shift + "`);'>Libur</button>";
                                }

                                tbody_value += "</td>";
                            }
                        } else {

                            for (let j = 0; j < response.data.days_in_month; j++) {

                                tbody_value += "<td></td>";
                            }
                        }


                        tbody_value += "</tr>";

                        $('#set-jadwal_nonshift_table tbody').append(tbody_value);
                    }

                    // initialize datatable jadwal non shift
                    if (!$.fn.dataTable.isDataTable('#set-jadwal_nonshift_table')) {

                        $('#set-jadwal_nonshift_table').DataTable({
                            "responsive": true,
                            "autoWidth": false,
                            "scrollX": true,
                            "pageLength": 50
                        });
                    }

                    // check editable jadwal nonshift
                    if (response.data.bool_shift_editable || true) {

                        // set editable to true
                        set_jadwalshift_editable = true;

                        // add shift options
                        $("#set-jadwal_nonshift_list-shift").append("<h4>List Opsi Shift</h4>");
                        for (var i = 0; i < response.data.shift_options.length; i++) {

                            var id_absensi = response.data.shift_options[i]['id_absensi'];
                            var nama_shift = response.data.shift_options[i]['nama_shift'];
                            var color_shift = response.data.shift_options[i]['hex_color_shift'];
                            var desc_shift = response.data.shift_options[i]['desc_shift'];
                            var jam_masuk = response.data.shift_options[i]['jam_masuk'];
                            var jam_pulang = response.data.shift_options[i]['jam_pulang'];
                            var tooltip = desc_shift + ' (' + jam_masuk + '-' + jam_pulang + ') ';

                            $("#set-jadwal_nonshift_list-shift").append("<button id='set-jadwalshift_" + id_absensi + "_options' style='background-color: #" + color_shift + "; margin-bottom: 10px; margin-right: 3px' class='btn btn-md active' onclick='change_shift_cursor_event(`#`+this.id, `" + id_absensi + "`, `" + nama_shift + "`, `" + color_shift + "`, `" + desc_shift + "`, `" + jam_masuk + "`, `" + jam_pulang + "`, null, null, null, null);' title='" + tooltip + "'>" + nama_shift + "</button>");
                        }

                        // add ketidakhadiran options
                        $("#set-jadwal_nonshift_list-ketidakhadiranshift").append("<h4>List Opsi Ketidakhadiran</h4>");
                        for (var i = 0; i < response.data.ketidakhadiranshift_options.length; i++) {

                            var id_ketidakhadiran = response.data.ketidakhadiranshift_options[i]['id_ketidakhadiran'];
                            var nama_ketidakhadiran = response.data.ketidakhadiranshift_options[i]['nama_ketidakhadiran'];
                            var hex_color_ketidakhadiran = response.data.ketidakhadiranshift_options[i]['hex_color_ketidakhadiran'];
                            var desc_ketidakhadiran = response.data.ketidakhadiranshift_options[i]['desc_ketidakhadiran'];

                            $("#set-jadwal_nonshift_list-ketidakhadiranshift").append("<button id='set-jadwalshift_" + id_ketidakhadiran + "_options' style='background-color: #" + hex_color_ketidakhadiran + "; margin-bottom: 10px; margin-right: 3px' class='btn btn-md active' onclick='change_shift_cursor_event(`#`+this.id, null, null, null, null, null, null, `" + id_ketidakhadiran + "`, `" + nama_ketidakhadiran + "`, `" + hex_color_ketidakhadiran + "`, `" + desc_ketidakhadiran + "`);' title='" + desc_ketidakhadiran + "'>" + nama_ketidakhadiran + "</button>&nbsp;");
                        }

                        // add hapus shift options
                        $("#set-jadwal_nonshift_hapus-shift").append("<h4>List Opsi Libur</h4>");
                        $("#set-jadwal_nonshift_hapus-shift").append("<button class='btn btn-md btn-second' onclick='change_clearshift_cursor_event(`#`+this.id);'>Libur</button>");
                    } else {

                        // set editable to false
                        set_jadwalshift_editable = false;
                    }

                    // check submitable jadwal non shift is allowed
                    if (response.data.bool_shift_submitable) {

                        // show submit shift
                        $("#btn-save-set-jadwal_nonshift").show();
                    } else {

                        // hide submit shift
                        $("#btn-save-set-jadwal_nonshift").hide();
                    }

                    // check generate jadwal non shift is still allowed
                    if (response.data.bool_generateable) {
                        $('#set-jadwal-nonshift_generate').show();
                    }

                    // get log data
                    get_log_pengaturan_nonshift();

                    // get list hari raya
                    get_list_hari_raya_nonshift();
                } else {

                    console.log('Data Jadwal Pegawan Non Shift tidak ditemukan');
                    $('#set-jadwal-nonshift_generate').show();
                }

                $("#set-jadwal-nonshift_search").html("<i class='fa fa-search'>&nbsp;&nbsp;Cari Jadwal Non Shift</i>").prop('disabled', false);
            },
            error: function(error) {

                $("#set-jadwal-nonshift_search").html("<i class='fa fa-search'>&nbsp;&nbsp;Cari Jadwal Non Shift</i>").prop('disabled', false);
                console.log('Kode : SEARCHJDWNS01. Gagal mengirim permintaan. ' + error.status + '-' + error.statusText);
                swal('Gagal mengirim permintaan Jadwal Pegawai Non Shift', '', 'error');
            }
        });
    }


    function save_jadwalnonshift_by_kepegawian(id_kepegawaian) {

        $('#modal-warning-submit-jadwalnonshift-by-kepegawaian').modal('hide');
        $('#btn-save-set-jadwal_nonshift').html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;Mohon Tunggu").css("pointer-events", "none");

        var month = remove_leading_zero($('#set-jadwal-noshift_select_month option:selected').val());
        var year = $('#set-jadwal-noshift_select_year option:selected').val();

        $.ajax({
            type: "POST",
            url: "<?php echo $url_api_master_data; ?>?action=act_save_jadwal_nonshift",
            dataType: "JSON",
            data: {
                id_kepegawaian: id_kepegawaian,
                month: month,
                year: year
            },
            success: function(data) {

                if (data.status == 1) {

                    $('#btn-save-set-jadwal_nonshift').hide();
                    $('#set-jadwal_nonshift_list-shift').empty();
                    $('#set-jadwal_nonshift_list-ketidakhadiranshift').empty();
                    $('#set-jadwal_nonshift_hapus-shift').empty();
                    $('#btn-save-set-jadwal_nonshift').html("Submit").css("pointer-events", "auto");
                    set_jadwalshift_editable = false;
                    swal("Simpan Jadwal Pegawai Non Shift Berhasil", data.message, "success");
                } else {

                    $('#btn-save-set-jadwal_nonshift').html("Submit").css("pointer-events", "auto");

                    swal("Simpan Jadwal Pegawai Non Shift Ditolak", data.message, "error");

                    console.log("Kode : SBSHIFT1. Kode Status = 0.");
                    console.log(data);
                }
            },
            error: function(errorMsg) {
                $('#btn-save-set-jadwal_nonshift').html("Submit").css("pointer-events", "auto");

                swal("Simpan Jadwal Pegawai Non Shift Gagal", "Silahkan hubungi helpdesk", "error");

                console.log("Kode : SBSHIFT1. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
            }
        });
    }

    function generate_jadwal_nonshift(id_kepegawaian) {

        $('#set-jadwal-nonshift_generate').html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;Mohon Tunggu").prop('disabled', true);
        $('#set-jadwal-nonshift_search').prop('disabled', true);

        var month = remove_leading_zero($('#set-jadwal-noshift_select_month option:selected').val());
        var year = $('#set-jadwal-noshift_select_year option:selected').val();

        $.ajax({
            type: "POST",
            url: "<?php echo $url_api_master_data; ?>?action=act_generate_nonshift",
            dataType: "JSON",
            data: {
                month: month,
                year: year,
                id_penanggung_jawab: id_kepegawaian
            },
            success: function(data) {

                $('#set-jadwal-nonshift_search').prop('disabled', false);

                if (data.status == 1) {

                    //after successfully generating shift record. aplication must 'searching' again to avoid mal-data.
                    $('#set-jadwal-nonshift_generate').hide();
                    search_jadwal_nonshift();
                } else {

                    console.log("Kode : GENERATEJDWLNST1. Kode Status = 0.");
                    console.log(data);
                }

                $('#set-jadwal-nonshift_generate').html("<i class='fa fa-plus'>&nbsp;&nbsp;Buat/Tambah Jadwal Non Shift</i>").prop('disabled', false);
            },
            error: function(errorMsg) {

                $('#set-jadwal-nonshift_generate').html("<i class='fa fa-plus'>&nbsp;&nbsp;Buat/Tambah Jadwal Non Shift</i>").prop('disabled', false);
                $('#set-jadwal-nonshift_search').prop('disabled', false);
                swal('Gagal Mengirim Permintaan Generate Jadwal Non Shift', '', 'error');
                console.log("Kode : GENERATEJDWLNST1. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
            }
        });
    }
    // end fungsi untuk set-jadwal-nonshift


    $(document).ready(function() {
        $('[data-toggle="tooltip-set-jadwalshift"]').tooltip();
        get_list_permintaan_validasi_jadwalshift();
        get_detail_permintaan_validasi_jadwalshift();
        init_colorpicker_master_absensi();
    });
</script>

<!-- trigger js function from php -->
<?php

// set-jadwalshift section
if (isset($pengaturanshift_year_selected) && isset($pengaturanshift_bulan_selected) && isset($pengaturanshift_unit_selected)) {
    // trigger js function
    echo '<script>search_selected_periode();</script>';
}
?>