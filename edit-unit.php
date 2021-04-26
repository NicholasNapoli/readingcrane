<?php
	/* EDIT UNIT */
	
	require_once("inc/settings.php");

	if(checkLogin() == 0){
  		### REDIRECT USER
		header('Location:index.php');
	} else {
		$title = 'Edit Unit |';
		#declare any classes that we might need here
		$myrefer = new referer;
		$myerror = new errors;
		
		$content = '';
		
		if ((isset($_GET['id'])) && ($_GET['id']!='')) {
			$curcode = 1;
		} elseif (isset($_POST['code'])) {
			$curcode = $_POST["code"];
		} else {
			header("Location: customer-management.php");
		}
		
		$onload = '';
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
		case 2:
			#SAVE CHANGES
			$myunit = new unit; 
			$myunit->unitid = $_POST["unitid"];
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
			
			if($myunit->save_unit()){
				header("Location:view-unit.php?id=".$_POST['unitid']."&message=update");
			} else {
				$content .= 'Error: Problem saving unit data';
			}
			
			break;

		case 1:
			
			
			$myunit = new unit;
			
			if((isset($_GET['id']))&&($_GET['id']!='')) {
				$_POST['id'] = $_GET['id'];
			}

			if ($myunit->getinfo($_POST["id"]) == FALSE){
				$content .= 'ERROR: DID NOT FIND UNIT #' . $_POST["unitid"];
				exit;
			}
			
			$myunit->getinfo($_POST["id"]);
			
			$content .= $myunit->breadcrumb($_POST["id"]);
			$unitid = $myunit->unitid;
			$name        =	$myunit->name;
			$buildingid  = 	$myunit->buildingid;
			$crane_mfg 	 = 	$myunit->crane_mfg;
			$approx_span = 	$myunit->approx_span;
			$power       =	$myunit->power;
			$serialnumber=	$myunit->serialnumber;
			$capacity    =	$myunit->capacity;
			$type        =	$myunit->type;
			$hoist_mfg   =	$myunit->hoist_mfg;
			$hoist_serialnumber = $myunit->hoist_serialnumber;
			$model_number =  $myunit->model_number;
			$approx_height = $myunit->approx_height;
			$unit_type =	strtoupper($myunit->unit_type);			

				
				if($unit_type == "HOIST") { 
					$mfg_label = 'Jib Crane - Monorail Mfg.';
				} else {
					$mfg_label = 'Crane Mfg.';
				}

				// Form HTML
				$form =  '<form name="frmunit" method="POST" action="edit-unit.php" onSubmit="return headerValidation()">
				<table width="720px" border="1">
				<tr class="dataheader" >
					<td align="center" colspan="4"><div align="center"> Unit Information (TYPE: '.$unit_type.')<span style="padding:4px;"><br>
					<span style="font-weight:bold;">All Header Fields Are Required</span><br>
					</span></div></td>
				</tr>
			  
				  <tr>
					<td style="padding:4px;" align="left" colspan="2"><div align="right">Name / Number
					  <input name="name" type="text" tabindex="1" value="'.$name.'">
					</div></td>
				  <td colspan="2" align="left" style="padding:4px;"><div align="right">'.$mfg_label.'
					<input type="text" name="crane_mfg" tabindex="2"  value="'.$crane_mfg.'">
				  </div></td>
				  </tr>
				<tr>
				  <td style="padding:4px;" align="left" colspan="2"><div align="right">Serial No.
					<input type="text" name="serialnumber" tabindex="3"  value="'.$serialnumber.'">
				  </div></td>
				  <td width="26%" align="left" style="padding:4px;"><div align="right">Model No.
					  <input type="text" name="model_number" size="10" tabindex="4" value="'.$model_number.'">
				  </div></td>
					<td style="padding:4px;"><div align="right">Hoist Mfg.
					  <input type="text" name="hoist_mfg" tabindex="5" value="'.$hoist_mfg.'">
				  </div></td>
				</tr>
				<tr>						
					<td style="padding:4px;" colspan="2">
					  <div align="right">Power 
						<input type="text" name="power" size="10" tabindex="6" value="'.$power.'">
				  </div></td>
					<td style="padding:4px;"><div align="right">Capacity
					  <input type="text" name="capacity" size="10" tabindex="7" value="'.$capacity.'">
					</div></td>
					<td style="padding:4px;" align="left"><div align="right">Type
						<input type="text" name="type" tabindex="8" value="'.$type.'">
					</div></td>			
				</tr>
				  
				  <tr>
				<td style="padding:4px;" align="left" colspan="2"><div align="right">Hoist Serial No.
					<input type="text" name="hoist_serialnumber" tabindex="9" value="'.$hoist_serialnumber.'">
				  </div></td>
				  
					<td style="padding:4px;" align="left"><div align="right">Approx. Height
					  <input type="text" name="approx_height" tabindex="10" style="width:50px;" value="'.$approx_height.'">
					</div></td>
				  
					<td align="center" style="padding:4px;"><div align="right">Approx. Span
					  <input type="text" name="approx_span" tabindex="11" style="width:50px;" value="'.$approx_span.'">
					</div></td>
				  </tr>
				  <tr>
				  <td colspan="5">
				<font class="required" style="float:left">* Indicates a required field</font>
				  
								<div align="right">
								  <input type="hidden" name="code" value="2">
							 <input type="hidden" name="unitid" value="'.$unitid.'">
								  <input type="hidden" name="buildingid" value="'.$buildingid.'">
								  <input type="hidden" name="unit_type" value="'.$unit_type.'">
								  <input type="submit" class="mybutton"  tabindex="11" value="SAVE '.$unit_type.' UNIT DATA">
							   </div></td>
				  </tr>
				</table>
				</form>';

			$content .= $form;
				
		   break;
		}
		
		unset($myrefer);
		unset($myerror);
		$v_navigation = vertical_navigation();
		/* Template File  */
		require_once("templates/standard.php");
	}
 
?>
