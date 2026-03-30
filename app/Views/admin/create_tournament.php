<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert bg-danger text-white alert-dismissible fade show py-3 border-0 shadow-sm rounded-4 mb-4" role="alert">
                    <i class="fas fa-exclamation-triangle me-2 fs-5 align-middle"></i> 
                    <span class="align-middle fw-bold"><?= session()->getFlashdata('error'); ?></span>
                    <button type="button" class="btn-close btn-close-white px-2 py-3" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card bg-black text-white border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="bg-gradient bg-primary" style="height: 4px; width: 100%;"></div>
                
                <div class="card-body p-4 p-sm-5">
                    
                    <div class="text-center mb-4">
                        <div class="d-inline-block bg-dark p-3 rounded-circle mb-3 border border-secondary shadow-sm">
                            <i class="fas fa-plus-circle fa-2x text-primary"></i>
                        </div>
                        <h4 class="card-title fw-bolder text-uppercase mb-1" style="letter-spacing: 1px;">Buat <span class="text-primary">Turnamen</span></h4>
                        <p class="text-light opacity-50 small">Tambahkan turnamen eFootball baru ke dalam sistem</p>
                    </div>
                    
                    <form action="<?= base_url('/admin/store'); ?>" method="POST">
                        <?= csrf_field(); ?>
                        
                        <div class="mb-4">
                            <label for="name" class="form-label text-light opacity-75 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Nama Turnamen</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fas fa-trophy"></i></span>
                                <input type="text" class="form-control bg-dark text-white border-secondary" id="name" name="name" placeholder="Contoh: eFootball Ramadan Cup" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label text-light opacity-75 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Deskripsi / Aturan Singkat</label>
                            <textarea class="form-control bg-dark text-white border-secondary rounded-3" id="description" name="description" rows="3" placeholder="Contoh: Kuota 32 Slot, Sistem Gugur, Hadiah Jutaan Rupiah..." required></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="rules" class="form-label text-light opacity-75 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Rules & Regulation Detail</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fas fa-scroll"></i></span>
                                <textarea class="form-control bg-dark text-white border-secondary rounded-end" id="rules" name="rules" rows="4" placeholder="1. Waktu tanding malam hari.&#10;2. Dilarang menggunakan formasi custom.&#10;3. DC = Kalah..." required></textarea>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-6">
                                <label for="quota" class="form-label text-light opacity-75 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Total Kuota Tim</label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-dark border-secondary text-warning"><i class="fas fa-layer-group"></i></span>
                                    <input type="number" id="quota" name="quota" class="form-control bg-dark text-white border-secondary" value="32" min="2" required>
                                </div>
                                <div class="form-text text-info small mt-1" style="font-size: 0.7rem;">Misal: Max 32 Tim bertanding.</div>
                            </div>

                            <div class="col-6">
                                <label for="max_slots" class="form-label text-light opacity-75 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Slot / Akun</label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fas fa-users-cog"></i></span>
                                    <input type="number" id="max_slots" name="max_slots" class="form-control bg-dark text-white border-secondary" value="1" min="1" required>
                                </div>
                                <div class="form-text text-info small mt-1" style="font-size: 0.7rem;">Berapa tim per 1 user.</div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label for="status" class="form-label text-light opacity-75 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Status Pendaftaran</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fas fa-door-open"></i></span>
                                <select class="form-select bg-dark text-white border-secondary fw-bold" id="status" name="status" required>
                                    <option value="open" class="text-success" selected>DIBUKA (Open)</option>
                                    <option value="ongoing" class="text-warning">BERJALAN (Ongoing)</option>
                                    <option value="completed" class="text-danger">SELESAI (Completed)</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary bg-gradient w-100 fw-bold py-3 rounded-pill shadow mb-4 text-white text-uppercase" style="letter-spacing: 1px;">
                            <i class="fas fa-paper-plane me-2"></i> Publish Turnamen
                        </button>
                    </form>

                    <div class="text-center border-top border-secondary pt-3">
                        <a href="<?= base_url('/'); ?>" class="text-light text-decoration-none opacity-50 small">
                            <i class="fas fa-arrow-left me-1"></i> Batal & Kembali ke Beranda
                        </a>
                    </div>
                    
                </div>
            </div>
            
        </div>
    </div>
</div>

<?= $this->endSection(); ?>