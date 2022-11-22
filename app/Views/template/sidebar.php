<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="#">SMAN 1 TANJAB BARAT</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">St</a>
        </div>
        <ul class="sidebar-menu">
            <?php if (session()->get('level') == 'admin') { ?>
                <li class="menu-header">Dashboard</li>
                <li><a class="nav-link" href="/admin/dashboard"><i class="fas fa-fire"></i> <span>Dashboard</span></a></li>
                <li class="menu-header">Master</li>
                <li><a class="nav-link" href="/admin/suratmasuk"><i class="fas fa-envelope"></i><span>Surat Masuk</span></a></li>
                <li><a class="nav-link" href="/admin/suratkeluar"><i class="fas fa-envelope-open"></i> <span>Surat Keluar</span></a></li>
                <li><a class="nav-link" href="/admin/users"><i class="fas fa-users"></i> <span>Data Users</span></a></li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-file-alt"></i><span>Laporan</span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="/admin/laporansuratmasuk">Surat Masuk</a></li>
                        <li class="active"><a class="nav-link" href="/admin/laporansuratkeluar">Surat Keluar</a></li>
                    </ul>
                </li>
                <li><a class="nav-link" href="/admin/profil"><i class="fas fa-user"></i> <span>Profile</span></a></li>
                <li class="menu-header">Logout</li>
                <li><a href="<?= base_url('/login/logout') ?>" class="dropdown-item has-icon text-danger"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
            <?php } else if (session()->get('level') == 'kepsek') { ?>
                <li class="menu-header">Dashboard</li>
                <li><a class="nav-link" href="/admin/dashboard"><i class="fas fa-fire"></i> <span>Dashboard</span></a></li>
                <li class="menu-header">Master</li>
                <li><a class="nav-link" href="/admin/suratmasuk"><i class="fas fa-envelope"></i><span>Surat Masuk</span></a></li>
                <li><a class="nav-link" href="/admin/suratkeluar"><i class="fas fa-envelope-open"></i> <span>Surat Keluar</span></a></li>
                <li><a class="nav-link" href="/admin/profil"><i class="fas fa-user"></i> <span>Profile</span></a></li>
                <li class="menu-header">Logout</li>
                <li><a href="<?= base_url('/login/logout') ?>" class="dropdown-item has-icon text-danger"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
            <?php } else {
            ?>
                <li><a class="nav-link" href="/guru"><i class="fas fa-home"></i><span>Home</span></a></li>
                <li><a class="nav-link" href="/guru/suratmasuk"><i class="fas fa-envelope"></i><span>Surat Masuk</span></a></li>
                <li><a class="nav-link" href="/guru/profil"><i class="fas fa-user"></i> <span>Profile</span></a></li>
                <li><a href="<?= base_url('/login/logout') ?>" class="dropdown-item has-icon text-danger"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
            <?php } ?>
    </aside>
</div>