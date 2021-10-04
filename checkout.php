<?php
    include("./force_login.php");
    global $user_id;
    include_once("./sqlInit.php");
    global $conn;
    include("./header.php");
?>

<!-- background on the website-->
<div class="container">
    <center>

        <?php
        // Used on account creation
        if(
            isset($_POST["fname"]) && isset($_POST["lname"]) &&
            isset($_POST["phone"]) && isset($_POST["email"]) && 
            isset($_POST["card_number"])&& isset($_POST["expire_month"])
            && isset($_POST["expire_year"]) && isset($_POST["cvv"])){
            $query = "CALL `update_customer_info`(".$user_id.", 
            '".$_POST["email"]."', '".$_POST["phone"]."', 
            '".$_POST["fname"]."', '".$_POST["lname"]."', 
            '".$_POST["card_number"]."', '".$_POST["expire_month"]."', '".$_POST["expire_year"]."', '".$_POST["cvv"]."');";
            mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
            $result = mysqli_store_result($conn);
            while (mysqli_next_result($conn));

            header("Location: ./invoice.php");
        }
        ?>
        <p>
        <h3>Purchase Information</h3>
        <form action="checkout.php" method="post">
            <?php
            
            $query = "CALL `select_customer_info`(".$user_id.")";
            mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
            $result = mysqli_store_result($conn);
            $row = mysqli_fetch_row($result);
            echo "
            <label for='fname'>First Name</label>
            <input type='text' placeholder='First Name' id='fname' name='fname' value='".$row[1]."' required>
            &ensp;
            <label for='lname'>Last Name</label>
            <input type='text' placeholder='Last Name' id='lname' name='lname' value='".$row[2]."' required>
            <br><br>
            <label for='phone'>Phone Number</label>
            <input type='text' placeholder='Phone' id='phone' name='phone' value='".$row[3]."' required>
            &ensp;
            <label for='email'>Email</label>
            <input type='text' placeholder='E-mail' id='email' name='email' value='".$row[4]."' required>
            <br><br>
            <label for='card'>Card Number</label>
            <input type='text' minlength='16' maxlength='16' placeholder='Card Number' id='card' name='card_number' value='".$row[5]."' required>
            &ensp;
            <label for='card'>Expiration Date</label>
            <input type='text' minlength='2' maxlength='2'placeholder='Month' id='expire_month' name='expire_month' value='".$row[6]."' size='1' required> /
            <input type='text' minlength='2' maxlength='2' placeholder='Year' id='expire_year' name='expire_year' value='".$row[7]."' size='1' required>
            <br><br>
            <label for='card'>CVV</label>
            <input type='text' minlength='3' maxlength='3' placeholder='cvv' id='cvv' name='cvv' value='".$row[8]."' size='10' required>
            <br><br>
            ";
            mysqli_free_result($result);
            while (mysqli_next_result($conn));
            ?>
            <button type="submit">Purchase</button>
        </form>
        <!-- <br>
       <button type="submit"><a href="viewOrders.php">Return to View Orders</a></button> -->
        <br><br><br>
    </center>
        </p>
    </div>
</div>

<?php
    include("./footer.php");
?>
