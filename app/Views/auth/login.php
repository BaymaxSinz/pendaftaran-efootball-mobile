<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-5"> <?php if(session()->getFlashdata('error')): ?>
                <div class="alert bg-danger text-white alert-dismissible fade show py-3 border-0 shadow-sm rounded-4 mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2 fs-5 align-middle"></i> 
                    <span class="align-middle fw-bold"><?= session()->getFlashdata('error'); ?></span>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert bg-success text-white alert-dismissible fade show py-3 border-0 shadow-sm rounded-4 mb-4" role="alert">
                    <i class="fas fa-check-circle me-2 fs-5 align-middle"></i> 
                    <span class="align-middle fw-bold"><?= session()->getFlashdata('success'); ?></span>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card bg-black text-white border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="bg-gradient bg-warning" style="height: 4px; width: 100%;"></div>
                
                <div class="card-body p-4 p-sm-5">
                    
                    <div class="text-center mb-4">
                        <div class="d-inline-block bg-dark p-3 rounded-circle mb-3 border border-secondary shadow-sm">
                            <i class="fas fa-gamepad fa-2x text-warning"></i>
                        </div>
                        <h4 class="card-title fw-bolder text-uppercase mb-1" style="letter-spacing: 1px;">Player <span class="text-warning">Login</span></h4>
                        <p class="text-light opacity-50 small">Masuk ke akun</p>
                    </div>
                    
                    <form action="<?= base_url('/login/process'); ?>" method="POST">
                        <?= csrf_field(); ?> <div class="mb-4">
                            <label class="form-label text-light opacity-75 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Email Address</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control bg-dark text-white border-secondary" name="email" placeholder="contoh@email.com" required>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label text-light opacity-75 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Password</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control bg-dark text-white border-secondary" name="password" placeholder="••••••••" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning bg-gradient w-100 fw-bold py-3 rounded-pill shadow mb-4 text-dark text-uppercase" style="letter-spacing: 1px;">
                            <i class="fas fa-sign-in-alt me-2"></i> Login Sekarang
                        </button>
                    </form>

                    <p class="text-center mb-0 small text-light opacity-75 pt-3 border-top border-secondary">
                        Belum punya skuad? <br>
                        <a href="<?= base_url('/register'); ?>" class="text-warning text-decoration-none fw-bold">
                            <i class="fas fa-user-plus me-1"></i> Daftar Akun Baru
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