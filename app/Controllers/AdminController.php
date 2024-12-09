<?php

namespace App\Controllers;

use App\Models\Orders;
use App\Services\NotificationServices;
use App\View;
use App\Controllers\Base\Controller;

class AdminController extends Controller
{
    public function index(): View
    {
        $notificationService = new NotificationServices();
        $notifications = $notificationService->getNotifications();

        $orders = new Orders();
        $allOrders = $orders->getAll();
        return View::render('admin.index', ['notifications' => $notifications, 'orders' => $allOrders]);
    }
}
