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
                                            <th>Penerima</th>
                                            <th>File</th>
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
                        <label for="no_surat">Nomor Surat</label>
                        <input type="hidden" name="id_surat" id="id_surat">
                        <input type="text" class="form-control" id="no_surat" name="no_surat" autocomplete="off">
                        <span id="no_surat_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="no_agenda">Nomor Agenda</label>
                        <input type="text" class="form-control" id="no_agenda" name="no_agenda" autocomplete="off">
                        <span id="no_agenda_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="tgl_surat">Tanggal Surat</label>
                        <input type="text" class="form-control datepicker" id="tgl_surat" name="tgl_surat" autocomplete="off">
                        <span id="tgl_surat_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="tgl_diterima">Penerima</label>
                        <input type="text" class="form-control" id="penerima" name="penerima" autocomplete="off">
                        <span id="penerima_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="file">File *pdf</label>
                        <label id="oldFileLabel"></label>
                        <div class="custom-file">
                            <input type="hidden" class="custom-file-input" id="oldFile" name="oldFile">
                            <input type="file" class="custom-file-input" id="file" name="userFile" onchange="uploaded()">
                            <label class="custom-file-label" for="file"></label>
                        </div>
                        <span id="file_error" class="text-danger"></span>
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
                url: "<?= base_url('admin/suratkeluar/ajax_list') ?>",
                type: "POST",
                data: {},
            },
            columnDefs: [{
                targets: [0, 4, 5, 6],
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
        $('#penerima_error').html('');
        $('#file_error').html('');
    }

    function add_record() {
        status = 'add';
        $('#ajaxModal').modal('show');
        $('#oldFileLabel').html('')
        $('#form-suratkeluar')[0].reset();
        $('.custom-file-label').html('Choose file');
        clear_error_message();
    }

    function edit(id_surat) {
        status = 'edit';
        clear_error_message();
        $('#ajaxModal').modal('show');
        $('#id_surat').val(id_surat);
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
                    $('#penerima').val(res.data.penerima);
                    $('#userFile').val(res.data.file);
                    $('#oldFile').val(res.data.file)
                    var html = '(<a href="<?= base_url(); ?>/admin/suratmasuk/download/' + res.data.id_surat + '">' + res.data.file + '</a>)'
                    $('#oldFileLabel').html(html);
                }
            }
        });
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
                    if (res.errors.no_surat) {
                        $('#no_surat_error').html(res.errors.no_surat);
                    }
                    if (res.errors.no_agenda) {
                        $('#no_agenda_error').html(res.errors.no_agenda);
                    }
                    if (res.errors.tgl_surat) {
                        $('#tgl_surat_error').html(res.errors.tgl_surat);
                    }
                    if (res.errors.penerima) {
                        $('#penerima_error').html(res.errors.penerima);
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