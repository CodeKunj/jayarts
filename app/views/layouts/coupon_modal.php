<?php
/**
 * JayArt Animated Collector Privilege & Coupon Celebration Modal
 * Atelier Nordic Aesthetic - Zero Emojis, Pure SVG & CSS Confetti Burst
 */
?>

<!-- Celebratory & Error Modal Container -->
<div id="couponCelebrationModal" class="fixed inset-0 z-[1000] hidden items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-opacity duration-300">
    
    <!-- Modal Card (Scale-in Animation) -->
    <div id="couponModalCard" class="relative w-full max-w-md bg-white rounded-3xl p-6 sm:p-8 shadow-2xl border border-stone-200/80 overflow-hidden transform transition-all duration-300 scale-95 opacity-0">
        
        <!-- Confetti Burst Container (Pure CSS Animations) -->
        <div id="couponConfettiContainer" class="absolute inset-0 pointer-events-none overflow-hidden">
            <!-- Confetti Particles -->
            <span class="confetti-p p1"></span>
            <span class="confetti-p p2"></span>
            <span class="confetti-p p3"></span>
            <span class="confetti-p p4"></span>
            <span class="confetti-p p5"></span>
            <span class="confetti-p p6"></span>
            <span class="confetti-p p7"></span>
            <span class="confetti-p p8"></span>
            <span class="confetti-p p9"></span>
            <span class="confetti-p p10"></span>
            <span class="confetti-p p11"></span>
            <span class="confetti-p p12"></span>
        </div>

        <!-- Close Button (Top-Right) -->
        <button type="button" onclick="closeCouponModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-stone-100 hover:bg-stone-200 text-stone-500 hover:text-black flex items-center justify-center transition-colors cursor-pointer z-10" aria-label="Close dialog">
            <svg width="14" height="14" style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <!-- ================= SUCCESS STATE ================= -->
        <div id="couponSuccessState" class="text-center relative z-10">
            <!-- Glowing Celebratory Badge with SVG Ribbon -->
            <div class="relative inline-block mb-3">
                <div class="w-16 h-16 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/30 mx-auto pulse-badge" style="width: 64px; height: 64px;">
                    <svg width="30" height="30" style="width: 30px; height: 30px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="absolute -top-1 -right-1 w-6 h-6 rounded-full bg-amber-400 text-[#18181B] flex items-center justify-center font-extrabold text-[10px] shadow-sm">
                    %
                </div>
            </div>

            <!-- Privilege Badge -->
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase bg-emerald-50 text-emerald-800 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                    <span>Collector Privilege Unlocked</span>
                </span>
            </div>

            <h3 class="text-xl sm:text-2xl font-extrabold text-[#18181B] tracking-tight mt-2.5">
                Voucher Applied!
            </h3>

            <!-- Prominent Savings Display -->
            <div class="my-3 py-3 px-4 bg-emerald-50/70 border border-emerald-100 rounded-2xl">
                <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-800">You Just Saved</p>
                <div id="couponModalSavedAmount" class="text-3xl sm:text-4xl font-extrabold text-emerald-700 font-mono tracking-tight my-0.5">
                    ₹ 0.00
                </div>
                <div class="flex items-center justify-center gap-2 mt-1">
                    <span id="couponModalCodeBadge" class="text-[11px] font-mono font-bold text-stone-700 bg-white px-2.5 py-0.5 rounded-md border border-stone-200 shadow-2xs">
                        CODE
                    </span>
                    <span id="couponModalTitle" class="text-[11px] font-medium text-emerald-800 truncate max-w-[200px]">
                        Promotional Discount
                    </span>
                </div>
            </div>

            <!-- Breakdown Card -->
            <div class="bg-stone-50 rounded-2xl p-4 text-xs space-y-2 border border-stone-200/70 text-left my-4">
                <div class="flex justify-between text-stone-600">
                    <span>Original Subtotal:</span>
                    <span id="couponModalSubtotal" class="font-semibold text-stone-900 font-mono">₹ 0.00</span>
                </div>
                <div class="flex justify-between text-emerald-700 font-bold">
                    <span>Privilege Savings:</span>
                    <span id="couponModalDiscount" class="font-mono">-₹ 0.00</span>
                </div>
                <div class="flex justify-between text-stone-600">
                    <span>Insured White-Glove Delivery:</span>
                    <span class="font-bold text-emerald-700">FREE</span>
                </div>
                <hr class="border-stone-200/80 my-1.5">
                <div class="flex justify-between items-baseline pt-0.5">
                    <span class="font-extrabold text-[#18181B] text-sm">Revised Total:</span>
                    <span id="couponModalTotal" class="text-lg font-extrabold text-[#18181B] font-mono">₹ 0.00</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-2 pt-1">
                <a href="<?= SITE_URL ?>/checkout" class="block w-full py-3.5 px-6 bg-[#18181B] hover:bg-black text-white text-xs font-bold uppercase tracking-wider rounded-full shadow-sm hover:shadow-md transition-all text-center">
                    Proceed to Checkout &rarr;
                </a>
                <button type="button" onclick="closeCouponModal(true)" class="block w-full py-2.5 px-4 text-stone-600 hover:text-stone-900 text-xs font-semibold text-center transition-colors cursor-pointer">
                    Stay on Cart &amp; Review Artworks
                </button>
            </div>
        </div>

        <!-- ================= ERROR / REJECTION STATE ================= -->
        <div id="couponErrorState" class="text-center relative z-10 hidden">
            <!-- Warning Badge -->
            <div class="w-14 h-14 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center shadow-xs mx-auto mb-3" style="width: 56px; height: 56px;">
                <svg width="26" height="26" style="width: 26px; height: 26px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>

            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase bg-amber-50 text-amber-800 border border-amber-200">
                Notice
            </span>

            <h3 class="text-lg font-extrabold text-[#18181B] tracking-tight mt-2">
                Coupon Could Not Be Applied
            </h3>

            <p id="couponModalErrorMessage" class="text-xs text-stone-600 mt-2 px-2 leading-relaxed">
                The coupon code you entered is invalid or does not match the terms.
            </p>

            <div class="pt-5 space-y-2">
                <button type="button" onclick="closeCouponModal(false)" class="w-full py-3 px-6 bg-[#18181B] hover:bg-black text-white text-xs font-bold rounded-full transition-all shadow-xs cursor-pointer">
                    Try Another Code
                </button>
                <a href="<?= SITE_URL ?>/offers" class="inline-block text-[11px] font-bold text-stone-600 hover:text-black underline pt-1">
                    View All Active Collector Offers &rarr;
                </a>
            </div>
        </div>

    </div>
</div>

<style>
/* Pure CSS Confetti & Badge Animations */
@keyframes pulseBadge {
    0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
    70% { transform: scale(1.04); box-shadow: 0 0 0 14px rgba(16, 185, 129, 0); }
    100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}
.pulse-badge {
    animation: pulseBadge 2s infinite cubic-bezier(0.4, 0, 0.6, 1);
}

.confetti-p {
    position: absolute;
    width: 8px;
    height: 8px;
    opacity: 0;
}
.confetti-p.p1  { top: 20%; left: 15%; background: #10B981; border-radius: 50%; animation: popConfetti 1s ease-out forwards; animation-delay: 0.05s; }
.confetti-p.p2  { top: 25%; right: 18%; background: #F59E0B; width: 6px; height: 10px; transform: rotate(25deg); animation: popConfetti 1.1s ease-out forwards; animation-delay: 0.1s; }
.confetti-p.p3  { top: 35%; left: 10%; background: #18181B; width: 6px; height: 6px; border-radius: 2px; animation: popConfetti 0.9s ease-out forwards; animation-delay: 0.15s; }
.confetti-p.p4  { top: 30%; right: 12%; background: #34D399; border-radius: 50%; animation: popConfetti 1.2s ease-out forwards; animation-delay: 0.08s; }
.confetti-p.p5  { top: 15%; left: 30%; background: #FBBF24; width: 7px; height: 9px; transform: rotate(45deg); animation: popConfetti 1s ease-out forwards; animation-delay: 0.12s; }
.confetti-p.p6  { top: 18%; right: 30%; background: #059669; border-radius: 50%; animation: popConfetti 1.15s ease-out forwards; animation-delay: 0.18s; }
.confetti-p.p7  { top: 40%; left: 22%; background: #FDE68A; width: 8px; height: 6px; animation: popConfetti 0.95s ease-out forwards; animation-delay: 0.07s; }
.confetti-p.p8  { top: 42%; right: 24%; background: #10B981; border-radius: 50%; animation: popConfetti 1.05s ease-out forwards; animation-delay: 0.14s; }
.confetti-p.p9  { top: 28%; left: 45%; background: #D97706; width: 6px; height: 6px; animation: popConfetti 1.2s ease-out forwards; animation-delay: 0.09s; }
.confetti-p.p10 { top: 22%; right: 42%; background: #18181B; width: 5px; height: 8px; transform: rotate(15deg); animation: popConfetti 1s ease-out forwards; animation-delay: 0.16s; }
.confetti-p.p11 { top: 38%; left: 35%; background: #34D399; border-radius: 50%; animation: popConfetti 1.1s ease-out forwards; animation-delay: 0.13s; }
.confetti-p.p12 { top: 32%; right: 35%; background: #F59E0B; width: 7px; height: 7px; animation: popConfetti 0.9s ease-out forwards; animation-delay: 0.11s; }

@keyframes popConfetti {
    0% {
        transform: translateY(20px) scale(0);
        opacity: 0;
    }
    40% {
        opacity: 1;
        transform: translateY(-20px) scale(1.2);
    }
    100% {
        transform: translateY(60px) rotate(360deg) scale(0.8);
        opacity: 0;
    }
}
</style>

<script>
/**
 * Global JayArt Coupon Modal Controller
 */
let couponModalShouldReload = false;

window.openCouponCelebrationModal = function(data) {
    const modal = document.getElementById('couponCelebrationModal');
    const card = document.getElementById('couponModalCard');
    const successState = document.getElementById('couponSuccessState');
    const errorState = document.getElementById('couponErrorState');
    const confetti = document.getElementById('couponConfettiContainer');

    if (!modal || !card) return;

    // Reset confetti animation
    if (confetti) {
        confetti.style.display = 'block';
        confetti.classList.remove('active');
        void confetti.offsetWidth; // Trigger reflow
        confetti.classList.add('active');
    }

    // Populate Data
    const savedAmount = data.discount_formatted || (data.discount ? ('₹ ' + Number(data.discount).toLocaleString('en-IN', {minimumFractionDigits: 2})) : '₹ 0.00');
    const code = data.code || 'COUPON';
    const title = data.title || (data.discount_value ? (data.discount_value + (data.discount_type === 'percentage' ? '% OFF' : ' FLAT OFF')) : 'Special Privilege');
    const subtotal = data.subtotal_formatted || '₹ 0.00';
    const discount = data.discount_formatted || savedAmount;
    const total = data.total_formatted || '₹ 0.00';

    document.getElementById('couponModalSavedAmount').innerText = savedAmount;
    document.getElementById('couponModalCodeBadge').innerText = code;
    document.getElementById('couponModalTitle').innerText = title;
    document.getElementById('couponModalSubtotal').innerText = subtotal;
    document.getElementById('couponModalDiscount').innerText = '-' + discount;
    document.getElementById('couponModalTotal').innerText = total;

    // Show Success, Hide Error
    successState.classList.remove('hidden');
    errorState.classList.add('hidden');

    couponModalShouldReload = true;

    // Animate In
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
        card.classList.remove('scale-95', 'opacity-0');
        card.classList.add('scale-100', 'opacity-100');
    }, 20);
};

window.openCouponErrorModal = function(errorMessage) {
    const modal = document.getElementById('couponCelebrationModal');
    const card = document.getElementById('couponModalCard');
    const successState = document.getElementById('couponSuccessState');
    const errorState = document.getElementById('couponErrorState');
    const confetti = document.getElementById('couponConfettiContainer');

    if (!modal || !card) return;

    if (confetti) confetti.style.display = 'none';

    document.getElementById('couponModalErrorMessage').innerText = errorMessage || 'Invalid coupon code. Please verify terms and try again.';

    // Show Error, Hide Success
    successState.classList.add('hidden');
    errorState.classList.remove('hidden');

    couponModalShouldReload = false;

    // Animate In
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
        card.classList.remove('scale-95', 'opacity-0');
        card.classList.add('scale-100', 'opacity-100');
    }, 20);
};

window.closeCouponModal = function(forceReload = false) {
    const modal = document.getElementById('couponCelebrationModal');
    const card = document.getElementById('couponModalCard');

    if (!modal || !card) return;

    card.classList.remove('scale-100', 'opacity-100');
    card.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        if (forceReload || couponModalShouldReload) {
            window.location.reload();
        }
    }, 200);
};

// Close modal on escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        const modal = document.getElementById('couponCelebrationModal');
        if (modal && !modal.classList.contains('hidden')) {
            window.closeCouponModal(false);
        }
    }
});
</script>

