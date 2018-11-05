<?php echo $header; ?>
<!-- Google Code for Contact Us Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 859761902;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "-PKcCPbXxG4Q7tn7mQM";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/859761902/?label=-PKcCPbXxG4Q7tn7mQM&guid=ON&script=0"/>
</div>
</noscript>
<div class="container">
	<div class="container" id="content">
		<h1 class="red jn member-title">CONTACT US</h1>
		<div class="col-md-12">
			<div class="col-md-5 pd-none contact-area">Wondering what size rug you should get for your living room? Need a reccomendation for the best rug cleaners on the market? Or maybe you just want to chat? We are here to help!<br />
				Just send us an email or give us a ring!
				<p>We cannot wait to hear from you!</p>

				<p>Connect with us!</p>

				<p class="contact-icons"><a href="http://www.pinterest.com/rothrugs/"><img src="catalog/view/theme/rr2/img/p-icon.jpg" /></a> <a href="https://twitter.com/rothrugs"><img src="catalog/view/theme/rr2/img/t-icon.jpg" /></a> <a href="https://www.facebook.com/RothRugs?ref=hl"><img src="catalog/view/theme/rr2/img/f-icon.jpg" /></a> <a href="http://instagram.com/rothrugs"><img src="catalog/view/theme/rr2/img/i-icon.jpg" /></a></p>

				<p class="phone">(888) 776 - 6425</p>
			</div>
			<div class="col-md-6 col-md-offset-1 pd-none">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="contact-form">
					<fieldset>
						<div class="form-group required">
							<div>
								<input type="text" name="name" value="<?php echo $name; ?>" id="input-name" placeholder="First Name"/>
								<?php if ($error_name) { ?>
								<div class="text-danger"><?php echo $error_name; ?></div>
								<?php } ?>
							</div>
						</div>					
						<div class="form-group required">
							<div>
								<input type="text" name="lastname" value="<?php echo $lastname; ?>" id="input-name" placeholder="Last Name"/>
								<?php if ($error_lastname) { ?>
								<div class="text-danger"><?php echo $error_lastname; ?></div>
								<?php } ?>
							</div>
						</div>
						<div class="form-group required">
							<div>
								<input type="text" name="email" value="<?php echo $email; ?>" id="input-email" placeholder="Email"/>
								<?php if ($error_email) { ?>
								<div class="text-danger"><?php echo $error_email; ?></div>
								<?php } ?>
							</div>
						</div>
						<div class="form-group required">
							<div>
								<input type="text" name="phone" value="<?php echo $phone; ?>" id="input-email" placeholder="Phone"/>
								<?php if ($error_phone) { ?>
								<div class="text-danger"><?php echo $error_phone; ?></div>
								<?php } ?>
							</div>
						</div>
						<div class="form-group required">
							<div>
								<input type="text" name="preferred" value="<?php echo $preferred; ?>" id="input-preferred" placeholder="Preferred Contact Time"/>
								<?php if ($error_preferred) { ?>
								<div class="text-danger"><?php echo $error_preferred; ?></div>
								<?php } ?>
							</div>
						</div>
						<div class="form-group required">
							<div>
								<textarea name="enquiry" rows="10" id="input-enquiry"><?php echo $enquiry; ?></textarea>
								<?php if ($error_enquiry) { ?>
								<div class="text-danger"><?php echo $error_enquiry; ?></div>
								<?php } ?>
							</div>
						</div>
						<!-- <div class="form-group required">
							<div>
								<input type="text" name="captcha" value="<?php echo $captcha; ?>" id="input-captcha" placeholder="Captcha"/>
								<img src="index.php?route=tool/captcha" alt="" />
								<?php if ($error_captcha) { ?>
								<div class="text-danger"><?php echo $error_captcha; ?></div>
								<?php } ?>
							</div>
						</div> -->
						<div class="form-group required">
							<div>
								<script src='https://www.google.com/recaptcha/api.js'></script>
								<div class="g-recaptcha" data-sitekey="6LfFHxEUAAAAANF3HP6uhVjh1Q0FB_yVohyMB-zv"></div>
								<?php if ($error_rcaptcha) { ?>
								<div class="text-danger"><?php echo $error_rcaptcha; ?></div>
								<?php } ?>
							</div>
						</div>
					</fieldset>
					<div class="buttons">
						<div class="pull-right">
							<input class="btn btn-primary" type="submit" value="Submit" />
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

		<?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?> 