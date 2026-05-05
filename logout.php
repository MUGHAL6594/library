<?php
  require_once "library.php";
  session_destroy();
  session_write_close();
  header("Location: login.php");
  exit();
?>