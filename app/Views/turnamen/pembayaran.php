<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card bg-black border-warning text-white shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-warning text-dark text-center py-3">
                    <h5 class="fw-black mb-0 text-uppercase">Langkah Terakhir!</h5>
                </div>
                <div class="card-body p-4 text-center">
                    <p class="text-light opacity-75 small">Selesaikan pembayaran untuk mengonfirmasi slot tim kamu di:</p>
                    <h4 class="fw-bold text-warning mb-4"><?= esc($turnamen['name']); ?></h4>

                    <div class="bg-dark p-4 rounded-4 border border-secondary mb-4">
                        <span class="text-light opacity-50 small d-block mb-2">TRANSFER KE REKENING:</span>
                        <h2 class="fw-black mb-1">BNI 1929585465</h2>
                        <h5 class="fw-bold text-info mb-3">A/N FADHILAH AKBAR</h5>
                        <hr class="border-secondary">
                        <h6 class="mb-0">NOMINAL: <span class="text-warning fw-bold">Rp <?= number_format($turnamen['prize'] * 0.1, 0, ',', '.'); ?></span></h6> 
                        <p class="small text-secondary mt-2">*Ganti dengan nominal biaya pendaftaranmu</p>
                    </div>

                    <div class="alert bg-primary bg-opacity-10 border-primary text-primary small rounded-3">
                        <i class="fas fa-camera me-2"></i> <strong>PENTING:</strong> Silakan screenshot halaman ini sebagai bukti pendaftaran sementara.
                    </div>

                    <?php 
                        // Membuat link WhatsApp otomatis
                        $wa_number = "628123456789"; // Ganti dengan nomor WA kamu
                        $pesan = "Halo Admin, saya ingin konfirmasi pembayaran pendaftaran turnamen.\n\n"
                               . "Turnamen: " . $turnamen['name'] . "\n"
                               . "Nama Tim: " . $tim['team_name'] . "\n"
                               . "ID Tim: #" . $tim['id'] . "\n\n"
                               . "Berikut saya lampirkan bukti transfernya.";
                        $wa_link = "https://wa.me/6285750665742" . $wa_number . "?text=" . urlencode($pesan);
                    ?>

                    <a href="<?= $wa_link; ?>" target="_blank" class="btn btn-success btn-lg w-100 rounded-pill fw-bold py-3 shadow">
                        <i class="fab fa-whatsapp me-2"></i> Kirim Bukti ke WhatsApp
                    </a>

                    <a href="<?= base_url('/tim-saya'); ?>" class="btn btn-link text-secondary text-decoration-none mt-3 small">
                        Nanti saja, ke halaman Tim Saya
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>