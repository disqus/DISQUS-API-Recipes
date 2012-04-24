<?php

// NOTE: Comments are called 'posts' in the Disqus system. Not to be confused with posts (a.k.a. articles) in the traditional blogging sense.

date_default_timezone_set('America/Los_Angeles');

$apikey = '<your key here>'; // get keys at http://disqus.com/api/ — can be public or secret for this endpoint
$parentPost = '<ID of the comment whose replies we want>';

// first we need to determine the thread ID of the post in question
$endpoint = 'https://disqus.com/api/3.0/posts/details?api_key='.$apikey.'&post='.$parentPost;

// create a new cURL resource
$session = curl_init($endpoint);

// set URL and other appropriate options
curl_setopt($session, CURLOPT_RETURNTRANSFER, 1); // instead of just returning true on success, return the result on success

// set threads info
$data = curl_exec($session);

// close cURL resource, and free up system resources
curl_close($session);

// decode the json data to make it easier to parse the php
$results = json_decode($data);
if ($results === NULL) die('Error parsing JSON');

// grab parent post's details
$thread = $results->response->thread;

// now that we know the thread ID, let's grab all of its posts

$limit = '100'; // max is 100 for this endpoint. 25 is default
$order = 'asc'; // asc = oldest to newest. default is desc
$since = $thread->createdAt; // leave alone to get all reply comments. otherwise set as UNIX timestamp

$endpoint = 'https://disqus.com/api/3.0/threads/listPosts?api_key='.$apikey.'&thread='.$thread.'&limit='.$limit.'&order='.$order.'&since='.$since;

$cursorMaxCount=0;
list100Posts($endpoint,$cursor,$cursorMaxCount);

function list100Posts($endpoint,$cursor,$cursorMaxCount) {
	global $parentPost;

	$session = curl_init($endpoint.$cursor);
	curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($session);
	curl_close($session);
	$results = json_decode($data);
	if ($results === NULL) die('Error parsing JSON');
	
	$posts = $results->response;

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
		}
	*/

	$i=0;
	foreach ($posts as $post) {
		if ($post->parent == $parentPost) {
			echo "<p>Posted at ".$post->createdAt." by ".$post->author->name." (".$post->author->profileUrl.") :";
			echo "<div>".$post->message."</div>";
			// uncomment to output results to .csv
			/*
			header('Content-type: text/csv');
			header('Content-disposition: attachment; filename="replies.csv"');
			$fp = fopen('php://output', 'w+');  
			fwrite($fp, $post->createdAt);
			fwrite($fp, ",");
			fwrite($fp, $post->author->name);
			fwrite($fp, ",");
			fwrite($fp, $post->author->profileUrl);
			fwrite($fp, ",");
			fwrite($fp, $post->message);
			fwrite($fp, "\r\n");
			fclose($fp);
			*/
		}
		$i++;
	}

	// cursor through until today
	if ($i == 100) {
		$cursor = $cursor->next;
		$i = 0;
		list100Posts($endpoint,$cursor);
		/* uncomment to only run $cursorMaxCount number of iterations
		$cursorMaxCount++;
		if ($cursorMaxCount < 10) {
			list100Threads($endpoint,$cursor,$cursorMaxCount);
		}*/
	}
}

?>