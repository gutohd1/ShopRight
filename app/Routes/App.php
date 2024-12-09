<?php

namespace App\Routes;

use App\Controllers\OrderController;
use App\Controllers\AdminController;
use App\Routes\Base\Routes;

class App extends Routes
{
    protected array $routes = [
        'get' => [
            '/admin' => ['name' =>'admin', 'class' => AdminController::class, 'action' => 'index'],
            '/api/session/clear' => ['name' => 'session.clear', 'class' => OrderController::class, 'action' => 'clear'],
        ],
        'post' => [
            '/api/order/create' => ['name' => 'order.create', 'class' => OrderController::class, 'action' => 'create']
        ],
    ];
}
