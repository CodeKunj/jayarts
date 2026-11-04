<?php
/**
 * JayArt Support Ticket Model
 */

declare(strict_types=1);

class Ticket extends Model {
    protected string $table = 'support_tickets';

    public static function generateTicketNumber(): string {
        return 'TCK-' . date('ym') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 4));
    }

    public function createTicket(array $data): int {
        $number = self::generateTicketNumber();
        $sql = "INSERT INTO `support_tickets` (`ticket_number`, `user_id`, `customer_name`, `customer_email`, `subject`, `category`, `priority`, `status`, `order_id`) 
                VALUES (:num, :uid, :name, :email, :sub, :cat, :prio, 'open', :oid)";
        
        $this->query($sql, [
            'num'   => $number,
            'uid'   => $data['user_id'] ?? null,
            'name'  => $data['customer_name'],
            'email' => $data['customer_email'],
            'sub'   => $data['subject'],
            'cat'   => $data['category'] ?? 'order_inquiry',
            'prio'  => $data['priority'] ?? 'medium',
            'oid'   => !empty($data['order_id']) ? (int)$data['order_id'] : null,
        ]);

        $ticketId = $this->lastInsertId();

        // Insert first message
        if (!empty($data['message'])) {
            $this->addMessage($ticketId, 'user', $data['customer_name'], $data['message'], $data['attachment_path'] ?? null);
        }

        return $ticketId;
    }

    public function addMessage(int $ticketId, string $senderType, string $senderName, string $message, ?string $attachment = null): int {
        $sql = "INSERT INTO `ticket_messages` (`ticket_id`, `sender_type`, `sender_name`, `message`, `attachment_path`) 
                VALUES (:tid, :st, :sn, :msg, :att)";
        
        $this->query($sql, [
            'tid' => $ticketId,
            'st'  => $senderType,
            'sn'  => $senderName,
            'msg' => $message,
            'att' => $attachment,
        ]);

        // Touch ticket updated_at
        $this->query("UPDATE `support_tickets` SET `updated_at` = NOW() WHERE `id` = :id", ['id' => $ticketId]);

        return $this->lastInsertId();
    }

    public function getMessages(int $ticketId): array {
        return $this->fetchAll(
            "SELECT * FROM `ticket_messages` WHERE `ticket_id` = :id ORDER BY `created_at` ASC",
            ['id' => $ticketId]
        );
    }
}

