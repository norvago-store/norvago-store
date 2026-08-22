<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="font-heading font-extrabold text-2xl text-white">
            <?= $game ? 'Edit Game: ' . esc($game['name']) : 'Tambah Game Baru' ?>
        </h1>
        <a href="<?= base_url('admin/games') ?>" class="text-xs text-slate-400 hover:text-white font-bold">&larr; Kembali</a>
    </div>

    <div class="bg-dark-card border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xl">
        <form action="<?= base_url('admin/games/save') ?>" method="POST" class="space-y-6">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= $game['id'] ?? '' ?>">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Nama Game <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="<?= esc($game['name'] ?? '') ?>" required placeholder="Contoh: Mobile Legends: Bang Bang" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-cyan-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Slug URL</label>
                    <input type="text" name="slug" value="<?= esc($game['slug'] ?? '') ?>" placeholder="mobile-legends (otomatis jika kosong)" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-cyan-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Kategori Game</label>
                    <select name="category_id" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-cyan-500 transition">
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= (isset($game['category_id']) && $game['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                <?= esc($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Developer / Publisher</label>
                    <input type="text" name="developer" value="<?= esc($game['developer'] ?? '') ?>" placeholder="Contoh: Moonton, Garena, Riot Games" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-cyan-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">URL Thumbnail / Logo <span class="text-red-400">*</span></label>
                    <input type="text" name="image_url" value="<?= esc($game['image_url'] ?? '') ?>" required placeholder="https://... atau /uploads/games/mlbb.png" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-cyan-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">URL Banner Header</label>
                    <input type="text" name="banner_url" value="<?= esc($game['banner_url'] ?? '') ?>" placeholder="https://... atau /uploads/banners/banner.jpg" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-cyan-500 transition">
                </div>
            </div>

            <!-- Form Dynamic Input Configuration -->
            <div class="border-t border-slate-800 pt-6 space-y-4">
                <h3 class="font-heading font-bold text-base text-cyan-400">Konfigurasi Form Input Target Akun</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">Tipe Input</label>
                        <select name="target_input_type" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-cyan-500 transition">
                            <option value="single" <?= (isset($game['target_input_type']) && $game['target_input_type'] === 'single') ? 'selected' : '' ?>>Single ID (1 Input)</option>
                            <option value="double" <?= (isset($game['target_input_type']) && $game['target_input_type'] === 'double') ? 'selected' : '' ?>>Double ID (User ID + Zone ID)</option>
                            <option value="server_dropdown" <?= (isset($game['target_input_type']) && $game['target_input_type'] === 'server_dropdown') ? 'selected' : '' ?>>UID + Server Dropdown</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">Label Input 1</label>
                        <input type="text" name="target_input_label_1" value="<?= esc($game['target_input_label_1'] ?? 'User ID') ?>" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-cyan-500 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">Label Input 2 (Zone/Server)</label>
                        <input type="text" name="target_input_label_2" value="<?= esc($game['target_input_label_2'] ?? '') ?>" placeholder="Zone ID / Server" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-cyan-500 transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">Endpoint Cek ID Nickname</label>
                        <select name="check_id_endpoint" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-cyan-500 transition">
                            <option value="">-- Tidak Ada Pengecekan --</option>
                            <option value="mlbb" <?= (isset($game['check_id_endpoint']) && $game['check_id_endpoint'] === 'mlbb') ? 'selected' : '' ?>>Mobile Legends (mlbb)</option>
                            <option value="ff" <?= (isset($game['check_id_endpoint']) && $game['check_id_endpoint'] === 'ff') ? 'selected' : '' ?>>Free Fire (ff)</option>
                            <option value="genshin" <?= (isset($game['check_id_endpoint']) && $game['check_id_endpoint'] === 'genshin') ? 'selected' : '' ?>>Genshin Impact (genshin)</option>
                            <option value="valorant" <?= (isset($game['check_id_endpoint']) && $game['check_id_endpoint'] === 'valorant') ? 'selected' : '' ?>>Valorant (valorant)</option>
                            <option value="pubgm" <?= (isset($game['check_id_endpoint']) && $game['check_id_endpoint'] === 'pubgm') ? 'selected' : '' ?>>PUBG Mobile (pubgm)</option>
                            <option value="hok" <?= (isset($game['check_id_endpoint']) && $game['check_id_endpoint'] === 'hok') ? 'selected' : '' ?>>Honor of Kings (hok)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">Urutan Tampil (Sort Order)</label>
                        <input type="number" name="sort_order" value="<?= esc($game['sort_order'] ?? 0) ?>" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-cyan-500 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Petunjuk Cara Menemukan User ID</label>
                    <textarea name="instructions" rows="3" class="w-full bg-dark-800 border border-slate-700 rounded-xl p-3 text-sm text-white focus:outline-none focus:border-cyan-500 transition"><?= esc($game['instructions'] ?? '') ?></textarea>
                </div>

                <div class="flex items-center space-x-6 pt-2">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="is_popular" value="1" <?= (!empty($game['is_popular'])) ? 'checked' : '' ?> class="rounded border-slate-700 text-cyan-500 focus:ring-cyan-500 bg-dark-800">
                        <span class="text-xs font-bold text-slate-300">Tampilkan di Populer</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" <?= (!isset($game['is_active']) || $game['is_active']) ? 'checked' : '' ?> class="rounded border-slate-700 text-cyan-500 focus:ring-cyan-500 bg-dark-800">
                        <span class="text-xs font-bold text-slate-300">Status Game Aktif</span>
                    </label>
                </div>
            </div>

            <div class="border-t border-slate-800 pt-6 flex justify-end space-x-3">
                <a href="<?= base_url('admin/games') ?>" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-400 hover:text-white bg-dark-800 hover:bg-dark-700 transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-cyan-600 hover:bg-cyan-500 shadow-md shadow-cyan-600/20 transition">Simpan Game</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
