<?= $this->extend('layouts/main_layout') ?>
<?= $this->section('content') ?>

<style>
  body {
    background-color: #2e2e2eff;
    color: #ffffffff;
  }
  h2 {
    color: #ffffffff;
    font-weight: 600;
  }
  .card {
    background: #fff;
    border: none;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  }
  .table {
    background-color: #fff;
    border-radius: 10px;
    overflow: hidden;
  }
  .table thead {
    background-color: #f1f3f5;
    color: #333;
  }
  .table tbody tr:hover {
    background-color: #f9f9f9;
  }
  .btn {
    border-radius: 8px;
    transition: all 0.2s ease-in-out;
  }
  .btn-primary {
    background-color: #007bff;
    border: none;
  }
  .btn-primary:hover {
    background-color: #0069d9;
  }
  .btn-warning {
    background-color: #ffc107;
    border: none;
  }
  .btn-warning:hover {
    background-color: #ffca2c;
  }
  .btn-danger {
    background-color: #dc3545;
    border: none;
  }
  .btn-danger:hover {
    background-color: #c82333;
  }
  .modal-content {
    background: #ffffff;
    color: #333;
    border-radius: 16px;
    border: none;
    box-shadow: 0 8px 24px rgba(0,0,0,0.2);
  }
  .modal-header {
    border-bottom: 1px solid #e5e5e5;
  }
  .form-label {
    color: #555;
    font-weight: 500;
  }
  .form-control {
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 10px;
    color: #333;
  }
  .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0,123,255,0.4);
  }
  .toast {
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
  }
  select.form-select {
    border-radius: 10px;
    border: 1px solid #ccc;
    transition: all 0.2s ease;
  }
  select.form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0,123,255,0.4);
  }

  /* 🖼 Product Image Styling */
  .table td img {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #eee;
    transition: transform 0.2s ease-in-out;
  }
  .table td img:hover {
    transform: scale(1.1);
  }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h2><i class="bi bi-box-seam me-2 text-primary"></i><?= esc($title) ?></h2>
  <button class="btn btn-primary px-4 py-2" data-bs-toggle="modal" data-bs-target="#addProductModal">
    <i class="bi bi-plus-lg"></i> Add Product
  </button>
</div>

<?php if (session()->getFlashdata('success')): ?>
  <div id="successToast" class="toast align-items-center text-bg-success border-0 position-fixed top-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        ✅ <?= session()->getFlashdata('success') ?>
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
<?php endif; ?>

<div class="card p-4">
  <table class="table table-hover align-middle mb-0">
    <thead>
      <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Name</th>
        <th>Type</th>
        <th>Price</th>
        <th>Stock</th>
        <th class="text-center" width="150">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($products)): ?>
        <?php foreach ($products as $product): ?>
          <tr>
            <td><?= $product['id'] ?></td>
            <td>
              <?php if (!empty($product['image'])): ?>
                <img src="<?= base_url('uploads/products/' . esc($product['image'])) ?>" alt="<?= esc($product['name']) ?>">
              <?php else: ?>
                <span class="text-muted fst-italic">No image</span>
              <?php endif; ?>
            </td>
            <td><?= esc($product['name']) ?></td>
            <td><?= esc($product['type']) ?></td>
            <td>₱<?= number_format($product['price'], 2) ?></td>
            <td><?= esc($product['stock']) ?></td>
            <td class="text-center">
              <button 
                class="btn btn-sm btn-warning editBtn" 
                data-id="<?= $product['id'] ?>"
                data-name="<?= esc($product['name']) ?>"
                data-type="<?= esc($product['type']) ?>"
                data-description="<?= esc($product['description']) ?>"
                data-price="<?= esc($product['price']) ?>"
                data-stock="<?= esc($product['stock']) ?>"
                data-bs-toggle="modal" 
                data-bs-target="#editProductModal">
                <i class="bi bi-pencil-square"></i>
              </button>
              <a href="<?= site_url('admin/products/delete/' . $product['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">
                <i class="bi bi-trash"></i>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="7" class="text-center text-muted">No products found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- 🟩 Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-plus-circle me-2 text-success"></i>Add New Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form action="<?= site_url('admin/products/store') ?>" method="post" enctype="multipart/form-data">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Product Name</label>
              <input type="text" name="name" class="form-control" placeholder="Enter product name" required>
            </div>
            <div class="col-md-6">
              <label class="form-label d-flex justify-content-between align-items-center">
                <span>Type</span>
                <a href="<?= site_url('admin/types') ?>" class="text-decoration-none text-primary small">
                  <i class="bi bi-bookmark-plus"></i> Manage Types
                </a>
              </label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-tag"></i></span>
                <select name="type" class="form-select" required>
                  <option value="">-- Select Type --</option>
                  <?php foreach ($types as $type): ?>
                    <option value="<?= esc($type['name']) ?>"><?= esc($type['name']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-12">
              <label class="form-label">Description</label>
              <textarea name="description" class="form-control" rows="3" placeholder="Short product description..."></textarea>
            </div>
            <div class="col-md-6">
              <label class="form-label">Price (₱)</label>
              <input type="number" step="0.01" name="price" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Stock</label>
              <input type="number" name="stock" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label">Product Image</label>
              <input type="file" name="image" class="form-control">
            </div>
          </div>
          <div class="text-end mt-4">
            <button type="submit" class="btn btn-success px-4 py-2">
              <i class="bi bi-check-circle"></i> Save Product
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- 🟨 Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="editProductForm" method="post" enctype="multipart/form-data">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Product Name</label>
              <input type="text" name="name" id="editName" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label d-flex justify-content-between align-items-center">
                <span>Type</span>
                <a href="<?= site_url('admin/types') ?>" class="text-decoration-none text-primary small">
                  <i class="bi bi-bookmark-plus"></i> Manage Types
                </a>
              </label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-tag"></i></span>
                <select name="type" id="editType" class="form-select" required>
                  <option value="">-- Select Type --</option>
                  <?php foreach ($types as $type): ?>
                    <option value="<?= esc($type['name']) ?>"><?= esc($type['name']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-12">
              <label class="form-label">Description</label>
              <textarea name="description" id="editDescription" class="form-control" rows="3"></textarea>
            </div>
            <div class="col-md-6">
              <label class="form-label">Price (₱)</label>
              <input type="number" step="0.01" name="price" id="editPrice" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Stock</label>
              <input type="number" name="stock" id="editStock" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label">Product Image</label>
              <input type="file" name="image" class="form-control">
            </div>
          </div>
          <div class="text-end mt-4">
            <button type="submit" class="btn btn-warning text-dark px-4 py-2">
              <i class="bi bi-check-circle"></i> Update Product
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const toastEl = document.getElementById("successToast");
  if (toastEl) {
    const toast = new bootstrap.Toast(toastEl, { delay: 2500 });
    toast.show();
  }

  document.querySelectorAll(".editBtn").forEach(btn => {
    btn.addEventListener("click", () => {
      const id = btn.dataset.id;
      document.getElementById("editName").value = btn.dataset.name;
      document.getElementById("editDescription").value = btn.dataset.description;
      document.getElementById("editPrice").value = btn.dataset.price;
      document.getElementById("editStock").value = btn.dataset.stock;
      document.getElementById("editType").value = btn.dataset.type;
      document.getElementById("editProductForm").action = "<?= site_url('admin/products/update') ?>/" + id;
    });
  });
});
</script>

<?= $this->endSection() ?>
