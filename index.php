<?php
session_start(); 
require_once('link.php');

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}



if (isset($_GET['category'])) {
    $sql = "SELECT id, name, price FROM `products` WHERE category = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $_GET['category']);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT `id`, `name`, `price` FROM `products`";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
}

require_once('template/head.php');
?>

<a href="product.php?item=<?= $id ?>" class="btn btn-primary">View Details</a>

<div class="container mb-4">
    <div class="row">
        <div class="col-md-12">
            <h3>Shopping Cart</h3>
            <?php if (!empty($_SESSION['cart'])): ?>
                <form method="post">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $cart_total = 0;
                            foreach ($_SESSION['cart'] as $item): 
                                $item_total = $item['price'] * $item['quantity'];
                                $cart_total += $item_total;
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['name']) ?></td>
                                    <td>$<?= number_format($item['price'], 2) ?></td>
                                    <td>
                                        <input type="number" name="quantity[<?= $item['id'] ?>]" 
                                               value="<?= $item['quantity'] ?>" min="1" class="form-control" style="width: 70px;">
                                    </td>
                                    <td>$<?= number_format($item_total, 2) ?></td>
                                    <td>
                                        <a href="?remove_from_cart=<?= $item['id'] ?>" class="btn btn-danger btn-sm">Remove</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                <td><strong>$<?= number_format($cart_total, 2) ?></strong></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="submit" name="update_quantity" class="btn btn-primary">Update Cart</button>
                    <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
                </form>
            <?php else: ?>
                <p>Your cart is empty.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <?php foreach($result as $product): 
            $name = htmlspecialchars($product['name']);
            $id = $product['id'];
            $price = number_format($product['price'], 2);
        ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= $name ?></h5>
                        <p class="card-text">Price: $<?= $price ?></p>
                        <a href="product.php?item=<?= $id ?>" class="btn btn-primary">View Details</a>
                        <a href="?add_to_cart=<?= $id ?>" class="btn btn-success">Add to Cart</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="container mb-4">
    <div class="row">
        <div class="col-md-12">
            <h4>Filter by Category:</h4>
            <div class="btn-group" role="group">
                <a href="index.php" class="btn btn-outline-primary">All</a>
                <a href="index.php?category=наушники" class="btn btn-outline-primary">Наушники</a>
                <a href="index.php?category=телефоны" class="btn btn-outline-primary">Телефоны</a>
                <a href="index.php?category=ноутбуки" class="btn btn-outline-primary">Ноутбуки</a>
            </div>
        </div>
    </div>
</div>

<?php
include_once('template/footer.php');
?>