<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<?php 
    $t = $turnamen;
    $quota = $t['quota'] > 0 ? $t['quota'] : 1;
    $percent = ($approved_teams_count / $quota) * 100;
    $isFull = $approved_teams_count >= $t['quota'];
    $bgImage = !empty($t['poster']) ? base_url('uploads/posters/' . $t['poster']) : 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=2070&auto=format&fit=crop';
?>

<div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4" style="height: 350px; background: url('<?= $bgImage; ?>') center/cover no-repeat;">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(180deg, rgba(0,0,0,0.2) 0%, rgba(10,15,28,0.8) 50%, rgba(10,15,28,1) 100%);"></div>
    
    <div class="card-body d-flex flex-column justify-content-end position-relative z-1 p-4 p-md-5">
        <div class="d-flex align-items-center mb-2">
            <?php if($t['status'] == 'completed'): ?>
                <span class="badge bg-danger shadow-sm px-3 py-2 rounded-pill me-2"><i class="fas fa-flag-checkered me-1"></i> SELESAI</span>
            <?php elseif($t['status'] == 'ongoing'): ?>
                <span class="badge bg-warning text-dark shadow-sm px-3 py-2 rounded-pill me-2"><i class="fas fa-play-circle me-1"></i> BERJALAN</span>
            <?php else: ?>
                <span class="badge bg-success shadow-sm px-3 py-2 rounded-pill me-2"><i class="fas fa-circle text-white small me-1" style="font-size: 6px; vertical-align: middle;"></i> DIBUKA</span>
            <?php endif; ?>
        </div>
        <h1 class="fw-black text-white text-uppercase mb-2 text-shadow-sm" style="letter-spacing: 1px; font-size: clamp(1.5rem, 4vw, 2.5rem);"><?= esc($t['name']); ?></h1>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card bg-black text-white border-secondary shadow-lg rounded-4 overflow-hidden" style="border-width: 1px;">
            
            <div class="card-header bg-dark border-secondary p-0">
                <ul class="nav nav-tabs nav-fill border-0 flex-nowrap overflow-x-auto hide-scrollbar" id="tournamentTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold text-uppercase py-3 rounded-0 fs-mobile" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" style="letter-spacing: 0.5px;"><i class="fas fa-info-circle me-1"></i> Info</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link w-100 fw-bold text-uppercase py-3 rounded-0 text-light opacity-75 fs-mobile" id="rules-tab" data-bs-toggle="tab" data-bs-target="#rules" type="button" role="tab" style="letter-spacing: 0.5px;"><i class="fas fa-scroll me-1"></i> Rules</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link w-100 fw-bold text-uppercase py-3 rounded-0 text-light opacity-75 fs-mobile" id="peserta-tab" data-bs-toggle="tab" data-bs-target="#peserta" type="button" role="tab" style="letter-spacing: 0.5px;"><i class="fas fa-users me-1"></i> Peserta</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link w-100 fw-bold text-uppercase py-3 rounded-0 text-warning opacity-75 fs-mobile" id="bagan-tab" data-bs-toggle="tab" data-bs-target="#bagan" type="button" role="tab" style="letter-spacing: 0.5px;"><i class="fas fa-sitemap me-1"></i> Bagan / Jadwal</button>
                    </li>
                </ul>
            </div>

            <div class="card-body p-4 p-md-5 tab-content" id="tournamentTabContent">
                
                <div class="tab-pane fade show active" id="info" role="tabpanel">
                    <h5 class="fw-bold text-warning mb-3 border-start border-3 border-warning ps-2">Deskripsi Turnamen</h5>
                    <p class="text-light opacity-75 mb-5" style="line-height: 1.8;"><?= nl2br(esc((string)$t['description'])); ?></p>

                    <h5 class="fw-bold text-success mb-3 border-start border-3 border-success ps-2">Total Hadiah (Prizepool)</h5>
                    <?php if(!empty($t['prize'])): ?>
                        <?php $prizeDisplay = is_numeric($t['prize']) ? 'Rp ' . number_format($t['prize'], 0, ',', '.') : $t['prize']; ?>
                        <div class="bg-dark bg-gradient border border-success border-opacity-50 p-4 rounded-3 d-flex align-items-center shadow-sm">
                            <i class="fas fa-money-bill-wave fa-3x text-success me-4 opacity-75"></i>
                            <div>
                                <h3 class="text-warning fw-black mb-0" style="letter-spacing: 1px;"><?= esc($prizeDisplay); ?></h3>
                                <span class="text-light opacity-50 small">Distribusi hadiah ditentukan oleh panitia.</span>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-light opacity-50 fst-italic">Hadiah belum ditentukan.</p>
                    <?php endif; ?>
                </div>

                <div class="tab-pane fade" id="rules" role="tabpanel">
                    <h5 class="fw-bold text-info mb-3 border-start border-3 border-info ps-2">Peraturan & Regulasi</h5>
                    <div class="bg-dark p-4 rounded-3 border border-secondary text-light opacity-75" style="line-height: 1.8;">
                        <?= !empty($t['rules']) ? nl2br(esc((string)$t['rules'])) : '<span class="fst-italic opacity-50">Belum ada aturan khusus yang ditambahkan.</span>'; ?>
                    </div>
                </div>

                <div class="tab-pane fade" id="peserta" role="tabpanel">
                    <h5 class="fw-bold text-primary mb-3 border-start border-3 border-primary ps-2">Daftar Tim Resmi</h5>
                    
                    <?php if(empty($peserta)): ?>
                        <div class="text-center py-5 bg-dark rounded-3 border border-secondary">
                            <i class="fas fa-user-astronaut fa-3x text-secondary opacity-50 mb-3"></i>
                            <p class="text-light opacity-50 mb-0">Belum ada tim yang disetujui. Jadilah yang pertama!</p>
                        </div>
                    <?php else: ?>
                        <div class="row g-3">
                            <?php foreach($peserta as $index => $tim): ?>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center bg-dark p-3 rounded-3 border border-secondary shadow-sm">
                                        <div class="bg-primary bg-gradient rounded-circle d-flex justify-content-center align-items-center me-3 fw-bold text-white shadow-sm" style="width: 40px; height: 40px;">
                                            <?= $index + 1; ?>
                                        </div>
                                        <div>
                                            <h6 class="text-white fw-bold mb-0 text-truncate" style="max-width: 200px;"><?= esc($tim['team_name']); ?></h6>
                                            <span class="text-light opacity-50 small"><i class="fas fa-user-tie me-1"></i> <?= esc($tim['manager_name']); ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="tab-pane fade" id="bagan" role="tabpanel">
                    
                    <?php if($t['status'] == 'completed' && !empty($champion)): ?>
                        <div class="bg-dark bg-gradient border border-warning border-opacity-75 rounded-4 p-4 text-center mb-5 shadow-lg position-relative overflow-hidden">
                            <div class="position-absolute top-0 start-0 w-100 h-100" style="background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px); background-size: 20px 20px; opacity: 0.5;"></div>
                            
                            <i class="fas fa-trophy fa-3x text-warning mb-2 position-relative z-1" style="filter: drop-shadow(0 0 10px rgba(255,193,7,0.8));"></i>
                            <h6 class="text-warning fw-bold text-uppercase mb-1 position-relative z-1" style="letter-spacing: 2px;">CHAMPION</h6>
                            <h2 class="fw-black text-white text-uppercase mb-1 position-relative z-1"><?= esc($champion['team_name']); ?></h2>
                            <span class="badge bg-black border border-secondary text-light opacity-75 rounded-pill position-relative z-1"><i class="fas fa-user-tie me-1"></i> <?= esc($champion['manager_name']); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($t['format']) && $t['format'] == 'league' && !empty($standings)): ?>
                        <h5 class="fw-bold text-success mb-3 border-start border-3 border-success ps-2">Klasemen <?= $t['status'] == 'completed' ? 'Akhir' : 'Sementara' ?></h5>
                        <div class="table-responsive mb-5 border border-secondary rounded-4 shadow-sm">
                            <table class="table table-dark table-striped table-hover mb-0" style="font-size: 0.9rem;">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th class="text-center py-3">#</th>
                                        <th class="py-3">TIM</th>
                                        <th class="text-center py-3" title="Main">P</th>
                                        <th class="text-center py-3" title="Menang">W</th>
                                        <th class="text-center py-3" title="Seri">D</th>
                                        <th class="text-center py-3" title="Kalah">L</th>
                                        <th class="text-center py-3" title="Selisih Gol">GD</th>
                                        <th class="text-center text-warning py-3">PTS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($standings as $index => $s): ?>
                                    <tr class="<?= $index == 0 ? 'border-warning border-2' : '' ?>">
                                        <td class="text-center fw-bold align-middle"><?= $index + 1 ?></td>
                                        <td class="fw-bold align-middle <?= $index == 0 ? 'text-warning' : '' ?>"><?= esc($s['name']) ?></td>
                                        <td class="text-center align-middle"><?= $s['played'] ?></td>
                                        <td class="text-center align-middle text-success"><?= $s['win'] ?></td>
                                        <td class="text-center align-middle text-secondary"><?= $s['draw'] ?></td>
                                        <td class="text-center align-middle text-danger"><?= $s['loss'] ?></td>
                                        <td class="text-center align-middle"><?= ($s['gd'] > 0 ? '+'.$s['gd'] : $s['gd']) ?></td>
                                        <td class="text-center fw-black text-warning fs-5 align-middle"><?= $s['points'] ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                    <h5 class="fw-bold text-warning mb-4 border-start border-3 border-warning ps-2">Jadwal & Hasil Pertandingan</h5>
                    
                    <?php if(empty($matches)): ?>
                        <div class="text-center py-5 bg-dark rounded-3 border border-secondary">
                            <i class="fas fa-sitemap fa-3x text-secondary opacity-50 mb-3"></i>
                            <h6 class="text-light fw-bold">Jadwal Belum Tersedia</h6>
                            <p class="text-light opacity-50 mb-0 small">Admin belum menutup pendaftaran dan menyusun jadwal.</p>
                        </div>
                    <?php else: ?>
                        
                        <?php 
                            $current_round = 0;
                            foreach($matches as $m): 
                                // Membuat Pembatas (Header) untuk setiap Babak / Pekan baru
                                if ($m['round'] != $current_round): 
                                    $current_round = $m['round'];
                                    $label_babak = (isset($t['format']) && $t['format'] == 'league') ? 'PEKAN / MATCHDAY' : 'BABAK';
                        ?>
                                    <div class="bg-secondary bg-opacity-25 py-2 px-3 rounded-pill mb-3 <?= $current_round > 1 ? 'mt-5' : 'mt-2'; ?> border border-secondary border-opacity-50 text-center">
                                        <h6 class="fw-bold text-warning mb-0 text-uppercase" style="letter-spacing: 1px;"><i class="fas fa-calendar-alt me-2"></i> <?= $label_babak ?> <?= $current_round; ?></h6>
                                    </div>
                        <?php endif; ?>
                            
                            <div class="card bg-dark border-secondary shadow-sm mb-3 rounded-4 overflow-hidden">
                                <div class="card-body p-0 d-flex align-items-stretch">
                                    
                                    <div class="flex-grow-1 p-3 text-center d-flex align-items-center justify-content-center <?= $m['winner_id'] == $m['team1_id'] ? 'bg-success bg-opacity-10' : ''; ?>" style="width: 40%;">
                                        <h6 class="fw-bold mb-0 text-white text-truncate w-100 px-2"><?= esc($m['team1_name']); ?></h6>
                                    </div>
                                    
                                    <div class="bg-black px-2 py-3 text-center border-start border-end border-secondary d-flex flex-column justify-content-center align-items-center" style="width: 20%; min-width: 80px;">
                                        <?php if($m['status'] == 'completed' && !empty($m['team2_id'])): ?>
                                            <span class="fw-black text-warning fs-5"><?= $m['score_team1']; ?> - <?= $m['score_team2']; ?></span>
                                            <span class="badge bg-secondary opacity-50 mt-1" style="font-size: 0.6rem;">SELESAI</span>
                                        <?php elseif(empty($m['team2_id'])): ?>
                                            <span class="fw-black text-info" style="font-size: 0.75rem;">LOLOS</span>
                                            <span class="badge bg-secondary opacity-50 mt-1" style="font-size: 0.6rem;">OTOMATIS</span>
                                        <?php else: ?>
                                            <span class="fw-black text-secondary opacity-50">VS</span>
                                            <span class="badge bg-warning text-dark mt-1" style="font-size: 0.6rem;">MENUNGGU</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="flex-grow-1 p-3 text-center d-flex align-items-center justify-content-center <?= $m['winner_id'] == $m['team2_id'] ? 'bg-success bg-opacity-10' : ''; ?>" style="width: 40%;">
                                        <?php if(empty($m['team2_id'])): ?>
                                            <h6 class="fw-bold mb-0 text-secondary opacity-50 fst-italic">BYE (Lolos)</h6>
                                        <?php else: ?>
                                            <h6 class="fw-bold mb-0 text-white text-truncate w-100 px-2"><?= esc($m['team2_name']); ?></h6>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            </div>

                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card bg-black text-white border-secondary shadow-lg rounded-4 overflow-hidden sticky-top" style="border-width: 1px; top: 20px;">
            <div class="card-body p-4 p-md-5">
                
                <h5 class="fw-bolder text-uppercase text-center mb-4"><i class="fas fa-ticket-alt text-primary me-2"></i>Status Pendaftaran</h5>

                <div class="bg-dark p-3 rounded-3 border border-secondary mb-4">
                    <div class="d-flex justify-content-between align-items-end mb-2">
                        <span class="text-light opacity-75 small fw-bold">Slot Terisi</span>
                        <span class="fw-black fs-5 <?= $isFull ? 'text-danger' : 'text-primary'; ?>"><?= $approved_teams_count; ?> <span class="text-light opacity-50 fs-6">/ <?= $t['quota']; ?></span></span>
                    </div>
                    <div class="progress bg-black rounded-pill border border-secondary border-opacity-25" style="height: 10px;">
                        <div class="progress-bar <?= $isFull ? 'bg-danger' : 'bg-primary bg-gradient'; ?> progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?= $percent; ?>%;"></div>
                    </div>
                    <div class="text-center mt-2">
                        <span class="text-info small fw-bold"><i class="fas fa-users-cog me-1"></i> Max <?= $t['max_slots']; ?> Tim / Akun</span>
                    </div>
                </div>

                <?php if($t['status'] == 'completed'): ?>
                    <button class="btn btn-dark border-secondary w-100 fw-bold py-3 rounded-pill text-light opacity-50" disabled><i class="fas fa-flag-checkered me-2"></i>Turnamen Selesai</button>
                <?php elseif($t['status'] == 'ongoing'): ?>
                    <button class="btn btn-warning w-100 fw-bold py-3 rounded-pill text-dark opacity-75" disabled><i class="fas fa-lock me-2"></i>Pendaftaran Ditutup</button>
                <?php elseif($isFull): ?>
                    <button class="btn btn-danger w-100 fw-bold py-3 rounded-pill opacity-75" disabled><i class="fas fa-times-circle me-2"></i>Kuota Penuh</button>
                <?php else: ?>
                    <a href="<?= base_url('/turnamen/daftar/' . $t['id']); ?>" class="btn btn-primary bg-gradient w-100 fw-bold py-3 rounded-pill shadow-lg text-uppercase fs-6" style="letter-spacing: 1px;">
                        Daftar Sekarang <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                <?php endif; ?>

                <div class="text-center mt-4">
                    <a href="<?= base_url('/'); ?>" class="text-light text-decoration-none opacity-50 small transition-hover">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Beranda
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    /* CSS UNTUK MENYEMBUNYIKAN SCROLLBAR DI TAB HP TAPI TETAP BISA DIGESER */
    .hide-scrollbar {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
    .hide-scrollbar::-webkit-scrollbar {
        display: none; /* Chrome, Safari and Opera */
    }

    /* Memaksa teks tidak turun ke bawah */
    .nav-tabs .nav-link { 
        border: none; 
        border-bottom: 3px solid transparent; 
        color: #adb5bd; 
        white-space: nowrap; 
    }
    .nav-tabs .nav-link.active { background-color: transparent; border-color: #0d6efd; color: #fff; }
    .nav-tabs .nav-link:hover:not(.active) { border-color: rgba(13, 110, 253, 0.5); color: #fff; }
    
    /* Ukuran font dinamis untuk Mobile */
    .fs-mobile { font-size: 0.85rem; }
    @media (max-width: 576px) {
        .fs-mobile { font-size: 0.75rem; padding-left: 10px !important; padding-right: 10px !important; }
    }

    .text-shadow-sm { text-shadow: 1px 1px 4px rgba(0,0,0,0.9); }
</style>

<script>
    // Memastikan tab aktif saat diklik (Bootstrap 5 logic)
    document.addEventListener("DOMContentLoaded", function(){
        var triggerTabList = [].slice.call(document.querySelectorAll('#tournamentTab button'))
        triggerTabList.forEach(function (triggerEl) {
            var tabTrigger = new bootstrap.Tab(triggerEl)
            triggerEl.addEventListener('click', function (event) {
                event.preventDefault()
                tabTrigger.show()
            })
        })
    });
</script>

<?= $this->endSection(); ?>