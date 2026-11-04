<?php
/**
 * JayArt Branded Fine Art Tax Invoice & Certificate of Acquisition
 * Printable & Downloadable
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Invoice #<?= htmlspecialchars($order['order_number']) ?> — JayArt Gallery</title>
<style>
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; margin: 0; padding: 40px; color: #1A1918; background: #fff; }
    .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #E8E4DF; border-radius: 12px; }
    .header { display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 24px; border-bottom: 2px solid #1B4332; margin-bottom: 24px; }
    .logo { font-family: Georgia, serif; font-size: 28px; font-weight: bold; color: #1B4332; letter-spacing: 1px; }
    .tagline { font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #C59A58; margin-top: 4px; }
    .invoice-details { text-align: right; font-size: 13px; color: #686461; }
    .invoice-number { font-family: monospace; font-size: 16px; font-weight: bold; color: #1B4332; }
    .address-grid { display: flex; justify-content: space-between; margin-bottom: 28px; font-size: 13px; line-height: 1.6; }
    .address-col { width: 48%; }
    .table { width: 100%; border-collapse: collapse; margin-bottom: 24px; font-size: 13px; }
    .table th { background: #FAF8F5; text-align: left; padding: 12px; border-bottom: 1px solid #E8E4DF; color: #686461; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; }
    .table td { padding: 14px 12px; border-bottom: 1px solid #F0ECE6; }
    .total-section { margin-left: auto; width: 300px; font-size: 13px; }
    .total-row { display: flex; justify-content: space-between; padding: 6px 0; color: #686461; }
    .grand-total { border-top: 2px solid #1B4332; padding-top: 10px; margin-top: 6px; font-size: 18px; font-weight: bold; color: #1B4332; }
    .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #E8E4DF; text-align: center; font-size: 11px; color: #96918D; }
    .print-btn { display: inline-block; padding: 10px 20px; background: #1B4332; color: #fff; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: bold; margin-bottom: 20px; cursor: pointer; border: none; }
    @media print { .print-btn { display: none; } .invoice-box { border: none; padding: 0; } }
</style>
</head>
<body>

<div style="max-width: 800px; margin: auto; text-align: right;">
    <button class="print-btn" onclick="window.print()">Print or Save as PDF</button>
</div>

<div class="invoice-box">
    
    <div class="header">
        <div>
            <div class="logo">JayArt</div>
            <div class="tagline">Fine Art Gallery &amp; Atelier</div>
            <div style="font-size: 11px; color: #686461; margin-top: 8px;">
                JayArt Private Limited • GSTIN: 27AABCJ1234F1Z5<br>
                Heritage Fine Arts District, Mumbai 400001
            </div>
        </div>
        <div class="invoice-details">
            <div class="invoice-number">TAX INVOICE #<?= htmlspecialchars($order['order_number']) ?></div>
            <div>Date: <?= date('d M Y', strtotime($order['created_at'])) ?></div>
            <div>Payment: <?= strtoupper($order['payment_method']) ?> (<?= strtoupper($order['payment_status']) ?>)</div>
        </div>
    </div>

    <div class="address-grid">
        <div class="address-col">
            <strong style="color: #1B4332; text-transform: uppercase; font-size: 11px; letter-spacing: 1px;">Billed &amp; Delivered To:</strong>
            <div style="margin-top: 6px;">
                <strong><?= htmlspecialchars($order['customer_name']) ?></strong><br>
                <?= htmlspecialchars($shipping['address_line1'] ?? '') ?>, <?= htmlspecialchars($shipping['address_line2'] ?? '') ?><br>
                <?= htmlspecialchars($shipping['city'] ?? '') ?>, <?= htmlspecialchars($shipping['state'] ?? '') ?> - <?= htmlspecialchars($shipping['postal_code'] ?? '') ?><br>
                Phone: <?= htmlspecialchars($order['customer_phone']) ?><br>
                Email: <?= htmlspecialchars($order['customer_email']) ?>
            </div>
        </div>
        <div class="address-col" style="text-align: right;">
            <strong style="color: #1B4332; text-transform: uppercase; font-size: 11px; letter-spacing: 1px;">Provenance Guarantee:</strong>
            <div style="margin-top: 6px; color: #686461;">
                Certificate of Authenticity Attached<br>
                Armored Museum Transit Packaging<br>
                Insured Consignment Delivery
            </div>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Artwork Description</th>
                <th>Artist</th>
                <th>Framing Option</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Price</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td>
                        <strong><?= htmlspecialchars($item['product_title']) ?></strong>
                        <div style="font-size: 11px; color: #888; font-family: monospace;"><?= htmlspecialchars($item['dimensions'] ?? '') ?></div>
                    </td>
                    <td><?= htmlspecialchars($item['artist_name'] ?? 'Artist') ?></td>
                    <td><?= htmlspecialchars($item['frame_chosen']) ?></td>
                    <td style="text-align: center; font-weight: bold;"><?= (int)$item['quantity'] ?></td>
                    <td style="text-align: right;"><?= Validator::formatPrice((float)$item['price']) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= Validator::formatPrice((float)$item['total_price']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-row">
            <span>Subtotal:</span>
            <span><?= Validator::formatPrice((float)$order['subtotal']) ?></span>
        </div>
        <?php if ((float)$order['discount_amount'] > 0): ?>
            <div class="total-row" style="color: #2D6A4F; font-weight: bold;">
                <span>Discount Applied:</span>
                <span>- <?= Validator::formatPrice((float)$order['discount_amount']) ?></span>
            </div>
        <?php endif; ?>
        <div class="total-row">
            <span>Insured Transit &amp; Packaging:</span>
            <span><?= ((float)$order['shipping_fee'] === 0.0) ? 'COMPLIMENTARY' : Validator::formatPrice((float)$order['shipping_fee']) ?></span>
        </div>
        <div class="total-row grand-total">
            <span>Total Payable:</span>
            <span><?= Validator::formatPrice((float)$order['total_amount']) ?></span>
        </div>
    </div>

    <div class="footer">
        <p>Thank you for patronizing authentic independent fine art. This document is a computer-generated tax invoice and proof of provenance transfer.</p>
        <p>&copy; <?= date('Y') ?> JayArt Gallery Private Limited • www.jayart.com • support@jayart.com</p>
    </div>

</div>

</body>
</html>

