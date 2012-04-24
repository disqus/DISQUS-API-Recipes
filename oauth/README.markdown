# DISQUS OAuth Test

This recipe will show you a method of setting up a simple OAuth session and recieving the Access Token necessary to make API requests requiring authentication.

How it Works: 
1. oauth-test.php provides a link which will pass the string necessary to pull up an authentication page within DISQUS
2. After authenticating you'll be sent to your redirect URL (example.com/oauth-callback.php)
3. You'll then be provided with the access token necessary to use API methods that require authentication

Additional information on DISQUS authentication can be found here: http://disqus.com/api/docs/auth/