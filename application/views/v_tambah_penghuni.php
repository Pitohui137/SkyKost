<!-- START PAGE CONTENT-->
<div class="page-content fade-in-up">
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title">
                <i class="fa fa-user-plus"></i> Tambah Penghuni - Kamar <?= $kamar->no_kamar ?>
            </div>
            <div class="ibox-tools">
                <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
            </div>
        </div>
        <div class="ibox-body">
            <form class="form-horizontal" id="formTambahPenghuni" action="<?= base_url('aksi-tambah-penghuni') ?>" method="post">
                
                <?php
                // ============================================
                // GENERATE TOKEN UNTUK PREVENT DOUBLE SUBMIT
                // ============================================
                $token = bin2hex(random_bytes(32));
                $this->session->set_userdata('form_token', $token);
                ?>
                <input type="hidden" name="form_token" value="<?= $token ?>">
                
                <!-- INFO KAMAR -->
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong><i class="fa fa-info-circle"></i> Info Kamar:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Lantai: <strong><?= $kamar->lantai ?></strong></li>
                        <li>Harga: <strong>Rp<?= number_format($kamar->harga, 0, ',', '.') ?>/bulan</strong></li>
                        <li>Status: <strong class="text-success">Tersedia</strong></li>
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <hr>

                <!-- DATA KAMAR (READ ONLY) -->
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">No. Kamar</label>
                    <div class="col-sm-9">
                        <input class="form-control form-control-lg font-weight-bold" 
                               type="text" 
                               name="no_kamar" 
                               value="<?= $kamar->no_kamar ?>" 
                               readonly 
                               style="background-color: #e9ecef;">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Harga Kamar per Bulan</label>
                    <div class="col-sm-9">
                        <input class="form-control form-control-lg text-primary font-weight-bold" 
                               type="text" 
                               value="Rp<?= number_format($kamar->harga, 0, ',', '.') ?>" 
                               readonly 
                               style="background-color: #e9ecef;">
                        <small class="form-text text-muted">
                            <i class="fa fa-info-circle"></i> Tagihan akan otomatis bertambah setiap bulan sesuai harga ini
                        </small>
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="mb-3"><i class="fa fa-user"></i> Data Penghuni</h5>

                <!-- DATA PENGHUNI -->
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">
                        Nama Lengkap <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-9">
                        <input class="form-control" 
                               type="text" 
                               name="nama" 
                               id="nama"
                               placeholder="Masukkan nama lengkap penghuni" 
                               maxlength="200" 
                               oninput="this.value = this.value.replace(/[^a-z A-Z ' .]/g, '');" 
                               required>
                        <small class="form-text text-muted">Nama yang akan digunakan untuk login ke portal penghuni</small>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">No. KTP</label>
                    <div class="col-sm-9">
                        <input class="form-control" 
                               type="text" 
                               name="no_ktp" 
                               placeholder="Nomor KTP (16 digit)" 
                               maxlength="50" 
                               oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">
                        Alamat Asal <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-9">
                        <textarea class="form-control" 
                                  name="alamat" 
                                  rows="2" 
                                  placeholder="Masukkan alamat lengkap" 
                                  maxlength="200" 
                                  required></textarea>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">
                        No. Telp/HP <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-9">
                        <input class="form-control" 
                               type="text" 
                               name="no" 
                               placeholder="Contoh: 081234567890" 
                               maxlength="30" 
                               oninput="this.value = this.value.replace(/[^0-9 +]/g, '');" 
                               required>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">
                        Password <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <input class="form-control" 
                                   type="password" 
                                   name="password" 
                                   id="password"
                                   placeholder="Minimal 6 karakter" 
                                   maxlength="200" 
                                   minlength="6"
                                   required>
                        </div>
                        <small class="form-text text-muted">
                            <i class="fa fa-info-circle"></i> Password ini akan digunakan penghuni untuk login ke portal penghuni
                        </small>
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="mb-3"><i class="fa fa-calendar"></i> Tanggal Mulai Huni</h5>
                
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">
                        Tanggal Masuk <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-9">
                        <div class="input-group date" id="datepicker">
                            <input class="form-control" 
                                   type="text" 
                                   name="tgl_masuk" 
                                   id="tgl_masuk" 
                                   placeholder="Pilih tanggal masuk (dd-mm-yyyy)" 
                                   autocomplete="off" 
                                   required>
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                        <small class="form-text text-muted">
                            <i class="fa fa-info-circle"></i> Tagihan akan mulai dihitung dari tanggal ini dan bertambah otomatis setiap bulan
                        </small>
                    </div>
                </div>

                <hr class="my-4">
                
                <!-- BUTTONS -->
                <div class="form-group row">
                    <div class="col-sm-9 ml-sm-auto">
                        <button class="btn btn-primary btn-lg" type="submit" id="btnSubmit">
                            <i class="fa fa-save"></i> <strong>Simpan Data Penghuni</strong>
                        </button>
                        <button class="btn btn-danger btn-lg" type="button" onclick="if(confirm('Yakin ingin membatalkan?')) window.history.back()">
                            <i class="fa fa-times"></i> Batal
                        </button>
                        <button class="btn btn-outline-secondary btn-lg" type="reset">
                            <i class="fa fa-refresh"></i> Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- INFO BOX -->
    <div class="row">
        <div class="col-lg-4">
            <div class="ibox">
                <div class="ibox-body" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 25px; border-radius: 10px;">
                    <div class="text-center mb-3">
                        <i class="fa fa-refresh fa-3x" style="opacity: 0.9;"></i>
                    </div>
                    <h5 class="font-weight-bold mb-3" style="color: white;">
                        Auto Update Tagihan
                    </h5>
                    <p style="color: rgba(255,255,255,0.9); margin: 0;">
                        Tagihan akan otomatis ter-update setiap bulan sesuai durasi sewa yang telah ditentukan.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="ibox">
                <div class="ibox-body" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 25px; border-radius: 10px;">
                    <div class="text-center mb-3">
                        <i class="fa fa-mobile fa-3x" style="opacity: 0.9;"></i>
                    </div>
                    <h5 class="font-weight-bold mb-3" style="color: white;">
                        Portal Penghuni
                    </h5>
                    <p style="color: rgba(255,255,255,0.9); margin: 0;">
                        Penghuni dapat login menggunakan <strong>nama lengkap</strong> dan <strong>password</strong> yang telah ditentukan.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="ibox">
                <div class="ibox-body" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 25px; border-radius: 10px;">
                    <div class="text-center mb-3">
                        <i class="fa fa-credit-card fa-3x" style="opacity: 0.9;"></i>
                    </div>
                    <h5 class="font-weight-bold mb-3" style="color: white;">
                        Pembayaran Online
                    </h5>
                    <p style="color: rgba(255,255,255,0.9); margin: 0;">
                        Penghuni dapat mengajukan pembayaran via QRIS, GoPay, DANA, dan Transfer Bank.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- PANDUAN -->
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">
                        <i class="fa fa-question-circle"></i> Panduan Pengisian
                    </div>
                </div>
                <div class="ibox-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold mb-3"><i class="fa fa-check-circle text-success"></i> Wajib Diisi:</h6>
                            <ul>
                                <li>Nama Lengkap (untuk login portal penghuni)</li>
                                <li>Alamat Asal</li>
                                <li>No. Telp/HP</li>
                                <li>Password (minimal 6 karakter)</li>
                                <li>Tanggal Mulai Huni</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold mb-3"><i class="fa fa-lightbulb-o text-warning"></i> Tips:</h6>
                            <ul>
                                <li>Pastikan <strong>nama</strong> dan <strong>password</strong> dicatat dengan baik</li>
                                <li>Tagihan akan otomatis bertambah: <strong>Harga Kamar × Jumlah Bulan Huni</strong></li>
                                <li>Sistem akan otomatis update tagihan setiap bulan via cron job</li>
                                <li>Penghuni hanya bisa dikeluarkan secara manual oleh admin</li>
                                <li>Penghuni bisa upload bukti pembayaran via portal</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->

