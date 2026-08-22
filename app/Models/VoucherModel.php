<?php

namespace App\Models;

use CodeIgniter\Model;

class VoucherModel extends Model
{
    protected $table = 'vouchers';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'code', 'name', 'type', 'amount', 'min_purchase', 'max_discount',
        'quota', 'used_count', 'valid_until', 'status'
    ];
    protected $useTimestamps = true;

    public function validateVoucher(string $code, float $amount): array
    {
        $code = strtoupper(trim($code));
        $voucher = $this->where('code', $code)->where('status', 'active')->first();

        if (!$voucher) {
            return ['valid' => false, 'message' => 'Kode voucher tidak valid atau tidak aktif'];
        }

        if ($voucher['valid_until'] && strtotime($voucher['valid_until']) < time()) {
            return ['valid' => false, 'message' => 'Kode voucher sudah kedaluwarsa'];
        }

        if ($voucher['quota'] > 0 && $voucher['used_count'] >= $voucher['quota']) {
            return ['valid' => false, 'message' => 'Kuota voucher telah habis'];
        }

        if ($amount < $voucher['min_purchase']) {
            return [
                'valid' => false,
                'message' => 'Minimal pembelian untuk voucher ini adalah Rp ' . number_format($voucher['min_purchase'], 0, ',', '.')
            ];
        }

        $discount = 0;
        if ($voucher['type'] === 'percent') {
            $discount = ($amount * $voucher['amount']) / 100;
            if ($voucher['max_discount'] > 0 && $discount > $voucher['max_discount']) {
                $discount = $voucher['max_discount'];
            }
        } else {
            $discount = $voucher['amount'];
        }

        return [
            'valid' => true,
            'voucher' => $voucher,
            'discount' => min($discount, $amount),
            'message' => 'Voucher berhasil digunakan! Hemat Rp ' . number_format($discount, 0, ',', '.')
        ];
    }
}
