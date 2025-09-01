<?php
session_start();
include('../backend/pages/db.php');
include("header.php");

$cart = $_SESSION['cart'] ?? [];
$total = 0;

$userState = 'Tamil Nadu';

$is_logged_in = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

if (!$is_logged_in) {
    $_SESSION['redirect_after_login'] = 'checkout.php';
}
?>

<div class="breadcrumb mb-0 py-26 bg-main-two-50">
    <div class="container container-lg">
        <div class="breadcrumb-wrapper flex-between flex-wrap gap-16">
            <h6 class="mb-0">Cart</h6>
            <ul class="flex-align gap-8 flex-wrap">
                <li class="text-sm">
                    <a href="index.php" class="text-gray-900 flex-align gap-8 hover-text-main-600">
                        <i class="ph ph-house"></i> Home
                    </a>
                </li>
                <li class="flex-align"><i class="ph ph-caret-right"></i></li>
                <li class="text-sm text-main-600">Product Cart</li>
            </ul>
        </div>
    </div>
</div>

<?php if (empty($cart)): ?>
    <section class="py-80">
        <div class="container container-lg text-center">
            <h4 class="mb-4">Your cart is empty.</h4>
            <a href="shop.php" class="btn btn-main">Continue Shopping</a>
        </div>
    </section>
