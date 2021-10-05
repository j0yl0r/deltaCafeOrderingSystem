<?php
    include_once("./sqlInit.php");
    global $conn;
    include("./header.php");
?>
<!-- delta cafe logo -->
<img src="logo.jpg" id="logo" width= 100px height= 100px/>
<br><br><br><br><br><br>
<div class="container">
    <center>

        <?php
        // Used on create account 
        if(isset($_POST["uname"]) && 
            isset($_POST["fname"]) && isset($_POST["lname"]) &&
            isset($_POST["phone"]) && isset($_POST["email"]) &&
            isset($_POST["psw"]) && isset($_POST["card"]) && 
            isset($_POST["expire_month"]) && isset($_POST["expire_year"]) 
            && isset($_POST["cvv"])){
            $query = "CALL `insert_new_customer`('".$_POST["uname"]."', '".$_POST["psw"]."', 
            '".$_POST["email"]."', '".$_POST["phone"]."', 
            '".$_POST["fname"]."', '".$_POST["lname"]."', 
            '".$_POST["card"]."', '".$_POST["expire_month"]."', '".$_POST["expire_year"]."', '".$_POST["cvv"]."');";
            mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
            $result = mysqli_store_result($conn);
            $login_successful = mysqli_fetch_row($result)[0];
            mysqli_free_result($result);
            header("Location: ./login.php");
        }
        ?>
        
<!--  New customer creating an account        -->
        <p><h3>Create An Account</h3>
        <form action="cusNewAcc.php" method="post">
            <input type="text" placeholder="Username" id="uname" name="uname" required>
            <br><br>
            <input type="text" placeholder="First Name" id="fname" name="fname" required>
            <input type="text" placeholder="Last Name" id="lname" name="lname" required>
            <br><br>
            <input type="text" placeholder="Phone" id="phone" name="phone" required>
            <input type="text" placeholder="E-mail" id="email" name="email" required>
            <br><br>
            <input type="password" placeholder="Password" id="psw" name="psw" required>
            <input type="password" placeholder="Re-Enter Password" id="psw" name="psw" required>
            <br><br>
            <input type="text" placeholder="Card Number" id="card" name="card" required>
            <input type="text" minlength='2' maxlength='2' placeholder="Month" id="expire_month" name="expire_month" size ="1" required> /
            <input type="text" minlength='2' maxlength='2' placeholder="Year" id="expire_year" name="expire_year" size ="1" required>
            <br><br>
            <input type="text" minlength='3' maxlength='3' placeholder="CVV" id="cvv" name="cvv" size = "10" required>
            <br><br>

            <button type="submit">Create Now</button>
        </form>
        <br><hr><br>
        Already a member? <button type="submit"><a href="login.php">Sign In</a></button>
        </p>
    </center>
</div>

<?php
    include("./footer.php");
?>
