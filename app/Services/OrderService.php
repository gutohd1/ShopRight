<?php

namespace App\Services;

use App\Models\Orders;
use App\Models\Products;

class OrderService
{
    CONST STOCK_THRESHOLD = 5;

    private Products $products;

    private array $error = [];

    private array $alert = [];

    private array $update = [];

    public function __construct(Products $products)
    {
        $this->products = $products;
    }

    public function processOrder(array $products): bool
    {
        if (count($products) > 0) {
            $total = 0;
            foreach ($products as $key => $product) {
                $product = array_intersect_key($product, array_flip(Orders::FIELDS));
                if (isset($product[Orders::PRODUCT_ID]) &&
                    isset($product[Orders::QUANTITY]) &&
                    isset($product[Orders::PRICE])) {
                    if (!$this->validateOrder($product[Orders::PRODUCT_ID], $product[Orders::QUANTITY])) {
                        $this->error[] = $product[Orders::PRODUCT_ID];
                    }

                    $total += $product[Orders::PRICE];
                    $products[$key] = $product;
                }
            }

            if (count($this->error) > 0) {
                return false;
            }

            $order = [
                'products' => $products,
                'total' => $total,
                'time' => time()
            ];
            if ($this->products->update($this->update)) {
                $orders = new Orders();
                if ($orders->create($order) !== false) {
                    if (count($this->alert) > 0) {
                        $notificationsService = new NotificationServices();
                        foreach($this->alert as $productId) {
                            $notificationsService->sendLowStockAlert($productId);
                        }
                    }
                    return true;
                }
            }
        }

        return false;
    }

    protected function validateOrder($productId, $quantity): bool
    {
        $product = $this->products->getProductById($productId);
        if ($product) {
            if ($quantity <= $product->stock) {
                $newQuantity = $product->stock-$quantity;
                if ($newQuantity < self::STOCK_THRESHOLD) {
                    $this->alert[] = $productId;
                }

                $this->update[] = ['id' => $productId, 'stock' => $newQuantity];
                return true;
            }
        }
        return false;
    }

    public function getErrors(): array
    {
        return $this->error;
    }
}
