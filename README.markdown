# DISQUS API Recipes

A cookbook of common recipes to help expedite your development process when using the [DISQUS API](http://disqus.com/api).

## Table of Contents

### Beginner 

* Get a thread's details: get-thread-details.php
* List a forum's 100 most active users and their comment counts: list-100-most-active-users.php
* Get an SSO account's username: get-sso-username.php

### Intermediate

* Create a guest comment: create-guest-comment.php
* List all threads created between a given date and now: list-all-threads-between-date-and-now.php
* Add users to the whitelist via CSV file: add_to_whitelist.php
* Close a thread using a single access token: single_access_token.php

### Advanced
* Test SSO with a single user: sso_test_recipe.php
* Generate an SSO remote_auth_s3 payload in C# .NET: /ASP.NET/generate_SSO_payload.cs
* (1/2) Get a forum's most popular threads and write to a cache file: get_and_write_popular_threads_to_cache.php
* (2/2) Get popular threads from cache file and display them: get_popular_threads_from_cache.php

## Requirements

For PHP scripts:

* PHP, preferably the latest version
* (Optional) [DISQUS API bindings for PHP](https://github.com/disqus/disqus-php) — none of these .php files require the bindings. Only use the bindings if you know you need them and already know how to use them.

## Usage

Run any of these files as-is. Enjoy!

## Requests

[Email us any requests](http://disqus.com/support).

Note: The integrity and utility of these recipes is very important to us. This means keeping them tidy and aimed at a wide audience. If you request a very specific recipe we may make it less specific so it's more accessible.

## Support

These recipes are meant as a starting point. Most recipes have only been tested to work with the latest version of Chrome stable at the time of creation. YMMV.