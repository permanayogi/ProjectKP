<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= $title ?></title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.7.2/css/all.min.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">


    <!-- CSS Libraries -->
    <link rel="stylesheet" href="<?= base_url() ?>/node_modules/bootstrap-daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="<?= base_url() ?>/node_modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/node_modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css">

    <!-- Template CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/components.css">
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar">
                <form class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
                    </ul>
                </form>

                <ul class="navbar-nav navbar-right">
                    <?php if (session()->get('level') == 'guru') : ?>
                        <li class="dropdown dropdown-list-toggle">
                            <a href="#" id="notif-icon" data-toggle="dropdown"><i class="far fa-bell"></i></a>
                            <div class="dropdown-menu dropdown-list dropdown-menu-right">
                                <div class="dropdown-header">Notifications
                                </div>
                                <div class="dropdown-list-content dropdown-list-icons">
                                    <div id="notification">
                                    </div>
                                </div>
                                <div class="dropdown-footer text-center">
                                    <a href="#">Close</a>
                                </div>
                            </div>
                        </li>
                    <?php endif; ?>
                    <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            <img alt="image" src="<?= base_url() ?>/assets/img/avatar/avatar-1.png" class="rounded-circle mr-1">
                            <div class="d-sm-none d-lg-inline-block"><?= session()->get('fullname') ?></div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <?php if (session()->get('level') == 'admin' || session()->get('level') == 'kepsek') { ?>
                                <a href="/admin/profil" class="dropdown-item has-icon">
                                    <i class="far fa-user"></i> Profile
                                </a>
                            <?php } else { ?>
                                <a href="/guru/profil" class="dropdown-item has-icon">
                                    <i class="far fa-user"></i> Profile
                                </a>
                            <?php } ?>
                            <div class="dropdown-divider"></div>
                            <a href="<?= base_url('/login/logout') ?>" class="dropdown-item has-icon text-danger">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            <?= $this->include('template/sidebar') ?>
            <?= $this->renderSection('content') ?>
            <footer class="main-footer">
                <div class="footer-left">
                    Copyright &copy; 2021 SMAN 1 Tanjab Barat. All rights reserved.
                </div>
                <div class="footer-right">
                    2.3.0
                </div>
            </footer>
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="<?= base_url() ?>/assets/js/stisla.js"></script>

    <!-- JS Libraies -->

    <script src="<?= base_url() ?>/node_modules/sweetalert/sweetalert.min.js"></script>
    <script src="<?= base_url() ?>/assets/js/page/modules-sweetalert.js"></script>
    <!-- Template JS File -->
    <script src="<?= base_url() ?>/assets/js/scripts.js"></script>
    <script src="<?= base_url() ?>/assets/js/custom.js"></script>
    <script src="<?= base_url() ?>/node_modules/datatables/DataTables-1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url() ?>/node_modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url() ?>/node_modules/datatables/Select-1.2.4/js/dataTables.select.min.js"></script>
    <script src="<?= base_url() ?>/node_modules/bootstrap-daterangepicker/daterangepicker.js"></script>

    <!-- Page Specific JS File -->
    <script src="<?= base_url() ?>/assets/js/page/modules-datatables.js"></script>
    <script>
        <?php if (session()->get('level') == 'guru') : ?>
            $(document).ready(function() {
                show_notification();
                count_notification();
            });

            function show_notification() {
                $.ajax({
                    url: "<?php echo base_url('/notification'); ?>",
                    type: 'POST',
                    cache: false,
                    success: function(data) {
                        $('#notification').html(data);
                    }
                });
            }

            function count_notification() {
                $.ajax({
                    url: "<?php echo base_url('/countNotification'); ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(res) {
                        if (res.success == true) {
                            if (res.count == 0) {
                                $('#notif-icon').removeClass().addClass('nav-link notification-toggle nav-link-lg');
                            } else if (res.count > 0) {
                                $('#notif-icon').removeClass().addClass('nav-link notification-toggle nav-link-lg beep');
                            }
                        }
                    }
                });
            }
        <?php endif; ?>
    </script>
    <?= $this->renderSection('script') ?>
</body>

</html>