<?php
	require_once("../includes/config.inc.php");
	$f->redirectBase = WEBSITE_URL;
	$f->isLogin('_admin','index.php');
	define("T","tbl_reg_profile_info");
	
	if(empty($_GET['ref_reg_id']) == false)
	{
		$_SESSION['this_ref_reg'] = $_GET['ref_reg_id'];
	}	
	if(empty($_SESSION['this_ref_reg']) == TRUE) $f->Redirect(MAIN_WEBSITE_URL."/registrations.php?index=List");
	
	$sql = "SELECT * FROM `".T."` WHERE `reg_id`='".$_SESSION['this_ref_reg']."'";
	$res = $db->get($sql);
	$row = $db->fetch_array($res);	
	
	$kovil = $f->getValue($row['kovil']);
	$native_place = $f->getValue($row['native_place']);
	$reg_profile_info_id = $f->getValue($row['reg_profile_info_id']);
	$wheelchair_accessible = $f->getValue($row['wheelchair_accessible']);
	
	if(empty($_POST['btnSubmit']) == FALSE)
	{
			$country = $_POST['country'];
			$country_array = explode("|", $country);
			$country = $country_array[1];
			
			$data_array = array(
				/*"reg_id" => $reg_id, 
				"email_id" => ($_POST['email_id']) ? ($f->setValue($_POST['email_id'])) : ('NULL'),*/
				"phone" => ($_POST['phone']) ? ($f->setValue($_POST['phone'])) : ('NULL'),
				"address_1" => ($_POST['address_1']) ? ($f->setValue($_POST['address_1'])) : ('NULL'),
				"address_2" => ($_POST['address_2']) ? ($f->setValue($_POST['address_2'])) : ('NULL'),
				"city" => ($_POST['city']) ? ($f->setValue($_POST['city'])) : ('NULL'),
				"state" => ($_POST['state']) ? ($f->setValue($_POST['state'])) : ('NULL'),
				"zip_code" => ($_POST['zip_code']) ? ($f->setValue($_POST['zip_code'])) : ('NULL'),		
				"country" => ($country) ? ($country) : ('NULL'),
				"kovil" => ($_POST['kovil']) ? ($f->setValue($_POST['kovil'])) : ('NULL'),
				"native_place" => ($_POST['native_place']) ? ($f->setValue($_POST['native_place'])) : ('NULL'),
				"wheelchair_accessible" => ($_POST['wheelchair_accessible']) ? ($f->setValue($_POST['wheelchair_accessible'])) : ('NULL')		
			);		
				
			$db->update(T, $data_array, "reg_profile_info_id", $reg_profile_info_id);
			$f->Redirect(CP."?index=List&msg=update");
	}
	
	if(empty($_GET['msg'])==false)
	{
		require_once('common-msg.php');											
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include('admin-css-js.php');?>

</head>
<body>
<!--Header-part-->
<div id="header">
	<?php include_once('header.php');?>
</div>
<!--close-Header-part--> 

<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
	<?php include_once('header_menu.php');?>
</div>
<!--close-top-Header-menu--> 
<!--sidebar-menu-->
<div id="sidebar">
	<?php include_once('admin-left-menu.php');?>
</div>
<!--sidebar-menu--> 

<!--main-container-part-->
<div id="content"> 
	<!--breadcrumbs-->
	<div id="content-header">
		<div id="breadcrumb"> <a href="javascript:;" title="" class="tip-bottom"><i class="icon-th-large"></i> Profile Information</a></div>
	</div>
	<!--End-breadcrumbs--> 
	
	<!--Chart-box-->
		<table class="table" cellpadding="0" cellspacing="0" border="0" style="padding-bottom:0px; margin:0px;">
			<tr>
				<td>					
					<a href="registrations.php?index=List" class="btn btn-inverse"> <i class="icon-step-backward"></i> Back to Registration</a>
				</td>
			</tr>
		</table>
		<?php if(empty($msg) == false){ ?>
		<div align="center"><?php echo $msg;?></div>
		<?php } ?>
      <form action="<?php echo CP.'?'.QS;?>" method="post" name="frmSettings" class="form-horizontal" id="basic_validate" novalidate enctype="multipart/form-data">
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-title">
					<h5>Profile Information</h5>
				</div>
				<div class="widget-content nopadding">	            				
					<div class="control-group">
						<label class="control-label">Email:</label>
						<div class="controls">							
							<input type="email" name="email_id" id="email_id" class="span11 required email" value="<?php echo $f->getValue($row['email_id']);?>" disabled />
						</div>
					</div>
               <div class="control-group">
						<label class="control-label">Phone:</label>
						<div class="controls">							
							<input name="phone" id="phone" type="tel" class="span11 required" value="<?php echo $f->getValue($row['phone']);?>" maxlength="20" />
                  </div>                  
					</div>
               
               <div class="control-group">
						<label class="control-label">Address Line 1:</label>
						<div class="controls">							
							<input name="address_1" id="address_1" type="text" class="span11 required" value="<?php echo $f->getValue($row['address_1']);?>" />
                  </div>                  
					</div> 
              
               <div class="control-group">
						<label class="control-label">Address Line 2:</label>
						<div class="controls">							
							<input name="address_2" id="address_2" type="text" class="span11" value="<?php echo $f->getValue($row['address_2']);?>" />
                  </div>                  
					</div>
               
                <div class="control-group">
						<label class="control-label">Country:</label>
						<div class="controls">							
							  <select name="country" id="country" class="span11" required>
                            <option value="">---</option>                         
                            <?php 
                                  $reg_country_id = "";
                                  $sql_country = "SELECT * FROM `tbl_country` WHERE `mark_for_deleted`='No' ORDER BY `display_order` ASC";
                                  $res_country = $db->get($sql_country, __FILE__, __LINE__);
                                  while($row_country = $db->fetch_array($res_country))
                                  {    
                                    if($row['country'] == $f->getValue($row_country['country_name']))
                                    {
                                       $reg_country_id = $row_country['country_id'];
                                       $selected = ' selected';
                                    }else{
                                       $selected = '';
                                    }
                              ?>
                              <option value="<?php echo $row_country['country_id']."|".$row_country['country_name'];?>"<?php echo $selected;?>><?php echo $f->getValue($row_country['country_name']);?></option>
                              <?php
                                 }								
                              ?>
                       </select>
                  </div>                  
					</div> 
               
               <div class="control-group">
						<label class="control-label">State:</label>
						<div class="controls" id="st">							
							<select name="state" id="state" class="span11">
                        <option value="">---</option>   
                        <?php 
									if(empty($reg_country_id) == FALSE)
									{
										$sql_state = "SELECT * FROM `tbl_state` WHERE `country_id`='".$reg_country_id."' AND `mark_for_deleted`='No' ORDER BY `state_name` ASC";
										$res_state = $db->get($sql_state);
										while($row_state = $db->fetch_array($res_state))
										{
								?>
                        <option value="<?php echo $f->getValue($row_state['state_name'])?>"<?php if($f->getValue($row_state['state_name']) == $row['state']) echo ' selected';?>><?php echo $f->getValue($row_state['state_name'])?></option> 
                        <?php
										}
									}
								?>	
                    </select>
                  </div>                  
					</div> 
               
                <div class="control-group">
						<label class="control-label">City:</label>
						<div class="controls">							
							<input name="city" id="city" type="text" class="span11 required" value="<?php echo $f->getValue($row['city']);?>" />
                  </div>                  
					</div>  
               
                <div class="control-group">
						<label class="control-label">Zip:</label>
						<div class="controls">							
							<input name="zip_code" id="zip_code" type="text" value="<?php echo $f->getValue($row['zip_code']);?>" placeholder="" class="span11 required" maxlength="6" />
                  </div>                  
					</div>
               
              
               
               <div class="control-group">
						<label class="control-label">Kovil:</label>
						<div class="controls">							
							  <select name="kovil" id="kovil" class="span11 required">
                           <option value="">---</option>
                           <option value="Ilayatrankudi Kovil"<?php if($kovil == 'Ilayatrankudi Kovil') echo ' selected';?>>Ilayatrankudi Kovil</option>
                           <option value="Mathur"<?php if($kovil == 'Mathur') echo ' selected';?>>Mathur</option>
                           <option value="Irani Kovil"<?php if($kovil == 'Irani Kovil') echo ' selected';?>>Irani Kovil</option>
                           <option value="Nemam Kovil"<?php if($kovil == 'Nemam Kovil') echo ' selected';?>>Nemam Kovil</option>
                           <option value="Pillayarpatti"<?php if($kovil == 'Pillayarpatti') echo ' selected';?>>Pillayarpatti</option>
                           <option value="Illuppakkudi Kovil"<?php if($kovil == 'Illuppakkudi Kovil') echo ' selected';?>>Illuppakkudi Kovil</option>
                           <option value="Soorakkudi Kovil"<?php if($kovil == 'Soorakkudi Kovil') echo ' selected';?>>Soorakkudi Kovil</option>
                           <option value="Vairavan Kovil"<?php if($kovil == 'Vairavan Kovil') echo ' selected';?>>Vairavan Kovil</option>
                           <option value="Velankudi Kovil"<?php if($kovil == 'Velankudi Kovil') echo ' selected';?>>Velankudi Kovil</option>
                        </select>
                  </div>                  
					</div>          
               
               <div class="control-group">
						<label class="control-label">Native (Ooru):</label>
						<div class="controls">							
							  <select name="native_place" id="native_place" class="span11 required">
                       		<option value="">---</option>
                      		<option value="Alavakkottai"<?php if($native_place == 'Alavakkottai') echo ' selected';?>>Alavakkottai</option>
                           <option value="Amaravathiputhur"<?php if($native_place == 'Amaravathiputhur') echo ' selected';?>>Amaravathiputhur</option>
                           <option value="Aranmanai Siruvayal"<?php if($native_place == 'Aranmanai Siruvayal') echo ' selected';?>>Aranmanai Siruvayal</option>
                           <!--<option value="Aravayal"<?php //if($native_place == 'Aravayal') echo ' selected';?>>Aravayal</option>-->
                           <option value="Arimalam"<?php if($native_place == 'Arimalam') echo ' selected';?>>Arimalam</option>
                           <option value="Ariyakkudi"<?php if($native_place == 'Ariyakkudi') echo ' selected';?>>Ariyakkudi</option>
                           <option value="Athangudi"<?php if($native_place == 'Athangudi') echo ' selected';?>>Athangudi</option>
                           <option value="Athangudi Muthupattinam"<?php if($native_place == 'Athangudi Muthupattinam') echo ' selected';?>>Athangudi Muthupattinam</option>
                           <option value="Athikadu Thekkur"<?php if($native_place == 'Athikadu Thekkur') echo ' selected';?>>Athikadu Thekkur</option>
                           <option value="Avanipatti"<?php if($native_place == 'Avanipatti') echo ' selected';?>>Avanipatti</option>
                           <option value="Chockalingamputhur"<?php if($native_place == 'Chockalingamputhur') echo ' selected';?>>Chockalingamputhur</option>
                           <option value="Chockanathapuram"<?php if($native_place == 'Chockanathapuram') echo ' selected';?>>Chockanathapuram</option>
                           <option value="Cholapuram"<?php if($native_place == 'Cholapuram') echo ' selected';?>>Cholapuram</option>
                           <option value="Devakottai"<?php if($native_place == 'Devakottai') echo ' selected';?>>Devakottai</option>
                           <option value="K. Lakshmipuram"<?php if($native_place == 'K. Lakshmipuram') echo ' selected';?>>K. Lakshmipuram</option>
                           <option value="Kadiyapatti"<?php if($native_place == 'Kadiyapatti') echo ' selected';?>>Kadiyapatti</option>
                           <option value="Kalaiyar Koil"<?php if($native_place == 'Kalaiyar Koil') echo ' selected';?>>Kalaiyar Koil</option>
                           <option value="Kalaiyarmangalam"<?php if($native_place == 'Kalaiyarmangalam') echo ' selected';?>>Kalaiyarmangalam</option>
                           <option value="Kallal"<?php if($native_place == 'Kallal') echo ' selected';?>>Kallal</option>
                           <option value="Kalluppatti"<?php if($native_place == 'Kalluppatti') echo ' selected';?>>Kalluppatti</option>
                           <option value="Kanadukathan"<?php if($native_place == 'Kanadukathan') echo ' selected';?>>Kanadukathan</option>
                           <option value="Kandanur"<?php if($native_place == 'Kandanur') echo ' selected';?>>Kandanur</option>
                           <option value="Kandaramanickam"<?php if($native_place == 'Kandaramanickam') echo ' selected';?>>Kandaramanickam</option>
                           <option value="Kandavarayanpatti"<?php if($native_place == 'Kandavarayanpatti') echo ' selected';?>>Kandavarayanpatti</option>
                           <option value="Karaikudi"<?php if($native_place == 'Karaikudi') echo ' selected';?>>Karaikudi</option>
                           <option value="Karaikudi Muthupatinam"<?php if($native_place == 'Karaikudi Muthupatinam') echo ' selected';?>>Karaikudi Muthupatinam</option>
                           <option value="Karungulam"<?php if($native_place == 'Karungulam') echo ' selected';?>>Karungulam</option>
                           <option value="Kilapoonkudi"<?php if($native_place == 'Kilapoonkudi') echo ' selected';?>>Kilapoonkudi</option>
                           <option value="Kilasevalpatti"<?php if($native_place == 'Kilasevalpatti') echo ' selected';?>>Kilasevalpatti</option>
                           <option value="Kollangudi Alagapuri"<?php if($native_place == 'Kollangudi Alagapuri') echo ' selected';?>>Kollangudi Alagapuri</option>
                           <option value="Konapet"<?php if($native_place == 'Konapet') echo ' selected';?>>Konapet</option>
                           <option value="Koppanapatti"<?php if($native_place == 'Koppanapatti') echo ' selected';?>>Koppanapatti</option>
                           <option value="Kothamangalam"<?php if($native_place == 'Kothamangalam') echo ' selected';?>>Kothamangalam</option>
                           <option value="Kottaiyur"<?php if($native_place == 'Kottaiyur') echo ' selected';?>>Kottaiyur</option>
                           <option value="Kulipirai"<?php if($native_place == 'Kulipirai') echo ' selected';?>>Kulipirai</option>
                           <option value="Kuruvikondanpatti"<?php if($native_place == 'Kuruvikondanpatti') echo ' selected';?>>Kuruvikondanpatti</option>
                           <option value="Madagupatti"<?php if($native_place == 'Madagupatti') echo ' selected';?>>Madagupatti</option>
                           <option value="Mahibalanpatti"<?php if($native_place == 'Mahibalanpatti') echo ' selected';?>>Mahibalanpatti</option>
                           <option value="Managiri"<?php if($native_place == 'Managiri') echo ' selected';?>>Managiri</option>
                           <option value="Mangalam"<?php if($native_place == 'Mangalam') echo ' selected';?>>Mangalam</option>
                           <option value="Melasivapuri"<?php if($native_place == 'Melasivapuri') echo ' selected';?>>Melasivapuri</option>
                           <option value="Mithilaipatti"<?php if($native_place == 'Mithilaipatti') echo ' selected';?>>Mithilaipatti</option>
                           <option value="N. Alagapuri"<?php if($native_place == 'N. Alagapuri') echo ' selected';?>>N. Alagapuri</option>
                           <option value="Nachandupatti"<?php if($native_place == 'Nachandupatti') echo ' selected';?>>Nachandupatti</option>
                           <option value="Nachiapuram"<?php if($native_place == 'Nachiapuram') echo ' selected';?>>Nachiapuram</option>
                           <option value="Natarajapuram"<?php if($native_place == 'Natarajapuram') echo ' selected';?>>Natarajapuram</option>
                           <option value="Nattarasankottai"<?php if($native_place == 'Nattarasankottai') echo ' selected';?>>Nattarasankottai</option>
                           <option value="Nemathanpatti"<?php if($native_place == 'Nemathanpatti') echo ' selected';?>>Nemathanpatti</option>
                           <option value="Nerkuppai"<?php if($native_place == 'Nerkuppai') echo ' selected';?>>Nerkuppai</option>
                           <option value="O. Siruvayal"<?php if($native_place == 'O. Siruvayal') echo ' selected';?>>O. Siruvayal</option>
                           <option value="Okkur"<?php if($native_place == 'Okkur') echo ' selected';?>>Okkur</option>
                           <option value="P. Alagapuri"<?php if($native_place == 'P. Alagapuri') echo ' selected';?>>P. Alagapuri</option>
                           <option value="Paganeri"<?php if($native_place == 'Paganeri') echo ' selected';?>>Paganeri</option>
                           <option value="Palavangudi"<?php if($native_place == 'Palavangudi') echo ' selected';?>>Palavangudi</option>
                           <option value="Pallathur"<?php if($native_place == 'Pallathur') echo ' selected';?>>Pallathur</option>
                           <option value="Panagudi"<?php if($native_place == 'Panagudi') echo ' selected';?>>Panagudi</option>
                           <option value="Panayapatti"<?php if($native_place == 'Panayapatti') echo ' selected';?>>Panayapatti</option>
                           <option value="Pattamangalam"<?php if($native_place == 'Pattamangalam') echo ' selected';?>>Pattamangalam</option>
                           <option value="Pon. Pudupatti"<?php if($native_place == 'Pon. Pudupatti') echo ' selected';?>>Pon. Pudupatti</option>
                           <option value="Puduvayal"<?php if($native_place == 'Puduvayal') echo ' selected';?>>Puduvayal</option>
                           <option value="Pulangkurichi"<?php if($native_place == 'Pulangkurichi') echo ' selected';?>>Pulangkurichi</option>
                           <option value="Ramachandrapuram"<?php if($native_place == 'Ramachandrapuram') echo ' selected';?>>Ramachandrapuram</option>
                           <option value="Rangiem"<?php if($native_place == 'Rangiem') echo ' selected';?>>Rangiem</option>
                           <option value="Rayavaram"<?php if($native_place == 'Rayavaram') echo ' selected';?>>Rayavaram</option>
                           <option value="Sakkandhi"<?php if($native_place == 'Sakkandhi') echo ' selected';?>>Sakkandhi</option>
                           <option value="Sembanur"<?php if($native_place == 'Sembanur') echo ' selected';?>>Sembanur</option>
                           <option value="Sevvur"<?php if($native_place == 'Sevvur') echo ' selected';?>>Sevvur</option>
                           <option value="Shanmuganathapuram"<?php if($native_place == 'Shanmuganathapuram') echo ' selected';?>>Shanmuganathapuram</option>
                           <option value="Siravayal"<?php if($native_place == 'Siravayal') echo ' selected';?>>Siravayal</option>
                           <option value="Sirukudalpatti"<?php if($native_place == 'Sirukudalpatti') echo ' selected';?>>Sirukudalpatti</option>
                           <option value="Siruvayal"<?php if($native_place == 'Siruvayal') echo ' selected';?>>Siruvayal</option>
                           <option value="Sivayogapuram"<?php if($native_place == 'Sivayogapuram') echo ' selected';?>>Sivayogapuram</option>
                           <option value="Thanichavoorani"<?php if($native_place == 'Thanichavoorani') echo ' selected';?>>Thanichavoorani</option>
                           <option value="Thenipatti"<?php if($native_place == 'Thenipatti') echo ' selected';?>>Thenipatti</option>
                           <option value="Ulagampatti"<?php if($native_place == 'Ulagampatti') echo ' selected';?>>Ulagampatti</option>
                           <option value="V. Lakshmipuram"<?php if($native_place == 'V. Lakshmipuram') echo ' selected';?>>V. Lakshmipuram</option>
                           <option value="Valayapatti"<?php if($native_place == 'Valayapatti') echo ' selected';?>>Valayapatti</option>
                           <option value="Vegupatti"<?php if($native_place == 'Vegupatti') echo ' selected';?>>Vegupatti</option>
                           <option value="Venthanpatti"<?php if($native_place == 'Venthanpatti') echo ' selected';?>>Venthanpatti</option>
                           <option value="Vetriyur"<?php if($native_place == 'Vetriyur') echo ' selected';?>>Vetriyur</option>
                           <option value="Virachilai"<?php if($native_place == 'Virachilai') echo ' selected';?>>Virachilai</option>
                           <option value="Viramathi"<?php if($native_place == 'Viramathi') echo ' selected';?>>Viramathi</option>
                           <option value="Viswanathapuram"<?php if($native_place == 'Viswanathapuram') echo ' selected';?>>Viswanathapuram</option>
                           <option value="Others - not listed here"<?php if($native_place == 'Others - not listed here') echo ' selected';?>>Others - not listed here</option>	
                     </select>
                  </div>                  
					</div> 
               
               <div class="control-group">
						<label class="control-label">&nbsp;</label>
						<div class="controls">
							<p>Do you need a Wheelchair Accessible room for you or any member in your party?</p>
                     <input name="wheelchair_accessible" type="radio" class="span11" value="Yes"<?php if($wheelchair_accessible == 'Yes') echo ' checked';?> />  YES &nbsp;&nbsp;&nbsp; <input name="wheelchair_accessible" type="radio" class="span11" value="No"<?php if($wheelchair_accessible == 'No') echo ' checked';?> />  NO
						</div>
					</div>         
           
               <!--<div class="control-group">
						<label class="control-label">Status:</label>
						<div class="controls">
							<select name="status" id="status" class="span11 required">
								<option value="Active"<?php if($_POST['status'] == 'Active') echo ' selected';?>>Active</option>
								<option value="Inactive"<?php if($_POST['status'] == 'Inactive') echo ' selected';?>>Inactive</option>
							</select>
						</div>
					</div>-->
					<div class="widget-content nopadding">
						<div class="control-group">
							<div class="controls">
								<input name="btnSubmit" id="btnSubmit" type="submit" value="Update" class="btn btn-success" />
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>		
</form>	
</div>

<!--end-main-container-part--> 

<!--Footer-part-->

<div class="row-fluid">
	<?php include_once('tb.php');?>
</div>

<!--end-Footer-part-->
<?php include('admin-footer.php');?>
<!--<link rel="stylesheet" href="<?php echo WEBSITE_URL;?>/css/bootstrap-wysihtml5.css" />
<script src="<?php echo WEBSITE_URL;?>/js/wysihtml5-0.3.0.js"></script> 
<script src="<?php echo WEBSITE_URL;?>/js/bootstrap-wysihtml5.js"></script> 
<script src="<?php echo WEBSITE_URL;?>/js/bootstrap-wysihtml5.js"></script> 
-->
<script type="text/javascript">
$(document).ready(function(){	
	$('#country').change(function() {
			
		var country_id_array = $(this).val();
		var country_array = country_id_array.split("|");	
		var country_id = country_array[0]; 
		
		$('#spinner-div').show();
		$.ajax({
			type: 'GET',
			url: '<?php echo WEBSITE_URL;?>/ajax.php',
			data: {target: 'GetState', CountryId: country_id},
			dataType: 'html',
			success: function(data) {					
					$('#spinner-div').hide();							
					$("#st").html(data);				
			}			
		});		
	});	
});
</script>

<div id="spinner-div" class="pt-5">
    <div class="spinner-grow text-danger" role="status">
    		<p>&nbsp;</p>
         <p>&nbsp;</p>
    		<span class="visually-hidden">Loading...</span>
    </div>
</div>
</body>
</html>
