<?php
ini_set('display_errors', 1);  
 
$key='YOUR_API_SECRET_KEY'; // TODO replace with your Disqus secret key from http://disqus.com/api/applications/
$forum='YOUR_SHORTNAME'; // Disqus shortname
$limit='5'; // The number of comments you want to show
$thread='DISQUS_IDENTIFIER'; // Same as your disqus_identifier
$endpoint = 'https://disqus.com/api/3.0/threads/listPosts.json?api_secret='.$key.'&forum='.$forum.'&thread='.$thread.'&limit='.$limit;
//$endpoint = 'http://disqus.com/';
 
// Get the results
$session = curl_init($endpoint);
$ch = curl_init();
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
$data = curl_exec($session);
curl_close($session);
 
// decode the json data to make it easier to parse with php
$results = json_decode($data);
 
// parse the desired JSON data into HTML for use on your site
$comments = $results->response;
 
foreach ($comments as $comment) 
{
  $finalResults .= 
    '<div class="dsq-widget-comment">
    <img class="dsq-user-avatar" src="'.$comment->author->avatar->cache.'" />
    <p class="dsq-comment-author"><a href="'.$comment->author->profileUrl.'">'.$comment->author->name.'</a></p>
    <p class="dsq-comment-content">'.$comment->message.'</p>
    </div>';
}
 
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>IDG Test Widget</title>
        <style>
            body { font-family: Arial; }
            
            a { 
                color: #0094ff;
                text-decoration: none; 
            }
            
            .dsq-comment-author { font-size: 18px; }
            
            .dsq-user-avatar {
                width: 32px;
                float: left;
                margin-right: 12px;
            }
        </style>
    </head>
    <body>
        <h1>Latest Comments (PHP)</h1>
        <br /><a href="">Code</a>
        <?php echo $finalResults; ?>
 
        <h1>Latest Comments (Javascript)</h1>
        <div id="comment_widget_js"></div>
 
        <script src="/assets/js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript">
        $.ajax({
		type:'GET',
		url: 'https://disqus.com/api/3.0/threads/listPosts.jsonp?api_key=YOUR_API_KEY&forum=YOUR_SHORTNAME&thread=ident:DISQUS_IDENTIFIER&limit=5', // TODO replace with your Public key
		cache: false,
            	dataType: "jsonp",
		success: function(commentsresult){
                	for (var i in commentsresult.response) {
                    		$('#comment_widget_js').append('<div class="dsq-widget-comment"><img class="dsq-user-avatar" src="' + commentsresult.response[i].author.avatar.cache + '" /><p class="dsq-comment-author"><a href="' + commentsresult.response[i].author.profileUrl + '">' + commentsresult.response[i].author.name + '</a></p><p class="dsq-comment-content">' + commentsresult.response[i].message + '</p></div>');
                	}
		}
	});
        </script>
    </body>
</html>
