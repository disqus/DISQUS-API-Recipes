/*
This script will calculate the Disqus SSO payload package
Please see the Integrating SSO guide to find out how to configure your account first: 
http://help.disqus.com/customer/portal/articles/236206

This example uses the Jackson JSON processor: http://jackson.codehaus.org/Home
*/
String DISQUS_SECRET_KEY = “<YOUR_SECRET_KEY>”; // Your Disqus secret key from http://disqus.com/api/applications/

// User data, replace values with authenticated user data
HashMap<String,String> message = new HashMap<String,String>();
message.put("id","uniqueId_123456789");
message.put("username","Charlie Chaplin");
message.put("email","charlie.chaplin@example.com");
//message.put("avatar","http://example.com/path-to-avatar.jpg"); // User's avatar URL (optional)
//message.put("url","http://example.com/"); // User's website or profile URL (optional)
 
// Encode user data
ObjectMapper mapper = new ObjectMapper();

String jsonMessage = mapper.writeValueAsString(message);

String base64EncodedStr = new String(Base64.encodeBase64(jsonMessage.getBytes()));

// Get the timestamp
long timestamp = System.currentTimeMillis()/1000;
 
// Assemble the HMAC-SHA1 signature
String signature = calculateRFC2104HMAC(base64EncodedStr + " " + timestamp, DISQUS_SECRET_KEY);

// Output string to use in remote_auth_s3 variable
System.out.println(base64EncodedStr + " " + signature + " " + timestamp);

private static String toHexString(byte[] bytes) 
{
	Formatter formatter = new Formatter();
	for (byte b : bytes) 
	{
		formatter.format("%02x", b);
	}

	return formatter.toString();
}

public static String calculateRFC2104HMAC(String data, String key)
throws SignatureException, NoSuchAlgorithmException, InvalidKeyException
{
	private final String HMAC_SHA1_ALGORITHM = "HmacSHA1";
	SecretKeySpec signingKey = new SecretKeySpec(key.getBytes(), HMAC_SHA1_ALGORITHM);
	Mac mac = Mac.getInstance(HMAC_SHA1_ALGORITHM);
	mac.init(signingKey);
	return toHexString(mac.doFinal(data.getBytes()));
}