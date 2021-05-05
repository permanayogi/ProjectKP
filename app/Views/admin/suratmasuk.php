<?= $this->extend('template/template') ?>

<?= $this->section('content') ?>
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Surat Masuk</h1>
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
                                            <th>Sifat Surat</th>
                                            <th>Pengirim</th>
                                            <th width="8%">Disposisi</th>
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
                <form id="form-suratmasuk" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="no_surat">Nomor Surat</label>
                            <input type="hidden" name="id_surat" id="id_surat">
                            <input type="text" class="form-control" id="no_surat" name="no_surat" autocomplete="off">
                            <span id="no_surat_error" class="text-danger"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="no_agenda">Nomor Agenda</label>
                            <input type="text" class="form-control" id="no_agenda" name="no_agenda" autocomplete="off">
                            <span id="no_agenda_error" class="text-danger"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tgl_surat">Tanggal Surat</label>
                            <input type="text" class="form-control datepicker" id="tgl_surat" name="tgl_surat" autocomplete="off">
                            <span id="tgl_surat_error" class="text-danger"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tgl_diterima">Tanggal Diterima</label>
                            <input type="text" class="form-control datepicker" id="tgl_diterima" name="tgl_diterima" autocomplete="off">
                            <span id="tgl_diterima_error" class="text-danger"></span>
                        </div>
                        <div class="form-group col-md-6 ">
                            <label for="sifat_surat">Sifat Surat</label>
                            <select type="text" class="form-control selectric" id="sifat_surat" name="sifat_surat">
                                <option value="Segera">Segera</option>
                                <option value="Penting">Penting</option>
                                <option value="Rahasia">Rahasia</option>
                                <option value="Biasa">Biasa</option>
                            </select>
                            <span id="sifat_surat_error" class="text-danger"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pengirim">Pengirim</label>
                            <input type="text" class="form-control" id="pengirim" name="pengirim" autocomplete="off">
                            <!-- <select class="form-control selectric" id="pengirim" name="pengirim">
                                <option>No Selected</option>
                            </select> -->
                            <span id="pengirim_error" class="text-danger"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="perihal">Perihal</label>
                            <input type="text" class="form-control" id="perihal" name="perihal" autocomplete="off">
                            <span id="perihal_error" class="text-danger"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="isi_ringkas">Isi Ringkas</label>
                            <input type="text" class="form-control" id="isi_ringkas" name="isi_ringkas" autocomplete="off">
                            <span id="isi_ringkas_error" class="text-danger"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="file">File *pdf</label>
                            <label id="oldFileLabel"></label>
                            <div class="custom-file">
                                <input type="hidden" class="custom-file-input" id="oldFile" name="oldFile">
                                <input type="file" class="custom-file-input" id="file" name="userFile" onchange="uploaded()">
                                <label class="custom-file-label" for="file"></label>
                            </div>
                            <span id="file_error" class="text-danger"></span>
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
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Detail</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Disposisi</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <h5>Detail Surat</h5>
                            <table class="table table-bordered table-hover">
                                <tbody>
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
                                        <td width="175">Tanggal Diterima</td>
                                        <td width="3">:</td>
                                        <td id="v_tgl_diterima"></td>
                                    </tr>
                                    <tr>
                                        <td width="175">Sifat Surat</td>
                                        <td width="3">:</td>
                                        <td id="v_sifat_surat"></td>
                                    </tr>
                                    <tr>
                                        <td width="175">Perihal</td>
                                        <td width="3">:</td>
                                        <td id="v_perihal"></td>
                                    </tr>
                                    <tr>
                                        <td width="175">Isi Ringkas</td>
                                        <td width="3">:</td>
                                        <td id="v_isi_ringkas"></td>
                                    </tr>
                                    <tr>
                                        <td width="175">File</td>
                                        <td width="3">:</td>
                                        <td id="v_file">
                                            <!-- <div id="img_preview" class="gallery">
                                            </div> -->
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div id="button">
                            </div>
                            <div class="table-responsive-sm">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Dari</th>
                                            <th>Instruksi</th>
                                            <th>Diteruskan Ke</th>
                                        </tr>
                                    </thead>
                                    <tbody id="row">

                                    <tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class=" modal fade" id="disposisiModal" tabindex="-1" role="dialog" aria-labelledby="ajaxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ajaxModalLabel">Add Disposisi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-disposisi">
                    <div class="form-group">
                        <label for="pengirim">Diteruskan Kepada</label>
                        <input type="hidden" name="id_surat" id="id">
                        <select class="form-control selectric" id="users" name="users">
                            <option>No Selected</option>
                        </select>
                        <span id="pengirim_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="sifat_surat">Keterangan</label>
                        <input type="text" class="form-control" id="isi" name="isi">
                        <span id="isi_error" class="text-danger"></span>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-arrow-circle-left "></i>&nbsp;Close</button>
                <button type="button" class="btn btn-primary" onclick="add_disposisi()"><i class="fa fa-plus-circle"></i>&nbsp;Save</button>
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
                url: "<?= base_url('admin/suratmasuk/ajax_list') ?>",
                type: "POST",
                data: {},
            },
            columnDefs: [{
                targets: [0, 6, 7],
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

    function detail(id_surat, get_disposisi) {
        $('#viewModal').modal('show');
        if (get_disposisi == 1) {
            $('a[href="#profile"]').tab('show');
        } else {
            $('a[href="#home"]').tab('show');
        };
        $.ajax({
            url: "<?php echo base_url('admin/suratmasuk/detail'); ?>" + '/' + id_surat,
            type: 'GET',
            dataType: 'JSON',
            success: function(res) {
                if (res.success == true) {
                    $('#v_no_surat').text(res.data.no_surat);
                    $('#v_no_agenda').text(res.data.no_agenda);
                    $('#v_tgl_surat').text(res.data.tanggal_surat);
                    $('#v_tgl_diterima').text(res.data.tanggal_diterima);
                    $('#v_pengirim').text(res.data.pengirim);
                    $('#v_sifat_surat').text(res.data.sifat_surat);
                    $('#v_isi_ringkas').text(res.data.isi_ringkas);
                    // if (res.data.disposisi == null) {
                    //     $('#v_disposisi').text("");
                    // } else {
                    //     $('#v_disposisi').text(res.data.disposisi);
                    // }
                    $('#v_perihal').text(res.data.perihal);
                    // var html = '<iframe src="<?= base_url(); ?>/uploads/' + res.data.file + '" width="600" height="400"></iframe>'
                    var html = '<a href="<?= base_url(); ?>/admin/suratmasuk/preview/' + res.data.id_surat + '" target="_blank">' + res.data.file + '</a>'
                    // var html = '<a onclick="preview()" href="javascript:void(0)" data-img="<?= base_url(); ?>/admin/suratmasuk/download/' + res.data.id_surat + '">' + res.data.file + '</a>'
                    // var html = '<div class="gallery-item" data-image="<?= base_url() ?>/uploads/img2.jpg' + '">' + '</div>';
                    // var html = '<img src="<?= base_url() ?>/uploads/img2.jpg"> </img>';
                    $('#v_file').html(html);
                }
            }
        });
        $.ajax({
            url: "<?php echo base_url('admin/disposisi/getDisposisi'); ?>" + '/' + id_surat,
            type: 'GET',
            cache: false,
            success: function(data) {
                $('#row').html(data);
                var html = '<button class="btn btn-warning btn-sm mr-2 my-3" style="border-radius: 4px;" onclick="disposisi(' + id_surat + ')"><i class="far fa-paper-plane"></i>&nbsp;Disposisi</button>'
                $('#button').html(html);
            }
        });
    }

    // function preview() {
    //     var file = $('#preview').data('img');
    //     var img = '<img src="' + file + '" >';
    //     $('#previewModal.modal-content').html(img);
    //     $('#previewModal').modal('show');
    // }

    function disposisi(id_surat) {
        clear_error_message();
        $('#disposisiModal').modal('show');
        $('#id').val(id_surat);
        $('#form-disposisi')[0].reset();
        $.ajax({
            url: "<?php echo base_url('admin/dashboard/getUsers'); ?>",
            type: 'POST',
            dataType: 'JSON',
            success: function(data) {
                var html = '';
                var i;
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i].id + '>' + data[i].fullname + ' - ' + data[i].jabatan + '</option>';
                }
                $('#users').html(html);
            }
        });
        return false;
    }

    function add_disposisi() {
        $.ajax({
            url: "<?php echo base_url('admin/disposisi/store'); ?>",
            type: 'POST',
            dataType: 'JSON',
            data: $('#form-disposisi').serialize(),
            success: function(res) {
                if (res.errors) {
                    if (res.errors.isi) {
                        $('#isi_error').html(res.errors.isi);
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
                    $('#disposisiModal').modal('hide');
                    $('#viewModal').modal('hide');
                    show_record();
                }
            }
        });
    }

    function clear_error_message() {
        $('#no_surat_error').html('');
        $('#tgl_surat_error').html('');
        $('#sifat_surat_error').html('');
        $('#perihal_error').html('');
        // $('#file_error').html('');
        $('#no_agenda_error').html('');
        $('#tgl_diterima_error').html('');
        $('#pengirim_error').html('');
        $('#isi_ringkas_error').html('');
        $('#file_error').html('');
    }

    function add_record() {
        status = 'add';
        $('#ajaxModal').modal('show');
        $('#oldFileLabel').html('')
        $('.custom-file-label').html('Choose file');
        $('#form-suratmasuk')[0].reset();
        clear_error_message();
    }

    function edit(id_surat) {
        status = 'edit';
        clear_error_message();
        $('#ajaxModal').modal('show');
        $('#id_surat').val(id_surat);
        $('#file').html('');
        $('.custom-file-label').html('Choose file');
        $.ajax({
            url: "<?php echo base_url('admin/suratmasuk/edit'); ?>",
            type: 'POST',
            dataType: 'JSON',
            data: $('#form-suratmasuk').serialize(),
            success: function(res) {
                if (res.success == true) {
                    $('#no_surat').val(res.data.no_surat);
                    $('#tgl_surat').val(res.data.tanggal_surat);
                    $('#sifat_surat').val(res.data.sifat_surat);
                    $('#perihal').val(res.data.perihal);
                    $('#no_agenda').val(res.data.no_agenda);
                    $('#tgl_diterima').val(res.data.tanggal_diterima);
                    $('#pengirim').val(res.data.pengirim);
                    $('#isi_ringkas').val(res.data.isi_ringkas);
                    $('#oldFile').val(res.data.file)
                    var html = '(<a href="<?= base_url(); ?>/admin/suratmasuk/download/' + res.data.id_surat + '">' + res.data.file + '</a>)'
                    $('#oldFileLabel').html(html);
                }
            }
        });
    }

    function proses() {
        if (status == 'add') {
            url = "<?php echo base_url('admin/suratmasuk/store'); ?>";
            var form = $('#form-suratmasuk')[0];
            var postData = new FormData(form);
        } else if (status == 'edit') {
            url = "<?php echo base_url('admin/suratmasuk/update'); ?>";
            var form = $('#form-suratmasuk')[0];
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
                if (res.errors) {
                    if (res.errors.no_surat) {
                        $('#no_surat_error').html(res.errors.no_agenda);
                    }
                    if (res.errors.tgl_surat) {
                        $('#tgl_surat_error').html(res.errors.tgl_surat);
                    }
                    if (res.errors.sifat_surat) {
                        $('#sifat_surat_error').html(res.errors.sifat_surat);
                    }
                    if (res.errors.perihal) {
                        $('#perihal_error').html(res.errors.perihal);
                    }
                    if (res.errors.no_agenda) {
                        $('#no_agenda_error').html(res.errors.no_agenda);
                    }
                    if (res.errors.tgl_diterima) {
                        $('#tgl_diterima_error').html(res.errors.tgl_diterima);
                    }
                    if (res.errors.pengirim) {
                        $('#pengirim_error').html(res.errors.pengirim);
                    }
                    if (res.errors.isi_ringkas) {
                        $('#isi_ringkas_error').html(res.errors.isi_ringkas);
                    }
                    if (res.errors.userFile) {
                        $('#file_error').html(res.errors.userFile);
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
                        url: "<?php echo base_url('admin/suratmasuk/hapus'); ?>" + '/' + id_surat,
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