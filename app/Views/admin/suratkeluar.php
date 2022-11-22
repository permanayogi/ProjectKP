<?= $this->extend('template/template'); ?>

<?= $this->section('content') ?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Surat Keluar</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div id="message">
                        </div>
                        <div class="card-header">
                            <button class="btn btn-primary btn-sm mr-1" style="border-radius: 4px;" onclick="add_record()"><i class="fa fa-plus"></i>&nbsp;Add Record</button>
                            <button class="btn btn-success btn-sm mr-1" style="border-radius: 4px;" onclick="show_record()"><i class="fas fa-sync"></i>&nbsp;Refresh</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="mytable" class="table table-bordered table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Tanggal Surat</th>
                                            <th>Nomor Surat</th>
                                            <th>Nomor Agenda</th>
                                            <th>Tipe</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal hide fade" id="ajaxModal" tabindex="-1" role="dialog" aria-labelledby="ajaxModalLabel" aria-hidden="true" data-focus-on="input:first">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ajaxModalLabel">Add/Update Record</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-suratkeluar" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="no_surat">Nomor Surat*</label>
                        <input type="hidden" name="id_surat" id="id_surat">
                        <input type="text" class="form-control" id="no_surat" name="no_surat" autocomplete="off">
                        <span id="no_surat_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="no_agenda">Nomor Agenda*</label>
                        <input type="text" class="form-control" id="no_agenda" name="no_agenda" autocomplete="off">
                        <span id="no_agenda_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="tgl_surat">Tanggal Surat*</label>
                        <input type="text" class="form-control datepicker" id="tgl_surat" name="tgl_surat" autocomplete="off">
                        <span id="tgl_surat_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="tipe_surat">Tipe Surat</label>
                        <select class="form-control selectric" name="tipeSurat" id="tipeSurat" onchange="getTipeSurat(this)">
                            <option value="">-- Pilih --</option>
                            <option value="Surat Undangan">Surat Undangan</option>
                            <option value="Surat Keterangan Siswa">Surat Keterangan Siswa</option>
                            <option value="Upload">Upload File</option>
                        </select>
                        <span id="tipe_surat_error" class="text-danger"></span>
                    </div>
                    <div class="form-group formKepada" style="display: none;">
                        <label for="kepada">Kepada*</label>
                        <input type="text" class="form-control inputKepada" id="kepada" name="kepada" autocomplete="off" required>
                        <span id="kepada_error" class="text-danger"></span>
                    </div>
                    <div class="uploadFile" style="display: none;">
                        <div class="form-group">
                            <label for="perihal">Perihal*</label>
                            <input type="text" class="form-control inputUpload" id="perihal" name="perihal" autocomplete="off">
                            <span id="perihal_error" class="text-danger"></span>
                        </div>
                        <div class="form-group uploadFile">
                            <label for="file">File *pdf,jpeg,jpg,png (maks 2 MB)</label>
                            <label id="oldFileLabel"></label>
                            <div class="custom-file">
                                <input type="hidden" class="custom-file-input" id="oldFile" name="oldFile">
                                <input type="file" class="custom-file-input inputUpload" id="file" name="userFile" onchange="uploaded()">
                                <label class="custom-file-label" for="file"></label>
                            </div>
                            <span id="file_error" class="text-danger"></span>
                        </div>
                    </div>
                    <div class="suratUndangan" style="display: none;">
                        <div class="form-group">
                            <label for="hari">Hari*</label>
                            <input type="text" class="form-control inputUndangan" id="hari" name="hari" autocomplete="off">
                            <span id="hari_error" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="jam">Jam*</label>
                            <input type="text" class="form-control inputUndangan" id="jam" name="jam" autocomplete="off">
                            <span id="jam_error" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="tempat">Tempat*</label>
                            <input type="text" class="form-control inputUndangan" id="tempat" name="tempat" autocomplete="off">
                            <span id="tempat_error" class="text-danger"></span>
                        </div>
                    </div>
                    <div class="keteranganAktif" style="display: none;">
                        <div class="form-group">
                            <label for="nama_siswa">Nama Siswa*</label>
                            <input type="text" class="form-control inputKeterangan" id="nama_siswa" name="nama_siswa" autocomplete="off">
                            <span id="nama_siswa_error" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="nisn">NIS/NISN*</label>
                            <input type="text" class="form-control inputKeterangan" id="nisn" name="nisn" autocomplete="off">
                            <span id="nisn_error" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="ttl">Tempat, Tanggal Lahir*</label>
                            <input type="text" class="form-control inputKeterangan" id="ttl" name="ttl" autocomplete="off">
                            <span id="ttl_error" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="kelas">Kelas*</label>
                            <input type="text" class="form-control inputKeterangan" id="kelas" name="kelas" autocomplete="off">
                            <span id="kelas_error" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="orang_tua">Orang Tua*</label>
                            <input type="text" class="form-control inputKeterangan" id="orang_tua" name="orang_tua" autocomplete="off">
                            <span id="orang_tua_error" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat*</label>
                            <input type="text" class="form-control inputKeterangan" id="alamat" name="alamat" autocomplete="off">
                            <span id="alamat_error" class="text-danger"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-arrow-circle-left "></i>&nbsp;Close</button>
                <button type="button" class="btn btn-primary" onclick="proses()"><i class="fa fa-plus-circle"></i>&nbsp;Save Record</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="ajaxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div>
                    <h5>Detail Surat</h5>
                    <table class="table table-bordered table-hover">
                        <tr>
                            <td width="175">Nomor Surat</td>
                            <td width="3">:</td>
                            <td id="v_no_surat"></td>
                        </tr>
                        <tr>
                            <td width="175">Nomor Agenda</td>
                            <td width="3">:</td>
                            <td id="v_no_agenda"></td>
                        </tr>
                        <tr>
                            <td width="175">Tanggal Surat</td>
                            <td width="3">:</td>
                            <td id="v_tgl_surat"></td>
                        </tr>
                        <tr>
                            <td width="175">Tipe</td>
                            <td width="3">:</td>
                            <td id="v_tipe_surat"></td>
                        </tr>
                        <tr id="detail_kepada">

                        </tr>
                        <tr id="detail_file">

                        </tr>
                        <tr id="detail_nama_siswa">

                        </tr>
                        <tr id="detail_nisn">

                        </tr>
                        <tr id="detail_ttl">

                        </tr>
                        <tr id="detail_kelas">

                        </tr>
                        <tr id="detail_orang_tua">

                        </tr>
                        <tr id="detail_alamat">

                        </tr>
                        <tr id="detail_hari">
                        </tr>
                        <tr id="detail_jam">

                        </tr>
                        <tr id="detail_tempat">

                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('script') ?>

