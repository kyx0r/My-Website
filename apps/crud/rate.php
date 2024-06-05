<?php
require('config.php'); 
require('upload_function.php');

$item_id=$_GET['id'];
$sql_get_item = "SELECT * FROM crud_tbl WHERE item_id=$item_id";
$result = $makeconnection->query( $sql_get_item);
$row = $result->fetch_assoc();



if (isset($_POST['submit'])) {

$item_rating = $_POST['item_rating'] + $row["item_rating"];
$item_rating_cnt = $row["item_rating_cnt"] + 1;
$sql_modify = "UPDATE crud_tbl SET 
item_rating = '$item_rating',
item_rating_cnt = '$item_rating_cnt'
WHERE item_id = '$item_id'";
	
$result = $makeconnection->query( $sql_modify );

header ("Location: index.php");
}

?>


<!doctype html>
<html>
<head>
<meta charset="UTF-8">
			<meta content="width=device-width,initial-scale=1.0" name="viewport">	
<title>Rate Item</title>
<link href="style.css" rel="stylesheet" type="text/css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="jquery-validation/dist/jquery.validate.js"></script>
	<link href="jquery-validation/basic_form_styling.css" rel="stylesheet" type="text/css">
</head>

<body>
	<div id="container">
	<header>
		<h1>Item Catalog</h1>
		<p><a href="index.php">Back to Catalog</a></p>
	</header>

		<main>
		<form action="" method="post" enctype="multipart/form-data" id="add_item_form">
		<label>1</label>
		<input type="radio" value=1 name="item_rating" required/>
		<br>
		<label>2 </label>
		<input type="radio" value=2 name="item_rating" required/>
		<br>
		<label>3 </label>
		<input type="radio" value=3 name="item_rating" required/>
		<br>
		<label>4 </label>
		<input type="radio" value=4 name="item_rating"required/>
		<br>
		<label>5 </label>
		<input type="radio" value=5 name="item_rating" required/>
		<br>
		<label></label><input type="submit" name="submit" id="submit" value="Submit">
		</form>	
		<script>
		$("#add_item_form").validate();
		</script>
	  </main>
		<!--end main-->
	</div>
	<!--end container-->
</body>
</html>
