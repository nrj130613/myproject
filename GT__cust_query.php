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

    $sql = "SELECT * FROM customers, orders
        WHERE customers.cust_name = '$cust_name' and customers.cust_phone = '$cust_phone' and shopID = '$shop_id' and orders.custID = customers.custID;";
} else {
    $sql = "SELECT * FROM orders
        JOIN customers ON orders.custID = customers.custID
        WHERE shopID = '$shop_id';";
}

$result = mysqli_query($link, $sql);
if (mysqli_num_rows($result) > 0) {
?>

<form method="post" action=cust_query.php>
    <label>Name: </label><input type="text" name="name" placeholder="Enter name" required=true><br>
    <label>Phone Number: </label><input type="text" name="phoneno" placeholder="Enter phone number" required=true><br>
    <button type="submit" name="submit" value="Submit">Search</button>
</form>

<?php
    echo "<table border=1>";
    echo "<tr>
            <th>Customer ID</th>
            <th>Name</th>
            <th>Surname</th>
            <th>SNS Account</th>
            <th>Platform</th>
            <th>Address</th>
            <th>Phone Number</th>
            <th>Email</th>
        </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>" . $row["custID"] . "</td>
                <td>" . $row["cust_name"] . "</td>
                <td>" . $row["cust_surname"] . "</td>
                <td>" . $row["sns_account"] . "</td>
                <td>" . $row["sns_platform"] . "</td>
                <td>" . $row["cust_address"] . "</td>
                <td>" . $row["cust_phone"] . "</td>
                <td>" . $row["cust_mail"] . "</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "No results found.";
}
mysqli_close($link);
?>