<script>
$(document).ready(function(){
    // ============================================
    // PREVENT DOUBLE SUBMIT
    // ============================================
    let isSubmitting = false;
    
    $('#formTambahPenghuni').on('submit', function(e) {
        // Jika sedang submit, prevent
        if (isSubmitting) {
            e.preventDefault();
            toastr.warning('Mohon tunggu, data sedang diproses...');
            return false;
        }
        
        // Validasi form
        if (!this.checkValidity()) {
            e.preventDefault();
            toastr.error('Mohon lengkapi semua field yang wajib diisi');
            return false;
        }
        
        // Set flag
        isSubmitting = true;
        
        // Disable button & show loading
        $('#btnSubmit')
            .prop('disabled', true)
            .html('<i class="fa fa-spinner fa-spin"></i> Menyimpan Data...');
        
        // Re-enable setelah 5 detik (safety mechanism)
        setTimeout(function() {
            isSubmitting = false;
            $('#btnSubmit')
                .prop('disabled', false)
                .html('<i class="fa fa-save"></i> <strong>Simpan Data Penghuni</strong>');
        }, 5000);
    });

    // ============================================
    // BOOTSTRAP DATEPICKER
    // ============================================
    $("#datepicker").datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true,
        format: "dd-mm-yyyy",
        startDate: '-1m',
        endDate: '+1m',
        todayHighlight: true
    });

    // Form Masks
    $("#tgl_masuk").mask("99-99-9999", {
        placeholder: "dd-mm-yyyy"
    });

    // ============================================
    // TOGGLE PASSWORD VISIBILITY
    // ============================================
    $('#togglePassword').click(function() {
        const passwordField = $('#password');
        const eyeIcon = $('#eyeIcon');
        
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            eyeIcon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            eyeIcon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // ============================================
    // FORM VALIDATION FEEDBACK
    // ============================================
    $('#nama').on('input', function() {
        const val = $(this).val().trim();
        if (val.length < 3) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid').addClass('is-valid');
        }
    });

    // ============================================
    // AUTO FOCUS NAMA
    // ============================================
    setTimeout(function() {
        $('#nama').focus();
    }, 500);
});
</script>

<style>
/* Custom Styles */
.text-danger {
    color: #dc3545 !important;
}

.font-weight-bold {
    font-weight: 600 !important;
}

#btnSubmit:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.form-control.is-valid {
    border-color: #28a745;
}

.form-control.is-invalid {
    border-color: #dc3545;
}

.ibox {
    margin-bottom: 30px;
}

.alert {
    border-radius: 8px;
}

.input-group-text {
    background-color: #f8f9fa;
}

hr {
    border-top: 2px solid #e9ecef;
}

h5 {
    color: #495057;
}

/* Responsive */
@media (max-width: 768px) {
    .col-form-label {
        text-align: left !important;
        margin-bottom: 5px;
    }
    
    .btn-lg {
        width: 100%;
        margin-bottom: 10px;
    }
}
</style>