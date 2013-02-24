using System;
using System.Web.Script.Serialization;
using System.Security.Cryptography;
using System.Text;

namespace sso_payload_example
{
    public class DisqusSSO
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
        ///         this.page.api_key = 'DISQUS_PUBLIC_KEY';
        ///     }
        /// 
        /// Code-behind:
        /// -----------
        /// DisqusSSO sso = new DisqusSSO();
        /// sso.DisqusApiSecret = "DISQUS_SECRET_KEY";
        /// Payload = sso.GetDisqusPayload("test1", "Charlie Chaplin", "charlie@example.com");
        /// 
        /// </summary>

        /// Disqus API secret key can be obtained here: http://disqus.com/api/applications/
        /// This will only work if that key is associated with your SSO remote domain
        public string DisqusApiSecret { get; set; } 
        
        // Only required arguments
        public string GetDisqusPayload(string user_id, string user_name, string user_email)
        {
            var userdata = new
            {
                id = user_id,
                username = user_name,
                email = user_email,
            };

            string serializedUserData = new JavaScriptSerializer().Serialize(userdata);
            return GeneratePayload(serializedUserData);
        }

        // Required + Avatar
        public string GetDisqusPayload(string user_id, string user_name, string user_email, string user_avatar)
        {
            var userdata = new
            {
                id = user_id,
                username = user_name,
                email = user_email,
                avatar = user_avatar,
            };

            string serializedUserData = new JavaScriptSerializer().Serialize(userdata);
            return GeneratePayload(serializedUserData);
        }

        // All Required + Optional arguments
        public string GetDisqusPayload(string user_id, string user_name, string user_email, string user_avatar, string user_url)
        {            
            var userdata = new 
            { 
                id = user_id, // Unique ID associated with each user. Make sure this never conflicts with test data or other users, REQUIRED
                username = user_name, // The name displayed next to the user's comments. 30 characters max. REQUIRED
                email = user_email, // Email address associated with user, REQUIRED
                avatar = user_avatar, // Avatar image, OPTIONAL
                url = user_url // Profile or website URL, OPTIONAL
            };

            string serializedUserData = new JavaScriptSerializer().Serialize(userdata);
            return GeneratePayload(serializedUserData);
        }

        // Take user data and finish generating payload
        private string GeneratePayload(string serializedUserData)
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
            byte[] apiBytes = Encoding.ASCII.GetBytes(DisqusApiSecret);
            HMACSHA1 hmac = new HMACSHA1(apiBytes);
            byte[] hashedMessage = hmac.ComputeHash(messageAndTimestampBytes);

            // Put it all together into the final payload
            return Message + " " + ByteToString(hashedMessage) + " " + Timestamp;
        }

        // Helper to convert bytes into a string for our hashed message
        public static string ByteToString(byte[] buff)
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