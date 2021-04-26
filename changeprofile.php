<?
	#AUTHOR:  Joseph Jacobs
	#Date:    March 30, 2004
	#Page:    sc_employee_edit.php
	#Purpose: This page will allow the editing of employess of the company
	#
	#The following is the list of codes we will use
	# 1 - User was selected, show there user info
	# 2 - Save all the new user info

	require_once("inc/settings.php");
	
	if(checkLogin() == 0){
  		### REDIRECT USER
		header('Location:index.php');
	} else {
		
		#declare any classes that we might need here
		$myrefer = new referer;
		$myerror = new errors;

		if (isset($_POST['code'])) {
			$curcode = $_POST["code"];
		} else {
			$curcode = 1;
		}
			
		$javascript = '
		<script language="JavaScript">
			// Declaring required variables
			var digits = "0123456789";
			// non-digit characters which are allowed in phone numbers
			var phoneNumberDelimiters = "()- ";
			// characters which are allowed in international phone numbers
			// (a leading + is OK)
			var validWorldPhoneChars = phoneNumberDelimiters + "+";
			// Minimum no of digits in an international phone no.
			var minDigitsInIPhoneNumber = 10;
		
			function changepassword(uid) {
				document.forms.frmemployee.suserid.value = uid;
				document.forms.frmemployee.action = "sc_changepassword.php";
				document.forms.frmemployee.code.value = 1;
				document.forms.frmemployee.submit();
			}
		
			function verify() {
				//First check to make sure fields are not null
				if (document.forms.frmemployee.fname.value == "" ||
					document.forms.frmemployee.lname.value == "" ||
					document.forms.frmemployee.login.value == ""
					) {
						alert ("Please make sure all required fields are filled in before proceeding");
						return false;
				}
		
				var Phone=document.forms.frmemployee.phone
				var Cell=document.forms.frmemployee.cell
		
				if ((Phone.value!=null)&&(Phone.value!="")){
					if (checkInternationalPhone(Phone.value)==false){
						alert("Please Enter a Valid Phone Number");
						Phone.value="";
						Phone.focus();
						return false;
					}
				}
		
				if ((Cell.value!=null)&&(Cell.value!="")){
					if (checkInternationalPhone(Cell.value)==false){
						alert("Please Enter a Valid Phone Number");
						Cell.value="";
						Phone.focus();
						return false;
					}
				}
		
				document.forms.frmemployee.go.disabled = true;
			document.forms.frmemployee.code.value = 2;
			document.forms.frmemployee.first.value = "no";
			document.forms.frmemployee.submit();
			}
		
			function isInteger(s)
			{
				var i;
				for (i = 0; i < s.length; i++)
				{
					// Check that current character is number.
					var c = s.charAt(i);
					if (((c < "0") || (c > "9"))) return false;
				}
				// All characters are numbers.
				return true;
			}
		
			function stripCharsInBag(s, bag)
			{
				var i;
				var returnString = "";
				// Search through strings characters one by one.
				// If character is not in bag, append to returnString.
				for (i = 0; i < s.length; i++)
				{
					// Check that current character isnt whitespace.
					var c = s.charAt(i);
					if (bag.indexOf(c) == -1) returnString += c;
				}
				return returnString;
			}
		
			function checkInternationalPhone(strPhone){
				s=stripCharsInBag(strPhone,validWorldPhoneChars);
				return (isInteger(s) && s.length >= minDigitsInIPhoneNumber);
			}

		
			function ShowUser(uid) {
			document.forms.frmemployee.suserid.value = uid;
				document.forms.frmemployee.code.value = 1;
				document.forms.frmemployee.submit();
			}
		
			function savechanges() {
				document.forms.frmemployee.code.value = 2;
				document.forms.frmemployee.go.disabled = true;
				document.forms.frmemployee.submit();
			}

		</script>
		';
				
		$content .= '<div id="message"></div>';
		$userid = $_SESSION['uid'];

		#GET EMPLOYEE INFO LOADED
		//$myuser->getinfo($userid);
		
		switch ($curcode) {
			//For Case 2, this means we are going to look at security requirements.  We load this first before 1
			//because if there are any errors, we can flag them and force the code to 1 to reload the page
			//and show where the errors are, we do not need this right now, because we have done all the
			//error checking that we needed through javascript.
			
			#we retrieved all necessary information, add them into the database now
			case 2:
				#SAVE CHANGES
			
				$myuser = new user;
				
				$myuser->username = $_POST["login"];
				$myuser->firstname = capitalize($_POST["fname"]);
				$myuser->lastname = capitalize($_POST["lname"]);
				$myuser->mi = $_POST["mi"];
				$myuser->description = $_POST["description"];
				$myuser->phone = $_POST["phone"];
				$myuser->cell = $_POST["cell"];
				$myuser->email = $_POST["email"];
				$myuser->userid = $_POST["suserid"];
				$myuser->isvisible = 'Y';
				
				if ($_POST["chkadmin"] != "" && $_POST["chkadmin"] != "N") $myuser->isadmin = 'Y';
				
			
				$myuser->save_user();
			 
				
				$content .=  '<font class="announcement">Your changes have been successfully been made, <b>' . $_POST["fname"] . ' ' . $_POST["lname"] . '</b></font>';
				break;
			
			case 1:
			
				$myuser = new user;
				
				if ($myuser->getinfo($userid) == FALSE) {
					$content .=  'ERROR ' . $_POST["suserid"];
					exit;
				}
				
				if(!isset($_POST['first'])) {
					$first = 'yes';
				} else {
					$first = $_POST['first'];
				}
				
				$content .=  '<form name="frmemployee" method="POST" enctype="multipart/form-data">';
				$content .=  '<input type="hidden" name="code" value="2">';
				$content .=  '<input type="hidden" name="first" value="' . $first . '">';
				$content .=  '<input type="hidden" name="suserid" value="' . $userid . '">';
				$content .=  '<INPUT TYPE="Hidden" Name="first" Value="no">';
			
				#IF FIRST TIME LOADING, USE VALUES FROM THE DATABASE
				if ($first == "yes" || $first == '') {
					$_POST["login"] = $myuser->username;
					$_POST["fname"] = $myuser->firstname;
					$_POST["lname"] = $myuser->lastname;
					$_POST["description"] = $myuser->description;
					$_POST["chkadmin"] = $myuser->isadmin;
					$_POST["mi"] = $myuser->mi;
					$_POST["phone"] = $myuser->phone;
					$_POST["cell"] = $myuser->cell;
					$_POST["email"] = $myuser->email;
					$_POST["sdate"] = $myuser->startdate;
					$_POST["chkvisible"] = $myuser->isvalid;
					$_POST['suserid'] = $userid;
				}
			
				   $content .=  '<table class="data" width="95%">';
				   $content .=  '<tr class="dataheader"><td align="Center" colspan=2>User Information</td></tr>';
				   $content .=  '<tr><td><font class="required">*First Name:</font> </td><td><input type="text" name="fname" size="25" maxlength="30" value="' . $_POST["fname"] . '">';
				   $content .=  ' &nbsp;&nbsp;<font class="required">*Last Name:</font> <input type="text" name="lname" size="25" maxlength="40" value="' . $_POST["lname"] . '"></td></tr>';
				   $content .=  '<tr><td>MI:</td><td><input type="text" name="mi" size=1 maxlength=1 value="' . $_POST["mi"] . '"></td></tr>';
				   $content .=  '<tr><td>Phone: </td><td><input type="text" name="phone" size="12" maxlength="12" value="' . $_POST["phone"] . '">&nbsp;&nbsp;&nbsp;###-###-#### Format please</td></tr>';
				   $content .=  '<tr><td>Cell Phone: </td><td><input type="text" name="cell" size="12" maxlength="12" value="' . $_POST["cell"] . '">&nbsp;&nbsp;&nbsp;###-###-#### Format please</td></tr>';
				   $content .=  '<tr><td>Email: </td><td><input type="text" name="email" size="50" maxlength="50" value="' . $_POST["email"] . '"></td></tr>';
				   $content .=  '<tr><td colspan=2 align="left">User Description: </td></tr>';
				   $content .=  '<tr><td colspan=2 align="left"><TEXTAREA NAME="description" ROWS=8 COLS=50>' . $_POST["description"] . '</textarea></td></tr>';
				   $content .=  '<tr><td colspan="2">&nbsp;</td></tr>';
				   $content .=  '<tr class="dataheader"><td align="Center" colspan=2>Account Information</td></tr>';
				   $content .=  '<tr><td><font class="required">*Login Name: <span style="color:#333;">' . $_POST["login"] . '</span></font>
				   </td><td><input type="hidden" name="login" size="30" maxlength="30" value="' . $_POST["login"] . '">&nbsp;&nbsp;&nbsp;*Note: Usernames cannot be modified</a></td></tr>';
				   $content .=  '<tr><td colspan="2">&nbsp;</td></tr>';
				   
				   if($_SESSION['admin']=='Y'){
					   $content .=  '<tr class="dataheader"><td align="Center" colspan=2>User Security</td></tr>';
					   $content .=  '<tr><td align="left" colspan="2"><input type="checkbox" name="chkadmin" ';
					   if($_POST["chkadmin"] == 'Y')
							$content .=  'CHECKED';
					   $content .=  '>Please check to make user an Administrator.</td></tr>';
					   
					   $content .=  '<tr><td align="left" colspan="2"></td></tr>';
				   } else {
				   		$content .='
				   		<input type="hidden" name="chkvisible" value="'.$_POST["chkvisible"].'">
				   		<input type="hidden" name="chkadmin" value="'.$_POST["chkadmin"].'">';
				   		

						
				   }
				   
				   
				    $content .=  '<tr><td colspan="2">';
				   
				   
				  $content .= '&nbsp;</td></tr>';
				   $content .=  '</table><br>';
				   $content .=  '<input type="button" class="mybutton" name="go" value="Save Changes to User!" onclick="javascript: verify();"><br><br>';
				   $content .=  '<font class="required">* Indicates a required field</font>';
			   $content .=  '</form>';
					
			   break;
		}
		
		
		// End Switch
		
		
		unset($myrefer);
		unset($myerror);
		unset($myuser);
		$v_navigation = vertical_navigation();
		/* Template File  */
		require_once("templates/standard.php");
	}
	
	

?>
