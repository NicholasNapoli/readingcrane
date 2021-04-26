<?php

	require_once("inc/settings.php");

	if(checkLogin() == 0){
  		### REDIRECT USER
		header('Location:index.php');
	} else {
		$title = 'Edit Location |';
		#declare any classes that we might need here
		$myrefer = new referer;
		$myerror = new errors;
		
		$content = '';
		
		if ((isset($_GET['id'])) || ($_GET['id']!='')) {
			$curcode = 1;
		} elseif (isset($_POST['code'])) {
			$curcode = $_POST["code"];
		} else {
			header("Location: customer-management.php");
		}
		
		$onload = '';
		
		$javascript = '
		<script language="JavaScript">
			function verify() {
				//First check to make sure fields are not null
				if (document.forms.frmlocation.name.value == "") {
						alert ("Please make sure all required fields are filled in before proceeding");
						return false;
				}
		}
    
	</script>';

		
	switch ($curcode) {

		#we retrieved all necessary information, add them into the database now
		case 2:
			#SAVE CHANGES
			$mylocation = new location;
			$mylocation->locationid = $_POST['locationid'];
			$mylocation->customerid = $_POST['customerid'];
			$mylocation->name = $_POST["name"];
			$mylocation->address = $_POST["address"];
			$mylocation->city = $_POST["city"];
			$mylocation->state = $_POST["state"];
			$mylocation->zip = $_POST["zip"];
			$mylocation->phone = $_POST["phone"];
			
			if($mylocation->save_location()){
				header("Location:view-location.php?id=".$_POST['locationid']."&message=update");
			} else {
			//$content .= '<font class="announcement">Your changes have been successfully been made to location: ' . $_POST["name"] .'</font>';
				$content .= 'Error: Problem saving location data';
			}
			#NOW WHERE DO WE GO ONCE THE USER IS ADDED TO THE TABLE?
			break;

		case 1:
			
			
			$mylocation = new location;
			
			if((isset($_GET['id']))&&($_GET['id']!='')) {
				$_POST["locationid"] = $_GET["id"];
			}
			
			if ($mylocation->getinfo($_POST["locationid"]) == FALSE){
				$content .= 'ERROR ' . $_POST["locationid"];
				exit;
			}
			
			$mylocation->getinfo($_POST["locationid"]);
			
			$content .= $mylocation->breadcrumb($_POST["locationid"]);

			$_POST['name'] = $mylocation->name;
			$_POST['customerid'] = $mylocation->customerid;
			$_POST['address'] = $mylocation->address;
			$_POST['city'] = $mylocation->city;
			$_POST['state'] = $mylocation->state;
			$_POST['zip'] = $mylocation->zip;
			$_POST['phone'] = $mylocation->phone;				

			$content .= '<form name="frmlocation" method="POST" action="edit-location.php">';
			$content .= '<input type="hidden" name="code" value="2">';
			$content .= '<input type="hidden" name="locationid" value="' . $_POST["locationid"] . '">';
			$content .= '<input type="hidden" name="customerid" value="' . $_POST['customerid'] . '">';
			$content .= '
			   <table class="data">
			   <tr class="dataheader"><td align="Center" colspan=2>Location Information</td></tr>
			   <tr><td><font class="required">*Location Name:</font> </td><td><input type="text" name="name" size="25" maxlength="30" value="'.$_POST['name'].'">
			   <tr><td>Address:</td><td><input type="text" name="address" size="50" maxlength="50" value="'.$_POST['address'].'"></td></tr>
			   <tr><td>City:</td><td><input type="text" name="city" size="50" maxlength="50" value="'.$_POST['city'].'"></td></tr>
			   <tr><td>State:</td><td><input type="text" name="state" size="50" maxlength="50" value="'.$_POST['state'].'"></td></tr>
			   <tr><td>Zip:</td><td><input type="text" name="zip" size="15" maxlength="15" value="'.$_POST['zip'].'"></td></tr>			   
			   <tr><td>Phone: </td><td><input type="text" name="phone" size="12" maxlength="12" value="'.$_POST['phone'].'">&nbsp;&nbsp;&nbsp;###-###-#### Format please</td></tr>
			   </table>
			   <input type="submit" class="mybutton" name="go" value="Save Location Information" onclick="javascript: verify();"><br><br>
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
