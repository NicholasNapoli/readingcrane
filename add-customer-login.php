<?php

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
			$curcode = '';
		}
		
		$onload = '';
		$javascript = '
		<script language="JavaScript">
		
			var digits = "0123456789"; // Declaring required variables
			var phoneNumberDelimiters = "()- "; // non-digit characters which are allowed in phone numbers
			var validWorldPhoneChars = phoneNumberDelimiters + "+"; // characters which are allowed in international phone numbers (a leading + is OK)
			var minDigitsInIPhoneNumber = 10; // Minimum no of digits in an international phone no.
	
			function verify()
			{
				//First check to make sure fields are not null
				if (document.forms.frmemployee.fname.value == "" || document.forms.frmemployee.lname.value == "" || document.forms.frmemployee.login.value == "" || document.forms.frmemployee.newpassword.value == "" || document.forms.frmemployee.verifypassword.value == "") {
						alert ("Please make sure all required fields are filled in before proceeding");
						return false;
				}
	
				//Now check password length
				if (document.forms.frmemployee.newpassword.value.length < 6) {
					alert("Sorry, but your password must be a minimum of 6 characters in length");
					return false;
				}
	
				//Now check to make sure the passwords match
				if (document.forms.frmemployee.newpassword.value != document.forms.frmemployee.verifypassword.value) {
					alert ("Your passwords do not match! Please verify the passwords and try again");
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
	
				if (!checkUsername()) {
					return false;
				}
					
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
		
				function checkUsername() {
					var un_array = new Array();
';
					$strquery = "SELECT USERNAME FROM `users`";
					$result = mysql_query($strquery) or die("Query failed : " . mysql_error());
					$mycount = 0;

					if (mysql_num_rows($result) >= 1) {
						while ($row = mysql_fetch_array($result)) {
							$javascript .= 'un_array[' . $mycount . '] = new Object();' . $nl;
							$javascript .= 'un_array[' . $mycount . '].uname = \'' . $row[0] . '\';' . $nl;
							$mycount++;
						}
					}

        $javascript .= '
        
        			for(var i = 0; i < un_array.length; i++) {
            			if(document.forms.frmemployee.login.value == un_array[i].uname) {
            				alert("Sorry, but this username already exists.  Please select another username for this user");
                			return false;
            			}
       				}

       				return true;
   				}

				function userinfo() {
					document.forms.frmemployee.code.value = 1;
					document.forms.frmemployee.go.disabled = true;
					document.forms.frmemployee.submit();
				}
		</script>';

		switch ($curcode) {
			#we retrieved all necessary information, add them into the database now
			case "insert":
				$myuser = new user; 
				$myuser->username = $_POST["login"];
				$myuser->userpassword = $_POST["newpassword"];
				$myuser->firstname = capitalize($_POST["fname"]);
				$myuser->lastname = capitalize($_POST["lname"]);
				$myuser->mi = $_POST["mi"];
				$myuser->description = $_POST["description"];
				$myuser->phone = $_POST["phone"];
				$myuser->cell = $_POST["cell"];
				$myuser->email = $_POST["email"];
				$myuser->isvisible = 'Y';
				$myuser->userlevel = $_POST["userlevel"];
				$myuser->userlocation = '';
				
				if ($_POST["userlevel"] == "1")
				{
					$myuser->isadmin = 'Y';
				}
				if ($_POST["userlevel"] == "2")
				{
					$myuser->userlocation = $_POST["userlocation"];
				}
				
			
				$myuser->create_user();
			
	
				$content .= 'Your new user has been successfully saved into the system';
				break;
			   
			default:
			
			$checked5 = '';
			$checked6 = '';
			$checked7 = '';
			$checked8 = '';
			$checked9 = '';
			
			
			 # THIS IS WHERE WE WILL GET THE BASIC INFO FROM THE USER
			   $content .= '
			   <form name="frmemployee" method="POST" enctype="multipart/form-data" action="add-customer-login.php">
			   <input type="hidden" name="code" value="insert">
			   <table class="data">
			   
			  
			   <tr class="dataheader"><td align="Center" colspan=2>Account Information</td></tr>
			   <tr><td><font class="required">*Login Name:</font> </td><td><input type="text" name="login" size="30" maxlength="30" value=""></td></tr>
			   <tr><td><font class="required">*Password:</font> </td><td><input type="password" name="newpassword" size="30" maxlength="30" value="">&nbsp;* Must be a Minimum 6 characters in length</td></tr>
			   <tr><td><font class="required">*Verify Password:</font> </td><td><input type="password" name="verifypassword" size="30" maxlength="30" value=""></td></tr>
			  
			   </table>
			   <br>
			   <input type="button" class="mybutton" name="go" value="Create New User" onclick="javascript: verify();"><br><br>
			   <font class="required">* Indicates a required field</font>
			   </form>';
			   break;
			
		}
		
		unset($myrefer);
		unset($myerror);
		$v_navigation = vertical_navigation();
		/* Template File  */
		require_once("templates/standard.php");
	}
 
?>
