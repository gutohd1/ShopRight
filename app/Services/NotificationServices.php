<?php

namespace App\Services;

use Predis\Client;

class NotificationServices
{
    const NAME = 'notifications';

    protected Client $redis;

    public function __construct()
    {
        global $redis;
        $this->redis = $redis;
    }

    public function sendLowStockAlert(string $productId): void
    {
        $notifications = [];
        if ($this->redis->exists(self::NAME)) {
            $notifications = (array)json_decode($this->redis->get(self::NAME));
        }
        $message = 'Product id: '.$productId.' stock is bellow '.OrderService::STOCK_THRESHOLD;
        $notifications[$productId] = $message;
        $this->redis->set(self::NAME, json_encode($notifications, true));
        $_SESSION[self::NAME][$productId] = $message;
    }

    public function getNotifications(): array
    {
        $notifications = [];
        if ($this->redis->exists(self::NAME)) {
            $notifications = (array)json_decode($this->redis->get(self::NAME));
        } elseif (isset($_SESSION[self::NAME])) {
            $notifications = $_SESSION[self::NAME];
        }
        return $notifications;
    }

}
