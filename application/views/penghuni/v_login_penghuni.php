<body>
    <div class="bg-login"></div>
    <div class="content login-parent">
        <form id="login-form" action="<?= base_url('penghuni/aksi-login') ?>" method="post">
                <div class="login-title">
                    <img src="<?= base_url('assets/img/skykost.jpg') ?>" alt="Kos" style="width: 120px; vertical-align: middle;">
                    <h2 class="login-title1">Portal Penghuni</h2>
                </div>
                <div class="social-auth-hr">
                    <span>Silakan Masuk</span>
                </div>
                <div class="form-group">
                    <div class="input-group-icon right">
                        <div class="input-icon"><i class="fa fa-user"></i></div>
                        <input class="form-control" type="text" name="nama" placeholder="Nama Lengkap" autocomplete="off" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group-icon right">
                        <div class="input-icon"><i class="fa fa-lock font-16"></i></div>
                        <input class="form-control" type="password" name="password" placeholder="Password" required>
                    </div>
                </div>
                <?php
                switch ($pesan){
                    case 'gagal_login':
                        echo '<div class="alert alert-danger gagal-login">Nama dan password tidak cocok.</div>';
                    break;
                    case 'berhasil_logout':
                        echo '<div class="alert alert-success berhasil-logout">Berhasil keluar dari sistem.</div>';
                    break;
                    case 'berhasil_ubah_pass':
                        echo '<div class="alert alert-warning berhasil-ubah">Berhasil mengubah password. Silakan login lagi.</div>';
                    break;
                    case 'tidak_aktif':
                        echo '<div class="alert alert-danger">Akun Anda sudah tidak aktif.</div>';
                    break;
                }
                ?>
                <div class="form-group">
                    <button class="btn btn-primary btn-block" type="submit">Login</button>
                </div>
                <div class="form-group text-center">
                    <a href="<?= base_url('login') ?>" class="text-muted">Login sebagai Admin</a>
                </div>
        </form>
    </div>
</body>