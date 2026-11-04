<?php
/**
 * JayArt Advanced SMTP & Transactional Mail System
 * Supports SMTP (SSL/TLS), native PHP mail fallback, and responsive art gallery templates
 */

declare(strict_types=1);

class Mailer {
    /**
     * Send Transactional Email
     */
    public static function send(string $toEmail, string $subject, string $htmlBody, ?string $toName = null): bool {
        $db = Database::connect();
        $smtp = null;
        if ($db !== null) {
            try {
                $stmt = $db->query("SELECT * FROM `smtp_settings` WHERE `id` = 1 LIMIT 1");
                $smtp = $stmt ? $stmt->fetch() : null;
            } catch (Throwable $e) {
                $smtp = null;
            }
        }

        $fromEmail = !empty($smtp['from_email']) ? $smtp['from_email'] : 'support@' . ($_SERVER['HTTP_HOST'] ?? 'jayart.com');
        $fromName  = !empty($smtp['from_name']) ? $smtp['from_name'] : APP_NAME;

        // If SMTP is enabled and password is configured, attempt socket SMTP send
        if (!empty($smtp['is_enabled']) && !empty($smtp['password']) && !empty($smtp['host'])) {
            $sent = self::sendSocketSMTP($smtp, $toEmail, $subject, $htmlBody, $fromEmail, $fromName);
            if ($sent) {
                return true;
            }
        }

        // Native PHP mail() fallback
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: =?UTF-8?B?" . base64_encode($fromName) . "?= <{$fromEmail}>\r\n";
        $headers .= "Reply-To: {$fromEmail}\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        $wrappedBody = self::wrapTemplate($subject, $htmlBody);
        return @mail($toEmail, '=?UTF-8?B?' . base64_encode($subject) . '?=', $wrappedBody, $headers);
    }

    /**
     * Socket-based SMTP Client (Zero External Dependency)
     */
    private static function sendSocketSMTP(array $smtp, string $to, string $subject, string $body, string $fromEmail, string $fromName): bool {
        $timeout = 10;
        $host = $smtp['host'];
        $port = (int)$smtp['port'];
        $enc  = strtolower($smtp['encryption'] ?? 'ssl');

        $prefix = ($enc === 'ssl') ? 'ssl://' : '';
        $socket = @fsockopen($prefix . $host, $port, $errno, $errstr, $timeout);

        if (!$socket) {
            error_log("SMTP Connection Error: {$errstr} ({$errno})");
            return false;
        }

        // Helper to read server response
        $read = function() use ($socket) {
            $response = '';
            while ($str = fgets($socket, 515)) {
                $response .= $str;
                if (substr($str, 3, 1) == ' ') break;
            }
            return $response;
        };

        $read(); // Initial greeting

        fwrite($socket, "EHLO " . ($_SERVER['SERVER_NAME'] ?? 'localhost') . "\r\n");
        $read();

        // STARTTLS if TLS chosen on port 587
        if ($enc === 'tls') {
            fwrite($socket, "STARTTLS\r\n");
            $read();
            stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            fwrite($socket, "EHLO " . ($_SERVER['SERVER_NAME'] ?? 'localhost') . "\r\n");
            $read();
        }

        // Authenticate
        fwrite($socket, "AUTH LOGIN\r\n");
        $read();
        fwrite($socket, base64_encode($smtp['username']) . "\r\n");
        $read();
        fwrite($socket, base64_encode($smtp['password']) . "\r\n");
        $authRes = $read();

        if (!str_starts_with($authRes, '235')) {
            fclose($socket);
            error_log("SMTP Auth Failed: {$authRes}");
            return false;
        }

        fwrite($socket, "MAIL FROM: <{$fromEmail}>\r\n");
        $read();
        fwrite($socket, "RCPT TO: <{$to}>\r\n");
        $read();
        fwrite($socket, "DATA\r\n");
        $read();

        $message  = "From: =?UTF-8?B?" . base64_encode($fromName) . "?= <{$fromEmail}>\r\n";
        $message .= "To: <{$to}>\r\n";
        $message .= "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=\r\n";
        $message .= "MIME-Version: 1.0\r\n";
        $message .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
        $message .= self::wrapTemplate($subject, $body) . "\r\n.\r\n";

        fwrite($socket, $message);
        $read();

        fwrite($socket, "QUIT\r\n");
        fclose($socket);

        return true;
    }

    /**
     * Wrap Email Body with Responsive Hallmark Atelier Master Template
     */
    public static function wrapTemplate(string $title, string $innerHtml): string {
        $siteUrl = SITE_URL;
        $appName = APP_NAME;
        $year = date('Y');

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$title}</title>
<style>
  body { margin: 0; padding: 0; background-color: #FAF8F5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1A1918; }
  .email-container { max-width: 600px; margin: 30px auto; background: #FFFFFF; border: 1px solid #E8E4DF; border-radius: 12px; overflow: hidden; }
  .email-header { background: #1B4332; padding: 30px 24px; text-align: center; }
  .email-header h1 { margin: 0; color: #FFFFFF; font-size: 26px; font-family: Georgia, serif; letter-spacing: 1px; }
  .email-header p { margin: 6px 0 0; color: #D4A574; font-size: 13px; text-transform: uppercase; letter-spacing: 1.5px; }
  .email-body { padding: 36px 30px; font-size: 15px; line-height: 1.6; color: #2C2B29; }
  .email-footer { background: #F6F4F0; padding: 24px; text-align: center; font-size: 12px; color: #8A8580; border-top: 1px solid #E8E4DF; }
  .btn { display: inline-block; padding: 12px 28px; background: #1B4332; color: #FFFFFF !important; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 18px 0; }
  .order-table { width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 14px; }
  .order-table th { text-align: left; padding: 10px; border-bottom: 2px solid #E8E4DF; color: #686461; }
  .order-table td { padding: 12px 10px; border-bottom: 1px solid #F0ECE6; }
</style>
</head>
<body>
<div class="email-container">
  <div class="email-header">
    <h1>{$appName}</h1>
    <p>Fine Art & Original Paintings</p>
  </div>
  <div class="email-body">
    {$innerHtml}
  </div>
  <div class="email-footer">
    <p>&copy; {$year} {$appName} Gallery. All rights reserved.</p>
    <p>Insured Museum-Grade Packaging & Worldwide Delivery</p>
  </div>
</div>
</body>
</html>
HTML;
    }
}

