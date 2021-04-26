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
		$customer_id = $_POST["customerid"];
		$filter = $_POST["filter"];
		
		$javascript = '
		<script language="JavaScript">
		
		function mydelete() {
			document.forms.frmdelete.code.value = 2;
			document.forms.frmdelete.submit();
		}
		
		function mycancel() {
			document.forms.frmdelete.code.value = 1;
			document.forms.frmdelete.action = "edit-customers.php";
			document.forms.frmdelete.submit();
		}
		
		</script>';
		
		$content .= '<div id="message"></div>';



		switch ($curcode) {
		
		#we retrieved all necessary information, add them into the database now
		case 2:
			#DELETE THE LISTING
			$mycustomer = new customer;
			$mycustomer->customerid =  $customer_id;
			
			$mycustomer->deletecustomer();
				
			#ALL DONE, ANNOUNCE OUR COMPLETION
			$content .= '<font class="announcement">Customer has been successfully deleted!</font>';
		
			#NOW WHERE DO WE GO ONCE THE USER IS ADDED TO THE TABLE?
			break;
		
		case 1:
			#show the listing to delete and verify
			$mycustomer = new customer;
			$mycustomer->getinfo($customer_id);
			
			$content .= '<form name="frmdelete" method="POST">';
			$content .= '<INPUT TYPE="Hidden" Name="code" Value="2">';
			$content .= '<INPUT TYPE="Hidden" Name="filter" Value="' . $_POST["filter"] . '">';
			$content .= '<INPUT TYPE="Hidden" Name="customerid" Value="' . $_POST["customerid"] . '">';
			
			$content .= '<font class="announcement">Are you sure you want to delete the following customer?</font><br><br>';
		
			#LETS DISPLAY A TABLE, AND SORT IT BY CITY
			$content .= '<table class="data" width="95%"><tr class="dataheader"><td>Name</td><td></td></tr>';
			$content .= '<tr>';
			$content .= '<td>'. $mycustomer->name . ', ' . $mycustomer->address . '</td><td></td></tr>';
			$content .= '</table></form>';
			$content .= '<input type="button" name="yes" value="Yes" onclick="javascript: mydelete();">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			$content .= '<input type="button" name="no" value="Cancel" onclick="javascript: mycancel();">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			break;
		}

	

		unset($myrefer);
		unset($myerror);
		unset($mycustomer);
		$v_navigation = vertical_navigation();
		/* Template File  */
		require_once("templates/standard.php");
		
	}
		
		
?>
