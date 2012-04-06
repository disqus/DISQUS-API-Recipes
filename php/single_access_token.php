<?php
    define('DISQUS_SECRET_KEY', '<secret_key>');
    define('DISQUS_PUBLIC_KEY', '<public_key>');

extract($_POST);

$thread_id = "<thread_id>";
$access_token = "<access_token>";

$url = 'https://disqus.com/api/3.0/threads/close.json';
$fields = array(
						'access_token'=>urlencode($access_token),
						'api_key'=>urlencode(DISQUS_PUBLIC_KEY),
						'api_secret'=>urlencode(DISQUS_SECRET_KEY),
						'thread'=>urlencode($thread_id)
						
				);

//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string);

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_POST,count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);

//execute post
$result = curl_exec($ch);

var_dump($url);

//close connection
curl_close($ch);

?>
