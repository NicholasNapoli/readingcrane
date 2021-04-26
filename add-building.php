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
				if (document.forms.frmbuilding.name.value == "") {
						alert ("Please make sure all required fields are filled in before proceeding");
						return false;
				}
		}
    
	</script>';

		switch ($curcode) {
			#we retrieved all necessary information, add them into the database now
			case "insert":
				$mybuilding = new building; 
				$mybuilding->name = $_POST["name"];
				$mybuilding->locationid = $_POST["locationid"];
				$mybuilding->create_building();
				header('Location: view-location.php?id='.$_POST['locationid']);
				break;
			   
			default:
			$mylocation = new location;
			$mylocation->getinfo($_GET['id']);
			
			$content .= $mylocation->breadcrumb($_GET['id']);	
			 # THIS IS WHERE WE WILL GET THE BASIC INFO FROM THE USER
			   $content .= '
			   <form name="frmbuilding" method="POST" action="add-building.php">
			   <input type="hidden" name="code" value="insert">
			   <table class="data">
			   <tr class="dataheader"><td align="Center" colspan=2>Building Information</td></tr>
			   <tr><td><font class="required">*Building Name:</font> </td><td><input type="text" name="name" size="25" maxlength="30" value="">
			   </table>
			   <input type="hidden" name="locationid" value="'.$_GET['id'].'">
			   <input type="submit" class="mybutton" name="go" value="Create New Building" onclick="javascript: verify();"><br><br>
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
