defmodule Disqus.SSO do
  @moduledoc """
  This module depends on Poison
  Add to the mix.exs deps section the folowing line
    {:poison, "~> 2.2.0"}

  like:
  defp deps do
    [
      {:poison, "~> 2.2.0"}
    ]
  end
  """


  @doc """
  user_params - a map contains:
    - required keys "id", "username", "email",
    - optional keys "avatar", "url"
  """
  def get_disqus_sso(user_params, disqus_api_key \\ nil, disqus_secret_key \\ nil) do
      # Getting keys from system env
      disqus_api_key =  disqus_api_key || System.get_env("DISQUS_API_KEY")
      disqus_secret_key = disqus_secret_key || System.get_env("DISQUS_SECRET_KEY")

      # create a JSON packet of our data attributes
      # and base64 encode
      message = %{
        "id" => user_params["id"],
        "username" => user_params["username"],
        "email" => user_params["email"],
        "avatar" => user_params["avatar"],
        "url" => user_params["url"]
      }
      |> Poison.encode!()
      |> Base.encode64()

      # generate a timestamp for signing the message
      timestamp = :os.system_time(:seconds)
      # generate our hmac signature
      hmac = :crypto.hmac(:sha, disqus_secret_key, "#{message} #{timestamp}") |> Base.encode16()

      # return a script tag to insert the sso message
      """
      <script type='text/javascript'>
          var disqus_config = function() {
              this.page.remote_auth_s3 = '#{message} #{hmac} #{timestamp}';
              this.page.api_key = '#{disqus_api_key}';
          }
  	  </script>
      """
  end
end
