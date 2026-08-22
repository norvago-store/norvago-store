<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-heading font-extrabold text-2xl text-white">Kelola Transaksi &amp; Pesanan</h1>
            <p class="text-xs text-slate-400">Pantau transaksi customer, konfirmasi pembayaran, cetak rekap PDF, dan kelola pesanan</p>
        </div>

        <!-- Print PDF Report CTA Button -->
        <div>
            <?php 
                $pdfParams = http_build_query([
                    'search'     => $search ?? '',
                    'status'     => $status ?? '',
                    'date_start' => $dateStart ?? '',
                    'date_end'   => $dateEnd ?? '',
                ]);
            ?>
            <a href="<?= base_url('admin/orders/export-pdf?' . $pdfParams) ?>" target="_blank" class="inline-flex items-center px-4 py-2.5 rounded-xl font-heading font-bold text-xs text-white bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 shadow-lg shadow-cyan-500/25 transition space-x-2">
                <i data-lucide="printer" class="w-4 h-4"></i>
                <span>Cetak / Download Rekap PDF</span>
            </a>
        </div>
    </div>

    <!-- Quick Summary KPI Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-dark-card border border-slate-800 rounded-2xl p-4 shadow-lg">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Transaksi</span>
            <span class="font-heading font-extrabold text-xl text-white mt-1 block"><?= count($orders) ?> Pesanan</span>
        </div>

        <div class="bg-dark-card border border-emerald-500/30 rounded-2xl p-4 shadow-lg">
            <span class="text-[11px] font-bold text-emerald-400 uppercase tracking-wider block">Total Omset (Lunas)</span>
            <span class="font-heading font-extrabold text-xl text-emerald-400 mt-1 block">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></span>
        </div>

        <div class="bg-dark-card border border-cyan-500/30 rounded-2xl p-4 shadow-lg">
            <span class="text-[11px] font-bold text-cyan-400 uppercase tracking-wider block">Transaksi Lunas</span>
            <span class="font-heading font-extrabold text-xl text-cyan-400 mt-1 block"><?= number_format($totalPaid, 0, ',', '.') ?> Pesanan</span>
        </div>

        <div class="bg-dark-card border border-amber-500/30 rounded-2xl p-4 shadow-lg">
            <span class="text-[11px] font-bold text-amber-400 uppercase tracking-wider block">Menunggu Bayar</span>
            <span class="font-heading font-extrabold text-xl text-amber-400 mt-1 block"><?= number_format($totalPending, 0, ',', '.') ?> Pesanan</span>
        </div>
    </div>

    <!-- Filter & Search Box -->
    <div class="bg-dark-card border border-slate-800 rounded-2xl p-4 shadow-xl">
        <form action="<?= base_url('admin/orders') ?>" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div class="relative lg:col-span-2">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="<?= esc($search ?? '') ?>" placeholder="Cari invoice, user ID, no WA, nickname..." class="w-full bg-dark-800 border border-slate-700 rounded-xl pl-9 pr-3 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
            </div>

            <div>
                <select name="status" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-500">
                    <option value="">-- Semua Status Bayar --</option>
                    <option value="unpaid" <?= ($status === 'unpaid') ? 'selected' : '' ?>>Menunggu Bayar (Unpaid)</option>
                    <option value="paid" <?= ($status === 'paid') ? 'selected' : '' ?>>Lunas (Paid)</option>
                    <option value="expired" <?= ($status === 'expired') ? 'selected' : '' ?>>Kedaluwarsa (Expired)</option>
                    <option value="cancelled" <?= ($status === 'cancelled') ? 'selected' : '' ?>>Dibatalkan (Cancelled)</option>
                </select>
            </div>

            <div>
                <input type="date" name="date_start" value="<?= esc($dateStart ?? '') ?>" placeholder="Dari Tanggal" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-300 focus:outline-none focus:border-cyan-500">
            </div>

            <div class="flex items-center space-x-2">
                <input type="date" name="date_end" value="<?= esc($dateEnd ?? '') ?>" placeholder="Sampai Tanggal" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-300 focus:outline-none focus:border-cyan-500">
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-cyan-600 hover:bg-cyan-500 transition shadow-md shadow-cyan-600/20">
                    Filter
                </button>
                <?php if (!empty($search) || !empty($status) || !empty($dateStart) || !empty($dateEnd)): ?>
                    <a href="<?= base_url('admin/orders') ?>" class="px-3 py-2 rounded-xl text-xs font-bold text-slate-400 hover:text-white bg-dark-800 hover:bg-dark-700 transition" title="Reset Filter">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-dark-card border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-dark-800 text-slate-400 uppercase text-[10px] font-bold border-b border-slate-800">
                    <tr>
                        <th class="py-3.5 px-4">Invoice</th>
                        <th class="py-3.5 px-4">Game &amp; Item</th>
                        <th class="py-3.5 px-4">Target Akun</th>
                        <th class="py-3.5 px-4">WhatsApp</th>
                        <th class="py-3.5 px-4">Total</th>
                        <th class="py-3.5 px-4">Status Bayar</th>
                        <th class="py-3.5 px-4">Pengiriman</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-slate-300">
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $ord): ?>
                            <tr class="hover:bg-dark-800/50 transition">
                                <td class="py-3.5 px-4 font-mono font-bold text-cyan-400">
                                    <a href="<?= base_url('admin/orders/detail/' . $ord['id']) ?>" class="hover:underline">
                                        #<?= esc($ord['invoice_no']) ?>
                                    </a>
                                    <span class="text-[10px] text-slate-500 block font-normal mt-0.5"><?= date('d/m/y H:i', strtotime($ord['created_at'])) ?> WIB</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <strong class="text-white block font-heading"><?= esc($ord['game_name']) ?></strong>
                                    <span class="text-xs text-slate-400"><?= esc($ord['product_name']) ?></span>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-xs">
                                    <?= esc($ord['target_user_id']) ?><?= $ord['target_zone_id'] ? ' ('.$ord['target_zone_id'].')' : '' ?>
                                    <?php if (!empty($ord['target_nickname'])): ?>
                                        <span class="text-[10px] text-emerald-400 block font-bold font-sans"><?= esc($ord['target_nickname']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-xs text-slate-400">
                                    <?= esc($ord['customer_phone']) ?>
                                </td>
                                <td class="py-3.5 px-4 font-bold text-white font-mono">
                                    Rp <?= number_format($ord['total_amount'], 0, ',', '.') ?>
                                </td>
                                <td class="py-3.5 px-4">
                                    <?php if (in_array($ord['payment_status'], ['paid', 'completed'])): ?>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">LUNAS</span>
                                    <?php elseif ($ord['payment_status'] === 'unpaid'): ?>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30">PENDING</span>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-500/20 text-red-400 border border-red-500/30"><?= strtoupper($ord['payment_status']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3.5 px-4">
                                    <?php if ($ord['delivery_status'] === 'success'): ?>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-500/20 text-cyan-400 border border-cyan-500/30">SUKSES</span>
                                    <?php elseif ($ord['delivery_status'] === 'processing'): ?>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/20 text-blue-400 border border-blue-500/30">PROSES</span>
                                    <?php elseif ($ord['delivery_status'] === 'failed'): ?>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-500/20 text-red-400 border border-red-500/30">GAGAL</span>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-500/20 text-slate-400 border border-slate-500/30">PENDING</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3.5 px-4 text-center space-x-1.5 whitespace-nowrap">
                                    <!-- Detail Button -->
                                    <a href="<?= base_url('admin/orders/detail/' . $ord['id']) ?>" class="px-2.5 py-1 rounded-lg bg-dark-700 hover:bg-cyan-600 text-white font-bold text-xs transition inline-block">
                                        Detail
                                    </a>

                                    <!-- Quick Mark Paid -->
                                    <?php if ($ord['payment_status'] === 'unpaid'): ?>
                                        <form action="<?= base_url('admin/orders/mark-paid/' . $ord['id']) ?>" method="POST" class="inline-block" onsubmit="return confirm('Konfirmasi terima pembayaran untuk order #<?= esc($ord['invoice_no']) ?>?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-600/30 hover:bg-emerald-600 text-emerald-300 hover:text-white font-bold text-xs transition border border-emerald-500/40">
                                                ✓ Bayar
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <!-- Delete Button -->
                                    <form action="<?= base_url('admin/orders/delete/' . $ord['id']) ?>" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi #<?= esc($ord['invoice_no']) ?> secara permanen?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="px-2 py-1 rounded-lg bg-red-500/10 hover:bg-red-600 text-red-400 hover:text-white font-bold text-xs transition border border-red-500/30" title="Hapus Transaksi">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5 inline-block"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-10 text-slate-500">
                                <i data-lucide="inbox" class="w-8 h-8 mx-auto text-slate-600 mb-2"></i>
                                Tidak ada data transaksi ditemukan sesuai kriteria filter.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
