<?php
session_start();
require './db.php';

// Handle adding a new product
if (isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $price = $_POST['price'];
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $image_url = trim($_POST['image_url']);

    $insert_product = $conn->prepare("
        INSERT INTO products (name, price, description, category, image_url) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $insert_product->bind_param("sdsss", $name, $price, $description, $category, $image_url);

    if ($insert_product->execute()) {
        echo "<p style='color: green; font-weight: bold;'>New product added successfully!</p>";
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'manage-products.php';
                }, 2000);
              </script>";
        exit();
    } else {
        echo "Failed to add product: " . $conn->error;
    }
}

// Handle editing a product
if (isset($_POST['edit_product'])) {
    $product_id = $_POST['product_id'];
    $name = trim($_POST['name']);
    $price = $_POST['price'];
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $image_url = trim($_POST['image_url']);

    $update_product = $conn->prepare("
        UPDATE products 
        SET name = ?, price = ?, description = ?, category = ?, image_url = ?
        WHERE product_id = ?
    ");
    $update_product->bind_param("sdsssi", $name, $price, $description, $category, $image_url, $product_id);

    if ($update_product->execute()) {
        echo "<p style='color: green; font-weight: bold;'>Product updated successfully!</p>";
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'manage-products.php';
                }, 2000);
              </script>";
        exit();
    } else {
        echo "Failed to update product: " . $conn->error;
    }
}

// Handle deleting a product
if (isset($_GET['delete'])) {
    $product_id = intval($_GET['delete']);
    $delete_product = $conn->prepare("DELETE FROM products WHERE product_id = ?");
    $delete_product->bind_param("i", $product_id);

    if ($delete_product->execute()) {
        echo "<p style='color: green; font-weight: bold;'>Product deleted successfully!</p>";
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'manage-products.php';
                }, 2000);
              </script>";
        exit();
    } else {
        echo "Failed to delete product: " . $conn->error;
    }
}

// Fetch products to display
$products_query = $conn->prepare("SELECT * FROM products");
$products_query->execute();
$products_result = $products_query->get_result();

// Fetch product details for editing
$edit_product = null;
if (isset($_GET['edit'])) {
    $product_id = intval($_GET['edit']);
    $product_query = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $product_query->bind_param("i", $product_id);
    $product_query->execute();
    $edit_product = $product_query->get_result()->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/styles/variables.css">
    <link rel="stylesheet" href="./assets/styles/default-styles.css">
    <link rel="stylesheet" href="./assets/styles/shops.css">
    <title>Manage Products</title>
</head>

<body>
    <?php require './php/header.php'; ?>

    <main class="manage-products">
        <h1>Manage Products</h1>

        <!-- Form to Add New Product -->
        <?php if (!$edit_product): ?>
        <h2>Add New Product</h2>
        <form method="POST" action="manage-products.php">
            <input type="text" name="name" placeholder="Product Name" required>
            <input type="number" name="price" placeholder="Price" step="0.01" required>
            <textarea name="description" placeholder="Description" required></textarea>
            <select name="category" required>
                <option value="Baseball">Baseball</option>
                <option value="Flat Caps">Flat Caps</option>
                <option value="Snapback">Snapback</option>
                <option value="Beanie">Beanie</option>
            </select>
            <input type="text" name="image_url" placeholder="Image URL" required>
            <button type="submit" name="add_product">Add Product</button>
        </form>
        <?php endif; ?>

        <!-- Form to Edit Product -->
        <?php if ($edit_product): ?>
        <h2>Edit Product</h2>
        <form method="POST" action="manage-products.php">
            <input type="hidden" name="edit_product" value="1">
            <input type="hidden" name="product_id" value="<?php echo $edit_product['product_id']; ?>">
            <input type="text" name="name" value="<?php echo htmlspecialchars($edit_product['name']); ?>" required>
            <input type="number" name="price" value="<?php echo $edit_product['price']; ?>" step="0.01" required>
            <textarea name="description" required><?php echo htmlspecialchars($edit_product['description']); ?></textarea>
            <select name="category" required>
                <option value="Baseball" <?php echo $edit_product['category'] === 'Baseball' ? 'selected' : ''; ?>>Baseball</option>
                <option value="Flat Caps" <?php echo $edit_product['category'] === 'Flat Caps' ? 'selected' : ''; ?>>Flat Caps</option>
                <option value="Snapback" <?php echo $edit_product['category'] === 'Snapback' ? 'selected' : ''; ?>>Snapback</option>
                <option value="Beanie" <?php echo $edit_product['category'] === 'Beanie' ? 'selected' : ''; ?>>Beanie</option>
            </select>
            <input type="text" name="image_url" value="<?php echo htmlspecialchars($edit_product['image_url']); ?>" required>
            <button type="submit">Save Changes</button>
        </form>
        <?php endif; ?>

        <!-- Existing Products Table -->
        <h2>Existing Products</h2>
        <table class="product-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product = $products_result->fetch_assoc()): ?>
                    <tr>
                        <td><img src="<?php echo htmlspecialchars("./assets/images/" . $product['image_url']); ?>" alt="<?php echo $product['name']; ?>" width="100"></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td>₱<?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($product['category']); ?></td>
                        <td><?php echo htmlspecialchars($product['description']); ?></td>
                        <td>
                            <a href="manage-products.php?delete=<?php echo $product['product_id']; ?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
                            |
                            <a href="manage-products.php?edit=<?php echo $product['product_id']; ?>">Edit</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>

    <?php require './php/footer.php'; ?>
</body>
</html>
