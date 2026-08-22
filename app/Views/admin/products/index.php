<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-heading font-extrabold text-2xl text-white">Kelola Produk &amp; Denominasi</h1>
            <p class="text-xs text-slate-400">Atur SKU produk, harga modal, harga umum, harga reseller, dan flash sale</p>
        </div>
        <a href="<?= base_url('admin/products/create') ?>" class="inline-flex items-center px-4 py-2.5 rounded-xl font-heading font-bold text-xs sm:text-sm text-white bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 shadow-md shadow-cyan-500/20 transition">
            <i data-lucide="plus" class="w-4 h-4 mr-1.5"></i> Tambah Produk Baru
        </a>
    </div>

    <!-- Filter by Game -->
    <div class="bg-dark-card border border-slate-800 rounded-2xl p-4 flex items-center space-x-3">
        <label class="text-xs font-bold text-slate-300">Filter Game:</label>
        <select onchange="location = this.value;" class="bg-dark-800 border border-slate-700 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none focus:border-cyan-500">
            <option value="<?= base_url('admin/products') ?>">-- Semua Game --</option>
            <?php foreach ($games as $g): ?>
                <option value="<?= base_url('admin/products?game_id=' . $g['id']) ?>" <?= ($gameId == $g['id']) ? 'selected' : '' ?>>
                    <?= esc($g['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="bg-dark-card border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-dark-800 text-slate-400 uppercase text-[10px] font-bold border-b border-slate-800">
                    <tr>
                        <th class="py-3.5 px-4">Game</th>
                        <th class="py-3.5 px-4">Nama Item / SKU</th>
                        <th class="py-3.5 px-4">Harga Modal</th>
                        <th class="py-3.5 px-4">Harga Normal</th>
                        <th class="py-3.5 px-4">Harga Reseller</th>
                        <th class="py-3.5 px-4">Provider</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-slate-300">
                    <?php foreach ($products as $p): ?>
                        <tr class="hover:bg-dark-800/50 transition">
                            <td class="py-3.5 px-4 font-bold text-white">
                                <?= esc($p['game_name']) ?>
                            </td>
                            <td class="py-3.5 px-4">
                                <strong class="text-white block font-heading"><?= esc($p['name']) ?></strong>
                                <span class="font-mono text-[10px] text-cyan-400"><?= esc($p['sku']) ?></span>
                                <?php if ($p['is_flash_sale']): ?>
                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-500 text-dark-900 ml-1">FLASH SALE</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-4 text-slate-400 font-mono">
                                Rp <?= number_format($p['price_cost'], 0, ',', '.') ?>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-white font-mono">
                                Rp <?= number_format($p['price_normal'], 0, ',', '.') ?>
                            </td>
                            <td class="py-3.5 px-4 text-emerald-400 font-bold font-mono">
                                Rp <?= number_format($p['price_reseller'], 0, ',', '.') ?>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-xs uppercase text-slate-400">
                                <?= esc($p['provider_code']) ?>
                            </td>
                            <td class="py-3.5 px-4">
                                <?php if ($p['status'] === 'available'): ?>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Tersedia</span>
                                <?php else: ?>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-500/20 text-red-400 border border-red-500/30">Kosong</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-4 space-x-2">
                                <a href="<?= base_url('admin/products/edit/' . $p['id']) ?>" class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-dark-700 hover:bg-cyan-600 transition inline-block">
                                    Edit
                                </a>
                                <a href="<?= base_url('admin/products/delete/' . $p['id']) ?>" onclick="return confirm('Hapus produk ini?')" class="px-3 py-1.5 rounded-lg text-xs font-bold text-red-400 bg-red-500/10 hover:bg-red-500 hover:text-white transition inline-block">
                                    Hapus
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
