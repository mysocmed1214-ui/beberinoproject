<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?? 'Admin Dashboard' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .sidebar {
      min-height: 100vh;
      background-color: #1f2937;
      color: #fff;
      width: 250px;
      position: fixed;
    }
    .sidebar a {
      color: #d1d5db;
      text-decoration: none;
      display: block;
      padding: 12px 20px;
      border-radius: 6px;
    }
    .sidebar a:hover, .sidebar a.active {
      background-color: #374151;
      color: #fff;
    }
    .content {
      margin-left: 250px;
      padding: 30px;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar d-flex flex-column p-3">
    <h4 class="text-white mb-4">🔥 Admin Panel</h4>
    <a href="<?= site_url('admin/dashboard') ?>" class="<?= current_url() == site_url('admin/dashboard') ? 'active' : '' ?>">
      <i class="bi bi-speedometer2 me-2"></i> Dashboard
    </a>
    <a href="<?= site_url('admin/products') ?>" class="<?= current_url() == site_url('admin/products') ? 'active' : '' ?>">
      <i class="bi bi-box-seam me-2"></i> Products
    </a>
    <a href="<?= site_url('admin/types') ?>" class="<?= current_url() == site_url('admin/types') ? 'active' : '' ?>">
      <i class="bi bi-tag me-2"></i> Product Types
    </a>
    <a href="<?= site_url('admin/orders') ?>" class="<?= current_url() == site_url('admin/orders') ? 'active' : '' ?>">
      <i class="bi bi-bag-check me-2"></i> Orders
    </a>
    <a href="<?= site_url('admin/customers') ?>" class="<?= current_url() == site_url('admin/customers') ? 'active' : '' ?>">
      <i class="bi bi-people me-2"></i> Customers
    </a>
    <hr class="text-secondary">
    <a href="<?= site_url('auth/admin_logout') ?>" class="text-danger">
      <i class="bi bi-box-arrow-right me-2"></i> Logout
    </a>
  </div>

  <!-- Main Content -->
  <div class="content">
    <?= $this->renderSection('content') ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
