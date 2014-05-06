<?php
	ini_set('display_errors', 'on');
	
	$key="<your DISQUS API application key>"; // Requires a registered DISQUS API application. Create one (free) at http://disqus.com/api/applications/
	$remote_domain="<remote domain slug>"; // slug of the remote domain established at http://disqus.com/api/sso/ (the bolded word)
	$remote_identifier="<unique ID>"; // unique ID passed in the remote_auth_s3 payload
	// For more on unique ID see http://docs.disqus.com/developers/sso/ > "Using HMAC-SHA1 to pass user data" > "The message body (Base64-encoded)" > "id"

	// construct the query with our API key and the query we want to make
	// FORMAT: user=remote:remote_domain-remote_identifier
	$endpoint = 'http://disqus.com/api/3.0/users/details.json?api_key='.urlencode($key).'&user=remote:'.$remote_domain.'-'.$remote_identifier;

	// setup curl to make a call to the endpoint
	$session = curl_init($endpoint);

	// indicates that we want the response back rather than just returning a "TRUE" string
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

	// execute GET and get the session back
	$result = curl_exec($session);

	// close connection
	curl_close($session);
	
	// decode the json data to make it easier to parse the php
	$results = json_decode($result);
	if ($results === NULL) die('Error parsing json');

	// show the response in the browser
	// var_dump($results);

	$sso_username = $results->response->username;
	echo $sso_username;

?>
