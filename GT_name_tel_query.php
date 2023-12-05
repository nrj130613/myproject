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
    $cust_name = $_POST["name"];
    $cust_phone = $_POST["phoneno"];

    $sql = "SELECT * FROM orders
        JOIN customers ON orders.custID = customers.custID
        WHERE customers.cust_name = '$cust_name' and customers.cust_phone = '$cust_phone' and shopID = '$shop_id';";
} else {
    $sql = "SELECT * FROM orders
        JOIN customers ON orders.custID = customers.custID
        WHERE shopID = '$shop_id';";
}

$result = mysqli_query($link, $sql);
if (mysqli_num_rows($result) > 0) {
?>

<form method="post" action=name_tel_query.php>
    <label>Name: </label><input type="text" name="name" placeholder="Enter name" required=true><br>
    <label>Phone Number: </label><input type="text" name="phoneno" placeholder="Enter phone number" required=true><br>
    <button type="submit" name="submit" value="Submit">Search</button>
</form>

<?php
    if(isset($_POST["submit"])) {
        echo "<p>" . $cust_name . "'s order detail</p>";
    }
    echo "<table border=1>";
    echo "<tr>
            <th>Order ID</th>
            <th>Customer ID</th>
            <th>Order Product</th>
            <th>Order Date</th>
            <th>Order Amount</th>
            <th>Order Price</th>
            <th>Order Latest Status</th>
            <th>Latest Status Date</th>
            <th>Order Tracking</th>
            <th>Order Lot</th>
        </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>" . $row["orderID"] . "</td>
                <td>" . $row["custID"] . "</td>
                <td>" . $row["order_product"] . "</td>
                <td>" . $row["order_date"] . "</td>
                <td>" . $row["order_amount"] . "</td>
                <td>" . $row["order_price"] . "</td>
                <td>" . $row["order_latest_status"] . "</td>
                <td>" . $row["latest_status_date"] . "</td>
                <td>" . $row["order_tracking"] . "</td>
                <td>" . $row["order_lot"] . "</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "No results found.";
}
mysqli_close($link);
?>
