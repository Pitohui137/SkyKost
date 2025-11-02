<!-- START PAGE CONTENT-->
            <div class="page-content fade-in-up">
                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title"><?= $judul_halaman ?></div>
                    </div>
                    <div class="ibox-body">
                        <?php if (empty($pengajuan)): ?>
                            <div class="alert alert-info text-center">
                                <i class="fa fa-info-circle"></i> Tidak ada pengajuan pembayaran
                            </div>
                        <?php else: ?>
                            <table class="table table-striped table-bordered table-hover" id="tabel-responsif" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 24.5px">No.</th>
                                        <th class="text-center">Tanggal Pengajuan</th>
                                        <th class="text-center">No. Kamar</th>
                                        <th class="text-center">Nama Penghuni</th>
                                        <th class="text-center">Nominal</th>
                                        <th class="text-center">Metode</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach ($pengajuan as $item): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td class="text-center"><?= date('d-m-Y H:i', strtotime($item->tgl_pengajuan)) ?></td>
                                        <td class="text-center"><?= $item->no_kamar ?></td>
                                        <td><?= $item->nama ?></td>
                                        <td class="text-center">Rp<?= number_format($item->nominal, 0, ',', '.') ?></td>
                                        <td class="text-center"><?= $item->metode_pembayaran ?></td>
                                        <td class="text-center">
                                            <?php if ($item->status == 'pending'): ?>
                                                <span class="badge badge-warning">Pending</span>
                                            <?php elseif ($item->status == 'approved'): ?>
                                                <span class="badge badge-success">Disetujui</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Ditolak</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-info active detail-pengajuan" 
                                                    data-id="<?= $item->id_pengajuan ?>"
                                                    data-nama="<?= $item->nama ?>"
                                                    data-kamar="<?= $item->no_kamar ?>"
                                                    data-nominal="<?= number_format($item->nominal, 0, ',', '.') ?>"
                                                    data-metode="<?= $item->metode_pembayaran ?>"
                                                    data-keterangan="<?= $item->keterangan ?>"
                                                    data-bukti="<?= base_url('assets/uploads/bukti_transfer/'.$item->bukti_transfer) ?>"
                                                    data-toggle="tooltip" 
                                                    data-placement="top" 
                                                    title="Lihat Detail">
                                                <span class="fa fa-eye"></span>
                                            </button>
                                            
                                            <?php if ($item->status == 'pending'): ?>
                                                <a class="btn btn-sm btn-success active approve-pengajuan" 
                                                   href="<?= base_url('approve-pembayaran/'.$item->id_pengajuan) ?>"
                                                   data-toggle="tooltip" 
                                                   data-placement="top" 
                                                   title="Setujui">
                                                    <span class="fa fa-check"></span>
                                                </a>
                                                <a class="btn btn-sm btn-warning active reject-pengajuan" 
                                                   href="<?= base_url('reject-pembayaran/'.$item->id_pengajuan) ?>"
                                                   data-toggle="tooltip" 
                                                   data-placement="top" 
                                                   title="Tolak">
                                                    <span class="fa fa-times"></span>
                                                </a>
                                            <?php endif; ?>

                                            <a class="btn btn-sm btn-danger active hapus-pengajuan" 
                                               id="<?= $item->id_pengajuan ?>"
                                               data-toggle="tooltip" 
                                               data-placement="top" 
                                               title="Hapus">
                                                <span class="fa fa-trash"></span>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- END PAGE CONTENT-->