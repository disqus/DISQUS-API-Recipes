<?php
	ini_set('display_errors', 'on');

	$secret_key='YOUR_API_SECRET_KEY'; // Requires a registered DISQUS API application. Create one (free) at http://disqus.com/api/applications/
	$access_token = 'YOUR_ACCESS_TOKEN'; // Admin access tokens are required to get the email address. They can be found at http://disqus.com/api/applications/
	$comment_id = $_GET['id']; // The comment ID you need information for
	
	// construct the query with our API key and the query we want to make
	$endpoint = 'https://disqus.com/api/3.0/posts/details.json?api_secret='.urlencode($secret_key).'&access_token='.$access_token.'&post='.$comment_id;
	
	// cURL the endpoint
	$session = curl_init($endpoint);
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($session);
	curl_close($session);

	// decode the json data to make it easier to parse the php
	$results = json_decode($result);
	if ($results === NULL) die('Error parsing json');

	// Get the data we need
	$username = $results->response->author->username;
	$email = $results->response->author->email; // This will be blank if you aren't using the right access_token, or are not using the secret key
	$display_name = $results->response->author->name;
	$avatar_url = $results->response->author->avatar->cache;
	
	// Return it formatted
	echo '<p><img src="'.$avatar_url.'"/></p><ul><li>'.$display_name.'</li><li>'.$username.'</li><li>'.$email.'</li></ul>';

?>