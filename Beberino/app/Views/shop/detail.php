<?= $this->extend('layouts/shop_layout') ?>
<?= $this->section('content') ?>

<div class="container my-5">
  <div class="row">
    <!-- Product Image -->
    <div class="col-md-6 text-center">
      <div class="p-3 border rounded shadow-sm bg-light">
        <img src="<?= !empty($product['image']) ? base_url('uploads/products/' . $product['image']) : base_url('assets/img/no-image.png') ?>" 
             class="img-fluid rounded"
             alt="<?= esc($product['name']) ?>"
             style="max-height: 400px; width: auto; object-fit: contain;">
      </div>
    </div>

    <!-- Product Info -->
    <div class="col-md-6">
      <h2 class="fw-bold"><?= esc($product['name']) ?></h2>
      <p class="text-muted"><?= esc($product['type']) ?></p>
      <p><?= nl2br(esc($product['description'])) ?></p>
      <h4 class="text-primary fw-bold mb-4">₱ <?= number_format($product['price'], 2) ?></h4>

      <!-- Add to Cart + Buy Now -->
      <form class="row g-2 mb-4" id="addToCartForm" method="post" action="/cart/add">
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        <div class="col-auto">
          <input id="qtyInput" type="number" name="quantity" value="1" min="1" class="form-control" style="width:100px">
        </div>
        <div class="col-auto">
          <button type="submit" class="btn btn-success">
            <i class="fa-solid fa-cart-plus"></i> Add to Cart
          </button>
        </div>
        <div class="col-auto">
          <button type="button" class="btn btn-primary" id="buyNowBtn">
            <i class="fa-solid fa-money-bill-wave"></i> Buy Now
          </button>
        </div>
      </form>

      <div class="border-top pt-3">
        <h5 class="fw-bold text-warning">
          ⭐ <?= $average_rating ?> / 5 <span class="text-muted">(<?= $review_count ?> reviews)</span>
        </h5>
      </div>
    </div>
  </div>

  <hr class="my-5">

  <!-- 📝 Review Form -->
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h4 class="fw-bold mb-3"><i class="fa-solid fa-pen-to-square"></i> Leave a Review</h4>

      <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
      <?php endif; ?>
      <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
      <?php endif; ?>

      <form action="<?= site_url('shop/review/' . $product['id']) ?>" method="post" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Your Name</label>
          <input type="text" name="customer_name" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Your Rating</label><br>
          <div class="rating-stars">
            <?php for ($i = 5; $i >= 1; $i--): ?>
              <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" required>
              <label for="star<?= $i ?>"><i class="fa-solid fa-star"></i></label>
            <?php endfor; ?>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Your Feedback</label>
          <textarea name="feedback" rows="3" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Upload Product Image</label>
          <input type="file" name="image" class="form-control" id="imageInput" accept="image/*">
          <img id="imagePreview" class="img-fluid mt-3 rounded shadow-sm d-none" style="max-height:200px;">
        </div>

        <button class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Submit Review</button>
      </form>
    </div>
  </div>

  <!-- 🧍 Customer Reviews -->
  <h4 class="fw-bold mb-3">
    <i class="fa-solid fa-comments"></i> Customer Reviews for <span class="text-primary"><?= esc($product['name']) ?></span>
  </h4>

  <div class="row">
    <?php if (empty($reviews)): ?>
      <p class="text-muted">No reviews yet. Be the first to leave one!</p>
    <?php else: ?>
      <?php foreach ($reviews as $r): ?>
        <?php if ($r['product_id'] == $product['id']): ?>
          <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <h6 class="fw-bold mb-1"><?= esc($r['customer_name']) ?></h6>
                <div class="star-rating mb-2">
                  <?php for ($i = 1; $i <= 5; $i++): ?>
                    <i class="fa-<?= $i <= $r['rating'] ? 'solid text-warning' : 'regular text-secondary' ?> fa-star"></i>
                  <?php endfor; ?>
                </div>
                <p><?= esc($r['feedback']) ?></p>
                <?php if ($r['image']): ?>
                  <img src="<?= base_url('uploads/reviews/' . $r['image']) ?>" 
                       class="img-fluid rounded mt-2 shadow-sm" style="max-height:200px;">
                <?php endif; ?>
                <small class="text-muted d-block mt-2">Posted on <?= date('F j, Y', strtotime($r['created_at'])) ?></small>
              </div>
            </div>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<!-- 💳 Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Payment Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="paymentForm">
        <div class="modal-body">
          <div id="paymentAlert" class="alert alert-danger d-none"></div>
          <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Full Name</label>
              <input type="text" name="customer_name" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control">
            </div>
            <div class="col-12">
              <label class="form-label">Delivery Address</label>
              <textarea name="address" rows="2" class="form-control" required></textarea>
            </div>
            <div class="col-md-4">
              <label class="form-label">Quantity</label>
              <input id="payQty" type="number" name="quantity" class="form-control" min="1" value="1" required>
            </div>
            <div class="col-md-8">
              <label class="form-label">Payment Method</label>
              <select name="payment_method" class="form-select" required>
                <option value="Card">Card</option>
                <option value="GCash">GCash</option>
                <option value="COD">Cash on Delivery</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" id="submitPayment" class="btn btn-success">Pay Now</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- 🧾 Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Receipt</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div id="receiptContainer" class="modal-body"></div>
      <div class="modal-footer">
        <button id="printReceipt" class="btn btn-outline-secondary">Print</button>
        <button class="btn btn-primary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- 🌟 Styles -->
