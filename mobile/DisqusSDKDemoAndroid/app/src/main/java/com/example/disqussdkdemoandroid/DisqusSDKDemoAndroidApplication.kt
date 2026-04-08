package com.example.disqussdkdemoandroid

import android.app.Application
import com.publisher.disqus.core.DisqusConfig
import com.publisher.disqus.core.DisqusError
import com.publisher.disqus.core.DisqusSDK

class DisqusSDKDemoAndroidApplication : Application() {

    override fun onCreate() {
        super.onCreate()
        try {
            DisqusSDK.initialize(
                DisqusConfig(
                    shortName        = BuildConfig.DISQUS_SHORT_NAME,
                    apiKey           = BuildConfig.DISQUS_API_KEY,
                    url              = BuildConfig.DISQUS_URL,
                    title            = BuildConfig.DISQUS_TITLE,
                    oauthCallbackUrl = "https://${BuildConfig.DISQUS_SHORT_NAME}.disqus.com/mobileauth/success",
                )
            )
        } catch (e: DisqusError.AlreadyInitialized) {
            // Safe to ignore on process restart edge cases
        }
    }
}
