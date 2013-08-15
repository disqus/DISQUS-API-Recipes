/* A Scala (Play) example
 * Adapted from the Java example */

package controllers

import play.api.libs.json.Json
import play.api.libs.json.Json._

// Don't use sun.misc as per
// http://stackoverflow.com/questions/2267036/work-sun-misc-base64encoder-decoder-for-getting-byte
import org.apache.commons.codec.binary.Base64
import javax.crypto.Mac
import javax.crypto.spec.SecretKeySpec

import models.User

object Hex {
  def valueOf(buf: Array[Byte]): String = buf.map("%02x" format _).mkString
}

object Disqus {

  val DISQUS_SECRET_KEY: String = "123456"
  val DISQUS_PUBLIC_KEY: String = "abcdef"

  def calculateRFC2104HMAC(data: String, key: String): String = {
    val HMAC_SHA1_ALGORITHM = "HmacSHA1"
    val signingKey = new SecretKeySpec(key.getBytes(), HMAC_SHA1_ALGORITHM)
    val mac = Mac.getInstance(HMAC_SHA1_ALGORITHM)
    mac.init(signingKey);
    Hex.valueOf(mac.doFinal(data.getBytes))
  }

  def getMessage(user: User): (String, String) = {

    val message = toJson(
      Map("id" -> toJson(user.id),
        "username" -> toJson(user.name),
        "email" -> toJson(user.email)))

    val jsonMessage: String = Json.stringify(message)
    val base64EncodedStr: String = new String(Base64.encodeBase64(jsonMessage.getBytes()))
    val timestamp: Long = System.currentTimeMillis() / 1000

    val signature: String = calculateRFC2104HMAC(base64EncodedStr + ' ' + timestamp, DISQUS_SECRET_KEY)

    (base64EncodedStr + " " + signature + " " + timestamp, DISQUS_PUBLIC_KEY)
  }

}
