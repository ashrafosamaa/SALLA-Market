<?php 
  session_start();
  $page_title = 'Profile';
  include_once ('includes/header.php');
	require_once 'app/connect.php';
  if (!isset($_SESSION['uid']) || !isset($_COOKIE['c_uid'])) {
    header('Location: login.php');
    exit;
  }

  $msg = '';

  $uid = $_COOKIE['c_uid'];
  $findUser = $mysql->query("SELECT * FROM users Where user_id = $uid");
  $user = $findUser->fetch_assoc();

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $name     = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $location = filter_var($_POST['location'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $phone    = filter_var($_POST['phone'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $errors = [];

    if (empty($email)) {
      $errors[] = 'Email is required';
    }
    $search_email = $mysql->query("SELECT email from users where email = '$email' and user_id != $uid");
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
    $search_username = $mysql->query("SELECT username from users where username = '$username' and user_id != $uid");
    if ($search_username->fetch_assoc() && !empty($search_username)) {
      $errors[] = 'Username is already exists';
    }

    if (empty($location)) {
      $errors[] = 'Location is required';
    }

    if (empty($phone)) {
      $errors[] = 'Phone is required';
    }
    if (!filter_var($phone, FILTER_VALIDATE_INT) && !empty($phone)) {
      $errors[] = 'Phone must be a number';
    }

    if (empty($errors)) {

      $q = $mysql->query("
        UPDATE users SET username = '$username',
        name = '$name',
        email = '$email',
        phone = '$phone',
        location = '$location'

        WHERE user_id = $uid
      ");

      if ($q) {
        echo '<div class="alert alert-success">Account updated successfully!</div>';

      } else {
        $errors[] = 'Cannot update user';
      }
    }

  }

?>

  <div id="profile-page">

    <div class="errors">
      <?php
        if (!empty($errors)) {
          foreach ($errors as $error) {
            echo "<div class='alert-error alert'><i class='fa-solid fa-circle-exclamation'></i> " . $error . "</div>";
          }
        }
      ?>
    </div>

    <div class="top-items">
      <div class="left">
        <img src="<?= $user['image'] ?>" alt="">
      </div>
      <div class="right">
        <h1><?= $user['name'] ?></h1>
        <h4>@<?= $user['username'] ?></h4>
      </div>
      <div class="absolute">
        <button id="show-update-info"><i class="fa-regular fa-user"></i> Update profile</button>
        <button onclick="window.location.href='update-password.php'"><i class="fa-solid fa-user-lock"></i> Change Password</button>
        <button onclick="window.location.href='update-picture.php'"><i class="fa-regular fa-image"></i> Change Profile picture</button>
        <button onclick="window.location.href='logout.php'"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</button>
      </div>
    </div>

    <div class="user-info">
      <ul>
        <li><span><i class="fa-regular fa-fw fa-id-card"></i> Full Name</span> <span><?= $user['name'] ?></span></li>
        <li><span><i class="fa-solid fa-fw fa-at"></i> Username</span> <span><?= $user['username'] ?></span></li>
        <li><span><i class="fa-regular fa-fw fa-envelope"></i> Email</span> <span><?= $user['email'] ?></span></li>
        <li><span><i class="fa-solid fa-fw fa-mobile"></i> Phone</span> <span><?= $user['phone'] ?></span></li>
        <li><span><i class="fa-solid fa-fw fa-location-dot"></i> Location</span> <span><?= $user['location'] ?></span></li>
        <li><span><i class="fa-solid fa-fw fa-user-clock"></i> Joined In</span> <span><?= date('d M, Y - h:i A', strtotime($user['created_at'])) ?></span></li>
      </ul>
    </div>

    <div class="update-profile" id="update-block">
      <h1>Update My Account</h1>

      <form action="" autocomplete="off" method='post' enctype="multipart/form-data">
        <div class="input">
          <label for="">E-mail</label>
          <input type="text" placeholder="E-mail Address" name="email" value="<?= $user['email'] ?>">
        </div>

        <div class="input">
          <label for="">Name</label>
          <input type="text" placeholder="Name" name="name" value="<?= $user['name'] ?>">
        </div>

        <div class="input">
          <label for="">Username</label>
          <input type="text" placeholder="Username" name="username" value="<?= $user['username'] ?>">
        </div>

        <div class="input">
          <label for="">Location</label>
          <input type="text" placeholder="Location" name="location" value="<?= $user['location'] ?>">
        </div>
        <div class="input">
          <label for="">Phone</label>
          <input type="text" placeholder="Phone" name="phone" value="<?= $user['phone'] ?>">
        </div>

        <div class="input">
          <button type="submit"><i class="fa-solid fa-cog"></i> Update</button>
        </div>

      </form>

      </div>

  </div>

<?php include_once ('includes/footer.php'); ?>