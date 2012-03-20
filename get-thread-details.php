<?php
	ini_set('display_errors', 'on');
	
	$key="<api key>"; // Requires a registered DISQUS API application. Create one (free) at http://disqus.com/api/applications/
	$thread="<DISQUS thread ID>";
	$forum="<DISQUS forum shortname>";

	// construct the query with our apikey and the query we want to make
	// Change api_key to api_secret when using your secret key
	/*
		DIFFERENT TYPES OF THREAD LOOKUPS:
		1. By DISQUS thread ID (default): thread=%s — thread IDs are universally unique in DISQUS, so you can remove 'forum' param if you like
		2. By identifier: thread:ident=%s — requires the forum parameter
		3. By URL: thread:link=%s — requires the forum parameter
	*/
	$endpoint = 'http://disqus.com/api/3.0/threads/details.json?api_key='.urlencode($key).'&forum='.$forum.'&thread='.urlencode($thread);

	// setup curl to make a call to the endpoint
	$session = curl_init($endpoint);

	// indicates that we want the response back rather than just returning a "TRUE" string
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

	// execute GET and get the session back
	$result = curl_exec($session);

	// close connection
	curl_close($session);

	// show the response in the browser
	var_dump($result);

?>