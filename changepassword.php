<?

	#AUTHOR:  Joseph Jacobs
	#Date:    April 4, 2004
	#Page:    sc_changepassword.php
	#Purpose: This page will allow the changing of an employee password for the company
	#
	#The following is the list of codes we will use
	# 1 - User id is given, we want to edit passwords
	# 2 - New password was given, save it.
		
	require_once("inc/settings.php");
	
	if(checkLogin() == 0){
  		### REDIRECT USER
		header('Location:index.php');
	} else {		
		
		#declare any classes that we might need here
		$myrefer = new referer;
		$myerror = new errors;

		$javascript = '
		<script language="JavaScript">
		
		function verify() {
			if (document.forms.frmpassword.newpassword.value.length < 6) {
				alert ("Your new password can not be less than 6 characters in length.  Please try again");
				return false;
			}
		
			if (document.forms.frmpassword.newpassword.value != document.forms.frmpassword.verifypassword.value) {
				alert ("Your passwords do not match!  Please verify the passwords and try again");
				return false;
			}
		
			document.forms.frmpassword.code.value = 2;
			document.forms.frmpassword.go.disabled = true;
			document.forms.frmpassword.submit();
		}
		</script>';
		
		$content .= '<div id="message"></div>';
		
		$myuser = new user;
		
		#GET EMPLOYEE INFO LOADED
		$myuser->getinfo($_POST["suserid"]);
		$curcode = $_POST["code"];
		switch ($curcode) {
			
			# For Case 2, we change the password
			case 2:
				#SAVE NEW PASSWORD FOR THE USER
				$myuser->changepassword($_POST["newpassword"]);
				$content .= '<font class="announcement">The new password has been successfully saved into the system</font>';
				break;
			
			# Ask for the new password
			case 1:
				$content .= '<form name="frmpassword" method="POST">';
				$content .= '<input type="hidden" name="code" value="2">';
				$content .= '<input type="hidden" name="suserid" value="' . $_POST["suserid"] . '">';
				$content .= '<font class="user">Setting New Password for ' . $myuser->firstname . " " . $myuser->lastname . "</font><br><br>";
				$content .= '<table class="data" width="95%">';
				$content .= '<tr class="datasubheading"><td colspan="2">New Password</td></tr>';
				$content .= '<tr><td><font class="required">*Password:</font> </td><td><input type="password" name="newpassword" size="15" maxlength="15" value="' . $_POST["newpassword"] . '">&nbsp;&nbsp;* Must be a Minimum 6 characters in length</td></tr>';
				$content .= '<tr><td><font class="required">*Verify Password:</font> </td><td><input type="password" name="verifypassword" size="15" maxlength="15" value="' . $_POST["verifypassword"] . '"></td></tr>';
				$content .= '</table><br>';
				$content .= '<input type="button" class="mybutton" name="go" value="Change Password!" onclick="javascript: verify();"><br><br>';
				$content .= '<font class="required">* Indicates a required field</font>';
				$content .= '</form>';
			
				break;
		}

		unset($myrefer);
		unset($myerror);
		unset($myuser);

		$v_navigation = vertical_navigation();
		/* Template File  */
		require_once("templates/standard.php");
	}
	