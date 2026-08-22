<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin Dashboard - Norvago') ?></title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            500: '#06b6d4',
                            600: '#0891b2',
                            700: '#0e7490',
                        },
                        dark: {
                            900: '#090d16',
                            800: '#0f172a',
                            700: '#1e293b',
                            600: '#334155',
                            card: '#131d31',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Lucide Icons & Alpine.js -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #090d16; color: #f1f5f9; }
        .font-heading { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-dark-900 text-slate-100 flex min-h-screen antialiased" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm lg:hidden"></div>

    <!-- Sidebar Navigation -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" class="fixed lg:static top-0 bottom-0 left-0 z-50 w-64 bg-dark-800 border-r border-slate-800 flex flex-col justify-between transition-transform duration-300">
        <div class="p-5 space-y-6">
            <!-- Brand Logo -->
            <a href="<?= base_url('admin/dashboard') ?>" class="flex items-center space-x-3 px-2">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center font-heading font-extrabold text-white text-lg shadow-md shadow-cyan-500/25">
                    N
                </div>
                <div>
                    <span class="font-heading font-extrabold text-lg text-white tracking-tight">NORVAGO ADMIN</span>
                    <span class="text-[10px] text-cyan-400 font-bold block -mt-1 tracking-wider uppercase">CONTROL PANEL</span>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav class="space-y-1 text-xs sm:text-sm font-semibold">
                <div class="px-3 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Ringkasan</div>
                <a href="<?= base_url('admin/dashboard') ?>" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-dark-700 transition">
                    <i data-lucide="layout-dashboard" class="w-4 h-4 text-cyan-400"></i>
                    <span>Dashboard</span>
                </a>

                <div class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Katalog Game</div>
                <a href="<?= base_url('admin/games') ?>" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-dark-700 transition">
                    <i data-lucide="gamepad-2" class="w-4 h-4 text-emerald-400"></i>
                    <span>Kelola Game</span>
                </a>
                <a href="<?= base_url('admin/products') ?>" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-dark-700 transition">
                    <i data-lucide="package" class="w-4 h-4 text-blue-400"></i>
                    <span>Produk &amp; Denom</span>
                </a>

                <div class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Transaksi &amp; QRIS</div>
                <a href="<?= base_url('admin/orders') ?>" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-dark-700 transition">
                    <i data-lucide="shopping-cart" class="w-4 h-4 text-amber-400"></i>
                    <span>Pesanan / Order</span>
                </a>
                <a href="<?= base_url('admin/mutations') ?>" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-dark-700 transition">
                    <i data-lucide="repeat" class="w-4 h-4 text-cyan-400"></i>
                    <span>Log Mutasi QRIS</span>
                </a>
                <a href="<?= base_url('admin/payments') ?>" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-dark-700 transition">
                    <i data-lucide="credit-card" class="w-4 h-4 text-purple-400"></i>
                    <span>Metode Bayar</span>
                </a>

                <div class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Promosi &amp; Web</div>
                <a href="<?= base_url('admin/banners') ?>" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-dark-700 transition">
                    <i data-lucide="image" class="w-4 h-4 text-pink-400"></i>
                    <span>Banner Slider</span>
                </a>
                <a href="<?= base_url('admin/vouchers') ?>" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-dark-700 transition">
                    <i data-lucide="ticket" class="w-4 h-4 text-yellow-400"></i>
                    <span>Kode Promo / Voucher</span>
                </a>
                <a href="<?= base_url('admin/settings') ?>" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-dark-700 transition">
                    <i data-lucide="settings" class="w-4 h-4 text-slate-400"></i>
                    <span>Pengaturan QRIS &amp; Web</span>
                </a>
            </nav>
        </div>

        <!-- Admin Profile & Logout -->
        <div class="p-4 border-t border-slate-800 bg-dark-900/50 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-lg bg-cyan-500/20 text-cyan-400 font-bold flex items-center justify-center text-xs">
                    AD
                </div>
                <div class="text-xs">
                    <div class="font-bold text-white"><?= esc(session()->get('admin_name') ?? 'Admin') ?></div>
                    <span class="text-[10px] text-slate-400">Administrator</span>
                </div>
            </div>
            <a href="<?= base_url('admin/logout') ?>" class="p-2 text-slate-400 hover:text-red-400 transition" title="Logout">
                <i data-lucide="log-out" class="w-4 h-4"></i>
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-grow flex flex-col min-w-0">
        <!-- Top Navbar -->
        <header class="h-16 bg-dark-800 border-b border-slate-800 px-4 sm:px-6 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg bg-dark-700 text-slate-300 lg:hidden">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
                <h2 class="font-heading font-bold text-base sm:text-lg text-white">
                    <?= esc($title ?? 'Admin Dashboard') ?>
                </h2>
            </div>

            <div class="flex items-center space-x-3">
                <a href="<?= base_url() ?>" target="_blank" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-300 bg-dark-700 hover:bg-dark-600 border border-slate-700 transition flex items-center">
                    <i data-lucide="external-link" class="w-3.5 h-3.5 mr-1.5"></i> Lihat Website
                </a>
            </div>
        </header>

        <!-- Flash Alert -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="px-4 sm:px-6 pt-4">
                <div class="bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 px-4 py-3 rounded-xl flex items-center space-x-2 text-xs sm:text-sm font-medium shadow-md">
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400"></i>
                    <span><?= session()->getFlashdata('success') ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="px-4 sm:px-6 pt-4">
                <div class="bg-red-950/80 border border-red-500/50 text-red-300 px-4 py-3 rounded-xl flex items-center space-x-2 text-xs sm:text-sm font-medium shadow-md">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-red-400"></i>
                    <span><?= session()->getFlashdata('error') ?></span>
                </div>
            </div>
        <?php endif; ?>

        <!-- Page Content -->
        <main class="p-4 sm:p-6 lg:p-8 flex-grow">
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
