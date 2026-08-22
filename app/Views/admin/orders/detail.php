<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-heading font-extrabold text-2xl text-white">
                Detail Transaksi #<?= esc($order['invoice_no']) ?>
            </h1>
            <span class="text-xs text-slate-400">Dibuat pada: <?= date('d M Y, H:i:s', strtotime($order['created_at'])) ?> WIB</span>
        </div>
        <a href="<?= base_url('admin/orders') ?>" class="text-xs text-slate-400 hover:text-white font-bold">&larr; Kembali ke Daftar</a>
    </div>

    <!-- Quick Action Buttons -->
    <div class="bg-dark-card border border-slate-800 rounded-3xl p-5 shadow-xl flex flex-wrap items-center gap-3">
        <?php if ($order['payment_status'] === 'unpaid'): ?>
            <form action="<?= base_url('admin/orders/mark-paid/' . $order['id']) ?>" method="POST" onsubmit="return confirm('Konfirmasi bahwa dana telah diterima secara manual?')">
                <?= csrf_field() ?>
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-500 shadow-md shadow-emerald-600/20 transition flex items-center">
                    <i data-lucide="check-circle-2" class="w-4 h-4 mr-1.5"></i> Konfirmasi Terima Pembayaran
                </button>
            </form>
        <?php endif; ?>

        <a href="<?= base_url('invoice/' . $order['invoice_no']) ?>" target="_blank" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-300 bg-dark-800 hover:bg-dark-700 border border-slate-700 transition flex items-center">
            <i data-lucide="external-link" class="w-4 h-4 mr-1.5"></i> Buka Halaman Invoice Customer
        </a>

        <!-- Delete Order Button -->
        <form action="<?= base_url('admin/orders/delete/' . $order['id']) ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi #<?= esc($order['invoice_no']) ?> secara permanen?')" class="sm:ml-auto">
            <?= csrf_field() ?>
            <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-red-400 hover:text-white bg-red-500/10 hover:bg-red-600 border border-red-500/30 transition flex items-center">
                <i data-lucide="trash-2" class="w-4 h-4 mr-1.5"></i> Hapus Transaksi Ini
            </button>
        </form>
    </div>

    <!-- Manual Delivery Processing Card -->
    <?php if ($order['payment_status'] === 'paid' && $order['delivery_status'] !== 'success'): ?>
        <div class="bg-gradient-to-r from-cyan-950/80 to-dark-card border border-cyan-500/40 rounded-3xl p-5 shadow-xl space-y-3">
            <div class="flex items-center space-x-2">
                <i data-lucide="send" class="w-5 h-5 text-cyan-400"></i>
                <h3 class="font-heading font-bold text-sm text-white">Pesanan Siap Dikirim (Pengiriman Manual)</h3>
            </div>
            <p class="text-xs text-slate-300">
                Pembayaran telah lunas. Silakan isi Diamond ke ID: <strong class="text-white font-mono"><?= esc($order['target_user_id']) ?><?= $order['target_zone_id'] ? ' ('.$order['target_zone_id'].')' : '' ?></strong>, lalu masukkan Serial Number (SN) di bawah untuk menyelesaikan pesanan.
            </p>
            <form action="<?= base_url('admin/orders/update-status/' . $order['id']) ?>" method="POST" class="flex flex-col sm:flex-row items-center gap-3 pt-1">
                <?= csrf_field() ?>
                <input type="hidden" name="payment_status" value="paid">
                <input type="hidden" name="delivery_status" value="success">
                <input type="text" name="provider_sn" value="SN-<?= date('YmdHi') ?>-<?= strtoupper(bin2hex(random_bytes(3))) ?>" required placeholder="Masukkan Serial Number (SN)" class="w-full sm:w-80 bg-dark-900 border border-slate-700 rounded-xl px-4 py-2.5 text-xs text-white font-mono focus:outline-none focus:border-cyan-500">
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl font-heading font-bold text-xs text-white bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 shadow-md shadow-emerald-500/20 transition flex items-center justify-center space-x-1.5 flex-shrink-0">
                    <i data-lucide="check-circle-2" class="w-4 h-4 mr-1"></i>
                    <span>✓ Selesaikan Pengiriman &amp; Kirim SN</span>
                </button>
            </form>
        </div>
    <?php endif; ?>

    <!-- Order Data Breakdown -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Customer & Account Data -->
        <div class="bg-dark-card border border-slate-800 rounded-3xl p-6 shadow-xl space-y-4">
            <h3 class="font-heading font-bold text-base text-cyan-400 flex items-center">
                <i data-lucide="user" class="w-4 h-4 mr-2"></i> Data Akun &amp; Pelanggan
            </h3>

            <div class="space-y-3 text-xs">
                <div class="flex justify-between py-1 border-b border-slate-800">
                    <span class="text-slate-400">Game:</span>
                    <span class="font-bold text-white"><?= esc($order['game_name']) ?></span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-800">
                    <span class="text-slate-400"><?= esc($order['target_input_label_1'] ?: 'User ID') ?>:</span>
                    <span class="font-mono font-bold text-cyan-400">
                        <?= esc($order['target_user_id']) ?>
                        <?= !empty($order['target_zone_id']) ? '(' . esc($order['target_zone_id']) . ')' : '' ?>
                    </span>
                </div>
                <?php if (!empty($order['target_nickname'])): ?>
                    <div class="flex justify-between py-1 border-b border-slate-800">
                        <span class="text-slate-400">Nickname Cek ID:</span>
                        <span class="font-bold text-emerald-400"><?= esc($order['target_nickname']) ?></span>
                    </div>
                <?php endif; ?>
                <div class="flex justify-between py-1 border-b border-slate-800">
                    <span class="text-slate-400">No. WhatsApp:</span>
                    <span class="font-bold text-white font-mono"><?= esc($order['customer_phone']) ?></span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-slate-400">Waktu Kedaluwarsa:</span>
                    <span class="text-slate-300"><?= $order['expires_at'] ? date('d/m/Y H:i', strtotime($order['expires_at'])) : '-' ?></span>
                </div>
            </div>
        </div>

        <!-- Payment & Delivery Breakdown -->
        <div class="bg-dark-card border border-slate-800 rounded-3xl p-6 shadow-xl space-y-4">
            <h3 class="font-heading font-bold text-base text-cyan-400 flex items-center">
                <i data-lucide="credit-card" class="w-4 h-4 mr-2"></i> Rincian Finansial &amp; Provider
            </h3>

            <div class="space-y-3 text-xs">
                <div class="flex justify-between py-1 border-b border-slate-800">
                    <span class="text-slate-400">Item Produk:</span>
                    <span class="font-bold text-white"><?= esc($order['product_name']) ?></span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-800">
                    <span class="text-slate-400">Metode Bayar:</span>
                    <span class="font-bold text-white"><?= esc($order['payment_name']) ?></span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-800">
                    <span class="text-slate-400">Harga Item:</span>
                    <span class="text-white">Rp <?= number_format($order['price_product'], 0, ',', '.') ?></span>
                </div>
                <?php if ($order['unique_code'] > 0): ?>
                    <div class="flex justify-between py-1 border-b border-slate-800">
                        <span class="text-slate-400">Kode Unik:</span>
                        <span class="text-amber-400 font-bold">+Rp <?= esc($order['unique_code']) ?></span>
                    </div>
                <?php endif; ?>
                <div class="flex justify-between py-1 border-b border-slate-800 font-bold text-sm">
                    <span class="text-white">Total Tagihan:</span>
                    <span class="text-cyan-400 font-mono">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-slate-400">Serial Number (SN):</span>
                    <span class="font-mono text-emerald-400 font-bold text-[11px]"><?= esc($order['provider_sn'] ?: 'Belum ada SN') ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Manual Status Override Form -->
    <div class="bg-dark-card border border-slate-800 rounded-3xl p-6 shadow-xl space-y-4">
        <h3 class="font-heading font-bold text-base text-white">Ubah Status / Override Manual</h3>
        
        <form action="<?= base_url('admin/orders/update-status/' . $order['id']) ?>" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
            <?= csrf_field() ?>

            <div>
                <label class="block font-bold text-slate-300 mb-1.5">Status Pembayaran</label>
                <select name="payment_status" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-cyan-500">
                    <option value="unpaid" <?= ($order['payment_status'] === 'unpaid') ? 'selected' : '' ?>>Unpaid</option>
                    <option value="paid" <?= ($order['payment_status'] === 'paid') ? 'selected' : '' ?>>Paid</option>
                    <option value="expired" <?= ($order['payment_status'] === 'expired') ? 'selected' : '' ?>>Expired</option>
                    <option value="cancelled" <?= ($order['payment_status'] === 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1.5">Status Pengiriman Provider</label>
                <select name="delivery_status" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-cyan-500">
                    <option value="pending" <?= ($order['delivery_status'] === 'pending') ? 'selected' : '' ?>>Pending</option>
                    <option value="processing" <?= ($order['delivery_status'] === 'processing') ? 'selected' : '' ?>>Processing</option>
                    <option value="success" <?= ($order['delivery_status'] === 'success') ? 'selected' : '' ?>>Success</option>
                    <option value="failed" <?= ($order['delivery_status'] === 'failed') ? 'selected' : '' ?>>Failed</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1.5">Input / Update SN Topup</label>
                <input type="text" name="provider_sn" value="<?= esc($order['provider_sn'] ?? '') ?>" placeholder="Input Serial Number" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-cyan-500 font-mono">
            </div>

            <div class="sm:col-span-3 flex justify-end pt-2">
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-slate-700 hover:bg-slate-600 transition">
                    Simpan Perubahan Status
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
