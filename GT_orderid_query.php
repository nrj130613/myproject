<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['username'])) {
    header("location:shop_login.php");
    exit; // Add exit here to stop further execution
}

$link = mysqli_connect("localhost", "root", "", "goodstracking");
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

$shop_id = $_SESSION['shopID'];

if (isset($_POST["submit"])) {
    $order_id = $_POST["order_id"];

    $sql1 = "SELECT * FROM updated_orders
        WHERE orderID = '$order_id' AND EXISTS (SELECT 1 FROM orders WHERE orderID = '$order_id' AND shopID = '$shop_id');";
    $result1 = mysqli_query($link, $sql1);

    $sql2 = "SELECT orderID, order_latest_status, latest_status_date, order_tracking FROM orders
        WHERE orderID = '$order_id' AND shopID = '$shop_id';";
    $result2 = mysqli_query($link, $sql2);

    $sql3 = "SELECT * FROM orders
        WHERE shopID = '$shop_id' and orderID = '$order_id';";
    $result3 = mysqli_query($link, $sql3);

    if (mysqli_num_rows($result1) > 0 && mysqli_num_rows($result2) > 0) {
        while ($row = mysqli_fetch_assoc($result3)) {
            echo "<p>" . "product name : " . $row["order_product"] . "</p>";}
        echo "<p> order status record </p>";
        echo "<table border=1>";
        echo "<tr>
            <th>Order ID</th>
            <th>Order Status</th>
            <th>Status Updated Date</th>
        </tr>";
        while ($row = mysqli_fetch_assoc($result1)) {
            echo "<tr>
                <td>" . $row["orderID"] . "</td>
                <td>" . $row["order_updated_status"] . "</td>
                <td>" . $row["updated_status_date"] . "</td>
                </tr>";
        }
        echo "</table>";
        echo "<p> order latest status </p>";
        echo "<table border=1>";
        echo "<tr>
            <th>Order ID</th>
            <th>Order Status</th>
            <th>Status Updated Date</th>
            <th>Order Tracking</th>
        </tr>";
        while ($row = mysqli_fetch_assoc($result2)) {
            echo "<tr>
                <td>" . $row["orderID"] . "</td>
                <td>" . $row["order_latest_status"] . "</td>
                <td>" . $row["latest_status_date"] . "</td>
                <td>" . $row["order_tracking"] . "</td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "No results found.";
    }
    mysqli_free_result($result1);
    mysqli_free_result($result2);
} else {
    ?>

    <form method="post" action="orderid_query.php">
        <label>Order ID: </label><input type="text" name="order_id" placeholder="Enter order ID" required><br>
        <button type="submit" name="submit" value="Submit">Search</button>
    </form>

    <?php
}

mysqli_close($link);
?>
