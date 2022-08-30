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

    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    $errors = [];

    if ($old_password == "") {
      $errors[] = 'Old password is required';
    }
    
    if ($new_password == "") {
      $errors[] = 'New password is required';
    }


    if ($old_password != "") {
      if (sha1($old_password) != $user['password']) {
        $errors[] = 'Your old password dose not match our records';
      }
    }

    if (strlen($new_password) < 8 && $new_password != "") {
      $errors[] = 'New Password cannot be less than 8 letters';
    }

    if (empty($errors)) {
      $p = sha1($new_password);
      $q = $mysql->query("
        UPDATE users SET password = '$p'
        WHERE user_id = $uid
      ");

      if ($q) {
        echo '<div class="alert alert-success">Password has been updated successfully</div>';
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
      <h1>Update Password</h1>

      <form action="" autocomplete="off" method='post' enctype="multipart/form-data">

        <div class="input">
          <label for="">Old Password</label>
          <input type="password" placeholder="Old Password" name="old_password">
        </div>

        <div class="input">
          <label for="">New Password</label>
          <input type="password" placeholder="Strong Password" name="new_password">
        </div>

        <div class="input">
          <button type="submit"><i class="fa-solid fa-cog"></i> Update</button>
        </div>

      </form>

    </div>

  </div>

<?php include_once ('includes/footer.php'); ?>