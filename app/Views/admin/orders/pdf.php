<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Laporan Rekap Transaksi - Norvago') ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
            background-color: #ffffff;
        }
        .font-heading {
            font-family: 'Outfit', sans-serif;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
                background: white;
            }
            @page {
                size: A4 landscape;
                margin: 1.5cm;
            }
            tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body class="p-6 sm:p-10 max-w-7xl mx-auto">

    <!-- Top Action Bar (Screen Only) -->
    <div class="no-print mb-8 p-4 bg-slate-900 text-white rounded-2xl shadow-xl flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center space-x-3">
            <span class="w-3 h-3 rounded-full bg-emerald-400 animate-ping"></span>
            <span class="text-sm font-bold">Pratinjau Dokumen Rekap Transaksi</span>
        </div>
        <div class="flex items-center space-x-3">
            <a href="<?= base_url('admin/orders') ?>" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-800 hover:bg-slate-700 text-slate-300 transition">
                &larr; Kembali ke Admin
            </a>
            <button onclick="window.print()" class="px-6 py-2 rounded-xl text-xs font-bold bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 text-white shadow-lg shadow-cyan-500/25 transition flex items-center space-x-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak / Simpan PDF</span>
            </button>
        </div>
    </div>

    <!-- Document Header -->
    <div class="border-b-2 border-slate-800 pb-6 mb-6 flex justify-between items-start">
        <div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-heading font-extrabold text-xl shadow-md">
                    N
                </div>
                <div>
                    <h1 class="font-heading font-extrabold text-2xl tracking-tight text-slate-900">
                        <?= esc($settings['site_name'] ?? 'NORVAGO') ?>
                    </h1>
                    <span class="text-xs text-slate-500 font-medium">Laporan Rekapitulasi Transaksi Penjualan</span>
                </div>
            </div>
        </div>

        <div class="text-right text-xs text-slate-600 space-y-1">
            <div><span class="font-bold text-slate-800">Tanggal Cetak:</span> <?= $printDate ?> WIB</div>
            <div><span class="font-bold text-slate-800">Status Filter:</span> <?= !empty($status) ? strtoupper($status) : 'SEMUA STATUS' ?></div>
            <?php if (!empty($dateStart) || !empty($dateEnd)): ?>
                <div><span class="font-bold text-slate-800">Periode:</span> <?= esc($dateStart ?: '-') ?> s/d <?= esc($dateEnd ?: '-') ?></div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50">
            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Total Transaksi</span>
            <span class="font-heading font-extrabold text-xl text-slate-900 mt-1 block"><?= number_format($totalCount, 0, ',', '.') ?> Pesanan</span>
        </div>

        <div class="p-4 rounded-xl border border-emerald-200 bg-emerald-50">
            <span class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider block">Total Omset (Lunas)</span>
            <span class="font-heading font-extrabold text-xl text-emerald-700 mt-1 block">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></span>
        </div>

        <div class="p-4 rounded-xl border border-blue-200 bg-blue-50">
            <span class="text-[11px] font-bold text-blue-700 uppercase tracking-wider block">Transaksi Lunas</span>
            <span class="font-heading font-extrabold text-xl text-blue-700 mt-1 block"><?= number_format($totalPaidCount, 0, ',', '.') ?> Pesanan</span>
        </div>

        <div class="p-4 rounded-xl border border-amber-200 bg-amber-50">
            <span class="text-[11px] font-bold text-amber-700 uppercase tracking-wider block">Menunggu Bayar</span>
            <span class="font-heading font-extrabold text-xl text-amber-700 mt-1 block"><?= number_format($totalPendingCount, 0, ',', '.') ?> Pesanan</span>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="overflow-x-auto border border-slate-200 rounded-xl">
        <table class="w-full text-left text-xs border-collapse">
            <thead>
                <tr class="bg-slate-100 border-b border-slate-200 text-slate-700 font-bold uppercase text-[10px] tracking-wider">
                    <th class="py-3 px-3">No</th>
                    <th class="py-3 px-3">No. Invoice &amp; Waktu</th>
                    <th class="py-3 px-3">Game &amp; Item</th>
                    <th class="py-3 px-3">Target Akun</th>
                    <th class="py-3 px-3">WhatsApp</th>
                    <th class="py-3 px-3">Pembayaran</th>
                    <th class="py-3 px-3 text-right">Total (Rp)</th>
                    <th class="py-3 px-3 text-center">Status Bayar</th>
                    <th class="py-3 px-3 text-center">Pengiriman</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="9" class="py-8 text-center text-slate-400">Tidak ada transaksi ditemukan pada periode ini.</td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($orders as $order): ?>
                        <tr class="hover:bg-slate-50 <?= $no % 2 === 0 ? 'bg-slate-50/50' : 'bg-white' ?>">
                            <td class="py-3 px-3 font-semibold text-slate-500"><?= $no++ ?></td>
                            <td class="py-3 px-3 font-mono font-bold text-slate-800">
                                #<?= esc($order['invoice_no']) ?>
                                <span class="block text-[10px] text-slate-500 font-sans font-normal mt-0.5">
                                    <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                                </span>
                            </td>
                            <td class="py-3 px-3">
                                <strong class="text-slate-900 block"><?= esc($order['game_name']) ?></strong>
                                <span class="text-slate-600 text-[11px]"><?= esc($order['product_name']) ?></span>
                            </td>
                            <td class="py-3 px-3 font-mono">
                                <span class="font-bold text-slate-800"><?= esc($order['target_user_id']) ?><?= $order['target_zone_id'] ? ' ('.$order['target_zone_id'].')' : '' ?></span>
                                <?php if (!empty($order['target_nickname'])): ?>
                                    <span class="block text-[10px] text-emerald-700 font-sans"><?= esc($order['target_nickname']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-3 text-slate-700 font-mono"><?= esc($order['customer_phone']) ?></td>
                            <td class="py-3 px-3 text-slate-700"><?= esc($order['payment_name'] ?: 'QRIS') ?></td>
                            <td class="py-3 px-3 text-right font-bold text-slate-900 font-mono">
                                Rp <?= number_format($order['total_amount'], 0, ',', '.') ?>
                            </td>
                            <td class="py-3 px-3 text-center">
                                <?php if (in_array($order['payment_status'], ['paid', 'completed'])): ?>
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-emerald-100 text-emerald-800 border border-emerald-300">LUNAS</span>
                                <?php elseif ($order['payment_status'] === 'unpaid'): ?>
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-amber-100 text-amber-800 border border-amber-300">PENDING</span>
                                <?php else: ?>
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-slate-100 text-slate-600 border border-slate-300"><?= strtoupper($order['payment_status']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-3 text-center">
                                <?php if ($order['delivery_status'] === 'success'): ?>
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-cyan-100 text-cyan-800 border border-cyan-300">SUKSES</span>
                                <?php elseif ($order['delivery_status'] === 'processing'): ?>
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-blue-100 text-blue-800 border border-blue-300">PROSES</span>
                                <?php elseif ($order['delivery_status'] === 'failed'): ?>
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-red-100 text-red-800 border border-red-300">GAGAL</span>
                                <?php else: ?>
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-slate-100 text-slate-600 border border-slate-300">PENDING</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Document Footer -->
    <div class="mt-8 pt-6 border-t border-slate-200 flex justify-between items-center text-xs text-slate-500">
        <div>
            Dicetak secara otomatis dari Sistem Manajemen <strong><?= esc($settings['site_name'] ?? 'Norvago') ?></strong>
        </div>
        <div class="font-mono">
            Halaman 1 / 1
        </div>
    </div>

</body>
</html>
