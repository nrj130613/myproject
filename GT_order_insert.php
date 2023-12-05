<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if(!isset($_SESSION['username'])){
    header("location:shop_login.php");
}
if(empty($_POST["submit"])) {
?>

<form action=order_insert.php method="post">
Insert New Order Details<p>
    Order Lot: <input type="text" name="order_lot"><p>
    Customer Name: <input type="text" name="cust_name"><p>
    Phone Number: <input type="text" name="cust_phone"><p>
    Product Name: <input type="text" name="order_product"><p>
    Quantity: <input type="text" name="amount"><p>
    Price: <input type="text" name="price"><p>
    Order Date: <input type="date" name="order_date"><p>
        <input type="submit" name="submit" value="Submit">
        <input type="reset" name="cancel" value="Reset">
</form>

<?php
} else {
    if(isset($_POST['submit'])){
    // Get the customer name and phone number from the form
    $name = $_POST['cust_name'];
    $phone = $_POST['cust_phone'];

    // Query the database to retrieve the customer ID
    $link = mysqli_connect("localhost", "root", "", "goodstracking");
    $query = "SELECT custID FROM customers WHERE cust_name = '$name' AND cust_phone = '$phone';";
    $result = mysqli_query($link, $query);

    if(mysqli_num_rows($result) == 1){
        // Get the customer ID
        $row = mysqli_fetch_assoc($result);
        $custID = $row['custID'];

        // Get the other order information from the form
        $order_lot = $_POST['order_lot'];
        $shopID = $_SESSION['shopID'];
        $order_product = $_POST['order_product'];
        $order_amount = $_POST['amount'];
        $order_price = $_POST['price'];
        $order_date = $_POST['order_date'];
        $order_latest_status = 'รับคำสั่งซื้อ';

        // Insert the order into the database
        $link = mysqli_connect("localhost", "root", "", "goodstracking");
        $query = "INSERT INTO orders
                VALUES (null, '$shopID', '$custID', '$order_product', '$order_date', $order_amount, $order_price, '$order_latest_status', DATE(NOW()), null, '$order_lot');";
        mysqli_query($link, $query);

        // Redirect to the dashboard page
        header("location:dashboard.php");
        exit;
    } else {
        // Display an error message if the customer is not found
        echo "Customer not found.";
    }
}
}
?>