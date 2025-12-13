            <!-- START PAGE CONTENT -->
            <div class="fade-in-up">
                <div class="row">
                    <div class="col-lg-12" style="padding: 0 0 15px 0;">
                            <!--Carousel Wrapper-->
                            <div id="slide-carousel" class="carousel slide carousel-fade" data-ride="carousel">
                                <!--Slides-->
                                <div class="carousel-inner" role="listbox">
                                    <div class="carousel-caption">
                                        <h3 class="h3-responsive big-brand1">SELAMAT DATANG</h3>
                                        <p class="big-brand2">SKY KOST</p>
                                    </div>
                                    <div class="carousel-item active">
                                        <div class="view">
                                            <img class="d-block" src="<?= base_url('assets/img/kos/1.jpg')  ?>"
                                            alt="First slide" size="40x40">
                                            <div class="mask rgba-black-light"></div>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class="view">
                                            <img class="d-block" src="<?= base_url('assets/img/kos/2.jpg')  ?>"
                                            alt="First slide" size="40x40">
                                            <div class="mask rgba-black-light"></div>
                                        </div>
                                    </div>
                                </div>
                                <!--/.Slides-->
                                <a class="carousel-control-next" href="#slide-carousel" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                            <!--/.Carousel Wrapper-->
                    </div>
                </div>
            </div>
            <div class="page-content fade-in-up">
                
             <!-- STATISTIK CARDS -->
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="ibox bg-success color-white widget-stat">
                            <div class="ibox-body">
                                <h2 class="m-b-5 font-strong">Rp<?= number_format($pendapatan_bulan_ini, 0, ',', '.') ?></h2>
                                <div class="m-b-5">PENDAPATAN BULAN INI</div>
                                <i class="fa fa-money widget-stat-icon"></i>
                                <div><small><?= date('F Y') ?></small></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="ibox bg-info color-white widget-stat">
                            <div class="ibox-body">
                                <h2 class="m-b-5 font-strong">Rp<?= number_format($pendapatan_tahun_ini, 0, ',', '.') ?></h2>
                                <div class="m-b-5">PENDAPATAN TAHUN INI</div>
                                <i class="ti-bar-chart widget-stat-icon"></i>
                                <div><small><?= date('Y') ?></small></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="ibox bg-warning color-white widget-stat">
                            <div class="ibox-body">
                                <h2 class="m-b-5 font-strong">Rp<?= number_format($total_piutang, 0, ',', '.') ?></h2>
                                <div class="m-b-5">TOTAL PIUTANG</div>
                                <i class="ti-receipt widget-stat-icon"></i>
                                <div><small>Belum Terbayar</small></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="ibox bg-danger color-white widget-stat">
                            <div class="ibox-body">
                                <h2 class="m-b-5 font-strong"><?= count($pengajuan_pending) ?></h2>
                                <div class="m-b-5">PENGAJUAN PENDING</div>
                                <i class="ti-alarm-clock widget-stat-icon"></i>
                                <div>
                                    <a href="<?= base_url('konfirmasi-pembayaran') ?>" class="text-white">
                                        <small><u>Lihat Detail</u></small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STATISTIK KAMAR & INFO -->
                <div class="row">
                    <div class="col-lg-8">
                        <div class="ibox">
                            <div class="ibox-head">
                                <div class="ibox-title">Grafik Pendapatan 12 Bulan Terakhir</div>
                                <div class="ibox-tools">
                                    <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
                                </div>
                            </div>
                            <div class="ibox-body">
                                <div style="height: 350px;">
                                    <canvas id="grafikPendapatan"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- STATUS KAMAR -->
                        <div class="ibox">
                            <div class="ibox-head">
                                <div class="ibox-title">Status Kamar</div>
                            </div>
                            <div class="ibox-body">
                                <div style="height: 200px; position: relative;">
                                    <canvas id="grafikKamar"></canvas>
                                </div>
                                <div class="mt-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span><i class="fa fa-circle text-success"></i> Terisi</span>
                                        <strong><?= $kamar_terisi ?> Kamar</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span><i class="fa fa-circle text-secondary"></i> Kosong</span>
                                        <strong><?= $total_kamar - $kamar_terisi ?> Kamar</strong>
                                    </div>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-success" 
                                             role="progressbar" 
                                             style="width: <?= $total_kamar > 0 ? ($kamar_terisi / $total_kamar * 100) : 0 ?>%">
                                        </div>
                                    </div>
                                    <small class="text-muted">Tingkat Hunian: <?= $total_kamar > 0 ? number_format($kamar_terisi / $total_kamar * 100, 1) : 0 ?>%</small>
                                </div>
                            </div>
                        </div>

                        <!-- INFO PENGHUNI -->
                        <div class="ibox">
                            <div class="ibox-head">
                                <div class="ibox-title">Info Penghuni</div>
                            </div>
                            <div class="ibox-body">
                                <div class="flexbox mb-3">
                                    <div>
                                        <h3 class="m-0"><?= $total_penghuni ?></h3>
                                        <div>Total Penghuni Aktif</div>
                                    </div>
                                    <div class="text-right">
                                        <i class="fa fa-users fa-3x text-primary"></i>
                                    </div>
                                </div>
                                <a href="<?= base_url('daftar-penghuni') ?>" class="btn btn-primary btn-block">
                                    Lihat Daftar Penghuni
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- PEMBAYARAN TERBARU -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox">
                            <div class="ibox-head">
                                <div class="ibox-title">Pembayaran Terbaru</div>
                                <div class="ibox-tools">
                                    <a href="<?= base_url('riwayat-pembayaran') ?>" class="btn btn-sm btn-primary">
                                        Lihat Semua
                                    </a>
                                </div>
                            </div>
                            <div class="ibox-body">
                                <?php if (empty($pembayaran_terbaru)): ?>
                                    <div class="text-center py-5">
                                        <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Belum ada pembayaran</p>
                                    </div>
                                <?php else: ?>
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>No. Kamar</th>
                                                <th>Nama</th>
                                                <th>Tanggal</th>
                                                <th class="text-right">Nominal</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($pembayaran_terbaru as $bayar): ?>
                                            <tr>
                                                <td><strong><?= $bayar->no_kamar ?></strong></td>
                                                <td><?= $bayar->nama ?></td>
                                                <td><i class="fa fa-calendar-o"></i> <?= $bayar->tgl_bayar ?></td>
                                                <td class="text-right">
                                                    <strong class="text-success">Rp<?= number_format($bayar->bayar, 0, ',', '.') ?></strong>
                                                </td>
                                                <td><?= $bayar->ket ?: '-' ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- INFO SISTEM -->
                <div class="row">
                    <div class="col-lg-4">
                        <div class="ibox">
                            <div class="ibox-body text-center" style="padding: 25px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 10px;">
                                <i class="fa fa-calendar-check-o fa-3x mb-3" style="opacity: 0.9;"></i>
                                <h5 class="font-strong" style="color: white;">Sistem Pembayaran Bulanan</h5>
                                <p style="color: rgba(255,255,255,0.9); margin: 0; font-size: 14px;">
                                    Pembayaran dihitung per bulan. Penghuni ditagih sesuai harga kamar bulanan.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="ibox">
                            <div class="ibox-body text-center" style="padding: 25px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 10px;">
                                <i class="fa fa-refresh fa-3x mb-3" style="opacity: 0.9;"></i>
                                <h5 class="font-strong" style="color: white;">Auto Update Tagihan</h5>
                                <p style="color: rgba(255,255,255,0.9); margin: 0; font-size: 14px;">
                                    Piutang otomatis terupdate sesuai durasi huni dan pembayaran yang masuk.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="ibox">
                            <div class="ibox-body text-center" style="padding: 25px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border-radius: 10px;">
                                <i class="fa fa-mobile fa-3x mb-3" style="opacity: 0.9;"></i>
                                <h5 class="font-strong" style="color: white;">Pembayaran Online</h5>
                                <p style="color: rgba(255,255,255,0.9); margin: 0; font-size: 14px;">
                                    Penghuni dapat upload bukti pembayaran via QRIS, GoPay, DANA, dan Transfer Bank.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- END PAGE CONTENT -->

            <!-- SCRIPT UNTUK GRAFIK -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
            <script>
                // Data untuk grafik
                var dataPendapatan = <?= json_encode($grafik_pendapatan) ?>;
                var labels = dataPendapatan.map(item => item.bulan);
                var values = dataPendapatan.map(item => parseInt(item.total));

                // Grafik Pendapatan 12 Bulan
                var ctxPendapatan = document.getElementById('grafikPendapatan').getContext('2d');
                var grafikPendapatan = new Chart(ctxPendapatan, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Pendapatan (Rp)',
                            data: values,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2,
                            borderRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Rp' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        if (value >= 1000000) {
                                            return 'Rp' + (value / 1000000).toFixed(1) + 'jt';
                                        }
                                        return 'Rp' + (value / 1000).toFixed(0) + 'rb';
                                    }
                                }
                            }
                        }
                    }
                });

                // Grafik Status Kamar (Donut)
                var ctxKamar = document.getElementById('grafikKamar').getContext('2d');
                var grafikKamar = new Chart(ctxKamar, {
                    type: 'doughnut',
                    data: {
                        labels: ['Terisi', 'Kosong'],
                        datasets: [{
                            data: [<?= $kamar_terisi ?>, <?= $total_kamar - $kamar_terisi ?>],
                            backgroundColor: ['#28a745', '#6c757d'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });

                // Filter Tahun - Load Data via AJAX
                $(document).ready(function(){
                    loadDataPendapatan(<?= date('Y') ?>);

                    $('#filterTahun').change(function(){
                        var tahun = $(this).val();
                        loadDataPendapatan(tahun);
                    });
                });

                function loadDataPendapatan(tahun) {
                    // Show loading
                    $('#bodyTabelPendapatan').html('<tr><td colspan="5" class="text-center"><div class="spinner-border text-primary" role="status"></div></td></tr>');
                    
                    $.ajax({
                        url: '<?= base_url("get-pendapatan-tahunan") ?>',
                        method: 'POST',
                        data: { tahun: tahun },
                        dataType: 'json',
                        success: function(response) {
                            var html = '';
                            var totalPendapatan = 0;
                            var totalTransaksi = 0;
                            var prevTotal = 0;

                            response.forEach(function(item, index) {
                                totalPendapatan += parseInt(item.total);
                                totalTransaksi += parseInt(item.jumlah_transaksi);
                                
                                var rataRata = item.jumlah_transaksi > 0 ? 
                                    (item.total / item.jumlah_transaksi) : 0;

                                var trend = '';
                                if (index > 0 && prevTotal > 0) {
                                    var persentase = ((item.total - prevTotal) / prevTotal * 100).toFixed(1);
                                    if (persentase > 0) {
                                        trend = '<span class="text-success"><i class="fa fa-arrow-up"></i> ' + 
                                                persentase + '%</span>';
                                    } else if (persentase < 0) {
                                        trend = '<span class="text-danger"><i class="fa fa-arrow-down"></i> ' + 
                                                Math.abs(persentase) + '%</span>';
                                    } else {
                                        trend = '<span class="text-muted">-</span>';
                                    }
                                } else {
                                    trend = '<span class="text-muted">-</span>';
                                }

                                html += '<tr>' +
                                    '<td><strong>' + item.bulan + '</strong></td>' +
                                    '<td class="text-right"><strong class="text-primary">Rp' + parseInt(item.total).toLocaleString('id-ID') + '</strong></td>' +
                                    '<td class="text-center"><span class="badge badge-info">' + item.jumlah_transaksi + '</span></td>' +
                                    '<td class="text-center">Rp' + parseInt(rataRata).toLocaleString('id-ID') + '</td>' +
                                    '<td class="text-center">' + trend + '</td>' +
                                    '</tr>';

                                prevTotal = item.total;
                            });

                            if (html === '') {
                                html = '<tr><td colspan="5" class="text-center text-muted">Tidak ada data untuk tahun ini</td></tr>';
                            }

                            $('#bodyTabelPendapatan').html(html);
                            $('#totalPendapatan').html('<strong class="text-success">Rp' + totalPendapatan.toLocaleString('id-ID') + '</strong>');
                            $('#totalTransaksi').html('<span class="badge badge-primary">' + totalTransaksi + '</span>');
                            
                            var avgTotal = totalTransaksi > 0 ? (totalPendapatan / totalTransaksi) : 0;
                            $('#rataRata').html('Rp' + parseInt(avgTotal).toLocaleString('id-ID'));
                        },
                        error: function() {
                            $('#bodyTabelPendapatan').html('<tr><td colspan="5" class="text-center text-danger">Gagal memuat data</td></tr>');
                        }
                    });
                }
            </script>

            <style>
                /* Dashboard Animations */
                .widget-stat {
                    animation: fadeInUp 0.5s ease;
                }

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

                /* Table hover effect */
                #tabelPendapatan tbody tr {
                    transition: all 0.3s ease;
                }

                #tabelPendapatan tbody tr:hover {
                    background-color: #f0f8ff !important;
                    transform: scale(1.01);
                    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                }

                /* Spinner */
                .spinner-border {
                    width: 2rem;
                    height: 2rem;
                }
            </style>
            <!-- END PAGE CONTENT-->