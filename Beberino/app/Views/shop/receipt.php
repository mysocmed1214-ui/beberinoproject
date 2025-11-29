<?php if (!isset($is_modal) || !$is_modal): ?>
  <?= $this->extend('layouts/shop_layout') ?>
  <?= $this->section('content') ?>
<?php endif; ?>

<style>
  /* 🔥 Uling Theme Styles */
  body {
    background-color: #1e1e1e;
  }
  .receipt-card {
    border-radius: 15px;
    background-color: #2b2b2b;
    color: #ffffff;
  }
  .receipt-header {
    border-bottom: 3px solid #ff8c00;
    padding-bottom: 15px;
  }
  .receipt-logo img {
    height: 70px;
  }
  .receipt-title {
    color: #ff8c00;
    font-weight: bold;
    letter-spacing: 1px;
  }
  .receipt-info p,
  .receipt-info h5 {
    margin: 6px 0;
    font-size: 1rem;
    color: #ffffff;
  }
  .receipt-info .text-muted {
    color: #cccccc !important;
  }
  .receipt-highlight {
    color: #ff8c00;
    font-weight: bold;
  }
  .success-box {
    border: 1px solid #ff8c00;
    background: rgba(255, 140, 0, 0.15);
    border-radius: 15px;
    padding: 20px;
    margin-top: 20px;
  }
  .success-box h5 {
    color: #ffffff;
  }
  .success-box i {
    color: #ff8c00;
  }
  .btn-uling {
    background-color: #ff8c00;
    color: #fff;
    border: none;
  }
  .btn-uling:hover {
    background-color: #e67e00;
  }
</style>

<div class="container my-5">
  <div class="card receipt-card shadow-lg border-0 p-4">
    <!-- 🧾 Header -->
    <div class="receipt-header text-center mb-4">
      <div class="receipt-logo mb-2">
        <img src="https://3.bp.blogspot.com/-wKJKPM04Br8/WqJV6H565pI/AAAAAAAAB4s/zWBuRAFuEVUdmmSemR7QGkRb5MCAGUCHQCLcBGAs/s1600/logo-web-center.png" alt="Uling Logo">
      </div>
      <h3 class="receipt-title">Official Purchase Receipt</h3>
      <p class="mb-0">Receipt No: 
        <span class="receipt-highlight">
          <?= esc($receipt['receipt_no'] ?? 'N/A') ?>
        </span>
      </p>
    </div>

    <!-- 🧍 Customer + Product Info -->
    <div class="receipt-info text-center">
      <h5 class="fw-bold mb-1">
        <?= esc($product['name'] ?? 'No Product Selected') ?>
      </h5>
      <p class="text-muted">
        <?= esc($product['type'] ?? '—') ?>
      </p>

      <hr class="border-light my-3" style="opacity: 0.2;">

      <div class="row justify-content-center text-start mb-3">
        <div class="col-md-6">
          <p><strong>Customer:</strong> <?= esc($receipt['customer_name'] ?? 'Juan Dela Cruz') ?></p>
          <p><strong>Address:</strong> <?= esc($receipt['address'] ?? '123 Uling Street, City, Philippines') ?></p>
          <p><strong>Quantity:</strong> <?= esc($receipt['quantity'] ?? 1) ?></p>
          <p><strong>Total Amount:</strong>
            <span class="receipt-highlight">
              ₱ <?= number_format($receipt['total'] ?? 0, 2) ?>
            </span>
          </p>
          <p><strong>Payment Method:</strong> <?= esc($receipt['payment_method'] ?? 'Cash') ?></p>
          <p><strong>Date:</strong> 
            <?= isset($receipt['created_at']) ? date('F j, Y g:i A', strtotime($receipt['created_at'])) : date('F j, Y g:i A') ?>
          </p>
        </div>
      </div>

      <div class="success-box text-center">
        <i class="fa-solid fa-circle-check fa-3x mb-2"></i>
        <h5 class="mt-2 mb-1">Payment Successful!</h5>
        <p class="text-light mb-0">Your order has been confirmed 🎉</p>
      </div>

      <a href="<?= base_url('/') ?>" class="btn btn-uling mt-4">
        <i class="fa-solid fa-house"></i> Back to Home
      </a>
    </div>
  </div>
</div>

<?php if (!isset($is_modal) || !$is_modal): ?>
  <?= $this->endSection() ?>
<?php endif; ?>
