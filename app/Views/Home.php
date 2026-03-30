<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div id="heroCarousel" class="carousel slide mb-5 shadow-lg rounded-4 overflow-hidden border-0" data-bs-ride="carousel">
    <div class="carousel-indicators mb-3">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="d-flex align-items-center justify-content-center position-relative" style="height: 240px; background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: url('https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=2070&auto=format&fit=crop') center/cover; opacity: 0.2;"></div>
                <div class="text-center position-relative z-1 px-4">
                    <span class="badge bg-primary bg-gradient text-white mb-3 px-4 py-2 rounded-pill fw-bold shadow-sm" style="font-size: 0.7rem; letter-spacing: 2px;"><i class="fas fa-fire me-1"></i> MUSIM KOMPETISI BARU</span>
                    <h2 class="fw-black text-white text-uppercase mb-2 text-shadow-lg" style="letter-spacing: 2px;">Arena <span class="text-warning">Terbuka</span></h2>
                    <p class="text-light opacity-75 small mb-0">Siapkan skuad terbaikmu dan rebut gelar juara bulan ini!</p>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="d-flex align-items-center justify-content-center position-relative" style="height: 240px; background: linear-gradient(135deg, #2b0f0f, #0a0a0c, #1a1a1a);">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: url('https://images.unsplash.com/photo-1511512578047-dfb367046420?q=80&w=2071&auto=format&fit=crop') center/cover; opacity: 0.2;"></div>
                <div class="text-center position-relative z-1 px-4">
                    <span class="badge bg-warning bg-gradient text-dark mb-3 px-4 py-2 rounded-pill fw-bold shadow-sm" style="font-size: 0.7rem; letter-spacing: 2px;"><i class="fas fa-gift me-1"></i> PRIZEPOOL SULTAN</span>
                    <h2 class="fw-black text-white text-uppercase mb-2 text-shadow-lg" style="letter-spacing: 2px;">Jutaan <span class="text-warning">Rupiah</span></h2>
                    <p class="text-light opacity-75 small mb-0">Tunjukkan dominasimu, kalahkan lawan, dan bawa pulang hadiahnya.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bolder text-uppercase mb-0 ps-3 border-start border-4 border-primary"><i class="fas fa-gamepad text-primary me-2"></i>Turnamen <span class="text-light opacity-75">Tersedia</span></h5>
    <?php if(session()->get('user_role') == 'admin'): ?>
        <a href="<?= base_url('/admin/create'); ?>" class="btn btn-sm btn-primary bg-gradient rounded-pill fw-bold px-3 text-uppercase shadow-sm" style="font-size: 0.75rem;">
            <i class="fas fa-plus me-1"></i> Buat Baru
        </a>
    <?php endif; ?>
</div>

