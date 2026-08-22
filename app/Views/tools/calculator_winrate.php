<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8" x-data="{
    totalMatch: '',
    currentWr: '',
    targetWr: '',
    result: null,

    calculate() {
        let t = parseInt(this.totalMatch);
        let c = parseFloat(this.currentWr);
        let tr = parseFloat(this.targetWr);

        if (!t || !c || !tr) {
            alert('Harap isi semua kolom!');
            return;
        }

        if (tr <= c) {
            alert('Target winrate harus lebih tinggi dari winrate saat ini!');
            return;
        }

        if (tr >= 100) {
            alert('Target winrate tidak bisa 100% atau lebih!');
            return;
        }

        // Formula: x = (TargetWR * TotalMatch - CurrentWR * TotalMatch) / (100 - TargetWR)
        let totalWin = (c / 100) * t;
        let winNeeded = Math.ceil(((tr * t) - (100 * totalWin)) / (100 - tr));

        if (winNeeded < 0) winNeeded = 0;
        this.result = winNeeded;
    }
}">

    <div class="text-center space-y-2">
        <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center mx-auto shadow-lg shadow-amber-500/20">
            <i data-lucide="calculator" class="w-6 h-6"></i>
        </div>
        <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-white">
            Kalkulator Winrate Mobile Legends
        </h1>
        <p class="text-xs sm:text-sm text-gray-400">
            Hitung jumlah kemenangan beruntun (win streak) yang Anda butuhkan untuk mencapai target winrate impian Anda.
        </p>
    </div>

    <div class="bg-dark-card border border-gray-800 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-5">
        <div class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-300 mb-1.5">Total Pertandingan Saat Ini</label>
                <input type="number" x-model="totalMatch" placeholder="Contoh: 350" class="w-full bg-dark-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-300 mb-1.5">Winrate Saat Ini (%)</label>
                    <input type="number" step="0.01" x-model="currentWr" placeholder="Contoh: 51.5" class="w-full bg-dark-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-300 mb-1.5">Target Winrate (%)</label>
                    <input type="number" step="0.01" x-model="targetWr" placeholder="Contoh: 60" class="w-full bg-dark-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition">
                </div>
            </div>

            <button type="button" @click="calculate" class="w-full py-3.5 rounded-xl font-heading font-bold text-sm text-dark-900 bg-gradient-to-r from-amber-400 to-orange-400 hover:from-amber-500 hover:to-orange-500 shadow-lg shadow-amber-500/20 transition">
                Hitung Win Streak yang Dibutuhkan
            </button>
        </div>

        <!-- Result Box -->
        <div x-show="result !== null" x-transition class="bg-dark-800 border border-amber-500/40 rounded-2xl p-6 text-center space-y-2">
            <span class="text-xs text-gray-400 block font-medium">Anda memerlukan sekitar:</span>
            <div class="font-heading font-extrabold text-4xl text-amber-400">
                <span x-text="result"></span> <span class="text-xl text-white">Menang Tanpa Kalah</span>
            </div>
            <p class="text-xs text-gray-400 pt-1">
                Untuk menaikkan winrate menjadi <strong class="text-white" x-text="targetWr + '%'"></strong>.
            </p>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
