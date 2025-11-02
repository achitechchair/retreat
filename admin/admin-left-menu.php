<a href="#" class="visible-phone">Menu</a>
<?php 
	$LeftMenuArr = array(
		"Dashboard" => "dashboard.php",
		"Manage Settings" => "settings.php",
		"Change Password" => "change-password.php",	
		
		"Manage Registration" => array(
			"Registration FAQ" =>  "registration-faq.php?index=List",
			"Registration Page Text" => "registration-page-content.php", 
			"Manage Packages" => "registration-packages.php?index=List",
			"Manage Country" => "country.php?index=List",
			"Manage State" => "state.php?index=List",						
			"Live Registration" =>  "registrations.php?index=List",
			"Cancel Registration" =>  "cancel-registrations.php?index=List",
			"Registration Report" =>  "report-registrations.php?index=List"			
		),
		
		"Manage Sponsors" => array(
			"Category" => "sponsors-category.php?index=List",
			"Sponsors" => "sponsors.php?index=List",
		),
		"Manage Updates" => "updates.php?index=List",		
		"Manage Page Content" => "page-content.php?index=List"
	);
	
		
	
?>
<ul>
<?php
	foreach($LeftMenuArr as $key => $val)
	{
		if(is_array($val) == TRUE)
		{
			$DivId = str_replace(array("/", " "), "_", strtolower($key))."_sub";	
?>
	<li id="<?php echo $DivId;?>" class="submenu"><a href="#"><i class="icon icon-list"></i><span class="Font13"><?php echo $key;?></span></a>
		<ul>
<?php
			$page_selection_array = array();
			foreach($val as $SubKey => $SubVal)
			{
				$SubCurrPage = strtok($SubVal,'?');
				//$SubActiveClass = (basename($_SERVER['PHP_SELF']) == $SubCurrPage) ? "sub_active" : "";
?>
			<li <?php if($SubCurrPage == basename($_SERVER['PHP_SELF'])) echo ' class="active"';?> id=""><a href="<?php echo WEBSITE_URL."/".$SubVal;?>" title="<?php echo $SubKey;?>"><i class="icon icon-chevron-right"></i><span class="Font13"><?php echo $SubKey;?></span></a></li>
<?php
				array_push($page_selection_array, $SubCurrPage);
			}
?>
		</ul>
<?php
		if(in_array(basename($_SERVER['PHP_SELF']), $page_selection_array))
		{
?>
		<script type="text/javascript">
			$('#<?php echo $DivId;?>').addClass('open');
			$('#<?php echo $DivId;?>').addClass('active');
		</script>
<?php
		}
?>
	</li>
<?php
		}
		else
		{
			$CurrPage = strtok($val,'?');
?>
	<li<?php if($CurrentPage == $CurrPage) echo ' class="active"';?>><a href="<?php echo WEBSITE_URL."/".$val;?>" title="<?php echo $key;?>"><i class="icon icon-chevron-right"></i><span class="Font13"><?php echo $key;?></span></a></li>
<?php	
		}
	}
?>
</ul>
