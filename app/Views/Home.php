<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div id="heroCarousel" class="carousel slide mb-5 shadow-lg rounded-4 overflow-hidden" data-bs-ride="carousel">
    
    <div class="carousel-indicators mb-2">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
    </div>

    <div class="carousel-inner">
        
        <div class="carousel-item active">
            <div class="d-flex align-items-center justify-content-center position-relative" style="height: 220px; background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: url('https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=2070&auto=format&fit=crop') center/cover; opacity: 0.2;"></div>
                
                <div class="text-center position-relative z-1 px-4">
                    <span class="badge bg-warning text-dark mb-3 px-3 py-2 rounded-pill fw-bold shadow-sm" style="font-size: 0.7rem; letter-spacing: 1px;">MUSIM BARU TELAH TIBA</span>
                    <h3 class="fw-black text-white text-uppercase mb-2" style="letter-spacing: 1.5px;">Arena <span class="text-primary">Terbuka</span></h3>
                    <p class="text-light opacity-75 small mb-0">Siapkan skuad terbaikmu dan rebut gelar juara bulan ini!</p>
                </div>
            </div>
        </div>

        <div class="carousel-item">
            <div class="d-flex align-items-center justify-content-center position-relative" style="height: 220px; background: linear-gradient(135deg, #2b0f0f, #0a0a0c, #1a1a1a);">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: url('https://images.unsplash.com/photo-1511512578047-dfb367046420?q=80&w=2071&auto=format&fit=crop') center/cover; opacity: 0.2;"></div>
                
                <div class="text-center position-relative z-1 px-4">
                    <span class="badge bg-danger text-white mb-3 px-3 py-2 rounded-pill fw-bold shadow-sm" style="font-size: 0.7rem; letter-spacing: 1px;">TOTAL HADIAH</span>
                    <h3 class="fw-black text-white text-uppercase mb-2" style="letter-spacing: 1.5px;">Jutaan <span class="text-warning">Rupiah</span></h3>
                    <p class="text-light opacity-75 small mb-0">Tunjukkan dominasimu, kalahkan lawan, dan bawa pulang hadiahnya.</p>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bolder text-uppercase mb-0 border-start border-4 border-warning ps-2"><i class="fas fa-trophy text-warning me-2"></i>Daftar Turnamen</h5>
    
    <?php if(session()->get('user_role') == 'admin'): ?>
        <a href="<?= base_url('/admin/create'); ?>" class="btn btn-sm btn-primary rounded-pill fw-bold shadow-sm px-3 text-uppercase" style="letter-spacing: 0.5px;">
            <i class="fas fa-plus-circle me-1"></i> Buat Baru
        </a>
    <?php endif; ?>
</div>

<div class="row g-4">
    <?php if(empty($turnamen)): ?>
        <div class="col-12 text-center py-5">
            <i class="fas fa-folder-open fa-3x text-secondary mb-3 opacity-50"></i>
            <h6 class="text-light opacity-50">Belum ada turnamen yang tersedia saat ini.</h6>
        </div>
    <?php else: ?>
        <?php foreach($turnamen as $t): ?>
            
            <?php 
                // Hitung persentase slot berdasarkan tim yang HANYA DISETUJUI
                $quota = $t['quota'] > 0 ? $t['quota'] : 1;
                $percent = ($t['approved_teams'] / $quota) * 100;
                $isFull = $t['approved_teams'] >= $t['quota'];
            ?>

            <div class="col-md-6 col-lg-4">
                <div class="card bg-dark text-white border-secondary shadow-lg rounded-4 h-100 overflow-hidden transition-hover">
                    <div class="card-body p-4 d-flex flex-column">
                        
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-secondary bg-opacity-25 p-2 rounded-3">
                                <i class="fas fa-futbol fa-2x text-light opacity-75"></i>
                            </div>
                            <?php if($t['status'] == 'completed'): ?>
                                <span class="badge bg-danger bg-gradient px-3 py-2 rounded-pill"><i class="fas fa-flag-checkered me-1"></i> SELESAI</span>
                            <?php elseif($t['status'] == 'ongoing'): ?>
                                <span class="badge bg-warning bg-gradient text-dark px-3 py-2 rounded-pill"><i class="fas fa-play-circle me-1"></i> BERJALAN</span>
                            <?php else: ?>
                                <span class="badge bg-success bg-gradient px-3 py-2 rounded-pill"><i class="fas fa-door-open me-1"></i> DIBUKA</span>
                            <?php endif; ?>
                        </div>

                        <h5 class="card-title fw-bold text-uppercase text-warning mb-2"><?= esc($t['name']); ?></h5>
                        <p class="card-text text-light opacity-75 small mb-4 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            <?= esc($t['description']); ?>
                        </p>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between text-light small fw-bold mb-1" style="font-size: 0.75rem;">
                                <span><i class="fas fa-users me-1 text-info"></i> Terisi: <?= $t['approved_teams']; ?>/<?= $t['quota']; ?> Tim (Disetujui)</span>
                                <span class="<?= $isFull ? 'text-danger' : 'text-info'; ?>"><?= round($percent); ?>%</span>
                            </div>
                            <div class="progress bg-black border border-secondary" style="height: 8px;">
                                <div class="progress-bar <?= $isFull ? 'bg-danger' : 'bg-info progress-bar-striped progress-bar-animated'; ?>" role="progressbar" style="width: <?= $percent; ?>%;"></div>
                            </div>
                        </div>

                        <div class="mt-auto">
                            <?php if($t['status'] == 'completed'): ?>
                                <button class="btn btn-secondary w-100 fw-bold rounded-pill shadow-sm" disabled><i class="fas fa-ban me-2"></i>Turnamen Selesai</button>
                            <?php elseif($t['status'] == 'ongoing'): ?>
                                <button class="btn btn-warning text-dark w-100 fw-bold rounded-pill shadow-sm" disabled><i class="fas fa-lock me-2"></i>Pendaftaran Ditutup</button>
                            <?php elseif($isFull): ?>
                                <button class="btn btn-danger bg-gradient w-100 fw-bold rounded-pill shadow-sm opacity-75" disabled><i class="fas fa-times-circle me-2"></i>KUOTA PENUH</button>
                            <?php else: ?>
                                <a href="<?= base_url('/turnamen/daftar/' . $t['id']); ?>" class="btn btn-primary bg-gradient w-100 fw-bold rounded-pill shadow-sm text-uppercase" style="letter-spacing: 1px;">
                                    <i class="fas fa-edit me-2"></i> Daftar Sekarang
                                </a>
                            <?php endif; ?>
                        </div>

                        <?php if(session()->get('user_role') == 'admin'): ?>
                            <div class="mt-3 pt-3 border-top border-secondary row g-2">
                                <div class="col-6">
                                    <a href="<?= base_url('/admin/edit/' . $t['id']); ?>" class="btn btn-outline-warning btn-sm w-100 rounded-pill fw-bold"><i class="fas fa-edit me-1"></i> Edit</a>
                                </div>
                                <div class="col-6">
                                    <a href="<?= base_url('/admin/tim/' . $t['id']); ?>" class="btn btn-outline-info btn-sm w-100 rounded-pill fw-bold"><i class="fas fa-users-cog me-1"></i> Kelola</a>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?>