<?
	#AUTHOR:  Joseph Jacobs
	#Date:    April 4, 2004
	#Page:    sc_changepassword_user.php
	#Purpose: This page will allow the changing of an employee password for a specified user but they must verify thier old password.
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
		$myuser = new user;
		
		if (isset($_POST['code'])) {
			$curcode = $_POST["code"];
		} else {
			$curcode = 1;
		}
		
		if (!isset($_POST['oldpassword'])){
			$_POST['oldpassword'] = '';
		}
		if (!isset($_POST['newpassword'])){
			$_POST['newpassword'] = '';
		}
		if (!isset($_POST['verifypassword'])){
			$_POST['verifypassword'] = '';
		}
			
		$javascript = '
		<script language="JavaScript">

		function tryagain(uid) {
			document.forms.frmpassword.suserid.value = uid;
			document.forms.frmpassword.code.value = 1;
			document.forms.frmpassword.submit();
		}
		
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
		$userid = $_SESSION['uid'];

		#GET EMPLOYEE INFO LOADED
		$myuser->getinfo($userid);
		
		switch ($curcode) {
		//For Case 2, we are going to actually change the password and then go back to the edit employee page.
		
		#we retrieved all necessary information, add them into the database now
		case 2:
			#First we have to verify that the old password matches for this user
			if ($myuser->verifypassword($userid, $_POST["oldpassword"])) {
				#SAVE NEW PASSWORD FOR THE USER
				$myuser->changepassword($_POST["newpassword"]);
		
				$content .=  '<font class="announcement">Your new password has been successfully saved into the system</font>';
			}
			else {
				$content .=  '<form name="frmpassword" method="POST">';
				$content .=  '<input type="hidden" name="code" value="1">';
				$content .=  '<input type="hidden" name="suserid" value="' . $userid . '">';
				$content .=  '<font class="announcement">Your Current Password does not match what is currently in the system. 
				Please <a href="javascript: tryagain(' . $userid . ');">Click here</a> to try again.</font>';
				$content .=  '</form>';
			}
		
			break;
		
		#This is where we ask them for the new password
		case 1:
			$content .=  '<form name="frmpassword" method="POST">';
			$content .=  '<input type="hidden" name="code" value="2">';
			$content .=  '<input type="hidden" name="suserid" value="' . $userid . '">';
			$content .=  '<font class="user">Setting New Password for ' . $myuser->firstname . " " . $myuser->lastname . "</font><br><br>";
			$content .=  '<table class="data" width="95%">';
			$content .=  '<tr class="datasubheading"><td colspan="2">Old Password</td></tr>';
			$content .=  '<tr><td><font class="required">*Current Password:</font> </td><td><input type="password" name="oldpassword" size="15" maxlength="15" value="' . $_POST["oldpassword"] . '">&nbsp;&nbsp;&nbsp;</td></tr>';
			$content .=  '<tr class="datasubheading"><td colspan="2">New Password</td></tr>';
			$content .=  '<tr><td><font class="required">*Password:</font> </td><td><input type="password" name="newpassword" size="15" maxlength="15" value="' . $_POST["newpassword"] . '"> &nbsp;&nbsp;* Must be a Minimum 6 characters in length</td></tr>';
			$content .=  '<tr><td><font class="required">*Verify Password:</font> </td><td><input type="password" name="verifypassword" size="15" maxlength="15" value="' . $_POST["verifypassword"] . '"></td></tr>';
			$content .=  '</table><br>';
			$content .=  '<input type="button" class="mybutton" name="go" value="Change Password!" onclick="javascript: verify();"><br><br>';
			$content .=  '<font class="required">* Indicates a required field</font>';
			$content .=  '</form>';
			break;
		}
		unset($myrefer);
		unset($myerror);
		unset($myuser);
		$v_navigation = vertical_navigation();
		/* Template File  */
		require_once("templates/standard.php");
	}

?>