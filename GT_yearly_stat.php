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
$order_year = "";

if(isset($_POST["submit"])) {
    $order_year = $_POST["year"];
    $sql = "SELECT YEAR(order_date), SUM(orders.order_amount), SUM(orders.order_price) FROM orders
    WHERE YEAR(order_date) = '$order_year' and shopID = '$shop_id'
    GROUP BY YEAR(order_date)
"; }
else {
    $sql = "SELECT YEAR(order_date), SUM(orders.order_amount), SUM(orders.order_price) FROM orders
    WHERE shopID = '$shop_id'
    GROUP BY YEAR(order_date)
"; }

$result = mysqli_query($link, $sql);
if (mysqli_num_rows($result) > 0) {
?>

<form method="post" action=yearly_stat.php>
    <label>Year: </label><input type="text" name="year" placeholder="YYYY" required=true><br>
    <button type="submit" name="submit" value="Submit">Search</button>
</form>

<?php
    echo "<table border=1>";
    echo "<tr>
            <th>Year</th>
            <th>Total Amount</th>
            <th>Total Sales</th>
        </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>" . $row["YEAR(order_date)"] . "</td>
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