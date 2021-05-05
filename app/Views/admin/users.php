<?= $this->extend('template/template') ?>

<?= $this->section('content') ?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>List User</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div id="message">
                        </div>
                        <div class="card-header">
                            <button class="btn btn-primary btn-sm mr-1" style="border-radius: 4px;" onclick="add_record()"><i class="fa fa-plus"></i>&nbsp;Add User</button>
                            <button class="btn btn-success btn-sm mr-1" style="border-radius: 4px;" onclick="show_record()"><i class="fas fa-sync"></i>&nbsp;Refresh</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="mytable" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Username</th>
                                            <th>Nama</th>
                                            <th>Level</th>
                                            <th>Jabatan</th>
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
                <h5 class="modal-title" id="ajaxModalLabel">Add/Update User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-users" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="hidden" name="id" id="id">
                        <input type="text" class="form-control" id="username" name="username" autocomplete="off">
                        <span id="username_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" autocomplete="off">
                        <span id="nama_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="level">Level</label>
                        <select type="text" class="form-control selectric" id="level" name="level">
                            <option value="admin">Admin</option>
                            <option value="kepsek">Kepala Sekolah</option>
                            <option value="guru">Guru</option>
                        </select>
                        <span id="level_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="jabatan">Jabatan</label>
                        <input type="text" class="form-control" id="jabatan" name="jabatan" autocomplete="off">
                        <span id="jabatan_error" class="text-danger"></span>
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
                url: "<?= base_url('admin/users/ajax_list') ?>",
                type: "POST",
                data: {},
            },
            columnDefs: [{
                targets: [5],
                orderable: false,
            }],

        });

    }

    function clear_error_message() {
        $('#username_error').html('');
        $('#nama_error').html('');
        $('#level_error').html('');
        $('#jabatan_error').html('');
    }

    function add_record() {
        status = 'add';
        $('#ajaxModal').modal('show');
        $('#form-users')[0].reset();
        clear_error_message();
    }

    function edit(id) {
        status = 'edit';
        clear_error_message();
        $('#ajaxModal').modal('show');
        $('#id').val(id);
        $.ajax({
            url: "<?php echo base_url('admin/users/edit'); ?>",
            type: 'POST',
            dataType: 'JSON',
            data: $('#form-users').serialize(),
            success: function(res) {
                if (res.success == true) {
                    $('#username').val(res.data.username);
                    $('#nama').val(res.data.fullname);
                    $('#level').val(res.data.level);
                    $('#jabatan').val(res.data.jabatan);
                }
            }
        });
    }

    function proses() {
        if (status == 'add') {
            url = "<?php echo base_url('admin/users/store'); ?>";
        } else if (status == 'edit') {
            url = "<?php echo base_url('admin/users/update'); ?>";
        }
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'JSON',
            data: $('#form-users').serialize(),
            success: function(res) {
                //console.log(res);
                if (res.errors) {
                    if (res.errors.username) {
                        $('#username_error').html(res.errors.username);
                    }
                    if (res.errors.nama) {
                        $('#nama_error').html(res.errors.nama);
                    }
                    if (res.errors.jabatan) {
                        $('#jabatan_error').html(res.errors.jabatan);
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

    function hapus(id) {
        swal({
                title: 'Hapus user?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((confirm) => {
                if (confirm) {
                    $.ajax({
                        url: "<?php echo base_url('admin/users/hapus'); ?>" + '/' + id,
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