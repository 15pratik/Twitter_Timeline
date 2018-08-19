<?php
	session_start();
	include 'lib/EpiOAuth.php';
	include 'lib/EpiTwitter.php';
	include 'lib/secret.php';
	include 'lib/EpiCurl.php';
	$twitterObj = new EpiTwitter($consumer_key, $consumer_secret);
	$oauth_token = $_GET['oauth_token'];
	echo "<div class='container'>";

	//no token
	if($oauth_token == '')
	{
		$url = $twitterObj->getAuthorizationUrl(); //signin
		echo "<h2>Twitter Timeline Challenge</h2>";
		echo "<div class='left_col'>";
			echo "<a href='#' class='know'>Know More</a>";
			echo "<div class='panel'>";
				echo "<p><strong>Part-1: User Timeline</strong></p>
					  <ol>
							<li>Start => User visit your script page.
							<li>He will be asked to connect using his Twitter account (Hint: Twitter Auth).
							<li>Once authenticated, your script will pull latest 10 tweets form his \"home\" timeline.
							<li>10 tweets will be displayed using a jQuery-slideshow.
					  </ol>
					  <p><strong>Part-2: Followers Timeline</strong></p>
					  <ol>
							<li>Below jQuery-slideshow (in step#4 from part-1), display list 10 followers (you can take any 10 random followers).
							<li>When user will click on a follower name, 10 tweets from that follower's user-timeline will be displayed in same jQuery-slider, without page refresh (use AJAX).";
				echo "</ol>";
			echo "</div>";
		echo "</div>";
		
		echo "<div class='right_col'>";
				echo "<a href='$url'>Please Sign In Using Your Twitter Account</a>";			
		echo "</div>";
	}
	else
	{
		$twitterObj->setToken($_GET['oauth_token']);
		$token = $twitterObj->getAccessToken();
		$twitterObj->setToken($token->oauth_token, $token->oauth_token_secret);
		$_SESSION['ot'] = $token->oauth_token;
		$_SESSION['ots'] = $token->oauth_token_secret;
		$twitterInfo= $twitterObj->get_accountVerify_credentials();
		$twitterInfo->response;
		$username = $twitterInfo->screen_name;
		$profilepic = $twitterInfo->profile_image_url;
		
		echo "<div class='left_col'>";
			echo "<div id='divWelcome' align='center'>Logged in as ".$username;
			echo "<br><img src=$profilepic title='Profile Picture'></div>";
		echo "</div>";

		echo "<div class='right_col'>";		
			echo "<h2>Twitter Timeline Challnege</h2>";
			
			include 'tweet.php';
			echo "Tweet Showcase:";		
			echo "<div id='divTweetContainer' class='slideshowTweet'>";
				getTweets($username, 'tweet');
			echo "</div>";
			
			include 'follower.php';
			getFollo($username);
		echo "</div>";
	}
	echo "</div>";
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Twitter Timeline Challenge </title>
 
    <link rel="stylesheet" type="text/css" href="style.css" >

	<!-- Include jQuery library -->
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
    <!-- Include Cycle plugin By Mike Alsup, Thanks malsup-->
	<script type="text/javascript" src="http://cloud.github.com/downloads/malsup/cycle/jquery.cycle.all.latest.js"></script>
	<script type="text/javascript">
		function remProp() {
			<!-- Remove the display style property of inner divs-->
			document.getElementById("divTweetContainer").getElementsByTagName("div").style.removeProperty("display"); 
		}
		$(document).ready(function() {
			$('.slideshowTweet').cycle({
				fx: 'fade'
			});
		});
		$(document).ready(function(){
			$(".know").click(function(){
				$(".panel").slideToggle("slow");
			});
		});
	</script>
</head>
    <body onLoad="remProp()">
    </body>
</html>