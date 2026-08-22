<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="font-heading font-extrabold text-2xl text-white">
            Edit Metode Pembayaran: <?= esc($payment['name']) ?>
        </h1>
        <a href="<?= base_url('admin/payments') ?>" class="text-xs text-slate-400 hover:text-white font-bold">&larr; Kembali</a>
    </div>

    <div class="bg-dark-card border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xl">
        <form action="<?= base_url('admin/payments/save') ?>" method="POST" class="space-y-5">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= $payment['id'] ?? '' ?>">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Nama Channel <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="<?= esc($payment['name'] ?? '') ?>" required class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-cyan-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Kode Unik Sistem</label>
                    <input type="text" name="code" value="<?= esc($payment['code'] ?? '') ?>" required class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white font-mono focus:outline-none focus:border-cyan-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Grup Channel</label>
                    <input type="text" name="group_name" value="<?= esc($payment['group_name'] ?? 'QRIS') ?>" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-cyan-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Tipe Driver</label>
                    <select name="type" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-cyan-500">
                        <option value="qris" <?= ($payment['type'] === 'qris') ? 'selected' : '' ?>>QRIS Dinamis Mandiri</option>
                        <option value="bank_transfer" <?= ($payment['type'] === 'bank_transfer') ? 'selected' : '' ?>>Bank Transfer</option>
                        <option value="ewallet" <?= ($payment['type'] === 'ewallet') ? 'selected' : '' ?>>E-Wallet</option>
                        <option value="balance" <?= ($payment['type'] === 'balance') ? 'selected' : '' ?>>Saldo Akun Member</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">URL Icon / Logo Pembayaran</label>
                    <input type="text" name="icon_url" value="<?= esc($payment['icon_url'] ?? '') ?>" placeholder="https://... atau /uploads/payments/qris.png" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-cyan-500">
                </div>
            </div>

            <!-- Fee & Account Settings -->
            <div class="border-t border-slate-800 pt-5 space-y-4">
                <h3 class="font-heading font-bold text-sm text-cyan-400">Biaya Admin &amp; Informasi Rekening</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">Fee Flat (Rp)</label>
                        <input type="number" name="fee_flat" value="<?= esc($payment['fee_flat'] ?? 0) ?>" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-cyan-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">Fee Persentase (%)</label>
                        <input type="number" step="0.01" name="fee_percent" value="<?= esc($payment['fee_percent'] ?? 0) ?>" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-cyan-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">Nomor Rekening / No. HP</label>
                        <input type="text" name="account_number" value="<?= esc($payment['account_number'] ?? '') ?>" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white font-mono focus:outline-none focus:border-cyan-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">Atas Nama Rekening</label>
                        <input type="text" name="account_name" value="<?= esc($payment['account_name'] ?? '') ?>" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-cyan-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Petunjuk Pembayaran Step-by-Step</label>
                    <textarea name="instructions" rows="4" class="w-full bg-dark-800 border border-slate-700 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-cyan-500"><?= esc($payment['instructions'] ?? '') ?></textarea>
                </div>

                <div class="flex items-center space-x-4">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="status" value="active" <?= ($payment['status'] === 'active') ? 'checked' : '' ?> class="rounded border-slate-700 text-cyan-500 focus:ring-cyan-500 bg-dark-800">
                        <span class="text-xs font-bold text-slate-300">Aktifkan Metode Pembayaran Ini</span>
                    </label>
                </div>
            </div>

            <div class="border-t border-slate-800 pt-5 flex justify-end space-x-3">
                <a href="<?= base_url('admin/payments') ?>" class="px-5 py-2 rounded-xl text-xs font-bold text-slate-400 hover:text-white bg-dark-800 hover:bg-dark-700 transition">Batal</a>
                <button type="submit" class="px-6 py-2 rounded-xl text-xs font-bold text-white bg-cyan-600 hover:bg-cyan-500 shadow-md shadow-cyan-600/20 transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
