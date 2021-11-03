<!-- logout, so the session will end -->
<?php
session_start(); 

// if the username and password match the database it will destroy(unset) when the user logout
if(isset($_SESSION['username']) && isset($_SESSION['password'])){
    unset($_SESSION['username']);
    unset($_SESSION['password']);
}
// Once the user logout it will go back to the login page
header("Location: ./login.php");
?>
