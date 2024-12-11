<?php
session_start();
require './db.php';

$imageBaseURL = "./assets/images/";

if (!isset($_SESSION['reservations'])) {
    $_SESSION['reservations'] = array();
}

// Handle adding a product to reservations from the cart
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        if (!isset($_SESSION['reservations'][$product_id])) {
            $_SESSION['reservations'][$product_id] = $quantity;
        } else {
            $_SESSION['reservations'][$product_id] += $quantity;
        }

        // Save to the database
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];

            // Retrieve user_id based on username
            $stmt_user = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
            $stmt_user->bind_param("s", $username);
            $stmt_user->execute();
            $user_result = $stmt_user->get_result();

            if ($user_result->num_rows > 0) {
                $user = $user_result->fetch_assoc();
                $user_id = $user['user_id'];

                // Check if the reservation already exists
                $check_stmt = $conn->prepare("SELECT * FROM reservations WHERE user_id = ? AND product_id = ?");
                $check_stmt->bind_param("ii", $user_id, $product_id);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();

                if ($check_result->num_rows > 0) {
                    // Update the quantity if reservation already exists
                    $update_stmt = $conn->prepare("UPDATE reservations SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
                    $update_stmt->bind_param("iii", $quantity, $user_id, $product_id);
                    $update_stmt->execute();
                } else {
                    // Insert a new reservation
                    $insert_stmt = $conn->prepare("INSERT INTO reservations (user_id, product_id, quantity) VALUES (?, ?, ?)");
                    $insert_stmt->bind_param("iii", $user_id, $product_id, $quantity);
                    $insert_stmt->execute();
                }
            }
        }
    }
    $_SESSION['cart'] = array(); // Clear the cart after transferring to reservations
}

// Handle cancel action
if (isset($_GET['cancel']) && isset($_SESSION['username'])) {
    $product_id = intval($_GET['cancel']);
    $username = $_SESSION['username'];

    // Retrieve user_id based on username
    $stmt_user = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    $stmt_user->bind_param("s", $username);
    $stmt_user->execute();
    $user_result = $stmt_user->get_result();

    if ($user_result->num_rows > 0) {
        $user = $user_result->fetch_assoc();
        $user_id = $user['user_id'];

        // Remove the reservation from the database
        $delete_stmt = $conn->prepare("DELETE FROM reservations WHERE user_id = ? AND product_id = ?");
        $delete_stmt->bind_param("ii", $user_id, $product_id);
        $delete_stmt->execute();

        // Remove it from the session reservations
        if (isset($_SESSION['reservations'][$product_id])) {
            unset($_SESSION['reservations'][$product_id]);
        }
    }
}

$reservation_products = array();

if (count($_SESSION['reservations']) > 0) {
    $product_ids = implode(",", array_keys($_SESSION['reservations']));

    $query = "SELECT * FROM products WHERE product_id IN ($product_ids)";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($product = $result->fetch_assoc()) {
            $reservation_products[] = $product;
        }
    }
}

// Calculate the total price
$total_price = 0;
foreach ($reservation_products as $product) {
    $total_price += $product['price'] * $_SESSION['reservations'][$product['product_id']];
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/styles/variables.css">
    <link rel="stylesheet" href="./assets/styles/default-styles.css">
    <link rel="stylesheet" href="./assets/styles/shops.css">
    <title>Cap It Off | Your Reservations</title>
</head>

<body>

    <?php require './php/header.php'; ?>

    <main>
        <h1 class="shop-title">Your Reservations</h1>

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
                <?php if (count($reservation_products) > 0): ?>
                    <?php foreach ($reservation_products as $product): ?>
                        <tr>
                            <td>
                                <img src="<?php echo htmlspecialchars($imageBaseURL . $product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" width="100">
                            </td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td>₱<?php echo number_format($product['price'], 2); ?></td>
                            <td><?php echo $_SESSION['reservations'][$product['product_id']]; ?></td>
                            <td>₱<?php echo number_format($product['price'] * $_SESSION['reservations'][$product['product_id']], 2); ?></td>
                            <td>
                                <a href="reservation.php?cancel=<?php echo $product['product_id']; ?>" class="remove-btn">Cancel</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="empty-cart-message">You have no reservations. Start shopping now!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if (count($reservation_products) > 0): ?>
            <div class="cart-summary">
                <h2>Total Price: ₱<?php echo number_format($total_price, 2); ?></h2>
                <a href="shop.php" class="checkout-btn">Reserve More Products</a>
            </div>
        <?php endif; ?>
    </main>

    <script src="./js/cart.js"></script>

    <?php require './php/footer.php'; ?>

</body>
</html>
