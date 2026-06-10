<?php
$aksi = "module/absensi/aksi-absensi-pegawai?";
$id = isset($url['id']) ? $url['id'] : null;
$dpa = isset($url['dpa']) ? $url['dpa'] : null;
$id_user_temp = isset($url['id_user']) ? $url['id_user'] : null;
$id_unit_temp = isset($url['id_unit']) ? $url['id_unit'] : null;
$bulan_temp = isset($url['bulan']) ? $url['bulan'] : null;
$tahun_temp = isset($url['tahun']) ? $url['tahun'] : null;
$temp_log_finger = isset($url['log_finger']) ? $url['log_finger'] : null;
switch ((isset($url['act']) ? $url['act'] : '')) {
    default:
        echo "default";
        header("location:error404");
        break;
    case "absensi-pegawai":
    ?>
        <div class="box">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title fa fa-list"> CHECK IN OUT ABSENSI PEGAWAI</h3>
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
                                        <select class="form-control select2" name="bulan" data-placeholder="-Pilih Bulan-" required>
                                            <?php loadBln('-Pilih Bulan-'); ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control select2" name="tahun" data-placeholder="-Pilih Tahun-" required>
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
                    <br><br><br><br>
                    <hr>
                    <?php
                    $bulan_post = isset($_POST['bulan']) ? $_POST['bulan'] : null;
                    $tahun_post = isset($_POST['tahun']) ? $_POST['tahun'] : null;

                    if ($bulan_post != null && $tahun_post != null) {

                        $bulan_absensi = FormatTgl('Y-m-d', $tahun_post . "-" . $bulan_post . "-01");;
                    ?>
                        <div class="box-header with-border">
                            <h3 class="box-title fa fa-list"> <label align="center">Laporan Absensi <?php echo konversiBulanTahun($bulan_absensi) . " "; ?></label></h3>
                            <div class="box-tools pull-right"></div>
                        </div>
                        <div class="box-body table-responsive">
                            <table id="laporan" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>NIP</th>
                                        <th>Finger Id</th>
                                        <th>Nama Pegawai</th>
                                        <th>Absensi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    $sql = bukaquery2("
                                            SELECT
                                                a.nip, a.nama_pegawai, a.log_finger, b.tanggal, b.status
                                            FROM tm_pegawai a
                                                LEFT JOIN log b ON a.log_finger = b.user
                                            WHERE a.id_user = '" . $id_user . "'
                                                AND YEAR(b.tanggal) = " . $tahun_post . "
                                                AND MONTH(b.tanggal) = " . $bulan_post . "
                                            ORDER BY b.tanggal ASC
                                        ");

                                    $no = 1;
                                    while ($row = fetch_array($sql)) {

                                        $status = $row['status'] == 'I' ? "Absensi Masuk" : "Absensi Pulang";
                                        echo "<tr><td>" . $no . "</td><td>" . $row['nip'] . "</td><td>" . $row['log_finger'] . "</td><td>" . $row['nama_pegawai'] . "</td><td>" . (hariindo($row['tanggal']) . " - " . $row['tanggal']) . "</td><td>" . $status . "</td></tr>";
                                        $no++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        <?php
        break;
    case 'absensi-pegawai-live-jadwal':
        ?>
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title fa fa-list"> LOG ABSENSI UNIT 1</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                            <i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <table align="Left">
                            <tr>
                                <td>
                                    <label>Tanggal</label>
                                </td>
                                <td>
                                    <label>Bulan</label>
                                </td>
                                <td>
                                    <label>Tahun</label>
                                </td>
                                <td></td>
                                <td>
                                    <label>Tanggal</label>
                                </td>
                                <td>
                                    <label>Bulan</label>
                                </td>
                                <td>
                                    <label>Tahun</label>
                                </td>
                                <td></td>
                                <td>
                                    <label>Unit</label>
                                </td>
                                <td>
                                    <label>Pegawai</label>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <select class="form-control select2" id="tanggal_absensi_start" name="tanggal_absensi_start" data-placeholder="-Pilih Tanggal-" required>
                                        <?php loadTgl('-Pilih Tanggal-'); ?>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control select2" id="bulan_absensi_start" name="bulan_absensi_start" data-placeholder="-Pilih Bulan-" required>
                                        <?php loadBln('-Pilih Bulan-'); ?>
                                    </select>
                                <td>
                                    <select class="form-control select2" id="tahun_absensi_start" name="tahun_absensi_start" data-placeholder="-Pilih Tahun-" required>
                                        <?php loadThn('-Pilih Tahun-'); ?>
                                    </select>
                                </td>
                                <td>
                                    <label>&nbsp;s/d&nbsp;</label>
                                </td>
                                <td>
                                    <select class="form-control select2" id="tanggal_absensi_end" name="tanggal_absensi_end" data-placeholder="-Pilih Tanggal-" required>
                                        <?php loadTgl('-Pilih Tanggal-'); ?>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control select2" id="bulan_absensi_end" name="bulan_absensi_end" data-placeholder="-Pilih Bulan-" required>
                                        <?php loadBln('-Pilih Bulan-'); ?>
                                    </select>
                                <td>
                                    <select class="form-control select2" id="tahun_absensi_end" name="tahun_absensi_end" data-placeholder="-Pilih Tahun-" onchange="get_list_pegawai_by_idunit();" required>
                                        <?php loadThn('-Pilih Tahun-'); ?>
                                    </select>
                                </td>
                                <td>&nbsp;&nbsp;</td>
                                <td>
                                    <select class="form-control select2" id="unit_absensi" name="unit_absensi" data-placeholder="-Pilih Unit-" onchange="get_list_pegawai_by_idunit();" required>
                                        <?php loadUnitByLevel(
                                            $idlevel,
                                            $id_unit,
                                            $kasatpel_pegawai,
                                            $kasie
                                        ); ?>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control select2" id="pegawai_absensi" name="pegawai_absensi" data-placeholder="" required>
                                        <option value="-Pilih Pegawai-" disabled> - Pilih Pegawai - </option>
                                    </select>
                                </td>
                                <td>
                                    <button type="button" id="search_absensi_live_jadwal" name="search_absensi_live_jadwal" name="submit" class="btn btn-info" onclick="get_abesnsi_live_jadwal(`#`+this.id);"><i class="fa fa-search"></i></button>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <br><br>
                    <hr>
                    <div class="box-body table-responsive">
                        <table id="absensi_pegawai_live_jadwal" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tanggal</th>
                                    <th>Nama Pegawai</th>
                                    <th>IN</th>
                                    <th>OUT</th>
                                    <th>Telat</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <thead>
                                <tr>
                                    <th colspan="4">TOTAL</th>
                                    <th colspan="2" style="background-color: #e48080;" id="absensi_total_telat_pulangcepat"></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        <?php
        break;
    case 'absensi-pegawai-live':
        ?>
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title fa fa-list"> LOG ABSENSI UNIT 2</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                            <i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <table align="Left">
                            <tr>
                                <td>
                                    <label>Tanggal</label>
                                </td>
                                <td>
                                    <label>Bulan</label>
                                </td>
                                <td>
                                    <label>Tahun</label>
                                </td>
                                <td></td>
                                <td>
                                    <label>Tanggal</label>
                                </td>
                                <td>
                                    <label>Bulan</label>
                                </td>
                                <td>
                                    <label>Tahun</label>
                                </td>
                                <td></td>
                                <td>
                                    <label>Unit</label>
                                </td>
                                <td>
                                    <label>Pegawai</label>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <select class="form-control select2" id="tanggal_absensi_start" name="tanggal_absensi_start" data-placeholder="-Pilih Bulan-" required>
                                        <?php loadTgl('-Pilih Tanggal-'); ?>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control select2" id="bulan_absensi_start" name="bulan_absensi_start" data-placeholder="-Pilih Bulan-" required>
                                        <?php loadBln('-Pilih Bulan-'); ?>
                                    </select>
                                <td>
                                    <select class="form-control select2" id="tahun_absensi_start" name="tahun_absensi_start" data-placeholder="-Pilih Tahun-" required>
                                        <?php loadThn('-Pilih Tahun-'); ?>
                                    </select>
                                </td>
                                <td>
                                    <label>&nbsp;s/d&nbsp;</label>
                                </td>
                                <td>
                                    <select class="form-control select2" id="tanggal_absensi_end" name="tanggal_absensi_end" data-placeholder="-Pilih Tanggal-" required>
                                        <?php loadTgl('-Pilih Tanggal-'); ?>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control select2" id="bulan_absensi_end" name="bulan_absensi_end" data-placeholder="-Pilih Bulan-" required>
                                        <?php loadBln('-Pilih Bulan-'); ?>
                                    </select>
                                <td>
                                    <select class="form-control select2" id="tahun_absensi_end" name="tahun_absensi_end" data-placeholder="-Pilih Tahun-" onchange="get_list_pegawai_by_idunit();" required>
                                        <?php loadThn('-Pilih Tahun-'); ?>
                                    </select>
                                </td>
                                <td>&nbsp;&nbsp;</td>
                                <td>
                                    <select class="form-control select2" id="unit_absensi" name="unit_absensi" data-placeholder="-Pilih Unit-" onchange="get_list_pegawai_by_idunit();" required>
                                        <?php loadUnitByLevel(
                                            $idlevel,
                                            $id_unit,
                                            $kasatpel_pegawai,
                                            $kasie
                                        ); ?>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control select2" id="pegawai_absensi" name="pegawai_absensi" data-placeholder="" required>
                                        <option value="-Pilih Pegawai-" disabled> - Pilih Pegawai - </option>
                                    </select>
                                </td>
                                <td>
                                    <button type="button" id="search_absensi" name="search_absensi" name="submit" class="btn btn-info" onclick="get_abesnsi_live(`#`+this.id);"><i class="fa fa-search"></i></button>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <br><br>
                    <hr>
                    <div class="box-body table-responsive">
                        <table id="absensi_pegawai_live" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Pegawai</th>
                                    <th>Tanggal</th>
                                    <th>Tipe</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php
        break;
    case 'rekap-hasil-absensi-pegawai-by-pj':
        ?>
            <div class="box">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title fa fa-list"> LAPORAN ABSENSI PEGAWAI 2 </h3>
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
                                            <label>Tanggal</label>
                                        </td>
                                        <td>
                                            <label>Bulan</label>
                                        </td>
                                        <td>
                                            <label>Tahun</label>
                                        </td>
                                        <td>
                                            <label>&nbsp;&nbsp;s/d&nbsp;&nbsp;</label>
                                        </td>
                                        <td>
                                            <label>Tanggal</label>
                                        </td>
                                        <td>
                                            <label>Bulan</label>
                                        </td>
                                        <td>
                                            <label>Tahun</label>
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>
                                            <label>Unit</label>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select class="form-control select2" name="tanggal_start" data-placeholder="-Pilih Tanggal-" required>
                                                <?= loadTgl(); ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="bulan_start" data-placeholder="-Pilih Bulan-" required>
                                                <?php loadBln('-Pilih Bulan-'); ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="tahun_start" data-placeholder="-Pilih Tahun-" required>
                                                <?php loadThn('-Pilih Tahun-'); ?>
                                            </select>
                                        </td>
                                        <td></td>
                                        <td>
                                            <select class="form-control select2" name="tanggal_end" data-placeholder="-Pilih Tanggal-" required>
                                                <?= loadTgl(); ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="bulan_end" data-placeholder="-Pilih Bulan-" required>
                                                <?php loadBln('-Pilih Bulan-'); ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="tahun_end" data-placeholder="-Pilih Tahun-" required>
                                                <?php loadThn('-Pilih Tahun-'); ?>
                                            </select>
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>
                                            <select class="form-control select2" name="id_unit" data-placeholder="-Pilih Unit-" required>
                                                <?php loadUnitByLevel(
                                                    $idlevel,
                                                    $id_unit,
                                                    $kasatpel_pegawai,
                                                    $kasie
                                                ); ?>
                                            </select>
                                        </td>
                                        <td>
                                            <button type="submit" name="search" name="submit" value="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </form>
                        <br><br><br><br>
                        <hr>
                        <?php
                        $tanggal_start = isset($_POST['tanggal_start']) ? $_POST['tanggal_start'] : null;
                        $bulan_start = isset($_POST['bulan_start']) ? $_POST['bulan_start'] : null;
                        $tahun_start = isset($_POST['tahun_start']) ? $_POST['tahun_start'] : null;
                        $tanggal_end = isset($_POST['tanggal_end']) ? $_POST['tanggal_end'] : null;
                        $bulan_end = isset($_POST['bulan_end']) ? $_POST['bulan_end'] : null;
                        $tahun_end = isset($_POST['tahun_end']) ? $_POST['tahun_end'] : null;
                        $idunit_post = isset($_POST['id_unit']) ? $_POST['id_unit'] : null;

                        if ($tanggal_start != null && $bulan_start != null && $tahun_start != null && $tanggal_end != null && $bulan_end != null && $tahun_end != null && $idunit_post != null) {

                        ?>
                            <div class="box-header with-border">
                                <h3 class="box-title fa fa-list"> <label align="center">Laporan Absensi <?= $tanggal_start . "-" . $bulan_start . "-" . $tahun_start . " s/d " . $tanggal_end . "-" . $bulan_end . "-" . $tahun_end; ?></label></h3>
                                <div class="box-tools pull-right"></div>
                            </div>
                            <div class="box-body table-responsive">
                                <table id="laporan" class="table table-bordered table-striped" style="width: 100%">
                                    <thead style="display: none;">
                                        <tr>
                                            <th style="display: none;">id</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result = array();
                                        $condition_idunit = $idunit_post == 'all' // jika id_unit dipilih all, berarti tidak ada filter id_unit kecualis unit UNT-000020
                                            ? " AND a.id_unit <> 'UNT-000020'"
                                            : " AND a.id_unit = '" . $idunit_post . "'";
                                        $sql = bukaquery2("
                                        SELECT
                                            a.id_user, b.nip,
                                            b.nama_pegawai, g.nama_level, h.nama_unit,
                                            a.date, IF(IFNULL(k.id_hari_raya, 0) <> 0, 1, 0) AS is_hari_raya, k.keterangan AS keterangan_hari_raya,
                                            a.shift_aktif,
                                            a.id_absensi, c.desc_shift,
                                            a.id_absensi_tipe, d.desc_shift_tipe,
                                            a.id_ketidakhadiran, e.desc_ketidakhadiran,
                                            a.jam_masuk_absensi_aktif, a.absensi_masuk, a.keterlambatan, i.keterangan AS keterangan_keterlambatan,
                                            a.jam_pulang_absensi_aktif, a.absensi_pulang, a.pulang_cepat, j.keterangan AS keterangan_pulangcepat
                                        FROM tm_jadwalpegawai_absensi_detail a
                                            INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                                            LEFT JOIN tm_shift c ON a.id_absensi = c.id_absensi
                                            LEFT JOIN tm_shift_tipe d ON a.id_absensi_tipe = d.id_absensi_tipe
                                            LEFT JOIN tm_shift_ketidakhadiran e ON a.id_ketidakhadiran = e.id_ketidakhadiran
                                            INNER JOIN tm_user f ON b.id_user = f.id_user
                                            INNER JOIN tm_level g ON f.id_level = g.id_level
                                            INNER JOIN tm_unit h ON a.id_unit = h.id_unit
                                            LEFT JOIN tm_absensi_ket_kepegawaian i ON a.keterangan_keterlambatan = i.id_keterangan
                                            LEFT JOIN tm_absensi_ket_kepegawaian j ON a.keterangan_pulangcepat = j.id_keterangan
                                            LEFT JOIN tm_hari_raya k ON a.date = k.tanggal
                                        WHERE a.date BETWEEN '" . $tahun_start . "-" . $bulan_start . "-" . $tanggal_start . "' AND '" . $tahun_end . "-" . $bulan_end . "-" . $tanggal_end . "'
                                        " . $condition_idunit . "
                                        ORDER BY a.id_user, a.date, a.id_absensi
                                    ");

                                        while ($row = fetch_assoc($sql)) {
                                            array_push($result, $row);
                                        }

                                        $id_user_cursor = "";
                                        $nm_pegawai_cursor = "";
                                        $nip_pegawai_cursor = "";
                                        $total_telat_hour = 0;
                                        $total_telat_menit = 0;
                                        $total_pulang_cepat_hour = 0;
                                        $total_pulang_cepat_minutes = 0;
                                        $total_durasi_kerja_hour = 0;
                                        $total_durasi_kerja_minutes = 0;
                                        $unit_cursor = "";
                                        $i_cursor = 1;


                                        for ($i = 0; $i < count($result); $i++) {

                                            $is_hari_raya = $result[$i]['is_hari_raya'] == 1;
                                            $keterangan_hari_raya = $result[$i]['keterangan_hari_raya'];
                                            $libur = $result[$i]['shift_aktif'] == 0 || ($result[$i]['id_absensi'] == '' && $result[$i]['id_ketidakhadiran'] == '');
                                            $libur_hari_raya = $is_hari_raya == 1 && $result[$i]['nama_level'] == 'PJ';
                                            $nama_pegawai = $result[$i]['nama_pegawai'];
                                            $tanggal = hariindo($result[$i]['date']) . ", " . konversiTgl($result[$i]['date']);
                                            $jam_masuk_absensi_aktif = ($result[$i]['shift_aktif'] == 1 || $result[$i]['shift_aktif'] == 2) ? date("H:i", strtotime($result[$i]['jam_masuk_absensi_aktif'])) : "";
                                            $jam_pulang_absensi_aktif = ($result[$i]['shift_aktif'] == 1 || $result[$i]['shift_aktif'] == 2) ? date("H:i", strtotime($result[$i]['jam_pulang_absensi_aktif'])) : "";
                                            $shift = !$libur_hari_raya
                                                ? (
                                                    !$libur && ($result[$i]['shift_aktif'] == 1 || $result[$i]['shift_aktif'] == 2)
                                                    ? $result[$i]['desc_shift'] . " (" . $result[$i]['desc_shift_tipe'] . ") <br>" . $jam_masuk_absensi_aktif . " - " . $jam_pulang_absensi_aktif
                                                    : (
                                                        $libur
                                                        ? 'Libur'
                                                        : $result[$i]['desc_ketidakhadiran']
                                                    )
                                                )
                                                : $keterangan_hari_raya;
                                            $absensi_masuk = !$is_hari_raya && !$libur
                                                ? $result[$i]['absensi_masuk']
                                                : '';
                                            $absensi_pulang = !$is_hari_raya  && !$libur
                                                ? $result[$i]['absensi_pulang']
                                                : '';
                                            $keterlambatan_hour = !$is_hari_raya  && !$libur
                                                ? (
                                                    $result[$i]['keterlambatan'] == 0 || $result[$i]['keterlambatan'] < 60 // jika telat 0 atau kurang < 1 jam, telat_hour kosong aja
                                                    ? ''
                                                    : floor(($result[$i]['keterlambatan'] / 60)) . " jam"
                                                )
                                                : '';
                                            $keterlambatan_minutes = !$is_hari_raya  && !$libur
                                                ? (
                                                    $result[$i]['keterlambatan'] == 0 // jika telat 0, telat_minutes kosong aja
                                                    ? ''
                                                    : ceil(($result[$i]['keterlambatan'] % 60)) . " menit"
                                                )
                                                : '';
                                            $pulang_cepat_hour = !$is_hari_raya && !$libur
                                                ? (
                                                    $result[$i]['pulang_cepat'] == 0 || $result[$i]['pulang_cepat'] < 60 // jika pulang cepat 0 atau kurang < jam, pulang_cepat_hour kosong aja
                                                    ? ''
                                                    : floor(($result[$i]['pulang_cepat'] / 60)) . " jam"
                                                )
                                                : '';
                                            $pulang_cepat_minutes = !$is_hari_raya && !$libur
                                                ? (
                                                    $result[$i]['pulang_cepat'] == 0 // jika pulang_cepat 0, pulang_cepat_minutes kosong aja
                                                    ? ''
                                                    : ceil(($result[$i]['pulang_cepat'] % 60)) . " menit"
                                                )
                                                : '';
                                            $keterangan_keterlambatan = $result[$i]['keterangan_keterlambatan'] != '' || $result[$i]['keterangan_keterlambatan'] != null
                                                ? $result[$i]['keterangan_keterlambatan']
                                                : '-';
                                            $keterangan_pulangcepat = $result[$i]['keterangan_pulangcepat'] != '' || $result[$i]['keterangan_pulangcepat'] != null
                                                ? $result[$i]['keterangan_pulangcepat']
                                                : '-';
                                            $durasikerja_hour = $absensi_masuk != '' && $absensi_masuk != '0000-00-00 00:00:00' && $absensi_pulang != '' && $absensi_pulang != '0000-00-00 00:00:00'
                                                ? floor(abs(strtotime($absensi_masuk) - strtotime($absensi_pulang)) / 3600) . " jam "
                                                : '';
                                            $durasikerja_minutes = $absensi_masuk != '' && $absensi_masuk != '0000-00-00 00:00:00' && $absensi_pulang != '' && $absensi_pulang != '0000-00-00 00:00:00'
                                                ? ceil(abs(strtotime($absensi_masuk) - strtotime($absensi_pulang)) / 60 % 60) . " menit "
                                                : '';

                                            /**
                                             * CATATAN : Disini penggunaan tr, td, colpan di-hidden buat akalin datatable. supaya copy excel nya bisa. silahkan baca disini https://stackoverflow.com/q/27290693/10227183
                                             */
                                            // apabila kursor id_user BEDA dengan id_user saat ini,
                                            // BUAT section biodata dan section summary telat dkk
                                            if ($id_user_cursor != $result[$i]['id_user']) {

                                                $id_user_cursor = $result[$i]['id_user'];
                                                $nm_pegawai_cursor = $result[$i]['nama_pegawai'];
                                                $nip_pegawai_cursor = $result[$i]['nip'];
                                                $unit_cursor = $result[$i]['nama_unit'];
                                                $i_cursor = 1;

                                                // section summary telat dkk
                                                if ($i != 0) echo "<tr><td style='display: none;'>" . $i . "</td><th></th><th></th><th></th><th></th><th></th><th>" . ($total_telat_hour + floor($total_telat_menit / 60)) . " jam</th><th>" . ceil($total_telat_menit % 60) . " menit</th><th>" . ($total_pulang_cepat_hour + floor($total_pulang_cepat_minutes / 60)) . " jam</th><th>" . ceil($total_pulang_cepat_minutes % 60) . " menit</th><th>" . ($total_durasi_kerja_hour + floor($total_durasi_kerja_minutes / 60)) . " jam</th><th>" . ceil($total_durasi_kerja_minutes % 60) . " menit</th><th></th></tr>";
                                                // section biodata
                                                if ($i != 0) echo "<tr><td style='display: none;'>" . $i . "</td><td colspan='12'>&nbsp;</td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></tr>";
                                                if ($i != 0) echo "<tr><td style='display: none;'>" . $i . "</td><td colspan='12'>&nbsp;</td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></tr>";
                                                if ($i != 0) echo "<tr><td style='display: none;'>" . $i . "</td><td colspan='12'>&nbsp;</td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></tr>";
                                                echo "<tr><td style='display: none;'>" . $i . "</td><td>&nbsp;</td><td>Nama</td><td colspan='10'> : " . $nm_pegawai_cursor . "</td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td></tr>";
                                                echo "<tr><td style='display: none;'>" . $i . "</td><td>&nbsp;</td><td>NIP</td><td colspan='10'> :" . $nip_pegawai_cursor . "</td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td></tr>";
                                                echo "<tr><td style='display: none;'>" . $i . "</td><td>&nbsp;</td><td>Unit</td><td colspan='10'> : " . $unit_cursor . "</td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td></tr>";
                                                echo "<tr><td style='display: none;'>" . $i . "</td><td colspan='12'>&nbsp;</td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td><td style='display: none;'></td></tr>";
                                                echo "<tr><td style='display: none;'>" . $i . "</td><th>No.</th><th>Tanggal</th><th>Shift</th><th>Absensi Masuk</th><th>Absensi Pulang</th><th>Telat</th><th></th><th>Pulang Cepat</th><th></th><th>Durasi Kerja</th><th></th><th>Total Telat & Pulang Cepat</th></tr>";

                                                $total_telat_hour = 0;
                                                $total_telat_menit = 0;
                                                $total_pulang_cepat_hour = 0;
                                                $total_pulang_cepat_minutes = 0;
                                                $total_durasi_kerja_hour = 0;
                                                $total_durasi_kerja_minutes = 0;
                                            }

                                            // loop data absensi pegawai
                                            if ($id_user_cursor == $result[$i]['id_user']) {

                                                $total_telat_pulangcepat_daily_hour = (int) str_replace('jam', '', $keterlambatan_hour) + (int) str_replace('jam', '', $pulang_cepat_hour);
                                                $total_telat_pulangcepat_daily_minutes = (int) str_replace('menit', '', $keterlambatan_minutes) + (int) str_replace('menit', '', $pulang_cepat_minutes);
                                                $total_telat_pulangcepat_daily = $total_telat_pulangcepat_daily_hour != 0 || $total_telat_pulangcepat_daily_minutes >= 60
                                                    ? ($total_telat_pulangcepat_daily_hour + floor($total_telat_pulangcepat_daily_minutes / 60)) . " jam"
                                                    : '';
                                                $total_telat_pulangcepat_daily .= $total_telat_pulangcepat_daily_minutes != 0
                                                    ? ($total_telat_pulangcepat_daily_minutes % 60) . " menit"
                                                    : '';

                                                echo "<tr><td style='display: none;'>" . $i . "</td><td>" . $i_cursor . "</td><td>" . $tanggal . "</td><td>" . $shift . "</td><td>" . $absensi_masuk . "</td><td>" . $absensi_pulang . "</td><td>" . $keterlambatan_hour . "</td><td>" . $keterlambatan_minutes . "</td><td>" . $pulang_cepat_hour . "</td><td>" . $pulang_cepat_minutes . "</td><td>" . $durasikerja_hour . "</td><td>" . $durasikerja_minutes . "</td><td>" . $total_telat_pulangcepat_daily . "</td></tr>";
                                                $i_cursor++;
                                                $total_telat_hour += (int) str_replace('jam', '', $keterlambatan_hour);
                                                $total_telat_menit += (int) str_replace('menit', '', $keterlambatan_minutes);
                                                $total_pulang_cepat_hour += (int) str_replace('jam', '', $pulang_cepat_hour);
                                                $total_pulang_cepat_minutes += (int) str_replace('menit', '', $pulang_cepat_minutes);
                                                $total_durasi_kerja_hour += (int) str_replace('jam', '', $durasikerja_hour);
                                                $total_durasi_kerja_minutes += (int) str_replace('jam', '', $durasikerja_minutes);

                                                // section summary telat dkk
                                                if ($i + 1 >= count($result)) echo "<tr><td style='display: none;'>" . $i . "</td><th></th><th></th><th></th><th></th><th></th><th>" . ($total_telat_hour + floor($total_telat_menit / 60)) . " jam</th><th>" . ceil($total_telat_menit % 60) . " menit</th><th>" . ($total_pulang_cepat_hour + floor($total_pulang_cepat_minutes / 60)) . " jam</th><th>" . ceil($total_pulang_cepat_minutes % 60) . " menit</th><th>" . ($total_durasi_kerja_hour + floor($total_durasi_kerja_minutes / 60)) . " jam</th><th>" . ceil($total_durasi_kerja_minutes % 60) . " menit</th><th></th></tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            <?php
            break;
        case "cek-absensi-pegawai":
            ?>
                <div class="box">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title fa fa-list"> CHECK IN OUT ABSENSI PEGAWAI</h3>
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
                                                <select class="form-control select2" name="bulan" data-placeholder="-Pilih Bulan-" required>
                                                    <?php loadBln('-Pilih Bulan-'); ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control select2" name="tahun" data-placeholder="-Pilih Tahun-" required>
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
                            <br><br><br><br>
                            <hr>
                            <?php
                            $date1 = date('00-m-Y');
                            $date2 = date('t-m-Y');
                            $bulan_post = isset($_POST['bulan']) ? $_POST['bulan'] : null;
                            $tahun_post = isset($_POST['tahun']) ? $_POST['tahun'] : null;
                            $tmtcek = FormatTgl('Y-m-d', $tahun_post . "-" . $bulan_post . "-01");
                            if ($bulan_post == '-Pilih Bulan-' or $tahun_post == 'Tidak Boleh' or $bulan_post == '' or $tahun_post == '') {
                                $bulan_absensi = date('Y-m-d');
                            } else {
                                $bulan_absensi = $tmtcek;
                            }
                            //api absensi
                            ?>
                            <div class="box-header with-border">
                                <h3 class="box-title fa fa-list"> <label align="center">Laporan Absensi <?php echo konversiBulanTahun($bulan_absensi) . " "; ?></label></h3>
                                <div class="box-tools pull-right">
                                </div>
                            </div>
                            <div class="box-body table-responsive">
                                <table id="laporan" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Nama Pegawai</th>
                                            <th>IN</th>
                                            <th>OUT</th>
                                            <th>Telat</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $biodata = fetch_array(bukaquery("select log_finger,nama_pegawai,id_unit from tm_pegawai where nip='$id'"));
                                        $from = FormatTgl('00-m-Y', $bulan_absensi);
                                        $to = FormatTgl('t-m-Y', $bulan_absensi);
                                        while (strtotime($from) < strtotime($to)) {
                                            $from = mktime(0, 0, 0, date("m", strtotime($from)), date("d", strtotime($from)) + 1, date("Y", strtotime($from)));
                                            $from = date("Y-m-d ", $from);
                                            $tanggalan = konversitanggal($from);
                                            $tanggalmin = date('Y-m-d', strtotime("-1 day", strtotime($from)));
                                            $tanggalplus = date('Y-m-d', strtotime("+1 day", strtotime($from)));
                                            $in = getOne("SELECT
                                            log.tanggal
                                            FROM
                                            log INNER JOIN tm_pegawai ON tm_pegawai.log_finger=log.`user`
                                            where tm_pegawai.log_finger='$biodata[log_finger]' AND log.tanggal LIKE '%$from%' and log.status='I' ORDER BY log.tanggal asc ");
                                            $out = getOne("SELECT
                                            log.tanggal
                                            FROM
                                            log INNER JOIN tm_pegawai ON tm_pegawai.log_finger=log.`user`
                                            where tm_pegawai.log_finger='$biodata[log_finger]' AND log.tanggal LIKE '%$from%' and log.status='O' ORDER BY log.tanggal desc");
                                            if (FormatTgl('H:i:s', $in) > '18:00:00' and FormatTgl('H:i:s', $in) < '23:59:00') {
                                                if ($in != '') {
                                                    $absen_in = FormatTgl('H:i:s', $in);
                                                    $status = getOne("select tm_shift.nama_shift from set_shift inner join tm_shift on set_shift.id_absensi=tm_shift.id_absensi where tm_shift.bi < '$absen_in' and tm_shift.ai > '$absen_in' and set_shift.id_unit='$biodata[id_unit]'");
                                                    $jam_masuk = getOne("select tm_shift.jam_masuk from set_shift inner join tm_shift on set_shift.id_absensi=tm_shift.id_absensi where tm_shift.bi < '$absen_in' and tm_shift.ai > '$absen_in' and set_shift.id_unit='$biodata[id_unit]'");
                                                } else {
                                                    $absen_in = '-';
                                                    $status = '-';
                                                    $jam_masuk = '';
                                                }
                                                $out1 = getOne("SELECT
                                            log.tanggal
                                            FROM
                                            log INNER JOIN tm_pegawai ON tm_pegawai.log_finger=log.`user`
                                            where tm_pegawai.log_finger='$biodata[log_finger]' AND log.tanggal LIKE '%$tanggalplus%' ORDER BY log.tanggal ASC");
                                                if ($out1 != '' and $absen_in != '-') {
                                                    $absen_out = FormatTgl('H:i:s', $out1);
                                                    $jam_pulang = getOne("select tm_shift.jam_pulang from set_shift inner join tm_shift on set_shift.id_absensi=tm_shift.id_absensi where tm_shift.bo < '$absen_out' and tm_shift.ao > '$absen_out' and set_shift.id_unit='$biodata[id_unit]'");
                                                } else {
                                                    $absen_out = '-';
                                                    $jam_pulang = '';
                                                }
                                            } else {
                                                if ($in != '') {
                                                    $absen_in = FormatTgl('H:i:s', $in);
                                                    $status = getOne("select tm_shift.nama_shift from set_shift inner join tm_shift on set_shift.id_absensi=tm_shift.id_absensi where tm_shift.bi < '$absen_in' and tm_shift.ai > '$absen_in' and set_shift.id_unit='$biodata[id_unit]'");
                                                    $jam_masuk = getOne("select tm_shift.jam_masuk from set_shift inner join tm_shift on set_shift.id_absensi=tm_shift.id_absensi where tm_shift.bi < '$absen_in' and tm_shift.ai > '$absen_in' and set_shift.id_unit='$biodata[id_unit]'");
                                                } else {
                                                    $absen_in = '-';
                                                    $status = '-';
                                                    $jam_masuk = '';
                                                }
                                                if ($out != '' and $absen_in != '-') {
                                                    $absen_out = FormatTgl('H:i:s', $out);
                                                    $jam_pulang = getOne("select tm_shift.jam_pulang from set_shift inner join tm_shift on set_shift.id_absensi=tm_shift.id_absensi where tm_shift.bo < '$absen_out' and tm_shift.ao > '$absen_out' and set_shift.id_unit='$biodata[id_unit]'");
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
                                                $jam = floor($hitung / (60 * 60));
                                                $telat_in = floor(($hitung - $jam * (60 * 60)) / 60);
                                            } else {
                                                $telat_in = '0';
                                            }
                                            //hitung pulang cepet
                                            if ($absen_out <= $jam_pulang) {
                                                $jam_finger = (is_string($absen_out) ? strtotime($absen_out) : $absen_out);
                                                $jam_out = (is_string($jam_pulang) ? strtotime($jam_pulang) : $jam_pulang);
                                                $hitung = $jam_out - $jam_finger;
                                                $jam = floor($hitung / (60 * 60));
                                                $telat_out = floor(($hitung - $jam * (60 * 60)) / 60);
                                            } else {
                                                $telat_out = '0';
                                            }
                                            $telat = $telat_in + $telat_out;
                                            if ($telat == '0' or $telat > '5') {
                                            } else {
                                                $telat = $telat;
                                            }
                                            $totelat[] = $telat;
                                        ?>
                                            <tr>
                                                <td><?php echo $tanggalan . " (" . hariindo($from) . ")"; ?></td>
                                                <td><?php echo $biodata['nama_pegawai']; ?></td>
                                                <td><?php echo $absen_in; ?></td>
                                                <td><?php echo $absen_out; ?></td>
                                                <td><?php echo $telat; ?></td>
                                                <td><?php echo $status; ?></td>
                                            <?php
                                        }
                                            ?>
                                    </tbody>
                                    <thead>
                                        <tr>
                                            <th colspan="4">TOTAL</th>
                                            <th colspan="2" style="background-color: #e48080;">
                                                <?php
                                                echo array_sum($totelat) . " Menit";
                                                ?>
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php
                break;

            case "absensi-pegawai1":
                require_once('conf/conf_absensi.php');
                ?>
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title fa fa-list"> CHECK IN OUT ABSENSI PEGAWAI</h3>
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
                                                <select class="form-control select2" name="bulan" data-placeholder="-Pilih Bulan-" required>
                                                    <?php loadBln('-Pilih Bulan-'); ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control select2" name="tahun" data-placeholder="-Pilih Tahun-" required>
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
                            <br><br><br><br>
                            <hr>
                            <?php
                            $bulan_post = isset($_POST['bulan']) ? $_POST['bulan'] : null;
                            $tahun_post = isset($_POST['tahun']) ? $_POST['tahun'] : null;
                            $tmtcek = FormatTgl('Y-m-d', $tahun_post . "-" . $bulan_post . "-01");
                            if ($bulan_post == '-Pilih Bulan-' or $tahun_post == 'Tidak Boleh') {
                                $bulan_absensi = date('Y-m-d');
                            ?>
                                <div class="box-header with-border">
                                    <h3 class="box-title fa fa-list"> <label>Bulan dan Tahun Tidak Sesuai</label></h3>
                                    <div class="box-tools pull-right">
                                    </div>
                                </div>
                            <?php
                            } else {
                                $bulan_absensi = $tmtcek;
                            ?>
                                <div class="box-header with-border">
                                    <h3 class="box-title fa fa-list"> <label align="center"> Laporan Absensi <?php echo konversiBulanTahun($bulan_absensi); ?></label></h3>
                                    <div class="box-tools pull-right">
                                    </div>
                                </div>
                            <?php }
                            ?>
                            <div class="box-body table-responsive">
                                <table id="laporan" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Nama Pegawai</th>
                                            <th>IN</th>
                                            <th>OUT</th>
                                            <!--                                <th>ASLI IN</th>
                                <th>ASLI OUT</th>-->
                                            <th>Telat</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $biodata = fetch_array(bukaquery("select log_finger,nama_pegawai from tm_pegawai where nip='$id'"));
                                        $from = FormatTgl('00-m-Y', $bulan_absensi);
                                        $to = FormatTgl('t-m-Y', $bulan_absensi);
                                        while (strtotime($from) < strtotime($to)) {
                                            $from = mktime(0, 0, 0, date("m", strtotime($from)), date("d", strtotime($from)) + 1, date("Y", strtotime($from)));
                                            $from = date("Y-m-d ", $from);
                                            $tanggalan = konversitanggal($from);
                                            $tanggalmin = date('Y-m-d', strtotime("-1 day", strtotime($from)));
                                            $tanggalplus = date('Y-m-d', strtotime("+1 day", strtotime($from)));
                                            $in = getOne("SELECT
                                        log.tanggal,
                                        log.`status`
                                        FROM
                                        log INNER JOIN tm_pegawai ON tm_pegawai.log_finger=log.`user`
                                        where tm_pegawai.log_finger='$biodata[log_finger]' AND log.tanggal LIKE '%$from%' and log.status='I'");
                                            $out = getOne("SELECT
                                        log.tanggal,
                                        log.`status`
                                        FROM
                                        log INNER JOIN tm_pegawai ON tm_pegawai.log_finger=log.`user`
                                        where tm_pegawai.log_finger='$biodata[log_finger]' AND log.tanggal LIKE '%$from%' and log.status='O'");
                                            if (FormatTgl('H:i:s', $in) < '23:59:00' and FormatTgl('H:i:s', $in > '18:00:00')) {
                                                $out1 = getOne("SELECT
                                        log.tanggal,
                                        log.`status`
                                        FROM
                                        log INNER JOIN tm_pegawai ON tm_pegawai.log_finger=log.`user`
                                        where tm_pegawai.log_finger='$biodata[log_finger]' AND log.tanggal LIKE '%$tanggalplus%' order by log.tanggal ASC");
                                                if ($in != '') {
                                                    $absen_in = FormatTgl('H:i:s', $in);
                                                    $status = getOne("select tm_shift.nama_shift from set_shift inner join tm_shift on set_shift.id_absensi=tm_shift.id_absensi where tm_shift.bi < '$absen_in' and tm_shift.ai > '$absen_in' and set_shift.id_unit='$id_unit'");
                                                    $jam_masuk = getOne("select tm_shift.jam_masuk from set_shift inner join tm_shift on set_shift.id_absensi=tm_shift.id_absensi where tm_shift.bi < '$absen_in' and tm_shift.ai > '$absen_in' and set_shift.id_unit='$id_unit'");
                                                    if ($absen_in >= $jam_masuk) {
                                                        $jam_finger = (is_string($absen_in) ? strtotime($absen_in) : $absen_in);
                                                        $jam_in = (is_string($jam_finger) ? strtotime($jam_masuk) : $jam_masuk);
                                                        $hitung = $jam_finger - $jam_in;
                                                        $jam = floor($hitung / (60 * 60));
                                                        $telat_in = floor(($hitung - $jam * (60 * 60)) / 60);
                                                    } else {
                                                        $telat_in = '';
                                                    }
                                                } else {
                                                    $absen_in = '-';
                                                    $status = '-';
                                                    $jam_masuk = '';
                                                }
                                                if ($out1 != '') {
                                                    $absen_out = FormatTgl('H:i:s', $out1);
                                                    $jam_pulang = getOne("select tm_shift.jam_pulang from set_shift inner join tm_shift on set_shift.id_absensi=tm_shift.id_absensi where tm_shift.bo < '$absen_out' and tm_shift.ao > '$absen_out' and set_shift.id_unit='$id_unit'");
                                                    if ($absen_out <= $jam_pulang) {
                                                        $jam_finger = (is_string($absen_out) ? strtotime($absen_out) : $absen_out);
                                                        $jam_out = (is_string($jam_pulang) ? strtotime($jam_pulang) : $jam_pulang);
                                                        $hitung = $jam_out - $jam_finger;
                                                        $jam = floor($hitung / (60 * 60));
                                                        $telat_out = floor(($hitung - $jam * (60 * 60)) / 60);
                                                    } else {
                                                        $telat_out = '';
                                                    }
                                                } else {
                                                    $absen_out = '-';
                                                    $jam_pulang = '';
                                                }
                                                //jika normal
                                            } else {
                                                if ($in != '') {
                                                    $absen_in = FormatTgl('H:i:s', $in);
                                                    $status = getOne("select tm_shift.nama_shift from set_shift inner join tm_shift on set_shift.id_absensi=tm_shift.id_absensi where tm_shift.bi < '$absen_in' and tm_shift.ai > '$absen_in' and set_shift.id_unit='$id_unit'");
                                                    $jam_masuk = getOne("select tm_shift.jam_masuk from set_shift inner join tm_shift on set_shift.id_absensi=tm_shift.id_absensi where tm_shift.bi < '$absen_in' and tm_shift.ai > '$absen_in' and set_shift.id_unit='$id_unit'");
                                                } else {
                                                    $absen_in = '-';
                                                    $status = '-';
                                                    $jam_masuk = '';
                                                }
                                                if ($out != '' and $absen_in != '-') {
                                                    $absen_out = FormatTgl('H:i:s', $out);
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
                                                $jam = floor($hitung / (60 * 60));
                                                $telat_in = floor(($hitung - $jam * (60 * 60)) / 60);
                                            } else {
                                                $telat_in = '0';
                                            }
                                            //hitung pulang cepet
                                            if ($absen_out <= $jam_pulang) {
                                                $jam_finger = (is_string($absen_out) ? strtotime($absen_out) : $absen_out);
                                                $jam_out = (is_string($jam_pulang) ? strtotime($jam_pulang) : $jam_pulang);
                                                $hitung = $jam_out - $jam_finger;
                                                $jam = floor($hitung / (60 * 60));
                                                $telat_out = floor(($hitung - $jam * (60 * 60)) / 60);
                                            } else {
                                                $telat_out = '0';
                                            }
                                            $telat = $telat_in + $telat_out;
                                            if ($telat == '0' or $telat > '5') {
                                            } else {
                                                $telat = $telat;
                                            }
                                        ?>
                                            <tr>
                                                <td><?php echo $tanggalan . " (" . hariindo($from) . ")"; ?></td>
                                                <td><?php echo $nama_pegawai; ?></td>
                                                <td style="background-color: <?php
                                                                                if ($absen_in == '-') {
                                                                                    echo '#b2b4e1';
                                                                                }
                                                                                ?>;"><?php echo $absen_in; ?></td>
                                                <td style="background-color: <?php
                                                                                if ($absen_out == '-') {
                                                                                    echo '#b2b4e1';
                                                                                }
                                                                                ?>;"><?php echo $absen_out; ?></td>
                                                <!--                    <td><?php // echo $absen_in1;                                                  
                                                                            ?></td>
                                    <td><?php // echo $absen_out1;                                                   
                                        ?></td>-->
                                                <td style="background-color: <?php
                                                                                if ($telat > 0) {
                                                                                    echo '#e48080';
                                                                                }
                                                                                ?>;"><?php
                                                                                        if ($telat == '0') {
                                                                                        } else {
                                                                                            echo $telat;
                                                                                        };
                                                                                        ?></td>
                                                <td><?php echo $status; ?></td>
                                            </tr>
                                        <?php
                                            $totelat[] = $telat;
                                        }
                                        ?>
                                        <thead>
                                            <tr>
                                                <th colspan="4">TOTAL</th>
                                                <th colspan="2" style="background-color: #e48080;">
                                                    <?php
                                                    echo array_sum($totelat) . " Menit";
                                                    ?>
                                                </th>
                                            </tr>
                                        </thead>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php
                break;

            case "rekap-absensi-pegawai":
                ?>
                    <input type="hidden" id="rekap-absensi-pegawai-id_kepegawaian" name="rekap-absensi-pegawai-id_kepegawaian" value="<?php echo $id_user; ?>">
                    <!-- modal - modal -->

                    <div class="modal fade" id="modal-warning-update-absensi-pegawai" name="modal-warning-update-absensi-pegawai" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header"></div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Anda yakin akan menyimpan hasil rekapitulasi absensi ini ?</label>
                                        <label>Absensi akan disimpankan kepada masing-masing pegawai</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                    <button type="button" class="btn btn-warning" onclick="update_absensi_pegawai(`<?php echo $id_user; ?>`);">SIMPAN</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modal-status-absensi-unit_absensi-pegawai" name="modal-status-absensi-unit_absensi-pegawai" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h3 class="modal-title fa fa-calendar">&nbsp;Lihat Status Absensi Unit</h3>
                                </div>
                                <div class="modal-body">
                                    <table id="modal-status-absensi-unit_absensi-pegawai_table" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Unit</th>
                                                <th>Sudah Digenerate</th>
                                                <th>Sudah Disubmit</th>
                                                <th>Tanggapan Kains/Kasatpel</th>
                                                <th>Ditarik Absensi</th>
                                                <th>Tarik Abensi ? </th>
                                                <th>Tarik By Pegawai ?</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                    <button type="button" class="btn btn-success" onclick="get_status_duplikat_absensi_pegawai();">Lanjutkan Generate Absensi</button>
                                    <!-- <button type="button" class="btn btn-success" onclick="rekap_cuti();">Lanjutkan Generate Absensi</button> -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modal-hapus-absensi_shift" name="modal-hapus-absensi_shift" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h3 class="modal-title fa fa-calendar">&nbsp;Hapus Rekapitulasi Absensi</h3>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Apakah anda yakin akan menghapus rekapulasi absensi ini ?</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                    <button type="button" class="btn btn-danger" onclick="delete_rekap_detail_absensi_pegawai_button();">Ya</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- end modal - modal -->

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
                                <div class="form-group">
                                    Bulan
                                    <br>
                                    <select class="form-control select2" id="rekap-absensi-pegawai-select-month" name="rekap-absensi-pegawai-select-month" data-placeholder="-Pilih Bulan-" onchange="select_date_rekap_absensi_pegawai_event(this);">
                                        <?php loadBln("-Pilih Bulan-"); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    Tahun
                                    <br>
                                    <select class="form-control select2" id="rekap-absensi-pegawai-select-year" name="rekap-absensi-pegawai-select-year" data-placeholder="-Pilih Tahun-" onchange="select_date_rekap_absensi_pegawai_event(this);">
                                        <?php loadThn('-Pilih Tahun-'); ?>
                                    </select>
                                </div>

                            </div>
                            <div class="form-group">
                                <br>
                                <button name="btn-rekap-absensi-pegawai-search" value="Cari" class="btn btn-info btn-md" onclick="rekap_absensi_pegawai_search_periode();">
                                    Cari Rekapitulasi
                                </button>

                                <button id="btn-delete-absensi-pegawai" name="btn-delete-absensi-pegawai" class="btn btn-danger btn-md" onclick="show_modal_hapus_absensi_pegawai();" style="display: none">
                                    Hapus Rekapitulasi Absensi
                                </button>

                                <button id="btn-generate-rekap-absensi-pegawai" name="btn-generate-rekap-absensi-pegawai" class="btn btn-success btn-md" onclick="get_status_unit_rekapitulasi_absensi_pegawai();" style="display: none;">
                                    Lihat Status Absensi Unit
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title fa fa-list"> REKAPITULASI ABSENSI PEGAWAI BULAN <?php echo strtoupper((konversiBulanTahun(TanggalAkhirBulanKemarin()))); ?></h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                    title="Collapse">
                                    <i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <!-- /.box-header -->
                            <div class="box-body table-responsive" id="rekap-absensi-pegawai-box">
                                <table class="table table-bordered table-striped" id="rekap-absensi-pegawai-table">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Pegawai</th>
                                            <th>Alpha/Hari</th>
                                            <th>Sakit 1-2/Hari</th>
                                            <th>Sakit >2/Hari</th>
                                            <th>Izin/Hari</th>
                                            <th>Telat/Menit</th>
                                            <th>Pulang Cepat/Menit</th>
                                            <th>Hari Kerja</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 0;
                                        $tmtcek = TanggalAkhirBulanKemarin();
                                        $sql_pegawai = bukaquery("SELECT 
                                        tm_pegawai.id_user, tm_pegawai.nip, tm_pegawai.nama_pegawai, tm_unit.id_unit , tm_unit.nama_unit                                
                                    FROM tm_pegawai
                                    INNER JOIN tm_unit ON tm_pegawai.id_unit=tm_unit.id_unit
                                    where (tm_pegawai.status_pegawai='NON PNS' OR tm_pegawai.status_pegawai='PJLP' OR tm_pegawai.status_pegawai='SPESIALIS') and tm_pegawai.status='AKTIF' 
                                        and tm_pegawai.tgl_masuk <= '$tmtcek'   
                                    order by tm_unit.nama_unit desc
                                    LIMIT 0
                            ");

                                        while ($row = fetch_array($sql_pegawai)) {
                                            $no++;
                                            $cv_waktu_penambahan = getOne("select count(id_waktu_k) from tm_waktu_k where month(tm_waktu_k.date_k)='$bln_sebelumnya' and year(tm_waktu_k.date_k)='$thn' and tm_waktu_k.id_user='$row[id_user]'");
                                            $cv_waktu_pengurangan = getOne("select count(id_waktu_t) from tm_waktu_t where month(tm_waktu_t.date_t)='$bln_sebelumnya' and year(tm_waktu_t.date_t)='$thn' and tm_waktu_t.id_user='$row[id_user]'");
                                            $cv_waktu_shift = getOne("select count(id_waktu_s) from tm_waktu_s where month(tm_waktu_s.date_s)='$bln_sebelumnya' and year(tm_waktu_s.date_s)='$thn' and tm_waktu_s.id_user='$row[id_user]'");
                                            $penambahan = fetch_array(bukaquery("SELECT * FROM tm_waktu_t where tm_waktu_t.date_t='$tmtcek' and tm_waktu_t.id_user='$row[id_user]'"));
                                            $pengurangan = fetch_array(bukaquery("SELECT * FROM tm_waktu_k where tm_waktu_k.date_k='$tmtcek' and tm_waktu_k.id_user='$row[id_user]'"));
                                            //$shift = fetch_array(bukaquery("SELECT * FROM tm_waktu_s where tm_waktu_s.date_s='$tmtcek' and tm_waktu_s.id_user='$row[id_user]'"));
                                            $jhk = fetch_array(bukaquery("SELECT (tm_waktu_s.j_hkm+tm_waktu_s.j_hks+tm_waktu_s.j_hlm+tm_waktu_s.j_hlp+tm_waktu_s.j_hls+tm_waktu_s.j_hrm+tm_waktu_s.j_hrp+tm_waktu_s.j_hrs+tm_waktu_s.j_ns) as jumlah from tm_waktu_s where tm_waktu_s.date_s='$tmtcek' and tm_waktu_s.id_user='$row[id_user]'"))
                                        ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $row['nip']; ?></td>
                                                <td><?php echo $row['nama_pegawai']; ?></td>
                                                <td><?php echo $row['nama_unit']; ?></td>
                                                <td><?php echo $pengurangan['alpha']; ?></td>
                                                <td><?php echo $pengurangan['sakit1']; ?></td>
                                                <td><?php echo $pengurangan['sakit2']; ?></td>
                                                <td><?php echo $pengurangan['izin']; ?></td>
                                                <td>asd<?php echo $pengurangan['telat']; ?></td>
                                                <td><?php
                                                    if ($jhk['jumlah'] != '') {
                                                        echo $jhk['jumlah'] . " Hari";
                                                    }
                                                    ?></td>
                                                <td>
                                                    <?php if ($cv_waktu_penambahan != '0') { ?>
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
                                                    <a href="?<?php echo paramEncrypt("module=absensi-pegawai&act=cek-absensi-pegawai&id=" . $row['nip'] . "&dpa=" . $row['id_unit'] . ""); ?>"><span title="Absensi" class="btn-xs btn-info fa fa-eye"><?php echo $row['nip']; ?></span></a>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="box-body">
                            <span id="rekap-absensi-update-button" name="rekap-absensi-update-button" data-toggle="modal" data-target="#modal-warning-update-absensi-pegawai" class="btn btn-block btn-warning btn-lg" style="display: none;">
                                <i class="fa fa-send">&nbsp;&nbsp;&nbsp;PERBARUI</i>
                            </span>
                        </div>
                    </div>

                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title fa fa-list">&nbsp;DAFTAR UNIT TELAH DIREKAPITULASI</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="box-body table-responsive" id="rekap-absensi-pegawai-list-unit-box">
                                <table class="table table-bordered table-striped" id="rekap-absensi-pegawai-list-unit-table">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Unit</th>
                                            <th>Tanggal Direkap</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php
                break;

            case "detail-absensi-pegawai":
                ?>
                    <!-- Modal modal -->
                    <div class="modal fade" id="detail-absensi-pegawai_edit-shift">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h3 class="modal-title fa fa-calendar">&nbsp;Ubah Shift</h3>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="detail-absensi-pegawai_edit-shift_id-kepegawaian" name="detail-absensi-pegawai_edit-shift_id-kepegawaian">
                                    <input type="hidden" id="detail-absensi-pegawai_edit-shift_id-unit" name="detail-absensi-pegawai_edit-shift_id-unit">
                                    <input type="hidden" id="detail-absensi-pegawai_edit-shift_id-user" name="detail-absensi-pegawai_edit-shift_id-user">
                                    <input type="hidden" id="detail-absensi-pegawai_edit-shift_log-finger" name="detail-absensi-pegawai_edit-shift_log-finger">
                                    <input type="hidden" id="detail-absensi-pegawai_edit-shift_month" name="detail-absensi-pegawai_edit-shift_month">
                                    <input type="hidden" id="detail-absensi-pegawai_edit-shift_year" name="detail-absensi-pegawai_edit-shift_year">
                                    <input type="hidden" id="detail-absensi-pegawai_edit-shift_date" name="detail-absensi-pegawai_edit-shift_date">
                                    <input type="hidden" id="detail-absensi-pegawai_edit-shift_id-absensi" name="detail-absensi-pegawai_edit-shift_id-absensi">
                                    <input type="hidden" id="detail-absensi-pegawai_edit-shift_id-absensi-tipe" name="detail-absensi-pegawai_edit-shift_id-absensi-tipe">
                                    <input type="hidden" id="detail-absensi-pegawai_edit-shift_id-ketidakhadiran" name="detail-absensi-pegawai_edit-shift_id-ketidakhadiran">
                                    <div class="form-group" id="detail-absensi-pegawai_edit-shift_profil"></div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Status Shift</label>
                                                <select class="form-control select2" style="width: 100%;" id="detail-absensi-pegawai_edit-shift_shift-option" name="detail-absensi-pegawai_edit-shift_shift-option" onchange="modaleditshift_options_shift();"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Status Shift</label>
                                                <select class="form-control select2" style="width: 100%;" id="detail-absensi-pegawai_edit-shift_shift-tipe-option" name="detail-absensi-pegawai_edit-shift_shift-tipe-option" onchange="modaleditshift_options_shifttipe();"></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Status Ketidakhadiran</label>
                                        <select class="form-control select2" style="width: 100%;" id="detail-absensi-pegawai_edit-shift_ketidakhadiran-option" name="detail-absensi-pegawai_edit-shift_ketidakhadiran-option" onchange="modaleditshift_options_ketidakhadiran();"></select>
                                    </div>
                                    <div class="form-group">
                                        <label>Hari Libur</label>
                                        <select class="form-control select2" style="width: 100%;" id="detail-absensi-pegawai_edit-shift_harilibur" name="detail-absensi-pegawai_edit-shift_harilibur" onchange="modaleditshift_options_harilibur();">
                                            <option selected value="0">Tidak</option>
                                            <option value="1">Ya</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                    <button type="button" id="detail-absensi-pegawai_edit-shift_harilibur-btn" class="btn btn-warning" onclick="put_shift_detail_absensi_pegawai(`#`+this.id);">Perbarui</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="detail-absensi-pegawai_edit_absensimasuk">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h3 class="modal-title fa fa-calendar">&nbsp;Ubah Absensi Masuk Pegawai</h3>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="detail-absensi-pegawai_edit_absensimasuk_id-kepegawaian" name="detail-absensi-pegawai_edit_absensimasuk_id-kepegawaian">
                                    <input type="hidden" id="detail-absensi-pegawai_edit_absensimasuk_id-user" name="detail-absensi-pegawai_edit_absensimasuk_id-user">
                                    <input type="hidden" id="detail-absensi-pegawai_edit_absensimasuk_id-unit" name="detail-absensi-pegawai_edit_absensimasuk_id-unit">
                                    <input type="hidden" id="detail-absensi-pegawai_edit_absensimasuk_month" name="detail-absensi-pegawai_edit_absensimasuk_month">
                                    <input type="hidden" id="detail-absensi-pegawai_edit_absensimasuk_year" name="detail-absensi-pegawai_edit_absensimasuk_year">
                                    <input type="hidden" id="detail-absensi-pegawai_edit_absensimasuk_date" name="detail-absensi-pegawai_edit_absensimasuk_date">

                                    <div class="form-group" id="detail-absensi-pegawai_edit_absensimasuk_profile"></div>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Pilih Absensi Masuk Baru</label>
                                                <select class="form-control" style="width: 100%;" id="detail-absensi-pegawai_edit_absensimasuk_list" name="detail-absensi-pegawai_edit_absensimasuk_list" onchange="modaleditabsensimasuk_list_event(this);"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <label>atau</label>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Buat Absensi Masuk Baru</label>
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" id="detail-absensi-pegawai_edit_absensimasuk_date-new" name="detail-absensi-pegawai_edit_absensimasuk_date-new" data-inputmask='"mask":"9999-99-99", "placeholder": "yyyy-mm-dd"' onchange="modaleditabsensimasuk_date_event(this);" data-mask>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" id="detail-absensi-pegawai_edit_absensimasuk_time-new" name="detail-absensi-pegawai_edit_absensimasuk_time-new" data-inputmask='"mask":"99:99:99", "placeholder": "hh:ii:ss"' onchange="modaleditabsensimasuk_time_event(this);" data-mask>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-sm btn-primary">Set</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <tr>
                                            <td><label>Jam Masuk Baru Terpilih</label></td>
                                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                            <td><input class="form-control" id="detail-absensi-pegawai_edit_absensimasuk_selected" name="detail-absensi-pegawai_edit_absensimasuk_selected" type="text" disabled></td>
                                        </tr>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-warning" onclick="put_absensimasuk_detail_absensi_pegawai();">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="detail-absensi-pegawai_edit_absensipulang">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h3 class="modal-title fa fa-calendar">&nbsp;Ubah Absensi Pulang Pegawai</h3>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="detail-absensi-pegawai_edit_absensipulang_id-kepegawaian" name="detail-absensi-pegawai_edit_absensipulang_id-kepegawaian">
                                    <input type="hidden" id="detail-absensi-pegawai_edit_absensipulang_id-user" name="detail-absensi-pegawai_edit_absensipulang_id-user">
                                    <input type="hidden" id="detail-absensi-pegawai_edit_absensipulang_id-unit" name="detail-absensi-pegawai_edit_absensipulang_id-unit">
                                    <input type="hidden" id="detail-absensi-pegawai_edit_absensipulang_month" name="detail-absensi-pegawai_edit_absensipulang_month">
                                    <input type="hidden" id="detail-absensi-pegawai_edit_absensipulang_year" name="detail-absensi-pegawai_edit_absensipulang_year">
                                    <input type="hidden" id="detail-absensi-pegawai_edit_absensipulang_date" name="detail-absensi-pegawai_edit_absensipulang_date">

                                    <div class="form-group" id="detail-absensi-pegawai_edit_absensipulang_profile"></div>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Pilih Absensi Pulang Baru</label>
                                                <select class="form-control" style="width: 100%;" id="detail-absensi-pegawai_edit_absensipulang_list" name="detail-absensi-pegawai_edit_absensipulang_list" onchange="modaleditabsensipulang_list_event(this);"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <label>atau</label>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Buat Absensi Pulang Baru</label>
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" id="detail-absensi-pegawai_edit_absensipulang_date-new" name="detail-absensi-pegawai_edit_absensipulang_date-new" data-inputmask='"mask":"9999-99-99", "placeholder": "yyyy-mm-dd"' onchange="modaleditabsensipulang_date_event(this);" data-mask>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" id="detail-absensi-pegawai_edit_absensipulang_time-new" name="detail-absensi-pegawai_edit_absensipulang_time-new" data-inputmask='"mask":"99:99:99", "placeholder": "hh:ii:ss"' onchange="modaleditabsensipulang_time_event(this);" data-mask>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-sm btn-primary">Set</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <tr>
                                            <td><label>Jam Pulang Baru Terpilih</label></td>
                                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                            <td><input class="form-control" id="detail-absensi-pegawai_edit_absensipulang_selected" name="detail-absensi-pegawai_edit_absensipulang_selected" type="text" disabled></td>
                                        </tr>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-warning" onclick="put_absensipulang_detail_absensi_pegawai();">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="detail-absensi-pegawai_edit_keterlambatan">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h3 class="modal-title fa fa-calendar">&nbsp;Ubah Keterlambatan Pegawai</h3>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="detail-absensi-pegawai_edit_keterlambatan_id-kepegawaian" name="detail-absensi-pegawai_edit_keterlambatan_id-kepegawaian">
                                    <input type="hidden" id="detail-absensi-pegawai_edit_keterlambatan_id-user" name="detail-absensi-pegawai_edit_keterlambatan_id-user">
                                    <input type="hidden" id="detail-absensi-pegawai_edit_keterlambatan_id-unit" name="detail-absensi-pegawai_edit_keterlambatan_id-unit">
                                    <input type="hidden" id="detail-absensi-pegawai_edit_keterlambatan_month" name="detail-absensi-pegawai_edit_keterlambatan_month">
                                    <input type="hidden" id="detail-absensi-pegawai_edit_keterlambatan_year" name="detail-absensi-pegawai_edit_keterlambatan_year">
                                    <input type="hidden" id="detail-absensi-pegawai_edit_keterlambatan_date" name="detail-absensi-pegawai_edit_keterlambatan_date">
                                    <input type="hidden" id="detail-absensi-pegawai_edit_keterlambatan-keterlambatan-old" name="detail-absensi-pegawai_edit_keterlambatan-keterlambatan-old">

                                    <div class="form-group" id="detail-absensi-pegawai_edit_keterlambatan_profile">
                                    </div>
                                    <div class="form-group">
                                        <label>Keterangan</label>
                                        <select class="form-control select2" id="detail-absensi-pegawai_edit_keterlambatan-keterangan" name="detail-absensi-pegawai_edit_keterlambatan-keterangan" data-placeholder="-Pilih Keterangan-" style="width: 100%;">
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Keterlambatan</label>
                                        <input type="number" class="form-control" id="detail-absensi-pegawai_edit_keterlambatan-keterlambatan" name="detail-absensi-pegawai_edit_keterlambatan-keterlambatan">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="button" id="detail-absensi-pegawai_edit_keterlambatan-keterlambatan-btn" class="btn btn-warning" onclick="put_keterlambatan_absensi_pegawai(`#`+this.id);">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="detail-absensi-pegawai_edit_pulangcepat">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h3 class="modal-title fa fa-calendar">&nbsp;Ubah Menit Pulang Cepat Pegawai</h3>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="detail-absensi-pegawai_edit_pulangcepat_id-kepegawaian" name="detail-absensi-pegawai_edit_pulangcepat_id-kepegawaian">
                                    <input type="hidden" id="detail-absensi-pegawai_edit_pulangcepat_id-user" name="detail-absensi-pegawai_edit_pulangcepat_id-user">
                                    <input type="hidden" id="detail-absensi-pegawai_edit_pulangcepat_id-unit" name="detail-absensi-pegawai_edit_pulangcepat_id-unit">
                                    <input type="hidden" id="detail-absensi-pegawai_edit_pulangcepat_month" name="detail-absensi-pegawai_edit_pulangcepat_month">
                                    <input type="hidden" id="detail-absensi-pegawai_edit_pulangcepat_year" name="detail-absensi-pegawai_edit_pulangcepat_year">
                                    <input type="hidden" id="detail-absensi-pegawai_edit_pulangcepat_date" name="detail-absensi-pegawai_edit_pulangcepat_date">
                                    <input type="hidden" id="detail-absensi-pegawai_edit_pulangcepat-pulangcepat-old" name="detail-absensi-pegawai_edit_pulangcepat-pulangcepat-old">

                                    <div class="form-group" id="detail-absensi-pegawai_edit_pulangcepat_profile"></div>
                                    <div class="form-group">
                                        <label>Keterangan</label>
                                        <select class="form-control select2" id="detail-absensi-pegawai_edit_pulangcepat-keterangan" name="detail-absensi-pegawai_edit_pulangcepat-keterangan" data-placeholder="-Pilih Keterangan-" style="width: 100%;">
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Pulang Cepat</label>
                                        <input type="number" class="form-control" id="detail-absensi-pegawai_edit_pulangcepat-pulangcepat" name="detail-absensi-pegawai_edit_pulangcepat-pulangcepat">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-warning" onclick="put_pulangcepat_absensi_pegawai();">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal modal -->
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title fa fa-list"> DETAIL ABSENSI PEGAWAI BULAN <?php echo strtoupper(konversiBulan($bulan_temp)) . " " . $tahun_temp; ?></h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">

                            <!-- profile pegawai -->
                            <?php
                            $sql = bukaquery2("SELECT
                            a.nip, a.foto, a.nama_pegawai, a.id_unit, a.jk, b.nama_unit, a.sub_bagian, d.id_level, e.nama_level
                        FROM tm_pegawai a
                            INNER JOIN tm_unit b ON a.id_unit = b.id_unit
                            INNER JOIN tm_user d ON a.id_user = d.id_user
                            INNER JOIN tm_level e ON d.id_level = e.id_level
                        WHERE a.id_user = '" . $id_user_temp . "'
                    ");

                            $pegawai = fetch_array($sql);
                            ?>
                            <div class="form-group">
                                <table>
                                    <tr>
                                        <td>
                                            <img src="<?php echo $pegawai['foto'] == '-' || $pegawai['foto'] == '' ? ($pegawai['jk'] == 'L' ? 'img/laki.png' : 'img/perempuan.png') : 'img/' . $pegawai['foto']; ?>" width="150" height="150" class="img-circle" alt="Generic Placeholder Thumbnail">
                                        </td>
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td>
                                            <table>
                                                <tr>
                                                    <td>Nama</td>
                                                    <td>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;</td>
                                                    <td><?php echo $pegawai['nama_pegawai']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>NIP</td>
                                                    <td>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;</td>
                                                    <td><?php echo $pegawai['nip']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Unit</td>
                                                    <td>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;</td>
                                                    <td><?php echo $pegawai['nama_level'] . " - " . $pegawai['nama_unit']; ?></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <!-- end profile pegawai -->

                            <!-- rekapitulasi absensi pegawai -->
                            <br>
                            <div class="form-group">
                                <h4>Rekapitulasi Absensi Pegawai</h4>
                                <table class="table table-bordered table-striped" id="detail-absensi-pegawai-rekap-table">
                                    <thead>
                                        <tr>
                                            <th>Alpha (-)</th>
                                            <th>Sakit < 2 Hari (-)</th>
                                            <th>Sakit > 2 Hari (-)</th>
                                            <th>Izin (-)</th>
                                            <th>Telat (-)</th>
                                            <th>Pulang Cepat (-)</th>
                                            <th>Cuti sakit (-)</th>
                                            <th>Cuti Alasan Penting (-)</th>
                                            <th>Cuti Persalinan (-)</th>
                                            <th>Izin Setengah Hari (-)</th>
                                            <th>Meninggal (-)</th>
                                            <th>Cuti Sakit -Covid (+)</th>
                                            <th>Cuti Alasan Penting (+)</th>
                                            <th>Cuti Tahunan (+)</th>
                                            <th>Diklat (+)</th>
                                            <th>SPD (+)</th>
                                            <th>Haji (+)</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $sql = bukaquery2("
                                SELECT 
                                    a.k_jml_alpha, a.k_jml_sakit_1hari, a.k_jml_sakit_2hari, a.k_jml_izin, a.k_jml_telat, a.k_jml_plng_cepat, a.k_jml_cuti_sakit, a.k_jml_cuti_alsnpenting, a.k_jml_cuti_prslnan, a.k_jml_izin_sethari, a.k_jml_meninggal, 
                                    a.t_jml_cuti_sakit, a.t_jml_cuti_alsnpenting, a.t_jml_cuti_thnan, a.t_jml_diklat, a.t_jml_spd, a.t_jml_haji
                                FROM tm_jadwalpegawai_absensi_rekap a
                                WHERE a.id_unit = '" . $id_unit_temp . "'
                                    AND a.id_user = '" . $id_user_temp . "'
                                    AND a.month = " . $bulan_temp . " 
                                    AND a.year = " . $tahun_temp . "
                            ");

                                    echo "<tbody>";
                                    while ($row = fetch_array($sql)) {
                                        echo "<tr>";
                                        echo "<td>" . $row['k_jml_alpha'] . " hari</td>";
                                        echo "<td>" . $row['k_jml_sakit_1hari'] . " hari</td>";
                                        echo "<td>" . $row['k_jml_sakit_2hari'] . " hari</td>";
                                        echo "<td>" . $row['k_jml_izin'] . " hari</td>";
                                        echo "<td>" . $row['k_jml_telat'] . " menit</td>";
                                        echo "<td>" . $row['k_jml_plng_cepat'] . " menit</td>";
                                        echo "<td>" . $row['k_jml_cuti_sakit'] . " hari</td>";
                                        echo "<td>" . $row['k_jml_cuti_alsnpenting'] . " hari</td>";
                                        echo "<td>" . $row['k_jml_cuti_prslnan'] . " hari</td>";
                                        echo "<td>" . $row['k_jml_izin_sethari'] . " hari</td>";
                                        echo "<td>" . $row['k_jml_meninggal'] . " hari</td>";
                                        echo "<td>" . $row['t_jml_cuti_sakit'] . " hari</td>";
                                        echo "<td>" . $row['t_jml_cuti_alsnpenting'] . " hari</td>";
                                        echo "<td>" . $row['t_jml_cuti_thnan'] . " hari</td>";
                                        echo "<td>" . $row['t_jml_diklat'] . " hari</td>";
                                        echo "<td>" . $row['t_jml_spd'] . " hari</td>";
                                        echo "<td>" . $row['t_jml_haji'] . " hari</td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody>";
                                    ?>

                                </table>
                            </div>
                            <!-- end rekapitulasi absensi pegawai -->

                            <br>
                            <div class="form-group">
                                <h4>Rekapitulasi Shift Pegawai</h4>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Total Hari Kerja</th>
                                            <th>Total Menit Shift&nbsp;<i class="fa fa-question-circle" title="Total Menit Shift = Total Waktu Kerja dari Shift"></i></th>
                                            <th>Total Menit Kerja&nbsp;<i class="fa fa-question-circle" title="Total Menit Kerja = Total Menit Shfit - Telat"></i></th>
                                            <th>Hari Kerja Sore</th>
                                            <th>Hari Kerja Malam</th>
                                            <th>Hari Libur Pagi</th>
                                            <th>Hari Libur Sore</th>
                                            <th>Hari Libur Malam</th>
                                            <th>Hari Raya Pagi</th>
                                            <th>Hari Raya Sore</th>
                                            <th>Hari Raya Malam</th>
                                            <th>Hari Non Shift</th>
                                        </tr>
                                    </thead>
                                    <?php

                                    $sql = bukaquery2("SELECT
                                a.jml_hari_kerja, a.jml_menit_kerja, a.s_jml_hks, a.s_jml_hkm, a.s_jml_hlp, a.s_jml_hls, a.s_jml_hlm, a.s_jml_hrp, a.s_jml_hrs, a.s_jml_hrm, a.s_jml_ns
                            FROM tm_jadwalpegawai_absensi_rekap a
                            WHERE a.id_unit = '" . $id_unit_temp . "'
                                AND a.id_user = '" . $id_user_temp . "'
                                AND a.month = " . $bulan_temp . " 
                                AND a.year = " . $tahun_temp . "
                        ");

                                    echo "<tbody>";
                                    while ($row = fetch_array($sql)) {
                                        echo "<tr>";
                                        echo "<td>" . $row['jml_hari_kerja'] . " hari</td>";
                                        echo "<td>" . $row['jml_menit_kerja'] . " menit</td>";
                                        echo "<td>0 menit</td>";
                                        echo "<td>" . $row['s_jml_hks'] . " hari</td>";
                                        echo "<td>" . $row['s_jml_hkm'] . " hari</td>";
                                        echo "<td>" . $row['s_jml_hlp'] . " hari</td>";
                                        echo "<td>" . $row['s_jml_hls'] . " hari</td>";
                                        echo "<td>" . $row['s_jml_hlm'] . " hari</td>";
                                        echo "<td>" . $row['s_jml_hrp'] . " hari</td>";
                                        echo "<td>" . $row['s_jml_hrs'] . " hari</td>";
                                        echo "<td>" . $row['s_jml_hrm'] . " hari</td>";
                                        echo "<td>" . $row['s_jml_ns'] . " hari</td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody>";
                                    ?>
                                </table>
                            </div>

                            <!-- detail absensi pegawai -->
                            <br>
                            <?php
                            $cek = bukaquery2("SELECT id_level FROM tm_user WHERE id_user = '$_SESSION[id_user]' and id_level = 'LVL-000015'")->num_rows;

                            if ($cek == 1) {
                            ?>
                                <button class='btn btn-warning' onclick="location.href='<?php echo $url_api_absensi; ?>?action=get_cleaning_absen&id_user=<?php echo $id_user_temp; ?>&bulan=<?php echo $bulan_temp; ?>&tahun=<?php echo $tahun_temp; ?>'">START CLEANING</button>
                            <?php } ?>
                            <div class="form-group">
                                <h4>Detail Absensi Pegawai</h4>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Tanggal</th>
                                            <th>Shift</th>
                                            <th>Tipe Shift</th>
                                            <th>Absensi Masuk</th>
                                            <th>Absensi Pulang</th>
                                            <th>Keterlambatan</th>
                                            <th>Pulang Cepat</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $sql = bukaquery2("
                                SELECT 
                                    a.date, a.id_absensi, b.desc_shift, a.id_absensi_tipe, d.desc_shift_tipe, a.id_ketidakhadiran, c.desc_ketidakhadiran, 
                                    IF(a.id_absensi = '' OR a.id_absensi IS NULL, 'F', 'T') AS shift_aktif, IF(a.id_ketidakhadiran = '' OR a.id_ketidakhadiran IS NULL, 'F', 'T') AS shift_nonaktif,
                                    IFNULL(e.keterangan, '') AS desc_hari_raya,
                                    a.jam_masuk_absensi_aktif, a.absensi_masuk, a.keterlambatan, f.keterangan AS keterangan_keterlambatan,
                                    a.jam_pulang_absensi_aktif, a.absensi_pulang, a.pulang_cepat, g.keterangan AS keterangan_pulangcepat
                                FROM tm_jadwalpegawai_absensi_detail a
                                    LEFT JOIN tm_shift b ON a.id_absensi = b.id_absensi
                                    LEFT JOIN tm_shift_ketidakhadiran c ON a.id_ketidakhadiran = c.id_ketidakhadiran
                                    LEFT JOIN tm_shift_tipe d ON a.id_absensi_tipe = d.id_absensi_tipe
                                    LEFT JOIN tm_hari_raya e ON a.date = e.tanggal
                                    LEFT JOIN tm_absensi_ket_kepegawaian f ON a.keterangan_keterlambatan = f.id_keterangan
                                    LEFT JOIN tm_absensi_ket_kepegawaian g ON a.keterangan_pulangcepat = g.id_keterangan
                                WHERE a.id_unit = '" . $id_unit_temp . "'
                                    AND a.id_user = '" . $id_user_temp . "'
                                    AND a.month = " . $bulan_temp . " 
                                    AND a.year = " . $tahun_temp . "
                                ORDER BY a.date
                            ");

                                    $i = 1;
                                    echo "<tbody>";
                                    while ($row = fetch_array($sql)) {
                                        $btn_ubah_shift = "<button class='btn btn-xs btn-warning' onclick='modal_detail_absensi_pegawai_edit_shift(`" . $id_user . "`, `" . $id_unit_temp . "`, `" . $id_user_temp . "`, `" . $temp_log_finger . "`, `" . $pegawai['nip'] . "`, `" . $pegawai['nama_pegawai'] . "`, `" . $pegawai['nama_unit'] . "`, `" . $pegawai['nama_level'] . "`, `" . $row['date'] . "`, `" . $bulan_temp . "`, `" . $tahun_temp . "`, `" . $row['id_absensi'] . "`, `" . $row['id_absensi_tipe'] . "`, `" . $row['id_ketidakhadiran'] . "`);'>ubah</button>";
                                        $btn_ubah_absensimasuk = "<button class='btn btn-xs btn-warning' onclick='modal_detail_absensi_pegawai_edit_absensimasuk(`" . $id_user . "`, `" . $row['desc_shift'] . " (" . $row['jam_masuk_absensi_aktif'] . " - " . $row['jam_pulang_absensi_aktif'] . ") " . "`, `" . $row['desc_shift_tipe'] . "`, `" . $row['absensi_masuk'] . "`, `" . $id_unit_temp . "`, `" . $id_user_temp . "`, `" . $temp_log_finger . "`, `" . $pegawai['nip'] . "`, `" . $pegawai['nama_pegawai'] . "`, `" . $pegawai['nama_unit'] . "`, `" . $pegawai['nama_level'] . "`, `" . $row['date'] . "`, `" . $bulan_temp . "`, `" . $tahun_temp . "`);'>ubah</button>";
                                        $btn_ubah_absensipulang = "<button class='btn btn-xs btn-warning' onclick='modal_detail_absensi_pegawai_edit_absensipulang(`" . $id_user . "`, `" . $row['desc_shift'] . " (" . $row['jam_masuk_absensi_aktif'] . " - " . $row['jam_pulang_absensi_aktif'] . ") " . "`, `" . $row['desc_shift_tipe'] . "`, `" . $row['absensi_pulang'] . "`, `" . $id_unit_temp . "`, `" . $id_user_temp . "`, `" . $temp_log_finger . "`, `" . $pegawai['nip'] . "`, `" . $pegawai['nama_pegawai'] . "`, `" . $pegawai['nama_unit'] . "`, `" . $pegawai['nama_level'] . "`, `" . $row['date'] . "`, `" . $bulan_temp . "`, `" . $tahun_temp . "`);'>ubah</button>";
                                        $btn_ubah_keterlambatan = $row['shift_aktif'] == 'T' && $row['keterlambatan'] != 0
                                            ? "<button class='btn btn-xs btn-warning' onclick='modal_detail_absensi_pegawai_edit_keterlambatan(`" . $id_user . "`, `" . $row['desc_shift'] . " (" . $row['jam_masuk_absensi_aktif'] . " - " . $row['jam_pulang_absensi_aktif'] . ") " . "`, `" . $row['desc_shift_tipe'] . "`, `" . $row['absensi_masuk'] . "`, `" . $id_unit_temp . "`, `" . $id_user_temp . "`, `" . $temp_log_finger . "`, `" . $pegawai['nip'] . "`, `" . $pegawai['nama_pegawai'] . "`, `" . $pegawai['nama_unit'] . "`, `" . $pegawai['nama_level'] . "`, `" . $row['date'] . "`, `" . $bulan_temp . "`, `" . $tahun_temp . "`);'>ubah</button>"
                                            : "";
                                        $btn_ubah_pulangcepat = $row['shift_aktif'] == 'T' && $row['pulang_cepat'] != 0
                                            ? "<button class='btn btn-xs btn-warning' onclick='modal_detail_absensi_pegawai_edit_pulangcepat(`" . $id_user . "`, `" . $row['desc_shift'] . " (" . $row['jam_masuk_absensi_aktif'] . " - " . $row['jam_pulang_absensi_aktif'] . ") " . "`, `" . $row['desc_shift_tipe'] . "`, `" . $row['absensi_pulang'] . "`, `" . $id_unit_temp . "`, `" . $id_user_temp . "`, `" . $temp_log_finger . "`, `" . $pegawai['nip'] . "`, `" . $pegawai['nama_pegawai'] . "`, `" . $pegawai['nama_unit'] . "`, `" . $pegawai['nama_level'] . "`, `" . $row['date'] . "`, `" . $bulan_temp . "`, `" . $tahun_temp . "`);'>ubah</button>"
                                            : "";
                                        $keterangan_keterlambatan = $row['keterangan_keterlambatan'] != null ||  $row['keterangan_keterlambatan'] != ''
                                            ? "Ket : " . $row['keterangan_keterlambatan']
                                            : "";
                                        $keterangan_pulangcepat = $row['keterangan_pulangcepat'] != null ||  $row['keterangan_pulangcepat'] != ''
                                            ? "Ket : " . $row['keterangan_pulangcepat']
                                            : "";

                                        echo "<tr>";
                                        echo "<td>" . $i . "</td>";
                                        echo "<td>" . date_format(date_create($row['date']), "d-m-Y (l)") . "</td>";
                                        echo "<td>" . ($row['shift_aktif'] == 'T'
                                            ? $row['desc_shift'] . " (" . $row['jam_masuk_absensi_aktif'] . " - " . $row['jam_pulang_absensi_aktif'] . ") " . $btn_ubah_shift
                                            : ($row['shift_nonaktif'] == 'T'
                                                ? $row['desc_ketidakhadiran'] . " " . $btn_ubah_shift
                                                : ($row['desc_hari_raya'] != ''
                                                    ? $row['desc_hari_raya'] . " " . $btn_ubah_shift
                                                    : '- ' . $btn_ubah_shift))) . "</td>";
                                        echo "<td>" . ($row['shift_aktif'] == 'T'
                                            ? $row['desc_shift_tipe']
                                            : '-') . "</td>";
                                        echo "<td>" . ($row['shift_aktif'] == 'T'
                                            ? $row['absensi_masuk'] . " <br>" . $keterangan_keterlambatan
                                            : '-') . "</td>";
                                        echo "<td>" . ($row['shift_aktif'] == 'T'
                                            ? $row['absensi_pulang'] . " <br>" . $keterangan_pulangcepat
                                            : '-') . "</td>";
                                        echo "<td>" . ($row['shift_aktif'] == 'T'
                                            ? $row['keterlambatan'] . " menit " . $btn_ubah_keterlambatan
                                            : '-') . "</td>";
                                        echo "<td>" . ($row['shift_aktif'] == 'T'
                                            ? $row['pulang_cepat'] . " menit " . $btn_ubah_pulangcepat
                                            : '-') . "</td>";
                                        echo "</tr>";
                                        $i++;
                                    }
                                    echo "</tbody>";
                                    ?>
                                </table>
                            </div>
                            <!-- end detail absensi pegawai -->

                        </div>
                    </div>

                <?php
                break;

            case "laporan-absensi-pegawai":
                ?>
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title fa fa-list"> LAPORAN ABSENSI PEGAWAI 1 </h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                    title="Collapse">
                                    <i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <form action="" method="post">
                                <div class="form-group">
                                    <table>
                                        <tr>
                                            <td>
                                                <label>Tanggal</label>
                                            </td>
                                            <td>
                                                <label>Bulan</label>
                                            </td>
                                            <td>
                                                <label>Tahun</label>
                                            </td>
                                            <td></td>
                                            <td>
                                                <label>Tanggal</label>
                                            </td>
                                            <td>
                                                <label>Bulan</label>
                                            </td>
                                            <td>
                                                <label>Tahun</label>
                                            </td>
                                            <td></td>
                                            <td>
                                                <label>Unit</label>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <select class="form-control select2" name="tanggal_start" required>
                                                    <?= loadTgl(); ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control select2" name="bulan_start" required>
                                                    <?php loadBln('-Pilih Bulan-'); ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control select2" name="tahun_start" required>
                                                    <?php loadThn('-Pilih Tahun-'); ?>
                                                </select>
                                            </td>
                                            <td>
                                                <label>&nbsp;&nbsp;&nbsp;s/d&nbsp;&nbsp;&nbsp;</label>
                                            </td>
                                            <td>
                                                <select class="form-control select2" name="tanggal_end" required>
                                                    <?= loadTgl(); ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control select2" name="bulan_end" required>
                                                    <?php loadBln('-Pilih Bulan-'); ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control select2" name="tahun_end" required>
                                                    <?php loadThn('-Pilih Tahun-'); ?>
                                                </select>
                                            </td>
                                            <td>&nbsp;</td>
                                            <td>
                                                <select class="form-control select2" name="id_unit" required>
                                                    <!-- <option value="all" selected>-Seluruh Unit-</option> -->
                                                    <?php loadUnitByLevel(
                                                        $idlevel,
                                                        $id_unit,
                                                        $kasatpel_pegawai,
                                                        $kasie
                                                    ); ?>
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
                            $tanggal_start = isset($_POST['tanggal_start']) ? $_POST['tanggal_start'] : null;
                            $bulan_start = isset($_POST['bulan_start']) ? $_POST['bulan_start'] : null;
                            $tahun_start = isset($_POST['tahun_start']) ? $_POST['tahun_start'] : null;
                            $tanggal_end = isset($_POST['tanggal_end']) ? $_POST['tanggal_end'] : null;
                            $bulan_end = isset($_POST['bulan_end']) ? $_POST['bulan_end'] : null;
                            $tahun_end = isset($_POST['tahun_end']) ? $_POST['tahun_end'] : null;
                            $id_unit = isset($_POST['id_unit']) ? $_POST['id_unit'] : null;

                            if ($tanggal_start != null && $bulan_start != null && $tahun_start != null && $tanggal_end != null && $bulan_end != null && $tahun_end != null && $id_unit != null) {
                            ?>
                                <!-- /.box-header -->
                                <div class="box-header with-border">
                                    <h3 class="box-title fa fa-list"> <label align="center">Laporan Absensi <?= $tanggal_start . "-" . $bulan_start . "-" . $tahun_start . " s/d " . $tanggal_end . "-" . $bulan_end . "-" . $tahun_end; ?></label></h3>
                                    <div class="box-tools pull-right">
                                    </div>
                                </div>
                                <div class="box-body table-responsive">
                                    <table id="laporan" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIP</th>
                                                <th>Nama Pegawai</th>
                                                <th>Nama Unit</th>
                                                <th>Jabatan</th>
                                                <th>Alpha</th>
                                                <th>Sakit 1-2 & >2 hari</th>
                                                <th>Izin</th>
                                                <th>Telat</th>
                                                <th>Pulang Cepat</th>
                                                <th>Total Telat & Pulang Cepat</th>
                                                <th>Hari Kerja</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            $no = 0;
                                            $condition_idunit = $id_unit == 'all' // jika id_unit dipilih all, berarti tidak ada filter id_unit kecualis unit UNT-000020
                                                ? " AND a.id_unit <> 'UNT-000020'"
                                                : " AND a.id_unit = '" . $id_unit . "'";

                                            $sql_pegawai = bukaquery("
                                    SELECT
                                        a.id_user AS user_id,
                                        a.nip,
                                        a.nama_pegawai,
                                        b.id_unit,
                                        b.nama_unit,
                                        d.nama_level,
                                        (
                                            SELECT COUNT(a.id_jadwalkerja_absensi)
                                            FROM tm_jadwalpegawai_absensi_detail a
                                            WHERE a.id_user = user_id
                                                AND a.id_ketidakhadiran = 'AKT-000001'
                                                AND a.date BETWEEN '" . $tahun_start . "-" . $bulan_start . "-" . $tanggal_start . "' AND '" . $tahun_end . "-" . $bulan_end . "-" . $tanggal_end . "'
                                        ) AS alpha,
                                        (
                                            SELECT COUNT(a.id_jadwalkerja_absensi)
                                            FROM tm_jadwalpegawai_absensi_detail a
                                            WHERE a.id_user = user_id
                                                AND a.id_ketidakhadiran = 'AKT-000002'
                                                AND a.date BETWEEN '" . $tahun_start . "-" . $bulan_start . "-" . $tanggal_start . "' AND '" . $tahun_end . "-" . $bulan_end . "-" . $tanggal_end . "'
                                        ) AS sakit1,
                                        (
                                            SELECT COUNT(a.id_jadwalkerja_absensi)
                                            FROM tm_jadwalpegawai_absensi_detail a
                                            WHERE a.id_user = user_id
                                                AND a.id_ketidakhadiran = 'AKT-000003'
                                                AND a.date BETWEEN '" . $tahun_start . "-" . $bulan_start . "-" . $tanggal_start . "' AND '" . $tahun_end . "-" . $bulan_end . "-" . $tanggal_end . "'
                                        ) AS sakit2,
                                        (
                                            SELECT COUNT(a.id_jadwalkerja_absensi)
                                            FROM tm_jadwalpegawai_absensi_detail a
                                            WHERE a.id_user = user_id
                                                AND a.id_ketidakhadiran = 'AKT-000004'
                                                AND a.date BETWEEN '" . $tahun_start . "-" . $bulan_start . "-" . $tanggal_start . "' AND '" . $tahun_end . "-" . $bulan_end . "-" . $tanggal_end . "'
                                        ) AS izin,
                                        (
                                            SELECT
                                                SUM(a.keterlambatan)
                                            FROM tm_jadwalpegawai_absensi_detail a
                                            WHERE a.id_user = user_id
                                                AND a.date BETWEEN '" . $tahun_start . "-" . $bulan_start . "-" . $tanggal_start . "' AND '" . $tahun_end . "-" . $bulan_end . "-" . $tanggal_end . "'
                                        ) AS telat,
                                        (
                                            SELECT
                                                SUM(a.pulang_cepat)
                                            FROM tm_jadwalpegawai_absensi_detail a
                                            WHERE a.id_user = user_id
                                                AND a.date BETWEEN '" . $tahun_start . "-" . $bulan_start . "-" . $tanggal_start . "' AND '" . $tahun_end . "-" . $bulan_end . "-" . $tanggal_end . "'
                                        ) AS pulang_cepat,
                                        (
                                            SELECT
                                                COUNT(a.id_jadwalkerja_absensi)
                                            FROM tm_jadwalpegawai_absensi_detail a
                                            WHERE a.id_user = user_id
                                                AND a.id_absensi <> ''
                                                AND a.date BETWEEN '" . $tahun_start . "-" . $bulan_start . "-" . $tanggal_start . "' AND '" . $tahun_end . "-" . $bulan_end . "-" . $tanggal_end . "'
                                        ) AS jml_shift
                                    FROM tm_pegawai a 
                                        INNER JOIN tm_unit b ON a.id_unit = b.id_unit 
                                        LEFT JOIN tm_user c ON a.id_user = c.id_user
                                        LEFT JOIN tm_level d ON c.id_level = d.id_level
                                    WHERE (a.status_pegawai = 'NON PNS' OR a.status_pegawai = 'PJLP' OR a.status_pegawai = 'SPESIALIS')
                                        " . $condition_idunit . "
                                    ORDER BY b.nama_unit DESC, d.nama_level
                                ");
                                            while ($row = fetch_array($sql_pegawai)) {
                                                $no++;
                                            ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= "'" . $row['nip']; ?></td>
                                                    <td><?= $row['nama_pegawai']; ?></td>
                                                    <td><?= $row['nama_unit']; ?></td>
                                                    <td><?= $row['nama_level']; ?></td>
                                                    <td><?= $row['alpha']; ?></td>
                                                    <td><?= $row['sakit1'] + $row['sakit2']; ?></td>
                                                    <td><?= $row['izin']; ?></td>
                                                    <td><?= $row['telat']; ?></td>
                                                    <td><?= $row['pulang_cepat']; ?></td>
                                                    <td><?= $row['telat'] + $row['pulang_cepat']; ?></td>
                                                    <td><?= $row['jml_shift'] != '' ? $row['jml_shift'] . ' Hari' : ''; ?></td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                <?php
                break;

            case "pengaturan-absensi":
                ?>
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title fa fa-list"> CONFIGURASI ABSENSI</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                    title="Collapse">
                                    <i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <button type="button" class="btn btn-info fa fa-plus" data-toggle="modal" data-target="#modal-add-pengaturan">
                                Buat Pengaturan
                            </button>
                            <br>
                            <!--Modal Add SKP -->
                            <div class="modal fade" id="modal-add-pengaturan">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title fa fa-plus"> Konfigurasi Shift</h4>
                                        </div>
                                        <form role="form" action="<?php echo $aksi . paramEncrypt('module=pengaturan&act=add-pengaturan'); ?>" method="post">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Bagian/Unit</label>
                                                    <select class="form-control select2" name="id_unit" data-placeholder="-Pilih Bagian/Unit-" style="width: 100%;" required>
                                                        <option selected="selected" value="">-Pilih Bagian/Unit-</option>
                                                        <?php
                                                        $tm_bagian = bukaquery("select tm_unit.id_unit, tm_unit.nama_unit from tm_unit");
                                                        while ($row = fetch_array($tm_bagian)) {
                                                            echo "<option value=" . $row['id_unit'] . ">" . $row['nama_unit'] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                    <label>Shift</label>
                                                    <select class="form-control select2" name="id_absensi" data-placeholder="-Pilih Shift-" style="width: 100%;" required>
                                                        <option selected="selected" value="">-Pilih Shift-</option>
                                                        <?php
                                                        $tm_shift = bukaquery("select tm_shift.id_absensi, tm_shift.nama_shift from tm_shift ");
                                                        while ($row = fetch_array($tm_shift)) {
                                                            echo "<option value=" . $row['id_absensi'] . ">" . $row['nama_shift'] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                    <br><br><br>
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
                                            <th>Nama Unit</th>
                                            <th>Nama Shift</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 0;
                                        $tt_shift = bukaquery("select set_shift.id_shift, tm_unit.id_unit, tm_unit.nama_unit, tm_shift.id_absensi, tm_shift.nama_shift from set_shift
                            inner join tm_shift on set_shift.id_absensi=tm_shift.id_absensi
                            inner join tm_unit on set_shift.id_unit=tm_unit.id_unit ");
                                        while ($row = fetch_array($tt_shift)) {
                                            $no++;
                                        ?>
                                            <!-- Edit Modal SKP -->
                                            <div class="modal fade" id="modal-ubah-<?php echo $row['id_shift']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <form action="<?php echo $aksi . paramEncrypt('module=pengaturan&act=update-pengaturan&id=' . $row['id_shift'] . ''); ?>" method="POST" role="form">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Update Pengaturan Shift</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Bagian/Unit</label>
                                                                        <select class="form-control select2" name="id_unit" data-placeholder="-Pilih Nama Unit/Bagian-" style="width: 100%;" required>
                                                                            <option selected="selected" value="">-Pilih Jenis Peringatan-</option>
                                                                            <?php
                                                                            $tm_unit = bukaquery("select tm_unit.id_unit, tm_unit.nama_unit from tm_unit");
                                                                            while ($tu = fetch_array($tm_unit)) {
                                                                                if ($row['id_unit'] == $tu['id_unit']) {
                                                                                    echo "<option value=" . $tu['id_unit'] . " selected=" . $row['id_unit'] . ">" . $tu['nama_unit'] . "</option>";
                                                                                } else {
                                                                                    echo "<option value=" . $tu['id_unit'] . ">" . $tu['nama_unit'] . "</option>";
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                        <label class="control-label">Nama Shift</label>
                                                                        <select class="form-control select2" name="id_absensi" data-placeholder="-Pilih Nama Shift-" style="width: 100%;" required>
                                                                            <option selected="selected" value="">-Pilih Nama Shift-</option>
                                                                            <?php
                                                                            $tm_shift = bukaquery("select tm_shift.id_absensi, tm_shift.nama_shift from tm_shift");
                                                                            while ($ct = fetch_array($tm_shift)) {
                                                                                if ($row['id_absensi'] == $ct['id_absensi']) {
                                                                                    echo "<option value=" . $ct['id_absensi'] . " selected=" . $row['id_absensi'] . ">" . $ct['nama_shift'] . "</option>";
                                                                                } else {
                                                                                    echo "<option value=" . $ct['id_absensi'] . ">" . $ct['nama_shift'] . "</option>";
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                        <br><br><br>
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
                                            <div class="modal fade" id="modal-hapus-<?php echo $row['id_shift']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <form action="<?php echo $aksi . paramEncrypt('module=pengaturan&act=delete-pengaturan&id=' . $row['id_shift'] . ''); ?>" method="POST" role="form">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Delete Surat Peringatan</h4>
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
                                                <td><?php echo $row['nama_shift'] ?></td>
                                                <td>
                                                    <span data-toggle="modal" data-target="#modal-ubah-<?php echo $row['id_shift']; ?>" title="Ubah" class="btn-xs btn-warning fa fa-edit"></span>
                                                    <span data-toggle="modal" data-target="#modal-hapus-<?php echo $row['id_shift']; ?>" title="Hapus" class="btn-xs btn-danger fa fa-trash"></span>
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
        }
            ?>
            <script src="libs/jquery/jquery.min.js"></script>
            <script>
                var rekap_absensi_pegawai_list_unit_shift_generated = [];
                var rekap_absensi_pegawai_list_unit_shift_generated_2 = [];
                var rekap_absensi_pegawai_list_user_shift_generated = [];
                var rekap_absensi_pegawai_generate_unit_spesialis = false;
                var id_session = null;
                var absensi_live_jadwal_table = null;
                var absensi_live_table = null;
                var rekapitulasi_absensi_pegawai_table = null;

                // fungsi untuk absensi-pegawai-live-jadwal
                function get_list_pegawai_by_idunit() {

                    var id_unit = $('#unit_absensi option:selected').val()

                    $.ajax({
                        url: "<?php echo $url_api_absensi; ?>?action=get_list_pegawai_by_idunit&id_unit=" + id_unit,
                        type: "GET",
                        dataType: "JSON",
                        success: function(response) {

                            if (response.status == 1) {

                                $('#pegawai_absensi').empty();
                                for (let i = 0; i < response.data.length; i++) {

                                    $('#pegawai_absensi').append(new Option(
                                        response.data[i].nama_pegawai,
                                        response.data[i].id_user
                                    ));
                                }
                            } else {

                                console.log("Kode GETLSTPEGAWAI01. Kode Status = 0.");
                                swal("LIST PEGAWAI ERROR", "", "error");
                            }
                        },
                        error: function(errorMsg) {
                            console.log("Kode : GETLSTPEGAWAI01. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
                        }
                    });
                }

                function get_abesnsi_live_jadwal(button_id) {

                    var date_start = $("#tahun_absensi_start option:selected").val() + "-" + $("#bulan_absensi_start option:selected").val() + "-" + $("#tanggal_absensi_start option:selected").val();
                    var date_end = $("#tahun_absensi_end option:selected").val() + "-" + $("#bulan_absensi_end option:selected").val() + "-" + $("#tanggal_absensi_end option:selected").val();
                    var id_unit = $("#unit_absensi").val();
                    var id_user = $("#pegawai_absensi").val();
                    var total_telat_pulangcepat = 0;



                    $(button_id).html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;").prop('disabled', true);
                    absensi_live_jadwal_table.clear();
                    $('#absensi_total_telat_pulangcepat').html("");

                    $.ajax({
                        url: "<?php echo $url_api_absensi; ?>?action=get_absensi_live_jadwal_by_iduser_idunit_month_year&id_unit=" + id_unit + "&id_user=" + id_user + "&date_start=" + date_start + "&date_end=" + date_end,
                        type: "GET",
                        dataType: "JSON",
                        success: function(response) {

                            if (response.status == 1) {

                                for (let i = 0; i < response.data.length; i++) {

                                    total_telat_pulangcepat += response.data[i].telat + response.data[i].pulang_cepat;
                                    absensi_live_jadwal_table.row.add([
                                        (i + 1),
                                        response.data[i].tanggal,
                                        response.data[i].nama_pegawai,
                                        response.data[i].absen_masuk,
                                        response.data[i].absen_pulang,
                                        response.data[i].telat,
                                        response.data[i].pulang_cepat
                                    ]).draw(false);
                                }

                                $('#absensi_total_telat_pulangcepat').html(total_telat_pulangcepat + " Menit");
                            } else {

                                console.log("Kode GETABSENSILIVE01. Kode Status = 0.");
                                swal(response.message, "", "error");
                            }

                            $(button_id).html('<i class="fa fa-search"></i>').prop('disabled', false);
                        },
                        error: function(errorMsg) {

                            $(button_id).html('<i class="fa fa-search"></i>').prop('disabled', false);
                            console.log("Kode : GETABSENSILIVE01. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
                        }
                    });
                }
                // end fungsi untuk absensi-pegawai-live-jadwal

                // fungsi untuk absensi-pegawai-live
                function get_abesnsi_live(button_id) {

                    var date_start = $("#tahun_absensi_start option:selected").val() + "-" + $("#bulan_absensi_start option:selected").val() + "-" + $("#tanggal_absensi_start option:selected").val();
                    var date_end = $("#tahun_absensi_end option:selected").val() + "-" + $("#bulan_absensi_end option:selected").val() + "-" + $("#tanggal_absensi_end option:selected").val();
                    var id_unit = $("#unit_absensi").val();
                    var id_user = $("#pegawai_absensi").val();

                    $(button_id).html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;").prop('disabled', true);
                    absensi_live_table.clear();

                    $.ajax({
                        url: "<?php echo $url_api_absensi; ?>?action=get_absensi_live_by_iduser_idunit_month_year&id_unit=" + id_unit + "&id_user=" + id_user + "&date_start=" + date_start + "&date_end=" + date_end,
                        type: "GET",
                        dataType: "JSON",
                        success: function(response) {

                            if (response.status == 1) {

                                for (let i = 0; i < response.data.length; i++) {

                                    absensi_live_table.row.add([
                                        (i + 1),
                                        response.data[i].nama_pegawai,
                                        response.data[i].tanggal,
                                        response.data[i].status == 'I' ? "Absen Masuk" : "Absen Keluar"
                                    ]).draw(false);
                                }
                            } else {

                                console.log("Kode GETABSENSILIVE01. Kode Status = 0.");
                                swal("LIST PEGAWAI ERROR", "", "error");
                            }

                            $(button_id).html('<i class="fa fa-search"></i>').prop('disabled', false);
                        },
                        error: function(errorMsg) {

                            $(button_id).html('<i class="fa fa-search"></i>').prop('disabled', false);
                            console.log("Kode : GETABSENSILIVE01. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
                        }
                    });
                }
                // end fungsi untuk absensi-pegawai-live-jadwal

                // fungsi untuk rekap-absensi-pegawai
                function remove_leading_zero(value) {
                    return value.replace(/^0+(?!\.|$)/, '');
                }

                function checkbox_rekap_absensi_pegawai_tariabsen_event(checkbox, cur_idunit) {

                    // jika checkbox dipilih, masukkan ke array list unit akan ditarik
                    if (checkbox.checked) {

                        rekap_absensi_pegawai_list_unit_shift_generated.push(cur_idunit);
                    } else {

                        var index = rekap_absensi_pegawai_list_unit_shift_generated.indexOf(cur_idunit);
                        if (index !== -1) rekap_absensi_pegawai_list_unit_shift_generated.splice(index, 1);
                    }
                }

                function select_date_rekap_absensi_pegawai_event(selected) {

                    $('#btn-generate-rekap-absensi-pegawai').hide();
                    $('#btn-delete-absensi-pegawai').hide();
                }

                function rekap_absensi_pegawai_search_periode() {

                    $('#btn-generate-rekap-absensi-pegawai').hide();
                    $('#rekap-absensi-update-button').hide();
                    $('#btn-delete-absensi-pegawai').hide();
                    $('#btn-generate-rekap-absensi-pegawai').hide();

                    $('#rekap-absensi-pegawai-list-unit-table tbody').empty();

                    $('#rekap-absensi-pegawai-box').html('');
                    $('#rekap-absensi-pegawai-box').append('<table class="table table-bordered table-striped" id="rekap-absensi-pegawai-table"><thead><tr><th>No.</th><th>Pegawai</th><th>Alpha/Hari</th><th>Sakit 1-2/Hari</th><th>Sakit >2/Hari</th><th>Izin/Hari</th><th>Telat/Menit</th><th>Pulang Cepat/Menit</th><th>Hari Kerja</th><th>Aksi</th></tr></thead><tbody></table>');

                    var month = remove_leading_zero($('#rekap-absensi-pegawai-select-month').val());
                    var year = $('#rekap-absensi-pegawai-select-year').val();

                    $.ajax({
                        type: "GET",
                        url: "<?php echo $url_api_absensi; ?>?action=get_rekapitulasi_absensi_pegawai&month=" + month + "&year=" + year,
                        dataType: "JSON",
                        success: function(response) {

                            if (response.status == 1) {

                                // rekap_absensi_pegawai_list_unit(month, year); dimatikan karna akan dibuat yg lebih interaktif dan baik dlm UX

                                $('#rekap-absensi-pegawai-table tbody').empty();
                                for (let i = 0; i < response.data.absensi.length; i++) {
                                    var no = i + 1;

                                    var nip = response.data.absensi[i]['nip'];
                                    var nm_pegawai = response.data.absensi[i]['nama_pegawai'];
                                    var id_unit = response.data.absensi[i]['id_unit'];
                                    var nm_unit = response.data.absensi[i]['nama_unit'];
                                    var nama_level = response.data.absensi[i]['nama_level'];
                                    var pegawai = nm_pegawai + "<br>" + nip + "<br>" + nm_unit + "-" + nama_level;
                                    var jml_alpha = response.data.absensi[i]['k_jml_alpha'];
                                    var jml_sakit_1hr = response.data.absensi[i]['k_jml_sakit_1hari'];
                                    var jml_sakit_2hr = response.data.absensi[i]['k_jml_sakit_2hari'];
                                    var jml_izin = response.data.absensi[i]['k_jml_izin'];
                                    var jml_telat = response.data.absensi[i]['k_jml_telat'];
                                    var jml_plng_cepat = response.data.absensi[i]['k_jml_plng_cepat'];
                                    var jml_harikerja = response.data.absensi[i]['jml_hari_kerja'];
                                    var url_target = response.data.absensi[i]['url_target'];
                                    var aksi = "<a href='?" + url_target + "' class='btn btn-primary btn-block' target='_blank'>Detail</a>";

                                    $('#rekap-absensi-pegawai-table tbody')
                                        .append("<tr><td>" + no + "</td><td>" + pegawai + "</td><td>" + jml_alpha + "</td><td>" + jml_sakit_1hr + "</td><td>" + jml_sakit_2hr + "</td><td>" + jml_izin + "</td><td>" + jml_telat + "</td><td>" + jml_plng_cepat + "</td><td>" + jml_harikerja + "</td><td>" + aksi + "</td></tr>");
                                }

                                $('#rekap-absensi-update-button')
                                    .css('pointer-events', 'auto')
                                    .show();

                                if (!$.fn.dataTable.isDataTable('#rekap-absensi-pegawai-table')) {

                                    $('#rekap-absensi-pegawai-table').dataTable({
                                        "responsive": true,
                                        "autoWidth": true,
                                        "paging": true,
                                        "pageLength": 50
                                    });
                                }

                                // tampilkan atau tidak tombol hapus absensi
                                if (response.status == 1) {
                                    $('#btn-delete-absensi-pegawai').show();
                                    $('#btn-generate-rekap-absensi-pegawai').show();
                                }

                                // ambil list unit yang sudah direkapitulasi
                                get_unit_rekapitulasi_absensi_pegawai(month, year);

                            } else {

                                console.log("Kode REKAPABS01. Kode Status = 0.");
                                $('#btn-generate-rekap-absensi-pegawai').show();
                            }
                        },
                        error: function(errorMsg) {

                            console.log("Kode : REKAPABS01. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
                        }
                    });
                }

                function rekap_absensi_pegawai_list_unit(month, year) {

                    $.ajax({
                        type: "GET",
                        url: "<?php echo $url_api_absensi; ?>?action=get_list_unit_rekapitulasi_absensi_pegawai&month=" + month + "&year=" + year,
                        dataType: "JSON",
                        success: function(response) {

                            if (response.status == 1) {
                                $('#rekap-absensi-pegawai-list-unit-table tbody').empty();

                                for (let i = 0; i < response.data.length; i++) {

                                    var no = i + 1;
                                    var nama_unit = response.data[i]['nama_unit'];
                                    var generate_time = response.data[i]['timestamp_generated'];
                                    var accepted_time = response.data[i]['timestamp_accepted'];

                                    $('#rekap-absensi-pegawai-list-unit-table tbody')
                                        .append("<tr><td>" + no + "</td><td>" + nama_unit + "</td><td>" + generate_time + "</td><td>" + accepted_time + "</td></tr>");
                                }

                                if (!$.fn.dataTable.isDataTable('#rekap-absensi-pegawai-list-unit-table')) {

                                    $('#rekap-absensi-pegawai-list-unit-table').dataTable({
                                        "responsive": true,
                                        "autoWidth": false,
                                        "paging": true,
                                        "pageLength": 10,
                                        "scrollX": true,
                                        "searching": false
                                    });
                                }
                            } else {

                                console.log("Kode : REKAPABSUNT01. Kode status = 0");
                            }
                        },
                        error: function(errorMsg) {

                            console.log("Kode : REKAPABSUNT01. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
                        }
                    });
                }

                function get_status_unit_rekapitulasi_absensi_pegawai() {

                    $('#btn-generate-rekap-absensi-pegawai').html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;Memperiksa Status Absensi Unit").prop('disabled', true);

                    rekapitulasi_absensi_pegawai_table.clear().draw();

                    var month = remove_leading_zero($('#rekap-absensi-pegawai-select-month').val());
                    var year = $('#rekap-absensi-pegawai-select-year').val();

                    $.ajax({
                        type: "GET",
                        url: "<?php echo $url_api_absensi; ?>?action=get_status_unit_rekapitulasi_absensi_pegawai&month=" + month + "&year=" + year,
                        dataType: "JSON",
                        success: function(response) {

                            if (response.status == 1) {

                                for (let i = 0; i < response.data.length; i++) {

                                    var text_color_is_generated = response.data[i]['is_generated'] == 'Sudah' ? 'black' : 'red';
                                    var text_color_is_submitted = response.data[i]['is_submitted'] == 'Sudah' ? 'black' : 'red';
                                    var text_color_kasie_response = response.data[i]['kasie_response'] == 'Sudah Divalidasi' ? 'black' : 'red';
                                    var text_color_validasi_absen = response.data[i]['validasi_absen'] == 'Sudah Ditarik' ? 'black' : 'red';

                                    var id_unit = response.data[i]['id_unit'];
                                    var nama_unit = "<p style='color: " + text_color_kasie_response + ";'>" + response.data[i]['nama_unit'] + "</p>";
                                    var is_generated = "<p style='color: " + text_color_is_generated + ";'>" + response.data[i]['is_generated'] + "</p>";
                                    var is_submitted = "<p style='color: " + text_color_is_submitted + ";'>" + response.data[i]['is_submitted'] + "</p>";
                                    var kasie_response = "<p style='color: " + text_color_kasie_response + ";'>" + response.data[i]['kasie_response'] + "</p>";
                                    var validasi_absen = "<p style='color: " + text_color_validasi_absen + ";'>" + response.data[i]['validasi_absen'] + "</p>";
                                    var opt_tarik_absensi_true = response.data[i]['kasie_response'] == 'Sudah Divalidasi' && response.data[i]['validasi_absen'] != 'Sudah Ditarik' ? 'checked' : ''; // opsi tarik absensi akan aktif apabila sudah divalidasi namun belum ditarik
                                    var opt_tarik_absensi = "<input type='checkbox' id='opt_tarik_absensi' name='opt_tarik_absensi' value='Ya' class='js-switch' " + opt_tarik_absensi_true + " onchange='checkbox_rekap_absensi_pegawai_tariabsen_event(this, `" + id_unit + "`);' /> Ya";
                                    var btn_select_pegawai = "<button type='button' class='btn btn-xs btn-primary' id='btn_list_pegawai-tarik_absensi-"+id_unit+"' name='btn_list_pegawai-tarik_absensi-"+id_unit+"' onclick='get_status_pegawai_rekapitulasi_absensi_pegawai(`"+id_unit+"`, `"+month+"`, `"+year+"`);'><i class='fa fa-search'></i></button><input id='list_pegawai-tarik_absensi-"+id_unit+"' name='list_pegawai-tarik_absensi-"+id_unit+"' type='hidden'/>";

                                    // jika opsi tarik absen true, masukkan ke list unit yg akan digenerate
                                    if (opt_tarik_absensi_true) rekap_absensi_pegawai_list_unit_shift_generated.push(id_unit);

                                    rekapitulasi_absensi_pegawai_table.row.add([
                                        (i + 1),
                                        nama_unit,
                                        is_generated,
                                        is_submitted,
                                        kasie_response,
                                        validasi_absen,
                                        opt_tarik_absensi,
                                        btn_select_pegawai
                                    ]).draw();
                                }

                                $('#modal-status-absensi-unit_absensi-pegawai').modal('show');
                                $('#btn-generate-rekap-absensi-pegawai').html("Lihat Status Absensi Unit").prop('disabled', false);
                            } else {

                                swal("Data Status Absensi Unit Tidak Tersedia", "", "error");
                                $('#btn-generate-rekap-absensi-pegawai').html("Lihat Status Absensi Unit").prop('disabled', false);
                                console.log("Kode : GETSTSUNITABS01. Kode Status = 0");
                            }
                        },
                        error: function(error) {

                            swal("Gagal Mengirim Permintaan Lihat Status Absensi Unit", "", "error");
                            $('#btn-generate-rekap-absensi-pegawai').html("Lihat Status Absensi Unit").prop('disabled', false);
                            console.log("Kode : GETSTSUNITABS01. " + error.status + "-" + error.statusText);
                        }
                    });
                }

                function get_unit_rekapitulasi_absensi_pegawai(month, year) {

                    $('#rekap-absensi-pegawai-list-unit-table tbody').empty();

                    $.ajax({
                        type: "GET",
                        url: "<?php echo $url_api_absensi; ?>?action=get_unit_rekapitulasi_absensi_pegawai&month=" + month + "&year=" + year,
                        dataType: "JSON",
                        success: function(response) {

                            if (response.status == 1) {

                                for (let i = 0; i < response.data.length; i++) {

                                    var tbody_value = "<tr><td>" + (i + 1) + "</td><td>" + response.data[i]['nama_unit'] + "</td><td>" + response.data[i]['created'] + "</td></tr>";

                                    $('#rekap-absensi-pegawai-list-unit-table tbody').append(tbody_value);
                                }

                                if (!$.fn.dataTable.isDataTable('#rekap-absensi-pegawai-list-unit-table')) {

                                    $('#rekap-absensi-pegawai-list-unit-table').dataTable({
                                        "responsive": true,
                                        "autoWidth": true,
                                        "paging": true,
                                        "pageLength": 10
                                    });
                                }
                            } else {

                                console.log('Kode : GETUNTREKAPABS01. Kode Status = 0');
                            }
                        },
                        error: function(error) {

                            console.log('Kode : GETUNTREKAPABS01. Gagal mengirim permintaan. ' + error.status + '-' + error.statusText);
                        }
                    });
                }

                function get_status_pegawai_rekapitulasi_absensi_pegawai(id_unit, month, year) {
                    
                    swal({
                        // Menambahkan CSS inline di dalam tag style agar SweetAlert menjadi lebar dan tinggi
                        title: '<style>' +
                            '.sweet-alert { width: 45vw !important; height: 45vh !important; margin-left: 0 !important; left: 2.5vw !important; }' +
                            '.sweet-alert .form-control { height: auto !important; }' + 
                            '</style>' +
                            '<h5>Pilih Pegawai (KOSONGKAN jika ingin semua pegawai)</h5>',
                        text: '<select id="pilih-pegawai" class="form-control" multiple="multiple" style="width:100%"></select>',
                        html: true,
                        showCancelButton: true,
                        confirmButtonText: "Simpan",
                    }, function() {
                        
                        var selectedIDs = $('#pilih-pegawai').val(); 

                        if (!selectedIDs || selectedIDs.length === 0) {
                            $('#btn_list_pegawai-tarik_absensi-'+id_unit).removeClass('btn-warning').addClass('btn-primary');
                            $('#list_pegawai-tarik_absensi-'+id_unit).val('');
                        } else {
                            $('#btn_list_pegawai-tarik_absensi-'+id_unit).removeClass('btn-primary').addClass('btn-warning');
                            $('#list_pegawai-tarik_absensi-'+id_unit).val(selectedIDs.join(','));
                        }
                    });

                    setTimeout(function() {
                        $.ajax({
                            url: '<?= $url_api_absensi; ?>?action=get_pegawai_by_idunit_shiftm&id_unit='+id_unit+'&month='+month+'&year='+year,
                            type: 'GET',
                            dataType: 'JSON',
                            success: function(response) {

                                if(response.status == 0) {
                                    $('#pilih-pegawai').select2({
                                        dropdownParent: $(".sweet-alert"),
                                        placeholder: "Data Pegawai tidak ditemukan",
                                        data: []
                                    });
                                } else {
                                    const dataPegawai = $.map(response.data, function(item) {
                                        return {
                                            id: item.id_user,
                                            text: item.nama_pegawai
                                        };
                                    });

                                    $('#pilih-pegawai').select2({
                                        dropdownParent: $('.sweet-alert'),
                                        placeholder: "Cari...",
                                        data: dataPegawai
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log('ERROR get_status_pegawai_rekapitulasi_absensi_pegawai. ', error);
                                $('#pilih-pegawai').select2({
                                    dropdownParent: $(".sweet-alert"),
                                    placeholder: "Gagal Memuat Data Pegawai",
                                    data: []
                                });
                            }
                        });
                    }, 500);
                }

                function update_absensi_pegawai(id_kepegawaian) {

                    $('#rekap-absensi-update-button').html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>").css('pointer-events', 'none');

                    $('#modal-warning-update-absensi-pegawai').modal('hide');

                    var month = remove_leading_zero($('#rekap-absensi-pegawai-select-month').val());
                    var year = $('#rekap-absensi-pegawai-select-year').val();

                    $.ajax({
                        type: "POST",
                        url: "<?php echo $url_api_absensi; ?>?action=update_absensi_pegawai",
                        data: {
                            id_kepegawaian: id_kepegawaian,
                            month: month,
                            year: year
                        },
                        dataType: "JSON",
                        success: function(response) {

                            swal(response.message, "", "success");
                            $("#rekap-absensi-update-button").html('<i class="fa fa-send">&nbsp;&nbsp;&nbsp;PERBARUI</i>').css('pointer-events', 'auto');
                        },
                        error: function(errorMsg) {

                            $("#rekap-absensi-update-button").html('<i class="fa fa-send">&nbsp;&nbsp;&nbsp;PERBARUI</i>').css('pointer-events', 'auto');
                            swal("Update Rekapitulasi Absensi Pegawai Gagal", "", "error");
                            console.log("Kode : UPDATEABSPG01. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
                        }
                    });
                }

                function get_status_duplikat_absensi_pegawai() {

                    $('#btn-generate-rekap-absensi-pegawai').html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;Memeriksa Duplikat Data").prop('disabled', true);
                    $('#modal-status-absensi-unit_absensi-pegawai').modal('hide');

                    var month = remove_leading_zero($('#rekap-absensi-pegawai-select-month').val());
                    var year = $('#rekap-absensi-pegawai-select-year').val();
                    var list_unit_serialized = JSON.stringify(rekap_absensi_pegawai_list_unit_shift_generated);

                    $.ajax({
                        type: "POST",
                        url: "<?php echo $url_api_absensi; ?>?action=get_status_duplikat_absensi_pegawai",
                        dataType: "JSON",
                        data: {
                            month: month,
                            year: year,
                            list_unit_serialized: list_unit_serialized
                        },
                        success: function(response) {

                            if (response.status == 1) {

                                console.log('get_status_duplikat_absensi_pegawai. count =  1');

                                var data = "";
                                for (var i = 0; i < response.data.length; i++) {

                                    data += response.data[i].nama_pegawai + " (" + response.data[i].nama_unit + ")\n";
                                }


                                $('#btn-generate-rekap-absensi-pegawai').html("Lihat Status Absensi Unit").prop('disabled', false);
                                swal("Duplikat Jadwal Absensi Ditemukan", data, "error");
                            } else {
                                console.log('get_status_duplikat_absensi_pegawai. count =  0');
                                delete_rekap_detail_absensi_pegawai();
                                console.log('get_status_duplikat_absensi_pegawai. count =  999');
                            }
                        },
                        error: function(error) {

                            $('#btn-generate-rekap-absensi-pegawai').html("Lihat Status Absensi Unit").prop('disabled', false);
                            swal("Permintaan Pemeriksaan Absensi Duplikat Gagal", "", "error");
                            console.log("Kode : CHKDPLKTRKPABS01. Gagal mengirim permintaan. " + error.status + "-" + error.statusText);
                        }
                    });
                }

                function delete_rekap_detail_absensi_pegawai() {

                    $('#btn-generate-rekap-absensi-pegawai').html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;Pembersihan Data Duplikat").prop('disabled', true);

                    var month = remove_leading_zero($('#rekap-absensi-pegawai-select-month').val());
                    var year = $('#rekap-absensi-pegawai-select-year').val();
                    var list_unit_serialized = JSON.stringify(rekap_absensi_pegawai_list_unit_shift_generated);
                    const list_user_serialized = JSON.stringify(rekap_absensi_pegawai_list_unit_shift_generated.map(item => {
                        const list_user = rekapitulasi_absensi_pegawai_table.$('#list_pegawai-tarik_absensi-'+item).val();
                        return {
                            id_unit: item,
                            list_user: list_user,
                        };
                    }));

                    $.ajax({
                        type: "POST",
                        url: "<?php echo $url_api_absensi; ?>?action=delete_absensi_pegawai",
                        dataType: "JSON",
                        data: {
                            year: year,
                            month: month,
                            list_unit_serialized: list_unit_serialized,
                            list_pegawai_serialized: list_user_serialized
                        },
                        success: function(response) {
                            console.log('delete_rekap_detail_absensi_pegawai success');
                            generate_listuser_rekapirulasi_absensi_pegawai_shift();
                        },
                        error: function(error) {

                            $('#btn-generate-rekap-absensi-pegawai').html("Lihat Status Absensi Unit").prop('disabled', false);
                            swal("Permintaan Pembersihan Absensi Duplikat Gagal", "", "error");
                            console.log("Kode : DLTRKPABS01. Gagal mengirim permintaan. " + error.status + "-" + error.statusText);
                        }
                    });
                }

                function delete_rekap_detail_absensi_pegawai_button() {

                    $('#modal-hapus-absensi_shift').modal('hide');
                    $('#btn-delete-absensi-pegawai').html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;Menghapus Data Absensi").prop('disabled', true);

                    var month = remove_leading_zero($('#rekap-absensi-pegawai-select-month').val());
                    var year = $('#rekap-absensi-pegawai-select-year').val();
                    $.ajax({
                        type: "POST",
                        url: "<?php echo $url_api_absensi; ?>?action=delete_absensi_pegawai_by_date",
                        dataType: "JSON",
                        data: {
                            year: year,
                            month: month
                        },
                        success: function(response) {

                            console.log('delete_rekap_detail_absensi_pegawai_button success');
                            $('#btn-delete-absensi-pegawai').html("Hapus Rekapitulasi Absensi").prop('disabled', false);
                            rekap_absensi_pegawai_search_periode();
                        },
                        error: function(error) {

                            $('#btn-delete-absensi-pegawai').html("Hapus Rekapitulasi Absensi").prop('disabled', false);
                            swal("Permintaan Penghapusan Absensi", "", "error");
                            console.log("Kode : DLTRKPABSBTN01. Gagal mengirim permintaan. " + error.status + "-" + error.statusText);
                        }
                    });
                }

                function show_modal_hapus_absensi_pegawai() {
                    $('#modal-hapus-absensi_shift').modal('show');
                }

                function generate_listuser_rekapirulasi_absensi_pegawai_shift() {

                    $('#btn-generate-rekap-absensi-pegawai').html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;Generate List Pegawai Rekapitulasi Absensi").prop('disabled', true);

                    var month = remove_leading_zero($('#rekap-absensi-pegawai-select-month').val());
                    var year = $('#rekap-absensi-pegawai-select-year').val();
                    var id_kepegawaian = $('#rekap-absensi-pegawai-id_kepegawaian').val();
                    var list_unit_serialized = JSON.stringify(rekap_absensi_pegawai_list_unit_shift_generated);

                    $.ajax({
                        type: "POST",
                        url: "<?php echo $url_api_absensi; ?>?action=generate_listuser_rekapitulasi_shift_absensi_pegawai",
                        dataType: "JSON",
                        data: {
                            id_kepegawaian: id_kepegawaian,
                            month: month,
                            year: year,
                            list_unit_serialized: list_unit_serialized
                        },
                        success: function(response) {

                            console.log('generate_listuser_rekapirulasi_absensi_pegawai_shift success. id_unit.length ' + response.list_id_unit.length + ' id_user.length ' + response.list_id_user.length + ' id_session ' + response.id_session);

                            // jika list_unit dan list_user kosong & generate_spesialis true
                            // berarti langsung generate_spesialis
                            if (response.list_id_unit.length == 0 && response.list_id_user.length == 0 && response.generate_unit_spesialis == true) {

                                rekap_absensi_pegawai_id_session = response.id_session;
                                rekap_absensi_pegawai_generate_unit_spesialis = response.generate_unit_spesialis;
                                generate_rekapdetail_absensi_drspesialis();
                            } else {

                                // kalau tidak berarti generate dari list dahulu, baru generate_spesialis
                                rekap_absensi_pegawai_list_unit_shift_generated = [...response.list_id_unit]; // list unit diperbarui
                                rekap_absensi_pegawai_list_unit_shift_generated_2 = [...response.list_id_unit]; // list unit diperbarui

                                // filter hasil dari response berdasarkan list_user yang dipilih user
                                rekap_absensi_pegawai_list_unit_shift_generated_2.forEach(element => {

                                    // jika kosong, berarti user mau tarik semua pegawai. ambil apa adanya dari server
                                    // jika tidak kosong, kita filter yg ada di kedua sisi
                                    if(rekapitulasi_absensi_pegawai_table.$('#list_pegawai-tarik_absensi-'+element).val() != '') {

                                        const list_user = rekapitulasi_absensi_pegawai_table.$('#list_pegawai-tarik_absensi-'+element).val().split(",");
                                        // console.log('list_user ');
                                        // console.log(list_user);
                                        // rekap_absensi_pegawai_list_user_shift_generated = response.list_id_user.filter(item => list_user.includes(item.id_user));
                                        rekap_absensi_pegawai_list_user_shift_generated.push(response.list_id_user.filter(item => list_user.includes(item.id_user))[0]);
                                        // console.log('rekap a: ');
                                        // console.log(rekap_absensi_pegawai_list_user_shift_generated);
                                    } else {
                                        
                                        rekap_absensi_pegawai_list_user_shift_generated = response.list_id_user;
                                    }
                                });

                                // console.log('rekap: ');
                                // console.log(rekap_absensi_pegawai_list_user_shift_generated);
                                rekap_absensi_pegawai_id_session = response.id_session;
                                rekap_absensi_pegawai_generate_unit_spesialis = response.generate_unit_spesialis;
                                generate_rekapitulasi_absensi_pegawai_shift();
                            }

                        },
                        error: function(error) {

                            $('#btn-generate-rekap-absensi-pegawai').html("Lihat Status Absensi Unit").prop('disabled', false);
                            swal("Permintaan Generate List Pegawai Gagal", "", "error");
                            console.log("Kode : GNRTLISTUSERREKAPABS01. Gagal mengirim permintaan. " + error.status + "-" + error.statusText);
                        }
                    });
                }

                function generate_rekapitulasi_absensi_pegawai_shift() {

                    console.log('generate_rekapitulasi_absensi_pegawai_shift ' + rekap_absensi_pegawai_list_user_shift_generated.length);
                    $('#btn-generate-rekap-absensi-pegawai').html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;Generate Rekapitulasi Absensi. Sisa : " + rekap_absensi_pegawai_list_user_shift_generated.length + " pegawai").prop('disabled', true);

                    var month = remove_leading_zero($('#rekap-absensi-pegawai-select-month').val());
                    var year = $('#rekap-absensi-pegawai-select-year').val();
                    var id_kepegawaian = $('#rekap-absensi-pegawai-id_kepegawaian').val();

                    // jika unit nya adalah UNT-000003 (spesialis). hapus dan skip saja. biar nanti dicek lewat generate_detail_absensi_pegawai_shift
                    if(rekap_absensi_pegawai_list_user_shift_generated[0]['id_unit'] == 'UNT-000003') {

                        rekap_absensi_pegawai_list_user_shift_generated.splice(0, 1);
                        generate_rekapitulasi_absensi_pegawai_shift();
                    } else {
                        console.log(rekap_absensi_pegawai_list_user_shift_generated);
                        console.log(rekap_absensi_pegawai_list_user_shift_generated[0]['id_user']);

                        console.log(rekap_absensi_pegawai_list_user_shift_generated[0]['id_unit']);

                        $.ajax({
                            type: "POST",
                            url: "<?php echo $url_api_absensi; ?>?action=generate_rekapitulasi_shift_absensi_pegawai",
                            dataType: "JSON",
                            data: {
                                id_kepegawaian: id_kepegawaian,
                                month: month,
                                year: year,
                                id_user: rekap_absensi_pegawai_list_user_shift_generated[0]['id_user'],
                                id_unit: rekap_absensi_pegawai_list_user_shift_generated[0]['id_unit'],
                                id_session: rekap_absensi_pegawai_id_session
                            },
                            success: function(response) {

                                // hapus list_id_uesr yang baru dieksekusi.
                                rekap_absensi_pegawai_list_user_shift_generated.splice(0, 1);

                                // apabila list id_unit masih tersisa untuk diekseskusi. maka diulangi
                                if (rekap_absensi_pegawai_list_user_shift_generated.length > 0) generate_rekapitulasi_absensi_pegawai_shift();
                                else generate_detail_absensi_pegawai_shift();
                            },
                            error: function(error) {

                                $('#btn-generate-rekap-absensi-pegawai').html("Lihat Status Absensi Unit").prop('disabled', false);
                                swal("Permintaan Generate Absensi Shift Gagal", "", "error");
                                console.log("Kode : GNRTREKAPABS01. Gagal mengirim permintaan. " + error.status + "-" + error.statusText);
                            }
                        });
                    }
                }

                function generate_detail_absensi_pegawai_shift() {

                    console.log('generate_detail_absensi_pegawai_shift init ' + rekap_absensi_pegawai_list_unit_shift_generated.length)
                    $('#btn-generate-rekap-absensi-pegawai').html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;Generate Detail Absensi. Sisa : " + rekap_absensi_pegawai_list_unit_shift_generated.length + " unit").prop('disabled', true);

                    var month = remove_leading_zero($('#rekap-absensi-pegawai-select-month').val());
                    var year = $('#rekap-absensi-pegawai-select-year').val();
                    var id_kepegawaian = $('#rekap-absensi-pegawai-id_kepegawaian').val();
                    const list_pegawai = rekapitulasi_absensi_pegawai_table.$('#list_pegawai-tarik_absensi-'+rekap_absensi_pegawai_list_unit_shift_generated[0]).val();
                    console.log('generate_detail_absensi_pegawai_shift month ' + month);
                    console.log('generate_detail_absensi_pegawai_shift year ' + year);
                    console.log('generate_detail_absensi_pegawai_shift id_kepegawaian ' + id_kepegawaian);
                    console.log('generate_detail_absensi_pegawai_shift id_unit ' + rekap_absensi_pegawai_list_unit_shift_generated[0]);
                    console.log('generate_detail_absensi_pegawai_shift list_pegawai ' + list_pegawai);

                    $.ajax({
                        type: "POST",
                        url: "<?php echo $url_api_absensi ?>?action=generate_detail_shift_absensi_pegawai",
                        dataType: "JSON",
                        data: {
                            id_kepegawaian: id_kepegawaian,
                            month: month,
                            year: year,
                            id_unit: rekap_absensi_pegawai_list_unit_shift_generated[0],
                            list_pegawai: list_pegawai
                        },
                        success: function(response) {

                            if (response.status == 1) {

                                console.log('generate_detail_absensi_pegawai_shift success ' + rekap_absensi_pegawai_list_unit_shift_generated[0]);
                                // hapus id_unit yang baru dieksekusi.
                                rekap_absensi_pegawai_list_unit_shift_generated.splice(0, 1);

                                // apabila list id_unit masih tersisa untuk diekseskusi. maka diulangi
                                if (rekap_absensi_pegawai_list_unit_shift_generated.length > 0) generate_detail_absensi_pegawai_shift();
                                else if (rekap_absensi_pegawai_generate_unit_spesialis) generate_rekapdetail_absensi_drspesialis(); // jika user ternyata melalukan permintaan rekap absen spesialis. tarik dahulu
                                else insert_log_absensi_pegawai();
                            } else {

                                $('#btn-generate-rekap-absensi-pegawai').html("Lihat Status Absensi Unit").prop('disabled', false);
                                swal("Generate Detail Shift Gagal. Id : " + rekap_absensi_pegawai_list_unit_shift_generated[0] + "", "", "error");
                                console.log("Kode : GNRTDETAILABS01. Kode Status = 0.");
                            }
                        },
                        error: function(errorMsg) {

                            $('#btn-generate-rekap-absensi-pegawai').html("Lihat Status Absensi Unit").prop('disabled', false);
                            swal("Generate Detail Shift Gagal", "", "error");
                            console.log("Kode : GNRTDETAILABS01. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
                        }
                    });
                }

                function generate_rekapitulasi_absensi_pegawai_nonshift() {

                    $('#btn-generate-rekap-absensi-pegawai').html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;Generate Rekapitulasi Absensi Non Shift").prop('disabled', true);

                    var month = remove_leading_zero($('#rekap-absensi-pegawai-select-month').val());
                    var year = $('#rekap-absensi-pegawai-select-year').val();
                    var id_kepegawaian = $('#rekap-absensi-pegawai-id_kepegawaian').val();

                    $.ajax({
                        type: "POST",
                        url: "<?php echo $url_api_absensi; ?>?action=generate_rekapitulasi_nonshift_absensi_pegawai",
                        dataType: "JSON",
                        data: {
                            month: month,
                            year: year,
                            id_kepegawaian: id_kepegawaian
                        },
                        success: function(response) {

                            console.log('generate_rekapitulasi_absensi_pegawai_nonshift success');

                            generate_detail_absensi_pegawai_nonshift();
                        },
                        error: function(error) {

                            $('#btn-generate-rekap-absensi-pegawai').html("Lihat Status Absensi Unit").prop('disabled', false);
                            swal("Generate Rekapitulasi Non Shift Gagal", "", "error");
                            console.log("Kode : GNRTREKAPABSNONSHF01. Gagal mengirim permintaan. " + error.status + "-" + error.statusText);
                        }
                    });
                }

                function generate_detail_absensi_pegawai_nonshift() {

                    $('#btn-generate-rekap-absensi-pegawai').html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;Generate Detail Absensi Non Shift").prop('disabled', true);

                    var month = remove_leading_zero($('#rekap-absensi-pegawai-select-month').val());
                    var year = $('#rekap-absensi-pegawai-select-year').val();
                    var id_kepegawaian = $('#rekap-absensi-pegawai-id_kepegawaian').val();

                    $.ajax({
                        type: "POST",
                        url: "<?php echo $url_api_absensi; ?>?action=generate_detail_nonshift_absensi_pegawai",
                        dataType: "JSON",
                        data: {
                            month: month,
                            year: year,
                            id_kepegawaian: id_kepegawaian
                        },
                        success: function(response) {

                            console.log('generate_detail_absensi_pegawai_nonshift success');
                            insert_log_absensi_pegawai();
                        },
                        error: function(error) {

                            $('#btn-generate-rekap-absensi-pegawai').html("Lihat Status Absensi Unit").prop('disabled', false);
                            swal("Generate Detail Non Shift Gagal", "", "error");
                            console.log("Kode : GNRTDETAILABSNONSHF01. Gagal mengirim permintaan. " + error.status + "-" + error.statusText);
                        }
                    });
                }

                function generate_rekapdetail_absensi_drspesialis() {

                    console.log('generate_rekapdetail_absensi_drspesialis init');
                    $('#btn-generate-rekap-absensi-pegawai').html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;Generate Absensi Dokter Spesialis").prop * ('disabled', true);

                    var month = remove_leading_zero($('#rekap-absensi-pegawai-select-month').val());
                    var year = $('#rekap-absensi-pegawai-select-year').val();
                    var id_kepegawaian = $('#rekap-absensi-pegawai-id_kepegawaian').val();

                    $.ajax({
                        url: "<?php echo $url_api_absensi; ?>?action=generate_rekapitulasi_detail_dokter_spesialis_absensi_pegawai",
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            id_kepegawaian: id_kepegawaian,
                            month: month,
                            year: year
                        },
                        success: function(response) {

                            insert_log_absensi_pegawai();
                        },
                        error: function(errorMsg) {

                            $('#btn-generate-rekap-absensi-pegawai').html('Lihat Status Absensi Unit').prop('disabled', false);
                            swal('Generate Absensi Dokter Spesialis Gagal', '', 'error');
                            console.log('Kode : GNRTABSSPESIALIS01. Gagal mengirim permintaan. ' + errorMsg.status + '-' + errorMsg.statusText)
                        }
                    });
                }

                function generate_detail_shift_absensi_pegawai_perunit(button_id) {

                    var id_kepegawaian = $('#rekap-absensi-pegawai-id_kepegawaian').val();
                    var month_selected = $('#rekap-absensi-pegawai-select-month option:selected').val();
                    var year_selected = $('#rekap-absensi-pegawai-select-year option:selected').val();
                    var unit_selected = $('#modal-generate-absensi-shift-perunit_unit option:selected').val();

                    $(button_id).html("Generate Absensi Unit. Sisa " + rekap_absensi_pegawai_list_user_shift_generated.length + " pegawai..").prop('disabled', true);
                    $.ajax({
                        url: "<?= $url_api_absensi; ?>?action=generate_detail_shift_absensi_pegawai",
                        method: "POST",
                        data: {
                            id_kepegawaian: id_kepegawaian,
                            month: month_selected,
                            year: year_selected,
                            id_unit: unit_selected,
                            id_user: rekap_absensi_pegawai_list_user_shift_generated[0]
                        },
                        dataType: "JSON",
                        success: function(response) {

                            // hapus list id_user yg baru dieksekusi
                            rekap_absensi_pegawai_list_user_shift_generated.splice(0, 1);

                            // apabila list id_user masih tersisa untuk dieksekusi. maka diulangi
                            if (rekap_absensi_pegawai_list_user_shift_generated.length > 0) {

                                generate_detail_shift_absensi_pegawai_perunit(button_id);
                            } else {

                                $('#modal-generate-absensi-shift-perunit').modal('hide');
                                swal("Generate Detail Absensi Per Unit Berhasil", "", "success");
                                $(button_id).html("Generate Absensi Unit").prop('disabled', false);
                                rekap_absensi_pegawai_search_periode();
                            }


                        },
                        error: function(error) {

                            $(button_id).html("Generate Absensi Unit").prop('disabled', false);
                            swal("Generate Detail Absensi Per Unit Error", "", "error");
                            console.log("Kode : DETAILABSUNIT01. Gagal mengirim permintaan. " + error.status + "-" + error.statusText);
                        }
                    });
                }

                function insert_log_absensi_pegawai() {

                    $('#btn-generate-rekap-absensi-pegawai').html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;Insert Log Absensi").prop('disabled', true);

                    var month = remove_leading_zero($('#rekap-absensi-pegawai-select-month').val());
                    var year = $('#rekap-absensi-pegawai-select-year').val();
                    var id_kepegawaian = $('#rekap-absensi-pegawai-id_kepegawaian').val();

                    $.ajax({
                        type: "POST",
                        url: "<?php echo $url_api_absensi; ?>?action=insert_log_absensi_pegawai",
                        data: {
                            month: month,
                            year: year,
                            id_kepegawaian: id_kepegawaian
                        },
                        dataType: "JSON",
                        success: function(response) {

                            console.log('insert_log_absensi_pegawai success');
                            $('#btn-generate-rekap-absensi-pegawai').html("Lihat Status Absensi Unit").prop('disabled', false);
                            rekap_cuti();
                        },
                        error: function(error) {

                            $('#btn-generate-rekap-absensi-pegawai').html("Lihat Status Absensi Unit").prop('disabled', false);
                            swal("Insert Log Absensi Pegawai Gagal", "", "error");
                            console.log("Kode : LOGREKAPABS01. Gagal mengirim permintaan. " + error.status + "-" + error.statusText);
                        }
                    });
                }

                function rekap_cuti() {
                    $('#btn-generate-rekap-absensi-pegawai').html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;Rekap Cuti. Sisa : " + rekap_absensi_pegawai_list_unit_shift_generated_2.length + " pegawai").prop('disabled', true);
                    $('#modal-status-absensi-unit_absensi-pegawai').modal('hide')

                    const month = remove_leading_zero($('#rekap-absensi-pegawai-select-month').val());
                    const year = $('#rekap-absensi-pegawai-select-year').val();
                    const id_unit = rekap_absensi_pegawai_list_unit_shift_generated_2[0];

                    console.log("rekap absen unit "+id_unit);
                    $.ajax({
                        type: "POST",
                        url: "<?php echo $url_api_absensi; ?>?action=rekap_cuti",
                        data: {
                            month: month,
                            year: year,
                            id_unit: id_unit
                        },
                        dataType: "JSON",
                        success: function(response) {

                            if(response.status == 1) {
                                // hapus id_unit yang baru dieksekusi
                                rekap_absensi_pegawai_list_unit_shift_generated_2.splice(0, 1);

                                // apabila list masih ada, ulangi rekap_cuti. jika habis, lanjut proses selanjutnya
                                if(rekap_absensi_pegawai_list_unit_shift_generated_2.length > 0) {

                                    rekap_cuti();
                                } else {

                                    console.log('rekap_cuti success');
                                    $('#btn-generate-rekap-absensi-pegawai').html("Lihat Status Absensi Unit").prop('disabled', false);
                                    rekap_absensi_pegawai_search_periode();
                                }
                            } else {

                                $('#btn-generate-rekap-absensi-pegawai').html("Lihat Status Absensi Unit").prop('disabled', false);
                                swal("Rekap Cuti Gagal. Id : " + rekap_absensi_pegawai_list_unit_shift_generated_2[0] + "", "", "error");
                                console.log("Kode : REKAPCUTI01. Kode Status = 0.");
                            }
                        },
                        error: function(error) {

                            $('#btn-generate-rekap-absensi-pegawai').html("Lihat Status Absensi Unit").prop('disabled', false);
                            swal("Rekap Cuti Gagal", "", "error");
                            console.log("Kode : LOGREKAPCUTI01. Gagal mengirim permintaan. " + error.status + "-" + error.statusText);
                        }
                    });
                }

                // end fungsi untuk rekap-absensi-pegawai

                // fungsi untuk detail-absensi-pegawai
                function modaleditshift_options_shift(selected) {

                    $('#detail-absensi-pegawai_edit-shift_ketidakhadiran-option').prop('selectedIndex', 0);

                }

                function modaleditshift_options_shifttipe(selected) {

                    $('#detail-absensi-pegawai_edit-shift_ketidakhadiran-option').prop('selectedIndex', 0);
                }

                function modaleditshift_options_ketidakhadiran(selected) {

                    $('#detail-absensi-pegawai_edit-shift_shift-option').prop('selectedIndex', 0);
                    $('#detail-absensi-pegawai_edit-shift_shift-tipe-option').prop('selectedIndex', 0);
                }

                function modaleditshift_options_harilibur(selected) {

                    $('#detail-absensi-pegawai_edit-shift_shift-option').prop('selectedIndex', 0);
                    $('#detail-absensi-pegawai_edit-shift_shift-tipe-option').prop('selectedIndex', 0);
                    $('#detail-absensi-pegawai_edit-shift_ketidakhadiran-option').prop('selectedIndex', 0);
                }

                function modaleditabsensimasuk_list_event(selected) {

                    $('#detail-absensi-pegawai_edit_absensimasuk_selected').val(selected.value);
                }

                function modaleditabsensimasuk_date_event(selected) {

                    var time = $('#detail-absensi-pegawai_edit_absensimasuk_time-new').val();
                    $('#detail-absensi-pegawai_edit_absensimasuk_selected').val(selected.value + " " + time);
                }

                function modaleditabsensimasuk_time_event(selected) {

                    var date = $('#detail-absensi-pegawai_edit_absensimasuk_date-new').val();
                    $('#detail-absensi-pegawai_edit_absensimasuk_selected').val(date + " " + selected.value);
                }

                function modaleditabsensipulang_list_event(selected) {

                    $('#detail-absensi-pegawai_edit_absensipulang_selected').val(selected.value);
                }

                function modaleditabsensipulang_date_event(selected) {

                    var time = $('#detail-absensi-pegawai_edit_absensipulang_time-new').val();
                    console.log('date pulang selected ' + selected.value + " " + time);
                    $('#detail-absensi-pegawai_edit_absensipulang_selected').val(selected.value + " " + time);
                }

                function modaleditabsensipulang_time_event(selected) {

                    var date = $('#detail-absensi-pegawai_edit_absensipulang_date-new').val();
                    console.log('time pulang selected ' + date + " " + selected.value);
                    $('#detail-absensi-pegawai_edit_absensipulang_selected').val(date + " " + selected.value);
                }

                function modal_detail_absensi_pegawai_edit_shift(id_kepegawaian, id_unit, id_user, log_finger, nip, nama_pegawai, unit, level_user, date, month, year, id_absensi, id_absensi_tipe, id_ketidakhadiran) {

                    $.ajax({
                        type: "GET",
                        url: "<?php echo $url_api_absensi; ?>?action=get_options_edit_shift_detail_absensi_pegawai",
                        dataType: "JSON",
                        success: function(response) {

                            if (response.status == 1) {

                                $("#detail-absensi-pegawai_edit-shift_profil").html("");
                                $("#detail-absensi-pegawai_edit-shift_profil").append("<tr><td>Tanggal</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + date + "</td></tr>");
                                $("#detail-absensi-pegawai_edit-shift_profil").append("<tr><td>Nama Pegawai</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + nama_pegawai + "</td></tr>");
                                $("#detail-absensi-pegawai_edit-shift_profil").append("<tr><td>NIP</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + nip + "</td></tr>");
                                $("#detail-absensi-pegawai_edit-shift_profil").append("<tr><td>Unit</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + (unit + " - " + level_user) + "</td></tr>");

                                $("#detail-absensi-pegawai_edit-shift_id-kepegawaian").val(id_kepegawaian);
                                $("#detail-absensi-pegawai_edit-shift_id-unit").val(id_unit);
                                $("#detail-absensi-pegawai_edit-shift_id-user").val(id_user);
                                $("#detail-absensi-pegawai_edit-shift_log-finger").val(log_finger);
                                $("#detail-absensi-pegawai_edit-shift_month").val(month);
                                $("#detail-absensi-pegawai_edit-shift_year").val(year);
                                $("#detail-absensi-pegawai_edit-shift_date").val(date);

                                $('#detail-absensi-pegawai_edit-shift_shift-option').empty();
                                $('#detail-absensi-pegawai_edit-shift_shift-option').append(new Option(
                                    '-',
                                    '',
                                    false,
                                    false
                                ));
                                for (let i = 0; i < response.data.shift_options.length; i++) {

                                    var selected = response.data.shift_options[i]['id_absensi'] == id_absensi;
                                    $('#detail-absensi-pegawai_edit-shift_shift-option').append(new Option(
                                        response.data.shift_options[i]['id_absensi'] + " - " + response.data.shift_options[i]['nama_shift'] + " - " + response.data.shift_options[i]['desc_shift'] + " (" + response.data.shift_options[i]['jam_masuk'] + "-" + response.data.shift_options[i]['jam_pulang'] + ")",
                                        response.data.shift_options[i]['id_absensi'],
                                        selected,
                                        selected
                                    ));
                                }

                                $('#detail-absensi-pegawai_edit-shift_shift-tipe-option').empty();
                                $('#detail-absensi-pegawai_edit-shift_shift-tipe-option').append(new Option(
                                    '-',
                                    '',
                                    false,
                                    false
                                ));
                                for (let i = 0; i < response.data.shift_tipe_options.length; i++) {

                                    var selected = response.data.shift_tipe_options[i]['id_absensi_tipe'] == id_absensi_tipe;
                                    $('#detail-absensi-pegawai_edit-shift_shift-tipe-option').append(new Option(
                                        response.data.shift_tipe_options[i]['id_absensi_tipe'] + " - " + response.data.shift_tipe_options[i]['nama_shift_tipe'] + " - " + response.data.shift_tipe_options[i]['desc_shift_tipe'],
                                        response.data.shift_tipe_options[i]['id_absensi_tipe'],
                                        selected,
                                        selected
                                    ));
                                }

                                $('#detail-absensi-pegawai_edit-shift_ketidakhadiran-option').empty();
                                $('#detail-absensi-pegawai_edit-shift_ketidakhadiran-option').append(new Option(
                                    '-',
                                    '',
                                    false,
                                    false
                                ));
                                for (let i = 0; i < response.data.ketidakhadiran_options.length; i++) {

                                    var selected = response.data.ketidakhadiran_options[i]['id_ketidakhadiran'] == id_ketidakhadiran;
                                    $('#detail-absensi-pegawai_edit-shift_ketidakhadiran-option').append(new Option(
                                        response.data.ketidakhadiran_options[i]['id_ketidakhadiran'] + " - " + response.data.ketidakhadiran_options[i]['nama_ketidakhadiran'] + " - " + response.data.ketidakhadiran_options[i]['desc_ketidakhadiran'],
                                        response.data.ketidakhadiran_options[i]['id_ketidakhadiran'],
                                        selected,
                                        selected
                                    ));
                                }

                                $('#detail-absensi-pegawai_edit-shift').modal('show');
                            } else {

                                swal("Kode : MDLPUTABSDTL2. Gagal membuka modal.", "", "error");
                                console.log("Kode : MDLPUTABSDTL1. Kode status = 0");
                            }
                        },
                        error: function(errorMsg) {

                            console.log("Kode : MDLPUTABSDTL1. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
                        }
                    });
                }

                function modal_detail_absensi_pegawai_edit_absensimasuk(id_kepegawaian, nama_shift, nama_shift_tipe, absensi_masuk, id_unit, id_user, log_finger, nip, nama_pegawai, unit, level_user, date, month, year) {

                    $.ajax({
                        type: "GET",
                        url: "<?php echo $url_api_absensi; ?>?action=get_log_kehadiran_by_logfinger_date&log_finger=" + log_finger + "&date=" + date + "&id_unit=" + id_unit + "&id_user=" + id_user,
                        dataType: "JSON",
                        success: function(response) {

                            if (response.status == 1) {

                                $('#detail-absensi-pegawai_edit_absensimasuk_profile').html("");
                                $('#detail-absensi-pegawai_edit_absensimasuk_profile').append("<tr><td>Tanggal</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + date + "</td></tr>");
                                $('#detail-absensi-pegawai_edit_absensimasuk_profile').append("<tr><td>Nama Pegawai</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + nama_pegawai + "</td></tr>");
                                $('#detail-absensi-pegawai_edit_absensimasuk_profile').append("<tr><td>NIP</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + nip + "</td></tr>");
                                $('#detail-absensi-pegawai_edit_absensimasuk_profile').append("<tr><td>Unit</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + (unit + " - " + level_user) + "</td></tr>");
                                $('#detail-absensi-pegawai_edit_absensimasuk_profile').append("<tr><td>Shift</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + nama_shift + "</td></tr>");
                                $('#detail-absensi-pegawai_edit_absensimasuk_profile').append("<tr><td>Tipe Shift</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + nama_shift_tipe + "</td></tr>");
                                $('#detail-absensi-pegawai_edit_absensimasuk_profile').append("<tr><td>Absensi Masuk</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + absensi_masuk + "</td></tr>");

                                $('#detail-absensi-pegawai_edit_absensimasuk_id-kepegawaian').val(id_kepegawaian);
                                $('#detail-absensi-pegawai_edit_absensimasuk_id-user').val(id_user);
                                $('#detail-absensi-pegawai_edit_absensimasuk_id-unit').val(id_unit);
                                $('#detail-absensi-pegawai_edit_absensimasuk_month').val(month);
                                $('#detail-absensi-pegawai_edit_absensimasuk_year').val(year);
                                $('#detail-absensi-pegawai_edit_absensimasuk_date').val(date);

                                $('#detail-absensi-pegawai_edit_absensimasuk_list').empty();
                                for (var i = 0; i < response.list_log.length; i++) {

                                    var selected = response.list_log[i]['tanggal'] == absensi_masuk;
                                    $('#detail-absensi-pegawai_edit_absensimasuk_list').append(new Option(
                                        response.list_log[i]['tanggal'],
                                        response.list_log[i]['tanggal'],
                                        selected,
                                        selected
                                    ));
                                }

                                $('#detail-absensi-pegawai_edit_absensimasuk').modal('show');

                            } else {

                                swal("Modal Absensi Masuk Pegawai Gagal ", "Kode : MDLPUTABSMSK02. Kode status = 0", "error");
                                console.log("Kode : MDLPUTABSMSK02. Kode status = 0");
                            }
                        },
                        error: function(errorMsg) {

                            swal("Modal Absensi Masuk Pegawai Gagal ", "Kode : MDLPUTABSMSK01. Gagal mengirim permitaan", "error");
                            console.log("Kode : MDLPUTABSMSK01. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
                        }
                    });
                }

                function modal_detail_absensi_pegawai_edit_absensipulang(id_kepegawaian, nama_shift, nama_shift_tipe, absensi_pulang, id_unit, id_user, log_finger, nip, nama_pegawai, unit, level_user, date, month, year) {

                    $.ajax({
                        type: "GET",
                        url: "<?php echo $url_api_absensi; ?>?action=get_log_kehadiran_by_logfinger_date&log_finger=" + log_finger + "&date=" + date + "&id_unit=" + id_unit + "&id_user=" + id_user,
                        dataType: "JSON",
                        success: function(response) {

                            if (response.status == 1) {

                                $('#detail-absensi-pegawai_edit_absensipulang_profile').html("");
                                $('#detail-absensi-pegawai_edit_absensipulang_profile').append("<tr><td>Tanggal</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + date + "</td></tr>");
                                $('#detail-absensi-pegawai_edit_absensipulang_profile').append("<tr><td>Nama Pegawai</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + nama_pegawai + "</td></tr>");
                                $('#detail-absensi-pegawai_edit_absensipulang_profile').append("<tr><td>NIP</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + nip + "</td></tr>");
                                $('#detail-absensi-pegawai_edit_absensipulang_profile').append("<tr><td>Unit</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + (unit + " - " + level_user) + "</td></tr>");
                                $('#detail-absensi-pegawai_edit_absensipulang_profile').append("<tr><td>Shift</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + nama_shift + "</td></tr>");
                                $('#detail-absensi-pegawai_edit_absensipulang_profile').append("<tr><td>Tipe Shift</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + nama_shift_tipe + "</td></tr>");
                                $('#detail-absensi-pegawai_edit_absensipulang_profile').append("<tr><td>Absensi Pulang</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + absensi_pulang + "</td></tr>");

                                $('#detail-absensi-pegawai_edit_absensipulang_id-kepegawaian').val(id_kepegawaian);
                                $('#detail-absensi-pegawai_edit_absensipulang_id-user').val(id_user);
                                $('#detail-absensi-pegawai_edit_absensipulang_id-unit').val(id_unit);
                                $('#detail-absensi-pegawai_edit_absensipulang_month').val(month);
                                $('#detail-absensi-pegawai_edit_absensipulang_year').val(year);
                                $('#detail-absensi-pegawai_edit_absensipulang_date').val(date);

                                $('#detail-absensi-pegawai_edit_absensipulang_list').empty();
                                for (var i = 0; i < response.list_log.length; i++) {

                                    var selected = response.list_log[i]['tanggal'] == absensi_pulang;
                                    $('#detail-absensi-pegawai_edit_absensipulang_list').append(new Option(
                                        response.list_log[i]['tanggal'],
                                        response.list_log[i]['tanggal'],
                                        selected,
                                        selected
                                    ));
                                }

                                $('#detail-absensi-pegawai_edit_absensipulang').modal('show');

                            } else {

                                swal("Modal Absensi Pulang Pegawai Gagal ", "Kode : MDLPUTABSPLG02. Kode status = 0", "error");
                                console.log("Kode : MDLPUTABSPLG02. Kode status = 0");
                            }
                        },
                        error: function(errorMsg) {

                            swal("Modal Absensi Pulang Pegawai Gagal ", "Kode : MDLPUTABSPLG01. Gagal mengirim permitaan", "error");
                            console.log("Kode : MDLPUTABSPLG01. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
                        }
                    });
                }

                function modal_detail_absensi_pegawai_edit_keterlambatan(id_kepegawaian, nama_shift, nama_shift_tipe, absensi_masuk, id_unit, id_user, log_finger, nip, nama_pegawai, unit, level_user, date, month, year) {

                    $.ajax({
                        type: "GET",
                        url: "<?php echo $url_api_absensi; ?>?action=get_log_kehadiran_by_logfinger_date&log_finger=" + log_finger + "&date=" + date + "&id_unit=" + id_unit + "&id_user=" + id_user,
                        dataType: "JSON",
                        success: function(response) {

                            if (response.status == 1) {

                                $('#detail-absensi-pegawai_edit_keterlambatan_profile').html("");
                                $('#detail-absensi-pegawai_edit_keterlambatan_profile').append("<tr><td>Tanggal</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + date + "</td></tr>");
                                $('#detail-absensi-pegawai_edit_keterlambatan_profile').append("<tr><td>Nama Pegawai</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + nama_pegawai + "</td></tr>");
                                $('#detail-absensi-pegawai_edit_keterlambatan_profile').append("<tr><td>NIP</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + nip + "</td></tr>");
                                $('#detail-absensi-pegawai_edit_keterlambatan_profile').append("<tr><td>Unit</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + (unit + " - " + level_user) + "</td></tr>");
                                $('#detail-absensi-pegawai_edit_keterlambatan_profile').append("<tr><td>Shift</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + nama_shift + "</td></tr>");
                                $('#detail-absensi-pegawai_edit_keterlambatan_profile').append("<tr><td>Tipe Shift</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + nama_shift_tipe + "</td></tr>");
                                $('#detail-absensi-pegawai_edit_keterlambatan_profile').append("<tr><td>Absensi Masuk</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + absensi_masuk + "</td></tr>");

                                $('#detail-absensi-pegawai_edit_keterlambatan_id-kepegawaian').val(id_kepegawaian);
                                $('#detail-absensi-pegawai_edit_keterlambatan_id-user').val(id_user);
                                $('#detail-absensi-pegawai_edit_keterlambatan_id-unit').val(id_unit);
                                $('#detail-absensi-pegawai_edit_keterlambatan_month').val(month);
                                $('#detail-absensi-pegawai_edit_keterlambatan_year').val(year);
                                $('#detail-absensi-pegawai_edit_keterlambatan_date').val(date);

                                // add value for keterlambatan
                                $('#detail-absensi-pegawai_edit_keterlambatan-keterlambatan').val(response.keterlambatan);
                                $('#detail-absensi-pegawai_edit_keterlambatan-keterlambatan-old').val(response.keterlambatan);

                                // add value for keterangan keterlambatan
                                $('#detail-absensi-pegawai_edit_keterlambatan-keterangan').empty();
                                for (let i = 0; i < response.list_keterangan_absensi.length; i++) {

                                    $('#detail-absensi-pegawai_edit_keterlambatan-keterangan').append(new Option(
                                        response.list_keterangan_absensi[i]['keterangan'],
                                        response.list_keterangan_absensi[i]['id_keterangan'],
                                        false,
                                        false
                                    ));
                                }

                                $('#detail-absensi-pegawai_edit_keterlambatan').modal('show');

                            } else {

                                swal("Modal Absensi Masuk Pegawai Gagal ", "Kode : MDLDTLTELAT01. Kode status = 0", "error");
                                console.log("Kode : MDLDTLTELAT01. Kode status = 0");
                            }
                        },
                        error: function(errorMsg) {

                            swal("Modal Absensi Masuk Pegawai Gagal ", "Kode : MDLPUTABSMSK01. Gagal mengirim permitaan", "error");
                            console.log("Kode : MDLPUTABSMSK01. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
                        }
                    });
                }

                function modal_detail_absensi_pegawai_edit_pulangcepat(id_kepegawaian, nama_shift, nama_shift_tipe, absensi_pulang, id_unit, id_user, log_finger, nip, nama_pegawai, unit, level_user, date, month, year) {

                    $.ajax({
                        type: "GET",
                        url: "<?php echo $url_api_absensi; ?>?action=get_log_kehadiran_by_logfinger_date&log_finger=" + log_finger + "&date=" + date + "&id_unit=" + id_unit + "&id_user=" + id_user,
                        dataType: "JSON",
                        success: function(response) {

                            if (response.status == 1) {

                                $('#detail-absensi-pegawai_edit_pulangcepat_profile').html("");
                                $('#detail-absensi-pegawai_edit_pulangcepat_profile').append("<tr><td>Tanggal</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + date + "</td></tr>");
                                $('#detail-absensi-pegawai_edit_pulangcepat_profile').append("<tr><td>Nama Pegawai</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + nama_pegawai + "</td></tr>");
                                $('#detail-absensi-pegawai_edit_pulangcepat_profile').append("<tr><td>NIP</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + nip + "</td></tr>");
                                $('#detail-absensi-pegawai_edit_pulangcepat_profile').append("<tr><td>Unit</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + (unit + " - " + level_user) + "</td></tr>");
                                $('#detail-absensi-pegawai_edit_pulangcepat_profile').append("<tr><td>Shift</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + nama_shift + "</td></tr>");
                                $('#detail-absensi-pegawai_edit_pulangcepat_profile').append("<tr><td>Tipe Shift</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + nama_shift_tipe + "</td></tr>");
                                $('#detail-absensi-pegawai_edit_pulangcepat_profile').append("<tr><td>Absensi Pulang</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td>" + absensi_pulang + "</td></tr>");
                                $('#detail-absensi-pegawai_edit_pulangcepat_id-kepegawaian').val(id_kepegawaian);
                                $('#detail-absensi-pegawai_edit_pulangcepat_id-user').val(id_user);
                                $('#detail-absensi-pegawai_edit_pulangcepat_id-unit').val(id_unit);
                                $('#detail-absensi-pegawai_edit_pulangcepat_month').val(month);
                                $('#detail-absensi-pegawai_edit_pulangcepat_year').val(year);
                                $('#detail-absensi-pegawai_edit_pulangcepat_date').val(date);

                                // add value for pulang cepat
                                $('#detail-absensi-pegawai_edit_pulangcepat-pulangcepat').val(response.pulang_cepat);
                                $('#detail-absensi-pegawai_edit_pulangcepat-pulangcepat-old').val(response.pulang_cepat);

                                // add value for keterangan pulang cepat
                                $('#detail-absensi-pegawai_edit_pulangcepat-keterangan').empty();
                                for (let i = 0; i < response.list_keterangan_absensi.length; i++) {

                                    $('#detail-absensi-pegawai_edit_pulangcepat-keterangan').append(new Option(
                                        response.list_keterangan_absensi[i]['keterangan'],
                                        response.list_keterangan_absensi[i]['id_keterangan'],
                                        false,
                                        false
                                    ));
                                }

                                $('#detail-absensi-pegawai_edit_pulangcepat').modal('show');

                            } else {

                                swal("Modal Absensi Pulang Pegawai Gagal ", "Kode : MDLPUTABSPLG02. Kode status = 0", "error");
                                console.log("Kode : MDLPUTABSPLG02. Kode status = 0");
                            }
                        },
                        error: function(errorMsg) {

                            swal("Modal Absensi Pulang Pegawai Gagal ", "Kode : MDLPUTABSPLG01. Gagal mengirim permitaan", "error");
                            console.log("Kode : MDLPUTABSPLG01. Gagal mengirim permintaan. " + errorMsg.status + "-" + errorMsg.statusText);
                        }
                    });
                }

                function put_absensimasuk_detail_absensi_pegawai() {
                    var id_kepegawaian = $('#detail-absensi-pegawai_edit_absensimasuk_id-kepegawaian').val();
                    var id_user = $('#detail-absensi-pegawai_edit_absensimasuk_id-user').val();
                    var id_unit = $('#detail-absensi-pegawai_edit_absensimasuk_id-unit').val();
                    var month = $('#detail-absensi-pegawai_edit_absensimasuk_month').val();
                    var year = $('#detail-absensi-pegawai_edit_absensimasuk_year').val();
                    var date = $('#detail-absensi-pegawai_edit_absensimasuk_date').val();
                    var absensi_masuk_selected = $('#detail-absensi-pegawai_edit_absensimasuk_selected').val();

                    $.ajax({
                        type: "POST",
                        url: "<?php echo $url_api_absensi; ?>?action=put_absensimasuk_detail_absensi_pegawai",
                        data: {
                            id_kepegawaian: id_kepegawaian,
                            id_user: id_user,
                            id_unit: id_unit,
                            month: month,
                            year: year,
                            date: date,
                            absensi_masuk_selected: absensi_masuk_selected
                        },
                        dataType: "JSON",
                        success: function(response) {

                            console.log(response);
                            swal({
                                title: "Perbarui Absensi Masuk Pegawai Berhasil",
                                type: "success"
                            }, function() {
                                window.location.reload();
                            });
                        },
                        error: function(errorMsg) {

                            swal("Perbarui Absensi Masuk Pegawai Gagal", "Kode : PUTABSMSK01", "error");
                            console.log("Kode : PUTABSMSK01. Gagal mengirim permitaan. " + errorMsg.status + "-" + errorMsg.statusText);
                        }
                    });
                }

                function put_absensipulang_detail_absensi_pegawai() {
                    var id_kepegawaian = $('#detail-absensi-pegawai_edit_absensipulang_id-kepegawaian').val();
                    var id_user = $('#detail-absensi-pegawai_edit_absensipulang_id-user').val();
                    var id_unit = $('#detail-absensi-pegawai_edit_absensipulang_id-unit').val();
                    var month = $('#detail-absensi-pegawai_edit_absensipulang_month').val();
                    var year = $('#detail-absensi-pegawai_edit_absensipulang_year').val();
                    var date = $('#detail-absensi-pegawai_edit_absensipulang_date').val();
                    var absensi_pulang_selected = $('#detail-absensi-pegawai_edit_absensipulang_selected').val();

                    $.ajax({
                        type: "POST",
                        url: "<?php echo $url_api_absensi; ?>?action=put_absensipulang_detail_absensi_pegawai",
                        data: {
                            id_kepegawaian: id_kepegawaian,
                            id_user: id_user,
                            id_unit: id_unit,
                            month: month,
                            year: year,
                            date: date,
                            absensi_pulang_selected: absensi_pulang_selected
                        },
                        dataType: "JSON",
                        success: function(response) {

                            console.log(response);
                            swal({
                                title: "Perbarui Absensi Pulang Pegawai Berhasil",
                                type: "success"
                            }, function() {
                                window.location.reload();
                            });
                        },
                        error: function(errorMsg) {

                            swal("Perbarui Absensi Pulang Pegawai Gagal", "Kode : PUTABSPLG01", "error");
                            console.log("Kode : PUTABSPLG01. Gagal mengirim permitaan. " + errorMsg.status + "-" + errorMsg.statusText);
                        }
                    });
                }

                function put_keterlambatan_absensi_pegawai(button_id) {

                    var id_kepegawaian = $('#detail-absensi-pegawai_edit_keterlambatan_id-kepegawaian').val();
                    var id_user = $('#detail-absensi-pegawai_edit_keterlambatan_id-user').val();
                    var id_unit = $('#detail-absensi-pegawai_edit_keterlambatan_id-unit').val();
                    var month = $('#detail-absensi-pegawai_edit_keterlambatan_month').val();
                    var year = $('#detail-absensi-pegawai_edit_keterlambatan_year').val();
                    var date = $('#detail-absensi-pegawai_edit_keterlambatan_date').val();
                    var keterlambatan_new = $('#detail-absensi-pegawai_edit_keterlambatan-keterlambatan').val();
                    var keterlambatan_old = $('#detail-absensi-pegawai_edit_keterlambatan-keterlambatan-old').val();
                    var keterangan = $('#detail-absensi-pegawai_edit_keterlambatan-keterangan').val();

                    $(button_id).html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;Mohon Tunggu").prop('disabled', true);

                    $.ajax({
                        type: "POST",
                        url: "<?php echo $url_api_absensi; ?>?action=put_keterlambatan_absensi_pegawai",
                        data: {
                            id_kepegawaian: id_kepegawaian,
                            id_user: id_user,
                            id_unit: id_unit,
                            month: month,
                            year: year,
                            date: date,
                            keterlambatan_old: keterlambatan_old,
                            keterlambatan_new: keterlambatan_new,
                            keterangan: keterangan
                        },
                        dataType: "JSON",
                        success: function(response) {

                            $(button_id).html("Perbarui").prop('disabled', false);
                            console.log(response);
                            swal({
                                title: "Perbarui Keterlambatan Pegawai Berhasil",
                                type: "success"
                            }, function() {
                                window.location.reload();
                            });
                        },
                        error: function(errorMsg) {

                            $(button_id).html("Perbarui").prop('disabled', false);
                            swal("Perbarui Keterlambatan Pegawai Gagal", "Kode : PUTTELAT01", "error");
                            console.log("Kode : PUTTELAT01. Gagal mengirim permitaan. " + errorMsg.status + "-" + errorMsg.statusText);
                        }
                    });
                }

                function put_pulangcepat_absensi_pegawai(button_id) {

                    var id_kepegawaian = $('#detail-absensi-pegawai_edit_pulangcepat_id-kepegawaian').val();
                    var id_user = $('#detail-absensi-pegawai_edit_pulangcepat_id-user').val();
                    var id_unit = $('#detail-absensi-pegawai_edit_pulangcepat_id-unit').val();
                    var month = $('#detail-absensi-pegawai_edit_pulangcepat_month').val();
                    var year = $('#detail-absensi-pegawai_edit_pulangcepat_year').val();
                    var date = $('#detail-absensi-pegawai_edit_pulangcepat_date').val();
                    var pulangcepat_new = $('#detail-absensi-pegawai_edit_pulangcepat-pulangcepat').val();
                    var pulangcepat_old = $('#detail-absensi-pegawai_edit_pulangcepat-pulangcepat-old').val();
                    var keterangan = $('#detail-absensi-pegawai_edit_pulangcepat-keterangan').val();

                    $(button_id).html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;Mohon Tunggu").prop('disabled', true);

                    $.ajax({
                        type: "POST",
                        url: "<?php echo $url_api_absensi; ?>?action=put_pulangcepat_absensi_pegawai",
                        data: {
                            id_kepegawaian: id_kepegawaian,
                            id_user: id_user,
                            id_unit: id_unit,
                            month: month,
                            year: year,
                            date: date,
                            pulangcepat_old: pulangcepat_old,
                            pulangcepat_new: pulangcepat_new,
                            keterangan: keterangan
                        },
                        dataType: "JSON",
                        success: function(response) {

                            $(button_id).html("Perbarui").prop('disabled', false);
                            console.log(response);
                            swal({
                                title: "Perbarui Pulang Cepat Pegawai Berhasil",
                                type: "success"
                            }, function() {
                                window.location.reload();
                            });
                        },
                        error: function(errorMsg) {

                            $(button_id).html("Perbarui").prop('disabled', false);
                            swal("Perbarui Pulang Cepat Pegawai Gagal", "Kode : PUTPLNGCPT01", "error");
                            console.log("Kode : PUTPLNGCPT01. Gagal mengirim permitaan. " + errorMsg.status + "-" + errorMsg.statusText);
                        }
                    });
                }

                function put_shift_detail_absensi_pegawai(button_id) {

                    var id_kepegawaian = $("#detail-absensi-pegawai_edit-shift_id-kepegawaian").val();
                    var id_unit = $("#detail-absensi-pegawai_edit-shift_id-unit").val();
                    var id_user = $("#detail-absensi-pegawai_edit-shift_id-user").val();
                    var log_finger = $("#detail-absensi-pegawai_edit-shift_log-finger").val();
                    var month = $("#detail-absensi-pegawai_edit-shift_month").val();
                    var year = $("#detail-absensi-pegawai_edit-shift_year").val();
                    var date = $("#detail-absensi-pegawai_edit-shift_date").val();
                    var id_absensi = $('#detail-absensi-pegawai_edit-shift_shift-option option:selected').val();
                    var id_absensi_tipe = $('#detail-absensi-pegawai_edit-shift_shift-tipe-option option:selected').val();
                    var id_ketidakhadiran = $('#detail-absensi-pegawai_edit-shift_ketidakhadiran-option option:selected').val();
                    var is_libur = $('#detail-absensi-pegawai_edit-shift_harilibur option:selected').val();

                    $(button_id).html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;Mohon Tunggu").prop('disabled', true);

                    $.ajax({
                        type: "POST",
                        url: "<?php echo $url_api_absensi; ?>?action=put_shift_detail_absensi_pegawai",
                        data: {
                            id_kepegawaian: id_kepegawaian,
                            id_unit: id_unit,
                            id_user: id_user,
                            log_finger: log_finger,
                            month: month,
                            year: year,
                            date: date,
                            id_absensi: id_absensi,
                            id_absensi_tipe: id_absensi_tipe,
                            id_ketidakhadiran: id_ketidakhadiran,
                            is_libur: is_libur
                        },
                        dataType: "JSON",
                        success: function(response) {

                            $(button_id).html("Perbarui").prop('disabled', false);
                            console.log(response);
                            swal({
                                title: "Perbarui Shift Pegawai Berhasil",
                                type: "success"
                            }, function() {
                                window.location.reload();
                            });
                        },
                        error: function(errorMsg) {
                            $(button_id).html("Perbarui").prop('disabled', false);
                            swal("Perbarui Shift Pegawai Gagal", "", "error");
                            console.log("Kode : PUTABSDTL01. Gagal mengirim permitaan. " + errorMsg.status + "-" + errorMsg.statusText);
                        }
                    });
                }
                // end fungsi untuk detail-absensi-pegawai

                // fungsi untuk lihat-absensi-unit
                function get_absensi_unit() {

                    var month = remove_leading_zero($("#lihat-absensi-unit_month option:selected").val());
                    var year = $("#lihat-absensi-unit_year option:selected").val();
                    var id_unit = $("#lihat-absensi-unit_id-unit option:selected").val();

                    $('#lihat-absensi-unit_table tbody').empty();
                    $('#lihat-absensi-unit_btn').html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>").attr('disabled', true);

                    $.ajax({
                        type: "GET",
                        url: "<?php echo $url_api_master_data; ?>?action=get_absensi_unit&id_unit=" + id_unit + "&month=" + month + "&year=" + year,
                        dataType: "JSON",
                        success: function(response) {

                            if (response.status == 1) {

                                for (let i = 0; i < response.data.length; i++) {

                                    var no = i + 1;
                                    var nm_pegawai = response.data[i]['nama_pegawai'];
                                    var tanggal = response.data[i]['date'];
                                    var desc_shift = response.data[i]['shift_aktif'] == "1" ? response.data[i]['desc_shift'] : response.data[i]['desc_ketidakhadiran'];
                                    var absensi_masuk = response.data[i]['absensi_masuk'];
                                    var absensi_pulang = response.data[i]['absensi_pulang'];
                                    var telat = response.data[i]['keterlambatan'];
                                    var pulang_cepat = response.data[i]['pulang_cepat'];
                                    var keterangan = "-";

                                    $("#lihat-absensi-unit_table tbody").append("<tr><td>" + no + "</td><td>" + nm_pegawai + "</td><td>" + tanggal + "</td><td>" + desc_shift + "</td><td>" + absensi_masuk + "</td><td>" + absensi_pulang + "</td><td>" + telat + "</td><td>" + pulang_cepat + "</td><td>" + keterangan + "</td></tr>");
                                }

                                if (!$.fn.dataTable.isDataTable('#lihat-absensi-unit_table')) {

                                    $('#lihat-absensi-unit_table').DataTable({
                                        "responsive": true,
                                        "autoWidth": false,
                                        "paging": true,
                                        "scrollX": true,
                                        "pageLength": 100
                                    });
                                }

                            } else {

                                console.log("Kode : GETABSUNIT02. Kode Status = 0");
                                swal("Data Absensi Unit tidak ditemukan", "", "error");
                            }

                            $('#lihat-absensi-unit_btn').html('<i class="fa fa-search"></i>&nbsp;&nbsp;Cari').attr('disabled', false);
                        },
                        error: function(error) {

                            console.log("Kode : GETABSUNIT01. Gagal mengirim permintaan. " + error.status + "-" + error.statusText);
                        }
                    });
                }
                // end fungsi untuk lihat-absensi-unit

                $(document).ready(function() {

                    absensi_live_jadwal_table = $('#absensi_pegawai_live_jadwal').DataTable({
                        "lengthMenu": [
                            100, 250, "All"
                        ]
                    });

                    absensi_live_table = $('#absensi_pegawai_live').DataTable({
                        "lengthMenu": [100, 250, "All"],
                        dom: 'lBfrtip',
                        buttons: [{
                            extend: 'collection',
                            text: 'Export To',
                            buttons: [
                                'copy', 'excel'
                            ]
                        }]
                    });

                    rekapitulasi_absensi_pegawai_table = $('#modal-status-absensi-unit_absensi-pegawai_table').DataTable({
                        "paging": true,
                        "response": true,
                        "autoWidth": false,
                        "searching": true
                    });
                });
            </script>