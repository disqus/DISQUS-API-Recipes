# DisqusSDKDemoIOS

iOS demo app showing Disqus SDK integration: article list with comment counts and article detail with embedded comments and authentication.

## Setup

1. **Add your API key** — open `DisqusSDKDemoIOS/AppDelegate.swift` and replace `"YOUR_API_KEY"` with your Disqus public API key.

2. **Open in Xcode** — open `DisqusSDKDemoIOS.xcodeproj`. Xcode will automatically resolve the local `DisqusSDK` Swift Package from `../../ios`.

3. **Select a simulator** — choose an iPhone simulator running iOS 17.4 or later.

4. **Build and run** — press ⌘R.

## Features

- Article list with live comment counts fetched via `DisqusService`
- Article detail screen with embedded `DisqusView`
- Login / Logout via `DisqusAuthManager` (`ASWebAuthenticationSession`)
- External link interception — opens in Safari and returns to comments

## OAuth Callback

The app uses an HTTPS Universal Link (`https://casualgamer.saas-dev.disqus.org/mobileauth/success`) as the OAuth callback. `ASWebAuthenticationSession` intercepts the redirect via the Associated Domains entitlement — no deep-link routing in `SceneDelegate` is needed.
