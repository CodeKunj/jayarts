<?php
/**
 * JayArt Dedicated Admin Login View
 * Professional Executive SaaS Interface
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JayArt Administration — Sign In</title>
    <!-- Local Production Compiled Tailwind CSS (Fast, 100% Offline, cPanel Ready, Zero-CDN Dependency) -->
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/tailwind.min.css?v=<?= APP_VERSION ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-[#0B0F19] min-h-screen flex items-center justify-center p-4 antialiased font-sans text-slate-100 relative overflow-hidden">

    <!-- Subtle background executive radial glow -->
    <div class="absolute -top-40 -left-40 w-96 h-96 rounded-full bg-emerald-500/10 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 rounded-full bg-blue-500/10 blur-3xl pointer-events-none"></div>

    <div class="max-w-md w-full bg-white text-slate-900 rounded-3xl p-8 sm:p-10 shadow-2xl border border-slate-800/20 relative z-10">
        <div class="text-center mb-8">
            <div class="flex flex-col items-center justify-center gap-1.5 mb-3">
                <img src="<?= ASSETS_URL ?>/images/logo.svg?v=<?= APP_VERSION ?>" alt="JayArt" class="h-9 w-auto object-contain">
                <span class="text-[9px] bg-slate-100 text-slate-700 border border-slate-200 px-2.5 py-0.5 rounded-full font-mono font-bold uppercase tracking-wider">Admin</span>
            </div>
            <h1 class="text-sm font-bold text-slate-800">Gallery Administration Console</h1>
            <p class="text-xs text-slate-400 mt-1">Authorized curators and managers only</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="mb-5 bg-rose-50 border border-rose-200 text-rose-700 text-xs px-4 py-3 rounded-2xl flex items-center gap-2 font-medium">
                <svg class="w-4 h-4 flex-shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <form action="<?= ADMIN_URL ?>/login" method="POST" class="space-y-4">
            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1.5">Administrator Email</label>
                <input type="email" name="email" required value="admin@jayart.com" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:bg-white transition-all font-medium">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="text-xs font-bold text-slate-700">Password</label>
                    <span class="text-[11px] text-slate-400 font-mono">Demo: admin123</span>
                </div>
                <input type="password" name="password" required value="admin123" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:bg-white transition-all font-medium">
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 bg-[#0F172A] hover:bg-slate-800 text-white rounded-xl text-xs font-bold tracking-wider transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                    <span>Sign In to Console</span>
                    <span>&rarr;</span>
                </button>
            </div>
        </form>

        <div class="text-center mt-6 pt-4 border-t border-slate-100 text-xs text-slate-400">
            &larr; <a href="<?= SITE_URL ?>" class="text-[#0F172A] font-bold hover:underline">Return to Storefront</a>
        </div>
    </div>

</body>
</html>
