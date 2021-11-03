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

            while (mysqli_next_result($conn));

            echo "<p>Are you sure you want to delete your account?</p>";
            echo "
                <form action='' method='post'>
                <button style='color:red;' type='submit'><a href='delete.php'>Yes</a></button>
                <button type='submit'><a href='Home.php'>No</a></button>
                </form>";
    
?>

    </center>
</div>

<?php
    include("./footer.php");
?>

