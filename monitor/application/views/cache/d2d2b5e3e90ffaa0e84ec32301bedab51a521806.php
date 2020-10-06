<!doctype html>
<html lang="en" class="no-js dark">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<title>B.I.M.A</title>
	<meta name="description" content="">
	<meta name="author" content="">
	<link href="<?=base_url()?>assets/constellation/assets/css/mini74d5.css?files=reset,common,form,standard,special-pages" rel="stylesheet" type="text/css">
	<link rel="shortcut icon" type="image/x-icon" href="<?=base_url()?>constellation/favicon.ico">
	<link rel="icon" type="image/png" href="<?=base_url()?>constellation/favicon-large.png">
	<script src="<?=base_url()?>assets/constellation/assets/js/libs/modernizr.custom.min.js"></script>

</head>

<body class="special-page login-bg dark">
	
	<section id="login-block" style="margin-top: -250px;">
		
		<div class="block-border">
			<div class="block-content no-title dark-bg">
				<center>
				<img src="<?=base_url()?>assets/constellation/assets/images/bijak.png" width="60" height="60">
				</center>
				<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b><br>BIJAK INTEGRATED MONITORING APPLICATION  
				</p>
			</div>
			<div class="block-content">
			<h1>Login System</h1>
			
			<div class="block-header">
				<p class="button no-margin" style="margin: 30px 0px 0px 0px; background-image: linear-gradient(to right, #434343 0%, black 100%);">
				
				
				<img src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/calendar.png" width="20" height="20" style="margin: 0px 0px 0px 0px;">
				<b id="Date" style="color:white;font-size:20px; margin: 0px 0px 0px 0px;">
				01 January 2020
				</b>
				<img src="<?=base_url()?>assets/constellation/assets/images/icons/fugue/clock.png" width="20" height="20" style="margin: 0px 0px 0px 0px;">
				<b id="hours" style="color:white;font-size:20px;">00</b>
				<b id="point" style="color:white;font-size:20px;">:</b>
				<b id="min" style="color:white;font-size:20px;">00</b>
				<b id="point" style="color:white;font-size:20px;">:</b>
				<b id="sec" style="color:white;font-size:20px;">00</b>
				</p>
			</div>
		
			<form class="form with-margin" name="login-form" id="login-form" method="post" action="<?=base_url()?>login/proses">
				<input type="hidden" name="a" id="a" value="send">
				<p class="inline-small-label">
					<label for="login"><span class="big">Username</span></label>
					<input type="text" name="login" id="login" class="full-width" value="">
				</p>
				<p class="inline-small-label">
					<label for="pass"><span class="big">Password</span></label>
					<input type="password" name="pass" id="pass" class="full-width" value="">
				</p>
				
				<button type="submit" class="float-right">Login</button>
				<br>
			</form>
			
			</div>
		</div>
		<br>
		<br>
		<br>
		<br>
		<br>
		<p align="center" style="color:white"><b>Copyright &copy <br>PT. BINTANG JASA ARTHA KELOLA</b><br><small style="color:white">BIJAK INTEGRATED MONITORING APPLICATION</small> 
		<br>
		<?=date("Y")?>
		</p>
	</section>
	
	
	<script src="<?=base_url()?>assets/constellation/assets/js/minif92b.php?files=libs/jquery-1.6.3.min,old-browsers,common,standard,jquery.tip.js"></script>
	<!--[if lte IE 8]><script src="<?=base_url()?>assets/constellation/assets/js/standard.ie.js"></script><![endif]-->
	
	<!-- example login script -->
	<script>
	
		$(document).ready(function()
		{
			var monthNames = ["Januari", "Febriari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
			var dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]

			// Create a newDate() object
			var newDate = new Date();
			// Extract the current date from Date object
			newDate.setDate(newDate.getDate());
			// Output the day, date, month and year    
			$('#Date').html(newDate.getDate() + ' ' + monthNames[newDate.getMonth()] + ' ' + newDate.getFullYear());

			setInterval(function() {
				// Create a newDate() object and extract the seconds of the current time on the visitor's
				var seconds = new Date().getSeconds();
				// Add a leading zero to seconds value
				$("#sec").html((seconds < 10 ? "0" : "") + seconds);
			}, 1000);

			setInterval(function() {
				// Create a newDate() object and extract the minutes of the current time on the visitor's
				var minutes = new Date().getMinutes();
				// Add a leading zero to the minutes value
				$("#min").html((minutes < 10 ? "0" : "") + minutes);
			}, 1000);

			setInterval(function() {
				// Create a newDate() object and extract the hours of the current time on the visitor's
				var hours = new Date().getHours();
				// Add a leading zero to the hours value
				$("#hours").html((hours < 10 ? "0" : "") + hours);
			}, 1000);
				
			
			
			
			
			// We'll catch form submission to do it in AJAX, but this works also with JS disabled
			$('#login-form').submit(function(event)
			{
				// Stop full page load
				event.preventDefault();
				
				// Check fields
				var login = $('#login').val();
				var pass = $('#pass').val();
				
				if (!login || login.length == 0)
				{
					$('#login-block').removeBlockMessages().blockMessage('Please enter your user name', {type: 'warning'});
				}
				else if (!pass || pass.length == 0)
				{
					$('#login-block').removeBlockMessages().blockMessage('Please enter your password', {type: 'warning'});
				}
				else
				{
					var submitBt = $(this).find('button[type=submit]');
					submitBt.disableBt();
					
					// Target url
					var target = $(this).attr('action');

					if (!target || target == '')
					{
						// Page url without hash
						target = document.location.href.match(/^([^#]+)/)[1];
					}
					
					// Request
					var data = {
						a: $('#a').val(),
						username: login,
						password: pass,
						'keep-logged': $('#keep-logged').attr('checked') ? 1 : 0
					};
					var redirect = $('#redirect');
					if (redirect.length > 0)
					{
						data.redirect = redirect.val();
					}
					
					// console.log(data);
					// Start timer
					var sendTimer = new Date().getTime();
					// Send
					$.ajax({
						url: target,
						dataType: 'json',
						type: 'POST',
						data: data,
						success: function(data, textStatus, XMLHttpRequest)
						{
							console.log(data);
							if (data.valid)
							{
								// Small timer to allow the 'cheking login' message to show when server is too fast
								var receiveTimer = new Date().getTime();
								if (receiveTimer-sendTimer < 500)
								{
									setTimeout(function()
									{
										document.location.href = data.redirect;
										
									}, 500-(receiveTimer-sendTimer));
								}
								else
								{
									document.location.href = data.redirect;
								}
							}
							else
							{
								// Message
								$('#login-block').removeBlockMessages().blockMessage(data.error || 'An unexpected error occured, please try again', {type: 'error'});
								
								submitBt.enableBt();
							}
						},
						error: function(XMLHttpRequest, textStatus, errorThrown)
						{
							// Message
							$('#login-block').removeBlockMessages().blockMessage('Error while contacting server, please try again', {type: 'error'});
							
							submitBt.enableBt();
						}
					});
					
					// Message
					$('#login-block').removeBlockMessages().blockMessage('Please wait, cheking login...', {type: 'loading'});
				}
			});
		});
	
	</script>
	
</html>
