# DisqusSDKDemoIOS

iOS demo app showing Disqus SDK integration: article list with comment counts and article detail with embedded comments and authentication.

## Setup

1. **Set up Disqus values** — open `DisqusSDKDemoIOS/AppDelegate.swift` and update `shortName`, `apiKey`, `url`, `title`, and `oauthCallbackUrl` with the appropriate values.

2. **Set up your entitlements file** - open `mobile/DisqusSDKDemoIOS/DisqusSDKDemoIOS/DisqusSDKDemoIOS.entitlements` and replace `YOUR_SHORTNAME` with your Disqus shortname.

2. **Install Disqus iOS SDK** - Follow the instructions to add the Disqus iOS SDK to this project. If you do not have access to these instructions, then please reach out to your Disqus representative or Disqus support.

3. **Open in Xcode** — open `DisqusSDKDemoIOS.xcodeproj`.

3. **Select a simulator** — choose an iPhone simulator running iOS 17.4 or later.

4. **Build and run** — press ⌘R.

## Features

- Article list with live comment counts fetched via `DisqusService`
- Article detail screen with embedded `DisqusView`
- Login / Logout via `DisqusAuthManager` (`ASWebAuthenticationSession`)
- External link interception — opens in Safari and returns to comments

## OAuth Callback

The app uses an HTTPS Universal Link (`https://YOUR_SHORTNAME.disqus.com/mobileauth/success`) as the OAuth callback. `ASWebAuthenticationSession` intercepts the redirect via the Associated Domains entitlement — no deep-link routing in `SceneDelegate` is needed.
