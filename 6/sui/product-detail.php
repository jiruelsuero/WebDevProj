<?php
session_start();  // Ensure sessions are properly initialized
require './db.php';  // Database connection

$imageBaseURL = "./assets/images/"; 

// Check if 'id' is provided in URL and is a valid integer
if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $product_id = intval($_GET['id']);

    // Fetch product details
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        echo "Product not found.";
        exit;
    }

    // Fetch related products
    $related_stmt = $conn->prepare("SELECT * FROM products WHERE category = ? AND product_id != ? LIMIT 4");
    if (!$related_stmt) {
        die("Query preparation failed: " . $conn->error);
    }
    $related_stmt->bind_param("si", $product['category'], $product_id);
    $related_stmt->execute();
    $related_products = $related_stmt->get_result();

    // Fetch product reviews
    $review_stmt = $conn->prepare("SELECT * FROM reviews WHERE product_id = ?");
    if (!$review_stmt) {
        die("Query preparation failed: " . $conn->error);
    }
    $review_stmt->bind_param("i", $product_id);
    $review_stmt->execute();
    $reviews = $review_stmt->get_result();

    // Handle review submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rating'], $_POST['comment'])) {
        if (isset($_SESSION['username'])) {
            $rating = intval($_POST['rating']);
            $comment = trim($_POST['comment']);
            $username = $_SESSION['username'];

            if ($rating >= 1 && $rating <= 5 && !empty($comment)) {
                $review_stmt = $conn->prepare("INSERT INTO reviews (product_id, name, rating, comment) VALUES (?, ?, ?, ?)");
                if (!$review_stmt) {
                    die("Query preparation failed: " . $conn->error);
                }

                $review_stmt->bind_param("isis", $product_id, $username, $rating, $comment);
                if ($review_stmt->execute()) {
                    echo "<p>Thank you for your review!</p>";
                    header("Location: product-detail.php?id=" . $product_id);
                    exit;
                } else {
                    echo "<p>Error submitting your review. Please try again later.</p>";
                }
            } else {
                echo "<p>Please provide a valid rating and comment.</p>";
            }
        } else {
            echo "<p>You must be logged in to submit a review.</p>";
        }
    }
} else {
    echo "Invalid product ID.";
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css" crossorigin="anonymous" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="./assets/styles/variables.css">
    <link rel="stylesheet" href="./assets/styles/default-styles.css">
    <link rel="stylesheet" href="./assets/styles/shops.css">
    <title>Cap It Off | Product Details</title>
</head>
<body>
    <?php require './php/header.php'; ?>

    <main class="prod-details-container">
        <section id="prod-details" class="section-p1">
            <div class="sprod-image">
                <img src="<?php echo htmlspecialchars($imageBaseURL . $product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="main-img" width="100%">
            </div>
            <div class="sprod-details">
                <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                <h2>₱<?php echo number_format($product['price'], 2); ?></h2>
                <div class="quantity-container">
                    <button class="quantity__button quantity__button--subtract" id="subtractBtn">-</button>
                    <span id="quantity" class="quantity__number">1</span>
                    <button class="quantity__button quantity__button--add" id="addBtn">+</button>
                </div>
                <a href="cart.php?add=<?php echo urlencode($product['product_id']); ?>&quantity=1" id="addToCartBtn" class="button">Add to Cart</a>
                <h6 class="product-details-title">Product Details</h6>
                <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
            </div>
        </section>
    </main>

    <!-- Review Section -->
    <div class="review-section">
    <h6>Customer Reviews & Rating</h6>

    <?php
    $total_rating = 0;
    $total_reviews = $reviews->num_rows;

    if ($total_reviews > 0) {
        while ($review = $reviews->fetch_assoc()) {
            $total_rating += $review['rating'];
        }
        $average_rating = $total_rating / $total_reviews;
    } else {
        $average_rating = 0;
    }
    ?>

    <div class="average-rating">
        <span>Average Rating:</span>
        <div class="rating">
            <?php for ($i = 1; $i <= 5; $i++) {
                echo ($i <= round($average_rating)) ? '<span class="star">&#9733;</span>' : '<span class="star">&#9734;</span>';
            } ?>
            <span>(<?php echo number_format($average_rating, 1); ?>)</span>
        </div>
    </div>

    <div class="review-form">
        <form method="POST" action="">
            <span>Rate this product:</span>
            <div class="rating-input">
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" required>
                    <label for="star<?php echo $i; ?>">★</label>
                <?php endfor; ?>
            </div>
            <textarea name="comment" placeholder="Leave your comment here..." rows="4" required></textarea>
            <button type="submit" class="button submit-comment">Submit Comment</button>
        </form>
    </div>

    <div class="review-comments">
        <?php if ($reviews->num_rows > 0): ?>
            <?php
            $reviews->data_seek(0);
            while ($review = $reviews->fetch_assoc()): ?>
                <div class="review">
                    <strong><?php echo htmlspecialchars($review['name']); ?></strong>
                    <span class="review-date"> - <?php echo htmlspecialchars($review['date']); ?></span>
                    <div class="rating">
                        <?php for ($i = 1; $i <= 5; $i++):
                            echo $i <= $review['rating'] ? '<span class="star">&#9733;</span>' : '<span class="star">&#9734;</span>';
                        endfor; ?>
                    </div>
                    <p><?php echo htmlspecialchars($review['comment']); ?></p>

                    <!-- Delete button for admin -->
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <form method="POST" action="">
                            <input type="hidden" name="delete_review_id" value="<?php echo $review['id']; ?>">
                            <button type="submit" class="button delete-btn" onclick="return confirm('Are you sure you want to delete this review?');">Delete</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No reviews yet. Be the first to leave a review!</p>
        <?php endif; ?>
    </div>
</div>


    <section class="related-products">
    <h3>Related Products</h3>
    <?php if ($related_products->num_rows > 0): ?>
        <div class="small-product-grid">
            <?php while ($related = $related_products->fetch_assoc()): ?>
                <div class="small-product-item">
                    <img src="<?php echo htmlspecialchars($imageBaseURL . $related['image_url']); ?>" alt="<?php echo htmlspecialchars($related['name']); ?>" class="small-product-img">
                    <div class="small-product-info">
                        <h5><?php echo htmlspecialchars($related['name']); ?></h5>
                        <p>₱<?php echo number_format($related['price'], 2); ?></p>
                        <a href="product-detail.php?id=<?php echo $related['product_id']; ?>" class="small-view-btn">View</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No related products available.</p>
    <?php endif; ?>
</section>





    <?php require './php/footer.php'; ?>
    <script src="./js/cart.js"></script>
    <script src="./js/shops2.js"></script>
    <script src="./js/app.js"></script>
    <script src="js/quantity.js"></script>
</body>
</html>
