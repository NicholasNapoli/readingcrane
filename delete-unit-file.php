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
		
		$myunit = new unit;
		$myunit->getinfo($_POST['unitid']);
		$content .= $myunit->breadcrumb($_POST['unitid']);	
		
		$curcode = $_POST["code"];
		$uploadid = $_POST["uploadid"];
		
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
			#DELETE THE upload
			$myupload = new upload;
			$myupload->uploadid =  $uploadid;
			
			$content .= $myupload->delete_unit_file($uploadid);
				
			
			break;
		
		case 1:
			#show the listing to delete and verify
			$myupload = new upload;
			$myupload->getinfo($uploadid);
			
			$content .= '<form name="frmdelete" method="POST">';
			$content .= '<INPUT TYPE="hidden" name="code" Value="2">';
			$content .= '<INPUT TYPE="hidden" name="unitid" Value="'.$_POST['unitid'].'">';
			$content .= '<INPUT TYPE="hidden" name="uploadid" Value="' . $_POST["uploadid"] . '">';
			
			$content .= '<font class="announcement">Are you sure you want to delete the following upload?</font><br><br>';
		

			$content .= '<p><b>File ID:</b> '. $myupload->uploadid . '<br>';
			$content .= '<b>File Name:</b> '. $myupload->filename . '<br>';
			$content .= '<b>File Path:</b> '. $myupload->filepath . '<br>';
			$content .= '<b>File Type:</b> '. $myupload->filetype . '<br>';
			$content .= '<b>File Timestamp:</b> '. $myupload->timestamp . '<br></p>';

			$content .= '<br><br><input type="button" name="yes" value="Yes" onclick="javascript: mydelete();"></form>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			break;
		}

	

		unset($myrefer);
		unset($myerror);
		unset($myupload);
		$v_navigation = vertical_navigation();
		/* Template File  */
		require_once("templates/standard.php");
		
	}
		
		
?>