<script>
    let url;
    let status = 'add';
    $(document).ready(function() {
        show_record();
    });

    function getTipeSurat(selectedObject) {
        var selected = selectedObject.value;
        if (selected == 'Upload') {
            uploadFileInput();
            disableKeterangan();
            disableUndangan();
        } else if (selected == 'Surat Keterangan Siswa') {
            suratKeteranganSiswa();
            disableUploadFile();
            disableUndangan();
        } else if (selected == 'Surat Undangan') {
            suratUndangan();
            disableUploadFile();
            disableKeterangan();
        }

    }

    function uploadFileInput() {
        $('.uploadFile').css("display", "block");
        $('.inputUpload').attr('disabled', false);
        $('.formKepada').css("display", "block");
        $('.inputKepada').attr('disabled', false);
    }

    function disableUploadFile() {
        $('.uploadFile').css("display", "none");
        $('.inputUpload').attr('disabled', true);
    }

    function suratKeteranganSiswa() {
        $('.keteranganAktif').css("display", "block");
        $('.inputKeterangan').attr('disabled', false);
        $('.formKepada').css("display", "none");
        $('.inputKepada').attr('disabled', false);
    }

    function disableKeterangan() {
        $('.keteranganAktif').css("display", "none");
        $('.inputKeterangan').attr('disabled', true);
    }

    function suratUndangan() {
        $('.suratUndangan').css("display", "block");
        $('.inputUndangan').attr('disabled', false);
        $('.formKepada').css("display", "block");
        $('.inputKepada').attr('disabled', false);
    }

    function disableUndangan() {
        $('.suratUndangan').css("display", "none");
        $('.inputUndangan').attr('disabled', true);
    }


    function show_record() {
        $('#mytable').DataTable({
            processing: true,
            serverSide: true,
            language: {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>'
            },
            bDestroy: true,
            responsive: true,
            order: [],
            ajax: {
                url: "<?= base_url('admin/suratkeluar/ajax_list') ?>",
                type: "POST",
                data: {},
            },
            columnDefs: [{
                targets: [0, 4],
                orderable: false,
            }],
        });

    }
    //get name uploaded file to upload label
    function uploaded() {
        const userFile = document.querySelector('#file');
        const fileLabel = document.querySelector('.custom-file-label');
        fileLabel.innerHTML = userFile.files[0].name;
    }

    function clear_error_message() {
        $('#no_surat_error').html('');
        $('#no_agenda_error').html('');
        $('#tgl_surat_error').html('');
        $('#kepada_error').html('');
        $('#perihal_error').html('');
        $('#file_error').html('');
        $('#tipe_surat_error').html('');
    }

    function disableAllType() {
        disableKeterangan();
        disableUndangan();
        disableUploadFile();
        $('.formKepada').css("display", "none");
        $('.inputKepada').attr('disabled', false);
    }

    function add_record() {
        status = 'add';
        disableAllType();
        $('#ajaxModal').modal('show');
        $('#oldFileLabel').html('')
        $('#form-suratkeluar')[0].reset();
        $('.custom-file-label').html('Choose file');
        clear_error_message();
        $.ajax({
            url: "<?php echo base_url('admin/suratkeluar/generateNoSurat'); ?>",
            type: 'POST',
            dataType: 'JSON',
            data: $('#form-suratkeluar').serialize(),
            success: function(res) {
                $('#no_surat').val(res.data);
                $('#no_agenda').val(res.no_agenda);
            }
        })
    }

    function edit(id_surat, tipe) {
        status = 'edit';
        clear_error_message();
        $('#ajaxModal').modal('show');
        $('#id_surat').val(id_surat);
        $('#file').val('');
        $('.custom-file-label').html('Choose file');
        $.ajax({
            url: "<?php echo base_url('admin/suratkeluar/edit'); ?>",
            type: 'POST',
            dataType: 'JSON',
            data: $('#form-suratkeluar').serialize(),
            success: function(res) {
                if (res.success == true) {
                    $('#no_surat').val(res.data.no_surat);
                    $('#no_agenda').val(res.data.no_agenda);
                    $('#tgl_surat').val(res.data.tanggal_surat);
                    $('#kepada').val(res.data.kepada);
                    $('#perihal').val(res.data.perihal);
                    $('#userFile').val(res.data.file);
                    $('#tipeSurat').val(res.data.tipe);
                    $('#oldFile').val(res.data.file);
                    $('#nama_siswa').val(res.tipe.nama_siswa);
                    $('#nisn').val(res.tipe.nisn);
                    $('#ttl').val(res.tipe.ttl);
                    $('#kelas').val(res.tipe.kelas);
                    $('#orang_tua').val(res.tipe.orang_tua);
                    $('#alamat').val(res.tipe.alamat);
                    $('#hari').val(res.tipe.hari);
                    $('#jam').val(res.tipe.jam);
                    $('#tempat').val(res.tipe.tempat);
                    var html = '(<a href="<?= base_url(); ?>/admin/suratkeluar/preview/' + res.data.id_surat + '" target="_blank">' + res.data.file + '</a>)'
                    $('#oldFileLabel').html(html);
                    if (tipe == 'Surat Keterangan Siswa') {
                        suratKeteranganSiswa();
                        disableUploadFile();
                        disableUndangan();
                    } else if (tipe == 'Surat Undangan') {
                        suratUndangan();
                        disableUploadFile();
                        disableKeterangan();
                    } else if (tipe == 'Upload') {
                        uploadFileInput();
                        disableKeterangan();
                        disableUndangan();
                    }
                }
            }
        });
    }

    function detail(id_surat) {
        $('#viewModal').modal('show');
        $.ajax({
            url: "<?php echo base_url('admin/suratkeluar/detail'); ?>" + '/' + id_surat,
            type: 'GET',
            dataType: 'JSON',
            success: function(res) {
                if (res.success == true) {
                    $('#v_no_surat').text(res.data.no_surat);
                    $('#v_no_agenda').text(res.data.no_agenda);
                    $('#v_tgl_surat').text(res.data.tanggal_surat);
                    $('#v_tipe_surat').text(res.data.tipe);
                    if (res.data.tipe == 'Upload') {
                        var kpd = '<td width="175">Kepada</td><td width="3">:</td><td>' + res.data.kepada + '</td>';
                        var prhl = '<td width="175">Perihal</td><td width="3">:</td><td>' + res.data.perihal + '</td>';
                        var file = '<td width="175">File</td><td width="3">:</td><td>' + '<a href="<?= base_url(); ?>/admin/suratkeluar/preview/' + res.data.id_surat + '" target="_blank">' + res.data.file + '</a>' + '</td>';
                        $('#detail_kepada').html(kpd);
                        $('#detail_perihal').html(prhl);
                        $('#detail_file').html(file);
                        clearDetailSuratKeterangan();
                        clearDetailSuratUndangan();
                    } else if (res.data.tipe == 'Surat Keterangan Siswa') {
                        var nama_siswa = '<td width="175">Nama Siswa</td><td width="3">:</td><td>' + res.dataSuratKeterangan.nama_siswa + '</td>';
                        var nisn = '<td width="175">NIS/NISN</td><td width="3">:</td><td>' + res.dataSuratKeterangan.nisn + '</td>';
                        var ttl = '<td width="175">Tempat, Tanggal Lahir</td><td width="3">:</td><td>' + res.dataSuratKeterangan.ttl + '</td>';
                        var kelas = '<td width="175">Kelas</td><td width="3">:</td><td>' + res.dataSuratKeterangan.kelas + '</td>';
                        var orang_tua = '<td width="175">Orang Tua</td><td width="3">:</td><td>' + res.dataSuratKeterangan.orang_tua + '</td>';
                        var alamat = '<td width="175">Alamat</td><td width="3">:</td><td>' + res.dataSuratKeterangan.alamat + '</td>';
                        $('#detail_nama_siswa').html(nama_siswa);
                        $('#detail_nisn').html(nisn);
                        $('#detail_ttl').html(ttl);
                        $('#detail_kelas').html(kelas);
                        $('#detail_orang_tua').html(orang_tua);
                        $('#detail_alamat').html(alamat);
                        clearDetailUpload();
                        clearDetailSuratUndangan();
                    } else {
                        var hari = '<td width="175">Hari</td><td width="3">:</td><td>' + res.dataSuratUndangan.hari + '</td>';
                        var jam = '<td width="175">Jam</td><td width="3">:</td><td>' + res.dataSuratUndangan.jam + '</td>';
                        var tempat = '<td width="175">Tempat</td><td width="3">:</td><td>' + res.dataSuratUndangan.tempat + '</td>';
                        $('#detail_hari').html(hari);
                        $('#detail_jam').html(jam);
                        $('#detail_tempat').html(tempat);
                        clearDetailUpload();
                        clearDetailSuratKeterangan();
                    }
                    // $('#v_kepada').text(res.data.kepada);
                    // $('#v_perihal').text(res.data.perihal);
                    // var html = '<a href="<?= base_url(); ?>/admin/suratkeluar/preview/' + res.data.id_surat + '" target="_blank">' + res.data.file + '</a>';
                    // $('#v_file').html(html);
                }
            }
        });
    }

    function clearDetailUpload() {
        $('#detail_kepada').html('');
        $('#detail_perihal').html('');
        $('#detail_file').html('');
    }

    function clearDetailSuratKeterangan() {
        $('#detail_nama_siswa').html('');
        $('#detail_nisn').html('');
        $('#detail_ttl').html('');
        $('#detail_kelas').html('');
        $('#detail_orang_tua').html('');
        $('#detail_alamat').html('');
    }

    function clearDetailSuratUndangan() {
        $('#detail_hari').html('');
        $('#detail_jam').html('');
        $('#detail_tempat').html('');
    }

    function proses() {
        if (status == 'add') {
            url = "<?php echo base_url('admin/suratkeluar/store'); ?>";
            var form = $('#form-suratkeluar')[0];
            var postData = new FormData(form);
        } else if (status == 'edit') {
            url = "<?php echo base_url('admin/suratkeluar/update'); ?>";
            var form = $('#form-suratkeluar')[0];
            var postData = new FormData(form);
        }
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'JSON',
            processData: false,
            contentType: false,
            data: postData,
            success: function(res) {
                //console.log(res);
                if (res.errors) {
                    if (res.errors.tipeSurat) {
                        $('#tipe_surat_error').html(res.errors.tipeSurat);
                    }
                    if (res.errors.no_surat) {
                        $('#no_surat_error').html(res.errors.no_surat);
                    }
                    if (res.errors.no_agenda) {
                        $('#no_agenda_error').html(res.errors.no_agenda);
                    }
                    if (res.errors.tgl_surat) {
                        $('#tgl_surat_error').html(res.errors.tgl_surat);
                    }
                    if (res.errors.kepada) {
                        $('#kepada_error').html(res.errors.kepada);
                    }
                    if (res.errors.perihal) {
                        $('#perihal_error').html(res.errors.perihal);
                    }
                    if (res.errors.userFile) {
                        $('#file_error').html(res.errors.userFile);
                    }
                    if (res.errors.nama_siswa) {
                        $('#nama_siswa_error').html(res.errors.nama_siswa);
                    }
                    if (res.errors.nisn) {
                        $('#nisn_error').html(res.errors.nisn);
                    }
                    if (res.errors.ttl) {
                        $('#ttl_error').html(res.errors.ttl);
                    }
                    if (res.errors.kelas) {
                        $('#kelas_error').html(res.errors.kelas);
                    }
                    if (res.errors.orang_tua) {
                        $('#orang_tua_error').html(res.errors.orang_tua);
                    }
                    if (res.errors.alamat) {
                        $('#alamat_error').html(res.errors.alamat);
                    }
                    if (res.errors.hari) {
                        $('#hari_error').html(res.errors.hari);
                    }
                    if (res.errors.jam) {
                        $('#jam_error').html(res.errors.jam);
                    }
                    if (res.errors.tempat) {
                        $('#tempat_error').html(res.errors.tempat);
                    }
                }
                if (res.success == true) {
                    $('#message').removeClass('hide');
                    $('#message').html(
                        '<div class="alert alert-success alert-has-icon alert-dismissible">\n\
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>\n\
                            <div class="alert-icon"><i class="far fa-lightbulb"></i></div>\n\
                            <div class="alert-body">\n\
                                <div class="alert-title">Success</div>\n\
                                ' + res.message + '.\n\
                            </div>\n\
                        </div>');
                    if (status == 'add') {
                        $('#ajaxModal').modal('hide');
                    } else if (status == 'edit') {
                        $('#ajaxModal').modal('hide');
                    }
                    show_record();
                }
            }
        });
    }

    function hapus(id_surat) {
        swal({
                title: 'Hapus data?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((confirm) => {
                if (confirm) {
                    $.ajax({
                        url: "<?php echo base_url('admin/suratkeluar/hapus'); ?>" + '/' + id_surat,
                        type: 'POST',
                        dataType: 'JSON',
                        success: function(res) {
                            if (res.success == true) {
                                swal('Data berhasil dihapus!', {
                                    icon: 'success',
                                });
                                show_record();
                            }
                        }
                    })
                }
            });
    }
</script>

<?= $this->endSection(); ?>