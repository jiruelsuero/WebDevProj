<?php
session_start();
require './db.php';

$imageBaseURL = "./assets/images/";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Handle adding a product to the cart
if (isset($_GET['add']) && filter_var($_GET['add'], FILTER_VALIDATE_INT)) {
    if (!isset($_SESSION['username'])) {
        header("Location: log-in.php");
        exit;
    }

    $product_id = intval($_GET['add']);
    $quantity = isset($_GET['quantity']) && filter_var($_GET['quantity'], FILTER_VALIDATE_INT) ? intval($_GET['quantity']) : 1;
    $username = $_SESSION['username'];

    $stmt_user = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    $stmt_user->bind_param("s", $username);
    $stmt_user->execute();
    $user_result = $stmt_user->get_result();

    if ($user_result->num_rows > 0) {
        $user = $user_result->fetch_assoc();
        $user_id = $user['user_id'];

        $insert_stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $insert_stmt->bind_param("iii", $user_id, $product_id, $quantity);

        if ($insert_stmt->execute()) {
            echo "<p>Product added to the cart successfully!</p>";
        } else {
            echo "<p>Error adding product to the cart. Please try again.</p>";
        }
    } else {
        echo "<p>User not found. Please log in again.</p>";
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Handle removing a product from the cart
if (isset($_GET['remove']) && filter_var($_GET['remove'], FILTER_VALIDATE_INT)) {
    $product_id = intval($_GET['remove']);
    $username = $_SESSION['username'];

    // Fetch user_id from the database
    $stmt_user = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    $stmt_user->bind_param("s", $username);
    $stmt_user->execute();
    $user_result = $stmt_user->get_result();

    if ($user_result->num_rows > 0) {
        $user = $user_result->fetch_assoc();
        $user_id = $user['user_id'];

        // Delete product from the cart table in the database
        $delete_stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $delete_stmt->bind_param("ii", $user_id, $product_id);

        if ($delete_stmt->execute()) {
            echo "<p>Product removed from the cart successfully!</p>";
        } else {
            echo "<p>Error removing product from the cart. Please try again.</p>";
        }
    }

    // Remove product from the session cart array
    unset($_SESSION['cart'][$product_id]);
}

// Handle sorting criteria
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
$order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';

$cart_products = array();

if (count($_SESSION['cart']) > 0) {
    $product_ids = implode(",", array_keys($_SESSION['cart']));

    $query = "SELECT * FROM products WHERE product_id IN ($product_ids)";

    if ($sort === 'name') {
        $query .= " ORDER BY name $order";
    } elseif ($sort === 'price') {
        $query .= " ORDER BY price $order";
    }

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($product = $result->fetch_assoc()) {
            $cart_products[] = $product;
        }
    }
}

// Calculate the total price
$total_price = 0;
foreach ($cart_products as $product) {
    $total_price += $product['price'] * $_SESSION['cart'][$product['product_id']];
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/styles/variables.css">
    <link rel="stylesheet" href="./assets/styles/default-styles.css">
    <link rel="stylesheet" href="./assets/styles/shops.css">
    <title>Cap It Off | Your Cart</title>
</head>

<body>

    <?php require './php/header.php'; ?>

    <main>
        <h1 class="shop-title">Your Cart</h1>

        <table class="product-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php if (count($cart_products) > 0): ?>
                    <?php foreach ($cart_products as $product): ?>
                        <tr>
                            <td>
                                <img src="<?php echo htmlspecialchars($imageBaseURL . $product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" width="100">
                            </td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td>₱<?php echo number_format($product['price'], 2); ?></td>
                            <td><?php echo $_SESSION['cart'][$product['product_id']]; ?></td>
                            <td>₱<?php echo number_format($product['price'] * $_SESSION['cart'][$product['product_id']], 2); ?></td>
                            <td>
                                <a href="cart.php?remove=<?php echo $product['product_id']; ?>" class="remove-btn">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="empty-cart-message">Your cart is empty. Start shopping now!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if (count($cart_products) > 0): ?>
            <div class="cart-summary">
                <h2>Total Price: ₱<?php echo number_format($total_price, 2); ?></h2>
                <a href="shop.php" class="checkout-btn">Add More Products</a>
            </div>
        <?php endif; ?>
    </main>

    <script src="./js/cart.js"></script>

    <?php require './php/footer.php'; ?>

</body>
</html>
