<head>
<title>ADVISOR ACCOUNT</title>
</head>
<body>
<h1>ADVISOR ACCOUNT PAGE!</h1>

<p id="screen_tmp_data"> Nothing Selected </p>
<p id="sql_tmp_data"/>

<!-- php helper class to get total number of questionnaire screens to display -->
<?php require 'account_helper.php';
//get list of all questionnaire screens for advisor to select
$all_screens = get_screens();
// display the list of screens to html
display_screens($all_screens);
?>


<!-- javascript to interpret submit button click -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript">

//list of screens added
var userInput = [];
	
// add items to list on checkbox click
function update_array(checkbox)
{
	// add item to list if checkbox clicked
        if (checkbox.checked)
        {
            userInput.push(checkbox.value);
        }
	// remove item from list if unchecked
        else
        {
	    var index = userInput.indexOf(checkbox.value);
            if (index > -1) 
	    {
	     	userInput.splice(index, 1);
	    }
        }

	// display item list to user
        var text = document.getElementById("screen_tmp_data").innerHTML  = "";
        for (var i in userInput)
        {
        	document.getElementById("screen_tmp_data").innerHTML = (document.getElementById("screen_tmp_data").innerHTML + " " + userInput[i]);
        }	
}

// submit button click listener
function update_screens_db()
{
	call_php_function();
}

//ajax to link javascript button clicks in order to store SQL data (in php)
function call_php_function() 
{
   //call the php account_helper.php file, pass post data to call specific function
   $.ajax({
      url:'account_helper.php',
      type: 'post',
      data: { "update_screens": "1", "input": userInput},
      complete: function (response) 
      {
          document.getElementById("sql_tmp_data").innerHTML = document.getElementById("sql_tmp_data").innerHTML + (response.responseText);
      },
      error: function () 
      {
       	   echo ("error");
      }
  });
  return false;
}
</script>


<p> Select the questionnaire screens to display to your clients. Screens will be shown in the order they are clicked. Press the submit button to save your questionnaire to the SQL database.</p>

<!-- button to go back to main screen -->
<form action="index.php" method="post"> 
<input type="submit" value="Go Back" />
</form>

</body>
</html>
