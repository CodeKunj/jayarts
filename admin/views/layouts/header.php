<?php
/**
 * JayArt Admin Top Bar Header
 * Clean Executive SaaS Style
 */
$adminUser = Auth::admin();
?>
<header class="admin-topbar">
    <div class="flex items-center gap-3">
        <!-- Mobile Sidebar Toggle Hamburger -->
        <button type="button" id="adminSidebarToggle" class="lg:hidden p-2 text-slate-600 hover:text-slate-900 rounded-xl hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" width="20" height="20" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        <div class="flex items-center gap-2.5">
            <span class="font-heading font-extrabold text-base text-slate-900 hidden sm:inline">Gallery Administration Console</span>
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>System Online</span>
            </span>
        </div>
    </div>

    <div class="flex items-center gap-3 sm:gap-4">
        <!-- Quick Action "+ Add Artwork" -->
        <a href="<?= ADMIN_URL ?>/products/create" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#0F172A] hover:bg-[#1E293B] text-white rounded-xl text-xs font-bold transition-all shadow-xs">
            <svg class="w-3.5 h-3.5" width="14" height="14" style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            <span class="hidden sm:inline">Add Artwork</span>
        </a>

        <!-- Admin Profile -->
        <div class="flex items-center gap-2 pl-3 border-l border-slate-200 text-xs">
            <div class="w-8 h-8 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                <?= substr($adminUser['name'] ?? 'Admin', 0, 1) ?>
            </div>
            <div class="hidden sm:block text-left">
                <span class="font-bold text-slate-800 block text-xs leading-tight"><?= htmlspecialchars($adminUser['name'] ?? 'Super Admin') ?></span>
                <span class="text-[10px] text-slate-400 capitalize block leading-tight"><?= htmlspecialchars($adminUser['role'] ?? 'Administrator') ?></span>
            </div>
        </div>
    </div>
</header>
