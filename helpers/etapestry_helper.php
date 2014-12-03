<?php

/**
 * This function grabs a user's tweets from twitter. It's not a
 *  bad idea to cache the output of this call!
 * @param  string $username The Twitter username to grab
 * @param  int    $n        The number of tweets to pull down
 * @return array            An array of tweets
 */
function birdseed_fetch($username, $n = 10)
{
//	$base_url = config_item('twitter_api_base_url');
//	$call_url = $base_url
//	            . 'statuses/user_timeline.json?screen_name='
//	            . $username
//	            . '&count='
//	            . $n;
//
//	$tweets = json_decode(file_get_contents($call_url));
//
//	if($tweets === FALSE)
//	{
//		# We didn't get a valid response back. Maybe the innerwebs are down.
//		return array();
//	}
//
//	return $tweets;
}