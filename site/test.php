<html>
	<head>
		<title>BAM...GRUZ</title>
		<link href="globalAssets/styles.css" rel="stylesheet" type="text/css">
	</head>
	<body>
<?		
require_once 'userAccess.php';
$user = new userAccess();

/*
if(!$user->is_loaded()) {
	$user->error("user not loaded");
} else {
	$user->error("user is loaded");
}
*/


// get variable names as they are in the register form
if(isset($POST["login"]))
{
	if(isset($_POST["log_uname"]) && isset($_POST["log_pword"]) && $user->login($_POST["log_uname"], $_POST["log_pword"], true, true))
	{
		// $data = array('name' => $reg_name, 'uname' => $reg_uname, 'pword' => $reg_pword, 'vpword' => $reg_verpword);
		// $user->checkData($data);
		
		$user->error("Login successful");

	}
	else
	{
		$user->error("Login failed, try again");
	}
}
else
{
	writeLogin();
}

function writeLogin()
{
	// print 
}


?>

<!--		
		<form action="<? echo $_SERVER['PHP_SELF']; ?>" method="post">
			<input type="text" name="reg_name" value="NAMEFIELD" id="signupform">
			<input type="text" name="reg_uname" value="USERNAMEFIELD" id="signupform">
			<input type="password" name="reg_pword" value="PASSWORDFIELD" id="signupform">
			<input type="password" name="reg_verpword" value="VERIFYPASSWORDFIELD" id="signupform">
			<p><input type="submit" value="Continue &rarr;"></p>
		</form>
	-->
		
		<form action="<? echo $_SERVER['PHP_SELF']; ?>" method="post">
			<input type="text" name="log_uname" id="loginform">
			<input type="password" name="log_pword" id="loginform">
			<p><input type="submit" name="login" value="Login &rarr;"></p>
		</form>
		
		
		
	</body>
</html>