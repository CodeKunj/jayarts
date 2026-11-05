<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Admin Panel — JayArt') ?></title>
    
    <!-- Google Web Fonts (Outfit + Plus Jakarta Sans + JetBrains Mono) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS with Professional Executive Theme -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        admin: {
                            sidebar: '#0B0F19',
                            dark: '#0F172A',
                            primary: '#0F172A',
                            accent: '#10B981',
                            surface: '#F8FAFC',
                            border: '#E2E8F0',
                        }
                    },
                    fontFamily: {
                        heading: ['"Outfit"', 'sans-serif'],
                        sans: ['"Plus Jakarta Sans"', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace']
                    }
                }
            }
        }
    </script>
    
    <!-- Chart.js for Admin Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Admin Master Stylesheet -->
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/admin.css?v=<?= APP_VERSION ?>">
</head>
<body class="admin-body">

    <?php require __DIR__ . '/sidebar.php'; ?>

    <div class="admin-main-wrap">
        <?php require __DIR__ . '/header.php'; ?>

        <main class="p-6 md:p-8 flex-1">
            <?php if ($flashSuccess = Auth::getFlash('success')): ?>
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-xs flex items-center gap-2">
                    <span>✓</span> <span><?= htmlspecialchars($flashSuccess) ?></span>
                </div>
            <?php endif; ?>

            <?php if ($flashErr = Auth::getFlash('error')): ?>
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-xs flex items-center gap-2">
                    <span>✕</span> <span><?= htmlspecialchars($flashErr) ?></span>
                </div>
            <?php endif; ?>

            <?= $content ?>
        </main>

        <footer class="p-6 border-t border-slate-200 text-center text-xs text-slate-500 bg-white">
            &copy; <?= date('Y') ?> JayArt Gallery Management System • High Performance PHP Engine
        </footer>
    </div>

    <!-- Global Custom Confirmation & Alert Modal -->
    <?php require_once ROOT_PATH . '/app/views/layouts/confirm_modal.php'; ?>

    <script src="<?= ASSETS_URL ?>/js/admin.js?v=<?= APP_VERSION ?>"></script>
</body>
</html>

