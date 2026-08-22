<!DOCTYPE html>
<html lang="id" class="dark scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Norvago - Top Up Game Murah & Cepat') ?></title>
    <meta name="description" content="<?= esc($settings['site_description'] ?? 'Platform Top Up Game Online Terpercaya dan Otomatis 24 Jam') ?>">

    <!-- Google Fonts Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN (v3.4) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#ecfeff',
                            100: '#cffafe',
                            400: '#22d3ee',
                            500: '#06b6d4',
                            600: '#0891b2',
                            700: '#0e7490',
                        },
                        dark: {
                            900: '#0b0f19',
                            800: '#111827',
                            700: '#1f2937',
                            600: '#374151',
                            card: '#161e2e',
                            hover: '#1e293b'
                        },
                        accent: '#38bdf8',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0b0f19;
            color: #f3f4f6;
        }
        .font-heading {
            font-family: 'Outfit', sans-serif;
        }
        .glow-brand {
            box-shadow: 0 0 25px -5px rgba(6, 182, 212, 0.4);
        }
        .glow-card {
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.5);
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #0b0f19;
        }
        ::-webkit-scrollbar-thumb {
            background: #1f2937;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #06b6d4;
        }
    </style>
</head>
<body class="bg-dark-900 text-gray-100 flex flex-col min-h-screen antialiased selection:bg-brand-500 selection:text-white">

    <!-- Top Announcement Bar -->
    <div class="bg-gradient-to-r from-cyan-600 via-blue-600 to-indigo-600 text-white text-xs font-semibold py-1.5 px-4 text-center">
        ⚡ Top Up Game Instan, 24 Jam Nonstop dengan QRIS All Payment & Konfirmasi Otomatis!
    </div>

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-50 bg-dark-900/90 backdrop-blur-md border-b border-gray-800 transition duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <a href="<?= base_url() ?>" class="flex items-center space-x-2.5 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-500 to-blue-500 flex items-center justify-center font-heading font-extrabold text-xl text-white shadow-lg shadow-cyan-500/25 group-hover:scale-105 transition-transform duration-300">
                            N
                        </div>
                        <div>
                            <span class="font-heading font-extrabold text-xl sm:text-2xl tracking-tight text-white group-hover:text-brand-400 transition-colors">
                                <?= esc($settings['site_name'] ?? 'Norvago') ?>
                            </span>
                            <span class="text-brand-400 text-xs block font-bold -mt-1 tracking-wider uppercase">TOP UP</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links (Desktop) -->
                <nav class="hidden md:flex items-center space-x-1 lg:space-x-2">
                    <a href="<?= base_url() ?>" class="px-3.5 py-2 rounded-lg text-sm font-semibold text-gray-300 hover:text-white hover:bg-dark-800 transition">
                        <i data-lucide="home" class="w-4 h-4 inline-block mr-1 text-brand-400"></i> Beranda
                    </a>
                    <a href="<?= base_url('tracking') ?>" class="px-3.5 py-2 rounded-xl text-sm font-bold text-cyan-300 hover:text-white bg-cyan-950/50 hover:bg-cyan-900/60 border border-cyan-500/30 hover:border-cyan-400/60 transition flex items-center shadow-sm">
                        <i data-lucide="receipt" class="w-4 h-4 inline-block mr-1.5 text-cyan-400"></i> Pesanan Saya
                    </a>
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.outside="open = false" class="px-3.5 py-2 rounded-lg text-sm font-semibold text-gray-300 hover:text-white hover:bg-dark-800 transition flex items-center">
                            <i data-lucide="calculator" class="w-4 h-4 inline-block mr-1 text-amber-400"></i> Kalkulator <i data-lucide="chevron-down" class="w-3.5 h-3.5 ml-1"></i>
                        </button>
                        <div x-show="open" x-transition class="absolute left-0 mt-2 w-48 bg-dark-card border border-gray-700 rounded-xl shadow-2xl py-2 z-50">
                            <a href="<?= base_url('calculator/winrate') ?>" class="block px-4 py-2 text-sm text-gray-300 hover:bg-dark-800 hover:text-brand-400">Winrate MLBB</a>
                            <a href="<?= base_url('calculator/magic-wheel') ?>" class="block px-4 py-2 text-sm text-gray-300 hover:bg-dark-800 hover:text-brand-400">Magic Wheel MLBB</a>
                        </div>
                    </div>
                </nav>

                <!-- User Account / Actions -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <!-- Mobile Pesanan Saya Button -->
                    <a href="<?= base_url('tracking') ?>" class="md:hidden px-3 py-1.5 rounded-xl text-xs font-bold text-cyan-300 bg-cyan-950/60 border border-cyan-500/40 hover:border-cyan-400 flex items-center space-x-1.5 shadow-sm" title="Pesanan Saya">
                        <i data-lucide="receipt" class="w-4 h-4 text-cyan-400"></i>
                        <span>Pesanan Saya</span>
                    </a>

                    <?php if (session()->get('user_logged_in')): ?>
                        <div class="flex items-center space-x-2 bg-dark-card border border-gray-700 px-3 py-1.5 rounded-xl">
                            <div class="text-right hidden sm:block">
                                <div class="text-xs font-bold text-gray-200"><?= esc(session()->get('user_name')) ?></div>
                                <div class="text-[10px] text-brand-400 font-semibold uppercase"><?= esc(session()->get('user_tier')) ?> MEMBER</div>
                            </div>
                            <a href="<?= base_url('member/dashboard') ?>" class="p-1.5 rounded-lg bg-brand-500/20 text-brand-400 hover:bg-brand-500 hover:text-white transition" title="Dashboard Member">
                                <i data-lucide="user" class="w-4 h-4"></i>
                            </a>
                            <a href="<?= base_url('logout') ?>" class="p-1.5 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white transition" title="Logout">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                            </a>
                        </div>
                    <?php else: ?>
                        <a href="<?= base_url('login') ?>" class="px-4 py-2 rounded-xl text-sm font-semibold text-gray-300 hover:text-white bg-dark-800 hover:bg-dark-700 border border-gray-700 transition">
                            Masuk
                        </a>
                        <a href="<?= base_url('register') ?>" class="px-4 py-2 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-brand-500 to-blue-600 hover:from-brand-600 hover:to-blue-700 shadow-md shadow-brand-500/20 transition">
                            Daftar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Notifications -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-emerald-950/70 border border-emerald-500/40 text-emerald-300 px-4 py-3 rounded-xl flex items-center justify-between shadow-lg">
                <div class="flex items-center space-x-2">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-400"></i>
                    <span class="text-sm font-medium"><?= session()->getFlashdata('success') ?></span>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-950/70 border border-red-500/40 text-red-300 px-4 py-3 rounded-xl flex items-center justify-between shadow-lg">
                <div class="flex items-center space-x-2">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-400"></i>
                    <span class="text-sm font-medium"><?= session()->getFlashdata('error') ?></span>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Content Area -->
    <main class="flex-grow">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-dark-800 border-t border-gray-800 mt-20 pt-12 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <!-- Brand Info -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-9 h-9 rounded-xl bg-brand-500 flex items-center justify-center font-heading font-extrabold text-lg text-white">
                            N
                        </div>
                        <span class="font-heading font-extrabold text-2xl tracking-tight text-white">
                            <?= esc($settings['site_name'] ?? 'NORVAGO') ?>
                        </span>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed max-w-md">
                        <?= esc($settings['site_description'] ?? 'Platform top up game online terpercaya nomor 1 di Indonesia dengan transaksi otomatis 24 jam dan metode pembayaran QRIS All Payment.') ?>
                    </p>
                    <div class="flex items-center space-x-3 pt-2">
                        <?php if (!empty($settings['whatsapp_cs'])): ?>
                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $settings['whatsapp_cs']) ?>" target="_blank" class="w-10 h-10 rounded-xl bg-dark-700 hover:bg-emerald-600 text-gray-300 hover:text-white flex items-center justify-center transition">
                                <i data-lucide="message-circle" class="w-5 h-5"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($settings['instagram_url'])): ?>
                            <a href="<?= esc($settings['instagram_url']) ?>" target="_blank" class="w-10 h-10 rounded-xl bg-dark-700 hover:bg-pink-600 text-gray-300 hover:text-white flex items-center justify-center transition">
                                <i data-lucide="instagram" class="w-5 h-5"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($settings['telegram_channel'])): ?>
                            <a href="<?= esc($settings['telegram_channel']) ?>" target="_blank" class="w-10 h-10 rounded-xl bg-dark-700 hover:bg-blue-600 text-gray-300 hover:text-white flex items-center justify-center transition">
                                <i data-lucide="send" class="w-5 h-5"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div>
                    <h4 class="font-heading font-bold text-white text-base mb-4">Navigasi Cepat</h4>
                    <ul class="space-y-2.5 text-sm text-gray-400">
                        <li><a href="<?= base_url() ?>" class="hover:text-brand-400 transition">Beranda</a></li>
                        <li><a href="<?= base_url('tracking') ?>" class="hover:text-brand-400 transition">Lacak Pesanan</a></li>
                        <li><a href="<?= base_url('calculator/winrate') ?>" class="hover:text-brand-400 transition">Kalkulator Winrate MLBB</a></li>
                        <li><a href="<?= base_url('calculator/magic-wheel') ?>" class="hover:text-brand-400 transition">Kalkulator Magic Wheel</a></li>
                    </ul>
                </div>

                <!-- Legal & Support -->
                <div>
                    <h4 class="font-heading font-bold text-white text-base mb-4">Bantuan & CS</h4>
                    <ul class="space-y-2.5 text-sm text-gray-400">
                        <li><a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $settings['whatsapp_cs'] ?? '6281234567890') ?>" class="hover:text-emerald-400 transition flex items-center"><i data-lucide="headset" class="w-4 h-4 mr-1.5"></i> WhatsApp Support</a></li>
                        <li><span class="text-xs text-gray-500">Jam Operasional: 24 Jam Nonstop</span></li>
                        <li class="pt-3">
                            <a href="<?= base_url('admin/login') ?>" class="text-xs text-gray-500 hover:text-gray-400 transition">
                                <i data-lucide="shield" class="w-3.5 h-3.5 inline-block mr-1"></i> Admin Panel
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Copyright -->
            <div class="border-t border-gray-800/80 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-500">
                <p>&copy; <?= date('Y') ?> <?= esc($settings['site_name'] ?? 'Norvago') ?>. All rights reserved.</p>
                <p class="mt-2 sm:mt-0 font-medium">Built with <span class="text-brand-400">CodeIgniter 4</span> &amp; Realtime Dynamic QRIS Engine</p>
            </div>
        </div>
    </footer>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
