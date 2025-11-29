<?= $this->extend('layouts/main_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>📋 Activity Logs</h3>
    <button id="clearLogsBtn" class="btn btn-danger btn-sm">
        <i class="bi bi-trash"></i> Clear Logs
    </button>
</div>

<p>View user actions and network activity.</p>

<div class="card mt-3 p-3">
    <table class="table table-bordered text-white align-middle" id="activityLogsTable">
        <thead style="background-color: #333;">
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Username</th>
                <th>Action</th>
                <th>IP Address</th>
                <th>MAC Address</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($logs as $log): ?>
            <tr style="background-color: #2b2b2b;">
                <td><?= esc($log['id']) ?></td>
                <td><?= esc($log['user_id']) ?></td>
                <td><?= esc($log['username']) ?></td>
                <td><?= esc($log['action']) ?></td>
                <td><?= esc($log['ip_address']) ?></td>
                <td><?= esc($log['mac_address'] ?? '-') ?></td>
                <td><?= date('F j, Y H:i:s', strtotime($log['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
document.getElementById('clearLogsBtn').addEventListener('click', function() {
    if (!confirm('Are you sure you want to clear all logs?')) return;

    fetch('<?= site_url('admin/activity-logs/clear') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            const tbody = document.querySelector('#activityLogsTable tbody');
            tbody.innerHTML = '';
            alert(data.message);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => alert('Error: ' + err));
});

</script>

<?= $this->endSection() ?>
