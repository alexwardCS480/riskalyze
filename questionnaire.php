<?php
session_start();

//check if passed in username is valid advisor username
check_username();

//get the SQL ordered list of how to display questionnaire screens
$ordered_screens = get_ordered_screens();
unset($ordered_screens[0]["id"]); // remove unused data

//if first time loading page
if ( isset($_POST["next_screen_data"]) == false || isset($_SESSION["current_screen"])== false) 
{
	// set index to beginnnig
	$_SESSION["current_screen"] = 1;
}

//if moving to next screen
if (isset($_POST['next_screen_data'])) 
{
    $_SESSION["current_screen"] = $_SESSION["current_screen"] + 1;
}

// load screen
show_next_screen($ordered_screens[0]);


// functions

//display the questionnaire screen (based on SQL database order)
function show_next_screen($ordered_screens)
{	
	//get the name of item 1, 2, 3... n
	$key = array_search($_SESSION["current_screen"], $ordered_screens);
	
	//no more screens, go back
	if ( empty($key) )
	{
		header("Location: https://www.fastfishdevelopment.com/riskalyze"); 
	}
	
	//get the corresponding html file content
	$filename = explode('_', $key);
	$html = file_get_contents('https://fastfishdevelopment.com/riskalyze/screens/'.$filename[0].'/'.$filename[0].'_'.$filename[1].'.html');	
	// display the content
	echo 'Questionnaire Filename: '.$filename[0].'_'.$filename[1].'<br/>';
	echo $html;
}


// verify advisor username is valid, save advisor id for later use
function check_username()
{
	// dont recheck database if only loading next page
	if ( isset($_POST["next_screen_data"]) == false )
	{
		// connect to database
		$conn = new mysqli('localhost','root','73j6T2%7$ehA3De*a9#PO^%ZXciIc$4C', 'advisors');
	
    	if ($conn->connect_error) 
		{
   		 	die("Connection failed: " . $conn->connect_error);
		} 
		
		$username = $_POST['username']; 
		
		//$username = stripslashes($username);
		$username = $conn->real_escape_string($username);
		
		$sql="SELECT id FROM account WHERE username='{$username}' LIMIT 1";
		$result=$conn->query($sql);
		$count = $result->num_rows;
		
		//if username is valid, save data
		if($count==1) 
		{
			$value = $result->fetch_object();
			$id = $value->id;
			$_SESSION["username"] = $username;
			$_SESSION["id"] = $id;
		}
		// invalid username, redirect
		else
		{
			header("Location: https://www.fastfishdevelopment.com/riskalyze"); 
			exit();
		}	
		$conn->close();
	}
}
	
// get the advisors ordering of questionnaire screens
function get_ordered_screens()
	{	
	// connect to database
	$conn = new mysqli('localhost','root','73j6T2%7$ehA3De*a9#PO^%ZXciIc$4C', 'advisors');

	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	} 

	$id=$_SESSION['id']; 
	$all_screens = array();
	
	$sql="SELECT * FROM screens WHERE id=$id";
	$result=$conn->query($sql);
	
	if ($result->num_rows > 0) 
	{
		while ($row = $result->fetch_assoc()) 
		{
			array_push($all_screens, $row);
		}
	}
	return $all_screens;
	}
?>