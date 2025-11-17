# Overview
This example shows how to install Disqus comments in a mobile app built with React Native using a Webview.

This requires hosting your Disqus comments on a separate page, which will be loaded in the Webview. You can use `mobiletemplate.html` located in `DISQUS-API-Recipes/mobile/js` to help create that page.

# Getting Started

1. Set up your Disqus comments page and use its url for `commentsUri` in `DISQUS-API-Recipes/mobile/react-native-webview-demo/app/index.tsx`
2. Navigate to `DISQUS-API-Recipes/mobile/react-native-webview-demo` and spin up the local server:
```
npm install
npm run dev
```
3. Open the project on your device, such as by scanning the QR code in your terminal with your mobile device.
