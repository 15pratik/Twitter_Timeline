<?php
	function getFollo($sname)
	{
		$follourl = 'https://api.twitter.com/1/followers/ids.json?cursor=-1&stringify_ids=true&screen_name='.$sname;
		$channel = curl_init();
		curl_setopt($channel, CURLOPT_URL, $follourl);
		curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($channel);
		curl_close($channel);
		
		$obj = json_decode($response);
		echo "<br>";
		$idSet = "";
		$limit = 0;
		foreach ($obj as $item)
		{
			if ( is_array( $item ) )
			{
				foreach ( $item as $sub_item )
				{
				 	if ($limit <10)
				 	{
					  $id = $sub_item;
					  $idSet = $idSet.",".$id;
					  $limit = $limit +1;
					}
				}
		  	} 
		}		
		$lookurl='https://api.twitter.com/1/users/lookup.json?user_id='.$idSet.'&include_entities=true';
		$channel = curl_init();
		curl_setopt($channel, CURLOPT_URL, $lookurl);
		curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($channel);
		curl_close($channel);
		
		$obj = json_decode($response);
		echo "<div id='divList' style='height: 150px; width: 700px; max-height: 150px; max-width: 700px;'>";
		echo "<font style='text-decoration:underline;'>Followers of $sname:</font><br><br>";
		foreach ($obj as $item)
		{
			$name = $item->name;
			$screenname = $item->screen_name;
			$profilepic = $item->profile_image_url_https;
			
			echo "<div>";
				echo "<img src=$profilepic style='vertical-align:middle;' title='Profile Picture'>";
				echo "<span style='padding-left:10px;'>Name: ".$name." | Screen Name: ".$screenname." | ";
				?><a href="#" onClick="ajaxLoad('<?php echo $screenname?>')">View Tweets</a><?php
			echo "</span></div><hr>";
		}
		echo "</div>";
	}
?>
<script type="text/javascript">
	function ajaxLoad(sname)
	{
		var screenname = sname;
		var xmlhttp;
		if (window.XMLHttpRequest)
		{
			xmlhttp=new XMLHttpRequest();
		}
		else
		{// older browsers (IE)
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
		{
		  	if (xmlhttp.readyState==4 && xmlhttp.status==200)
		  	{
				document.getElementById("divTweetContainer").innerHTML=xmlhttp.responseText;
				repeatSlideshow();
		  	}
		}
		xmlhttp.open("GET","tweet.php?screenname="+screenname+"&type=follower",true);
		xmlhttp.send();
	}
	function repeatSlideshow()
	{
		$('.slideshowTweet').cycle({
			fx: 'fade'
		});
	}
</script>