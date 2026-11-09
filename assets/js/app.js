/**
 * JayArt Master JavaScript Application
 * Mobile Drawer, Toasts, Wishlist Toggling & Interactions
 */

document.addEventListener('DOMContentLoaded', () => {
    initSculpturePreloader();
    initMobileDrawer();
    initWishlistToggles();
});

/**
 * Classical Sculpture Preloader Controller
 */
function initSculpturePreloader() {
    const preloader = document.getElementById('jayartPreloader');
    if (!preloader) return;

    let dismissed = false;
    const hide = () => {
        if (dismissed) return;
        dismissed = true;
        preloader.classList.add('preloader-hidden');
        setTimeout(() => {
            if (preloader.parentNode) {
                preloader.remove();
            }
        }, 550);
    };

    if (document.readyState === 'complete') {
        setTimeout(hide, 320);
    } else {
        window.addEventListener('load', () => setTimeout(hide, 320));
        setTimeout(hide, 2000);
    }
}

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

const wishlistInFlight = new Set();

/**
 * Global Wishlist Toggle Function (Triggered by onclick="toggleWishlist(id, this)" or click events)
 */
async function toggleWishlist(productId, btnElement = null, explicitAction = 'toggle') {
    if (!productId) return;
    productId = parseInt(productId, 10);
    if (!productId || isNaN(productId)) return;

    if (wishlistInFlight.has(productId)) {
        return;
    }
    wishlistInFlight.add(productId);

    if (btnElement) {
        btnElement.classList.add('scale-125');
        setTimeout(() => btnElement.classList.remove('scale-125'), 200);
    }

    try {
        let base = (typeof window.SITE_URL !== 'undefined' && window.SITE_URL) ? window.SITE_URL.replace(/\/$/, '') : '';
        if (window.location.protocol === 'https:' && base.startsWith('http:')) {
            base = base.replace(/^http:/, 'https:');
        }
        const endpoint = base ? `${base}/profile/wishlist/toggle` : '/profile/wishlist/toggle';

        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('action', explicitAction);

        const response = await fetch(endpoint, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        if (!response.ok) {
            console.error('Wishlist toggle HTTP error:', response.status);
            showToast('Unable to update wishlist (' + response.status + ').', 'error');
            return;
        }

        const data = await response.json();

        // Update Heart Icon styling on all buttons for this product
        const isSaved = !!data.saved;
        const targetBtns = document.querySelectorAll(`[onclick*="toggleWishlist(${productId}"], [onclick*="removeFromWishlist(${productId}"], [onclick*="togglePinSave(${productId}"], .wishlist-btn-${productId}, .btn-wishlist[data-product-id="${productId}"]`);
        targetBtns.forEach(btn => {
            const svg = btn.querySelector('svg');
            const label = btn.querySelector('.save-label');
            if (isSaved) {
                btn.classList.add('active', 'is-saved', 'text-red-500');
                if (label) label.textContent = 'Saved';
                if (svg) {
                    svg.setAttribute('fill', 'currentColor');
                    svg.classList.add('text-red-500');
                }
            } else {
                btn.classList.remove('active', 'is-saved', 'text-red-500');
                if (label) label.textContent = 'Save';
                if (svg) {
                    svg.setAttribute('fill', 'none');
                    svg.classList.remove('text-red-500');
                }
            }
        });

        // If on wishlist page and removed, gracefully fade out the card
        if (!isSaved) {
            const wishCard = document.getElementById(`wishlist-item-${productId}`);
            if (wishCard) {
                wishCard.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
                wishCard.style.opacity = '0';
                wishCard.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    wishCard.remove();
                    const grid = document.getElementById('wishlist-grid');
                    if (grid && grid._originalPins) {
                        grid._originalPins = grid._originalPins.filter(p => p.id !== `wishlist-item-${productId}`);
                    }
                    const remaining = document.querySelectorAll('[id^="wishlist-item-"]');
                    if (remaining.length === 0) {
                        location.reload();
                    }
                }, 260);
            }
        }

        // Update header wishlist badge if present
        const badge = document.getElementById('wishlistCountBadge');
        if (badge && typeof data.wishlist_count !== 'undefined') {
            badge.textContent = data.wishlist_count;
            if (data.wishlist_count > 0) {
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }

        showToast(data.message || (isSaved ? 'Artwork added to wishlist!' : 'Artwork removed from wishlist.'), isSaved ? 'success' : 'info');
    } catch (err) {
        console.error('Wishlist toggle error:', err);
        showToast('Unable to update wishlist. Please try again.', 'error');
    } finally {
        wishlistInFlight.delete(productId);
    }
}
window.toggleWishlist = toggleWishlist;

/**
 * Explicit Wishlist Item Remover (Used on My Saved Artworks page)
 */
async function removeFromWishlist(productId, btnElement = null) {
    return toggleWishlist(productId, btnElement, 'remove');
}
window.removeFromWishlist = removeFromWishlist;

