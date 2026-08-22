<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-heading font-extrabold text-2xl text-white">Kelola Kode Promo &amp; Voucher</h1>
            <p class="text-xs text-slate-400">Buat kupon diskon potongan tetap (flat) atau persentase (%)</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Add Voucher Form -->
        <div class="lg:col-span-1 bg-dark-card border border-slate-800 rounded-3xl p-5 shadow-xl space-y-4">
            <h3 class="font-heading font-bold text-base text-cyan-400">Buat Voucher Baru</h3>
            
            <form action="<?= base_url('admin/vouchers/save') ?>" method="POST" class="space-y-3.5">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Kode Voucher <span class="text-red-400">*</span></label>
                    <input type="text" name="code" required placeholder="Contoh: HEMAT50" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white uppercase font-mono focus:outline-none focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Nama Promo</label>
                    <input type="text" name="name" required placeholder="Diskon Pengguna Baru" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-500">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Tipe Diskon</label>
                        <select name="type" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-500">
                            <option value="fixed">Nominal Flat (Rp)</option>
                            <option value="percent">Persentase (%)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Besar Diskon</label>
                        <input type="number" step="0.01" name="amount" required placeholder="10000 / 5" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Min. Belanja (Rp)</label>
                        <input type="number" name="min_purchase" value="0" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Maks. Diskon (Rp)</label>
                        <input type="number" name="max_discount" value="0" placeholder="0 jika bebas" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Kuota Pemakaian</label>
                        <input type="number" name="quota" value="100" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Berlaku Sampai</label>
                        <input type="date" name="valid_until" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-500">
                    </div>
                </div>

                <button type="submit" class="w-full py-2.5 rounded-xl font-heading font-bold text-xs text-white bg-cyan-600 hover:bg-cyan-500 transition shadow-md shadow-cyan-600/20">
                    Buat Voucher
                </button>
            </form>
        </div>

        <!-- Vouchers List Table -->
        <div class="lg:col-span-2 bg-dark-card border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-dark-800 text-slate-400 uppercase text-[10px] font-bold border-b border-slate-800">
                        <tr>
                            <th class="py-3 px-4">Kode</th>
                            <th class="py-3 px-4">Nama Promo</th>
                            <th class="py-3 px-4">Diskon</th>
                            <th class="py-3 px-4">Min. Beli</th>
                            <th class="py-3 px-4">Terpakai / Kuota</th>
                            <th class="py-3 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800 text-slate-300">
                        <?php foreach ($vouchers as $v): ?>
                            <tr class="hover:bg-dark-800/50 transition">
                                <td class="py-3 px-4 font-mono font-bold text-cyan-400">
                                    <?= esc($v['code']) ?>
                                </td>
                                <td class="py-3 px-4 font-semibold text-white">
                                    <?= esc($v['name']) ?>
                                </td>
                                <td class="py-3 px-4 font-bold text-emerald-400">
                                    <?= $v['type'] === 'percent' ? $v['amount'] . '%' : 'Rp ' . number_format($v['amount'], 0, ',', '.') ?>
                                </td>
                                <td class="py-3 px-4 text-slate-400">
                                    Rp <?= number_format($v['min_purchase'], 0, ',', '.') ?>
                                </td>
                                <td class="py-3 px-4">
                                    <?= esc($v['used_count']) ?> / <?= esc($v['quota']) ?>
                                </td>
                                <td class="py-3 px-4">
                                    <a href="<?= base_url('admin/vouchers/delete/' . $v['id']) ?>" onclick="return confirm('Hapus voucher ini?')" class="px-2.5 py-1 rounded bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white font-bold text-[11px] transition">
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
</div>

<?= $this->endSection() ?>
