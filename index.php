<?php

  /* Site Variables */
	require_once("inc/settings.php");
	
	### START THE ERROR ARRAY ###
	$errorm = '';
	$loggedin = checkLogin();

	if($loggedin == 0) { // User is not already logged in
		### CHECK FOR USER INFORMATION IN POST ###
		if ((isset($_POST['user'])) && (isset($_POST['pass']))) {
		
			### CONVERT POST PASS TO MD5 ###
			$pass = md5(stripslashes($_POST['pass']));
			$username = $_POST['user'];
			
			### COMPARE AGAINST DATABASE, QUERY FOR NUMBER OF ROWS
			$sql = "SELECT UserID, username, email, admin FROM users 
			WHERE username = '". $username ."' AND userpassword = '".$pass."'";
			
			$result = mysql_query($sql) or die('Query failed. ' . mysql_error());
			
			$row_returned = mysql_num_rows($result);
			
			### ITERATE THROUGH RETURNED ROW AND ASSIGN USER VARIABLES ###
			$i=0;
			
			while ($i < $row_returned) {
					$_uid 		=	mysql_result($result,$i,"UserID");
					$_username 	=	mysql_result($result,$i,"username");
					$_email		=	mysql_result($result,$i,"email");
					$_admin	=	mysql_result($result,$i,"admin");
					$i++;
			}
			
			### DOES ROW EVALUATE TO 1?
			if ($row_returned == 1) {
				### THE USER ID AND PASSWORD MATCH, SET SESSION VARS, IF USER HAS ACTIVATED ACCT
			
					### THIS VAR TELLS OTHER PAGES WE ARE LOGGED IN ###
					$_SESSION['db_is_logged_in'] = true;
					
					### REGISTER SESSION VARIABLES
					session_register ("uid"); 			### USERS ID
					session_register ("username"); 		### USERNAME
					session_register ("email"); 		### EMAIL
					session_register ("admin"); 		### ADMIN LEVEL
					session_register("password");		### PASSWORD
			
					### GIVE VALUES TO SESSION_VARS FROM ABOVE ROW RETURNED WHILE
					$HTTP_SESSION_VARS ["uid"] 		= 	$_uid;
					$HTTP_SESSION_VARS ["username"] = 	$_username;
					$HTTP_SESSION_VARS ["email"] 	= 	$_email;
					$HTTP_SESSION_VARS ["admin"] 	= 	$_admin;
					$HTTP_SESSION_VARS ["password"] = 	$pass;
			
			
					### CHECK TO SEE IF THE USER WANTS TO BE REMEMBERED
					setcookie("ReadingCraneInspection1", $HTTP_SESSION_VARS ["username"], time()+60*60*24*100, "/");
					setcookie("ReadingCraneInspection2", $pass, time()+60*60*24*100, "/");
					
					### REDIRECT USER TO LOGGED IN PAGE.
					header('Location:dashboard.php');
				
			} else {
			   
			 ### LOGIN MISMATCH - THIS MESSAGE IS SHOWN WHEN THE LOGIN CREDENTIALS HAVE FAILED
			 $content = "The login was incorrect.";
			 
		}
		
	} else {
			
			/* Show the Login Form */
			$content = '
			<div id="login-form">
				<form method="post" action="index.php">
				
				Username: <input type="text" name="user" size="15">&nbsp;&nbsp;&nbsp;&nbsp;
				Password: <input name="pass" type="password" size="15">
		
				<input type="submit" value="Login">
				
				</form>
			</div>';

		}
		
	/* Template File  */
	require_once("templates/standard.php");
		
} else {
	### REDIRECT USER TO LOGGED IN PAGE.
	header('Location:dashboard.php');
}


 
?>
