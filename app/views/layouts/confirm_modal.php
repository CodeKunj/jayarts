<?php
/**
 * JayArt Global Custom Confirmation & Alert Modal
 * Replaces native browser alert(), confirm(), and prompt() dialogs
 * Nordic Atelier Executive Theme - Zero Emojis, Pure SVG, Accessible Keyboard Navigation
 */
?>
<div id="jayConfirmModal" 
     class="fixed inset-0 z-[10000] hidden items-center justify-center p-4 sm:p-6 bg-black/60 backdrop-blur-xs transition-opacity duration-200" 
     role="dialog" 
     aria-modal="true" 
     aria-labelledby="jayConfirmTitle"
     onclick="if (event.target === this) JayDialog.handleCancel();">
    
    <div id="jayConfirmCard" 
         class="relative w-full max-w-sm sm:max-w-md bg-white rounded-3xl p-6 sm:p-7 shadow-2xl border border-stone-200/90 overflow-hidden transform scale-95 opacity-0 transition-all duration-200 my-auto text-center"
         onclick="event.stopPropagation()">
        
        <!-- Top Close Button -->
        <button type="button" 
                onclick="JayDialog.handleCancel()" 
                class="absolute top-4 right-4 w-8 h-8 rounded-full bg-stone-100 hover:bg-stone-200 text-stone-500 hover:text-black flex items-center justify-center transition-colors cursor-pointer"
                aria-label="Close dialog">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <!-- Icon Container -->
        <div id="jayConfirmIconWrap" class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4 border transition-colors shadow-xs">
            <!-- Danger / Trash Icon -->
            <svg id="jayConfirmIconDanger" class="w-7 h-7 text-red-600 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            <!-- Info / Question Icon -->
            <svg id="jayConfirmIconInfo" class="w-7 h-7 text-stone-900 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <!-- Success Icon -->
            <svg id="jayConfirmIconSuccess" class="w-7 h-7 text-emerald-600 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <!-- Warning Icon -->
            <svg id="jayConfirmIconWarning" class="w-7 h-7 text-amber-600 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>

        <!-- Title -->
        <h3 id="jayConfirmTitle" class="text-lg sm:text-xl font-heading font-extrabold text-[#18181B] tracking-tight">
            Confirm Action
        </h3>

        <!-- Description / Message -->
        <p id="jayConfirmMessage" class="text-xs sm:text-sm text-stone-600 leading-relaxed mt-2 max-w-sm mx-auto">
            Are you sure you want to proceed?
        </p>

        <!-- Actions Row -->
        <div class="mt-6 grid grid-cols-2 gap-3" id="jayConfirmButtonsWrap">
            <button id="jayConfirmCancelBtn" 
                    type="button" 
                    onclick="JayDialog.handleCancel()"
                    class="w-full py-2.5 sm:py-3 px-4 rounded-full border border-stone-200 bg-white hover:bg-stone-100 text-stone-700 text-xs sm:text-sm font-bold transition-all cursor-pointer">
                Cancel
            </button>
            <button id="jayConfirmOkBtn" 
                    type="button" 
                    onclick="JayDialog.handleConfirm()"
                    class="w-full py-2.5 sm:py-3 px-4 rounded-full bg-[#18181B] hover:bg-black text-white text-xs sm:text-sm font-bold transition-all shadow-sm cursor-pointer">
                Confirm
            </button>
        </div>
    </div>
</div>

