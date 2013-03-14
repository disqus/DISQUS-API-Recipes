<?php

//This is a all-in-one example of API authentication and making API calls using OAuth
//More information on using OAuth with Disqus can be found here: http://disqus.com/api/docs/auth/

error_reporting(E_ALL ^ E_NOTICE) ;

$PUBLIC_KEY = "<API_KEY>";
$SECRET_KEY = "<API_SECRET>";
$redirect = "http://<PATH_TO_THIS_FILE>/all-in-one.php";

$endpoint = 'https://disqus.com/api/oauth/2.0/authorize?';
$client_id = $PUBLIC_KEY;
$scope = 'read,write';
$response_type = 'code';
$redirect_uri = 'http://localhost/~helpdesk3/all-in-one.php';

$auth_url = $endpoint.'&client_id='.$client_id.'&scope='.$scope.'&response_type='.$response_type.'&redirect_uri='.$redirect_uri;

// Trigger the initial authentication call to receive a code
echo "<h3>Trigger authentication -> <a href='".$auth_url."'>OAuth</a></h3>";


// Get the code to request access
$CODE = $_GET['code'];

if($CODE){

// Build the URL and request the authentication token
extract($_POST);

$authorize = "authorization_code";

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
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

//execute post
$data = curl_exec($ch);

//close connection
curl_close($ch);

//turn the string into a object
$auth_results = json_decode($data);


echo "<p><h3>The authentication information returned:</h3>";
var_dump($auth_results);
echo "</p>";

$access_token = $auth_results->access_token;

echo "<p><h3>The access token you'll use in API calls:</h3>";
echo $access_token;
echo "</p>";



	function getData($url, $SECRET_KEY, $access_token){

  			//Setting OAuth parameters
        $oauth_params = (object) array(
          'access_token' => $access_token, 
          'api_secret' => $SECRET_KEY
          );

          $param_string = '';

          
          //Build the endpiont from the fields selected and put add it to the string.
       
          //foreach($params as $key=>$value) { $param_string .= $key.'='.$value.'&'; }
          foreach($oauth_params as $key=>$value) { $param_string .= $key.'='.$value.'&'; }
          $param_string = rtrim($param_string, "&");

          // setup curl to make a call to the endpoint
          $url .= $param_string;

          //echo $url;
          $session = curl_init($url);

          // indicates that we want the response back rather than just returning a "TRUE" string
          curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($session,CURLOPT_FOLLOWLOCATION,true);

          // execute GET and get the session backs
          $results = curl_exec($session);
          // close connection
          curl_close($session);
          // show the response in the browser
          return  json_decode($results);
    }


    //Setting the correct endpoint
    $cases_endpoint = 'https://disqus.com/api/3.0/users/details.json?';

    //Calling the function to getData
    $user_details = getData($cases_endpoint, $SECRET_KEY, $access_token);
    echo "<p><h3>Getting user details:</h3>";
    var_dump($user_details);
    echo "</p>";
    
    //Setting the correct endpoint
    $forums_endpoint = 'https://disqus.com/api/3.0/users/listForums.json?';

    //Calling the function to getData
    $forum_details = getData($forums_endpoint, $SECRET_KEY, $access_token);
    echo "<p><h3>Getting forum details:</h3>";
    var_dump($forum_details);
    echo "</p>";
    }

?>




