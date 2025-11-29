<?= $this->extend('layouts/shop_layout') ?>
<?= $this->section('content') ?>

<style>
  body { background-color: #f5f5f5; }
  .cart-card { border: none; border-radius: 10px; background-color: #fff; }
  .cart-img { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; }
  .cart-item { border-bottom: 1px solid #eee; padding: 15px 0; }
  .cart-item:last-child { border-bottom: none; }
  .btn-view { background-color: #ff6f00; border: none; color: #fff; }
  .btn-view:hover { background-color: #e65c00; }
  .cart-header { background-color: #fafafa; border-bottom: 2px solid #ff6f00; padding: 12px 0; font-weight: 600; color: #444; }
</style>

<div class="container my-5">
  <div class="card cart-card shadow-sm">
    <div class="card-body">
      <h4 class="fw-bold mb-4 text-center text-dark">🛒 My Shopping Cart</h4>

      <?php if (empty($cart)): ?>
        <div class="text-center py-5">
          <i class="fa-solid fa-cart-shopping fa-3x text-muted mb-3"></i>
          <p class="text-muted mb-3">Your cart is empty.</p>
          <a href="<?= base_url('/') ?>" class="btn btn-primary">
            <i class="fa-solid fa-store"></i> Back to Shop
          </a>
        </div>
      <?php else: ?>
        <form action="<?= base_url('cart/update') ?>" method="post">
          <div class="row text-center cart-header mb-2">
            <div class="col-md-2">Product</div>
            <div class="col-md-4">Details</div>
            <div class="col-md-2">Quantity</div>
            <div class="col-md-2">Subtotal</div>
            <div class="col-md-2">Action</div>
          </div>

          <?php $total = 0; $totalQty = 0; ?>
          <?php foreach ($cart as $c): ?>
            <?php 
              $subtotal = $c['price'] * $c['quantity']; 
              $total += $subtotal; 
              $totalQty += $c['quantity'];
            ?>
            <div class="row align-items-center text-center cart-item">
              <div class="col-md-2">
                <img src="<?= $c['image'] ? base_url('uploads/products/' . $c['image']) : base_url('assets/no-image.png') ?>" 
                     alt="<?= esc($c['name']) ?>" 
                     class="cart-img shadow-sm">
              </div>
              <div class="col-md-4 text-start-center">
                <h6 class="fw-bold mb-1"><?= esc($c['name']) ?></h6>
                <p class="text-muted small mb-1">₱ <?= number_format($c['price'], 2) ?> each</p>
              </div>
              <div class="col-md-2">
                <input type="number" name="quantity[<?= $c['id'] ?>]" 
                       value="<?= $c['quantity'] ?>" min="1" 
                       class="form-control text-center mx-auto" style="width:90px;">
              </div>
              <div class="col-md-2 fw-bold text-success">
                ₱ <?= number_format($subtotal, 2) ?>
              </div>
              <div class="col-md-2 d-flex flex-column align-items-center">
                <a href="<?= base_url('product/' . $c['id']) ?>" 
   class="btn btn-sm btn-view mb-2">
   <i class="fa-solid fa-eye"></i> View
</a>

                <a href="<?= base_url('cart/remove/' . $c['id']) ?>" 
                   class="btn btn-sm btn-outline-danger">
                  <i class="fa-solid fa-trash"></i>
                </a>
              </div>
            </div>
          <?php endforeach; ?>

          <div class="d-flex justify-content-between align-items-center mt-4">
            <h5 class="fw-bold mb-0">
              Total: <span class="text-danger">₱ <?= number_format($total, 2) ?></span>
            </h5>
            <button type="submit" class="btn btn-outline-primary">
              <i class="fa-solid fa-rotate"></i> Update Cart
            </button>
          </div>
        </form>
      <?php endif; ?>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
