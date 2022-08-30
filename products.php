<?php
  session_start();
  $page_title = 'Products';
	include_once ('includes/header.php');
	require_once 'app/connect.php';

  $query = '';

  if (!isset($_GET['search'])) {
    $query = 'SELECT * FROM products';
  } else {
    $search_value = $_GET['search'];
    $query = "SELECT * FROM products WHERE product_title LIKE  '%$search_value%'";
  }

  $result = $mysql->query($query);
  $products = $result->fetch_all();


?>

<main id="products-page">


  <div class="search">
    <h1>Products</h1>
    <form action="" method='get'>
      <i class="fa-solid fa-magnifying-glass"></i>
      <input type="text" placeholder="Search for something" name='search' value="<?php if (isset($_GET['search'])) { echo $_GET['search']; } ?>">
    </form>
  </div>

  <?php if (!empty($products)): ?>
    <div class="products">
      <?php foreach ($products as $product): ?>
        <div class="product">
          <img src="<?= $product[5] ?>" alt="">
          <div class="text">
          <h1><a href="view-product.php?product_id=<?= $product[0] ?>"><?= $product[1] ?></a></h1>
          <span class="text-success">$ <?= $product[4] ?></span>
          <p class='gray'><?= $product[7] ?></p>
          <p class='gray'><?= $product[8] ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  <?php else: ?>
    <div class="no-result"><i class="fa-solid fa-ban"></i> No Search Results</div>
  <?php endif; ?>

</main>

<?php include_once ('includes/footer.php'); ?>
