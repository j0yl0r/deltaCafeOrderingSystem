<!DOCTYPE html>
<html>
    <head>
        <title>Delta Cafe</title>
        <link rel="stylesheet" href="DCSstyle.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>

    <!-- Delta Cafe Navigation Bar     -->
    <body>
        <?php
        global $user_id;
        if(isset($user_id)){
            $query = "SELECT role FROM users 
                      WHERE id = $user_id";

            mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
            $result = mysqli_store_result($conn);
            $user_role = mysqli_fetch_row($result)[0];
            mysqli_free_result($result);
            while (mysqli_next_result($conn));

            //company logo              
            echo '<img src="logo.jpg" id="logo" width= 100px height= 100px/>';
            $navbar_html = "<div class='navbar'>";
            switch($user_role){
                case "administrator":
                    $navbar_html .= "
                        <a href='index.php'>Home</a>
                        <a href='manageInventories.php'>Manage Inventories</a>
                        <a href='manageOrders.php'>Manage Orders</a>
                        <a href='manageAccount.php'>Manage Accounts</a>";
                    break;
                case "customer":
                    $navbar_html .= "
                        <a href='home.php'>Home</a>
                        <a href='orderNow.php'>View Menu</a>
                        <a href='viewOrders.php'>View Orders</a>
                        <div class='dropdown'>
                            <button class='dropbtn'>
                            <a href=''>Account</a>
                            </button>
                        <div class='dropdown-content'>
                            <a href='editCustomerInfo.php'>Edit Account Info</a>
                        </div>
                    </div>";
                    break;
                default:        
                    $navbar_html .= "User role '".$user_role."' not recognized";
              
            }
            $navbar_html .= "
                    <a href='logout.php' class='right'>
                        Sign out 
                        (" . $_SESSION['username'] .
                        ")</a>
            </div>"
            ;
            echo $navbar_html;
        }
        ?>

