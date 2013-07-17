<?php

date_default_timezone_set('America/Los_Angeles');

$apikey = '<your key here>'; // get keys at http://disqus.com/api/ — can be public or secret for this endpoint
$forum = '<your DISQUS forum shortname>';
$limit = '100'; // max is 100 for this endpoint. 25 is default
$order = 'asc'; // asc = oldest to newest. default is desc
$since = '1320123600'; // 1320123600 = nov 1, 2011 midnight EST

$endpoint = 'https://disqus.com/api/3.0/threads/list?api_key='.$apikey.'&forum='.$forum.'&limit='.$limit.'&order='.$order.'&since='.$since."&cursor=".$cursor;

$j=0;
list100Threads($endpoint,$cursor,$j);

function list100Threads($endpoint,$cursor,$j) {
	//echo "Endpoint is ".$endpoint."<br />";
	//echo "Cursor is ".$cursor;
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
	if ($results === NULL) die('Error parsing json');

	// grab threads
	$threads = $results->response;

	// grab the current cursor
	$cursor = $results->cursor;
	//var_dump($cursor);

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

	//echo "<ul>";
	$i=0;
	foreach ($threads as $thread) {
		$url = $thread->link;
		$count = $thread->posts;
		$created = $thread->createdAt;
		//echo "<li>".$created." ".$url." ".$count."</li>";
		// output to csv
		header('Content-type: text/csv');
		header('Content-disposition: attachment; filename="threads.csv"');
		$fp = fopen('php://output', 'w+');  
		fwrite($fp, $created);
		fwrite($fp, ",");
		fwrite($fp, $url);
		fwrite($fp, ",");
		fwrite($fp, $count);
		fwrite($fp, "\r\n");
		fclose($fp);
		$i++;
	}
	//echo "</ul>";

	// cursor through until today
	if ($i == 100) {
		$cursor = $cursor->next;
		$i = 0;
		list100Threads($endpoint,$cursor);
		/* uncomment to only run $j number of iterations
		$j++;
		if ($j < 10) {
			list100Threads($endpoint,$cursor,$j);
		}*/
	}
}

?>