<?php
// expects $r array (receipt data)
?>
<div class="receipt p-3" style="font-family:monospace; max-width:800px;">
  <div class="d-flex justify-content-between mb-2">
    <div>
      <h5 class="mb-0">Uling Shop</h5>
      <small>Unit 1, Uling Mall • Contact: 0917-000-0000</small>
    </div>
    <div class="text-end">
      <div><strong>RECEIPT</strong></div>
      <div>No: <?= esc($r['receipt_no']) ?></div>
      <div>Order #: <?= esc($r['order_id']) ?></div>
      <div><?= esc($r['date']) ?></div>
    </div>
  </div>

  <hr>

  <div class="mb-2">
    <strong>Bill To:</strong><br>
    <?= esc($r['buyer_name']) ?><br>
    <?= esc($r['buyer_email']) ?><br>
    <?= nl2br(esc($r['buyer_address'])) ?>
  </div>

  <table class="table table-sm mb-2">
    <thead>
      <tr>
        <th>Item</th>
        <th class="text-center">Qty</th>
        <th class="text-end">Unit (₱)</th>
        <th class="text-end">Line (₱)</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($r['items'] as $it): ?>
        <tr>
          <td><?= esc($it['name']) ?></td>
          <td class="text-center"><?= esc($it['qty']) ?></td>
          <td class="text-end"><?= esc($it['unit']) ?></td>
          <td class="text-end"><?= esc($it['line']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="3" class="text-end">Subtotal</td>
        <td class="text-end"><?= esc($r['subtotal']) ?></td>
      </tr>
      <tr>
        <td colspan="3" class="text-end">Shipping</td>
        <td class="text-end"><?= esc($r['shipping']) ?></td>
      </tr>
      <tr>
        <td colspan="3" class="text-end">Tax</td>
        <td class="text-end"><?= esc($r['tax']) ?></td>
      </tr>
      <tr>
        <td colspan="3" class="text-end"><strong>Total</strong></td>
        <td class="text-end"><strong><?= esc($r['total']) ?></strong></td>
      </tr>
    </tfoot>
  </table>

  <div class="small text-muted">
    Payment Method: <?= esc($r['payment_method']) ?><br>
    Thank you for shopping with us!
  </div>
</div>
