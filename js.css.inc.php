<link href="<?php echo MAIN_WEBSITE_URL;?>/css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="<?php echo MAIN_WEBSITE_URL;?>/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo MAIN_WEBSITE_URL;?>/css/meanmenu.css" rel="stylesheet" type="text/css" />
<link href="<?php echo MAIN_WEBSITE_URL;?>/css/media.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="<?php echo MAIN_WEBSITE_URL;?>/custom.css" rel="stylesheet" type="text/css" />
<!--slider css-->
<link rel="stylesheet" href="<?php echo MAIN_WEBSITE_URL;?>/css/flexslider.css" type="text/css" />
<!--js-->
<script type="text/javascript" src="<?php echo MAIN_WEBSITE_URL;?>/js/jquery-1.10.2.min.js"></script>
<!--menu js-->
<script type="text/javascript" src="<?php echo MAIN_WEBSITE_URL;?>/js/jquery.meanmenu.js"></script>
<script>
jQuery(document).ready(function () {
    jQuery('header nav').meanmenu();
});
</script>
<!--header resize-->
<script type="text/javascript" src="<?php echo MAIN_WEBSITE_URL;?>/js/classie.js"></script>

<!--slider js-->
<script type="text/javascript" src="<?php echo MAIN_WEBSITE_URL;?>/js/jquery-flexslider.min.js"></script>
<script type="text/javascript" src="<?php echo MAIN_WEBSITE_URL;?>/js/jquery-flexslider-setup.js"></script>

<!--carosal js-->
<script src="<?php echo MAIN_WEBSITE_URL;?>/carosul/jquery.jcarousellite_1.0.1.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#carosul").jCarouselLite({
		btnNext: "#next_button",
		btnPrev: "#previous_button",
		visible:3,
		scroll: 1,
		auto:0 
	});
	$("#carosul1").jCarouselLite({
		btnNext: "#next_button1",
		btnPrev: "#previous_button1",
		visible:3,
		scroll: 1,
		auto:0 
	});
});
</script>
<link href="<?php echo MAIN_WEBSITE_URL;?>/carosul/carosulstyle.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
	$(document).ready(function() {
		$('nav ul li:has(ul)').addClass('menu_drop');
		
		$('.menu_drop').mouseover(function() {
			$(this).addClass('menu_up');
		});
		
		$('.menu_drop').mouseout(function() {
			$(this).removeClass('menu_up');
		});
	});
</script>

<script type="text/javascript" src="<?php echo MAIN_WEBSITE_URL;?>/accordian/accordion.js"></script>
<script type="text/javascript" src="<?php echo MAIN_WEBSITE_URL;?>/accordian/accordion-control.js"></script>
<script type="text/javascript" src="<?php echo MAIN_WEBSITE_URL;?>/js/jquery.numeric.js"></script>
<script type="text/javascript">
$(document).ready(function() {	
	$(".numeric").numeric(); 
	$(".number_only").numeric(false);
});
</script>

<script type="text/javascript" src='https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit' async defer></script>
<script type="text/javascript">
var onloadCallback = function() {
	grecaptcha.render('grecaptcha', {
		'sitekey' : '<?php echo CAPTCHA_PUBLIC_KEY;?>'
	});
};
</script>



