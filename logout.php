<?php 
  session_start();
  unset($_SESSION['uid']);
  if (isset($_COOKIE['c_uid'])) {
    unset($_COOKIE['c_uid']); 
    setcookie('c_uid', null, -1, '/'); 
  }
  session_unset();
  session_destroy();

  