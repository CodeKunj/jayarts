# JayArt — Complete cPanel Deployment & Administration Guide

This document outlines the step-by-step procedure to deploy the **JayArt** fine art e-commerce platform onto any standard **cPanel Shared or Dedicated Hosting** environment.

---

## 1. Architecture & Directory Overview

JayArt is engineered to sit directly in your cPanel `public_html/` folder without needing subfolder redirects or virtual host modifications.

```text
public_html/                      <-- Your cPanel Web Root
├── index.php                     <-- Public Front Controller (Router Entrypoint)
├── .htaccess                     <-- Root Rewrite Engine, HTTPS Enforcer & Gzip
├── config/
│   ├── config.php                <-- App paths, base URLs, session initialization
│   ├── database.php              <-- MySQL PDO Connection (Host, User, Pass, DB)
│   └── .htaccess                 <-- Blocks direct browser access (Deny from all)
├── core/
│   ├── App.php                   <-- PSR-4 Class Autoloader
│   ├── Router.php                <-- Clean URL Dynamic Router
│   ├── Model.php                 <-- PDO Base Model with Prepared Statements
│   ├── Controller.php            <-- Template Engine & Output Buffering
│   ├── Auth.php                  <-- User & Admin Session Management
│   ├── Validator.php             <-- CSRF Tokens, XSS Filtering, Slugs
│   ├── SEO.php                   <-- Meta Tags, GA4, GSC, Meta Pixel, Schema JSON-LD
│   ├── Mailer.php                <-- Socket SMTP Client & Luxury Email Templates
│   └── .htaccess                 <-- Blocks direct browser access
├── app/
│   ├── controllers/              <-- Storefront Controllers (Home, Shop, Cart, Checkout, Profile, Blog, Tracking...)
│   ├── models/                   <-- 14 Active Database Models
│   ├── views/                    <-- Mobile-First Master Views & Hallmark Atelier Layouts
│   └── .htaccess                 <-- Blocks direct browser access
├── admin/
│   ├── index.php                 <-- Dedicated Admin Front Controller (/admin)
│   ├── controllers/              <-- 14 Full Admin Controllers
│   └── views/                    <-- Admin Views (SEO, Delivery, Inventory, Payments, Mail, Tickets, Ads, Settings)
├── assets/
│   ├── css/                      <-- tokens.css, mobile.css, style.css, admin.css
│   ├── js/                       <-- app.js, cart.js, chatbox.js, admin.js
│   └── images/                   <-- Logos, banners, trust badges
├── uploads/
│   ├── products/                 <-- High-resolution painting photos & framing previews
│   ├── blogs/                    <-- Art story cover images
│   ├── receipts/                 <-- UPI / Bank transfer transaction screenshots
│   ├── ads/                      <-- Custom curated exhibition posters
│   └── .htaccess                 <-- CRITICAL: Disables PHP script execution in uploads
└── database/
    ├── schema.sql                <-- 24 Relational Tables with Indexes & Constraints
    ├── seed.sql                  <-- Initial Categories, Sample Artworks, Admin, Payment Gateways & Ads
    └── migrate.php               <-- One-Click Automated Database Installer
```

---

## 2. Step-by-Step cPanel Installation

### Step A: Create MySQL Database & User in cPanel
1. Log in to your **cPanel Dashboard**.
2. Under the **Databases** section, click on **MySQL® Database Wizard**.
3. **Step 1: Create A Database**:
   - Enter a name (e.g., `jayart_db`).
   - Click **Next Step**.
4. **Step 2: Create Database Users**:
   - Enter a username (e.g., `jayart_user`).
   - Use the **Password Generator** to create a strong password. Copy this password to a secure place.
   - Click **Create User**.
5. **Step 3: Add User to the Database**:
   - Check the box **ALL PRIVILEGES**.
   - Click **Make Changes**.

---

### Step B: Import Database Tables & Seed Data

You have **two options** to install the database:

#### Option 1 (Recommended — 1-Click Browser Installer):
1. Configure your database credentials in `config/database.php` (as detailed in Step C).
2. Open your browser and navigate to:
   ```text
   https://yourdomain.com/database/migrate.php
   ```
