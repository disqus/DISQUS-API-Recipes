<!DOCTYPE html>
<?php
include 'config.php';

// Catch errors

if (isset($_GET['error'])) {

    die($_GET['error']);
    
}

// **************************************************************************************
//
// If 'verify' is set in the URL, render the TOS. 
// The querystring parameter was put in the API application's audience sync callback URL
//
// **************************************************************************************

if (isset($_GET['verify'])) {

    $code = $_GET['code']; // A temporary token which you will exchange for a finalized access token.

    $username = $_GET['username']; // The username of the user who you're requesting authorization of.

    $userid = $_GET['user_id']; // A unique identifier for this user which is guaranteed not to change.

    $audiencesync_uri = $_GET['audiencesync_uri']; // The URL to which you will redirect the user once you've confirmed their authorization.

    $next_url = constant("BaseSitePath") . "as-callback.php?&user_id=" . $userid . "&success=1&code=" . $code . "&audiencesync_uri=" . urlencode($audiencesync_uri);

    echo   '<html>
            <body>
            <h1>Your website name</h1>
            <h4>I accept that:</h4>
            <ul>
            <li>Comments will be moderated by the website</li>
            <li>I can\'t do bad stuff</li>
            <li>I am over the age of 13</li>
            </ul>
            <p><a href="' . $next_url . '">Accept</a></p>
            </body>
            </html>';
}


// **************************************************************************************
//
// Get the access token
//
// **************************************************************************************

else
{
    // Get the code for request access
    $code = $_GET['code'];
    $audiencesync_uri = urldecode($_GET['audiencesync_uri']);

    // Request the access token
    extract($_POST);

    $url = 'https://disqus.com/api/oauth/2.0/access_token/';
    $fields = array(
	    'grant_type'=>urlencode("audiencesync"),
	    'client_id'=>urlencode(constant("DisqusApiPublic")),
	    'client_secret'=>urlencode(constant("DisqusApiSecret")),
	    'redirect_uri'=>urlencode(constant("BaseSitePath") . 'as-callback.php?verify=1'),
	    'code'=>urlencode($code)
    );

    //url-ify the data for the POST
    foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
    rtrim($fields_string, "&");

    //open connection
    $ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POST,count($fields));
    curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);

    //execute post
    $result = curl_exec($ch);

    //close connection
    curl_close($ch);

    $auth_results = json_decode($result);

    if (isset($auth_results->error)) {

        die($auth_results->error);
    
    }

    // Extract access token and render
    $access_token = $auth_results->access_token;
    $user_id = $auth_results->user_id;
    $success = $_GET['success'];

    $completion_url = $audiencesync_uri . "?client_id=" . constant("DisqusApiPublic") . "&user_id=" . $user_id . "&access_token=" . $access_token . "&success=" . $success;

    echo   '<script type="text/javascript">
            window.location = "' . $completion_url . '";
            </script>';
}
?>