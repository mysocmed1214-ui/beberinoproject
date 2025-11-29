<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?? 'Admin Dashboard' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    /* Charcoal Theme Layout */
    body {
      background-color: #1e1e1e;
      color: #f5f5f5;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Sidebar */
    .sidebar {
      min-height: 100vh;
      background-color: #2b2b2b;
      color: #fff;
      width: 250px;
      position: fixed;
      box-shadow: 2px 0 8px rgba(0,0,0,0.5);
    }

    .sidebar h4 {
      color: #ff8c00;
      font-weight: bold;
      letter-spacing: 1px;
    }

    .sidebar a {
      color: #d1d5db;
      text-decoration: none;
      display: block;
      padding: 12px 20px;
      border-radius: 8px;
      margin-bottom: 4px;
      transition: all 0.2s;
    }

    .sidebar a:hover, .sidebar a.active {
      background-color: #ff8c00;
      color: #1e1e1e;
    }

    .sidebar hr {
      border-color: #444;
      margin: 15px 0;
    }

    /* Main Content */
    .content {
      margin-left: 250px;
      padding: 30px;
      min-height: 100vh;
    }

    /* Scrollbar for sidebar */
    .sidebar {
      overflow-y: auto;
    }

    .sidebar::-webkit-scrollbar {
      width: 6px;
    }
    .sidebar::-webkit-scrollbar-thumb {
      background-color: #ff8c00;
      border-radius: 3px;
    }

    /* Cards / Panels */
    .card {
      background-color: #2b2b2b;
      border: none;
      color: #fff;
      border-radius: 12px;
      box-shadow: 0 3px 12px rgba(0,0,0,0.4);
    }

    /* Links */
    .sidebar a i {
      margin-right: 10px;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar d-flex flex-column p-3">
    <h4 class="mb-4">🔥 Admin Panel</h4>
    <a href="<?= site_url('admin/dashboard') ?>" class="<?= current_url() == site_url('admin/dashboard') ? 'active' : '' ?>">
      <i class="bi bi-speedometer2"></i> Dashboard
    </a>
    <a href="<?= site_url('admin/products') ?>" class="<?= current_url() == site_url('admin/products') ? 'active' : '' ?>">
      <i class="bi bi-box-seam"></i> Products
    </a>
    <a href="<?= site_url('admin/types') ?>" class="<?= current_url() == site_url('admin/types') ? 'active' : '' ?>">
      <i class="bi bi-tag"></i> Product Types
    </a>
    <a href="<?= site_url('admin/orders') ?>" class="<?= current_url() == site_url('admin/orders') ? 'active' : '' ?>">
      <i class="bi bi-bag-check"></i> Orders
    </a>
    <a href="<?= site_url('admin/customers') ?>" class="<?= current_url() == site_url('admin/customers') ? 'active' : '' ?>">
      <i class="bi bi-people"></i> Customers
    </a>
    <hr>
    <a href="<?= site_url('auth/admin_logout') ?>" class="text-danger">
      <i class="bi bi-box-arrow-right"></i> Logout
    </a>
  </div>

  <!-- Main Content -->
  <div class="content">
    <?= $this->renderSection('content') ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
