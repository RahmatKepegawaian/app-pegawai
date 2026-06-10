<?php
$aksi = "module/cuti-pegawai/aksi-cuti-pegawai?";
$id = isset($url['id']) ? $url['id'] : null;
$dpa = isset($url['dpa']) ? $url['dpa'] : null;
switch ((isset($url['act']) ? $url['act'] : '')) {
    default:
        echo "default";
        header("location:error404");
        break;

    case "list-data-pengajuan-cuti-pegawai":
        $tampil_cuti = 'yes';
        $cuti = bukaquery("SELECT tm_hari_cuti.id_cuti, tm_hari_cuti.tanggal, tm_pegawai.nama_pegawai
            FROM tm_hari_cuti
            INNER JOIN tm_cuti ON tm_cuti.id_cuti=tm_hari_cuti.id_cuti
            INNER JOIN tm_pegawai ON tm_pegawai.id_user=tm_cuti.id_user where tm_pegawai.id_unit='$id_unit'");
        ?>
        <div class="box">
            
            <div class="modal fade" id="modal-maping-cuti" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h3 class="box-title fa fa-list"> LIST DATA MAPING CUTI YANG DI AMBIL</h3>
                        </div>
                        <div class="modal-body ">
                            <div class="box-body no-padding">
                            </div>
                        </div>
                        <div class="modal-footer">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tutup Edit Modal permintaan -->
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST PENGAJUAN CUTI PEGAWAI</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>

            <div class="box-body">
                <button type="button" class="btn btn-info fa fa-plus" id="btn_add_pengajuan_cuti" data-toggle="modal" onclick="show_modal_pengajuan_cuti(`<?= $id_user; ?>`, `<?= str_replace(' ', '_', $kasie); ?>`, `<?= $id_unit; ?>`, `#`+this.id);">
                    Buat Pengajuan Cuti
                </button>
                <br>

                <!--Modal Add Cuti -->
                <div class="modal fade" id="modal-add-pengajuan-cuti" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title fa fa-plus"> Pengajuan Data Cuti pegawai</h4>
                            </div>
                            <form role="form" name="pilihan" action="<?php echo "$url_api_cuti_pegawai" ?>" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="post_pengajuan_cuti">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <input type="hidden" name="id_user" value="<?php echo $id_user; ?>">
                                        <label class="control-label">Nama Pegawai</label>
                                        <input class="form-control" type="text" id="nm_pegawai" name="nm_pegawai" readonly>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="control-label">Tahun Cuti </label>
                                                <select class="form-control select3" onchange="cuti_pegawai_sisa_cuti(`<?= $id_user; ?>`, `<?= $thn_now; ?>`, `<?= $bln_now; ?>`, `#modal-add-pengajuan-cuti`, ``)" id="tahun" name="tahun" style="width: 100%;" required>
                                                    <option disabled selected>-</option>
                                                    <?php loadThn5(); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Jenis Cuti</label>
                                                <select class="form-control select3" id="jns_cuti_permohonan" name="jns_cuti_permohonan" onchange="cuti_pegawai_sisa_cuti_by_opsi(`<?= $id_user; ?>`, `<?= $thn_now; ?>`, `<?= $bln_now; ?>`, `#modal-add-pengajuan-cuti`, ``, ``)" style="width: 100%" required>
                                                    <option disabled selected>-</option>
                                                </select>
                                                
                                            </div>
                                        </div>
                                        <label class="control-label">Alasan Cuti</label>
                                        <textarea class="form-control" rows="3" id="alasan_cuti" name="alasan_cuti" placeholder="Alasan.." required></textarea>
                                        <label class="control-label">Alamat Saat Cuti</label>
                                        <textarea class="form-control" rows="2" id="alamat_cuti" name="alamat_cuti" placeholder="Alamat Selama Cuti" required></textarea>
                                        <label class="control-label">No Tlp Yang Dapat Di Hubungi</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-phone"></i></div>
                                            <input type="number" id="no_tlp" name="no_tlp" placeholder="Your Phone Number" class="form-control pull-right" required>
                                        </div>
                                        <br>
                                        <button type="button" class="btn btn-block btn-success" id="tambahcuti_verifikasi_btn" name="tambahcuti_verifikasi_btn" onclick="verifikasi_cuti(`#`+this.id, `<?= $id_user; ?>`, `<?= $thn_now; ?>`);">Verifikasi</button>
                                        <div id="assesmentpengganti_div" style="display: none;">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="div" id="sisa_cuti">
                                                        <label class="control-label">Sisa Cuti</label>
                                                        <input class="form-control" type="text" id="sisa_cuti" name="sisa_cuti" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <br>
                                                    <button type="button" class="btn btn-success btn-block" id="modal-add-pengajuan-message" name="modal-add-pengajuan-message" data-message="" onclick="show_modal_detail_sisa_cuti();">Detail Sisa Cuti</button>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="tglbiasa">
                                                        <label class="labeltglbiasa">Periode Cuti</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon"><i class=""></i></div>
                                                            <input type="text" id="list_tgl_cuti" name="list_tgl_cuti" class="form-control pull-right multidatepicker" autocomplete="off">
                                                        </div>
                                                    </div> 
                                                </div>
                                            </div>
                                            <!-- <div class="tglmendadak">
                                                <label class="labeltglmendadak">Periode Cuti Mendadak</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon"><i class=""></i></div>
                                                    <input type="text" id="list_tgl_cutimendadak" name="list_tgl_cutimendadak" class="form-control pull-right multidatepickermendadak" autocomplete="off">
                                                </div>
                                            </div> -->
                                            <!-- <div class="tglcap">
                                                <label>Periode Cuti Alasan Penting</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon"><i class=""></i></div>
                                                    <input type="text" id="list_tgl_cuticap" name="list_tgl_cuticap" class="form-control pull-right multidatepickercap" autocomplete="off">
                                                </div>
                                            </div> -->
                                            <div class="row bukti1">
                                                <div class="col-md-12">
                                                    <label>Bukti 1</label>
                                                    <input type="file" class="form-control" name="bukti1">
                                                </div>
                                            </div>
                                            <div class="row bukti2">
                                                <div class="col-md-12">
                                                    <label>Bukti 2</label>
                                                    <input type="file" class="form-control" name="bukti2">
                                                </div>
                                            </div>
                                            <label>Pengganti Cuti</label>
                                            <select class="form-control select3" id="id_user_pengganti" name="id_user_pengganti" data-placeholder="-Pilih Nama Pengganti-" style="width: 100%;" required>
                                            </select>
                                            <label>Disposisi PJ</label>
                                            <select class="form-control select3" id="id_user_pj" name="id_user_pj" data-placeholder="-Pilih Nama PJ-" style="width: 100%;" required>
                                            </select>
                                            <label>Disposisi Kasatpel</label>
                                            <select class='form-control select3' id="id_user_kasatpel" name='id_user_kasatpel' data-placeholder='-Pilih Nama Kasatpel-' style='width: 100%' required>
                                            </select>
                                            <label>Disposisi Kasie</label>
                                            <select class="form-control select3" id="id_user_kasie" name="id_user_kasie" data-placeholder="-Pilih Nama Kasie-" style="width: 100%;" required>
                                            </select>
                                            <label>Disposisi Kasubbag TU</label>
                                            <select class="form-control select3" id="id_user_ktu" name="id_user_ktu" data-placeholder="-Pilih Nama Kasie-" style="width: 100%;" required>
                                            </select>
                                            <label>Disposisi Direktur</label>
                                            <select class="form-control select3" id="id_user_direktur" name="id_user_direktur" data-placeholder="-Pilih Nama Direktur-" style="width: 100%;" required>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <!-- <input id="btn_send_pengajuan_cuti" type="button" class="btn btn-primary" value="Simpan" onclick="post_pengajuan_cuti(`<?= $id_user; ?>`, `#`+this.id);"> -->
                                    <input id="btn_send_pengajuan_cuti" type="submit" class="btn btn-primary" value="Simpan" style="display: none;">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Tutup Modal Cuti -->

                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Permohonan</th>
                                <th>Pemohon</th>
                                <th>Jenis Cuti</th>
                                <th>Periode Cuti</th>
                                <th>Digantikan Oleh</th>
                                <th>Acc Kepegawaian</th>
                                <th>Acc Pengganti</th>
                                <th>Acc PJ</th>
                                <th>Acc Kasatpel</th>
                                <th>Acc Kasie</th>
                                <th>Acc Kasubag TU</th>
                                <th>Acc Direktur</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $no = 0;
                            $tm_cuti_pemohon_tab1 = bukaquery("
                                SELECT c.id_cuti, c.jumlah_hari, GROUP_CONCAT( date_format(tanggal, '%d-%m-%Y') ORDER BY tanggal ASC) hari_cuti, c.tahun_cuti, c.id_ketidakhadiran, c.tgl_permohonan, s.desc_ketidakhadiran, 
                                c.alasan_cuti, c.alamat_cuti, c.no_tlp, 
                                c.acc_kepegawaian, c.acc_pengganti, c.acc_pj, c.acc_kasatpel, c.acc_kasie, c.acc_ktu, c.acc_direktur, 
                                c.tgl_acc_kepegawaian, c.tgl_acc_pengganti, c.tgl_acc_pj, c.tgl_acc_kasatpel, c.tgl_acc_kasie, c.tgl_acc_ktu, c.tgl_acc_direktur, 
                                c.id_user_pengganti, p1.nama_pegawai AS pemohon, p2.nama_pegawai AS nm_pengganti, c.id_user_pj, c.id_user_kasatpel, c.id_user_kasie, c.alasan, c.no_surti, 
                                c.buk1, c.buk2, c.aktif,
                                i.nama_pegawai AS nm_pengganti,
                                j.nama_pegawai AS nm_pj,
                                k.nama_pegawai AS nm_kasatpel,
                                l.nama_pegawai AS nm_kasie,
                                m.nama_pegawai AS nm_ktu,
                                n.nama_pegawai AS nm_direktur
                                FROM tm_cuti c
                                INNER JOIN tm_shift_ketidakhadiran s ON c.id_ketidakhadiran = s.id_ketidakhadiran
                                INNER JOIN tm_pegawai p1 ON c.id_user = p1.id_user
                                INNER JOIN tm_pegawai p2 ON c.id_user_pengganti = p2.id_user
                                INNER JOIN tm_hari_cuti h ON c.id_cuti = h.id_cuti 
                                LEFT JOIN tm_pegawai AS i ON c.id_user_pengganti = i.id_user
                                LEFT JOIN tm_pegawai AS j ON c.id_user_pj = j.id_user
                                LEFT JOIN tm_pegawai AS k ON c.id_user_kasatpel = k.id_user
                                LEFT JOIN tm_pegawai AS l ON c.id_user_kasie = l.id_user
                                LEFT JOIN tm_pegawai AS m ON c.id_user_ktu = m.id_user
                                LEFT JOIN tm_pegawai AS n ON c.id_user_direktur = n.id_user
                                WHERE
                                    c.id_user = $id_user 
                                    AND (
                                    tahun_cuti = YEAR (now())
                                    OR tahun_cuti = YEAR (now()) - 1) 
                                GROUP BY c.id_cuti 
                                ORDER BY c.tgl_permohonan DESC
                            ");
                            while ($row = fetch_array($tm_cuti_pemohon_tab1)) {
                                $tanggal_explode = explode(',', $row['hari_cuti']);

                                if (strtolower($row['desc_ketidakhadiran']) == 'persalinan') {
                                    $tgl_psln_awal = $tanggal_explode[0];
                                    $tgl_psln_akhir = end($tanggal_explode);
                                    $list_tanggal = "$tgl_psln_awal<br> S/D<br> $tgl_psln_akhir";
                                } else
                                    $list_tanggal = implode('<br>', $tanggal_explode);


                                $no++;
                            ?>



                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo FormatTgl('d/m/Y', $row['tgl_permohonan']); ?></td>
                                    <td><?php echo $row['pemohon']; ?></td>
                                    <td><?php echo $row['desc_ketidakhadiran'] . " (" . $row['tahun_cuti'] . ")"; ?></td>
                                    <td>
                                        <p><?php
                                            // echo "(" . $row['jumlah_hari'] . " Hari) " . konversiTanggalBulan(getOne("select tanggal from tm_hari_cuti where id_cuti='$row[id_cuti]' order by tanggal asc")) . " s/d " . konversiTanggalBulan(getOne("select tanggal from tm_hari_cuti where id_cuti='$row[id_cuti]' order by tanggal desc")) . " " . FormatTgl('Y', getOne("select tanggal from tm_hari_cuti where id_cuti='$row[id_cuti]' order by tanggal desc"))
                                            echo "(" . $row['jumlah_hari'] . " Hari)<br> $list_tanggal";
                                            ?>
                                    </td>
                                    <td><?php echo $row['nm_pengganti']; ?></td>
                                    <td><?php
                                        if ($row['acc_kepegawaian'] == 'T') {
                                            echo "<i class='fa fa-close' title='ditolak'></i>";
                                        } elseif ($row['acc_kepegawaian'] == 'Y') {
                                            echo "<i class='fa fa-check' title='diterima'></i>";
                                        }

                                        if (!empty($row['tgl_acc_kepegawaian']))
                                            echo "<br>(" . $row['tgl_acc_kepegawaian'] . ")";
                                        ?>
                                    </td>
                                    <td><?php
                                        if ($row['acc_pengganti'] == 'T') {
                                            echo "<i class='fa fa-close' title='ditolak'></i>";
                                        } elseif ($row['acc_pengganti'] == 'Y') {
                                            echo "<i class='fa fa-check' title='diterima'></i>";
                                        }

                                        if (!empty($row['tgl_acc_pengganti']))
                                            echo "<br>(" . $row['tgl_acc_pengganti'] . ")";

                                        echo "<br>(" . $row['nm_pengganti'] . ")";
                                        ?>
                                    </td>
                                    <td><?php
                                        if ($row['acc_pengganti'] != '-') {
                                            if ($row['acc_pj'] == 'T') {
                                                echo "<i class='fa fa-close' title='ditolak'></i>";
                                            } elseif ($row['acc_pj'] == 'Y') {
                                                echo "<i class='fa fa-check' title='diterima'></i>";
                                            }

                                            if (!empty($row['tgl_acc_pj']))
                                                echo "<br>(" . $row['tgl_acc_pj'] . ")";
                                        }
                                        echo "<br>(" . $row['nm_pj'] . ")";
                                        ?>
                                    </td>
                                    <td><?php
                                        if ($row['acc_pj'] != '-') {
                                            if ($row['acc_kasatpel'] == 'T') {
                                                echo "<i class='fa fa-close' title='ditolak'></i>";
                                            } elseif ($row['acc_kasatpel'] == 'Y') {
                                                echo "<i class='fa fa-check' title='diterima'></i>";
                                            }

                                            if (!empty($row['tgl_acc_kasatpel']))
                                                echo "<br>(" . $row['tgl_acc_kasatpel'] . ")";
                                        }
                                        echo "<br>(" . $row['nm_kasatpel'] . ")";
                                        ?>
                                    </td>
                                    <td><?php
                                        if ($row['acc_kasie'] == 'T') {
                                            echo "<i class='fa fa-close' title='ditolak'></i>";
                                        } elseif ($row['acc_kasie'] == 'Y') {
                                            echo "<i class='fa fa-check' title='diterima'></i>";
                                        }

                                        if (!empty($row['tgl_acc_kasie']))
                                            echo "<br>(" . $row['tgl_acc_kasie'] . ")";
                                        
                                            echo "<br>(" . $row['nm_kasie'] . ")";
                                        ?>
                                    </td>
                                    <td><?php
                                        if ($row['acc_ktu'] == 'T') {
                                            echo "<i class='fa fa-close' title='ditolak'></i>";
                                        } elseif ($row['acc_ktu'] == 'Y') {
                                            echo "<i class='fa fa-check' title='diterima'></i>";
                                        }

                                        if (!empty($row['tgl_acc_ktu']))
                                            echo "<br>(" . $row['tgl_acc_ktu'] . ")";
                                        echo "<br>(" . $row['nm_ktu'] . ")";
                                        ?>
                                    </td>
                                    <td><?php
                                        if ($row['acc_direktur'] == 'T') {
                                            echo "<i class='fa fa-close' title='ditolak'></i>";
                                        } elseif ($row['acc_direktur'] == 'Y') {
                                            echo "<i class='fa fa-check' title='diterima'></i>";
                                        }

                                        if (!empty($row['tgl_acc_direktur']))
                                            echo "<br>(" . $row['tgl_acc_direktur'] . ")";
                                        echo "<br>(" . $row['nm_direktur'] . ")";
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        if (
                                            $row['aktif'] == 1 
                                            && $row['acc_kasie'] == '-'
                                        ) {
                                            // echo "<span onclick=\"show_modal_update_cuti($row[id_cuti],'$row[id_ketidakhadiran]', 'pengajuan');\" class='btn-xs btn-warning fa fa-edit' data-toggle='modal'></span>";
                                            echo "<span onClick=\"show_modal_hapus_cuti($row[id_cuti], 'pengajuan')\" title='Hapus' class='btn-xs btn-danger fa fa-trash'></span><br>";
                                        } else if ($row['acc_direktur'] == 'Y' && $row['aktif'] == 1) {
                                            echo "<a href='cetak-surat-cuti-$row[id_cuti]' target='_blank'><span data-toggle='modal' title='Cetak' class='btn-xs btn-success fa fa-file-pdf-o'> Cetak Surat</span></a>";
                                        } else if ($row['aktif'] == 0) {
                                            echo "<span data-toggle='modal' data-target='#modal-alasan' onClick=\"alasan_cuti_ditolak('$row[alasan]')\" title='Alasan' class='btn-xs btn-danger fa fa-eye'> Alasan</span><br>";
                                        } else {
                                            echo "<span data-toggle='modal' title='Menunggu Assesment' class='btn-xs btn-info fa fa-refresh'> Menunggu Assesment</span>";
                                        }


                                        if (!empty($row['buk1'])) { ?>
                                            <a href="<?php echo "$url_app/bukti-cuti-1/$row[id_cuti]"; ?>" target="_blank"><span title="Bukti 1" class="btn-xs btn-info fa fa-refresh"> Bukti 1</span></a>
                                        <?php
                                        }
                                        if (!empty($row['buk2'])) { ?>
                                            <a href="<?php echo "$url_app/bukti-cuti-2/$row[id_cuti]"; ?>" target="_blank"><span title="Bukti 2" class="btn-xs btn-info fa fa-refresh"> Bukti 2</span></a>
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






            <!-- KUMPULAN MODAL -->
            <!-- Modal Update Cuti -->
            <div class="modal fade" id="modal-form-update-cuti" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title fa fa-plus"> Update Data Cuti pegawai</h4>
                        </div>
                        <form role="form" name="pilihan" action="<?php echo "$url_api_cuti_pegawai" ?>" id="form-update-cuti" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="edit_pengajuan_cuti">
                            <div class="modal-body">
                                <div class="form-group">
                                    <input type="hidden" id="id_cuti_lama" name="id_cuti_lama">
                                    <input type="hidden" id="jenis_form" name="jenis_form" value="ubah">
                                    <input type="hidden" name="id_user" value="<?php echo $id_user; ?>">
                                    <input type="hidden" name="bukti1" id="bukti1">
                                    <input type="hidden" name="buk1" id="buk1">
                                    <input type="hidden" name="mime1" id="mime1">
                                    <input type="hidden" name="id_user" value="<?php echo $id_user; ?>">
                                    <label class="control-label">Nama Pegawai</label>
                                    <input class="form-control" type="text" id="nm_pegawai" name="nm_pegawai" readonly>
                                    <label class="control-label">Tahun Cuti </label>
                                    <select class="form-control select4" onchange="cuti_pegawai_sisa_cuti(`<?= $id_user; ?>`, `<?= $thn_now; ?>`, `<?= $bln_now; ?>`, `#form-update-cuti`, `<?= $id_ketidakhadiran; ?>`)" id="tahun" name="tahun" style="width: 100%;" required>
                                        <option disabled selected>-</option>
                                        <?php loadThn5(); ?>
                                    </select>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <label>Jenis Cuti</label>
                                            <select class="form-control select4" id="jns_cuti_permohonan" name="jns_cuti_permohonan" style="width: 100%" onchange="cuti_pegawai_sisa_cuti_by_opsi(`<?= $id_user; ?>`, `<?= $thn_now; ?>`, `<?= $bln_now; ?>`, `#form-update-cuti`, ``, ``)" required>
                                                <option disabled selected>-</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="control-label">Sisa (hari)</label>
                                            <input class="form-control" type="text" id="sisa_cuti" name="sisa_cuti" readonly>
                                        </div>
                                    </div>
                                    <div class="row bukti1">
                                        <div class="col-md-12">
                                            <label>Bukti 1</label>
                                            <input type="file" class="form-control" name="bukti1">
                                        </div>
                                    </div>
                                    <div class="row bukti2">
                                        <div class="col-md-12">
                                            <label>Bukti 2</label>
                                            <input type="file" class="form-control" name="bukti2">
                                        </div>
                                    </div>
                                    <label class="control-label">Alasan Cuti</label>
                                    <textarea class="form-control" rows="3" id="alasan_cuti" name="alasan_cuti" placeholder="Alasan.." required></textarea>
                                    <label class="control-label">Alamat Saat Cuti</label>
                                    <textarea class="form-control" rows="3" id="alamat_cuti" name="alamat_cuti" placeholder="Alamat Selama Cuti" required></textarea>
                                    <label class="control-label">No Tlp Yang Dapat Di Hubungi</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-phone"></i></div>
                                        <input type="number" id="no_tlp" name="no_tlp" placeholder="Your Phone Number" class="form-control pull-right" required>
                                    </div>
                                    <div class="tglbiasa">
                                        <label class="labeltglbiasa">Periode Cuti</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class=""></i></div>
                                            <input type="text" id="list_tgl_cuti" name="list_tgl_cuti" class="form-control pull-right multidatepicker" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="tglmendadak">
                                        <label class="labeltglmendadak">Periode Cuti Mendadak</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class=""></i></div>
                                            <input type="text" id="list_tgl_cutimendadak" name="list_tgl_cutimendadak" class="form-control pull-right multidatepickermendadak" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="tglcap">
                                        <label>Periode Cuti Alasan Penting</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class=""></i></div>
                                            <input type="text" id="list_tgl_cuticap" name="list_tgl_cuticap" class="form-control pull-right multidatepickercap" autocomplete="off">
                                        </div>
                                    </div>
                                    <label>Pengganti Cuti</label>
                                    <select class="form-control select4" id="id_user_pengganti" name="id_user_pengganti" data-placeholder="-Pilih Nama Pengganti-" style="width: 100%;" required>
                                    </select>
                                    <label>Disposisi PJ</label>
                                    <select class="form-control select4" id="id_user_pj" name="id_user_pj" data-placeholder="-Pilih Nama PJ-" style="width: 100%;" required>
                                    </select>
                                    <label>Disposisi Kasatpel</label>
                                    <select class='form-control select4' id="id_user_kasatpel" name='id_user_kasatpel' data-placeholder='-Pilih Nama Kasatpel-' style='width: 100%' required>
                                    </select>
                                    <label>Disposisi Kasie</label>
                                    <select class="form-control select4" id="id_user_kasie" name="id_user_kasie" data-placeholder="-Pilih Nama Kasie-" style="width: 100%;" required>
                                    </select>
                                    <label>Disposisi Kasubbag TU</label>
                                    <select class="form-control select4" id="id_user_ktu" name="id_user_ktu" data-placeholder="-Pilih Nama Kasie-" style="width: 100%;" required>
                                    </select>
                                    <label>Disposisi Direktur</label>
                                    <select class="form-control select4" id="id_user_direktur" name="id_user_direktur" data-placeholder="-Pilih Nama Direktur-" style="width: 100%;" required>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <!-- <input id="btn_send_pengajuan_cuti" type="button" class="btn btn-primary" value="Simpan" onclick="post_pengajuan_cuti(`<?= $id_user; ?>`, `#`+this.id);"> -->
                                <input type="submit" class="btn btn-primary" value="Update">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Tutup Modal Cuti -->
        </div>
    <?php
    break;
    case "list-data-semua-cuti-pegawai":
        $tampil_cuti = 'yes';
    ?>
        <div class="row">
            <div class="col-md-6">
                <form method="post" action="#">
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
                                        <select class="form-control select2" name="bulan" data-placeholder="-Pilih Bulan-">
                                            <?php
                                            $loadBln = empty($_POST['bulan']) ? $bulan_skr : $_POST['bulan'];
                                            loadBln('-Pilih Bulan-', $loadBln);
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        Tahun
                                        <br>
                                        <select class="form-control select2" name="tahun" data-placeholder="-Pilih Tahun-">
                                            <?php
                                            $loadThn = empty($_POST['tahun']) ? $tahun_skr : $_POST['tahun'];
                                            loadThn('-Pilih Tahun-', $loadThn);
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row row-no-gutters">
                                    <div class="col-lg-6">
                                        Unit
                                        <select class="form-control select2" name="unit" data-placeholder="-Pilih Unit-">
                                            <?php
                                            $loadUnitByLevel = empty($_POST['unit']) || (!empty($_POST['id_user']) && $_POST['id_user'] != 'all') ? null : $_POST['unit'];
                                            loadUnitByLevel($idlevel, $loadUnitByLevel, $kasatpel_pegawai, $kasie);
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        Nama Pegawai
                                        <select class="form-control select2" name="id_user">
                                            <?php
                                            $query_list_pegawai = bukaquery("select id_user, nama_pegawai from tm_pegawai where status = 'aktif' and nama_pegawai <> '' order by nama_pegawai");

                                            echo "<option value='all'>-Pilih Pegawai-</option>";
                                            while ($list_pegawai = fetch_assoc($query_list_pegawai)) {
                                                $selected = !empty($_POST['id_user']) && $_POST['id_user'] == $list_pegawai['id_user'] ? 'selected' : null;
                                                echo "<option value='$list_pegawai[id_user]' $selected>$list_pegawai[nama_pegawai]</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" value="Cari" class="btn btn-info btn-btn-group-lg">
                                    <i class="fa fa-search">&nbsp;&nbsp;Cari</i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title fa fa-list">&nbsp;REKAP CUTI PEGAWAI</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <h4>Pilih Unit</h4>
                            <div class="row row-no-gutters">
                                <div class="col-lg-12">
                                    Sub Bagian
                                    <select class="form-control select2" id="rekap-cuti_subbagian" name="rekap-cuti_subbagian" data-placeholder="-Pilih Unit-">
                                        <?php
                                        loadSubBagianByLevel('LVL-000011', 'Tata Usaha');
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row row-no-gutters">
                                <div class="col-lg-12">
                                    Tahun
                                    <select class="form-control select2" id="rekap-cuti_tahun" name="rekap-cuti_tahun" data-placeholder="-Pilih Unit-">
                                        <?php
                                        loadThnnow();
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="button" value="Cari" class="btn btn-info btn-btn-group-lg" id="list-data-semua-cuti-pegawai_rekap_btn" name="list-data-semua-cuti-pegawai_rekap_btn" onclick="cari_rekapitulasi_cuti_pegawai(`#`+this.id);">
                                <i class="fa fa-search">&nbsp;&nbsp;Cari</i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box" id="rekap-cuti_box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> REKAP CUTI PEGAWAI</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="box-body table-responsive">
                    <table id="example1" name="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Pegawai</th>
                                <th>Sub Bagian</th>
                                <th>Cuti Sakit</th>
                                <th>Cuti Tahunan</th>
                                <th>CPCB</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="box">
            <div class="modal fade" id="modal-maping-cuti" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h3 class="box-title fa fa-list"> LIST DATA MAPING CUTI YANG DI AMBIL</h3>
                        </div>
                        <div class="modal-body ">
                            <div class="box-body no-padding">
                                <!-- <div id="calendar"></div> -->
                            </div>
                        </div>
                        <div class="modal-footer">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tutup Edit Modal permintaan -->
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST SEMUA PENGAJUAN CUTI PEGAWAI</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>

            <div class="box-body">
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Permohonan</th>
                                <th>Pemohon</th>
                                <th>Jenis Cuti</th>
                                <th>Periode Cuti</th>
                                <th>Digantikan Oleh</th>
                                <th>Acc Kepegawaian</th>
                                <th>Acc Pengganti</th>
                                <th>Acc PJ</th>
                                <th>Acc Kasatpel</th>
                                <th>Acc Kasie</th>
                                <th>Acc Kasubag TU</th>
                                <th>Acc Direktur</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $no = 0;
                            $klausa_bulan = !empty($_POST['bulan']) ? "AND month(tm_hari_cuti.tanggal) = '$_POST[bulan]'" : "AND month(tm_hari_cuti.tanggal) = $bulan_skr";
                            $klausa_tahun = !empty($_POST['tahun']) ? "AND year(tm_hari_cuti.tanggal) = $_POST[tahun]" : "AND year(tm_hari_cuti.tanggal) = $tahun_skr";
                            $klausa_unit = !empty($_POST['unit']) && $_POST['unit'] != 'all' ? "AND b.id_unit = '$_POST[unit]'" : null;
                            $klausa_id_user = !empty($_POST['id_user']) && $_POST['id_user'] != 'all' ? "AND b.id_user = '$_POST[id_user]'" : null;
                            $klausa_unit = empty($klausa_id_user) ? $klausa_unit : null;

                            $tm_cuti_pemohon_tab2 = bukaquery("
                                SELECT
                                    tm_cuti.id_cuti, tm_cuti.jumlah_hari, tm_cuti.tahun_cuti, group_concat(date_format(tm_hari_cuti.tanggal, '%d-%m-%Y') ORDER BY tm_hari_cuti.tanggal ASC) hari_cuti,
                                    tm_cuti.tgl_permohonan, tm_shift_ketidakhadiran.id_ketidakhadiran, tm_shift_ketidakhadiran.desc_ketidakhadiran, tm_cuti.alasan_cuti, tm_cuti.alamat_cuti, tm_cuti.no_tlp,
                                    tm_cuti.acc_kepegawaian, tm_cuti.acc_pengganti, tm_cuti.acc_pj, tm_cuti.acc_kasatpel, tm_cuti.acc_kasie, tm_cuti.acc_ktu, tm_cuti.acc_direktur,
                                    tm_cuti.tgl_acc_kepegawaian, tm_cuti.tgl_acc_pengganti, tm_cuti.tgl_acc_pj, tm_cuti.tgl_acc_kasatpel, tm_cuti.tgl_acc_kasie, tm_cuti.tgl_acc_ktu, tm_cuti.tgl_acc_direktur,
                                    tm_cuti.id_user_pengganti, tm_cuti.id_user_pj, tm_cuti.id_user_kasatpel, tm_cuti.id_user_kasie,
                                    b.nama_pegawai AS pemohon, c.nama_pegawai AS nm_pengganti, d.nama_pegawai AS nm_pj, e.nama_pegawai AS nm_kasatpel, f.nama_pegawai AS nm_kasie, g.nama_pegawai AS nm_ktu, h.nama_pegawai AS nm_direktur,
                                    tm_cuti.alasan, tm_cuti.no_surti, tm_cuti.bukti1, tm_cuti.bukti2
                                FROM tm_cuti
                                INNER JOIN tm_shift_ketidakhadiran ON tm_cuti.id_ketidakhadiran = tm_shift_ketidakhadiran.id_ketidakhadiran
                                INNER JOIN tm_pegawai b ON tm_cuti.id_user = b.id_user
                                INNER JOIN tm_hari_cuti ON tm_cuti.id_cuti = tm_hari_cuti.id_cuti
                                LEFT JOIN tm_pegawai c ON tm_cuti.id_user_pengganti = c.id_user
                                LEFT JOIN tm_pegawai d ON tm_cuti.id_user_pj = d.id_user
                                LEFT JOIN tm_pegawai e ON tm_cuti.id_user_kasatpel = e.id_user
                                LEFT JOIN tm_pegawai f ON tm_cuti.id_user_kasie = f.id_user and f.status = 'aktif'
                                LEFT JOIN tm_pegawai g ON tm_cuti.id_user_ktu = g.id_user and g.status = 'aktif'
                                LEFT JOIN tm_pegawai h ON tm_cuti.id_user_direktur = h.id_user and h.status = 'aktif'
                                where 
                                    tm_cuti.tahun_cuti = year(now())
                                    $klausa_bulan
                                    $klausa_tahun
                                    $klausa_unit 
                                    $klausa_id_user
                                GROUP BY tm_cuti.id_cuti 
                                order by tm_hari_cuti.tanggal desc
                            ");
                            while ($row = fetch_array($tm_cuti_pemohon_tab2)) {
                                $tanggal_explode = explode(',', $row['hari_cuti']);

                                if (strtolower($row['desc_ketidakhadiran']) == 'persalinan') {
                                    $tanggal_explode = explode(',', $row['hari_cuti']);
                                    $tgl_psln_awal = $tanggal_explode[0];
                                    $tgl_psln_akhir = end($tanggal_explode);
                                    $list_tanggal = "$tgl_psln_awal<br> S/D<br> $tgl_psln_akhir";
                                } else {
                                    $list_tanggal = implode('<br>', $tanggal_explode);
                                }

                                $no++;
                            ?>
                                <!-- Assesment OK -->
                                <div class="modal fade" id="modal-kepegawaian-assesment-<?php echo $row['id_cuti']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=cuti-pegawai&act=assesment-pengajuan-cuti-kepegawaian&id=' . $row['id_cuti'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Assesment Pengajuan Cuti</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" id="id_user" name="id_user" value="<?= $id_user; ?>">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label>Anda akan MENYETUJUI pengajuan cuti <?php echo getOne("select tm_pegawai.nama_pegawai from tm_pegawai where tm_pegawai.id_user='$row[id_user]'"); ?> periode <?php echo "(" . $row['jumlah_hari'] . " Hari) " . $text_tgl_cuti2; ?> ?</label>
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
                                <!-- Tutup Assestmen OK -->
                                <!-- Assesment Tolak -->
                                <div class="modal fade" id="modal-reject-<?php echo $row['id_cuti']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=cuti-pegawai&act=reject-pengajuan-cuti&id=' . $row['id_cuti'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Reject Pengajuan Cuti</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <input class="form-control" type="hidden" id="id_user" name="id_user" value="<?= $id_user; ?>">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label>Anda akan MENOLAK pengajuan cuti, sebagai penggantinya <?php echo getOne("select tm_pegawai.nama_pegawai from tm_pegawai where tm_pegawai.id_user='$row[id_user]'"); ?> periode <?php echo "(" . $row['jumlah_hari'] . " Hari) " . $text_tgl_cuti2; ?> ?</label>
                                                        </div>
                                                        <br>
                                                        <label class="control-label">Catatan (opsional)</label>
                                                        <input class="form-control" type="text" id="reject_notes" name="reject_notes">
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
                                <!-- Tutup Assestmen Tolak -->
                                <!-- Edit Modal Cuti -->
                                <div class="modal fade" id="modal-acc-<?php echo $row['id_cuti']; ?>" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo $aksi . paramEncrypt('module=cuti-pegawai&act=acc-pengajuan-cuti&id=' . $row['id_cuti'] . ''); ?>" method="POST" role="form">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">ACC Pengajuan Cuti</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label class="control-label">Tanggal Permohonan (Tgl/Bln/Thn)</label>
                                                            <input type="text" class="form-control" value="<?php echo FormatTgl('d-m-Y', $row['tgl_permohonan']); ?>" disabled>

                                                            <label class="control-label">Jenis Cuti</label>
                                                            <input type="text" class="form-control" value="<?php echo $row['desc_ketidakhadiran'] ?>" disabled>

                                                            <label class="control-label">Alasan Cuti</label>
                                                            <textarea class="form-control" rows="3" disabled><?php echo $row['alasan_cuti']; ?></textarea>

                                                            <label class="control-label">Alamat Saat Cuti</label>
                                                            <textarea class="form-control" rows="3" disabled><?php echo $row['alamat_cuti']; ?></textarea>

                                                            <label class="control-label">No Tlp Yang Dapat Di Hubungi</label>
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-phone"></i>
                                                                </div>
                                                                <input type="number" class="form-control" value="<?php echo $row['no_tlp']; ?>" disabled>
                                                            </div>

                                                            <label>Pengganti Cuti</label>
                                                            <input type="text" class="form-control" value="<?php echo $row['nm_pengganti'] ?>" disabled>

                                                            <label>Disposisi PJ</label>
                                                            <input type="text" class="form-control" value="<?php echo $row['nm_pj'] ?>" disabled>

                                                            <label>Disposisi Kasatpel</label>
                                                            <input type="text" class="form-control" value="<?php echo $row['nm_kasatpel'] ?>" disabled>

                                                            <label>Disposisi Kasie</label>
                                                            <input type="text" class="form-control" value="<?php echo $row['nm_kasie'] ?>" disabled>

                                                            <label>Disposisi KTU</label>
                                                            <input type="text" class="form-control" value="<?php echo $row['nm_ktu'] ?>" disabled>

                                                            <label>Disposisi Direktur</label>
                                                            <input type="text" class="form-control" value="<?php echo $row['nm_direktur'] ?>" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" name="acc" class="btn btn-primary">Acc</button>
                                                    <button type="submit" name="tolak" class="btn btn-danger">Batal</button>
                                                    <button type="submit" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- Edit Modal Cuti -->

                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo FormatTgl('d/m/Y', $row['tgl_permohonan']); ?></td>
                                    <td><?php echo $row['pemohon']; ?></td>
                                    <td><?php echo $row['desc_ketidakhadiran'] . " (" . $row['tahun_cuti'] . ")"; ?></td>
                                    <td>
                                        <p><?php
                                            // echo "(" . $row['jumlah_hari'] . " Hari) " . konversiTanggalBulan(getOne("select tanggal from tm_hari_cuti where id_cuti='$row[id_cuti]' order by tanggal asc")) . " s/d " . konversiTanggalBulan(getOne("select tanggal from tm_hari_cuti where id_cuti='$row[id_cuti]' order by tanggal desc")) . " " . FormatTgl('Y', getOne("select tanggal from tm_hari_cuti where id_cuti='$row[id_cuti]' order by tanggal desc"))
                                            echo "(" . $row['jumlah_hari'] . " Hari)<br> $list_tanggal";
                                            ?>
                                    </td>
                                    <td><?php echo $row['nm_pengganti']; ?></td>
                                    <td><?php
                                        if ($row['acc_kepegawaian'] == 'T') {
                                            echo "<i class='fa fa-close' title='ditolak'></i>";
                                        } elseif ($row['acc_kepegawaian'] == 'Y') {
                                            echo "<i class='fa fa-check' title='diterima'></i>";
                                        }

                                        if (!empty($row['tgl_acc_kepegawaian']))
                                            echo "<br>(" . $row['tgl_acc_kepegawaian'] . ")";
                                        ?>
                                    </td>
                                    <td><?php
                                        if ($row['acc_pengganti'] == 'T') {
                                            echo "<i class='fa fa-close' title='ditolak'></i>";
                                        } elseif ($row['acc_pengganti'] == 'Y') {
                                            echo "<i class='fa fa-check' title='diterima'></i>";
                                        }

                                        if (!empty($row['tgl_acc_pengganti']))
                                            echo "<br>(" . $row['tgl_acc_pengganti'] . ")";
                                        ?>
                                    </td>
                                    <td><?php
                                        if ($row['acc_pengganti'] != '-') {
                                            if ($row['acc_pj'] == 'T') {
                                                echo "<i class='fa fa-close' title='ditolak'></i>";
                                            } elseif ($row['acc_pj'] == 'Y') {
                                                echo "<i class='fa fa-check' title='diterima'></i>";
                                            }

                                            if (!empty($row['tgl_acc_pj']))
                                                echo "<br>(" . $row['tgl_acc_pj'] . ")";
                                        }
                                        ?>
                                    </td>
                                    <td><?php
                                        if ($row['acc_kasatpel'] == 'T') {
                                            echo "<i class='fa fa-close' title='ditolak'></i>";
                                        } elseif ($row['acc_kasatpel'] == 'Y') {
                                            echo "<i class='fa fa-check' title='diterima'></i>";
                                        }

                                        if (!empty($row['tgl_acc_kasatpel']))
                                            echo "<br>(" . $row['tgl_acc_kasatpel'] . ")";
                                        ?>
                                    </td>
                                    <td><?php
                                        if ($row['acc_kasie'] == 'T') {
                                            echo "<i class='fa fa-close' title='ditolak'></i>";
                                        } elseif ($row['acc_kasie'] == 'Y') {
                                            echo "<i class='fa fa-check' title='diterima'></i>";
                                        }

                                        if (!empty($row['tgl_acc_kasie']))
                                            echo "<br>(" . $row['tgl_acc_kasie'] . ")";
                                        ?>
                                    </td>
                                    <td><?php
                                        if ($row['acc_ktu'] == 'T') {
                                            echo "<i class='fa fa-close' title='ditolak'></i>";
                                        } elseif ($row['acc_ktu'] == 'Y') {
                                            echo "<i class='fa fa-check' title='diterima'></i>";
                                        }

                                        if (!empty($row['tgl_acc_ktu']))
                                            echo "<br>(" . $row['tgl_acc_ktu'] . ")";
                                        ?>
                                    </td>
                                    <td><?php
                                        if ($row['acc_direktur'] == 'T') {
                                            echo "<i class='fa fa-close' title='ditolak'></i>";
                                        } elseif ($row['acc_direktur'] == 'Y') {
                                            echo "<i class='fa fa-check' title='diterima'></i>";
                                        }

                                        if (!empty($row['tgl_acc_direktur']))
                                            echo "<br>(" . $row['tgl_acc_direktur'] . ")";
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        if ($row['acc_pengganti'] == 'Y' and $row['acc_pj'] == 'Y' and $row['acc_kasatpel'] == 'Y' and $row['acc_kasie'] == 'Y' and $row['acc_ktu'] == 'Y' and $row['acc_direktur'] == 'Y')
                                            echo "<a href='cetak-surat-cuti-$row[id_cuti]' target='_blank'><span data-toggle='modal' title='Cetak' class='btn-xs btn-success fa fa-file-pdf-o'> Cetak Surat</span></a>";
                                        elseif ($row['acc_pengganti'] == 'Y' and $row['acc_pj'] == 'Y' and $row['acc_kasie'] == 'T') {
                                            // if ($row['alasan'] == 'PRB') {
                                            //     $perihal = 'Perubahan Tanggal ' . ucwords(strtolower($$row['desc_ketidakhadiran']));
                                            // } else {
                                            //     $perihal = 'Penangguhan ' . ucwords(strtolower($$row['desc_ketidakhadiran']));
                                            // }
                                            echo "<span data-toggle='modal' data-target='#modal-alasan' onClick=\"alasan_cuti_ditolak('$row[alasan]')\" title='Alasan' class='btn-xs btn-danger fa fa-eye'> Alasan</span><br>";
                                        } else {
                                            if ($row['acc_pj'] == '-' && $row['id_user_pj'] == $_SESSION['id_user']) {
                                                if ($row['acc_pengganti'] == '-')
                                                    echo "<span data-toggle='modal' data-target='#modal-reject-$row[id_cuti]' title='Tolak' class='btn-xs btn-danger fa fa-close'></span>
                                                    <span data-toggle='modal' data-target='#modal-kepegawaian-assesment-$row[id_cuti]' title='Setujui' class='btn-xs btn-success fa fa-check'></span>";
                                                elseif ($row['acc_pengganti'] == 'T')
                                                    echo "<span title='Ditolak' class='btn-xs btn-warning fa fa-close'></span>";
                                                else
                                                    echo "<span title='Disetujui' class='btn-xs btn-success fa fa-check'></span>";
                                            } else if ($row['acc_pengganti'] == 'Y' or $row['acc_pj'] == 'Y' or $row['acc_kasatpel'] == 'Y' or $row['acc_kasie'] == 'Y')
                                                echo "<span data-toggle='modal' title='Menunggu Assesment' class='btn-xs btn-info fa fa-refresh'> Menunggu Assesment</span>
                                                <span onClick=\"show_modal_hapus_cuti($row[id_cuti], 'semua')\" title='Hapus' class='btn-xs btn-danger fa fa-trash'></span><br>";
                                            else
                                                echo "<span onclick=\"show_modal_update_cuti($row[id_cuti],'$row[id_ketidakhadiran]');\" class='btn-xs btn-warning fa fa-edit' data-toggle='modal'></span>
                                                <span onClick=\"show_modal_hapus_cuti($row[id_cuti], 'semua')\" title='Hapus' class='btn-xs btn-danger fa fa-trash'></span><br>";
                                        }

                                        if (!empty($row['buk1']))
                                            echo "<a href='$url_app/bukti-cuti-1/$row[id_cuti]' target='_blank'><span title='Bukti 1' class='btn-xs btn-info fa fa-refresh'> Bukti 1</span></a><br>";

                                        if (!empty($row['buk2']))
                                            echo "<a href='$url_app/bukti-cuti-2/$row[id_cuti]' target='_blank'><span title='Bukti 2' class='btn-xs btn-info fa fa-refresh'> Bukti 2</span></a><br>";

                                        if (in_array($row['id_ketidakhadiran'], ['AKT-000011', 'AKT-000018']) !== false && $row['acc_kepegawaian'] == '-')
                                            echo "<span data-toggle='modal' data-target='#modal-acc-$row[id_cuti]' title='Acc' class='btn-xs btn-warning fa fa-edit'>Acc</span><br>";
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
        </div>
    <?php
        break;

    case "list-data-maping-cuti-pegawai":
        $tampil_cuti = 'yes';
        $cuti = bukaquery("
        SELECT
            tm_hari_cuti.id_cuti,
            tm_hari_cuti.tanggal,
            tm_pegawai.nama_pegawai 
        FROM tm_hari_cuti
        INNER JOIN tm_cuti ON tm_cuti.id_cuti = tm_hari_cuti.id_cuti
        INNER JOIN tm_pegawai ON tm_pegawai.id_user = tm_cuti.id_user
        ");
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST DATA MAPING CUTI PEGAWAI</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <!-- <div id="calendar"></div> -->
            </div>
        </div>

    <?php
        break;

    case "list-data-assesment-kepeg":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST DATA ASSESMENT KEPEGAWAIAN</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab"> PERMOHONAN</a></li>
                        <li><a href="#tab_2" data-toggle="tab">HISTORY ASSESMENT</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <!-- /.box-header -->
                            <div class="box-body table-responsive">
                                <table id="example_normal" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Pemohon</th>
                                            <th>Tanggal Permohonan</th>
                                            <th>Jenis Cuti</th>
                                            <th>Periode Cuti</th>
                                            <th>Alasan Cuti</th>
                                            <th>Acc Pengganti</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 0;

                                        $tm_cuti_kepeg_tab1 = bukaquery("
                                            SELECT
                                                c.id_cuti, 
                                                c.tgl_permohonan, 
                                                c.id_user, 
                                                p.nama_pegawai, 
                                                c.tahun_cuti, 
                                                sk.desc_ketidakhadiran, 
                                                c.alasan_cuti, 
                                                c.acc_pengganti, 
                                                c.acc_kepegawaian, 
                                                IF(c.buk1 is not null,1,null) buk1, 
                                                IF(c.buk2 is not null,1,null) buk2, 
                                                GROUP_CONCAT( date_format(hc.tanggal, '%d-%m-%Y') ORDER BY hc.tanggal ASC ) hari_cuti  
                                            FROM tm_cuti c
                                            INNER JOIN tm_pegawai p ON c.id_user = p.id_user
                                            INNER JOIN tm_shift_ketidakhadiran sk ON c.id_ketidakhadiran = sk.id_ketidakhadiran
                                            INNER JOIN tm_hari_cuti hc ON c.id_cuti = hc.id_cuti 
                                            WHERE
                                                c.acc_kepegawaian = '-' 
                                                AND c.tahun_cuti = YEAR (now()) 
                                            GROUP BY c.id_cuti 
                                            ORDER BY c.id_cuti DESC
                                        ");
                                        while ($row = fetch_array($tm_cuti_kepeg_tab1)) {

                                            $tanggal_explode = explode(',', $row['hari_cuti']);
                                            $tanggal1 = date_format(date_create($tanggal_explode[0]), 'd-m-Y');
                                            $tanggal2 = date_format(date_create(end($tanggal_explode)), 'd-m-Y');
                                            $lama_cuti = HitungHari(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10)))) - HitungHariMinggu(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10))));

                                            if (strtolower($row['desc_ketidakhadiran']) == 'persalinan') {
                                                $tanggal_explode = explode(',', $row['hari_cuti']);
                                                $tgl_psln_awal = $tanggal_explode[0];
                                                $tgl_psln_akhir = end($tanggal_explode);
                                                $list_tanggal = "$tgl_psln_awal<br> S/D<br> $tgl_psln_akhir";
                                            } else {
                                                $list_tanggal = str_replace(',', '<br>', $row['hari_cuti']);
                                            }

                                            $no++;
                                        ?>

                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $row['nama_pegawai']; ?></td>
                                                <td><?php echo FormatTgl('d/m/Y', $row['tgl_permohonan']); ?></td>
                                                <td><?php echo $row['desc_ketidakhadiran'] . " (" . $row['tahun_cuti'] . ")"; ?></td>
                                                <td><?php echo "(" . $row['jumlah_hari'] . " Hari)<br>" . $list_tanggal; ?></td>
                                                <td><?php echo $row['alasan_cuti']; ?></td>
                                                <td>
                                                    <span data-id='<?php echo $row['id_cuti']; ?>' data-id_user='<?php echo $row['id_user']; ?>' data-list_tanggal='<?= $list_tanggal; ?>' data-kolom_acc='acc_kepegawaian' data-kolom_tgl_acc='tgl_acc_kepegawaian' data-jenis_cuti='<?php echo $row['desc_ketidakhadiran']; ?>' data-link_header='module=cuti-pegawai&act=list-data-assesment-kepeg' title="Modal Asesmen" class="btn-xs btn-danger fa fa-close show-modal-reject-cuti"></span>
                                                    <span data-id='<?php echo $row['id_cuti']; ?>' data-id_user='<?php echo $row['id_user']; ?>' data-list_tanggal='<?= $list_tanggal; ?>' data-kolom_acc='acc_kepegawaian' data-kolom_tgl_acc='tgl_acc_kepegawaian' data-jenis_cuti='<?php echo $row['desc_ketidakhadiran']; ?>' data-link_header='module=cuti-pegawai&act=list-data-assesment-kepeg' title="Modal Asesmen" class="btn-xs btn-success fa fa-check show-modal-acc-cuti"></span>

                                                    <?php
                                                    if (!empty($row['buk1']) || !empty($row['buk1']))
                                                        echo "<br>";

                                                    if (!empty($row['buk1'])) { ?>
                                                        <a href="<?php echo "$url_app/bukti-cuti-1/$row[id_cuti]"; ?>" target="_blank"><span title="Bukti 1" class="btn-xs btn-info fa fa-refresh"> Bukti 1</span></a>
                                                    <?php
                                                    }
                                                    if (!empty($row['buk2'])) { ?>
                                                        <br>
                                                        <a href="<?php echo "$url_app/bukti-cuti-2/$row[id_cuti]"; ?>" target="_blank"><span title="Bukti 2" class="btn-xs btn-info fa fa-refresh"> Bukti 2</span></a>
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
                        <div class="tab-pane" id="tab_2">
                            <!-- /.box-header -->
                            <div class="box-body table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Pemohon</th>
                                            <th>Tgl Permohonan</th>
                                            <th>Tgl Asesmen</th>
                                            <th>Jenis Cuti</th>
                                            <th>Periode Cuti</th>
                                            <th>Alasan</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 0;
                                        $tm_cuti_kepeg_tab2 = bukaquery("
                                            SELECT
                                                tm_cuti.id_cuti,
                                                tm_cuti.tgl_permohonan,
                                                tm_pegawai.nama_pegawai,
                                                tm_cuti.jumlah_hari,
                                                tm_shift_ketidakhadiran.desc_ketidakhadiran,
                                                tm_cuti.tahun_cuti,
                                                tm_cuti.alasan_cuti,
                                                tm_cuti.tgl_acc_pengganti,
                                                tm_cuti.acc_pengganti,
                                                acc_kepegawaian,
                                                GROUP_CONCAT( date_format(tm_hari_cuti.tanggal, '%d-%m-%Y') ORDER BY tm_hari_cuti.tanggal ASC ) hari_cuti  
                                            FROM tm_cuti
                                            INNER JOIN tm_pegawai ON tm_cuti.id_user = tm_pegawai.id_user
                                            INNER JOIN tm_shift_ketidakhadiran ON tm_cuti.id_ketidakhadiran = tm_shift_ketidakhadiran.id_ketidakhadiran
                                            INNER JOIN tm_hari_cuti ON tm_cuti.id_cuti = tm_hari_cuti.id_cuti 
                                            WHERE
                                                tm_cuti.tahun_cuti = YEAR (
                                                now()) 
                                                AND ( tm_cuti.id_ketidakhadiran = 'AKT-000011' OR tm_cuti.id_ketidakhadiran = 'AKT-000018' ) 
                                                AND acc_kepegawaian <> '-' 
                                            GROUP BY
                                                tm_cuti.id_cuti 
                                            ORDER BY
                                                tm_cuti.id_cuti desc
                                        ");
                                        while ($row = fetch_array($tm_cuti_kepeg_tab2)) {

                                            $tanggal_explode = explode(',', $row['hari_cuti']);
                                            $tanggal1 = date_format(date_create($tanggal_explode[0]), 'd-m-Y');
                                            $tanggal2 = date_format(date_create(end($tanggal_explode)), 'd-m-Y');
                                            $lama_cuti = HitungHari(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10)))) - HitungHariMinggu(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10))));


                                            if (strtolower($row['desc_ketidakhadiran']) == 'persalinan') {
                                                $tanggal_explode = explode(',', $row['hari_cuti']);
                                                $tgl_psln_awal = $tanggal_explode[0];
                                                $tgl_psln_akhir = end($tanggal_explode);
                                                $list_tanggal = "$tgl_psln_awal<br> S/D<br> $tgl_psln_akhir";
                                            } else {
                                                $list_tanggal = str_replace(',', '<br>', $row['hari_cuti']);
                                            }

                                            $no++;
                                        ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $row['nama_pegawai']; ?></td>
                                                <td><?php echo FormatTgl('d/m/Y', $row['tgl_permohonan']); ?></td>
                                                <td><?php echo FormatTgl('d/m/Y H:i:s', $row['tgl_acc_pengganti']); ?></td>
                                                <td><?php echo $row['desc_ketidakhadiran'] . " (" . $row['tahun_cuti'] . ")"; ?></td>
                                                <td><?php echo "(" . $row['jumlah_hari'] . " Hari)<br>" . $list_tanggal; ?></td>
                                                <td><?php echo $row['alasan_cuti']; ?></td>
                                                <td>
                                                    <?php if ($row['acc_kepegawaian'] == 'T') { ?>
                                                        <span title="Ditolak" class="btn-xs btn-danger fa fa-close"></span>
                                                    <?php } else { ?>
                                                        <span title="Disetujui" class="btn-xs btn-success fa fa-check"></span>
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
                </div>
            </div>
        </div>
    <?php
        break;

    case "list-data-assesment-pengganti-cuti-pegawai":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST DATA PERMOHONAN PENGGANTI CUTI PEGAWAI</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab"> PERMOHONAN</a></li>
                        <li><a href="#tab_2" data-toggle="tab">HISTORY ASSESMENT</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <!-- /.box-header -->
                            <div class="box-body table-responsive">
                                <table id="example_normal" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Pemohon</th>
                                            <th>Tanggal Permohonan</th>
                                            <th>Jenis Cuti</th>
                                            <th>Periode Cuti</th>
                                            <th>Alasan Cuti</th>
                                            <th>Acc Pengganti</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 0;

                                        $tm_cuti_pengganti_tab1 = bukaquery2("
                                            SELECT
                                                c.id_cuti, 
                                                c.tgl_permohonan, 
                                                p.id_user,
                                                p.nama_pegawai, 
                                                c.jumlah_hari,
                                                c.tahun_cuti, 
                                                sk.desc_ketidakhadiran, 
                                                c.alasan_cuti, 
                                                c.acc_pengganti, 
                                                c.acc_kepegawaian, 
                                                IF(c.buk1 is not null,1,null) buk1, 
                                                IF(c.buk2 is not null,1,null) buk2, 
                                                GROUP_CONCAT( date_format(hc.tanggal, '%d-%m-%Y') ORDER BY hc.tanggal ASC ) hari_cuti  
                                            FROM tm_cuti c
                                            INNER JOIN tm_pegawai p ON c.id_user = p.id_user
                                            INNER JOIN tm_shift_ketidakhadiran sk ON c.id_ketidakhadiran = sk.id_ketidakhadiran
                                            INNER JOIN tm_hari_cuti hc ON c.id_cuti = hc.id_cuti 
                                            WHERE
                                                c.aktif = 1
                                                AND c.id_user_pengganti = '$id_user' 
                                                AND c.acc_pengganti = '-' 
                                                AND c.acc_kepegawaian = 'Y' 
                                            GROUP BY c.id_cuti 
                                            ORDER BY c.id_cuti DESC
                                        ");

                                        while ($row = fetch_array($tm_cuti_pengganti_tab1)) {

                                            $tanggal_explode = explode(',', $row['hari_cuti']);
                                            $tanggal1 = date_format(date_create($tanggal_explode[0]), 'd-m-Y');
                                            $tanggal2 = date_format(date_create(end($tanggal_explode)), 'd-m-Y');
                                            $lama_cuti = HitungHari(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10)))) - HitungHariMinggu(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10))));


                                            if (strtolower($row['desc_ketidakhadiran']) == 'persalinan') {
                                                $tanggal_explode = explode(',', $row['hari_cuti']);
                                                $tgl_psln_awal = $tanggal_explode[0];
                                                $tgl_psln_akhir = end($tanggal_explode);
                                                $list_tanggal = "$tgl_psln_awal<br> S/D<br> $tgl_psln_akhir";
                                            } else
                                                $list_tanggal = implode('<br>', $tanggal_explode);

                                            $no++;
                                        ?>

                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $row['nama_pegawai']; ?></td>
                                                <td><?php echo FormatTgl('d/m/Y', $row['tgl_permohonan']); ?></td>
                                                <td><?php echo $row['desc_ketidakhadiran'] . " (" . $row['tahun_cuti'] . ")"; ?></td>
                                                <td><?php echo "(" . $row['jumlah_hari'] . " Hari)<br>" . $list_tanggal; ?></td>
                                                <td><?php echo $row['alasan_cuti']; ?></td>
                                                <td>
                                                    <span data-id='<?php echo $row['id_cuti']; ?>' data-id_user='<?php echo $row['id_user']; ?>' data-list_tanggal='<?= $list_tanggal; ?>' data-kolom_acc='acc_pengganti' data-kolom_tgl_acc='tgl_acc_pengganti' data-jenis_cuti='<?php echo $row['desc_ketidakhadiran']; ?>' data-link_header='module=cuti-pegawai&act=list-data-assesment-pengganti-cuti-pegawai' title="Modal Asesmen" class="btn-xs btn-danger fa fa-close show-modal-reject-cuti"></span>
                                                    <span data-id='<?php echo $row['id_cuti']; ?>' data-id_user='<?php echo $row['id_user']; ?>' data-list_tanggal='<?= $list_tanggal; ?>' data-kolom_acc='acc_pengganti' data-kolom_tgl_acc='tgl_acc_pengganti' data-jenis_cuti='<?php echo $row['desc_ketidakhadiran']; ?>' data-link_header='module=cuti-pegawai&act=list-data-assesment-pengganti-cuti-pegawai' title="Modal Asesmen" class="btn-xs btn-success fa fa-check show-modal-acc-cuti"></span>

                                                    <?php
                                                    if (!empty($row['buk1']) || !empty($row['buk1']))
                                                        echo "<br>";

                                                    if (!empty($row['buk1'])) { ?>
                                                        <a href="<?php echo "$url_app/bukti-cuti-1/$row[id_cuti]"; ?>" target="_blank"><span title="Bukti 1" class="btn-xs btn-info fa fa-refresh"> Bukti 1</span></a>
                                                    <?php
                                                    }
                                                    if (!empty($row['buk2'])) { ?>
                                                        <br>
                                                        <a href="<?php echo "$url_app/bukti-cuti-2/$row[id_cuti]"; ?>" target="_blank"><span title="Bukti 2" class="btn-xs btn-info fa fa-refresh"> Bukti 2</span></a>
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
                        <div class="tab-pane" id="tab_2">
                            <!-- /.box-header -->
                            <div class="box-body table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Pemohon</th>
                                            <th>Tanggal Permohonan</th>
                                            <th>Tanggal Acc</th>
                                            <th>Jenis Cuti</th>
                                            <th>Periode Cuti</th>
                                            <th>Alasan Cuti</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 0;
                                        $tm_cuti_pengganti_tab2 = bukaquery("
                                            SELECT
                                                tm_cuti.id_cuti,
                                                tm_cuti.tgl_permohonan,
                                                tm_pegawai.nama_pegawai,
                                                tm_cuti.jumlah_hari,
                                                tm_shift_ketidakhadiran.desc_ketidakhadiran,
                                                tm_cuti.tahun_cuti,
                                                tm_cuti.alasan_cuti,
                                                tm_cuti.tgl_acc_pengganti,
                                                tm_cuti.acc_pengganti,
                                                GROUP_CONCAT( date_format(tm_hari_cuti.tanggal, '%d-%m-%Y') ORDER BY tm_hari_cuti.tanggal ASC ) hari_cuti  
                                            FROM tm_cuti
                                            INNER JOIN tm_pegawai ON tm_cuti.id_user = tm_pegawai.id_user
                                            INNER JOIN tm_shift_ketidakhadiran ON tm_cuti.id_ketidakhadiran = tm_shift_ketidakhadiran.id_ketidakhadiran
                                            INNER JOIN tm_hari_cuti ON tm_cuti.id_cuti = tm_hari_cuti.id_cuti 
                                            WHERE
                                                tm_cuti.id_user_pengganti = '$id_user' 
                                                AND tm_cuti.acc_pengganti != '-' 
                                                AND YEAR ( tm_cuti.tgl_permohonan )= '$thn_now'
                                            GROUP BY
                                                tm_cuti.id_cuti 
                                            ORDER BY
                                                tm_cuti.id_cuti desc
                                        ");
                                        while ($row = fetch_array($tm_cuti_pengganti_tab2)) {

                                            $tanggal_explode = explode(',', $row['hari_cuti']);
                                            $tanggal1 = date_format(date_create($tanggal_explode[0]), 'd-m-Y');
                                            $tanggal2 = date_format(date_create(end($tanggal_explode)), 'd-m-Y');
                                            $lama_cuti = HitungHari(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10)))) - HitungHariMinggu(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10))));


                                            if (strtolower($row['desc_ketidakhadiran']) == 'persalinan') {
                                                $tanggal_explode = explode(',', $row['hari_cuti']);
                                                $tgl_psln_awal = $tanggal_explode[0];
                                                $tgl_psln_akhir = end($tanggal_explode);
                                                $list_tanggal = "$tgl_psln_awal<br> S/D<br> $tgl_psln_akhir";
                                            } else {
                                                $list_tanggal = str_replace(',', '<br>', $row['hari_cuti']);
                                            }

                                            $no++;
                                        ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $row['nama_pegawai']; ?></td>
                                                <td><?php echo FormatTgl('d/m/Y', $row['tgl_permohonan']); ?></td>
                                                <td><?php echo FormatTgl('d/m/Y H:i:s', $row['tgl_acc_pengganti']); ?></td>
                                                <td><?php echo $row['desc_ketidakhadiran'] . " (" . $row['tahun_cuti'] . ")"; ?></td>
                                                <td><?php echo "(" . $row['jumlah_hari'] . " Hari)<br>" . $list_tanggal; ?></td>
                                                <td><?php echo $row['alasan_cuti']; ?></td>
                                                <td>
                                                    <?php if ($row['acc_pengganti'] == 'T') { ?>
                                                        <span title="Ditolak" class="btn-xs btn-danger fa fa-close"></span>
                                                    <?php } else { ?>
                                                        <span title="Disetujui" class="btn-xs btn-success fa fa-check"></span>
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
                </div>
            </div>
        </div>
    <?php
        break;


    case "list-data-assesment-pj-cuti-pegawai":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST DATA PERMOHONAN CUTI ASSESMENT PJ</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab"> PERMOHONAN</a></li>
                        <li><a href="#tab_2" data-toggle="tab">HISTORY ASSESMENT</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <!-- /.box-header -->
                            <div class="box-body table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Pemohon</th>
                                            <th>Tanggal Permohonan</th>
                                            <th>Jenis Cuti</th>
                                            <th>Periode Cuti</th>
                                            <th>Alasan Cuti</th>
                                            <th>Acc PJ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 0;
                                        $tm_cuti = bukaquery("
                                            SELECT
                                                c.id_cuti, 
                                                c.tgl_permohonan, 
                                                p.id_user, 
                                                p.nama_pegawai, 
                                                c.jumlah_hari,
                                                c.tahun_cuti, 
                                                sk.desc_ketidakhadiran, 
                                                c.alasan_cuti, 
                                                c.acc_pengganti, 
                                                c.acc_kepegawaian, 
                                                IF(c.buk1 is not null,1,null) buk1, 
                                                IF(c.buk2 is not null,1,null) buk2, 
                                                GROUP_CONCAT( date_format(hc.tanggal, '%d-%m-%Y') ORDER BY hc.tanggal ASC ) hari_cuti  
                                            FROM tm_cuti c
                                            INNER JOIN tm_pegawai p ON c.id_user = p.id_user
                                            INNER JOIN tm_shift_ketidakhadiran sk ON c.id_ketidakhadiran = sk.id_ketidakhadiran
                                            INNER JOIN tm_hari_cuti hc ON c.id_cuti = hc.id_cuti 
                                            WHERE
                                                c.acc_pengganti = 'Y' 
                                                AND c.acc_pj = '-' 
                                                AND c.id_user_pj = '$id_user'
                                            GROUP BY c.id_cuti 
                                            ORDER BY c.id_cuti DESC
                                        ");
                                        while ($row = fetch_array($tm_cuti)) {

                                            $tanggal_explode = explode(',', $row['hari_cuti']);
                                            $tanggal1 = date_format(date_create($tanggal_explode[0]), 'd-m-Y');
                                            $tanggal2 = date_format(date_create(end($tanggal_explode)), 'd-m-Y');
                                            $lama_cuti = HitungHari(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10)))) - HitungHariMinggu(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10))));


                                            if (strtolower($row['desc_ketidakhadiran']) == 'persalinan') {
                                                $tanggal_explode = explode(',', $row['hari_cuti']);
                                                $tgl_psln_awal = $tanggal_explode[0];
                                                $tgl_psln_akhir = end($tanggal_explode);
                                                $list_tanggal = "$tgl_psln_awal<br> S/D<br> $tgl_psln_akhir";
                                            } else {
                                                $list_tanggal = str_replace(',', '<br>', $row['hari_cuti']);
                                            }

                                            $no++;
                                        ?>

                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $row['nama_pegawai']; ?></td>
                                                <td><?php echo FormatTgl('d/m/Y', $row['tgl_permohonan']); ?></td>
                                                <td><?php echo $row['desc_ketidakhadiran'] . " (" . $row['tahun_cuti'] . ")"; ?></td>
                                                <td><?php echo "(" . $row['jumlah_hari'] . " Hari)<br>" . $list_tanggal; ?></td>
                                                <td><?php echo $row['alasan_cuti']; ?></td>
                                                <td>
                                                    <span data-id='<?php echo $row['id_cuti']; ?>' data-id_user='<?php echo $row['id_user']; ?>' data-list_tanggal='<?= $list_tanggal; ?>' data-kolom_acc='acc_pj' data-kolom_tgl_acc='tgl_acc_pj' data-jenis_cuti='<?php echo $row['desc_ketidakhadiran']; ?>' data-link_header='module=cuti-pegawai&act=list-data-assesment-pj-cuti-pegawai' title="Modal Asesmen" class="btn-xs btn-danger fa fa-close show-modal-reject-cuti"></span>
                                                    <span data-id='<?php echo $row['id_cuti']; ?>' data-id_user='<?php echo $row['id_user']; ?>' data-list_tanggal='<?= $list_tanggal; ?>' data-kolom_acc='acc_pj' data-kolom_tgl_acc='tgl_acc_pj' data-jenis_cuti='<?php echo $row['desc_ketidakhadiran']; ?>' data-link_header='module=cuti-pegawai&act=list-data-assesment-pj-cuti-pegawai' title="Modal Asesmen" class="btn-xs btn-success fa fa-check show-modal-acc-cuti"></span>

                                                    <?php
                                                    if (!empty($row['buk1']) || !empty($row['buk1']))
                                                        echo "<br>";

                                                    if (!empty($row['buk1'])) { ?>
                                                        <a href="<?php echo "$url_app/bukti-cuti-1/$row[id_cuti]"; ?>" target="_blank"><span title="Bukti 1" class="btn-xs btn-info fa fa-refresh"> Bukti 1</span></a>
                                                    <?php
                                                    }
                                                    if (!empty($row['buk2'])) { ?>
                                                        <br>
                                                        <a href="<?php echo "$url_app/bukti-cuti-2/$row[id_cuti]"; ?>" target="_blank"><span title="Bukti 2" class="btn-xs btn-info fa fa-refresh"> Bukti 2</span></a>
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
                        <div class="tab-pane" id="tab_2">
                            <!-- /.box-header -->
                            <div class="box-body table-responsive">
                                <table id="example_normal" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Pemohon</th>
                                            <th>Tanggal Permohonan</th>
                                            <th>Tanggal Acc</th>
                                            <th>Jenis Cuti</th>
                                            <th>Periode Cuti</th>
                                            <th>Alasan Cuti</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 0;
                                        $tm_cuti = bukaquery("
                                            SELECT
                                                tm_cuti.id_cuti,
                                                tm_cuti.tgl_permohonan,
                                                tm_pegawai.nama_pegawai,
                                                tm_cuti.jumlah_hari,
                                                tm_shift_ketidakhadiran.desc_ketidakhadiran,
                                                tm_cuti.tahun_cuti,
                                                tm_cuti.alasan_cuti,
                                                tm_cuti.tgl_acc_pengganti,
                                                tm_cuti.acc_pengganti,
                                                acc_kepegawaian,
                                                tm_cuti.acc_pj,
                                                tm_cuti.tgl_acc_pj,
                                                GROUP_CONCAT( date_format(tm_hari_cuti.tanggal, '%d-%m-%Y') ORDER BY tm_hari_cuti.tanggal ASC ) hari_cuti  
                                            FROM tm_cuti
                                            INNER JOIN tm_pegawai ON tm_cuti.id_user = tm_pegawai.id_user
                                            INNER JOIN tm_shift_ketidakhadiran ON tm_cuti.id_ketidakhadiran = tm_shift_ketidakhadiran.id_ketidakhadiran
                                            INNER JOIN tm_hari_cuti ON tm_cuti.id_cuti = tm_hari_cuti.id_cuti 
                                            WHERE
                                                YEAR ( tm_cuti.tgl_permohonan )= '$thn_now' 
                                                AND tm_cuti.acc_pj != '-' 
                                                AND tm_cuti.id_user_pj = '$id_user'
                                            GROUP BY
                                                tm_cuti.id_cuti 
                                            ORDER BY
                                                tm_cuti.id_cuti desc
                                        ");
                                        while ($row = fetch_array($tm_cuti)) {

                                            $tanggal_explode = explode(',', $row['hari_cuti']);
                                            $tanggal1 = date_format(date_create($tanggal_explode[0]), 'd-m-Y');
                                            $tanggal2 = date_format(date_create(end($tanggal_explode)), 'd-m-Y');
                                            $lama_cuti = HitungHari(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10)))) - HitungHariMinggu(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10))));


                                            if (strtolower($row['desc_ketidakhadiran']) == 'persalinan') {
                                                $tanggal_explode = explode(',', $row['hari_cuti']);
                                                $tgl_psln_awal = $tanggal_explode[0];
                                                $tgl_psln_akhir = end($tanggal_explode);
                                                $list_tanggal = "$tgl_psln_awal<br> S/D<br> $tgl_psln_akhir";
                                            } else {
                                                $list_tanggal = str_replace(',', '<br>', $row['hari_cuti']);
                                            }

                                            $no++;
                                        ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $row['nama_pegawai']; ?></td>
                                                <td><?php echo FormatTgl('d/m/Y', $row['tgl_permohonan']); ?></td>
                                                <td><?php echo FormatTgl('d/m/Y H:i:s', $row['tgl_acc_pj']); ?></td>
                                                <td><?php echo $row['desc_ketidakhadiran'] . " (" . $row['tahun_cuti'] . ")"; ?></td>
                                                <td><?php echo "(" . $row['jumlah_hari'] . " Hari)<br>" . $list_tanggal; ?></td>
                                                <td><?php echo $row['alasan_cuti']; ?></td>
                                                <td>
                                                    <?php if ($row['acc_pj'] == 'T') { ?>
                                                        <span title="Ditolak" class="btn-xs btn-danger fa fa-close"></span>
                                                    <?php } else { ?>
                                                        <span title="Disetujui" class="btn-xs btn-success fa fa-check"></span>
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
                </div>
            </div>
        </div>
    <?php
        break;
    case "list-data-assesment-kasatpel-cuti-pegawai":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST DATA PERMOHONAN CUTI ASSESMENT KOINST/KASATPEL</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab"> PERMOHONAN</a></li>
                        <li><a href="#tab_2" data-toggle="tab">HISTORY ASSESMENT</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <!-- /.box-header -->
                            <div class="box-body table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Pemohon</th>
                                            <th>Tanggal Permohonan</th>
                                            <th>Jenis Cuti</th>
                                            <th>Periode Cuti</th>
                                            <th>Alasan Cuti</th>
                                            <th>Acc</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 0;
                                        $tm_cuti = bukaquery("
                                            SELECT
                                                c.id_cuti, 
                                                c.tgl_permohonan, 
                                                c.id_user, 
                                                p.nama_pegawai, 
                                                c.jumlah_hari,
                                                c.tahun_cuti, 
                                                sk.desc_ketidakhadiran, 
                                                c.alasan_cuti, 
                                                c.acc_pengganti, 
                                                c.acc_kepegawaian, 
                                                IF(c.buk1 is not null,1,null) buk1, 
                                                IF(c.buk2 is not null,1,null) buk2, 
                                                GROUP_CONCAT( date_format(hc.tanggal, '%d-%m-%Y') ORDER BY hc.tanggal ASC ) hari_cuti  
                                            FROM tm_cuti c
                                            INNER JOIN tm_pegawai p ON c.id_user = p.id_user
                                            INNER JOIN tm_shift_ketidakhadiran sk ON c.id_ketidakhadiran = sk.id_ketidakhadiran
                                            INNER JOIN tm_hari_cuti hc ON c.id_cuti = hc.id_cuti 
                                            WHERE
                                                acc_kasatpel = '-' 
                                                AND acc_pengganti = 'Y' 
                                                AND acc_pj = 'Y' 
                                                AND c.id_user_kasatpel = '$id_user'
                                            GROUP BY c.id_cuti 
                                            ORDER BY c.id_cuti DESC
                                        ");
                                        while ($row = fetch_array($tm_cuti)) {

                                            $tanggal_explode = explode(',', $row['hari_cuti']);
                                            $tanggal1 = date_format(date_create($tanggal_explode[0]), 'd-m-Y');
                                            $tanggal2 = date_format(date_create(end($tanggal_explode)), 'd-m-Y');
                                            $lama_cuti = HitungHari(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10)))) - HitungHariMinggu(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10))));


                                            if (strtolower($row['desc_ketidakhadiran']) == 'persalinan') {
                                                $tanggal_explode = explode(',', $row['hari_cuti']);
                                                $tgl_psln_awal = $tanggal_explode[0];
                                                $tgl_psln_akhir = end($tanggal_explode);
                                                $list_tanggal = "$tgl_psln_awal<br> S/D<br> $tgl_psln_akhir";
                                            } else {
                                                $list_tanggal = str_replace(',', '<br>', $row['hari_cuti']);
                                            }

                                            $no++;
                                        ?>

                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $row['nama_pegawai']; ?></td>
                                                <td><?php echo FormatTgl('d/m/Y', $row['tgl_permohonan']); ?></td>
                                                <td><?php echo $row['desc_ketidakhadiran'] . " (" . $row['tahun_cuti'] . ")"; ?></td>
                                                <td><?php echo "(" . $row['jumlah_hari'] . " Hari)<br> " . $list_tanggal; ?></td>
                                                <td><?php echo $row['alasan_cuti']; ?></td>
                                                <td>
                                                    <span data-id='<?php echo $row['id_cuti']; ?>' data-id_user='<?php echo $row['id_user']; ?>' data-list_tanggal='<?= $list_tanggal; ?>' data-kolom_acc='acc_kasatpel' data-kolom_tgl_acc='tgl_acc_kasatpel' data-jenis_cuti='<?php echo $row['desc_ketidakhadiran']; ?>' data-link_header='module=cuti-pegawai&act=list-data-assesment-kasatpel-cuti-pegawai' title="Modal Asesmen" class="btn-xs btn-danger fa fa-close show-modal-reject-cuti"></span>
                                                    <span data-id='<?php echo $row['id_cuti']; ?>' data-id_user='<?php echo $row['id_user']; ?>' data-list_tanggal='<?= $list_tanggal; ?>' data-kolom_acc='acc_kasatpel' data-kolom_tgl_acc='tgl_acc_kasatpel' data-jenis_cuti='<?php echo $row['desc_ketidakhadiran']; ?>' data-link_header='module=cuti-pegawai&act=list-data-assesment-kasatpel-cuti-pegawai' title="Modal Asesmen" class="btn-xs btn-success fa fa-check show-modal-acc-cuti"></span>

                                                    <?php
                                                    if (!empty($row['buk1']) || !empty($row['buk1']))
                                                        echo "<br>";

                                                    if (!empty($row['buk1'])) { ?>
                                                        <a href="<?php echo "$url_app/bukti-cuti-1/$row[id_cuti]"; ?>" target="_blank"><span title="Bukti 1" class="btn-xs btn-info fa fa-refresh"> Bukti 1</span></a>
                                                    <?php
                                                    }
                                                    if (!empty($row['buk2'])) { ?>
                                                        <br>
                                                        <a href="<?php echo "$url_app/bukti-cuti-2/$row[id_cuti]"; ?>" target="_blank"><span title="Bukti 2" class="btn-xs btn-info fa fa-refresh"> Bukti 2</span></a>
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
                        <div class="tab-pane" id="tab_2">
                            <!-- /.box-header -->
                            <div class="box-body table-responsive">
                                <table id="example_normal" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Pemohon</th>
                                            <th>Tanggal Permohonan</th>
                                            <th>Tanggal Acc</th>
                                            <th>Jenis Cuti</th>
                                            <th>Periode Cuti</th>
                                            <th>Alasan Cuti</th>
                                            <th>Assesment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 0;
                                        $tm_cuti = bukaquery("
                                            SELECT
                                                tm_cuti.id_cuti,
                                                tm_cuti.tgl_permohonan,
                                                tm_pegawai.id_user,
                                                tm_pegawai.nama_pegawai,
                                                tm_cuti.jumlah_hari,
                                                tm_shift_ketidakhadiran.desc_ketidakhadiran,
                                                tm_cuti.tahun_cuti,
                                                tm_cuti.alasan_cuti,
                                                tm_cuti.acc_kasatpel,
                                                tm_cuti.tgl_acc_kasatpel,
                                                GROUP_CONCAT( date_format(tm_hari_cuti.tanggal, '%d-%m-%Y') ORDER BY tm_hari_cuti.tanggal ASC ) hari_cuti  
                                            FROM tm_cuti
                                            INNER JOIN tm_pegawai ON tm_cuti.id_user = tm_pegawai.id_user
                                            INNER JOIN tm_shift_ketidakhadiran ON tm_cuti.id_ketidakhadiran = tm_shift_ketidakhadiran.id_ketidakhadiran
                                            INNER JOIN tm_hari_cuti ON tm_cuti.id_cuti = tm_hari_cuti.id_cuti 
                                            WHERE
                                                YEAR ( tm_cuti.tgl_permohonan )= '$thn_now' 
                                                AND tm_cuti.id_user_kasatpel = '$id_user' 
                                                AND acc_kasatpel != '-' 
                                                AND YEAR ( tm_cuti.tgl_permohonan )= '$thn_now'
                                            GROUP BY
                                                tm_cuti.id_cuti 
                                            ORDER BY
                                                tm_cuti.id_cuti desc
                                        ");
                                        while ($row = fetch_array($tm_cuti)) {

                                            $tanggal_explode = explode(',', $row['hari_cuti']);
                                            $tanggal1 = date_format(date_create($tanggal_explode[0]), 'd-m-Y');
                                            $tanggal2 = date_format(date_create(end($tanggal_explode)), 'd-m-Y');
                                            $lama_cuti = HitungHari(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10)))) - HitungHariMinggu(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10))));


                                            if (strtolower($row['desc_ketidakhadiran']) == 'persalinan') {
                                                $tanggal_explode = explode(',', $row['hari_cuti']);
                                                $tgl_psln_awal = $tanggal_explode[0];
                                                $tgl_psln_akhir = end($tanggal_explode);
                                                $list_tanggal = "$tgl_psln_awal<br> S/D<br> $tgl_psln_akhir";
                                            } else {
                                                $list_tanggal = str_replace(',', '<br>', $row['hari_cuti']);
                                            }

                                            $no++;
                                        ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $row['nama_pegawai']; ?></td>
                                                <td><?php echo FormatTgl('d/m/Y', $row['tgl_permohonan']); ?></td>
                                                <td><?php echo FormatTgl('d/m/Y H:i:s', $row['tgl_acc_kasatpel']); ?></td>
                                                <td><?php echo $row['desc_ketidakhadiran'] . " (" . $row['tahun_cuti'] . ")"; ?></td>
                                                <td><?php echo "(" . $row['jumlah_hari'] . " Hari)<br>" . $list_tanggal; ?></td>
                                                <td><?php echo $row['alasan_cuti']; ?></td>
                                                <td>
                                                    <?php if ($row['acc_kasie'] == 'T') { ?>
                                                        <span title="Ditolak" class="btn-xs btn-danger fa fa-close"></span>
                                                        <a href="<?php echo 'cetak-surat-penolakan-cuti-' . $row['id_cuti'] . ''; ?>" target="_blank"><span data-toggle="modal" title="Cetak" class="btn-xs btn-warning fa fa-file-pdf-o"> Cetak Surat Penolakan</span></a>
                                                    <?php } else { ?>
                                                        <span title="Disetujui" class="btn-xs btn-success fa fa-check"></span>
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
                </div>
            </div>
        </div>
    <?php
        break;
    case "list-data-assesment-kasie-cuti-pegawai":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST DATA PERMOHONAN CUTI ASSESMENT KASIE</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab"> PERMOHONAN</a></li>
                        <li><a href="#tab_2" data-toggle="tab">HISTORY ASSESMENT</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <!-- /.box-header -->
                            <div class="box-body table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Pemohon</th>
                                            <th>Tanggal Permohonan</th>
                                            <th>Jenis Cuti</th>
                                            <th>Periode Cuti</th>
                                            <th>Alasan Cuti</th>
                                            <th>Acc</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 0;
                                        $tm_cuti = bukaquery("
                                            SELECT
                                                c.id_cuti, 
                                                c.tgl_permohonan, 
                                                p.nama_pegawai, 
                                                c.id_user,
                                                c.jumlah_hari,
                                                c.tahun_cuti, 
                                                sk.desc_ketidakhadiran, 
                                                c.alasan_cuti, 
                                                c.acc_pengganti, 
                                                c.acc_kepegawaian, 
                                                IF(c.buk1 is not null,1,null) buk1, 
                                                IF(c.buk2 is not null,1,null) buk2, 
                                                GROUP_CONCAT( date_format(hc.tanggal, '%d-%m-%Y') ORDER BY hc.tanggal ASC ) hari_cuti  
                                            FROM tm_cuti c
                                            INNER JOIN tm_pegawai p ON c.id_user = p.id_user
                                            INNER JOIN tm_shift_ketidakhadiran sk ON c.id_ketidakhadiran = sk.id_ketidakhadiran
                                            INNER JOIN tm_hari_cuti hc ON c.id_cuti = hc.id_cuti 
                                            WHERE
                                                c.acc_pengganti = 'Y' 
                                                AND c.acc_pj = 'Y' 
                                                AND c.acc_kasatpel = 'Y' 
                                                AND c.acc_kasie = '-' 
                                                AND c.id_user_kasie = '$id_user' 
                                                AND year(c.tgl_permohonan) = $thn_now 
                                            GROUP BY c.id_cuti 
                                            ORDER BY c.id_cuti DESC
                                        ");
                                        while ($row = fetch_array($tm_cuti)) {

                                            $tanggal_explode = explode(',', $row['hari_cuti']);
                                            $tanggal1 = date_format(date_create($tanggal_explode[0]), 'd-m-Y');
                                            $tanggal2 = date_format(date_create(end($tanggal_explode)), 'd-m-Y');
                                            $lama_cuti = HitungHari(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10)))) - HitungHariMinggu(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10))));


                                            if (strtolower($row['desc_ketidakhadiran']) == 'persalinan') {
                                                $tanggal_explode = explode(',', $row['hari_cuti']);
                                                $tgl_psln_awal = $tanggal_explode[0];
                                                $tgl_psln_akhir = end($tanggal_explode);
                                                $list_tanggal = "$tgl_psln_awal<br> S/D<br> $tgl_psln_akhir";
                                            } else {
                                                $list_tanggal = str_replace(',', '<br>', $row['hari_cuti']);
                                            }

                                            $no++;
                                        ?>

                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $row['nama_pegawai']; ?></td>
                                                <td><?php echo FormatTgl('d/m/Y', $row['tgl_permohonan']); ?></td>
                                                <td><?php echo $row['desc_ketidakhadiran'] . " (" . $row['tahun_cuti'] . ")"; ?></td>
                                                <td><?php echo "(" . $row['jumlah_hari'] . " Hari)<br> " . $list_tanggal; ?></td>
                                                <td><?php echo $row['alasan_cuti']; ?></td>
                                                <td>
                                                    <span data-id='<?php echo $row['id_cuti']; ?>' data-id_user='<?php echo $row['id_user']; ?>' data-list_tanggal='<?= $list_tanggal; ?>' data-kolom_acc='acc_kasie' data-kolom_tgl_acc='tgl_acc_kasie' data-jenis_cuti='<?php echo $row['desc_ketidakhadiran']; ?>' data-link_header='module=cuti-pegawai&act=list-data-assesment-kasie-cuti-pegawai' title="Modal Asesmen" class="btn-xs btn-danger fa fa-close show-modal-reject-cuti"></span>
                                                    <span data-id='<?php echo $row['id_cuti']; ?>' data-id_user='<?php echo $row['id_user']; ?>' data-list_tanggal='<?= $list_tanggal; ?>' data-kolom_acc='acc_kasie' data-kolom_tgl_acc='tgl_acc_kasie' data-jenis_cuti='<?php echo $row['desc_ketidakhadiran']; ?>' data-link_header='module=cuti-pegawai&act=list-data-assesment-kasie-cuti-pegawai' title="Modal Asesmen" class="btn-xs btn-success fa fa-check show-modal-acc-cuti"></span>

                                                    <?php
                                                    if (!empty($row['buk1']) || !empty($row['buk1']))
                                                        echo "<br>";

                                                    if (!empty($row['buk1'])) { ?>
                                                        <a href="<?php echo "$url_app/bukti-cuti-1/$row[id_cuti]"; ?>" target="_blank"><span title="Bukti 1" class="btn-xs btn-info fa fa-refresh"> Bukti 1</span></a>
                                                    <?php
                                                    }
                                                    if (!empty($row['buk2'])) { ?>
                                                        <br>
                                                        <a href="<?php echo "$url_app/bukti-cuti-2/$row[id_cuti]"; ?>" target="_blank"><span title="Bukti 2" class="btn-xs btn-info fa fa-refresh"> Bukti 2</span></a>
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
                        <div class="tab-pane" id="tab_2">
                            <!-- /.box-header -->
                            <div class="box-body table-responsive">
                                <table id="example_normal" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Pemohon</th>
                                            <th>Tanggal Permohonan</th>
                                            <th>Tanggal Acc</th>
                                            <th>Jenis Cuti</th>
                                            <th>Periode Cuti</th>
                                            <th>Alasan Cuti</th>
                                            <th>Assesment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 0;
                                        $tm_cuti = bukaquery("
                                            SELECT
                                                tm_cuti.id_cuti,
                                                tm_cuti.tgl_permohonan,
                                                tm_pegawai.id_user,
                                                tm_pegawai.nama_pegawai,
                                                tm_cuti.jumlah_hari,
                                                tm_shift_ketidakhadiran.desc_ketidakhadiran,
                                                tm_cuti.tahun_cuti,
                                                tm_cuti.alasan_cuti,
                                                tm_cuti.acc_kasie,
                                                tm_cuti.tgl_acc_kasie,
                                                GROUP_CONCAT( date_format(tm_hari_cuti.tanggal, '%d-%m-%Y') ORDER BY tm_hari_cuti.tanggal ASC ) hari_cuti  
                                            FROM tm_cuti
                                            INNER JOIN tm_pegawai ON tm_cuti.id_user = tm_pegawai.id_user
                                            INNER JOIN tm_shift_ketidakhadiran ON tm_cuti.id_ketidakhadiran = tm_shift_ketidakhadiran.id_ketidakhadiran
                                            INNER JOIN tm_hari_cuti ON tm_cuti.id_cuti = tm_hari_cuti.id_cuti 
                                            WHERE
                                                YEAR ( tm_cuti.tgl_permohonan )= '$thn_now' 
                                                AND tm_cuti.id_user_kasie = '$id_user' 
                                                AND acc_kasie != '-' 
                                                AND YEAR ( tm_cuti.tgl_permohonan )= '$thn_now'
                                            GROUP BY
                                                tm_cuti.id_cuti 
                                            ORDER BY
                                                tm_cuti.id_cuti desc
                                        ");

                                        while ($row = fetch_assoc($tm_cuti)) {

                                            $tanggal_explode = explode(',', $row['hari_cuti']);
                                            $tanggal1 = date_format(date_create($tanggal_explode[0]), 'd-m-Y');
                                            $tanggal2 = date_format(date_create(end($tanggal_explode)), 'd-m-Y');
                                            $lama_cuti = HitungHari(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10)))) - HitungHariMinggu(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10))));


                                            if (strtolower($row['desc_ketidakhadiran']) == 'persalinan') {
                                                $tanggal_explode = explode(',', $row['hari_cuti']);
                                                $tgl_psln_awal = $tanggal_explode[0];
                                                $tgl_psln_akhir = end($tanggal_explode);
                                                $list_tanggal = "$tgl_psln_awal<br> S/D<br> $tgl_psln_akhir";
                                            } else {
                                                $list_tanggal = str_replace(',', '<br>', $row['hari_cuti']);
                                            }

                                            $no++;
                                        ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $row['nama_pegawai']; ?></td>
                                                <td><?php echo FormatTgl('d/m/Y', $row['tgl_permohonan']); ?></td>
                                                <td><?php echo FormatTgl('d/m/Y H:i:s', $row['tgl_acc_kasie']); ?></td>
                                                <td><?php echo $row['desc_ketidakhadiran'] . " (" . $row['tahun_cuti'] . ")"; ?></td>
                                                <td><?php echo "( " . $row['jumlah_hari'] . " Hari)<br>" . $list_tanggal; ?></td>
                                                <td><?php echo $row['alasan_cuti']; ?></td>
                                                <td>
                                                    <?php if ($row['acc_kasie'] == 'T') { ?>
                                                        <span title="Ditolak" class="btn-xs btn-danger fa fa-close"></span>
                                                        <!-- <a href="<?php echo 'cetak-surat-penolakan-cuti-' . $row['id_cuti'] . ''; ?>" target="_blank"><span data-toggle="modal" title="Cetak" class="btn-xs btn-warning fa fa-file-pdf-o"> Cetak Surat Penolakan</span></a> -->
                                                    <?php } else { ?>
                                                        <span title="Disetujui" class="btn-xs btn-success fa fa-check"></span>
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
                </div>
            </div>
        </div>
    <?php
        break;
    case "list-data-assesment-ktu-cuti-pegawai":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST DATA PERMOHONAN CUTI ASSESMENT KTU</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab"> PERMOHONAN</a></li>
                        <li><a href="#tab_2" data-toggle="tab">HISTORY ASSESMENT</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <!-- /.box-header -->
                            <div class="box-body table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Pemohon</th>
                                            <th>Tanggal Permohonan</th>
                                            <th>Jenis Cuti</th>
                                            <th>Periode Cuti</th>
                                            <th>Alasan Cuti</th>
                                            <th>Acc</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 0;
                                        $tm_cuti = bukaquery("
                                            SELECT
                                                c.id_cuti, 
                                                c.tgl_permohonan, 
                                                p.nama_pegawai, 
                                                c.id_user,
                                                c.jumlah_hari,
                                                c.tahun_cuti, 
                                                sk.desc_ketidakhadiran, 
                                                c.alasan_cuti, 
                                                c.acc_ktu, 
                                                IF(c.buk1 is not null,1,null) buk1, 
                                                IF(c.buk2 is not null,1,null) buk2, 
                                                GROUP_CONCAT( date_format(hc.tanggal, '%d-%m-%Y') ORDER BY hc.tanggal ASC ) hari_cuti  
                                            FROM tm_cuti c
                                            INNER JOIN tm_pegawai p ON c.id_user = p.id_user
                                            INNER JOIN tm_shift_ketidakhadiran sk ON c.id_ketidakhadiran = sk.id_ketidakhadiran
                                            INNER JOIN tm_hari_cuti hc ON c.id_cuti = hc.id_cuti 
                                            WHERE
                                                c.acc_pengganti = 'Y' 
                                                AND c.acc_pj = 'Y' 
                                                AND c.acc_kasatpel = 'Y' 
                                                AND c.acc_kasie = 'Y' 
                                                AND c.acc_ktu = '-' 
                                                AND c.id_user_ktu = '$id_user' 
                                                AND year(c.tgl_permohonan) = $thn_now 
                                            GROUP BY c.id_cuti 
                                            ORDER BY c.id_cuti DESC
                                        ");
                                        while ($row = fetch_array($tm_cuti)) {

                                            $tanggal_explode = explode(',', $row['hari_cuti']);
                                            $tanggal1 = date_format(date_create($tanggal_explode[0]), 'd-m-Y');
                                            $tanggal2 = date_format(date_create(end($tanggal_explode)), 'd-m-Y');
                                            $lama_cuti = HitungHari(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10)))) - HitungHariMinggu(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10))));


                                            if (strtolower($row['desc_ketidakhadiran']) == 'persalinan') {
                                                $tanggal_explode = explode(',', $row['hari_cuti']);
                                                $tgl_psln_awal = $tanggal_explode[0];
                                                $tgl_psln_akhir = end($tanggal_explode);
                                                $list_tanggal = "$tgl_psln_awal<br> S/D<br> $tgl_psln_akhir";
                                            } else {
                                                $list_tanggal = str_replace(',', '<br>', $row['hari_cuti']);
                                            }

                                            $no++;
                                        ?>

                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $row['nama_pegawai']; ?></td>
                                                <td><?php echo FormatTgl('d/m/Y', $row['tgl_permohonan']); ?></td>
                                                <td><?php echo $row['desc_ketidakhadiran'] . " (" . $row['tahun_cuti'] . ")"; ?></td>
                                                <td><?php echo "(" . $row['jumlah_hari'] . " Hari)<br> " . $list_tanggal; ?></td>
                                                <td><?php echo $row['alasan_cuti']; ?></td>
                                                <td>
                                                    <span data-id='<?php echo $row['id_cuti']; ?>' data-id_user='<?php echo $row['id_user']; ?>' data-list_tanggal='<?= $list_tanggal; ?>' data-kolom_acc='acc_ktu' data-kolom_tgl_acc='tgl_acc_ktu' data-jenis_cuti='<?php echo $row['desc_ketidakhadiran']; ?>' data-link_header='module=cuti-pegawai&act=list-data-assesment-ktu-cuti-pegawai' title="Modal Asesmen" class="btn-xs btn-danger fa fa-close show-modal-reject-cuti"></span>
                                                    <span data-id='<?php echo $row['id_cuti']; ?>' data-id_user='<?php echo $row['id_user']; ?>' data-list_tanggal='<?= $list_tanggal; ?>' data-kolom_acc='acc_ktu' data-kolom_tgl_acc='tgl_acc_ktu' data-jenis_cuti='<?php echo $row['desc_ketidakhadiran']; ?>' data-link_header='module=cuti-pegawai&act=list-data-assesment-ktu-cuti-pegawai' title="Modal Asesmen" class="btn-xs btn-success fa fa-check show-modal-acc-cuti"></span>

                                                    <?php
                                                    if (!empty($row['buk1']) || !empty($row['buk1']))
                                                        echo "<br>";

                                                    if (!empty($row['buk1'])) { ?>
                                                        <a href="<?php echo "$url_app/bukti-cuti-1/$row[id_cuti]"; ?>" target="_blank"><span title="Bukti 1" class="btn-xs btn-info fa fa-refresh"> Bukti 1</span></a>
                                                    <?php
                                                    }
                                                    if (!empty($row['buk2'])) { ?>
                                                        <br>
                                                        <a href="<?php echo "$url_app/bukti-cuti-2/$row[id_cuti]"; ?>" target="_blank"><span title="Bukti 2" class="btn-xs btn-info fa fa-refresh"> Bukti 2</span></a>
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
                        <div class="tab-pane" id="tab_2">
                            <!-- /.box-header -->
                            <div class="box-body table-responsive">
                                <table id="example_normal" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Pemohon</th>
                                            <th>Tanggal Permohonan</th>
                                            <th>Jenis Cuti</th>
                                            <th>Periode Cuti</th>
                                            <th>Alasan Cuti</th>
                                            <th>Assesment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 0;
                                        $tm_cuti = bukaquery("
                                            SELECT
                                                tm_cuti.id_cuti,
                                                tm_cuti.tgl_permohonan,
                                                tm_pegawai.id_user,
                                                tm_pegawai.nama_pegawai,
                                                tm_cuti.jumlah_hari,
                                                tm_shift_ketidakhadiran.desc_ketidakhadiran,
                                                tm_cuti.tahun_cuti,
                                                tm_cuti.alasan_cuti,
                                                tm_cuti.tgl_acc_ktu,
                                                tm_cuti.acc_ktu,
                                                GROUP_CONCAT( date_format(tm_hari_cuti.tanggal, '%d-%m-%Y') ORDER BY tm_hari_cuti.tanggal ASC ) hari_cuti  
                                            FROM tm_cuti
                                            INNER JOIN tm_pegawai ON tm_cuti.id_user = tm_pegawai.id_user
                                            INNER JOIN tm_shift_ketidakhadiran ON tm_cuti.id_ketidakhadiran = tm_shift_ketidakhadiran.id_ketidakhadiran
                                            INNER JOIN tm_hari_cuti ON tm_cuti.id_cuti = tm_hari_cuti.id_cuti 
                                            WHERE
                                                YEAR ( tm_cuti.tgl_permohonan )= '$thn_now' 
                                                AND tm_cuti.id_user_ktu = '$id_user' 
                                                AND acc_ktu != '-' 
                                                AND YEAR ( tm_cuti.tgl_permohonan )= '$thn_now'
                                            GROUP BY
                                                tm_cuti.id_cuti 
                                            ORDER BY
                                                tm_cuti.id_cuti desc
                                        ");
                                        while ($row = fetch_array($tm_cuti)) {

                                            $tanggal_explode = explode(',', $row['hari_cuti']);
                                            $tanggal1 = date_format(date_create($tanggal_explode[0]), 'd-m-Y');
                                            $tanggal2 = date_format(date_create(end($tanggal_explode)), 'd-m-Y');
                                            $lama_cuti = HitungHari(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10)))) - HitungHariMinggu(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10))));


                                            if (strtolower($row['desc_ketidakhadiran']) == 'persalinan') {
                                                $tanggal_explode = explode(',', $row['hari_cuti']);
                                                $tgl_psln_awal = $tanggal_explode[0];
                                                $tgl_psln_akhir = end($tanggal_explode);
                                                $list_tanggal = "$tgl_psln_awal<br> S/D<br> $tgl_psln_akhir";
                                            } else {
                                                $list_tanggal = str_replace(',', '<br>', $row['hari_cuti']);
                                            }

                                            $no++;
                                        ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $row['nama_pegawai']; ?></td>
                                                <td><?php echo FormatTgl('d/m/Y', $row['tgl_permohonan']); ?></td>
                                                <td><?php echo FormatTgl('d/m/Y H:i:s', $row['tgl_acc_ktu']); ?></td>
                                                <td><?php echo $row['desc_ketidakhadiran'] . " (" . $row['tahun_cuti'] . ")"; ?></td>
                                                <td><?php echo "(" . $row['jumlah_hari'] . " Hari)<br> " . $list_tanggal; ?></td>
                                                <td><?php echo $row['alasan_cuti']; ?></td>
                                                <td>
                                                    <?php if ($row['acc_kasie'] == 'T') { ?>
                                                        <span title="Ditolak" class="btn-xs btn-danger fa fa-close"></span>
                                                        <a href="<?php echo 'cetak-surat-penolakan-cuti-' . $row['id_cuti'] . ''; ?>" target="_blank"><span data-toggle="modal" title="Cetak" class="btn-xs btn-warning fa fa-file-pdf-o"> Cetak Surat Penolakan</span></a>
                                                    <?php } else { ?>
                                                        <span title="Disetujui" class="btn-xs btn-success fa fa-check"></span>
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
                </div>
            </div>
        </div>
    <?php
        break;
    case "list-data-assesment-direktur-cuti-pegawai":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST DATA PERMOHONAN CUTI ASSESMENT DIREKTUR</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab"> PERMOHONAN</a></li>
                        <li><a href="#tab_2" data-toggle="tab">HISTORY ASSESMENT</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <!-- /.box-header -->
                            <div class="box-body table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Pemohon</th>
                                            <th>Tanggal Permohonan</th>
                                            <th>Jenis Cuti</th>
                                            <th>Periode Cuti</th>
                                            <th>Alasan Cuti</th>
                                            <th>Acc</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 0;
                                        $tm_cuti = bukaquery("
                                            SELECT
                                                c.id_cuti, 
                                                c.tgl_permohonan, 
                                                p.nama_pegawai, 
                                                c.id_user,
                                                c.jumlah_hari,
                                                c.tahun_cuti, 
                                                sk.desc_ketidakhadiran, 
                                                c.alasan_cuti, 
                                                c.acc_kasie,
                                                c.acc_direktur,
                                                IF(c.buk1 is not null,1,null) buk1, 
                                                IF(c.buk2 is not null,1,null) buk2, 
                                                GROUP_CONCAT( date_format(hc.tanggal, '%d-%m-%Y') ORDER BY hc.tanggal ASC ) hari_cuti  
                                            FROM tm_cuti c
                                            INNER JOIN tm_pegawai p ON c.id_user = p.id_user
                                            INNER JOIN tm_shift_ketidakhadiran sk ON c.id_ketidakhadiran = sk.id_ketidakhadiran
                                            INNER JOIN tm_hari_cuti hc ON c.id_cuti = hc.id_cuti 
                                            WHERE
                                                c.acc_pengganti = 'Y' 
                                                AND c.acc_pj = 'Y' 
                                                AND c.acc_kasatpel = 'Y' 
                                                AND c.acc_kasie = 'Y' 
                                                AND c.acc_ktu = 'Y' 
                                                AND c.acc_direktur = '-' 
                                                AND c.id_user_direktur = '$id_user'
                                            GROUP BY c.id_cuti 
                                            ORDER BY c.id_cuti DESC
                                        ");
                                        while ($row = fetch_array($tm_cuti)) {

                                            $tanggal_explode = explode(',', $row['hari_cuti']);
                                            $tanggal1 = date_format(date_create($tanggal_explode[0]), 'd-m-Y');
                                            $tanggal2 = date_format(date_create(end($tanggal_explode)), 'd-m-Y');
                                            $lama_cuti = HitungHari(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10)))) - HitungHariMinggu(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10))));


                                            if (strtolower($row['desc_ketidakhadiran']) == 'persalinan') {
                                                $tanggal_explode = explode(',', $row['hari_cuti']);
                                                $tgl_psln_awal = $tanggal_explode[0];
                                                $tgl_psln_akhir = end($tanggal_explode);
                                                $list_tanggal = "$tgl_psln_awal<br> S/D<br> $tgl_psln_akhir";
                                            } else {
                                                $list_tanggal = str_replace(',', '<br>', $row['hari_cuti']);
                                            }

                                            $no++;
                                        ?>

                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $row['nama_pegawai']; ?></td>
                                                <td><?php echo FormatTgl('d/m/Y', $row['tgl_permohonan']); ?></td>
                                                <td><?php echo $row['desc_ketidakhadiran'] . " (" . $row['tahun_cuti'] . ")"; ?></td>
                                                <td><?php echo "(" . $row['jumlah_hari'] . " Hari) <br> " . $list_tanggal; ?></td>
                                                <td><?php echo $row['alasan_cuti']; ?></td>
                                                <td>
                                                    <span data-id='<?php echo $row['id_cuti']; ?>' data-id_user='<?php echo $row['id_user']; ?>' data-list_tanggal='<?= $list_tanggal; ?>' data-kolom_acc='acc_direktur' data-kolom_tgl_acc='tgl_acc_direktur' data-jenis_cuti='<?php echo $row['desc_ketidakhadiran']; ?>' data-link_header='module=cuti-pegawai&act=list-data-assesment-direktur-cuti-pegawai' title="Modal Asesmen" class="btn-xs btn-danger fa fa-close show-modal-reject-cuti"></span>
                                                    <span data-id='<?php echo $row['id_cuti']; ?>' data-id_user='<?php echo $row['id_user']; ?>' data-list_tanggal='<?= $list_tanggal; ?>' data-kolom_acc='acc_direktur' data-kolom_tgl_acc='tgl_acc_direktur' data-jenis_cuti='<?php echo $row['desc_ketidakhadiran']; ?>' data-link_header='module=cuti-pegawai&act=list-data-assesment-direktur-cuti-pegawai' title="Modal Asesmen" class="btn-xs btn-success fa fa-check show-modal-acc-cuti"></span>

                                                    <?php
                                                    if (!empty($row['buk1']) || !empty($row['buk1']))
                                                        echo "<br>";

                                                    if (!empty($row['buk1'])) { ?>
                                                        <a href="<?php echo "$url_app/bukti-cuti-1/$row[id_cuti]"; ?>" target="_blank"><span title="Bukti 1" class="btn-xs btn-info fa fa-refresh"> Bukti 1</span></a>
                                                    <?php
                                                    }
                                                    if (!empty($row['buk2'])) { ?>
                                                        <br>
                                                        <a href="<?php echo "$url_app/bukti-cuti-2/$row[id_cuti]"; ?>" target="_blank"><span title="Bukti 2" class="btn-xs btn-info fa fa-refresh"> Bukti 2</span></a>
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
                        <div class="tab-pane" id="tab_2">
                            <!-- /.box-header -->
                            <div class="box-body table-responsive">
                                <table id="example_normal" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No Surat</th>
                                            <th>Nama Pemohon</th>
                                            <th>Tanggal Permohonan</th>
                                            <th>Tanggal Acc</th>
                                            <th>Jenis Cuti</th>
                                            <th>Periode Cuti</th>
                                            <th>Alasan Cuti</th>
                                            <th>Assesment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 0;
                                        $tm_cuti = bukaquery("
                                            SELECT
                                                tm_cuti.id_cuti,
                                                tm_cuti.tgl_permohonan,
                                                tm_cuti.no_surti,
                                                tm_pegawai.id_user,
                                                tm_pegawai.nama_pegawai,
                                                tm_cuti.jumlah_hari,
                                                tm_shift_ketidakhadiran.desc_ketidakhadiran,
                                                tm_cuti.tahun_cuti,
                                                tm_cuti.alasan_cuti,
                                                tm_cuti.acc_kasie,
                                                tm_cuti.acc_direktur,
                                                tm_cuti.tgl_acc_direktur,
                                                GROUP_CONCAT( date_format(tm_hari_cuti.tanggal, '%d-%m-%Y') ORDER BY tm_hari_cuti.tanggal ASC ) hari_cuti  
                                            FROM tm_cuti
                                            INNER JOIN tm_pegawai ON tm_cuti.id_user = tm_pegawai.id_user
                                            INNER JOIN tm_shift_ketidakhadiran ON tm_cuti.id_ketidakhadiran = tm_shift_ketidakhadiran.id_ketidakhadiran
                                            INNER JOIN tm_hari_cuti ON tm_cuti.id_cuti = tm_hari_cuti.id_cuti 
                                            WHERE
                                                YEAR ( tm_cuti.tgl_permohonan )= '$thn_now' 
                                                AND tm_cuti.id_user_direktur = '$id_user' 
                                                AND acc_direktur != '-' 
                                                AND YEAR ( tm_cuti.tgl_permohonan )= '$thn_now'
                                            GROUP BY
                                                tm_cuti.id_cuti 
                                            ORDER BY
                                                tm_cuti.id_cuti desc
                                        ");
                                        while ($row = fetch_array($tm_cuti)) {

                                            $tanggal_explode = explode(',', $row['hari_cuti']);
                                            $tanggal1 = date_format(date_create($tanggal_explode[0]), 'd-m-Y');
                                            $tanggal2 = date_format(date_create(end($tanggal_explode)), 'd-m-Y');
                                            $lama_cuti = HitungHari(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10)))) - HitungHariMinggu(date('d-m-Y', strtotime(substr($row['hari_cuti'], 0, 10))), date('d-m-Y', strtotime(substr($row['hari_cuti'], 13, 10))));


                                            if (strtolower($row['desc_ketidakhadiran']) == 'persalinan') {
                                                $tanggal_explode = explode(',', $row['hari_cuti']);
                                                $tgl_psln_awal = $tanggal_explode[0];
                                                $tgl_psln_akhir = end($tanggal_explode);
                                                $list_tanggal = "$tgl_psln_awal<br> S/D<br> $tgl_psln_akhir";
                                            } else {
                                                $list_tanggal = str_replace(',', '<br>', $row['hari_cuti']);
                                            }

                                            $no++;
                                        ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $row['no_surti']; ?></td>
                                                <td><?php echo $row['nama_pegawai']; ?></td>
                                                <td><?php echo FormatTgl('d/m/Y', $row['tgl_permohonan']); ?></td>
                                                <td><?php echo FormatTgl('d/m/Y H:i:s', $row['tgl_acc_direktur']); ?></td>
                                                <td><?php echo $row['desc_ketidakhadiran'] . " (" . $row['tahun_cuti'] . ")"; ?></td>
                                                <td><?php echo "(" . $row['jumlah_hari'] . " Hari) <br> $list_tanggal"; ?></td>
                                                <td><?php echo $row['alasan_cuti']; ?></td>
                                                <td>
                                                    <!-- <?php if ($row['acc_direktur'] == 'Y') { ?>
                                                        <a href="<?php echo 'cetak-surat-penolakan-cuti-' . $row['id_cuti'] . ''; ?>" target="_blank"><span data-toggle="modal" title="Batal" class="btn-xs btn-warning fa fa-close"> Cetak Surat Penolakan</span></a>
                                                    <?php } ?> -->

                                                    <span title="Disetujui" class="btn-xs btn-success fa fa-check"></span>
                                                    <a href='cetak-surat-cuti-<?= $row['id_cuti']; ?>' target='_blank'><span data-toggle='modal' title='Cetak' class='btn-xs btn-success fa fa-print'></span></a>
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
    <?php
        break;
    case "list-data-cuti-pegawai":
    ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list"> LIST DATA CUTI PEGAWAI</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="tab-content">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Unit</th>
                                <th>Nama Pegawai</th>
                                <th>Tanggal Permohonan</th>
                                <th>Periode Cuti</th>
                                <th>Cuti</th>
                                <th>Acc</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $sql = bukaquery2("
                                SELECT
                                    a.id_cuti,
                                    a.id_user,
                                    b.nama_pegawai,
                                    d.nama_unit,
                                    a.tgl_permohonan,
                                    a.periode_cuti,
                                    a.id_ketidakhadiran,
                                    c.desc_ketidakhadiran,
                                    a.tahun_cuti,
                                    a.acc_pengganti,
                                    e.nama_pegawai AS nm_pengganti,
                                    a.acc_pj,
                                    f.nama_pegawai AS nm_pj,
                                    a.acc_kasatpel,
                                    g.nama_pegawai AS nm_ksp,
                                    a.acc_kasie,
                                    h.nama_pegawai AS nm_kasie,
                                    a.acc_ktu,
                                    i.nama_pegawai AS nm_ktu,
                                    a.acc_direktur,
                                    j.nama_pegawai AS nm_direktur 
                                FROM tm_cuti a
                                INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                                INNER JOIN tm_shift_ketidakhadiran c ON a.id_ketidakhadiran = c.id_ketidakhadiran
                                INNER JOIN tm_unit d ON b.id_unit = d.id_unit
                                INNER JOIN tm_pegawai e ON a.id_user_pengganti = e.id_user
                                INNER JOIN tm_pegawai f ON a.id_user_pj = f.id_user
                                INNER JOIN tm_pegawai g ON a.id_user_kasatpel = g.id_user
                                INNER JOIN tm_pegawai h ON a.id_user_kasie = h.id_user 
                                AND h.`status` = 'AKTIF'
                                INNER JOIN tm_pegawai i ON a.id_user_ktu = i.id_user 
                                AND i.`status` = 'AKTIF'
                                INNER JOIN tm_pegawai j ON a.id_user_direktur = j.id_user 
                                AND j.`status` = 'AKTIF' 
                                ORDER BY
                                    a.tgl_permohonan DESC
                            ");
                            while ($row = fetch_array($sql)) {
                            ?>
                                <tr>
                                    <td><?= $no; ?></td>
                                    <td><?= $row['nama_unit']; ?></td>
                                    <td><?= $row['nama_pegawai']; ?></td>
                                    <td><?= $row['tgl_permohonan']; ?></td>
                                    <td><?= $row['periode_cuti']; ?></td>
                                    <td><?= $row['desc_ketidakhadiran'] . " (" . $row['tahun_cuti'] . ")"; ?></td>
                                    <td>
                                        <table>
                                            <tr>
                                                <td><?= $row['acc_pengganti'] != '-' ? "(" . $row['acc_pengganti'] . ") " : ""; ?>Acc Pengganti</td>
                                                <td>&nbsp;:&nbsp;</td>
                                                <td><?= $row['nm_pengganti']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?= $row['acc_pj'] != '-' ? "(" . $row['acc_pengganti'] . ") " : ""; ?>Acc PJ</td>
                                                <td>&nbsp;:&nbsp;</td>
                                                <td><?= $row['nm_pj']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?= $row['acc_kasatpel'] != '-' ? "(" . $row['acc_pengganti'] . ") " : ""; ?>Acc KSP</td>
                                                <td>&nbsp;:&nbsp;</td>
                                                <td><?= $row['nm_ksp']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?= $row['acc_kasie'] != '-' ? "(" . $row['acc_pengganti'] . ") " : ""; ?>Acc KASIE</td>
                                                <td>&nbsp;:&nbsp;</td>
                                                <td><?= $row['nm_kasie']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?= $row['acc_ktu'] != '-' ? "(" . $row['acc_pengganti'] . ") " : ""; ?>Acc KTU</td>
                                                <td>&nbsp;:&nbsp;</td>
                                                <td><?= $row['nm_ktu']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?= $row['acc_direktur'] != '-' ? "(" . $row['acc_pengganti'] . ") " : ""; ?>Acc Direktur</td>
                                                <td>&nbsp;:&nbsp;</td>
                                                <td><?= $row['nm_direktur']; ?></td>
                                            </tr>
                                        </table>
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
    case "list-data-penambahan-cuti":
        ?>
        <!-- Modal-modal -->
        <div class="modal fade" id="modal-data_penambah_cuti-tambah" role="dialog" aria-label="myModalLabel1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Tambah Data</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="control-label">Tahun Cuti</label>
                                    <select class="form-control" name="tahun-modal-data_penambah_cuti-tambah" id="tahun-modal-data_penambah_cuti-tambah">
                                    <?php loadThn5(); ?>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label class="control-label">Cuti</label>
                                    <select class="form-control" name="cuti-modal-data_penambah_cuti-tambah" id="cuti-modal-data_penambah_cuti-tambah">
                                    <?php 
                                    $sql = bukaquery("
                                        SELECT
                                            a.id_ketidakhadiran, a.desc_ketidakhadiran
                                        FROM tm_shift_ketidakhadiran AS a
                                        WHERE a.is_show_for_cuti_options = '1'
                                    ");
                                    while ($row = fetch_assoc($sql)) {
                                        ?>
                                        <option value="<?= $row['id_ketidakhadiran']; ?>"><?= $row['desc_ketidakhadiran']; ?></option>
                                        <?php
                                    }
                                    ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label">Jenis Penambahan</label>
                                    <select class="form-control" name="jenis_penambahan-modal-data_penambah_cuti-tambah" id="jenis_penambahan-modal-data_penambah_cuti-tambah">
                                        <option value="1">(+) Penambahan</option>
                                        <option value="2">(-) Pengurangan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="control-label">Jumlah</label>
                                    <input type="number" class="form-control" id="jumlah-modal-data_penambah_cuti-tambah" name="jumlah-modal-data_penambah_cuti-tambah">
                                </div>
                                <div class="col-md-9">
                                    <label class="control-label">Keterngan</label>
                                    <input type="text" class="form-control" id="keterangan-modal-data_penambah_cuti-tambah" name="keterangan-modal-data_penambah_cuti-tambah">
                                </div>
                            </div>
                            <label class="control-label">Pegawai</label>
                            <select class="form-control select2" name="pegawai-modal-data_penambah_cuti-tambah" id="pegawai-modal-data_penambah_cuti-tambah" style="width: 100%">
                            <?php 
                            $sql = bukaquery("
                                SELECT
                                    a.id_user, a.nama_pegawai
                                FROM tm_pegawai AS a
                                WHERE a.status_pegawai = 'NON PNS' AND a.status = 'AKTIF'
                                ORDER BY a.nama_pegawai
                            ");
                            while ($row = fetch_assoc($sql)) {
                                ?>
                                <option value="<?= $row['id_user']; ?>"><?= $row['nama_pegawai']; ?></option>
                                <?php
                            }
                            ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="btn_tambah-modal-data_penambah_cuti-tambah" name="btn_tambah-modal-data_penambah_cuti-tambah" onclick="tambah_data_penambahan_cuti(`#`+this.id);">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end Modal-modal -->

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list">LIST PENAMBAHAN PENGURANGAN CUTI PEGAWAI</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <button type="button" class="btn btn-info" onclick="show_modal_tambah_data_penambahan_cuti();"><i class="fa fa-plus"></i>&nbsp;Tambah Data</button>
                <br>
                <br>
                <div class="form-group">
                    <div class="table-responsive">
                        <table id="laporan" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tahun</th>
                                    <th>Cuti</th>
                                    <th>Jenis Cuti</th>
                                    <th>Jumlah</th>
                                    <th>Nama Pegawai</th>
                                    <th>Kepegawaian</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $list_data = bukaquery("
                                        SELECT
                                            a.id,
                                            a.id_user, b.nama_pegawai, a.id_ketidakhadiran, c.desc_ketidakhadiran, a.tahun_cuti,
                                            a.jenis, a.jumlah, a.id_kepegawaian, d.nama_pegawai AS nm_kepegawaian, a.keterangan
                                        FROM tm_penambahan_cuti AS a
                                            INNER JOIN tm_pegawai AS b ON a.id_user = b.id_user
                                            INNER JOIN tm_shift_ketidakhadiran AS c ON a.id_ketidakhadiran = c.id_ketidakhadiran
                                            INNER JOIN tm_pegawai AS d ON a.id_kepegawaian = d.id_user
                                        WHERE a.active = '1'
                                        ORDER BY a.tahun_cuti
                                    ");
                                while ($row = fetch_array($list_data)) {

                                    $jenis_penambahan = $row['jenis'] == '1' 
                                    ? '<span class="label label-primary">Penambahan</span>' 
                                    : '<span class="label label-warning">Pengurangan</span>';
                                ?>
                                    <tr>
                                        <td><?= $no; ?></td>
                                        <td><?= $row['tahun_cuti']; ?></td>
                                        <td><?= $row['desc_ketidakhadiran']; ?></td>
                                        <td><?= $jenis_penambahan; ?></td>
                                        <td><?= $row['jumlah']; ?></td>
                                        <td><?= $row['nama_pegawai']; ?></td>
                                        <td><?= $row['nm_kepegawaian']; ?></td>
                                        <td><?= $row['keterangan']; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-danger" id="btn-data-penambahan-cuti-hapus-<?= $row['id']; ?>" onclick="hapus_data_penambahan_cuti(`#`+this.id, `<?= $row['id']; ?>`);"><i class="fa fa-trash"></i></button>
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
        </div>
        <?php
        break;
    }
?>


<!-- Assesment OK -->
<div class="modal fade" id="modal-assesment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?php echo $aksi . paramEncrypt('act=asesmen-cuti'); ?>" method="POST" role="form">
                <input type="hidden" id="id" name="id">
                <input type="hidden" id="id_user" name="id_user">
                <input type="hidden" id="kolom_acc" name="kolom_acc">
                <input type="hidden" id="kolom_tgl_acc" name="kolom_tgl_acc">
                <input type="hidden" id="jenis_cuti" name="jenis_cuti">
                <input type="hidden" id="link_header" name="link_header">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Assesment Pengajuan Cuti</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-group">
                            <label>Anda akan MENYETUJUI cuti berikut ?</label>
                        </div>
                    </div>
                    <div class="form-group" id="list_tanggal">
                        <label class="control-label">Tanggal Cuti Pegawai</label>
                        <input class="form-control" type="text" id="list_tanggal" name="list_tanggal" readonly>
                    </div>
                    <div class="form-group" id="no_suratcuti">
                        <label class="control-label">Nomor Surat</label>
                        <input class="form-control" type="text" id="no_surti" name="no_surti">
                    </div>
                    <div class="form-group" id="tgl_acc">
                        <label class="control-label">Tanggal Acc</label>
                        <input class="form-control" type="text" id="datepicker1" placeholder="mm/dd/yyyy" name="tgl_acc" required readonly>
                    </div>
                    <div class="form-group" id="jam_acc">
                        <label class="control-label">Jam Acc</label>
                        <input class="form-control" type="text" id="jam-mulai-utama" placeholder="00:00" name="jam_acc" required readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="acc" value="Y" class="btn btn-primary" autofocus>Setuju</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Tutup Assestmen OK -->


<!-- Assesment Tolak -->
<div class="modal fade" id="modal-reject" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?php echo $aksi . paramEncrypt('act=asesmen-cuti'); ?>" method="POST" role="form">
                <input type="hidden" id="id" name="id">
                <input type="hidden" id="id_user" name="id_user">
                <input type="hidden" id="kolom_acc" name="kolom_acc">
                <input type="hidden" id="kolom_tgl_acc" name="kolom_tgl_acc">
                <input type="hidden" id="jenis_cuti" name="jenis_cuti">
                <input type="hidden" id="link_header" name="link_header">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Batalkan Pengajuan Cuti</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">Catatan (opsional)</label>
                        <input class="form-control" type="text" id="reject_notes" name="reject_notes">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="acc" value="T" class="btn btn-primary" autofocus>Batalkan</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Tutup Assestmen Tolak -->


<!-- Hapus Modal Cuti -->
<div class="modal fade" id="modal-hapus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?php echo $aksi . paramEncrypt('module=cuti-pegawai&act=delete-pengajuan-cuti'); ?>" method="POST" id="form-modal-hapus" role="form">
                <input type="hidden" id="id" name="id">
                <input type="hidden" id="link" name="link">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Batalkan Pengajuan Cuti</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-group">
                            <label>Apakah anda yakin ?</label>
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
<!-- Hapus Modal Cuti -->


<!-- Alasan Modal Cuti -->
<div class="modal fade" id="modal-alasan" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?php echo $aksi . paramEncrypt('module=cuti-pegawai&act=update-pengajuan-cuti&id=' . $row['id_cuti'] . ''); ?>" method="POST" role="form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Alasan Penolakan</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-group">
                            <span class="form-control" id="alasan-cuti-ditolak"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Alasan Modal Cuti -->

<script src="libs/jquery/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".show-modal-acc-cuti").click(function() {
            var id = $(this).data('id');
            var id_user = $(this).data('id_user');
            var kolom_acc = $(this).data('kolom_acc');
            var kolom_tgl_acc = $(this).data('kolom_tgl_acc');
            var jenis_cuti = $(this).data('jenis_cuti');
            var link_header = $(this).data('link_header');
            var no_surti = $(this).data('no_surti');
            var list_tanggal = $(this).data('list_tanggal');
            // console.log(id_user);

            if (kolom_acc == 'acc_direktur') {

                $('#no_suratcuti').show();
                $('#tgl_acc').show();
                $('#jam_acc').show();
            } else {

                $('#no_suratcuti').hide();
                $('#tgl_acc').hide();
                $('#jam_acc').hide();
            }

            show_modal_acc_cuti(id, id_user, kolom_acc, kolom_tgl_acc, jenis_cuti, link_header, no_surti, list_tanggal);
        });

        $(".show-modal-reject-cuti").click(function() {
            var id = $(this).data('id');
            var id_user = $(this).data('id_user');
            var kolom_acc = $(this).data('kolom_acc');
            var kolom_tgl_acc = $(this).data('kolom_tgl_acc');
            var jenis_cuti = $(this).data('jenis_cuti');
            var link_header = $(this).data('link_header');

            show_modal_reject_cuti(id, id_user, kolom_acc, kolom_tgl_acc, jenis_cuti, link_header);
        });
    });



    // PROSES TAMBAH CUTI
    function show_modal_pengajuan_cuti(id_user, sub_bagian, id_unit, btn_id) {

        $(btn_id).html("&nbsp;&nbsp;<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;").prop('disabled', true);
        $('#id_user_pengganti').empty();
        $('#id_user_pj').empty();
        $('#id_user_kasatpel').empty();
        $('#id_user_kasie').empty();
        $('#id_user_ktu').empty();
        $('.bukti1').hide();
        $('.bukti2').hide();
        $('.tglcap').hide();
        $('.tglmendadak').hide();

        console.log("<?= $url_api_cuti_pegawai; ?>?action=get_detail_pegawai&id_user=" + id_user + "&sub_bagian=" + sub_bagian + "&id_unit=" + id_unit);

        $.ajax({
            url: "<?= $url_api_cuti_pegawai; ?>?action=get_detail_pegawai&id_user=" + id_user + "&sub_bagian=" + sub_bagian + "&id_unit=" + id_unit,
            type: 'GET',
            dataType: 'JSON',
            success: function(response) {

                if (response.status == 1) {

                    $('#nm_pegawai').val(response.data.data_pegawai.nama_pegawai);
                    $('#jabatan_pegawai').val(response.data.data_pegawai.nm_jabatan);
                    $('#alamat_cuti').val(response.data.data_pegawai.alamat);
                    $('#no_tlp').val(response.data.data_pegawai.no_hp_wa);

                    for (let i = 0; i < response.data.opsi_pengganti_cuti.length; i++) {

                        $('#id_user_pengganti').append(new Option(
                            response.data.opsi_pengganti_cuti[i].nama_pegawai,
                            response.data.opsi_pengganti_cuti[i].id_user
                        ));
                    }

                    for (let i = 0; i < response.data.opsi_pj.length; i++) {

                        $('#id_user_pj').append(new Option(
                            response.data.opsi_pj[i].nama_pegawai,
                            response.data.opsi_pj[i].id_user
                        ));
                    }

                    for (let i = 0; i < response.data.list_ksp.length; i++) {

                        $('#id_user_kasatpel').append(new Option(
                            response.data.list_ksp[i].nama_pegawai,
                            response.data.list_ksp[i].id_user
                        ));
                    }

                    for (let i = 0; i < response.data.opsi_kasie.length; i++) {

                        $('#id_user_kasie').append(new Option(
                            response.data.opsi_kasie[i].nama_pegawai,
                            response.data.opsi_kasie[i].id_user
                        ));
                    }

                    for (let i = 0; i < response.data.opsi_ktu.length; i++) {

                        $('#id_user_ktu').append(new Option(
                            response.data.opsi_ktu[i].nama_pegawai,
                            response.data.opsi_ktu[i].id_user
                        ));
                    }

                    for (let i = 0; i < response.data.opsi_direktur.length; i++) {

                        $('#id_user_direktur').append(new Option(
                            response.data.opsi_direktur[i].nama_pegawai,
                            response.data.opsi_direktur[i].id_user
                        ));
                    }

                    $('#modal-add-pengajuan-cuti').modal('show');
                } else {

                    swal('Data Pegawai tidak ditemukan');
                }

                $(btn_id).html("Buat Pengajuan Cuti").prop('disabled', false);
            },
            error: function(errorMsg) {

                $(btn_id).html("Buat Pengajuan Cuti").prop('disabled', false);
                console.log("error show_modal_pengajuan_cuti eror. " + errorMsg.status + "-" + errorMsg.statusText);
                swal("Error show_modal_pengajuan_cuti " + errorMsg.statusText);
            }
        });

    }

    function cuti_pegawai_sisa_cuti(id_user, current_year, current_month, modal_id, jns_cuti) {

        var selected_year = jns_cuti == '' ? $(modal_id + ' #tahun option:selected').val() : current_year;

        $(modal_id + ' #jns_cuti_permohonan').empty();

        $.ajax({
            url: "<?= $url_api_cuti_pegawai; ?>?action=get_opsi_cuti_pegawai&id_user=" + id_user + "&selected_year=" + selected_year + "&current_year=" + current_year + "&current_month=" + current_month + "&jns_cuti=" + jns_cuti,
            type: "GET",
            dataType: "JSON",
            success: function(response) {

                if (response.status == 1) {

                    $(modal_id + ' #sisa_cuti').val(response.data.opsi_cuti[0].sisa_cuti);

                    for (let i = 0; i < response.data.opsi_cuti.length; i++) {

                        $(modal_id + ' #jns_cuti_permohonan').append(new Option(
                            response.data.opsi_cuti[i].desc_ketidakhadiran,
                            response.data.opsi_cuti[i].id_ketidakhadiran
                        ));
                    }

                    if (jns_cuti != '') {
                        $(modal_id + " #jns_cuti_permohonan option[value='" + jns_cuti + "']").prop('selected', true);

                        cuti_pegawai_sisa_cuti_by_opsi(`<?= $id_user; ?>`, `<?= $thn_now; ?>`, `<?= $bln_now; ?>`, modal_id, jns_cuti, ``);
                    }

                    // verifikasi karna mengubah tahun, biar tombol dibawahnya kehide
                    verifikasi_cuti_ulang();
                } else {

                    swal("Error cuti_pegawai_sisa_cuti 1 " + response.status);
                }
            },
            error: function(errorMsg) {

                console.log("error cuti_pegawai_sisa_cuti 2 eror. " + errorMsg.status + "-" + errorMsg.statusText);
                swal("Error cuti_pegawai_sisa_cuti 3 " + errorMsg.statusText);
            }
        });
    }

    function cuti_pegawai_sisa_cuti_by_opsi(id_user, current_year, current_month, modal_id, selected_jns_cuti, selected_year) {

        var selected_opsi = selected_jns_cuti == '' 
        ? $(modal_id + ' #jns_cuti_permohonan option:selected').val() 
        : selected_jns_cuti;

        var selected_year = selected_year == '' 
        ? $(modal_id + ' #tahun option:selected').val() 
        : selected_year;

        if (selected_opsi == "AKT-000012") { // cuti tahunan
            $(modal_id + " .tglbiasa").removeAttr('required');
            $(modal_id + " .tglbiasa").show();
            $(modal_id + " .tglbiasa").attr('required');
            $(modal_id + " .labeltglbiasa").text('Periode Cuti Tahunan');

            $(modal_id + " .bukti1").hide();
            $(modal_id + " .bukti2").hide();
            // $(modal_id + " .tglmendadak").hide();
            // $(modal_id + " .tglmendadak").removeAttr('required');
            // $(modal_id + " .tglcap").hide();
            // $(modal_id + " .tglcap").removeAttr('required');
        } else if (selected_opsi == "AKT-000018") { // cuti mendadak
            $(modal_id + " .bukti1").show();
            $(modal_id + " .bukti2").show();
            // $(modal_id + " .tglmendadak").removeAttr('required');
            // $(modal_id + " .tglmendadak").show();
            // $(modal_id + " .tglmendadak").attr('required');
            // $(modal_id + " #multidatepicker").prop('id', 'cabeku');
            // $(modal_id + " .labeltglmendadak").text('Periode Cuti Mendadak');

            // $(modal_id + " .tglbiasa").hide();
            // $(modal_id + " .tglbiasa").removeAttr('required');
            // $(modal_id + " .tglcap").hide();
            // $(modal_id + " .tglcap").removeAttr('required');
            $(modal_id + " .tglbiasa").removeAttr('required');
            $(modal_id + " .tglbiasa").show();
            $(modal_id + " .tglbiasa").attr('required');
            $(modal_id + " .labeltglbiasa").text('Periode Cuti Mendadak');
        } else if (selected_opsi == "AKT-000011") { // cuti alasan penting
            // $(modal_id + " .tglcap").removeAttr('required');
            // $(modal_id + " .tglcap").show();
            // $(modal_id + " .tglcap").attr('required');
            $(modal_id + " .bukti1").show();
            $(modal_id + " .labeltglbiasa").text('Periode CAP');
            $(modal_id + " .tglbiasa").removeAttr('required');
            $(modal_id + " .tglbiasa").show();
            $(modal_id + " .tglbiasa").attr('required');

            $(modal_id + " .bukti2").hide();
            // $(modal_id + " .tglmendadak").hide();
            // $(modal_id + " .tglmendadak").removeAttr('required');
            // $(modal_id + " .tglbiasa").hide();
            // $(modal_id + " .tglbiasa").removeAttr('required');
        } else if (selected_opsi == "AKT-000005") { // cuti persalinan
            $(modal_id + " .labeltglbiasa").text('Pilih Tgl Awal Saja');
            $(modal_id + " .bukti1").show();
            $(modal_id + " .tglbiasa").removeAttr('required');
            $(modal_id + " .tglbiasa").show();
            $(modal_id + " .tglbiasa").attr('required');

            $(modal_id + " .bukti2").hide();
            // $(modal_id + " .tglmendadak").hide();
            // $(modal_id + " .tglmendadak").removeAttr('required');
            // $(modal_id + " .tglcap").hide();
            // $(modal_id + " .tglcap").removeAttr('required');
        } else if (selected_opsi == "AKT-000017") { // cpcb
            $(modal_id + " .labeltglbiasa").text('Periode CPCB');
            $(modal_id + " .tglbiasa").removeAttr('required');
            $(modal_id + " .tglbiasa").show();
            $(modal_id + " .tglbiasa").attr('required');

            $(modal_id + " .bukti1").hide();
            $(modal_id + " .bukti2").hide();
            // $(modal_id + " .tglmendadak").hide();
            // $(modal_id + " .tglmendadak").removeAttr('required');
            // $(modal_id + " .tglcap").hide();
            // $(modal_id + " .tglcap").removeAttr('required');
        }

        verifikasi_cuti_ulang();
    }

    function post_pengajuan_cuti(id_user, btn_id) {

        $('#search_absensi').html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;").prop('disabled', true);

        var jenis_form = 'tambah';
        var thn_periode_cuti = $('#tahun option:selected').val();
        var jns_cuti_permohonan = $('#jns_cuti_permohonan option:selected').val();
        var sisa_cuti = $('#sisa_cuti').val();
        var alasan_cuti = $('#alasan_cuti').val();
        var alamat_cuti = $('#alamat_cuti').val();
        var no_tlp = $('#no_tlp').val();
        var list_tgl_cuti = $('#multidatepicker').val();
        var id_user_pengganti = $('#id_user_pengganti option:selected').val();
        var id_user_pj = $('#id_user_pj option:selected').val();
        var id_user_kasatpel = $('#id_user_kasatpel option:selected').val();
        var id_user_kasie = $('#id_user_kasie option:selected').val();
        var id_user_ktu = $('#id_user_ktu option:selected').val();
        var id_user_direktur = $('#id_user_direktur option:selected').val();


        $.ajax({
            url: "<?= $url_api_cuti_pegawai; ?>?action=post_pengajuan_cuti",
            type: "POST",
            data: {
                id_user: id_user,
                list_tgl_cuti: list_tgl_cuti,
                periode_thn_cuti: thn_periode_cuti,
                id_ketidakhadiran: jns_cuti_permohonan,
                sisa_cuti: sisa_cuti,
                alasan_cuti: alasan_cuti,
                alamat_cuti: alamat_cuti,
                nohp_cuti: no_tlp,
                iduser_pengganti: id_user_pengganti,
                iduser_pj: id_user_pj,
                iduser_ksp: id_user_kasatpel,
                iduser_kasie: id_user_kasie,
                iduser_ktu: id_user_ktu,
                iduser_direktur: id_user_direktur,
                jenis_form: jenis_form,
            },
            dataType: "JSON",
            success: function(response) {

                if (response.status == 1) {
                    swal({
                        title: "Pengajuan Cuti Berhasil",
                        type: "success"
                    }, function() {
                        window.location.reload();
                    });
                } else {
                    swal("Error. " + response.status);
                }
                $('#search_absensi').html("Simpan").prop('disabled', false);
            },
            error: function(errorMsg) {

                $('#search_absensi').html("Simpan").prop('disabled', false);
                console.log("error post_pengajuan_cuti eror. " + errorMsg.status + "-" + errorMsg.statusText);
                swal("Error post_pengajuan_cuti " + errorMsg.statusText);
            }
        });
    }

    function verifikasi_cuti_ulang() {

        $('#assesmentpengganti_div').hide();
        $('#btn_send_pengajuan_cuti').hide();
    }

    function verifikasi_cuti(btn_id, id_user, thn_now) {

        const selected_year = $('#modal-add-pengajuan-cuti #tahun option:selected').val();
        const id_ketidakhiadiran = $('#modal-add-pengajuan-cuti #jns_cuti_permohonan').val();

        $('#modal-add-pengajuan-cuti #sisa_cuti').val('0');
        $("#modal-add-pengajuan-cuti #sisa_cuti").show();
        $(btn_id).html("&nbsp;&nbsp;<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;").prop('disabled', true);

        // sebelum muncul assesment pengganti cuti, pastikan cuti yang akan diajukan itu valid
        $.ajax({
            url: "<?= $url_api_cuti_pegawai; ?>?action=get_hasil_verifikasi_cuti",
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_user: id_user,
                selected_year: selected_year,
                current_year: thn_now,
                id_ketidakhadiran: id_ketidakhiadiran
            },
            success: function(response) {

                if(response.status == 1) {

                    const length_data = response.periode_cuti_setting.tanggal_tersedia.length;
                    const startDate = length_data != 0
                    ? response.periode_cuti_setting.tanggal_tersedia[0]
                    : null;
                    const endDate = length_data != 0
                    ? response.periode_cuti_setting.tanggal_tersedia[length_data - 1]
                    : null;

                    $('#list_tgl_cuti').val("");
                    $('#modal-add-pengajuan-cuti .multidatepicker').datepicker('destroy');
                    if(startDate != null && endDate != null) { // hanya muncul datepicker nya jika batas tanggal ada

                        $('#modal-add-pengajuan-cuti .multidatepicker').datepicker({
                            format: "mm/dd/yyyy",
                            multidate: true,
                            autoclose: false,
                            startDate: startDate,
                            endDate: endDate,
                        });
                    }
                    $('#modal-add-pengajuan-cuti #sisa_cuti').val(response.periode_cuti_setting.kuota_cuti);

                    $('#modal-add-pengajuan-message').data('message', response.message);

                    $('#assesmentpengganti_div').show();
                    $('#btn_send_pengajuan_cuti').show();
                    
                    if(response.message_2 != '') {

                        swal(response.message_2);
                    }
                } else {

                    console.log("error verifikasi_cuti eror. " + response.message);
                    swal("Error verifikasi_cuti " + response.message);
                }

                $(btn_id).html("Verifikasi").prop('disabled', false);
            },
            error: function(errorMsg) {

                $('#assesmentpengganti_div').hide();
                $('#btn_send_pengajuan_cuti').hide();

                $(btn_id).html("Verifikasi").prop('disabled', false);
                console.log("error verifikasi_cuti eror. " + errorMsg.status + "-" + errorMsg.statusText);
                swal("Error verifikasi_cuti " + errorMsg.statusText);
            }
        });
    }

    function show_modal_detail_sisa_cuti() {
        
        const savedString = $('#modal-add-pengajuan-message').data('message');
        swal({
            title: "",
            text: savedString
        });
    }
    // PROSES TAMBAH CUTI



    // PROSES UPDATE CUTI
    function show_modal_update_cuti(id_cuti, id_ketidakhadiran) {

        // $("#").html("&nbsp;&nbsp;<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;").prop('disabled', true);

        $('#form-update-cuti #id_user_pengganti').empty();
        $('#form-update-cuti #id_user_pj').empty();
        $('#form-update-cuti #id_user_kasatpel').empty();
        $('#form-update-cuti #id_user_kasie').empty();
        $('#form-update-cuti #id_user_ktu').empty();
        $('#form-update-cuti .bukti1').hide();
        $('#form-update-cuti .bukti2').hide();
        $('#form-update-cuti .tglcap').hide();
        $('#form-update-cuti .tglmendadak').hide();

        console.log("<?= $url_api_cuti_pegawai; ?>?action=get_form_update_cuti&id_cuti=" + id_cuti + "&id_ketidakhadiran=" + id_ketidakhadiran);
        //return false;

        $.ajax({
            url: "<?= $url_api_cuti_pegawai; ?>?action=get_form_update_cuti&id_cuti=" + id_cuti + "&id_ketidakhadiran=" + id_ketidakhadiran,
            type: 'GET',
            dataType: 'JSON',
            success: function(response) {
                // console.log(response);
                // return false;

                if (response.status == 1) {

                    var current_year = new Date().getFullYear();
                    var hari_cuti = response.data.hari_cuti;

                    $('#form-update-cuti #id_cuti_lama').val(id_cuti);
                    $('#form-update-cuti #nm_pegawai').val(response.data.data_pegawai.nama_pegawai);
                    $('#form-update-cuti #jabatan_pegawai').val(response.data.data_pegawai.nm_jabatan);
                    $('#form-update-cuti #alamat_cuti').val(response.data.data_pegawai.alamat);
                    $('#form-update-cuti #no_tlp').val(response.data.data_pegawai.no_hp_wa);
                    $('#form-update-cuti #alasan_cuti').val(response.data.alasan_cuti);

                    if (id_ketidakhadiran == 'AKT-000011')
                        $("#form-update-cuti .multidatepickercap").datepicker('setDates', hari_cuti);
                    else if (id_ketidakhadiran == 'AKT-000018')
                        $("#form-update-cuti .multidatepickermendadak").datepicker('setDates', hari_cuti);
                    else
                        $("#form-update-cuti .multidatepicker").datepicker('setDates', hari_cuti);


                    cuti_pegawai_sisa_cuti(response.data.data_pegawai.id_user, response.data.tahun_cuti, 1, '#form-update-cuti', id_ketidakhadiran);

                    $('#form-update-cuti #tahun').val(response.data.tahun_cuti).change();

                    for (let i = 0; i < response.data.opsi_pengganti_cuti.length; i++) {

                        $('#form-update-cuti #id_user_pengganti').append(new Option(
                            response.data.opsi_pengganti_cuti[i].nama_pegawai,
                            response.data.opsi_pengganti_cuti[i].id_user
                        ));
                    }

                    for (let i = 0; i < response.data.opsi_pj.length; i++) {

                        $('#form-update-cuti #id_user_pj').append(new Option(
                            response.data.opsi_pj[i].nama_pegawai,
                            response.data.opsi_pj[i].id_user
                        ));
                    }

                    for (let i = 0; i < response.data.list_ksp.length; i++) {

                        $('#form-update-cuti #id_user_kasatpel').append(new Option(
                            response.data.list_ksp[i].nama_pegawai,
                            response.data.list_ksp[i].id_user
                        ));
                    }

                    for (let i = 0; i < response.data.opsi_kasie.length; i++) {

                        $('#form-update-cuti #id_user_kasie').append(new Option(
                            response.data.opsi_kasie[i].nama_pegawai,
                            response.data.opsi_kasie[i].id_user
                        ));
                    }

                    for (let i = 0; i < response.data.opsi_ktu.length; i++) {

                        $('#form-update-cuti #id_user_ktu').append(new Option(
                            response.data.opsi_ktu[i].nama_pegawai,
                            response.data.opsi_ktu[i].id_user
                        ));
                    }

                    for (let i = 0; i < response.data.opsi_direktur.length; i++) {

                        $('#form-update-cuti #id_user_direktur').append(new Option(
                            response.data.opsi_direktur[i].nama_pegawai,
                            response.data.opsi_direktur[i].id_user
                        ));
                    }

                    $('#form-update-cuti #id_user_pengganti').val(response.data.id_user_pengganti);
                    $('#form-update-cuti #id_user_pj').val(response.data.id_user_pj);
                    $('#form-update-cuti #id_user_kasatpel').val(response.data.id_user_kasatpel);
                    $('#form-update-cuti #id_user_kasie').val(response.data.id_user_kasie);
                    $('#form-update-cuti #id_user_ktu').val(response.data.id_user_ktu);

                    $('#modal-form-update-cuti').modal('show');
                } else {

                    swal('Data Pegawai tidak ditemukan');
                }

                // $(btn_id).html("Buat Pengajuan Cuti").prop('disabled', false);
            },
            error: function(errorMsg) {

                // $(btn_id).html("Update Pengajuan Cuti").prop('disabled', false);
                console.log("error show_modal_update_cuti eror. " + errorMsg.status + "-" + errorMsg.statusText);
                swal("Error show_modal_update_cuti " + errorMsg.statusText);
            }
        });

    }
    // PROSES UPDATE CUTI



    // MODAL ACC / REJECT CUTI
    // TAMPILKAN MODAL ACC CUTI
    function show_modal_acc_cuti(id, id_user, kolom_acc, kolom_tgl_acc, jenis_cuti, link_header, no_surti, list_tanggal) {
        $('#modal-assesment #id').val(id);
        $('#modal-assesment #id_user').val(id_user);
        $('#modal-assesment #kolom_acc').val(kolom_acc);
        $('#modal-assesment #kolom_tgl_acc').val(kolom_tgl_acc);
        $('#modal-assesment #jenis_cuti').val(jenis_cuti);
        $('#modal-assesment #link_header').val(link_header);
        $('#modal-assesment #no_surti').val(no_surti);
        $('#modal-assesment #list_tanggal').val(list_tanggal);

        $('#modal-assesment').modal('show');
    }

    // TAMPILKAN MODAL REJECT CUTI
    function show_modal_reject_cuti(id, id_user, kolom_acc, kolom_tgl_acc, jenis_cuti, link_header) {
        $('#modal-reject #id').val(id);
        $('#modal-reject #id_user').val(id_user);
        $('#modal-reject #kolom_acc').val(kolom_acc);
        $('#modal-reject #kolom_tgl_acc').val(kolom_tgl_acc);
        $('#modal-reject #jenis_cuti').val(jenis_cuti);
        $('#modal-reject #link_header').val(link_header);

        $('#modal-reject').modal('show');
    }
    // MODAL ACC / REJECT CUTI



    // PROSES HAPUS CUTI
    function show_modal_hapus_cuti(id_cuti, link) {

        // $("#").html("&nbsp;&nbsp;<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;").prop('disabled', true);

        $('#form-modal-hapus #id').val(id_cuti);
        $('#form-modal-hapus #link').val(link);
        $('#modal-hapus').modal('show');

    }

    // PROSES HAPUS CUTI
    function alasan_cuti_ditolak(alasan) {
        console.log(alasan);
        $("#modal-alasan #alasan-cuti-ditolak").text(alasan);
    }

    function cari_rekapitulasi_cuti_pegawai(btn_id) {

        $(btn_id).html("&nbsp;&nbsp;<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;").prop('disabled', true);

        const sub_bagian = $('#rekap-cuti_subbagian').val();
        const selected_year = $('#rekap-cuti_tahun').val();

        let temp_table = $('#rekap-cuti_box #example1').DataTable();
        temp_table.clear().draw();
        
        $.ajax({
            url: "<?= $url_api_cuti_pegawai; ?>?action=get_summary_cuti_pegawai",
            type: 'POST',
            data: {
                sub_bagian: sub_bagian,
                selected_year: selected_year
            },
            dataType: 'JSON',
            success: function(response) {

                console.log(response);
                if(response.status == 1) {
                    
                    for (let i = 0; i < response.list_cuti_pegawai.length; i++) {
                        
                        let row_data = [
                            (i + 1),
                            response.list_cuti_pegawai[i].nama_pegawai,
                            response.list_cuti_pegawai[i].sub_bagian
                        ];

                        for (let j = 0; j < response.list_cuti_pegawai[i].list_cuti.length; j++) {

                            row_data.push(response.list_cuti_pegawai[i].list_cuti[j].sisa_cuti);
                        }
                        temp_table.row.add(row_data);
                        temp_table.draw();
                    }
                }

                $(btn_id).html('<i class="fa fa-search">&nbsp;&nbsp;Cari</i>').prop('disabled', false);
            },
            error: function(xhr, status, error) {

                $(btn_id).html('<i class="fa fa-search">&nbsp;&nbsp;Cari</i>').prop('disabled', false);
                console.log("error cari_rekapitulasi_cuti_pegawai eror. " + errorMsg.status + "-" + errorMsg.statusText);
                swal("Error cari_rekapitulasi_cuti_pegawai " + errorMsg.statusText);
            }
        });
    }
    // end section list-data-pengajuan-cuti-pegawai

    // start list-data-penambahan-cuti
    function show_modal_tambah_data_penambahan_cuti() {

        $('#modal-data_penambah_cuti-tambah').modal('show');
    }

    function tambah_data_penambahan_cuti(btn_id) {

        const tahun = $('#tahun-modal-data_penambah_cuti-tambah').val();
        const cuti = $('#cuti-modal-data_penambah_cuti-tambah option:selected').val();
        const jenis_penambahan = $('#jenis_penambahan-modal-data_penambah_cuti-tambah option:selected').val();
        const jumlah = $('#jumlah-modal-data_penambah_cuti-tambah').val();
        const keterangan = $('#keterangan-modal-data_penambah_cuti-tambah').val();
        const id_user = $('#pegawai-modal-data_penambah_cuti-tambah').val();
        const id_pegawaian = <?= $id_user ?>;

        $(btn_id).html("&nbsp;&nbsp;<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;").prop('disabled', true);

        $.ajax({
            url: "<?= $url_api_cuti_pegawai; ?>?action=post_penambahan_cuti",
            type: 'POST',
            data: {
                tahun: tahun,
                cuti: cuti,
                jenis_penambahan: jenis_penambahan,
                jumlah: jumlah,
                keterangan: keterangan,
                id_user: id_user,
                id_pegawaian: id_pegawaian
            },
            dataType: 'JSON',
            success: function(response){

                if (response.status == 1) {

                    swal({
                        title: "Berhasil",
                        type: "success"
                    }, function() {
                        window.location.reload();
                    });
                } else {

                    swal(response.message);
                }

                $(btn_id).html("Simpan").prop('disabled', false);
            },
            error: function(errorMsg) {

                $(btn_id).html("Simpan").prop('disabled', false);
                console.log("error tambah_data_penambahan_cuti eror. " + errorMsg.status + "-" + errorMsg.statusText);
                swal("Error tambah_data_penambahan_cuti " + errorMsg.statusText);
            }
        });
    }

    function hapus_data_penambahan_cuti(btn_id, id) {
        
        $(btn_id).html("&nbsp;&nbsp;<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;&nbsp;").prop('disabled', true);

        $.ajax({
            url: "<?= $url_api_cuti_pegawai; ?>?action=delete_penambahan_cuti",
            type: 'POST',
            data: {
                id: id
            },
            dataType: 'JSON',
            success: function(response){

                if (response.status == 1) {

                    swal({
                        title: "Berhasil hapus",
                        type: "success"
                    }, function() {
                        window.location.reload();
                    });
                } else {

                    swal(response.message);
                }

                $(btn_id).html('<i class="fa fa-trash"></i>').prop('disabled', false);
            },
            error: function(errorMsg) {

                $(btn_id).html('<i class="fa fa-trash"></i>').prop('disabled', false);
                console.log("error hapus_data_penambahan_cuti eror. " + errorMsg.status + "-" + errorMsg.statusText);
                swal("Error hapus_data_penambahan_cuti " + errorMsg.statusText);
            }
        });
    }
    // end list-data-penambahan-cuti
</script>