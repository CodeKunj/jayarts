<?php
/**
 * JayArt Single Ticket Conversation Thread View
 */
?>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    
    <div class="flex items-center justify-between pb-6 border-b border-linen mb-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="font-mono text-sm font-bold text-forest">Ticket #<?= htmlspecialchars($ticket['ticket_number']) ?></span>
                <span class="text-[9px] uppercase font-bold px-2 py-0.5 rounded bg-canvas border border-linen text-muted">
                    <?= htmlspecialchars($ticket['status']) ?>
                </span>
            </div>
            <h1 class="font-serif text-xl sm:text-2xl font-bold text-ink"><?= htmlspecialchars($ticket['subject']) ?></h1>
        </div>
        <a href="<?= SITE_URL ?>/profile/tickets" class="text-xs font-semibold text-forest hover:text-gold transition-colors">
            &larr; All Tickets
        </a>
    </div>

    <!-- Messages Container -->
    <div class="space-y-4 mb-8">
        <?php foreach ($messages as $msg): 
            $isAdmin = ($msg['sender_type'] === 'admin');
        ?>
            <div class="flex gap-3 <?= $isAdmin ? 'flex-row' : 'flex-row-reverse' ?>">
                <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center text-xs font-bold text-white <?= $isAdmin ? 'bg-gold' : 'bg-forest' ?>">
                    <?= $isAdmin ? 'JA' : substr($msg['sender_name'], 0, 1) ?>
                </div>
                <div class="max-w-[85%] <?= $isAdmin ? 'bg-white border border-linen' : 'bg-forest text-white' ?> rounded-2xl p-4 shadow-sm text-xs">
                    <div class="flex items-center justify-between gap-4 mb-1 text-[10px] <?= $isAdmin ? 'text-muted' : 'text-white/70' ?>">
                        <span class="font-bold"><?= htmlspecialchars($msg['sender_name']) ?> (<?= ucfirst($msg['sender_type']) ?>)</span>
                        <span><?= date('d M, h:i A', strtotime($msg['created_at'])) ?></span>
                    </div>
                    <div class="leading-relaxed whitespace-pre-line">
                        <?= htmlspecialchars($msg['message']) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Reply Form -->
    <div class="bg-white border border-linen rounded-2xl p-5 shadow-sm">
        <h3 class="font-serif text-xs font-bold text-ink uppercase tracking-wider mb-3">Add Response</h3>
        <form action="<?= SITE_URL ?>/profile/ticket/<?= (int)$ticket['id'] ?>/reply" method="POST" class="space-y-3">
            <?= Validator::csrfField() ?>
            <textarea name="message" required rows="3" placeholder="Write your reply..." class="form-input text-xs"></textarea>
            <div class="flex justify-end">
                <button type="submit" class="btn-primary py-2 px-6 rounded-xl text-xs font-bold">
                    Send Reply &rarr;
                </button>
            </div>
        </form>
    </div>

</div>

