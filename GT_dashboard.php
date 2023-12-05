<?php
session_start();
if(!isset($_SESSION['username'])){
    header("location:shop_login.php");
}

if(isset($_POST['logout'])){
    session_destroy();
    header("location:shop_login.php");
}
?>

Welcome, <?php echo $_SESSION['username']; ?>!
<ul>
    <li><a href="cust_insert.php">Insert Customer & Order Details </a></li>
    <li><a href="order_update.php">Update Order Status</a></li>
    <li><a href="search.php">Search</a></li>
    <li><a href="stat.php">Sales Statistics</a></li>
</ul>

<form method="post">
    <input type="submit" name="logout" value="Logout">
</form>