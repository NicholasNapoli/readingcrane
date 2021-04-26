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
				confirmSubmit();
				//First check to make sure fields are not null
				if (document.forms.frmupload.filename.value == "") {
						alert ("Please enter a friendly, descriptive filename");
						return false;
				}
				if (document.forms.frmupload.uploaded.value == "") {
						alert ("Please select a file to upload");
						return false;
				}
				confirmSubmit();
			}
			
			function confirmSubmit() {
				var agree=confirm("Uploading files to the Reading Crane system may take a while, during which the website may appear unresponsive. Please do not click the back button during upload.");
				if (agree) {
					return true;
				} else {
					return false;
				}
			}
		

    
		</script>';

		switch ($curcode) {
			#we retrieved all necessary information, add them into the database now
			case "upload":
				$myupload = new upload; 
				$myupload->filename = $_POST["filename"];
				$myupload->unitid = $_POST["unitid"];
				if($myupload->upload_unit_file())
				{
					header('Location: view-unit.php?id='.$_POST['unitid'].'#unit-files');
				} else {
					$content .= 'File upload error';
				}
				break;
			   
			default:
				$myunit = new unit;
				$myunit->getinfo($_GET['id']);
			
				$content .= $myunit->breadcrumb($_GET['id']);	

				$content .= '
				<form name="frmupload" method="POST" action="upload-unit-file.php" enctype="multipart/form-data" onsubmit="verify()">
				<input type="hidden" name="code" value="upload">
				<table class="data">
				<tr class="dataheader"><td align="Center" colspan=2>File Information</td></tr>
				<tr><td><font class="required">*File Name:</font> </td><td><input type="text" name="filename" size="25" maxlength="30" value=""></td></tr>
				<tr><td><font class="required">*Select File:</font><br>
				<span style="font-size:10px;">Supported File Extensions Include - Video (.mp4, .flv, .mov, .wmv) | Audio (.mp3) | Image (.jpg, .gif, .png) | Office (.doc, .docx, .xls, .xlsx, .pdf, .ppt)
				</span></td><td>
				<input name="uploaded" type="file" size="35" />
				</td></tr>
				<tr>
				<td><font class="required">* Indicates a required field</font></td>
				<td>
				<input type="hidden" name="unitid" value="'.$_GET['id'].'">
				<input type="submit" class="mybutton" name="go" value="Upload Unit File"></td></tr>
				</table>
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