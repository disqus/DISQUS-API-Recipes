<!DOCTYPE html>
<html>
<head></head>
<body>

<?php
	// cache filename you saved the API results as
	$filename="dsq-listpopular-cache.txt";
	
	// gets the contents of your cache file
	$output = file_get_contents($filename);
	
	// outputs the HTML from your cache file
	echo $output;
?>

</body>
</html>