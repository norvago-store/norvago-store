<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator - Norvago</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #090d16; }
        .font-heading { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4 text-slate-100 antialiased selection:bg-cyan-500 selection:text-white">

    <div class="max-w-md w-full bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-2xl space-y-6">
        
        <div class="text-center space-y-2">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center font-heading font-extrabold text-2xl text-white mx-auto shadow-lg shadow-cyan-500/25">
                N
            </div>
            <h1 class="font-heading font-extrabold text-2xl text-white">Administrator Login</h1>
            <p class="text-xs text-slate-400">Masuk ke control panel manajemen Norvago</p>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-950/80 border border-red-500/50 text-red-300 px-4 py-3 rounded-xl text-xs font-medium flex items-center space-x-2">
                <i data-lucide="alert-circle" class="w-4 h-4 text-red-400"></i>
                <span><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 px-4 py-3 rounded-xl text-xs font-medium flex items-center space-x-2">
                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400"></i>
                <span><?= session()->getFlashdata('success') ?></span>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('admin/login-process') ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1.5">Username Admin</label>
                <div class="relative">
                    <i data-lucide="user" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="username" value="<?= old('username') ?>" required placeholder="admin" class="w-full bg-slate-800 border border-slate-700 rounded-xl pl-10 pr-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1.5">Password</label>
                <div class="relative">
                    <i data-lucide="lock" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full bg-slate-800 border border-slate-700 rounded-xl pl-10 pr-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition">
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-xl font-heading font-bold text-sm text-white bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 shadow-lg shadow-cyan-500/25 transition">
                Masuk ke Dashboard
            </button>
        </form>

        <div class="text-center text-[11px] text-slate-500">
            Default credentials: <strong>admin</strong> / <strong>admin123</strong>
        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
