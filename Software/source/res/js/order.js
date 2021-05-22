function order() {
    var user = getCookie("logged_in_username");
    if(user===""){
        document.getElementById("loginalert").style.display = "inline";
    }else{
        document.getElementById("order").style.display = "inline";
        document.getElementById("mybasket").style.display = "none";
        document.getElementById("tableOrders").style.display = "none";
        document.getElementById("emptyBasket").style.display = "none";
        document.getElementById("orderBtn").style.display = "none";
    }
}

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

