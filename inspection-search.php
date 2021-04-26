<?php

	require_once("inc/settings.php");
	
	if(checkLogin() == 0){
		### REDIRECT USER
		header('Location:index.php');
	} else {
	
		$title = 'Inspection Search |';
	
		if((isset($_POST['search-term']) && $_POST['search-term']!='')) {
				
				$search_term = $_POST['search-term'];
				
				// Make the connnection.
				$dbc = mysql_connect (DB_HOST, DB_USER, DB_PASSWORD) OR die ('Could not connect to MySQL: ' . mysql_error() );
				
				// Select the database.
				mysql_select_db (DB_NAME, $dbc) OR die ('Could not select the database: ' . mysql_error() );
				
				$search_query = "SELECT * FROM inspections_header WHERE jobnumber LIKE '%$search_term%' 
				OR date LIKE '%$search_term%' OR customer LIKE '%$search_term%' OR autoindex LIKE '%$search_term%'";
				
				$c_switch = 0;
				
				// HTML Output	
				
				$pager = new PS_Pagination($dbc, $search_query, "8", "12");
		
				// PAGINATE Call
				$rs = $pager->paginate();
				
				$content .= "Showing Search Results For: <b>".$search_term."</b><br><br>";
				
				
				//Display the navigation
				$content.= $pager->renderFullNav();
				
				$thisuser = new user;
				$thisuser->getinfo($_SESSION['uid']);
				$userid = $thisuser->userid;
			
				if($rs!=FALSE) {
					
					$content .= '<table cellspacing="5px" border="0" style="width:730px;border:2px solid #EEE;">';
					
					// Loop with RS (above) to produce values
					while($row = mysql_fetch_assoc($rs)){
						if($c_switch % 2)
						{
							// I'm in an even row
							$bg_color = '#eeeeee';
						}else{
							// I'm in an odd row
							$bg_color = '#dddddd';
						}
						
						if($row['jobnumber']==''){
							$title = 'No Job Number';
						} else {
							$title = $row['jobnumber'];
						}
						
						// Inspection Created By
						
						$username_query = mysql_query('select `Firstname`, `Lastname` from users WHERE `UserID`='.$row['userid']);
						if($userarr = mysql_fetch_array($username_query)){
							$username = $userarr[0].' '.$userarr[1];
						} else {
							$username = 'NO USER';
						}
						
						$content .= '
						
						<tr>
							<td style="padding:4px;" align="left" bgcolor="'.$bg_color.'">
								<!--<a href="edit-inspection.php?form_page_jump=1&inspection_id='.$row['autoindex'].'" title="Edit This Inspection" style="color:blue;">--><b>'.$title.'</b><!--</a>-->
							</td>
							<td style="padding:4px;" bgcolor="'.$bg_color.'">Date: '.$row['date'].'&nbsp;</td>
							<td style="padding:4px;" bgcolor="'.$bg_color.'"><span style="color:#666">'.$username.'</span>';
										if ($_SESSION['userlevel']==1) {
											$content .= '<br><a href="view-inspections.php?byuser='.$row['userid'].'" style="font-size:.85em;">Filter by User</a>';
										}
							$content .= '&nbsp;</td>
							<td style="padding:4px;" bgcolor="'.$bg_color.'" align="center">';
							
							if ($row['unitid']==0) {

								$content .= '
								<form method="post" action="view-inspection.php" style="display:inline;">
									<input type="hidden" name="id" value="'.$row['autoindex'].'">
									<input type="submit" value="View Inspection">
								</form>';
									
							} else {
							
								$content .= '
								<form method="get" action="view-inspection.php" style="display:inline;">
									<input type="hidden" name="id" value="'.$row['autoindex'].'">
									<input type="submit" value="View Inspection">
								</form>';
									
							}
							

							
							$content .= '
							</td>
							<td style="padding:4px;" bgcolor="'.$bg_color.'" align="center">';
							
							if ( $_SESSION['userlevel'] != 4 ) {
								if ($row['lock']==NULL) {
									$content .= '<form method="post" action="edit-inspection.php" style="display:inline;">
									<input type="hidden" name="inspection_id" value="'.$row['autoindex'].'">
									<input type="hidden" name="inspection_type" value="'.$row['inspection_type'].'">
									<input type="hidden" name="cat_num" value="1">
									<input type="submit" value="Edit Inspection">	
									</form>';
								} else {
									$content .= '<input type="submit" value="Edit Inspection" disabled="disabled">';
								}
							}

						$content .= '</tr>';
						$c_switch++;
					}
					
					$content .= '</table>';
				} else {
					$content .= '0 results';
				}

		} else {
			$content .= '
			<h2>Search Inspections</h2>
			<form action="inspection-search.php" method="post" name="search" id="search">
			<label for="search-term">Search Term:</label>
			<input type="text" name="search-term" value="">
			<input type="submit" value="Search">
			</form>
			';
		}
		
		$v_navigation = vertical_navigation();
		
		/* Template File  */
		require_once("templates/standard.php");
		
	}		
?>
