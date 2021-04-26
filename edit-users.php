<?php

	require_once("inc/settings.php");

	if(checkLogin() == 0){
  		### REDIRECT USER
		header('Location:index.php');
	} else {
		
		#declare any classes that we might need here
		$myrefer = new referer;
		$myerror = new errors;
		$content = '';
		if (isset($_POST['code'])) {
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

    function changepassword(uid) {
    	document.forms.frmemployee.suserid.value = uid;
    	document.forms.frmemployee.action = "changepassword.php";
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
	document.forms.frmemployee.code.value = 3;
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
    	document.forms.frmemployee.filter.value = letter;
    	document.forms.frmemployee.code.value = 1;
    	document.forms.frmemployee.submit();
    }

    function ShowUser(uid) {
 	document.forms.frmemployee.suserid.value = uid;
    	document.forms.frmemployee.code.value = 2;
    	document.forms.frmemployee.submit();
    }

    function savechanges() {
    	document.forms.frmemployee.code.value = 3;
    	document.forms.frmemployee.go.disabled = true;
    	document.forms.frmemployee.submit();
    }
    
    function deletelisting(uid) {
      	document.forms.frmemployee.suserid.value = uid;
       	document.forms.frmemployee.code.value = 1;
       	document.forms.frmemployee.action = "delete-user.php";
       	document.forms.frmemployee.submit();
    }
    

</script>
		';

		
	switch ($curcode) {
//For Case 2, this means we are going to look at security requirements.  We load this first before 1
//because if there are any errors, we can flag them and force the code to 1 to reload the page
//and show where the errors are, we do not need this right now, because we have done all the
//error checking that we needed through javascript.

#we retrieved all necessary information, add them into the database now
case 3:
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
	
	if (isset($_POST['chkvisible']) && $_POST["chkvisible"] != "N") {
		$myuser->isvalid = 'Y';
	} else {
		$myuser->isvalid = 'N';
	}
	
    if ($_POST['userlevel']==1) {
		$myuser->isadmin = 'Y';
		$myuser->userlevel = 1;
		$myuser->userlocation = '';
	}
	
	if($_POST['userlevel']==2) {
		$myuser->isadmin = 'N';
		$myuser->userlevel = 2;
		$myuser->userlocation = $_POST['userlocation']; // User locations are only for Inspection Managers
	}
	
	if($_POST['userlevel']==3) {
		$myuser->isadmin = 'N';
		$myuser->userlevel = 3;
		$myuser->userlocation = '';
	}
	
	if($_POST['userlevel']==4) {
		$myuser->isadmin = 'N';
		$myuser->userlevel = 4;
		$myuser->userlocation = '';
	}
	
    $myuser->save_user();
 
    $content .= '<font class="announcement">Your changes have been successfully been made to ' . $_POST["fname"] . ' ' . $_POST["lname"] . '</font>';

    #NOW WHERE DO WE GO ONCE THE USER IS ADDED TO THE TABLE?
    break;

case 2:
    $myuser = new user;
    if ($myuser->getinfo($_POST["suserid"]) == FALSE) {
    	$content .= 'ERROR ' . $_POST["suserid"];
    	exit;
    }

#$content .= $_POST["suserid"];

    $content .= '<form name="frmemployee" method="POST" enctype="multipart/form-data" action="edit-users.php">';
    $content .= '<input type="hidden" name="code" value="3">';
    $content .= '<input type="hidden" name="first" value="' . $_POST["first"] . '">';
    $content .= '<input type="hidden" name="suserid" value="' . $_POST["suserid"] . '">';
    $content .= '<INPUT TYPE="Hidden" Name="first" Value="no">';

    #IF FIRST TIME LOADING, USE VALUES FROM THE DATABASE
    if ($_POST["first"] == "yes" || $_POST["first"] == "") {
	    #$content .= "FIRST";
	    $_POST["login"] = $myuser->username;
	    $_POST["fname"] = $myuser->firstname;
	    $_POST["lname"] = $myuser->lastname;
	    #$content .= "#" . $myuser->description . "#";
	    $_POST["description"] = $myuser->description;
	    $_POST["chkadmin"] = $myuser->isadmin;
	    $_POST["mi"] = $myuser->mi;
	    $_POST["phone"] = $myuser->phone;
	    $_POST["cell"] = $myuser->cell;
	    $_POST["email"] = $myuser->email;
	    //$_POST["sdate"] = $myuser->startdate;
	    $_POST["chkvisible"] = $myuser->isvalid;
		$_POST["userlevel"] = $myuser->userlevel;
		$_POST["userlocation"] = $myuser->userlocation;
	}

       $content .= '<a href="edit-users.php">Back To Users</a><br><table class="data" width="95%">';
       $content .= '<tr class="dataheader"><td align="Center" colspan=2>User Information</td></tr>';
       $content .= '<tr><td><font class="required">*First Name:</font> </td><td><input type="text" name="fname" size="25" maxlength="30" value="' . $_POST["fname"] . '">';
       $content .= ' &nbsp;&nbsp;<font class="required">*Last Name:</font> <input type="text" name="lname" size="25" maxlength="40" value="' . $_POST["lname"] . '"></td></tr>';
       $content .= '<tr><td>MI:</td><td><input type="text" name="mi" size=1 maxlength=1 value="' . $_POST["mi"] . '"></td></tr>';
       $content .= '<tr><td>Phone: </td><td><input type="text" name="phone" size="12" maxlength="12" value="' . $_POST["phone"] . '">&nbsp;&nbsp;&nbsp;###-###-#### Format please</td></tr>';
       $content .= '<tr><td>Cell Phone: </td><td><input type="text" name="cell" size="12" maxlength="12" value="' . $_POST["cell"] . '">&nbsp;&nbsp;&nbsp;###-###-#### Format please</td></tr>';
       $content .= '<tr><td>Email: </td><td><input type="text" name="email" size="50" maxlength="50" value="' . $_POST["email"] . '"></td></tr>';
       $content .= '<tr><td colspan=2 align="left">User Description: </td></tr>';
       $content .= '<tr><td colspan=2 align="left"><TEXTAREA NAME="description" ROWS=8 COLS=50>' . $_POST["description"] . '</textarea></td></tr>';
       $content .= '<tr><td colspan="2">&nbsp;</td></tr>';
       $content .= '<tr class="dataheader"><td align="Center" colspan=2>Account Information</td></tr>';
       $content .= '<tr><td><font class="required">*Login Name:</font> </td><td><input type="text" size="30" maxlength="30" value="' . $_POST["login"] . '" DISABLED>
       <INPUT TYPE="HIDDEN" NAME="login" VALUE="' . $_POST["login"] . '">
       &nbsp;&nbsp;&nbsp;*Note: A Users username can not be modified</a></td></tr>';
       $content .= '<tr><td colspan="2">&nbsp;</td></tr>';
       $content .= '<tr class="dataheader"><td align="Center" colspan=2>User Security</td></tr>';
       $content .= '<tr><td align="left" colspan="2">';
	   
	   // $content .= '<input type="checkbox" name="chkadmin" ';
       if(($_POST['chkadmin'] == 'Y') || ($_POST['userlevel'] == 1)) { $checked1 = 'CHECKED'; } else {$checked1 = '';}
	   if($_POST['userlevel'] == '2') { $checked2 = 'CHECKED'; } else {$checked2 = '';}
	   if($_POST['userlevel'] == '3') { $checked3 = 'CHECKED'; } else {$checked3 = '';}
	   if($_POST['userlevel'] == '4') { $checked4 = 'CHECKED'; } else {$checked4 = '';}
	   
	   if($_POST['userlocation'] == 'reading') { $checked5 = 'CHECKED'; } else {$checked5 = '';}
	   if($_POST['userlocation'] == 'baltimore') { $checked6 = 'CHECKED'; } else {$checked6 = '';}
	   if($_POST['userlocation'] == 'williamsport') { $checked7 = 'CHECKED'; } else {$checked7 = '';}
	   if($_POST['userlocation'] == 'winchester') { $checked8 = 'CHECKED'; } else {$checked8 = '';}
	   if($_POST['userlocation'] == 'wilmington') { $checked9 = 'CHECKED'; } else {$checked9 = '';}
	   
	   $content .='
			<fieldset>
			<legend><b>Select User Level:</b></legend><br>
			<input id="admin" type="radio" name="userlevel" value="1" '.$checked1.'/>
			<label for="admin">Administrator</label>

			<input id="inspmanager" type="radio" name="userlevel" value="2" '.$checked2.'/>
			<label for="inspmanager">Inspection Manager</label>

			<input id="inspector" type="radio" name="userlevel" value="3" '.$checked3.'/>
			<label for="inspector">Inspector</label>

			<input id="sales" type="radio" name="userlevel" value="4" '.$checked4.'/>
			<label for="sales">Sales</label>
			</fieldset></td></tr>';
		
		if ($_POST['userlevel'] ==	2) {	
			$content .='
			
			   <tr class="dataheader"><td align="Center" colspan=2>User Location</td></tr>
			   <tr><td align="left" colspan="2">
				<fieldset>
				<legend><b>Select User\'s Primary Location (Inspection Managers Only):</b></legend><br>
				<input id="reading" type="radio" name="userlocation" value="reading" '.$checked5.'/>
				<label for="reading">Reading, PA</label>
				<input id="baltimore" type="radio" name="userlocation" value="baltimore" '.$checked6.'/>
				<label for="baltimore">Baltimore, MD</label>
				<input id="williamsport" type="radio" name="userlocation" value="williamsport" '.$checked7.'/>
				<label for="williamsport">Williamsport, PA</label>
				<input id="winchester" type="radio" name="userlocation" value="winchester" '.$checked8.'/>
				<label for="winchester">Winchester, PA</label>
				<input id="wilmington" type="radio" name="userlocation" value="wilmington" '.$checked9.'/>
				<label for="wilmington">Wilmington, DE</label>
				</fieldset>
				</td></tr>';
		} else {
			$content .= '
			<tr><td align="left" colspan="2"><input type="hidden" name="userlocation" value="">
			</td></tr>';
		}
	
       
       $content .= '<tr><td align="left" colspan="2"><input type="checkbox" name="chkvisible" ';
              if($_POST["chkvisible"] == 'Y')
              		$content .= 'CHECKED';
       $content .= '>Valid user</td></tr>';
       
       $content .= '</table><br>';
       $content .= '<input type="button" class="mybutton" name="go" value="Save Changes to User!" onclick="javascript: verify();"><br><br>';
       $content .= '<font class="required">* Indicates a required field</font>';
   $content .= '</form>';
        
   break;

case 1:
    #show the letters available of the last names
    $link = dbconnect();
    $strquery = "SELECT DISTINCT SUBSTRING(UPPER(LASTNAME), 1, 1) AS LETTER FROM `users`";
    $result = mysql_query($strquery, $link) or die("LETTER Query failed : " . mysql_error());

    $content .= '<form name="frmemployee" method="POST" action="edit-users.php">';
    $content .= '<INPUT TYPE="Hidden" Name="code" Value="2">';
    
    if (isset($_POST["filter"])) { 	$filter = $_POST["filter"];	} else { $filter = ''; }
    if (isset($_POST["suserid"])) { 	$suserid = $_POST["suserid"];	} else { $suserid = ''; }
   	
   	
   	$content .= '<INPUT TYPE="Hidden" Name="filter" Value="' . $filter . '">';
    $content .= '<INPUT TYPE="Hidden" Name="suserid" Value="' . $suserid . '">';
    $content .= '<INPUT TYPE="Hidden" Name="first" Value="yes">';

    $rowcount = 0;
    $content .= '<font class="announcement">Choose the letter of the last name of the user you wish to edit</font><br><br>';

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
    $strquery = "SELECT USERNAME, FIRSTNAME, LASTNAME, UserID, ADMIN FROM `users` ";
    if ($_POST["filter"] == "all")
    	$strquery .= " ORDER BY LASTNAME, FIRSTNAME";
    else
    	$strquery .= " WHERE SUBSTRING(UPPER(LASTNAME), 1, 1) = '" . $_POST["filter"] . "' ORDER BY LASTNAME, FIRSTNAME";

    $result = mysql_query($strquery, $link) or die("USER Query failed : " . mysql_error());

    $content .= '<table class="data" width="95%"><tr class="dataheader"><td>Name</td>
    <td>Username</td><td>Quick Options</td></tr>';
    $mycount = 0;	

    while ($row = mysql_fetch_array($result)) {
    	if ($row[4] == 'Y')
    		$content .= '<tr class="admin" style="font-weight:bold;">';
    	elseif (($mycount % 2) == 0)
    		$content .= '<tr class="dataalt">';
    	else
	      	$content .= '<tr>';

        $content .= '<td><a href="javascript: ShowUser(' . $row[3] . ');">' . $row[2] . ', ' . $row[1] . '</a></td>
        <td>' . $row[0] . '</td><td>
        <a href="javascript: changepassword(' . $row[3] . ');">Change Password</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
        <a href="javascript: deletelisting(' . $row[3] . ');">Delete</a> ';
        $content .= '</td>';
        $content .= '</tr>';
	$mycount++;
    }

    $content .= '</table></form>';
    $content .= '<p class="subheading">NOTE: Please click on the users name to edit their profile.</p>';

	break;
}
	
		
		
		
		
		
		
		unset($myrefer);
		unset($myerror);
		$v_navigation = vertical_navigation();
		/* Template File  */
		require_once("templates/standard.php");
	}
 
?>
