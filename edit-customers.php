<?php

	require_once("inc/settings.php");

	if(checkLogin() == 0){
  		### REDIRECT USER
		header('Location:index.php');
	} else {
		$title = 'Edit Customer |';
		#declare any classes that we might need here
		$myrefer = new referer;
		$myerror = new errors;
		
		$content = '';
		
		if (isset($_GET['id']) && $_GET['id']!='') {
			$curcode = 2;
		} elseif (isset($_POST['code'])) {
			$curcode = $_POST["code"];
		} else {
			$curcode = 1;
		}
		
		$onload = '';
		
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

   

			function verify() {
				//First check to make sure fields are not null
				if (document.forms.frmcustomer.name.value == "") {
						alert ("Please make sure all required fields are filled in before proceeding");
						return false;
				}

		 

			document.forms.frmcustomer.go.disabled = true;
			document.forms.frmcustomer.code.value = 3;
			document.forms.frmcustomer.first.value = "no";
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
			// Search through string\'s characters one by one.
			// If character is not in bag, append to returnString.
			for (i = 0; i < s.length; i++)
			{
				// Check that current character isn\'t whitespace.
				var c = s.charAt(i);
				if (bag.indexOf(c) == -1) returnString += c;
			}
			return returnString;
		}

		function checkInternationalPhone(strPhone){
			s=stripCharsInBag(strPhone,validWorldPhoneChars);
			return (isInteger(s) && s.length >= minDigitsInIPhoneNumber);
		}

		   
		function Filter(letter) {
			document.forms.frmcustomer.filter.value = letter;
			document.forms.frmcustomer.code.value = 1;
			document.forms.frmcustomer.submit();
		}

		function ShowCustomer(uid) {
		document.forms.frmcustomer.customerid.value = uid;
			document.forms.frmcustomer.code.value = 2;
			document.forms.frmcustomer.submit();
		}

		function savechanges() {
			document.forms.frmcustomer.code.value = 3;
			document.forms.frmcustomer.go.disabled = true;
			document.forms.frmcustomer.submit();
		}
    
		function deletecustomer(uid) {
			document.forms.frmcustomer.customerid.value = uid;
			document.forms.frmcustomer.code.value = 1;
			document.forms.frmcustomer.action = "delete-customer.php";
			document.forms.frmcustomer.submit();
		}

	</script>';

		
	switch ($curcode) {
		//For Case 2, this means we are going to look at security requirements.  We load this first before 1
		//because if there are any errors, we can flag them and force the code to 1 to reload the page
		//and show where the errors are, we do not need this right now, because we have done all the
		//error checking that we needed through javascript.

		#we retrieved all necessary information, add them into the database now
		case 3:
			#SAVE CHANGES
			$mycustomer = new customer;
			$mycustomer->customerid = $_POST['customerid'];
			$mycustomer->name = $_POST["name"];
			$mycustomer->address = $_POST["address"];
			$mycustomer->contact = $_POST["contact"];
			$mycustomer->phone = $_POST["phone"];
			if($mycustomer->save_customer()){
				header("Location:view-customer.php?id=".$mycustomer->customerid);
			} else {
			//$content .= '<font class="announcement">Your changes have been successfully been made to Customer: ' . $_POST["name"] .'</font>';
				$content .= 'Error: Problem saving customer data';
			}
			#NOW WHERE DO WE GO ONCE THE USER IS ADDED TO THE TABLE?
			break;

		case 2:
			
			
			$mycustomer = new customer;	
			
			if((isset($_GET['id']))&&($_GET['id']!='')) {
				$_POST["customerid"] = $_GET["id"];
				$_POST["first"] = '';
			}
			
			if ($mycustomer->getinfo($_POST["customerid"]) == FALSE){
				$content .= 'ERROR ' . $_POST["customerid"];
				exit;
			}
			
			$content .= $mycustomer->breadcrumb($_POST["customerid"]);	

			$content .= '<form name="frmcustomer" method="POST" action="edit-customers.php">';
			$content .= '<input type="hidden" name="code" value="3">';
			$content .= '<input type="hidden" name="first" value="' . $_POST["first"] . '">';
			$content .= '<input type="hidden" name="customerid" value="' . $_POST["customerid"] . '">';
			$content .= '<INPUT TYPE="Hidden" Name="first" Value="no">';

			#IF FIRST TIME LOADING, USE VALUES FROM THE DATABASE
			if ($_POST["first"] == "yes" || $_POST["first"] == "") {
				$_POST['name'] = $mycustomer->name;
				$_POST['address'] = $mycustomer->address;
				$_POST['contact'] = $mycustomer->contact;
				$_POST['phone'] = $mycustomer->phone;				
			}

			 $content .= '
			   <table class="data">
			   <tr class="dataheader"><td align="Center" colspan=2>Customer Information</td></tr>
			   <tr><td><font class="required">*Customer Name:</font> </td><td><input type="text" name="name" size="25" maxlength="30" value="'.$_POST['name'].'">
			   <tr><td>Address:</td><td><input type="text" name="address" size="50" maxlength="50" value="'.$_POST['address'].'"></td></tr>
			   <tr><td>Contact:</td><td><input type="text" name="contact" size="50" maxlength="50" value="'.$_POST['contact'].'"></td></tr>			   
			   <tr><td>Phone: </td><td><input type="text" name="phone" size="12" maxlength="12" value="'.$_POST['phone'].'">&nbsp;&nbsp;&nbsp;###-###-#### Format please</td></tr>
			   </table>
			   <input type="button" class="mybutton" name="go" value="Save Customer Information" onclick="javascript: verify();"><br><br>
			   <font class="required">* Indicates a required field</font>
			   </form>';
				
		   break;

		case 1:
			#show the letters available of the last names
			$link = dbconnect();
			$strquery = "SELECT DISTINCT SUBSTRING(UPPER(NAME), 1, 1) AS LETTER FROM `customers`";
			$result = mysql_query($strquery, $link) or die("CUSTOMER LETTER Query failed : " . mysql_error());

			$content .= '<form name="frmcustomer" method="POST" action="edit-customers.php">';
			$content .= '<INPUT TYPE="Hidden" Name="code" Value="2">';
			
			if (isset($_POST["filter"])) { 	$filter = $_POST["filter"];	} else { $filter = ''; }
			if (isset($_POST["customerid"])) { 	$customerid = $_POST["customerid"];	} else { $customerid = ''; }
			
			
			$content .= '<INPUT TYPE="Hidden" Name="filter" Value="' . $filter . '">';
			$content .= '<INPUT TYPE="Hidden" Name="customerid" Value="' . $customerid . '">';
			$content .= '<INPUT TYPE="Hidden" Name="first" Value="yes">';

			$rowcount = 0;
			$content .= '<font class="announcement">Customers (Alphabetical)</font><br><br>';

			while ($row = mysql_fetch_array($result)) {
				if ($rowcount == 0 && $filter == null)
					$_POST["filter"] = $row[0];

				if ($rowcount != 0)
					$content .= ' | ';

				if ($_POST["filter"] != $row[0])
					$content .= ' <A HREF="javascript: Filter(\'' . $row[0] . '\');">' . $row[0] . '</A> ';
				else
					$content .= ' ' . $row[0] . ' ';

				$rowcount++;
			}

			if ($_POST["filter"] == "all")
				$content .= '&nbsp;&nbsp;&nbsp;&nbsp;Show All';
			else
				$content .= '&nbsp;&nbsp;&nbsp;&nbsp;<A HREF="javascript: Filter(\'all\');">Show All</A>';
			$content .= '<br><br>';

			#LETS DISPLAY A TABLE, AND SORT IT ALPHABETICALLY, THEY CAN VIEW EVERYONE AT ONE TIME IF THEY LIKE
			#Fix the query based on the letter they chose to sort by
			$strquery = "SELECT id, name, address FROM `customers` ";
			if ($_POST["filter"] == "all")
				$strquery .= " ORDER BY name";
			else
				$strquery .= " WHERE SUBSTRING(UPPER(NAME), 1, 1) = '" . $_POST["filter"] . "' ORDER BY name, address";

			$result = mysql_query($strquery, $link) or die("Customer Query failed : " . mysql_error());

			$content .= '<table class="data" width="95%"><tr class="dataheader"><td>Customer</td>
			<td>Address</td><td>Options</td></tr>';
			$mycount = 0;	

			while ($row = mysql_fetch_array($result)) {
				if (($mycount % 2) == 0)
					$content .= '<tr class="dataalt">';
				else
					$content .= '<tr>';

				$content .= '<td><a href="javascript: ShowCustomer('.$row[0].');">'.$row[1].'</a></td>
				<td>' . $row[2] . '</td><td>
				<a href="javascript: deletecustomer(' . $row[0] . ');">Delete</a> ';
				$content .= '</td>';
				$content .= '</tr>';
			$mycount++;
			}

			$content .= '</table></form>';
			$content .= '<p class="subheading">NOTE: Please click on the customer name to edit information.</p>';

			break;
		}
		
		unset($myrefer);
		unset($myerror);
		$v_navigation = vertical_navigation();
		/* Template File  */
		require_once("templates/standard.php");
	}
 
?>
