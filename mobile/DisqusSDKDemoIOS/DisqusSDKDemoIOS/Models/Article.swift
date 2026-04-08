// Copyright (c) 2024 Publisher. Licensed under the MIT License.
import Foundation

struct Article {
    let id: String
    let title: String
    let url: String
    let shortName: String
    let apiKey: String

    static let samples: [Article] = [
        Article(
            id:        "ARTICLE_ID",
            title:     "ARTICLE_TITLE",
            url:       "YOUR_URL",
            shortName: "YOUR_SHORT_NAME",
            apiKey:    "YOUR_API_KEY"
        ),
    ]
}
