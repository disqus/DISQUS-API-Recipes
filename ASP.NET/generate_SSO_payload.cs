// Description:
// This is an example of how to generate the remote_auth_s3 payload in C#, used in conjunction with Disqus single sign-on (SSO)
// For documentation on how SSO works with Disqus, see this guide: http://docs.disqus.com/developers/sso/
// 
// Prerequisites:
// You need the the Jayrock JSON library, which can be used in all versions of .NET. 
// Download it here: http://code.google.com/p/jayrock/
//
// Usage:
// The output is the hmacString variable, which is then put in place inline with the Disqus Javascript (remote_auth_s3).
 
protected void Page_Load(object sender, EventArgs e)
{
        StringBuilder sb = new StringBuilder();
 
        NameValueCollection nvc = new NameValueCollection();
        /// The number '1' is used as an example, this would be the user's unique ID
        nvc.Add("1", userId);
        /// exampleuser would be the commenter's display name
        nvc.Add("exampleuser", userName);
        /// user@example.com would be replaced with the commenter's email address
        nvc.Add("user@example.com", email);
           
 
        Jayrock.Json.Conversion.JsonConvert.Export(nvc, sb);
 
        string jsonString = sb.ToString();
 
        byte[] toEncodeAsBytes = System.Text.ASCIIEncoding.ASCII.GetBytes(jsonString);
 
        string Message = System.Convert.ToBase64String(toEncodeAsBytes);
 
        TimeSpan ts = (DateTime.UtcNow - new DateTime(1970, 1, 1, 0, 0, 0));
        string Timestamp = Convert.ToInt32(ts.TotalSeconds).ToString();
 
        string messageAndTimestamp = Message + ' ' + Timestamp;
        byte[] messageAndTimestampBytes = Encoding.ASCII.GetBytes(messageAndTimestamp);
 
        byte[] keyBytes = Encoding.ASCII.GetBytes(DISQUS_SECRET_KEY);
        HMACSHA1 hmac = new HMACSHA1(keyBytes);
 
        byte[] hashmessage = hmac.ComputeHash(messageAndTimestampBytes);
 
        hmacString = Message + " " + ByteToString(hashmessage) + " " + Timestamp;  
}       
 
public static string ByteToString(byte[] buff)
{
        string sbinary = "";
 
        for (int i = 0; i < buff.Length; i++)
        {
            sbinary += buff[i].ToString("X2"); // hex format
        }
        return (sbinary);
}