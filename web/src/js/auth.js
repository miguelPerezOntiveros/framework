function onSignIn(googleUser){
  console.log("idToken, %o", googleUser.getAuthResponse().id_token);
  var auth2 = gapi.auth2.getAuthInstance();
  auth2.disconnect();
  
  $.post('login.php?sidebar=' + ($('.sidebarWrapper_sidebar').hasClass('active')?'':'1'), {id_token: googleUser.getAuthResponse().id_token}, function(response){
    console.log('response from login.php:', response);
    response = JSON.parse(response);
    if(response.error)
      $('.alert-danger').attr('hidden', false)
    else
      window.location = response.success;
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