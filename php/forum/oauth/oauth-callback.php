<?php

$PUBLIC_KEY = "g1rAhrQKFIZgBjothiLnPRPNkAN65QME3lY16cktCRU28p7df7mfHvyIl4JuXvBx";
$SECRET_KEY = "kJluhqMiymsniJy2vGFWYmxRUwGQ29Lsrr0taCTYAInw96uqzkBpwqrAv3BAeV5t";

// Get the code for request access
$CODE = $_GET['code'];

// Request the access token
extract($_POST);

$authorize = "authorization_code";
$redirect = "http://steelheadapps.com/forum/";

$url = 'https://disqus.com/api/oauth/2.0/access_token/?';
$fields = array(
	'grant_type'=>urlencode($authorize),
	'client_id'=>urlencode($PUBLIC_KEY),
	'client_secret'=>urlencode($SECRET_KEY),
	'redirect_uri'=>urlencode($redirect),
	'code'=>urlencode($CODE)
);

//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, "&");

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_POST,count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);

$auth_results = json_decode($data);

// Extract access token
// You can get the reauth token as well, but having the user log in again isn't a big deal
$access_token = $auth_results->access_token;

// Set the cookie to expire in 29 days
setcookie("dsq_access_token", $access_token, time()+3600*24*29);

?>
