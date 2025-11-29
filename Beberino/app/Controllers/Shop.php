<?php namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\TypeModel;
use App\Models\ReviewModel;
use App\Models\PurchaseModel;

class Shop extends BaseController
{
    public function __construct()
    {
        helper(['text', 'form', 'url', 'log']); // include the log helper
    }

    // 🏠 Homepage
    public function index()
    {
        $productModel = new ProductModel();
        $typeModel = new TypeModel();
        $purchaseModel = new PurchaseModel();

        $products = $productModel->orderBy('created_at', 'DESC')->findAll();

        foreach ($products as &$p) {
            $soldData = $purchaseModel->selectSum('quantity')
                ->where('product_id', $p['id'])
                ->first();
            $p['sold'] = $soldData['quantity'] ?? 0;
        }

        $data = [
            'title'    => 'Uling Shop',
            'products' => $products,
            'types'    => $typeModel->orderBy('name', 'ASC')->findAll(),
        ];

        return view('shop/index', $data);
    }

    // 📦 Product Details
    public function detail($id)
    {
        $productModel = new ProductModel();
        $typeModel    = new TypeModel();
        $reviewModel  = new ReviewModel();

        $product = $productModel->find($id);
        if (!$product) {
            return redirect()->to('/')->with('error', 'Product not found.');
        }

        $reviews = $reviewModel->where('product_id', $id)->orderBy('created_at', 'DESC')->findAll();
        $average = $reviewModel->selectAvg('rating')->where('product_id', $id)->first();
        $averageRating = round($average['rating'] ?? 0, 1);

        $data = [
            'title'          => $product['name'],
            'product'        => $product,
            'types'          => $typeModel->orderBy('name', 'ASC')->findAll(),
            'reviews'        => $reviews,
            'average_rating' => $averageRating,
            'review_count'   => count($reviews),
        ];

        return view('shop/detail', $data);
    }

    // 🛒 Buy Function
    public function buy($id = null)
    {
        $purchaseModel = new PurchaseModel();
        $productModel  = new ProductModel();

        $product_id = $id ?? $this->request->getPost('product_id');
        $quantity   = (int) $this->request->getPost('quantity');
        $customer   = $this->request->getPost('customer_name');
        $address    = $this->request->getPost('address');
        $payment    = $this->request->getPost('payment_method');

        $product = $productModel->find($product_id);
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        if ($quantity <= 0) {
            return redirect()->back()->with('error', 'Invalid quantity.');
        }

        $total = $product['price'] * $quantity;
        $receipt_no = 'REC-' . strtoupper(bin2hex(random_bytes(4)));

        $purchaseData = [
            'receipt_no'     => $receipt_no,
            'product_id'     => $product_id,
            'customer_name'  => $customer,
            'address'        => $address,
            'quantity'       => $quantity,
            'total'          => $total,
            'payment_method' => $payment,
            'created_at'     => date('Y-m-d H:i:s')
        ];

        $purchaseModel->insert($purchaseData);

        // Log activity
        log_activity(
            session()->get('user_id'),
            session()->get('user_name') ?? $customer,
            "Purchased {$quantity} x {$product['name']} (Receipt: {$receipt_no})"
        );

        return view('shop/receipt', [
            'product'  => $product,
            'receipt'  => $purchaseData,
            'is_modal' => true
        ]);
    }

    // 🗂 Products by Category
    public function category($type)
    {
        $productModel = new ProductModel();
        $typeModel = new TypeModel();
        $purchaseModel = new PurchaseModel();

        $decodedType = urldecode($type);
        $products = $productModel->where('type', $decodedType)->findAll();

        foreach ($products as &$p) {
            $soldData = $purchaseModel->selectSum('quantity')->where('product_id', $p['id'])->first();
            $p['sold'] = $soldData['quantity'] ?? 0;
        }

        $data = [
            'title' => ucfirst($decodedType) . ' Products',
            'products' => $products,
            'types' => $typeModel->orderBy('name', 'ASC')->findAll(),
            'category' => $decodedType
        ];

        return view('shop/index', $data);
    }

    // ✍️ Add Review
    public function review($id)
    {
        $reviewModel  = new ReviewModel();
        $productModel = new ProductModel();

        $product = $productModel->find($id);
        if (!$product) {
            return redirect()->to('/')->with('error', 'Product not found.');
        }

        $imageFile = $this->request->getFile('image');
        $imageName = null;

        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $imageName = $imageFile->getRandomName();
            $imageFile->move('uploads/reviews', $imageName);
        }

        $data = [
            'product_id'    => $id,
            'customer_name' => $this->request->getPost('customer_name'),
            'rating'        => $this->request->getPost('rating'),
            'feedback'      => $this->request->getPost('feedback'),
            'image'         => $imageName,
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        if (empty($data['customer_name']) || empty($data['rating']) || empty($data['feedback'])) {
            return redirect()->to("/shop/detail/$id")->with('error', 'Please fill out all required fields.');
        }

        $reviewModel->insert($data);

        // Log activity
        log_activity(
            session()->get('user_id'),
            session()->get('user_name') ?? $data['customer_name'],
            "Added review for product {$product['name']} (Rating: {$data['rating']})"
        );

        return redirect()->to("/product/$id")->with('success', 'Thank you for your feedback!');
    }
}
