
<div class="message_left_heading">Registration Pages</div>
<div class="message_left_content">
     <ul>
     		 <li><a href="<?php echo MAIN_WEBSITE_URL;?>/registration-dashboard"<?php if($CurrentPage == "registration-dashboard.php") echo ' class="active"';?>>Dashboard</a></li>
          <?php 
			 	if($_SESSION['reg_status'] == 'Active')
				{
			 ?>
          <li><a href="<?php echo MAIN_WEBSITE_URL;?>/edit-registration-profile-info"<?php if($CurrentPage == "edit-registration-profile-info.php") echo ' class="active"';?>>Profile Information</a></li>
          <li><a href="<?php echo MAIN_WEBSITE_URL;?>/edit-registration-member-info"<?php if($CurrentPage == "edit-registration-member-info.php") echo ' class="active"';?>>Member Information</a></li>
          <li><a href="<?php echo MAIN_WEBSITE_URL;?>/edit-registration-family-info"<?php if($CurrentPage == "edit-registration-family-info.php") echo ' class="active"';?>>Family Information</a></li>
          <?php 
					if($_SESSION['pay_mode'] == 'Zelle')
					{
			 ?>
          <!--<li><a href="<?php echo MAIN_WEBSITE_URL;?>/registration-zelle-payment-details"<?php if($CurrentPage == "registration-zelle-payment-details.php") echo ' class="active"';?>>Zelle Payment Details</a></li>-->
          <?php 
					}
				}
			 ?>	
          
          <li><a href="<?php echo MAIN_WEBSITE_URL;?>/logout.php" title="Confirm Log Out" class="ButtonLogout">Logout</a></li>
     </ul>
</div>
