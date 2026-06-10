<!-- sidebar menu: : style can be found in sidebar.less -->
<ul class="sidebar-menu" data-widget="tree">
    <?php if ($superuser == '') { ?>
        <li class="header">MAIN NAVIGATION</li>
        <?php if ($status_pegawai != 'PNS') { ?>
            <li>
                <a href="?<?php echo paramEncrypt('module=dashboard&act=dashboard'); ?>">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-book"></i> <span>Kinerja</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="treeview">
                        <a href="#"><i class="fa fa-circle-o"></i> SKP
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="?<?php echo paramEncrypt('module=kinerja-pegawai&act=list-data-skp-tahunan-pegawai'); ?>"><i class="fa fa-circle-o"></i> SKP Tahunan</a></li>
                        </ul>
                    </li>
                    <li><a href="?<?php echo paramEncrypt('module=kinerja-pegawai&act=list-data-kinerja-pegawai'); ?>"><i class="fa fa-circle-o"></i> Aktifitas</a></li>
                </ul>
            </li>
            <!-- pjlp -->

            <!--    <li>-->
            <!--    <a href="?<?php echo paramEncrypt('module=dashboard&act=dashboard'); ?>">-->
            <!--        <i class="fa fa-dashboard"></i> <span>Dashboard</span>-->
            <!--    </a>-->
            <!--</li>-->
            <!-- <li class="treeview">
                <a href="#">
                    <i class="fa fa-book"></i> <span>Kinerja PJLP</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="treeview">
                        <a href="#"><i class="fa fa-circle-o"></i> SKP
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="?<?php echo paramEncrypt('module=kinerja-pjlp&act=list-data-skp-tahunan-pegawai'); ?>"><i class="fa fa-circle-o"></i> SKP Tahunan</a></li>                    
                        </ul>
                    </li>
                    <li><a href="?<?php echo paramEncrypt('module=kinerja-pjlp&act=list-data-kinerja-pegawai'); ?>"><i class="fa fa-circle-o"></i> Aktifitas</a></li>
                </ul>
            </li> -->

        <?php } else { ?>
            <li>
                <a href="?<?php echo paramEncrypt('module=dashboard&act=home'); ?>">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
        <?php } ?>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-book"></i> <span>Cuti</span>
                <?php
                if ($cek_pengajuan_cuti_total > 0) {
                ?>
                    <span class="pull-right-container">
                        <small class="label pull-right bg-red"><?php echo $cek_pengajuan_cuti_total; ?></small>
                    </span>
                <?php } else { ?>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                <?php } ?>
            </a>
            <ul class="treeview-menu">
                <li class="treeview-menu">
                    <?php
                    if ($_SESSION['id_level'] == "LVL-000013" || $_SESSION['id_level'] == "LVL-000014" || $_SESSION['id_level'] == "LVL-000015") :
                    ?>
                <li>
                    <a href="?<?php echo paramEncrypt('module=cuti-pegawai&act=list-data-semua-cuti-pegawai'); ?>">
                        <i class="fa fa-circle-o"></i> Semua Cuti Pegawai
                    </a>
                </li>
            <?php
                    endif;
            ?>
            <li>
                <a href="?<?php echo paramEncrypt('module=cuti-pegawai&act=list-data-pengajuan-cuti-pegawai'); ?>">
                    <i class="fa fa-circle-o"></i> Pengajuan Cuti
                </a>
            </li>
            <?php
            if ($_SESSION['id_level'] == "LVL-000013" || $_SESSION['id_level'] == "LVL-000014" || $_SESSION['id_level'] == "LVL-000015") :
            ?>
                <li>
                    <a href="?<?php echo paramEncrypt('module=cuti-pegawai&act=list-data-assesment-kepeg'); ?>">
                        <i class="fa fa-circle-o"></i> Assesment Kepegawaian
                        <?php
                        if ($cek_ass_kepeg > 0) {
                        ?>
                            <span class="pull-right-container">
                                <small class="label pull-right bg-red"><?php echo $cek_ass_kepeg; ?></small>
                            </span>
                        <?php } ?>
                    </a>
                </li>
            <?php endif; ?>
            <li>
                <a href="?<?php echo paramEncrypt('module=cuti-pegawai&act=list-data-assesment-pengganti-cuti-pegawai'); ?>">
                    <i class="fa fa-circle-o"></i> Assesment Pengganti
                    <?php
                    if ($cek_pengajuan_cuti_pengganti > 0) {
                    ?>
                        <span class="pull-right-container">
                            <small class="label pull-right bg-red"><?php echo $cek_pengajuan_cuti_pengganti; ?></small>
                        </span>
                    <?php } ?>
                </a>
            </li>
            <?php
            // if ($hak_akses['menu_val_pj'] == '1' || $_SESSION['id_level'] == 'LVL-000013') {
            if (true) { // semua orang bs buka asssesment pj. aman, karena difilter berdasarkan id_pj nya sesuai user
            ?>
                <li>
                    <a href="?<?php echo paramEncrypt("module=cuti-pegawai&act=list-data-assesment-pj-cuti-pegawai"); ?>">
                        <i class="fa fa-circle-o"></i> Assesment PJ
                        <?php
                        if ($cek_pengajuan_cuti_pj > 0) {
                        ?>
                            <span class="pull-right-container">
                                <small class="label pull-right bg-red"><?php echo $cek_pengajuan_cuti_pj; ?></small>
                            </span>
                        <?php
                        }
                        ?>
                    </a>
                </li>
            <?php
            }
            if ($hak_akses['menu_val_kasatpel'] == '1') {
            ?>
                <li>
                    <a href="?<?php echo paramEncrypt("module=cuti-pegawai&act=list-data-assesment-kasatpel-cuti-pegawai"); ?>">
                        <i class="fa fa-circle-o"></i> Assesment Kasatpel
                        <?php
                        if ($cek_pengajuan_cuti_kasatpel > 0) {
                        ?>
                            <span class="pull-right-container">
                                <small class="label pull-right bg-red"><?php echo $cek_pengajuan_cuti_kasatpel; ?></small>
                            </span>
                        <?php
                        }
                        ?>
                    </a>
                </li>
            <?php
            }
            if ($hak_akses['menu_val_kasie'] == '1' || $hak_akses['menu_val_direktur'] == '1') {
            ?>
                <li>
                    <a href="?<?php echo paramEncrypt("module=cuti-pegawai&act=list-data-assesment-kasie-cuti-pegawai"); ?>">
                        <i class="fa fa-circle-o"></i> Assesment Kasie
                        <?php
                        if ($cek_pengajuan_cuti_kasie > 0) {
                        ?>
                            <span class="pull-right-container">
                                <small class="label pull-right bg-red"><?php echo $cek_pengajuan_cuti_kasie; ?></small>
                            </span>
                        <?php } ?>
                    </a>
                </li>
                <li>
                    <a href="?<?php echo paramEncrypt('module=cuti-pegawai&act=list-data-assesment-ktu-cuti-pegawai'); ?>"><i class="fa fa-circle-o"></i> Assesment KTU
                        <?php
                        if ($cek_pengajuan_cuti_ktu > 0) {
                        ?>
                            <span class="pull-right-container">
                                <small class="label pull-right bg-red"><?php echo $cek_pengajuan_cuti_ktu; ?></small>
                            </span>
                        <?php } ?>
                    </a>
                </li>
                <li>
                    <a href="?<?php echo paramEncrypt('module=cuti-pegawai&act=list-data-assesment-direktur-cuti-pegawai'); ?>"><i class="fa fa-circle-o"></i> Assesment Direktur
                        <?php
                        if ($cek_pengajuan_cuti_direktur > 0) {
                        ?>
                            <span class="pull-right-container">
                                <small class="label pull-right bg-red"><?php echo $cek_pengajuan_cuti_direktur; ?></small>
                            </span>
                        <?php } ?>
                    </a>
                </li>
            <?php
            }
            ?>
            </ul>
        </li>
        <?php
        if ($unit_akses_menu_pergantian_dinas) {
        ?>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-book"></i>
                    <span>
                        Pergantian Dinas
                    </span>
                    <span class="pull-right-container">
                        <?php
                        $count = getOne("
                            SELECT 
                                COUNT(a.id_tukarshift_validation) AS total
                            FROM tt_tukarshift_validation a
                            WHERE a.id_receiver = '" . $id_user . "'
                                AND a.answered = 0
                        ");
                        if ($count > 0) {
                        ?>
                            <small class="label pull-right bg-red"><?php echo $count; ?></small>
                        <?php
                        } else {
                        ?>
                            <i class="fa fa-angle-left pull-right"></i>
                        <?php
                        } ?>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="?<?php echo paramEncrypt('module=tukar-shift&act=tukar-shift-add') ?>">
                            <i class="fa fa-circle-o"></i>Buat Permintaan
                        </a>
                    </li>
                    <li>
                        <a href="?<?php echo paramEncrypt('module=tukar-shift&act=tukar-shift-receive') ?>">
                            <i class="fa fa-circle-o"></i>Permintaan Tukar Shift
                            <?php if ($count > 0) {
                            ?>
                                <small class="label pull-right bg-red"><?php echo $count; ?></small>
                            <?php
                            } ?>
                        </a>
                    </li>
                </ul>
            </li>
        <?php
        }
        ?>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-clock-o"></i>
                <span>Log Absensi</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li>
                    <a href="?<?php echo paramEncrypt('module=absensi-pegawai&act=absensi-pegawai'); ?>">
                        <i class="fa fa-circle-o"></i>Pribadi
                    </a>
                </li>
                <?php if ($hak_akses['menu_kepegawaian_absensi'] == '1') { ?>
                    <li>
                        <a href="?<?php echo paramEncrypt('module=absensi-pegawai&act=absensi-pegawai-live-jadwal'); ?>">
                            <i class="fa fa-circle-o"></i>Unit 1
                        </a>
                    </li>
                    <li>
                        <a href="?<?php echo paramEncrypt('module=absensi-pegawai&act=absensi-pegawai-live'); ?>">
                            <i class="fa fa-circle-o"></i>Unit 2
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </li>
        <?php
    }
    // if ($day > $setup['tutup_kinerja'] and $day <= $setup['validasi_pj']) {
        if ($hak_akses['menu_val_kasatpel'] == '1' or $hak_akses['menu_val_pj'] == '1' or $hak_akses['menu_val_kasie'] == '1' or $hak_akses['menu_val_kepegawaian'] == '1') {
        ?>
            <li class="header">VALIDASI</li>
            <li>
                <?php
                $url_target = "";

                switch ($_SESSION['id_level']) {
                    // dicomment sebab belum digunakan
                    // case 'LVL-000005':
                    // case 'LVL-000011':
                    //     $url_target = 'module=validasi-pegawai&act=tabel-validasi-pegawai';
                    //     break;

                    default:
                        $url_target = 'module=validasi-pegawai&act=list-validasi-pegawai';
                        break;
                } ?>
                <a href="?<?php echo paramEncrypt($url_target); ?>">
                    <i class="fa fa-check-square-o"></i> <span>Validasi Pegawai</span>
                </a>
            </li>
            <?php
            }
            if ($hak_akses['menu_val_kasatpel'] == '1' or $hak_akses['menu_val_kasie'] == '1') {
            ?>
                <li>
                    <a href="?<?php echo paramEncrypt('module=validasi-pegawai&act=list-validasi-pjlp'); ?>">
                        <i class="fa fa-sort-numeric-asc"></i> <span>Penilaian PJLP </span>
                    </a>
                </li>
            <?php
            }
            ?>
            <?php if ($hak_akses['menu_val_kasatpel'] == '1') {
            ?>
                <li>
                    <a href="?<?php echo paramEncrypt('module=master-data&act=list-permintaan-validasi-jadwalshift'); ?>">
                        <i class="fa fa-calendar"></i><span>Validasi Pengaturan Shift </span>
                    </a>
                </li>
            <?php
            } ?>

    <?php
        // }
    // }
    ?>

    <?php if ($hak_akses['menu_shift_pj'] == '1' && $unit_akses_menu_shift_pj) { ?>
        <li class="header">SHIFT UNIT (PJ)</li>
        <li>
            <a href="?<?php echo paramEncrypt('module=master-data&act=set-jadwalshift'); ?>">
                <i class="fa fa-calendar"></i><span>Buat Shift</span></a>
        </li>
    <?php } ?>

    <?php if ($hak_akses['menu_shift_managerial'] == '1') { ?>
        <li class="header">SHIFT UNIT PEGAWAI</li>
        <li>
            <a href="?<?php echo paramEncrypt('module=master-data&act=list-shift-pegawai-all-unit'); ?>">
                <i class="fa fa-calendar"></i><span>Shift Pegawai</span>
            </a>
        </li>
    <?php } ?>

    

    

    <?php if ($hak_akses['menu_kepegawaian'] == '1' or $superuser != '') { ?>
        <li class="header">KEPEGAWAIAN</li>
        <?php if ($hak_akses['submenu_data_pegawai'] == '1') {
        ?>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i> <span>Data Pegawai</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="?<?php echo paramEncrypt('module=master-data&act=list-master-data-pegawai-pns'); ?>"><i class="fa fa-circle-o"></i> PNS</a></li>
                    <li><a href="?<?php echo paramEncrypt('module=master-data&act=list-master-data-pegawai-non-pns'); ?>"><i class="fa fa-circle-o"></i> NON PNS</a></li>
                    <li><a href="?<?php echo paramEncrypt('module=master-data&act=list-master-data-pegawai-pjlp'); ?>"><i class="fa fa-circle-o"></i> PJLP</a></li>
                    <li><a href="?<?php echo paramEncrypt('module=master-data&act=list-master-data-pegawai-spesialis'); ?>"><i class="fa fa-circle-o"></i> SPESIALIS</a></li>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-id-badge"></i> SIP/SIP Pegawai
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="?<?php echo paramEncrypt('module=master-data&act=list-sip-pegawai-non-pns'); ?>"><i class="fa fa-circle-o"></i> SIP</a></li>
                            <li><a href="?<?php echo paramEncrypt('module=master-data&act=list-str-pegawai-non-pns'); ?>"><i class="fa fa-circle-o"></i> STR</a></li>
                            <li><a href="?<?php echo paramEncrypt('module=master-data&act=list-spk-pegawai-non-pns'); ?>"><i class="fa fa-circle-o"></i> SPK</a></li>
                            <li><a href="?<?php echo paramEncrypt('module=master-data&act=list-rkk-pegawai-non-pns'); ?>"><i class="fa fa-circle-o"></i> RKK</a></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-edit"></i> Form Pegawai
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="?<?php echo paramEncrypt('module=form-surat-peringatan&act=list-data-form-sp'); ?>"><i class="fa fa-circle-o"></i> Form SP/HUKDIS</a></li>
                            <li><a href="?<?php echo paramEncrypt('module=master-data&act=list-master-data-hari-kerja'); ?>"><i class="fa fa-circle-o"></i> Form Hari Kerja</a></li>
                            <li><a href="?<?php echo paramEncrypt('module=master-data&act=list-master-izin-belajar'); ?>"><i class="fa fa-circle-o"></i> Form Izin Belajar</a></li>
                            <li><a href="?<?php echo paramEncrypt('module=master-data&act=list-master-grade'); ?>"><i class="fa fa-circle-o"></i> Grade Jabatan</a></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-database"></i> Master Data
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="?<?php echo paramEncrypt('module=master-data&act=list-master-data-skp'); ?>"><i class="fa fa-circle-o"></i> Master SKP</a></li>
                            <li><a href="?<?php echo paramEncrypt('module=master-data&act=list-master-data-bagian'); ?>"><i class="fa fa-circle-o"></i> Master Bagian</a></li>
                            <li><a href="?<?php echo paramEncrypt('module=master-data&act=list-master-data-kasatpel'); ?>"><i class="fa fa-circle-o"></i> Master Kasatpel</a></li>
                            <li><a href="?<?php echo paramEncrypt('module=master-data&act=list-master-data-sanksi'); ?>"><i class="fa fa-circle-o"></i> Master Sanksi</a></li>
                            <li><a href="?<?php echo paramEncrypt('module=master-data&act=list-master-absensi'); ?>"><i class="fa fa-circle-o"></i> Master Shift/Absensi</a></li>
                            <li><a href="?<?php echo paramEncrypt('module=master-data&act=list-master-shift-ketidakhadiran'); ?>"><i class="fa fa-circle-o"></i>Master Ketidakhadiran</a></li>
                            <li><a href="?<?php echo paramEncrypt('module=master-data&act=list-master-hari-libur'); ?>"><i class="fa fa-circle-o"></i>Master Hari Libur</a></li>
                            <li><a href="?<?php echo paramEncrypt('module=master-data&act=list-master-hari-raya'); ?>"><i class="fa fa-circle-o"></i>Master Hari Raya</a></li>
                            <li><a href="?<?php echo paramEncrypt('module=master-data&act=list-master-kuota-cuti'); ?>"><i class="fa fa-circle-o"></i>Master Kuota Cuti</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="?<?php echo paramEncrypt('module=absensi-pegawai&act=pengaturan-absensi'); ?>"><i class="fa fa-cog"></i> Pengaturan Absensi/Shift
                        </a>
                    </li>
                    <li>
                        <a href="?<?php echo paramEncrypt('module=master-data&act=upload-data-pegawai'); ?>"><i class="fa fa-upload"></i> Upload Pegawai
                        </a>
                    </li>
                </ul>
            </li>
        <?php
        } ?>
        <!-- <li>
            <a href="?<?php echo paramEncrypt('module=laporan&act=laporan-shift-pegawai'); ?>">
                <i class="fa fa-calendar"></i><span>Rekapitulasi Shift </span>
            </a>
        </li> -->
        <?php if ($hak_akses['menu_kepegawaian_absensi'] == '1') {
        ?>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-calendar"></i><span>Shift Pegawai</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="?<?php echo paramEncrypt('module=master-data&act=set-jadwal-nonshift'); ?>">
                            <i class="fa fa-calendar"></i><span>Non Shift</span>
                        </a>
                    </li>
                    <li>
                        <a href="?<?php echo paramEncrypt('module=master-data&act=list-shift-pegawai-all-unit'); ?>">
                            <i class="fa fa-calendar"></i><span>Shift</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-book"></i><span>Cuti Pegawai</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">

                    <li>
                        <a href="?<?= paramEncrypt('module=cuti-pegawai&act=list-data-cuti-pegawai'); ?>">
                            <i class="fa fa-circle-o"></i><span>Data Cuti</span>
                        </a>
                    </li>
                    <li>
                        <a href="?<?php echo paramEncrypt('module=cuti-pegawai&act=list-data-penambahan-cuti'); ?>">
                            <i class="fa fa-circle-o"></i><span>Penambahan Cuti Pegawai</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-check-square-o"></i><span>Validasi Absensi</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="?<?php echo paramEncrypt('module=absensi-pegawai&act=rekap-absensi-pegawai'); ?>">
                            <i class="fa fa-check-square-o"></i> <span>Seluruh Unit</span>
                        </a>
                    </li>
                </ul>
            </li>
        <?php
        }
    }
    if ($hak_akses['menu_kepegawaian'] == '1' and $hak_akses['menu_surtug'] == '1') {
        ?>
        <li>
            <a href="?<?php echo paramEncrypt('module=form-surat-tugas&act=list-data-form-tugas'); ?>">
                <i class="fa fa-file"></i> <span>Form Surat Tugas</span>
            </a>
        </li>
    <?php
    }
    ?>
    <?php if ($hak_akses['menu_diklat'] == '1') { ?>
        <li class="header">DIKLAT</li>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-book"></i> <span>Agenda</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="?<?php echo paramEncrypt('module=agenda-rapat&act=list-agenda-rapat'); ?>"><i class="fa fa-circle-o"></i> List Agenda</a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-university"></i> <span>Pelatihan</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="?<?php echo paramEncrypt('module=diklat&act=list-diklat-pegawai'); ?>"><i class="fa fa-circle-o"></i> List Pelatihan Pegawai</a></li>
            </ul>
        </li>
    <?php } ?>
    <?php if ($hak_akses['menu_keuangan'] == '1' or $superuser != '') { ?>
        <li class="header">KEUANGAN</li>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-money"></i> <span>Form Keuangan</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="?<?php echo paramEncrypt('module=master-data&act=list-master-data-penyerapan'); ?>"><i class="fa fa-circle-o"></i> Form Penyerapan</a></li>
                <li><a href="?<?php echo paramEncrypt('module=master-data&act=list-master-data-shifting'); ?>"><i class="fa fa-circle-o"></i> Form Honor Shift</a></li>
            </ul>
        </li>
        <li>
            <a href="?<?php echo paramEncrypt('module=slip-gaji&act=list-req-slip-gaji-keuangan'); ?>">
                <i class="fa fa-check-square-o"></i> Permintaan Slip Gaji
                <?php
                if ($notifikasi_permintaan_slipgaji > 0) {
                ?>
                    <span class="pull-right-container">
                        <small class="label pull-right bg-red"><?php echo $notifikasi_permintaan_slipgaji; ?></small>
                    </span>
                <?php
                }
                ?>
            </a>
        </li>
        <li>
            <a href="?<?php echo paramEncrypt('module=master-data&act=setPPH21'); ?>">
                <i class="fa fa-check-square-o"></i> <span>Input PPH21</span>
            </a>
        </li>
    <?php } ?>
    <?php if ((($hak_akses['menu_keuangan'] == '1' or $hak_akses['menu_kepegawaian'] == '1' or $hak_akses['menu_val_kasie'] == '1' or $hak_akses['menu_val_kasatpel'] == '1') and $hak_akses['menu_laporan'] == '1') or $superuser != '') { ?>
        <li class="header">LAPORAN</li>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-file"></i> <span>Rekapitulasi</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="treeview">
                    <a href="#"><i class="fa fa-amazon"></i>Absensi
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="?<?php echo paramEncrypt('module=absensi-pegawai&act=laporan-absensi-pegawai'); ?>"><i class="fa fa-circle-o"></i> Lap Absensi 1</a></li>
                        <li><a href="?<?php echo paramEncrypt('module=absensi-pegawai&act=rekap-hasil-absensi-pegawai-by-pj'); ?>"><i class="fa fa-circle-o"></i> Lap Absensi 2</a></li>
                    </ul>
                </li>
                <?php if ($hak_akses['menu_kepegawaian'] == '1' or $superuser != '') { ?>
                    <li><a href="?<?php echo paramEncrypt('module=laporan&act=lap-data-upload'); ?>"><i class="fa fa-upload"></i>Data Upload </a></li>
                    <?php if ($hak_akses['menu_val_kasie'] == '1' or $hak_akses['menu_kepegawaian'] == '1' or $superuser != '') { ?>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-calendar-check-o"></i> Cuti
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="?<?php echo paramEncrypt('module=cuti-pegawai&act=list-data-maping-cuti-pegawai'); ?>"><i class="fa fa-circle-o"></i> Cuti Pegawai</a></li>
                            </ul>
                        </li>
                <?php
                    }
                }
                ?>
                <?php if ($hak_akses['menu_keuangan'] == '1' or $superuser != '') { ?>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-dollar"></i> Gaji
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="?<?php echo paramEncrypt('module=laporan&act=laporan-gaji-bruto-pegawai'); ?>"><i class="fa fa-circle-o"></i> Gaji Pegawai</a></li>
                            <li><a href="?<?php echo paramEncrypt('module=laporan&act=laporan-tunjangan-pegawai'); ?>"><i class="fa fa-circle-o"></i> Tunjangan Pegawai</a></li>
                        </ul>
                    </li>
                    <li><a href="?<?php echo paramEncrypt('module=master-data&act=set-spj'); ?>"><i class="fa fa-map-signs"></i> Maping TTD SPJ</a></li>
                <?php } ?>
                <?php if ($hak_akses['menu_val_kasie'] == '1' or $hak_akses['menu_kepegawaian'] == '1'  or $hak_akses['menu_val_kasatpel'] == '1' or $superuser != '') { ?>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-file-o"></i> Penilaian Pegawai
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="?<?php echo paramEncrypt('module=laporan&act=laporan-penilaian-pegawai'); ?>"><i class="fa fa-circle-o"></i> Bulanan</a></li>
                        </ul>
                    </li>
                <?php } ?>
                <?php if ($hak_akses['menu_val_kasie'] == '1' or $hak_akses['menu_kepegawaian'] == '1' or $superuser != '') { ?>
                    <li>
                        <a href="?<?php echo paramEncrypt('module=laporan&act=rekap-shift-pegawai'); ?>"><i class="fa fa-address-book"></i> Rekap Shifting
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
    
    <?php if ($hak_akses['menu_kepegawaian'] == '1' or $superuser != '') { ?>
        <li class="header">CONFIGURASI</li>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-cogs"></i> <span>Pengaturan</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="treeview">
                    <a href="#"><i class="fa fa-users"></i>Level User/Jabatan
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="?<?php echo paramEncrypt('module=configurasi&act=list-hak-akses'); ?>"><i class="fa fa-circle-o"></i> List Hak Akses/Jabatan</a></li>
                    </ul>
                </li>
                <li><a href="?<?php echo paramEncrypt('module=configurasi&act=setup'); ?>"><i class="fa fa-cog"></i> Setup</a></li>
            </ul>
        </li>
    <?php } ?>


</ul>