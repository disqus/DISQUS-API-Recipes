import { StyleSheet, View, Text, ScrollView } from 'react-native';

import { DisqusComments } from 'disqus-sdk-react-native';

export default function WebViewScreen() {
  // Disqus Comments configuration
  // Replace these values with your actual values
  const params = {
    shortname: 'YOUR_SHORTNAME',
    url: 'https://YOUR_WEBSITE.com/EXAMPLE/ARTICLE/',
    title: 'YOUR_THREAD_TITLE',
    identifier: 'YOUR_THREAD_IDENTIFIER',
    api_key: 'YOUR_PUBLIC_DISQUS_API_KEY',
    anchor_color: '#2E9FFF',
    background_color: '#FFFFFF',
  };

  return (
    <ScrollView style={styles.container} contentContainerStyle={styles.scrollContent}>
      <View style={styles.headerContainer}>
        <Text style={styles.headerText}>Disqus React Native Demo</Text>
      </View>

      <View style={styles.footerContainer}>
        <Text style={styles.sectionText}>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In at maximus velit, elementum elementum neque. Ut sit amet posuere lectus, vel tristique sem. Fusce non laoreet nunc. Pellentesque pharetra velit nec lorem aliquet, accumsan posuere turpis lobortis. Sed varius dui ut nulla consequat consequat. Suspendisse ac consequat lacus. Cras sollicitudin ex sed est vehicula, in viverra sapien laoreet. Ut dui magna, aliquam sed pharetra eu, placerat et lorem. Sed condimentum sodales ex.</Text>
        <Text style={styles.sectionText}>Suspendisse cursus tortor eu turpis consequat, vitae vestibulum nulla posuere. Sed ac pretium leo. Cras massa sem, feugiat eget efficitur at, sodales quis turpis. Aliquam ut justo elit. Nulla consectetur ut nisi ut facilisis. Aliquam erat volutpat. Nullam euismod dolor et velit iaculis, vitae lobortis libero fermentum. Donec posuere dictum ex, tincidunt consequat libero sollicitudin nec. Duis at massa nec elit maximus tincidunt eget et ante. Nam elementum, sem sed malesuada finibus, tellus diam sodales justo, sit amet consequat turpis ligula sed odio. Quisque cursus purus urna, ac congue nunc mattis imperdiet.</Text>
      </View>

      <View style={styles.webviewContainer}>
        <DisqusComments
          shortname={params.shortname}
          commentsUrl={params.url}
          title={params.title}
          backgroundColor={params.background_color}
          anchorColor={params.anchor_color}
          showDefaultLoader
          apiKey={params.api_key}
          nestedScrollEnabled
          webviewContainerStyle={styles.webviewContainer}
        />
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
    height: 800,
    position: 'relative',
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
