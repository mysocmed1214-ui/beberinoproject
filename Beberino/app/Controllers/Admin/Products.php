<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\TypeModel;
use App\Models\ActivityLogModel;

class Products extends BaseController
{
    protected $productModel;
    protected $typeModel;
    protected $logModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->typeModel = new TypeModel();
        $this->logModel = new ActivityLogModel();
        helper('log'); // ✅ Load your log_activity() helper
    }

    public function index()
    {
        $data = [
            'title' => 'Products',
            'products' => $this->productModel->orderBy('created_at', 'DESC')->findAll(),
            'types' => $this->typeModel->orderBy('name', 'ASC')->findAll(),
        ];
        return view('admin/products/index', $data);
    }

    public function store()
    {
        $file = $this->request->getFile('image');
        $imageName = null;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $imageName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/products', $imageName);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'type' => $this->request->getPost('type'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'stock' => $this->request->getPost('stock'),
            'image' => $imageName,
        ];

        $this->productModel->insert($data);

        // ✅ Corrected log function
        log_activity(session()->get('user_id'), session()->get('username'), 'Add Product', $data);

        return redirect()->to('/admin/products')->with('success', 'Product added successfully.');
    }

    public function update($id)
    {
        $file = $this->request->getFile('image');
        $product = $this->productModel->find($id);
        $imageName = $product['image'] ?? null;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newImage = $file->getRandomName();
            $file->move(FCPATH . 'uploads/products', $newImage);

            if ($imageName && file_exists(FCPATH . 'uploads/products/' . $imageName)) {
                unlink(FCPATH . 'uploads/products/' . $imageName);
            }

            $imageName = $newImage;
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'type' => $this->request->getPost('type'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'stock' => $this->request->getPost('stock'),
            'image' => $imageName,
        ];

        $this->productModel->update($id, $data);

        // ✅ Corrected log function
        log_activity(session()->get('user_id'), session()->get('username'), 'Update Product', [
            'id' => $id,
            'data' => $data
        ]);

        return redirect()->to('/admin/products')->with('success', 'Product updated successfully.');
    }

    public function delete($id)
    {
        $product = $this->productModel->find($id);

        if ($product && !empty($product['image'])) {
            $imagePath = FCPATH . 'uploads/products/' . $product['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $this->productModel->delete($id);

        // ✅ Corrected log function
        log_activity(session()->get('user_id'), session()->get('username'), 'Delete Product', [
            'id' => $id,
            'name' => $product['name'] ?? 'Unknown'
        ]);

        return redirect()->to('/admin/products')->with('success', 'Product deleted successfully.');
    }
}
