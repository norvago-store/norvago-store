<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php 
    $allActivePms = [];
    foreach ($groupedPayments as $pms) {
        foreach ($pms as $p) $allActivePms[] = $p;
    }
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-36 sm:pb-40" x-data="orderPage()">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- LEFT COLUMN: Game Details & How to Top Up -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Game Card Summary -->
            <div class="bg-dark-card border border-gray-800 rounded-3xl p-5 sm:p-6 shadow-xl sticky top-24 space-y-5">
                <div class="flex items-center space-x-4">
                    <?php 
                        $imgSrc = $game['image_url'];
                        if (!empty($imgSrc) && !str_starts_with($imgSrc, 'http')) {
                            $imgSrc = base_url(ltrim($imgSrc, '/'));
                        }
                    ?>
                    <img src="<?= esc($imgSrc) ?>" alt="<?= esc($game['name']) ?>" class="w-20 h-20 rounded-2xl object-cover shadow-md border border-gray-700" onerror="this.onerror=null;this.src='https://placehold.co/200x200/0f172a/06b6d4?text=<?= urlencode($game['name']) ?>'">
                    <div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-brand-500/20 text-brand-400 border border-brand-500/30 uppercase">
                            <?= esc($game['developer'] ?: 'Official Game') ?>
                        </span>
                        <h1 class="font-heading font-extrabold text-xl sm:text-2xl text-white mt-1 leading-tight">
                            <?= esc($game['name']) ?>
                        </h1>
                        <span class="text-xs text-gray-400 block"><?= esc($game['subtitle']) ?></span>
                    </div>
                </div>

                <!-- Info Badges -->
                <div class="grid grid-cols-2 gap-2 text-center text-xs">
                    <div class="bg-dark-800 border border-gray-800 rounded-xl p-2.5">
                        <span class="text-gray-400 block text-[10px]">Waktu Proses</span>
                        <span class="font-bold text-emerald-400 flex items-center justify-center mt-0.5">
                            <i data-lucide="zap" class="w-3.5 h-3.5 mr-1"></i> 1 - 5 Detik
                        </span>
                    </div>
                    <div class="bg-dark-800 border border-gray-800 rounded-xl p-2.5">
                        <span class="text-gray-400 block text-[10px]">Metode Pembayaran</span>
                        <span class="font-bold text-cyan-400 flex items-center justify-center mt-0.5">
                            <i data-lucide="qr-code" class="w-3.5 h-3.5 mr-1"></i> QRIS Realtime
                        </span>
                    </div>
                </div>

                <!-- How to Order Instructions -->
                <?php if (!empty($game['instructions'])): ?>
                    <div class="bg-dark-800/80 border border-gray-800 rounded-2xl p-4 space-y-2">
                        <h4 class="text-xs font-bold text-white uppercase tracking-wider flex items-center">
                            <i data-lucide="help-circle" class="w-4 h-4 mr-1.5 text-brand-400"></i> Petunjuk Pengisian ID:
                        </h4>
                        <p class="text-xs text-gray-300 leading-relaxed">
                            <?= nl2br(esc($game['instructions'])) ?>
                        </p>
                    </div>
                <?php endif; ?>

                <!-- Reseller Tier Pricing Badge (if logged in) -->
                <?php if ($userTier !== 'basic'): ?>
                    <div class="bg-gradient-to-r from-amber-500/10 to-orange-500/10 border border-amber-500/30 rounded-2xl p-3.5 flex items-center space-x-3">
                        <i data-lucide="crown" class="w-6 h-6 text-amber-400 flex-shrink-0"></i>
                        <div>
                            <span class="text-xs font-bold text-amber-300 uppercase block"><?= esc($userTier) ?> MEMBER AKTIF</span>
                            <span class="text-[11px] text-gray-400">Anda otomatis mendapatkan harga reseller termurah!</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- RIGHT COLUMN: 5-Step Order Flow -->
        <div class="lg:col-span-2 space-y-6">

            <!-- STEP 1: Input Account Data -->
            <div class="bg-dark-card border border-gray-800 rounded-3xl p-5 sm:p-7 shadow-xl space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-brand-500 text-white font-heading font-extrabold flex items-center justify-center text-sm shadow-md shadow-brand-500/20">
                        1
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-lg text-white">Masukkan Data Akun</h3>
                        <p class="text-xs text-gray-400">Masukkan ID game Anda dengan benar</p>
                    </div>
                </div>

                <!-- Input Fields based on game schema -->
                <div class="space-y-4 pt-2">
                    <?php if ($game['target_input_type'] === 'double'): ?>
                        <!-- Double Input (e.g. MLBB: User ID + Zone ID) -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-gray-300 mb-1.5"><?= esc($game['target_input_label_1'] ?: 'User ID') ?> <span class="text-red-400">*</span></label>
                                <input type="text" x-model="userId" placeholder="<?= esc($game['target_input_placeholder_1'] ?: 'Masukkan User ID') ?>" class="w-full bg-dark-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-300 mb-1.5"><?= esc($game['target_input_label_2'] ?: 'Zone ID') ?> <span class="text-red-400">*</span></label>
                                <input type="text" x-model="zoneId" placeholder="<?= esc($game['target_input_placeholder_2'] ?: 'Zone ID') ?>" class="w-full bg-dark-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition">
                            </div>
                        </div>

                    <?php elseif ($game['target_input_type'] === 'server_dropdown'): ?>
                        <!-- Server Dropdown (e.g. Genshin: UID + Server) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-300 mb-1.5"><?= esc($game['target_input_label_1']) ?> <span class="text-red-400">*</span></label>
                                <input type="text" x-model="userId" placeholder="<?= esc($game['target_input_placeholder_1'] ?: 'Masukkan UID') ?>" class="w-full bg-dark-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-300 mb-1.5"><?= esc($game['target_input_label_2'] ?: 'Server') ?> <span class="text-red-400">*</span></label>
                                <select x-model="selectedServer" class="w-full bg-dark-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition">
                                    <option value="">-- Pilih Server --</option>
                                    <?php 
                                        $servers = json_decode($game['server_list'] ?? '[]', true) ?: [];
                                        foreach ($servers as $srv): 
                                    ?>
                                        <option value="<?= esc($srv['code'] ?? $srv['name']) ?>"><?= esc($srv['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                    <?php else: ?>
                        <!-- Single Input (e.g. Free Fire / Valorant / PUBGM) -->
                        <div>
                            <label class="block text-xs font-bold text-gray-300 mb-1.5"><?= esc($game['target_input_label_1']) ?> <span class="text-red-400">*</span></label>
                            <input type="text" x-model="userId" placeholder="<?= esc($game['target_input_placeholder_1'] ?: 'Masukkan Player ID') ?>" class="w-full bg-dark-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition">
                        </div>
                    <?php endif; ?>

                    <!-- Check ID Button & Verified Nickname Display -->
                    <div class="mt-3 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                        <button type="button" @click="checkNickname" :disabled="checkingId" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-dark-800 hover:bg-dark-700 border border-gray-700 hover:border-brand-500 transition flex items-center space-x-2">
                            <span x-show="!checkingId"><i data-lucide="search" class="w-3.5 h-3.5 inline-block mr-1"></i> Cek Nickname Akun</span>
                            <span x-show="checkingId" class="flex items-center"><i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin mr-1.5"></i> Memeriksa...</span>
                        </button>

                        <!-- Verified Badge -->
                        <div x-show="idChecked" x-transition class="bg-emerald-950/80 border border-emerald-500/50 rounded-xl px-3 py-1.5 text-xs text-emerald-300 flex items-center space-x-1.5">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400"></i>
                            <span>Username: <strong class="text-white" x-text="nickname"></strong></span>
                        </div>

                        <!-- Error Message -->
                        <div x-show="checkError" x-transition class="text-xs text-red-400 font-medium">
                            <span x-text="checkError"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 2: Select Nominal / Product -->
            <div class="bg-dark-card border border-gray-800 rounded-3xl p-5 sm:p-7 shadow-xl space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-brand-500 text-white font-heading font-extrabold flex items-center justify-center text-sm shadow-md shadow-brand-500/20">
                            2
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-lg text-white">Pilih Nominal Top Up</h3>
                            <p class="text-xs text-gray-400">Pilih item denom yang ingin Anda beli</p>
                        </div>
                    </div>
                </div>

                <!-- Product Categories Tab -->
                <?php if (count($productCategories) > 1): ?>
                    <div class="flex flex-wrap gap-2 pt-1 border-b border-gray-800 pb-3">
                        <button type="button" @click="selectedCategory = 'all'" :class="selectedCategory === 'all' ? 'bg-brand-500 text-white font-bold' : 'bg-dark-800 text-gray-400 hover:text-white border border-gray-700'" class="px-3.5 py-1.5 rounded-xl text-xs transition">
                            Semua Nominal
                        </button>
                        <?php foreach ($productCategories as $cat): ?>
                            <button type="button" @click="selectedCategory = '<?= esc($cat['name']) ?>'" :class="selectedCategory === '<?= esc($cat['name']) ?>' ? 'bg-brand-500 text-white font-bold' : 'bg-dark-800 text-gray-400 hover:text-white border border-gray-700'" class="px-3.5 py-1.5 rounded-xl text-xs transition">
                                <?= esc($cat['name']) ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Product Cards Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-2">
                    <?php foreach ($products as $product): ?>
                        <?php 
                            // Determine item price for display
                            $itemPrice = $product['price_normal'];
                            if ($product['is_flash_sale'] && $product['flash_sale_price'] > 0) {
                                $itemPrice = $product['flash_sale_price'];
                            } elseif ($userTier === 'vip' && $product['price_reseller'] > 0) {
                                $itemPrice = $product['price_reseller'];
                            } elseif ($userTier === 'gold' && $product['price_gold'] > 0) {
                                $itemPrice = $product['price_gold'];
                            }
                        ?>
                        <div x-show="selectedCategory === 'all' || selectedCategory === '<?= esc($product['category_name'] ?? '') ?>'">
                            <button type="button" 
                                    @click="selectProductId(<?= (int) $product['id'] ?>)" 
                                    :class="selectedProduct && parseInt(selectedProduct.id) === <?= (int) $product['id'] ?> ? 'border-brand-400 bg-brand-500/10 ring-2 ring-brand-400 shadow-lg shadow-cyan-500/20' : 'border-gray-800 bg-dark-800 hover:border-gray-700 hover:bg-dark-700/60'" 
                                    class="w-full text-left p-3.5 rounded-2xl border transition-all duration-200 flex flex-col justify-between h-full relative overflow-hidden group cursor-pointer">
                                
                                <!-- Flash Sale Badge -->
                                <?php if ($product['is_flash_sale']): ?>
                                    <span class="absolute top-0 right-0 px-2 py-0.5 rounded-bl-xl text-[9px] font-extrabold bg-gradient-to-r from-red-500 to-orange-500 text-white shadow-md uppercase">
                                        ⚡ Flash Sale
                                    </span>
                                <?php endif; ?>

                                <div>
                                    <h4 class="font-heading font-bold text-xs sm:text-sm text-white group-hover:text-brand-400 transition-colors line-clamp-2">
                                        <?= esc($product['name']) ?>
                                    </h4>
                                </div>

                                <div class="mt-3 pt-2 border-t border-gray-700/50 flex items-baseline justify-between">
                                    <div>
                                        <?php if ($product['is_flash_sale'] && $product['price_normal'] > $product['flash_sale_price']): ?>
                                            <span class="text-[10px] text-gray-500 line-through block -mb-0.5">
                                                Rp <?= number_format($product['price_normal'], 0, ',', '.') ?>
                                            </span>
                                        <?php endif; ?>
                                        <span class="font-heading font-extrabold text-xs sm:text-sm text-brand-400">
                                            Rp <?= number_format($itemPrice, 0, ',', '.') ?>
                                        </span>
                                    </div>
                                    <div class="w-4 h-4 rounded-full border flex items-center justify-center" :class="selectedProduct && parseInt(selectedProduct.id) === <?= (int) $product['id'] ?> ? 'border-brand-400 bg-brand-500 text-white' : 'border-gray-600'">
                                        <div x-show="selectedProduct && parseInt(selectedProduct.id) === <?= (int) $product['id'] ?>" class="w-1.5 h-1.5 rounded-full bg-white"></div>
                                    </div>
                                </div>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- STEP 3: Select Payment Method -->
            <div class="bg-dark-card border border-gray-800 rounded-3xl p-5 sm:p-7 shadow-xl space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-brand-500 text-white font-heading font-extrabold flex items-center justify-center text-sm shadow-md shadow-brand-500/20">
                        3
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-lg text-white">Pilih Metode Pembayaran</h3>
                        <p class="text-xs text-gray-400">Pilih channel pembayaran yang Anda inginkan</p>
                    </div>
                </div>

                <div class="space-y-4 pt-2">
                    <?php foreach ($groupedPayments as $groupName => $pms): ?>
                        <div class="space-y-2">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">
                                <?= esc($groupName) ?>
                            </span>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <?php 
                                    $initialPayment = !empty($allActivePms) ? $allActivePms[0] : null;
                                ?>
                                <?php foreach ($pms as $pm): ?>
                                    <?php 
                                        $isSelectedByDefault = ($initialPayment && (int)$pm['id'] === (int)$initialPayment['id']);
                                    ?>
                                    <button type="button" 
                                            @click="selectPaymentId(<?= (int) $pm['id'] ?>)" 
                                            :class="selectedPayment && parseInt(selectedPayment.id) === <?= (int) $pm['id'] ?> ? 'border-brand-400 bg-brand-500/10 ring-2 ring-brand-400/50 shadow-lg shadow-cyan-500/20' : 'border-gray-800 bg-dark-800 hover:border-gray-700 hover:bg-dark-700/60'" 
                                            class="w-full text-left p-4 rounded-2xl border transition-all duration-200 flex items-center justify-between group cursor-pointer <?= $isSelectedByDefault ? 'border-brand-400 bg-brand-500/10 ring-2 ring-brand-400/50 shadow-lg shadow-cyan-500/20' : 'border-gray-800 bg-dark-800 hover:border-gray-700 hover:bg-dark-700/60' ?>">
                                        
                                        <div class="flex items-center space-x-3">
                                            <div class="w-11 h-11 rounded-xl bg-dark-900 border p-2 flex items-center justify-center flex-shrink-0 transition-colors <?= $isSelectedByDefault ? 'border-brand-500 bg-brand-500/20 text-brand-400' : 'border-gray-700 text-gray-400' ?>" 
                                                 :class="selectedPayment && parseInt(selectedPayment.id) === <?= (int) $pm['id'] ?> ? 'border-brand-500 bg-brand-500/20 text-brand-400' : 'border-gray-700 text-gray-400'">
                                                <?php if ($pm['type'] === 'qris'): ?>
                                                    <i data-lucide="qr-code" class="w-6 h-6 text-brand-400"></i>
                                                <?php elseif ($pm['type'] === 'balance'): ?>
                                                    <i data-lucide="wallet" class="w-6 h-6 text-amber-400"></i>
                                                <?php else: ?>
                                                    <i data-lucide="credit-card" class="w-6 h-6 text-blue-400"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <h4 class="font-heading font-bold text-sm text-white group-hover:text-brand-300 transition-colors">
                                                    <?= esc($pm['name']) ?>
                                                </h4>
                                                <span class="text-xs text-emerald-400 font-semibold flex items-center mt-0.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block mr-1"></span>
                                                    Bebas Biaya Admin
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Checklist Icon indicator -->
                                        <div class="w-6 h-6 rounded-full border flex items-center justify-center transition-all duration-200 flex-shrink-0 <?= $isSelectedByDefault ? 'border-emerald-500 bg-emerald-500 shadow-md shadow-emerald-500/40 text-white' : 'border-gray-600 bg-dark-900/80 text-transparent' ?>" 
                                             :class="selectedPayment && parseInt(selectedPayment.id) === <?= (int) $pm['id'] ?> ? 'border-emerald-500 bg-emerald-500 shadow-md shadow-emerald-500/40 text-white' : 'border-gray-600 bg-dark-900/80 text-transparent'">
                                            <svg class="w-3.5 h-3.5 text-white" 
                                                 x-show="selectedPayment && parseInt(selectedPayment.id) === <?= (int) $pm['id'] ?>"
                                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- STEP 4: WhatsApp Contact & Promo Code -->
            <div class="bg-dark-card border border-gray-800 rounded-3xl p-5 sm:p-7 shadow-xl space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-brand-500 text-white font-heading font-extrabold flex items-center justify-center text-sm shadow-md shadow-brand-500/20">
                        4
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-lg text-white">Kontak &amp; Kode Promo</h3>
                        <p class="text-xs text-gray-400">Bukti pembayaran dan status transaksi akan dikirim via WhatsApp</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <!-- WhatsApp Input -->
                    <div>
                        <label class="block text-xs font-bold text-gray-300 mb-1.5">No. WhatsApp <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <i data-lucide="phone" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="tel" x-model="customerPhone" placeholder="Contoh: 081234567890" class="w-full bg-dark-800 border border-gray-700 rounded-xl pl-10 pr-4 py-3 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition">
                        </div>
                    </div>

                    <!-- Promo Code Input -->
                    <div>
                        <label class="block text-xs font-bold text-gray-300 mb-1.5">Kode Promo / Voucher</label>
                        <div class="flex space-x-2">
                            <input type="text" x-model="voucherCode" placeholder="Contoh: NORVAGAMING" class="w-full bg-dark-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-500 uppercase focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition">
                            <button type="button" @click="applyVoucher" :disabled="checkingVoucher" class="px-4 py-3 rounded-xl text-xs font-bold text-white bg-dark-800 hover:bg-dark-700 border border-gray-700 hover:border-brand-500 transition flex-shrink-0">
                                <span x-show="!checkingVoucher">Terapkan</span>
                                <span x-show="checkingVoucher"><i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i></span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Voucher Feedback -->
                <div x-show="voucherMessage" x-transition class="text-xs font-medium" :class="voucherSuccess ? 'text-emerald-400' : 'text-red-400'">
                    <span x-text="voucherMessage"></span>
                </div>
            </div>

            <!-- End of Step 4 -->
        </div>

    </div>

    <!-- STICKY PERMANENT BOTTOM PAYMENT NAVBAR -->
    <div class="fixed bottom-0 left-0 right-0 z-40 bg-dark-900/95 backdrop-blur-xl border-t border-brand-500/30 shadow-[0_-10px_35px_-5px_rgba(0,0,0,0.8)] transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4 flex items-center justify-between gap-3 sm:gap-6">
            
            <!-- Left Info: Selected Product Preview & Total Price -->
            <div class="flex items-center space-x-3 sm:space-x-4 min-w-0">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-brand-500/20 text-brand-400 border border-brand-500/30 flex items-center justify-center flex-shrink-0 hidden xs:flex">
                    <i data-lucide="shopping-bag" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center space-x-2 text-[11px] sm:text-xs text-gray-400">
                        <span class="font-medium truncate max-w-[120px] sm:max-w-xs" x-text="selectedProduct ? selectedProduct.name : 'Pilih nominal top up...'"></span>
                        <template x-if="selectedPayment">
                            <span class="hidden md:inline text-gray-500">&bull; <span class="text-gray-300 font-semibold" x-text="selectedPayment.name"></span></span>
                        </template>
                    </div>

                    <div class="flex items-baseline space-x-2">
                        <span class="text-[10px] text-gray-400 hidden sm:inline">Total:</span>
                        <div class="font-heading font-extrabold text-lg sm:text-2xl lg:text-3xl text-brand-400" x-text="formatRupiah(getTotalPrice())">
                            Rp 0
                        </div>
                    </div>

                    <div class="text-[9px] sm:text-[11px] text-gray-400 hidden sm:block truncate" x-show="selectedPayment">
                        Fee: <span class="text-gray-300" x-text="formatRupiah(getFeeAmount())"></span>
                        <template x-if="discountAmount > 0">
                            <span class="text-emerald-400 ml-1 font-bold">(Hemat <span x-text="formatRupiah(discountAmount)"></span>)</span>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Right: Beli Sekarang CTA Button -->
            <div class="flex-shrink-0">
                <button type="button" @click="validateAndOpenModal" class="px-5 sm:px-8 py-2.5 sm:py-3.5 rounded-xl sm:rounded-2xl font-heading font-extrabold text-xs sm:text-base text-white bg-gradient-to-r from-brand-500 via-cyan-500 to-blue-600 hover:from-brand-600 hover:to-blue-700 shadow-xl shadow-cyan-500/25 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center space-x-1.5 sm:space-x-2">
                    <span>Beli Sekarang</span>
                    <i data-lucide="arrow-right" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                </button>
            </div>

        </div>
    </div>

    <!-- ORDER CONFIRMATION MODAL -->
    <div x-show="confirmModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-dark-900/80 backdrop-blur-sm">
        
        <div @click.outside="confirmModal = false" class="bg-dark-card border border-gray-700 rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl space-y-5">
            <div class="flex items-center justify-between border-b border-gray-800 pb-3">
                <h3 class="font-heading font-bold text-lg text-white flex items-center">
                    <i data-lucide="shield-check" class="w-5 h-5 text-brand-400 mr-2"></i> Konfirmasi Pesanan
                </h3>
                <button @click="confirmModal = false" class="text-gray-400 hover:text-white p-1 rounded-lg">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="space-y-3 text-xs sm:text-sm">
                <div class="bg-dark-800 border border-gray-800 rounded-2xl p-4 space-y-2.5">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Game:</span>
                        <span class="font-bold text-white"><?= esc($game['name']) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">User ID / Akun:</span>
                        <span class="font-bold text-cyan-400" x-text="userId + (zoneId ? ' (' + zoneId + ')' : (selectedServer ? ' (' + selectedServer + ')' : ''))"></span>
                    </div>
                    <div class="flex justify-between" x-show="nickname">
                        <span class="text-gray-400">Nickname:</span>
                        <span class="font-bold text-emerald-400" x-text="nickname"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Item Produk:</span>
                        <span class="font-bold text-white" x-text="selectedProduct ? selectedProduct.name : ''"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Metode Bayar:</span>
                        <span class="font-bold text-white" x-text="selectedPayment ? selectedPayment.name : ''"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">No. WhatsApp:</span>
                        <span class="font-bold text-white" x-text="customerPhone"></span>
                    </div>
                </div>

                <div class="flex justify-between items-center px-1 pt-1">
                    <span class="font-bold text-gray-300">Total Tagihan:</span>
                    <span class="font-heading font-extrabold text-xl text-brand-400" x-text="formatRupiah(getTotalPrice())"></span>
                </div>
            </div>

            <div class="flex space-x-3 pt-2">
                <button type="button" @click="confirmModal = false" class="w-1/2 py-3 rounded-xl text-sm font-semibold text-gray-300 bg-dark-800 hover:bg-dark-700 border border-gray-700 transition">
                    Batal
                </button>
                <button type="button" @click="submitOrder" :disabled="submitting" class="w-1/2 py-3 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-brand-500 to-blue-600 hover:from-brand-600 hover:to-blue-700 shadow-lg shadow-brand-500/25 transition flex items-center justify-center space-x-1.5">
                    <span x-show="!submitting">Konfirmasi &amp; Bayar</span>
                    <span x-show="submitting" class="flex items-center"><i data-lucide="loader-2" class="w-4 h-4 animate-spin mr-1.5"></i> Memproses...</span>
                </button>
            </div>
        </div>

    </div>

</div>

<script>
const paymentMethodsList = <?= json_encode($allActivePms) ?>;
const productsList = <?= json_encode($products) ?>;

function orderPage() {
    return {
        gameId: <?= (int) $game['id'] ?>,
        userId: '',
        zoneId: '',
        selectedServer: '',
        nickname: '',
        checkingId: false,
        idChecked: false,
        checkError: '',

        products: productsList,
        selectedProduct: null,
        selectedCategory: 'all',

        payments: paymentMethodsList,
        // Payment pre-selected with QRIS by default!
        selectedPayment: paymentMethodsList.length > 0 ? paymentMethodsList[0] : null,
        
        voucherCode: '',
        discountAmount: 0,
        voucherMessage: '',
        voucherSuccess: false,
        checkingVoucher: false,

        customerPhone: '<?= session()->get('user_logged_in') ? esc($user['phone'] ?? '') : '' ?>',

        confirmModal: false,
        submitting: false,

        selectProductId(id) {
            this.selectedProduct = this.products.find(p => parseInt(p.id) === parseInt(id)) || null;
            if (this.voucherSuccess) {
                this.applyVoucher();
            }
        },

        selectPaymentId(id) {
            this.selectedPayment = this.payments.find(p => parseInt(p.id) === parseInt(id)) || null;
        },

        getBasePrice() {
            if (!this.selectedProduct) return 0;
            let p = this.selectedProduct;
            <?php if ($userTier === 'vip'): ?>
                if (p.price_reseller > 0) return parseFloat(p.price_reseller);
            <?php elseif ($userTier === 'gold'): ?>
                if (p.price_gold > 0) return parseFloat(p.price_gold);
            <?php endif; ?>
            if (p.is_flash_sale == 1 && parseFloat(p.flash_sale_price) > 0) {
                return parseFloat(p.flash_sale_price);
            }
            return parseFloat(p.price_normal);
        },

        getFeeAmount() {
            if (!this.selectedPayment) return 0;
            let base = this.getBasePrice();
            let flat = parseFloat(this.selectedPayment.fee_flat || 0);
            let pct = parseFloat(this.selectedPayment.fee_percent || 0);
            return flat + ((base * pct) / 100);
        },

        getTotalPrice() {
            let base = this.getBasePrice();
            let fee = this.getFeeAmount();
            let total = (base + fee) - this.discountAmount;
            return total > 0 ? total : 0;
        },

        formatRupiah(num) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num);
        },

        apiUrl(endpoint) {
            endpoint = endpoint.replace(/^\//, '');
            let base = '<?= base_url() ?>';
            if (!base.endsWith('/')) base += '/';
            if (window.location.origin && !base.startsWith(window.location.origin)) {
                let basePath = '<?= parse_url(base_url(), PHP_URL_PATH) ?: '/' ?>';
                if (!basePath.endsWith('/')) basePath += '/';
                return window.location.origin + basePath + endpoint;
            }
            return base + endpoint;
        },

        async checkNickname() {
            if (!this.userId) {
                this.checkError = 'Harap isi ID Game terlebih dahulu';
                return;
            }
            this.checkingId = true;
            this.checkError = '';
            this.idChecked = false;

            let formData = new FormData();
            formData.append('game_id', this.gameId);
            formData.append('user_id', this.userId);
            formData.append('zone_id', this.zoneId || this.selectedServer);

            try {
                let res = await fetch(this.apiUrl('order/check-id'), {
                    method: 'POST',
                    body: formData
                });
                let data = await res.json();
                if (data.status === 'success') {
                    this.nickname = data.nickname;
                    this.idChecked = true;
                } else {
                    this.checkError = data.message;
                }
            } catch (e) {
                this.checkError = 'Gagal memeriksa ID. Pastikan ID & Zone benar atau coba beberapa saat lagi.';
            } finally {
                this.checkingId = false;
            }
        },

        async applyVoucher() {
            if (!this.voucherCode || !this.selectedProduct) return;
            this.checkingVoucher = true;
            this.voucherMessage = '';

            let formData = new FormData();
            formData.append('code', this.voucherCode);
            formData.append('amount', this.getBasePrice());

            try {
                let res = await fetch(this.apiUrl('order/apply-voucher'), {
                    method: 'POST',
                    body: formData
                });
                let data = await res.json();
                if (data.status === 'success') {
                    this.discountAmount = parseFloat(data.discount_amount);
                    this.voucherMessage = data.message;
                    this.voucherSuccess = true;
                } else {
                    this.discountAmount = 0;
                    this.voucherMessage = data.message;
                    this.voucherSuccess = false;
                }
            } catch (e) {
                this.voucherMessage = 'Gagal memproses voucher.';
                this.voucherSuccess = false;
            } finally {
                this.checkingVoucher = false;
            }
        },

        validateAndOpenModal() {
            if (!this.userId) {
                alert('Harap masukkan Data Akun Game Anda pada Langkah 1.');
                return;
            }
            if (!this.selectedProduct) {
                alert('Harap pilih Nominal Top Up pada Langkah 2.');
                return;
            }
            if (!this.selectedPayment) {
                alert('Harap pilih Metode Pembayaran pada Langkah 3.');
                return;
            }
            if (!this.customerPhone) {
                alert('Harap masukkan Nomor WhatsApp Anda pada Langkah 4.');
                return;
            }
            this.confirmModal = true;
        },

        async submitOrder() {
            this.submitting = true;
            let formData = new FormData();
            formData.append('game_id', this.gameId);
            formData.append('product_id', this.selectedProduct.id);
            formData.append('payment_method_id', this.selectedPayment.id);
            formData.append('target_user_id', this.userId);
            formData.append('target_zone_id', this.zoneId || this.selectedServer);
            formData.append('target_nickname', this.nickname);
            formData.append('customer_phone', this.customerPhone);
            formData.append('voucher_code', this.voucherCode);

            try {
                let res = await fetch(this.apiUrl('order/checkout'), {
                    method: 'POST',
                    body: formData
                });
                let data = await res.json();
                if (data.status === 'success') {
                    window.location.href = data.redirect_url;
                } else {
                    alert(data.message || 'Terjadi kesalahan saat memproses pesanan.');
                    this.submitting = false;
                }
            } catch (e) {
                alert('Gagal menghubungi server. Silakan coba lagi.');
                this.submitting = false;
            }
        }
    };
}
</script>

<?= $this->endSection() ?>
