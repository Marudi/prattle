<!DOCTYPE html>
<html lang="en">
<head>
    <title> Book My Salon </title>
    
	<!-- FONTS -->
    
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Great+Vibes' rel='stylesheet' type='text/css'>
    
</head>
<body style="margin:30px;padding: 25px;">

	<!-- ABOUT-US-TITLE -->
	<div style="border:1px solid #e7e7e7;padding:20px;border-radius: 8px;">
		<div style="width:100%;">
			<img src="<?php echo base_url(); ?>assets/images/login_logo.png" style="margin:0 auto; display:block;" align="middle">
		</div>
			<div style="border-radius: 8px;font-family: 'Open Sans',sans-serif;font-size:16px;line-height:35px;text-align:justify;padding-bottom: 30px;border:1px solid #e7e7e7;margin-bottom:20px;padding: 30px;">
		Hi <?php echo $firstname; ?>,
		<br>
		<br>
        <p>
        Your account has beed activated.
        </p>
        <p>
		Thank you for your interest in Book my saloon.Our Vision is to make the beauty experience more easy,convinient and affordable,Your Comments and feed backs are most valuable to us for upgradingthe service provided to you.Gave us a knock if you have any other doubts and questions.
        </p>
        <p>
        <h4>Your account details</h4>
        <div> Login URL : <b><?php echo base_url(); ?></b> </div>
        <div> Username : <b><?php echo $username; ?></b> </div>
        <div> Password : <b><?php echo $password; ?></b> </div>
        </p>
        <p>
		If you have any questions or feedback, please feel free to contact us at <a href="mailto:enquiry@bookmysalons.com" style="color:#eb4354;font-style:italic;">enquiry@bookmysalons.com </a>.
        </p>
		<br>
		<br>
		Thank you,<br>
		BMS Team

		
		</div>
		<div style="width:100%;background:#424047;color:#777382;font-family: 'Open Sans',sans-serif;text-align:center;padding-top: 10px;font-size:13px;">
		Copyright &copy; BOOKMYSALON. All rights reserved. <br>
		<div style="list-style:none;display:inline-flex;padding-top:10px;padding-bottom:10px;">
		<li style="padding:5px"><img src="<?php echo base_url(); ?>assets/images/facebook.png"></li>
		<li style="padding:5px"><img src="<?php echo base_url(); ?>assets/images/instagram.png"></li>
		<li style="padding:5px"><img src="<?php echo base_url(); ?>assets/images/twitter.png"></li>
		<li style="padding:5px"><img src="<?php echo base_url(); ?>assets/images/youtube.png"></li>
		</div>
		</div>
	</div>
 

</body>
</html>