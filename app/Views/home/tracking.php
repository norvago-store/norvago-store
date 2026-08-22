<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8" x-data="{ searchType: '<?= !empty($phone) ? 'phone' : 'invoice' ?>' }">
    
    <!-- Title Section -->
    <div class="text-center space-y-3">
        <div class="w-14 h-14 rounded-2xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center mx-auto shadow-lg shadow-cyan-500/20 border border-cyan-500/30">
            <i data-lucide="receipt" class="w-7 h-7"></i>
        </div>
        <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-white">
            Pesanan Saya &amp; Status Pengiriman
        </h1>
        <p class="text-xs sm:text-sm text-gray-400 max-w-lg mx-auto">
            Pantau status pembayaran QRIS dan pengiriman item game Anda secara real-time. Cukup masukkan Nomor Invoice atau Nomor WhatsApp Anda.
        </p>
    </div>

    <!-- Search / Filter Card -->
    <div class="bg-dark-card border border-gray-800 rounded-3xl p-6 sm:p-7 shadow-2xl space-y-4">
        
        <!-- Tab Type Switcher -->
        <div class="flex space-x-2 border-b border-gray-800 pb-3">
            <button type="button" @click="searchType = 'invoice'" :class="searchType === 'invoice' ? 'bg-cyan-500 text-white font-bold shadow-md shadow-cyan-500/25' : 'bg-dark-800 text-gray-400 hover:text-white border border-gray-700'" class="px-4 py-2 rounded-xl text-xs transition flex items-center space-x-1.5">
                <i data-lucide="hash" class="w-3.5 h-3.5"></i>
                <span>Cari via No. Invoice</span>
            </button>
            <button type="button" @click="searchType = 'phone'" :class="searchType === 'phone' ? 'bg-cyan-500 text-white font-bold shadow-md shadow-cyan-500/25' : 'bg-dark-800 text-gray-400 hover:text-white border border-gray-700'" class="px-4 py-2 rounded-xl text-xs transition flex items-center space-x-1.5">
                <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                <span>Cari via No. WhatsApp</span>
            </button>
        </div>

        <!-- Form via Invoice -->
        <form x-show="searchType === 'invoice'" action="<?= base_url('tracking') ?>" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-grow">
                <i data-lucide="receipt" class="w-5 h-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="invoice" value="<?= esc($invoiceNo ?? '') ?>" placeholder="Contoh: INV-20260823-1A2B3C" required class="w-full bg-dark-800 border border-gray-700 rounded-xl pl-11 pr-4 py-3.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 uppercase font-mono transition">
            </div>
            <button type="submit" class="px-6 py-3.5 rounded-xl font-heading font-bold text-sm text-white bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 shadow-lg shadow-cyan-500/25 transition flex items-center justify-center space-x-2">
                <i data-lucide="search" class="w-4 h-4"></i>
                <span>Cari Pesanan</span>
            </button>
        </form>

        <!-- Form via WhatsApp -->
        <form x-show="searchType === 'phone'" action="<?= base_url('tracking') ?>" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-grow">
                <i data-lucide="phone" class="w-5 h-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="tel" name="phone" value="<?= esc($phone ?? '') ?>" placeholder="Contoh: 081234567890" required class="w-full bg-dark-800 border border-gray-700 rounded-xl pl-11 pr-4 py-3.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono transition">
            </div>
            <button type="submit" class="px-6 py-3.5 rounded-xl font-heading font-bold text-sm text-white bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 shadow-lg shadow-cyan-500/25 transition flex items-center justify-center space-x-2">
                <i data-lucide="search" class="w-4 h-4"></i>
                <span>Cari Pesanan</span>
            </button>
        </form>

    </div>

    <!-- Orders List Results -->
    <?php if (!empty($orders)): ?>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-heading font-bold text-base text-white flex items-center space-x-2">
                    <i data-lucide="list-ordered" class="w-4 h-4 text-cyan-400"></i>
                    <span>Daftar Pesanan Ditemukan (<?= count($orders) ?>)</span>
                </h3>
            </div>

            <div class="space-y-4">
                <?php foreach ($orders as $ord): ?>
                    <div class="bg-dark-card border border-gray-800 rounded-3xl p-5 sm:p-6 shadow-xl hover:border-gray-700 transition space-y-4">
                        
                        <!-- Top Row: Invoice & Badges -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-800 pb-4">
                            <div>
                                <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">No. Invoice</span>
                                <div class="font-heading font-extrabold text-base sm:text-lg text-cyan-400 font-mono">
                                    #<?= esc($ord['invoice_no']) ?>
                                </div>
                                <span class="text-[11px] text-gray-500"><?= date('d M Y, H:i', strtotime($ord['created_at'])) ?> WIB</span>
                            </div>

                            <!-- Status Badges -->
                            <div class="flex flex-wrap items-center gap-2">
                                <!-- Payment Status Badge -->
                                <?php if (in_array($ord['payment_status'], ['paid', 'completed'])): ?>
                                    <span class="px-3 py-1 rounded-xl text-xs font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center space-x-1">
                                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                                        <span>PEMBAYARAN LUNAS</span>
                                    </span>
                                <?php elseif ($ord['payment_status'] === 'unpaid'): ?>
                                    <span class="px-3 py-1 rounded-xl text-xs font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center space-x-1 animate-pulse">
                                        <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                        <span>MENUNGGU PEMBAYARAN</span>
                                    </span>
                                <?php else: ?>
                                    <span class="px-3 py-1 rounded-xl text-xs font-bold bg-red-500/20 text-red-400 border border-red-500/30">
                                        <?= strtoupper(esc($ord['payment_status'])) ?>
                                    </span>
                                <?php endif; ?>

                                <!-- Delivery Status Badge -->
                                <?php if ($ord['delivery_status'] === 'success'): ?>
                                    <span class="px-3 py-1 rounded-xl text-xs font-bold bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 flex items-center space-x-1">
                                        <i data-lucide="check-check" class="w-3.5 h-3.5"></i>
                                        <span>PENGIRIMAN SUKSES</span>
                                    </span>
                                <?php elseif ($ord['delivery_status'] === 'processing'): ?>
                                    <span class="px-3 py-1 rounded-xl text-xs font-bold bg-blue-500/20 text-blue-300 border border-blue-500/30 flex items-center space-x-1 animate-pulse">
                                        <i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i>
                                        <span>SEDANG DIPROSES (1-10 MENIT)</span>
                                    </span>
                                <?php elseif ($ord['delivery_status'] === 'failed'): ?>
                                    <span class="px-3 py-1 rounded-xl text-xs font-bold bg-red-500/20 text-red-400 border border-red-500/30">
                                        PENGIRIMAN GAGAL
                                    </span>
                                <?php else: ?>
                                    <span class="px-3 py-1 rounded-xl text-xs font-bold bg-gray-700/40 text-gray-400 border border-gray-700">
                                        PENDING
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Middle Row: Product & Target Info -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
                            <div class="bg-dark-800 border border-gray-800 rounded-2xl p-3.5">
                                <span class="text-gray-400 block text-[10px]">Game &amp; Item</span>
                                <strong class="text-white block font-heading text-sm mt-0.5"><?= esc($ord['game_name']) ?></strong>
                                <span class="text-gray-300"><?= esc($ord['product_name']) ?></span>
                            </div>

                            <div class="bg-dark-800 border border-gray-800 rounded-2xl p-3.5">
                                <span class="text-gray-400 block text-[10px]">Target Akun</span>
                                <strong class="text-cyan-400 font-mono block text-sm mt-0.5">
                                    <?= esc($ord['target_user_id']) ?><?= $ord['target_zone_id'] ? ' ('.esc($ord['target_zone_id']).')' : '' ?>
                                </strong>
                                <?php if (!empty($ord['target_nickname'])): ?>
                                    <span class="text-emerald-400 font-semibold block text-[11px]"><?= esc($ord['target_nickname']) ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="bg-dark-800 border border-gray-800 rounded-2xl p-3.5">
                                <span class="text-gray-400 block text-[10px]">Total Pembayaran</span>
                                <strong class="text-brand-400 font-mono block text-sm mt-0.5">
                                    Rp <?= number_format($ord['total_amount'], 0, ',', '.') ?>
                                </strong>
                                <span class="text-gray-400 text-[10px]"><?= esc($ord['payment_name'] ?? 'QRIS Realtime') ?></span>
                            </div>

                            <div class="bg-dark-800 border border-gray-800 rounded-2xl p-3.5 flex flex-col justify-between">
                                <div>
                                    <span class="text-gray-400 block text-[10px]">Serial Number (SN)</span>
                                    <span class="font-mono text-white text-xs mt-0.5 block truncate">
                                        <?= !empty($ord['sn_code']) ? esc($ord['sn_code']) : '<span class="text-gray-500 italic">Dalam proses</span>' ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Processing Banner Notice (If Paid & In Delivery) -->
                        <?php if ($ord['payment_status'] === 'paid' && $ord['delivery_status'] === 'processing'): ?>
                            <div class="bg-gradient-to-r from-blue-950/80 to-cyan-950/80 border border-blue-500/40 rounded-2xl p-4 flex items-center space-x-3 text-xs text-blue-200">
                                <i data-lucide="loader-2" class="w-5 h-5 animate-spin text-cyan-400 flex-shrink-0"></i>
                                <div>
                                    <strong class="text-white block">Pembayaran Diterima! Pesanan Sedang Dikirim</strong>
                                    <span>Item top up sedang dikirimkan ke akun Anda. Estimasi waktu masuk <strong>1 - 10 menit</strong>.</span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Action Button Bar -->
                        <div class="flex items-center justify-between pt-1">
                            <span class="text-xs text-gray-400">
                                No. WhatsApp: <strong class="text-gray-300 font-mono"><?= esc($ord['customer_phone']) ?></strong>
                            </span>

                            <a href="<?= base_url('invoice/' . $ord['invoice_no']) ?>" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-heading font-bold text-white bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 shadow-md shadow-cyan-500/20 transition space-x-1.5">
                                <span>Buka Halaman Invoice &amp; QRIS</span>
                                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php elseif (!empty($invoiceNo) || !empty($phone)): ?>
        <div class="bg-dark-card border border-gray-800 rounded-3xl p-10 text-center space-y-3">
            <div class="w-12 h-12 rounded-2xl bg-red-500/10 text-red-400 flex items-center justify-center mx-auto">
                <i data-lucide="alert-triangle" class="w-6 h-6"></i>
            </div>
            <h3 class="font-heading font-bold text-lg text-white">Pesanan Tidak Ditemukan</h3>
            <p class="text-xs text-gray-400 max-w-sm mx-auto">
                Nomor invoice atau nomor WhatsApp yang Anda masukkan tidak terdaftar dalam sistem. Pastikan data yang dimasukkan sudah benar.
            </p>
        </div>
    <?php endif; ?>

</div>

<?= $this->endSection() ?>
