<body>
    <div class="bg-login"></div>
    <div class="content changepass-parent">
        <form id="changepass-form" action="<?= base_url('penghuni/aksi-ubah-password') ?>" method="post" novalidate="novalidate">
            <div class="changepass-title">
                <h2 class="changepass-title1">Ubah Password</h2>
            </div>
            <div class="social-auth-hr">
                <span>Mengubah password akun <?="<strong>".$this->session->userdata('nama_penghuni')."</strong>"?></span>
            </div>
            <div class="form-group">
                <input class="form-control" type="password" name="password" placeholder="Password Lama" required>
            </div>
            <div class="form-group">
                <input class="form-control" type="password" name="password_baru" id="password_baru" placeholder="Password Baru" required>
            </div>
            <div class="form-group">
                <input class="form-control" type="password" name="konfirmasi_password_baru" placeholder="Konfirmasi Password Baru" required>
            </div>
            <?php if ($pesan == 'gagal_ubah_pass') echo '<div class="alert alert-danger">Password lama tidak benar.</div>' ?>
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <button class="btn btn-outline-primary btn-block" type="button" onclick="window.history.back()"><strong>Kembali</strong></button>
                    </div>
                </div>
                <div class="col-4">
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <button class="btn btn-primary btn-block" type="submit"><strong>Submit</strong></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>