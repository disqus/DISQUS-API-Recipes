<!DOCTYPE HTML>
<html>
<head></head>
<body>
<p>Add users to whitelist</p>
<p>
Response:
</p>
<?php 

//  This script will take a CSV file named 'whitelist.csv' and whitelist every user for a given forum. 
//	For this to work, you upload a CSV file containing email addresses (1 per line) to be whitelisted. 
//  You should change "YOUR_FORUM_SHORTNAME" to the appropriate forum as well in the code below.

	$row = 1;
	$file = fopen("whitelist.csv", "r");
	
	while (($data = fgetcsv($file, 8000, ",")) !== FALSE) {
    	$num = count($data);
    	$row++;
    	for ($c=0; $c < $num; $c++) {

	$curl_handle = curl_init();
	curl_setopt($curl_handle, CURLOPT_URL, "http://disqus.com/api/3.0/whitelists/add.json");
	curl_setopt($curl_handle, CURLOPT_POST, true);
	curl_setopt($curl_handle, CURLOPT_POSTFIELDS, "email=$data[$c]&api_secret=YOUR_API_SECRET_KEY&forum=YOUR_FORUM_SHORTNAME");
	curl_exec ($curl_handle);
	curl_close($curl_handle);
	
 		
 		
 	$response = file_get_contents($curl_handle);	
 	$emails = json_decode($response);

			foreach ( $emails->response as $email )
				{
				echo "{$email->value} was added to the whitelist><br />";
				}

    	}
    		}
?>

</body>
</html>