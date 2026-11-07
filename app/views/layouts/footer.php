<?php
/**
 * JayArt Modern Master Footer Layout & Customer Care Chat Widget
 * Shopcart-Inspired High-Converting E-Commerce Design
 */
?>
    </main>
    <!-- Main Content Ends -->

    <!-- Master Footer (Full Screen Edge-to-Edge) -->
    <footer class="w-full bg-[#111827] text-gray-300 pt-16 pb-24 lg:pb-12 mt-16 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 mb-12">
                
                <!-- Col 1: Brand & Philosophy -->
                <div class="lg:col-span-2">
                    <a href="<?= SITE_URL ?>" class="inline-block mb-4 group" title="JayArt Fine Art Gallery">
                        <img src="<?= ASSETS_URL ?>/images/logo.svg?v=<?= APP_VERSION ?>" alt="JayArt" class="h-9 w-auto brightness-0 invert opacity-95 group-hover:opacity-100 transition-opacity">
                    </a>
                    <p class="text-xs text-gray-400 leading-relaxed max-w-sm mb-6">
                        An acclaimed online gallery connecting art lovers with certified original paintings, modern acrylics, and fine art watercolors. Every piece arrives with an authenticated physical provenance certificate.
                    </p>
                    <div class="flex items-center gap-2 text-xs text-stone-300 font-bold">
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        <span>100% Authentic Originals Guarantee</span>
                    </div>
                </div>

                <!-- Col 2: Art Categories -->
                <div>
                    <h5 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Categories</h5>
                    <ul class="space-y-2.5 text-xs text-gray-400 font-medium">
                        <li><a href="<?= SITE_URL ?>/shop?category=oil-paintings" class="hover:text-white transition-colors">Oil Paintings</a></li>
                        <li><a href="<?= SITE_URL ?>/shop?category=acrylic-art" class="hover:text-white transition-colors">Acrylic Paintings</a></li>
                        <li><a href="<?= SITE_URL ?>/shop?category=watercolor-paintings" class="hover:text-white transition-colors">Watercolor Art</a></li>
                        <li><a href="<?= SITE_URL ?>/shop?category=modern-abstract" class="hover:text-white transition-colors">Modern Abstract</a></li>
                        <li><a href="<?= SITE_URL ?>/shop?category=traditional-heritage" class="hover:text-white transition-colors">Traditional Heritage</a></li>
                    </ul>
                </div>

                <!-- Col 3: Customer Care -->
                <div>
                    <h5 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Customer Care</h5>
                    <ul class="space-y-2.5 text-xs text-gray-400 font-medium">
                        <li><a href="<?= SITE_URL ?>/track" class="hover:text-white transition-colors">Track Order Delivery</a></li>
                        <li><a href="<?= SITE_URL ?>/profile/tickets" class="hover:text-white transition-colors">Support Tickets</a></li>
                        <li><a href="<?= SITE_URL ?>/contact" class="hover:text-white transition-colors">Help & FAQ</a></li>
                        <li><a href="<?= SITE_URL ?>/blog" class="hover:text-white transition-colors">Art Journal & Stories</a></li>
                        <li><a href="<?= SITE_URL ?>/profile" class="hover:text-white transition-colors">Account & Orders</a></li>
                    </ul>
                </div>

                <!-- Col 4: Newsletter -->
                <div>
                    <h5 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Newsletter</h5>
                    <p class="text-xs text-gray-400 leading-relaxed mb-3">
                        Receive exclusive exhibition invitations and early-access collector pricing.
                    </p>
                    <form onsubmit="event.preventDefault(); (typeof showToast === 'function' ? showToast('Thank you for subscribing to JayArt Salon!', 'success') : null);" class="space-y-2">
                        <input type="email" required placeholder="Enter your email" class="w-full text-xs px-4 py-2.5 rounded-full bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:border-stone-400">
                        <button type="submit" class="w-full py-2.5 bg-white hover:bg-stone-100 text-[#18181B] text-xs font-bold rounded-full transition-colors shadow-sm">
                            Subscribe
                        </button>
                    </form>
                </div>

            </div>

            <!-- Bottom Line: Copyright & Payment Badges -->
            <div class="pt-8 border-t border-gray-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-500">
                <p>&copy; <?= date('Y') ?> JayArt Gallery. All rights reserved. Handcrafted Original Paintings.</p>
                <div class="flex items-center gap-4 text-gray-400 font-semibold text-[11px]">
                    <span>Razorpay Secure</span>
                    <span>&bull;</span>
                    <span>Direct UPI</span>
                    <span>&bull;</span>
                    <span>COD Available</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Sticky Mobile Bottom Navigation Bar -->
    <?php require_once APP_PATH . '/views/layouts/bottom_nav.php'; ?>

    <!-- Customer Care Floating Chatbox Widget -->
    <div id="curatorChatWidget" class="fixed bottom-[74px] sm:bottom-8 right-3.5 sm:right-6 z-40">
        <!-- Chat Trigger Floating Button -->
        <button id="chatToggleBtn" type="button" class="w-12 h-12 sm:w-14 sm:h-14 p-3 sm:p-3.5 rounded-full bg-[#18181B] hover:bg-black text-white shadow-2xl flex items-center justify-center transition-all hover:scale-105 active:scale-95 border-2 border-white" aria-label="Customer Care Live Chat">
            <svg id="chatIconOpen" class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            <svg id="chatIconClose" class="w-5 h-5 sm:w-6 sm:h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            <!-- Online status badge -->
            <span class="absolute top-0 right-0 w-3 h-3 sm:w-3.5 sm:h-3.5 bg-amber-400 border-2 border-white rounded-full"></span>
        </button>

        <!-- Floating Chatbox Modal -->
        <div id="chatModal" class="hidden absolute bottom-16 right-0 w-[92vw] sm:w-[380px] bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden flex flex-col max-h-[520px]">
            <!-- Header -->
            <div class="bg-[#18181B] text-white p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center font-bold text-sm">JA</div>
                    <div>
                        <h4 class="font-bold text-xs tracking-wide">JayArt Order &amp; Curator Support</h4>
                        <p class="text-[10px] text-stone-300 flex items-center gap-1 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                            <span>Live Curator Online (Reply: &lt;1 min)</span>
                        </p>
                    </div>
                </div>
                <button id="chatCloseBtn" class="text-white/80 hover:text-white p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Messages Stream -->
            <div id="chatMessages" class="p-4 flex-1 overflow-y-auto space-y-3.5 text-xs bg-[#F8FAFC] min-h-[240px]">
                <!-- Welcome Message -->
                <div class="flex gap-2.5 items-start">
                    <div class="w-7 h-7 rounded-full bg-[#18181B] text-white flex-shrink-0 flex items-center justify-center text-[10px] font-bold">JA</div>
                    <div class="bg-white border border-gray-100 p-3.5 rounded-2xl rounded-tl-none shadow-xs text-gray-800 leading-relaxed">
                        <p class="font-bold text-gray-900 mb-1">Hello! Welcome to JayArt Gallery Concierge</p>
                        <p>How can we assist you with your art collection today? Select a quick option below or type your order number:</p>
                        
                        <!-- Quick Question Chips -->
                        <div class="mt-3 flex flex-wrap gap-1.5">
                            <button type="button" onclick="sendQuickChat('Track my order')" class="px-3 py-1.5 bg-stone-100 hover:bg-stone-200 text-[#18181B] rounded-full text-[11px] font-bold border border-stone-200 transition-colors">
                                Track Order
                            </button>
                            <button type="button" onclick="sendQuickChat('What are the custom framing options?')" class="px-3 py-1.5 bg-stone-100 hover:bg-stone-200 text-[#18181B] rounded-full text-[11px] font-bold border border-stone-200 transition-colors">
                                Framing Info
                            </button>
                            <button type="button" onclick="sendQuickChat('How to pay via UPI QR code?')" class="px-3 py-1.5 bg-stone-100 hover:bg-stone-200 text-[#18181B] rounded-full text-[11px] font-bold border border-stone-200 transition-colors">
                                UPI & Payments
                            </button>
                            <button type="button" onclick="sendQuickChat('What is your authenticity and return guarantee?')" class="px-3 py-1.5 bg-stone-100 hover:bg-stone-200 text-[#18181B] rounded-full text-[11px] font-bold border border-stone-200 transition-colors">
                                14-Day Returns
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- WhatsApp Connect Strip -->
            <div class="p-2.5 bg-stone-50 border-t border-stone-100 flex items-center justify-between px-4">
                <span class="text-[11px] text-stone-700 font-semibold">Prefer WhatsApp instant chat?</span>
                <a href="https://wa.me/919876543210?text=Hi%20JayArt%20Gallery%2C%20I%20have%20an%20inquiry%20regarding%20my%20order" target="_blank" class="text-[11px] font-bold text-[#16A34A] hover:underline flex items-center gap-1">
                    <span>Chat on WhatsApp</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <!-- Input Box -->
            <form id="chatForm" class="p-3 bg-white border-t border-gray-100 flex items-center gap-2">
                <input id="chatInput" type="text" placeholder="Type your order number or question..." class="flex-1 text-xs border border-gray-200 rounded-full px-4 py-2.5 focus:outline-none focus:border-[#18181B] bg-[#F8FAFC]">
                <button type="submit" class="p-2.5 rounded-full bg-[#18181B] text-white hover:bg-black shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Global All Filters Modal -->
    <?php require_once __DIR__ . '/filter_modal.php'; ?>

    <!-- Global Coupon Celebration & Discount Modal -->
    <?php require_once __DIR__ . '/coupon_modal.php'; ?>

    <!-- Global Custom Confirmation & Alert Modal -->
    <?php require_once __DIR__ . '/confirm_modal.php'; ?>

    <!-- Scripts (With filemtime cache-busting) -->
    <?php 
    $appJsVer = APP_VERSION;
    $appJsFile = ROOT_PATH . '/assets/js/app.js';
    if (file_exists($appJsFile)) {
        $appJsVer .= '.' . filemtime($appJsFile);
    }
    ?>
    <script src="<?= ASSETS_URL ?>/js/app.js?v=<?= $appJsVer ?>"></script>
    <script src="<?= ASSETS_URL ?>/js/cart.js?v=<?= APP_VERSION ?>"></script>
    <script src="<?= ASSETS_URL ?>/js/chatbox.js?v=<?= APP_VERSION ?>"></script>
</body>
</html>
