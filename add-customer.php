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
				if (document.forms.frmcustomer.name.value == "") {
						alert ("Please make sure all required fields are filled in before proceeding");
						return false;
				}
	
				document.forms.frmcustomer.submit();
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

		</script>';

		switch ($curcode) {
			#we retrieved all necessary information, add them into the database now
			case "insert":
				$mycustomer = new customer; 
				$mycustomer->name = $_POST["name"];
				$mycustomer->address = $_POST["address"];
				$mycustomer->contact = $_POST["contact"];
				$mycustomer->phone = $_POST["phone"];
			
				$mycustomer->create_customer();
			
				$content .= 'Your new customer has been successfully saved into the system';
				break;
			   
			default:
			 # THIS IS WHERE WE WILL GET THE BASIC INFO FROM THE USER
			   $content .= '
			   <form name="frmcustomer" method="POST" action="add-customer.php">
			   <input type="hidden" name="code" value="insert">
			   <table class="data">
			   <tr class="dataheader"><td align="Center" colspan=2>Customer Information</td></tr>
			   <tr><td><font class="required">*Customer Name:</font> </td><td><input type="text" name="name" size="25" maxlength="30" value="">
			   <tr><td>Address:</td><td><input type="text" name="address" size="50" maxlength="50" value=""></td></tr>
			   <tr><td>Contact:</td><td><input type="text" name="contact" size="50" maxlength="50" value=""></td></tr>		   
			   <tr><td>Phone: </td><td><input type="text" name="phone" size="12" maxlength="12" value="">&nbsp;&nbsp;&nbsp;###-###-#### Format please</td></tr>
			   </table>
			   <input type="submit" class="mybutton" name="go" value="Create New Customer" onclick="javascript: verify();"><br><br>
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
