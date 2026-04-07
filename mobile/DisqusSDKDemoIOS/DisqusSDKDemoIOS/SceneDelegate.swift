// Copyright (c) 2024 Publisher. Licensed under the MIT License.
import UIKit
import DisqusSDK

class SceneDelegate: UIResponder, UIWindowSceneDelegate {

    var window: UIWindow?

    func scene(
        _ scene: UIScene,
        willConnectTo session: UISceneSession,
        options connectionOptions: UIScene.ConnectionOptions
    ) {
        guard let windowScene = scene as? UIWindowScene else { return }

        let window = UIWindow(windowScene: windowScene)
        let navController = UINavigationController(rootViewController: ArticleListViewController())
        window.rootViewController = navController
        self.window = window
        window.makeKeyAndVisible()
    }


}
