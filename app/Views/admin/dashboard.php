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
            <td><?= $data['alamat'] ?></td>
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
            <td colspan="3" width="175"><a href="#" class="btn btn-icon icon-left btn-primary" onclick="edit(<?= $data['id'] ?>)"><i class="far fa-edit"></i>Edit </a></td>
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
        <form id="form-updateprofilsekolah" method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <label for="nama_sekolah">Nama Sekolah</label>
            <input type="hidden" name="id" id="id">
            <input type="text" class="form-control" id="nama_sekolah" name="nama_sekolah" autocomplete="off">
            <span id="nama_sekolah_error" class="text-danger"></span>
          </div>
          <div class="form-group">
            <label for="alamat">Alamat</label>
            <input type="text" class="form-control" id="alamat" name="alamat" autocomplete="off">
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
        </form>
      </div>
      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-arrow-circle-left "></i>&nbsp;Close</button>
        <button type="button" class="btn btn-primary" onclick="proses()"><i class="fa fa-plus-circle"></i>&nbsp;Save Record</button>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>

<script>
  function edit(id_surat) {
    $('#ajaxModal').modal('show');
    $('#id_surat').val(id_surat);
    $.ajax({
      url: "<?php echo base_url('Admin/Dashboard/editProfilSekolah'); ?>",
      type: 'POST',
      dataType: 'JSON',
      data: $('#form-updateprofilsekolah').serialize(),
      success: function(res) {
        if (res.success == true) {
          $('#nama_sekolah').val(res.data.nama_sekolah);
          $('#alamat').val(res.data.alamat);
          $('#provinsi').val(res.data.provinsi);
          $('#kecamatan').val(res.data.kecamatan);
        }
      }
    });
  }
</script>

<?= $this->endSection() ?>