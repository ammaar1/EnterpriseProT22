// to empty the basket just find the cookie with the basket with all the orders
// and set it to an empty string
function emptyBasket(cname) {
  cookies = document.cookie;
  basket = getCookie("basket");
  cookies = cookies.replace(basket,'');
  document.cookie = cookies;
  console.log(document.cookie);
  location.reload();
}
// function to get specific cookie, since all cookies are saved in one instance string
function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}
