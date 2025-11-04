<!-- START PAGE CONTENT-->
            <div class="page-content fade-in-up">
                
                <!-- PROFIL CARD WITH PHOTO -->
                <div class="row">
                    <div class="col-lg-4">
                        <div class="ibox">
                            <div class="ibox-body text-center" style="padding: 30px;">
                                <div class="profile-photo-wrapper">
                                    <?php 
                                    $foto_path = $penghuni->foto && file_exists('./assets/uploads/foto_penghuni/'.$penghuni->foto) 
                                        ? base_url('assets/uploads/foto_penghuni/'.$penghuni->foto)
                                        : base_url('assets/uploads/foto_penghuni/default-avatar.png');
                                    ?>
                                    <div class="profile-photo-container">
                                        <img src="<?= $foto_path ?>" 
                                             alt="Foto Profil" 
                                             id="previewFoto"
                                             class="profile-photo">
                                        <div class="photo-overlay">
                                            <label for="uploadFoto" class="upload-label">
                                                <i class="fa fa-camera"></i>
                                                <span>Ganti Foto</span>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <form id="formUploadFoto" method="post" enctype="multipart/form-data" style="display: none;">
                                        <input type="file" 
                                               id="uploadFoto" 
                                               name="foto" 
                                               accept="image/*"
                                               onchange="uploadFotoProfil()">
                                    </form>
                                </div>

                                <h4 class="mt-4 mb-1 font-strong"><?= $penghuni->nama ?></h4>
                                <p class="text-muted mb-3">
                                    <i class="fa fa-home"></i> Kamar <?= $penghuni->no_kamar ?>
                                </p>

                                <div class="profile-stats">
                                    <div class="stat-item">
                                        <h5 class="mb-0 text-success"><?= $penghuni->piutang == 0 ? 'Lunas' : 'Belum Lunas' ?></h5>
                                        <small class="text-muted">Status Pembayaran</small>
                                    </div>
                                </div>

                                <a href="<?= base_url('penghuni/ubah-password') ?>" class="btn btn-primary btn-block mt-4">
                                    <i class="fa fa-lock"></i> Ubah Password
                                </a>
                            </div>
                        </div>

                        <!-- TIPS CARD -->
                        <div class="ibox">
                            <div class="ibox-body" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 10px;">
                                <h5 class="mb-3" style="color: white;">
                                    <i class="fa fa-lightbulb-o"></i> Tips Foto Profil
                                </h5>
                                <ul class="mb-0" style="padding-left: 20px; color: rgba(255,255,255,0.9);">
                                    <li>Gunakan foto dengan pencahayaan baik</li>
                                    <li>Format: JPG, PNG, JPEG</li>
                                    <li>Ukuran maksimal: 2MB</li>
                                    <li>Rasio persegi untuk hasil terbaik</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <!-- DATA PRIBADI -->
                        <div class="ibox">
                            <div class="ibox-head">
                                <div class="ibox-title">
                                    <i class="fa fa-user"></i> Data Pribadi
                                </div>
                            </div>
                            <div class="ibox-body">
                                <table class="table table-borderless profile-table">
                                    <tr>
                                        <td width="35%">
                                            <i class="fa fa-user text-primary"></i> 
                                            <strong>Nama Lengkap</strong>
                                        </td>
                                        <td><?= $penghuni->nama ?></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="fa fa-id-card text-primary"></i> 
                                            <strong>No. KTP</strong>
                                        </td>
                                        <td><?= $penghuni->no_ktp ?></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="fa fa-map-marker text-primary"></i> 
                                            <strong>Alamat Asal</strong>
                                        </td>
                                        <td><?= $penghuni->alamat ?></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="fa fa-phone text-primary"></i> 
                                            <strong>No. Telp/HP</strong>
                                        </td>
                                        <td><?= $penghuni->no ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- DATA KAMAR -->
                        <div class="ibox">
                            <div class="ibox-head">
                                <div class="ibox-title">
                                    <i class="fa fa-home"></i> Informasi Kamar
                                </div>
                            </div>
                            <div class="ibox-body">
                                <table class="table table-borderless profile-table">
                                    <tr>
                                        <td width="35%">
                                            <i class="fa fa-building text-success"></i> 
                                            <strong>No. Kamar</strong>
                                        </td>
                                        <td><span class="badge badge-primary" style="font-size: 14px;"><?= $penghuni->no_kamar ?></span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="fa fa-calendar-check-o text-success"></i> 
                                            <strong>Tanggal Masuk</strong>
                                        </td>
                                        <td><?= $penghuni->tgl_masuk ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- DATA PEMBAYARAN -->
                        <div class="ibox">
                            <div class="ibox-head">
                                <div class="ibox-title">
                                    <i class="fa fa-money"></i> Informasi Pembayaran
                                </div>
                            </div>
                            <div class="ibox-body">
                                <div class="row text-center">
                                    <div class="col-md-4">
                                        <div class="info-box">
                                            <div class="info-icon bg-primary">
                                                <i class="fa fa-calculator"></i>
                                            </div>
                                            <h4 class="mt-3 mb-1">Rp<?= number_format($penghuni->harga_per_bulan, 0, ',', '.') ?></h4>
                                            <p class="text-muted mb-0">Total harga_per_bulan</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-box">
                                            <div class="info-icon bg-success">
                                                <i class="fa fa-check-circle"></i>
                                            </div>
                                            <h4 class="mt-3 mb-1">Rp<?= number_format($penghuni->bayar, 0, ',', '.') ?></h4>
                                            <p class="text-muted mb-0">Telah Dibayar</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-box">
                                            <div class="info-icon <?= $penghuni->piutang > 0 ? 'bg-warning' : 'bg-success' ?>">
                                                <i class="fa <?= $penghuni->piutang > 0 ? 'fa-exclamation-triangle' : 'fa-star' ?>"></i>
                                            </div>
                                            <h4 class="mt-3 mb-1">
                                                <?php if ($penghuni->piutang == 0): ?>
                                                    <span class="text-success">LUNAS</span>
                                                <?php else: ?>
                                                    Rp<?= number_format($penghuni->piutang, 0, ',', '.') ?>
                                                <?php endif; ?>
                                            </h4>
                                            <p class="text-muted mb-0">Sisa Tagihan</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <?php 
                                $persentase = $penghuni->harga_per_bulan > 0 ? ($penghuni->bayar / $penghuni->harga_per_bulan * 100) : 0;
                                ?>
                                <div class="mt-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Progress Pembayaran</span>
                                        <strong><?= number_format($persentase, 1) ?>%</strong>
                                    </div>
                                    <div class="progress" style="height: 20px; border-radius: 10px;">
                                        <div class="progress-bar progress-bar-striped <?= $persentase >= 100 ? 'bg-success' : 'bg-warning' ?>" 
                                             role="progressbar" 
                                             style="width: <?= $persentase ?>%;" 
                                             aria-valuenow="<?= $persentase ?>" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            <?= number_format($persentase, 1) ?>%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- END PAGE CONTENT-->

            <style>
                /* Profile Photo Styling */
                .profile-photo-wrapper {
                    margin-bottom: 20px;
                }

                .profile-photo-container {
                    position: relative;
                    width: 180px;
                    height: 180px;
                    margin: 0 auto;
                    border-radius: 50%;
                    overflow: hidden;
                    border: 5px solid #f0f0f0;
                    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
                    transition: all 0.3s ease;
                }

                .profile-photo-container:hover {
                    border-color: #667eea;
                    box-shadow: 0 8px 30px rgba(102, 126, 234, 0.3);
                }

                .profile-photo {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    transition: all 0.3s ease;
                }

                .photo-overlay {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.6);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                    cursor: pointer;
                }

                .profile-photo-container:hover .photo-overlay {
                    opacity: 1;
                }

                .upload-label {
                    color: white;
                    text-align: center;
                    cursor: pointer;
                    margin: 0;
                }

                .upload-label i {
                    display: block;
                    font-size: 32px;
                    margin-bottom: 5px;
                }

                .upload-label span {
                    font-size: 14px;
                    font-weight: 500;
                }

                /* Profile Stats */
                .profile-stats {
                    padding: 20px;
                    background: #f8f9fa;
                    border-radius: 10px;
                    margin-top: 20px;
                }

                .stat-item h5 {
                    font-size: 20px;
                    font-weight: 600;
                }

                /* Profile Table */
                .profile-table tr {
                    border-bottom: 1px solid #f0f0f0;
                }

                .profile-table tr:last-child {
                    border-bottom: none;
                }

                .profile-table td {
                    padding: 15px 10px;
                    vertical-align: middle;
                }

                .profile-table i {
                    margin-right: 8px;
                    font-size: 16px;
                }

                /* Info Box */
                .info-box {
                    padding: 20px;
                    border-radius: 10px;
                    transition: all 0.3s ease;
                }

                .info-box:hover {
                    background: #f8f9fa;
                    transform: translateY(-5px);
                }

                .info-icon {
                    width: 60px;
                    height: 60px;
                    line-height: 60px;
                    border-radius: 50%;
                    color: white;
                    font-size: 24px;
                    margin: 0 auto;
                    text-align: center;
                }

                /* Loading Overlay */
                .loading-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.7);
                    display: none;
                    align-items: center;
                    justify-content: center;
                    z-index: 9999;
                }

                .loading-content {
                    text-align: center;
                    color: white;
                }

                .loading-spinner {
                    border: 4px solid #f3f3f3;
                    border-top: 4px solid #667eea;
                    border-radius: 50%;
                    width: 50px;
                    height: 50px;
                    animation: spin 1s linear infinite;
                    margin: 0 auto 15px;
                }

                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }

                /* Responsive */
                @media (max-width: 768px) {
                    .profile-photo-container {
                        width: 150px;
                        height: 150px;
                    }

                    .info-box {
                        margin-bottom: 20px;
                    }
                }
            </style>

            <!-- Loading Overlay -->
            <div class="loading-overlay" id="loadingOverlay">
                <div class="loading-content">
                    <div class="loading-spinner"></div>
                    <p>Mengunggah foto...</p>
                </div>
            </div>

            <script>
                function uploadFotoProfil() {
                    const fileInput = document.getElementById('uploadFoto');
                    const file = fileInput.files[0];
                    
                    if (!file) return;
                    
                    // Validasi tipe file
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                    if (!allowedTypes.includes(file.type)) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Format Tidak Didukung',
                            text: 'Hanya file JPG, JPEG, dan PNG yang diperbolehkan',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }
                    
                    // Validasi ukuran file (max 2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        Swal.fire({
                            icon: 'error',
                            title: 'File Terlalu Besar',
                            text: 'Ukuran file maksimal 2MB',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }
                    
                    // Preview foto
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('previewFoto').src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                    
                    // Upload via AJAX
                    const formData = new FormData();
                    formData.append('foto', file);
                    
                    // Show loading
                    document.getElementById('loadingOverlay').style.display = 'flex';
                    
                    fetch('<?= base_url("penghuni/upload-foto") ?>', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Hide loading
                        document.getElementById('loadingOverlay').style.display = 'none';
                        
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Foto profil berhasil diperbarui',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: data.message || 'Terjadi kesalahan saat upload foto',
                                confirmButtonText: 'OK'
                            });
                            // Kembalikan foto ke semula
                            document.getElementById('previewFoto').src = '<?= $foto_path ?>';
                        }
                    })
                    .catch(error => {
                        // Hide loading
                        document.getElementById('loadingOverlay').style.display = 'none';
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat upload foto',
                            confirmButtonText: 'OK'
                        });
                        // Kembalikan foto ke semula
                        document.getElementById('previewFoto').src = '<?= $foto_path ?>';
                    });
                }
            </script>