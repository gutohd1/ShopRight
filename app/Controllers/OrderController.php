<?php

namespace App\Controllers;

use App\Services\OrderService;
use App\Models\Products;

class OrderController
{
    public function create(): string
    {
        $message = 'Error trying to create order';
        $data = [];
        if (isset($_POST['products'])) {
            $products = json_decode($_POST['products'], true);
            if (! is_null($products)) {
                $order = new OrderService(new Products());
                if ($order->processOrder($products)) {
                    return json_encode(['status' => 'success', 'message' => 'Order successfully created']);
                } else {
                    $message = 'Some products on your order are not available';
                    $data = $order->getErrors();
                }
            }
        }
        return json_encode(['status' => 'error', 'message' => $message, 'data' => $data]);
    }

    public function clear(): void
    {
        global $redis;
        $redis->flushall();
        $_SESSION['products'] = [];
        $_SESSION['notifications'] = [];
        var_dump('cleared');die;
    }
}
