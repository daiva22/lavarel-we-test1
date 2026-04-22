<!DOCTYPE html>
<html>
<head>
    <title>Wrapping - AUTOTECH</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div class="navbar">
    <div class="logo">
        <a href="{{ url('/#home') }}">AUTOTECH</a>
    </div>

    <div class="nav-links">
        <a href="{{ url('/') }}">Home</a>
        <a href="{{ url('/#shop') }}">Shop</a>
        <a href="{{ url('/#services') }}">Services</a>
        <a href="{{ url('/wrapping') }}" class="active">Wrapping</a>
        <a href="{{ url('/number-plate') }}">Number Plate</a>
    </div>

    <a href="{{ url('/cart') }}">🛒</a>
</div>

<section class="wrapping-section">
    <div class="wrapping-header">
        <h1>Car Wrapping</h1>
        <div class="wrapping-count">0 Products</div>
    </div>

    <div class="wrapping-grid" id="wrappingGrid"></div>
</section>

<script>
const wrappingProducts = [
    {
        name:"Gloss Black Wrap",
        description:"Premium glossy finish.",
        price:"Rs 12,000",
        image:"/images/wrap1.jpg"
    },
    {
        name:"Forged Carbon Wrap",
        description:"Sport luxury carbon texture.",
        price:"Rs 18,000",
        image:"/images/wrap2.jpg"
    }
];

const wrapGrid = document.getElementById("wrappingGrid");
const wrapCount = document.querySelector(".wrapping-count");

function renderWrapping(){
    wrapGrid.innerHTML="";
    wrappingProducts.forEach(p=>{
        wrapGrid.innerHTML+=`
            <div class="wrap-card">
                <div class="wrap-image">
                    <img src="${p.image}" alt="${p.name}">
                </div>
                <div class="wrap-content">
                    <div class="wrap-title">${p.name}</div>
                    <div class="wrap-description">${p.description}</div>
                    <div class="wrap-price">${p.price}</div>
                    <button class="wrap-btn" type="button">Add to Cart</button>
                </div>
            </div>
        `;
    });
    wrapCount.innerText = wrappingProducts.length + " Products";
}
renderWrapping();
</script>
<script src="/js/cart.js"></script>
</body>
</html>