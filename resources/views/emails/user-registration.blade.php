<!DOCTYPE html>
<html>
<head>
    <title>Moonlaunch Media</title>
</head>
<body>
	<h4>Hello {{ $details['name'] }},</h4>
	<p> Wellcome to Moonlaunch Media </p>
	<p> Thanks for the registration with us. Please verify your email by just clicking on the link provided below.</p>
	<p> <a href=" {{ $details['usertoken'] }}"> Verify Email </a></p>
	<p>Regards,</p>
	<p>Banner Team</p>
</body>
</html>