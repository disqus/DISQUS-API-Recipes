# Sending custom email notifications

This example shows you how to send custom email notifications whenever a new comment is posted on a thread. The most common use will be to send the post author an email notification when a new comment is posted in their article.

This example is written in PHP, but can be adapted to any language.

## Before you begin

* Make sure you've registered a [Disqus API application](http://disqus.com/api/applications/)
* Basic API accounts are limited to 1000 requests an hour, which should be fine unless you get more than 1000 comments an hour.
* This example requires modification so you can find the proper author email addresses given a post ID, URL, or any other data you choose.

## Files

### embed.html

A sample of the Disqus comments embed with the proper onNewComment callback. When a new comment is posted, it'll POST data to sendnotification.php which sends the actual email notification.

### php/sendnotification.php

This script receives data from the embed callback, makes a Disqus API request, and sends a formatted email to the author.

## Support

This recipe is a starting point, and actual usage must be adapted to your own system and needs. No support is offered for this recipe.