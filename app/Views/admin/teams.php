<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container py-4">
    
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert bg-success text-white border-0 shadow-sm rounded-4 mb-4 alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success'); ?><button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert bg-danger text-white border-0 shadow-sm rounded-4 mb-4 alert-dismissible fade show"><i class="fas fa-exclamation-triangle me-2"></i> <?= session()->getFlashdata('error'); ?><button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-black text-uppercase text-white mb-0" style="letter-spacing: 0.5px;">Kelola <span class="text-info">Tim</span></h5>
            <span class="text-light opacity-50 small"><?= esc($turnamen['name']); ?></span>
        </div>
        <a href="<?= base_url('/'); ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3"><i class="fas fa-arrow-left me-1"></i> Beranda</a>
    </div>

    <?php if($turnamen['status'] == 'open'): ?>
        <div class="card bg-black border border-warning shadow-lg rounded-4 overflow-hidden mb-4">
            <div class="card-body p-4 text-center position-relative">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(45deg, rgba(255,193,7,0.1), rgba(0,0,0,0));"></div>
                <h5 class="text-warning fw-bold mb-2 position-relative z-1"><i class="fas fa-random me-2"></i> Drawing Bagan & Tutup Pendaftaran</h5>
                <p class="text-light opacity-75 small mb-4 position-relative z-1">Pastikan semua tim sudah dicek statusnya. Mengklik tombol ini akan mengacak posisi lawan secara otomatis dan turnamen akan langsung dimulai!</p>
                
                <a href="<?= base_url('admin/generate-bracket/' . $turnamen['id']); ?>" class="btn btn-warning fw-bold text-dark rounded-pill px-4 py-2 shadow-lg position-relative z-1" onclick="return confirm('PERINGATAN!\n\nApakah kamu yakin ingin menutup pendaftaran dan mengacak bagan sekarang? Proses ini tidak bisa dibatalkan!');">
                    <i class="fas fa-bolt me-1"></i> Mulai Drawing Otomatis
                </a>
            </div>
        </div>
    <?php elseif($turnamen['status'] == 'ongoing'): ?>
        <div class="alert bg-dark border border-success text-success text-center rounded-4 shadow-sm mb-4">
            <i class="fas fa-check-circle me-1"></i> <b>Drawing Selesai!</b> Pendaftaran sudah ditutup dan Turnamen sedang berlangsung.
        </div>
    <?php endif; ?>

    <div class="card bg-black border-secondary rounded-4 shadow-sm overflow-hidden">
        <div class="card-body p-0">
            <?php if(empty($teams)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-secondary opacity-50 mb-3"></i>
                    <p class="text-light opacity-50 mb-0">Belum ada tim yang mendaftar.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0" style="min-width: 600px;">
                        <thead class="bg-dark text-light opacity-75 text-uppercase small">
                            <tr>
                                <th class="ps-4 py-3">Nama Tim</th>
                                <th class="py-3">Manajer (User)</th>
                                <th class="py-3">Status</th>
                                <th class="py-3 text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($teams as $tim): ?>
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <td class="ps-4 py-3 fw-bold text-white"><?= esc($tim['team_name']); ?></td>
                                    <td class="py-3 text-light opacity-75"><i class="fas fa-user-circle me-1"></i> <?= esc($tim['player_name']); ?></td>
                                    <td class="py-3">
                                        <?php if($tim['status'] == 'approved'): ?>
                                            <span class="badge bg-success bg-gradient rounded-pill px-3 py-2"><i class="fas fa-check me-1"></i> Disetujui</span>
                                        <?php elseif($tim['status'] == 'rejected'): ?>
                                            <span class="badge bg-danger bg-gradient rounded-pill px-3 py-2"><i class="fas fa-times me-1"></i> Ditolak</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary bg-gradient rounded-pill px-3 py-2"><i class="fas fa-clock me-1"></i> Menunggu</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4 py-3">
                                        <?php if($turnamen['status'] == 'open'): ?>
                                            <div class="btn-group shadow-sm">
                                                <a href="<?= base_url('/admin/tim/status/' . $tim['id'] . '/approved'); ?>" class="btn btn-sm btn-success fw-bold <?= $tim['status'] == 'approved' ? 'disabled' : ''; ?>"><i class="fas fa-check"></i></a>
                                                <a href="<?= base_url('/admin/tim/status/' . $tim['id'] . '/pending'); ?>" class="btn btn-sm btn-secondary fw-bold <?= $tim['status'] == 'pending' ? 'disabled' : ''; ?>"><i class="fas fa-undo"></i></a>
                                                <a href="<?= base_url('/admin/tim/status/' . $tim['id'] . '/rejected'); ?>" class="btn btn-sm btn-danger fw-bold <?= $tim['status'] == 'rejected' ? 'disabled' : ''; ?>"><i class="fas fa-times"></i></a>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-secondary opacity-50 small fst-italic"><i class="fas fa-lock me-1"></i> Terkunci</span>
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