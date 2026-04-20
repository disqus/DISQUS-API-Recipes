require 'rubygems'
require 'base64'
require 'cgi'
require 'openssl'
require "json"
 
DISQUS_SECRET_KEY = '<YOUR_SECRET_KEY>'
DISQUS_PUBLIC_KEY = '<YOUR_PUBLIC_KEY>'
 
def get_disqus_sso(user)
    # create a JSON packet of our data attributes
    data = 	{
      'id' => user['id'],
      'username' => user['username'],
      'email' => user['email']
	  #'avatar' => user['avatar'],
	  #'url' => user['url']
    }.to_json
 
    # encode the data to base64
    message  = Base64.encode64(data).gsub("\n", "")
    # generate a timestamp for signing the message
    timestamp = Time.now.to_i
    # generate our hmac signature
    sig = OpenSSL::HMAC.hexdigest('sha1', DISQUS_SECRET_KEY, '%s %s' % [message, timestamp])
 
    # return a script tag to insert the sso message
    return "<script type=\"text/javascript\">
        var disqus_config = function() {
            this.page.remote_auth_s3 = \"#{message} #{sig} #{timestamp}\";
            this.page.api_key = \"#{DISQUS_PUBLIC_KEY}\";
        }
	</script>".html_safe
end