3. The script will automatically create all 24 tables and insert all default seed records (products, categories, gateways, SEO tags, ads, admin account).
4. After success, delete or protect `database/migrate.php`.

#### Option 2 (Via phpMyAdmin):
1. In cPanel, click **phpMyAdmin**.
2. Select your newly created database from the left sidebar.
3. Click the **Import** tab on the top menu.
4. Click **Choose File** and select `database/schema.sql` from your computer. Click **Go** (bottom).
5. Once imported, click the **Import** tab again.
6. Select `database/seed.sql` and click **Go**.

---

### Step C: Configure Environment (.env)

JayArt uses a secure, production-grade `.env` engine. Credentials and secrets are never committed to GitHub.

1. In cPanel **File Manager** (or via SSH terminal), in your `public_html/` folder:
2. Copy the template `.env.example` to `.env`:
   ```bash
   cp .env.example .env
   ```
3. Edit the `.env` file and input your production details:
   ```ini
   APP_NAME="JayArt"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com

   # cPanel MySQL Database credentials
   DB_HOST=localhost
   DB_PORT=3306
   DB_NAME=cpaneluser_jayart_db
   DB_USER=cpaneluser_jayart_user
   DB_PASS=YourStrongPasswordHere
   DB_CHARSET=utf8mb4

   # Payment Keys (Razorpay & UPI)
   RAZORPAY_KEY_ID=rzp_live_XXXXXXXXXXXXXX
   RAZORPAY_KEY_SECRET=YourRazorpaySecret
   UPI_ID=yourgallery@upi

   # Webmail / SMTP
   SMTP_HOST=mail.yourdomain.com
   SMTP_PORT=465
   SMTP_USER=notifications@yourdomain.com
   SMTP_PASS=YourEmailPassword
   ```
4. Save the file. Ensure permissions for `.env` are set to `0600` or `0644`.

---

### Step D: Deployment via GitHub & cPanel Git Version Control

Because your project is managed via GitHub at `https://github.com/CodeKunj/jayarts.git`, you can automate deployments in cPanel:

#### Option 1 (cPanel Git Version Control — Recommended):
1. In cPanel, click **Git™ Version Control** under the *Files* section.
2. Click **Create**.
3. Set **Clone URL**: `https://github.com/CodeKunj/jayarts.git`
4. Set **Repository Path**: `/home/cpaneluser/public_html` (or your subdomain folder).
5. Set **Repository Name**: `jayarts`
6. Click **Create**.
7. In the cPanel Git interface, whenever you push changes from your local computer to GitHub (`git push origin main`), simply click **Update from Remote** (or **Pull**) in cPanel to deploy instantly!

#### Option 2 (Manual ZIP via cPanel File Manager):
1. In cPanel **File Manager**, enter the `public_html/` folder.
2. Upload your project `.zip` and click **Extract**.
3. Ensure folders have `0755` permissions and files have `0644` permissions.
4. Ensure `storage/cache/` and `uploads/` folders are writable (`0755` or `0775`).

---

### Step E: Enable Free SSL (HTTPS)

1. In cPanel, navigate to **SSL/TLS Status**.
2. Click **Run AutoSSL** to generate a free Let's Encrypt / Sectigo certificate.
3. The root `.htaccess` already includes automatic HTTP-to-HTTPS 301 redirection rules.

---

## 3. Initial Admin Login

- **Admin URL:** `https://yourdomain.com/admin` (or `https://yourdomain.com/admin/login`)
- **Default Email:** `admin@jayart.com`
- **Default Password:** `admin123`

> [!IMPORTANT]
> Immediately upon your first login, navigate to **Admin > Users/Profile** to update your administrator email and password.

---

## 4. Configuring Key Store Modules

### 1. Payment Gateways & Direct UPI (`/admin/payments`)
- **Razorpay Integration:**
  - Set Mode to **Test** or **Live**.
  - Enter your **Key ID** (`rzp_live_...` or `rzp_test_...`) and **Key Secret**.
