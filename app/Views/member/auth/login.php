<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-md mx-auto px-4 py-12">
    <div class="bg-dark-card border border-gray-800 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
        <div class="text-center space-y-2">
            <div class="w-12 h-12 rounded-2xl bg-brand-500/20 text-brand-400 flex items-center justify-center mx-auto">
                <i data-lucide="user" class="w-6 h-6"></i>
            </div>
            <h1 class="font-heading font-extrabold text-2xl text-white">Masuk Akun Member</h1>
            <p class="text-xs text-gray-400">Masuk untuk menikmati harga diskon reseller dan kemudahan deposit saldo.</p>
        </div>

        <form action="<?= base_url('login-process') ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                <label class="block text-xs font-bold text-gray-300 mb-1.5">Username / Email</label>
                <div class="relative">
                    <i data-lucide="at-sign" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="username" value="<?= old('username') ?>" required placeholder="Username atau Email" class="w-full bg-dark-800 border border-gray-700 rounded-xl pl-10 pr-4 py-3 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-300 mb-1.5">Password</label>
                <div class="relative">
                    <i data-lucide="lock" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full bg-dark-800 border border-gray-700 rounded-xl pl-10 pr-4 py-3 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition">
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-xl font-heading font-bold text-sm text-white bg-gradient-to-r from-brand-500 to-blue-600 hover:from-brand-600 hover:to-blue-700 shadow-lg shadow-brand-500/25 transition">
                Masuk Sekarang
            </button>
        </form>

        <div class="text-center text-xs text-gray-400 pt-2 border-t border-gray-800">
            Belum punya akun? <a href="<?= base_url('register') ?>" class="text-brand-400 font-bold hover:underline">Daftar Sekarang</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
