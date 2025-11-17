import { useState, useRef } from 'react';
import { StyleSheet, View, ActivityIndicator, Text, ScrollView } from 'react-native';
import { WebView, WebViewNavigation } from 'react-native-webview';

export default function WebViewScreen() {
  const [isLoading, setIsLoading] = useState(true);
  const webViewRef = useRef<WebView>(null);

  // Replace commentsUri with the URL where your comment thread is located
  const commentsUri = '';

  // Array of patterns that indicate login success
  const loginSuccessPatterns = [
    'disqus.com/next/login-success',
    'disqus.com/embed/comments'
  ];

    // Check if the current URL matches a redirect pattern
  const handleNavigationStateChange = (navState: WebViewNavigation) => {
    const { url } = navState;

    // Normalize the URL to lowercase
    const navigatedUri = url.toLowerCase();

    // Check if the current URL matches any of the login success patterns
    if (loginSuccessPatterns.some(pattern => navigatedUri.includes(pattern))) {
      // Set flag to redirect after page loads
      if (webViewRef && webViewRef.current) {
        webViewRef.current.stopLoading();
        webViewRef.current.injectJavaScript(`window.location = '${commentsUri}'`);
      }
    }
  };

  return (
    <ScrollView style={styles.container} contentContainerStyle={styles.scrollContent}>
      <View style={styles.headerContainer}>
        <Text style={styles.headerText}>Disqus Webview Demo</Text>
        <Text style={styles.sectionText}>This is a demo of embedding Disqus in a React Native app.</Text>
        <Text style={styles.sectionText}>Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.</Text>
        <Text style={styles.sectionText}>Integer rhoncus tincidunt arcu, id posuere nibh viverra ut. Nulla sed orci vel nibh aliquet tempus nec non justo. Donec at tempor elit, eu viverra leo. Nulla congue enim non quam rutrum bibendum. Vestibulum tincidunt sapien commodo nibh egestas interdum. Proin aliquet vulputate augue, ac semper nisi varius ut. Aenean rhoncus mauris congue, tempor risus et, facilisis lacus. Nullam vehicula enim semper diam vulputate viverra. Aliquam efficitur diam in faucibus rhoncus. Nam augue nisl, volutpat ut nibh nec, volutpat bibendum nisi.</Text>
        <Text style={styles.sectionText}>Sed non eros et tellus posuere aliquam. Quisque eu auctor urna. Donec hendrerit arcu est, non commodo nunc ultrices ut. Fusce ornare condimentum fringilla. Etiam auctor rutrum elit, vitae tempus elit auctor vel. Aliquam quis nunc leo. Duis urna sapien, cursus ut convallis quis, efficitur sit amet ex. Mauris elementum dapibus nulla, sed ultrices nulla varius sit amet. Donec aliquet, dui sit amet suscipit auctor, elit nibh tempor mi, fermentum accumsan ipsum ex vitae velit. In id ultrices justo. Phasellus porta sodales ligula.</Text>
        <Text style={styles.sectionText}>Phasellus eleifend dapibus nisl id tincidunt. Curabitur pretium non lacus eu sagittis. Cras condimentum ipsum ac ex lobortis volutpat. Phasellus placerat molestie lacus, sit amet congue ligula vehicula eu. Nunc varius enim id turpis gravida molestie. Sed neque metus, faucibus at diam vehicula, blandit imperdiet dolor. Integer euismod mauris in ipsum volutpat egestas. Aliquam erat volutpat. Integer vitae ante eros.</Text>
      </View>

      <View style={styles.webviewContainer}>
        <WebView
          ref={webViewRef}
          source={{ uri: commentsUri }}
          originWhitelist={['*']}
          style={styles.webview}
          onLoadStart={() => setIsLoading(true)}
          onLoadEnd={() => setIsLoading(false)}
          onNavigationStateChange={handleNavigationStateChange}
          webviewDebuggingEnabled={true}
          thirdPartyCookiesEnabled={true}
          scrollEnabled={true}
        />
        {isLoading && (
          <View style={styles.loadingContainer}>
            <ActivityIndicator size='large' color='#0066cc' />
          </View>
        )}
      </View>

      <View style={styles.footerContainer}>
        <Text style={styles.sectionText}>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In at maximus velit, elementum elementum neque. Ut sit amet posuere lectus, vel tristique sem. Fusce non laoreet nunc. Pellentesque pharetra velit nec lorem aliquet, accumsan posuere turpis lobortis. Sed varius dui ut nulla consequat consequat. Suspendisse ac consequat lacus. Cras sollicitudin ex sed est vehicula, in viverra sapien laoreet. Ut dui magna, aliquam sed pharetra eu, placerat et lorem. Sed condimentum sodales ex.</Text>
        <Text style={styles.sectionText}>Suspendisse cursus tortor eu turpis consequat, vitae vestibulum nulla posuere. Sed ac pretium leo. Cras massa sem, feugiat eget efficitur at, sodales quis turpis. Aliquam ut justo elit. Nulla consectetur ut nisi ut facilisis. Aliquam erat volutpat. Nullam euismod dolor et velit iaculis, vitae lobortis libero fermentum. Donec posuere dictum ex, tincidunt consequat libero sollicitudin nec. Duis at massa nec elit maximus tincidunt eget et ante. Nam elementum, sem sed malesuada finibus, tellus diam sodales justo, sit amet consequat turpis ligula sed odio. Quisque cursus purus urna, ac congue nunc mattis imperdiet.</Text>
      </View>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f5f5',
  },
  scrollContent: {
    flexGrow: 1,
  },
  headerContainer: {
    padding: 20,
    paddingTop: 60,
  },
  headerText: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#333333',
    marginBottom: 8,
  },
  sectionText: {
    fontSize: 14,
    color: '#555555',
    lineHeight: 20,
    marginBottom: 6,
  },
  webviewContainer: {
    height: 600,
    position: 'relative',
  },
  webview: {
    flex: 1,
  },
  footerContainer: {
    padding: 20,
  },
  loadingContainer: {
    position: 'absolute',
    top: 0,
    left: 0,
    right: 0,
    bottom: 0,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#f5f5f5',
  },
});