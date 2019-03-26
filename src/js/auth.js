function init() {
  gapi.load("auth2", function() {
    gapi.auth2.authorize({
      client_id: "640592571227-igj3s1lcf6v580op163fg4v26vins5ib.apps.googleusercontent.com",
      scope: "email profile openid"
    }, function(response) {
    if (response.error) {
      console.log('ERROR');
      return;
    }
    var accessToken = response.access_token;
    console.log("idToken, %o", response.id_token);
  });
})}

  /*
  I'm following this https://developers.google.com/identity/sign-in/web/backend-auth
  I may want to reduce expiration time https://developers.google.com/identity/sign-in/web/reference#googleauthcurrentuserget
  
  Pending:
    - Call this on the backend: https://oauth2.googleapis.com/tokeninfo?id_token=
    - check that the aud claim contains one of your app's client IDs

  Note:
    Sub is a Google account can have multiple emails at different points in time, but the sub value is never changed. Use sub within your application as the unique-identifier key for the user.
    Email may not be unique and is not suitable for use as a primary key. 
  */