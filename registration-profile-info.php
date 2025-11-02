<?php 
	require_once("includes/config.inc.php");
	define("TABLE", "tbl_registration");	
	
	if(empty($_SESSION['reg_id_edit']) == FALSE) $f->Redirect(MAIN_WEBSITE_URL."/registration-dashboard");
	
	$reg_id = $_SESSION['reg_id'];
	if(empty($reg_id) == TRUE) $f->Redirect(MAIN_WEBSITE_URL."/registration-step1");
	
	$sql = "SELECT * FROM `".TABLE."` WHERE `reg_id`=".$reg_id;
	$res = $db->get($sql);
	$rec = $db->num_rows($res);
	if($rec == 0)
	{
		$f->Redirect(MAIN_WEBSITE_URL."/registration-step1");
	}
	$row = $db->fetch_array($res);
	$ref_number = $row['ref_number'];
	
	// Submit form =========================================================================================================================
	if(empty($_POST['btnSubmit']) == FALSE)
	{
		$email_id = $_POST['email_id'];
		$sql_check = "SELECT `email_id` FROM `tbl_reg_profile_info`
					     WHERE `email_id`='".$email_id."' AND `mark_for_deleted`='No' AND (`status`='Active' OR `status`='Cancel')";
		$res_check = $db->get($sql_check);
		$rec_check = $db->num_rows($res_check);
		if($rec_check > 0)
		{
			$msg = $f->getHtmlError("Email address is currently in use on our system.");			
		}else{
			
			$country = $f->POST_VAL('country');
			$country_array = explode("|", $country);
			$country = $country_array[1];
			
			$data_array = array(
				"reg_id" => $reg_id, 
				"email_id" => ($_POST['email_id']) ? ($f->setValue($_POST['email_id'])) : ('NULL'),
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
			
			if(empty($_SESSION['reg_profile_info_id']) == TRUE)
			{
				$db->insert("tbl_reg_profile_info", $data_array);
				$reg_profile_info_id = $db->last_insert_id();
				$_SESSION['reg_profile_info_id'] = $reg_profile_info_id;
			}else{
				$db->update("tbl_reg_profile_info", $data_array, "reg_profile_info_id", $_SESSION['reg_profile_info_id']);
			}
			
			$f->Redirect(MAIN_WEBSITE_URL."/registration-member-info");
		}
	}
	
?>
<?php 
	include_once('doctype.php');
?>
<head>
<?php require_once('title.inc.php');?>
<?php require_once('js.css.inc.php');?>
</head>
<body>
<!-------------- Header ------------------->
<header class="inner_header">
	<?php include_once('header.php');?>
</header>
<div class="header_mobilenav"></div>

<!------------------- Header end------------------->
<div class="clear"></div>
<!------------------- Slider area------------------->
<section class="inner_area">
	<div class="container">
		<div class="inner_container">
			<div style="font-size:30px; color: #2F2D9B; font-weight: 600; text-transform: uppercase; padding-bottom:10px;">Profile Information</div>
			<div style="font-size:20px; padding-bottom: 30px;">Enter the following information for the primary guest responsible for this registration</div>
          <?php if(empty($msg) == false){ ?>
            <div align="center" class="display_message"><?php echo $msg;?></div>
            <p></p>
        	<?php } ?>
         <form name="reg_frm" id="reg_frm" method="post" action="<?php echo MAIN_WEBSITE_URL?>/registration-profile-info">
            <div class="register_area">
               <div class="row">
                  <div class="col-md-6"><p class="style1">Email <span>*</span></p><input name="email_id" id="email_id" type="email" value="<?php echo $f->POST_VAL('email_id');?>" placeholder="" class="input3 email" required /></div>
                  <div class="col-md-6"><p class="style1">Mobile <span>*</span></p><input name="phone" id="phone" type="tel" value="<?php echo $f->POST_VAL('phone')?>" placeholder="" class="input3" maxlength="20" required /></div>	
               </div>
               <div><p class="style1">Address Line 1 <span>*</span></p><input name="address_1" id="address_1" type="text" value="<?php echo $f->POST_VAL('address_1');?>" placeholder="" class="input3" required /></div>
               <div><p class="style1"> Address Line 2</p><input name="address_2" id="address_2" type="text" value="<?php echo $f->POST_VAL('address_2');?>" placeholder="" class="input3" /></div>
               <div class="row">
                  
              	 <div class="col-md-6"><p class="style1">Country <span>*</span></p>
                     <select name="country" id="country" class="input3" required>
                         <option value="">---</option>                         
                         <?php 
                               $sql_country = "SELECT * FROM `tbl_country` WHERE `status`='Active' AND `mark_for_deleted`='No' ORDER BY `display_order` ASC";
                               $res_country = $db->get($sql_country, __FILE__, __LINE__);
                               while($row_country = $db->fetch_array($res_country))
                               {                              					  					
                           ?>
                           <option value="<?php echo $row_country['country_id']."|".$row_country['country_name'];?>"><?php echo $f->getValue($row_country['country_name']);?></option>
                           <?php
                              }								
                           ?>
                    </select>
                  </div>
                  <div class="col-md-6"><p class="style1">State <span>*</span></p>
                  	<div id="st">
                     <select name="state" id="state" class="input3">
                        <option value="">---</option>                        
                    </select>
                    </div>
                  </div>   
                                 
                  <div class="col-md-6"><p class="style1">City <span>*</span></p><input name="city" id="city" type="text" value="<?php echo $f->POST_VAL('city');?>" placeholder="" class="input3" required /></div>
                  <div class="col-md-6"><p class="style1">Zip Code <span>*</span></p><input name="zip_code" id="zip_code" type="text" value="<?php echo $f->POST_VAL('zip_code');?>" placeholder="" class="input3" maxlength="6" required /></div>
                  
                  <div class="col-md-6"><p class="style1">Kovil <span>*</span></p>
                     <select name="kovil" id="kovil" class="input3" required>
                     <option value="">---</option>
                     <option value="Ilayatrankudi Kovil">Ilayatrankudi Kovil</option>
                     <option value="Mathur">Mathur</option>
                     <option value="Irani Kovil">Irani Kovil</option>
                     <option value="Nemam Kovil">Nemam Kovil</option>
                     <option value="Pillayarpatti">Pillayarpatti</option>
                     <option value="Illuppakkudi Kovil">Illuppakkudi Kovil</option>
                     <option value="Soorakkudi Kovil">Soorakkudi Kovil</option>
                     <option value="Vairavan Kovil">Vairavan Kovil</option>
                     <option value="Velankudi Kovil">Velankudi Kovil</option>
                  </select>
                  </div>
                  <div class="col-md-6"><p class="style1">Native (Ooru) <span>*</span></p>
                     <select name="native_place" id="native_place" class="input3" required>
                        <option value="">---</option>
                        <option value="Alavakkottai">Alavakkottai</option>
                        <option value="Amaravathiputhur">Amaravathiputhur</option>
                        <option value="Aranmanai Siruvayal">Aranmanai Siruvayal</option>
                        <!--<option value="Aravayal">Aravayal</option>-->
                        <option value="Arimalam">Arimalam</option>
                        <option value="Ariyakkudi">Ariyakkudi</option>
                        <option value="Athangudi">Athangudi</option>
                        <option value="Athangudi Muthupattinam">Athangudi Muthupattinam</option>
                        <option value="Athikadu Thekkur">Athikadu Thekkur</option>
                        <option value="Avanipatti">Avanipatti</option>
                        <option value="Chockalingamputhur">Chockalingamputhur</option>
                        <option value="Chockanathapuram">Chockanathapuram</option>
                        <option value="Cholapuram">Cholapuram</option>
                        <option value="Devakottai">Devakottai</option>
                        <option value="K. Lakshmipuram">K. Lakshmipuram</option>
                        <option value="Kadiyapatti">Kadiyapatti</option>
                        <option value="Kalaiyar Koil">Kalaiyar Koil</option>
                        <option value="Kalaiyarmangalam">Kalaiyarmangalam</option>
                        <option value="Kallal">Kallal</option>
                        <option value="Kalluppatti">Kalluppatti</option>
                        <option value="Kanadukathan">Kanadukathan</option>
                        <option value="Kandanur">Kandanur</option>
                        <option value="Kandaramanickam">Kandaramanickam</option>
                        <option value="Kandavarayanpatti">Kandavarayanpatti</option>
                        <option value="Karaikudi">Karaikudi</option>
                        <option value="Karaikudi Muthupatinam">Karaikudi Muthupatinam</option>
                        <option value="Karungulam">Karungulam</option>
                        <option value="Kilapoonkudi">Kilapoonkudi</option>
                        <option value="Kilasevalpatti">Kilasevalpatti</option>
                        <option value="Kollangudi Alagapuri">Kollangudi Alagapuri</option>
                        <option value="Konapet">Konapet</option>
                        <option value="Koppanapatti">Koppanapatti</option>
                        <option value="Kothamangalam">Kothamangalam</option>
                        <option value="Kottaiyur">Kottaiyur</option>
                        <option value="Kulipirai">Kulipirai</option>
                        <option value="Kuruvikondanpatti">Kuruvikondanpatti</option>
                        <option value="Madagupatti">Madagupatti</option>
                        <option value="Mahibalanpatti">Mahibalanpatti</option>
                        <option value="Managiri">Managiri</option>
                        <option value="Mangalam">Mangalam</option>
                        <option value="Melasivapuri">Melasivapuri</option>
                        <option value="Mithilaipatti">Mithilaipatti</option>
                        <option value="N. Alagapuri">N. Alagapuri</option>
                        <option value="Nachandupatti">Nachandupatti</option>
                        <option value="Nachiapuram">Nachiapuram</option>
                        <option value="Natarajapuram">Natarajapuram</option>
                        <option value="Nattarasankottai">Nattarasankottai</option>
                        <option value="Nemathanpatti">Nemathanpatti</option>
                        <option value="Nerkuppai">Nerkuppai</option>
                        <option value="O. Siruvayal">O. Siruvayal</option>
                        <option value="Okkur">Okkur</option>
                        <option value="P. Alagapuri">P. Alagapuri</option>
                        <option value="Paganeri">Paganeri</option>
                        <option value="Palavangudi">Palavangudi</option>
                        <option value="Pallathur">Pallathur</option>
                        <option value="Panagudi">Panagudi</option>
                        <option value="Panayapatti">Panayapatti</option>
                        <option value="Pattamangalam">Pattamangalam</option>
                        <option value="Pon. Pudupatti">Pon. Pudupatti</option>
                        <option value="Puduvayal">Puduvayal</option>
                        <option value="Pulangkurichi">Pulangkurichi</option>
                        <option value="Ramachandrapuram">Ramachandrapuram</option>
                        <option value="Rangiem">Rangiem</option>
                        <option value="Rayavaram">Rayavaram</option>
                        <option value="Sakkandhi">Sakkandhi</option>
                        <option value="Sembanur">Sembanur</option>
                        <option value="Sevvur">Sevvur</option>
                        <option value="Shanmuganathapuram">Shanmuganathapuram</option>
                        <option value="Siravayal">Siravayal</option>
                        <option value="Sirukudalpatti">Sirukudalpatti</option>
                        <option value="Siruvayal">Siruvayal</option>
                        <option value="Sivayogapuram">Sivayogapuram</option>
                        <option value="Thanichavoorani">Thanichavoorani</option>
                        <option value="Thenipatti">Thenipatti</option>
                        <option value="Ulagampatti">Ulagampatti</option>
                        <option value="V. Lakshmipuram">V. Lakshmipuram</option>
                        <option value="Valayapatti">Valayapatti</option>
                        <option value="Vegupatti">Vegupatti</option>
                        <option value="Venthanpatti">Venthanpatti</option>
                        <option value="Vetriyur">Vetriyur</option>
                        <option value="Virachilai">Virachilai</option>
                        <option value="Viramathi">Viramathi</option>
                        <option value="Viswanathapuram">Viswanathapuram</option>
								<option value="Others - not listed here">Others - not listed here</option>							
                     </select>
                  </div>
                  <div class="col-md-12"><p class="style1">Do you need a Wheelchair Accessible room for you or any member in your party? <span>*</span></p>
                     <div><input name="wheelchair_accessible" type="radio" value="Yes" />  YES &nbsp;&nbsp;&nbsp; <input name="wheelchair_accessible" type="radio" value="No" checked />  NO</div>
                  </div>
               </div>
               <br>
               <div class="clear"></div>
               <div class=""><input name="btn" type="button" value="Back" class="back_button" onClick="javascript:window.location.href='<?php echo MAIN_WEBSITE_URL;?>/registration-packages'" />&nbsp;&nbsp;&nbsp;<input name="btnSubmit" type="submit" value="Next" class="submit" /></div>
               <p></p>
               <!--<p>YOUR REFERENCE NUMBER IS <b><?php echo $ref_number;?></b><br />If you want to modify your registration, please use your email and the above registration number.</p>-->
            </div>
         </form>
		</div>
	</div>
	<div class="clear"></div>
</section>
<div class="clear"></div>

<footer>
	<?php include_once('footer.php');?>
</footer>
<?php include_once('common-footer.php');?>
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