<!-- START PAGE CONTENT-->
<div class="page-content fade-in-up">
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title">
                <i class="fa fa-plus-circle"></i> <?= $judul_halaman ?>
            </div>
            <div class="ibox-tools">
                <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
            </div>
        </div>
        <div class="ibox-body">
            <form class="form-horizontal" id="formTambahKamar" action="<?= base_url('aksi-tambah-kamar') ?>" method="post">
                
                <!-- ALERT INFO -->
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong><i class="fa fa-info-circle"></i> Informasi:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Nomor kamar harus unik dan belum terdaftar</li>
                        <li>Format nomor kamar: 3 digit (contoh: 101, 201, 301)</li>
                        <li>Harga kamar dalam satuan <strong>per bulan</strong></li>
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <hr>

                <!-- DATA KAMAR -->
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">
                        Lantai <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-9">
                        <select class="form-control" name="lantai" id="lantai" required>
                            <option value="">-- Pilih Lantai --</option>
                            <option value="1">Lantai 1</option>
                            <option value="2">Lantai 2</option>
                            <option value="3">Lantai 3</option>
                            <option value="4">Lantai 4</option>
                            <option value="5">Lantai 5</option>
                        </select>
                        <small class="form-text text-muted">Pilih lantai untuk kamar baru</small>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">
                        Nomor Kamar <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-9">
                        <input class="form-control" 
                               type="text" 
                               name="no_kamar" 
                               id="no_kamar"
                               placeholder="Contoh: 101, 201, 301" 
                               maxlength="5"
                               pattern="[0-9]{3,5}"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '');" 
                               required>
                        <small class="form-text text-muted">
                            <i class="fa fa-info-circle"></i> Format: 3-5 digit angka (contoh: 101 untuk Lantai 1 Kamar 01)
                        </small>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">
                        Harga per Bulan <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input class="form-control" 
                                   type="number" 
                                   name="harga" 
                                   id="harga"
                                   placeholder="Masukkan harga kamar per bulan" 
                                   min="100000"
                                   max="10000000"
                                   required>
                        </div>
                        <small class="form-text text-muted">
                            <i class="fa fa-money"></i> Harga sewa kamar per bulan (Minimal Rp100.000)
                        </small>
                        <div id="previewHarga" class="mt-2" style="display: none;">
                            <strong class="text-primary">Preview: <span id="hargaText"></span></strong>
                        </div>
                    </div>
                </div>

                <hr class="my-4">
                
                <!-- BUTTONS -->
                <div class="form-group row">
                    <div class="col-sm-9 ml-sm-auto">
                        <button class="btn btn-primary btn-lg" type="submit" id="btnSubmit">
                            <i class="fa fa-save"></i> <strong>Simpan Data Kamar</strong>
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
                        <i class="fa fa-building fa-3x" style="opacity: 0.9;"></i>
                    </div>
                    <h5 class="font-weight-bold mb-3" style="color: white;">
                        Penomoran Kamar
                    </h5>
                    <p style="color: rgba(255,255,255,0.9); margin: 0;">
                        Gunakan format <strong>LXX</strong> dimana L adalah lantai dan XX adalah nomor urut (contoh: 101, 201).
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="ibox">
                <div class="ibox-body" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 25px; border-radius: 10px;">
                    <div class="text-center mb-3">
                        <i class="fa fa-money fa-3x" style="opacity: 0.9;"></i>
                    </div>
                    <h5 class="font-weight-bold mb-3" style="color: white;">
                        Harga Fleksibel
                    </h5>
                    <p style="color: rgba(255,255,255,0.9); margin: 0;">
                        Harga per bulan dapat disesuaikan dengan fasilitas kamar dan dapat diubah sewaktu-waktu.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="ibox">
                <div class="ibox-body" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 25px; border-radius: 10px;">
                    <div class="text-center mb-3">
                        <i class="fa fa-check-circle fa-3x" style="opacity: 0.9;"></i>
                    </div>
                    <h5 class="font-weight-bold mb-3" style="color: white;">
                        Langsung Tersedia
                    </h5>
                    <p style="color: rgba(255,255,255,0.9); margin: 0;">
                        Setelah ditambahkan, kamar langsung tersedia untuk disewakan kepada penghuni baru.
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
                        <i class="fa fa-question-circle"></i> Panduan Penambahan Kamar
                    </div>
                </div>
                <div class="ibox-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold mb-3"><i class="fa fa-check-circle text-success"></i> Langkah-langkah:</h6>
                            <ol>
                                <li>Pilih lantai untuk kamar baru</li>
                                <li>Masukkan nomor kamar yang unik</li>
                                <li>Tentukan harga sewa per bulan</li>
                                <li>Klik "Simpan Data Kamar"</li>
                            </ol>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold mb-3"><i class="fa fa-lightbulb-o text-warning"></i> Tips:</h6>
                            <ul>
                                <li>Pastikan nomor kamar belum terdaftar</li>
                                <li>Gunakan format konsisten untuk nomor kamar</li>
                                <li>Pertimbangkan fasilitas saat menentukan harga</li>
                                <li>Harga dapat diubah kapan saja melalui menu "Edit Harga"</li>
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
    
    $('#formTambahKamar').on('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            toastr.warning('Mohon tunggu, data sedang diproses...');
            return false;
        }
        
        if (!this.checkValidity()) {
            e.preventDefault();
            toastr.error('Mohon lengkapi semua field yang wajib diisi');
            return false;
        }
        
        isSubmitting = true;
        
        $('#btnSubmit')
            .prop('disabled', true)
            .html('<i class="fa fa-spinner fa-spin"></i> Menyimpan Data...');
        
        setTimeout(function() {
            isSubmitting = false;
            $('#btnSubmit')
                .prop('disabled', false)
                .html('<i class="fa fa-save"></i> <strong>Simpan Data Kamar</strong>');
        }, 5000);
    });

    // ============================================
    // AUTO GENERATE NOMOR KAMAR
    // ============================================
    $('#lantai').on('change', function() {
        const lantai = $(this).val();
        if (lantai) {
            // Sarankan format nomor kamar berdasarkan lantai
            $('#no_kamar').attr('placeholder', 'Contoh: ' + lantai + '01, ' + lantai + '02, ' + lantai + '03');
        }
    });

    // ============================================
    // FORMAT PREVIEW HARGA
    // ============================================
    $('#harga').on('input', function() {
        const val = $(this).val();
        if (val && val >= 100000) {
            const formatted = 'Rp' + parseInt(val).toLocaleString('id-ID');
            $('#hargaText').text(formatted + ' per bulan');
            $('#previewHarga').show();
            $(this).removeClass('is-invalid').addClass('is-valid');
        } else {
            $('#previewHarga').hide();
            if (val && val < 100000) {
                $(this).addClass('is-invalid');
            }
        }
    });

    // ============================================
    // VALIDASI NOMOR KAMAR
    // ============================================
    $('#no_kamar').on('input', function() {
        const val = $(this).val();
        if (val.length >= 3) {
            $(this).removeClass('is-invalid').addClass('is-valid');
        } else {
            $(this).removeClass('is-valid');
        }
    });

    // ============================================
    // AUTO FOCUS LANTAI
    // ============================================
    setTimeout(function() {
        $('#lantai').focus();
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

#previewHarga {
    padding: 10px;
    background: #f0f8ff;
    border-radius: 5px;
    border-left: 4px solid #007bff;
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