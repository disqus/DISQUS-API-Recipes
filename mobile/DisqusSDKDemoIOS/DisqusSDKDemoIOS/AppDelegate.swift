// Copyright (c) 2024 Publisher. Licensed under the MIT License.
import UIKit
import DisqusSDK

@main
class AppDelegate: UIResponder, UIApplicationDelegate {

    func application(
        _ application: UIApplication,
        didFinishLaunchingWithOptions launchOptions: [UIApplication.LaunchOptionsKey: Any]?
    ) -> Bool {
        do {
            try DisqusSDK.shared.initialize(with: DisqusConfig(
                shortName:        "hovseptestrealm",
                apiKey:           "YOUR_API_KEY",   // Replace with your Disqus public API key
                url:              "https://hovspian.github.io/disqus-embed/",
                title:            "test_template",
                environment:      .staging,
                oauthCallbackUrl: "https://casualgamer.saas-dev.disqus.org/mobileauth/success",
                anchorColor:      "#FF0000",
                backgroundColor:  "#80e51b"
            ))
        } catch {
            print("[DisqusSDKDemoIOS] SDK init error: \(error)")
        }
        return true
    }

    // MARK: UISceneSession Lifecycle

    func application(
        _ application: UIApplication,
        configurationForConnecting connectingSceneSession: UISceneSession,
        options: UIScene.ConnectionOptions
    ) -> UISceneConfiguration {
        UISceneConfiguration(name: "Default Configuration", sessionRole: connectingSceneSession.role)
    }
}
