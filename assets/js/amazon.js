document.getElementById('LoginWithAmazon').onclick = function() {
  alert(1);
    options = { scope : 'profile' };
    amazon.Login.authorize(options, 'https://testsite.dev/amazon');
    return false;
  };