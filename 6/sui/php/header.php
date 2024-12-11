<?php
// Start the session to manage user login state
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$isAdmin = false;


if(isset($_SESSION['user_id'])) {
    $isAdmin = $_SESSION['role'] === 'admin';
}
$logoLink = $isAdmin ? './manage-products.php' : './index.php';
echo '<script>var isAdmin = ' . ($isAdmin ? 'true' : 'false') . ';</script>';
?>

<header>
    <nav class="navbar">
        <div class="navbar__logo">
            <a href="<?php echo $logoLink; ?>">
                <span class="navbar__logo-icon"></span>
            </a>
        </div>
        <ul class="navbar__menu">
            <li class="navbar__item"><a href="index.php" class="navbar__links">Home</a></li>
            <li class="navbar__item"><a href="shop.php" class="navbar__links">Shop</a></li>
            <li class="navbar__item"><a href="cart.php" class="navbar__links">Cart</a></li>

            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                <li class="navbar__item"><a href="logout.php" class="navbar__links">Log Out</a></li>
                <li class="navbar__item"><span class="button">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span></li>
            <?php else: ?>
                <li class="navbar__item"><a href="log-in.php" class="navbar__links">Log In</a></li>
                <li class="navbar__btn"><a href="./register.php" class="button" id="signup">Sign Up</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
