<?php

function updateThreadTitle($threadId, $shortname, $newTitle)
{
	$api="<secret key goes here>";
	$accessToken="<your default access token>";
	$fields_string=""; // DO NOT EDIT

	// set POST variables
	$url = 'https://disqus.com/api/3.0/threads/update.json';
	
	$fields = array(
		'api_secret'=>urlencode($api), // change to api_key when using a public key
		'thread'=>urlencode($threadId),
		'title'=>urlencode($newTitle),
		'forum'=>$shortname,
	);

	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string,'&');
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_POST,count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);

	// execute POST
	$result = curl_exec($ch);

	// close connection
	curl_close($ch);
	
	// Show new information
	var_dump($result);
}

?>