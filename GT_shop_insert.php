<?php
if(empty($_POST["submit"])) {
?>
<form action="shop_insert.php" method="POST">
Sign Up<p>
   	Shop ID <input type="text" name="shopID"><p>
    	Username <input type="text" name="username"><p>
	Password <input type="text" name="password"><p>
    	Email <input type="text" name="email"><p>
	Shop Name <input type="text" name="shop_name"><p>
  	Seller Name <input type="text" name="seller_name"><p>
	Seller Surname <input type="text" name="seller_surname"><p>
	Seller ID Number <input type="text" name="seller_idno"><p>
	Phone Number <input type="text" name="phoneno"><p>

     	<input type="submit" name="submit" value="Submit">
     	<input type="reset" name="cancel" value="Reset">
</form> 
<?php
}
else {  
	$shopID = $_POST["shopID"];
	$shopname = $_POST["shop_name"];
	$seller_name = $_POST["seller_name"];
	$seller_surname = $_POST["seller_surname"];
	$seller_idnumber = $_POST["seller_idno"];
    	$shop_username = $_POST["username"];
    	$shop_password = $_POST["password"];
    	$shop_email = $_POST["email"];
    	$seller_phoneno = $_POST["phoneno"];
	$link = mysqli_connect("localhost", "root", "", "goodstracking");
	$sql = "INSERT INTO shops 
			VALUES('$shopID', '$shopname', '$seller_name', '$seller_surname', '$seller_idnumber',
			'@$shop_username', '$shop_password', '$shop_email', '$seller_phoneno');";
	$result = mysqli_query($link, $sql);
	if ($result)
	{	echo "การเพิ่มข้อมูลลงในฐานข้อมูลประสบความสำเร็จ<br>";
		mysqli_close($link);
	}
	else
	{	echo "ไม่สามารถเพิ่มข้อมูลใหม่ลงในฐานข้อมูลได้<br>";
	}
}
?>
