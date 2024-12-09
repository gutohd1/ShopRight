<?php

namespace App\Models;

use App\Models\Base\Model;

class Orders extends Model
{
    const PRODUCT_ID = 'product_id';
    const QUANTITY = 'quantity';
    const PRICE = 'price';

    const FIELDS = [
        self::PRODUCT_ID,
        self::QUANTITY,
        self::PRICE
    ];
}
