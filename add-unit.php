<?php

	/* Add - Unit
	*/
	
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
		
		
		$javascript = '
		<script language="javascript" type="text/javascript">
			function headerValidation(){

				var name = document.frmunit.name
				var crane_mfg = document.frmunit.crane_mfg
				var approx_span = document.frmunit.approx_span
				var power = document.frmunit.power
				var serialnumber = document.frmunit.serialnumber
				var capacity = document.frmunit.capacity
				var type = document.frmunit.type
				var hoist_mfg = document.frmunit.hoist_mfg
				var hoist_serialnumber = document.frmunit.hoist_serialnumber
				var model_number = document.frmunit.model_number
				var approx_height = document.frmunit.approx_height

				
				if ((name.value==null)||(name.value=="")){
					alert("Please enter a Unit Name / Number.")
					name.focus()
					return false
				}
				
				if ((crane_mfg.value==null)||(crane_mfg.value=="")){
					alert("Please enter a crane manufacturer.")
					crane_mfg.focus()
					return false
				}
				
				if ((approx_span.value==null)||(approx_span.value=="")){
					alert("Please enter an approximate span.")
					approx_span.focus()
					return false
				}
				
				if ((power.value==null)||(power.value=="")){
					alert("Please enter a power value.")
					power.focus()
					return false
				}
				
				if ((serialnumber.value==null)||(serialnumber.value=="")){
					alert("Please enter a serial number.")
					serialnumber.focus()
					return false
				}
				
				if ((capacity.value==null)||(capacity.value=="")){
					alert("Please enter a capacity value.")
					capacity.focus()
					return false
				}
				
				if ((type.value==null)||(type.value=="")){
					alert("Please enter a type.")
					type.focus()
					return false
				}
				
				if ((hoist_mfg.value==null)||(hoist_mfg.value=="")){
					alert("Please enter a hoist manufacturer.")
					hoist_mfg.focus()
					return false
				}
				
				if ((hoist_serialnumber.value==null)||(hoist_serialnumber.value=="")){
					alert("Please enter a hoist serial number.")
					hoist_serialnumber.focus()
					return false
				}
				
				if ((model_number.value==null)||(model_number.value=="")){
					alert("Please enter a model number.")
					model_number.focus()
					return false
				}
				
				if ((approx_height.value==null)||(approx_height.value=="")){
					alert("Please enter an approximate height.")
					approx_height.focus()
					return false
				}	
				
				return true
			}
			
		</script>';
		

		switch ($curcode) {
			#we retrieved all necessary information, add them into the database now
			case "insert":
				
				$myunit = new unit; 
				$myunit->name = $_POST["name"];
				$myunit->buildingid = $_POST["buildingid"];
				$myunit->crane_mfg = $_POST["crane_mfg"];
				$myunit->approx_span = $_POST["approx_span"];
				$myunit->power = $_POST["power"];
				$myunit->serialnumber = $_POST["serialnumber"];
				$myunit->capacity = $_POST["capacity"];
				$myunit->type = $_POST["type"];
				$myunit->hoist_mfg = $_POST["hoist_mfg"];
				$myunit->hoist_serialnumber = $_POST["hoist_serialnumber"];
				$myunit->model_number = $_POST["model_number"];
				$myunit->approx_height = $_POST["approx_height"];
				$myunit->unit_type = $_POST["unit_type"];
				$myunit->create_unit();
				header('Location: view-building.php?id='.$_POST['buildingid']);
				break;
			
			case "display_unit_form":
			
				$mybuild = new unit;
				$mybuild->getinfo($_POST['buildingid']);
				$content .= $mybuild->breadcrumb($_POST['buildingid'], TRUE);	

				$unit_type = strtoupper($_POST['unit_type']);
				
				if($unit_type == "HOIST") { 
					$mfg_label = 'Jib Crane - Monorail Mfg.';
				} else {
					$mfg_label = 'Crane Mfg.';
				}

				// Form HTML
				$form =  '<form name="frmunit" method="POST" action="add-unit.php" onSubmit="return headerValidation()">
				<table width="720px" border="1">
				<tr class="dataheader" >
					<td align="center" colspan="4"><div align="center"> Unit Information (TYPE: '.$unit_type.')<span style="padding:4px;"><br>
					<span style="font-weight:bold;">All Header Fields Are Required</span><br>
					</span></div></td>
				</tr>
				<tr>
					<td style="padding:4px;" align="left" colspan="2"><div align="right">Name / Number
					  <input name="name" type="text" tabindex="1">
					</div></td>
				  <td colspan="2" align="left" style="padding:4px;"><div align="right">'.$mfg_label.'
					<input type="text" name="crane_mfg" tabindex="2">
				  </div></td>
				  </tr>
				<tr>
				  <td style="padding:4px;" align="left" colspan="2"><div align="right">Serial No.
					<input type="text" name="serialnumber" tabindex="3">
				  </div></td>
				  	
				  <td width="25%" align="left" style="padding:4px;"><div align="right">Model No.
					  <input type="text" name="model_number" size="10" tabindex="4">
				  </div></td>
					<td style="padding:4px;"><div align="right">Hoist Mfg.
					  <input type="text" name="hoist_mfg" tabindex="5">
				  </div></td>
				</tr>
				<tr>						
					<td style="padding:4px;" colspan="2">
					  <div align="right">Power 
						<input type="text" name="power" size="10" tabindex="6">
				  </div></td>
					<td style="padding:4px;"><div align="right">Capacity
					  <input type="text" name="capacity" size="10" tabindex="7">
					</div></td>
					<td style="padding:4px;" align="left"><div align="right">Type
						<input type="text" name="type" tabindex="8">
					</div></td>			
				</tr>
				<tr>
				<td style="padding:4px;" align="left" colspan="2"><div align="right">Hoist Serial No.
					<input type="text" name="hoist_serialnumber" tabindex="9">
				  </div></td>
				  
					<td style="padding:4px;" align="left"><div align="right">Approx. Height
					  <input type="text" name="approx_height" tabindex="10" style="width:50px;">
					</div></td>
				  
					<td align="center" style="padding:4px;"><div align="right">Approx. Span
					  <input type="text" name="approx_span" tabindex="11" style="width:50px;">
					</div></td>
				  </tr>
				  <tr>
				  <td colspan="5">
				<font class="required" style="float:left">* Indicates a required field</font>
				  
								<div align="right">
								  <input type="hidden" name="code" value="insert">
								  <input type="hidden" name="buildingid" value="'.$_POST['buildingid'].'">
								  <input type="hidden" name="unit_type" value="'.$_POST['unit_type'].'">
								  <input type="submit" class="mybutton"  tabindex="11" value="CREATE NEW '.$unit_type.' UNIT">
							   </div></td>
				  </tr>
				</table>
				</form>';

				
				$content .= $form;
				
				break;
				
			default:
			
				$mybuild = new building;
				$mybuild->getinfo($_GET['id']);
				$content .= $mybuild->breadcrumb($_GET['id'], TRUE);	
			 // SELECT A UNIT TYPE
			   $content .= '
				<form name="frmunit" method="POST" action="add-unit.php">
			   <input type="hidden" name="code" value="display_unit_form">
			   <input type="hidden" name="buildingid" value="'.$_GET['id'].'">
				Select a unit type:
				<select name="unit_type">
					<option value="crane">Crane</option>
					<option value="hoist">Hoist</option>
				</select>
				<input type="submit" value="Set Unit Type" style="padding:2px;">
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
