<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8" x-data="{
    currentPoints: '',
    diamondsNeeded: null,
    coasNeeded: null,

    calculate() {
        let pts = parseInt(this.currentPoints);
        if (isNaN(pts) || pts < 0) {
            alert('Harap masukkan Magic Points saat ini!');
            return;
        }

        if (pts >= 200) {
            alert('Magic Points Anda sudah mencapai 200 (Bisa langsung klaim Skin Legend)!');
            return;
        }

        let remaining = 200 - pts;
        // In MLBB, 5 spins cost 270 diamonds (giving approx 5 points)
        // 1 spin approx 60 diamonds or 54 with discount
        let spins5 = Math.ceil(remaining / 5);
        this.diamondsNeeded = spins5 * 270;
        this.coasNeeded = spins5 * 270;
    }
}">

    <div class="text-center space-y-2">
        <div class="w-12 h-12 rounded-2xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center mx-auto shadow-lg shadow-cyan-500/20">
            <i data-lucide="sparkles" class="w-6 h-6"></i>
        </div>
        <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-white">
            Kalkulator Magic Wheel MLBB
        </h1>
        <p class="text-xs sm:text-sm text-gray-400">
            Hitung perkiraan jumlah Diamond atau Crystal of Aurora (COA) yang dibutuhkan untuk mendapatkan Magic Crystal (Skin Legend).
        </p>
    </div>

    <div class="bg-dark-card border border-gray-800 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-5">
        <div class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-300 mb-1.5">Magic Points Saat Ini (0 - 199)</label>
                <input type="number" x-model="currentPoints" placeholder="Contoh: 140" min="0" max="199" class="w-full bg-dark-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition">
            </div>

            <button type="button" @click="calculate" class="w-full py-3.5 rounded-xl font-heading font-bold text-sm text-white bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 shadow-lg shadow-cyan-500/20 transition">
                Hitung Perkiraan Diamonds
            </button>
        </div>

        <!-- Result Box -->
        <div x-show="diamondsNeeded !== null" x-transition class="bg-dark-800 border border-cyan-500/40 rounded-2xl p-6 text-center space-y-3">
            <span class="text-xs text-gray-400 block font-medium">Estimasi Diamond / COA yang Dibutuhkan:</span>
            <div class="font-heading font-extrabold text-3xl sm:text-4xl text-cyan-400">
                <span x-text="diamondsNeeded"></span> <span class="text-lg text-white">Diamonds</span>
            </div>
            <p class="text-xs text-gray-400">
                Perhitungan berdasarkan 5x spin (270 Diamonds per 5 points).
            </p>
            <div class="pt-2">
                <a href="<?= base_url('order/mobile-legends') ?>" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold text-white bg-brand-500 hover:bg-brand-600 transition">
                    Top Up Diamonds MLBB Sekarang &rarr;
                </a>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