<script>
window.JayDialog = (function() {
    let currentResolver = null;

    function open(options = {}) {
        const modal = document.getElementById('jayConfirmModal');
        const card = document.getElementById('jayConfirmCard');
        const titleEl = document.getElementById('jayConfirmTitle');
        const msgEl = document.getElementById('jayConfirmMessage');
        const okBtn = document.getElementById('jayConfirmOkBtn');
        const cancelBtn = document.getElementById('jayConfirmCancelBtn');
        const iconWrap = document.getElementById('jayConfirmIconWrap');
        const buttonsWrap = document.getElementById('jayConfirmButtonsWrap');

        if (!modal || !card) return Promise.resolve(false);

        const {
            title = 'Confirm Action',
            message = 'Are you sure you want to proceed?',
            confirmText = 'Confirm',
            cancelText = 'Cancel',
            type = 'danger',
            isAlert = false
        } = options;

        titleEl.textContent = title;
        msgEl.textContent = message;
        okBtn.textContent = confirmText;
        cancelBtn.textContent = cancelText;

        // Reset icons
        document.querySelectorAll('#jayConfirmIconWrap svg').forEach(svg => svg.classList.add('hidden'));

        if (type === 'danger') {
            iconWrap.className = 'w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-red-50 border border-red-100 shadow-xs';
            const icon = document.getElementById('jayConfirmIconDanger');
            if (icon) icon.classList.remove('hidden');
            okBtn.className = 'w-full py-2.5 sm:py-3 px-4 rounded-full bg-red-600 hover:bg-red-700 text-white text-xs sm:text-sm font-bold transition-all shadow-sm cursor-pointer';
        } else if (type === 'warning') {
            iconWrap.className = 'w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-amber-50 border border-amber-100 shadow-xs';
            const icon = document.getElementById('jayConfirmIconWarning');
            if (icon) icon.classList.remove('hidden');
            okBtn.className = 'w-full py-2.5 sm:py-3 px-4 rounded-full bg-[#18181B] hover:bg-black text-white text-xs sm:text-sm font-bold transition-all shadow-sm cursor-pointer';
        } else if (type === 'success') {
            iconWrap.className = 'w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-emerald-50 border border-emerald-100 shadow-xs';
            const icon = document.getElementById('jayConfirmIconSuccess');
            if (icon) icon.classList.remove('hidden');
            okBtn.className = 'w-full py-2.5 sm:py-3 px-4 rounded-full bg-emerald-700 hover:bg-emerald-800 text-white text-xs sm:text-sm font-bold transition-all shadow-sm cursor-pointer';
        } else {
            iconWrap.className = 'w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-stone-100 border border-stone-200 shadow-xs';
            const icon = document.getElementById('jayConfirmIconInfo');
            if (icon) icon.classList.remove('hidden');
            okBtn.className = 'w-full py-2.5 sm:py-3 px-4 rounded-full bg-[#18181B] hover:bg-black text-white text-xs sm:text-sm font-bold transition-all shadow-sm cursor-pointer';
        }

        if (isAlert) {
            cancelBtn.classList.add('hidden');
            buttonsWrap.className = 'mt-6';
            okBtn.className = 'w-full py-2.5 sm:py-3 px-4 rounded-full bg-[#18181B] hover:bg-black text-white text-xs sm:text-sm font-bold transition-all shadow-sm cursor-pointer';
        } else {
            cancelBtn.classList.remove('hidden');
            buttonsWrap.className = 'mt-6 grid grid-cols-2 gap-3';
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        setTimeout(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        }, 10);

        return new Promise((resolve) => {
            currentResolver = resolve;
        });
    }

    function close(result) {
        const modal = document.getElementById('jayConfirmModal');
        const card = document.getElementById('jayConfirmCard');
        if (card) {
            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');
        }
        setTimeout(() => {
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
            document.body.style.overflow = '';
            if (currentResolver) {
                currentResolver(result);
                currentResolver = null;
            }
        }, 150);
    }

    function handleConfirm() {
        close(true);
    }

    function handleCancel() {
        close(false);
    }

    // Keyboard bindings
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('jayConfirmModal');
        if (!modal || modal.classList.contains('hidden')) return;

        if (e.key === 'Escape') {
            e.preventDefault();
            handleCancel();
        } else if (e.key === 'Enter') {
            e.preventDefault();
            handleConfirm();
        }
    });

    return {
        open,
        handleConfirm,
        handleCancel
    };
})();

// Global Helpers
window.showConfirmModal = function(options) {
    if (typeof options === 'string') {
        options = { message: options };
    }
    return window.JayDialog.open({ ...options, isAlert: false });
};

window.showAlertModal = function(options) {
    if (typeof options === 'string') {
        options = { message: options };
    }
    return window.JayDialog.open({
        title: options.title || 'Notice',
        message: options.message || '',
        confirmText: options.buttonText || options.confirmText || 'Got it',
        type: options.type || 'info',
        isAlert: true
    });
};

window.jayConfirm = window.showConfirmModal;
window.jayAlert = window.showAlertModal;

// Automatic Form Interception for data-confirm
document.addEventListener('submit', function(e) {
    const form = e.target;
    if (!form || !form.getAttribute) return;
    const confirmMsg = form.getAttribute('data-confirm');
    if (confirmMsg && !form.dataset.confirmed) {
        e.preventDefault();
        e.stopPropagation();

        const title = form.getAttribute('data-confirm-title') || 'Confirm Action';
        const confirmBtn = form.getAttribute('data-confirm-btn') || 'Confirm';
        const cancelBtn = form.getAttribute('data-cancel-btn') || 'Cancel';
        const type = form.getAttribute('data-confirm-type') || 'danger';

        window.showConfirmModal({
            title: title,
            message: confirmMsg,
            confirmText: confirmBtn,
            cancelText: cancelBtn,
            type: type
        }).then(confirmed => {
            if (confirmed) {
                form.dataset.confirmed = 'true';
                if (typeof form.requestSubmit === 'function') {
                    form.requestSubmit();
                } else {
                    form.submit();
                }
            }
        });
    }
}, true);

// Automatic Click Interception for data-confirm on buttons or links
document.addEventListener('click', function(e) {
    const trigger = e.target.closest ? e.target.closest('[data-confirm]') : null;
    if (!trigger || trigger.tagName === 'FORM') return;
    if (trigger.dataset.confirmed === 'true') {
        trigger.dataset.confirmed = '';
        return;
    }
    e.preventDefault();
    e.stopPropagation();

    const msg = trigger.getAttribute('data-confirm');
    const title = trigger.getAttribute('data-confirm-title') || 'Confirm Action';
    const confirmBtn = trigger.getAttribute('data-confirm-btn') || 'Confirm';
    const cancelBtn = trigger.getAttribute('data-cancel-btn') || 'Cancel';
    const type = trigger.getAttribute('data-confirm-type') || 'danger';

    window.showConfirmModal({
        title, message: msg, confirmText: confirmBtn, cancelText: cancelBtn, type
    }).then(ok => {
        if (ok) {
            if (trigger.tagName === 'A' && trigger.href) {
                window.location.href = trigger.href;
            } else if (trigger.type === 'submit' && trigger.form) {
                trigger.form.dataset.confirmed = 'true';
                if (typeof trigger.form.requestSubmit === 'function') {
                    trigger.form.requestSubmit(trigger);
                } else {
                    trigger.form.submit();
                }
            } else {
                trigger.dataset.confirmed = 'true';
                trigger.click();
            }
        }
    });
}, true);
</script>
