<?php
	session_start();
	$page_title = 'Add Product';
	include_once ('includes/header.php');
	require_once 'app/connect.php';
	
	if (!isset($_SESSION['uid']) || !isset($_COOKIE['c_uid'])) {
    header('Location: login.php');
  }

	

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$title = filter_var($_POST['title'], FILTER_SANITIZE_SPECIAL_CHARS);
		$price = filter_var($_POST['price'], FILTER_SANITIZE_SPECIAL_CHARS);
		$size = filter_var($_POST['size'], FILTER_SANITIZE_SPECIAL_CHARS);
		$brand = filter_var($_POST['brand'], FILTER_SANITIZE_SPECIAL_CHARS);
		$category = filter_var($_POST['category'], FILTER_SANITIZE_SPECIAL_CHARS);
		$qty = $_POST['quantity'];
		$color = filter_var($_POST['color'], FILTER_SANITIZE_SPECIAL_CHARS);
		$image = $_FILES['image'];

		$errors = [];
		$allowed_extensions = ['image/jpg', 'image/png', 'image/jpeg'];

		$target_file = UPLOAD_TARGET . time() . '_' . uniqid() . basename($image['name']);
		$allow_upload = true;

		if ($image['size'] > 50000000) {
			$errors[] = 'Image must be less than 5 megabyte';
			$allow_upload = false;
		}
		if (!in_array($image['type'], $allowed_extensions)) {
			$errors[] = 'This file is not allowed';
			$allow_upload = false;
		}

		if ($title == "") {
			$errors[] = 'Title Cannot be empty';
		}

		if (empty($price)) {
			$errors[] =  'Price Cannot be empty';
		}
		if (!empty($price) && !filter_var($price, FILTER_VALIDATE_INT)) {
			$errors[] = "Price must be number";
		}

		if (empty($qty)) {
			$errors[] = 'Quantity Cannot be empty';
		}
		if (!empty($qty) && !filter_var($qty, FILTER_VALIDATE_INT)) {
			$errors[] = 'Quantity must be number';
		}

		if ($color == "") {
			$errors[] = 'Color Cannot be empty';
		}

		if ($image == "") {
			$errors[] = 'Image Cannot be empty';
		}

		if ($brand == "") {
			$errors[] = 'Brand Cannot be empty';
		}

		if ($category == "") {
			$errors[] = 'Category Cannot be empty';
		}


		if (empty($errors)) {
			move_uploaded_file($image['tmp_name'], $target_file);
			$result = $mysql->query("
				INSERT INTO products(product_title, product_price, color, size, quantity, product_image, category, brand)
				VALUES('$title', '$price', '$color', '$size', '$qty', '$target_file', '$category', '$brand')
			");
			if ($result) {
				echo "<div class='alert alert-success'><i class='fa-solid fa-circle-check'></i> Product has been added successfully!</div>";
				sleep(2);
				header('Location: products.php');
			} else {
				echo "<div class='alert alert-error'>Cannot added product!</div>";
			}
		}
	}

?>

<main id="add-product-page">

	<?php
		if (!empty($errors)) {
			foreach ($errors as $error) {
				echo "<div class='alert-error alert'><i class='fa-solid fa-circle-exclamation'></i> " . $error . "</div>";
			}
		}
	?>

	<h1>Add New Product</h1>

	<form action="" method="post" enctype="multipart/form-data">

		<div class="input">
			<label for="">Product title</label>
			<input type="text" placeholder="Product title" name="title" value="<?php if (isset($_POST['title'])) { echo $_POST['title']; } ?>">
		</div>

		<div class="input">
			<label for="">Product Category</label>
			<input type="text" placeholder="Product Category" name="category" value="<?php if (isset($_POST['category'])) { echo $_POST['category']; } ?>">
		</div>

		<div class="input">
			<label for="">Brand</label>
			<input type="text" placeholder="Ex: Apple" name="brand" value="<?php if (isset($_POST['brand'])) { echo $_POST['brand']; } ?>">
		</div>

		<div class="input">
			<label for="">Product Price</label>
			<input type="text" placeholder="Product price" name="price" value="<?php if (isset($_POST['price'])) { echo $_POST['price']; } ?>">
		</div>

		<div class="input">
			<label for="">Product Size</label>
				<select name="size">
						<option value="Small">Small</option>
						<option value="Medium">Medium</option>
						<option value="Large">Large</option>
						<option value="X Large">X Large</option>
						<option value="2X Large">2X Large</option>
						<option value="3X Large">3X Large</option>
				</select>
		</div>

		<div class="input">
			<label for="">Product Color</label>
			<input type="text" placeholder="Product Color" name="color" value="<?php if (isset($_POST['color'])) { echo $_POST['color']; } ?>">
		</div>

        <div class="input">
			<label for="">Product Image</label>
			<input type="file" name="image">
		</div>

		<div class="input">
			<label for="">Quantity</label>
			<input type="text" placeholder="Product quantity" name="quantity" value="<?php if (isset($_POST['quantity'])) { echo $_POST['quantity']; } ?>">
		</div>

		<div class="input">
			<button type="submit"><i class="fa-solid fa-plus"></i> Create Product</button>
		</div>

	</form>
</main>

<?php include_once ('includes/footer.php'); ?>
