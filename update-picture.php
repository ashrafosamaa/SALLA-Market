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

    $image = $_FILES['image'];

    $errors = [];
    
    $allowed_extensions = ['image/jpg', 'image/png', 'image/jpeg'];

		$target_file = UPLOAD_TARGET . time() . '_' . uniqid() . basename($image['name']);

		if ($image['size'] > 50000000 && !empty($image)) {
			$errors[] = 'Image must be less than 5 megabyte';
		}
		if (!in_array($image['type'], $allowed_extensions)) {
			$errors[] = 'This file is not allowed';
			$allow_upload = false;
		}


    if (empty($errors)) {
      move_uploaded_file($image['tmp_name'], $target_file);
      $q = $mysql->query("
        UPDATE users SET image = '$target_file'
        WHERE user_id = $uid
      ");

      if ($q) {
        echo '<div class="alert alert-success">Picture has been updated successfully</div>';
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

    <div class="update-profile">
      <h1>Update Profile Picture</h1>

      <form action="" autocomplete="off" method='post' enctype="multipart/form-data">

        <div class="input">
          <label for="">New Picture</label>
          <input type="file" placeholder="Old Password" name="image">
        </div>

        <div class="input">
          <button type="submit"><i class="fa-solid fa-cog"></i> Update</button>
        </div>

      </form>

    </div>

  </div>

<?php include_once ('includes/footer.php'); ?>