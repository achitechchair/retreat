<?php 
	require_once("includes/config.inc.php");
	
	
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
<!------------------- Slider area------------------->
<section class="inner_banner" style="background-image:url(<?php echo MAIN_WEBSITE_URL;?>/images/inner-banner.jpg);">
<img src="<?php echo MAIN_WEBSITE_URL;?>/images/inner-banner.jpg" />
    <div class="flexcaption">
        <div class="container">
            <div class="flexcaption_area">
                <div class="flexcaption_style4">Stage Program FAQ</div>
            </div>
        </div>
    </div>
	<div class="clear"></div>
<div class="flexcaption_darkshade"></div>    
</section>
<!------------- Slider area end----------------->

<div class="clear"></div>

<section class="inner_area">
	<div class="container">
		<?php /*?>
		<div class="faqarea">
      	<?php 
				$sql_faq = "SELECT * FROM `tbl_registration_faq` WHERE `status`='Active' AND `mark_for_deleted`='No' ORDER BY `display_order` ASC";
				$res_faq = $db->get($sql_faq);
				while($row_faq = $db->fetch_array($res_faq))
				{
			?>
			<div class="onestop_block">
				<div class="onestop_block_heading faq_heading"><?php echo $f->getValue($row_faq['faq_title']);?></div>
				<div class="faq_details"><?php echo $f->getHTMLDecode($row_faq['faq_desc']);?></div>
			</div>
			<?php 
				}
			?>	
		</div>
		<?php */?>
		<div class="faqarea">
			<div class="onestop_block">
				<div class="onestop_block_heading faq_heading">Our Region has only been allocated a few minutes of stage time, but you are asking for a 30-second teaser. Do you want us to basically record the whole performance?</div>
				<div class="faq_details">No, we only need brief snippets that capture the essence of your performance within the 30-second teaser.</div>
			</div>
			<div class="onestop_block">
				<div class="onestop_block_heading faq_heading">Is the teaser mandatory? Do you need a part of the actual performance in the teaser or just what song we are using, or who is participating in it?</div>
				<div class="faq_details">The teaser is not mandatory, but it's highly encouraged as it helps promote your program and generate buzz for more attendance. It doesn't have to feature the actual program; it could be funny bloopers or snippets of your practice. We welcome any creative content that showcases your performance or participants.</div>
			</div>
			<div class="onestop_block">
				<div class="onestop_block_heading faq_heading">Can we ask for more stage time if needed?</div>
				<div class="faq_details">We have allocated initial stage timing based on the number of participants registered for the retreat from each region. Once the registration closes, we may be able to offer additional minutes, if possible. This retreat stage timing is limited based on the survey conducted and to provide opportunities for networking.</div>
			</div>
			<div class="onestop_block">
				<div class="onestop_block_heading faq_heading">If someone that registered from our region cancels, will our allocated stage time decrease?</div>
				<div class="faq_details">We will try our best to not reduce allocated stage time unless there is a significant drop in the number of participants from your region. Our aim is to maintain fairness and consistency in stage time allocations while accommodating changes in participant numbers.</div>
			</div>
			<div class="onestop_block">
				<div class="onestop_block_heading faq_heading">Can I change aspects of my program after sending in the individual program details spreadsheet?</div>
				<div class="faq_details">Yes, you can update the spreadsheet and audio/video elements until June 2nd, which is the final deadline. However, the initial submission must be made by May 19th.</div>
			</div>
			<div class="onestop_block">
				<div class="onestop_block_heading faq_heading">Is our allocated time one chunk or can we split it up?</div>
				<div class="faq_details">Your allocated time is one continuous segment. Due to the limited overall stage time, we cannot split each regional timing into different slots. Additionally, audio files submitted, even if containing more than one program within your timeslot, need to be one recording and a single audio file.</div>
			</div>
			<div class="onestop_block">
				<div class="onestop_block_heading faq_heading">Can we change when our region performs (e.g., going 3rd instead of 2nd)?</div>
				<div class="faq_details">We will inform you of your performing time well in advance and work with your contact to ensure sequential scheduling. However, due to the coordination involved with multiple regions and stages, we kindly request refraining from asking for changes in timing unless the situation is unavoidable.</div>
			</div>
			<div class="onestop_block">
				<div class="onestop_block_heading faq_heading">What format should audio or video files be in?</div>
				<div class="faq_details">For audio files, we prefer WAV or MP3 format. For video files, please use MP4 format.</div>
			</div>
			<div class="onestop_block">
				<div class="onestop_block_heading faq_heading">Does the maximum time allocated include the program duration, set up time, and take down time?</div>
				<div class="faq_details">Yes, the maximum time allocated includes the program duration, setup time, and take down time. We want to clarify this so that you can incorporate it into your regular practice.</div>
			</div>
			<div class="onestop_block">
				<div class="onestop_block_heading faq_heading">The spreadsheet asks if there are participants under the age of 12. Is that a minimum age of participation?</div>
				<div class="faq_details">No, there is no minimum age for participation. We ask for this information to ensure that participants aged 12 and below are accompanied by adults backstage before and after the program.</div>
			</div>
			<div class="onestop_block">
				<div class="onestop_block_heading faq_heading">Do we have to bring all the props/set-up stuff we need, or will there be anything available there that we can use?</div>
				<div class="faq_details">Yes, please plan to bring the required props with you. If you need a table/chair for your performance, please let the program team know in advance.</div>
			</div>
			<div class="onestop_block">
				<div class="onestop_block_heading faq_heading">Do we have to submit the content for the MCs to read about our program?</div>
				<div class="faq_details">Please send the details about the program, participants, choreographer, and any special mentions to the Program team. The Stage Program Team will take care of providing the necessary information to the MC.</div>
			</div>
			<div class="onestop_block">
				<div class="onestop_block_heading faq_heading">If I need a slideshow or picture in the background during my performance, do I have to send that in?</div>
				<div class="faq_details">Yes, please send those requests to the Program team, and they will work with you to accommodate them.</div>
			</div>
			<div class="onestop_block">
				<div class="onestop_block_heading faq_heading">Can we MC our own programs?</div>
				<div class="faq_details">The Retreat Stage program team will handle MC duties.</div>
			</div>
			<div class="onestop_block">
				<div class="onestop_block_heading faq_heading">How can we contact the NSNA Stage program team for questions?</div>
				<div class="faq_details">Email is the best way to communicate with us. Work with your regional rep or designated POC to reach out to us at <a href="mailto:nsnaretreatprogram@achi.org">nsnaretreatprogram@achi.org</a>.</div>
			</div>
		</div>	
	</div>
	<div class="clear"></div>
</section>

<div class="clear"></div>

<footer>
	<?php include_once('footer.php');?>
</footer>
<?php include_once('common-footer.php');?>
</body>
</html>