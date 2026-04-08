package com.example.disqussdkdemoandroid.ui

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView
import com.example.disqussdkdemoandroid.R
import com.example.disqussdkdemoandroid.data.Article

class ArticleAdapter(
    private val articles: List<Article>,
    private val onArticleClick: (Article) -> Unit,
) : RecyclerView.Adapter<ArticleAdapter.ViewHolder>() {

    private val commentCounts = mutableMapOf<String, Int>()

    fun updateCommentCounts(counts: Map<String, Int>) {
        commentCounts.clear()
        commentCounts.putAll(counts)
        notifyDataSetChanged()
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(R.layout.item_article, parent, false)
        return ViewHolder(view)
    }

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        holder.bind(articles[position])
    }

    override fun getItemCount() = articles.size

    inner class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        private val titleText: TextView = itemView.findViewById(R.id.text_title)
        private val countBadge: TextView = itemView.findViewById(R.id.text_comment_count)

        fun bind(article: Article) {
            titleText.text = article.title
            val count = commentCounts[article.url] ?: 0
            countBadge.text = itemView.context.getString(R.string.comment_count_format, count)
            itemView.setOnClickListener { onArticleClick(article) }
        }
    }
}
