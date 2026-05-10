<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-5"> <?php if(session()->has('errors')): ?>
                <div class="alert bg-danger text-white alert-dismissible fade show py-3 border-0 shadow-sm rounded-4 mb-4" role="alert">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-exclamation-circle me-3 mt-1 fs-5"></i>
                        <div>
                            <strong class="d-block mb-1">Pendaftaran Gagal!</strong>
                            <ul class="mb-0 ps-3 small">
                                <?php foreach(session('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card bg-black text-white border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="bg-gradient bg-primary" style="height: 4px; width: 100%;"></div>
                
                <div class="card-body p-4 p-sm-5">
                    
                    <div class="text-center mb-4">
                        <div class="d-inline-block bg-dark p-3 rounded-circle mb-3 border border-secondary shadow-sm">
                            <i class="fas fa-user-plus fa-2x text-primary"></i>
                        </div>
                        <h4 class="card-title fw-bolder text-uppercase mb-1" style="letter-spacing: 1px;">Create <span class="text-primary">Account</span></h4>
                        <p class="text-light opacity-50 small">Daftar untuk mulai mengikuti turnamen</p>
                    </div>
                    
                    <form action="<?= base_url('/register/process'); ?>" method="POST">
                        <?= csrf_field(); ?> <div class="mb-4">
                            <label class="form-label text-light opacity-75 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Nama Lengkap</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fas fa-id-card"></i></span>
                                <input type="text" class="form-control bg-dark text-white border-secondary" name="name" placeholder="Contoh: Budi Santoso" value="<?= old('name') ?>" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-light opacity-75 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Email Address</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control bg-dark text-white border-secondary" name="email" placeholder="contoh@email.com" value="<?= old('email') ?>" required>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label text-light opacity-75 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Password</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control bg-dark text-white border-secondary" name="password" placeholder="••••••••" required>
                            </div>
                            <div class="form-text text-info small mt-1">
                                <i class="fas fa-shield-alt me-1"></i>Gunakan password yang aman dan mudah diingat.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary bg-gradient w-100 fw-bold py-3 rounded-pill shadow mb-4 text-white text-uppercase" style="letter-spacing: 1px;">
                            <i class="fas fa-rocket me-2"></i> Daftar Sekarang
                        </button>
                    </form>

                    <p class="text-center mb-0 small text-light opacity-75 pt-3 border-top border-secondary">
                        Sudah punya akun? <br>
                        <a href="<?= base_url('/login'); ?>" class="text-warning text-decoration-none fw-bold">
                            <i class="fas fa-sign-in-alt me-1"></i> Login di sini
                        </a>
                    </p>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="<?= base_url('/'); ?>" class="text-light text-decoration-none opacity-50 small">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Beranda
                </a>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection(); ?>