<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-heading font-extrabold text-2xl text-white">Kelola Metode Pembayaran &amp; Biaya Admin</h1>
            <p class="text-xs text-slate-400">Atur channel pembayaran, nomor rekening, petunjuk bayar, dan fixed/percent fee</p>
        </div>
    </div>

    <div class="bg-dark-card border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-dark-800 text-slate-400 uppercase text-[10px] font-bold border-b border-slate-800">
                    <tr>
                        <th class="py-3.5 px-4">Nama Channel</th>
                        <th class="py-3.5 px-4">Grup</th>
                        <th class="py-3.5 px-4">Tipe</th>
                        <th class="py-3.5 px-4">Biaya Admin (Fee)</th>
                        <th class="py-3.5 px-4">No. Rekening / Info</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-slate-300">
                    <?php foreach ($payments as $p): ?>
                        <tr class="hover:bg-dark-800/50 transition">
                            <td class="py-3.5 px-4">
                                <strong class="text-white block font-heading"><?= esc($p['name']) ?></strong>
                                <span class="text-mono text-[10px] text-cyan-400"><?= esc($p['code']) ?></span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-300">
                                <?= esc($p['group_name']) ?>
                            </td>
                            <td class="py-3.5 px-4 uppercase font-mono text-xs text-slate-400">
                                <?= esc($p['type']) ?>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-xs">
                                <?php if ($p['fee_flat'] == 0 && $p['fee_percent'] == 0): ?>
                                    <span class="text-emerald-400 font-bold">Rp 0 (Gratis)</span>
                                <?php else: ?>
                                    <?= $p['fee_flat'] > 0 ? 'Rp ' . number_format($p['fee_flat'], 0, ',', '.') : '' ?>
                                    <?= $p['fee_percent'] > 0 ? '+ ' . $p['fee_percent'] . '%' : '' ?>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-xs">
                                <?= esc($p['account_number'] ?: '-') ?>
                            </td>
                            <td class="py-3.5 px-4">
                                <?php if ($p['status'] === 'active'): ?>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Aktif</span>
                                <?php else: ?>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-500/20 text-red-400 border border-red-500/30">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-4">
                                <a href="<?= base_url('admin/payments/edit/' . $p['id']) ?>" class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-dark-700 hover:bg-cyan-600 transition inline-block">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
