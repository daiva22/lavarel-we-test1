<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>AUTOTECH</title>

  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <style>
    .service-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 20px;
      margin-top: 30px;
    }

    .service-category-card {
      display: block;
      text-decoration: none;
      color: inherit;
      border-radius: 14px;
      overflow: hidden;
      background: #111;
      box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
      transition: transform 0.2s ease;
    }

    .service-category-card:hover {
      transform: translateY(-4px);
    }

    .service-category-image {
      width: 100%;
      height: 220px;
      object-fit: cover;
      display: block;
      background: #ddd;
    }

    .service-category-no-image {
      width: 100%;
      height: 220px;
      background: #222;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-size: 18px;
      font-weight: bold;
      text-align: center;
      padding: 20px;
    }

    .service-category-label {
      padding: 16px;
      text-align: center;
      font-size: 20px;
      font-weight: bold;
      color: #fff;
      background: #111;
    }
  </style>
</head>

<body>

  <nav class="navbar">
    <div class="nav-left">
      <a class="logo" href="{{ url('/#home') }}">AUTOTECH</a>
    </div>

    <div class="nav-center">
      <ul class="nav-links">
        <li><a href="{{ url('/#home') }}" class="nav-home">Home</a></li>
        <li><a href="{{ url('/#shop') }}">Shop</a></li>
        <li><a href="{{ url('/#services') }}">Services</a></li>
        <li><a href="{{ url('/booking') }}">Booking</a></li>
        <li><a href="{{ url('/reviews') }}">Reviews</a></li>
      </ul>
    </div>

    <div class="nav-right nav-icons">
      <a href="{{ url('/account') }}" title="Account">👤</a>
      <a href="{{ url('/cart') }}" title="Cart">🛒</a>
    </div>
  </nav>

  <section class="full-bg" id="home">
    <div class="section-content hero-content">
      <h3>ABOUT US</h3>
      <h1>
        At AUTOTECH, we make cars louder,<br />
        sleeker, and better.
        From powerful speakers to tinted
        windows, custom stickers, and advanced scans - we've got your
        ride covered.
      </h1>
    </div>
  </section>

  <section class="shop-hero" id="shop">
    <h1 class="shop-title">Shop</h1>

    <div class="shop-cards">
      <a href="{{ url('/sound_system') }}" class="shop-card">
        <img src="{{ asset('images/sound-system.png') }}" alt="Sound System">
        <div class="card-label">Sound System</div>
      </a>

      <a href="{{ url('/accessories') }}" class="shop-card">
        <img src="{{ asset('images/Accessories.png') }}" alt="Accessories">
        <div class="card-label">Accessories</div>
        
      </a>

      <a href="{{ url('/packages') }}" class="shop-card">
        <img src="{{ asset('images/Packages.png') }}" alt="Packages">
        <div class="card-label">Packages</div>
      </a>
    </div>
  </section>

  <section class="services-hero" id="services">
    <h2 class="section-title">Services</h2>

    <div class="service-cards">
      @forelse($serviceCategories as $category)
        <a href="{{ route('services.front.category', $category->id) }}" class="service-category-card">
          @if($category->image)
            <img
              src="{{ asset($category->image) }}"
              alt="{{ $category->name }}"
              class="service-category-image"
            >
          @else
            <div class="service-category-no-image">
              {{ $category->name }}
            </div>
          @endif

          <div class="service-category-label">
            {{ $category->name }}
          </div>
        </a>
      @empty
        <p style="text-align: center; width: 100%;">No services available right now.</p>
      @endforelse
    </div>
  </section>

  <section class="contact-section" id="contact">
    <div class="contact-left">
      <h2>CONTACT US</h2>
      <p>For any query contact us using the details below.</p>

      <div class="contact-info">
        <span>Phone: XXXXXXXX</span>
        <span>Email: xxxxxxxxx@gmail.com</span>
        <span>Address: XXXXXXX</span>
        <span>Opening hours: 09:00 – 18:00</span>
      </div>
    </div>

    <div class="social-icons">
      <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" title="Instagram">
        <i class="fa-brands fa-instagram"></i>
      </a>
      <a href="https://tiktok.com" target="_blank" rel="noopener noreferrer" title="TikTok">
        <i class="fa-brands fa-tiktok"></i>
      </a>
      <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" title="YouTube">
        <i class="fa-brands fa-youtube"></i>
      </a>
    </div>
  </section>

  <script>
    const sections = document.querySelectorAll("section[id]");
    const navLinks = document.querySelectorAll(".nav-links a");

    window.addEventListener("scroll", () => {
      let currentId = "";

      sections.forEach(section => {
        const sectionTop = section.offsetTop - 120;
        if (window.scrollY >= sectionTop) currentId = section.id;
      });

      navLinks.forEach(link => {
        link.classList.remove("active");
        const href = link.getAttribute("href");
        const hash = href && href.includes("#") ? href.split("#")[1] : "";
        if (hash && hash === currentId) link.classList.add("active");
      });
    });

    const currentPath = window.location.pathname.replace(/\/+$/, "");
    navLinks.forEach(link => {
      const href = link.getAttribute("href");
      if (!href) return;

      if (href.includes("#")) return;

      const linkPath = new URL(href, window.location.origin).pathname.replace(/\/+$/, "");
      if (linkPath && linkPath === currentPath && currentPath !== "") {
        link.classList.add("active");
      }
    });
  </script>

  <script src="/js/cart.js"></script>
</body>
</html>