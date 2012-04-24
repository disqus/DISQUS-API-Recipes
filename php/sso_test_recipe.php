<?php
define('DISQUS_SECRET_KEY', '<secret key>');
define('DISQUS_PUBLIC_KEY', '<public key>');

$data = array(
    "id" => "999",
    "username" => "Disqus Test",
    "email" => "disqus-test@disqus.com",
    "avatar" => "http://dl.dropbox.com/u/31679327/Screenshots/30v.png",
    "url" => "http://disqus.com"
);

$message = base64_encode(json_encode($data));
$timestamp = time();
$hmac = hash_hmac('sha1', "$message $timestamp", DISQUS_SECRET_KEY);
?>

<!DOCTYPE HTML>
<html>	
<head>
    <title>Test Site</title>
</head>
<body>

    <div id="disqus_thread"></div>

    <script type="text/javascript">
        var disqus_config = function() {
            this.page.remote_auth_s3 = "<?php echo "$message $hmac $timestamp"; ?>";
            this.page.api_key = "<?php echo DISQUS_PUBLIC_KEY; ?>";
        }
        
        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
        var disqus_shortname = 'shmeriously'; // required: replace example with your forum shortname
        var disqus_developer = 1;

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function() {
            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
    </script>
    <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
    <a href="http://disqus.com" class="dsq-brlink">blog comments powered by <span class="logo-disqus">Disqus</span></a>

</body>
</html>