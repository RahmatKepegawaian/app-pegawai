<?php
$URI = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;
$url = decode($URI);
$page = isset($url['module']) ? $url['module'] : null;
$action = isset($url['act']) ? $url['act'] : null;
$id = isset($url['id']) ? $url['id'] : null;


if ($page == 'auto-update' && $action == 'form-update-absen-dari-shift-ke-detail') {
    $list_nama_pegawais = bukaquery2("select id_user, nama_pegawai from tm_pegawai");
?>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title fa fa-list"> PILIH UNIT</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
                    <i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="form-group">
                <form method="post" action="?<?php echo paramEncrypt('module=auto-update&act=update-absen-dari-shift-ke-detail'); ?>">
                    <table>
                        <thead>
                            <tr>
                                <td>Unit</td>
                            </tr>
                        </thead>
                        <tbody>
                            <td>
                                <select class="form-control select2" id="tabel-validasi-pegawai-select-unit" name="id_user">
                                    <?php
                                    while ($list_nama_pegawai = mysqli_fetch_assoc($list_nama_pegawais)) {
                                        echo "<option value='$list_nama_pegawai[id_user]'>$list_nama_pegawai[nama_pegawai]</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <button class="btn btn-info" id="set-tabel-validasi-pegawai-select-unit" name="set-tabel-validasi-pegawai-select-unit" value="Cari" onclick="search_unit_tabelvalidasi();">
                                    <i class="fa fa-search"></i>
                                </button>
                            </td>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
<?php
} else if ($page == 'auto-update' && $action == 'update-absen-dari-shift-ke-detail') {
    prd($_POST);

    $autonumber = notahun('id_cuti', 'tm_cuti');
    $id_unit = getOne("select id_unit from tm_pegawai where id_user='$id'");
    $tgl_cuti = date('d-m-Y', strtotime(substr($_POST['periode_cuti'], 0, 10)));
    $tanggal_permohonan = FormatTgl('Y-m-d', $_POST['tgl_permohonan']);
    $tanggal_permohonan1 = FormatTgl('d-m-Y', $_POST['tgl_permohonan']);
}
