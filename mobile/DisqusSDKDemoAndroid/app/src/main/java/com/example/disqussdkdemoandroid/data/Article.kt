package com.example.disqussdkdemoandroid.data

data class Article(
    val id: String,
    val title: String,
    val url: String,
    val shortName: String,
    val apiKey: String,
) {
    companion object {
        val SAMPLES = listOf(
            Article(
                id        = "ARTICLE_ID",
                title     = "ARTICLE_TITLE",
                url       = "YOUR_URL",
                shortName = "YOUR_SHORT_NAME",
                apiKey    = "YOUR_API_KEY"
            )
        )
    }
}
