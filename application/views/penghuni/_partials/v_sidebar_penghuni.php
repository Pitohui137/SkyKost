<!-- START SIDEBAR-->
        <nav class="page-sidebar" id="sidebar">
            <div id="sidebar-collapse">
                <div class="admin-block d-flex">
                    <?php
                    // Ambil foto penghuni untuk sidebar
                    $id_penghuni_sidebar = $this->session->userdata('id_penghuni');
                    $penghuni_sidebar = $this->db->get_where('penghuni', ['id' => $id_penghuni_sidebar])->row();
                    $foto_sidebar = $penghuni_sidebar && $penghuni_sidebar->foto && file_exists('./assets/uploads/foto_penghuni/'.$penghuni_sidebar->foto)
                        ? base_url('assets/uploads/foto_penghuni/'.$penghuni_sidebar->foto)
                        : base_url('assets/uploads/foto_penghuni/default-avatar.png');
                    ?>
                    <div>
                        <img src="<?= $foto_sidebar ?>" width="45px" style="height: 45px; object-fit: cover; border-radius: 50%;" />
                    </div>
                    <div class="admin-info">
                        <div class="font-strong"><?= $this->session->userdata('nama_penghuni') ?></div>
                        <small>Kamar <?= $this->session->userdata('no_kamar') ?></small>
                    </div>
                </div>
                <ul class="side-menu metismenu">
                    <li>
                        <a <?php if ($judul_halaman == 'Dashboard Penghuni') echo 'class="active"' ?> href="<?= base_url('penghuni/dashboard') ?>">
                            <i class="sidebar-item-icon fa fa-dashboard"></i>
                            <span class="nav-label">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a <?php if ($judul_halaman == 'Profil Saya') echo 'class="active"' ?> href="<?= base_url('penghuni/profil') ?>">
                            <i class="sidebar-item-icon fa fa-user"></i>
                            <span class="nav-label">Profil Saya</span>
                        </a>
                    </li>
                    <li>
                        <a <?php if ($judul_halaman == 'Riwayat Pembayaran') echo 'class="active"' ?> href="<?= base_url('penghuni/riwayat-pembayaran') ?>">
                            <i class="sidebar-item-icon fa fa-history"></i>
                            <span class="nav-label">Riwayat Pembayaran</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- END SIDEBAR-->
        <div class="content-wrapper">