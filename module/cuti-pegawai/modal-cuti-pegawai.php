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
                                        <label class="control-label">Tahun Cuti </label>
                                        <select class="form-control select3" onchange="cuti_pegawai_sisa_cuti(`<?= $id_user; ?>`, `<?= $thn_now; ?>`, `<?= $bln_now; ?>`)" id="tahun" name="tahun" style="width: 100%;" required>
                                            <option disabled selected>-</option>
                                            <?php loadThn5(); ?>
                                        </select>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label>Jenis Cuti</label>
                                                <select class="form-control select3" id="jns_cuti_permohonan" name="jns_cuti_permohonan" style="width: 100%" onchange="cuti_pegawai_sisa_cuti_by_opsi(`<?= $id_user; ?>`, `<?= $thn_now; ?>`, `<?= $bln_now; ?>`)" required>
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
                                                <input type="text" name="list_tgl_cuti" class="form-control pull-right" id="multidatepicker">
                                            </div>
                                        </div>
                                        <div class="tglmendadak">
                                            <label class="labeltglmendadak">Periode Cuti</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class=""></i></div>
                                                <input type="text" name="list_tgl_cutimendadak" class="form-control pull-right" id="multidatepickermendadak">
                                            </div>
                                        </div>
                                        <div class="tglcap">
                                            <label>Periode Cuti</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class=""></i></div>
                                                <input type="text" name="list_tgl_cuticap" class="form-control pull-right" id="multidatepickercap">
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
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <!-- <input id="btn_send_pengajuan_cuti" type="button" class="btn btn-primary" value="Simpan" onclick="post_pengajuan_cuti(`<?= $id_user; ?>`, `#`+this.id);"> -->
                                    <input id="btn_send_pengajuan_cuti" type="submit" class="btn btn-primary" value="Simpan">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Tutup Modal Cuti -->