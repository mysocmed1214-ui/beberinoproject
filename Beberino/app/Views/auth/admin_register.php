<!-- Admin Registration -->
<div class="d-flex align-items-center justify-content-center min-vh-100" style="background: #282828ff;">
  <div class="card shadow-lg p-4" style="max-width:400px; width:100%; background:#222; color:#f5f5f5; border-radius:12px;">
    <div class="text-center mb-4">
      <h3 style="color:#ff6f00;">Admin Registration</h3>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <form action="<?= site_url('auth/adminRegisterPost') ?>" method="post">
      <div class="mb-3">
        <label>Full Name</label>
        <input type="text" name="fullname" class="form-control" required
               style="background:#333; color:#f5f5f5; border:none;">
      </div>

      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required
               style="background:#333; color:#f5f5f5; border:none;">
      </div>

      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required
               style="background:#333; color:#f5f5f5; border:none;">
      </div>

      <button type="submit" class="btn w-100" style="background:#ff6f00; color:#fff;">Register as Admin</button>
    </form>

    <div class="mt-3 text-center">
      <a href="<?= site_url('auth/admin_login') ?>" style="color:#bbb;">← Back to Admin Login</a>
    </div>
  </div>
</div>