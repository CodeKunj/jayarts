/**
 * JayArt Dynamic Cart Operations (AJAX)
 * Instant Add-to-Cart, Quantity Adjustment, Coupon Validation
 */

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

        const response = await fetch(`${SITE_URL}/cart/add`, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        const data = await response.json();

        if (data.success) {
            updateCartBadges(data.cart_count);
            showToast(data.message || 'Painting added to collection cart.', 'success');
        } else {
            showToast(data.message || 'Unable to add painting to cart.', 'error');
        }
    } catch (err) {
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

        const response = await fetch(`${SITE_URL}/cart/add`, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        const data = await response.json();
        if (data.success) {
            window.location.href = `${SITE_URL}/checkout`;
        } else {
            showToast(data.message || 'Unable to proceed to checkout.', 'error');
        }
    } catch (err) {
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

        const response = await fetch(`${SITE_URL}/cart/update`, {
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

        const response = await fetch(`${SITE_URL}/cart/remove`, {
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

