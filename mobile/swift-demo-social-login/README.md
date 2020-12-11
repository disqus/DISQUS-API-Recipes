# Overview
The social login demo works in two parts.

1. The server-side code that mocks the website hosting Disqus. The relevant code is in the `node_server` folder. It is currently set up to run locally.
2. The client-side/native code in Swift. Note: The demo uses Alamofire and SwiftyJSON Pods. Be sure to install accordingly.

# Getting Started

## Setting up local server
1. Set up the following server side code to host Disqus. a) Refer to index.js b) Refer to views/index.html. Be sure to edit the other config variables, the public API key, and s.src to be `https://your_shortname_goes_here.disqus.com/embed.js`
2. Build the dependencies at `DISQUS-API-Recipes/mobile/swift-demo-social-login/node_server`
2. Navigate to `DISQUS-API-Recipes/mobile/swift-demo-social-login/node_server` and spin up the local server:
```
$ npm install
$ node index.js

port is  5000
Your app is listening on port 5000
```
3. Verify that the local server is running by going to http://localhost:5000/. 

## Setting up client environment
1. Build the social_login_client.xcworkspace
2. Run the simulator
3. At this point you should be able to log in via social auth.
