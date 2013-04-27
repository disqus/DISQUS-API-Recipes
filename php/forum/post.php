<?php
ini_set('display_errors', 'on');

/* 
Hard-coded variables
*/
$key = "<public api key>"; // Requires a registered DISQUS API application. Create one (free) at http://disqus.com/api/applications/
$forum = "<DISQUS forum shortname>";

/* 
Fluid variables
*/
$title = "";
$message = "";
$timestamp = "";
$slug = "";
$authorName = "";
$authorAvatar = "";
$authorUsername = "";
$threadId = $_GET['post'];

// Check if there are POST variables
if (isset($_POST['title'])
{
	$title = $_POST['title'];
	$message = $_POST['message'];
	$timestamp = $_POST['timestamp'];
	$slug = $_POST['slug'];
	$authorName = $_POST['name'];
	$authorAvatar = $_POST['avatar'];
	$authorUsername = $_POST['username'];
}
else // Direct link, load from the API
{
	$endpoint = 'http://disqus.com/api/3.0/threads/details.json?api_key='.urlencode($key).'&forum='.$forum.'&thread='.urlencode($threadId);

	// setup curl to make a call to the endpoint
	$session = curl_init($endpoint);
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($session);
	curl_close($session);

	// show the response in the browser
	var_dump($result);
	
	// TODO replace variables with values from API
}

?>

<div class="container">

	<h1 class="postTitle"><?php echo $title ?></h1>

	<div class="postMeta">
		<img src="<?php echo $authorAvatar ?>"/>&nbsp;<?php echo $authorName ?>&nbsp;-&nbsp;<?php echo $timestamp ?>
	</div>

	<div class="postBody"><?php echo $message ?></div>

	<div id="disqus_thread"></div>
	<script type="text/javascript">
		var disqus_shortname = '<?php echo $forum ?>'; // Required - Replace example with your forum shortname
		var disqus_title = '<?php echo $title ?>';
		var disqus_identifier = '<?php echo $slug ?>';
		//var disqus_url = '<?php echo $forum ?>';
		
		(function() {
			var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
			dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
			(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
		})();
	</script>
	<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
	<a href="http://disqus.com" class="dsq-brlink">powered by <span class="logo-disqus">Disqus</span></a>
</div>