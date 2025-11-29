<h3>Checkout</h3>
<?php if(empty($cart)): ?>
  <p>Cart empty.</p>
<?php else: ?>
  <p>Review your items then click Place Order.</p>
  <ul class="list-group mb-3">
  <?php $total=0; foreach($cart as $c): $subtotal=$c['price']*$c['quantity']; $total += $subtotal; ?>
    <li class="list-group-item d-flex justify-content-between">
      <div><?= esc($c['name']) ?> x <?= $c['quantity'] ?></div>
      <div>₱ <?= number_format($subtotal,2) ?></div>
    </li>
  <?php endforeach; ?>
  <li class="list-group-item d-flex justify-content-between">
    <strong>Total</strong> <strong>₱ <?= number_format($total,2) ?></strong>
  </li>
  </ul>

  <form action="/checkout/place" method="post">
    <button class="btn btn-success">Place Order</button>
  </form>
<?php endif; ?>
