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

            echo '<img src="logo.jpg" id="logo" width= 100px height= 100px/>'; // logo picture
            $navbar_html = "<div class='navbar'>";

            // Using swtich statement to determine if the user is Customer or Admin
            switch($user_role){
                //If it is Admin it will user the Admin Navigation Bar
                case "administrator":
                    $navbar_html .= "
                        <a href='home.php'>Home</a>
                        <a href='manageMenu.php'>Manage Menu</a>
                        <a href='manageOrder.php'>Manage Orders</a>
                        <div class='dropdown'>
                        <button class='dropbtn'>
                        <a href=''>Manage Accounts</a>
                        </button>
                    <div class='dropdown-content'>
                        <a href='manageCusAcc.php'>Customer Accounts</a>
                        <a href='manageAdminAcc.php'>Administrator Accounts</a>
                    </div>
                </div>";
                    break;
                // If it is Customer it will user the Customer Navigation Bar
                case "customer":
                    $navbar_html .= "
                        <a href='home.php'>Home</a>
                        <a href='orderNow.php'>Menu</a>
                        <a href='viewOrders.php'>View Orders</a>
                        <div class='dropdown'>
                            <button class='dropbtn'>
                            <a href=''>Account</a>
                            </button>
                        <div class='dropdown-content'>
                            <a href='editCustomerInfo.php'>Edit Account Info</a>
                            <a href='deleteCusAcc.php'>Delete Account</a>
                        </div>
                    </div>";
                    break;
                default:        
                    $navbar_html .= "User role '".$user_role."' not recognized";
              
            }
            // Logging out from the website
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

