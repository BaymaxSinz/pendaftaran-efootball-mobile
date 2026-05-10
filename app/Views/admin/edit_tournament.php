<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert bg-danger text-white alert-dismissible fade show py-3 border-0 shadow-sm rounded-4 mb-4" role="alert">
                    <i class="fas fa-exclamation-triangle me-2 fs-5 align-middle"></i> 
                    <span class="align-middle fw-bold"><?= session()->getFlashdata('error'); ?></span>
                    <button type="button" class="btn-close btn-close-white px-2 py-3" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card bg-black text-white border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="bg-gradient bg-warning" style="height: 4px; width: 100%;"></div>
                
                <div class="card-body p-4 p-sm-5">
                    
                    <div class="text-center mb-4">
                        <div class="d-inline-block bg-dark p-3 rounded-circle mb-3 border border-secondary shadow-sm">
                            <i class="fas fa-edit fa-2x text-warning"></i>
                        </div>
                        <h4 class="card-title fw-bolder text-uppercase mb-1" style="letter-spacing: 1px;">Edit <span class="text-warning">Turnamen</span></h4>
                    </div>
                    
                    <form action="<?= base_url('/admin/update/' . $turnamen['id']); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        
                        <div class="mb-4">
                            <label class="form-label text-light opacity-75 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Nama Turnamen</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fas fa-trophy"></i></span>
                                <input type="text" class="form-control bg-dark text-white border-secondary" name="name" value="<?= esc($turnamen['name']); ?>" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-light opacity-75 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Poster Turnamen</label>
                            
                            <?php if(!empty($turnamen['poster'])): ?>
                                <div class="mb-3 text-center bg-dark p-2 rounded-3 border border-secondary">
                                    <img src="<?= base_url('uploads/posters/' . $turnamen['poster']); ?>" alt="Poster" class="img-fluid rounded-3" style="max-height: 250px; object-fit: contain;">
                                </div>
                            <?php endif; ?>
                            
                            <input type="hidden" name="old_poster" value="<?= esc($turnamen['poster'] ?? ''); ?>">
                            <input class="form-control bg-dark text-white border-secondary" type="file" name="poster" accept="image/*">
                            <div class="form-text text-info small mt-1" style="font-size: 0.75rem;"><i class="fas fa-mobile-alt me-1"></i> Rekomendasi rasio 9:16 (Ukuran IG Story / 1080x1920).</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-light opacity-75 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Total Hadiah (Prizepool)</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-dark border-secondary text-warning"><i class="fas fa-gift"></i></span>
                                <input type="text" class="form-control bg-dark text-white border-secondary fw-bold text-warning" name="prize" value="<?= esc($turnamen['prize'] ?? ''); ?>" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-light opacity-75 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Deskripsi Singkat</label>
                            <textarea class="form-control bg-dark text-white border-secondary rounded-3" name="description" rows="3" required><?= esc($turnamen['description']); ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-light opacity-75 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Rules & Regulation Detail</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fas fa-scroll"></i></span>
                                <textarea class="form-control bg-dark text-white border-secondary rounded-end" name="rules" rows="4" required><?= esc($turnamen['rules'] ?? ''); ?></textarea>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label text-light opacity-75 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Total Kuota</label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-dark border-secondary text-info"><i class="fas fa-layer-group"></i></span>
                                    <input type="number" name="quota" class="form-control bg-dark text-white border-secondary" value="<?= esc($turnamen['quota'] ?? '32'); ?>" min="2" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label text-light opacity-75 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Slot / Akun</label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fas fa-users-cog"></i></span>
                                    <input type="number" name="max_slots" class="form-control bg-dark text-white border-secondary" value="<?= esc($turnamen['max_slots'] ?? '1'); ?>" min="1" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-light fw-bold">Format Turnamen</label>
                            <select name="format" class="form-select bg-dark text-white border-secondary" required>
                                <option value="bracket">Sistem Gugur (Bracket)</option>
                                <option value="league">Sistem Liga (Klasemen)</option>
                            </select>
                            <div class="form-text text-secondary">Sistem gugur = yang kalah tersingkir. Sistem liga = semua saling bertemu (ada poin & klasemen).</div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label text-light opacity-75 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Status</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fas fa-toggle-on"></i></span>
                                <select name="status" class="form-select bg-dark text-white border-secondary fw-bold">
                                    <option value="open" class="text-success" <?= $turnamen['status'] == 'open' ? 'selected' : ''; ?>>DIBUKA</option>
                                    <option value="ongoing" class="text-warning" <?= $turnamen['status'] == 'ongoing' ? 'selected' : ''; ?>>BERJALAN</option>
                                    <option value="completed" class="text-danger" <?= $turnamen['status'] == 'completed' ? 'selected' : ''; ?>>SELESAI</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning bg-gradient w-100 fw-bold py-3 rounded-pill shadow mb-4 text-dark text-uppercase" style="letter-spacing: 1px;">
                            <i class="fas fa-save me-2"></i> Simpan Perubahan
                        </button>
                    </form>

                    <div class="card bg-black border border-danger shadow-sm rounded-4 mb-4">
                        <div class="card-body p-3 text-center">
                            <h6 class="fw-bold text-uppercase text-danger mb-2" style="font-size: 0.85rem;"><i class="fas fa-exclamation-triangle me-1"></i> Zona Berbahaya</h6>
                            <a href="<?= base_url('/admin/delete/' . $turnamen['id']); ?>" class="btn btn-outline-danger btn-sm w-100 fw-bold rounded-pill" onclick="return confirm('PERINGATAN! Apakah kamu yakin ingin menghapus turnamen ini? Semua pendaftar akan hilang permanen!');">
                                <i class="fas fa-trash-alt me-1"></i> Hapus Turnamen
                            </a>
                        </div>
                    </div>

                    <div class="text-center border-top border-secondary pt-3">
                        <a href="<?= base_url('/'); ?>" class="text-light text-decoration-none opacity-50 small">
                            <i class="fas fa-arrow-left me-1"></i> Batal & Kembali
                        </a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?= $this->endSection(); ?>