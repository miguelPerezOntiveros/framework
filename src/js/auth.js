function onSignIn(googleUser){
  console.log("idToken, %o", googleUser.getAuthResponse().id_token);
  var auth2 = gapi.auth2.getAuthInstance();
  auth2.disconnect();
  
  $.post('login.php', {id_token: googleUser.getAuthResponse().id_token}, function(response){
    console.log('response from login.php:', response);
    if(!response.error){
      // session has been set on the backend
      window.location = 'index.php';
    }
  })
}
function init() {
  gapi.load("auth2", function() {
    auth2 = gapi.auth2.init({
      client_id: "640592571227-igj3s1lcf6v580op163fg4v26vins5ib.apps.googleusercontent.com",
      scope: 'profile email'
    });
    gapi.signin2.render('my-signin2', {
      'onsuccess': onSignIn,
      'prompt': 'select_account'
    });
  });
}

  /*
  following these
  - https://developers.google.com/identity/sign-in/web/backend-auth
  - https://developers.google.com/identity/sign-in/web/reference#gapiauth2clientconfig

  Note:
    a Google account can have multiple emails at different points in time, but the sub value is never changed. Use sub within your application as the unique-identifier key for the user.
    Email may not be unique and is not suitable for use as a primary key. 
  */