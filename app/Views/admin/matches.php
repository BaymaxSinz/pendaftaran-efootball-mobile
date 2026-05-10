<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-secondary pb-3">
        <div>
            <h4 class="fw-bolder text-white text-uppercase mb-1">
                <i class="fas fa-gamepad text-primary me-2"></i> Kelola Pertandingan
            </h4>
            <span class="text-light opacity-75">
                Turnamen: <strong class="text-warning"><?= esc($turnamen['name']); ?></strong> 
                (Format: <span class="text-info text-uppercase fw-bold"><?= $turnamen['format'] == 'league' ? 'Liga' : 'Gugur'; ?></span>)
            </span>
        </div>
        <a href="<?= base_url('admin'); ?>" class="btn btn-outline-light rounded-pill btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert bg-success text-white alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4">
            <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success'); ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert bg-danger text-white alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4">
            <i class="fas fa-exclamation-triangle me-2"></i> <?= session()->getFlashdata('error'); ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if($turnamen['status'] == 'completed' && !empty($champion)): ?>
        <div class="card bg-warning text-dark border-0 shadow-lg rounded-4 mb-4 text-center p-4">
            <i class="fas fa-trophy fa-4x mb-3 text-dark opacity-75"></i>
            <h5 class="fw-bold mb-1 text-uppercase">SANG JUARA</h5>
            <h2 class="fw-black mb-0"><?= esc($champion['team_name']); ?></h2>
            <p class="mb-0 mt-2"><strong>Manajer:</strong> <?= esc($champion['manager_name']); ?></p>
        </div>
    <?php endif; ?>

    <?php if($turnamen['format'] == 'league' && !empty($standings)): ?>
        <div class="card bg-black border-secondary shadow-sm rounded-4 mb-4">
            <div class="card-header bg-dark border-secondary">
                <h6 class="fw-bold text-success mb-0"><i class="fas fa-list-ol me-2"></i> Klasemen Sementara</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-dark table-striped table-hover mb-0" style="font-size: 0.85rem;">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="text-center py-2">#</th>
                            <th class="py-2">TIM</th>
                            <th class="text-center py-2">P</th>
                            <th class="text-center py-2">W</th>
                            <th class="text-center py-2">D</th>
                            <th class="text-center py-2">L</th>
                            <th class="text-center py-2">GD</th>
                            <th class="text-center text-warning py-2">PTS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($standings as $index => $s): ?>
                        <tr>
                            <td class="text-center fw-bold align-middle"><?= $index + 1 ?></td>
                            <td class="fw-bold align-middle <?= $index == 0 ? 'text-warning' : '' ?>"><?= esc($s['name']) ?></td>
                            <td class="text-center align-middle"><?= $s['played'] ?></td>
                            <td class="text-center align-middle text-success"><?= $s['win'] ?></td>
                            <td class="text-center align-middle text-secondary"><?= $s['draw'] ?></td>
                            <td class="text-center align-middle text-danger"><?= $s['loss'] ?></td>
                            <td class="text-center align-middle"><?= ($s['gd'] > 0 ? '+'.$s['gd'] : $s['gd']) ?></td>
                            <td class="text-center fw-black text-warning fs-6 align-middle"><?= $s['points'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <?php if($all_completed && $turnamen['format'] == 'bracket' && $turnamen['status'] == 'ongoing'): ?>
        <div class="card bg-black border border-primary shadow-lg rounded-4 mb-4 text-center p-4">
            <h6 class="text-primary fw-bold mb-3">Semua pertandingan Babak <?= $current_round; ?> telah selesai!</h6>
            <a href="<?= base_url('admin/generate-next-round/' . $turnamen['id'] . '/' . $current_round); ?>" class="btn btn-primary fw-bold rounded-pill px-4">
                <i class="fas fa-random me-1"></i> Generate Babak Selanjutnya
            </a>
        </div>
    <?php elseif($turnamen['format'] == 'league' && $turnamen['status'] == 'ongoing'): ?>
        <?php 
            $semua_selesai = true;
            foreach($matches as $m) {
                if($m['status'] == 'pending') { $semua_selesai = false; break; }
            }
        ?>
        <?php if($semua_selesai): ?>
            <div class="card bg-black border border-warning shadow-lg rounded-4 mb-4 text-center p-4">
                <h6 class="text-warning fw-bold mb-3"><i class="fas fa-check-double me-2"></i> Semua Pertandingan Liga Telah Selesai!</h6>
                <a href="<?= base_url('admin/complete-league/' . $turnamen['id']); ?>" class="btn btn-warning text-dark fw-bold rounded-pill px-4" onclick="return confirm('Tutup turnamen sekarang dan tetapkan juara?');">
                    <i class="fas fa-trophy me-1"></i> Tutup Turnamen & Tetapkan Juara
                </a>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if(empty($matches)): ?>
        <div class="alert bg-dark text-center border-secondary py-5 rounded-4">
            <i class="fas fa-sitemap fa-3x text-secondary mb-3"></i>
            <h5 class="text-white">Bagan belum dibuat</h5>
            <p class="text-light opacity-50 mb-0">Silakan ke halaman Kelola Tim dan klik "Mulai Susun Jadwal".</p>
        </div>
    <?php else: ?>
        <div class="row">
            <?php 
                $current_round_loop = 0;
                foreach($matches as $m): 
                    if ($m['round'] != $current_round_loop): 
                        $current_round_loop = $m['round'];
                        $label_babak = ($turnamen['format'] == 'league') ? 'PEKAN / MATCHDAY' : 'BABAK';
            ?>
                <div class="col-12 mt-4 mb-2">
                    <h5 class="fw-black text-warning border-bottom border-warning pb-2 d-inline-block">
                        <i class="fas fa-calendar-alt me-2"></i> <?= $label_babak; ?> <?= $current_round_loop; ?>
                    </h5>
                </div>
            <?php endif; ?>

                <div class="col-md-6 mb-3">
                    <div class="card bg-dark border-secondary shadow-sm rounded-4 h-100">
                        <div class="card-body p-3">
                            <form action="<?= base_url('admin/update-score/' . $m['id']); ?>" method="post">
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-secondary opacity-75">Match #<?= $m['match_number']; ?></span>
                                    <?php if($m['status'] == 'completed'): ?>
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i> Selesai</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Menunggu Skor</span>
                                    <?php endif; ?>
                                </div>

                                <div class="row align-items-center text-center g-2">
                                    <div class="col-5">
                                        <h6 class="text-white fw-bold text-truncate" title="<?= esc($m['team1_name']); ?>"><?= esc($m['team1_name']); ?></h6>
                                        <?php if($m['status'] == 'pending' && !empty($m['team2_id'])): ?>
                                            <input type="number" name="score_team1" class="form-control form-control-lg bg-black text-white border-secondary text-center fw-bold mt-2" required min="0" value="0">
                                        <?php else: ?>
                                            <div class="fs-3 fw-black text-<?= $m['winner_id'] == $m['team1_id'] ? 'success' : 'light'; ?> mt-2"><?= $m['score_team1']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="col-2">
                                        <div class="text-secondary fw-bold fs-5">VS</div>
                                    </div>

                                    <div class="col-5">
                                        <?php if(empty($m['team2_id'])): ?>
                                            <h6 class="text-secondary fst-italic">BYE (Lolos)</h6>
                                            <div class="fs-3 fw-black text-secondary mt-2">-</div>
                                        <?php else: ?>
                                            <h6 class="text-white fw-bold text-truncate" title="<?= esc($m['team2_name']); ?>"><?= esc($m['team2_name']); ?></h6>
                                            <?php if($m['status'] == 'pending'): ?>
                                                <input type="number" name="score_team2" class="form-control form-control-lg bg-black text-white border-secondary text-center fw-bold mt-2" required min="0" value="0">
                                            <?php else: ?>
                                                <div class="fs-3 fw-black text-<?= $m['winner_id'] == $m['team2_id'] ? 'success' : 'light'; ?> mt-2"><?= $m['score_team2']; ?></div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php if($m['status'] == 'pending' && !empty($m['team2_id'])): ?>
                                    <button type="submit" class="btn btn-primary w-100 mt-3 rounded-pill fw-bold btn-sm">
                                        <i class="fas fa-save me-1"></i> Simpan Skor
                                    </button>
                                <?php endif; ?>

                            </form>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?= $this->endSection(); ?>