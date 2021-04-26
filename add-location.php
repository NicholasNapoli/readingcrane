<?php

	require_once("inc/settings.php");

	if(checkLogin() == 0){
  		### REDIRECT USER
		header('Location:index.php');
	} else {
		
		#declare any classes that we might need here
		$myrefer = new referer;
		$myerror = new errors;

	
		if (isset($_GET['id']) && $_GET['id']!='') {
			$curcode = 1;
		} elseif (isset($_POST['code'])) {
			$curcode = $_POST['code'];
		} else {
			header('Location: customer-management.php');
		}
		
		$onload = '';
		$javascript = '
		<script language="JavaScript">
			function verify() {
				//First check to make sure fields are not null
				if (document.forms.frmlocation.name.value == "") {
						alert ("Please make sure all required fields are filled in before proceeding");
						return false;
				} else {

		}
	</script>';

		switch ($curcode) {
			#we retrieved all necessary information, add them into the database now
			case "insert":
				$mylocation = new location; 
				$mylocation->name = $_POST["name"];
				$mylocation->customerid = $_POST["customerid"];
				$mylocation->address = $_POST["address"];
				$mylocation->city = $_POST["city"];
				$mylocation->state = $_POST["state"];
				$mylocation->zip = $_POST["zip"];
				$mylocation->phone = $_POST["phone"];
				
				$mylocation->create_location();
				header('Location: view-customer.php?id='.$_POST['customerid']);
			
				$content .= 'Your new location has been successfully saved into the system';
				break;
			   
			default:
				$id = $_GET['id'];
				$mycust = new customer;
				$mycust->getinfo($id);
				$content .= $mycust->breadcrumb($id);
				
				# THIS IS WHERE WE WILL GET THE BASIC INFO FROM THE USER
				$content .= '
				<form name="frmlocation" method="POST" action="add-location.php">
				<input type="hidden" name="code" value="insert">
				<table class="data">
				<tr class="dataheader"><td align="Center" colspan=2>Location Information</td></tr>
				<tr><td><font class="required">*Location Name:</font> </td><td><input type="text" name="name" size="25" maxlength="30" value="">
				<tr><td>Address:</td><td><input type="text" name="address" size="50" maxlength="50" value=""></td></tr>
				<tr><td>City:</td><td><input type="text" name="city" size="50" maxlength="50" value=""></td></tr>
				<tr><td>State:</td><td><input type="text" name="state" size="50" maxlength="50" value=""></td></tr>
				<tr><td>Zip:</td><td><input type="text" name="zip" size="15" maxlength="15" value=""></td></tr>			   
				<tr><td>Phone: </td><td><input type="text" name="phone" size="12" maxlength="12" value="">&nbsp;&nbsp;&nbsp;###-###-#### Format please</td></tr>
				</table>
				<input type="hidden" name="customerid" value="'.$id.'">
				<input type="submit" class="mybutton" name="go" value="Create New location" onclick="javascript: verify();"><br><br>
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