<style>
.rating-stars {
  display: flex;
  flex-direction: row; /* ✅ fix: was row-reverse */
  gap: 4px;
  justify-content: flex-start; /* ✅ align stars to the left */
}
.rating-stars input {
  display: none;
}
.rating-stars label {
  cursor: pointer;
  font-size: 1.8rem;
  color: #ccc;
  transition: color 0.2s;
}
.rating-stars label:hover i,
.rating-stars label:hover ~ label i,
.rating-stars input:checked ~ label i {
  color: #ffcc00;
}
.star-rating i {
  font-size: 1.1rem;
  margin-right: 2px;
}
</style>


<!-- ⚙️ Scripts -->
<script>
document.addEventListener("DOMContentLoaded", () => {
  const buyBtn = document.getElementById('buyNowBtn');
  const form = document.getElementById('paymentForm');
  const qtyInput = document.getElementById('qtyInput');
  const payQty = document.getElementById('payQty');
  const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
  const receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));

  qtyInput.addEventListener('change', () => payQty.value = qtyInput.value);
  buyBtn.addEventListener('click', () => {
    payQty.value = qtyInput.value || 1;
    paymentModal.show();
  });

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('submitPayment');
    btn.disabled = true; btn.innerHTML = 'Processing...';

    const formData = new FormData(form);
    const res = await fetch('<?= base_url("shop/buy/" . $product["id"]) ?>', { method: 'POST', body: formData });
    const html = await res.text();

    paymentModal.hide();
    document.getElementById('receiptContainer').innerHTML = html;
    receiptModal.show();
    btn.disabled = false; btn.innerHTML = 'Pay Now';
  });

  document.getElementById('printReceipt').addEventListener('click', () => {
    const content = document.getElementById('receiptContainer').innerHTML;
    const w = window.open('', '', 'width=800,height=900');
    w.document.write('<html><head><title>Receipt</title></head><body>'+content+'</body></html>');
    w.document.close();
    w.print();
  });

  // Live image preview for review form
  document.getElementById('imageInput')?.addEventListener('change', e => {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    if (file) {
      preview.src = URL.createObjectURL(file);
      preview.classList.remove('d-none');
    } else {
      preview.classList.add('d-none');
    }
  });
});
</script>

<?= $this->endSection() ?>
