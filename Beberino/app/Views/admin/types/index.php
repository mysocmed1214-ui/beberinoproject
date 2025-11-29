<?= $this->extend('layouts/main_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h2><?= esc($title) ?></h2>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTypeModal">
    <i class="bi bi-plus-lg"></i> Add Type
  </button>
</div>

<?php if (session()->getFlashdata('success')): ?>
  <div id="toast" class="toast-notification">
    <i class="bi bi-check-circle-fill"></i>
    <?= session()->getFlashdata('success') ?>
  </div>
<?php endif; ?>

<table class="table table-bordered table-hover">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Description</th>
      <th>Created</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($types)): ?>
      <?php foreach ($types as $type): ?>
        <tr>
          <td><?= $type['id'] ?></td>
          <td><?= esc($type['name']) ?></td>
          <td><?= esc($type['description']) ?></td>
          <td><?= date('M d, Y h:i A', strtotime($type['created_at'])) ?></td>
          <td>
            <a href="<?= site_url('admin/types/delete/' . $type['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this type?')">
              <i class="bi bi-trash"></i>
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="5" class="text-center text-muted">No product types found.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<!-- 🧩 Add Type Modal -->
<div class="modal fade" id="addTypeModal" tabindex="-1" aria-labelledby="addTypeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content modal-charcoal">
      <div class="modal-header border-0">
        <h5 class="modal-title text-white" id="addTypeModalLabel">Add Product Type</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="<?= site_url('admin/types/store') ?>" method="post">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label text-light">Name</label>
            <input type="text" name="name" class="form-control bg-dark text-white border-secondary" required>
          </div>
          <div class="mb-3">
            <label class="form-label text-light">Description</label>
            <textarea name="description" class="form-control bg-dark text-white border-secondary" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="submit" class="btn btn-success w-100 fw-bold py-2">Save Type</button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
/* === Blur background effect === */
.modal-backdrop.show {
  backdrop-filter: blur(8px);
  background-color: rgba(0, 0, 0, 0.4);
}

/* === Charcoal modal styling === */
.modal-charcoal {
  background-color: #2b2b2b;
  color: white;
  border-radius: 12px;
  box-shadow: 0 4px 25px rgba(0, 0, 0, 0.5);
}

.modal-charcoal .form-control {
  background-color: #3b3b3b;
  color: #fff;
}

.modal-charcoal .form-control:focus {
  background-color: #444;
  border-color: #0d6efd;
  color: #fff;
  box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

/* === iPhone-style toast === */
.toast-notification {
  position: fixed;
  top: 20px;
  right: 20px;
  background: #198754;
  color: white;
  padding: 15px 20px;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.2);
  font-size: 15px;
  display: flex;
  align-items: center;
  gap: 10px;
  animation: slideDown 0.5s ease, fadeOut 0.5s ease 3s forwards;
  z-index: 9999;
}

.toast-notification i {
  font-size: 18px;
}
body {
    background-color: #2e2e2eff;
    color: #ffffffff;
  }

@keyframes slideDown {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeOut {
  to { opacity: 0; transform: translateY(-20px); }
}
</style>

<?= $this->endSection() ?>
