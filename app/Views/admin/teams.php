<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container py-4">
    
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert bg-success text-white border-0 shadow-sm rounded-4 mb-4 alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success'); ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert bg-danger text-white border-0 shadow-sm rounded-4 mb-4 alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i> <?= session()->getFlashdata('error'); ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-black text-uppercase text-white mb-0" style="letter-spacing: 0.5px;">Kelola <span class="text-info">Tim</span></h5>
            <span class="text-light opacity-50 small"><?= esc($turnamen['name']); ?> (Format: <?= ucfirst(esc($turnamen['format'])); ?>)</span>
        </div>
        <!-- Mengarah ke base_url('admin') yang sudah terdaftar di routes -->
        <a href="<?= base_url('admin'); ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <?php if($turnamen['status'] == 'open'): ?>
        <div class="card bg-black border border-warning shadow-lg rounded-4 overflow-hidden mb-4">
            <div class="card-body p-4 text-center position-relative">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(45deg, rgba(255,193,7,0.1), rgba(0,0,0,0)); pointer-events: none;"></div>
                <h5 class="text-warning fw-bold mb-2 position-relative z-1"><i class="fas fa-random me-2"></i> Drawing & Tutup Pendaftaran</h5>
                <p class="text-light opacity-75 small mb-4 position-relative z-1">
                    Pastikan hanya tim yang berstatus <b>"Disetujui"</b> yang akan masuk ke dalam bagan. <br>
                    Tindakan ini tidak dapat dibatalkan!
                </p>
                
                <?php 
                $generateUrl = ($turnamen['format'] == 'league') 
                    ? base_url('admin/generate-league/' . $turnamen['id']) 
                    : base_url('admin/generate-bracket/' . $turnamen['id']); 
                ?>

                <a href="<?= $generateUrl; ?>" 
                   class="btn btn-warning fw-bold text-dark rounded-pill px-4 shadow" 
                   onclick="return confirm('Apakah Anda yakin? Pastikan jumlah tim sudah sesuai dan semua status sudah dicek.');">
                    <i class="fas fa-play-circle me-1"></i> Mulai Susun Jadwal & Kunci Tim
                </a>
            </div>
        </div>
    <?php elseif($turnamen['status'] == 'ongoing' || $turnamen['status'] == 'completed'): ?>
        <div class="alert bg-dark border border-success text-success text-center rounded-4 shadow-sm mb-4">
            <i class="fas fa-lock me-1"></i> <b>Turnamen Sedang/Sudah Berjalan.</b> Data tim telah dikunci untuk menjaga integritas bagan.
        </div>
    <?php endif; ?>

    <div class="card bg-black border-secondary rounded-4 shadow-sm overflow-hidden">
        <div class="card-body p-0">
            <?php if(empty($teams)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-users-slash fa-3x text-secondary opacity-25 mb-3"></i>
                    <p class="text-light opacity-50 mb-0">Belum ada tim yang mendaftar ke turnamen ini.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0">
                        <thead class="bg-dark text-light opacity-75 text-uppercase small">
                            <tr>
                                <th class="ps-4 py-3">Nama Tim</th>
                                <th class="py-3">Manajer</th>
                                <th class="py-3 text-center">Status</th>
                                <th class="py-3 text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($teams as $tim): ?>
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <td class="ps-4 py-3 fw-bold text-white"><?= esc($tim['team_name']); ?></td>
                                    <td class="py-3 text-light opacity-75 small">
                                        <i class="fas fa-user-circle me-1"></i> <?= esc($tim['player_name']); ?>
                                    </td>
                                    <td class="py-3 text-center">
                                        <?php if($tim['status'] == 'approved'): ?>
                                            <span class="badge bg-success rounded-pill px-3">Disetujui</span>
                                        <?php elseif($tim['status'] == 'rejected'): ?>
                                            <span class="badge bg-danger rounded-pill px-3">Ditolak</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark rounded-pill px-3">Menunggu</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4 py-3">
                                        <?php if($turnamen['status'] == 'open'): ?>
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?= base_url('admin/tim/status/' . $tim['id'] . '/approved'); ?>" 
                                                   class="btn btn-success <?= $tim['status'] == 'approved' ? 'active disabled' : ''; ?>" title="Setujui">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                                <a href="<?= base_url('admin/tim/status/' . $tim['id'] . '/pending'); ?>" 
                                                   class="btn btn-secondary <?= $tim['status'] == 'pending' ? 'active disabled' : ''; ?>" title="Kembalikan ke Menunggu">
                                                    <i class="fas fa-undo"></i>
                                                </a>
                                                <a href="<?= base_url('admin/tim/status/' . $tim['id'] . '/rejected'); ?>" 
                                                   class="btn btn-danger <?= $tim['status'] == 'rejected' ? 'active disabled' : ''; ?>" title="Tolak">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-secondary opacity-50 small fst-italic">
                                                <i class="fas fa-lock me-1"></i> Terkunci
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>