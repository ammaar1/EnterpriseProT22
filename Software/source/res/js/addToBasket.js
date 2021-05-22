// function to get the product selected and the supermarket that has it and save it as an order in the cookie 'basket'
function addToBasket(product,supermarket) {
  basket = getCookie("basket");
  console.log(basket);
  // get the quantity and price of the selected product and supermarket
  quantity = document.getElementById(supermarket+"-price").innerHTML;//WRONG NAMING BUT WORKS, DO NOT BREAK 
  price = document.getElementById(supermarket+"-quantity").value;//WRONG NAMING BUT WORKS, DO NOT BREAK 
  if(parseInt(price)===0){//WRONG NAMING BUT WORKS, DO NOT BREAK 
      return;
  }
  // order format = product-supermarket-quantity-price!
  newOrder = product+"-"+supermarket+"-"+quantity+"-"+price+"!";
  document.cookie = "basket="+basket+""+newOrder;
  console.log(getCookie("basket"));
  window.location.replace("/mysupermarketshopt22/basket.php");
}
// function to get specific cookie, since all cookies are saved in one instance 
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
