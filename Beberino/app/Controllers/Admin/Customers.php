<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Customers extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // Show all customers
    public function index()
    {
        if (!session()->get('logged_in') || !session()->get('is_admin')) {
            return redirect()->to('/auth/admin_login');
        }

        $customers = $this->userModel->where('is_admin', 0)->findAll();

        return view('admin/customers/index', [
            'customers' => $customers,
            'title' => 'Customers'
        ]);
    }

    // Show edit form
    public function edit($id)
    {
        if (!session()->get('logged_in') || !session()->get('is_admin')) {
            return redirect()->to('/auth/admin_login');
        }

        $customer = $this->userModel->find($id);

        if (!$customer || $customer['is_admin'] == 1) {
            return redirect()->to('/admin/customers')->with('error', 'Customer not found.');
        }

        return view('admin/customers/edit', [
            'customer' => $customer,
            'title' => 'Edit Customer'
        ]);
    }

    // Update customer
    public function update($id)
{
    if (!session()->get('logged_in') || !session()->get('is_admin')) {
        return redirect()->to('/auth/admin_login');
    }

    $data = [
        'fullname' => $this->request->getPost('fullname'),
        'email'    => $this->request->getPost('email'),
        'is_admin' => $this->request->getPost('is_admin'),
    ];

    $this->userModel->update($id, $data);

    return redirect()->to('/admin/customers')->with('success', 'Customer updated successfully.');
}


    // Delete customer
    public function delete($id)
    {
        if (!session()->get('logged_in') || !session()->get('is_admin')) {
            return redirect()->to('/auth/admin_login');
        }

        $customer = $this->userModel->find($id);

        if ($customer && $customer['is_admin'] == 0) {
            $this->userModel->delete($id);
            return redirect()->to('/admin/customers')->with('success', 'Customer deleted successfully.');
        }

        return redirect()->to('/admin/customers')->with('error', 'Customer not found or cannot delete admin.');
    }
}
