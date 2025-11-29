<?= $this->extend('layouts/main_layout') ?>
<?= $this->section('content') ?>

<style>
  /* Charcoal Theme */
  body {
    background-color: #2e2e2eff;
    color: #f5f5f5;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .dashboard-card {
    border-radius: 12px;
    background-color: #2b2b2b;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.3);
  }

  .dashboard-card h5 {
    font-weight: 600;
    color: #ff8c00;
  }

  .dashboard-card p {
    font-size: 1.8rem;
    font-weight: bold;
    margin: 0;
  }

  .table thead {
    background-color: #333;
    color: #ff8c00;
  }

  .table tbody tr {
    background-color: #2b2b2b;
    color: #fff;
  }

  .chart-container {
    background-color: #2b2b2b;
    padding: 20px;
    border-radius: 12px;
    margin-top: 20px;
  }

  .card-icon {
    font-size: 1.8rem;
    margin-right: 10px;
  }
</style>

<div class="container-fluid mt-4">
  <h2>Welcome, <?= session()->get('admin_name') ?> 👋</h2>
  <p class="text-muted-white">You are now viewing the admin dashboard.</p>

  <!-- Dashboard Stats -->
  <div class="row mt-4">
    <div class="col-md-3">
      <div class="dashboard-card text-center">
        <h5><i class="fa-solid fa-dollar-sign card-icon"></i> Revenue</h5>
        <p>₱ <?= number_format($totalRevenue ?? 0, 2) ?></p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="dashboard-card text-center">
        <h5><i class="fa-solid fa-cart-shopping card-icon"></i> Total Orders</h5>
        <p><?= $totalOrders ?? 0 ?></p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="dashboard-card text-center">
        <h5><i class="fa-solid fa-users card-icon"></i> Customers</h5>
        <p><?= $totalCustomers ?? 0 ?></p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="dashboard-card text-center">
        <h5><i class="fa-solid fa-boxes-stacked card-icon"></i> Items Sold</h5>
        <p><?= $totalPurchases ?? 0 ?></p>
      </div>
    </div>
  </div>

  <!-- Revenue Chart -->
  <div class="chart-container">
    <canvas id="revenueChart" height="100"></canvas>
  </div>

  <!-- Recent Purchases -->
  <div class="card dashboard-card mt-4">
    <h5>Recent Purchases</h5>
    <table class="table table-bordered mt-3">
      <thead>
        <tr>
          <th>Receipt No</th>
          <th>Customer</th>
          <th>Product</th>
          <th>Quantity</th>
          <th>Total</th>
          <th>Payment</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($recentPurchases as $purchase): ?>
          <tr>
            <td><?= esc($purchase['receipt_no']) ?></td>
            <td><?= esc($purchase['customer_name']) ?></td>
            <td><?= esc($purchase['product_name']) ?></td>
            <td><?= esc($purchase['quantity']) ?></td>
            <td>₱ <?= number_format($purchase['total'], 2) ?></td>
            <td><?= esc($purchase['payment_method']) ?></td>
            <td><?= date('F j, Y', strtotime($purchase['created_at'])) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('revenueChart').getContext('2d');
  const revenueChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: <?= json_encode($revenueChartLabels ?? []) ?>,
      datasets: [{
        label: 'Revenue (₱)',
        data: <?= json_encode($revenueChartData ?? []) ?>,
        backgroundColor: 'rgba(255,140,0,0.2)',
        borderColor: '#ff8c00',
        borderWidth: 2,
        tension: 0.3,
        fill: true
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { labels: { color: '#fff' } }
      },
      scales: {
        x: { ticks: { color: '#fff' }, grid: { color: '#444' } },
        y: { ticks: { color: '#fff' }, grid: { color: '#444' } }
      }
    }
  });
</script>

<?= $this->endSection() ?>
