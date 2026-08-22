<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi #<?= esc($order['invoice_no']) ?></title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background: #fff;
            color: #000;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .receipt {
            max-width: 380px;
            width: 100%;
            border: 1px dashed #999;
            padding: 20px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 10px 0; }
        .flex { display: flex; justify-content: space-between; margin-bottom: 4px; font-size: 12px; }
        .title { font-size: 16px; font-weight: bold; margin-bottom: 4px; }
        .status { padding: 4px 8px; border: 1px solid #000; display: inline-block; margin-top: 5px; font-size: 11px; }
        @media print {
            body { padding: 0; }
            .receipt { border: none; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="receipt">
        <div class="text-center">
            <div class="title"><?= strtoupper(esc($settings['site_name'] ?? 'NORVAGO')) ?></div>
            <div style="font-size: 11px;"><?= esc($settings['site_tagline'] ?? 'Top Up Game Terpercaya') ?></div>
            <div style="font-size: 10px; color: #555;">WA CS: <?= esc($settings['whatsapp_cs'] ?? '081234567890') ?></div>
            <div class="status font-bold">
                <?= strtoupper($order['payment_status'] === 'paid' ? 'LUNAS / BERHASIL' : $order['payment_status']) ?>
            </div>
        </div>

        <div class="divider"></div>

        <div class="flex">
            <span>No. Invoice</span>
            <span class="font-bold">#<?= esc($order['invoice_no']) ?></span>
        </div>
        <div class="flex">
            <span>Tanggal</span>
            <span><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></span>
        </div>
        <div class="flex">
            <span>Metode Bayar</span>
            <span><?= esc($order['payment_name']) ?></span>
        </div>

        <div class="divider"></div>

        <div class="flex font-bold">
            <span>Game</span>
            <span><?= esc($order['game_name']) ?></span>
        </div>
        <div class="flex">
            <span>User ID</span>
            <span><?= esc($order['target_user_id']) ?><?= $order['target_zone_id'] ? ' ('.$order['target_zone_id'].')' : '' ?></span>
        </div>
        <?php if (!empty($order['target_nickname'])): ?>
            <div class="flex">
                <span>Nickname</span>
                <span><?= esc($order['target_nickname']) ?></span>
            </div>
        <?php endif; ?>
        <div class="flex">
            <span>Item</span>
            <span><?= esc($order['product_name']) ?></span>
        </div>

        <div class="divider"></div>

        <div class="flex">
            <span>Harga Produk</span>
            <span>Rp <?= number_format($order['price_product'], 0, ',', '.') ?></span>
        </div>
        <?php if ($order['price_fee'] > 0): ?>
            <div class="flex">
                <span>Biaya Admin</span>
                <span>Rp <?= number_format($order['price_fee'], 0, ',', '.') ?></span>
            </div>
        <?php endif; ?>
        <?php if ($order['unique_code'] > 0): ?>
            <div class="flex">
                <span>Kode Unik</span>
                <span>+Rp <?= esc($order['unique_code']) ?></span>
            </div>
        <?php endif; ?>
        <?php if ($order['discount_amount'] > 0): ?>
            <div class="flex">
                <span>Diskon</span>
                <span>-Rp <?= number_format($order['discount_amount'], 0, ',', '.') ?></span>
            </div>
        <?php endif; ?>

        <div class="divider"></div>

        <div class="flex font-bold" style="font-size: 14px;">
            <span>TOTAL BAYAR</span>
            <span>Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></span>
        </div>

        <?php if (!empty($order['provider_sn'])): ?>
            <div class="divider"></div>
            <div style="font-size: 10px; word-break: break-all;">
                <strong>SN:</strong> <?= esc($order['provider_sn']) ?>
            </div>
        <?php endif; ?>

        <div class="divider"></div>

        <div class="text-center" style="font-size: 10px; color: #555; margin-top: 10px;">
            Terima kasih telah berbelanja di <?= esc($settings['site_name'] ?? 'Norvago') ?>.<br>
            Simpan struk ini sebagai bukti transaksi yang sah.
        </div>

        <div class="no-print" style="margin-top: 20px; text-align: center;">
            <button onclick="window.print()" style="padding: 6px 16px; font-size: 12px; cursor: pointer;">Cetak Ulang</button>
        </div>
    </div>

</body>
</html>