- **Direct Dynamic UPI QR Code:**
  - Enter your Gallery UPI VPA (e.g., `yourgallery@okhdfcbank` or `yourbusiness@upi`).
  - Set your Merchant Name (e.g., `JayArt Gallery`).
  - Checkout automatically generates a live NPCI UPI QR code dynamically encoded with the exact order amount and order reference, plus instant one-tap buttons for Google Pay, PhonePe, and Paytm on mobile devices.
  - Customers can submit their 12-digit UTR bank reference and upload receipt screenshots.
- **Cash on Delivery (COD):**
  - Enable/disable and set optional handling surcharge.
- **Direct Bank Transfer:**
  - Enter your Bank Name, Account Holder, Account Number, and IFSC code for high-value collector transfers.

---

### 2. SEO & Webmaster Integrations (`/admin/seo`)
- **Global Meta Title & Description:** Default search engine appearance for your homepage and gallery.
- **Google Analytics 4 (GA4):** Enter your Measurement ID (e.g., `G-XXXXXXXXXX`).
- **Google Search Console (GSC):** Paste your verification meta tag content.
- **Meta Pixel (Facebook Pixel):** Enter your numeric Pixel ID.
- **Bing Webmaster Tools:** Enter your Bing authentication key.
- **Robots.txt Editor:** Easily add custom indexing instructions.
- **Dynamic XML Sitemap:** Automatically generated and accessible live at `https://yourdomain.com/sitemap.xml`.

---

### 3. Individual Product & Blog SEO
- **Products:** When adding or editing any painting in **Admin > Paintings**, use the **SEO & Search Preview** tab to set a unique meta title, custom description, focus keywords, and inspect the **Live Google Mobile Search Snippet Preview**.
- **Art Stories (Blog):** Each article in **Admin > Art Stories** features its own dedicated SEO panel with Article Schema JSON-LD.

---

### 4. Poster-Style Ad Units & Monetization (`/admin/ads`)
- **Aspect-Locked Art Posters (CLS = 0):**
  - **Slot 1 (Header Billboard):** Displays below top navigation for special salon announcements.
  - **Slot 2 (In-Grid Poster):** Seamlessly embedded in the painting catalog grid (Slot 3 or 7) with `aspect-[3/4]` matching painting cards.
  - **Slot 3 (In-Article Blog):** Embedded between article paragraphs.
  - **Slot 4 (Footer Banner):** Sits above the footer.
- **Google AdSense:** Switch any slot's type from "Custom Poster" to "Google AdSense Code" and paste responsive `<ins class="adsbygoogle">` snippets.

---

### 5. Delivery Tracking System (`/admin/delivery`)
- Track artwork shipments by Order Number.
- Assign courier partner (BlueDart Apex, Fine Art Express, DHL Express, FedEx Art Secure).
- Add live shipment milestones (e.g., *Archival Crating Completed*, *Handed to Art Courier*, *Departed Hub*, *Out for Insured White-Glove Delivery*).
- Patrons can track real-time milestones at `/track` without logging in.

---

### 6. SMTP Mail Configuration (`/admin/mail`)
- Enter your cPanel Webmail or external SMTP details:
  - **Host:** `mail.yourdomain.com` (or `smtp.gmail.com` / `smtp.sendgrid.net`)
  - **Port:** `465` (SSL) or `587` (TLS)
  - **Username & Password:** Your email account credentials.
- Test connection instantly with the built-in **Send Live Test Email** tool.

---

### 7. Support Tickets & Floating Chatbox (`/admin/tickets`)
- Integrated floating customer care chatbox appears on all storefront pages with instant answers and direct WhatsApp connection.
- Patrons can open tickets directly from their user dashboard (`/profile/tickets`).
- Curators can respond from the Admin panel with custom replies or one-click canned response templates.

---

## 5. Security & Maintenance Checklist

1. **Uploads Protection:** Verify `uploads/.htaccess` contains `php_flag engine off` to prevent malicious executable files.
2. **Backups:** Set up automated cPanel weekly backups for database and `public_html/`.
3. **PHP Version:** Recommended PHP 8.1, 8.2, 8.3, or 8.4 with `pdo_mysql`, `mbstring`, `openssl`, and `gd` enabled.

