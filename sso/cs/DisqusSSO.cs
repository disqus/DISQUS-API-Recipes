using System;
using System.Web.Script.Serialization;
using System.Security.Cryptography;
using System.Text;

namespace Disqus.Examples
{
    public static class SSO
    {
        /// <summary>
        /// This class generates the payload we need to authenticate users remotely through Disqus
        /// This requires the Disqus SSO package and to have set up your application/remote domain properly
        /// See here for more: http://help.disqus.com/customer/portal/articles/236206-integrating-single-sign-on
        /// 
        /// Usage:
        /// After inputting user data, a final payload will be generated which you use for the javascript variable 'remote_auth_s3'
        /// 
        /// Markup:
        /// ------
        /// var disqus_config = function () {
        ///         this.page.remote_auth_s3 = '<%= Payload %>';
        ///         this.page.api_key = 'DISQUS_PUBLIC_KEY'; // TODO enter your API public key
        ///     }
        /// 
        /// Code-behind:
        /// -----------
        /// string Payload = Disqus.Examples.SSO.GetPayload("test1", "Charlie Chaplin", "charlie@example.com");
        /// 
        /// </summary>

        /// Disqus API secret key can be obtained here: http://disqus.com/api/applications/
        /// This will only work if that key is associated with your SSO remote domain
        /// It is highly recommended that you DO NOT hard-code your API secret key here, and instead read it from a secure configuration store
        
        private const string _apiSecret = "DISQUS_SECRET_KEY"; // TODO enter your API secret key (for illustrative purposes only)

        /// <summary>
        /// Gets the Disqus SSO payload to authenticate users
        /// </summary>
        /// <param name="user_id">The unique ID to associate with the user</param>
        /// <param name="user_name">Non-unique name shown next to comments.</param>
        /// <param name="user_email">User's email address, defined by RFC 5322</param>
        /// <param name="avatar_url">URL of the avatar image</param>
        /// <param name="website_url">Website, blog or custom profile URL for the user, defined by RFC 3986</param>
        /// <returns>A string containing the signed payload</returns>
        public static string GetPayload(string user_id, string user_name, string user_email, string avatar_url = "", string website_url = "")
        {
            var userdata = new
            {
                id = user_id,
                username = user_name,
                email = user_email,
                avatar = avatar_url,
                url = website_url
            };

            string serializedUserData = new JavaScriptSerializer().Serialize(userdata);
            return GeneratePayload(serializedUserData);
        }

        /// <summary>
        /// Method to log out a user from SSO
        /// </summary>
        /// <returns>A signed, empty payload string</returns>
        public static string LogoutUser()
        {
            var userdata = new { };
            string serializedUserData = new JavaScriptSerializer().Serialize(userdata);
            return GeneratePayload(serializedUserData);
        }

        private static string GeneratePayload(string serializedUserData)
        {
            byte[] userDataAsBytes = Encoding.ASCII.GetBytes(serializedUserData);

            // Base64 Encode the message
            string Message = System.Convert.ToBase64String(userDataAsBytes);

            // Get the proper timestamp
            TimeSpan ts = (DateTime.UtcNow - new DateTime(1970, 1, 1, 0, 0, 0));
            string Timestamp = Convert.ToInt32(ts.TotalSeconds).ToString();

            // Convert the message + timestamp to bytes
            byte[] messageAndTimestampBytes = Encoding.ASCII.GetBytes(Message + " " + Timestamp);

            // Convert Disqus API key to HMAC-SHA1 signature
            byte[] apiBytes = Encoding.ASCII.GetBytes(_apiSecret);
            using (HMACSHA1 hmac = new HMACSHA1(apiBytes)) {
                byte[] hashedMessage = hmac.ComputeHash(messageAndTimestampBytes);

                // Put it all together into the final payload
                return Message + " " + ByteToString(hashedMessage) + " " + Timestamp;
            }
        }

        private static string ByteToString(byte[] buff)
        {
            string sbinary = "";

            for (int i = 0; i < buff.Length; i++)
            {
                sbinary += buff[i].ToString("X2"); // hex format
            }
            return (sbinary);
        }
    }
}
