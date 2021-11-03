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

        if(isset($_POST['update']) && isset($_POST['userid']) && 
           isset($_POST['fname']) && isset($_POST['lname']) && 
           isset($_POST['email']) && isset($_POST['phone']) && 
           isset($_POST['card'])&& isset($_POST['expire_month']) && 
           isset($_POST['expire_year']) &&  isset($_POST['cvv']) && 
           isset($_POST['typeAcc'])){

            // If this page was loaded to update an customer info
            $query = "UPDATE users SET id= '$_POST[userid]', role ='$_POST[typeAcc]', first_name ='$_POST[fname]', last_name ='$_POST[lname]', email ='$_POST[email]', phone_number ='$_POST[phone]'
                      WHERE id ='$_POST[hidden]'";
            $query2= "UPDATE customer_info SET  card_number ='$_POST[card]', expire_month ='$_POST[expire_month]', expire_year ='$_POST[expire_year]', cvv ='$_POST[cvv]'
                      WHERE customer_id ='$_POST[hidden]'";
            
            mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
            mysqli_multi_query($conn, $query2) or die(mysqli_error($conn));
            $result = mysqli_store_result($conn);
            while(mysqli_next_result($conn));
            echo "<p style='color:red;'><b>
                  Updated Customer ID ".$_POST['userid']. ": ".$_POST['fname']. " " .$_POST['lname']. 
                 "</b></p>";


        }else if(isset ($_POST['delete']) && isset($_POST['hidden'])){

            // If this page was loaded to delete account
            $query = "DELETE FROM users
                      WHERE id='$_POST[hidden]'";
            mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
            $result = mysqli_store_result($conn);
            while(mysqli_next_result($conn));
            echo "<p style='color:red;'><b>
                  Deleted Account ID ".$_POST['userid']. ": ".$_POST['fname']. " " . $_POST['lname' ]. 
                  "</b></p>";
        } 

    ?>

    <p>
    <h3>Manage Customer Accounts</h3>
    </p>
    <br>
    <!-- Be able to view and edit customers account-->
    <?php
        $query = "SELECT  u.*, c.*
                FROM users u JOIN customer_info c 
                WHERE u.id = c.customer_id
                ORDER BY u.id DESC";

        mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
        $result = mysqli_store_result($conn);
        while($row = mysqli_fetch_row($result)){

            $formID = "userID" . $row[0];
            echo "<table class ='myTable'>";
            echo "
                <form id='".$formID."' action='./manageCusAcc.php' method='post'>
                <input type='hidden' id='hidden' name='hidden' value='".$row[0]. "' >
                <tr>
                    <td style='width: 150px;'><b>Account ID:</b></td>
                    <td>
                        <input style='width: 300px;' type='text' id='userid' name='userid' value= '".$row[0]. "'  required> 
                    </td>
                </tr>
                <tr>
                    <td><b>Account Type:</b></td>
                    <td>
                        <input style='width: 300px;' type='text' id='typeAcc' name='typeAcc' value= '".$row[5]. "' required>
                    </td>
                </tr>   
                <tr>
                    <td><b>First Name:</b></td>
                    <td> 
                        <input style='width: 300px;' type='text' id='fname' name='fname' value= '".$row[6]. "'  required>
                    </td>
                </tr>
                <tr>
                    <td><b>Last Name:</b></td>
                    <td>
                        <input style='width: 300px;' type='text' id='lname' name='lname' value= '".$row[7]. "'  required>
                    </td>
                </tr>
                <tr>
                    <td><b>Email:</b></td>
                    <td>
                        <input style='width: 300px;' type='text' id='email' name='email' value= '".$row[3]. "'  required>
                    </td>
                </tr>
                <tr>
                    <td><b>Phone:</b></td>
                    <td>
                        <input style='width: 300px;' type='text' id='phone' name='phone' value= '".$row[4]. "' >
                    </td>
                </tr>                    
                <tr>
                    <td><b>Card Number:</b></td>
                    <td>
                        <input minlength='16' maxlength='16'  style='width: 300px;' type='text' id='card' name='card' value= '".$row[10]. "' required>
                    </td>
                </tr>
                <tr>
                    <td><b>Expiration Date:</b></td>
                    <td>
                    <input type='text' minlength='2' maxlength='2'placeholder='Month' id='expire_month' name='expire_month' value='".$row[11]."' size='1' required> /
                    <input type='text' minlength='2' maxlength='2' placeholder='Year' id='expire_year' name='expire_year' value='".$row[12]."' size='1' required>
                    </td>
                </tr>        
                <tr>
                <td><b>CVV:</b></td>
                <td>
                <input type='text' minlength='3' maxlength='3' placeholder='cvv' id='cvv' name='cvv' value='".$row[13]."' size='10' required>
                </td>
            </tr>     
                <tr>
                    <td></td>
                    <td>
                        <button type='submit' name='update' value=''>Update</button>
                        <button type='submit' name='delete' value=''>Delete</button>
                    </td>
                </tr>
                </table>
                </form>
                <br><br>";
        }
        echo "<hr><br><br>";


        mysqli_free_result($result);
        while (mysqli_next_result($conn));
    ?>
    </center>
</div>
          
<?php
    include("./footer.php");
?>