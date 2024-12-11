<div class="review-section">
    <h6>Customer Reviews & Rating</h6>

    <!-- Form to Submit a Review -->
    <div class="review-form">
        <span>Rate this product:</span>
        <form action="./action/submitReview.php" method="POST">
            <!-- Pass the product ID securely -->
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">

            <!-- Star Rating Input -->
            <div class="rating-input">
                <input type="radio" id="star5" name="rating" value="5" required>
                <label for="star5" title="5 stars">★</label>
                <input type="radio" id="star4" name="rating" value="4">
                <label for="star4" title="4 stars">★</label>
                <input type="radio" id="star3" name="rating" value="3">
                <label for="star3" title="3 stars">★</label>
                <input type="radio" id="star2" name="rating" value="2">
                <label for="star2" title="2 stars">★</label>
                <input type="radio" id="star1" name="rating" value="1">
                <label for="star1" title="1 star">★</label>
            </div>

            <!-- Comment Input -->
            <textarea name="comment" placeholder="Leave your comment here..." rows="4" required></textarea>

            <!-- Submit Button -->
            <button type="submit" class="button submit-comment">Submit Review</button>
        </form>
    </div>

    <!-- Display Existing Comments -->
    <div class="review-comments">
        <h6>Customer Reviews</h6>
        <?php
        // Fetch reviews for the product
        $reviews_query = "
            SELECT r.rating, r.comment, r.created_at, u.username 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE r.product_id = ?";
        $reviews_stmt = $conn->prepare($reviews_query);
        $reviews_stmt->bind_param("i", $product['product_id']);
        $reviews_stmt->execute();
        $reviews_result = $reviews_stmt->get_result();

        // Display fetched reviews
        if ($reviews_result->num_rows > 0): 
            while ($review = $reviews_result->fetch_assoc()): ?>
                <div class="review">
                    <strong><?php echo htmlspecialchars($review['username']); ?></strong>
                    <span class="review-date"> - <?php echo htmlspecialchars(date("F j, Y", strtotime($review['created_at']))); ?></span>
                    <div class="rating">
                        <?php
                        for ($i = 0; $i < 5; $i++) {
                            echo $i < $review['rating'] ? '<span class="star">&#9733;</span>' : '<span class="star">&#9734;</span>';
                        }
                        ?>
                    </div>
                    <p><?php echo htmlspecialchars($review['comment']); ?></p>
                </div>
            <?php endwhile; 
        else: ?>
            <p>No reviews yet. Be the first to review!</p>
        <?php endif; ?>
    </div>
</div>
