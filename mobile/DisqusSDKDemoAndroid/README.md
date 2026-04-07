# DisqusSDKDemoAndroid

An end-to-end example Android app demonstrating Disqus SDK integration.

## Setup

### 1. Fill in credentials

Open `gradle.properties` and replace the placeholder values:

```properties
disqusShortName=your_forum_short_name
disqusApiKey=your_public_api_key
disqusThreadUrl=https://yoursite.com/some-article
```

- `disqusShortName` — your Disqus forum short name (found in Admin › Settings › General)
- `disqusApiKey` — a public API key from [disqus.com/api/applications/](https://disqus.com/api/applications/)
- `disqusThreadUrl` — the canonical URL of a page registered as a Disqus thread; the demo
  embeds this single thread on every article detail screen

### 2. Register OAuth callback

The demo uses App Links — no custom scheme registration is needed. Ensure Disqus has configured `assetlinks.json` for your package name and SHA-256 fingerprint so the OS can verify the `https://YOURSHORTNAME.disqus.com/mobileauth/success` App Link at install time.

### 3. Open in Android Studio

Open the `mobile/DisqusSDKDemoAndroid/` folder (not the repo root) in Android Studio Hedgehog or later.

If the Gradle wrapper JAR is missing, Android Studio will offer to download it automatically,
or run `gradle wrapper --gradle-version 8.4` from this directory.

### 4. Run

Build and run the `:app` module on an emulator (API 26+) or physical device.

## What the demo shows

| Screen | Demonstrates |
|---|---|
| Article list (`MainActivity`) | `DisqusService.getCommentCounts` — fetches comment counts for multiple URLs |
| Article detail (`ArticleDetailActivity`) | `DisqusView.attach/load/reload/destroy` |
| Login button | `DisqusAuthManager.startLogin` — Chrome Custom Tab OAuth flow |
| Login inside embed | Auth URL intercepted by SDK → Chrome Custom Tab launched automatically |
| Logout button | `DisqusAuthManager.logout` |
| OAuth callback | `onNewIntent` → `handleCallback` (no `returnToComments` needed) |
| Event subscription | `DisqusAuthEvent`, `DisqusNavigationEvent` via `DisqusEventDispatcher` |

## Project structure

```
DisqusSDKDemoAndroid/
├── app/                        # Demo application module
├── disqus-sdk/                 # Library wrapper; source redirected to ../../android/
├── build.gradle.kts            # Root build (AGP 8.2.2, Kotlin 1.9.23)
├── settings.gradle.kts
└── gradle.properties           # Credentials (do not commit real values)
```

The `disqus-sdk` module contains no Kotlin files of its own — its `sourceSets` configuration
points directly at the SDK source in `android/src/main/java`, so the demo always builds
against the live source tree without duplication.