<div class="row">
    <?php if(empty($turnamen)): ?>
        <div class="col-12 text-center py-5">
            <div class="bg-dark d-inline-block p-4 rounded-circle mb-3 border border-secondary shadow-sm">
                <i class="fas fa-folder-open fa-2x text-secondary opacity-50"></i>
            </div>
            <h6 class="text-light opacity-50">Belum ada turnamen yang tersedia saat ini.</h6>
        </div>
    <?php else: ?>
        <?php foreach($turnamen as $t): ?>
            <?php 
                $quota = $t['quota'] > 0 ? $t['quota'] : 1;
                $percent = ($t['approved_teams'] / $quota) * 100;
                $isFull = $t['approved_teams'] >= $t['quota'];
                $bgImage = !empty($t['poster']) ? base_url('uploads/posters/' . $t['poster']) : 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=800&auto=format&fit=crop';
            ?>
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                
                <div class="card bg-black border-0 shadow-lg rounded-4 h-100 overflow-hidden card-hover position-relative">
                    
                    <div class="position-relative overflow-hidden bg-dark" style="height: 280px;">
                        
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: url('<?= $bgImage; ?>') center/cover no-repeat; filter: blur(15px) brightness(0.4); transform: scale(1.1);"></div>
                        
                        <div class="position-relative w-100 h-100 image-zoom" style="background: url('<?= $bgImage; ?>') center/contain no-repeat; z-index: 1;"></div>
                        
                        <div class="position-absolute bottom-0 start-0 w-100" style="height: 40px; background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,1) 100%); z-index: 2;"></div>

                        <div class="position-absolute top-0 end-0 p-3 z-3">
                            <?php if($t['status'] == 'completed'): ?>
                                <span class="badge bg-danger shadow-sm px-3 py-2 rounded-pill"><i class="fas fa-flag-checkered me-1"></i> SELESAI</span>
                            <?php elseif($t['status'] == 'ongoing'): ?>
                                <span class="badge bg-warning text-dark shadow-sm px-3 py-2 rounded-pill"><i class="fas fa-play-circle me-1"></i> BERJALAN</span>
                            <?php else: ?>
                                <span class="badge bg-success shadow-sm px-3 py-2 rounded-pill"><i class="fas fa-circle text-white small me-1" style="font-size: 6px; vertical-align: middle;"></i> DIBUKA</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card-body p-4 d-flex flex-column bg-black position-relative z-3 border border-top-0 border-secondary border-opacity-25 rounded-bottom-4 mt-n2">
                        
                        <?php if(!empty($t['prize'])): ?>
                            <?php $prizeDisplay = is_numeric($t['prize']) ? 'Rp ' . number_format($t['prize'], 0, ',', '.') : $t['prize']; ?>
                            <div class="d-inline-flex align-items-center bg-dark border border-warning border-opacity-75 rounded-pill px-3 py-2 shadow-lg position-absolute" style="top: -25px; left: 20px;">
                                <i class="fas fa-gift text-warning me-2"></i>
                                <span class="text-warning fw-black" style="font-size: 0.9rem; letter-spacing: 0.5px;"><?= esc($prizeDisplay); ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="<?= !empty($t['prize']) ? 'mt-2' : '' ?>"></div>

                        <h4 class="fw-black text-white text-uppercase mb-4 text-truncate" style="letter-spacing: 0.5px;"><?= esc($t['name']); ?></h4>
                        
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-end mb-2">
                                <span class="text-light opacity-50" style="font-size: 0.7rem; font-weight: 600; letter-spacing: 1px;">SLOT TERISI</span>
                                <span class="fw-black <?= $isFull ? 'text-danger' : 'text-primary'; ?>" style="font-size: 0.8rem;"><?= $t['approved_teams']; ?> / <?= $t['quota']; ?></span>
                            </div>
                            <div class="progress bg-dark rounded-pill" style="height: 4px;">
                                <div class="progress-bar <?= $isFull ? 'bg-danger' : 'bg-primary bg-gradient'; ?>" role="progressbar" style="width: <?= $percent; ?>%;"></div>
                            </div>
                        </div>

                        <div class="mt-auto position-relative">
                            <?php if($t['status'] == 'completed'): ?>
                                <a href="<?= base_url('/turnamen/detail/' . $t['id']); ?>" class="btn btn-outline-secondary w-100 fw-bold rounded-3 py-2 stretched-link text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">
                                    Lihat Hasil
                                </a>
                            <?php elseif($t['status'] == 'ongoing'): ?>
                                <a href="<?= base_url('/turnamen/detail/' . $t['id']); ?>" class="btn btn-outline-warning w-100 fw-bold rounded-3 py-2 stretched-link text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">
                                    Pantau
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('/turnamen/detail/' . $t['id']); ?>" class="btn btn-primary bg-gradient w-100 fw-bold rounded-3 shadow-lg py-2 text-uppercase stretched-link d-flex justify-content-center align-items-center" style="font-size: 0.85rem; letter-spacing: 1px;">
                                    Lihat Detail <i class="fas fa-chevron-right ms-2" style="font-size: 0.7rem;"></i>
                                </a>
                            <?php endif; ?>
                        </div>

                        <?php if(session()->get('user_role') == 'admin'): ?>
                            <div class="mt-3 pt-3 border-top border-secondary border-opacity-25 d-flex justify-content-between align-items-center position-relative" style="z-index: 3;">
                                <span class="text-danger opacity-75 fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;"><i class="fas fa-shield-alt me-1"></i> ADMIN</span>
                                <div class="d-flex gap-3">
                                    <a href="<?= base_url('/admin/edit/' . $t['id']); ?>" class="text-warning text-decoration-none fw-bold" style="font-size: 0.75rem;"><i class="fas fa-edit me-1"></i> Edit</a>
                                    <a href="<?= base_url('/admin/tim/' . $t['id']); ?>" class="text-info text-decoration-none fw-bold" style="font-size: 0.75rem;"><i class="fas fa-users-cog me-1"></i> Kelola</a>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
    .text-shadow-lg { text-shadow: 0px 4px 10px rgba(0,0,0,0.9); }
    .card-hover { transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1), box-shadow 0.3s ease; }
    .card-hover:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.9) !important; }
    .image-zoom { transition: transform 0.5s ease; }
    .card-hover:hover .image-zoom { transform: scale(1.05); }
</style>

<?= $this->endSection(); ?>