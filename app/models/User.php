<?php
/**
 * JayArt User Model
 */

declare(strict_types=1);

class User extends Model {
    protected string $table = 'users';

    public function findByEmail(string $email): ?array {
        return $this->findBy('email', $email);
    }

    public function createCustomer(string $name, string $email, string $password, ?string $phone = null): int {
        return $this->insert([
            'name'     => $name,
            'email'    => $email,
            'password' => Auth::hashPassword($password),
            'phone'    => $phone,
            'role'     => 'customer',
            'status'   => 'active',
        ]);
    }

    public function getAddresses(int $userId): array {
        return $this->fetchAll(
            "SELECT * FROM `addresses` WHERE `user_id` = :user_id ORDER BY `is_default` DESC, `id` DESC",
            ['user_id' => $userId]
        );
    }

    public function addAddress(int $userId, array $addressData): int {
        if (!empty($addressData['is_default'])) {
            $this->query("UPDATE `addresses` SET `is_default` = 0 WHERE `user_id` = :user_id", ['user_id' => $userId]);
        }

        $sql = "INSERT INTO `addresses` (`user_id`, `recipient_name`, `phone`, `address_line1`, `address_line2`, `city`, `state`, `postal_code`, `country`, `is_default`) 
                VALUES (:user_id, :recipient_name, :phone, :address_line1, :address_line2, :city, :state, :postal_code, :country, :is_default)";
        
        $params = [
            'user_id'        => $userId,
            'recipient_name' => $addressData['recipient_name'],
            'phone'          => $addressData['phone'],
            'address_line1'  => $addressData['address_line1'],
            'address_line2'  => $addressData['address_line2'] ?? null,
            'city'           => $addressData['city'],
            'state'          => $addressData['state'],
            'postal_code'    => $addressData['postal_code'],
            'country'        => $addressData['country'] ?? 'India',
            'is_default'     => !empty($addressData['is_default']) ? 1 : 0
        ];

        $this->query($sql, $params);
        return $this->lastInsertId();
    }
}

