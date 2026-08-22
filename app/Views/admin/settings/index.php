<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-heading font-extrabold text-2xl text-white">Pengaturan Website &amp; QRIS Automation</h1>
            <p class="text-xs text-slate-400">Konfigurasi Dynamic QRIS Engine, Webhook Secret, Informasi Toko, dan Provider API</p>
        </div>
    </div>

    <!-- 1. Self-Hosted Dynamic QRIS & Automation Webhook Configuration -->
    <div class="bg-dark-card border border-cyan-500/40 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
        <div class="flex items-center space-x-3 border-b border-slate-800 pb-4">
            <div class="w-10 h-10 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center">
                <i data-lucide="qr-code" class="w-5 h-5"></i>
            </div>
            <div>
                <h3 class="font-heading font-bold text-lg text-white">Konfigurasi Self-Hosted Dynamic QRIS Engine</h3>
                <p class="text-xs text-slate-400">Sistem otomatis mengubah QRIS statis Anda menjadi QRIS dinamis per nominal transaksi</p>
            </div>
        </div>

        <form action="<?= base_url('admin/settings/save') ?>" method="POST" class="space-y-5">
            <?= csrf_field() ?>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1.5">
                    String Payload QRIS Statis Merchant Anda <span class="text-red-400">*</span>
                </label>
                <textarea name="qris_static_payload" rows="3" required placeholder="00020101021126590013ID.CO.QRIS.WWW..." class="w-full bg-dark-800 border border-slate-700 rounded-xl p-3 text-xs text-cyan-300 font-mono focus:outline-none focus:border-cyan-500"><?= esc($settings['qris_static_payload'] ?? '') ?></textarea>
                <span class="text-[11px] text-slate-400 mt-1 block leading-relaxed">
                    💡 <strong>Cara Mendapatkan:</strong> Scan gambar QRIS Anda (dari BCA, Nobu, GoPay Merchant, DANA Bisnis, ShopeePay) menggunakan aplikasi pembaca QR Scanner / Google Lens di HP, lalu salin teks panjang payload-nya ke kotak di atas.
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Nama Merchant di QRIS</label>
                    <input type="text" name="qris_merchant_name" value="<?= esc($settings['qris_merchant_name'] ?? 'NORVAGO') ?>" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Kota Merchant</label>
                    <input type="text" name="qris_city" value="<?= esc($settings['qris_city'] ?? 'JAKARTA') ?>" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-500">
                </div>
            </div>

            <div class="border-t border-slate-800 pt-5 space-y-3">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold text-slate-300">
                        Secret Key Webhook Mutasi
                    </label>
                    <span class="text-[11px] text-slate-400">Header: <code>X-Webhook-Secret</code></span>
                </div>
                <div class="flex space-x-2">
                    <input type="text" name="webhook_secret_key" value="<?= esc($settings['webhook_secret_key'] ?? '') ?>" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-xs text-cyan-300 font-mono focus:outline-none focus:border-cyan-500">
                </div>
                <p class="text-[11px] text-slate-400">
                    Endpoint Webhook: <code class="text-cyan-300 font-mono"><?= base_url('api/webhook/qris-mutation') ?></code>
                </p>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="px-6 py-2.5 rounded-xl font-heading font-bold text-xs text-white bg-cyan-600 hover:bg-cyan-500 shadow-md shadow-cyan-600/20 transition">
                    Simpan Pengaturan QRIS
                </button>
            </div>
        </form>
    </div>

    <!-- 2. General Website Settings -->
    <div class="bg-dark-card border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xl space-y-6">
        <div class="flex items-center space-x-3 border-b border-slate-800 pb-4">
            <div class="w-10 h-10 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center">
                <i data-lucide="globe" class="w-5 h-5"></i>
            </div>
            <div>
                <h3 class="font-heading font-bold text-lg text-white">Informasi Website &amp; Kontak CS</h3>
                <p class="text-xs text-slate-400">Nama toko, tagline, deskripsi SEO, dan nomor WhatsApp CS</p>
            </div>
        </div>

        <form action="<?= base_url('admin/settings/save') ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Nama Website / Toko</label>
                    <input type="text" name="site_name" value="<?= esc($settings['site_name'] ?? 'Norvago') ?>" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Tagline Website</label>
                    <input type="text" name="site_tagline" value="<?= esc($settings['site_tagline'] ?? '') ?>" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1.5">Deskripsi Singkat (SEO Meta)</label>
                <textarea name="site_description" rows="2" class="w-full bg-dark-800 border border-slate-700 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-cyan-500"><?= esc($settings['site_description'] ?? '') ?></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Nomor WhatsApp CS</label>
                    <input type="text" name="whatsapp_cs" value="<?= esc($settings['whatsapp_cs'] ?? '') ?>" placeholder="6281234567890" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">URL Instagram</label>
                    <input type="url" name="instagram_url" value="<?= esc($settings['instagram_url'] ?? '') ?>" placeholder="https://instagram.com/..." class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">URL Telegram Channel</label>
                    <input type="url" name="telegram_channel" value="<?= esc($settings['telegram_channel'] ?? '') ?>" placeholder="https://t.me/..." class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-500">
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="px-6 py-2.5 rounded-xl font-heading font-bold text-xs text-white bg-slate-700 hover:bg-slate-600 transition">
                    Simpan Informasi Web
                </button>
            </div>
        </form>
    </div>

    <!-- 3. Check ID & Nickname API Configuration -->
    <div class="bg-dark-card border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xl space-y-6">
        <div class="flex items-center space-x-3 border-b border-slate-800 pb-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                <i data-lucide="user-check" class="w-5 h-5"></i>
            </div>
            <div>
                <h3 class="font-heading font-bold text-lg text-white">Konfigurasi API Cek ID Nickname Game (MLBB, FF, Genshin, dll)</h3>
                <p class="text-xs text-slate-400">Pilih provider untuk verifikasi username / nickname pemain secara real-time</p>
            </div>
        </div>

        <form action="<?= base_url('admin/settings/save') ?>" method="POST" class="space-y-4" x-data="{ checkProvider: '<?= esc($settings['check_id_provider'] ?? 'auto') ?>' }">
            <?= csrf_field() ?>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1.5">Pilih Provider Cek ID</label>
                <select name="check_id_provider" x-model="checkProvider" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-500">
                    <option value="auto">Mode Otomatis / Direct Moonton (Live Cek Server Resmi)</option>
                    <option value="simulation">Mode Simulasi &amp; Testing (Terima Semua ID Dummy)</option>
                    <option value="apigames">ApiGames.id (Direct API Cek ID)</option>
                    <option value="vip_reseller">VIP Reseller (Game Feature API)</option>
                    <option value="custom">Custom Endpoint API / Scraper Pribadi</option>
                </select>
            </div>

            <!-- ApiGames fields -->
            <div x-show="checkProvider === 'apigames'" class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-dark-800/60 p-4 rounded-2xl border border-slate-800">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">ApiGames Merchant ID</label>
                    <input type="text" name="apigames_merchant_id" value="<?= esc($settings['apigames_merchant_id'] ?? '') ?>" placeholder="M24XXXXX" class="w-full bg-dark-900 border border-slate-700 rounded-xl px-4 py-2 text-xs text-white font-mono focus:outline-none focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">ApiGames Secret Key</label>
                    <input type="password" name="apigames_secret_key" value="<?= esc($settings['apigames_secret_key'] ?? '') ?>" placeholder="Secret key dari dashboard ApiGames" class="w-full bg-dark-900 border border-slate-700 rounded-xl px-4 py-2 text-xs text-white font-mono focus:outline-none focus:border-cyan-500">
                </div>
            </div>

            <!-- VIP Reseller fields -->
            <div x-show="checkProvider === 'vip_reseller'" class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-dark-800/60 p-4 rounded-2xl border border-slate-800">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">VIP Reseller API ID</label>
                    <input type="text" name="provider_vip_api_id" value="<?= esc($settings['provider_vip_api_id'] ?? '') ?>" placeholder="API ID VIP Reseller" class="w-full bg-dark-900 border border-slate-700 rounded-xl px-4 py-2 text-xs text-white font-mono focus:outline-none focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">VIP Reseller API Key</label>
                    <input type="password" name="provider_vip_api_key" value="<?= esc($settings['provider_vip_api_key'] ?? '') ?>" placeholder="API Key VIP Reseller" class="w-full bg-dark-900 border border-slate-700 rounded-xl px-4 py-2 text-xs text-white font-mono focus:outline-none focus:border-cyan-500">
                </div>
            </div>

            <!-- Custom URL template -->
            <div x-show="checkProvider === 'custom'" class="bg-dark-800/60 p-4 rounded-2xl border border-slate-800 space-y-2">
                <label class="block text-xs font-bold text-slate-300">Custom URL API Template</label>
                <input type="text" name="custom_check_id_url" value="<?= esc($settings['custom_check_id_url'] ?? '') ?>" placeholder="https://api.domain.com/cek?game={game}&id={user_id}&zone={zone_id}" class="w-full bg-dark-900 border border-slate-700 rounded-xl px-4 py-2 text-xs text-white font-mono focus:outline-none focus:border-cyan-500">
                <span class="text-[10px] text-slate-400 block">Variabel yang tersedia: <code>{game}</code>, <code>{user_id}</code>, <code>{zone_id}</code>. Response JSON harus berisi key <code>username</code> atau <code>nickname</code>.</span>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="px-6 py-2.5 rounded-xl font-heading font-bold text-xs text-white bg-slate-700 hover:bg-slate-600 transition">
                    Simpan Pengaturan Cek ID
                </button>
            </div>
        </form>
    </div>

    <!-- 4. Provider API Configuration -->
    <div class="bg-dark-card border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xl space-y-6">
        <div class="flex items-center space-x-3 border-b border-slate-800 pb-4">
            <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center">
                <i data-lucide="cpu" class="w-5 h-5"></i>
            </div>
            <div>
                <h3 class="font-heading font-bold text-lg text-white">Konfigurasi API Provider Game (Opsional)</h3>
                <p class="text-xs text-slate-400">Hubungkan akun Digiflazz atau VIP Reseller jika ingin otomatis tembak stok provider pihak ketiga</p>
            </div>
        </div>

        <form action="<?= base_url('admin/settings/save') ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Digiflazz Username</label>
                    <input type="text" name="provider_digiflazz_user" value="<?= esc($settings['provider_digiflazz_user'] ?? '') ?>" placeholder="Username Digiflazz" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-xs text-white font-mono focus:outline-none focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Digiflazz Production/Dev API Key</label>
                    <input type="password" name="provider_digiflazz_key" value="<?= esc($settings['provider_digiflazz_key'] ?? '') ?>" placeholder="dev-xxxx / prod-xxxx" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-4 py-2.5 text-xs text-white font-mono focus:outline-none focus:border-cyan-500">
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="px-6 py-2.5 rounded-xl font-heading font-bold text-xs text-white bg-slate-700 hover:bg-slate-600 transition">
                    Simpan Konfigurasi Provider
                </button>
            </div>
        </form>
    </div>

</div>

<?= $this->endSection() ?>
