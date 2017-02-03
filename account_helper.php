<?php
session_start();

//if not logged in, redirect
if( isset( $_SESSION["id"]) == false ) 
{
	header("Location: https://www.fastfishdevelopment.com/riskalyze");
	exit();
}	

//handle post data function call from ajax
if ( isset( $_POST["update_screens"]) && $_POST["update_screens"] === "1")
{
	update_screens();
}

function get_screens()
{	
	$conn = new mysqli('localhost','root','73j6T2%7$ehA3De*a9#PO^%ZXciIc$4C', 'advisors');
	if ($conn->connect_error) 
	{
	 	die("Connection failed: " . $conn->connect_error);
	} 

	$username=$_SESSION['username']; 
	$id=$_SESSION['id']; 
	$all_screens = array();
	
	$sql="SHOW COLUMNS FROM screens";
	
	$result=$conn->query($sql);
	
	if ($result) 
	{
		while ($row = $result->fetch_assoc()) 
		{
			array_push($all_screens, $row["Field"]);
		}
	}
	$conn->close();
	return $all_screens;
	}
	
function display_screens($screens)
{
	for ( $i = 1; $i < count($screens); $i++)
	{
		echo "<input id='$screens[$i]' name='checkbox' value='$screens[$i]' type='checkbox' onchange='update_array(this)' ;>  $screens[$i] <br/>";  
	}
	echo "<button type='button' name='update' formtarget='_self' onclick='update_screens_db()' >Submit</button>";
}

function update_screens()
{
	$screens = get_screens();
	$values = array_fill(1, count($screens)-1, 0);
	$id=$_SESSION['id']; 

	
	//loop over  screens to show
	for ( $i = 0; $i < count($_POST["input"]); $i++)
	{
		//get index of screen
		$key = array_search( $_POST["input"][$i], $screens);
		// update values from index (maintaining order)
		$values[$key] = $i+1;
		
	}
	//now values holds the data we want to insert into our database
	$conn = new mysqli('localhost','root','73j6T2%7$ehA3De*a9#PO^%ZXciIc$4C', 'advisors');
	
	
    	if ($conn->connect_error) 
	{
	 	die("Connection failed: " . $conn->connect_error);
	} 

	$screen_statements = "";
	for ( $i = 1; $i < count($screens); $i++)
	{
	 	$screen_statements = $screen_statements . " $screens[$i]=$values[$i]";
	 
		 if ( $i < count($screens) -1 )
		 {
			 $screen_statements = $screen_statements . ', ';
		 }
	}
	
	$sql = "UPDATE screens SET $screen_statements WHERE id=$id";
	
	if ($conn->query($sql) === TRUE) 
	{
    		echo "<br/>SUCCESS: SQL 'screens' table now contains:<br/>";
		echo $screen_statements;
	} 
	else 
	{
    		echo "Error updating " . $conn->error;
	}

	$conn->close();
}
	
?>
