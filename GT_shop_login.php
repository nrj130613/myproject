<?php
session_start();
if(isset($_SESSION['username'])){
    header("location:dashboard.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // connect to the database
    $link = mysqli_connect("localhost", "root", "", "goodstracking");

    // check if the username and password exist in the database
    $query = "SELECT * FROM shops WHERE shop_username = '$username' and shop_password = '$password'";
    $result = mysqli_query($link,$query);
    $count = mysqli_num_rows($result);

    if($count == 1) {
        // store the username and shop_id in session and redirect to dashboard page
        $row = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $username;
        $_SESSION['shopID'] = $row['shopID'];
        header("location:dashboard.php");
    }else {
        $error = "Invalid username or password";
        echo $error;
    }

    // close the database connection
    mysqli_close($link);
}
?>

<form action="" method="post">
    <p>Username</p>
    <input type="text" name="username" required>
    <p>Password</p>
    <input type="password" name="password" required>
    <p><input type="submit" value="Login">
</form>

<p>Don't have an account? <a href="shop_insert.php">Sign Up</a></p>