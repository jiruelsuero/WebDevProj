function editProduct(id, name, price, description, category, imageUrl) {
    // Prompt user for updated details
    const newName = prompt("Enter Product Name:", name) || name;
    const newPrice = prompt("Enter Product Price:", price) || price;
    const newDescription = prompt("Enter Product Description:", description) || description;
    const newCategory = prompt("Enter Product Category:", category) || category;
    const newImageUrl = prompt("Enter Image URL:", imageUrl) || imageUrl;

    // Create a hidden form to submit the data
    const form = document.createElement("form");
    form.method = "POST";
    form.action = "manage-products.php";

    // Add hidden inputs for the data
    form.innerHTML = `
        <input type="hidden" name="edit_product" value="true">
        <input type="hidden" name="product_id" value="${id}">
        <input type="hidden" name="name" value="${newName}">
        <input type="hidden" name="price" value="${newPrice}">
        <input type="hidden" name="description" value="${newDescription}">
        <input type="hidden" name="category" value="${newCategory}">
        <input type="hidden" name="image_url" value="${newImageUrl}">
    `;

    // Append the form to the body and submit it
    document.body.appendChild(form);
    form.submit();
}
