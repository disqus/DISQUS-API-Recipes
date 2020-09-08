//
//  ViewController.swift
//  test
//
//  Created by Derrick Lin on 9/1/20.
//  Copyright © 2020 Derrick Lin. All rights reserved.
//

import UIKit
import WebKit
import Alamofire
import SwiftyJSON


class ViewController: UIViewController, WKNavigationDelegate, WKUIDelegate {

    @IBOutlet var mywebview: WKWebView!
    
    func renderSite(payload: String) {
        var components = URLComponents()
        components.scheme = "http"
        components.host = "localhost"
        components.port = 5000
//        components.scheme = "https"
//        components.host = "sleepy-shelf-33354.herokuapp.com"
//
        components.queryItems = [
            URLQueryItem(name: "title", value: "Hovsep"),
            URLQueryItem(name: "identifier", value: "the_hovsep_identifier"),
            URLQueryItem(name: "payload", value: payload)
        ]
        let url = components.url!
        mywebview.load(URLRequest(url: url))
    }
    
    func login() {
        let parameters = [
            "email": "dmatt+9898@disqus.com",
            "password": "pwd"
        ]
//        let parameters = [
//            "username": "uniqueperson122327",
//            "password": "pwd"
//        ]
        
//        AF.request("https:/sleepy-shelf-33354.herokuapp.com/login", parameters: parameters).responseJSON { response in
        AF.request("http://localhost:5000/login", parameters: parameters).responseJSON { response in
            switch response.result {
            case .success(let value):
                let response_dict = (value as! [String: String])
                let auth_payload = response_dict["auth"]!;
                self.renderSite(payload: auth_payload)
            case .failure(let error):
                print(error)
            }
        }
    }
    
    override func viewDidLoad() {
        super.viewDidLoad()
        mywebview.navigationDelegate = self
        mywebview.uiDelegate = self
        login()
    }


    func webView(_ webView: WKWebView,
                   createWebViewWith configuration: WKWebViewConfiguration,
                   for navigationAction: WKNavigationAction,
                   windowFeatures: WKWindowFeatures) -> WKWebView? {
        if navigationAction.targetFrame == nil, let url = navigationAction.request.url {
          if url.description.lowercased().range(of: "http://") != nil ||
            url.description.lowercased().range(of: "https://") != nil ||
            url.description.lowercased().range(of: "mailto:") != nil {
            webView.load(navigationAction.request)
          }
        }
      return nil
    }
    

    func webView(
        _ webView: WKWebView,
        decidePolicyFor navigationAction: WKNavigationAction,
        decisionHandler: @escaping (WKNavigationActionPolicy) -> Void) {

        guard let url = navigationAction.request.url else {
            decisionHandler(.allow)
            return
        }

        if (url.absoluteString.contains("/login-success") ||
            (url.absoluteString.contains("/logout"))) {
            decisionHandler(.cancel)
            var components = URLComponents()
            components.scheme = "http"
            components.host = "localhost"
            components.port = 5000
//            components.scheme = "https"
//            components.host = "sleepy-shelf-33354.herokuapp.com"
            components.queryItems = [
                URLQueryItem(name: "title", value: "Hovsep"),
                URLQueryItem(name: "identifier", value: "the_hovsep_identifier")
            ]
            mywebview.load(URLRequest(url: components.url!))
        }
        else {
            decisionHandler(.allow)
        }
    }
}

