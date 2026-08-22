<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-10" x-data="{
    searchQuery: '',
    activeCategory: 'all',
    filterGames(gameCategory, gameName) {
        const matchesCat = (this.activeCategory === 'all' || this.activeCategory == gameCategory);
        const matchesSearch = (!this.searchQuery || gameName.toLowerCase().includes(this.searchQuery.toLowerCase()));
        return matchesCat && matchesSearch;
    }
}">

    <!-- 1. Hero Banner Slider -->
    <?php if (!empty($banners)): ?>
        <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-gray-800 bg-dark-card" x-data="{ activeSlide: 0, total: <?= count($banners) ?> }" x-init="setInterval(() => { activeSlide = (activeSlide + 1) % total }, 5000)">
            <div class="relative h-48 sm:h-72 md:h-84 lg:h-96 w-full">
                <?php foreach ($banners as $index => $banner): ?>
                    <div x-show="activeSlide === <?= $index ?>" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute inset-0">
                        <div class="w-full h-full bg-gradient-to-r from-dark-900 via-dark-900/60 to-transparent absolute z-10 flex items-center px-6 sm:px-12 md:px-16">
                            <div class="max-w-xl space-y-2 sm:space-y-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-brand-500/20 text-brand-400 border border-brand-500/30">
                                    🔥 PROMO TERBAIK HARI INI
                                </span>
                                <h2 class="font-heading font-extrabold text-2xl sm:text-4xl lg:text-5xl text-white leading-tight">
                                    <?= esc($banner['title']) ?>
                                </h2>
                                <?php if (!empty($banner['subtitle'])): ?>
                                    <p class="text-xs sm:text-base text-gray-300 line-clamp-2">
                                        <?= esc($banner['subtitle']) ?>
                                    </p>
                                <?php endif; ?>
                                <?php if (!empty($banner['link_url'])): ?>
                                    <div class="pt-1 sm:pt-2">
                                        <a href="<?= esc($banner['link_url']) ?>" class="inline-flex items-center px-4 py-2 sm:px-5 sm:py-2.5 rounded-xl font-bold text-xs sm:text-sm text-white bg-gradient-to-r from-brand-500 to-blue-600 hover:from-brand-600 hover:to-blue-700 shadow-lg shadow-brand-500/25 transition">
                                            Top Up Sekarang <i data-lucide="arrow-right" class="w-4 h-4 ml-1.5"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <img src="<?= esc($banner['image_url']) ?>" alt="<?= esc($banner['title']) ?>" class="w-full h-full object-cover object-center opacity-40">
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Slide Indicators -->
            <?php if (count($banners) > 1): ?>
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-20 flex space-x-2">
                    <?php foreach ($banners as $index => $banner): ?>
                        <button @click="activeSlide = <?= $index ?>" :class="activeSlide === <?= $index ?> ? 'w-6 bg-brand-400' : 'w-2 bg-gray-600'" class="h-2 rounded-full transition-all duration-300"></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- 2. Live Search & Filter Bar -->
    <div class="bg-dark-card border border-gray-800 rounded-2xl p-4 sm:p-5 shadow-xl flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Search Input -->
        <div class="relative w-full md:w-96">
            <i data-lucide="search" class="w-5 h-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" x-model="searchQuery" placeholder="Cari game favorit Anda (MLBB, FF, Genshin...)" class="w-full bg-dark-800 border border-gray-700 rounded-xl pl-11 pr-4 py-2.5 text-sm text-white placeholder-gray-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition">
        </div>

        <!-- Category Filter Tabs -->
        <div class="flex items-center space-x-2 overflow-x-auto w-full md:w-auto pb-1 md:pb-0 scrollbar-none">
            <button @click="activeCategory = 'all'" :class="activeCategory === 'all' ? 'bg-brand-500 text-white shadow-md shadow-brand-500/25' : 'bg-dark-800 text-gray-300 hover:bg-dark-700'" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition">
                Semua Game
            </button>
            <?php foreach ($categories as $cat): ?>
                <button @click="activeCategory = '<?= $cat['id'] ?>'" :class="activeCategory === '<?= $cat['id'] ?>' ? 'bg-brand-500 text-white shadow-md shadow-brand-500/25' : 'bg-dark-800 text-gray-300 hover:bg-dark-700'" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition">
                    <?= esc($cat['name']) ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 3. Flash Sale Section (if any) -->
    <?php if (!empty($flashSales)): ?>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center">
                        <i data-lucide="zap" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-heading font-extrabold text-xl text-white">⚡ Flash Sale Terbatas</h3>
                        <p class="text-xs text-gray-400">Harga promo termurah hari ini, stok terbatas!</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php foreach ($flashSales as $fs): ?>
                    <a href="<?= base_url('order/' . $fs['game_slug']) ?>" class="group bg-dark-card border border-amber-500/30 hover:border-amber-400 rounded-2xl p-4 flex flex-col justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-amber-500/10">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-amber-500 text-dark-900 uppercase tracking-wide">
                                    DISKON SPESIAL
                                </span>
                                <span class="text-xs text-gray-400"><?= esc($fs['game_name']) ?></span>
                            </div>
                            <h4 class="font-heading font-bold text-sm sm:text-base text-white group-hover:text-amber-400 transition-colors line-clamp-1">
                                <?= esc($fs['name']) ?>
                            </h4>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-800 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-gray-500 line-through block">Rp <?= number_format($fs['price_normal'], 0, ',', '.') ?></span>
                                <span class="font-heading font-extrabold text-sm sm:text-base text-amber-400">Rp <?= number_format($fs['flash_sale_price'], 0, ',', '.') ?></span>
                            </div>
                            <span class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center group-hover:bg-amber-500 group-hover:text-dark-900 transition">
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- 4. Game Catalog Grid -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-heading font-extrabold text-2xl text-white">🎮 Pilih Game Favorit</h3>
                <p class="text-xs sm:text-sm text-gray-400">Pilih game untuk memulai proses top up otomatis</p>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 sm:gap-5">
            <?php foreach ($allGames as $game): ?>
                <div x-show="filterGames('<?= $game['category_id'] ?>', '<?= addslashes($game['name']) ?>')" class="transition-all duration-300">
                    <a href="<?= base_url('order/' . $game['slug']) ?>" class="group block bg-dark-card border border-gray-800 hover:border-brand-500/80 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl hover:shadow-cyan-500/20 transition-all duration-300 hover:-translate-y-1.5 flex flex-col h-full">
                        <!-- Thumbnail Image -->
                        <div class="relative aspect-square w-full overflow-hidden bg-dark-800">
                            <?php if ($game['is_popular']): ?>
                                <span class="absolute top-2 right-2 z-10 px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-md">
                                    POPULER
                                </span>
                            <?php endif; ?>
                            <?php 
                                $imgSrc = $game['image_url'];
                                if (!empty($imgSrc) && !str_starts_with($imgSrc, 'http')) {
                                    $imgSrc = base_url(ltrim($imgSrc, '/'));
                                }
                            ?>
                            <img src="<?= esc($imgSrc) ?>" alt="<?= esc($game['name']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy" onerror="this.onerror=null;this.src='https://placehold.co/400x400/0f172a/06b6d4?text=<?= urlencode($game['name']) ?>'">
                            <div class="absolute inset-0 bg-gradient-to-t from-dark-900 via-transparent to-transparent opacity-80"></div>
                        </div>

                        <!-- Card Content -->
                        <div class="p-3.5 flex flex-col justify-between flex-grow">
                            <div>
                                <h4 class="font-heading font-bold text-sm sm:text-base text-white group-hover:text-brand-400 transition-colors line-clamp-1">
                                    <?= esc($game['name']) ?>
                                </h4>
                                <span class="text-[11px] text-gray-400 block mt-0.5"><?= esc($game['developer'] ?: $game['subtitle'] ?: 'Game Online') ?></span>
                            </div>
                            <div class="mt-3 pt-2.5 border-t border-gray-800/80 flex items-center justify-between">
                                <span class="text-[10px] font-bold text-emerald-400 flex items-center">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1 animate-pulse"></span> Instan
                                </span>
                                <span class="text-xs font-bold text-brand-400 group-hover:translate-x-1 transition-transform">
                                    Top Up &rarr;
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 5. Quick Tracker & Value Propositions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6">
        <!-- Feature 1 -->
        <div class="bg-dark-card border border-gray-800 p-5 rounded-2xl flex items-start space-x-4">
            <div class="w-12 h-12 rounded-xl bg-brand-500/20 text-brand-400 flex items-center justify-center flex-shrink-0">
                <i data-lucide="zap" class="w-6 h-6"></i>
            </div>
            <div>
                <h4 class="font-heading font-bold text-white text-base">Proses Otomatis 1-5 Detik</h4>
                <p class="text-xs text-gray-400 mt-1">Sistem robot kami memproses pengiriman diamonds secara otomatis detik itu juga setelah dana diterima.</p>
            </div>
        </div>

        <!-- Feature 2 -->
        <div class="bg-dark-card border border-gray-800 p-5 rounded-2xl flex items-start space-x-4">
            <div class="w-12 h-12 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center flex-shrink-0">
                <i data-lucide="qr-code" class="w-6 h-6"></i>
            </div>
            <div>
                <h4 class="font-heading font-bold text-white text-base">QRIS Dinamis Otomatis</h4>
                <p class="text-xs text-gray-400 mt-1">Scan QRIS dari GoPay, OVO, DANA, BCA, Mandiri, atau ShopeePay dengan nominal yang langsung terisi otomatis.</p>
            </div>
        </div>

        <!-- Feature 3 -->
        <div class="bg-dark-card border border-gray-800 p-5 rounded-2xl flex items-start space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center flex-shrink-0">
                <i data-lucide="shield-check" class="w-6 h-6"></i>
            </div>
            <div>
                <h4 class="font-heading font-bold text-white text-base">Garansi 100% Legal & Aman</h4>
                <p class="text-xs text-gray-400 mt-1">Semua produk resmi dan legal langsung dari developer game. Akun Anda dijamin aman 100%.</p>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
