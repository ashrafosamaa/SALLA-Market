<?php
  session_start();
	include_once ('includes/header.php');
	require_once 'app/connect.php';

  if (!isset($_GET['product_id']) || !filter_var($_GET['product_id'], FILTER_VALIDATE_INT)) {
    header('Location: products.php');
  }

  $id = $_GET['product_id'];
  $res = $mysql->query("SELECT * FROM products WHERE product_id = '$id'");

  $product = $res->fetch_assoc();

  if (!isset($product)) {
    echo "<div class='alert alert-error' style='margin: 10px 25px;'>No Data!</div>";
    exit;
  }

  
?>
<main id="view-product-page">
  <div class="product">
    <div class="left-item">
      <img src="<?= $product['product_image'] ?>" alt="product image">
    </div>
    <div class="right-item">
      <h1><?= $product['product_title'] ?></h1>
      <h3>$ <?= $product['product_price'] ?></h3>
      <ul>
        <li><span><i class="fa-solid fa-hashtag"></i> Model Number</span> <span><?= uniqid() ?></span></li>
        <li><span><i class="fa-solid fa-layer-group"></i> Category</span> <span><?= $product['category'] ?></span></li>
        <li><span><i class="fa-solid fa-code-branch"></i> Brand</span> <span><?= $product['brand'] ?></span></li>
        <li><span><i class="fa-solid fa-truck-arrow-right"></i> Shipping Fee</span> <span>$ <?= rand(1, 50) ?></span></li>
        <li><span><i class="fa-solid fa-percent"></i> Offer</span> <span><?= rand(10, 100) . '%' ?></span></li>
        <li><span><i class="fa-solid fa-palette"></i> Color</span> <span><?= $product['color'] ?></span></li>
        <li><span><i class="fa-solid fa-arrow-up-short-wide"></i> Size</span> <span><?= $product['size'] ?></span></li>
        <li><span><i class="fa-solid fa-list-ol"></i> Quantity</span> <span><?= $product['quantity'] ?> pieces</span></li>
      </ul>

    </div>
  </div>
</main>

<?php include_once ('includes/footer.php'); ?>
