<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="noindex, nofollow, noarchive" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<meta name="viewport" content="width=device-width, maximum-scale = 1, minimum-scale=1" />
<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
<title><?php echo PAGE_TITLE;?></title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="<?php echo WEBSITE_URL;?>/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo WEBSITE_URL;?>/css/bootstrap-responsive.min.css" />
<?php if(basename($_SERVER['PHP_SELF']) == 'index.php' || basename($_SERVER['PHP_SELF']) == 'forgot_password.php'):?>
<link rel="stylesheet" href="<?php echo WEBSITE_URL;?>/css/matrix-login.css" />
<?php else:?>
<link rel="stylesheet" href="<?php echo WEBSITE_URL;?>/css/uniform.css" />
<link rel="stylesheet" href="<?php echo WEBSITE_URL;?>/css/select2.css" />
<link rel="stylesheet" href="<?php echo WEBSITE_URL;?>/css/fullcalendar.css" />
<link rel="stylesheet" href="<?php echo WEBSITE_URL;?>/css/matrix-style.css" />
<link rel="stylesheet" href="<?php echo WEBSITE_URL;?>/css/matrix-media.css" />
<link rel="stylesheet" href="<?php echo WEBSITE_URL;?>/css/datepicker.css" />
<link rel="stylesheet" href="<?php echo WEBSITE_URL;?>/css/bootstrap-wysihtml5.css" />
<link href="<?php echo WEBSITE_URL;?>/font-awesome/css/font-awesome.css" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo WEBSITE_URL;?>/css/jquery.gritter.css" />
<?php endif;?>
<link href="<?php echo WEBSITE_URL;?>/font-awesome/css/font-awesome.css" rel="stylesheet" />
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
<link href='<?php echo MAIN_WEBSITE_URL;?>/js/jAlert-master/src/jAlert.css' rel='stylesheet' type="text/css" />
<link href="<?php echo WEBSITE_URL;?>/custom-admin.css" rel="stylesheet" type="text/css" />
<!-- All Javascript Files -->
<script src="<?php echo WEBSITE_URL;?>/js/jquery.min.js"></script> 
<script type="text/javascript" src="<?php echo MAIN_WEBSITE_URL;?>/js/jquery.numeric.js"></script>
<script type="text/javascript">
$(document).ready(function () {
	var DocWidth = $(document).width();
	
	$.trim($('#breadcrumb').html());
	
	if(DocWidth >= 1280) {
		//$('#sidebar ul').height($(document).height());
		//$('#footer').css('padding-left', $('#sidebar ul').width());
		
		$('#breadcrumb').prepend('<a href="javascript:;" id="LeftNavCtrl" title="" class="tip-bottom"><i class="icon-reorder"></i></a>&nbsp;')
		
		$('#LeftNavCtrl').click(function() {
			$('#sidebar').toggle('fast', 'linear', function() {
				if($('#content').css('margin-left') == '220px') {
					$('#content').css('margin-left', '0px');
				} else {
					$('#content').css('margin-left', '220px');
				}
			});		
		});
	}
	
	$('.clnumeric').bind('copy paste cut',function(e) {
		e.preventDefault();
	});
	
	$('.specialinput').bind('keypress keyup keydown copy paste cut',function(e) {
		e.preventDefault();
	});
	
	$('.no_enter').keydown(function(e) {		
		if(e.keyCode == 13)
		{
			e.preventDefault(); // Makes no difference			
		}
	});
});

function slugify(string) {
	return string.toString().trim().toLowerCase().replace(/\s+/g, "-").replace(/[^\w\-]+/g, "").replace(/\-\-+/g, "-").replace(/^-+/, "").replace(/-+$/, "");
}

$(document).ready(function () {
    	//$('#content').css('min-height' , 1050);
});

function RotateImage(pImageFile, pRotate)
{
	$("#LoaderDiv").show();
	$.ajax({
		type: 'GET',
		url: '<?php echo WEBSITE_URL;?>/rotate-image.php',
		data: {ImageFile: pImageFile, Rotate: pRotate},
		dataType: 'html',
		success: function() {
			$("#LoaderDiv").hide();	
			window.location.reload(true);			
		},
		error: function (error) {
			errorAlert('Error: ' + eval(error));
		}	
	});
}
</script>
<script type="text/javascript" src="<?php echo WEBSITE_URL;?>/ckeditor/ckeditor.js"></script>
