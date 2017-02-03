<html>
<body>
<?php session_unset()?>

<h1>Login to advisor account</h1>
<p> Enter your advisor username and password to add/remove questionnaire screens or change order</p>

<form action="login.php" method="post">
Username: <input type="text" name="username"><br>
Password:  <input type="text" name="password"><br>
<input type="submit">
</form>


<h1>Or take questionnaire</h1>
<p> Enter the name of your advisor to take their personalized questionnaire </p>

<form action="questionnaire.php" method="post">
Username: <input type="text" name="username"><br>
<input type="submit">
</form>


</body>
</html>