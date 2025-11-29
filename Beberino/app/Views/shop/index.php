<?= $this->extend('layouts/shop_layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-success text-center"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
  <div class="alert alert-danger text-center"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<h3 class="fw-bold mb-4 text-center">
  <i class="fa-solid fa-boxes-stacked"></i>
  <?= isset($category) ? esc(ucfirst($category)) . ' Products' : 'Our Latest Products' ?>
</h3>


<div class="container">
  <div class="row g-4 justify-content-center">
    <?php foreach ($products as $p): ?>
      <?php 
        $reviewModel = new \App\Models\ReviewModel();
        $avgRating = $reviewModel->where('product_id', $p['id'])->selectAvg('rating')->first();
        $average = round($avgRating['rating'] ?? 0, 1);
        $sold = $p['sold'] ?? 0;
      ?>

      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="card shadow-sm border-0 h-100">
          <!-- 🖼 Product Image -->
          <?php if ($p['image']): ?>
            <img src="<?= base_url('uploads/products/' . $p['image']) ?>" 
                 class="card-img-top img-fluid rounded-top" 
                 alt="<?= esc($p['name']) ?>" 
                 style="height: 220px; object-fit: cover;">
          <?php else: ?>
            <img src="<?= base_url('assets/no-image.png') ?>" 
                 class="card-img-top img-fluid rounded-top" 
                 alt="No image" 
                 style="height: 220px; object-fit: cover;">
          <?php endif; ?>

          <!-- 📝 Product Details -->
          <div class="card-body text-center d-flex flex-column justify-content-between">
            <div>
              <h6 class="fw-bold mb-1 text-dark"><?= esc($p['name']) ?></h6>
              <p class="text-muted small mb-2"><?= esc($p['type']) ?></p>
            </div>

            <!-- 💰 Price -->
            <p class="fw-bold text-primary fs-5 mb-2">₱ <?= number_format($p['price'], 2) ?></p>

            <!-- ⭐ Rating + Sold -->
            <div class="d-flex justify-content-between align-items-center text-muted small mb-2">
              <div>
                <i class="fa-solid fa-star text-warning"></i>
                <?= $average > 0 ? number_format($average, 1) : '0.0' ?>
              </div>
              <div><?= esc($p['sold']) ?> sold</div>

            </div>

            <!-- 🔘 Actions -->
            <div>
              <a href="<?= base_url('product/' . $p['id']) ?>" class="btn btn-primary btn-sm w-100 mb-2">
                <i class="fa-solid fa-eye"></i> View Details
              </a>
              <form method="post" action="<?= base_url('cart/add') ?>">
                <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                <input type="hidden" name="quantity" value="1">
                <button class="btn btn-outline-success btn-sm w-100">
                  <i class="fa-solid fa-cart-plus"></i> Add to Cart
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?= $this->endSection() ?>
