<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title></title>
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="author" content="" />
		<link rel="shortcut icon" href=""> <!--favicon link-->

		<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
		<link rel="stylesheet" type="text/css" href="css/bootstrap-readable.css" />
		<link rel="stylesheet" type="text/css" href="css/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="css/style.css" />
	</head>

	<body>
		<?php 
			session_start(); 
		?>

		<div class="container">	

			<nav class="left-nav col-md-2 col-xs-2">
				<div class="nav-button" name="en">
					English
				</div>
				<div class="nav-button" name="ms">
					Malay
				</div>
				<div class="nav-button" name="ta">
					Tamil
				</div>
				<div class="nav-button" name="ja">
					Japanese
				</div>
			</nav>

			<div class="row">
				<header class="col-md-11 col-md-offset-1
								col-xs-9 col-xs-offset-3">
					<h1>My Word Journal</h1>
				</header>
			</div>

			<div class="float-left 
						col-md-5 col-md-offset-1
						col-xs-9 col-xs-offset-3">
				<div class="row">
					<section class="translate-wrapper col-md-12">
						<header>Translate Parameters</header>
						<div class="translate-content">
							<div class="row">
								<div class="well 
											col-md-8 col-md-push-2
											col-xs-10 col-xs-push-1">
									<span id="langFrom">null</span>
									<span class="glyphicon glyphicon-arrow-right"></span>
									<span id="langTo">null</span>
									<span class="glyphicon glyphicon-refresh" id="changeLang"></span>
								</div>
							</div>
							<div class="row">
								<input type="text" id="langPhrase" class="langPhrase" autofocus/>
								<span class="glyphicon glyphicon-globe" id="btnSubmit"></span>
								<span class="langPhrase-bar"></span>
							</div>		
						</div>
					</section>				
				</div>

				<div class="row">
					<section class="fav-wrapper col-md-12">
						<header>Favourite Words</header>
						<div class="fav-content">
							<div class="row">
								<div class="fav">
									<p>No favourites found!</p>
								</div>
							</div>		
						</div>
					</section>		
				</div>
			</div>

			<div class="float-right 
						col-md-5 col-md-offset-1
						col-xs-9 col-xs-offset-3">
				<div class="row">
					<section class="result-wrapper col-md-12">
						<header>Results Obtained</header>
						<div class="result-content">							
							<div class="row">
								<div class="result">
									<p id="result-empty">No content to show</p>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>

		</div>

	<script src="js/jquery-2.1.4.min.js"></script>
	<script src="js/bootstrap.js"></script>	
	<script src="js/jquery-ui.js"></script>
	<script>
		$(document).ready(function() {

			//start clicks action
			var langFrom = langTo = null;

			$('.nav-button').on('click', function() {
				var navBtnData = this.getAttribute('name');
				switch(navBtnData) {
					case 'en':
						langFrom = 'English';
						langTo = 'English';
						break;
					case 'ms':
						langFrom = 'English';
						langTo = 'Malay';
						break;
					case 'ta':
						langFrom = 'English';
						langTo = 'Tamil';
						break;
					case 'ja':
						langFrom = 'English';
						langTo = 'Japanese';
						break;
				}

				$('#langFrom').html(langFrom);
				$('#langTo').html(langTo);

				$.ajax({
					type: "POST",
					url: "fav.php",
					data: { langFav: navBtnData},
					success: function(result) {
						$('.fav').html(result);
					}
				});
			});

			$('#changeLang').on('click', function() {
				langTo = [langFrom, langFrom = langTo][0];
				$('#langFrom').html(langFrom);
				$('#langTo').html(langTo);
			});

			$('#btnSubmit').on('click', function() {
				var langPhrase = $('#langPhrase').val();
				$.ajax({
					type: "POST",
					url: "translate.php",
					data: {
						langFrom: langFrom,
						langTo: langTo,
						langPhrase: langPhrase
					},
					success: function(result) {
						$('.result').html(result);
						//start jquery ui
						$('.accordion').accordion({
							collapsible: true,
							active: false
						});
						//end jquery ui
					}
				});
			});

			$('#langPhrase').keyup(function(event) {
				if(event.keyCode == 13) {
					$('#btnSubmit').click();
				}
			});
			//end clicks action

			//start some css hacks
			$('#langFrom').parent().map(function() {
				$(this).css({"float":"left", "margin-right":"10%"});
				$(this).next().css({"float":"left", "margin-right":"5%"});
			});

			$('#langPhrase').parent().map(function() {
				$(this).css("clear", "left");
				$(this).children().css({"border": "none", "background-color": "ghostwhite"});
			});

			$('.nav-button').on('click', function() {
				$('.nav-button').removeClass("clicked");
				$(this).toggleClass("clicked");
			})
			//ens some css hacks
		})
	</script>
	</body>
</html>
