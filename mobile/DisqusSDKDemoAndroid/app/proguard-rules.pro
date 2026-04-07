# Keep SDK public classes
-keep class com.publisher.disqus.** { *; }

# Keep WebView JavaScript interfaces
-keepclassmembers class * {
    @android.webkit.JavascriptInterface <methods>;
}

# OkHttp
-dontwarn okhttp3.**
-dontwarn okio.**
