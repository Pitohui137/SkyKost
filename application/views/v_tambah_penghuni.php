<!-- START PAGE CONTENT-->
            <div class="page-content fade-in-up">
                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title">Tambah Penghuni</div>
                        <div class="ibox-tools">
                            <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
                        </div>
                    </div>
                    <div class="ibox-body">
                        <form class="form-horizontal" action="<?= base_url('aksi-tambah-penghuni') ?>" method="post">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">No. Kamar</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" name="no_kamar" value="<?= $kamar->no_kamar ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Harga Kamar per Bulan</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" name="harga" value="Rp<?= number_format($kamar->harga, 0, ',', '.') ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Nama Lengkap</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" name="nama" placeholder="Nama Lengkap Penghuni" maxlength="200" oninput="this.value = this.value.replace(/[^a-z A-Z ' .]/g, '');" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">No. KTP</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" name="no_ktp" placeholder="No. KTP Penghuni" maxlength="50" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Alamat Asal</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" name="alamat" placeholder="Alamat Asal Penghuni" maxlength="200" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">No. Telp/HP</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" name="no" placeholder="No. Telp/HP Penghuni" maxlength="30" oninput="this.value = this.value.replace(/[^0-9 +]/g, '');" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Password</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="password" name="password" placeholder="Password" maxlength="200" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Tanggal Mulai Huni</label>
                                <div class="col-sm-9">
                                    <div class="input-group date" id="datepicker">
                                        <input class="form-control" type="text" name="tgl_masuk" id="tgl_masuk" placeholder="Pilih Tanggal Masuk" autocomplete="off" required>
                                        <span class="input-group-addon bg-white"><i class="fa fa-calendar"></i></span>
                                    </div>
                                    <small class="text-muted">Penghuni akan dikenakan harga_per_bulan per bulan sejak tanggal ini</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Durasi Sewa (Bulan)</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="number" name="durasi_bulan" id="durasi_bulan" placeholder="Masukkan durasi sewa dalam bulan" min="1" max="60" value="12" required>
                                    <small class="text-muted">Misal: 1, 3, 6, 12 bulan, dst.</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Total harga_per_bulan</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" id="total_harga_per_bulan_display" value="Rp<?= number_format($kamar->harga * 12, 0, ',', '.') ?>" readonly>
                                    <input type="hidden" name="harga_per_bulan" id="harga_per_bulan" value="<?= $kamar->harga ?>">
                                    <small class="text-muted">Total harga_per_bulan untuk durasi sewa yang dipilih</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-9 ml-sm-auto">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                    <button class="btn btn-danger" type="button" onclick="window.history.back()">Batal</button>
                                    <button class="btn btn-outline-default" type="reset" value="Reset">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- END PAGE CONTENT-->

            <script>
                $(document).ready(function(){
                    // Bootstrap datepicker
                    $("#datepicker").datepicker({
                        todayBtn: "linked",
                        keyboardNavigation: false,
                        forceParse: false,
                        autoclose: true,
                        format: "dd-mm-yyyy"
                    });

                    // Form Masks
                    $("#tgl_masuk").mask("99-99-9999", {
                        placeholder: "dd-mm-yyyy"
                    });

                    // Hitung total harga_per_bulan otomatis
                    var hargaPerBulan = <?= $kamar->harga ?>;
                    
                    $('#durasi_bulan').on('input', function(){
                        var durasi = parseInt($(this).val()) || 0;
                        var total = hargaPerBulan * durasi;
                        
                        $('#total_harga_per_bulan_display').val('Rp' + total.toLocaleString('id-ID'));
                    });

                    // Trigger kalkulasi awal
                    $('#durasi_bulan').trigger('input');
                });
            </script>