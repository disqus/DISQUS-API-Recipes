README 
====


## Requirements
1. Host a page containing Disqus on an externally-accessible server. (Refer to `views/index.html` for example)
2. A server that handles SSO data logic on server side. (In the demo, we use the same server to handle SSO data logic and host the page containing Disqus)
3. Ensure log in via mobile app is secure. The demo has the server pass user unique payload to the mobile app, which is then passed to Disqus via URL. The payload is what authenticates the user.
4. Have a Pro Disqus site created with SSO feature enabled.
5. Configure your remote domain at https://disqus.com/api/sso/
6. Configure your Disqus application at https://disqus.com/api/applications/ and enter your domain, in this case disqus-sso-demo.glitch.me and select your SSO Domain from the dropdown that you created in step 6.

In the demo, we mock the serverside logic and the page at https://sleepy-shelf-33354.herokuapp.com/. Feel free to use for your testing purposes. Local development works as well. 


## Overview

The mobile-sso demo includes two parts:
1. the server-side code (js-server-demo)
    a) `https://sleepy-shelf-33354.herokuapp.com/login` is the URL we hit for the SSO payload generation. You will need to integrate  authentication here. Refer to `index.js` for more details
    b) `views/index.html` will check for the URL parameters `identifier`, `title`, and `payload`. Then it will load it into Disqus config variables. 
2. the client-side/native code in Swift
    a) I've included commented out URLs for to hit local host and the server for your convenience.
    b) within `login()`, `parameters` is meant to mock the authentication and data retrieval. You will need to implement this.
    c) Note: The demo uses Alamofire and SwiftyJSON. 
    
### Getting Started
1. Set up the following server side code to host Disqus. 
    a) Refer to `index.js`
    b) Refer to `views/index.html`. Be sure to edit the other config variables, the public API key, and `s.src` to be `https://your_shortname_goes_here.disqus.com/embed.js`
2. Authenticate user on mobile app
3. Make a request to server for payload. This would be the equivalent of hitting https://sleepy-shelf-33354.herokuapp.com/login.
    a) Refer to the login function `ViewController.swift` for more details
    b) Server side it will hit `app.get("/login" ...`
4. Make a request to URL pointing to page containing Disqus (requirement 1) with three query parameters. Example URL: ```https://sleepy-shelf-33354.herokuapp.com/?title=Hovsep&identifier=the_hovsep_identifier&payload=payload_goes_here```
    a) SSO payload 
    b) thread title ([Configuration Variable](https://help.disqus.com/en/articles/1717084-javascript-configuration-variables))
    c) thread identifier ([Configuration Variable](https://help.disqus.com/en/articles/1717084-javascript-configuration-variables))
5. Load response in webview.

### Technical Flow
1. User logs into mobile app and needs access to Disqus at a specific thread via SSO.
2. Mobile app requests a SSO payload from server. ([Generation code](https://github.com/disqus/DISQUS-API-Recipes/tree/master/sso/javascript))  
3. Server generates and responds with SSO payload. 
4. Mobile App makes a request to URL pointing to page containing Disqus.
5. Mobile loads response. 
![](https://i.imgur.com/bXS5Q3Y.png)


