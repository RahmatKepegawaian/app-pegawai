<?php
$aksi = "module/form-sp/aksi-form-sp?";
$id = isset($url['id']) ? $url['id'] : null;
$dpa = isset($url['dpa']) ? $url['dpa'] : null;
switch ((isset($url['act']) ? $url['act'] : '')) {
    default :
        echo"default";
        header("location:error404");
        break;

    case "list-diklat-pegawai":
        ?>
        <div class="box">
            <div class="box-header with-border">  
                <h3 class="box-title fa fa-list">  LIST DATA DIKLAT PEGAWAI</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fa fa-minus"></i></button>
                </div>                
            </div>
            <div class="box-body">   
                <!-- /.box-header -->
                <div class="table-responsive">
                    <table id="laporan" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nanam Pegawai</th>
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
                            $sql_riwayat_diklat = bukaquery("SELECT tm_riwayat_diklat.id_riwayat_diklat, tm_riwayat_diklat.id_user, tm_riwayat_diklat.nama_pelatihan, tm_riwayat_diklat.instansi_pelatihan, tm_riwayat_diklat.lokasi,
                                    tm_riwayat_diklat.alamat_pelatihan, tm_riwayat_diklat.periode, tm_riwayat_diklat.total_jam, tm_riwayat_diklat.jenis_diklat, tm_riwayat_diklat.no_sertifikat, tm_riwayat_diklat.status_akreditasi, tm_riwayat_diklat.config, tm_pegawai.nama_pegawai 
                                    from tm_riwayat_diklat inner join tm_pegawai on tm_pegawai.id_user=tm_riwayat_diklat.id_user");
                            while ($row = fetch_array($sql_riwayat_diklat)) {
                                $no++;
                                ?>                               
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $row['nama_pegawai']; ?></td>
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
        </div>
        <?php
        break;

   
}
?>


