<?php
	session_start();
  $page_title = 'Login';
  include_once ('includes/header.php');
	require_once 'app/connect.php';

  if (isset($_SESSION['uid']) || isset($_COOKIE['c_uid'])) {
    header('Location: profile.php');
  }


  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = sha1($_POST['password']);

    $errors = [];

    if (empty($email)) {
      $errors[] = 'Email is required';
    }

    if (empty($password)) {
      $errors[] = 'Password is required';
    }

    if (empty($errors)) {
      $q = $mysql->query("SELECT * FROM users WHERE email = '$email' AND password = '$password'");
      $user = $q->fetch_assoc();
      if (!$user) {
        $errors[] = 'No User found';
      } else {
        $_SESSION['uid'] = $user['user_id'];
        setcookie('c_uid', $user['user_id'], time() + (86400 * 30), '/');
        header('Location: profile.php');
      }
    }

  }

?>

<main id="login-page">

  <?php
		if (!empty($errors)) {
			foreach ($errors as $error) {
				echo "<div class='alert-error alert'><i class='fa-solid fa-circle-exclamation'></i> " . $error . "</div>";
			}
		}
	?>

  <div class="login-form">
    <img src="includes/images/login.png" alt="">

    <h1>Login</h1>

    <form action="" autocomplete="off" method='post'>
      <div class="input">
        <label for="">E-mail</label>
        <input type="text" placeholder="E-mail Address" name="email" value="<?php if (isset($_POST['email'])) { echo $_POST['email']; } ?>">
      </div>

      <div class="input">
        <label for="">Password</label>
        <input type="password" placeholder="Password" name="password">
      </div>

      <div class="input">
        <button type="submit"><i class="fa-solid fa-sign-in-alt"></i> Login</button>
      </div>

    </form>
  </div>

</main>

<?php include_once ('includes/footer.php'); ?>
