<?php
    include_once("./sqlInit.php"); // connection page
    global $conn;
    include("./header.php"); // navigation bar
?>

<img src="logo.jpg" id="logo" width= 100px height= 100px/> <!--logo picture -->
<br><br><br><br><br><br> <!-- white space -->

<!-- background on the website-->
<div class="container">
    <center>

        <?php
        // Used on login
        if(isset($_POST["uname"]) && isset($_POST["psw"])){
            $query = "CALL `attempt_login`('".$_POST["uname"]."', '".$_POST["psw"]."');";
            mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
            $result = mysqli_store_result($conn);
            $login_successful = mysqli_fetch_row($result)[0];
            mysqli_free_result($result);
            while (mysqli_next_result($conn));
            if(!$login_successful){
                echo "<h3>Failed to sign in user: ".$_POST["uname"]."</h3>";
            } else {
                session_start();
                $_SESSION["username"] = $_POST["uname"];
                $_SESSION["password"] = $_POST["psw"];
                header("Location: ./home.php"); // go to home page when login is successful
            }
        }
        ?>
        <!-- Login form  -->
        <form action="./login.php" method="post">
            <h3>Log In to Delta Cafe</h3>
            <label for="uname"><b>Username</b></label>
                <input type="text" placeholder="Enter Username" id="uname" name="uname" required>
            <br><br>
            <label for="psw"><b>Password</b></label>
                <input type="password" placeholder="Enter Password" id="psw" name="psw" required>
            <br><br>
            <button type="submit" id="submit_button">Sign In<Input:datetime-local></Input:datetime-local></button>
        </form>
        <br><br><br>
        <hr><br>
        <!-- New customer registration -->
        New to Delta Cafe? <button type="submit"><a href="cusNewAcc.php">Create Account</a></button>
    </center>
</div>

<?php
    include("./footer.php");
?>
