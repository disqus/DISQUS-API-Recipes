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

// handles login from client and calls SSO code for user in our "database"
app.get("/login", function (request, response) {
    console.log("request is", request.query);
    let username = request.query.username
    let password = request.query.password
    let email = request.query.email
    if (email === 'dmatt+9898@disqus.com') {
        let payload = disqusSignon(users[0])
        response.send(payload);
   } else if (username === 'uniqueperson122327') {
        let payload = disqusSignon(users[4])
        response.send(payload);
   } else {
        let payload = disqusSignon(users[16])
        response.send(payload);
   }
});

// Simple in-memory "user database" for the purpose of this demo
var users = [
  // test user 0 
  {
    id:'4226149898',
    username:'98989898',
    email:'dmatt+9898@disqus.com',
    avatar:'https://i.imgur.com/AYgoB04.jpg',
    url:'https://advrider.com/index.php?members/disqustest.422614/',
    profile_url:'https://example.com/providedProfileUrl-4226149898'
  },
  // test user 1 
  {
    id:'123456869',
    username: 'uniqueperson7',
    email: 'person7@example.com',
    avatar:'https://i.imgur.com/AYgoB04.jpg',
    url: 'example7.com',
    profile_url:'https://example.com/providedProfileUrl-123456869'
  },
  // test user 2 
  {
    id:'1234532329',
    username: 'uniqueperson127',
    email: 'person327@example.com',
    avatar:'https://avataaars.io/?avatarStyle=Circle',
    url: 'example7.com',
    profile_url:'https://example.com/providedProfileUrl-1234532329'
  },
  // test user 3 
  {
    id:'1234532329',
    username: 'uniqueperson126',
    email: 'person327@example.com',
    avatar:'https://i.imgur.com/AYgoB04.jpg',
    url: 'example7.com',
    profile_url:'https://example.com/providedProfileUrl-1234532329'
  },
  // test user 4
  {
    id:'1234523232329',
    username: 'uniqueperson122327',
    email: 'dmatt@disqus.com',
    avatar:'https://i.imgur.com/pTL5Clh.jpg',
    url: 'example7.com',
    profile_url:'https://example.com/providedProfileUrl-1234523232329'
  },
  // test user 5
  {
    id:'123452323232901',
    username: 'uniqueperson12232701',
    email: 'dmatt01@disqus.com',
    avatar:'https://i.imgur.com/pTL5Clh.jpg',
    url: 'example7.com',
    profile_url:'https://example.com/providedProfileUrl-123452323232901'
  },
  // test user 6
  {
    id:'123456789432148214325523',
    username: 'uniqueperson123456789432148214325523',
    email: 'taylan+123456789432148214325523@disqus.com',
    avatar:'https://i.imgur.com/pTL5Clh.jpg',
    url: 'example7.com',
    profile_url:'https://example.com/providedProfileUrl-123456789432148214325523'
  },
  // test user 7
  {
    id:'123456789012345678',
    username: 'uniqueperson123456789012345678901',
    email: 'taylan+123456789012345678901@disqus.com',
    avatar:'https://i.imgur.com/pTL5Clh.jpg',
    url: 'example7.com',
    profile_url:'https://example.com/now-this-is-pod-racing-123456789012345678901'
  },
  // test user 8
  {
    id:'1234567890123456781',
    username: 'uniqueperson1234567890123456781',
    email: 'taylan+1234567890123456781@disqus.com',
    avatar:'https://upload.wikimedia.org/wikipedia/commons/e/ec/%28U.S.%29_National_Maritime_Intelligence_Center_logo.png',
    url: 'example7.com',
    profile_url:'https://example.com/now-this-is-pod-racing-2'
  },
  // test user 9
  {
    id:'1234567890123456782',
    username: 'uniqueperson9',
    email: 'taylan+1234567890123456782@disqus.com',
    avatar:'https://upload.wikimedia.org/wikipedia/commons/8/83/%2C%2BGIACOMO%2BD%27ANGELIS%2BBIPLANE%2C%2BMADRAS%2B1910%2Bxxx.jpg',
    url: 'example7.com',
    profile_url:'https://example.com/now-this-is-pod-racing-9'
  },
  // test user 10
  {
    id:'1234567890123456783',
    username: 'uniqueperson10',
    email: 'taylan+1234567890123456783@disqus.com',
    avatar:'https://upload.wikimedia.org/wikipedia/commons/6/64/---File---M.R.Jaykar2.PNG',
    url: 'example7.com',
    profile_url:'https://example.com/now-this-is-pod-racing-10'
  },
  // test user 11
  {
    id:'1234567890123456784',
    username: 'uniqueperson11',
    email: 'taylan+1234567890123456784@disqus.com',
    avatar:'https://upload.wikimedia.org/wikipedia/commons/5/58/---File---N.D.Velkar.png',
    url: 'example7.com',
    profile_url:'https://example.com/now-this-is-pod-racing-11'
  },
  // test user 12
  {
    id:'1234567890123456785',
    username: 'uniqueperson12',
    email: 'taylan+1234567890123456785@disqus.com',
    avatar:'https://upload.wikimedia.org/wikipedia/commons/5/58/---File---N.D.Velkar.png',
    url: 'example7.com',
    profile_url:'https://example.com/now-this-is-pod-racing-12'
  },
  // test user 13
  {
    id:'1234567890123456786',
    username: 'uniqueperson13',
    email: 'taylan+1234567890123456786@disqus.com',
    avatar:'https://upload.wikimedia.org/wikipedia/commons/4/4f/1-PA250215%2C_The_new_temple.JPG',
    url: 'example7.com',
    profile_url:'https://example.com/now-this-is-pod-racing-13'
  },
  // test user 14
  {
    id:'123456789014',
    username: 'uniqueperson-t14',
    email: 'taylan+123456789014@disqus.com',
    avatar:'https://upload.wikimedia.org/wikipedia/commons/0/05/1-PA250222_At_Sarakki%2C.JPG',
    url: 'example7.com',
    profile_url:'https://example.com/now-this-is-pod-racing-14'
  },
  // test user 15
  {
    id:'123456789015',
    username: 'uniqueperson-t15',
    email: 'taylan+123456789015@disqus.com',
    avatar:'https://upload.wikimedia.org/wikipedia/commons/5/57/100_Rupees_Eagle_Note_Back.jpg',
    url: 'example7.com',
    profile_url:'https://example.com/now-this-is-pod-racing-15'
  },
  // test user 16
  {
    id:'123456789016',
    username: 'uniqueperson-t16',
    email: 'taylan+123456789016@disqus.com',
    avatar:'https://upload.wikimedia.org/wikipedia/commons/7/72/1000BlueBull.jpg',
    url: 'example7.com',
    profile_url:'https://example.com/now-this-is-pod-racing-16'
  },
];

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


