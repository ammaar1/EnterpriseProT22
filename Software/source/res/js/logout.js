// in order to logout, just erase the cookies hodling the information about the user
// this is done by setting the expiration date of the cookie to a date that has passed
function logout() {
  document.cookie = "logged_in=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
  document.cookie = "logged_in_username=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
  window.location.replace("/mysupermarketshopt22/index.php");
}



