<?php

namespace App\Services\Payment;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrisService
{
    /**
     * Convert static QRIS string to dynamic QRIS with exact amount using EMVCo standard
     *
     * @param string $staticQris The raw merchant static QRIS payload
     * @param float|int $amount The exact total amount to be paid
     * @return string Dynamic QRIS EMVCo string
     */
    public function generateDynamicQris(string $staticQris, float $amount): string
    {
        // 1. Trim whitespace
        $qris = trim($staticQris);

        // 2. Remove Tag 63 (CRC) if already present at the end (e.g., 6304XXXX)
        if (preg_match('/6304[A-Fa-f0-9]{4}$/', $qris)) {
            $qris = substr($qris, 0, -8);
        } elseif (preg_match('/6304$/', $qris)) {
            $qris = substr($qris, 0, -4);
        }

        // 3. Change Point of Initiation Method from Static (010211) to Dynamic (010212)
        if (strpos($qris, '010211') !== false) {
            $qris = str_replace('010211', '010212', $qris);
        }

        // 4. Format Tag 54 (Transaction Amount)
        $amountStr = (string) (int) round($amount);
        $amountLen = str_pad(strlen($amountStr), 2, '0', STR_PAD_LEFT);
        $tag54 = '54' . $amountLen . $amountStr;

        // Check if Tag 58 (Country Code ID) or Tag 53 (Currency Code 360) exists to insert Tag 54 before Tag 58
        $splitTag = '5802ID';
        if (strpos($qris, $splitTag) !== false) {
            $parts = explode($splitTag, $qris, 2);
            $qris = $parts[0] . $tag54 . $splitTag . $parts[1];
        } else {
            // Fallback: append tag 54 before CRC
            $qris .= $tag54;
        }

        // 5. Append Tag 63 prefix for CRC computation (Tag 63 length 04)
        $qris .= '6304';

        // 6. Compute CRC16 CCITT
        $crc = $this->computeCRC16($qris);

        return $qris . $crc;
    }

    /**
     * Compute CRC-16/CCITT-FALSE (Polynomial 0x1021, Init 0xFFFF)
     */
    private function computeCRC16(string $data): string
    {
        $crc = 0xFFFF;
        $len = strlen($data);

        for ($c = 0; $c < $len; $c++) {
            $crc ^= (ord($data[$c]) << 8);
            for ($i = 0; $i < 8; $i++) {
                if ($crc & 0x8000) {
                    $crc = (($crc << 1) ^ 0x1021) & 0xFFFF;
                } else {
                    $crc = ($crc << 1) & 0xFFFF;
                }
            }
        }

        return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }

    /**
     * Generate Base64 Data URI image for QR Code
     *
     * @param string $payload
     * @return string Data URI (e.g. data:image/svg+xml;base64,...)
     */
    public function renderQrCodeDataUri(string $payload): string
    {
        try {
            $options = new QROptions([
                'version'      => QRCode::VERSION_AUTO,
                'outputType'   => QRCode::OUTPUT_MARKUP_SVG,
                'eccLevel'     => QRCode::ECC_M,
                'addQuietzone' => true,
                'svgUseFill'   => true,
            ]);

            $qrcode = new QRCode($options);
            return $qrcode->render($payload);
        } catch (\Throwable $e) {
            // Fallback: return public online QR rendering endpoint if needed
            return 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($payload);
        }
    }
}
