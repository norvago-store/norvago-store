<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-heading font-extrabold text-2xl text-white">Log Mutasi QRIS &amp; Bank</h1>
            <p class="text-xs text-slate-400">Riwayat dana masuk otomatis via webhook notifikasi bank/e-wallet</p>
        </div>
    </div>

    <!-- Webhook Integration Notice -->
    <div class="bg-gradient-to-r from-cyan-950/80 to-dark-card border border-cyan-500/40 rounded-3xl p-5 shadow-xl space-y-2">
        <div class="flex items-center space-x-2">
            <i data-lucide="webhook" class="w-5 h-5 text-cyan-400"></i>
            <h3 class="font-heading font-bold text-sm text-white">URL Endpoint Automation Webhook Anda</h3>
        </div>
        <p class="text-xs text-slate-300">
            Aplikasi listener di HP (Tasker/MacroDroid) atau bot scraper dapat menembak notifikasi dana masuk ke URL:
        </p>
        <div class="bg-dark-900 border border-slate-700 rounded-xl p-3 flex items-center justify-between font-mono text-xs text-cyan-300">
            <span><?= base_url('api/webhook/qris-mutation') ?></span>
            <span class="text-[10px] text-slate-500">Method: POST</span>
        </div>
    </div>

    <!-- Mutations Table -->
    <div class="bg-dark-card border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-dark-800 text-slate-400 uppercase text-[10px] font-bold border-b border-slate-800">
                    <tr>
                        <th class="py-3.5 px-4">Waktu</th>
                        <th class="py-3.5 px-4">Sumber</th>
                        <th class="py-3.5 px-4">Nominal Masuk</th>
                        <th class="py-3.5 px-4">Keterangan / Raw Payload</th>
                        <th class="py-3.5 px-4">Order Terkait</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Aksi Manual</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-slate-300">
                    <?php if (!empty($mutations)): ?>
                        <?php foreach ($mutations as $m): ?>
                            <tr class="hover:bg-dark-800/50 transition">
                                <td class="py-3.5 px-4 whitespace-nowrap text-slate-400">
                                    <?= date('d/m/Y H:i:s', strtotime($m['created_at'])) ?>
                                </td>
                                <td class="py-3.5 px-4 font-bold text-white uppercase">
                                    <?= esc($m['source']) ?>
                                </td>
                                <td class="py-3.5 px-4 font-heading font-extrabold text-base text-emerald-400 font-mono">
                                    +Rp <?= number_format($m['amount'], 0, ',', '.') ?>
                                </td>
                                <td class="py-3.5 px-4 max-w-xs truncate text-slate-400 font-mono text-[11px]">
                                    <?= esc($m['description'] ?: $m['raw_content']) ?>
                                </td>
                                <td class="py-3.5 px-4">
                                    <?php if ($m['matched_order_id']): ?>
                                        <a href="<?= base_url('admin/orders/detail/' . $m['matched_order_id']) ?>" class="font-mono font-bold text-cyan-400 hover:underline">
                                            #<?= esc($m['invoice_no']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-slate-500">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3.5 px-4">
                                    <?php if ($m['status'] === 'matched'): ?>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">MATCHED</span>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30">UNMATCHED</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3.5 px-4">
                                    <?php if ($m['status'] === 'unmatched' && !empty($unpaidOrders)): ?>
                                        <form action="<?= base_url('admin/mutations/match-manual') ?>" method="POST" class="flex items-center space-x-1.5" onsubmit="return confirm('Pasangkan mutasi ini ke order yang dipilih?')">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="mutation_id" value="<?= $m['id'] ?>">
                                            <select name="order_id" required class="bg-dark-900 border border-slate-700 rounded-lg px-2 py-1 text-[11px] text-white focus:outline-none">
                                                <option value="">-- Pilih Order --</option>
                                                <?php foreach ($unpaidOrders as $uOrd): ?>
                                                    <option value="<?= $uOrd['id'] ?>">#<?= esc($uOrd['invoice_no']) ?> (Rp <?= number_format($uOrd['total_amount'], 0, ',', '.') ?>)</option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="submit" class="px-2 py-1 rounded bg-cyan-600 hover:bg-cyan-500 text-white font-bold text-[10px] transition">
                                                Pasangkan
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-slate-600 text-xs">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-8 text-slate-500">Belum ada mutasi tercatat di sistem.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
