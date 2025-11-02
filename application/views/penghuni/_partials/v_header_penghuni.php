<body class="fixed-navbar">
    <div class="page-wrapper">
        <!-- START HEADER-->
        <header class="header">
            <div class="page-brand">
                <a class="link" href="<?= base_url('penghuni/dashboard') ?>">
                    <span class="brand">Sky Kost Penghuni
                        <span class="brand-tip"></span>
                    </span>
                    <span class="brand-mini">SKP</span>
                </a>
            </div>
            <div class="flexbox flex-1">
                <!-- START TOP-LEFT TOOLBAR-->
                <ul class="nav navbar-toolbar">
                    <li>
                        <a class="nav-link sidebar-toggler js-sidebar-toggler"><i class="ti-menu"></i></a>
                    </li>
                </ul>
                <!-- END TOP-LEFT TOOLBAR-->
                <!-- START TOP-RIGHT TOOLBAR-->
                <ul class="nav navbar-toolbar">
                    <li class="dropdown dropdown-user">
                        <a class="nav-link dropdown-toggle link" data-toggle="dropdown">
                            <?php
                            // Ambil foto penghuni dari database
                            $id_penghuni = $this->session->userdata('id_penghuni');
                            $penghuni_header = $this->db->get_where('penghuni', ['id' => $id_penghuni])->row();
                            $foto_header = $penghuni_header && $penghuni_header->foto && file_exists('./assets/uploads/foto_penghuni/'.$penghuni_header->foto)
                                ? base_url('assets/uploads/foto_penghuni/'.$penghuni_header->foto)
                                : base_url('assets/uploads/foto_penghuni/default-avatar.png');
                            ?>
                            <img src="<?= $foto_header ?>" style="width: 35px; height: 35px; object-fit: cover; border-radius: 50%;" />
                            <span><?= $this->session->userdata('nama_penghuni') ?></span><i class="fa fa-angle-down m-l-5"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right" style="min-width: 150px">
                            <a class="dropdown-item" href="<?= base_url('penghuni/profil') ?>"><i class="fa fa-user"></i>Profil Saya</a>
                            <a class="dropdown-item" href="<?= base_url('penghuni/ubah-password') ?>"><i class="fa fa-lock"></i>Ubah Password</a>
                            <a class="dropdown-item" id="logout-alert-penghuni"><i class="fa fa-power-off"></i>Logout</a>
                        </ul>
                    </li>
                </ul>
                <!-- END TOP-RIGHT TOOLBAR-->
            </div>
        </header>
        <!-- END HEADER-->