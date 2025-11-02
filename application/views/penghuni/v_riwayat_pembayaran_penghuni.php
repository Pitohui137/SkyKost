<!-- START PAGE CONTENT-->
            <div class="page-content fade-in-up">
                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title"><?= $judul_halaman ?></div>
                    </div>
                    <div class="ibox-body">
                        <?php if (empty($pembayaran)): ?>
                            <div class="alert alert-info text-center">
                                <i class="fa fa-info-circle"></i> Belum ada riwayat pembayaran
                            </div>
                        <?php else: ?>
                            <table class="table table-striped table-bordered table-hover" id="tabel-responsif" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 24.5px">No.</th>
                                        <th class="text-center">Tanggal Pembayaran</th>
                                        <th class="text-center">Nominal Pembayaran</th>
                                        <th class="text-center">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach ($pembayaran as $bayar): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td class="text-center"><?= $bayar->tgl_bayar ?></td>
                                        <td class="text-center">Rp<?= number_format($bayar->bayar, 0, ',', '.') ?></td>
                                        <td><?= $bayar->ket ?: '-' ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-right"><strong>Total Telah Dibayar:</strong></td>
                                        <td class="text-center"><strong>Rp<?= number_format(array_sum(array_column($pembayaran, 'bayar')), 0, ',', '.') ?></strong></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- END PAGE CONTENT-->