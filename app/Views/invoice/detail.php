<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{
    invoiceNo: '<?= esc($order['invoice_no']) ?>',
    paymentStatus: '<?= esc($order['payment_status']) ?>',
    deliveryStatus: '<?= esc($order['delivery_status']) ?>',
    providerSn: '<?= esc($order['provider_sn'] ?? '') ?>',
    remainingSeconds: <?= $remainingSeconds ?>,
    copiedTotal: false,
    copiedAccount: false,
    pollingInterval: null,

    apiUrl(endpoint) {
        endpoint = endpoint.replace(/^\//, '');
        let base = '<?= base_url() ?>';
        if (!base.endsWith('/')) base += '/';
        if (window.location.origin && !base.startsWith(window.location.origin)) {
            let basePath = '<?= parse_url(base_url(), PHP_URL_PATH) ?: '/' ?>';
            if (!basePath.endsWith('/')) basePath += '/';
            return window.location.origin + basePath + endpoint;
        }
        return base + endpoint;
    },

    init() {
        // Countdown timer
        if (this.paymentStatus === 'unpaid' && this.remainingSeconds > 0) {
            let timer = setInterval(() => {
                if (this.remainingSeconds > 0) {
                    this.remainingSeconds--;
                } else {
                    clearInterval(timer);
                    this.paymentStatus = 'expired';
                }
            }, 1000);
        }

        // Live status polling if unpaid or if delivery is not finished yet
        if (this.paymentStatus === 'unpaid' || this.deliveryStatus !== 'success') {
            this.pollingInterval = setInterval(() => {
                this.checkStatus();
            }, 3000);
        }
    },

    formatTime(seconds) {
        let mins = Math.floor(seconds / 60);
        let secs = seconds % 60;
        return (mins < 10 ? '0' : '') + mins + ':' + (secs < 10 ? '0' : '') + secs;
    },

    copyToClipboard(text, type) {
        navigator.clipboard.writeText(text);
        if (type === 'total') {
            this.copiedTotal = true;
            setTimeout(() => { this.copiedTotal = false }, 2000);
        } else if (type === 'account') {
            this.copiedAccount = true;
            setTimeout(() => { this.copiedAccount = false }, 2000);
        }
    },

    async checkStatus() {
        try {
            let res = await fetch(this.apiUrl('invoice/check-status/' + this.invoiceNo));
            let data = await res.json();
            if (data.status === 'success') {
                if (data.is_paid) {
                    this.paymentStatus = data.payment_status;
                    this.deliveryStatus = data.delivery_status;
                    this.providerSn = data.provider_sn;
                    if (data.delivery_status === 'success') {
                        clearInterval(this.pollingInterval);
                    }
                }
            }
        } catch (e) {
            console.log('Polling error:', e);
        }
    }
}">

    <div class="space-y-6">

        <!-- Top Header Status -->
        <div class="bg-dark-card border border-gray-800 rounded-3xl p-6 sm:p-8 shadow-xl">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-800 pb-6">
                <div>
                    <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider block">INVOICE TRANSAKSI</span>
                    <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-white mt-1">
                        #<?= esc($order['invoice_no']) ?>
                    </h1>
                    <span class="text-xs text-gray-400 block mt-1">Waktu Pesanan: <?= date('d M Y, H:i', strtotime($order['created_at'])) ?> WIB</span>
                </div>

                <!-- Status Badges -->
                <div class="flex items-center space-x-2">
                    <!-- Payment Status Badge -->
                    <template x-if="paymentStatus === 'paid' || paymentStatus === 'completed'">
                        <span class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center">
                            <i data-lucide="check-circle-2" class="w-4 h-4 mr-1.5"></i> LUNAS
                        </span>
                    </template>
                    <template x-if="paymentStatus === 'unpaid'">
                        <span class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center animate-pulse">
                            <i data-lucide="clock" class="w-4 h-4 mr-1.5"></i> MENUNGGU PEMBAYARAN
                        </span>
                    </template>
                    <template x-if="paymentStatus === 'expired'">
                        <span class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-red-500/20 text-red-400 border border-red-500/30 flex items-center">
                            <i data-lucide="x-circle" class="w-4 h-4 mr-1.5"></i> KEDALUWARSA
                        </span>
                    </template>

                    <!-- Delivery Status Badge -->
                    <template x-if="deliveryStatus === 'success'">
                        <span class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 flex items-center">
                            <i data-lucide="check" class="w-4 h-4 mr-1.5"></i> TOP UP SUKSES
                        </span>
                    </template>
                    <template x-if="(paymentStatus === 'paid' || paymentStatus === 'completed') && deliveryStatus !== 'success'">
                        <span class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center animate-pulse">
                            <i data-lucide="loader-2" class="w-4 h-4 mr-1.5 animate-spin"></i> SEDANG DIPROSES (1-10 MENIT)
                        </span>
                    </template>
                </div>
            </div>

            <!-- 1. PAID & DELIVERY SUCCESS STATE BANNER -->
            <div x-show="(paymentStatus === 'paid' || paymentStatus === 'completed') && deliveryStatus === 'success'" x-transition class="mt-6 bg-gradient-to-r from-emerald-950/80 to-dark-800 border border-emerald-500/40 rounded-2xl p-6 text-center space-y-3">
                <div class="w-16 h-16 bg-emerald-500/20 text-emerald-400 rounded-full flex items-center justify-center mx-auto shadow-lg shadow-emerald-500/20">
                    <i data-lucide="check-circle" class="w-10 h-10"></i>
                </div>
                <h3 class="font-heading font-extrabold text-xl sm:text-2xl text-white">Pembayaran Sukses &amp; Diamond Berhasil Dikirim!</h3>
                <p class="text-xs sm:text-sm text-gray-300 max-w-md mx-auto">
                    Pesanan Anda telah selesai diproses ke akun game. Terima kasih telah melakukan top up di <strong class="text-white"><?= esc($settings['site_name'] ?? 'Norvago') ?></strong>!
                </p>

                <div x-show="providerSn" class="bg-dark-900/80 border border-gray-700 rounded-xl p-3 max-w-md mx-auto text-xs font-mono text-cyan-300">
                    <span class="text-gray-400 block text-[10px]">Nomor Seri / Serial Number (SN):</span>
                    <strong x-text="providerSn"></strong>
                </div>

                <div class="pt-2">
                    <a href="<?= base_url('invoice/print/' . $order['invoice_no']) ?>" target="_blank" class="inline-flex items-center px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-dark-700 hover:bg-dark-600 border border-gray-600 transition">
                        <i data-lucide="printer" class="w-4 h-4 mr-1.5"></i> Cetak / Simpan Struk
                    </a>
                </div>
            </div>

            <!-- 2. PAID BUT DELIVERY IN PROGRESS (MANUAL 1-10 MINUTES) BANNER -->
            <div x-show="(paymentStatus === 'paid' || paymentStatus === 'completed') && deliveryStatus !== 'success'" x-transition class="mt-6 bg-gradient-to-r from-cyan-950/80 via-dark-800 to-dark-800 border border-cyan-500/40 rounded-2xl p-6 text-center space-y-3">
                <div class="w-16 h-16 bg-cyan-500/20 text-cyan-400 rounded-full flex items-center justify-center mx-auto shadow-lg shadow-cyan-500/20">
                    <i data-lucide="loader-2" class="w-8 h-8 animate-spin"></i>
                </div>
                <h3 class="font-heading font-extrabold text-xl sm:text-2xl text-white">Pembayaran Lunas! Sedang Dalam Proses Pengiriman</h3>
                <p class="text-xs sm:text-sm text-gray-300 max-w-lg mx-auto">
                    Pembayaran Anda telah berhasil kami terima. Item Diamond Anda saat ini sedang dalam <strong>antrian proses pengiriman manual</strong> oleh admin dengan estimasi waktu <strong>1 - 10 menit</strong>.
                </p>

                <div class="bg-dark-900/80 border border-cyan-500/30 rounded-xl p-3 max-w-md mx-auto text-xs text-cyan-300 flex items-center justify-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-cyan-400 animate-ping"></span>
                    <span>Halaman ini akan otomatis terupdate begitu Diamond selesai dikirim...</span>
                </div>

                <?php if (!empty($settings['whatsapp_cs'])): ?>
                    <div class="pt-2">
                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $settings['whatsapp_cs']) ?>?text=Halo%20Admin%2C%20saya%20sudah%20bayar%20Invoice%20<?= urlencode($order['invoice_no']) ?>" target="_blank" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-500 transition shadow-md shadow-emerald-600/20">
                            <i data-lucide="message-circle" class="w-4 h-4 mr-1.5"></i> Hubungi WhatsApp Admin
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- UNPAID STATE & PAYMENT BOX -->
            <div x-show="paymentStatus === 'unpaid'" class="mt-6 space-y-6">
                
                <!-- Countdown Banner -->
                <div class="bg-dark-800 border border-amber-500/30 rounded-2xl p-4 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
                    <div class="flex items-center space-x-3">
                        <i data-lucide="alarm-clock" class="w-6 h-6 text-amber-400 animate-bounce"></i>
                        <div>
                            <span class="text-xs text-gray-400 block">Batas Waktu Pembayaran:</span>
                            <span class="font-bold text-sm text-white">Selesaikan pembayaran sebelum waktu habis</span>
                        </div>
                    </div>
                    <div class="font-heading font-extrabold text-2xl text-amber-400 tracking-wider bg-dark-900 px-4 py-2 rounded-xl border border-gray-700" x-text="formatTime(remainingSeconds)">
                        15:00
                    </div>
                </div>

                <!-- QRIS / Payment Channel Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                    
                    <!-- Left: QR Code or Bank Info -->
                    <div class="bg-dark-800 border border-gray-700 rounded-2xl p-6 text-center space-y-4">
                        <?php if ($order['payment_type'] === 'qris' && !empty($qrCodeDataUri)): ?>
                            <span class="text-xs font-bold text-brand-400 uppercase tracking-wider block">
                                SCAN QRIS DENGAN APLIKASI APAPUN
                            </span>
                            <div class="bg-white p-3 rounded-2xl inline-block shadow-xl max-w-[240px] w-full">
                                <img src="<?= $qrCodeDataUri ?>" alt="QRIS Code" class="w-full h-auto object-contain mx-auto">
                            </div>
                            <div class="text-[11px] text-gray-400">
                                Mendukung: <span class="text-gray-300 font-semibold">BCA, Mandiri, BRI, BNI, GoPay, OVO, DANA, ShopeePay, LinkAja</span>
                            </div>
                            <div>
                                <a href="<?= $qrCodeDataUri ?>" download="QRIS-<?= $order['invoice_no'] ?>.svg" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold text-white bg-dark-700 hover:bg-dark-600 border border-gray-600 transition">
                                    <i data-lucide="download" class="w-3.5 h-3.5 mr-1.5"></i> Unduh Gambar QR
                                </a>
                            </div>

                        <?php else: ?>
                            <!-- Bank Transfer / E-Wallet Info -->
                            <div class="space-y-4 text-left">
                                <span class="text-xs font-bold text-brand-400 uppercase tracking-wider block text-center">
                                    INFORMASI TRANSFER
                                </span>
                                <div class="bg-dark-900 border border-gray-700 rounded-xl p-4 space-y-3">
                                    <div>
                                        <span class="text-[10px] text-gray-400 block">Metode Pembayaran:</span>
                                        <span class="font-bold text-sm text-white"><?= esc($order['payment_name']) ?></span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-gray-400 block">Nomor Rekening / No. E-Wallet:</span>
                                        <div class="flex items-center justify-between mt-1">
                                            <span class="font-mono font-bold text-base text-cyan-400"><?= esc($order['account_number'] ?: '-') ?></span>
                                            <button @click="copyToClipboard('<?= esc($order['account_number']) ?>', 'account')" class="text-xs px-2.5 py-1 rounded bg-dark-700 hover:bg-dark-600 text-gray-300 hover:text-white border border-gray-600 transition">
                                                <span x-show="!copiedAccount">Salin</span>
                                                <span x-show="copiedAccount" class="text-emerald-400 font-bold">Tersalin!</span>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-gray-400 block">Atas Nama:</span>
                                        <span class="font-semibold text-xs text-gray-200"><?= esc($order['account_name'] ?: 'NORVAGO') ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Right: Total Nominal & Auto-refresh status -->
                    <div class="space-y-5">
                        <div class="bg-dark-800 border border-gray-700 rounded-2xl p-5 space-y-3">
                            <span class="text-xs text-gray-400 block font-medium">Jumlah yang Harus Dibayar:</span>
                            <div class="flex items-center justify-between">
                                <div class="font-heading font-extrabold text-3xl text-brand-400">
                                    Rp <?= number_format($order['total_amount'], 0, ',', '.') ?>
                                </div>
                                <button @click="copyToClipboard('<?= $order['total_amount'] ?>', 'total')" class="text-xs px-3 py-1.5 rounded-xl bg-brand-500/20 text-brand-300 hover:bg-brand-500 hover:text-white border border-brand-500/40 transition flex items-center space-x-1">
                                    <i data-lucide="copy" class="w-3.5 h-3.5 mr-1"></i>
                                    <span x-show="!copiedTotal">Salin Nominal</span>
                                    <span x-show="copiedTotal" class="text-emerald-300 font-bold">Tersalin!</span>
                                </button>
                            </div>

                            <?php if ($order['unique_code'] > 0): ?>
                                <div class="bg-amber-500/10 border border-amber-500/30 rounded-xl p-3 text-xs text-amber-300 leading-relaxed">
                                    ⚠️ <strong>PENTING:</strong> Pastikan Anda mentransfer <strong>PERSIS</strong> sesuai nominal di atas hingga 3 digit terakhir (<strong class="text-white"><?= esc($order['unique_code']) ?></strong>) agar sistem dapat memverifikasi otomatis tanpa konfirmasi manual.
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Auto Polling Indicator -->
                        <div class="bg-dark-900/60 border border-gray-800 rounded-xl p-3.5 flex items-center space-x-3 text-xs text-gray-400">
                            <div class="w-3 h-3 rounded-full bg-brand-400 animate-ping flex-shrink-0"></div>
                            <span>Menunggu pembayaran masuk... Halaman akan otomatis diperbarui begitu Anda selesai membayar.</span>
                        </div>

                        <!-- Manual Refresh Status Button -->
                        <button type="button" @click="checkStatus" class="w-full py-3 rounded-xl font-heading font-bold text-xs text-white bg-dark-800 hover:bg-brand-500/20 border border-gray-700 hover:border-brand-500/50 transition flex items-center justify-center space-x-2">
                            <i data-lucide="refresh-cw" class="w-3.5 h-3.5 text-brand-400"></i>
                            <span>Saya Sudah Bayar (Cek Status Sekarang)</span>
                        </button>
                    </div>

                </div>

            </div>

            <!-- EXPIRED STATE -->
            <div x-show="paymentStatus === 'expired'" class="mt-6 bg-red-950/60 border border-red-500/40 rounded-2xl p-6 text-center space-y-3">
                <i data-lucide="clock-alert" class="w-12 h-12 text-red-400 mx-auto"></i>
                <h3 class="font-heading font-extrabold text-xl text-white">Invoice Ini Telah Kedaluwarsa</h3>
                <p class="text-xs text-gray-400">Batas waktu pembayaran 15 menit telah habis. Silakan buat pesanan baru.</p>
                <div class="pt-2">
                    <a href="<?= base_url('order/' . $order['game_slug']) ?>" class="inline-flex items-center px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-brand-500 hover:bg-brand-600 transition">
                        Pesan Ulang
                    </a>
                </div>
            </div>
        </div>

        <!-- Order Summary Details Card -->
        <div class="bg-dark-card border border-gray-800 rounded-3xl p-6 sm:p-8 shadow-xl space-y-4">
            <h3 class="font-heading font-bold text-lg text-white flex items-center">
                <i data-lucide="receipt" class="w-5 h-5 text-brand-400 mr-2"></i> Rincian Pesanan
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs sm:text-sm">
                <div class="bg-dark-800 border border-gray-800 rounded-2xl p-4 space-y-2.5">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Game:</span>
                        <span class="font-bold text-white"><?= esc($order['game_name']) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400"><?= esc($order['target_input_label_1'] ?: 'User ID') ?>:</span>
                        <span class="font-bold text-cyan-400">
                            <?= esc($order['target_user_id']) ?>
                            <?= !empty($order['target_zone_id']) ? '(' . esc($order['target_zone_id']) . ')' : '' ?>
                            <?= !empty($order['target_server']) ? '(' . esc($order['target_server']) . ')' : '' ?>
                        </span>
                    </div>
                    <?php if (!empty($order['target_nickname'])): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Nickname:</span>
                            <span class="font-bold text-emerald-400"><?= esc($order['target_nickname']) ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="flex justify-between">
                        <span class="text-gray-400">No. WhatsApp:</span>
                        <span class="font-bold text-white"><?= esc($order['customer_phone']) ?></span>
                    </div>
                </div>

                <div class="bg-dark-800 border border-gray-800 rounded-2xl p-4 space-y-2.5">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Item Produk:</span>
                        <span class="font-bold text-white"><?= esc($order['product_name']) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Harga Item:</span>
                        <span class="text-gray-200">Rp <?= number_format($order['price_product'], 0, ',', '.') ?></span>
                    </div>
                    <?php if ($order['price_fee'] > 0): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Biaya Admin:</span>
                            <span class="text-gray-200">Rp <?= number_format($order['price_fee'], 0, ',', '.') ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($order['unique_code'] > 0): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Kode Unik:</span>
                            <span class="text-amber-400 font-bold font-mono">+Rp <?= sprintf('%03d', $order['unique_code']) ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($order['discount_amount'] > 0): ?>
                        <div class="flex justify-between">
                            <span class="text-emerald-400">Potongan Diskon:</span>
                            <span class="text-emerald-400 font-bold">-Rp <?= number_format($order['discount_amount'], 0, ',', '.') ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="border-t border-gray-700 pt-2 flex justify-between items-center font-bold">
                        <span class="text-white">Total Tagihan:</span>
                        <span class="text-brand-400 font-heading text-base">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions Accordion -->
        <?php if (!empty($order['payment_instructions'])): ?>
            <div class="bg-dark-card border border-gray-800 rounded-3xl p-6 sm:p-8 shadow-xl space-y-3">
                <h3 class="font-heading font-bold text-base text-white flex items-center">
                    <i data-lucide="info" class="w-5 h-5 text-brand-400 mr-2"></i> Petunjuk Pembayaran:
                </h3>
                <div class="text-xs text-gray-300 leading-relaxed whitespace-pre-line bg-dark-800/80 p-4 rounded-2xl border border-gray-800">
                    <?= esc($order['payment_instructions']) ?>
                </div>
            </div>
        <?php endif; ?>

    </div>

</div>

<?= $this->endSection() ?>
