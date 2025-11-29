<?= $this->extend('layouts/main_layout') ?>
<?= $this->section('content') ?>

<h3>⚙️ System Settings</h3>
<p>Toggle the system between Online and Maintenance modes.</p>

<div class="card p-4 mt-3">
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="systemModeSwitch" <?= $mode === 'online' ? 'checked' : '' ?>>
        <label class="form-check-label" for="systemModeSwitch">
            <?= $mode === 'online' ? 'System is Online' : 'System Under Maintenance' ?>
        </label>
    </div>
</div>

<script>
document.getElementById('systemModeSwitch').addEventListener('change', function() {
    fetch('<?= site_url('admin/system/toggle') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            const label = document.querySelector('label[for="systemModeSwitch"]');
            label.textContent = data.newMode === 'online' ? 'System is Online' : 'System Under Maintenance';
        }
    });
});
</script>

<?= $this->endSection() ?>
