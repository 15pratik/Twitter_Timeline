<?php
	if($_GET['type']=='follower')
	{
		getTweets($_GET['screenname'],$_GET['type']);
	}
	function getTweets($sname,$type)
	{
		$tweeturl = 'https://api.twitter.com/1/statuses/user_timeline.json?include_entities=true&include_rts=false&screen_name='.$sname.'&count=10';
		
		$channel = curl_init();
		curl_setopt($channel, CURLOPT_URL, $tweeturl);
		curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($channel);
		curl_close($channel);
		
		$obj = json_decode($response);
		$divno = 1;
		$replacecount =1;
		foreach ($obj as $item)
		{
			$text = $item->text;
			$timestamp = $item->created_at;
			if($type == 'tweet')
			{
				echo "<div id='divTweet$divno'>";
			}
			if($type =='follower')
			{
				echo "<div id='divFollo$divno'>";
			}
			echo "<p align='center'>Tweets of $sname</p>";
			echo "<p align='center'>Tweet $divno of 10</p>";
			print $text."<br> Tweeted on: ".str_replace("+0000","",$timestamp,$replacecount);
			echo "</div>";
			$divno = $divno + 1;
		}
	}
?>