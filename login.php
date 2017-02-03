<?php
session_start();

// connect to sql database
$conn = new mysqli('localhost','root','73j6T2%7$ehA3De*a9#PO^%ZXciIc$4C', 'advisors');
	
if ($conn->connect_error) 
{
 	die("Connection failed: " . $conn->connect_error);
} 

// get post data to enter to database
$username=$_POST['username']; 
$password=$_POST['password']; 
// some minimal security
$username = stripslashes($username);
$password = stripslashes($password);
$username = $conn->real_escape_string($username);
$password = $conn->real_escape_string($password);

//query the database
$sql="SELECT id FROM account WHERE username='{$username}' and password='{$password}' LIMIT 1";
$result=$conn->query($sql);
$count=$result->num_rows;

//save results
if($count==1) 
{
	$value = $result->fetch_object();
	$id = $value->id;
	$_SESSION["username"] = $username;
	$_SESSION["id"] = $id;
	
	header("Location: https://www.fastfishdevelopment.com/riskalyze/account.php");
}
else
{
	header("Location: https://www.fastfishdevelopment.com/riskalyze"); /* Redirect browser */
	exit();
}	
?>