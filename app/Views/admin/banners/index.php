<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-heading font-extrabold text-2xl text-white">Kelola Banner Promo Slider</h1>
            <p class="text-xs text-slate-400">Atur gambar banner slider yang tampil di halaman beranda</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Add Banner Form -->
        <div class="lg:col-span-1 bg-dark-card border border-slate-800 rounded-3xl p-5 shadow-xl space-y-4">
            <h3 class="font-heading font-bold text-base text-cyan-400">Tambah Banner Baru</h3>
            
            <form action="<?= base_url('admin/banners/save') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Judul Banner <span class="text-red-400">*</span></label>
                    <input type="text" name="title" required placeholder="Contoh: Flash Sale MLBB" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Sub Judul / Deskripsi Singkat</label>
                    <input type="text" name="subtitle" placeholder="Diskon hingga 20% hari ini" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">URL Gambar Banner <span class="text-red-400">*</span></label>
                    <input type="url" name="image_url" required placeholder="https://..." class="w-full bg-dark-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">URL Link Tujuan (Opsional)</label>
                    <input type="text" name="link_url" placeholder="/order/mobile-legends atau #games" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Urutan (Sort Order)</label>
                    <input type="number" name="sort_order" value="1" class="w-full bg-dark-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-500">
                </div>
                <button type="submit" class="w-full py-2.5 rounded-xl font-heading font-bold text-xs text-white bg-cyan-600 hover:bg-cyan-500 transition shadow-md shadow-cyan-600/20">
                    Tambah Banner
                </button>
            </form>
        </div>

        <!-- Banners List -->
        <div class="lg:col-span-2 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?php foreach ($banners as $b): ?>
                    <div class="bg-dark-card border border-slate-800 rounded-3xl overflow-hidden shadow-xl flex flex-col justify-between">
                        <div class="relative aspect-video w-full overflow-hidden bg-dark-800">
                            <img src="<?= esc($b['image_url']) ?>" alt="" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-dark-900 via-transparent to-transparent opacity-80"></div>
                            <div class="absolute bottom-3 left-3 right-3">
                                <h4 class="font-heading font-bold text-sm text-white line-clamp-1"><?= esc($b['title']) ?></h4>
                                <span class="text-[10px] text-slate-300 block line-clamp-1"><?= esc($b['subtitle']) ?></span>
                            </div>
                        </div>
                        <div class="p-3.5 flex items-center justify-between border-t border-slate-800">
                            <span class="text-[10px] text-slate-400 font-mono">Urutan: <?= esc($b['sort_order']) ?></span>
                            <a href="<?= base_url('admin/banners/delete/' . $b['id']) ?>" onclick="return confirm('Hapus banner ini?')" class="px-2.5 py-1 rounded bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white font-bold text-xs transition">
                                Hapus
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
