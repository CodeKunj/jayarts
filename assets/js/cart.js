/**
 * JayArt Dynamic Cart Operations (AJAX)
 * Instant Add-to-Cart, Quantity Adjustment, Coupon Validation
 */

function getCartApiUrl(path) {
    const cleanPath = path.replace(/^\//, '');
    let base = (typeof window.SITE_URL !== 'undefined' && window.SITE_URL) ? window.SITE_URL.replace(/\/$/, '') : '';
    if (window.location.protocol === 'https:' && base.startsWith('http:')) {
        base = base.replace(/^http:/, 'https:');
    }
    if (base && (base.includes(window.location.host) || base.startsWith('/'))) {
        try {
            const parsed = new URL(base, window.location.origin);
            const prefix = parsed.pathname.replace(/\/$/, '');
            return prefix ? `${prefix}/${cleanPath}` : `/${cleanPath}`;
        } catch (e) {
            // fallback to base string
        }
    }
    return base ? `${base}/${cleanPath}` : `/${cleanPath}`;
}

document.addEventListener('DOMContentLoaded', () => {
    initQuickAddButtons();
});

/**
 * Handle "Add to Cart" Form Submissions and Quick Add Buttons
 */
function initQuickAddButtons() {
    document.querySelectorAll('.btn-add-to-cart').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();

            const productId = btn.getAttribute('data-product-id');
            const qty = parseInt(btn.getAttribute('data-qty') || '1', 10);
            const frame = btn.getAttribute('data-frame') || 'Rolled Canvas';

            await handleAddToCart(productId, qty, frame, btn);
        });
    });
}

/**
 * Core AJAX Add to Cart
 */
async function handleAddToCart(productId, quantity = 1, frameChosen = 'Rolled Canvas', btnElement = null) {
    if (btnElement) {
        btnElement.disabled = true;
        btnElement.classList.add('opacity-75');
    }

    try {
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('quantity', quantity);
        formData.append('frame_chosen', frameChosen);

        const response = await fetch(getCartApiUrl('cart/add'), {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        if (!response.ok) {
            console.error('Cart add failed HTTP:', response.status);
            showToast('Unable to connect to gallery server (' + response.status + ').', 'error');
            return;
        }

        let data;
        try {
            data = await response.json();
        } catch (jsonErr) {
            console.error('Non-JSON response from gallery server:', jsonErr);
            showToast('Unexpected server response. Please try again.', 'error');
            return;
        }

        if (data && data.success) {
            updateCartBadges(data.cart_count);
            showToast(data.message || 'Painting added to collection cart.', 'success');
        } else {
            showToast((data && data.message) ? data.message : 'Unable to add painting to cart.', 'error');
        }
    } catch (err) {
        console.error('Error communicating with gallery server:', err);
        showToast('Error communicating with gallery server.', 'error');
    } finally {
        if (btnElement) {
            btnElement.disabled = false;
            btnElement.classList.remove('opacity-75');
        }
    }
}

/**
 * Global Quick Add to Cart (Used by product cards across storefront)
 */
async function quickAddToCart(productId, quantity = 1, frameChosen = 'Rolled Canvas') {
    await handleAddToCart(productId, quantity, frameChosen);
}
window.quickAddToCart = quickAddToCart;

/**
 * Global Quick Buy Now (Adds to cart and redirects immediately to checkout)
 */
async function quickBuyNow(productId, quantity = 1, frameChosen = 'Rolled Canvas') {
    try {
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('quantity', quantity);
        formData.append('frame_chosen', frameChosen);

        const response = await fetch(getCartApiUrl('cart/add'), {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        if (!response.ok) {
            console.error('Buy Now failed HTTP:', response.status);
            showToast('Unable to proceed to checkout (' + response.status + ').', 'error');
            return;
        }

        let data;
        try {
            data = await response.json();
        } catch (jsonErr) {
            console.error('Non-JSON response from gallery server:', jsonErr);
            showToast('Unable to proceed to checkout. Please try again.', 'error');
            return;
        }

        if (data && data.success) {
            window.location.href = getCartApiUrl('checkout');
        } else {
            showToast((data && data.message) ? data.message : 'Unable to proceed to checkout.', 'error');
        }
    } catch (err) {
        console.error('Error redirecting to checkout:', err);
        showToast('Error redirecting to checkout.', 'error');
    }
}
window.quickBuyNow = quickBuyNow;

/**
 * Update Header & Mobile Bottom Nav Badges
 */
function updateCartBadges(count) {
    const headerBadge = document.getElementById('cartCountBadge');
    const bottomBadge = document.getElementById('bottomNavCartBadge');

    const num = parseInt(count, 10);

    [headerBadge, bottomBadge].forEach(badge => {
        if (!badge) return;
        if (num > 0) {
            badge.textContent = num;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    });
}

/**
 * Update Cart Item Quantity on Cart Page
 */
async function updateCartItem(itemKey, newQty) {
    try {
        const formData = new FormData();
        formData.append('item_key', itemKey);
        formData.append('quantity', newQty);

        const response = await fetch(getCartApiUrl('cart/update'), {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        const data = await response.json();
        if (data.success) {
            window.location.reload();
        } else {
            showToast(data.message || 'Could not update quantity', 'error');
        }
    } catch (err) {
        showToast('Error updating cart', 'error');
    }
}

/**
 * Remove Item from Cart with Custom JayArt Confirmation Modal
 */
async function removeCartItem(itemKey) {
    let confirmed = true;
    if (typeof showConfirmModal === 'function') {
        confirmed = await showConfirmModal({
            title: 'Remove Artwork',
            message: 'Are you sure you want to remove this original painting from your collection cart?',
            confirmText: 'Remove Artwork',
            cancelText: 'Keep in Cart',
            type: 'danger'
        });
    }

    if (!confirmed) {
        return;
    }

    try {
        const formData = new FormData();
        formData.append('item_key', itemKey);

        const response = await fetch(getCartApiUrl('cart/remove'), {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        const data = await response.json();
        if (data.success) {
            window.location.reload();
        } else {
            if (typeof showToast === 'function') {
                showToast(data.message || 'Could not remove artwork', 'error');
            }
        }
    } catch (err) {
        if (typeof showToast === 'function') {
            showToast('Error removing artwork from cart', 'error');
        }
    }
}

