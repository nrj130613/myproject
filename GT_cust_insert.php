<?php
session_start();
if(!isset($_SESSION['username'])){
    header("location:shop_login.php");
}
if(empty($_POST["submit"])) {
?>

<form action="cust_insert.php" method="POST">
Insert Customer information<p>
   	Customer ID <input type="text" name="cust_id"><p>
	Name <input type="text" name="cust_name"><p>
  	Surname <input type="text" name="cust_surname"><p>
	Account Name <input type="text" name="account"><p>
<?php
	$link = mysqli_connect("localhost", "root", "", "goodstracking");
	$query = "SELECT DISTINCT sns_platform FROM customers";
	$result = mysqli_query($link, $query);
	echo "SNS platform <select name=sns_platform>";
	while ($dbarr = mysqli_fetch_array($result)) 
	{
		echo "<option>$dbarr[sns_platform]</option>";	
	}
	echo "</select><p>";
?>
    Address <textarea name="cust_address" rows=5 cols=40></textarea><p>
    Phone Number <input type="text" name="cust_phone"><p>
    Email <input type="email" name="cust_mail"><p>
     	<input type="submit" name="submit" value="Submit">
     	<input type="reset" name="cancel" value="Reset">
</form>
<p>Old customer? <a href="order_insert.php">Insert Order</a></p>

<?php
}
else {
	$custID = $_POST["cust_id"];
	$cust_name = $_POST["cust_name"];
	$cust_surname = $_POST["cust_surname"];
	$sns_account = $_POST["account"];
	$sns_platform = $_POST["sns_platform"];
    $cust_address = $_POST["cust_address"];
    $cust_phone = $_POST["cust_phone"];
    $cust_mail = $_POST["cust_mail"];
	$link = mysqli_connect("localhost", "root", "", "goodstracking");
	$sql = "INSERT INTO customers 
			VALUES('$custID', '$cust_name', '$cust_surname', '$sns_account', '$sns_platform', '$cust_address', '$cust_phone', '$cust_mail');";
	$result = mysqli_query($link, $sql);
	if ($result)
	{	echo "การเพิ่มข้อมูลลงในฐานข้อมูลประสบความสำเร็จ<br>";
		mysqli_close($link);	
		echo "<button><a href='order_insert.php'>Insert New Order</a></button>";
		exit;
	} else {
		echo "ไม่สามารถเพิ่มข้อมูลใหม่ลงในฐานข้อมูลได้<br>";
		}
}
?>