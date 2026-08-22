<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="font-heading font-extrabold text-2xl text-white">
            <?= $product ? 'Edit Produk: ' . esc($product['name']) : 'Tambah Produk / Denominasi Baru' ?>
        </h1>
        <a href="<?= base_url('admin/products') ?>" class="text-xs text-slate-400 hover:text-white font-bold">&larr; Kembali</a>
    </div>

    <div class="bg-dark-card border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xl">
        <form action="<?= base_url('admin/products/save') ?>" method="POST" class="space-y-6">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= $product['id'] ?? '' ?>">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Pilih Game <span class="text-red-400">*</span></label>
                    <select name="game_id" required class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-cyan-500 transition">
                        <?php foreach ($games as $g): ?>
                            <option value="<?= $g['id'] ?>" <?= (isset($product['game_id']) && $product['game_id'] == $g['id']) ? 'selected' : '' ?>>
                                <?= esc($g['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Nama Item / Denom <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="<?= esc($product['name'] ?? '') ?>" required placeholder="Contoh: 86 Diamonds" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-cyan-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">SKU Internal Produk</label>
                    <input type="text" name="sku" value="<?= esc($product['sku'] ?? '') ?>" placeholder="MLBB-86 (otomatis jika kosong)" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white font-mono focus:outline-none focus:border-cyan-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Kategori Grup Denom</label>
                    <select name="category_id" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-cyan-500 transition">
                        <option value="">-- Tanpa Kategori Khusus --</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= (isset($product['category_id']) && $product['category_id'] == $c['id']) ? 'selected' : '' ?>>
                                <?= esc($c['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Pricing Configuration -->
            <div class="border-t border-slate-800 pt-6 space-y-4">
                <h3 class="font-heading font-bold text-base text-cyan-400">Pengaturan Harga Multi-Tier (Rp)</h3>

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">Harga Modal (Cost)</label>
                        <input type="number" name="price_cost" value="<?= esc($product['price_cost'] ?? 0) ?>" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-cyan-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">Harga Normal (Publik) <span class="text-red-400">*</span></label>
                        <input type="number" name="price_normal" value="<?= esc($product['price_normal'] ?? 0) ?>" required class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white font-bold text-cyan-400 focus:outline-none focus:border-cyan-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">Harga Gold Member</label>
                        <input type="number" name="price_gold" value="<?= esc($product['price_gold'] ?? 0) ?>" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-cyan-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">Harga Reseller VIP</label>
                        <input type="number" name="price_reseller" value="<?= esc($product['price_reseller'] ?? 0) ?>" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white font-bold text-emerald-400 focus:outline-none focus:border-cyan-500 transition">
                    </div>
                </div>

                <!-- Flash Sale Setup -->
                <div class="bg-dark-800 border border-amber-500/30 rounded-2xl p-4 space-y-3">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="is_flash_sale" value="1" <?= (!empty($product['is_flash_sale'])) ? 'checked' : '' ?> class="rounded border-slate-700 text-amber-500 focus:ring-amber-500 bg-dark-900">
                        <span class="text-xs font-bold text-amber-400">Aktifkan Mode Flash Sale untuk Produk Ini</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-300 mb-1">Harga Flash Sale (Rp)</label>
                            <input type="number" name="flash_sale_price" value="<?= esc($product['flash_sale_price'] ?? 0) ?>" placeholder="Harga diskon flash sale" class="w-full bg-dark-900 border border-slate-700 rounded-xl px-4 py-2 text-xs text-amber-400 focus:outline-none focus:border-amber-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Provider Integration Mapping -->
            <div class="border-t border-slate-800 pt-6 space-y-4">
                <h3 class="font-heading font-bold text-base text-cyan-400">Integrasi Provider Topup</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">Driver Provider</label>
                        <select name="provider_code" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-cyan-500 transition">
                            <option value="manual" <?= (isset($product['provider_code']) && $product['provider_code'] === 'manual') ? 'selected' : '' ?>>Manual / Norva Auto Engine</option>
                            <option value="digiflazz" <?= (isset($product['provider_code']) && $product['provider_code'] === 'digiflazz') ? 'selected' : '' ?>>Digiflazz</option>
                            <option value="vip" <?= (isset($product['provider_code']) && $product['provider_code'] === 'vip') ? 'selected' : '' ?>>VIP Reseller</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">SKU / Kode Produk di Provider</label>
                        <input type="text" name="provider_sku" value="<?= esc($product['provider_sku'] ?? '') ?>" placeholder="Contoh: ML86" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white font-mono focus:outline-none focus:border-cyan-500 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">Status Ketersediaan</label>
                        <select name="status" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-cyan-500 transition">
                            <option value="available" <?= (isset($product['status']) && $product['status'] === 'available') ? 'selected' : '' ?>>Tersedia (Ready)</option>
                            <option value="empty" <?= (isset($product['status']) && $product['status'] === 'empty') ? 'selected' : '' ?>>Stok Habis (Empty)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Urutan Tampil (Sort Order)</label>
                    <input type="number" name="sort_order" value="<?= esc($product['sort_order'] ?? 0) ?>" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-cyan-500 transition">
                </div>
            </div>

            <div class="border-t border-slate-800 pt-6 flex justify-end space-x-3">
                <a href="<?= base_url('admin/products') ?>" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-400 hover:text-white bg-dark-800 hover:bg-dark-700 transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-cyan-600 hover:bg-cyan-500 shadow-md shadow-cyan-600/20 transition">Simpan Produk</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
