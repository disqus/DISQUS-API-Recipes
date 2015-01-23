<cfcomponent displayname="Comment" hint="Disqus Interface">
	 
	<!--- In CF10 and earlier, and likely in systems upgraded to CF11, SerializeJSON is explicity
	     case-insensitive. In fact, it converts all keys to UPPERCASE, which will cause SSO to fail.
	     To work around this, declare the struct with curly brackets and declare the keys with array-style notation.
	     After you've declared a key, you can use regular dot notation to reference them. --->
	<cfset VARIABLES.stcMemberInfo = {} />
	<cfset VARIABLES.intUnixTimestamp = dateDiff('s', dateConvert('UTC2Local', createDateTime(1970, 1, 1, 0, 0, 0)), Now()) />
	<cfset VARIABLES.stgPublicKey = "">
	<cfset VARIABLES.stgPrivateKey = "">
	<cfset VARIABLES.stgForumShortName = "">
	 
	 <!--- Use array-style key declaration, like so: --->
	<cfset stcMemberInfo[ "id" ] = currentUser.getUserID() >
	<cfset stcMemberInfo[ "username" ] = currentUser.getUserName() >
	<cfset stcMemberInfo[ "email" ] = currentUser.getEmail() >
	<cfset stcMemberInfo[ "avatar" ] = currentUser.getAvatar() >
	<cfset stcMemberInfo[ "url" ] = currentUser.getURL() >
	 
	<cffunction name="DISQUS_HMAC_SHA1" returntype="string" access="public" output="false">
		<cfargument name="signKey" type="string" required="true" />
		<cfargument name="signMessage" type="string" required="true" />
		
		<cfset var strKey=createObject("java", "java.lang.String") />
		<cfset var strData=createObject("java", "java.lang.String") />
		<cfset var key=createObject("java", "javax.crypto.spec.SecretKeySpec") />
		<cfset var mac=createObject("java", "javax.crypto.Mac") />
		<cfset var messageDigest=createObject("java", "java.security.MessageDigest") />
		<cfset var sha1=messageDigest.getInstance("SHA1") />
		<cfset var strMessage=createObject("java", "java.lang.String") />
		<cfset var strTimestamp=createObject("java", "java.lang.String") />
		<cfset strKey=strKey.init(ARGUMENTS.signKey) />
		<cfset strData=strData.init(ARGUMENTS.signMessage) />
	 
		<cfset key=key.init(strKey.getBytes(), "HMACSHA1") />
		<cfset mac=mac.getInstance(key.getAlgorithm()) />
		<cfset mac.init(key) />
		<cfset output = BinaryEncode(mac.doFinal(ARGUMENTS.signMessage.getBytes()), "hex") />
		<cfreturn output />
	</cffunction>
	 
	<cffunction name = "createLoginToken" access = "public" output = "false">
		<cfscript>
			var intTimestamp = VARIABLES.intUnixTimestamp;
			var stgData = "";
			var stgMessage = "";
			var stgSignature = "";
		</cfscript>
		<cfif ! StructKeyExists(VARIABLES.stcMemberInfo, "id")>
			<cfreturn "" />
		</cfif>
		<cfscript>
			stgData = SerializeJSON(VARIABLES.stcMemberInfo);
			stgMessage = ToBase64(stgData) & " " & intTimestamp;
			stgSignature = DISQUS_HMAC_SHA1( VARIABLES.stgPrivateKey, stgMessage);
			return ToBase64(stgData) & " " & stgSignature & " " & intTimestamp;
		</cfscript>
	 
	</cffunction>
	 
	<cffunction name = "embed" access = "public" output = "true">
		<cfargument name="stgUniqueThreadIdentifier" type="string" required="yes">
		<cfargument name="stgUrl" type="string" required="no" default = "">
	 
		<cfset var stgReturn = '
			<div id="disqus_thread"></div>
			<script type="text/javascript">
				/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
				var disqus_shortname = "#VARIABLES.stgForumShortName#"; // required: replace example with your forum shortname
				var disqus_developer = 1;
				var disqus_identifier = "#ARGUMENTS.stgUniqueThreadIdentifier#";
				alert("#VARIABLES.stcMemberInfo.avatar#");
	 
				/* * * DON''T EDIT BELOW THIS LINE * * */
				(function() {
					var dsq = document.createElement("script"); dsq.type = "text/javascript"; dsq.async = true;
					dsq.src = "//" + disqus_shortname + ".disqus.com/embed.js";
					(document.getElementsByTagName("head")[0] || document.getElementsByTagName("body")[0]).appendChild(dsq);
				})();
	 
				var disqus_config = function() {
					this.page.remote_auth_s3 = "#createLoginToken()#";
					this.page.api_key = "#VARIABLES.stgPublicKey#";
				}
			</script>
			<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
			<!--
			<a href="http://disqus.com" class="dsq-brlink">blog comments powered by <span class="logo-disqus">Disqus</span></a>
			-->' >
		<cfreturn stgReturn>
	</cffunction>
	 
</cfcomponent>
