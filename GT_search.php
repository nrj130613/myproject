<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if(!isset($_SESSION['username'])){
    header("location:shop_login.php");
}
?>

Search<p>
<ul>
    <li><a href="lot_query.php">Search Order Details by Lot</a></li>
    <li><a href="name_tel_query.php">Search Order Details by Name & Tel</a></li>
    <li><a href="orderid_query.php">Search Order Status Record by Order ID</a></li>
    <li><a href="cust_query.php">Customer Details Search</a></li>
</ul>