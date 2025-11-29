<?= $this->extend('layouts/main_layout') ?>
<?= $this->section('content') ?>

<h3>👥 Customers</h3>
<p>View registered users/customers.</p>

<div class="card mt-3 p-3">
    <table class="table table-bordered text-white align-middle">
        <thead style="background-color: #333;">
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Is Admin</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $customer): ?>
                <tr style="background-color: #2b2b2b;">
                    <td><?= esc($customer['id']) ?></td>
                    <td><?= esc($customer['fullname']) ?></td>
                    <td><?= esc($customer['email']) ?></td>
                    <td><?= $customer['is_admin'] ? 'Yes' : 'No' ?></td>
                    <td><?= date('F j, Y', strtotime($customer['created_at'])) ?></td>
                    <td><?= $customer['updated_at'] ? date('F j, Y', strtotime($customer['updated_at'])) : '-' ?></td>
                    <td>
                        <!-- Edit Button -->
                        <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editModal<?= $customer['id'] ?>" title="Edit">
                            <i class="bi bi-pencil-fill"></i>
                        </button>

                        <!-- Delete Button -->
                        <a href="<?= site_url('admin/customers/delete/'.$customer['id']) ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this customer?')">
                            <i class="bi bi-trash-fill"></i>
                        </a>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal<?= $customer['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $customer['id'] ?>" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content" style="background-color: #2b2b2b; color: #fff;">
                              <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel<?= $customer['id'] ?>">Edit Customer</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <form action="<?= site_url('admin/customers/update/'.$customer['id']) ?>" method="POST">
                                  <?= csrf_field() ?>
                                  <div class="modal-body">
                                      <div class="mb-3">
                                          <label for="fullname<?= $customer['id'] ?>" class="form-label">Full Name</label>
                                          <input type="text" class="form-control bg-dark text-white border-secondary" id="fullname<?= $customer['id'] ?>" name="fullname" value="<?= esc($customer['fullname']) ?>" required>
                                      </div>
                                      <div class="mb-3">
                                          <label for="email<?= $customer['id'] ?>" class="form-label">Email</label>
                                          <input type="email" class="form-control bg-dark text-white border-secondary" id="email<?= $customer['id'] ?>" name="email" value="<?= esc($customer['email']) ?>" required>
                                      </div>
                                      <div class="mb-3">
                                          <label for="is_admin<?= $customer['id'] ?>" class="form-label">Is Admin</label>
                                          <select class="form-select bg-dark text-white border-secondary" id="is_admin<?= $customer['id'] ?>" name="is_admin">
                                              <option value="0" <?= $customer['is_admin'] == 0 ? 'selected' : '' ?>>No</option>
                                              <option value="1" <?= $customer['is_admin'] == 1 ? 'selected' : '' ?>>Yes</option>
                                          </select>
                                      </div>
                                      <div class="mb-3">
                                          <label class="form-label">Created At</label>
                                          <input type="text" class="form-control bg-dark text-white border-secondary" value="<?= date('F j, Y', strtotime($customer['created_at'])) ?>" disabled>
                                      </div>
                                      <div class="mb-3">
                                          <label class="form-label">Updated At</label>
                                          <input type="text" class="form-control bg-dark text-white border-secondary" value="<?= $customer['updated_at'] ? date('F j, Y', strtotime($customer['updated_at'])) : '-' ?>" disabled>
                                      </div>
                                  </div>
                                  <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                      <button type="submit" class="btn btn-warning text-dark">Update</button>
                                  </div>
                              </form>
                            </div>
                          </div>
                        </div>
                        <!-- End Modal -->
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?= $this->endSection() ?>