<?php else: ?>
<section class="cart py-80">
    <div class="container container-lg">
        <div class="row gy-4">
            <div class="col-xl-9 col-lg-8">
                <div class="cart-table border border-gray-100 rounded-8 px-40 py-48">
                    <div class="overflow-x-auto scroll-sm scroll-sm-horizontal">
                        <table class="table style-three">
                            <thead>
                                <tr>
                                    <th>Delete</th>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart as $id => $item): 
                                    $qty = intval($item['qty']);
                                    $price = floatval($item['price']);
                                    $subtotal = $price * $qty;
                                    $total += $subtotal;
                                ?>
                                <tr>
                                    <td>
                                        <a href="remove_cart.php?id=<?= urlencode($id) ?>" 
                                           onclick="return confirm('Remove this item?')" 
                                           class="btn btn-sm"
                                           style="background-color:#ac5393; color:#fff;">
                                           <i class="ph ph-trash"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="table-product d-flex align-items-center gap-24">
                                            <a href="product-detail.php" class="table-product__thumb border border-gray-100 rounded-8 flex-center">
                                                <img src="../backend/pages/<?= htmlspecialchars($item['image']) ?>" alt="Product Image">
                                            </a>
                                            <div class="table-product__content text-start">
                                                <h6 class="title text-lg fw-semibold mb-8">
                                                    <a href="product-detail.php" class="link text-line-2" style="width:200px; height:70px;" ><?= htmlspecialchars($item['product_name']) ?></a>
                                                </h6>
                                                <div class="flex-align gap-16 mb-16">
                                                    <div class="flex-align gap-6">
                                                        <span class="text-md fw-semibold text-gray-900"><?= htmlspecialchars($item['attribute']) ?></span>
                                                    </div>
                                                    <span class="text-sm fw-medium text-gray-200">|</span>
                                                    <span class="text-neutral-600 text-sm"><?= htmlspecialchars($item['product_code']) ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="text-lg fw-semibold">₹<?= number_format($price, 2) ?></span></td>
                                    <td>
                                        <div class="d-flex rounded-4 overflow-hidden">
                                            <button type="button" class="quantity__minus border border-end border-gray-100 flex-shrink-0 h-48 w-48 text-neutral-600 flex-center">
                                                <i class="ph ph-minus"></i>
                                            </button>
                                            <input type="number" class="quantity__input flex-grow-1 border border-gray-100 border-start-0 border-end-0 text-center w-32 px-4"
                                                value="<?= $qty ?>" min="1" data-id="<?= $id ?>" max="<?= isset($item['max_qty']) ? intval($item['max_qty']) : 10 ?>">
                                            <button type="button" class="quantity__plus border border-end border-gray-100 flex-shrink-0 h-48 w-48 text-neutral-600 flex-center">
                                                <i class="ph ph-plus"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td><span class="text-lg fw-semibold subtotal">₹<?= number_format($subtotal, 2) ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4">
                <div class="cart-sidebar border border-gray-100 rounded-8 px-24 py-40">
                    <h6 class="text-xl mb-32">Cart Totals</h6>
                    <?php
                        $deliveryStart = date('d M, Y', strtotime('+4 days'));
                        $deliveryEnd = date('d M, Y', strtotime('+6 days'));

                        if ($total >= 1000) {
                            $shippingCharge = 0;
                        } else {
                            $shippingCharge = (strtolower($userState) === 'tamil nadu') ? 70 : 130;
                        }
                        $grandTotal = $total + $shippingCharge;
                    ?>
                    <div class="bg-color-three rounded-8 p-24">
                        <div class="mb-32 flex-between gap-8">
                            <span class="text-gray-900 font-heading-two">Subtotal</span>
                            <span class="cart-subtotal text-gray-900 fw-semibold">₹<?= number_format($total, 2) ?></span>
                        </div>
                        <div class="mb-32 flex-between gap-8">
                            <span class="text-gray-900 font-heading-two">Estimated Delivery</span>
                            <span class="text-gray-700 fw-semibold">Within <br>4 to 5 Days</span>
                        </div>
                        <div class="mb-32 flex-between gap-8">
                            <span class="text-gray-900 font-heading-two">Shipping</span>
                            <span class="text-gray-900 fw-semibold">
                                <?= $shippingCharge === 0 ? 'Free Shipping' : '₹' . number_format($shippingCharge, 2) ?>
                            </span>
                        </div>
                        <div class="mb-0 flex-between gap-8">
                            <span class="text-gray-900 font-heading-two">Estimated Tax</span>
                            <span class="text-gray-900 fw-semibold">Tax included</span>
                        </div>
                    </div>
                    <div class="bg-color-three rounded-8 p-24 mt-24">
                        <div class="flex-between gap-8">
                            <span class="text-gray-900 text-xl fw-semibold">Total</span>
                            <span class="total-amount text-gray-900 text-xl fw-semibold">₹<?= number_format($grandTotal, 2) ?></span>
                        </div>
                    </div>
                    
                    <?php if ($is_logged_in): ?>
                        <a href="checkout.php" class="btn btn-main mt-40 py-18 w-100 rounded-8">Proceed to Checkout</a>
                    <?php else: ?>
                        <a href="account.php?redirect=checkout.php" class="btn btn-main mt-40 py-18 w-100 rounded-8">Login to Checkout</a>
                        <div class="text-center mt-3">
                            <small class="text-muted">You need to login or register to complete your purchase</small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include("footer.php"); ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const updateTotals = () => {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(cell => {
            const amount = parseFloat(cell.textContent.replace(/[^\d.]/g, '')) || 0;
            total += amount;
        });

        let shipping = total >= 1000 ? 0 : ("<?= strtolower($userState) ?>" === "tamil nadu" ? 70 : 130);

        document.querySelector('.cart-subtotal').textContent = '₹' + total.toFixed(2);
        document.querySelector('.total-amount').textContent = '₹' + (total + shipping).toFixed(2);
    };

    document.querySelectorAll('.quantity__plus, .quantity__minus').forEach(button => {
        button.addEventListener('click', function () {
            const input = this.closest('div').querySelector('.quantity__input');
            let qty = parseInt(input.value, 10);
            const max = parseInt(input.getAttribute('max'), 10) || 99;

            if (this.classList.contains('quantity__plus') && qty < max) {
                qty++;
            } else if (this.classList.contains('quantity__minus') && qty > 1) {
                qty--;
            }

            input.value = qty;
            input.dispatchEvent(new Event('change'));
        });
    });

    document.querySelectorAll('.quantity__input').forEach(input => {
        input.addEventListener('change', function () {
            let qty = parseInt(this.value, 10);
            if (isNaN(qty) || qty < 1) qty = 1;
            this.value = qty;

            const row = this.closest('tr');
            const id = this.dataset.id;

            fetch('update_cart.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `id=${encodeURIComponent(id)}&qty=${qty}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    row.querySelector('.subtotal').textContent = '₹' + data.subtotal.toFixed(2);
                    updateTotals();
                } else {
                    alert(data.message || 'Could not update cart.');
                }
            });
        });
    });
});
</script>