/**
 * Wishlist Heart Button Initializer for data-attribute elements
 */
function initWishlistToggles() {
    document.querySelectorAll('.btn-wishlist:not([onclick])').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            e.stopPropagation();

            const productId = btn.getAttribute('data-product-id');
            if (!productId) return;

            await toggleWishlist(productId, btn);
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

/**
 * Header Currency Switcher Dropdown (Supports Mobile & Desktop)
 */
window.toggleCurrencyDropdown = function(e) {
    if (e) e.stopPropagation();
    const btn = e ? (e.currentTarget || e.target.closest('button')) : null;
    const parent = btn ? btn.closest('.relative') : null;
    const menu = parent ? parent.querySelector('[id^="siteCurrencyMenu"]') : document.getElementById('siteCurrencyMenu');
    if (menu) {
        menu.classList.toggle('hidden');
    }
};

document.addEventListener('click', (e) => {
    document.querySelectorAll('[id^="siteCurrencyMenu"]').forEach(menu => {
        const wrap = menu.closest('.relative');
        if (!menu.classList.contains('hidden') && wrap && !wrap.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });
});

/**
 * Pinterest True Waterfall Masonry Layout Engine
 * Dynamically balances pins into responsive columns with natural variable heights and left-to-right row flow.
 */
function initPinterestWaterfall() {
    const grids = document.querySelectorAll('.pinterest-masonry-grid');
    if (!grids.length) return;

    grids.forEach(grid => {
        // Collect and cache original pins once in DOM order
        if (!grid._originalPins || !grid._originalPins.length) {
            const rawPins = Array.from(grid.querySelectorAll('.pinterest-pin'));
            if (!rawPins.length) return;
            grid._originalPins = rawPins;
        }

        const pins = grid._originalPins;
        if (!pins.length) return;

        const width = grid.getBoundingClientRect().width || window.innerWidth;

        // Dynamic responsive column counts:
        // Mobile (< 600px): 2 columns
        // Tablet (600px - 959px): 3 columns
        // Desktop (960px - 1319px): 4 columns
        // Wide Desktop (>= 1320px): 5 columns
        let colCount = 2;
        if (width >= 1320) {
            colCount = 5;
        } else if (width >= 960) {
            colCount = 4;
        } else if (width >= 600) {
            colCount = 3;
        }

        // Avoid rebuilding if already configured for this column count
        const existingColumns = grid.querySelectorAll(':scope > .pinterest-column');
        if (grid._currentCols === colCount && existingColumns.length === colCount) {
            return;
        }
        grid._currentCols = colCount;

        const gap = width < 640 ? '14px' : (width < 1024 ? '16px' : '18px');

        // Prepare flex container
        grid.innerHTML = '';
        grid.style.display = 'flex';
        grid.style.flexDirection = 'row';
        grid.style.alignItems = 'flex-start';
        grid.style.gap = gap;
        grid.style.width = '100%';
        grid.classList.add('is-waterfall-ready');

        // Create column containers and track column heights
        const columns = [];
        const colHeights = new Array(colCount).fill(0);
        for (let i = 0; i < colCount; i++) {
            const col = document.createElement('div');
            col.className = 'pinterest-column';
            col.style.cssText = `flex: 1 1 0%; min-width: 0; display: flex; flex-direction: column; gap: ${gap};`;
            grid.appendChild(col);
            columns.push(col);
        }

        // Distribute pins using Shortest-Column Masonry (True Pinterest Waterfall)
        const colWidth = (width - ((colCount - 1) * parseInt(gap, 10))) / colCount;
        pins.forEach((pin) => {
            // Pick the column with the minimum cumulative height
            let minIdx = 0;
            let minH = colHeights[0];
            for (let i = 1; i < colCount; i++) {
                if (colHeights[i] < minH) {
                    minH = colHeights[i];
                    minIdx = i;
                }
            }

            columns[minIdx].appendChild(pin);

            // Compute estimated item height contribution:
            let pinH = 320;
            const media = pin.querySelector('.pinterest-pin-media');
            if (media) {
                const aspect = media.style.aspectRatio;
                if (aspect && aspect.includes('/')) {
                    const parts = aspect.split('/').map(n => parseFloat(n.trim()));
                    if (parts.length === 2 && parts[0] > 0 && parts[1] > 0) {
                        pinH = (colWidth / (parts[0] / parts[1])) + 85;
                    }
                } else if (pin.offsetHeight > 0) {
                    pinH = pin.offsetHeight;
                }
            }
            colHeights[minIdx] += pinH;
        });
    });
}
window.initPinterestWaterfall = initPinterestWaterfall;

// Initialize on DOM load and setup debounced resize listener
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPinterestWaterfall);
} else {
    initPinterestWaterfall();
}

let pinterestResizeTimer = null;
window.addEventListener('resize', () => {
    clearTimeout(pinterestResizeTimer);
    pinterestResizeTimer = setTimeout(initPinterestWaterfall, 80);
});


