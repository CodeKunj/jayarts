/**
 * JayArt Admin Panel JavaScript
 * Responsive Drawer, Image Previews, Tab Management
 */

document.addEventListener('DOMContentLoaded', () => {
    initAdminSidebar();
    initTabSwitchers();
});

function initAdminSidebar() {
    const toggleBtn = document.getElementById('adminSidebarToggle');
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('adminSidebarOverlay');

    if (!toggleBtn || !sidebar) return;

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        overlay?.classList.toggle('open');
    });

    overlay?.addEventListener('click', () => {
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
    });
}

function initTabSwitchers() {
    document.querySelectorAll('.admin-tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-tab');
            if (!targetId) return;

            const container = btn.closest('.admin-tab-container') || document;

            // Reset buttons
            container.querySelectorAll('.admin-tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // Reset tab contents
            container.querySelectorAll('.admin-tab-content').forEach(c => c.classList.add('hidden'));
            const target = document.getElementById(targetId);
            if (target) {
                target.classList.remove('hidden');
            }
        });
    });
}

