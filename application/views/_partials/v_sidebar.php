        <!-- START SIDEBAR-->
        <nav class="page-sidebar" id="sidebar">
            <div id="sidebar-collapse">
                <div class="admin-block d-flex">
                    <div>
                        <img src="<?= base_url('assets/img/admin-avatar.png') ?>" width="45px" />
                    </div>
                    <div class="admin-info">
                        <div class="font-strong"><?= $this->session->userdata('nama') ?></div><small><?= $this->session->userdata('username') ?></small></div>
                </div>
                <ul class="side-menu metismenu">
                    <li>
                        <a <?php if ($judul_halaman == 'Dasbor') echo 'class="active"' ?> href="<?= base_url('dasbor') ?>"><i class="sidebar-item-icon fa fa-dashboard"></i>
                            <span class="nav-label">Dashboard</span>
                        </a>
                    </li>
                    <li class="heading">KAMAR</li>
                    <li>
                        <a <?php if ($judul_halaman == 'Daftar Kamar') echo 'class="active"' ?> href="<?= base_url('daftar-kamar') ?>"><i class="sidebar-item-icon fa fa-th-list"></i>
                            <span class="nav-label">Daftar Kamar</span>
                        </a>
                    </li>
                    <li class="heading">PENGHUNI</li>
                    <li>
                        <a <?php if ($judul_halaman == 'Daftar Penghuni') echo 'class="active"' ?> href="<?= base_url('daftar-penghuni') ?>"><i class="sidebar-item-icon fa fa-user-circle"></i>
                            <span class="nav-label">Daftar Penghuni</span>
                        </a>
                    </li>
                   <li class="heading">KEUANGAN</li>
                    <li>
                        <a <?php if ($judul_halaman == 'Konfirmasi Pembayaran') echo 'class="active"' ?> href="<?= base_url('konfirmasi-pembayaran') ?>">
                            <i class="sidebar-item-icon fa fa-check-circle"></i>
                            <span class="nav-label">Konfirmasi Pembayaran</span>
                        </a>
                    </li>
                    <li>
                        <a <?php if ($judul_halaman == 'Riwayat Pembayaran') echo 'class="active"' ?> href="<?= base_url('riwayat-pembayaran') ?>">
                            <i class="sidebar-item-icon fa fa-history"></i>
                            <span class="nav-label">Riwayat Pembayaran</span>
                        </a>
                    </li>
                    <li>
                        <a <?php if ($judul_halaman == 'Update Tagihan') echo 'class="active"' ?> href="<?= base_url('c_tagihan/update_tagihan_bulanan/skykost') ?>">
                        <i class="sidebar-item-icon fa fa-refresh"></i>
                        <span class="nav-label">Update Tagihan</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- END SIDEBAR-->
        <div class="content-wrapper">
