<?= $this->extend('template/template') ?>

<?= $this->section('content') ?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Laporan Surat Keluar</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="tgl_dari" class="col-md-1 col-form-label">Tanggal</label>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control datepicker" id="start_date" name="start_date" placeholder="Dari" autocomplete="off" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <label for="tgl_sampai" class="col-form-label">-</label>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control datepicker" id="end_date" name="end_date" placeholder="Sampai" autocomplete="off" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-icon icon-left btn-success" onclick="proses()"><i class="fas fa-search"></i>Proses</button>
                                    <button type="button" class="btn btn-icon icon-left btn-danger" onclick="reset()"><i class="fas fa-undo"></i>Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card" id="report" style="display: none;">
                        <div class="card-header">
                            <form action="/cetaklaporansuratkeluar" method="POST" target="_blank">
                                <input type="hidden" name="startDate" id="startDate">
                                <input type="hidden" name="endDate" id="endDate">
                                <button type="submit" class="btn btn-info btn-sm mr-1" style="border-radius: 4px;"><i class="fas fa-print"></i>&nbsp;Print</button>
                            </form>
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
                                            <th>Kepada</th>
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

<?= $this->endSection(); ?>

<?= $this->section('script') ?>

<script>
    $(document).ready(function() {
        $('#start_date').val('');
        $('#end_date').val('');
    });

    function reset() {
        $('#start_date').val('');
        $('#end_date').val('');
        $('#report').hide();
    }

    function proses() {
        var startDate = document.getElementById("start_date").value;
        var endDate = document.getElementById("end_date").value;
        $('#startDate').val(startDate);
        $('#endDate').val(endDate);
        if (startDate == "" || endDate == "") {
            alert("Jangan ada input yang kosong");
            return false;
        } else {
            $('#report').show();
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
                    url: "<?= base_url('admin/laporansuratkeluar/getTabelLaporan') ?>",
                    type: "POST",
                    data: {
                        'startDate': startDate,
                        'endDate': endDate
                    },
                },
                columnDefs: [{
                    targets: [4],
                    orderable: false,
                }],
            });
        }
    }
</script>

<?= $this->endSection(); ?>