<?= $this->extend('template/template') ?>

<?= $this->section('content') ?>
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="far fa-user"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Users</h4>
                        </div>
                        <div class="card-body">
                            <?= $count['cu'] ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="far fa-envelope"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Surat Masuk</h4>
                        </div>
                        <div class="card-body">
                            <?= $count['csm'] ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="far fa-envelope-open"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Surat Keluar</h4>
                        </div>
                        <div class="card-body">
                            <?= $count['csk'] ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="card">
        <?php if (session()->getFlashData('errors')) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="alert-body">
                    <?php foreach (session()->getFlashData('errors') as $error) : ?>
                        <li><?= $error ?></li>
                    <?php endforeach ?>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif ?>
        <?php if (session()->getFlashData('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <div class="alert-body">
                    <?= session()->getFlashData('success') ?>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif ?>
        <div class="card-header">
            <h4>Profil Sekolah</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td width="175">Nama Sekolah</td>
                        <td width="3">:</td>
                        <td><?= $data['nama_sekolah'] ?></td>
                    </tr>
                    <tr>
                        <td width="175">Alamat</td>
                        <td width="3">:</td>
                        <td><?= $data['alamat_sekolah'] ?></td>
                    </tr>
                    <tr>
                        <td width="175">Provinsi</td>
                        <td width="3">:</td>
                        <td><?= $data['provinsi'] ?></td>
                    </tr>
                    <tr>
                        <td width="175">Kecamatan</td>
                        <td width="3">:</td>
                        <td><?= $data['kecamatan'] ?></td>
                    </tr>
                    <tr>
                        <td width="175">No Telpon</td>
                        <td width="3">:</td>
                        <td><?= $data['telpon'] ?></td>
                    </tr>
                    <tr>
                        <td width="175">Kode Pos</td>
                        <td width="3">:</td>
                        <td><?= $data['kode_pos'] ?></td>
                    </tr>
                    <tr>
                        <?php if (session()->get('level') != 'kepsek') { ?>
                            <td colspan="3" width="175"><a href="#" class="btn btn-icon icon-left btn-primary" onclick="edit(<?= $data['id'] ?>)"><i class="far fa-edit"></i>Edit </a></td>
                        <?php } ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal hide fade" id="ajaxModal" tabindex="-1" role="dialog" aria-labelledby="ajaxModalLabel" aria-hidden="true" data-focus-on="input:first">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ajaxModalLabel">Update</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('admin/dashboard/updateProfilSekolah') ?>" id="form-updateprofilsekolah" method="POST">
                    <div class="form-group">
                        <label for="nama_sekolah">Nama Sekolah</label>
                        <input type="hidden" name="id" id="id">
                        <input type="text" class="form-control" id="nama_sekolah" name="nama_sekolah" autocomplete="off">
                        <span id="nama_sekolah_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat_sekolah" autocomplete="off">
                        <span id="alamat_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="provinsi">Provinsi</label>
                        <input type="text" class="form-control" id="provinsi" name="provinsi" autocomplete="off">
                        <span id="provinsi_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="kecamatan">Kecamatan</label>
                        <input type="text" class="form-control" id="kecamatan" name="kecamatan" autocomplete="off">
                        <span id="kecamatana_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="no_telp">No Telp</label>
                        <input type="text" class="form-control" id="no_telp" name="no_telp" autocomplete="off">
                        <span id="kecamatana_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="kode_pos">Kode Pos</label>
                        <input type="text" class="form-control" id="kode_pos" name="kode_pos" autocomplete="off">
                        <span id="kecamatana_error" class="text-danger"></span>
                    </div>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-arrow-circle-left "></i>&nbsp;Close</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-plus-circle"></i>&nbsp;Save Record</button>
            </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>

<script>
    function edit(id_surat) {
        $('#ajaxModal').modal('show');
        $('#id').val(id_surat);
        $.ajax({
            url: "<?php echo base_url('Admin/Dashboard/editProfilSekolah'); ?>",
            type: 'POST',
            dataType: 'JSON',
            data: $('#form-updateprofilsekolah').serialize(),
            success: function(res) {
                if (res.success == true) {
                    $('#nama_sekolah').val(res.data.nama_sekolah);
                    $('#alamat').val(res.data.alamat_sekolah);
                    $('#provinsi').val(res.data.provinsi);
                    $('#kecamatan').val(res.data.kecamatan);
                    $('#no_telp').val(res.data.telpon);
                    $('#kode_pos').val(res.data.kode_pos);
                }
            }
        });
    }

    // function update() {
    //     var form = $('#form-updateprofilsekolah')[0];
    //     var postData = new FormData(form);
    //     $.ajax({
    //         url: "<?= base_url('admin/dashboard/updateProfilSekolah') ?>",
    //         type: 'POST',
    //         dataType: 'JSON',
    //         processData: false,
    //         contentType: false,
    //         data: postData,
    //         success: function(res) {
    //             if (res.success == true) {
    //                 $('#message').removeClass('hide');
    //                 $('#message').html(
    //                     '<div class="alert alert-success alert-has-icon alert-dismissible">\n\
    //                         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>\n\
    //                         <div class="alert-icon"><i class="far fa-lightbulb"></i></div>\n\
    //                         <div class="alert-body">\n\
    //                             <div class="alert-title">Success</div>\n\
    //                             ' + res.message + '.\n\
    //                         </div>\n\
    //                     </div>');
    //                 $('#form-updateprofilsekolah').modal('hide');
    //             }
    //         }
    //     })
    // }
</script>

<?= $this->endSection() ?>