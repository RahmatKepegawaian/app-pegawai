<?php

$aksi = "module/slip-gaji/aksi-slip-gaji?";
$id = isset($url['id']) ? $url['id'] : null;

switch ((isset($url['act']) ? $url['act'] : '')) {
    default:
        echo "default";
        header('location:error.php');
        break;
    case "list-req-slip-gaji-keuangan";
    ?>
    <!-- Modal - Modal -->
    <!-- End Modal - Modal -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title fa fa-list">  LIST PERMINTAAN SLIP GAJI</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toogle="tooltip" title="Collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="box-body table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Pegawai</th>
                            <th>Slip Gaji</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                        $index = 1;
                        $arr_slipgajii_order = array();

                        // buat array dari tm_slipgaji_order
                        // untuk menjadi opsi pembaruan status request slip gaji
                        // oleh keuangan
                        $sql = bukaquery2("
                            SELECT
                                a.id_slipgaji_order, a.desc_status
                            FROM tm_slipgaji_order a
                            WHERE a.is_option_keuangan = '1'
                            ORDER BY a.order_keuangan
                        ");
                        while ($row = fetch_assoc($sql)) {
                            
                            array_push($arr_slipgajii_order, $row);
                        }
                        
                        // ambil request slip gaji
                        $sql = bukaquery2("
                            SELECT
                                a.id_slipgaji_request,
                                a.id_user AS id_pegawai, b.nama_pegawai AS nm_pegawai, c.nama_unit, b.no_hp_wa,
                                d.id_penilaian, MONTH(d.tanggal_penilaian) AS month, YEAR(d.tanggal_penilaian) AS year,
                                a.id_slipgaji_order, e.desc_status, e.hex_color_keuangan, e.is_final_option_keuangan, e.show_print_slipgaji_keuangan,
                                IFNULL(f.pph21, 0) AS pph21
                            FROM tt_slipgaji_req a
                                INNER JOIN tm_pegawai b ON a.id_user = b.id_user
                                INNER JOIN tm_unit c ON b.id_unit = c.id_unit
                                LEFT JOIN tm_penilaian d ON a.id_penilaian = d.id_penilaian
                                INNER JOIN tm_slipgaji_order e ON a.id_slipgaji_order = e.id_slipgaji_order
                                LEFT JOIN tt_pph21 f ON MONTH(d.tanggal_penilaian) = f.bulan	
                                    AND YEAR(d.tanggal_penilaian) = f.tahun
                                    AND a.id_user = f.id_user
                            WHERE a.id_keuangan = '".$id_user."'
                            ORDER BY a.created DESC
                        ");
                        while ($row = fetch_array($sql)) {

                            $btn_color = "background-color: #".$row['hex_color_keuangan'].";";
                            $url_print = "cetak-slip-gaji1-".$row['month']."-".$row['year']."-".$row['id_pegawai'];
                            ?>
                            <!-- Modal ubah -->
                            <div class="modal fade" id="slipgaji-modal-ubah-<?php echo $row['id_slipgaji_request']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="<?php echo $aksi.paramEncrypt('module=slip-gaji&act=update-status-keuangan&id='.$row['id_slipgaji_request']) ?>" method="POST" role="form">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <h4 class="modal-title" id="myModalLabel1">UBAH STATUS PERMINTAAN SLIP GAJI</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">

                                                    <input type="hidden" name="no_pengirim" value="<?php echo $row['no_hp_wa']; ?>">
                                                    <input type="hidden" name="id_penilaian" value="<?php echo $row['id_penilaian']; ?>">
                                                    <input type="hidden" name="id_pegawai" value="<?php echo $row['id_pegawai']; ?>">
                                                    <input type="hidden" name="id_keuangan" value="<?php echo $id_user; ?>">
                                                    <input type="hidden" name="month" value="<?php echo $row['month']; ?>">
                                                    <input type="hidden" name="year" value="<?php echo $row['year']; ?>">

                                                    <label>Status</label>
                                                    <select class="form-control select2" name="id_slipgaji_order_upd" style="width: 100%;">
                                                    <?php
                                                    for ($i = 0; $i < count($arr_slipgajii_order); $i++) {

                                                        $selected = $arr_slipgajii_order[$i]['id_slipgaji_order'] == $row['id_slipgaji_order']
                                                        ? 'selected'
                                                        : '';
                                                        
                                                        echo "<option value='".$arr_slipgajii_order[$i]['id_slipgaji_order']."' ".$selected.">".$arr_slipgajii_order[$i]['desc_status']."</option>";
                                                    }
                                                    ?>
                                                    </select>
                                                    <label>PPH21</label>
                                                    <input class="form-control" type="number" name="value_pph21" value="<?php echo $row['pph21']; ?>" required>
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
                            <!-- end Modal ubah -->

                            <tr>
                                <td><?php echo $index; ?></td>
                                <td><?php echo $row['nm_pegawai']." (".$row['nama_unit'].")"; ?></td>
                                <td><?php echo konversiBulan(sprintf('%02d', $row['month']))." - ".$row['year']; ?></td>
                                <td><button class="btn btn-sm" style="<?php echo $btn_color; ?>"><?php echo $row['desc_status']; ?></button></td>
                                <td>
                                    <?php
                                    if(!$row['is_final_option_keuangan']) {
                                        ?>
                                        <span data-toggle="modal" data-target="#slipgaji-modal-ubah-<?php echo $row['id_slipgaji_request']; ?>" title="Ubah" class="btn btn-warning">Ubah</span>
                                        <?php
                                    }
                                    if($row['show_print_slipgaji_keuangan']) {
                                        ?>
                                        <a href="<?php echo $url_print; ?>" target="_blank">
                                            <span class="btn btn-success">Print</span>
                                        </a>
                                        <?php
                                    }
                                    ?>                                    
                                </td>
                            </tr>
                            <?php
                            $index++;
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
<script>

    // fungsi untuk slip-gaji
    function slipgaji_get() {

        console.log('abc');
        $('#slipgaji-modal-ubah').modal('show');
    }
    // end fungsi untuk slip-gaji
</script>