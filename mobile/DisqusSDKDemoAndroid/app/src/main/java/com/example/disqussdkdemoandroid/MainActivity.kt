package com.example.disqussdkdemoandroid

import android.content.Intent
import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.DividerItemDecoration
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.example.disqussdkdemoandroid.data.Article
import com.example.disqussdkdemoandroid.ui.ArticleAdapter
import com.publisher.disqus.core.DisqusSDK
import com.publisher.disqus.service.DisqusService
import com.publisher.disqus.service.DisqusThreadRef

class MainActivity : AppCompatActivity() {

    private lateinit var adapter: ArticleAdapter
    private lateinit var service: DisqusService

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        service = DisqusService(DisqusSDK.config, DisqusSDK.logger)
        adapter = ArticleAdapter(Article.SAMPLES) { article ->
            startActivity(
                Intent(this, ArticleDetailActivity::class.java)
                    .putExtra(ArticleDetailActivity.EXTRA_ARTICLE_ID, article.id)
            )
        }

        val recyclerView = findViewById<RecyclerView>(R.id.recycler_view)
        recyclerView.layoutManager = LinearLayoutManager(this)
        recyclerView.addItemDecoration(
            DividerItemDecoration(this, DividerItemDecoration.VERTICAL)
        )
        recyclerView.adapter = adapter

        loadCommentCounts()
    }

    private fun loadCommentCounts() {
        val threads = Article.SAMPLES.map { DisqusThreadRef(it.shortName, it.url, it.id) }
        service.getCommentCounts(threads) { result ->
            result.onSuccess { counts ->
                runOnUiThread { adapter.updateCommentCounts(counts) }
            }
        }
    }
}
