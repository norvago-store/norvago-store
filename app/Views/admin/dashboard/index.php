<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-8">
    
    <!-- Top Statistics Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Revenue Today -->
        <div class="bg-dark-card border border-slate-800 rounded-3xl p-5 shadow-xl flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Omset Hari Ini</span>
                <div class="font-heading font-extrabold text-xl sm:text-2xl text-cyan-400 mt-1">
                    Rp <?= number_format($revToday, 0, ',', '.') ?>
                </div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center">
                <i data-lucide="dollar-sign" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Card 2: Revenue Month -->
        <div class="bg-dark-card border border-slate-800 rounded-3xl p-5 shadow-xl flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Omset Bulan Ini</span>
                <div class="font-heading font-extrabold text-xl sm:text-2xl text-emerald-400 mt-1">
                    Rp <?= number_format($revMonth, 0, ',', '.') ?>
                </div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                <i data-lucide="trending-up" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Card 3: Total Orders -->
        <div class="bg-dark-card border border-slate-800 rounded-3xl p-5 shadow-xl flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Transaksi</span>
                <div class="font-heading font-extrabold text-xl sm:text-2xl text-white mt-1">
                    <?= number_format($totalOrders, 0, ',', '.') ?>
                    <span class="text-xs text-emerald-400 font-normal">(<?= $paidOrders ?> Lunas)</span>
                </div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center">
                <i data-lucide="shopping-bag" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Card 4: Total Games -->
        <div class="bg-dark-card border border-slate-800 rounded-3xl p-5 shadow-xl flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Game Aktif</span>
                <div class="font-heading font-extrabold text-xl sm:text-2xl text-purple-400 mt-1">
                    <?= number_format($totalGames, 0, ',', '.') ?> Game
                </div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-500/20 text-purple-400 flex items-center justify-center">
                <i data-lucide="gamepad" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <!-- Recent Orders & Recent Mutations -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: Recent Transactions Table -->
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-heading font-bold text-lg text-white flex items-center">
                    <i data-lucide="shopping-cart" class="w-5 h-5 text-cyan-400 mr-2"></i> Transaksi Terbaru
                </h3>
                <a href="<?= base_url('admin/orders') ?>" class="text-xs text-cyan-400 font-bold hover:underline">Lihat Semua &rarr;</a>
            </div>

            <div class="bg-dark-card border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-dark-800 text-slate-400 uppercase text-[10px] font-bold border-b border-slate-800">
                            <tr>
                                <th class="py-3 px-4">Invoice</th>
                                <th class="py-3 px-4">Game &amp; Item</th>
                                <th class="py-3 px-4">Akun</th>
                                <th class="py-3 px-4">Total</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800 text-slate-300">
                            <?php if (!empty($recentOrders)): ?>
                                <?php foreach ($recentOrders as $ord): ?>
                                    <tr class="hover:bg-dark-800/60 transition">
                                        <td class="py-3 px-4 font-mono font-bold text-cyan-400">
                                            #<?= esc($ord['invoice_no']) ?>
                                            <span class="text-[10px] text-slate-500 block font-normal"><?= date('d/m H:i', strtotime($ord['created_at'])) ?></span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="font-bold text-white"><?= esc($ord['game_name']) ?></div>
                                            <span class="text-[11px] text-slate-400"><?= esc($ord['product_name']) ?></span>
                                        </td>
                                        <td class="py-3 px-4 font-mono">
                                            <?= esc($ord['target_user_id']) ?><?= $ord['target_zone_id'] ? ' ('.$ord['target_zone_id'].')' : '' ?>
                                        </td>
                                        <td class="py-3 px-4 font-bold text-white">
                                            Rp <?= number_format($ord['total_amount'], 0, ',', '.') ?>
                                        </td>
                                        <td class="py-3 px-4">
                                            <?php if ($ord['payment_status'] === 'paid'): ?>
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">LUNAS</span>
                                            <?php elseif ($ord['payment_status'] === 'unpaid'): ?>
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30">PENDING</span>
                                            <?php else: ?>
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-500/20 text-red-400 border border-red-500/30"><?= strtoupper($ord['payment_status']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3 px-4">
                                            <a href="<?= base_url('admin/orders/detail/' . $ord['id']) ?>" class="px-2.5 py-1 rounded bg-dark-700 hover:bg-cyan-600 text-white font-bold text-[11px] transition">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-6 text-slate-500">Belum ada transaksi.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right 1 Col: Recent QRIS Mutations -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-heading font-bold text-lg text-white flex items-center">
                    <i data-lucide="repeat" class="w-5 h-5 text-cyan-400 mr-2"></i> Mutasi QRIS Realtime
                </h3>
                <a href="<?= base_url('admin/mutations') ?>" class="text-xs text-cyan-400 font-bold hover:underline">Semua &rarr;</a>
            </div>

            <div class="bg-dark-card border border-slate-800 rounded-3xl p-4 shadow-xl space-y-3">
                <?php if (!empty($recentMutations)): ?>
                    <?php foreach ($recentMutations as $mut): ?>
                        <div class="bg-dark-800 border border-slate-800 rounded-2xl p-3 flex items-center justify-between text-xs">
                            <div>
                                <span class="font-bold text-emerald-400 text-sm">
                                    +Rp <?= number_format($mut['amount'], 0, ',', '.') ?>
                                </span>
                                <span class="text-[10px] text-slate-400 block mt-0.5">
                                    <?= date('H:i:s d M', strtotime($mut['created_at'])) ?> &bull; <?= esc($mut['source']) ?>
                                </span>
                            </div>
                            <div>
                                <?php if ($mut['status'] === 'matched'): ?>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                        Matched
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30">
                                        Unmatched
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-6 text-slate-500 text-xs">
                        Belum ada mutasi masuk.
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>

<?= $this->endSection() ?>
