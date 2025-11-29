<?= $this->extend('layouts/main_layout') ?>
<?= $this->section('content') ?>

<style>
    .card-type {
        background-color: #2b2b2b;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 3px 12px rgba(0,0,0,0.4);
        color: #fff;
    }
     h2 {
    color: #ffffffff;
    font-weight: 600;
  }
</style>

<div class="container-fluid">
    <h2>Product Types Summary</h2>
    <p class="text-muted-white">Overview of charcoal types and total sold.</p>

    <div class="row mt-4">
        <?php foreach ($typesData as $type): ?>
            <div class="col-md-4">
                <div class="card-type text-center">
                    <h5><?= esc($type['type']) ?></h5>
                    <p>Total Sold: <strong><?= esc($type['total_sold']) ?></strong></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="card mt-4 p-4">
        <h5>Most Sold Products by Type</h5>
        <canvas id="typeChart" height="100"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('typeChart').getContext('2d');
const typeChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($chartLabels) ?>,
        datasets: [{
            label: 'Total Sold',
            data: <?= json_encode($chartData) ?>,
            backgroundColor: 'rgba(255,140,0,0.7)',
            borderColor: '#ff8c00',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            x: { ticks: { color: '#fff' }, grid: { color: '#444' } },
            y: { ticks: { color: '#fff' }, grid: { color: '#444' } }
        },
        plugins: {
            legend: { labels: { color: '#fff' } }
        }
    }
});
</script>

<?= $this->endSection() ?>
