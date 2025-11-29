<?php

namespace App\Controllers;

use App\Models\PurchaseModel;
use App\Models\UserModel;
use App\Models\ProductModel;
helper('log'); // make sure log_activity() is available

class Admin extends BaseController
{
    public function dashboard()
    {
        if (!session()->get('logged_in') || !session()->get('is_admin')) {
            return redirect()->to('/auth/admin_login');
        }

        $purchaseModel = new PurchaseModel();
        $userModel     = new UserModel();
        $productModel  = new ProductModel();

        // Total Revenue
        $totalRevenue = $purchaseModel->selectSum('total')->first()['total'] ?? 0;

        // Total Orders
        $totalOrders = $purchaseModel->countAllResults();

        // Total Customers
        $totalCustomers = $userModel->where('is_admin', 0)->countAllResults();

        // Total Purchases / Quantity
        $totalPurchases = $purchaseModel->selectSum('quantity')->first()['quantity'] ?? 0;

        // Recent Purchases (latest 10)
        $recentPurchases = $purchaseModel
            ->select('purchases.*, products.name as product_name')
            ->join('products', 'products.id = purchases.product_id')
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->findAll();

        // Chart Data (Revenue per day, last 7 days)
        $builder = $purchaseModel->select('DATE(created_at) as day, SUM(total) as total')
            ->groupBy('DATE(created_at)')
            ->orderBy('DATE(created_at)', 'ASC')
            ->limit(7)
            ->findAll();

        $revenueChartLabels = array_column($builder, 'day');
        $revenueChartData   = array_column($builder, 'total');

        // Log activity with network info
        log_activity(
            session()->get('user_id'),
            session()->get('user_name'),
            'Viewed Admin Dashboard',
            $_SERVER // optional extra data to capture request headers
        );

        return view('admin/dashboard', [
            'totalRevenue'       => $totalRevenue,
            'totalOrders'        => $totalOrders,
            'totalCustomers'     => $totalCustomers,
            'totalPurchases'     => $totalPurchases,
            'recentPurchases'    => $recentPurchases,
            'revenueChartLabels' => $revenueChartLabels,
            'revenueChartData'   => $revenueChartData
        ]);
    }

    public function orders()
    {
        if (!session()->get('logged_in') || !session()->get('is_admin')) {
            return redirect()->to('/auth/admin_login');
        }

        $purchaseModel = new PurchaseModel();
        $productModel = new ProductModel();

        $builder = $purchaseModel->builder();
        $builder->select('products.type, SUM(purchases.quantity) as total_sold');
        $builder->join('products', 'products.id = purchases.product_id');
        $builder->groupBy('products.type');
        $results = $builder->get()->getResultArray();

        $types = [];
        $totals = [];
        foreach ($results as $row) {
            $types[] = $row['type'];
            $totals[] = (int)$row['total_sold'];
        }

        // Log activity with network info
        log_activity(
            session()->get('user_id'),
            session()->get('user_name'),
            'Viewed Orders Overview',
            $_SERVER // optional extra data to capture request headers
        );

        return view('admin/orders/index', [
            'typesData'   => $results,
            'chartLabels' => $types,
            'chartData'   => $totals
        ]);
    }
}
