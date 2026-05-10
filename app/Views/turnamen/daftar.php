<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6"> <?php if(session()->getFlashdata('error')): ?>
                <div class="alert bg-danger text-white alert-dismissible fade show py-3 border-0 shadow-sm rounded-4 mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2 fs-5 align-middle"></i> 
                    <span class="align-middle fw-bold"><?= session()->getFlashdata('error'); ?></span>
                    <button type="button" class="btn-close btn-close-white px-2 py-3" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card bg-black text-white border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="bg-gradient bg-success" style="height: 4px; width: 100%;"></div>
                
                <div class="card-body p-4 p-sm-5">
                    
                    <div class="text-center mb-4">
                        <div class="d-inline-block bg-dark p-3 rounded-circle mb-3 border border-secondary shadow-sm">
                            <i class="fas fa-edit fa-2x text-success"></i>
                        </div>
                        <h4 class="card-title fw-bolder text-uppercase mb-1" style="letter-spacing: 1px;">Join <span class="text-success">Tournament</span></h4>
                        <p class="text-light opacity-50 small">Lengkapi data tim kamu untuk mendaftar</p>
                    </div>
                    
                    <div class="alert bg-dark border border-secondary text-light mb-4 p-3 rounded-3 shadow-sm d-flex align-items-center">
                        <i class="fas fa-trophy fa-2x text-warning me-3"></i>
                        <div>
                            <small class="d-block text-warning fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">MENDAFTAR KE:</small>
                            <h5 class="fw-bold mb-0 text-white text-uppercase" style="letter-spacing: 0.5px;"><?= esc($turnamen['name']); ?></h5>
                        </div>
                    </div>

                    

                    <form action="<?= base_url('/turnamen/simpan'); ?>" method="POST">
                        <?= csrf_field(); ?> <input type="hidden" name="tournament_id" value="<?= $turnamen['id']; ?>">

                        <div class="mb-4">
    <label class="form-label text-warning small fw-bold text-uppercase" style="letter-spacing: 0.5px;">
        <i class="fas fa-exclamation-triangle me-1"></i> Rules & Regulation Turnamen
    </label>
    
    <div class="p-3 bg-dark border border-secondary rounded-3 text-light opacity-75 shadow-inner" style="max-height: 180px; overflow-y: auto; font-size: 0.85rem; white-space: pre-wrap; line-height: 1.6;"><?= esc($turnamen['rules'] ?? 'Belum ada aturan khusus yang ditetapkan oleh admin.'); ?></div>
    
    <div class="form-check mt-3 bg-black border border-secondary p-2 rounded-3 shadow-sm d-flex align-items-center">
        <input class="form-check-input bg-dark border-secondary ms-1 me-3 shadow-none" type="checkbox" id="agreeRules" required style="width: 1.2rem; height: 1.2rem;">
        <label class="form-check-label text-light small fw-bold" for="agreeRules" style="padding-top: 2px;">
            Saya telah membaca, memahami, dan menyetujui aturan di atas.
        </label>
    </div>
</div>

                        <div class="mb-4">
                            <label for="team_name" class="form-label text-light opacity-75 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Nama Tim / Squad</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fas fa-shield-alt"></i></span>
                                <input type="text" class="form-control bg-dark text-white border-secondary" id="team_name" name="team_name" placeholder="Contoh: Garuda eSports" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="in_game_name" class="form-label text-light opacity-75 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Nickname (IGN)</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fas fa-gamepad"></i></span>
                                <input type="text" class="form-control bg-dark text-white border-secondary" id="in_game_name" name="in_game_name" placeholder="Contoh: Budi_Gaming99" required>
                            </div>
                            <div class="form-text text-info small mt-1">
                                <i class="fas fa-info-circle me-1"></i>Sesuai dengan nama akun di dalam game.
                            </div>
                        </div>

                        <div class="mb-5">
                            <label for="in_game_id" class="form-label text-light opacity-75 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">ID Pemain (Angka)</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fas fa-id-badge"></i></span>
                                <input type="number" class="form-control bg-dark text-white border-secondary" id="in_game_id" name="in_game_id" placeholder="Contoh: 123456789" required>
                            </div>
                            <div class="form-text text-info small mt-1">
                                <i class="fas fa-info-circle me-1"></i>Digunakan lawan untuk mengirim permintaan Add Friend.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success bg-gradient w-100 fw-bold py-3 rounded-pill shadow mb-3 text-white text-uppercase" style="letter-spacing: 1px;">
                            <i class="fas fa-paper-plane me-2"></i> Kirim Pendaftaran
                        </button>
                        
                        <div class="text-center">
                            <a href="<?= base_url('/'); ?>" class="text-light text-decoration-none opacity-50 small">
                                <i class="fas fa-arrow-left me-1"></i> Batal & Kembali
                            </a>
                        </div>
                        
                    </form>

                </div>
            </div>
            
        </div>
    </div>
</div>

<?= $this->endSection(); ?>