<?php
	session_start();
  $page_title = 'Register';
  include_once ('includes/header.php');
	require_once 'app/connect.php';

  if (isset($_SESSION['uid']) || isset($_COOKIE['c_uid'])) {
    header('Location: profile.php');
  }


  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $name = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $location = filter_var($_POST['location'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $image = $_FILES['image'];
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = sha1($_POST['password']);

    $errors = [];
		$allowed_extensions = ['image/jpg', 'image/png', 'image/jpeg'];

		$target_file = UPLOAD_TARGET . time() . '_' . uniqid() . basename($image['name']);

		if ($image['size'] > 50000000 && !empty($image)) {
			$errors[] = 'Image must be less than 5 megabyte';
		}
		if (!in_array($image['type'], $allowed_extensions) && !empty($image)) {
			$errors[] = 'This file is not allowed';
		}

    if (empty($email)) {
      $errors[] = 'Email is required';
    }
    $search_email = $mysql->query("SELECT email from users where email = '$email'");
    if ($search_email->fetch_assoc() && !empty($search_email)) {
      $errors[] = 'E-mail address is already exists';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($email)) {
      $errors[] = 'This is not a valid email address';
    }

    if (empty($name)) {
      $errors[] = 'Name is required';
    }

    if (empty($username)) {
      $errors[] = 'Username is required';
    }
    $search_username = $mysql->query("SELECT username from users where username = '$username'");
    if ($search_username->fetch_assoc() && !empty($search_username)) {
      $errors[] = 'Username is already exists';
    }

    if (empty($location)) {
      $errors[] = 'Location is required';
    }

    if (empty($image)) {
      $errors[] = 'Image is required';
    }

    if (empty($phone)) {
      $errors[] = 'Phone is required';
    }
    if (!filter_var($phone, FILTER_VALIDATE_INT) && !empty($phone)) {
      $errors[] = 'Phone must be a number';
    }

    if (!empty($password) && strlen($password) < 8) {
      $errors[] = 'Password cannot be less than 8 numbers';
    }
    if (empty($password)) {
      $errors[] = 'Password is required';
    }

    if (empty($errors)) {
      $q = $mysql->query("
        INSERT INTO users(username, email, name, password, location, phone, image)
        VALUES('$username', '$email', '$name', '$password', '$location', '$phone', '$target_file')
      ");
      move_uploaded_file($image['tmp_name'], $target_file);
      if ($q) {
        $u_q = $mysql->query("SELECT user_id from users WHERE email = '$email'");
        $user = $u_q->fetch_assoc();
        $_SESSION['uid'] = $user['user_id'];
        setcookie('c_uid', $user['user_id'], time() + (86400 * 30), '/');
        header('Location: profile.php');
      } else {
        $errors[] = 'Cannot create user';
      }
    }

  }

?>

<main id="register-page">

  <?php
		if (!empty($errors)) {
			foreach ($errors as $error) {
				echo "<div class='alert-error alert'><i class='fa-solid fa-circle-exclamation'></i> " . $error . "</div>";
			}
		}
	?>

  <div class="login-form">
    <img src="includes/images/add-user.png" alt="">

    <h1>Create New User</h1>

    <form action="" autocomplete="off" method='post' enctype="multipart/form-data">
      <div class="input">
        <label for="">E-mail</label>
        <input type="text" placeholder="E-mail Address" name="email" value="<?php if (isset($_POST['email'])) { echo $_POST['email']; } ?>">
      </div>

      <div class="input">
        <label for="">Name</label>
        <input type="text" placeholder="Name" name="name" value="<?php if (isset($_POST['name'])) { echo $_POST['name']; } ?>">
      </div>

      <div class="input">
        <label for="">Username</label>
        <input type="text" placeholder="Username" name="username" value="<?php if (isset($_POST['username'])) { echo $_POST['username']; } ?>">
      </div>

      <div class="input">
        <label for="">Location</label>
        <input type="text" placeholder="Location" name="location" value="<?php if (isset($_POST['location'])) { echo $_POST['location']; } ?>">
      </div>
      <div class="input">
        <label for="">Phone</label>
        <input type="text" placeholder="Phone" name="phone" value="<?php if (isset($_POST['phone'])) { echo $_POST['phone']; } ?>">
      </div>

      <div class="input">
        <label for="">Picture</label>
        <input type="file" name="image">
      </div>

      <div class="input">
        <label for="">Password</label>
        <input type="password" placeholder="Password" name="password">
      </div>

      <div class="input">
        <button type="submit"><i class="fa-solid fa-user-plus"></i> Create Account</button>
      </div>

    </form>
  </div>

</main>

<?php include_once ('includes/footer.php'); ?>
