<?php
/*******

This script will receive a post ID, look up the author, and send them an email notifying of a new comment.

The actual technique used would vary depending on your CMS or integration, and it's up to you to determine
what's a reliable way to look up the thread author.

********/

// Replace with your own Disqus API secret key from http://disqus.com/api/applications/
// This is used to look up the most recent comments for a thread
$disqusApiSecret = 'YOUR_API_SECRET_KEY'; 

// The new Disqus comment ID, which we'll look up to send with the notification
$commentId = $_POST['comment'];

// The article's post ID. Use anything that gives you a reliable signal to look up an author.
$postId = $_POST['post'];

// The email address of the author we're notifying
// TODO look up the author of the post
$postAuthor = 'author@example.com'; 


// Use the posts/details endpoint to get comment content: http://disqus.com/api/docs/posts/details/

$session = curl_init('http://disqus.com/api/3.0/posts/details.json?api_secret=' . $disqusApiSecret .'&post=' . $commentId . '&related=thread');

curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($session);

curl_close($session);

// decode the json data to make it easier to parse the php
$results = json_decode($result);

// Handle errors
if ($results === NULL) die('Error');


/**********************
// Get the data we need
**********************/

// Author and thread objects
$author = $results->response->author;

$thread = $results->response->thread;

$comment = $results->response->raw_message;


// Build the email message

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'From:someone-auto@example.com' . '\r\n'; // TODO replace with your own notifier email

$subject = 'New comment on ' . $thread->title;

$message = '<h3>A comment was posted on <a href="' . $thread->link . '#comment-' . $commentId . '">' . $thread->title . '</a></h3><p>' . $author->name . ' wrote:</p><blockquote>' . $comment .'</blockquote><p><a href="http://' . $results->response->forum . '.disqus.com/admin/moderate/#/approved/search/id:' . $commentId . '">Moderate comment</a></p>';



// Send the email		
mail($postAuthor,$subject,$message,$headers);

echo 'Sent email to ' . $postAuthor;
?>