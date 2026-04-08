plugins {
    id("com.android.application")
    id("org.jetbrains.kotlin.android")
}

android {
    namespace = "com.example.disqussdkdemoandroid"
    compileSdk = 34

    defaultConfig {
        applicationId = "com.example.disqussdkdemoandroid"
        minSdk = 26
        targetSdk = 34
        versionCode = 1
        versionName = "1.0"

        val shortName = project.findProperty("disqusShortName") as String? ?: ""
        val apiKey    = project.findProperty("disqusApiKey") as String? ?: ""
        val url       = project.findProperty("disqusUrl") as String? ?: ""
        val title     = project.findProperty("disqusTitle") as String? ?: ""

        buildConfigField("String", "DISQUS_SHORT_NAME", "\"$shortName\"")
        buildConfigField("String", "DISQUS_API_KEY",    "\"$apiKey\"")
        buildConfigField("String", "DISQUS_URL",        "\"$url\"")
        buildConfigField("String", "DISQUS_TITLE",      "\"$title\"")
    }

    buildTypes {
        release {
            isMinifyEnabled = false
            proguardFiles(
                getDefaultProguardFile("proguard-android-optimize.txt"),
                "proguard-rules.pro"
            )
        }
    }

    buildFeatures {
        buildConfig = true
    }

    compileOptions {
        sourceCompatibility = JavaVersion.VERSION_17
        targetCompatibility = JavaVersion.VERSION_17
    }

    kotlinOptions {
        jvmTarget = "17"
    }
}

dependencies {
    implementation(files("libs/disqus-sdk-android-0.1.0-release.aar"))
    implementation("androidx.core:core-ktx:1.12.0")
    implementation("androidx.appcompat:appcompat:1.6.1")
    implementation("com.google.android.material:material:1.11.0")
    implementation("androidx.recyclerview:recyclerview:1.3.2")
    implementation("androidx.browser:browser:1.7.0")
    implementation("com.squareup.okhttp3:okhttp:4.12.0")
}
