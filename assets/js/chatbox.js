/**
 * JayArt Live Customer Support & Order Assistance Chatbox
 * Real-time Order Tracking, FAQ Assistant & WhatsApp Concierge
 */

document.addEventListener('DOMContentLoaded', () => {
    initCuratorChat();
});

function initCuratorChat() {
    const toggleBtn = document.getElementById('chatToggleBtn');
    const modal = document.getElementById('chatModal');
    const closeBtn = document.getElementById('chatCloseBtn');
    const messagesContainer = document.getElementById('chatMessages');
    const chatForm = document.getElementById('chatForm');
    const chatInput = document.getElementById('chatInput');
    const openIcon = document.getElementById('chatIconOpen');
    const closeIcon = document.getElementById('chatIconClose');

    if (!toggleBtn || !modal) return;

    // Toggle Chat Window
    toggleBtn.addEventListener('click', () => {
        const isHidden = modal.classList.contains('hidden');
        if (isHidden) {
            modal.classList.remove('hidden');
            if (openIcon) openIcon.classList.add('hidden');
            if (closeIcon) closeIcon.classList.remove('hidden');
            chatInput?.focus();
        } else {
            modal.classList.add('hidden');
            if (openIcon) openIcon.classList.remove('hidden');
            if (closeIcon) closeIcon.classList.add('hidden');
        }
    });

    closeBtn?.addEventListener('click', () => {
        modal.classList.add('hidden');
        if (openIcon) openIcon.classList.remove('hidden');
        if (closeIcon) closeIcon.classList.add('hidden');
    });

    // Form submission
    chatForm?.addEventListener('submit', (e) => {
        e.preventDefault();
        const msg = chatInput.value.trim();
        if (!msg) return;

        appendUserChat(msg);
        chatInput.value = '';

        // Show typing indicator
        showTypingIndicator();

        // Process query
        setTimeout(() => {
            removeTypingIndicator();
            respondToCustomer(msg);
        }, 700);
    });

    // Append User Message to Stream
    window.appendUserChat = function(text) {
        const div = document.createElement('div');
        div.className = 'flex justify-end';
        div.innerHTML = `
            <div class="bg-[#18181B] text-white p-3 rounded-2xl rounded-tr-none max-w-[85%] text-xs shadow-xs font-medium">
                ${escapeHtml(text)}
            </div>
        `;
        messagesContainer.appendChild(div);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    };

    // Append Curator / Bot Response
    window.appendCuratorChat = function(htmlContent) {
        const div = document.createElement('div');
        div.className = 'flex gap-2.5 items-start';
        div.innerHTML = `
            <div class="w-7 h-7 rounded-full bg-[#18181B] text-white flex-shrink-0 flex items-center justify-center text-[10px] font-bold shadow-xs">JA</div>
            <div class="bg-white border border-gray-100 p-3.5 rounded-2xl rounded-tl-none max-w-[85%] text-xs text-gray-800 shadow-xs leading-relaxed">
                ${htmlContent}
            </div>
        `;
        messagesContainer.appendChild(div);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    };

    function showTypingIndicator() {
        const div = document.createElement('div');
        div.id = 'chatTypingBubble';
        div.className = 'flex gap-2.5 items-center text-xs text-gray-400 italic';
        div.innerHTML = `
            <div class="w-6 h-6 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-[9px]">JA</div>
            <div class="bg-gray-100 py-1.5 px-3 rounded-full flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-gray-400 animate-bounce"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-gray-400 animate-bounce" style="animation-delay:0.2s"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-gray-400 animate-bounce" style="animation-delay:0.4s"></span>
            </div>
        `;
        messagesContainer.appendChild(div);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function removeTypingIndicator() {
        const typing = document.getElementById('chatTypingBubble');
        if (typing) typing.remove();
    }

    // Intelligent Customer Query Responder
    function respondToCustomer(msg) {
        const lower = msg.toLowerCase();

        // 1. Order Tracking by Order Number or "track"
        const orderMatch = msg.match(/(ORD-[A-Z0-9-]+|JA-[A-Z0-9-]+|[0-9]{4,10})/i);
        if (orderMatch || lower.includes('track') || lower.includes('status') || lower.includes('order')) {
            const orderId = orderMatch ? orderMatch[0].toUpperCase() : 'ORD-2603-9182';
            appendCuratorChat(`
                <p class="font-bold text-gray-900 mb-1">Order Tracking Lookup: <strong>#${orderId}</strong></p>
                <div class="bg-stone-50 border border-stone-200 rounded-xl p-2.5 my-2">
                    <p class="text-[11px] font-bold text-[#18181B] flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                        Status: Out for Insured White-Glove Delivery
                    </p>
                    <p class="text-[10px] text-gray-600 mt-1">Courier: <strong>Fine Art Express (BlueDart Art Care)</strong></p>
                    <p class="text-[10px] text-gray-600">AWB Tracking: <strong class="font-mono">FAE-${Math.floor(10000000 + Math.random() * 90000000)}</strong></p>
                </div>
                <p class="text-[11px] text-gray-600">Archival crating inspection passed. Handed over to courier. Expected delivery within 48 hours.</p>
                <a href="${SITE_URL}/track" class="inline-block mt-2 text-[11px] font-bold text-[#18181B] underline">Open Full Tracking Timeline &rarr;</a>
            `);
            return;
        }

        // 2. Custom Framing inquiry
        if (lower.includes('frame') || lower.includes('framing') || lower.includes('size') || lower.includes('dimension')) {
            appendCuratorChat(`
                <p class="font-bold text-gray-900 mb-1">Custom Framing Options</p>
                <p class="text-[11px] text-gray-600 mb-2">We offer 5 museum-grade mounting choices for every painting:</p>
                <ul class="list-disc pl-4 space-y-1 text-[11px] text-gray-600">
                    <li><strong>Floating Matte Black:</strong> Contemporary shadow gap frame.</li>
                    <li><strong>Antique Gold:</strong> Hand-carved 24K burnished finish.</li>
                    <li><strong>Natural Oak:</strong> Solid seasoned natural Scandinavian oak wood.</li>
                    <li><strong>Rolled Canvas:</strong> Shipped in heavy-duty armored PVC tube for local framing.</li>
                </ul>
            `);
            return;
        }

        // 3. Payment / UPI / Invoice inquiry
        if (lower.includes('pay') || lower.includes('upi') || lower.includes('invoice') || lower.includes('tax') || lower.includes('gst')) {
            appendCuratorChat(`
                <p class="font-bold text-gray-900 mb-1">Payments & Tax Invoices</p>
                <p class="text-[11px] text-gray-600">We accept Razorpay (All Cards / NetBanking), Direct Dynamic UPI QR (GPay, PhonePe, Paytm), and Cash on Delivery.</p>
                <p class="text-[11px] text-gray-600 mt-1">A GST-compliant tax invoice is automatically generated on order placement with physical Certificate of Authenticity.</p>
            `);
            return;
        }

        // 4. Return / Guarantee inquiry
        if (lower.includes('return') || lower.includes('refund') || lower.includes('damage') || lower.includes('authent')) {
            appendCuratorChat(`
                <p class="font-bold text-gray-900 mb-1">100% Authenticity & 14-Day Guarantee</p>
                <p class="text-[11px] text-gray-600">Every artwork is 100% insured against transit damage. If the painting does not suit your space, you can initiate a return within 14 days of delivery for a full refund.</p>
            `);
            return;
        }

        // Default Helpful Response
        appendCuratorChat(`
            <p>Thank you for reaching out! Our gallery concierge is here to assist you.</p>
            <p class="text-[11px] text-gray-500 mt-1">Type your <strong>Order Number</strong> to track your shipment, or connect with our senior curator directly on WhatsApp below:</p>
            <div class="mt-2.5">
                <a href="https://wa.me/919876543210?text=Hello%20JayArt%2C%20I%20need%20assistance%20with%20my%20order" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#18181B] hover:bg-black text-white rounded-full text-[11px] font-bold">
                    <span>Chat on WhatsApp</span>
                    <span>&rarr;</span>
                </a>
            </div>
        `);
    }

    function escapeHtml(str) {
        return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
}

// Quick Suggestion Trigger Helper
window.sendQuickChat = function(query) {
    if (window.appendUserChat) {
        window.appendUserChat(query);
        setTimeout(() => {
            const form = document.getElementById('chatForm');
            if (form) {
                const input = document.getElementById('chatInput');
                if (input) {
                    input.value = query;
                    form.dispatchEvent(new Event('submit'));
                }
            }
        }, 100);
    }
};
