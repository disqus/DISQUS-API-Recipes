<?php
	ini_set('display_errors', 'on');
	// More documentation on this DISQUS API endpoint at http://disqus.com/api/docs/forums/listMostActiveUsers/

	$key="<your DISQUS API application key>"; // Requires a registered DISQUS API application. Create one (free) at http://disqus.com/api/applications/
	$forum="<DISQUS forum>";
	$limit="100"; // list 100 users. max is 100

	// construct the query with our API key and the query we want to make
	$endpoint = 'http://disqus.com/api/3.0/forums/listMostActiveUsers.json?api_secret='.urlencode($key).'&forum='.$forum.'&limit='.$limit;

	// setup curl to make a call to the endpoint
	$session = curl_init($endpoint);

	// indicates that we want the response back rather than just returning a "TRUE" string
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

	// execute GET and get the session back
	$data = curl_exec($session);

	// close connection
	curl_close($session);
	
	// decode the json data to make it easier to parse the php
	$results = json_decode($data);
	if ($results === NULL) die('Error parsing json');
	$users = $results->response;

	foreach ($users as $user) {
		echo $user->username.",".$user->numPosts."<br>";
	}

?>
