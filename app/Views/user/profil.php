<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container py-2">
    <div class="card bg-black border-0 shadow-lg rounded-4 overflow-hidden mb-4 position-relative">
        <div class="bg-gradient bg-primary" style="height: 60px; width: 100%;"></div>
        <div class="card-body text-center mt-n4 pb-4 position-relative">
            <div class="d-inline-flex justify-content-center align-items-center bg-dark border border-3 border-black rounded-circle shadow-lg mb-3" style="width: 80px; height: 80px; z-index: 2; position: relative; margin-top: -40px;">
                <i class="fas fa-user-ninja fa-3x text-primary"></i>
            </div>
            <h4 class="fw-black text-white text-uppercase mb-0" style="letter-spacing: 1px;"><?= esc($user['name']); ?></h4>
            <p class="text-light opacity-50 small mb-0"><i class="fas fa-envelope me-1"></i> <?= esc($user['email']); ?></p>
            
            <?php if(session()->get('user_role') == 'admin'): ?>
                <span class="badge bg-warning text-dark mt-2 fw-bold"><i class="fas fa-crown me-1"></i> Administrator</span>
            <?php else: ?>
                <span class="badge bg-info text-dark mt-2 fw-bold"><i class="fas fa-gamepad me-1"></i> Player</span>
            <?php endif; ?>
        </div>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert bg-success text-white py-3 border-0 shadow-sm rounded-4 mb-4"><i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success'); ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert bg-danger text-white py-3 border-0 shadow-sm rounded-4 mb-4"><i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error'); ?></div>
    <?php endif; ?>

    <ul class="nav nav-pills nav-justified mb-4 gap-2" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill fw-bold text-uppercase shadow-sm" id="pills-stats-tab" data-bs-toggle="pill" data-bs-target="#pills-stats" type="button" role="tab" style="font-size: 0.85rem;"><i class="fas fa-trophy me-1"></i> Trophy Room (COMING SOON)</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-bold text-uppercase shadow-sm" id="pills-settings-tab" data-bs-toggle="pill" data-bs-target="#pills-settings" type="button" role="tab" style="font-size: 0.85rem;"><i class="fas fa-cog me-1"></i> Pengaturan</button>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        
        <div class="tab-pane fade show active" id="pills-stats" role="tabpanel">
            <div class="row g-3">
                <div class="col-6">
                    <div class="bg-dark border border-secondary p-3 rounded-4 text-center shadow-sm h-100">
                        <i class="fas fa-shield-alt fa-2x text-info mb-2"></i>
                        <h3 class="fw-black text-white mb-0">?</h3>
                        <small class="text-light opacity-50 fw-bold" style="font-size: 0.7rem;">TIM DIDAFTARKAN</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="bg-dark border border-secondary p-3 rounded-4 text-center shadow-sm h-100">
                        <i class="fas fa-medal fa-2x text-warning mb-2"></i>
                        <h3 class="fw-black text-white mb-0">?</h3>
                        <small class="text-light opacity-50 fw-bold" style="font-size: 0.7rem;">PRESTASI/JUARA</small>
                    </div>
                </div>
            </div>
            <div class="alert bg-black border border-secondary mt-3 text-center rounded-4 text-light opacity-50 small">
                <i class="fas fa-tools mb-2 fs-4 d-block"></i>
                Riwayat statistik turnamen akan segera hadir di update selanjutnya!
            </div>
        </div>

        <div class="tab-pane fade" id="pills-settings" role="tabpanel">
            
            <div class="card bg-black text-white border-secondary shadow-lg rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-uppercase text-info mb-3 border-bottom border-secondary pb-2"><i class="fas fa-user-edit me-2"></i> Edit Profil</h6>
                    <form action="<?= base_url('/profil/update'); ?>" method="POST">
                        <?= csrf_field(); ?>
                        <div class="mb-3">
                            <label class="form-label text-light opacity-75 small fw-bold">Nama Lengkap</label>
                            <input type="text" class="form-control bg-dark text-white border-secondary" name="name" value="<?= esc($user['name']); ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-light opacity-75 small fw-bold">Email</label>
                            <input type="email" class="form-control bg-dark text-white border-secondary" name="email" value="<?= esc($user['email']); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-info bg-gradient w-100 fw-bold rounded-pill text-dark"><i class="fas fa-save me-2"></i>Simpan Profil</button>
                    </form>
                </div>
            </div>

            <div class="card bg-black text-white border-secondary shadow-lg rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-uppercase text-danger mb-3 border-bottom border-secondary pb-2"><i class="fas fa-lock me-2"></i> Ubah Password</h6>
                    <form action="<?= base_url('/profil/password'); ?>" method="POST">
                        <?= csrf_field(); ?>
                        <div class="mb-3">
                            <label class="form-label text-light opacity-75 small fw-bold">Password Lama</label>
                            <input type="password" class="form-control bg-dark text-white border-secondary" name="old_password" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-light opacity-75 small fw-bold">Password Baru</label>
                            <input type="password" class="form-control bg-dark text-white border-secondary" name="new_password" required>
                        </div>
                        <button type="submit" class="btn btn-outline-danger w-100 fw-bold rounded-pill"><i class="fas fa-key me-2"></i>Update Password</button>
                    </form>
                </div>
            </div>

            <div class="card bg-black border border-danger shadow-lg rounded-4 mb-4 mt-4">
                <div class="card-body p-4 text-center">
                    <h6 class="fw-bold text-uppercase text-danger mb-3">Keluar</h6>
                    <p class="text-light opacity-50 small mb-3">Sesi kamu akan diakhiri dan kamu harus login kembali untuk mendaftar turnamen.</p>
                    <a href="<?= base_url('/logout'); ?>" class="btn btn-danger bg-gradient w-100 fw-bold rounded-pill shadow-sm py-3 text-uppercase" onclick="return confirm('Apakah kamu yakin ingin keluar dari akun ini?');" style="letter-spacing: 1px;">
                        <i class="fas fa-sign-out-alt me-2"></i> Keluar Akun (Logout)
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection(); ?>