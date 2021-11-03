
<?php
    $user_id = -1;
    session_start();
    include_once("./sqlInit.php");
    global $conn;

    //This function will go to the login page
    function go_to_login(){
        header("Location: ./login.php");
    }

    if(isset($_SESSION['username']) && isset($_SESSION['password'])){
        $username = $_SESSION['username'];
        $password = $_SESSION['password'];

        $query = "CALL `attempt_login`('".$username."', '".$password."');";
        mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
        $result = mysqli_store_result($conn);
        $login_successful = mysqli_fetch_row($result)[0];
        mysqli_free_result($result);
        while (mysqli_next_result($conn));

        // if the username and password is invalid it will go back to the login page
        if(!$login_successful){
            go_to_login();
        } else{
            //If the login is successful it will go to the home page
            $query = "CALL `select_user_id`('".$username."', '".$password."')";
            mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
            $result = mysqli_store_result($conn);
            $user_id = mysqli_fetch_row($result)[0];
            mysqli_free_result($result);
            while (mysqli_next_result($conn));
        }
    } else{
        go_to_login();
    }
?>
