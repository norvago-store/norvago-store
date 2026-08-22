<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-md mx-auto px-4 py-12">
    <div class="bg-dark-card border border-gray-800 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
        <div class="text-center space-y-2">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-brand-500 to-blue-600 text-white flex items-center justify-center mx-auto shadow-lg shadow-brand-500/25">
                <i data-lucide="user-plus" class="w-6 h-6"></i>
            </div>
            <h1 class="font-heading font-extrabold text-2xl text-white">Daftar Akun Baru</h1>
            <p class="text-xs text-gray-400">Bergabung sekarang untuk mendapatkan harga reseller termurah!</p>
        </div>

        <form action="<?= base_url('register-process') ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                <label class="block text-xs font-bold text-gray-300 mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" value="<?= old('name') ?>" required placeholder="Contoh: Budi Santoso" class="w-full bg-dark-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-300 mb-1.5">Username</label>
                <input type="text" name="username" value="<?= old('username') ?>" required placeholder="Contoh: budi123" class="w-full bg-dark-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-300 mb-1.5">Email</label>
                <input type="email" name="email" value="<?= old('email') ?>" required placeholder="Contoh: budi@gmail.com" class="w-full bg-dark-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-300 mb-1.5">Nomor WhatsApp</label>
                <input type="tel" name="phone" value="<?= old('phone') ?>" required placeholder="Contoh: 081234567890" class="w-full bg-dark-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-300 mb-1.5">Password</label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full bg-dark-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition">
            </div>

            <button type="submit" class="w-full py-3.5 rounded-xl font-heading font-bold text-sm text-white bg-gradient-to-r from-brand-500 to-blue-600 hover:from-brand-600 hover:to-blue-700 shadow-lg shadow-brand-500/25 transition">
                Daftar Akun Sekarang
            </button>
        </form>

        <div class="text-center text-xs text-gray-400 pt-2 border-t border-gray-800">
            Sudah punya akun? <a href="<?= base_url('login') ?>" class="text-brand-400 font-bold hover:underline">Masuk di sini</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
