<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if(!isset($_SESSION['username'])){
    header("location:shop_login.php");
}

$link = mysqli_connect("localhost", "root", "", "goodstracking");
if (!$link) {
	die("Connection failed: " . mysqli_connect_error());
}

$shop_id = $_SESSION['shopID'];

if(isset($_POST["submit"])) {
    $order_lot = $_POST["order_lot"];

    $sql = "SELECT orders.order_lot, SUM(orders.order_amount), SUM(orders.order_price) FROM orders
    WHERE orders.order_lot = '$order_lot' and shopID = '$shop_id'
    GROUP BY orders.order_lot
"; }
else {
    $sql = "SELECT orders.order_lot, SUM(orders.order_amount), SUM(orders.order_price) FROM orders
    WHERE shopID = '$shop_id'
    GROUP BY orders.order_lot
"; }

$result = mysqli_query($link, $sql);
if (mysqli_num_rows($result) > 0) {
?>

<form method="post" action=lot_stat.php>
    <label>Order Lot: </label><input type="text" name="order_lot" placeholder="Enter order lot" required=true><br>
    <button type="submit" name="submit" value="Submit">Search</button>
</form>

<?php
    echo "<table border=1>";
    echo "<tr>
            <th>Order Lot</th>
            <th>Total Amount</th>
            <th>Total Sales</th>
        </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>" . $row["order_lot"] . "</td>
                <td>" . $row["SUM(orders.order_amount)"] . "</td>
                <td>" . $row["SUM(orders.order_price)"] . "</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "No results found.";
}
mysqli_close($link);
?>