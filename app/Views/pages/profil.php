<?= $this->extend('template/template') ?>

<?= $this->section('content') ?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Profil</h1>
        </div>
        <div class="section-body">
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
                <?php if (session()->getFlashData('wrongpassword')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="alert-body">
                            <?= session()->getFlashData('wrongpassword') ?>
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif ?>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Edit Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Change Password</a>
                        </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <form action="/updateprofil" method="POST">
                                <div class="form-group row">
                                    <label for="inputUsername" class="col-sm-3 col-form-label">Username</label>
                                    <div class="col-sm-9">
                                        <input type="hidden" name="id" id="id" value="<?= $data['id'] ?>">
                                        <input type="text" class="form-control" id="inputUsername" name="username" placeholder="Username" autocomplete="off" value="<?= $data['username'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputNama" class="col-sm-3 col-form-label">Nama</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="inputNama" name="nama" placeholder="Nama" autocomplete="off" value="<?= $data['fullname'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="level" class="col-sm-3 col-form-label">Level</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="level" placeholder="Level" value="<?= $data['level'] ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="jabatan" class="col-sm-3 col-form-label">Jabatan</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="jabatan" placeholder="Jabatan" value="<?= $data['jabatan'] ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label"></label>
                                    <div class="col-sm-9">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <form action="/changepassword" method="POST">
                                <div class="form-group row">
                                    <label for="currentpassword" class="col-sm-3 col-form-label">Password Sekarang</label>
                                    <div class="col-sm-9">
                                        <input type="hidden" name="id" id="id" value="<?= $data['id'] ?>">
                                        <input type="password" class="form-control" id="currentpassword" name="current_password" placeholder="Password Sekarang">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="newpassword" class="col-sm-3 col-form-label">Password Baru</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" id="newpassword" name="new_password" placeholder="Password Baru">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="repeatpassword" class="col-sm-3 col-form-label">Ulangi Password</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" id="repeatpassword" name="repeat_password" placeholder="Ulangi Password">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label"></label>
                                    <div class="col-sm-9">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<?= $this->endSection(); ?>