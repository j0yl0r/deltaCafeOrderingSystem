<?php
    include("./force_login.php");
    global $user_id;
    include_once("./sqlInit.php");
    global $conn;
    include("./header.php");
?>

<div class="container">
    <center>



<?php
        // Delete account function
        if(isset($_POST["delete_account"])){
            $query = "CALL `delete_account`(".$_POST['delete_account'].");";
            mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
            $result = mysqli_store_result($conn);
            while (mysqli_next_result($conn));
            echo "<p style='color:red;'><b>Your Account has been deleted</b></p>";
      
        }

        while (mysqli_next_result($conn));
        echo "<br>";
        echo " Are you sure?";
        echo" 
            <br><br>     
            <form action='' method='post'>       
            <button style='color:red;' type='submit' name='delete_account' value=".$user_id."><a>Yes</a></button>
            <button type='submit'><a href='Home.php'>No</a></button>
            </form>";
?>

    </center>
</div>

<?php
    include("./footer.php");
?>

