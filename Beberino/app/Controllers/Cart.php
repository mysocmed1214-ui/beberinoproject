<?php namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\PurchaseModel;

helper(['text', 'form']);

class Cart extends BaseController
{
    public function index()
    {
        $cart = session()->get('cart') ?? [];
        return view('cart/index', ['cart' => $cart]);
    }

    public function add()
    {
        $id = $this->request->getPost('product_id');
        $qty = (int) ($this->request->getPost('quantity') ?? 1);

        $productModel = new ProductModel();
        $p = $productModel->find($id);
        if (!$p) return redirect()->back();

        $cart = session()->get('cart') ?? [];

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $qty;
        } else {
            $cart[$id] = [
                'id'       => $p['id'],
                'name'     => $p['name'],
                'price'    => $p['price'],
                'quantity' => $qty,
                'image'    => $p['image']
            ];
        }

        session()->set('cart', $cart);
        return redirect()->to('/cart');
    }

    public function update()
    {
        $items = $this->request->getPost('quantity');
        $cart  = session()->get('cart') ?? [];

        foreach ($items as $id => $qty) {
            if (isset($cart[$id])) {
                $cart[$id]['quantity'] = max(1, (int)$qty);
            }
        }

        session()->set('cart', $cart);
        return redirect()->to('/cart');
    }

    public function remove($id)
    {
        $cart = session()->get('cart') ?? [];
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->set('cart', $cart);
        }

        return redirect()->to('/cart');
    }

    public function checkout()
    {
        $cart = session()->get('cart') ?? [];
        return view('cart/index', ['cart' => $cart]);
    }

   public function placeOrder()
{
    $cart = session()->get('cart') ?? [];

    if (empty($cart)) {
        return $this->response->setStatusCode(400)->setBody('Your cart is empty.');
    }

    $customerName   = $this->request->getPost('customer_name');
    $email          = $this->request->getPost('email');
    $address        = $this->request->getPost('address');
    $paymentMethod  = $this->request->getPost('payment_method');

    $purchaseModel  = new PurchaseModel();

    $totalAmount = 0;
    $totalQty = 0;
    $receipt_no = 'REC-' . strtoupper(bin2hex(random_bytes(4)));

    $purchaseData = [];

    foreach ($cart as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $totalAmount += $subtotal;
        $totalQty += $item['quantity'];

        $data = [
            'receipt_no'     => $receipt_no,
            'product_id'     => $item['id'],
            'customer_name'  => $customerName,
            'address'        => $address,
            'quantity'       => $item['quantity'],
            'total'          => $subtotal,
            'payment_method' => $paymentMethod,
            'created_at'     => date('Y-m-d H:i:s'),
        ];

        $purchaseModel->insert($data);
        $purchaseData[] = $data;
    }

    // Clear the cart after placing order
    session()->remove('cart');

    // Prepare data for the receipt view
    $data = [
        'cartItems'      => $cart,
        'receipt'        => $purchaseData,
        'receipt_no'     => $receipt_no,
        'customer_name'  => $customerName,
        'email'          => $email,
        'address'        => $address,
        'payment_method' => $paymentMethod,
        'totalAmount'    => $totalAmount,
        'totalQty'       => $totalQty,
        'is_modal'       => true
    ];

    return view('shop/receipt', $data);
}

}
