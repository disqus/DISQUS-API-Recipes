import { useEffect } from 'react';
import { View, StyleSheet } from 'react-native';
import { useRouter } from 'expo-router';

/**
 * This route handles the OAuth callback from Disqus.
 * When Android App Links redirect here, it signals Chrome Custom Tabs to close.
 * expo-web-browser captures the URL and delivers the result to the waiting component.
 *
 * This screen waits briefly for the auth flow to complete, then goes back.
 */
export default function MobileAuthSuccess() {
  const router = useRouter();

  useEffect(() => {
    // Small delay to ensure expo-web-browser delivers the result
    // and the DisqusComments component processes the postMessage
    const timer = setTimeout(() => {
      if (router.canGoBack()) {
        router.back();
      } else {
        router.replace('/');
      }
    }, 100); // 100ms should be enough for the auth callback to complete

    return () => clearTimeout(timer);
  }, []);

  return (
    <View style={styles.container}>
      {/* Invisible screen - only exists briefly for the deep link */}
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: 'transparent',
  },
});
