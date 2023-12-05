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
$sql = "SELECT * FROM orders WHERE shopID = '$shop_id'";
$result = mysqli_query($link, $sql);
$data = array();
if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_assoc($result)) {
		$data[] = $row;
	}
} else {
	echo "No results found.";
	mysqli_close($link);
	exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$search_term = $_POST["search"];
	$data = array_filter($data, function($row) use ($search_term) {
		return strpos($row["order_lot"], $search_term) !== false;
	});
}

?>

<form method="post" action=lot_query.php>
	<label for="search">Order Lot: </label>
	<input type="text" id="search" name="search" placeholder="Enter order lot" value="<?php echo isset($_POST['search']) ? $_POST['search'] : ''; ?>" required=true>
	<button type="submit" name="submit" value="Submit">Search</button>
</form>

<?php
if (!empty($data)) {
	echo "<table border=1>";
	echo "<tr>
            <th>Order Lot</th>
            <th>Order ID</th>
            <th>Customer ID</th>
            <th>Order Product</th>
            <th>Order Date</th>
            <th>Order Amount</th>
            <th>Order Price</th>
            <th>Order Latest Status</th>
            <th>Latest Status Date</th>
            <th>Order Tracking</th>
        </tr>";
	foreach ($data as $row) {
		echo "<tr>
                <td>" . $row["order_lot"] . "</td>
                <td>" . $row["orderID"] . "</td>
                <td>" . $row["custID"] . "</td>
                <td>" . $row["order_product"] . "</td>
                <td>" . $row["order_date"] . "</td>
                <td>" . $row["order_amount"] . "</td>
                <td>" . $row["order_price"] . "</td>
                <td>" . $row["order_latest_status"] . "</td>
                <td>" . $row["latest_status_date"] . "</td>
                <td>" . $row["order_tracking"] . "</td>
            </tr>";
	}
	echo "</table>";
}
mysqli_close($link);
?>
