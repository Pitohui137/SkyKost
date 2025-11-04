<!-- START PAGE CONTENT-->
            <div class="page-content fade-in-up">
                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title"><?= $judul_halaman ?></div>
                    </div>
                    <div class="ibox-body">
                        <table class="table table-striped table-bordered table-hover" id="tabel-responsif" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 24.5px">No.</th>
                                    <th class="text-center">Lantai</th>
                                    <th class="text-center">No. Kamar</th>
                                    <th class="text-center">Harga per Bulan</th>
                                    <th class="text-center">Status Kamar</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($kamar as $k): ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td class="text-center"><?= 'Lantai '.$k->lantai ?></td>
                                    <td class="text-center"><strong><?= $k->no_kamar ?></strong></td>
                                    <td class="text-center">
                                        <strong class="text-primary">Rp<?= number_format($k->harga, 0, ',', '.') ?></strong>
                                        <br>
                                        <small class="text-muted">/ bulan</small>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($k->jml_penghuni == '1'): ?>
                                            <span class="badge badge-danger">
                                                <i class="fa fa-user"></i> Sudah Berpenghuni
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-success">
                                                <i class="fa fa-check"></i> Tersedia
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a class="btn btn-sm btn-success active" 
                                           href="<?= base_url('tambah-penghuni/'.$k->no_kamar) ?>" 
                                           data-toggle="tooltip" 
                                           data-placement="top" 
                                           title="Tambah Penghuni">
                                            <span class="fa fa-plus"></span> Penghuni
                                        </a>
                                        <a class="btn btn-sm btn-info active" 
                                           href="<?= base_url('edit-harga-kamar/'.$k->no_kamar) ?>" 
                                           data-toggle="tooltip" 
                                           data-placement="top" 
                                           title="Ubah Harga">
                                            <span class="fa fa-pencil"></span> Harga
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- CARD INFO -->
                <div class="row mt-4">
                    <div class="col-lg-4">
                        <div class="ibox">
                            <div class="ibox-body text-center" style="padding: 25px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 10px;">
                                <i class="fa fa-home fa-3x mb-3" style="opacity: 0.9;"></i>
                                <h3 class="font-strong" style="color: white;">Sistem Pembayaran</h3>
                                <p style="color: rgba(255,255,255,0.9); margin: 0;">
                                    Harga kamar dihitung <strong>per bulan</strong>. Penghuni akan ditagih setiap bulan berdasarkan harga kamar yang telah ditentukan.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="ibox">
                            <div class="ibox-body text-center" style="padding: 25px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 10px;">
                                <i class="fa fa-calendar fa-3x mb-3" style="opacity: 0.9;"></i>
                                <h3 class="font-strong" style="color: white;">Auto Update</h3>
                                <p style="color: rgba(255,255,255,0.9); margin: 0;">
                                    Tagihan akan otomatis terupdate setiap awal bulan. Penghuni akan ditagih sesuai harga per bulan yang berlaku.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="ibox">
                            <div class="ibox-body text-center" style="padding: 25px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border-radius: 10px;">
                                <i class="fa fa-calculator fa-3x mb-3" style="opacity: 0.9;"></i>
                                <h3 class="font-strong" style="color: white;">Fleksibel</h3>
                                <p style="color: rgba(255,255,255,0.9); margin: 0;">
                                    Durasi sewa fleksibel. Penghuni bisa sewa 1 bulan, 3 bulan, 6 bulan, atau lebih sesuai kebutuhan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PAGE CONTENT-->