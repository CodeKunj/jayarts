/**
 * JayArt Master JavaScript Application
 * Mobile Drawer, Toasts, Wishlist Toggling & Interactions
 */

document.addEventListener('DOMContentLoaded', () => {
    initMobileDrawer();
    initWishlistToggles();
});

/**
 * Mobile Drawer Open/Close Handler
 */
function initMobileDrawer() {
    const openBtn = document.getElementById('mobileDrawerOpen');
    const closeBtn = document.getElementById('mobileDrawerClose');
    const overlay = document.getElementById('mobileDrawerOverlay');

    if (!overlay) return;

    if (openBtn) {
        openBtn.addEventListener('click', () => {
            overlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            overlay.classList.remove('open');
            document.body.style.overflow = '';
        });
    }

    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
            overlay.classList.remove('open');
            document.body.style.overflow = '';
        }
    });
}

/**
 * Wishlist Heart Button Toggler (AJAX)
 */
function initWishlistToggles() {
    document.querySelectorAll('.btn-wishlist').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            e.stopPropagation();

            const productId = btn.getAttribute('data-product-id');
            if (!productId) return;

            try {
                const formData = new FormData();
                formData.append('product_id', productId);

                const response = await fetch(`${SITE_URL}/profile/wishlist/toggle`, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                const data = await response.json();
                if (data.redirect) {
                    window.location.href = data.redirect;
                    return;
                }

                if (data.saved) {
                    btn.classList.add('active');
                    btn.querySelector('svg').setAttribute('fill', 'currentColor');
                    showToast(data.message || 'Artwork saved to your wishlist!', 'success');
                } else {
                    btn.classList.remove('active');
                    btn.querySelector('svg').setAttribute('fill', 'none');
                    showToast(data.message || 'Artwork removed from your wishlist.', 'info');
                }
            } catch (err) {
                // If not logged in, prompt sign in
                showToast('Please sign in to save artworks to your personal wishlist.', 'error');
            }
        });
    });
}

/**
 * Global Toast Notification System
 */
function showToast(message, type = 'success') {
    let container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;

    let icon = '✓';
    if (type === 'error') icon = '✕';
    if (type === 'info') icon = 'ℹ';

    toast.innerHTML = `<span class="font-bold">${icon}</span> <span>${message}</span>`;
    container.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-10px)';
        toast.style.transition = 'all 0.25s ease';
        setTimeout(() => toast.remove(), 250);
    }, 3500);
}

