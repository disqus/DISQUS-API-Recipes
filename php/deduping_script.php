<?php

//Check a number of comments against themselves to confirm they're not duplicates. If they are, delete them.

date_default_timezone_set('America/Los_Angeles');

$apikey = 'SECRET_KEY'; // get keys at http://disqus.com/api/ — can be public or secret for this endpoint
$forum = 'SHORTNAME';
$limit = 50; // max is 100 for this endpoint. 25 is default
$order = 'asc'; // asc = oldest to newest. default is desc
$since = '1318846851'; // **** You may need to reset this if you encounter a memory error. **** HEX Timecode - Timestamp conversion http://fmdiff.com/fm/timestamp.html

$endpoint = 'https://disqus.com/api/3.0/forums/listPosts?api_secret='.$apikey.'&forum='.$forum.'&limit='.$limit.'&order='.$order.'&since='.$since.'&cursor='.$cursor;

$num_results_checked = 0;
$cursorMaxCount=0;
$totalThreads=0;
$closedThreads=0;
list100Threads($endpoint,$cursor,$cursorMaxCount,$limit);

function list100Threads($endpoint,$cursor,$cursorMaxCount,$limit) {
	global $apikey, $totalThreads, $closedThreads, $num_results_checked;
	// create a new cURL resource
	$session = curl_init($endpoint.$cursor);

	// set URL and other appropriate options
	curl_setopt($session, CURLOPT_RETURNTRANSFER, 1); // instead of just returning true on success, return the result on success

	// set threads info
	$data = curl_exec($session);

	// close cURL resource, and free up system resources
	curl_close($session);

	// decode the json data to make it easier to parse the php
	$results = json_decode($data);

	if ($results === NULL) die('No data has been gathered.');

	// grab threads
	$posts = $results->response;

	// grab the current cursorMaxCount
	$cursor = $results->cursor;

	/* What the cursor array looks like:
		"cursor": {
		    "prev": null,
		    "hasNext": true,
		    "next": "1320136178861708:0:0",
		    "hasPrev": false,
		    "total": null,
		    "id": "1320136178861708:0:0",
		    "more": true
		} */
	
		//Start from the first response.
		$post_checked= 0;
		//Start from the response directly after the first.
		$post_checked_against = 1;

		//Cycle through the responses: Run while we're under the response limit and we have valid Post IDs to check against.
		while ($post_checked < $limit && $posts[$post_checked_against]->id !== NULL){
			echo '<h3>Post being checked - '.$post_checked_against.'</h3>';

			//Check against other responses: Run while we're not equal to the limit and we still have valid Post IDs to check against.
			while($post_checked_against !== $limit && $posts[$post_checked_against]->id !== NULL) {
				echo '<p>Comparing '.$posts[$post_checked] -> id.' with '.$posts[$post_checked_against] -> id.'</p>';

				//If the messages of these two responses are the same posted by the same username, delete the Post ID being checked against vs. the one cycled through.
				if($posts[$post_checked] -> message === $posts[$post_checked_against] -> message && $posts[$post_checked] -> author -> username === $posts[$post_checked_against] -> author -> username) {
				$getThreadDetails = 'https://disqus.com/api/3.0/posts/remove?api_secret='.$apikey.'&post='.$posts[$post_checked_against] -> id;
				$threadDetailsSession = curl_init($getThreadDetails);
				curl_setopt($threadDetailsSession,CURLOPT_POST,1);
				curl_setopt($threadDetailsSession,CURLOPT_POSTFIELDS,'');
				curl_setopt($threadDetailsSession,CURLOPT_RETURNTRANSFER,1); // prevents the output from being displayed on the pgae
				$data = curl_exec($threadDetailsSession);
				curl_close($threadDetailsSession);
				echo '<h4>Same comment by the same author. Post '.$posts[$post_checked_against] -> id.' was deleted.</h4>';
				}
				//Cycling through the posts being checked against.
				$post_checked_against++;
			}
			
			//Increment in order to pass up what has already been checked and cycle through what is left.
			$post_checked++;
			$post_checked_against = $post_checked+ 1;

			//Tracking the number of Posts checked.
			$num_results_checked++;
		}
	
	//Getting the next page of results if we hit the limit.
	if ($post_checked_against === $limit) {
		$cursor = $cursor->next;
		echo '<h4>'.$num_results_checked.' processed. On to the next batch.</h4>';
		list100Threads($endpoint,$cursor,$cursorMaxCount,$limit);
		
		/* uncomment to only run $cursorMaxCount number of iterations
		$cursorMaxCount++;
		if ($cursorMaxCount < 10) {
			list100Threads($endpoint,$cursor,$cursorMaxCount);
		}*/
	}
}
	//Telling us that we're done.
	echo '<h4>Done processing. '.$num_results_checked.' posts checked.</h4>';

?>