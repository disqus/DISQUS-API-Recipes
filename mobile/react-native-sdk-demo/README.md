# Overview
This example shows how to install Disqus comments in a mobile app built with React Native using the Disqus React Native SDK.

You will need access to the Disqus React Native SDK documentation to follow the installation and setup instructions there.

# Getting Started

1. Set up your Disqus comments configuration in `DISQUS-API-Recipes/mobile/react-native-sdk-demo/app/index.tsx`.
2. To support authentication, you will need to update `DISQUS-API-Recipes/mobile/react-native-sdk-demo/app.json` with your Apple team id, bundle identifier, associated domains, Android package, and Android host. You will also need to follow the Disqus React Native SDK instructions for additional authentication setup.
3. Install the Disqus React Native SDK in this project, using the instructions from the SDK's documentation.
4. Navigate to `DISQUS-API-Recipes/mobile/react-native-sdk-demo` and spin up the local server:
```
npm install
npm run dev
```
> **Note:** For authentication, you will need to run `npm run ios` (for iOS) and/or `npm run android` (for Android) to build an app that supports deep links/universal links.

5. Open the project on your device, such as by scanning the QR code in your terminal with your mobile device.
