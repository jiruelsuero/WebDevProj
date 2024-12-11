<?php

require_once './db.php';

$imageBaseURL = "./assets/images/";

$query = "SELECT * FROM products";
$result = $conn->query($query);
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css" crossorigin="anonymous" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="./assets/styles/variables.css">
    <link rel="stylesheet" href="./assets/styles/default-styles.css">
    <link rel="stylesheet" href="./assets/styles/shops.css">
    <title>Cap It Off | Shop</title>
</head>

<body>
    <?php require './php/header.php'; ?>

    <main>
        <h1 class="shop-title">Shop Products</h1>

        <!-- Product Table -->
        <table class="product-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Details</th> <!-- New Column for Details Button -->
                </tr>
            </thead>

            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['product_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td>₱<?php echo number_format($row['price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                        <td>
                            <img src="<?php echo htmlspecialchars($imageBaseURL . $row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" width="100">
                        </td>

                        <td>
                            <!-- Corrected the link with proper product_id -->
                            <a href="product-detail.php?id=<?php echo urlencode($row['product_id']); ?>" class="details-button">
                                View Details
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>

    <?php require './php/footer.php'; ?>
    <script src="./js/cart.js"></script>
    <script src="./js/app.js"></script>

</body>
</html>

<?php
// Close database connection
$conn->close();
?>
