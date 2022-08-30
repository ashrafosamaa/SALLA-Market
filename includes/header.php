<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title><?php if (isset($page_title)) { echo $page_title; } else { echo "Team App"; } ?></title>
    <link rel="stylesheet" href="includes/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

  <nav id="navbar">
    <h1 id="heading-title"><a href="index.php"><i class="fa-solid fa-shop"></i>  SALLA</a></h1>      
    <ul>
      <li><a href="products.php"><i class="fa-solid fa-store"></i>Products</a></li>
      <?php if (!isset($_SESSION['uid']) || !isset($_COOKIE['c_uid'])): ?>
        <li><a href="login.php"><i class="fa-solid fa-arrow-right-to-bracket"></i> Login</a></li>
        <li><a href="register.php"><i class="fa-solid fa-user-plus"></i> Register</a></li>
      <?php else: ?>
        <li><a href="add-product.php"><i class="fa-solid fa-cart-plus"></i> Sell Product</a></li>
        <li><a href="profile.php"><i class="fa-solid fa-user"></i>Profile</a></li>
      <?php endif; ?>
    </ul>
  </nav>

