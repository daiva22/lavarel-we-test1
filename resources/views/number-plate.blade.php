<!DOCTYPE html>
<html>
<head>
    <title>Number Plate - AUTOTECH</title>
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
        <a href="{{ url('/wrapping') }}">Wrapping</a>
        <a href="{{ url('/number-plate') }}" class="active">Number Plate</a>
    </div>

    <a href="{{ url('/cart') }}" title="Cart">🛒</a>
</div>

<section class="plate-section">
    <div class="plate-header">
        <h1>Custom Number Plates</h1>
        <div class="plate-count">0 Designs</div>
    </div>

    <div class="plate-grid" id="plateGrid"></div>
</section>

<script>
const plateProducts = [
    {
        name:"Standard Plate",
        description:"Durable aluminium plate.",
        price:"Rs 1,200",
        image:"/images/plate1.jpg"
    },
    {
        name:"3D Gel Plate",
        description:"Raised gel lettering.",
        price:"Rs 2,000",
        image:"/images/plate2.jpg"
    }
];

const plateGrid = document.getElementById("plateGrid");
const plateCount = document.querySelector(".plate-count");

function renderPlates(){
    plateGrid.innerHTML="";
    plateProducts.forEach(p=>{
        plateGrid.innerHTML+=`
            <div class="plate-card">
                <div class="plate-image">
                    <img src="${p.image}" alt="${p.name}">
                </div>
                <div class="plate-content">
                    <div class="plate-title">${p.name}</div>
                    <div class="plate-description">${p.description}</div>
                    <div class="plate-price">${p.price}</div>
                    <button class="plate-btn" type="button">Add to Cart</button>
                </div>
            </div>
        `;
    });
    plateCount.innerText = plateProducts.length + " Designs";
}
renderPlates();
</script>


<script src="/js/cart.js"></script>
</body>
</html>