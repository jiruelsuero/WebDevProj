<?php
// Start the session to manage user login state
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css" integrity="sha384-HzLeBuhoNPvSl5KYnjx0BT+WB0QEEqLprO+NBkkk5gbc67FTaL7XIGa2w1L0Xbgc" crossorigin="anonymous" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/styles/variables.css">
    <link rel="stylesheet" href="./assets/styles/default-styles.css">
    <link rel="stylesheet" href="./assets/styles/styles.css" />
    
    <title>Cap It Off</title>
</head>

<body>
    <?php require './php/header.php'; ?>
    
    <main>
        <!-- Hero Section -->
        <section class="hero" id="home">
            <div class="hero__container">
                <h1 class="hero__heading">Cap your look, elevate your <span>vibe</span></h1>
                <p class="hero__description">Elevate every outfit with effortless style</p>
                <button class="hero__button"><a href="./shop.php">Explore</a></button>
            </div>
        </section>
        
        <!-- Main Section -->
        <section class="main-section" id="about">
            <div class="main__container">
                <div class="main__img--container">
                    <img src="assets/images/1.jfif" alt="..." />
                </div>
                <div class="main__content">
                    <h1>What do <span>we do?</span></h1>
                    <h2>Top off your style with the perfect fit! From cool baseball caps to sleek flat caps, cozy beanies, and bold snapbacks, we’ve got your headwear covered.</h2>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section class="services" id="services">
            <h1>Our Cap Section</h1>
            <div class="services__wrapper">
                <div class="services__card">
                    <h2>Baseball Caps</h2>
                    <p>Stay cool and comfortable on the field with our breathable Baseball Cap.</p>
                    <div class="services__btn">
                        <button id="shopButton"><a href="./shop.php">Get Now</a></button>
                    </div>
                </div>
                <div class="services__card">
                    <h2>Flat Caps</h2>
                    <p>Classic style, timeless appeal. Elevate your outfit with a sleek flat cap—where sophistication meets everyday wear.</p>
                    <div class="services__btn">
                        <button id="shopButton"><a href="./shop.php">Get Now</a></button>
                    </div>
                </div>
                <div class="services__card">
                    <h2>Snapbacks</h2>
                    <p>Bold looks, unbeatable fit. Turn heads with a snapback that speaks your style and personality.</p>
                    <div class="services__btn">
                        <button id="shopButton"><a href="./shop.php">Get Now</a></button>
                    </div>
                </div>
                <div class="services__card">
                    <h2>Beanies</h2>
                    <p>Stay warm, stay cool. Our cozy beanies blend comfort with effortless style for any season.</p>
                    <div class="services__btn">
                        <button id="shopButton"><a href="./shop.php">Get Now</a></button>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <?php require './php/footer.php'; ?>
    
    <script src="./js/cart.js"></script>
    <script src="./js/app.js"></script>
</body>

</html>
