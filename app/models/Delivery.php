<?php
/**
 * JayArt Delivery Tracking Model
 */

declare(strict_types=1);

class Delivery extends Model {
    protected string $table = 'delivery_tracking';

    /**
     * Add Milestone to Delivery
     */
    public function addMilestone(int $deliveryId, string $statusTitle, ?string $description = null, ?string $location = null): int {
        $sql = "INSERT INTO `delivery_milestones` (`delivery_id`, `status_title`, `description`, `location`, `milestone_time`) 
                VALUES (:did, :title, :desc, :loc, NOW())";
        
        $this->query($sql, [
            'did'   => $deliveryId,
            'title' => $statusTitle,
            'desc'  => $description,
            'loc'   => $location
        ]);

        // Update current status on main tracking record
        $this->query("UPDATE `delivery_tracking` SET `current_status` = :st WHERE `id` = :id", [
            'st' => $statusTitle,
            'id' => $deliveryId
        ]);

        return $this->lastInsertId();
    }
}

