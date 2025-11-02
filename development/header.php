<div class="container">
     <div class="logo"><a href="<?php echo MAIN_WEBSITE_URL;?>/"><img src="<?php echo MAIN_WEBSITE_URL;?>/images/logo.png" alt="" /></a></div>
     <div class="header_right"> 
          <!--menu-->
          <nav>
               <ul>
                    <li><a href="javascript:;"<?php if($CurrentPage == "index.php") echo ' class="active"';?>>Retreat 2024</a>
                         <ul>
                         		<?php 
											if($CurrentPage == "index.php")
											{
										?>
                              <li><a href="javascript:;" class="pricing_reg">Packages</a></li>
                              <?php 
											}else{
										?>
                               <li><a href="<?php echo MAIN_WEBSITE_URL;?>/#pricing" class="pricing_reg">Packages</a></li>
                              <?php 
											}
										?>	
                              <li><a href="<?php echo MAIN_WEBSITE_URL;?>/hotel-info">Hotel Info </a></li>
                              <?php /* ?>
                              <li><a href="<?php echo MAIN_WEBSITE_URL;?>/directions">Directions</a></li>
                              <li><a href="<?php echo MAIN_WEBSITE_URL;?>/youth-activities">Youth Activities</a></li>
                              <?php */ ?>
                              <li><a href="<?php echo MAIN_WEBSITE_URL;?>/program-schedule">Program Schedule</a></li>
                              <li><a href="<?php echo MAIN_WEBSITE_URL;?>/registration-statistics">Registration Statistics</a></li>
                         </ul>
                    </li>
                    <?php /* ?>
                    <li><a href="<?php echo MAIN_WEBSITE_URL;?>/updates"<?php if($CurrentPage == "updates.php") echo ' class="active"';?>>Updates</a></li>                    
                    <li><a href="<?php echo MAIN_WEBSITE_URL;?>/faq"<?php if($CurrentPage == "faq.php") echo ' class="active"';?>>FAQ</a></li>
                    <?php */ ?>
                    <li><a href="<?php echo MAIN_WEBSITE_URL;?>/contact-us"<?php if($CurrentPage == "contact-us.php") echo ' class="active"';?>>Contact</a></li>
               </ul>
               <div class="clear"></div>
          </nav>
          <!--menu end-->
          <div class="header_button"><a href="<?php echo MAIN_WEBSITE_URL;?>/registration-home">Register Now</a></div>
     </div>
</div>
<div class="clear"></div>
