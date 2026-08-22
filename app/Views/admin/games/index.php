<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-heading font-extrabold text-2xl text-white">Kelola Game &amp; Form Input</h1>
            <p class="text-xs text-slate-400">Atur game aktif, skema form input, dan endpoint pengecekan nickname</p>
        </div>
        <a href="<?= base_url('admin/games/create') ?>" class="inline-flex items-center px-4 py-2.5 rounded-xl font-heading font-bold text-xs sm:text-sm text-white bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 shadow-md shadow-cyan-500/20 transition">
            <i data-lucide="plus" class="w-4 h-4 mr-1.5"></i> Tambah Game Baru
        </a>
    </div>

    <div class="bg-dark-card border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-dark-800 text-slate-400 uppercase text-[10px] font-bold border-b border-slate-800">
                    <tr>
                        <th class="py-3.5 px-4">Game</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Tipe Input Form</th>
                        <th class="py-3.5 px-4">Cek ID</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-slate-300">
                    <?php foreach ($games as $game): ?>
                        <tr class="hover:bg-dark-800/50 transition">
                            <td class="py-3.5 px-4 flex items-center space-x-3">
                                <img src="<?= esc($game['image_url']) ?>" alt="" class="w-10 h-10 rounded-xl object-cover border border-slate-700">
                                <div>
                                    <strong class="text-white block font-heading"><?= esc($game['name']) ?></strong>
                                    <span class="text-[11px] text-slate-400"><?= esc($game['developer'] ?: $game['subtitle']) ?></span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-300">
                                <?= esc($game['category_name'] ?: 'Games') ?>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-dark-700 text-cyan-300 uppercase font-mono">
                                    <?= esc($game['target_input_type']) ?>
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-xs text-amber-400">
                                <?= esc($game['check_id_endpoint'] ?: 'None') ?>
                            </td>
                            <td class="py-3.5 px-4">
                                <?php if ($game['is_active']): ?>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Aktif</span>
                                <?php else: ?>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-500/20 text-red-400 border border-red-500/30">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-4 space-x-2">
                                <a href="<?= base_url('admin/games/edit/' . $game['id']) ?>" class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-dark-700 hover:bg-cyan-600 transition inline-block">
                                    Edit
                                </a>
                                <a href="<?= base_url('admin/games/delete/' . $game['id']) ?>" onclick="return confirm('Hapus game ini? Semua produk terkait akan terhapus.')" class="px-3 py-1.5 rounded-lg text-xs font-bold text-red-400 bg-red-500/10 hover:bg-red-500 hover:text-white transition inline-block">
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
