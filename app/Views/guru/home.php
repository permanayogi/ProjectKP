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
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="myTable" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Tanggal Surat</th>
                                            <th>Nomor Surat</th>
                                            <th>Nomor Agenda</th>
                                            <th>Sifat Surat</th>
                                            <th>Pengirim</th>
                                            <th>Status</th>
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

<div class="modal hide fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="ajaxModalLabel" aria-hidden="true" data-focus-on="input:first">
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
                            <table class="table table-hover">
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
                                            <div id="img_preview" class="gallery">
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div id="status">
                            </div>
                            <div class="table-responsive">
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
                <h5 class="modal-title" id="ajaxModalLabel">Disposisi Record</h5>
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
<?= $this->endSection() ?>

<?= $this->section('script') ?>

<script>
    $(document).ready(function() {
        show_record();
    });

    function show_record() {
        $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            language: {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>'
            },
            bDestroy: true,
            responsive: true,
            order: [],
            ajax: {
                url: "<?= base_url('guru/listData') ?>",
                type: "POST",
                data: {},
            },
            columnDefs: [{
                targets: [0, 7],
                orderable: false,
            }],

        });

    }

    function detail(id_surat, id_disposisi) {
        var html;
        $('#viewModal').modal('show');
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
                    $('#v_perihal').text(res.data.perihal);
                    var html = '<a href="<?= base_url(); ?>/admin/suratmasuk/download/' + res.data.id_surat + '">' + res.data.file + '</a>'
                    // var html = '<div class="gallery-item" data-image="<?= base_url() ?>/uploads/img2.jpg' + '">' + '</div>';
                    // var html = '<img src="<?= base_url() ?>/uploads/img2.jpg"> </img>';
                    $('#img_preview').html(html);
                }
            }
        });
        $.ajax({
            url: "<?php echo base_url('guru/home/getDisposisi'); ?>" + '/' + id_surat,
            type: 'GET',
            cache: false,
            success: function(data) {
                $('#row').html(data);
            }
        });
        $.ajax({
            url: "<?php echo base_url('admin/disposisi/getStatus'); ?>" + '/' + id_disposisi,
            type: 'POST',
            dataType: 'JSON',
            success: function(res) {
                if (res.success == true) {
                    if (res.data.status == "Belum di proses") {
                        html = '<button class="btn btn-warning btn-sm mr-2 my-3" style="border-radius: 4px;" onclick="disposisi(' + id_surat + ')"><i class="far fa-paper-plane"></i>&nbsp;Disposisi</button>' +
                            '<button class="btn btn-danger btn-sm mb-3 my-3" style="border-radius: 4px;" onclick="confirm(' + res.data.id_disposisi + ')"><i class="far fa-check-square"></i>&nbsp;Done!</button>';
                    } else {
                        html = '<button class="btn btn-icon icon-left btn-success my-3"><i class="fas fa-check"></i>&nbsp;Done!</button>'
                    }
                    $('#status').html(html);
                }
            }
        });
    }

    function confirm(id_disposisi) {
        swal({
                title: 'Selesai disposisi?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((confirm) => {
                if (confirm) {
                    $.ajax({
                        url: "<?php echo base_url('admin/disposisi/updateStatus'); ?>" + '/' + id_disposisi,
                        type: 'POST',
                        dataType: 'JSON',
                        success: function(res) {
                            if (res.success == true) {
                                swal('Selesai disposisi!', {
                                    icon: 'success',
                                });
                                $('#viewModal').modal('hide');
                                show_record();
                                show_notification();
                                count_notification();
                            }
                        }
                    })
                }
            });
    }

    function disposisi(id_surat) {
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
                    $('#message').html('<div class="alert alert-success alert-dismissible">\n\
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>\n\
                        <h5><i class="icon fa fa-info-circle"></i> <b>Success!</b> ' + res.message + '</h5></div>');
                    $('#disposisiModal').modal('hide');
                    $('#viewModal').modal('hide');
                    show_record();
                }
            }
        });
    }
</script>

<?= $this->endSection(); ?>