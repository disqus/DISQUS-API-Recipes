<?php

date_default_timezone_set('America/Los_Angeles');

$apikey = '<secret (not public) key goes here>'; // get keys at http://disqus.com/api/ — can be public or secret for this endpoint
$forum = '<forum shortname goes here>';
$limit = '100'; // max is 100 for this endpoint. 25 is default
$order = 'asc'; // asc = oldest to newest. default is desc
$since = '1333256400'; // 1333256400 = april 4, 2012 midnight EST

$endpoint = 'https://disqus.com/api/3.0/threads/list?api_secret='.$apikey.'&forum='.$forum.'&limit='.$limit.'&order='.$order.'&since='.$since."&cursor=".$cursor;

$cursorMaxCount=0;
$totalThreads=0;
$closedThreads=0;
list100Threads($endpoint,$cursor,$cursorMaxCount);

function list100Threads($endpoint,$cursor,$cursorMaxCount) {
	global $apikey, $totalThreads, $closedThreads;
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
	if ($results === NULL) die('Error parsing JSON');

	// grab threads
	$threads = $results->response;

	// grab the current cursor
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
	foreach ($threads as $thread) {
		$id = $thread->id;
		echo "Processing thread ".$id."... ";
		// first let's find out if the thread is already open or not
		$getThreadDetails = 'https://disqus.com/api/3.0/threads/details?api_secret='.$apikey.'&thread='.$id;
		$threadDetailsSession = curl_init($getThreadDetails);
		curl_setopt($threadDetailsSession, CURLOPT_RETURNTRANSFER, 1); // instead of just returning true on success, return the result on success
		$data = curl_exec($threadDetailsSession);
		curl_close($threadDetailsSession);
		// decode the json data to make it easier to parse the php
		$result = json_decode($data);
		if ($result === NULL) die('Error parsing JSON');
		$thread = $result->response;
		if ($thread->isClosed == false) {
			echo "Thread is already open. Skipping.<br />";
		} else {
			// open the thread
			$openThread = 'https://disqus.com/api/3.0/threads/open?api_secret='.$apikey.'&thread='.$id;
			$openThreadSession = curl_init($openThread);
			curl_setopt($openThreadSession,CURLOPT_POST,1);
			curl_setopt($openThreadSession,CURLOPT_POSTFIELDS,'');
			curl_setopt($openThreadSession,CURLOPT_RETURNTRANSFER,1); // prevents the output from being displayed on the pgae
			$result = curl_exec($openThreadSession);
			curl_close($openThreadSession);
			echo "Thread was closed. Opened.<br />";
			$closedThreads++;	
		}
		$totalThreads++;
		$i++;
	}

	// cursor through until today
	if ($i == 100) {
		$cursor = $cursor->next;
		$i = 0;
		list100Threads($endpoint,$cursor);
		/* uncomment to only run $cursorMaxCount number of iterations
		$cursorMaxCount++;
		if ($cursorMaxCount < 10) {
			list100Threads($endpoint,$cursor,$cursorMaxCount);
		}*/
	}
}

echo "<br />Out of ".$totalThreads." threads, ".$closedThreads." were closed. They are now open. Blind, but now they see.";

?>