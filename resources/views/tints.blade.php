<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tints - AUTOTECH</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">
        <a href="{{ url('/#home') }}">AUTOTECH</a>
    </div>

    <div class="nav-links">
        <a href="{{ url('/') }}">Home</a>
        <a href="{{ url('/#shop') }}">Shop</a>
        <a href="{{ url('/#services') }}">Services</a>
        <a href="{{ url('/booking') }}">Booking</a>
        <a href="{{ url('/tints') }}" class="active">Tints</a>
    </div>

    <a href="{{ url('/cart') }}" title="Cart">🛒</a>
</div>

<!-- TINTS SECTION -->
<section class="tints-section">

    <div class="tints-header">
        <h1>Window Tints</h1>
        <div class="tints-count">0 Products</div>
    </div>

    <div class="tints-filters">
        <select>
            <option>All Types</option>
            <option>Ceramic</option>
            <option>Carbon</option>
            <option>Dyed</option>
        </select>

        <input type="text" placeholder="Search tints...">
    </div>

    <div class="tints-grid" id="tintsGrid">
        <!-- Products will be injected here by JS later -->
    </div>

</section>

<script>
/* ===== DEMO DATA (Frontend Only) ===== */
const products = [
    {
        name: "Ceramic Tint 5%",
        description: "High heat rejection, premium ceramic film.",
        price: "Rs 4,500",
        image: "/images/tint1.jpg"
    },
    {
        name: "Carbon Tint 15%",
        description: "Fade resistant carbon technology.",
        price: "Rs 3,800",
        image: "/images/tint2.jpg"
    }
];

/* ===== RENDER PRODUCTS ===== */
const grid = document.getElementById("tintsGrid");
const count = document.querySelector(".tints-count");

function renderProducts(){
    grid.innerHTML = "";
    products.forEach(product => {
        grid.innerHTML += `
            <div class="tint-card">
                <div class="tint-image">
                    <img src="${product.image}" alt="${product.name}">
                </div>
                <div class="tint-content">
                    <div class="tint-title">${product.name}</div>
                    <div class="tint-description">${product.description}</div>
                    <div class="tint-price">${product.price}</div>
                    <button class="tint-btn" type="button">Add to Cart</button>
                </div>
            </div>
        `;
    });
    count.innerText = products.length + " Products";
}

renderProducts();
</script>
<script src="/js/cart.js"></script>
</body>
</html>