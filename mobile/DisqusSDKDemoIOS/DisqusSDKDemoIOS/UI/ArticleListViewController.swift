// Copyright (c) 2024 Publisher. Licensed under the MIT License.
import UIKit
import DisqusSDK

final class ArticleListViewController: UITableViewController {

    private var commentCounts: [String: Int] = [:]

    override func viewDidLoad() {
        super.viewDidLoad()
        title = "Articles"
        tableView.register(UITableViewCell.self, forCellReuseIdentifier: "ArticleCell")
        loadCommentCounts()
    }

    // MARK: - UITableViewDataSource

    override func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        Article.samples.count
    }

    override func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        let cell = tableView.dequeueReusableCell(withIdentifier: "ArticleCell", for: indexPath)
        let article = Article.samples[indexPath.row]
        let count = commentCounts[article.url] ?? 0
        var content = cell.defaultContentConfiguration()
        content.text = article.title
        content.secondaryText = "\(count) comment\(count == 1 ? "" : "s")"
        cell.contentConfiguration = content
        cell.accessoryType = .disclosureIndicator
        return cell
    }

    // MARK: - UITableViewDelegate

    override func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        tableView.deselectRow(at: indexPath, animated: true)
        let article = Article.samples[indexPath.row]
        let detail = ArticleDetailViewController(article: article)
        navigationController?.pushViewController(detail, animated: true)
    }

    // MARK: - Private

    private func loadCommentCounts() {
        guard let config = try? DisqusSDK.shared.config(),
              let logger = try? DisqusSDK.shared.logger() else { return }
        let threads = Article.samples.map {
            DisqusThreadRef(shortName: $0.shortName, url: $0.url, identifier: $0.id)
        }
        let service = DisqusService(config: config, logger: logger)
        service.getCommentCounts(for: threads) { [weak self] result in
            guard let self else { return }
            if case .success(let counts) = result {
                self.commentCounts = counts
                self.tableView.reloadData()
            }
        }
    }
}
