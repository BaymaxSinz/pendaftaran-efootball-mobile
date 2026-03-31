<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container py-4">
    
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert bg-success text-white border-0 shadow-sm rounded-4 mb-4"><i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success'); ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert bg-danger text-white border-0 shadow-sm rounded-4 mb-4"><i class="fas fa-exclamation-triangle me-2"></i> <?= session()->getFlashdata('error'); ?></div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-black text-uppercase text-white mb-0" style="letter-spacing: 0.5px;">Kelola <span class="text-warning">Skor & Bagan</span></h5>
            <span class="text-light opacity-50 small"><?= esc($turnamen['name']); ?></span>
        </div>
        <a href="<?= base_url('/'); ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3"><i class="fas fa-arrow-left me-1"></i> Beranda</a>
    </div>

    <?php if($turnamen['status'] == 'completed'): ?>
        <div class="card bg-black border border-warning shadow-lg rounded-4 mb-4 overflow-hidden position-relative">
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle, rgba(255,193,7,0.15) 0%, rgba(0,0,0,0) 70%);"></div>
            
            <div class="card-body p-5 text-center position-relative z-1">
                <i class="fas fa-trophy fa-4x text-warning mb-3" style="filter: drop-shadow(0 0 15px rgba(255,193,7,0.8));"></i>
                <h6 class="text-light opacity-75 fw-bold text-uppercase mb-1" style="letter-spacing: 2px;">SANG JUARA</h6>
                
                <h1 class="fw-black text-warning text-uppercase mb-3" style="letter-spacing: 1px; text-shadow: 0 4px 10px rgba(0,0,0,0.9);">
                    <?= !empty($champion) ? esc($champion['team_name']) : 'TIDAK DIKETAHUI'; ?>
                </h1>
                
                <?php if(!empty($champion)): ?>
                    <span class="badge bg-warning text-dark fw-bold rounded-pill px-4 py-2 shadow-sm fs-6">
                        <i class="fas fa-crown me-1"></i> Manajer: <?= esc($champion['manager_name']); ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    <?php elseif($all_completed): ?>
        <div class="card bg-black border border-success shadow-lg rounded-4 mb-4">
            <div class="card-body p-4 text-center">
                <h6 class="text-success fw-bold mb-2"><i class="fas fa-check-double me-2"></i> Semua Pertandingan Babak <?= $current_round; ?> Selesai!</h6>
                <p class="text-light opacity-75 small mb-3">Klik tombol di bawah untuk menyusun jadwal bagi tim yang menang.</p>
                <a href="<?= base_url('admin/generate-next-round/' . $turnamen['id'] . '/' . $current_round); ?>" class="btn btn-success bg-gradient fw-bold rounded-pill px-4" onclick="return confirm('Buat jadwal Babak selanjutnya?');">
                    <i class="fas fa-layer-group me-1"></i> Generate Babak <?= $current_round + 1; ?>
                </a>
            </div>
        </div>
    <?php endif; ?>

    <div class="row g-3">
        <?php foreach($matches as $m): ?>
            <div class="col-12 col-md-6">
                <div class="card bg-black border-secondary rounded-4 shadow-sm h-100 border border-opacity-50 <?= $m['round'] < $current_round ? 'opacity-50' : ''; ?>">
                    
                    <div class="card-header bg-dark border-secondary py-2 px-3 d-flex justify-content-between align-items-center">
                        <span class="badge bg-secondary bg-opacity-50 text-light rounded-pill">Babak <?= $m['round']; ?> - Match <?= $m['match_number']; ?></span>
                        <?php if($m['status'] == 'completed'): ?>
                            <span class="badge bg-success"><i class="fas fa-check me-1"></i> Selesai</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Menunggu</span>
                        <?php endif; ?>
                    </div>

                    <div class="card-body p-3">
                        <div class="row align-items-center text-center">
                            
                            <div class="col-4">
                                <h6 class="fw-bold text-white mb-0 text-truncate <?= $m['winner_id'] == $m['team1_id'] ? 'text-success' : ''; ?>"><?= esc($m['team1_name']); ?></h6>
                            </div>
                            
                            <div class="col-4">
                                <?php if(empty($m['team2_id'])): ?>
                                    <span class="badge bg-info text-dark px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.65rem;"><i class="fas fa-forward me-1"></i> Lolos Otomatis</span>
                                <?php elseif($m['status'] == 'completed'): ?>
                                    <div class="bg-dark border border-secondary rounded-pill py-1 px-3 d-inline-block shadow-sm">
                                        <h4 class="fw-black text-warning mb-0"><?= $m['score_team1']; ?> : <?= $m['score_team2']; ?></h4>
                                    </div>
                                <?php else: ?>
                                    <button type="button" class="btn btn-sm btn-primary bg-gradient rounded-pill w-100 shadow-sm py-2" data-bs-toggle="modal" data-bs-target="#scoreModal<?= $m['id']; ?>">
                                        <i class="fas fa-keyboard me-1"></i> Input Skor
                                    </button>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-4">
                                <?php if(empty($m['team2_id'])): ?>
                                    <h6 class="text-secondary opacity-50 fst-italic mb-0 small">BYE</h6>
                                <?php else: ?>
                                    <h6 class="fw-bold text-white mb-0 text-truncate <?= $m['winner_id'] == $m['team2_id'] ? 'text-success' : ''; ?>"><?= esc($m['team2_name']); ?></h6>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <?php if($m['status'] == 'pending' && !empty($m['team2_id'])): ?>
            <div class="modal fade" id="scoreModal<?= $m['id']; ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content bg-dark text-white border border-secondary rounded-4 shadow-lg">
                        <div class="modal-header border-secondary">
                            <h5 class="modal-title fw-bold"><i class="fas fa-edit text-warning me-2"></i>Input Skor Pertandingan</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="<?= base_url('admin/update-score/' . $m['id']); ?>" method="POST">
                            <?= csrf_field(); ?>
                            <div class="modal-body p-4 text-center">
                                
                                <div class="d-flex justify-content-between align-items-center gap-3">
                                    <div class="w-100">
                                        <label class="form-label text-light opacity-75 small fw-bold text-truncate w-100"><?= esc($m['team1_name']); ?></label>
                                        <input type="number" name="score_team1" class="form-control form-control-lg bg-black text-white border-secondary text-center fw-bold fs-3" required min="0">
                                    </div>
                                    <div class="fw-black text-secondary fs-4 pt-4">VS</div>
                                    <div class="w-100">
                                        <label class="form-label text-light opacity-75 small fw-bold text-truncate w-100"><?= esc($m['team2_name']); ?></label>
                                        <input type="number" name="score_team2" class="form-control form-control-lg bg-black text-white border-secondary text-center fw-bold fs-3" required min="0">
                                    </div>
                                </div>
                                
                                <div class="alert bg-black border border-danger text-danger small mt-4 mb-0 text-start">
                                    <i class="fas fa-info-circle me-1"></i> Tidak boleh seri. Masukkan hasil skor penalti/babak tambahan jika di waktu normal seri.
                                </div>

                            </div>
                            <div class="modal-footer border-secondary">
                                <button type="button" class="btn btn-outline-secondary rounded-pill fw-bold" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-warning fw-bold rounded-pill text-dark"><i class="fas fa-save me-1"></i> Simpan Hasil</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        <?php endforeach; ?>
    </div>
</div>

<?= $this->endSection(); ?>