<?php
$aksi = "module/tukar-shift/aksi-tukar-shift?";
$id = isset($url['id']) ? $url['id'] : null;
$dpa = isset($url['dpa']) ? $url['dpa'] : null;
switch ((isset($url['act']) ? $url['act'] : '')) {
    default :
        echo"default";
        header('location:error.php');
        break;
    case 'tukar-shift-add':
        ?>
        
        <input type="hidden" id="tukar-shift_id-user" name="tukar-shift_id-user" value="<?php echo $id_user; ?>">
        <input type="hidden" id="tukar-shift_id-unit" name="tukar-shift_id-unit" value="<?php echo $id_unit; ?>">
        <input type="hidden" id="tukar-shift_curyear" name="tukar-shift_curyear" value="<?php echo $thn_now; ?>">
        <input type="hidden" id="tukar-shift_curmonth" name="tukar-shift_curmonth" value="<?php echo $bln_now; ?>">

        <!-- Modal-modal -->
        <div class="modal fade" id="tukar-shift_modal-add" name="tukar-shift_modal-add">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title fa fa-plus">&nbsp;Tambah Permitaan Pergantian Dinas</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" id="tukarshift_idsender" name="tukarshift_idsender" value="<?php echo $id_user; ?>"> 
                            <label>Nama Pengirim</label>
                            <input type="text" class="form-control" value="<?php echo $nama_pegawai; ?>" required readonly>
                            <label>Dinas Pengirim</label>
                            <select class="form-control select2" id="tukarshift_shift_1" name="tukarshift_shift_1" style="width: 100%;" onchange="get_list_user_tukarshift(`<?php echo $bln_now; ?>`, `<?php echo $thn_now; ?>`);" required>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nama Penerima</label>
                            <select class="form-control select2" id="tukarshift_idreceiver" name="tukarshift_idreceiver" style="width: 100%;" onchange="get_list_shift_tukarshift();" required>
                            </select>
                            <label>Dinas Penerima</label>
                            <select class="form-control select2" id="tukarshift_shift_2" name="tukarshift_shift_2" style="width: 100%;" required>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Keperluan</label>
                            <textarea class="form-control" id="tukarshift_keperluan" name="tukarshift_keperluan">-</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button id="tukar-shift-add_submit-btn" name="tukar-shift-add_submit-btn" type="submit" class="btn btn-success" onclick="submit_permintaan_tukarshift();">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="tukar-shift_modal-hapus" name="tukar-shift_modal-hapus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="<?php echo $aksi.paramEncrypt('module=tukar-shift&act=delete-tukar-shift'); ?>" method="POST" role="form">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Hapus Permintaan Pergantian Dinas</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="tukar-shift_id" name="tukar-shift_id"> 
                            <div class="form-group">
                                <label>Yakin anda ingin menghapus permitaan ini ?</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="tukar-shift_modal-log-tukarshift">
            <div class="modal-dialog">
                <div class="modal-content modal-lg">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-label="true">&times;</span>
                        </button>
                        <h4 class="modal-title fa fa-plus">&nbsp;Log Status Permintaan Pertukaran Dinas</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <table id="tukar-shift_modal-log-tukarshift_table" name="tukar-shift_modal-log-tukarshift_table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Keterangan</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end Modal-modal -->

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list">
                    &nbsp;PERGANTIAN DINAS
                </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-toogle="tooltop" title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <button type="button" class="btn btn-success fa fa-plus" data-toogle="modal" onclick="modal_add_permintaan_tukarshift(`<?php echo $id_user; ?>`, `<?php echo $id_unit; ?>`, `<?php echo $bln_now; ?>`, `<?php echo $thn_now; ?>`);">&nbsp;Buat Permintaan</button>
                </div>
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Pengirim</th>
                                <th>Penerima</th>
                                <th>Keperluan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                            $i = 1;
                            $sql = bukaquery2("
                                SELECT
                                    a.id_tukarshift, 
                                    a.id_sender, b.nama_pegawai AS nm_pegawai_sender,
                                    a.id_jadwalkerja_shift_1, d.date AS date_1, g.desc_shift AS desc_shift_1, g.jam_masuk AS jam_masuk_1, g.jam_pulang AS jam_pulang_1,
                                    a.id_receiver, c.nama_pegawai AS nm_pegawai_receiver,
                                    a.id_jadwalkerja_shift_2, e.date AS date_2, f.desc_shift AS desc_shift_2, f.jam_masuk AS jam_masuk_2, f.jam_pulang AS jam_pulang_2,
                                    a.id_tukarshift_status, h.nama_status, h.start_order,
                                    a.keperluan,
                                    a.created
                                FROM tt_tukarshift a
                                    INNER JOIN tm_pegawai b ON a.id_sender = b.id_user
                                    INNER JOIN tm_pegawai c ON a.id_receiver = c.id_user
                                    INNER JOIN tm_jadwalpegawai_shift_m d ON a.id_jadwalkerja_shift_1 = d.id_jadwalkerja_shift
                                    INNER JOIN tm_jadwalpegawai_shift_m e ON a.id_jadwalkerja_shift_2 = e.id_jadwalkerja_shift
                                    INNER JOIN tm_shift f ON d.id_absensi = f.id_absensi
                                    INNER JOIN tm_shift g ON e.id_absensi = g.id_absensi
                                    INNER JOIN tm_tukarshift_order h ON a.id_tukarshift_status = h.id_tukarshift_status
                                    WHERE a.id_sender = '".$id_user."'
                                ORDER BY a.created DESC
                            ");

                            while ($row = fetch_array($sql)) {
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td>
                                        <table>
                                            <tr>
                                                <td>Nama Pegawai</td>
                                                <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                                <td><?php echo $row['nm_pegawai_sender']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Tanggal Pergantian</td>
                                                <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                                <td><?php echo $row['date_1']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Shift</td>
                                                <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                                <td><?php echo $row['desc_shift_1']; ?> (<?php echo $row['jam_masuk_1']; ?> - <?php echo $row['jam_pulang_1']; ?>)</td>
                                            </tr>
                                        </table>
                                        
                                    </td>
                                    <td>
                                        <table>
                                            <tr>
                                                <td>Nama Pegawai</td>
                                                <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                                <td><?php echo $row['nm_pegawai_receiver']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Tanggal Pergantian</td>
                                                <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                                <td><?php echo $row['date_2']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Shift</td>
                                                <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                                <td><?php echo $row['desc_shift_2']; ?> (<?php echo $row['jam_masuk_2']; ?> - <?php echo $row['jam_pulang_2']; ?>)</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td><?php echo $row['keperluan']; ?></td>
                                    <td>
                                        <?php echo $row['nama_status']; ?>
                                        <br>
                                        <button type="button" class="btn btn-primary btn-xs" onclick="get_log_tukarshift(`<?php echo $row['id_tukarshift']; ?>`);">Log Validasi</button>
                                    </td>
                                    <td>
                                    <?php if($row['id_sender'] == $id_user && $row['start_order'] == 1) { ?>
                                    <button class="btn btn-danger" onclick="delete_permintaan_tukarshift(`<?php echo $row['id_tukarshift']; ?>`);">Hapus</button>
                                    <?php } ?>   
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php
        break;
    case 'tukar-shift-receive':
        ?>

        <!-- Modal-modal -->
        <!-- End Modal-modal -->

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title fa fa-list">
                    &nbsp;PERMINTAAN PERGANTIAN DINAS
                </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-toogle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Pengirim</th>
                            <th>Shift Ditukar</th>
                            <th>Penerima</th>
                            <th>Keperluan</th>
                            <th>Status</th>
                            <th>Tanggal Permintaan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    
                        $i = 1;
                        bukaquery2("
                            UPDATE
                                tt_tukarshift_validation
                            SET timestamp_read = NOW()
                            WHERE answered = 0
                        ");
                        
                        $sql = bukaquery2("
                            SELECT
                                a.id_tukarshift_validation,
                                b.nama_pegawai AS nm_sender,
                                h.nama_pegawai AS nm_receiver,
                                a.id_tukarshift, 
                                d.date AS date_1, f.desc_shift AS desc_shift_1, f.jam_masuk AS jam_masuk_1, f.jam_pulang AS jam_pulang_1,
                                e.date AS date_2, g.desc_shift AS desc_shift_2, g.jam_masuk AS jam_masuk_2, g.jam_pulang AS jam_pulang_2,
                                c.keperluan,
                                a.answered,
                                a.timestamp_request
                            FROM tt_tukarshift_validation a
                                INNER JOIN tm_pegawai b ON a.id_sender = b.id_user
                                INNER JOIN tt_tukarshift c ON a.id_tukarshift = c.id_tukarshift
                                INNER JOIN tm_jadwalpegawai_shift_m d ON c.id_jadwalkerja_shift_1 = d.id_jadwalkerja_shift
                                INNER JOIN tm_jadwalpegawai_shift_m e ON c.id_jadwalkerja_shift_2 = e.id_jadwalkerja_shift
                                INNER JOIN tm_shift f ON d.id_absensi = f.id_absensi
                                INNER JOIN tm_shift g ON e.id_absensi = g.id_absensi
                                INNER JOIN tm_pegawai h ON h.id_user = c.id_receiver
                            WHERE a.id_receiver = '".$id_user."'
                            ORDER BY a.timestamp_request DESC
                        ");
                        while ($row = fetch_array($sql)) {
                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $row['nm_sender']; ?></td>
                                <td>
                                <table>
                                    <tr>
                                        <td>Dari :</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>Menjadi :</td>
                                    </tr>
                                    <tr>
                                        <td>
                                        <?php echo $row['date_1']."<br>".$row['desc_shift_1']."<br>".$row['jam_masuk_1']."-".$row['jam_pulang_1']; ?>
                                        </td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>
                                        <?php echo $row['date_2']."<br>".$row['desc_shift_2']."<br>".$row['jam_masuk_2']."-".$row['jam_pulang_2']; ?>
                                        </td>
                                    </tr>
                                </table>
                                </td>
                                <td><?php echo $row['nm_receiver']; ?></td>
                                <td><?php echo $row['keperluan']; ?></td>
                                <td><?php echo $row['answered'] == 0 ? "Belum Dijawab": ($row['answered'] == 1 ? "Diterima" : "Ditolak"); ?></td>
                                <td><?php echo $row['timestamp_request']; ?></td>
                                <td>
                                <?php echo $row['answered'] == 0 
                                    ? "<button id='btn_tukarshift_ans_y_".$row['id_tukarshift_validation']."' name='btn_tukarshift_ans_y_".$row['id_tukarshift_validation']."' type='button' class='btn btn-success btn-block btn-sm' onclick='post_answer_validation_by_receiver(`#`+this.id, `1`, `".$row['id_tukarshift_validation']."`);'>TERIMA</button> <button id='btn_tukarshift_ans_n_".$row['id_tukarshift_validation']."' name='btn_tukarshift_ans_n_".$row['id_tukarshift_validation']."' type='button' class='btn btn-warning btn-block btn-sm' onclick='post_answer_validation_by_receiver(`#`+this.id, `2`, `".$row['id_tukarshift_validation']."`);'>TOLAK</button>"
                                    : "-"
                                ?>
                                </td>
                            </tr>
                            <?php
                            $i++;
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
        break;
}
?>
<script src="libs/jquery/jquery.min.js"></script>
<script>

    // fungsi untuk tukar-shift
    function modal_add_permintaan_tukarshift(id_sender, id_unit, month, year) {

        month = 4;

        $.ajax({
            type: "GET",
            url: "<?php echo $url_api_tukar_shift ?>?action=get_validation_status_add&id_sender="+id_sender+"&id_unit="+id_unit+"&month="+month+"&year="+year,
            dataType: "JSON",
            success: function(response) {

                if(response.status == 1) {

                    $('#tukarshift_shift_1').empty();
                    for (let i = 0; i < response.data.length; i++) {
                        $('#tukarshift_shift_1').append(new Option(
                            response.data[i].date+" "+response.data[i].desc_shift+" ("+response.data[i].jam_masuk+"-"+response.data[i].jam_pulang+")",
                            response.data[i].id_jadwalkerja_shift,
                            false,
                            false
                        ));
                        
                    }
                    $('#tukar-shift_modal-add').modal('show');
                } else {

                    swal("Permintaan Pergantian Dinas Ditolak", response.message, "error");
                    console.log("Kode : GETVLDTUKARSHIFT1. Kode status != 0. Pesan :"+response.message);
                }
            },
            error: function(error) {
                
                swal("Gagal Mendapatkan Status Validasi Pergantian Dinas", "", "error");
                console.log("Kode : GETVLDTUKARSHIFT1. Gagal mengirim permintaan. "+error.status+"-"+error.statusText);
            }
        });
        
    }

    function get_list_user_tukarshift(month, year) {

        $('#tukarshift_idreceiver').empty();
        
        var id_sender = $('#tukarshift_idsender').val();
        var id_unit = $('#tukar-shift_id-unit').val();

        $.ajax({
            type: "GET",
            url: "<?php echo $url_api_tukar_shift ?>?action=get_list_user_tukarshift&month="+month+"&year="+year+"&id_unit="+id_unit+"&id_sender="+id_sender,
            dataType: "JSON",
            success: function(response) {
                
                if(response.status == 1) {

                    $('#tukarshift_idreceiver').empty();

                    for (let i = 0; i < response.data.length; i++) {

                        $('#tukarshift_idreceiver').append(new Option(
                            response.data[i].nama_pegawai,
                            response.data[i].id_user,
                            false,
                            false
                        ));
                    }

                    // langsung dicari data shiftnya. berdasarkan pilihan pertama dari id_receiver
                    get_list_shift_tukarshift();
                    
                } else {

                    console.log('GETLSTUSRTUKARSHIFT01. Kode Status != 1');
                }
            },
            error: function(error) {
                
                console.log('GETLSTUSRTUKARSHIFT01. Gagal Mengirim Permitaan. '+error.status+"-"+error.statusText);
            }
        });
    }

    function get_list_shift_tukarshift() {

        $('#tukarshift_shift_2').empty();

        var id_unit = $('#tukar-shift_id-unit').val();
        var month = $('#tukar-shift_curmonth').val();
        var year = $('#tukar-shift_curyear').val();
        var id_receiver = $('#tukarshift_idreceiver option:selected').val();

        $.ajax({
            type: "GET",
            url: "<?php echo $url_api_tukar_shift ?>?action=get_list_shift_tukarshift&id_unit="+id_unit+"&month="+month+"&year="+year+"&id_receiver="+id_receiver,
            dataType: "JSON",
            success: function(response) {
                
                if(response.status == 1) {

                    $('#tukarshift_shift_2').empty();
                    for (let i = 0; i < response.data.length; i++) {
                        
                        $('#tukarshift_shift_2').append(new Option(
                            response.data[i].date+" "+response.data[i].desc_shift+" ("+response.data[i].jam_masuk+"-"+response.data[i].jam_pulang+")",
                            response.data[i].id_jadwalkerja_shift,
                            false,
                            false
                        ));
                    }
                } else {

                    console.log("Kode : GETVLDTUKARSHIFT2. Kode status != 0");
                }
            },
            error: function(error) {
                
                console.log("Kode : GETVLDTUKARSHIFT2. Gagal Mengirim Permintaan. "+error.status+"-"+error.statusText);
            }
        });
    }

    function get_log_tukarshift(id_tukarshift) {

        $('#tukar-shift_modal-log-tukarshift_table tbody').empty();

        $.ajax({
            url: "<?php echo $url_api_tukar_shift; ?>?action=get_log_tukarshift&id_tukarshift="+id_tukarshift,
            type: "GET",
            dataType: "JSON",
            success: function(response) {

                if(response.status == 1) {

                    for (let i = 0; i < response.data.length; i++) {
                    
                        var index = i + 1;
                        var keterangan = response.data[i]['keterangan'];
                        var created = response.data[i]['created'];
                        
                        $('#tukar-shift_modal-log-tukarshift_table tbody').append("<tr><td>"+index+"</td><td>"+keterangan+"</td><td>"+created+"</td></tr>");
                    }

                    if(!$.fn.dataTable.isDataTable('#tukar-shift_modal-log-tukarshift_table')) {

                        $('#tukar-shift_modal-log-tukarshift_table').DataTable({
                            "responsive": true,
                            "autoWidth": true
                        });
                    }
                } else {

                    console.log('Kode GETLOGTKRSHIFT01. Kode Status = 0');
                }

                $('#tukar-shift_modal-log-tukarshift').modal('show');
            },
            error: function(error){

                console.log('Kode GETLOGTKRSHIFT01. Gagal mengirim permintaan. '+error.status+'-'+error.statusText);
                swal('Gagal Mengirim Permintaan Log Tukar Shift', '', 'error');
            }
        });
    }

    function submit_permintaan_tukarshift() {

        $('#tukar-shift-add_submit-btn').html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>&nbsp;Mohon Tunggu").prop('disabled', true);

        var id_sender = $('#tukarshift_idsender').val();
        var id_receiver = $('#tukarshift_idreceiver').val();
        var id_jadwalkerja_shift_1 = $('#tukarshift_shift_1 option:selected').val();
        var id_jadwalkerja_shift_2 = $('#tukarshift_shift_2 option:selected').val();
        var keperluan = $('#tukarshift_keperluan').val();

        $.ajax({
            url: "<?php echo $url_api_tukar_shift; ?>?action=submit_permintaan_tukarshift",
            type: "POST",
            data: {
                id_sender: id_sender,
                id_receiver: id_receiver,
                id_jadwalkerja_shift_1: id_jadwalkerja_shift_1,
                id_jadwalkerja_shift_2: id_jadwalkerja_shift_2,
                keperluan: keperluan
            },
            dataType: "JSON",
            success: function(response) {

                console.log(response);
                swal({
                    title: "Mengirim Permintaan Tukar Shift berhasil",
                    type: "success"
                }, function() {
                    window.location.reload();
                });    
            },
            error: function(errorMsg) {

                console.log("SUBMITTUKARSHIFT01. Gagal mengirim permintaan. "+errorMsg.status+"-"+errorMsg.statusText);
                swal("Gagal Mengirim Permintaan Tukar Shift", "Kode : SUBMITTUKARSHIFT01", "error");
            }
        });
    }

    function post_answer_validation_by_receiver(button_id, answer, id_tukarshift_validation) {

        $(button_id).html("<i class='fa fa-spinner fa-spin fa-1x fa-fw'></i>").prop('disabled', true);
        $.ajax({
            url: "<?php echo $url_api_tukar_shift; ?>?action=post_answer_validation_by_receiver",
            method: "POST",
            data: {
                id_tukarshift_validation: id_tukarshift_validation,
                answer: answer
            },
            dataType: "JSON",
            success: function(response) {

                console.log(response);
                swal({
                    title: "Mengirim Jawaban Permintaan Tukar Shift berhasil",
                    type: "success"
                }, function() {
                    window.location.reload();
                });  
            },
            error: function(error) {

                swal("Gagal Mengirim Jawaban Permintaan Tukar Shift", "", "error");
                console.log("Kode : POSTANSTKRSHIFT1. Gagal mengirim permintaan. "+error.status+"-"+error.statusText);
            }
        });
    }

    function delete_permintaan_tukarshift(id_tukarshift) {

        $('#tukar-shift_id').val(id_tukarshift);
        $('#tukar-shift_modal-hapus').modal('show');

    }
    
    // end fungsi untuk tukar-shift

</script>
