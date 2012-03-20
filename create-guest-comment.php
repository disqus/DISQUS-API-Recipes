<?php
	ini_set('display_errors', 'on');

	$thread="<thread ID goes here>"; // e.g., 455718495 — you'll need to also create a $forum and pass that if you know only the thread's URL or identifier rather than the ID
	$api="<key goes here>"; // Generate one at http://disqus.com/api/applications/ -- Secret key is required for anonymous comment posting
	$message="Hello world."; // this is the content of the comment, i.e., what you'd normally type in the postbox
	$author_email="sample@disqus.com"; // optional, including this will still make the comment a guest comment, but it will now be claimable 
	$author_name="Bruce Wayne"; // optional, can be any display name you like
	$fields_string=""; // DO NOT EDIT

	// set POST variables
	$url = 'http://disqus.com/api/3.0/posts/create.json'; // full documentation at http://disqus.com/api/docs/posts/create/
	$fields = array(
		'api_secret'=>urlencode($api), // change to api_key when using a public key
		'thread'=>urlencode($thread),
		'message'=>urlencode($message),
		'author_email'=>urlencode($author_email),
		'author_name'=>urlencode($author_name),
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

	// debug info, as you like it
	// echo "<p>Request IP: ".$_SERVER['REMOTE_ADDR']."</p>";
	// echo "<p>Server IP: ".$_SERVER['SERVER_ADDR']."</p>";
	// echo "<p>Request URI: ".$_SERVER['REQUEST_URI']."</p>";
	// echo "<p>Path Info: ".$_SERVER['PATH_INFO']."</p>";
	// echo "<p>Original Path Info: ".$_SERVER['ORIG_PATH_INFO']."</p>";
	// echo "<p>API key: ".$api."</p>";

?>