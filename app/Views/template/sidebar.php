<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="#">Stisla</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">St</a>
        </div>
        <ul class="sidebar-menu">
            <?php if (session()->get('level') == 'admin') : ?>
                <li class="menu-header">Dashboard</li>
                <li><a class="nav-link" href="/admin/dashboard"><i class="fas fa-fire"></i> <span>Dashboard</span></a></li>
            <?php endif; ?>
            <li class="menu-header">Master</li>
            <li><a class="nav-link" href="<?= $urlSuratMasuk ?>"><i class="fas fa-envelope"></i><span>Surat Masuk</span></a></li>
            <?php if (session()->get('level') == 'admin') : ?>
                <li><a class="nav-link" href="/admin/suratkeluar"><i class="fas fa-envelope-open"></i> <span>Surat Keluar</span></a></li>
                <li><a class="nav-link" href="/admin/users"><i class="fas fa-user"></i> <span>User</span></a></li>
            <?php endif; ?>
            <li><a class="nav-link" href="/profil"><i class="fas fa-user"></i> <span>Profile</span></a></li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-file-alt"></i><span>Laporan</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="/admin/laporansuratmasuk">Surat Masuk</a></li>
                    <li class="active"><a class="nav-link" href="/admin/laporansuratkeluar">Surat Keluar</a></li>
                </ul>
            </li>
            <li class="menu-header">Logout</li>
            <li><a href="<?= base_url('/login/logout') ?>" class="dropdown-item has-icon text-danger"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
    </aside>
</div>