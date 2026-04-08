package com.example.disqussdkdemoandroid

import android.content.Intent
import android.net.Uri
import android.os.Bundle
import android.view.Menu
import android.view.MenuItem
import androidx.appcompat.app.AppCompatActivity
import androidx.appcompat.widget.Toolbar
import androidx.browser.customtabs.CustomTabsIntent
import com.example.disqussdkdemoandroid.data.Article
import com.publisher.disqus.auth.DisqusAuthEvent
import com.publisher.disqus.auth.DisqusAuthManager
import com.publisher.disqus.core.DisqusEventToken
import com.publisher.disqus.core.DisqusSDK
import com.publisher.disqus.ui.DisqusView
import com.publisher.disqus.web.DisqusNavigationEvent

class ArticleDetailActivity : AppCompatActivity() {

    companion object {
        const val EXTRA_ARTICLE_ID = "extra_article_id"
    }

    private lateinit var disqusView: DisqusView
    private lateinit var authManager: DisqusAuthManager

    private var authToken: DisqusEventToken? = null
    private var navToken: DisqusEventToken? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_article_detail)

        val articleId = intent.getStringExtra(EXTRA_ARTICLE_ID) ?: ""
        val article = Article.SAMPLES.find { it.id == articleId }

        val toolbar = findViewById<Toolbar>(R.id.toolbar)
        setSupportActionBar(toolbar)
        supportActionBar?.apply {
            setDisplayHomeAsUpEnabled(true)
            title = article?.title ?: getString(R.string.app_name)
        }

        disqusView = findViewById(R.id.disqus_view)
        authManager = DisqusAuthManager(
            DisqusSDK.config,
            DisqusSDK.eventDispatcher,
            DisqusSDK.logger,
        )

        subscribeEvents()

        val articleConfig = DisqusSDK.config.copy(
            shortName  = article?.shortName ?: DisqusSDK.config.shortName,
            url        = article?.url       ?: DisqusSDK.config.url,
            title      = article?.title     ?: DisqusSDK.config.title,
            identifier = article?.id,
            apiKey     = article?.apiKey    ?: DisqusSDK.config.apiKey,
        )

        disqusView.attach(
            config = articleConfig,
            dispatcher = DisqusSDK.eventDispatcher,
            logger = DisqusSDK.logger,
        )
        disqusView.load()
    }

    private fun subscribeEvents() {
        authToken = DisqusSDK.eventDispatcher.subscribe(DisqusAuthEvent::class.java) { event ->
            when (event) {
                is DisqusAuthEvent.LoginSuccess -> {
                    runOnUiThread { disqusView.reload() }
                }
                is DisqusAuthEvent.LoggedOut -> {
                    runOnUiThread { disqusView.reload() }
                }
                is DisqusAuthEvent.LoginFailure -> Unit
            }
        }

        navToken = DisqusSDK.eventDispatcher.subscribe(DisqusNavigationEvent::class.java) { event ->
            when (event) {
                is DisqusNavigationEvent.Redirect -> {
                    runOnUiThread {
                        openInBrowser(event.url)
                        disqusView.returnToComments()
                    }
                }
                DisqusNavigationEvent.LoginDetected -> Unit
            }
        }
    }

    private fun openInBrowser(url: String) {
        try {
            CustomTabsIntent.Builder().build().launchUrl(this, Uri.parse(url))
        } catch (e: Exception) {
            startActivity(Intent(Intent.ACTION_VIEW, Uri.parse(url)))
        }
    }

    override fun onCreateOptionsMenu(menu: Menu): Boolean {
        menuInflater.inflate(R.menu.menu_article_detail, menu)
        return true
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        return when (item.itemId) {
            android.R.id.home -> {
                onBackPressedDispatcher.onBackPressed()
                true
            }
            else -> super.onOptionsItemSelected(item)
        }
    }

    override fun onNewIntent(intent: Intent) {
        super.onNewIntent(intent)
        val data = intent.data ?: return
        authManager.handleCallback(data)
        // Do not call returnToComments() here:
        // - WebView-initiated auth: the WebView never left the embed; postOAuthComplete is
        //   already queued and a reload would cancel it before !oauthComplete fires.
        // - SDK-initiated auth (startLogin): reload is handled by the LoginSuccess event
        //   subscription above (disqusView.reload()).
    }

    override fun onDestroy() {
        authToken?.let { DisqusSDK.eventDispatcher.unsubscribe(it) }
        navToken?.let { DisqusSDK.eventDispatcher.unsubscribe(it) }
        disqusView.destroy()
        super.onDestroy()
    }
}
