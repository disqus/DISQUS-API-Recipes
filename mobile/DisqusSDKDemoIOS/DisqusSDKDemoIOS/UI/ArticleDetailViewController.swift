// Copyright (c) 2024 Publisher. Licensed under the MIT License.
import UIKit
import SafariServices
import DisqusSDK

final class ArticleDetailViewController: UIViewController {

    private let article: Article
    private let disqusView = DisqusView()
    private var navToken: DisqusEventToken?

    init(article: Article) {
        self.article = article
        super.init(nibName: nil, bundle: nil)
    }

    required init?(coder: NSCoder) { fatalError("init(coder:) not supported") }

    override func viewDidLoad() {
        super.viewDidLoad()
        title = article.title
        view.backgroundColor = .systemBackground

        setupDisqusView()
        subscribeEvents()
        loadThread()
    }

    deinit {
        if let d = try? DisqusSDK.shared.eventDispatcher() {
            navToken.map { d.unsubscribe($0) }
        }
        disqusView.destroy()
    }

    // MARK: - Setup

    private func setupDisqusView() {
        disqusView.translatesAutoresizingMaskIntoConstraints = false
        view.addSubview(disqusView)
        NSLayoutConstraint.activate([
            disqusView.topAnchor.constraint(equalTo: view.safeAreaLayoutGuide.topAnchor),
            disqusView.leadingAnchor.constraint(equalTo: view.leadingAnchor),
            disqusView.trailingAnchor.constraint(equalTo: view.trailingAnchor),
            disqusView.bottomAnchor.constraint(equalTo: view.bottomAnchor),
        ])
    }

    private func loadThread() {
        guard let config = try? DisqusSDK.shared.config(),
              let dispatcher = try? DisqusSDK.shared.eventDispatcher(),
              let logger = try? DisqusSDK.shared.logger() else { return }

        // Build per-article oauthCallbackUrl from the article's shortName.
        // The global config's oauthCallbackUrl provides the domain suffix and path template
        // (e.g. "SHORTNAME.disqus.com/mobileauth/success"). Each article replaces just
        // the subdomain prefix with its own shortName so the AASA and server redirect match.
        let articleCallbackUrl: String?
        if let template = config.oauthCallbackUrl,
           let templateUrl = URL(string: template),
           let host = templateUrl.host,
           let dotRange = host.range(of: ".") {
            let domainSuffix = String(host[dotRange.upperBound...])
            articleCallbackUrl = "https://\(article.shortName).\(domainSuffix)\(templateUrl.path)"
        } else {
            articleCallbackUrl = config.oauthCallbackUrl
        }

        let articleConfig = try? DisqusConfig(
            shortName:        article.shortName,
            apiKey:           article.apiKey,
            url:              article.url,
            title:            article.title,
            oauthCallbackUrl: articleCallbackUrl,
            identifier:       article.id
        )
        guard let articleConfig else { return }

        disqusView.attach(config: articleConfig, dispatcher: dispatcher, logger: logger)
        disqusView.load()
    }

    private func subscribeEvents() {
        guard let dispatcher = try? DisqusSDK.shared.eventDispatcher() else { return }

        navToken = dispatcher.subscribe(DisqusNavigationRedirectEvent.self) { [weak self] event in
            DispatchQueue.main.async {
                let safari = SFSafariViewController(url: event.url)
                self?.present(safari, animated: true)
            }
        }
    }

}
