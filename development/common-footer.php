<link rel='stylesheet' href='<?php echo MAIN_WEBSITE_URL;?>/js/jAlert-master/src/jAlert.css'>
<script src='<?php echo MAIN_WEBSITE_URL;?>/js/jAlert-master/src/jAlert.js'></script>
<script src='<?php echo MAIN_WEBSITE_URL;?>/js/jAlert-master/src/jAlert-functions.js'></script>
<script type="text/javascript">
$(document).ready(function() {		
	$('.ButtonLogout').click(confirm);	
	function confirm() 
	{ 
		$.jAlert({
			'type': 'confirm',
			'title':'Alert',
			'confirmQuestion':' Are you sure you want to Logout? ',
			'theme': 'green',
			'showAnimation': 'flipInX',
			'closeBtn': false,
			'onConfirm': function(){
				window.location.href = "<?php echo MAIN_WEBSITE_URL;?>/logout.php";
			}
		});
		return false;
	}	
});
</script>

<p id="back-top"><a href="#top"><img src="<?php echo MAIN_WEBSITE_URL;?>/images/back-to-top.png" /></a></p>
<script type="text/javascript" src="<?php echo MAIN_WEBSITE_URL;?>/js/bootstrap.min.js"></script> 
<!--scroll to top--> 
<script>
$(document).ready(function(){

	// hide #back-top first
	$("#back-top").hide();
	
	// fade in #back-top
	$(function () {
		$(window).scroll(function () {
			if ($(this).scrollTop() > 100) {
				$('#back-top').fadeIn();
			} else {
				$('#back-top').fadeOut();
			}
		});

		// scroll body to 0px on click
		$('#back-top a').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	});

});
</script>

