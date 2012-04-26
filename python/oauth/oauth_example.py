# author adam@disqus.com

from flask import Flask
app = Flask(__name__)

from flask import session
from flask import redirect
from flask import url_for
from flask import escape
from flask import request

import functools
import simplejson
import urllib
import urllib2

from disqusapi import InvalidAccessToken
from disqusapi import DisqusAPI

## TODO: make this read from a file
public_key = "PUT YOUR KEY HERE"
secret_key = "PUT YOUR KEY HERE"

app.secret_key = "this is my salt"

disqus = DisqusAPI(secret_key, public_key)


################################################################################
## UTILS


class User(object):
    """User object based on disqus auth token"""
    def __init__(self, username, user_id, access_token, expires_in, token_type, refresh_token):
        super(User, self).__init__()
        self.username = username
        self.user_id = user_id
        self.access_token = access_token
        self.expires_in = expires_in
        self.token_type = token_type
        self.refresh_token = refresh_token

    def __repr__(self):
        return "<{username}:{access_token}>".format(**self.__dict__)


def current_user():
    cu = None
    if 'auth' in session:
        auth = session['auth']
        cu = User(**auth)
    return cu


################################################################################
## APP


@app.route("/")
def hello():
    cu = current_user()
    if cu:
        return 'Logged in as %s' % escape(session['username'])
    else:
        return redirect('/oauth/authorize/')


@app.route("/bye")
def goodbye():
    return "Goodbye World!"


@app.route("/foo/<int:bar>/<baz>")
def foobarbaz(bar, baz):
    if bar == 1:
        return "yay it's a one!"
    elif bar == 2:
        return "two"
    else:
        return baz


################################################################################
## AUTH STUFF (DO NOT CHANGE)

class Logout(Exception):
    pass


def api_call(func, **kwargs):
    try:
        if 'auth' in session:
            result = func(access_token=session['auth']['access_token'], **kwargs)
        else:
            result = func(**kwargs)
    except InvalidAccessToken:
        raise Logout
    return result


@app.errorhandler(Logout)
def logout_handler(error):
    try:
        del session['auth']
    except KeyError:
        pass
    return redirect(url_for('oauth_authorize'))


def login_required(func):
    @functools.wraps(func)
    def wrapped(*args, **kwargs):
        if 'auth' not in session:
            return redirect(url_for('oauth_authorize'))
        return func(*args, **kwargs)
    return wrapped


@app.route('/oauth/authorize/')
def oauth_authorize():
    return redirect('https://disqus.com/api/oauth/2.0/authorize/?%s' % (urllib.urlencode({
        'client_id': disqus.public_key,
        'scope': 'read,write',
        'response_type': 'code',
        'redirect_uri': url_for('oauth_callback', _external=True),
    }),))


@app.route('/oauth/callback/')
def oauth_callback():
    code = request.args.get('code')
    error = request.args.get('error')
    if error or not code:
        # TODO: show error
        return redirect('/')

    req = urllib2.Request('https://disqus.com/api/oauth/2.0/access_token/', urllib.urlencode({
        'grant_type': 'authorization_code',
        'client_id': disqus.public_key,
        'client_secret': disqus.secret_key,
        'redirect_uri': url_for('oauth_callback', _external=True),
        'code': code,
    }))

    resp = urllib2.urlopen(req).read()

    data = simplejson.loads(resp)

    session['auth'] = data
    session['username'] = data['username']
    session.permanent = True

    return redirect('/')

################################################################################
## RUN IT

if __name__ == "__main__":
    app.config["DEBUG"] = True
    app.run()
