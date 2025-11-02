<!-- START PAGE CONTENT -->
            <div class="page-content fade-in-up">
                
                <!-- WELCOME BANNER -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px; overflow: hidden;">
                            <div class="ibox-body" style="padding: 30px;">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h2 class="font-strong mb-2" style="color: white;">Selamat Datang, <?= $penghuni->nama ?>! 👋</h2>
                                        <p style="color: rgba(255,255,255,0.9); font-size: 16px;">
                                            Anda menghuni Kamar <strong><?= $penghuni->no_kamar ?></strong> | 
                                            Masa huni hingga <strong><?= $penghuni->tgl_keluar ?></strong>
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <i class="fa fa-home" style="font-size: 80px; opacity: 0.2;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STATISTIK CARDS -->
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="ibox widget-card">
                            <div class="ibox-body text-center" style="padding: 25px;">
                                <div class="widget-icon bg-primary mb-3">
                                    <i class="fa fa-money"></i>
                                </div>
                                <h3 class="font-strong mb-2">Rp<?= number_format($penghuni->biaya, 0, ',', '.') ?></h3>
                                <p class="text-muted mb-0">Total Biaya Sewa</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="ibox widget-card">
                            <div class="ibox-body text-center" style="padding: 25px;">
                                <div class="widget-icon bg-success mb-3">
                                    <i class="fa fa-check-circle"></i>
                                </div>
                                <h3 class="font-strong mb-2">Rp<?= number_format($penghuni->bayar, 0, ',', '.') ?></h3>
                                <p class="text-muted mb-0">Telah Dibayar</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="ibox widget-card">
                            <div class="ibox-body text-center" style="padding: 25px;">
                                <div class="widget-icon <?= $penghuni->piutang > 0 ? 'bg-warning' : 'bg-success' ?> mb-3">
                                    <i class="fa <?= $penghuni->piutang > 0 ? 'fa-exclamation-triangle' : 'fa-star' ?>"></i>
                                </div>
                                <h3 class="font-strong mb-2">
                                    <?php if ($penghuni->piutang == 0): ?>
                                        <span class="text-success">LUNAS</span>
                                    <?php else: ?>
                                        Rp<?= number_format($penghuni->piutang, 0, ',', '.') ?>
                                    <?php endif; ?>
                                </h3>
                                <p class="text-muted mb-0">Sisa Tagihan</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PROGRESS BAR PEMBAYARAN -->
                <?php 
                $persentase = $penghuni->biaya > 0 ? ($penghuni->bayar / $penghuni->biaya * 100) : 0;
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox">
                            <div class="ibox-body" style="padding: 25px;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="mb-0">Progress Pembayaran</h5>
                                    <span class="badge badge-<?= $persentase >= 100 ? 'success' : 'warning' ?>" style="font-size: 14px;">
                                        <?= number_format($persentase, 1) ?>%
                                    </span>
                                </div>
                                <div class="progress" style="height: 25px; border-radius: 15px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated 
                                                <?= $persentase >= 100 ? 'bg-success' : 'bg-warning' ?>" 
                                         role="progressbar" 
                                         style="width: <?= $persentase ?>%;" 
                                         aria-valuenow="<?= $persentase ?>" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        <strong><?= number_format($persentase, 1) ?>%</strong>
                                    </div>
                                </div>
                                <small class="text-muted mt-2 d-block">
                                    Rp<?= number_format($penghuni->bayar, 0, ',', '.') ?> dari Rp<?= number_format($penghuni->biaya, 0, ',', '.') ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STATUS PENGAJUAN PEMBAYARAN -->
                <?php if (!empty($pengajuan_pending)): ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox">
                            <div class="ibox-head" style="background: #f8f9fa;">
                                <div class="ibox-title">
                                    <i class="fa fa-clock-o"></i> Status Pengajuan Pembayaran
                                </div>
                            </div>
                            <div class="ibox-body">
                                <div class="row">
                                    <?php foreach ($pengajuan_pending as $pengajuan): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card-pengajuan">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h5 class="mb-1">Rp<?= number_format($pengajuan->nominal, 0, ',', '.') ?></h5>
                                                    <small class="text-muted">
                                                        <i class="fa fa-calendar"></i> 
                                                        <?= date('d M Y H:i', strtotime($pengajuan->tgl_pengajuan)) ?>
                                                    </small>
                                                </div>
                                                <span class="badge badge-<?= $pengajuan->status == 'pending' ? 'warning' : ($pengajuan->status == 'approved' ? 'success' : 'danger') ?>">
                                                    <?php 
                                                    if ($pengajuan->status == 'pending') echo 'Menunggu';
                                                    elseif ($pengajuan->status == 'approved') echo 'Disetujui';
                                                    else echo 'Ditolak';
                                                    ?>
                                                </span>
                                            </div>
                                            <div class="mb-2">
                                                <i class="fa fa-credit-card"></i> <?= $pengajuan->metode_pembayaran ?>
                                            </div>
                                            <a href="<?= base_url('assets/uploads/bukti_transfer/'.$pengajuan->bukti_transfer) ?>" 
                                               target="_blank" 
                                               class="btn btn-sm btn-outline-info">
                                                <i class="fa fa-eye"></i> Lihat Bukti
                                            </a>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- FORM PEMBAYARAN -->
                <?php if ($penghuni->piutang > 0): ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox payment-section">
                            <div class="ibox-head" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                                <div class="ibox-title" style="color: white;">
                                    <i class="fa fa-credit-card"></i> Lakukan Pembayaran
                                </div>
                            </div>
                            <div class="ibox-body" style="padding: 30px;">
                                <div class="row">
                                    <!-- QRIS & Metode Pembayaran -->
                                    <div class="col-md-6 mb-4">
                                        <h5 class="mb-4">
                                            <i class="fa fa-qrcode"></i> Metode Pembayaran
                                        </h5>
                                        
                                        <!-- QRIS -->
                                        <div class="text-center mb-4 qris-container">
                                            <div class="qris-badge mb-2">
                                                <span class="badge badge-primary" style="font-size: 14px;">
                                                    <i class="fa fa-mobile"></i> Scan dengan Aplikasi
                                                </span>
                                            </div>
                                            <div class="qris-image-wrapper">
                                                <img src="<?= base_url('assets/img/qris.png') ?>" 
                                                     alt="QRIS" 
                                                     class="img-fluid qris-image">
                                            </div>
                                            <p class="text-muted mt-3">
                                                <i class="fa fa-check-circle text-success"></i> 
                                                Scan untuk pembayaran instan
                                            </p>
                                        </div>

                                        <!-- Metode Lain -->
                                        <div class="payment-methods">
                                            <h6 class="mb-3">Atau transfer ke rekening:</h6>
                                            <div class="payment-option">
                                                <div class="payment-icon" style="background: #00D09C;">
                                                    <i class="fa fa-mobile"></i>
                                                </div>
                                                <div>
                                                    <strong>GoPay</strong><br>
                                                    <span class="text-muted">0813-3389-6104</span>
                                                </div>
                                            </div>
                                            <div class="payment-option">
                                                <div class="payment-icon" style="background: #118EEA;">
                                                    <i class="fa fa-mobile"></i>
                                                </div>
                                                <div>
                                                    <strong>DANA</strong><br>
                                                    <span class="text-muted">0813-3389-6104</span>
                                                </div>
                                            </div>
                                            <div class="payment-option">
                                                <div class="payment-icon" style="background: #003D79;">
                                                    <i class="fa fa-bank"></i>
                                                </div>
                                                <div>
                                                    <strong>Bank BCA</strong><br>
                                                    <span class="text-muted">1234567890 a/n Sky Kost</span>
                                                </div>
                                            </div>
                                            <div class="payment-option">
                                                <div class="payment-icon" style="background: #FFD500;">
                                                    <i class="fa fa-bank"></i>
                                                </div>
                                                <div>
                                                    <strong>Bank Mandiri</strong><br>
                                                    <span class="text-muted">0987654321 a/n Sky Kost</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Form Upload Bukti -->
                                    <div class="col-md-6">
                                        <h5 class="mb-4">
                                            <i class="fa fa-upload"></i> Konfirmasi Pembayaran
                                        </h5>
                                        <form action="<?= base_url('penghuni/ajukan-pembayaran') ?>" 
                                              method="post" 
                                              enctype="multipart/form-data" 
                                              id="form-pembayaran">
                                            
                                            <div class="form-group">
                                                <label>Nominal Pembayaran <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Rp</span>
                                                    </div>
                                                    <input type="number" 
                                                           class="form-control form-control-lg" 
                                                           name="nominal" 
                                                           placeholder="0" 
                                                           required>
                                                </div>
                                                <small class="text-muted">
                                                    Sisa tagihan: <strong class="text-danger">Rp<?= number_format($penghuni->piutang, 0, ',', '.') ?></strong>
                                                </small>
                                            </div>

                                            <div class="form-group">
                                                <label>Metode Pembayaran <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-lg" name="metode_pembayaran" required>
                                                    <option value="">-- Pilih Metode --</option>
                                                    <option value="QRIS">📱 QRIS</option>
                                                    <option value="GoPay">💚 GoPay</option>
                                                    <option value="DANA">💙 DANA</option>
                                                    <option value="Transfer BCA">🏦 Transfer Bank BCA</option>
                                                    <option value="Transfer Mandiri">🏦 Transfer Bank Mandiri</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Upload Bukti Transfer <span class="text-danger">*</span></label>
                                                <div class="custom-file">
                                                    <input type="file" 
                                                           class="custom-file-input" 
                                                           id="buktiTransfer"
                                                           name="bukti_transfer" 
                                                           accept="image/*" 
                                                           required>
                                                    <label class="custom-file-label" for="buktiTransfer">Pilih file...</label>
                                                </div>
                                                <small class="text-muted">Format: JPG, PNG, JPEG (Max 2MB)</small>
                                                
                                                <!-- Preview Image -->
                                                <div id="imagePreview" class="mt-3" style="display: none;">
                                                    <img id="previewImg" src="" class="img-thumbnail" style="max-height: 200px;">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label>Keterangan (Opsional)</label>
                                                <textarea class="form-control" 
                                                          name="keterangan" 
                                                          rows="2" 
                                                          placeholder="Catatan tambahan..."></textarea>
                                            </div>

                                            <button type="submit" class="btn btn-primary btn-lg btn-block" style="border-radius: 10px;">
                                                <i class="fa fa-check"></i> Kirim Konfirmasi Pembayaran
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; border-radius: 15px;">
                            <div class="ibox-body text-center" style="padding: 40px;">
                                <i class="fa fa-check-circle" style="font-size: 64px; margin-bottom: 20px;"></i>
                                <h3 class="font-strong" style="color: white;">Pembayaran Sudah Lunas!</h3>
                                <p style="color: rgba(255,255,255,0.9); font-size: 16px;">
                                    Terima kasih atas pembayaran Anda. Semua tagihan telah terbayar lunas.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Riwayat Pembayaran Terakhir -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox">
                            <div class="ibox-head">
                                <div class="ibox-title">
                                    <i class="fa fa-history"></i> Riwayat Pembayaran Terakhir
                                </div>
                                <div class="ibox-tools">
                                    <a href="<?= base_url('penghuni/riwayat-pembayaran') ?>" class="btn btn-sm btn-primary">
                                        Lihat Semua
                                    </a>
                                </div>
                            </div>
                            <div class="ibox-body">
                                <?php if (empty($pembayaran)): ?>
                                    <div class="text-center py-5">
                                        <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Belum ada riwayat pembayaran</p>
                                    </div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Tanggal</th>
                                                    <th class="text-right">Nominal</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no = 1; foreach (array_slice($pembayaran, 0, 5) as $bayar): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td>
                                                        <i class="fa fa-calendar-o"></i> <?= $bayar->tgl_bayar ?>
                                                    </td>
                                                    <td class="text-right">
                                                        <strong class="text-success">
                                                            Rp<?= number_format($bayar->bayar, 0, ',', '.') ?>
                                                        </strong>
                                                    </td>
                                                    <td><?= $bayar->ket ?: '-' ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- END PAGE CONTENT -->

            <style>
                /* Widget Card Styling */
                .widget-card {
                    border-radius: 15px;
                    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
                    transition: all 0.3s ease;
                    border: none;
                }

                .widget-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 5px 25px rgba(0,0,0,0.15);
                }

                .widget-icon {
                    width: 70px;
                    height: 70px;
                    line-height: 70px;
                    border-radius: 50%;
                    color: white;
                    font-size: 30px;
                    display: inline-block;
                }

                /* Payment Methods */
                .payment-option {
                    display: flex;
                    align-items: center;
                    padding: 15px;
                    margin-bottom: 10px;
                    border: 2px solid #e9ecef;
                    border-radius: 10px;
                    transition: all 0.3s ease;
                }

                .payment-option:hover {
                    border-color: #007bff;
                    background: #f8f9fa;
                    transform: translateX(5px);
                }

                .payment-icon {
                    width: 50px;
                    height: 50px;
                    line-height: 50px;
                    text-align: center;
                    border-radius: 10px;
                    color: white;
                    font-size: 24px;
                    margin-right: 15px;
                }

                /* QRIS Styling */
                .qris-container {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    padding: 20px;
                    border-radius: 15px;
                    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
                }

                .qris-image-wrapper {
                    background: white;
                    padding: 15px;
                    border-radius: 10px;
                    display: inline-block;
                }

                .qris-image {
                    max-width: 250px;
                    border-radius: 5px;
                }

                /* Card Pengajuan */
                .card-pengajuan {
                    padding: 20px;
                    border: 2px solid #e9ecef;
                    border-radius: 10px;
                    transition: all 0.3s ease;
                }

                .card-pengajuan:hover {
                    border-color: #007bff;
                    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                }

                /* Form Styling */
                .form-control-lg {
                    border-radius: 8px;
                }

                .custom-file-label {
                    border-radius: 8px;
                }

                /* Animations */
                @keyframes fadeInUp {
                    from {
                        opacity: 0;
                        transform: translateY(20px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .fade-in-up {
                    animation: fadeInUp 0.6s ease;
                }

                /* Progress Bar Custom */
                .progress {
                    box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
                }

                /* Responsive */
                @media (max-width: 768px) {
                    .widget-icon {
                        width: 50px;
                        height: 50px;
                        line-height: 50px;
                        font-size: 24px;
                    }

                    .qris-image {
                        max-width: 200px;
                    }
                }
            </style>

            <script>
                // Preview image before upload
                document.getElementById('buktiTransfer').addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('previewImg').src = e.target.result;
                            document.getElementById('imagePreview').style.display = 'block';
                        }
                        reader.readAsDataURL(file);
                        
                        // Update label
                        document.querySelector('.custom-file-label').textContent = file.name;
                    }
                });

                // Form validation
                document.getElementById('form-pembayaran').addEventListener('submit', function(e) {
                    const nominal = this.querySelector('[name="nominal"]').value;
                    const sisaTagihan = <?= $penghuni->piutang ?>;
                    
                    if (parseInt(nominal) > sisaTagihan) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Nominal Melebihi Tagihan',
                            text: 'Nominal pembayaran tidak boleh melebihi sisa tagihan Anda',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            </script>