// server.js
// where your node app starts

// init project
if (process.env.NODE_ENV !== 'production') require('dotenv').config()

var express = require('express');
var app = express();
var CryptoJS = require('crypto-js');
var DISQUS_SECRET = process.env.DISQUS_SECRET;
var DISQUS_PUBLIC = process.env.DISQUS_PUBLIC;

// http://expressjs.com/en/starter/static-files.html
app.use(express.static('public'));

// http://expressjs.com/en/starter/basic-routing.html
app.get("/", function (request, response) {
  response.sendFile(__dirname + '/views/index.html');
});

// listen for requests :)
console.log("port is ", process.env.PORT);
app.listen(process.env.PORT, function () {
  console.log('Your app is listening on port ' + process.env.PORT);
});

// SSO payload generation code from https://github.com/disqus/DISQUS-API-Recipes/tree/master/sso/javascript

function disqusSignon(user) {
    var disqusData = {
      id: user.id,
      username: user.username,
      email: user.email,
      // optional 
      avatar: user.avatar,
      url: user.url,
      profile_url: user.profile_url
    };


    // Pass an empty JSON object to generate payload that logs out user with client-side DISQUS.reset()
    var disqusNullData = ({});

    var disqusStr = JSON.stringify(disqusData);
    var timestamp = Math.round(+new Date() / 1000);

    /*
     * Note that `Buffer` is part of node.js
     * For pure Javascript or client-side methods of
     * converting to base64, refer to this link:
     * http://stackoverflow.com/questions/246801/how-can-you-encode-a-string-to-base64-in-javascript
     */
    var message = new Buffer(disqusStr).toString('base64');

    /* 
     * CryptoJS is required for hashing (included in dir)
     * https://code.google.com/p/crypto-js/
     */
    var result = CryptoJS.HmacSHA1(message + " " + timestamp, DISQUS_SECRET);
    var hexsig = CryptoJS.enc.Hex.stringify(result);

    return {
      pubKey: DISQUS_PUBLIC,
      auth: message + " " + hexsig + " " + timestamp,
    };
}


