<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container py-4">
    <div class="d-flex align-items-end mb-4 border-bottom border-secondary pb-2">
        <h4 class="fw-bolder text-white text-uppercase mb-0" style="letter-spacing: 1px;">
            <i class="fas fa-users fs-4 text-info me-2 align-middle"></i> 
            <span class="align-middle">Status Pendaftaran <span class="text-warning">Saya</span></span>
        </h4>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert bg-success text-white alert-dismissible fade show py-3 border-0 shadow-sm rounded-4 mb-4" role="alert">
            <i class="fas fa-check-circle me-2 fs-5 align-middle"></i> 
            <span class="align-middle fw-bold"><?= session()->getFlashdata('success'); ?></span>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert bg-danger text-white alert-dismissible fade show py-3 border-0 shadow-sm rounded-4 mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2 fs-5 align-middle"></i> 
            <span class="align-middle fw-bold"><?= session()->getFlashdata('error'); ?></span>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(empty($my_teams)): ?>
        <div class="alert bg-black border border-secondary text-center py-5 rounded-4 shadow-sm" role="alert">
            <i class="fas fa-clipboard-list fs-1 mb-3 text-secondary"></i><br>
            <span class="text-light opacity-75 fs-5">Kamu belum mendaftar di turnamen manapun.</span><br>
            <a href="<?= base_url('/'); ?>" class="btn btn-primary mt-3 rounded-pill fw-bold shadow-sm">
                <i class="fas fa-search me-2"></i>Cari Turnamen
            </a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach($my_teams as $tim): ?>
                <div class="col-md-6 mb-4">
                    <div class="card bg-black text-white border-0 shadow-lg rounded-4 overflow-hidden h-100 position-relative">
                        
                        <div class="bg-gradient bg-info" style="height: 4px; width: 100%;"></div>

                        <div class="card-body p-4 d-flex flex-column">
                            
                            <div class="mb-3">
                                <span class="badge bg-dark border border-secondary text-warning px-3 py-2 rounded-pill small fw-bold text-uppercase">
                                    <i class="fas fa-trophy me-1"></i> <?= esc($tim['turnamen_name']); ?>
                                </span>
                            </div>

                            <h4 class="card-title fw-black text-white text-uppercase mb-3" style="font-weight: 900; letter-spacing: 0.5px;">
                                <?= esc($tim['team_name']); ?>
                            </h4>
                            
                            <div class="p-3 bg-dark rounded-3 border border-secondary mb-4">
                                <div class="row g-2">
                                    <div class="col-6 border-end border-secondary">
                                        <small class="d-block text-light opacity-50 mb-1" style="font-size: 0.75rem;">NICKNAME (IGN)</small>
                                        <span class="fw-bold text-info"><i class="fas fa-gamepad me-1"></i> <?= esc($tim['in_game_name'] ?? '-'); ?></span>
                                    </div>
                                    <div class="col-6 ps-3">
                                        <small class="d-block text-light opacity-50 mb-1" style="font-size: 0.75rem;">ID EFOOTBALL</small>
                                        <span class="fw-bold text-info font-monospace"><i class="fas fa-id-badge me-1"></i> <?= esc($tim['in_game_id'] ?? '-'); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-auto border-top border-secondary pt-3">
                                <div>
                                    <?php if($tim['status'] == 'pending'): ?>
                                        <span class="badge bg-warning text-dark bg-gradient px-3 py-2 rounded-pill fw-bold shadow-sm"><i class="fas fa-clock me-1"></i>Menunggu</span>
                                    <?php elseif($tim['status'] == 'approved'): ?>
                                        <span class="badge bg-success bg-gradient px-3 py-2 rounded-pill fw-bold shadow-sm"><i class="fas fa-check-circle me-1"></i>Disetujui</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger bg-gradient px-3 py-2 rounded-pill fw-bold shadow-sm"><i class="fas fa-times-circle me-1"></i>Ditolak</span>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if($tim['turnamen_status'] == 'open'): ?>
    
                                    <a href="<?= base_url('/user/batal/' . $tim['id']); ?>" class="btn btn-sm btn-outline-danger rounded-pill fw-bold" onclick="return confirm('Yakin ingin membatalkan pendaftaran?');">
                                        <i class="fas fa-times-circle me-1"></i> Batal
                                    </a>

                                <?php else: ?>
                                    
                                    <span class="badge bg-dark border border-secondary text-secondary opacity-75 rounded-pill px-3 py-2 shadow-sm">
                                        <i class="fas fa-lock me-1"></i> Terkunci
                                    </span>

                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?><?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container py-4">
    <div class="d-flex align-items-end mb-4 border-bottom border-secondary pb-2">
        <h4 class="fw-bolder text-white text-uppercase mb-0" style="letter-spacing: 1px;">
            <i class="fas fa-users fs-4 text-info me-2 align-middle"></i> 
            <span class="align-middle">Status Pendaftaran <span class="text-warning">Saya</span></span>
        </h4>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert bg-success text-white alert-dismissible fade show py-3 border-0 shadow-sm rounded-4 mb-4" role="alert">
            <i class="fas fa-check-circle me-2 fs-5 align-middle"></i> 
            <span class="align-middle fw-bold"><?= session()->getFlashdata('success'); ?></span>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert bg-danger text-white alert-dismissible fade show py-3 border-0 shadow-sm rounded-4 mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2 fs-5 align-middle"></i> 
            <span class="align-middle fw-bold"><?= session()->getFlashdata('error'); ?></span>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(empty($my_teams)): ?>
        <div class="alert bg-black border border-secondary text-center py-5 rounded-4 shadow-sm" role="alert">
            <i class="fas fa-clipboard-list fs-1 mb-3 text-secondary"></i><br>
            <span class="text-light opacity-75 fs-5">Kamu belum mendaftar di turnamen manapun.</span><br>
            <a href="<?= base_url('/'); ?>" class="btn btn-primary mt-3 rounded-pill fw-bold shadow-sm">
                <i class="fas fa-search me-2"></i>Cari Turnamen
            </a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach($my_teams as $tim): ?>
                <div class="col-md-6 mb-4">
                    <div class="card bg-black text-white border-0 shadow-lg rounded-4 overflow-hidden h-100 position-relative">
                        
                        <div class="bg-gradient bg-info" style="height: 4px; width: 100%;"></div>

                        <div class="card-body p-4 d-flex flex-column">
                            
                            <div class="mb-3">
                                <span class="badge bg-dark border border-secondary text-warning px-3 py-2 rounded-pill small fw-bold text-uppercase">
                                    <i class="fas fa-trophy me-1"></i> <?= esc($tim['turnamen_name']); ?>
                                </span>
                            </div>

                            <h4 class="card-title fw-black text-white text-uppercase mb-3" style="font-weight: 900; letter-spacing: 0.5px;">
                                <?= esc($tim['team_name']); ?>
                            </h4>
                            
                            <div class="p-3 bg-dark rounded-3 border border-secondary mb-4">
                                <div class="row g-2">
                                    <div class="col-6 border-end border-secondary">
                                        <small class="d-block text-light opacity-50 mb-1" style="font-size: 0.75rem;">NICKNAME (IGN)</small>
                                        <span class="fw-bold text-info"><i class="fas fa-gamepad me-1"></i> <?= esc($tim['in_game_name'] ?? '-'); ?></span>
                                    </div>
                                    <div class="col-6 ps-3">
                                        <small class="d-block text-light opacity-50 mb-1" style="font-size: 0.75rem;">ID EFOOTBALL</small>
                                        <span class="fw-bold text-info font-monospace"><i class="fas fa-id-badge me-1"></i> <?= esc($tim['in_game_id'] ?? '-'); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-auto border-top border-secondary pt-3">
                                <div>
                                    <?php if($tim['status'] == 'pending'): ?>
                                        <span class="badge bg-warning text-dark bg-gradient px-3 py-2 rounded-pill fw-bold shadow-sm"><i class="fas fa-clock me-1"></i>Menunggu</span>
                                    <?php elseif($tim['status'] == 'approved'): ?>
                                        <span class="badge bg-success bg-gradient px-3 py-2 rounded-pill fw-bold shadow-sm"><i class="fas fa-check-circle me-1"></i>Disetujui</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger bg-gradient px-3 py-2 rounded-pill fw-bold shadow-sm"><i class="fas fa-times-circle me-1"></i>Ditolak</span>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if($tim['turnamen_status'] == 'open'): ?>
    
                                    <a href="<?= base_url('/user/batal/' . $tim['id']); ?>" class="btn btn-sm btn-outline-danger rounded-pill fw-bold" onclick="return confirm('Yakin ingin membatalkan pendaftaran?');">
                                        <i class="fas fa-times-circle me-1"></i> Batal
                                    </a>

                                <?php else: ?>
                                    
                                    <span class="badge bg-dark border border-secondary text-secondary opacity-75 rounded-pill px-3 py-2 shadow-sm">
                                        <i class="fas fa-lock me-1"></i> Terkunci
                                    </span>

                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?>