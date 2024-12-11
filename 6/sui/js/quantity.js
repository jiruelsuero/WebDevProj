// JavaScript for updating quantity and adding to cart

let quantity = 1; // Initial quantity

// Get DOM elements
const subtractBtn = document.getElementById('subtractBtn');
const addBtn = document.getElementById('addBtn');
const quantityDisplay = document.getElementById('quantity');
const addToCartBtn = document.getElementById('addToCartBtn');

// Subtract button
subtractBtn.addEventListener('click', function() {
    if (quantity > 1) {
        quantity--;
        quantityDisplay.textContent = quantity;
    }
});

// Add button
addBtn.addEventListener('click', function() {
    quantity++;
    quantityDisplay.textContent = quantity;
});

// Update the "Add to Cart" button with the current quantity
addToCartBtn.addEventListener('click', function(event) {
    // Append the current quantity to the "add to cart" URL
    const url = new URL(addToCartBtn.href);
    url.searchParams.set('quantity', quantity);
    addToCartBtn.href = url.toString(); // Update the href with new quantity
});
