<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container py-4">

    <!-- HEADER -->
    <div class="d-flex align-items-center mb-4 border-bottom border-secondary pb-3">
        <div class="bg-info rounded-3 p-2 me-3">
            <i class="fas fa-clipboard-check fs-4 text-dark"></i>
        </div>
        <h4 class="fw-bolder text-white text-uppercase mb-0" style="letter-spacing: 1px;">
            Status Pendaftaran <span class="text-warning">Saya</span>
        </h4>
    </div>

    <!-- FLASH MESSAGES -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert bg-success text-white alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4">
            <i class="fas fa-check-circle me-2"></i>
            <strong>Berhasil!</strong> <?= session()->getFlashdata('success'); ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert bg-danger text-white alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>Gagal!</strong> <?= session()->getFlashdata('error'); ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- CONTENT SECTION -->
    <?php if (empty($my_teams)): ?>
        <div class="card bg-black border border-secondary py-5 rounded-4 shadow-sm text-center">
            <div class="card-body">
                <i class="fas fa-folder-open fs-1 mb-3 text-secondary opacity-50"></i>
                <p class="text-light opacity-75 fs-5">Kamu belum memiliki riwayat pendaftaran turnamen.</p>
                <a href="<?= base_url('/'); ?>" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                    <i class="fas fa-search me-2"></i> Cari Turnamen
                </a>
            </div>
        </div>
    <?php else: ?>

        <div class="row">
            <?php foreach ($my_teams as $tim): ?>
                <div class="col-md-6 mb-4">
                    <div class="card bg-black text-white border border-secondary shadow-lg rounded-4 overflow-hidden h-100">
                        
                        <!-- ACCENT LINE -->
                        <div class="bg-info" style="height: 3px; width: 40%;"></div>

                        <div class="card-body p-4 d-flex flex-column">
                            <!-- TOURNAMENT BADGE -->
                            <div class="mb-3">
                                <span class="badge bg-dark border border-warning text-warning px-3 py-2 rounded-pill small fw-bold">
                                    <i class="fas fa-trophy me-1"></i> <?= esc($tim['turnamen_name']); ?>
                                </span>
                            </div>

                            <!-- TEAM NAME -->
                            <h3 class="h4 fw-black text-uppercase mb-3" style="letter-spacing: 0.5px;">
                                <?= esc($tim['team_name']); ?>
                            </h3>

                            <!-- INFO GRID -->
                            <div class="row g-0 bg-dark rounded-3 border border-secondary mb-4 overflow-hidden">
                                <div class="col-6 p-3 border-end border-secondary">
                                    <small class="d-block text-uppercase text-secondary fw-bold mb-1" style="font-size: 0.65rem;">IGN / Nickname</small>
                                    <span class="text-info fw-bold">
                                        <?= esc($tim['in_game_name'] ?? '-'); ?>
                                    </span>
                                </div>
                                <div class="col-6 p-3">
                                    <small class="d-block text-uppercase text-secondary fw-bold mb-1" style="font-size: 0.65rem;">ID Player</small>
                                    <span class="text-info font-monospace fw-bold">
                                        <?= esc($tim['in_game_id'] ?? '-'); ?>
                                    </span>
                                </div>
                            </div>

                            <!-- FOOTER ACTION -->
                            <div class="mt-auto pt-3 border-top border-secondary d-flex justify-content-between align-items-center">
                                
                                <!-- STATUS PILL -->
                                <?php 
                                    $status_map = [
                                        'pending'  => ['bg-warning text-dark', 'fa-clock', 'Menunggu'],
                                        'approved' => ['bg-success', 'fa-check-circle', 'Disetujui'],
                                        'rejected' => ['bg-danger', 'fa-times-circle', 'Ditolak']
                                    ];
                                    $cur = $status_map[$tim['status']] ?? ['bg-secondary', 'fa-question-circle', 'Unknown'];
                                ?>
                                <span class="badge <?= $cur[0]; ?> px-3 py-2 rounded-pill">
                                    <i class="fas <?= $cur[1]; ?> me-1"></i> <?= $cur[2]; ?>
                                </span>

                                <!-- CANCEL ACTION -->
                                <?php if ($tim['turnamen_status'] === 'open'): ?>
                                    <a href="<?= base_url('/user/batal/' . $tim['id']); ?>" 
                                       class="btn btn-sm btn-link text-danger text-decoration-none fw-bold"
                                       onclick="return confirm('Yakin ingin membatalkan pendaftaran ini?');">
                                        <i class="fas fa-trash-alt me-1"></i> Batal
                                    </a>
                                <?php else: ?>
                                    <span class="small text-secondary italic">
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