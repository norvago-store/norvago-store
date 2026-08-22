<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    
    <!-- User Profile Header Card -->
    <div class="bg-dark-card border border-gray-800 rounded-3xl p-6 sm:p-8 shadow-xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-brand-500 to-blue-600 text-white font-heading font-extrabold text-2xl flex items-center justify-center shadow-lg shadow-cyan-500/25">
                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <h1 class="font-heading font-extrabold text-xl sm:text-2xl text-white"><?= esc($user['name']) ?></h1>
                        <span class="px-2.5 py-0.5 rounded-md text-[10px] font-extrabold bg-brand-500/20 text-brand-400 border border-brand-500/30 uppercase">
                            <?= esc($user['tier']) ?> TIER
                        </span>
                    </div>
                    <span class="text-xs text-gray-400 block mt-0.5">@<?= esc($user['username']) ?> &bull; <?= esc($user['email']) ?></span>
                </div>
            </div>

            <!-- Balance Card -->
            <div class="bg-dark-800 border border-gray-700 rounded-2xl p-4 sm:p-5 flex items-center justify-between sm:space-x-8">
                <div>
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">SALDO AKUN</span>
                    <span class="font-heading font-extrabold text-xl sm:text-2xl text-emerald-400">
                        Rp <?= number_format($user['balance'], 0, ',', '.') ?>
                    </span>
                </div>
                <div class="pl-4 border-l border-gray-700">
                    <span class="text-[10px] text-gray-400 block">Level Diskon</span>
                    <span class="text-xs font-bold text-amber-400 capitalize"><?= esc($user['tier']) ?> Price</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Order History Section -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-heading font-bold text-lg sm:text-xl text-white flex items-center">
                <i data-lucide="history" class="w-5 h-5 text-brand-400 mr-2"></i> Riwayat Transaksi Anda
            </h3>
        </div>

        <?php if (!empty($orders)): ?>
            <div class="bg-dark-card border border-gray-800 rounded-3xl overflow-hidden shadow-xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs sm:text-sm">
                        <thead class="bg-dark-800/80 text-gray-400 border-b border-gray-800 text-[11px] uppercase">
                            <tr>
                                <th class="py-3.5 px-4 font-bold">No. Invoice</th>
                                <th class="py-3.5 px-4 font-bold">Game &amp; Item</th>
                                <th class="py-3.5 px-4 font-bold">Tujuan ID</th>
                                <th class="py-3.5 px-4 font-bold">Total Tagihan</th>
                                <th class="py-3.5 px-4 font-bold">Status Bayar</th>
                                <th class="py-3.5 px-4 font-bold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800 text-gray-300">
                            <?php foreach ($orders as $ord): ?>
                                <tr class="hover:bg-dark-800/50 transition">
                                    <td class="py-3.5 px-4 font-mono font-bold text-cyan-400">
                                        #<?= esc($ord['invoice_no']) ?>
                                        <span class="text-[10px] text-gray-500 block font-normal"><?= date('d/m/y H:i', strtotime($ord['created_at'])) ?></span>
                                    </td>
                                    <td class="py-3.5 px-4 font-semibold text-white">
                                        <?= esc($ord['game_name']) ?>
                                        <span class="text-xs text-gray-400 block"><?= esc($ord['product_name']) ?></span>
                                    </td>
                                    <td class="py-3.5 px-4 text-xs font-mono">
                                        <?= esc($ord['target_user_id']) ?><?= $ord['target_zone_id'] ? ' ('.$ord['target_zone_id'].')' : '' ?>
                                        <?php if (!empty($ord['target_nickname'])): ?>
                                            <span class="text-[10px] text-emerald-400 block font-bold"><?= esc($ord['target_nickname']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3.5 px-4 font-bold text-white">
                                        Rp <?= number_format($ord['total_amount'], 0, ',', '.') ?>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <?php if ($ord['payment_status'] === 'paid'): ?>
                                            <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                                Lunas
                                            </span>
                                        <?php elseif ($ord['payment_status'] === 'unpaid'): ?>
                                            <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30">
                                                Pending
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-red-500/20 text-red-400 border border-red-500/30">
                                                <?= ucfirst($ord['payment_status']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <a href="<?= base_url('invoice/' . $ord['invoice_no']) ?>" class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-dark-700 hover:bg-brand-500 transition inline-block">
                                            Lihat
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-dark-card border border-gray-800 rounded-3xl p-10 text-center space-y-3">
                <i data-lucide="inbox" class="w-12 h-12 text-gray-600 mx-auto"></i>
                <h4 class="font-heading font-bold text-base text-white">Belum Ada Transaksi</h4>
                <p class="text-xs text-gray-400">Anda belum melakukan pembelian apapun.</p>
                <div class="pt-2">
                    <a href="<?= base_url() ?>" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold text-white bg-brand-500 hover:bg-brand-600 transition">
                        Mulai Top Up Game &rarr;
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>

<?= $this->endSection() ?>
