<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if(!isset($_SESSION['username'])){
    header("location:shop_login.php");
}
?>

Stat<p>
<ul>
    <li><a href="lot_stat.php">Sales by Lot</a></li>
    <li><a href="monthly_stat.php">Monthly Sales</a></li>
    <li><a href="yearly_stat.php">Yearly Sales</a></li>
</ul>