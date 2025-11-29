<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= $title ?? 'Uling Shop' ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- ✅ Bootstrap & Icons (no integrity attributes) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <!-- Owl Carousel -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" rel="stylesheet">

  <style>
    :root {
      --charcoal-dark: #2b2b2b;
      --charcoal-light: #3f3f3f;
      --ash-gray: #b0b0b0;
      --accent: #ff914d;
      --accent-hover: #ffb36a;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f3f4f6;
      color: #222;
    }

    /* Navbar */
    .navbar {
      background-color: var(--charcoal-dark) !important;
    }
    .navbar-brand {
      color: var(--accent) !important;
      font-weight: 700;
      letter-spacing: 0.5px;
    }
    .nav-link {
      color: #f0f0f0 !important;
      font-weight: 500;
      margin-right: 10px;
      display: flex;
      align-items: center;
      gap: 5px;
    }
    .nav-link:hover {
      color: var(--accent) !important;
    }

    /* Hero */
    .hero {
      background: linear-gradient(90deg, var(--charcoal-dark), var(--charcoal-light));
      color: white;
      padding: 70px 0;
      text-align: center;
      margin-bottom: 40px;
    }

    /* Cards */
    .card {
      border: none;
      border-radius: 10px;
      transition: all 0.3s ease-in-out;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .card:hover {
      transform: translateY(-6px);
      box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    }
    .card img {
      height: 200px;
      object-fit: cover;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
    }

    /* Buttons */
    .btn-primary {
      background-color: var(--accent);
      border: none;
    }
    .btn-primary:hover {
      background-color: var(--accent-hover);
    }

    /* Footer */
    footer {
      background-color: var(--charcoal-dark);
      color: white;
      padding: 25px 0;
      text-align: center;
      margin-top: 60px;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="/"><i class="fa-solid fa-fire"></i> Uling Shop</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="/"><i class="fa-solid fa-store"></i> Shop</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"><i class="fa-solid fa-layer-group"></i> Categories</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/category/Hardwood%20Charcoal">Hardwood Charcoal</a></li>
            <li><a class="dropdown-item" href="/category/Coconut%20Charcoal">Coconut Charcoal</a></li>
            <li><a class="dropdown-item" href="/category/Mixed%20Charcoal">Mixed Charcoal</a></li>
            <li><a class="dropdown-item" href="/category/Normal%20Charcoal">Normal Charcoal</a></li>
          </ul>
        </li>
      </ul>

      <ul class="navbar-nav ms-auto">
        <?php if(session()->get('logged_in')): ?>
          <li class="nav-item"><a class="nav-link" href="#"><i class="fa-solid fa-user"></i> <?= esc(session()->get('user_name')) ?></a></li>
          <li class="nav-item"><a class="nav-link" href="/cart"><i class="fa-solid fa-cart-shopping"></i> Cart</a></li>
          <li class="nav-item"><a class="nav-link" href="/auth/logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="/auth/login"><i class="fa-solid fa-right-to-bracket"></i> Login</a></li>
          <li class="nav-item"><a class="nav-link" href="/auth/register"><i class="fa-solid fa-user-plus"></i> Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>






<!-- ✅ Scripts (clean, correct order, no integrity) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<script>
  $(document).ready(function(){
    $(".owl-carousel").owlCarousel({
      loop:true,
      margin:15,
      autoplay:true,
      autoplayTimeout:3000,
      dots: true,
      responsive:{
        0:{items:1},
        576:{items:2},
        768:{items:3},
        1200:{items:4}
      }
    });
  });

  console.log("✅ Bootstrap JS loaded properly.");
</script>

</body>
</html>
