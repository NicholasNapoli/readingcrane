<?

	#The following is the list of codes we will use
	# 1 - Verify deletion
	# 2 - Delete IT!

	require_once("inc/settings.php");
	
	if(checkLogin() == 0){
		### REDIRECT USER
		header('Location:index.php');
	} else {
		#declare any classes that we might need here
		$myrefer = new referer;
		$myerror = new errors;
		
		$curcode = $_POST["code"];
		$user_id = $_POST["suserid"];
		$filter = $_POST["filter"];
		
		$javascript = '
		<script language="JavaScript">
		
		function mydelete() {
			document.forms.frmdelete.code.value = 2;
			document.forms.frmdelete.submit();
		}
		
		function mycancel() {
			document.forms.frmdelete.code.value = 1;
			document.forms.frmdelete.action = "edit-users.php";
			document.forms.frmdelete.submit();
		}
		
		</script>';
		
		$content .= '<div id="message"></div>';



		switch ($curcode) {
		
		#we retrieved all necessary information, add them into the database now
		case 2:
			#DELETE THE LISTING
			$myuser = new user;
			$myuser->userid =  $user_id;
			
			$myuser->deleteuser();
				
			#ALL DONE, ANNOUNCE OUR COMPLETION
			$content .= '<font class="announcement">Your User has been successfully deleted!</font>';
		
			#NOW WHERE DO WE GO ONCE THE USER IS ADDED TO THE TABLE?
			break;
		
		case 1:
			#show the listing to delete and verify
			$myuser = new user;
			$myuser->getinfo($user_id);
			
			$content .= '<form name="frmdelete" method="POST">';
			$content .= '<INPUT TYPE="Hidden" Name="code" Value="2">';
			$content .= '<INPUT TYPE="Hidden" Name="filter" Value="' . $_POST["filter"] . '">';
			$content .= '<INPUT TYPE="Hidden" Name="suserid" Value="' . $_POST["suserid"] . '">';
			
			$content .= '<font class="announcement">Are you sure you want to delete the following User?</font><br><br>';
		
			#LETS DISPLAY A TABLE, AND SORT IT BY CITY
			$content .= '<table class="data" width="95%"><tr class="dataheader"><td>Name</td><td>Username</td></tr>';
			$content .= '<tr>';
			$content .= '<td>'. $myuser->lastname . ', ' . $myuser->firstname . '</td><td>' . $myuser->username . '</td></tr>';
			$content .= '</table></form>';
			$content .= '<input type="button" name="yes" value="Yes" onclick="javascript: mydelete();">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			$content .= '<input type="button" name="no" value="Cancel" onclick="javascript: mycancel();">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
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
