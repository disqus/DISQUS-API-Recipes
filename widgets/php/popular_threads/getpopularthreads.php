<!DOCTYPE html>
<html>
<head></head>
<body>

<?php
	// cache filename to save the API results to
	$filename="dsq-listpopular-cache.txt";
	// disqus application secret key. You can create one at http://disqus.com/api/applications/
	$key="ENTER_SECRET_KEY_HERE";
	// forum shortname to pull data from
	$forum="ENTER_SHORTNAME_HERE";
	// interval which to pull the most popular threads from. Options are 1h, 6h, 12h, 1d, 3d, 7d, 30d, 90d
	$interval="90d";
	// limit of threads we want to receive (max 100)
	$limit="5";
	// construct the query with our API key and the query we want to make
	$endpoint = 'https://disqus.com/api/3.0/threads/listPopular.json?api_secret='.urlencode($key).'&forum='.$forum.'&interval='.$interval.'&limit='.$limit;

	//  Initiate curl
	$ch = curl_init();
	// Disable SSL verification
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	// Will return the response, if false it print the response
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// Set the url
	curl_setopt($ch, CURLOPT_URL, $endpoint);
	// Execute
	$data = curl_exec($ch);
	$results = json_decode($data);

	// error message if API call fails
	if ($results === NULL) die('Error getting API results');
	
	// parse the desired JSON data into HTML for use on your site
	$threads = $results->response;
		foreach ($threads as $thread) {
		$finalResults .= "<p class=\"dsq-widget-thread\"><a href=\"".$thread->link."\">".$thread->title."</a>&nbsp;(".$thread->posts.")</p>";
		}
		
	// save api results to the cache file you specified in $filename
	file_put_contents($filename, $finalResults);	
	
	// outputs the parsed HTML for your own viewing (optional)
	echo $finalResults;
?>

</body>
</html>
